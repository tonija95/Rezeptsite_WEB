<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db_gets.php';
require_once __DIR__ . '/recipe_save.php';

/**
 * Variablen, die das Page-File verwendet:
 * - $userId, $isAdmin
 * - $recipeId
 * - $units
 * - $groupedTags, $allowedTagIds
 * - $form, $errors
 * - $pageTitle
 */

// -------------------------
// Auth / Rollen
// -------------------------
$userId = 0;

if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $userId = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id'])) {
    $userId = (int)$_SESSION['user']['id'];
}

$isAdmin = isset($_SESSION['role']) && (string)$_SESSION['role'] === 'admin';

if ($userId <= 0) {
    header('Location: index.php');
    exit;
}

// -------------------------
// GET Param
// -------------------------
$isEdit   = isset($_GET['id']) && ctype_digit((string)$_GET['id']);
$recipeId = $isEdit ? (int)$_GET['id'] : null;

// -------------------------
// Statische Options
// -------------------------
$units = ['g', 'kg', 'ml', 'l', 'EL', 'TL', 'Prise', 'Stk', 'Pkg'];

// -------------------------
// Tags laden und gruppieren
// -------------------------
$tags          = getAllTags();
$groupedTags   = [];
$allowedTagIds = [];

foreach ($tags as $tag) {
    $cat = (string)($tag['category'] ?? '');
    $tid = (int)($tag['id'] ?? 0);

    if ($cat === '' || $tid <= 0) {
        continue;
    }

    $groupedTags[$cat][] = [
        'id'   => $tid,
        'name' => (string)($tag['name'] ?? ''),
    ];

    $allowedTagIds[$tid] = true;
}

// -------------------------
// Form State
// -------------------------
$errors = [];
$form = [
    'title'        => '',
    'description'  => '',
    'time_min'     => '',
    'servings'     => '',
    'steps'        => '',
    'picture_path' => '/img/placeholder_food.jpg',
    'tag_ids'      => [],
    'ingredients'  => [],
];

// -------------------------
// Bestehendes Rezept laden (Edit)
// -------------------------
if ($recipeId !== null) {
    $rows   = getRecipesWithTags([$recipeId]);
    $recipe = $rows[$recipeId] ?? null;

    if (!$recipe) {
        http_response_code(404);
        $errors[] = 'Kein Rezept gefunden.';
        $recipeId = null;
    } else {
        $ownerId = (int)($recipe['user_id'] ?? 0);

        if (!$isAdmin && $userId > 0 && $ownerId !== $userId) {
            http_response_code(403);
            $errors[] = 'Du kannst nur eigene Rezepte bearbeiten.';
        } else {
            $form['title']        = (string)($recipe['title'] ?? '');
            $form['description']  = (string)($recipe['description'] ?? '');
            $form['time_min']     = (string)($recipe['time_min'] ?? '');
            $form['servings']     = (string)($recipe['servings'] ?? '');
            $form['steps']        = (string)($recipe['steps'] ?? '');
            $form['picture_path'] = (string)($recipe['picture_path'] ?? '/img/placeholder_food.jpg');

            $form['ingredients'] = getIngredientsByRecipeId((int)($recipe['id'] ?? $recipeId));

            $tagIds = [];
            if (!empty($recipe['tags']) && is_array($recipe['tags'])) {
                foreach ($recipe['tags'] as $t) {
                    if (isset($t['id'])) {
                        $tagIds[] = (int)$t['id'];
                    }
                }
            }
            $form['tag_ids'] = array_values(array_unique($tagIds));
        }
    }
}

// -------------------------
// POST Handling
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $removedIndex = isset($_POST['remove_ing']) ? (int)$_POST['remove_ing'] : -1;
    $added        = isset($_POST['add_ing']);

    // Basisfelder
    $form['title']       = trim((string)($_POST['title'] ?? ''));
    $form['description'] = trim((string)($_POST['description'] ?? ''));
    $form['time_min']    = trim((string)($_POST['time_min'] ?? ''));
    $form['servings']    = trim((string)($_POST['servings'] ?? ''));
    $form['steps']       = trim((string)($_POST['steps'] ?? ''));

    // Bildpfad
    $form['picture_path'] = trim((string)($_POST['picture_path'] ?? '/img/placeholder_food.jpg'));
    if ($form['picture_path'] === '') {
        $form['picture_path'] = '/img/placeholder_food.jpg';
    }

    // Tags aus Dropdowns
    $tagIds = [];
    foreach ($groupedTags as $category => $_tags) {
        $raw = $_POST[$category] ?? [];
        $raw = is_array($raw) ? $raw : [$raw];

        foreach ($raw as $val) {
            if (!is_numeric($val)) {
                continue;
            }

            $tid = (int)$val;
            if (isset($allowedTagIds[$tid])) {
                $tagIds[] = $tid;
            }
        }
    }
    $form['tag_ids'] = array_values(array_unique($tagIds));

    // Zutaten arrays einsammeln
    $iq = $_POST['ingredients']['quantity'] ?? [];
    $iu = $_POST['ingredients']['unit'] ?? [];
    $in = $_POST['ingredients']['name'] ?? [];

    $ingredients = [];
    $len = max(count((array)$iq), count((array)$iu), count((array)$in));

    for ($i = 0; $i < $len; $i++) {
        $qty  = trim((string)($iq[$i] ?? ''));
        $unit = trim((string)($iu[$i] ?? ''));
        $name = trim((string)($in[$i] ?? ''));

        if ($qty === '' && $unit === '' && $name === '') {
            continue;
        }

        $ingredients[] = [
            'quantity' => $qty,
            'unit'     => $unit,
            'name'     => $name,
        ];
    }

    // Nur UI-Action: Ingredient entfernen
    if ($removedIndex >= 0) {
        if (isset($ingredients[$removedIndex])) {
            array_splice($ingredients, $removedIndex, 1);
        }
        $form['ingredients'] = $ingredients;
    }
    // Nur UI-Action: Ingredient hinzufügen
    elseif ($added) {
        $ingredients[] = ['quantity' => '', 'unit' => '', 'name' => ''];
        $form['ingredients'] = $ingredients;
    }
    // Tatsächlich speichern
    else {
        $form['ingredients'] = $ingredients;

        // Validierung
        if ($form['title'] === '') {
            $errors[] = 'Titel ist erforderlich.';
        }

        if ($form['time_min'] !== '' && (!ctype_digit($form['time_min']) || (int)$form['time_min'] < 0)) {
            $errors[] = 'Dauer muss eine Zahl >= 0 sein.';
        }

        if ($form['servings'] !== '' && (!ctype_digit($form['servings']) || (int)$form['servings'] < 0)) {
            $errors[] = 'Portionen muss eine Zahl >= 0 sein.';
        }

        // Speichern
        if (!$errors) {
            $recipeData = [
                'title'        => $form['title'],
                'description'  => $form['description'],
                'time_min'     => ($form['time_min'] === '') ? null : (int)$form['time_min'],
                'servings'     => ($form['servings'] === '') ? null : (int)$form['servings'],
                'steps'        => $form['steps'],
                'picture_path' => $form['picture_path'],
            ];

            $savedId = saveRecipe($recipeId, $recipeData, $form['tag_ids'], $form['ingredients'], $userId);

            if ($savedId <= 0) {
                http_response_code(403);
                $errors[] = 'Speichern fehlgeschlagen (keine Berechtigung oder Rezept nicht gefunden).';
            } else {
                header('Location: recipe.php?id=' . $savedId);
                exit;
            }
        }
    }
}

$pageTitle = ($recipeId !== null) ? 'Rezept bearbeiten' : 'Neues Rezept erstellen';

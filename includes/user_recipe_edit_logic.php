<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db_gets.php';
require_once __DIR__ . '/db_inserts.php';
require_once __DIR__ . '/recipe_save.php';
require_once __DIR__ . '/upload_helpers.php';

$userId = isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])
    ? (int)$_SESSION['user_id']
    : 0;

if ($userId <= 0) {
    header('Location: index.php');
    exit;
}

$isEdit   = isset($_GET['id']) && ctype_digit((string)$_GET['id']);
$recipeId = $isEdit ? (int)$_GET['id'] : null;

$units = ['g', 'kg', 'ml', 'l', 'EL', 'TL', 'Prise', 'Stk', 'Pkg'];

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

if ($recipeId !== null) {
    $rows   = getRecipesWithTags([$recipeId]);
    $recipe = $rows[$recipeId] ?? null;

    if (!$recipe) {
        http_response_code(404);
        $errors[] = 'Kein Rezept gefunden.';
        $recipeId = null;
    } else {
        $ownerId = (int)($recipe['user_id'] ?? 0);

        if ($ownerId !== $userId) {
            http_response_code(403);
            $errors[] = 'Du kannst nur eigene Rezepte bearbeiten.';
        } else {
            $form['title']        = (string)($recipe['title'] ?? '');
            $form['description']  = (string)($recipe['description'] ?? '');
            $form['time_min']     = (string)($recipe['time_min'] ?? '');
            $form['servings']     = (string)($recipe['servings'] ?? '');
            $form['steps']        = (string)($recipe['steps'] ?? '');
            $form['picture_path'] = (string)($recipe['picture_path'] ?? '/img/placeholder_food.jpg');

            $form['ingredients'] = getIngredientsByRecipeId($recipeId);

            $tagIds = [];
            foreach (($recipe['tags'] ?? []) as $t) {
                if (isset($t['id'])) {
                    $tagIds[] = (int)$t['id'];
                }
            }
            $form['tag_ids'] = array_values(array_unique($tagIds));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $removedIndex = isset($_POST['remove_ing']) ? (int)$_POST['remove_ing'] : -1;
    $added        = isset($_POST['add_ing']);

    $form['title']       = trim((string)($_POST['title'] ?? ''));
    $form['description'] = trim((string)($_POST['description'] ?? ''));
    $form['time_min']    = trim((string)($_POST['time_min'] ?? ''));
    $form['servings']    = trim((string)($_POST['servings'] ?? ''));
    $form['steps']       = trim((string)($_POST['steps'] ?? ''));

    $pp = trim((string)($_POST['current_picture_path'] ?? '/img/placeholder_food.jpg'));
    $form['picture_path'] = ($pp === '') ? '/img/placeholder_food.jpg' : $pp;

    $tagIds = [];
    foreach ($groupedTags as $cat => $_tags) {
        $raw = $_POST[$cat] ?? [];
        foreach ((array)$raw as $val) {
            if (is_numeric($val)) {
                $tid = (int)$val;
                if (isset($allowedTagIds[$tid])) {
                    $tagIds[] = $tid;
                }
            }
        }
    }
    $form['tag_ids'] = array_values(array_unique($tagIds));

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

    if ($removedIndex >= 0) {
        if (isset($ingredients[$removedIndex])) {
            array_splice($ingredients, $removedIndex, 1);
        }
        $form['ingredients'] = $ingredients;
    } elseif ($added) {
        $ingredients[] = ['quantity' => '', 'unit' => '', 'name' => ''];
        $form['ingredients'] = $ingredients;
    } else {
        $form['ingredients'] = $ingredients;

        if ($form['title'] === '') {
            $errors[] = 'Titel ist erforderlich.';
        }

        if ($form['time_min'] !== '' && (!ctype_digit($form['time_min']) || (int)$form['time_min'] < 0)) {
            $errors[] = 'Dauer muss eine Zahl ≥ 0 sein.';
        }

        if ($form['servings'] !== '' && (!ctype_digit($form['servings']) || (int)$form['servings'] < 0)) {
            $errors[] = 'Portionen muss eine Zahl ≥ 0 sein.';
        }

        if (!$errors) {
            $recipeData = [
                'title'        => $form['title'],
                'description'  => $form['description'],
                'time_min'     => ($form['time_min'] === '') ? null : (int)$form['time_min'],
                'servings'     => ($form['servings'] === '') ? null : (int)$form['servings'],
                'steps'        => $form['steps'],
                'picture_path' => $form['picture_path'],
            ];

            $savedId = saveRecipe(
                $recipeId,
                $recipeData,
                $form['tag_ids'],
                $form['ingredients'],
                $userId,
                false
            );

            if ($savedId <= 0) {
                http_response_code(403);
                $errors[] = 'Speichern fehlgeschlagen.';
            } else {
                if (isset($_FILES['image']) && (int)$_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $publicDirAbs = realpath(__DIR__ . '/../public');

                    $upload = uploadRecipeImage($savedId, $_FILES['image'], (string)$publicDirAbs);

                    if (!empty($upload['error'])) {
                        $errors[] = (string)$upload['error'];
                    } else {
                        $form['picture_path'] = (string)$upload['path'];
                        updateRecipe($savedId, $form, $userId, false);
                    }
                }

                if (!$errors) {
                    header('Location: recipe.php?id=' . $savedId);
                    exit;
                }
            }
        }
    }
}

$pageTitle = ($recipeId !== null) ? 'Rezept bearbeiten' : 'Neues Rezept erstellen';

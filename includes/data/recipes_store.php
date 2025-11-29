<?php

// Session sicher starten
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../pre datatable/recipe_examples.php';

/**
 * Gibt alle Rezepte zurück (aus Session, oder Fallback: Examples)
 */
function recipesAll(): array {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    // Wenn Session-Rezepte vorhanden, diese verwenden
    if (isset($_SESSION['recipes']) && is_array($_SESSION['recipes'])) {
        return $_SESSION['recipes'];
    }
    
    // Sonst: Examples laden und in Session speichern
    $examples = getExampleRecipes();
    $_SESSION['recipes'] = $examples;
    return $examples;
}

/**
 * Speichert die komplette Rezeptliste in der Session
 */
function recipesSave(array $recipes): void {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $_SESSION['recipes'] = array_values($recipes);
}

/**
 * Findet Rezept nach ID
 */
function recipeById(int $id): ?array {
    foreach (recipesAll() as $r) {
        if ((int)($r['id'] ?? 0) === $id) {
            return $r;
        }
    }
    return null;
}

/**
 * Fügt neues Rezept hinzu
 */
function recipesAdd(array $data, string $user): int {
    $list = recipesAll();
    $newId = 1;
    foreach ($list as $r) {
        $rid = (int)($r['id'] ?? 0);
        if ($rid >= $newId) $newId = $rid + 1;
    }
    
    // Zutaten normalisieren
    $ingredients = [];
    if (is_array($data['ingredients'] ?? null)) {
        foreach ($data['ingredients'] as $ing) {
            $qty  = trim((string)($ing['quantity'] ?? ''));
            $unit = trim((string)($ing['unit'] ?? ''));
            $name = trim((string)($ing['name'] ?? ''));
            if ($name !== '') {
                $ingredients[] = ['quantity' => $qty, 'unit' => $unit, 'name' => $name];
            }
        }
    }
    
    $newRecipe = [
        'id'           => $newId,
        'title'        => (string)($data['title'] ?? ''),
        'description'  => (string)($data['description'] ?? ''),
        'time_minutes' => (int)($data['time_minutes'] ?? 0),
        'servings'     => (int)($data['servings'] ?? 0),
        'image_url'    => (string)($data['image_url'] ?? ''),
        'tags'         => is_array($data['tags'] ?? null) ? $data['tags'] : [],
        'ingredients'  => $ingredients,
        'steps'        => (string)($data['steps'] ?? ''),
        'user'         => $user,
    ];
    
    $list[] = $newRecipe;
    recipesSave($list);
    return $newId;
}

/**
 * Aktualisiert ein bestehendes Rezept
 */
function recipesUpdate(int $id, array $data): bool {
    $list = recipesAll();
    $found = false;
    
    // Zutaten normalisieren
    $ingredients = [];
    if (is_array($data['ingredients'] ?? null)) {
        foreach ($data['ingredients'] as $ing) {
            $qty  = trim((string)($ing['quantity'] ?? ''));
            $unit = trim((string)($ing['unit'] ?? ''));
            $name = trim((string)($ing['name'] ?? ''));
            if ($name !== '') {
                $ingredients[] = ['quantity' => $qty, 'unit' => $unit, 'name' => $name];
            }
        }
    }
    
    foreach ($list as &$r) {
        if ((int)($r['id'] ?? 0) === $id) {
            $r['title']        = (string)($data['title'] ?? '');
            $r['description']  = (string)($data['description'] ?? '');
            $r['time_minutes'] = (int)($data['time_minutes'] ?? 0);
            $r['servings']     = (int)($data['servings'] ?? 0);
            $r['image_url']    = (string)($data['image_url'] ?? '');
            $r['tags']         = is_array($data['tags'] ?? null) ? $data['tags'] : [];
            $r['ingredients']  = $ingredients;
            $r['steps']        = (string)($data['steps'] ?? '');
            $found = true;
            break;
        }
    }
    
    if ($found) {
        recipesSave($list);
    }
    return $found;
}
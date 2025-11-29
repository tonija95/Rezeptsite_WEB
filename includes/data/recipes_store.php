<?php

// Session sicher starten
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../pre datatable/recipe_examples.php';

// einmalig mit Beispieldaten füllen
function recipesSeed(): void {
    if (!isset($_SESSION['recipes']) || !is_array($_SESSION['recipes'])) {
        $_SESSION['recipes'] = getExampleRecipes();
    }
}

function recipesAll(): array {
    recipesSeed();
    return $_SESSION['recipes'];
}

function recipeById(int $id): ?array {
    foreach (recipesAll() as $r) {
        if ((int)($r['id'] ?? 0) === $id) return $r;
    }
    return null;
}

function recipesSave(array $list): void {
    $_SESSION['recipes'] = array_values($list);
}

function recipesAdd(array $data, string $user): int {
    $list = recipesAll();
    $maxId = 0;
    foreach ($list as $r) { $maxId = max($maxId, (int)($r['id'] ?? 0)); }
    $newId = $maxId + 1;

    // Zutaten normalisieren
    $ingredients = [];
    if (is_array($data['ingredients'] ?? null)) {
        foreach ($data['ingredients'] as $ing) {
            $qty = trim((string)($ing['quantity'] ?? ''));
            $unit = trim((string)($ing['unit'] ?? ''));
            $name = trim((string)($ing['name'] ?? ''));
            if ($name !== '') {
                $ingredients[] = ['quantity' => $qty, 'unit' => $unit, 'name' => $name];
            }
        }
    }

    $list[] = [
        'id'           => $newId,
        'user'         => $user,
        'title'        => (string)($data['title'] ?? ''),
        'description'  => (string)($data['description'] ?? ''),
        'time_minutes' => (int)($data['time_minutes'] ?? 0),
        'servings'     => (int)($data['servings'] ?? 0),
        'image_url'    => (string)($data['image_url'] ?? ''),
        'tags'         => is_array($data['tags'] ?? null) ? $data['tags'] : [],
        // NEU:
        'ingredients'  => $ingredients,
        'steps'        => (string)($data['steps'] ?? ''),
    ];
    recipesSave($list);
    return $newId;
}

function recipesDelete(int $id): bool {
    $list = recipesAll();
    $before = count($list);
    $list = array_values(array_filter($list, fn($r) => (int)($r['id'] ?? 0) !== $id));
    recipesSave($list);
    return count($list) < $before;
}
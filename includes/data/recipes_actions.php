<?php

require_once __DIR__ . '/recipes_store.php';

function recipeIsOwner(array $r, string $user): bool {
    return (($r['user'] ?? '') === $user);
}

function recipeCanDelete(array $r, string $currentUser, bool $isAdmin = false): bool {
    return $isAdmin || recipeIsOwner($r, $currentUser);
}

function recipeCanEdit(array $r, string $currentUser): bool {
    return recipeIsOwner($r, $currentUser);
}

/**
 * Löscht ein Rezept nach ID. Erlaubt: Besitzer oder Admin.
 */
function recipeDeleteById(int $id, string $currentUser, bool $isAdmin = false): bool {
    $list = recipesAll();
    $ok = false;
    $new = [];
    foreach ($list as $r) {
        $rid = (int)($r['id'] ?? 0);
        if ($rid === $id && recipeCanDelete($r, $currentUser, $isAdmin)) {
            $ok = true; // auslassen = löschen
            continue;
        }
        $new[] = $r;
    }
    if ($ok) recipesSave($new);
    return $ok;
}

// Optional: nur zurückgeben, wenn Besitzer oder Admin (für Edit/Details mit Schutz)
function recipeByIdOwned(int $id, string $currentUser, bool $isAdmin = false): ?array {
    $r = recipeById($id);
    if (!$r) return null;
    if ($isAdmin || ($r['user'] ?? '') === $currentUser) return $r;
    return null;
}
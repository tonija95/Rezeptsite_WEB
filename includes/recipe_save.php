<?php

require_once __DIR__ . '/db_inserts.php';

function saveRecipe(
    ?int $recipeId,
    array $recipeData,
    array $tagIds,
    array $ingredients,
    int $userId
): int {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $isAdmin = isset($_SESSION['role']) && (string)$_SESSION['role'] === 'admin';

    // CREATE
    if ($recipeId === null) {
        $newId = createRecipe($recipeData, $userId);

        if ($newId <= 0) {
            return 0;
        }

        replaceRecipeTags($newId, $tagIds);
        replaceRecipeIngredients($newId, $ingredients);

        return $newId;
    }

    // UPDATE (mit Ownership/Admin-Check in updateRecipe)
    $ok = updateRecipe($recipeId, $recipeData, $userId, $isAdmin);

    if (!$ok) {
        // Wichtig: wenn Update nicht erlaubt/fehlgeschlagen → KEINE Tags/Zutaten überschreiben
        return 0;
    }

    replaceRecipeTags($recipeId, $tagIds);
    replaceRecipeIngredients($recipeId, $ingredients);

    return $recipeId;
}

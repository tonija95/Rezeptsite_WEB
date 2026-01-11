<?php
declare(strict_types=1);

require_once __DIR__ . '/db_inserts.php';

function saveRecipe(
    ?int $recipeId,
    array $recipeData,
    array $tagIds,
    array $ingredients,
    int $userId,
    bool $isAdmin
): int {
    if ($recipeId === null) {
        $newId = createRecipe($recipeData, $userId);
        if ($newId <= 0) {
            return 0;
        }

        replaceRecipeTags($newId, $tagIds);
        replaceRecipeIngredients($newId, $ingredients);

        return $newId;
    }

    $ok = updateRecipe($recipeId, $recipeData, $userId, $isAdmin);
    if (!$ok) {
        return 0;
    }

    replaceRecipeTags($recipeId, $tagIds);
    replaceRecipeIngredients($recipeId, $ingredients);

    return $recipeId;
}

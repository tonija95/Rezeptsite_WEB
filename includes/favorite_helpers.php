<?php

require_once __DIR__ . '/db_gets.php';
require_once __DIR__ . '/db_inserts.php';

function toggleFavorite(int $userId, int $recipeId): bool
{
    if (isRecipeFavorited($userId, $recipeId)) {
        removeFavorite($userId, $recipeId);
        return false;
    }

    addFavorite($userId, $recipeId);
    return true;
}

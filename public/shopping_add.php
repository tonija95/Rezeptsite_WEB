<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/db_inserts.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: recipes.php');
    exit;
}

$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($userId <= 0) {
    header('Location: index.php');
    exit;
}

$recipeId = isset($_POST['recipe_id']) && ctype_digit((string)$_POST['recipe_id'])
    ? (int)$_POST['recipe_id']
    : 0;

$returnUrl = trim((string)($_POST['return'] ?? ''));
if ($returnUrl === '' || str_contains($returnUrl, 'shopping_add.php')) {
    $returnUrl = 'recipe.php?id=' . $recipeId;
}

if ($recipeId <= 0) {
    header('Location: ' . $returnUrl);
    exit;
}

$ingredients = getIngredientsByRecipeId($recipeId);


addIngredientsToShoppingList($userId, $ingredients);

header('Location: ' . $returnUrl);
exit;

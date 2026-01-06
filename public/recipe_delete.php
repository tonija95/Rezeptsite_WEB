<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../includes/db_inserts.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'recipes.php'));
    exit;
}

$id = isset($_POST['id']) && ctype_digit((string)$_POST['id']) ? (int)$_POST['id'] : 0;

$returnUrl = trim((string)($_POST['return'] ?? $_SERVER['HTTP_REFERER'] ?? 'recipes.php'));
if ($returnUrl === '' || str_contains($returnUrl, 'recipe_delete.php')) {
    $returnUrl = 'recipes.php';
}

$isLoggedIn = isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;
if (!$isLoggedIn) {
    header('Location: ' . $returnUrl);
    exit;
}

$userId  = (int)$_SESSION['user_id'];
$isAdmin = (isset($_SESSION['role']) && (string)$_SESSION['role'] === 'admin');

if ($id <= 0) {
    header('Location: ' . $returnUrl);
    exit;
}

deleteRecipe($id, $userId, $isAdmin);

header('Location: ' . $returnUrl);
exit;

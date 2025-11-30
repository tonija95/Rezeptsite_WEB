<?php



if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$currentUser = (string)$_SESSION['user'];
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

require_once __DIR__ . '/../includes/data/recipes_actions.php';

$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$fallback = $isAdmin ? 'admin_recipes.php' : 'user_my_recipes.php';
$back = $ref !== '' ? $ref : $fallback;

if ($id > 0 && recipeDeleteById($id, $currentUser, $isAdmin)) {
  header('Location: ' . $back);
} else {
  $sep = (strpos($back, '?') !== false) ? '&' : '?';
  header('Location: ' . $back . $sep . 'error=delete');
}
exit;
<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')
    || (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true);

if (!$isAdmin) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_users.php');
    exit;
}

require_once __DIR__ . '/../includes/db_inserts.php'; 

$userIdToDelete = isset($_POST['id']) && ctype_digit((string)$_POST['id']) ? (int)$_POST['id'] : 0;
$returnUrl = trim((string)($_POST['return'] ?? 'admin_users.php'));
if ($returnUrl === '' || str_contains($returnUrl, 'admin_user_delete.php')) {
    $returnUrl = 'admin_users.php';
}

$currentAdminId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if ($userIdToDelete <= 0 || $currentAdminId <= 0) {
    header('Location: ' . $returnUrl);
    exit;
}

$ok = deleteUserById($userIdToDelete, $currentAdminId);


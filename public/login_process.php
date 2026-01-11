<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/db_inserts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));

    $password = $_POST['password'] ?? '';

    $user = getUserByUsername($username);

    if ($user && is_string($user['password']) && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user'] = (string)$user['name'];
        $_SESSION['role'] = (string)($user['role'] ?? 'user');

        $redirectUrl = ($_SESSION['role'] === 'admin') ? 'admin.php' : 'user_my_recipes.php';
        header('Location: ' . $redirectUrl);
        exit;
    }

    $_SESSION['login_error'] = 'Ungültige Anmeldedaten!';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

header('Location: index.php');
exit;

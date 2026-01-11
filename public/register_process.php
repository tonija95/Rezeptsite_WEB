<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/db_inserts.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}

$username = trim((string)($_POST['username'] ?? ''));
$email    = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $email === '' || $password === '') {
    $_SESSION['login_error'] = 'Bitte alle Felder ausfüllen.';
    header('Location: registration.php');
    exit;
}

if (strlen($username) < 3) {
    $_SESSION['login_error'] = 'Benutzername zu kurz.';
    header('Location: registration.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Ungültige E-Mail-Adresse.';
    header('Location: registration.php');
    exit;
}

$existing = getUserByUsername($username);
if ($existing) {
    $_SESSION['login_error'] = 'Benutzername existiert bereits.';
    header('Location: registration.php');
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$newUserId = createUser($username, $email, $hashedPassword);

if ($newUserId <= 0) {
    $_SESSION['login_error'] = 'Registrierung fehlgeschlagen.';
    header('Location: registration.php');
    exit;
}

session_regenerate_id(true);

$_SESSION['user_id'] = (int)$newUserId;
$_SESSION['user']    = $username;
$_SESSION['role']    = 'user';

header('Location: user_my_recipes.php');
exit;

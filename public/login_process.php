<?php
// ALT:
// session_start();

// NEU:
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ALT: keine DB
// ----------------
// $users = [
//     'admin' => 'admin123',
//     'anna' => 'anna123'
// ];

// NEU: DB-Funktionen laden
require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/db_inserts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ALT:
    // $username = $_POST['username'] ?? '';

    // NEU:
    $username = trim((string)($_POST['username'] ?? ''));

    $password = $_POST['password'] ?? '';

    // ALT: Prüfen gegen Array
    // if (isset($users[$username]) && $users[$username] === $password) {

    // NEU: User aus DB laden
    $user = getUserByUsername($username);

    // 1) Falls Passwort bereits gehasht in DB dann mit password_verify prüfen
    if ($user && is_string($user['password']) && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user'] = (string)$user['name'];
        $_SESSION['role']    = (string)($user['role'] ?? 'user');

        if ($_SESSION['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: user_my_recipes.php');
        }

        exit;
    }

    // 2) PW das noch im Klartext in DB: Direktvergleich + Hashen
    if ($user && (string)$user['password'] === $password) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        if ($newHash) {
            updateUserPassword((int)$user['id'], $newHash);
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user'] = (string)$user['name'];
        $_SESSION['role']    = (string)($user['role'] ?? 'user');

        if ($_SESSION['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: user_my_recipes.php');
        }

        exit;
    }

    // Fehlersituation
    $error = 'Ungültige Anmeldedaten!';
    $_SESSION['login_error'] = $error;
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
} else {
    header('Location: index.php');
    exit;
}

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

    if ($user && (string)$user['password'] === $password) {

        // ALT: Admin-Login speziell
        // if ($username === 'admin') {
        //     $_SESSION['admin_logged_in'] = true;
        //     $_SESSION['role'] = 'admin';
        //     header('Location: admin.php');
        // } else {
        //     $_SESSION['user'] = $username;
        //     header('Location: user_dashboard.php');
        // }

        // NEU: Einheitliche Session-Werte
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
    } else {
        $error = 'Ungültige Anmeldedaten!';
        $_SESSION['login_error'] = $error;
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

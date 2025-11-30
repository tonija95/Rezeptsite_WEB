<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Benutzerliste
    $users = [
        'admin' => 'admin123',
        'anna' => 'anna123'
    ];

    // Prüft Anmeldedaten
    if (isset($users[$username]) && $users[$username] === $password) {
        if ($username === 'admin') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['role'] = 'admin';
            header('Location: admin.php');
        } else {
            $_SESSION['user'] = $username;
            header('Location: user_dashboard.php');
        }
        exit;
    } else {
        // Fehler bei falschen Daten
        $error = 'Ungültige Anmeldedaten!';
        $_SESSION['login_error'] = $error;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    // Weiterleitung bei direktem Aufruf
    header('Location: index.php');
    exit;
}
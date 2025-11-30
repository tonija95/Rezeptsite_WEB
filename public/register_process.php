<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prüft Eingaben
    if (empty($username) || empty($email) || empty($password)) {
        die('Bitte füllen Sie alle Felder aus.');
    }

    // Speichert Benutzernamen in der Session
    $_SESSION['user'] = $username;

    // Weiterleitung zum Dashboard
    header('Location: user_dashboard.php');
    exit;
} else {
    // Weiterleitung bei direktem Aufruf
    header('Location: registration.php');
    exit;
}
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/db_inserts.php';

/* Nur POST erlauben */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}

/* Eingaben lesen */
$username = trim((string)($_POST['username'] ?? ''));
$email    = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');

/* Validierung */
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

/* Prüfen ob Username bereits existiert (case-sensitiv!) */
$existing = getUserByUsername($username);
if ($existing) {
    $_SESSION['login_error'] = 'Benutzername existiert bereits.';
    header('Location: registration.php');
    exit;
}

/*
 * HINWEIS:
 * Passwort wird aktuell im Klartext gespeichert.
 * Hashing kann später hier ergänzt werden.
 */

/* User anlegen */
$newUserId = createUser($username, $email, $password);

if ($newUserId <= 0) {
    $_SESSION['login_error'] = 'Registrierung fehlgeschlagen.';
    header('Location: registration.php');
    exit;
}

/* Direkt einloggen */
session_regenerate_id(true);

$_SESSION['user_id'] = (int)$newUserId;
$_SESSION['user']    = $username;
$_SESSION['role']    = 'user';

/* Weiterleitung */
header('Location: user_my_recipes.php');
exit;

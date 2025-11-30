<?php
// filepath: c:\xampp\htdocs\rezeptsite\public\recipe_delete.php
declare(strict_types=1);

// Session sicherstellen
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../includes/data/recipes_actions.php';

// Nur POST zulassen
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Wenn direkte GET-Anfrage: zurück zur Referer/Übersicht
    $ref = $_SERVER['HTTP_REFERER'] ?? 'recipes.php';
    header('Location: ' . $ref);
    exit;
}

// Sicherheits- / Eingabechecks
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$returnUrl = trim((string)($_POST['return'] ?? $_SERVER['HTTP_REFERER'] ?? 'recipes.php'));
if ($returnUrl === '' || str_contains($returnUrl, 'recipe_delete.php')) {
    $returnUrl = 'recipes.php';
}

// Rolle / Admin frühzeitig feststellen (Admins dürfen auch ohne 'user' löschen)
$isAdmin = (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin')
    || (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true);

// Wenn weder angemeldeter User noch Admin → abbrechen
if (empty($_SESSION['user']) && !$isAdmin) {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Nicht eingeloggt.'];
    header('Location: ' . $returnUrl);
    exit;
}

$currentUser = (string)($_SESSION['user'] ?? '');

// Valid id
if ($id <= 0) {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Ungültige Rezept-ID.'];
    header('Location: ' . $returnUrl);
    exit;
}

// Delete-Funktion aufrufen (verschiedene mögliche Bezeichner)
$deleted = false;
if (function_exists('recipeDeleteById')) {
    $deleted = recipeDeleteById($id, $currentUser, $isAdmin);
} elseif (function_exists('recipesDelete')) {
    $deleted = recipesDelete($id, $currentUser, $isAdmin);
} elseif (function_exists('recipesDeleteById')) {
    $deleted = recipesDeleteById($id);
} else {
    // keine Lösch-Funktion gefunden
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Lösch-Funktion nicht vorhanden.'];
    header('Location: ' . $returnUrl);
    exit;
}

// Ergebnis-Feedback und Redirect
if ($deleted) {
    $_SESSION['flash'] = ['type' => 'success', 'msg' => "Rezept #{$id} wurde gelöscht."];
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Löschen fehlgeschlagen oder keine Berechtigung.'];
}

header('Location: ' . $returnUrl);
exit;
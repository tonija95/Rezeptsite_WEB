<?php
session_start();

// Entfernt automatische Anmeldung für 'anna'
if (!isset($_SESSION['user']) && !isset($_SESSION['admin_logged_in'])) {
    $_SESSION['user'] = null; // Kein Standardbenutzer
}

function esc($s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

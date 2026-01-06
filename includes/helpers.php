<?php

if (session_status() === PHP_SESSION_NONE) { session_start(); }

define('BASE_URL', '/rezeptsite/public');

function esc($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

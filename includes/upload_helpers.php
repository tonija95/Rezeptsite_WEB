<?php
declare(strict_types=1);

/**
 * File: /includes/upload_helpers.php
 *
 * Erwartete Struktur:
 * - Upload-Zielordner (physisch): /public/uploads/recipes/
 * - DB-Pfad (relativ zur public): /uploads/recipes/recipe_<id>.<ext>
 */

function ensureDir(string $dirAbs): bool
{
    if (is_dir($dirAbs)) {
        return is_writable($dirAbs);
    }

    return mkdir($dirAbs, 0775, true) && is_writable($dirAbs);
}

function detectImageExtension(string $tmpPath): ?string
{
    if (!is_file($tmpPath)) {
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($tmpPath);

    // Nur erlaubte Bildtypen
    $map = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    return $map[$mime] ?? null;
}

function isUploadOk(array $file): bool
{
    return isset($file['error'], $file['tmp_name'])
        && (int)$file['error'] === UPLOAD_ERR_OK
        && is_uploaded_file((string)$file['tmp_name']);
}

/**
 * Speichert ein Rezeptbild unter: recipe_<id>.<ext>
 *
 * @param int    $recipeId   Rezept-ID (muss > 0 sein)
 * @param array  $file       $_FILES['...']
 * @param string $publicDirAbs Absoluter Pfad zum /public Ordner (z.B. __DIR__.'/../public')
 * @param string $subDir     Unterordner innerhalb public (Standard: '/uploads/recipes')
 *
 * @return array{path:?string,error:?string}
 *         path: DB-Pfad z.B. '/uploads/recipes/recipe_42.jpg'
 */
function uploadRecipeImage(
    int $recipeId,
    array $file,
    string $publicDirAbs,
    string $subDir = '/uploads/recipes'
): array {
    if ($recipeId <= 0) {
        return ['path' => null, 'error' => 'Ungültige Rezept-ID für Upload.'];
    }

    // Kein Upload ausgewählt → kein Fehler, einfach "nichts ändern"
    if (!isset($file['error']) || (int)$file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    if (!isUploadOk($file)) {
        $code = (int)($file['error'] ?? -1);
        return ['path' => null, 'error' => 'Upload fehlgeschlagen (Code: ' . $code . ').'];
    }

    // Optional: Größenlimit (z.B. 5 MB)
    $maxBytes = 5 * 1024 * 1024;
    $size = (int)($file['size'] ?? 0);
    if ($size <= 0 || $size > $maxBytes) {
        return ['path' => null, 'error' => 'Bild ist zu groß (max. 5 MB).'];
    }

    $tmp = (string)$file['tmp_name'];
    $ext = detectImageExtension($tmp);
    if ($ext === null) {
        return ['path' => null, 'error' => 'Ungültiger Bildtyp. Erlaubt: JPG, PNG, WEBP, GIF.'];
    }

    // Zielordner absolut: <publicDirAbs> + <subDir>
    $subDir = '/' . ltrim($subDir, '/');
    $targetDirAbs = rtrim($publicDirAbs, DIRECTORY_SEPARATOR) . str_replace('/', DIRECTORY_SEPARATOR, $subDir);

    if (!ensureDir($targetDirAbs)) {
        return ['path' => null, 'error' => 'Upload-Ordner ist nicht beschreibbar.'];
    }

    $filename = 'recipe_' . $recipeId . '.' . $ext;
    $targetAbs = $targetDirAbs . DIRECTORY_SEPARATOR . $filename;

    // Überschreiben ist gewollt (neues Bild ersetzt altes)
    if (!move_uploaded_file($tmp, $targetAbs)) {
        return ['path' => null, 'error' => 'Bild konnte nicht gespeichert werden.'];
    }

    // DB-Pfad (relativ zu /public)
    $dbPath = $subDir . '/' . $filename;

    return ['path' => $dbPath, 'error' => null];
}

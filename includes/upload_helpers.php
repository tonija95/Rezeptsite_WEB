<?php
declare(strict_types=1);

function ensureDir(string $dirAbs): bool
{
    if (is_dir($dirAbs)) {
        return is_writable($dirAbs);
    }

    return mkdir($dirAbs, 0775, true) && is_writable($dirAbs);
}

function deleteOldRecipeImages(int $recipeId, string $targetDirAbs): void
{
    if ($recipeId <= 0 || !is_dir($targetDirAbs)) {
        return;
    }

    $extensions = ['jpg', 'png', 'webp', 'gif'];
    foreach ($extensions as $ext) {
        $oldFile = $targetDirAbs . DIRECTORY_SEPARATOR . 'recipe_' . $recipeId . '.' . $ext;
        if (is_file($oldFile)) {
            @unlink($oldFile);
        }
    }
}

function detectImageExtension(string $tmpPath): ?string
{
    if (!is_file($tmpPath)) {
        return null;
    }

    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($tmpPath);

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

function uploadRecipeImage(
    int $recipeId,
    array $file,
    string $publicDirAbs,
    string $subDir = '/uploads/recipes'
): array {
    if ($recipeId <= 0) {
        return ['path' => null, 'error' => 'Ungültige Rezept-ID für Upload.'];
    }

    $absPath = realpath($publicDirAbs);
    if ($absPath === false || !is_dir($absPath)) {
        return ['path' => null, 'error' => 'Public-Verzeichnis nicht gefunden.'];
    }

    if (!isset($file['error']) || (int)$file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    if (!isUploadOk($file)) {
        $code = (int)($file['error'] ?? -1);
        return ['path' => null, 'error' => 'Upload fehlgeschlagen (Code: ' . $code . ').'];
    }

    $maxBytes = 10 * 1024 * 1024;
    $size = (int)($file['size'] ?? 0);
    if ($size <= 0 || $size > $maxBytes) {
        return ['path' => null, 'error' => 'Bild ist zu groß (max. 10 MB).'];
    }

    $tmp = (string)$file['tmp_name'];
    $ext = detectImageExtension($tmp);
    if ($ext === null) {
        return ['path' => null, 'error' => 'Ungültiger Bildtyp.'];
    }

    $subDir = '/' . ltrim($subDir, '/');
    $targetDirAbs = rtrim($absPath, DIRECTORY_SEPARATOR)
        . str_replace('/', DIRECTORY_SEPARATOR, $subDir);

    if (!ensureDir($targetDirAbs)) {
        return ['path' => null, 'error' => 'Upload-Ordner ist nicht beschreibbar.'];
    }

    deleteOldRecipeImages($recipeId, $targetDirAbs);

    $filename  = 'recipe_' . $recipeId . '.' . $ext;
    $targetAbs = $targetDirAbs . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($tmp, $targetAbs)) {
        return ['path' => null, 'error' => 'Bild konnte nicht gespeichert werden.'];
    }

    $returnPath = $subDir . '/' . $filename . '?v=' . time();

    return [
        'path'  => $returnPath,
        'error' => null
    ];
}

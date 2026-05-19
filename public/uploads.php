<?php
/**
 * Secure file delivery for filesystem uploads stored outside /public.
 */

require_once __DIR__ . '/../config/bootstrap.php';

$relativePath = ltrim(str_replace('\\', '/', trim($_GET['path'] ?? '')), '/');
if ($relativePath === '' || !str_starts_with($relativePath, 'uploads/')) {
    http_response_code(404);
    exit('Not found');
}

$absolutePath = APP_ROOT . '/' . $relativePath;
$realAbsolutePath = realpath($absolutePath);
$realUploadsRoot = realpath(UPLOADS_DIR);

if ($realAbsolutePath === false
    || $realUploadsRoot === false
    || !str_starts_with($realAbsolutePath, $realUploadsRoot . DIRECTORY_SEPARATOR)
    || !is_file($realAbsolutePath)
) {
    http_response_code(404);
    exit('Not found');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($realAbsolutePath) ?: 'application/octet-stream';

header('Content-Type: ' . $mimeType);
header('Content-Length: ' . (string)filesize($realAbsolutePath));
header('Cache-Control: public, max-age=86400');
header('X-Content-Type-Options: nosniff');
readfile($realAbsolutePath);
exit;

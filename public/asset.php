<?php
// Fallback asset server (not needed if Apache aliases work correctly)
$file = realpath(__DIR__ . '/../assets/' . ltrim($_GET['f'] ?? '', '/'));
$root = realpath(__DIR__ . '/../assets');
if (!$file || !str_starts_with($file, $root)) { http_response_code(404); exit; }
$mime = mime_content_type($file) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Cache-Control: public, max-age=86400');
readfile($file);

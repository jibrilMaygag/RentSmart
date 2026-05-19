<?php
/**
 * RentSmart - Application Configuration
 */

// Load .env (check root first, then database/)
foreach ([__DIR__ . '/../.env', __DIR__ . '/../database/.env'] as $envPath) {
    if (file_exists($envPath)) {
        $env = parse_ini_file($envPath);
        if ($env !== false) {
            foreach ($env as $key => $value) {
                if (!isset($_ENV[$key])) { $_ENV[$key] = $value; }
            }
        }
        break;
    }
}

define('APP_NAME',  $_ENV['APP_NAME']  ?? 'RentSmart');
define('APP_ENV',   $_ENV['APP_ENV']   ?? 'development');
define('APP_DEBUG', in_array($_ENV['APP_DEBUG'] ?? 'true', ['true','1',true], true));
// APP_URL = base URL to the /public directory (no trailing slash)
define('APP_URL', rtrim($_ENV['APP_URL'] ?? 'http://localhost/RentSmart/public', '/'));

define('DB_HOST',    $_ENV['DB_HOST']    ?? 'localhost');
define('DB_PORT',    (int)($_ENV['DB_PORT'] ?? 3306));
define('DB_NAME',    $_ENV['DB_NAME']    ?? 'rentsmart');
define('DB_USER',    $_ENV['DB_USER']    ?? 'root');
define('DB_PASS',    $_ENV['DB_PASS']    ?? '');
define('DB_CHARSET', 'utf8mb4');

define('SESSION_LIFETIME',  (int)($_ENV['SESSION_LIFETIME']  ?? 7200));
define('REMEMBER_LIFETIME', (int)($_ENV['REMEMBER_LIFETIME'] ?? 2592000));

// Uploads are stored in /public/assets/uploads (web-accessible)
define('UPLOAD_DIR', dirname(__DIR__) . '/public/assets/uploads');
define('UPLOAD_URL', APP_URL . '/assets/uploads');
define('MAX_FILE_SIZE',      (int)($_ENV['MAX_FILE_SIZE'] ?? 5242880));
define('ALLOWED_MIME_TYPES', ['image/jpeg','image/png','image/webp']);
define('ALLOWED_EXTENSIONS', ['jpg','jpeg','png','webp']);

define('ITEMS_PER_PAGE', 9);
define('CURRENCY', $_ENV['CURRENCY'] ?? 'ETB');
define('DEFAULT_PROPERTY_IMG', 'pexels-photo-106399.jpeg');

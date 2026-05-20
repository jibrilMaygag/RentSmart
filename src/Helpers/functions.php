<?php
/**
 * Global Helper Functions
 */

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price): string
{
    return number_format($price, 2, '.', ',') . ' ' . CURRENCY;
}

function defaultPropertyImageUrl(): string
{
    if (preg_match('#^https?://#i', DEFAULT_PROPERTY_IMG) === 1) {
        return DEFAULT_PROPERTY_IMG;
    }

    return APP_URL . '/assets/media/img/' . DEFAULT_PROPERTY_IMG;
}

function imageUrl(string $path): string
{
    if (empty($path)) {
        return defaultPropertyImageUrl();
    }

    $normalizedPath = ltrim(str_replace('\\', '/', trim($path)), '/');

    if ($normalizedPath === '') {
        return defaultPropertyImageUrl();
    }

    if (preg_match('#^https?://#i', $normalizedPath) === 1) {
        return $normalizedPath;
    }

    if (str_starts_with($normalizedPath, PROPERTY_UPLOADS_SUBDIR . '/')) {
        return UPLOADS_HANDLER_URL . '?path=' . rawurlencode($normalizedPath);
    }

    if (str_starts_with($normalizedPath, 'assets/')) {
        return APP_BASE_URL . '/' . encodeUrlPath($normalizedPath);
    }

    if ((str_starts_with($normalizedPath, 'prop_') || str_starts_with($normalizedPath, 'upload_'))
        && str_contains($normalizedPath, '/') === false
    ) {
        return LEGACY_UPLOAD_URL . '/' . rawurlencode($normalizedPath);
    }

    return APP_URL . '/assets/media/img/' . rawurlencode($normalizedPath);
}

function encodeUrlPath(string $path): string
{
    $segments = array_filter(explode('/', trim(str_replace('\\', '/', $path), '/')), 'strlen');
    return implode('/', array_map('rawurlencode', $segments));
}

function redirect(string $path, int $statusCode = 302): never
{
    $url = str_starts_with($path, 'http') ? $path : APP_URL . '/' . ltrim($path, '/');
    header('Location: ' . $url, true, $statusCode);
    exit;
}

function jsonResponse(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function flash(string $key, mixed $value = null): mixed
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function isActive(string $path): bool
{
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return str_contains($currentPath, $path);
}

function csrfField(): string
{
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

function route(string $path, array $params = []): string
{
    $url = APP_URL . '/' . ltrim($path, '/');
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    return $url;
}

function asset(string $path): string
{
    return APP_URL . '/assets/' . ltrim($path, '/');
}

function currentPath(): string
{
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $basePath = parse_url(APP_URL, PHP_URL_PATH) ?: '';
    $relativePath = '/' . ltrim(substr($requestPath, strlen($basePath)), '/');

    if ($relativePath !== '/' && str_ends_with($relativePath, '/')) {
        $relativePath = rtrim($relativePath, '/');
    }

    return $relativePath === '' ? '/' : $relativePath;
}

function routeIs(string ...$paths): bool
{
    $path = currentPath();

    foreach ($paths as $candidate) {
        if ($candidate === $path) {
            return true;
        }

        if ($candidate !== '/' && str_starts_with($path, $candidate . '/')) {
            return true;
        }
    }

    return false;
}

function userFirstName(array|null $user): string
{
    if (!$user || empty($user['full_name'])) {
        return 'Guest';
    }

    $parts = preg_split('/\s+/', trim($user['full_name']));
    return $parts[0] ?? 'Guest';
}

function formatBytes(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow   = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}

function formatDate(string $date, string $format = 'M d, Y'): string
{
    return date($format, strtotime($date));
}

function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return 'just now';
    if ($diff < 3600)   return floor($diff / 60) . 'm ago';
    if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M d, Y', strtotime($datetime));
}

function getMonths(): array
{
    return [
        1=>'January',2=>'February',3=>'March',4=>'April',
        5=>'May',6=>'June',7=>'July',8=>'August',
        9=>'September',10=>'October',11=>'November',12=>'December'
    ];
}

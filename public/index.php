<?php
/**
 * RentSmart - Front Controller
 * All requests go through here (XAMPP: http://localhost/RentSmart/public/)
 */

require_once __DIR__ . '/../config/bootstrap.php';

// ── Simple Router ──────────────────────────────────────────────────────────────
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base   = parse_url(APP_URL, PHP_URL_PATH); // e.g. /RentSmart/public
$path   = '/' . ltrim(substr($uri, strlen($base)), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Strip trailing slash (except root)
if ($path !== '/' && str_ends_with($path, '/')) {
    $path = rtrim($path, '/');
}

// CSRF check on every mutating request
$csrf = new CSRFMiddleware();
$csrf->handle();

// ── Route Dispatch ─────────────────────────────────────────────────────────────
$home = new HomeController();
$auth = new AuthController();

// Match /property/{id}
if (preg_match('#^/property/(\d+)$#', $path, $m)) {
    $home->showProperty((int)$m[1]);
    exit;
}

// Match /api/toggle-favorite/{id}
if (preg_match('#^/api/toggle-favorite/(\d+)$#', $path, $m) && $method === 'POST') {
    $home->toggleFavorite((int)$m[1]);
    exit;
}

switch ($path) {
    case '/':
    case '':
        $home->index();
        break;

    case '/search':
        $home->search();
        break;

    case '/login':
        ($method === 'POST') ? $auth->login() : $auth->showLogin();
        break;

    case '/signup':
        ($method === 'POST') ? $auth->signup() : $auth->showSignup();
        break;

    case '/logout':
        $auth->logout();
        break;

    case '/dashboard':
        $home->dashboard();
        break;

    case '/contact':
        ($method === 'POST') ? $home->submitContact() : $home->showContact();
        break;

    default:
        http_response_code(404);
        include __DIR__ . '/../resources/views/404.php';
        break;
}

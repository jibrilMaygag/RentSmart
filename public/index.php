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
$landlord = new LandlordController();
$messages = new MessageController();

// Match /property/{id}/contact
if (preg_match('#^/property/(\d+)/contact$#', $path, $m)) {
    ($method === 'POST')
        ? $messages->sendInquiry((int)$m[1])
        : $messages->showContactLandlord((int)$m[1]);
    exit;
}

// Match /dashboard/listings/{id}/edit
if (preg_match('#^/dashboard/listings/(\d+)/edit$#', $path, $m)) {
    ($method === 'POST')
        ? $landlord->updateListing((int)$m[1])
        : $landlord->showEditListing((int)$m[1]);
    exit;
}

// Match /dashboard/listings/{id}/delete
if (preg_match('#^/dashboard/listings/(\d+)/delete$#', $path, $m) && $method === 'POST') {
    $landlord->deleteListing((int)$m[1]);
    exit;
}

// Match /dashboard/listings/{id}/status
if (preg_match('#^/dashboard/listings/(\d+)/status$#', $path, $m) && $method === 'POST') {
    $landlord->updateListingStatus((int)$m[1]);
    exit;
}

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

    case '/dashboard/listings':
        $landlord->listings();
        break;

    case '/dashboard/listings/create':
        ($method === 'POST') ? $landlord->createListing() : $landlord->showCreateListing();
        break;

    case '/dashboard/messages':
        $landlord->messages();
        break;

    case '/favorites':
        $home->favoritesPage();
        break;

    case '/contact':
        ($method === 'POST') ? $home->submitContact() : $home->showContact();
        break;

    default:
        http_response_code(404);
        include __DIR__ . '/../resources/views/404.php';
        break;
}

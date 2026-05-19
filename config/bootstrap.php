<?php
/**
 * Bootstrap - Application initialization
 */

// Define the app root (parent of /config)
define('APP_ROOT', dirname(__DIR__));

// Load configuration (defines all constants + loads .env)
require_once APP_ROOT . '/config/config.php';

// Error reporting
if (!defined('APP_DEBUG') || APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

date_default_timezone_set('Africa/Addis_Ababa');

// Load database
require_once APP_ROOT . '/config/database.php';

// Load models
require_once APP_ROOT . '/src/Models/BaseModel.php';
require_once APP_ROOT . '/src/Models/User.php';
require_once APP_ROOT . '/src/Models/Property.php';
require_once APP_ROOT . '/src/Models/Message.php';

// Load services
require_once APP_ROOT . '/src/Services/AuthService.php';
require_once APP_ROOT . '/src/Services/Validator.php';
require_once APP_ROOT . '/src/Services/UploadService.php';

// Load middleware
require_once APP_ROOT . '/src/Middleware/AuthMiddleware.php';
require_once APP_ROOT . '/src/Middleware/CSRFMiddleware.php';

// Load helpers
require_once APP_ROOT . '/src/Helpers/functions.php';

// Load controllers
require_once APP_ROOT . '/src/Controllers/BaseController.php';
require_once APP_ROOT . '/src/Controllers/HomeController.php';
require_once APP_ROOT . '/src/Controllers/AuthController.php';
require_once APP_ROOT . '/src/Controllers/LandlordController.php';
require_once APP_ROOT . '/src/Controllers/MessageController.php';

// Start session
AuthService::startSession();

// Generate CSRF token for this session if missing
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

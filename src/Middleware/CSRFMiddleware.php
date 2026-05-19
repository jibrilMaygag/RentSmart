<?php
/**
 * CSRF Middleware - Verify CSRF tokens
 */

class CSRFMiddleware
{
    protected AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    /**
     * Verify CSRF token from POST/PUT/DELETE requests
     */
    public function handle(): bool
    {
        // Only verify on non-safe HTTP methods
        if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD', 'OPTIONS'])) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

            if (!$this->auth->verifyCSRFToken($token)) {
                http_response_code(419);
                die('CSRF token mismatch');
            }
        }
        return true;
    }
}

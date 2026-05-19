<?php
/**
 * Auth Middleware
 */

class AuthMiddleware
{
    protected AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function handle(): bool
    {
        return $this->auth->check();
    }

    public function requireAuth(string $redirectTo = 'login'): void
    {
        if (!$this->auth->check()) {
            flash('error', 'You must be logged in to access that page.');
            redirect($redirectTo);
        }
    }

    public function requireGuest(string $redirectTo = 'dashboard'): void
    {
        if ($this->auth->check()) {
            redirect($redirectTo);
        }
    }

    public function requireRole(string $role): void
    {
        $this->requireAuth();
        if (!$this->auth->isRole($role)) {
            http_response_code(403);
            die('Unauthorized: insufficient permissions.');
        }
    }
}

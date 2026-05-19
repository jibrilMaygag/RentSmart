<?php
/**
 * Base Controller
 */

abstract class BaseController
{
    protected AuthService $auth;
    protected AuthMiddleware $authMiddleware;
    protected CSRFMiddleware $csrfMiddleware;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->authMiddleware = new AuthMiddleware();
        $this->csrfMiddleware = new CSRFMiddleware();
    }
    /**
     * Render a view
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("View not found: {$view}");
        }

        require $viewPath;
    }

    /**
     * Render JSON
     */
    protected function json(array $data, int $status = 200): never
    {
        jsonResponse($data, $status);
    }

    /**
     * Redirect
     */
    protected function redirect(string $path, int $status = 302): never
    {
        redirect($path, $status);
    }

    /**
     * Flash message
     */
    protected function flash(string $key, mixed $value): void
    {
        flash($key, $value);
    }

    /**
     * Get current user
     */
    protected function user(): array|null
    {
        return $this->auth->user();
    }

    /**
     * Require authentication
     */
    protected function requireAuth(): void
    {
        if (!$this->auth->check()) {
            $this->flash('error', 'You must be logged in');
            $this->redirect('login');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        if (!$this->auth->isRole($role)) {
            http_response_code(403);
            die('Unauthorized');
        }
    }

    /**
     * Require guest (not authenticated)
     */
    protected function requireGuest(): void
    {
        if ($this->auth->check()) {
            $this->redirect('dashboard');
        }
    }
}

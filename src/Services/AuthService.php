<?php
/**
 * Auth Service - Handles authentication and session management
 */

class AuthService
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Start secure session
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'secure' => ($_ENV['APP_ENV'] ?? 'production') === 'production',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    /**
     * Login user
     */
    public function login(string $email, string $password, bool $remember = false): bool
    {
        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + REMEMBER_LIFETIME, '/', '', false, true);
            $this->userModel->update($user['id'], ['remember_token' => hash('sha256', $token)]);
        }

        return true;
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
    }

    /**
     * Get current user
     */
    public function user(): array|null
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return $this->userModel->find($_SESSION['user_id']);
    }

    /**
     * Check if user is authenticated
     */
    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if user is a specific role
     */
    public function isRole(string $role): bool
    {
        return ($_SESSION['user_role'] ?? null) === $role;
    }

    /**
     * Generate CSRF token
     */
    public function generateCSRFToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public function verifyCSRFToken(string $token): bool
    {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}

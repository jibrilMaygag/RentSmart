<?php
/**
 * Auth Controller
 */

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        $this->requireGuest();
        $old = $_SESSION['login_old_input'] ?? [];
        unset($_SESSION['login_old_input']);

        $this->view('login', ['old' => $old]);
    }

    public function login(): void
    {
        $this->requireGuest();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        $_SESSION['login_old_input'] = [
            'email'    => trim($_POST['email'] ?? ''),
            'remember' => isset($_POST['remember']),
        ];

        $validator = new Validator($_POST);
        $validator->required('email',    'Email')
                  ->email('email',       'Email')
                  ->required('password', 'Password');

        if (!$validator->passes()) {
            $this->flash('error', 'Please fill in all required fields correctly.');
            $this->redirect('login');
        }

        $authService = new AuthService();
        if ($authService->login(
            $validator->get('email'),
            $validator->get('password'),
            isset($_POST['remember'])
        )) {
            unset($_SESSION['login_old_input']);
            $this->flash('success', 'Welcome back!');
            $this->redirect('dashboard');
        } else {
            $this->flash('error', 'Invalid email or password. Please try again.');
            $this->redirect('login');
        }
    }

    public function showSignup(): void
    {
        $this->requireGuest();
        $this->view('signup');
    }

    public function signup(): void
    {
        $this->requireGuest();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('signup');
        }

        // Preserve old input for re-population on error
        $_SESSION['old_input'] = [
            'full_name' => $_POST['full_name'] ?? '',
            'email'     => $_POST['email'] ?? '',
            'role'      => $_POST['role'] ?? 'renter',
        ];

        $validator = new Validator($_POST);
        $validator->required('full_name',        'Full Name')
                  ->required('email',            'Email')
                  ->email('email',               'Email')
                  ->unique('email',              'users')
                  ->required('password',         'Password')
                  ->min('password',    8,        'Password')
                  ->required('password_confirm', 'Confirm Password')
                  ->match('password', 'password_confirm', 'Password');

        if (!$validator->passes()) {
            $this->flash('errors', $validator->errors());
            $this->redirect('signup');
        }

        $userModel = new User();
        try {
            $userModel->create([
                'full_name' => $validator->get('full_name'),
                'email'     => $validator->get('email'),
                'password'  => $validator->get('password'),
                'role'      => in_array($validator->get('role'), ['renter','landlord'])
                               ? $validator->get('role') : 'renter',
            ]);

            unset($_SESSION['old_input']);
            $this->flash('success', 'Account created successfully! Please log in.');
            $this->redirect('login');
        } catch (Exception $e) {
            error_log('Signup error: ' . $e->getMessage());
            $this->flash('error', 'An error occurred during signup. Please try again.');
            $this->redirect('signup');
        }
    }

    public function logout(): void
    {
        $this->requireAuth();
        $authService = new AuthService();
        $authService->logout();
        $this->flash('success', 'You have been logged out successfully.');
        $this->redirect('');
    }
}

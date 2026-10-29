<?php
declare(strict_types=1);

namespace App\Controllers\Authentication;

use App\Core\Controller;
use App\Models\User;
use App\Security\Audit;
use App\Security\InputSanitizer;
use App\Security\Session;

class LoginController extends Controller
{
    private function roleDirectory(string $roleName): string
    {
        $map = [
            'Super Administrator' => 'super-admin',
            'National Administrator' => 'national-admin',
            'District Administrator' => 'district-admin',
            'Registration Officer' => 'registration-officer',
            'Supervisor' => 'supervisor',
            'Card Printing Officer' => 'card-printing-officer',
            'Auditor' => 'auditor',
            'Read Only User' => 'read-only-user',
        ];

        $roleName = trim((string) $roleName);

        return $map[$roleName] ?? strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $roleName));
    }

    public function landing(): void
    {
        if (isset($_SESSION['user'])) {
            $role = $_SESSION['user']['role'] ?? 'Registration Officer';
            $this->redirect('/' . $this->roleDirectory($role) . '/dashboard');
        }

        $this->view('auth/landing', [
            'pageTitle' => 'National Registration Bureau',
        ]);
    }

    public function index(): void
    {
        $this->login();
    }

    public function login(): void
    {
        if (isset($_SESSION['user'])) {
            $role = $_SESSION['user']['role'] ?? 'Registration Officer';
            $this->redirect('/' . $this->roleDirectory($role) . '/dashboard');
        }

        $this->view('auth/login', [
            'pageTitle' => 'Login',
        ]);
    }

    public function authenticate(): void
    {
        $identifier = trim((string) ($_POST['email'] ?? $_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($identifier === '' || $password === '') {
            $_SESSION['error'] = 'Username/email and password are required.';
            $this->redirect('/login');
        }

        $sanitizedIdentifier = InputSanitizer::email($identifier);
        if ($sanitizedIdentifier !== '' && str_contains($sanitizedIdentifier, '@')) {
            $identifier = $sanitizedIdentifier;
        }

        $userModel = new User();
        $user = $userModel->findByIdentifier($identifier);

        if ($user === null || !$userModel->verifyPassword($password, (string) ($user['password'] ?? ''))) {
            Audit::log('login_failed', 'authentication', null, $identifier, 'Invalid login attempt');
            $_SESSION['error'] = 'Invalid username/email or password.';
            $this->redirect('/login');
        }

        if (($user['status'] ?? 'active') !== 'active') {
            $_SESSION['error'] = 'Your account is not active.';
            $this->redirect('/login');
        }

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'full_name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
            'email' => $user['email'] ?? '',
            'username' => $user['username'] ?? '',
            'role' => $user['role_name'] ?? 'Registration Officer',
        ];

        Session::regenerate();
        Audit::log('login_success', 'authentication', null, 'Logged in', null, (int) $user['id'], $_SESSION['user']['full_name']);

        $redirectMap = [
            'Super Administrator' => '/super-admin/dashboard',
            'National Administrator' => '/national-admin/dashboard',
            'District Administrator' => '/district-admin/dashboard',
            'Registration Officer' => '/registration-officer/dashboard',
            'Supervisor' => '/supervisor/dashboard',
            'Card Printing Officer' => '/card-printing-officer/dashboard',
            'Auditor' => '/auditor/dashboard',
            'Read Only User' => '/read-only-user/dashboard',
        ];

        $roleName = $_SESSION['user']['role'] ?? 'Registration Officer';
        $redirectPath = $redirectMap[$roleName] ?? '/' . $this->roleDirectory($roleName) . '/dashboard';

        $this->redirect($redirectPath);
    }
}

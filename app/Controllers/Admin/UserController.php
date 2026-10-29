<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class UserController extends Controller
{
    private function resolveRoleView(): string
    {
        $role = trim((string) ($_SESSION['user']['role'] ?? 'Super Administrator'));
        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $role));
        $slug = trim((string) $slug, '-');

        $candidate = $slug . '/users';
        $path = dirname(__DIR__, 2) . '/Views/' . $candidate . '.php';

        if ($slug !== '' && file_exists($path)) {
            return $candidate;
        }

        return 'admin/users';
    }

    public function index(): void
    {
        $db = Database::connection();

        try {
            $users = $db->query(
                'SELECT u.*, r.name AS role_name
                 FROM users u
                 LEFT JOIN roles r ON r.id = u.role_id
                 ORDER BY u.created_at DESC
                 LIMIT 20'
            )->fetchAll();
        } catch (\Throwable $e) {
            $users = [];
        }

        $this->view($this->resolveRoleView(), [
            'pageTitle' => 'User Management',
            'users' => $users,
            'user' => $_SESSION['user'] ?? null,
            'role' => ($_SESSION['user']['role'] ?? 'Super Administrator'),
        ]);
    }
}

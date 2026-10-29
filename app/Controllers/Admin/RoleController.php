<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Role;

class RoleController extends Controller
{
    private function resolveRoleView(): string
    {
        $role = trim((string) ($_SESSION['user']['role'] ?? 'Super Administrator'));
        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $role));
        $slug = trim((string) $slug, '-');

        $candidate = $slug . '/roles';
        $path = dirname(__DIR__, 2) . '/Views/' . $candidate . '.php';

        if ($slug !== '' && file_exists($path)) {
            return $candidate;
        }

        return 'admin/roles';
    }

    public function index(): void
    {
        $db = Database::connection();

        try {
            $roles = $db->query(
                'SELECT r.*, COUNT(u.id) AS user_count
                 FROM roles r
                 LEFT JOIN users u ON u.role_id = r.id
                 GROUP BY r.id
                 ORDER BY r.name ASC'
            )->fetchAll();
        } catch (\Throwable $e) {
            $roles = [];
        }

        foreach (($roles ?? []) as $index => $role) {
            $roleName = (string) ($role['name'] ?? '');
            $roles[$index]['permission_count'] = count(Role::effectivePermissions($roleName, Role::defaultPermissionsByRole()));
            $roles[$index]['inherited_roles'] = Role::inheritedRoleNames($roleName);
        }

        $this->view($this->resolveRoleView(), [
            'pageTitle' => 'Role Management',
            'roles' => $roles,
            'user' => $_SESSION['user'] ?? null,
            'role' => ($_SESSION['user']['role'] ?? 'Super Administrator'),
        ]);
    }
}

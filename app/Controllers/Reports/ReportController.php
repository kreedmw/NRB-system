<?php
declare(strict_types=1);

namespace App\Controllers\Reports;

use App\Core\Controller;
use App\Core\Database;

class ReportController extends Controller
{
    private function resolveRoleView(): string
    {
        $role = trim((string) ($_SESSION['user']['role'] ?? 'Super Administrator'));
        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $role));
        $slug = trim((string) $slug, '-');

        $candidate = $slug . '/reports';
        $path = dirname(__DIR__, 2) . '/Views/' . $candidate . '.php';

        if ($slug !== '' && file_exists($path)) {
            return $candidate;
        }

        return 'reports/index';
    }

    public function index(): void
    {
        $db = Database::connection();

        $stats = [
            'citizens' => 0,
            'national_ids' => 0,
            'births' => 0,
            'approvals' => 0,
        ];

        try {
            $stats = [
                'citizens' => (int) $db->query('SELECT COUNT(*) FROM citizens')->fetchColumn(),
                'national_ids' => (int) $db->query('SELECT COUNT(*) FROM national_ids')->fetchColumn(),
                'births' => (int) $db->query('SELECT COUNT(*) FROM births')->fetchColumn(),
                'approvals' => (int) $db->query('SELECT COUNT(*) FROM approvals')->fetchColumn(),
            ];
        } catch (\Throwable $e) {
            $stats = [
                'citizens' => 0,
                'national_ids' => 0,
                'births' => 0,
                'approvals' => 0,
            ];
        }

        $this->view($this->resolveRoleView(), [
            'pageTitle' => 'Reports & Analytics',
            'stats' => $stats,
            'user' => $_SESSION['user'] ?? null,
            'role' => ($_SESSION['user']['role'] ?? 'Super Administrator'),
        ]);
    }
}

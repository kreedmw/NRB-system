<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class DashboardController extends Controller
{
    private function resolveRoleView(): string
    {
        $role = trim((string) ($_SESSION['user']['role'] ?? 'Super Administrator'));
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $role) ?? $role);
        $slug = trim((string) $slug, '-');

        $candidate = $slug . '/dashboard';
        $path = dirname(__DIR__, 2) . '/Views/' . $candidate . '.php';

        if ($slug !== '' && file_exists($path)) {
            return $candidate;
        }

        return 'admin/dashboard';
    }

    private function tableExists(string $tableName): bool
    {
        try {
            $stmt = Database::connection()->prepare(
                'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :table_name'
            );
            $stmt->execute([':table_name' => $tableName]);

            return (int) $stmt->fetchColumn() > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function countTable(string $tableName, ?string $whereClause = null, array $params = []): int
    {
        if (!$this->tableExists($tableName)) {
            return 0;
        }

        $sql = 'SELECT COUNT(*) FROM ' . $tableName;

        if ($whereClause !== null && $whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        }

        try {
            $stmt = Database::connection()->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function recentRegistrations(): array
    {
        if (!$this->tableExists('citizens')) {
            return [];
        }

        try {
            $stmt = Database::connection()->query(
                'SELECT national_registration_number, CONCAT(first_name, " ", COALESCE(middle_name, ""), " ", surname) AS full_name, place_of_birth, current_status, created_at FROM citizens ORDER BY created_at DESC LIMIT 5'
            );
            $rows = $stmt->fetchAll();

            $list = [];
            foreach ($rows as $row) {
                $name = trim((string) ($row['full_name'] ?? 'Unknown Citizen'));
                $list[] = [
                    'nrn' => (string) ($row['national_registration_number'] ?? 'NRN-UNKNOWN'),
                    'name' => $name !== '' ? $name : 'Unknown Citizen',
                    'district' => (string) ($row['place_of_birth'] ?? 'National'),
                    'status' => (string) ($row['current_status'] ?? 'Active'),
                ];
            }

            return $list;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function recentActivity(): array
    {
        if (!$this->tableExists('audit_logs')) {
            return [];
        }

        try {
            $stmt = Database::connection()->query(
                'SELECT action, description, created_at FROM audit_logs ORDER BY created_at DESC LIMIT 5'
            );
            $rows = $stmt->fetchAll();

            $list = [];
            foreach ($rows as $row) {
                $time = date('H:i', strtotime((string) ($row['created_at'] ?? date('Y-m-d H:i:s'))));
                $text = trim((string) ($row['description'] ?? ($row['action'] ?? 'System activity')));
                $list[] = ['time' => $time, 'text' => $text !== '' ? $text : 'System activity recorded'];
            }

            return $list;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function districtSummary(): array
    {
        if (!$this->tableExists('districts') || !$this->tableExists('citizens')) {
            return [];
        }

        try {
            $stmt = Database::connection()->query(
                'SELECT d.name, COUNT(c.id) AS total FROM districts d LEFT JOIN citizens c ON c.district_of_birth = d.id GROUP BY d.id, d.name ORDER BY total DESC, d.name ASC LIMIT 5'
            );
            $rows = $stmt->fetchAll();

            if ($rows === []) {
                return [];
            }

            $max = max(array_map(static fn ($row) => (int) ($row['total'] ?? 0), $rows));
            $max = $max > 0 ? $max : 1;

            $districts = [];
            foreach ($rows as $row) {
                $count = (int) ($row['total'] ?? 0);
                $districts[] = [
                    'name' => (string) ($row['name'] ?? 'Unknown'),
                    'value' => $max > 0 ? (int) round(($count / $max) * 100) : 0,
                    'count' => $count,
                ];
            }

            return $districts;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function chartSeries(): array
    {
        $labels = [];
        $registrations = [];
        $births = [];
        $ids = [];

        for ($i = 7; $i >= 0; --$i) {
            $labels[] = date('M', strtotime('-' . $i . ' month'));
        }

        $sql = 'SELECT DATE_FORMAT(created_at, "%b") AS label, COUNT(*) AS total FROM citizens WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 MONTH) GROUP BY YEAR(created_at), MONTH(created_at)';
        $birthSql = 'SELECT DATE_FORMAT(created_at, "%b") AS label, COUNT(*) AS total FROM births WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 MONTH) GROUP BY YEAR(created_at), MONTH(created_at)';
        $idSql = 'SELECT DATE_FORMAT(created_at, "%b") AS label, COUNT(*) AS total FROM national_ids WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 MONTH) GROUP BY YEAR(created_at), MONTH(created_at)';

        try {
            $results = Database::connection()->query($sql)->fetchAll();
            $map = [];
            foreach ($results as $row) {
                $map[(string) ($row['label'] ?? '')] = (int) ($row['total'] ?? 0);
            }
            foreach ($labels as $label) {
                $registrations[] = $map[$label] ?? 0;
            }
        } catch (\Throwable $e) {
            $registrations = array_fill(0, count($labels), 0);
        }

        try {
            $results = Database::connection()->query($birthSql)->fetchAll();
            $map = [];
            foreach ($results as $row) {
                $map[(string) ($row['label'] ?? '')] = (int) ($row['total'] ?? 0);
            }
            foreach ($labels as $label) {
                $births[] = $map[$label] ?? 0;
            }
        } catch (\Throwable $e) {
            $births = array_fill(0, count($labels), 0);
        }

        try {
            $results = Database::connection()->query($idSql)->fetchAll();
            $map = [];
            foreach ($results as $row) {
                $map[(string) ($row['label'] ?? '')] = (int) ($row['total'] ?? 0);
            }
            foreach ($labels as $label) {
                $ids[] = $map[$label] ?? 0;
            }
        } catch (\Throwable $e) {
            $ids = array_fill(0, count($labels), 0);
        }

        return [
            'labels' => $labels,
            'registrations' => $registrations,
            'births' => $births,
            'ids' => $ids,
        ];
    }

    public function index(): void
    {
        $stats = [
            'citizens' => $this->countTable('citizens'),
            'national_ids' => $this->countTable('national_ids'),
            'births' => $this->countTable('births'),
            'deaths' => 0,
            'users' => $this->countTable('users'),
            'pending_approvals' => $this->countTable('birth_approvals', 'LOWER(status) IN (\'pending\', \'submitted\', \'in review\')'),
            'printed_cards' => $this->countTable('national_ids', 'status = \'issued\' OR status = \'printed\''),
            'today_registrations' => $this->countTable('citizens', 'DATE(created_at) = CURDATE()') + $this->countTable('births', 'DATE(created_at) = CURDATE()'),
            'active_users' => $this->countTable('users', 'status = :status', [':status' => 'active']),
            'users_online' => $this->countTable('user_sessions', 'expires_at > NOW()'),
            'security_events' => $this->countTable('audit_logs', 'created_at >= NOW() - INTERVAL 1 DAY'),
        ];

        $chart = $this->chartSeries();
        $user = $_SESSION['user'] ?? [];
        $role = $user['role'] ?? 'Super Administrator';

        $this->view($this->resolveRoleView(), [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'user' => $user,
            'role' => $role,
            'recentRegistrations' => $this->recentRegistrations(),
            'districts' => $this->districtSummary(),
            'activity' => $this->recentActivity(),
            'chartLabels' => $chart['labels'],
            'chartData' => [
                'registrations' => $chart['registrations'],
                'births' => $chart['births'],
                'ids' => $chart['ids'],
            ],
        ]);
    }
}

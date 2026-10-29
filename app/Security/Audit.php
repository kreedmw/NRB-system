<?php
declare(strict_types=1);

namespace App\Security;

use App\Core\Database;

final class Audit
{
    public static function log(
        string $action,
        string $module,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?string $reason = null,
        ?int $userId = null,
        ?string $userName = null
    ): void {
        try {
            $stmt = Database::connection()->prepare(
                'INSERT INTO audit_logs (user_id, user_name, ip_address, device, browser, operating_system, action, module, old_value, new_value, reason, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
            );

            $stmt->execute([
                $userId,
                $userName,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                self::browser(),
                self::os(),
                $action,
                $module,
                $oldValue,
                $newValue,
                $reason,
            ]);
        } catch (\Throwable) {
            // Intentionally fail-safe: do not break the application if audit insertion fails.
        }
    }

    private static function browser(): string
    {
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (preg_match('/Edg\//i', $agent)) return 'Microsoft Edge';
        if (preg_match('/Chrome\//i', $agent)) return 'Google Chrome';
        if (preg_match('/Firefox\//i', $agent)) return 'Firefox';
        if (preg_match('/Safari\//i', $agent)) return 'Safari';
        return 'Unknown';
    }

    private static function os(): string
    {
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (preg_match('/Windows/i', $agent)) return 'Windows';
        if (preg_match('/Mac OS X/i', $agent)) return 'macOS';
        if (preg_match('/Linux/i', $agent)) return 'Linux';
        return 'Unknown';
    }
}

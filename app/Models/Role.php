<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    protected string $table = 'roles';

    public static function roleHierarchy(): array
    {
        return [
            'Super Administrator' => [
                'National Administrator',
                'District Administrator',
                'Supervisor',
                'Registration Officer',
                'Card Printing Officer',
                'Auditor',
                'Read Only User',
            ],
            'National Administrator' => [
                'District Administrator',
                'Supervisor',
                'Registration Officer',
                'Card Printing Officer',
                'Auditor',
                'Read Only User',
            ],
            'District Administrator' => [
                'Supervisor',
                'Registration Officer',
                'Card Printing Officer',
                'Auditor',
                'Read Only User',
            ],
            'Supervisor' => [
                'Registration Officer',
                'Read Only User',
            ],
            'Registration Officer' => [
                'Read Only User',
            ],
            'Card Printing Officer' => [
                'Read Only User',
            ],
            'Auditor' => [
                'Read Only User',
            ],
            'Read Only User' => [],
        ];
    }

    public static function inheritedRoleNames(string $roleName): array
    {
        $roleName = trim($roleName);
        $hierarchy = self::roleHierarchy();

        if ($roleName === '' || !isset($hierarchy[$roleName])) {
            return [];
        }

        $inherited = [];
        $stack = $hierarchy[$roleName];

        while ($stack !== []) {
            $current = array_pop($stack);

            if ($current === null || $current === '' || isset($inherited[$current])) {
                continue;
            }

            $inherited[$current] = true;

            foreach ($hierarchy[$current] ?? [] as $childRole) {
                if (!isset($inherited[$childRole])) {
                    $stack[] = $childRole;
                }
            }
        }

        return array_keys($inherited);
    }

    public static function effectivePermissions(string $roleName, array $permissionsByRole = []): array
    {
        $roleName = trim($roleName);
        $permissionsByRole = $permissionsByRole === [] ? self::defaultPermissionsByRole() : $permissionsByRole;

        if ($roleName === '') {
            return [];
        }

        $effective = [];
        $roles = self::inheritedRoleNames($roleName);
        $roles[] = $roleName;

        foreach ($roles as $currentRole) {
            foreach ($permissionsByRole[$currentRole] ?? [] as $permission) {
                $effective[$permission] = true;
            }
        }

        return array_keys($effective);
    }

    public static function defaultPermissionsByRole(): array
    {
        return [
            'Super Administrator' => [
                'manage_users',
                'manage_roles',
                'create_and_assign_permissions',
                'manage_system_settings',
                'configure_security_policies',
                'manage_backups',
                'database_maintenance',
                'enable_disable_maintenance_mode',
                'view_audit_logs',
                'force_logout_users',
                'unlock_locked_accounts',
                'manage_api_keys',
                'manage_registration_centres',
                'configure_id_numbering_rules',
                'manage_printers',
                'view_print_queue',
                'approve_print_quality',
                'view_print_logs',
                'manage_biometric_devices',
                'view_reports',
                'view_dashboards',
                'search_citizens',
                'view_records',
                'register_citizen',
                'approve_registration',
                'print_card',
                'view_audit',
                'api_access',
            ],
            'National Administrator' => [
                'view_reports',
                'view_dashboards',
                'search_citizens',
                'view_records',
                'register_citizen',
                'approve_registration',
                'print_card',
                'view_print_queue',
                'approve_print_quality',
                'view_print_logs',
                'view_audit',
                'api_access',
            ],
            'District Administrator' => [
                'view_reports',
                'view_dashboards',
                'search_citizens',
                'view_records',
                'register_citizen',
                'approve_registration',
                'print_card',
                'view_print_queue',
                'approve_print_quality',
                'view_audit',
            ],
            'Supervisor' => [
                'view_reports',
                'view_dashboards',
                'search_citizens',
                'view_records',
                'register_citizen',
                'approve_registration',
                'reject_registration',
                'request_corrections',
                'approve_updates',
                'approve_replacements',
                'review_registration_history',
            ],
            'Registration Officer' => [
                'view_dashboards',
                'search_citizens',
                'view_records',
                'register_citizen',
                'edit_citizen_records_before_approval',
                'register_births',
                'register_deaths',
                'capture_photographs',
                'capture_signatures',
                'capture_fingerprints',
                'upload_supporting_documents',
                'submit_applications_for_approval',
            ],
            'Card Printing Officer' => [
                'view_dashboards',
                'search_citizens',
                'view_records',
                'print_card',
                'view_print_queue',
                'approve_print_quality',
                'manage_printers',
                'view_print_logs',
                'reprint_cards',
                'manage_print_queues',
                'mark_cards_as_collected',
                'record_printing_errors',
            ],
            'Auditor' => [
                'view_dashboards',
                'search_citizens',
                'view_records',
                'view_reports',
                'view_audit_logs',
                'view_login_history',
                'view_activity_reports',
                'view_security_events',
                'export_audit_reports',
            ],
            'Read Only User' => [
                'view_dashboards',
                'search_citizens',
                'view_records',
                'view_reports',
                'print_reports',
            ],
        ];
    }
}

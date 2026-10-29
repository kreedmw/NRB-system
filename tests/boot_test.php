<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/helpers.php';

if (!class_exists('App\\Controllers\\Authentication\\LoginController')) {
    fwrite(STDERR, "Missing LoginController\n");
    exit(1);
}

if (!class_exists('App\\Controllers\\Admin\\DashboardController')) {
    fwrite(STDERR, "Missing DashboardController\n");
    exit(1);
}

if (!class_exists('App\\Controllers\\Citizen\\CheckStatusController')) {
    fwrite(STDERR, "Missing CheckStatusController\n");
    exit(1);
}

if (!class_exists('App\\Models\\User')) {
    fwrite(STDERR, "Missing User model\n");
    exit(1);
}

if (!class_exists('App\\Models\\Role')) {
    fwrite(STDERR, "Missing Role model\n");
    exit(1);
}

$inherited = \App\Models\Role::inheritedRoleNames('Super Administrator');
if (!in_array('National Administrator', $inherited, true)) {
    fwrite(STDERR, "Super Administrator must inherit National Administrator\n");
    exit(1);
}

if (!in_array('Read Only User', $inherited, true)) {
    fwrite(STDERR, "Super Administrator must inherit Read Only User\n");
    exit(1);
}

if (count(array_unique($inherited)) !== count($inherited)) {
    fwrite(STDERR, "Role inheritance must not contain duplicate roles\n");
    exit(1);
}

$permissions = \App\Models\Role::effectivePermissions('Super Administrator', [
    'Super Administrator' => ['manage_users', 'view_reports'],
    'National Administrator' => ['manage_roles', 'view_reports'],
    'Registration Officer' => ['register_citizen'],
    'Read Only User' => ['view_reports'],
]);

if (!in_array('manage_users', $permissions, true) || !in_array('register_citizen', $permissions, true)) {
    fwrite(STDERR, "Super Administrator effective permissions must include inherited access\n");
    exit(1);
}

if (count(array_unique($permissions)) !== count($permissions)) {
    fwrite(STDERR, "Effective permissions must not contain duplicates\n");
    exit(1);
}

echo "NRB bootstrap checks passed\n";

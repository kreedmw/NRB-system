<?php

declare(strict_types=1);

use App\Controllers\Citizen\BirthController;
use App\Controllers\Citizen\NationalIDController;
use App\Controllers\Printing\QueueController;

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/births',
    [
        BirthController::class,
        'index'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/deaths',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'deaths'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/applications',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'applications'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/approvals/pending',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'pendingApprovals'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/approvals/review',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reviewApprovals'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/approvals/approved',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'approvedApprovals'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/queue',
    [
        QueueController::class,
        'index'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/preview',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'printPreview'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/reports',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'printingReports'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/printed',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'printedCards'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/reprint',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reprintCards'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/printers',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'printerManagement'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/citizens',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsCitizens'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/births',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsBirths'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/deaths',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsDeaths'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/ids',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsIds'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/export',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsExport'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/custom',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsCustom'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/audit',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'auditLogs'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/login-history',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'loginHistory'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/permissions',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securityPermissions'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/sessions',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securitySessions'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/alerts',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securityAlerts'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/system-logs',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securitySystemLogs'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/general',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsGeneral'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/system',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsSystem'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/email',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsEmail'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/sms',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsSms'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/backups',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsBackups'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/database',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsDatabase'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/maintenance',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsMaintenance'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/profile',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'profileOverview'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/profile/password',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'profilePassword'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/profile/activity',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'profileActivity'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/registration-centres',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'registrationCentres'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/national-id/issue',
    [
        NationalIDController::class,
        'issue'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/national-id/search',
    [
        NationalIDController::class,
        'search'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/national-id/renew',
    [
        NationalIDController::class,
        'renew'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/national-id/replace',
    [
        NationalIDController::class,
        'replace'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/national-id/verify',
    [
        NationalIDController::class,
        'verify'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/national-id/suspend',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'nationalIdSuspend'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/national-id/history',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'nationalIdHistory'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/approvals/rejected',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'approvalsRejected'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/approvals/history',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'approvalsHistory'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/printed',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'printedCards'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/reprint',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reprintCards'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/printers',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'printerManagement'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/printing/reports',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'printingReports'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/citizens',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsCitizens'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/births',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsBirths'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/deaths',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsDeaths'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/ids',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsIds'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/export',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsExport'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/reports/custom',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'reportsCustom'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/permissions',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securityPermissions'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/sessions',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securitySessions'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/alerts',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securityAlerts'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/security/system-logs',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'securitySystemLogs'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/system',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsSystem'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/email',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsEmail'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/sms',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsSms'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/backups',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsBackups'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/database',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsDatabase'
    ]
);

$router->middleware(\App\Middleware\AuthMiddleware::class)->get(
    '/settings/maintenance',
    [
        \App\Controllers\Workflow\OperationsController::class,
        'settingsMaintenance'
    ]
);

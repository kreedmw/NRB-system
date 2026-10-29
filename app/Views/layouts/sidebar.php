<?php

declare(strict_types=1);

use App\Models\Role;

$currentRole = trim((string) ($_SESSION['user']['role'] ?? 'Read Only User'));
$roleDirectoryMap = [
    'Super Administrator' => 'super-admin',
    'National Administrator' => 'national-admin',
    'District Administrator' => 'district-admin',
    'Registration Officer' => 'registration-officer',
    'Supervisor' => 'supervisor',
    'Card Printing Officer' => 'card-printing-officer',
    'Auditor' => 'auditor',
    'Read Only User' => 'read-only-user',
];

$currentRoleDirectory = $roleDirectoryMap[$currentRole] ?? strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $currentRole));
$currentRoleDirectory = trim((string) $currentRoleDirectory, '-');
if ($currentRoleDirectory === '') {
    $currentRoleDirectory = 'read-only-user';
}

$navigationBasePath = '/' . $currentRoleDirectory;

$effectivePermissions = array_fill_keys(Role::effectivePermissions($currentRole, Role::defaultPermissionsByRole()), true);

$sidebarGroups = [
    [
        'type' => 'link',
        'label' => 'Dashboard',
        'href' => $navigationBasePath . '/dashboard',
        'icon' => 'fa-gauge-high',
        'permission' => 'view_dashboards',
    ],
    [
        'type' => 'group',
        'label' => 'Registration',
        'icon' => 'fa-user-plus',
        'items' => [
            ['label' => 'Citizen Registration', 'href' => '/citizen/register', 'permission' => 'register_citizen'],
            ['label' => 'Citizen Search', 'href' => '/citizen', 'permission' => 'search_citizens'],
            ['label' => 'Birth Registration', 'href' => '/births', 'permission' => 'register_births'],
            ['label' => 'Death Registration', 'href' => '/deaths', 'permission' => 'register_deaths'],
            ['label' => 'Applications', 'href' => '/applications', 'permission' => 'submit_applications_for_approval'],
        ],
    ],
    [
        'type' => 'group',
        'label' => 'National ID',
        'icon' => 'fa-id-card',
        'items' => [
            ['label' => 'Issue New ID', 'href' => '/national-id/issue', 'permission' => 'register_citizen'],
            ['label' => 'Search ID', 'href' => '/national-id/search', 'permission' => 'search_citizens'],
            ['label' => 'Verify ID', 'href' => '/national-id/verify', 'permission' => 'view_records'],
            ['label' => 'Renew ID', 'href' => '/national-id/renew', 'permission' => 'register_citizen'],
            ['label' => 'Replace Lost ID', 'href' => '/national-id/replace', 'permission' => 'register_citizen'],
        ],
    ],
    [
        'type' => 'group',
        'label' => 'Approvals',
        'icon' => 'fa-list-check',
        'items' => [
            ['label' => 'Pending Approvals', 'href' => '/approvals/pending', 'permission' => 'approve_registration'],
            ['label' => 'Under Review', 'href' => '/approvals/review', 'permission' => 'approve_registration'],
            ['label' => 'Approved Records', 'href' => '/approvals/approved', 'permission' => 'approve_registration'],
        ],
    ],
    [
        'type' => 'group',
        'label' => 'Printing',
        'icon' => 'fa-print',
        'items' => [
            ['label' => 'Print Queue', 'href' => '/printing/queue', 'permission' => 'print_card'],
            ['label' => 'Card Preview', 'href' => '/printing/preview', 'permission' => 'print_card'],
        ],
    ],
    [
        'type' => 'group',
        'label' => 'Reports',
        'icon' => 'fa-chart-column',
        'items' => [
            ['label' => 'Dashboard Statistics', 'href' => $navigationBasePath . '/reports', 'permission' => 'view_reports'],
            ['label' => 'Citizen Reports', 'href' => '/reports/citizens', 'permission' => 'view_reports'],
            ['label' => 'Birth Reports', 'href' => '/reports/births', 'permission' => 'view_reports'],
            ['label' => 'Death Reports', 'href' => '/reports/deaths', 'permission' => 'view_reports'],
            ['label' => 'ID Reports', 'href' => '/reports/ids', 'permission' => 'view_reports'],
            ['label' => 'Export Data', 'href' => '/reports/export', 'permission' => 'view_reports'],
        ],
    ],
    [
        'type' => 'group',
        'label' => 'Security',
        'icon' => 'fa-shield-halved',
        'items' => [
            ['label' => 'Users', 'href' => $navigationBasePath . '/users', 'permission' => 'manage_users'],
            ['label' => 'Roles', 'href' => $navigationBasePath . '/roles', 'permission' => 'manage_roles'],
            ['label' => 'Permissions', 'href' => '/security/permissions', 'permission' => 'create_and_assign_permissions'],
            ['label' => 'Audit Logs', 'href' => '/security/audit', 'permission' => 'view_audit_logs'],
            ['label' => 'Login History', 'href' => '/security/login-history', 'permission' => 'view_audit_logs'],
        ],
    ],
    [
        'type' => 'group',
        'label' => 'Settings',
        'icon' => 'fa-gear',
        'items' => [
            ['label' => 'General Settings', 'href' => '/settings/general', 'permission' => 'manage_system_settings'],
            ['label' => 'System Security', 'href' => '/settings/security', 'permission' => 'configure_security_policies'],
            ['label' => 'My Profile', 'href' => '/profile', 'permission' => 'view_dashboards'],
            ['label' => 'Change Password', 'href' => '/profile/password', 'permission' => 'view_dashboards'],
        ],
    ],
];
?>
<style>
    .sidebar .nav-item .left,
    .sidebar .menu-button .left,
    .sidebar .menu-button .right,
    .sidebar .menu-button .left i,
    .sidebar .nav-item .left i,
    .sidebar .menu-button .right i,
    .sidebar .submenu a {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
    }

    .sidebar .nav-item .left i,
    .sidebar .menu-button .left i,
    .sidebar .menu-button .right i {
        min-width: 1.1rem !important;
        text-align: center !important;
        font-size: 0.95rem !important;
    }

    .sidebar .nav-item .left,
    .sidebar .menu-button .left {
        gap: 0.7rem !important;
    }

    .sidebar .menu-group {
        margin-bottom: 0.45rem;
        border-radius: 12px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .sidebar .menu-button {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.92);
        border-radius: 12px;
        padding: 0.72rem 0.8rem;
        text-align: left;
        font-weight: 600;
        cursor: pointer;
        font: inherit;
    }

    .sidebar .menu-button.active {
        background: rgba(255, 255, 255, 0.08);
    }

    .sidebar .menu-button .right {
        transition: transform 0.2s ease;
    }

    .sidebar .menu-group.open .menu-button .right {
        transform: rotate(180deg);
    }

    .sidebar .submenu {
        display: none;
        padding: 0 0.6rem 0.55rem;
    }

    .sidebar .menu-group.open .submenu {
        display: block;
    }

    .sidebar .submenu a {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.52rem 0.7rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.82rem;
        border-radius: 8px;
        margin-top: 0.2rem;
        opacity: 1;
        visibility: visible;
    }

    .sidebar .submenu a:hover,
    .sidebar .submenu a.active {
        background: rgba(255, 255, 255, 0.06);
        color: white;
    }
</style>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="<?= e(base_url('assets/images/logo-mw-67x75.png')) ?>" alt="NRB Logo" style="width: 50px; height: 50px; object-fit: contain;">
        </div>
        <div>
            <p class="sidebar-title">Government</p>
            <p class="sidebar-app">NRB System</p>
        </div>
    </div>

    <?php foreach ($sidebarGroups as $group): ?>
        <?php
            if (($group['type'] ?? 'group') === 'link') {
                $permission = $group['permission'] ?? null;
                if ($permission !== null && !isset($effectivePermissions[$permission])) {
                    continue;
                }
                $href = $group['href'] ?? '#';
                $label = $group['label'] ?? 'Menu';
                $icon = $group['icon'] ?? 'fa-circle';
                ?>
                <div class="nav-group">
                    <a href="<?= e(route_url($href)) ?>" class="nav-item">
                        <div class="left" style="display:flex; align-items:center; gap:0.7rem; min-width:0;">
                            <i class="fa-solid <?= e($icon) ?>" style="display:inline-block; min-width:1.05rem; text-align:center; line-height:1;"></i>
                            <span><?= e($label) ?></span>
                        </div>
                    </a>
                </div>
            <?php continue; }

            $visibleItems = [];
            foreach (($group['items'] ?? []) as $item) {
                $permission = $item['permission'] ?? null;
                if ($permission === null || isset($effectivePermissions[$permission])) {
                    $visibleItems[] = $item;
                }
            }

            if ($visibleItems === []) {
                continue;
            }
        ?>
        <div class="menu-group">
            <button type="button" class="menu-button">
                <span class="left" style="display:flex; align-items:center; gap:0.7rem; min-width:0;">
                    <i class="fa-solid <?= e($group['icon'] ?? 'fa-circle') ?>" style="display:inline-block; min-width:1.05rem; text-align:center; line-height:1;"></i>
                    <span><?= e($group['label'] ?? 'Menu') ?></span>
                </span>
                <span class="right"><i class="fa-solid fa-chevron-down" style="display:inline-block; line-height:1;"></i></span>
            </button>
            <div class="submenu">
                <?php foreach ($visibleItems as $item): ?>
                    <a href="<?= e(route_url($item['href'] ?? '#')) ?>" style="display:flex; align-items:center; gap:0.55rem;">
                        <span style="display:inline-block; min-width:0.9rem; text-align:center; line-height:1;">•</span>
                        <span><?= e($item['label'] ?? 'Link') ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="nav-group">
        <a href="<?= e(route_url('/logout')) ?>" class="nav-item">
            <div class="left" style="display:flex; align-items:center; gap:0.7rem; min-width:0;">
                <i class="fa-solid fa-right-from-bracket" style="display:inline-block; min-width:1.05rem; text-align:center; line-height:1;"></i>
                <span>Logout</span>
            </div>
        </a>
    </div>
</aside>
<script>
    (() => {
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) {
            return;
        }

        const appBase = <?= json_encode(app_base_path()) ?> || '';

        const normalizePath = (value) => {
            let raw = String(value || '/');
            if (raw.includes('://')) {
                try {
                    raw = new URL(raw).pathname;
                } catch (error) {
                    raw = String(value || '/');
                }
            }

            const cleaned = raw.replace(/\/+$/, '') || '/';
            if (!cleaned || cleaned === '') {
                return '/';
            }

            if (appBase && cleaned.startsWith(appBase)) {
                const relative = cleaned.slice(appBase.length) || '/';
                return relative === '' ? '/' : relative;
            }

            return cleaned;
        };

        const setGroupState = (group, open) => {
            const submenu = group.querySelector('.submenu');
            const button = group.querySelector('.menu-button');
            if (!submenu || !button) {
                return;
            }

            group.classList.toggle('open', open);
            button.classList.toggle('active', open);
            button.setAttribute('aria-expanded', String(open));
            submenu.style.display = open ? 'block' : 'none';
        };

        const toggleGroup = (group) => {
            const isOpen = group.classList.contains('open');
            setGroupState(group, !isOpen);
        };

        const syncCurrentSection = () => {
            const currentPath = normalizePath(window.location.pathname);
            const allGroups = sidebar.querySelectorAll('.menu-group');
            const allLinks = sidebar.querySelectorAll('.nav-item, .submenu a');

            let matchedGroup = null;
            allLinks.forEach((link) => {
                link.classList.remove('active');
                const href = normalizePath(link.getAttribute('href') || '');
                if (href === currentPath) {
                    link.classList.add('active');
                    matchedGroup = link.closest('.menu-group') || matchedGroup;
                }
            });

            allGroups.forEach((group) => {
                const isMatched = group === matchedGroup;
                setGroupState(group, isMatched);
            });
        };

        sidebar.querySelectorAll('.menu-button').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const group = button.closest('.menu-group');
                if (group) {
                    toggleGroup(group);
                }
            });
        });

        syncCurrentSection();
    })();
</script>

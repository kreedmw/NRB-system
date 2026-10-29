<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'NRB Workflow') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --nrb-dark: #2a3a24;
            --nrb-primary: #004aad;
            --nrb-bg: #f5f7fa;
            --nrb-card: #ffffff;
            --nrb-border: rgba(15,23,42,0.08);
            --nrb-slate: #1f2937;
            --nrb-muted: #64748b;
            --nrb-success: #28a745;
            --nrb-warning: #ffc107;
            --nrb-danger: #dc3545;
            --nrb-sidebar: #11251f;
            --nrb-sidebar-alt: #17352d;
        }

        * { box-sizing: border-box; }
        body { margin: 0; background: var(--nrb-bg); color: var(--nrb-slate); font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
        .app-shell { min-height: 100vh; display: flex; }
        .sidebar { width: 260px; background: linear-gradient(180deg, var(--nrb-sidebar) 0%, var(--nrb-sidebar-alt) 100%); color: #fff; position: fixed; top: 0; left: 0; bottom: 0; overflow-y: auto; border-right: 1px solid rgba(255,255,255,0.08); }
        .sidebar-header { padding: 1.25rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; gap: 0.85rem; }
        .sidebar-logo { width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; background: rgba(255,255,255,0.14); font-weight: 700; }
        .sidebar-title { margin: 0; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.72); }
        .sidebar-app { margin: 0; font-weight: 700; }
        .nav-group { padding: 1rem 0.75rem 0.5rem; }
        .nav-item { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 0.72rem 0.8rem; border-radius: 12px; color: rgba(255,255,255,0.9); text-decoration: none; margin-bottom: 0.2rem; }
        .nav-item:hover, .nav-item.active { background: rgba(255,255,255,0.08); color: #fff; }
        .left { display: flex; align-items: center; gap: 0.75rem; }
        .menu-group { margin-bottom: 0.45rem; border-radius: 12px; overflow: hidden; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); }
        .menu-button { width: 100%; display: flex; align-items: center; justify-content: space-between; border: none; background: transparent; color: rgba(255,255,255,0.92); padding: 0.72rem 0.8rem; text-align: left; font-weight: 600; }
        .menu-button.active { background: rgba(255,255,255,0.08); border-radius: 10px; }
        .menu-button .right { transition: transform 0.2s ease; }
        .menu-group.open .menu-button .right { transform: rotate(180deg); }
        .submenu { display: none; padding: 0 0.6rem 0.55rem; }
        .menu-group.open .submenu { display: block; }
        .submenu a { display: block; padding: 0.52rem 0.7rem; color: rgba(255,255,255,0.82); text-decoration: none; font-size: 0.82rem; border-radius: 8px; margin-top: 0.2rem; }
        .submenu a:hover, .submenu a.active { background: rgba(255,255,255,0.06); color: #fff; }

        .main { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; }
        .topbar { height: 74px; background: rgba(255,255,255,0.92); border-bottom: 1px solid var(--nrb-border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 20; }
        .topbar-left { display: flex; align-items: center; gap: 1rem; font-weight: 700; color: var(--nrb-dark); }
        .toggle-btn { width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--nrb-border); background: white; display: grid; place-items: center; }
        .topbar-search { width: min(440px, 42vw); background: #f8fafc; border: 1px solid var(--nrb-border); border-radius: 12px; padding: 0.8rem 1rem; display: flex; align-items: center; gap: 0.7rem; }
        .topbar-search input { border: none; background: transparent; outline: none; width: 100%; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .header-action { position: relative; width: 42px; height: 42px; border-radius: 12px; background: #f8fafc; border: 1px solid var(--nrb-border); display: grid; place-items: center; color: var(--nrb-slate); }
        .header-action .badge { position: absolute; top: -4px; right: -2px; border-radius: 999px; font-size: 0.62rem; padding: 0.2rem 0.45rem; background: var(--nrb-danger); color: white; }
        .profile-menu-wrap { position: relative; }
        .profile-pill { display: flex; align-items: center; gap: 0.75rem; background: #f8fafc; border: 1px solid var(--nrb-border); border-radius: 14px; padding: 0.5rem 0.8rem 0.5rem 0.55rem; cursor: pointer; transition: all 0.2s ease; }
        .profile-pill:hover { border-color: rgba(0,74,173,0.25); box-shadow: 0 8px 18px rgba(15,23,42,0.06); }
        .profile-avatar { width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); color: white; display: grid; place-items: center; font-weight: 700; flex-shrink: 0; }
        .profile-meta { display: flex; flex-direction: column; line-height: 1.2; }
        .profile-meta small { color: var(--nrb-muted); }
        .profile-dropdown { position: absolute; top: calc(100% + 0.6rem); right: 0; min-width: 230px; background: white; border: 1px solid var(--nrb-border); border-radius: 16px; box-shadow: 0 18px 40px rgba(15,23,42,0.12); overflow: hidden; display: none; z-index: 60; }
        .profile-menu-wrap.open .profile-dropdown { display: grid; }
        .profile-dropdown a { display: flex; align-items: center; gap: 0.75rem; padding: 0.82rem 1rem; color: var(--nrb-slate); text-decoration: none; border-bottom: 1px solid rgba(15,23,42,0.05); font-size: 0.92rem; font-weight: 600; background: white; }
        .profile-dropdown a:last-child { border-bottom: none; }
        .profile-dropdown a:hover { background: #f8fafc; color: var(--nrb-primary); text-decoration: none; }
        .profile-dropdown a i { width: 16px; text-align: center; color: inherit; }

        .content { padding: 1.6rem 2rem 2.4rem; }
        .page-hero { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .eyebrow { display: inline-block; font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase; color: var(--nrb-primary); background: rgba(0,74,173,0.08); padding: 0.35rem 0.6rem; border-radius: 999px; font-weight: 700; }
        .page-hero h1 { margin: 0.5rem 0 0.2rem; font-size: 1.9rem; font-weight: 800; color: var(--nrb-dark); }
        .page-hero p { margin: 0; color: var(--nrb-muted); }
        .primary-btn { background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); color: #fff; border: none; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; }

        .summary-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 1rem; margin-bottom: 1.1rem; }
        .summary-card { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 18px; padding: 1rem 1.1rem; box-shadow: 0 10px 20px rgba(15,23,42,0.04); }
        .summary-card .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .summary-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; color: #fff; }
        .summary-card h3 { margin: 0 0 0.7rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--nrb-muted); }
        .summary-card .value { margin: 0; font-size: 2rem; font-weight: 800; color: var(--nrb-dark); }

        .issue-window { margin: 1.2rem 0 1.6rem; }
        .issue-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 1rem; }
        .issue-box { padding: 1.2rem; }
        .issue-box h4 { margin: 0 0 1rem; font-size: 1.1rem; font-weight: 800; color: var(--nrb-dark); }
        .issue-form { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 1rem; }
        .field { display: flex; flex-direction: column; gap: 0.45rem; }
        .field.full { grid-column: 1 / -1; }
        .field label { font-size: 0.78rem; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; color: var(--nrb-muted); }
        .field input, .field select, .field textarea { width: 100%; border: 1px solid rgba(15,23,42,0.12); border-radius: 12px; background: #f8fafc; padding: 0.85rem 0.9rem; font-size: 0.95rem; color: var(--nrb-slate); }
        .field textarea { min-height: 100px; resize: vertical; }
        .field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: rgba(0,74,173,0.5); box-shadow: 0 0 0 3px rgba(0,74,173,0.12); }
        .issue-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem; }
        .secondary-btn { background: #eef5ff; color: var(--nrb-primary); border: 1px solid rgba(0,74,173,0.18); border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; }
        .checklist { display: grid; gap: 0.8rem; }
        .check-item { display: flex; align-items: center; gap: 0.8rem; background: #f8fafc; border: 1px solid rgba(15,23,42,0.08); border-radius: 12px; padding: 0.8rem 0.9rem; }
        .check-icon { width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center; background: rgba(40,167,69,0.12); color: var(--nrb-success); font-size: 0.8rem; }
        .check-item.pending .check-icon { background: rgba(255,193,7,0.15); color: #b7791f; }
        .check-item strong { display: block; color: var(--nrb-dark); }
        .check-item span { display: block; font-size: 0.8rem; color: var(--nrb-muted); }

        .panel { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 20px; padding: 1.2rem; box-shadow: 0 10px 20px rgba(15,23,42,0.03); }
        .panel-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .panel-head h4 { margin: 0; font-weight: 800; color: var(--nrb-dark); }
        .muted { color: var(--nrb-muted); font-size: 0.85rem; }
        .table { margin-bottom: 0; }
        .table thead th { font-size: 0.75rem; letter-spacing: 0.06em; text-transform: uppercase; color: var(--nrb-muted); }
        .status-pill { display: inline-block; padding: 0.35rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; }
        .status-approved { background: rgba(40,167,69,0.12); color: var(--nrb-success); }
        .status-pending { background: rgba(255,193,7,0.15); color: #9a6700; }
        .status-queued { background: rgba(0,74,173,0.1); color: var(--nrb-primary); }

        @media (max-width: 900px) {
            .sidebar { position: static; width: 100%; }
            .main { margin-left: 0; width: 100%; }
            .app-shell { display: block; }
            .topbar { padding: 0 1rem; }
            .topbar-search { display: none; }
            .content { padding: 1rem; }
            .page-hero { display: block; }
            .summary-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

            <div class="menu-group">
                <button type="button" class="menu-button"><span class="left"><i class="fa-solid fa-gear"></i> Settings</span><span class="right"><i class="fa-solid fa-chevron-down"></i></span></button>
                <div class="submenu">
                    <a href="<?= e(route_url('/settings/general')) ?>">General Settings</a>
                    <a href="<?= e(route_url('/profile')) ?>">My Profile</a>
                    <a href="<?= e(route_url('/profile/password')) ?>">Change Password</a>
                </div>
            </div>

            <div class="nav-group">
                <a href="<?= e(route_url('/logout')) ?>" class="nav-item"><div class="left"><i class="fa-solid fa-right-from-bracket"></i> Logout</div></a>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="toggle-btn"><i class="fa-solid fa-bars"></i></button>
                    <span><?= e($pageTitle ?? 'NRB Workflow') ?></span>
                </div>

                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search records, officers or applications...">
                </div>

                <div class="topbar-right">
                    <div class="header-action">
                        <i class="fa-solid fa-bell"></i>
                        <span class="badge">6</span>
                    </div>
                    <div class="header-action"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="profile-menu-wrap">
                        <div class="profile-pill" tabindex="0">
                            <div class="profile-avatar"><?= e(substr($user['full_name'] ?? 'Admin', 0, 2)) ?></div>
                            <div class="profile-meta">
                                <div style="font-weight:700; font-size:0.9rem; color:var(--nrb-slate);">
                                    <?= e($user['full_name'] ?? 'Admin User') ?>
                                </div>
                                <small><?= e($user['username'] ?? $user['email'] ?? ($role ?? 'Administrator')) ?></small>
                                <small><?= e($user['email'] ?? '') ?></small>
                            </div>
                        </div>
                        <div class="profile-dropdown">
                            <a href="<?= e(route_url('/profile')) ?>"><i class="fa-solid fa-user"></i> My Profile</a>
                            <a href="<?= e(route_url('/profile/password')) ?>"><i class="fa-solid fa-key"></i> Change Password</a>
                            <a href="<?= e(route_url('/profile/activity')) ?>"><i class="fa-solid fa-clock-rotate-left"></i> Login Activity</a>
                            <a href="<?= e(route_url('/logout')) ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="page-hero">
                    <div>
                        <span class="eyebrow">NRB Operations</span>
                        <h1><?= e($pageTitle ?? 'Workflow') ?></h1>
                        <p><?= e($subtitle ?? 'Operational overview and records management.') ?></p>
                    </div>
                    <button class="primary-btn"><i class="fa-solid fa-plus"></i> <?= e($actionLabel ?? 'New Record') ?></button>
                </div>

                <?php if (!empty($issueMode)): ?>
                    <div class="issue-window">
                        <div class="issue-grid">
                            <div class="panel issue-box">
                                <h4>Citizen Verification &amp; Issuance Details</h4>
                                <form class="issue-form">
                                    <div class="field">
                                        <label for="nid-search">NID Number</label>
                                        <input id="nid-search" type="text" value="MWI-2026-18472" />
                                    </div>
                                    <div class="field">
                                        <label for="issue-type">Issue Type</label>
                                        <select id="issue-type">
                                            <option>New Card</option>
                                            <option>Replacement</option>
                                            <option>Renewal</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label for="full-name">Full Name</label>
                                        <input id="full-name" type="text" value="Moses Nkhoma" />
                                    </div>
                                    <div class="field">
                                        <label for="district">District</label>
                                        <input id="district" type="text" value="Lilongwe" />
                                    </div>
                                    <div class="field">
                                        <label for="facility">Registration Centre</label>
                                        <input id="facility" type="text" value="Lilongwe Central Office" />
                                    </div>
                                    <div class="field">
                                        <label for="date">Issue Date</label>
                                        <input id="date" type="date" value="2026-10-29" />
                                    </div>
                                    <div class="field full">
                                        <label for="notes">Review Notes</label>
                                        <textarea id="notes">Applicant verified against national records. Biometrics cleared and pending final printing check.</textarea>
                                    </div>
                                </form>
                                <div class="issue-actions">
                                    <button class="secondary-btn" type="button"><i class="fa-solid fa-floppy-disk"></i> Save Draft</button>
                                    <button class="primary-btn" type="button"><i class="fa-solid fa-id-card"></i> Approve &amp; Issue</button>
                                </div>
                            </div>

                            <div class="panel issue-box">
                                <h4>Issuance Checklist</h4>
                                <div class="checklist">
                                    <div class="check-item">
                                        <div class="check-icon"><i class="fa-solid fa-check"></i></div>
                                        <div>
                                            <strong>Biometric Verification</strong>
                                            <span>Matched and validated</span>
                                        </div>
                                    </div>
                                    <div class="check-item">
                                        <div class="check-icon"><i class="fa-solid fa-check"></i></div>
                                        <div>
                                            <strong>Identity Review</strong>
                                            <span>Documentation complete</span>
                                        </div>
                                    </div>
                                    <div class="check-item pending">
                                        <div class="check-icon"><i class="fa-solid fa-clock"></i></div>
                                        <div>
                                            <strong>Printing Approval</strong>
                                            <span>Awaiting final sign-off</span>
                                        </div>
                                    </div>
                                    <div class="check-item pending">
                                        <div class="check-icon"><i class="fa-solid fa-clock"></i></div>
                                        <div>
                                            <strong>Card Dispatch</strong>
                                            <span>Not yet assigned</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="summary-grid">
                    <?php foreach (($stats ?? []) as $stat): ?>
                        <div class="summary-card">
                            <div class="top">
                                <div class="summary-icon" style="background: linear-gradient(135deg, #004aad, #17352d);">
                                    <i class="fa-solid <?= e($stat['icon'] ?? 'fa-file') ?>"></i>
                                </div>
                            </div>
                            <h3><?= e($stat['label'] ?? 'Records') ?></h3>
                            <p class="value"><?= e((string) ($stat['value'] ?? 0)) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <h4><?= e($pageTitle ?? 'Records') ?> Overview</h4>
                        <span class="muted">Updated today</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <?php foreach (($columns ?? []) as $col): ?>
                                        <th><?= e($col) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($rows ?? []) as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $key => $value): ?>
                                            <?php if ($key === 'status'): ?>
                                                <?php
                                                    $status = strtolower((string) $value);
                                                    $class = str_contains($status, 'approved') ? 'status-approved' : (str_contains($status, 'pending') || str_contains($status, 'review') || str_contains($status, 'queued') ? 'status-pending' : 'status-queued');
                                                ?>
                                                <td><span class="status-pill <?= e($class) ?>"><?= e((string) $value) ?></span></td>
                                            <?php else: ?>
                                                <td><?= e((string) $value) ?></td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const appBase = <?= json_encode(app_base_path()) ?> || '';

        const normalizePath = (value) => {
            const cleaned = String(value || '/').replace(/\/+$/, '') || '/';
            if (!cleaned || cleaned === '') return '/';
            if (appBase && cleaned.startsWith(appBase)) {
                const relative = cleaned.slice(appBase.length) || '/';
                return relative === '' ? '/' : relative;
            }
            return cleaned;
        };

        const sidebar = document.querySelector('.sidebar');
        const profileMenu = document.querySelector('.profile-menu-wrap');

        const closeMobileSidebar = () => {
            if (sidebar) sidebar.classList.remove('open');
        };

        const toggleProfileMenu = () => {
            if (!profileMenu) return;
            profileMenu.classList.toggle('open');
        };

        document.querySelector('.toggle-btn')?.addEventListener('click', () => {
            if (sidebar) {
                sidebar.classList.toggle('open');
            }
        });

        document.addEventListener('click', (event) => {
            const target = event.target;
            if (!target) return;

            if (profileMenu && !profileMenu.contains(target)) {
                profileMenu.classList.remove('open');
            }

            if (sidebar && !sidebar.contains(target) && !document.querySelector('.toggle-btn')?.contains(target)) {
                closeMobileSidebar();
            }
        });

        document.querySelector('.profile-pill')?.addEventListener('click', toggleProfileMenu);
        document.querySelector('.profile-pill')?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleProfileMenu();
            }
        });

        document.querySelectorAll('a[href^="/"], form[action^="/"]').forEach((element) => {
            const raw = element.getAttribute(element.hasAttribute('href') ? 'href' : 'action');
            if (!raw || raw.startsWith('http') || raw.startsWith('#') || raw.startsWith('mailto:') || raw.startsWith('tel:')) {
                return;
            }

            const prefixed = appBase ? (raw === '/' ? appBase + '/' : appBase + raw) : raw;
            if (element.hasAttribute('href')) {
                element.setAttribute('href', prefixed);
            }
            if (element.hasAttribute('action')) {
                element.setAttribute('action', prefixed);
            }
        });

        const currentPath = normalizePath(window.location.pathname);

        const applyActiveMenuState = () => {
            document.querySelectorAll('.nav-item, .submenu a').forEach((link) => link.classList.remove('active'));
            document.querySelectorAll('.menu-button').forEach((button) => button.classList.remove('active'));

            const currentLink = [...document.querySelectorAll('.nav-item, .submenu a')].find((link) => {
                const href = normalizePath(link.getAttribute('href') || '');
                return href === currentPath;
            });

            if (!currentLink) return;

            currentLink.classList.add('active');
            const group = currentLink.closest('.menu-group');
            if (group) {
                group.classList.add('open');
                const button = group.querySelector('.menu-button');
                if (button) button.classList.add('active');
            }
        };

        applyActiveMenuState();

        document.querySelectorAll('.menu-button').forEach((button) => {
            button.addEventListener('click', () => {
                const group = button.closest('.menu-group');
                if (group) {
                    const isOpen = group.classList.contains('open');
                    group.classList.toggle('open');
                    document.querySelectorAll('.menu-button').forEach((item) => {
                        item.classList.toggle('active', item === button && !isOpen);
                    });
                }
            });
        });

        document.querySelectorAll('.submenu a').forEach((link) => {
            link.addEventListener('click', () => {
                document.querySelectorAll('.submenu a').forEach((item) => item.classList.remove('active'));
                link.classList.add('active');
                const group = link.closest('.menu-group');
                if (group) {
                    group.classList.add('open');
                    const button = group.querySelector('.menu-button');
                    if (button) button.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>

<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Reports') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        body { margin:0; background:var(--nrb-bg); color:var(--nrb-slate); font-family:"Segoe UI",sans-serif; }
        .app-shell { min-height:100vh; display:flex; }
        .sidebar { width:260px; background: linear-gradient(180deg, var(--nrb-sidebar) 0%, var(--nrb-sidebar-alt) 100%); color:white; position:fixed; top:0; left:0; bottom:0; overflow-y:auto; }
        .sidebar-header { padding:1.25rem 1rem; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; align-items:center; gap:0.8rem; }
        .sidebar-logo { width:42px; height:42px; border-radius:12px; background: rgba(255,255,255,0.12); display:grid; place-items:center; font-weight:700; }
        .sidebar-title { margin:0; font-size:0.72rem; letter-spacing:0.08em; color:rgba(255,255,255,0.72); text-transform:uppercase; }
        .sidebar-app { margin:0; font-weight:700; }
        .nav-group { padding:1rem 0.8rem; }
        .nav-item { display:flex; align-items:center; justify-content:space-between; padding:0.72rem 0.8rem; border-radius:12px; color: rgba(255,255,255,0.88); text-decoration:none; margin-bottom:0.25rem; border:none; background: transparent; text-align:left; }
        .nav-item:hover, .nav-item.active { background: rgba(255,255,255,0.08); }
        .nav-item .left { display:flex; align-items:center; gap:0.7rem; }
        .menu-group { margin-bottom: 0.45rem; border-radius: 12px; overflow: hidden; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); }
        .menu-button { width:100%; display:flex; align-items:center; justify-content:space-between; border:none; background: transparent; color: rgba(255,255,255,0.92); border-radius: 12px; padding: 0.72rem 0.8rem; text-align:left; font-weight:600; }
        .menu-button.active { background: rgba(255,255,255,0.08); }
        .menu-button .left { display:flex; align-items:center; gap:0.7rem; }
        .menu-button .right { transition: transform 0.2s ease; }
        .menu-group.open .menu-button .right { transform: rotate(180deg); }
        .submenu { display:none; padding: 0 0.6rem 0.55rem; }
        .menu-group.open .submenu { display:block; }
        .submenu a { display:block; padding:0.52rem 0.7rem; color: rgba(255,255,255,0.8); text-decoration:none; font-size:0.82rem; border-radius:8px; margin-top:0.2rem; }
        .submenu a:hover, .submenu a.active { background: rgba(255,255,255,0.06); color:white; }
        .main { margin-left:260px; width: calc(100% - 260px); }
        .topbar { height:74px; background: rgba(255,255,255,0.94); border-bottom:1px solid var(--nrb-border); display:flex; align-items:center; justify-content:space-between; padding:0 2rem; position:sticky; top:0; }
        .toggle-btn { width:38px; height:38px; border-radius:10px; border:1px solid var(--nrb-border); background:white; display:grid; place-items:center; }
        .topbar-search { width:min(440px,42vw); background:#f8fafc; border:1px solid var(--nrb-border); border-radius:12px; padding:0.8rem 1rem; display:flex; align-items:center; gap:0.6rem; }
        .topbar-search input { border:none; background:transparent; width:100%; outline:none; }
        .topbar-right { display:flex; align-items:center; gap:1rem; }
        .header-action { position:relative; width:42px; height:42px; border-radius:12px; background:#f8fafc; border:1px solid var(--nrb-border); display:grid; place-items:center; color: var(--nrb-slate); }
        .header-action .badge { position:absolute; top:-4px; right:-2px; border-radius:999px; font-size:0.62rem; padding:0.2rem 0.45rem; background: var(--nrb-danger); color:white; }
        .profile-menu-wrap { position: relative; }
        .profile-pill { display:flex; align-items:center; gap:0.75rem; background:#f8fafc; border:1px solid var(--nrb-border); border-radius:14px; padding:0.5rem 0.8rem 0.5rem 0.55rem; cursor:pointer; transition:all 0.2s ease; }
        .profile-pill:hover { border-color: rgba(0,74,173,0.25); box-shadow:0 8px 18px rgba(15,23,42,0.06); }
        .profile-avatar { width:35px; height:35px; border-radius:50%; background:linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); color:white; display:grid; place-items:center; font-weight:700; flex-shrink:0; }
        .profile-meta { display:flex; flex-direction:column; line-height:1.2; }
        .profile-meta small { color: var(--nrb-muted); }
        .profile-dropdown { position:absolute; top: calc(100% + 0.6rem); right:0; min-width:230px; background:white; border:1px solid var(--nrb-border); border-radius:16px; box-shadow:0 18px 40px rgba(15,23,42,0.12); overflow:hidden; display:none; z-index:60; }
        .profile-menu-wrap.open .profile-dropdown { display:grid; }
        .profile-dropdown a { display:flex; align-items:center; gap:0.75rem; padding:0.82rem 1rem; color: var(--nrb-slate); text-decoration:none; border-bottom:1px solid rgba(15,23,42,0.05); font-size:0.92rem; font-weight:600; background:white; }
        .profile-dropdown a:last-child { border-bottom:none; }
        .profile-dropdown a:hover { background:#f8fafc; color:var(--nrb-primary); text-decoration:none; }
        .profile-dropdown a i { width:16px; text-align:center; color:inherit; }
        .content { padding:1.6rem 2rem 2.4rem; }
        .summary-grid { display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:1rem; margin-bottom:1rem; }
        .summary-card { background:var(--nrb-card); border:1px solid var(--nrb-border); border-radius:18px; padding:1rem; box-shadow:0 10px 20px rgba(15,23,42,0.03); }
        .summary-card h3 { font-size:0.78rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--nrb-muted); margin:0 0 0.8rem; }
        .summary-card .value { font-size:2rem; font-weight:800; color:var(--nrb-dark); margin:0; }
        .panel { background:var(--nrb-card); border:1px solid var(--nrb-border); border-radius:20px; padding:1.2rem; box-shadow:0 10px 20px rgba(15,23,42,0.03); }
        .panel-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
        .panel-head h4 { margin:0; font-weight:800; color:var(--nrb-dark); }
        .badge-soft { background: rgba(0,74,173,0.08); color: var(--nrb-primary); border-radius:999px; padding:0.35rem 0.7rem; font-size:0.72rem; font-weight:700; }
        .chart-wrap { position:relative; height:320px; }
        .table thead th { font-size:0.76rem; text-transform: uppercase; letter-spacing:0.06em; color: var(--nrb-muted); }
        @media (max-width: 900px) { .sidebar { position: static; width:100%; } .main { margin-left:0; width:100%; } .app-shell { display:block; } .topbar { padding:0 1rem; } .topbar-search { display:none; } .content { padding:1rem; } .summary-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media (max-width:560px) { .summary-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <main class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="toggle-btn"><i class="fa-solid fa-bars"></i></button>
                    <span>Reports & Analytics</span>
                </div>

                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search reports, districts or officers...">
                </div>

                <div class="topbar-right">
                    <div class="header-action">
                        <i class="fa-solid fa-bell"></i>
                        <span class="badge">12</span>
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
                <div class="summary-grid">
                    <div class="summary-card">
                        <h3>Citizens</h3>
                        <p class="value"><?= e((string) ($stats['citizens'] ?? 0)) ?></p>
                    </div>
                    <div class="summary-card">
                        <h3>National IDs</h3>
                        <p class="value"><?= e((string) ($stats['national_ids'] ?? 0)) ?></p>
                    </div>
                    <div class="summary-card">
                        <h3>Birth Registrations</h3>
                        <p class="value"><?= e((string) ($stats['births'] ?? 0)) ?></p>
                    </div>
                    <div class="summary-card">
                        <h3>Approvals</h3>
                        <p class="value"><?= e((string) ($stats['approvals'] ?? 0)) ?></p>
                    </div>
                </div>

                <div class="panel mb-4">
                    <div class="panel-head">
                        <h4>Registration Performance</h4>
                        <span class="badge-soft">Quarterly</span>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="reportsChart"></canvas>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <h4>Report Exports</h4>
                        <span class="badge-soft">Available</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Report</th>
                                    <th>Period</th>
                                    <th>Format</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Citizens by District</td>
                                    <td>Monthly</td>
                                    <td>CSV</td>
                                    <td><span class="badge bg-success-subtle text-success rounded-pill">Ready</span></td>
                                </tr>
                                <tr>
                                    <td>Birth Registration Summary</td>
                                    <td>Quarterly</td>
                                    <td>PDF</td>
                                    <td><span class="badge bg-warning-subtle text-warning rounded-pill">Queued</span></td>
                                </tr>
                                <tr>
                                    <td>ID Issuance Overview</td>
                                    <td>Yearly</td>
                                    <td>XLSX</td>
                                    <td><span class="badge bg-success-subtle text-success rounded-pill">Ready</span></td>
                                </tr>
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

        const ctx = document.getElementById('reportsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun'],
                    datasets: [
                        { label: 'Registrations', data: [1200, 1600, 1700, 2100, 2500, 3100], backgroundColor: '#004aad' },
                        { label: 'Births', data: [420, 470, 560, 620, 720, 810], backgroundColor: '#28a745' },
                        { label: 'ID Issued', data: [540, 680, 700, 940, 1100, 1380], backgroundColor: '#2a3a24' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    </script>
</body>
</html>

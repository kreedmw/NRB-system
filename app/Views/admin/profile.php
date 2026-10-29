<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'My Profile') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --nrb-dark: #142d28;
            --nrb-primary: #004aad;
            --nrb-bg: #f4f7fb;
            --nrb-card: #ffffff;
            --nrb-border: rgba(15, 23, 42, 0.08);
            --nrb-slate: #1f2937;
            --nrb-muted: #64748b;
            --nrb-success: #28a745;
            --nrb-warning: #ffc107;
            --nrb-danger: #dc3545;
            --nrb-sidebar: #11251f;
            --nrb-sidebar-alt: #17352d;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--nrb-bg);
            color: var(--nrb-slate);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .app-shell { min-height: 100vh; display: flex; }
        .sidebar { width: 260px; background: linear-gradient(180deg, var(--nrb-sidebar) 0%, var(--nrb-sidebar-alt) 100%); color: white; position: fixed; top: 0; left: 0; bottom: 0; overflow-y: auto; border-right: 1px solid rgba(255,255,255,0.08); z-index: 40; }
        .sidebar-header { padding: 1.25rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; gap: 0.85rem; }
        .sidebar-logo { width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; background: rgba(255,255,255,0.14); overflow: hidden; }
        .sidebar-logo img { width: 100%; height: 100%; object-fit: contain; }
        .sidebar-title { margin: 0; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.72); }
        .sidebar-app { margin: 0; font-weight: 700; }
        .nav-group { padding: 1rem 0.75rem 0.5rem; }
        .nav-item { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 0.72rem 0.8rem; border-radius: 12px; color: rgba(255,255,255,0.9); text-decoration: none; margin-bottom: 0.2rem; }
        .nav-item:hover, .nav-item.active { background: rgba(255,255,255,0.08); color: #fff; }
        .left { display: flex; align-items: center; gap: 0.75rem; }
        .menu-group { margin-bottom: 0.45rem; border-radius: 12px; overflow: hidden; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); }
        .menu-button { width: 100%; display: flex; align-items: center; justify-content: space-between; border: none; background: transparent; color: rgba(255,255,255,0.92); padding: 0.72rem 0.8rem; text-align: left; font-weight: 600; }
        .menu-button.active { background: rgba(255,255,255,0.08); }
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
        .topbar-search input { border: none; background: transparent; width: 100%; outline: none; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .header-action { position: relative; width: 42px; height: 42px; border-radius: 12px; background: #f8fafc; border: 1px solid var(--nrb-border); display: grid; place-items: center; color: var(--nrb-slate); }
        .header-action .badge { position: absolute; top: -4px; right: -2px; border-radius: 999px; font-size: 0.62rem; padding: 0.2rem 0.45rem; background: var(--nrb-danger); color: white; }
        .profile-menu-wrap { position: relative; }
        .profile-pill { display: flex; align-items: center; gap: 0.75rem; background: #f8fafc; border: 1px solid var(--nrb-border); border-radius: 14px; padding: 0.5rem 0.8rem 0.5rem 0.55rem; cursor: pointer; }
        .profile-avatar { width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); color: white; display: grid; place-items: center; font-weight: 700; }
        .profile-meta { display: flex; flex-direction: column; line-height: 1.2; }
        .profile-meta small { color: var(--nrb-muted); }
        .profile-dropdown { position: absolute; top: calc(100% + 0.6rem); right: 0; min-width: 230px; background: white; border: 1px solid var(--nrb-border); border-radius: 16px; box-shadow: 0 18px 40px rgba(15,23,42,0.12); overflow: hidden; display: none; z-index: 60; }
        .profile-menu-wrap.open .profile-dropdown { display: grid; }
        .profile-dropdown a { display: flex; align-items: center; gap: 0.75rem; padding: 0.82rem 1rem; color: var(--nrb-slate); text-decoration: none; border-bottom: 1px solid rgba(15,23,42,0.05); font-size: 0.92rem; font-weight: 600; }
        .profile-dropdown a:last-child { border-bottom: none; }
        .profile-dropdown a:hover { background: #f8fafc; color: var(--nrb-primary); }

        .content { padding: 1.8rem 2rem 2.5rem; }
        .page-hero { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.2rem; }
        .eyebrow { display: inline-block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--nrb-primary); background: rgba(0,74,173,0.08); padding: 0.35rem 0.6rem; border-radius: 999px; }
        .page-hero h1 { margin: 0.5rem 0 0.2rem; font-size: 1.9rem; font-weight: 800; color: var(--nrb-dark); }
        .page-hero p { margin: 0; color: var(--nrb-muted); }
        .primary-btn { background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); color: white; border: none; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; }
        .secondary-btn { background: #eef5ff; color: var(--nrb-primary); border: 1px solid rgba(0,74,173,0.18); border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; }

        .stats-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 1rem; margin-bottom: 1.2rem; }
        .stat-card { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 18px; padding: 1rem 1.1rem; box-shadow: 0 10px 20px rgba(15,23,42,0.03); }
        .stat-card .top { display: flex; align-items: center; justify-content: space-between; }
        .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; color: white; }
        .stat-card h3 { margin: 0.9rem 0 0.3rem; font-size: 0.8rem; letter-spacing: 0.06em; text-transform: uppercase; color: var(--nrb-muted); }
        .stat-card .value { margin: 0; font-size: 2rem; font-weight: 800; color: var(--nrb-dark); }

        .profile-content { display: grid; grid-template-columns: 360px minmax(0,1fr); gap: 1.2rem; }
        .panel { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 20px; padding: 1.1rem; box-shadow: 0 10px 20px rgba(15,23,42,0.03); }
        .profile-card { padding: 1.3rem; }
        .profile-photo { width: 120px; height: 120px; border-radius: 26px; margin: 0 auto 1rem; display: grid; place-items: center; background: linear-gradient(135deg, #e8f1ff, #dff4ea); color: var(--nrb-primary); font-size: 3rem; font-weight: 800; }
        .profile-name { text-align: center; font-size: 1.5rem; font-weight: 800; margin-bottom: 0.2rem; }
        .profile-role { text-align: center; color: var(--nrb-primary); font-weight: 700; margin-bottom: 1rem; }
        .status-badge { display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(40,167,69,0.12); color: var(--nrb-success); padding: 0.38rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; }
        .info-list { display: grid; gap: 0.8rem; margin-top: 1.2rem; }
        .info-item { display: flex; justify-content: space-between; gap: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(15,23,42,0.05); }
        .info-item:last-child { border-bottom: 0; padding-bottom: 0; }
        .info-item span:first-child { color: var(--nrb-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .info-item strong { color: var(--nrb-slate); font-size: 0.94rem; }

        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .section-header h3 { margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--nrb-dark); }
        .section-header a { text-decoration: none; color: var(--nrb-primary); font-weight: 700; }

        .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 1rem; }
        .detail-card { background: #f8fafc; border: 1px solid rgba(15,23,42,0.06); border-radius: 16px; padding: 1rem; }
        .detail-card h4 { margin: 0 0 0.7rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--nrb-muted); }
        .detail-card p { margin: 0; color: var(--nrb-slate); font-weight: 600; }
        .detail-card small { color: var(--nrb-muted); }

        .list-stack { display: grid; gap: 0.7rem; }
        .mini-list-item { display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid rgba(15,23,42,0.06); border-radius: 12px; padding: 0.8rem 0.9rem; }
        .mini-list-item .meta { font-weight: 600; color: var(--nrb-slate); }
        .mini-list-item .tag { font-size: 0.72rem; padding: 0.35rem 0.55rem; border-radius: 999px; background: rgba(0,74,173,0.08); color: var(--nrb-primary); font-weight: 700; }

        .timeline { display: grid; gap: 0.75rem; }
        .timeline-item { display: grid; grid-template-columns: 54px minmax(0,1fr); gap: 0.85rem; align-items: start; }
        .timeline-time { background: #edf5ff; color: var(--nrb-primary); border-radius: 10px; font-size: 0.72rem; font-weight: 700; padding: 0.45rem 0.55rem; text-align: center; }
        .timeline-body { background: #f8fafc; border: 1px solid rgba(15,23,42,0.06); border-radius: 12px; padding: 0.8rem 0.9rem; }
        .timeline-body strong { display: block; margin-bottom: 0.2rem; color: var(--nrb-dark); }
        .timeline-body span { color: var(--nrb-muted); font-size: 0.82rem; }

        @media (max-width: 900px) {
            .sidebar { position: static; width: 100%; }
            .main { margin-left: 0; width: 100%; }
            .app-shell { display: block; }
            .topbar { padding: 0 1rem; }
            .topbar-search { display: none; }
            .content { padding: 1rem; }
            .profile-content { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
            .detail-grid { grid-template-columns: 1fr; }
            .page-hero { display: block; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <main class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="toggle-btn"><i class="fa-solid fa-bars"></i></button>
                    <span><?= e($pageTitle ?? 'My Profile') ?></span>
                </div>

                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search records, officers or applications...">
                </div>

                <div class="topbar-right">
                    <div class="header-action"><i class="fa-solid fa-bell"></i><span class="badge">4</span></div>
                    <div class="header-action"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="profile-menu-wrap">
                        <div class="profile-pill" tabindex="0">
                            <div class="profile-avatar"><?= e(substr((string) ($user['full_name'] ?? 'Admin'), 0, 2)) ?></div>
                            <div class="profile-meta">
                                <div style="font-weight:700; font-size:0.9rem; color:var(--nrb-slate);">
                                    <?= e((string) ($user['full_name'] ?? 'Admin User')) ?>
                                </div>
                                <small><?= e((string) ($user['username'] ?? $user['email'] ?? ($role ?? 'Administrator'))) ?></small>
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
                        <span class="eyebrow">Administrator</span>
                        <h1><?= e($pageTitle ?? 'My Profile') ?></h1>
                        <p><?= e($subtitle ?? 'Personal overview and operational profile.') ?></p>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <button class="secondary-btn" type="button"><i class="fa-solid fa-pen-to-square"></i> Edit profile</button>
                        <button class="primary-btn" type="button"><i class="fa-solid fa-download"></i> Export data</button>
                    </div>
                </div>

                <div class="profile-content">
                    <aside class="panel profile-card">
                        <div class="profile-photo"><?= e(substr((string) ($user['full_name'] ?? 'AU'), 0, 2)) ?></div>
                        <div class="profile-name"><?= e((string) ($user['full_name'] ?? 'Admin User')) ?></div>
                        <div class="profile-role"><?= e((string) ($role ?? 'Super Administrator')) ?></div>
                        <div style="display:flex; justify-content:center; margin-bottom:1rem;">
                            <span class="status-badge"><i class="fa-solid fa-circle-check"></i> <?= e((string) ($profile['status'] ?? 'Active')) ?></span>
                        </div>

                        <div class="info-list">
                            <div class="info-item">
                                <span>Employee ID</span>
                                <strong><?= e((string) ($profile['employeeId'] ?? 'NRB-ADM-1048')) ?></strong>
                            </div>
                            <div class="info-item">
                                <span>Department</span>
                                <strong><?= e((string) ($profile['department'] ?? 'NRB')) ?></strong>
                            </div>
                            <div class="info-item">
                                <span>Office</span>
                                <strong><?= e((string) ($profile['office'] ?? 'Headquarters')) ?></strong>
                            </div>
                            <div class="info-item">
                                <span>Risk Level</span>
                                <strong><?= e((string) ($profile['riskLevel'] ?? 'Low')) ?></strong>
                            </div>
                        </div>
                    </aside>

                    <section style="display:grid; gap:1.2rem;">
                        <div class="panel">
                            <div class="section-header">
                                <h3>Core profile</h3>
                                <a href="#">Manage access</a>
                            </div>
                            <div class="detail-grid">
                                <div class="detail-card">
                                    <h4>Email</h4>
                                    <p><?= e((string) ($user['email'] ?? 'admin@nrb.gov.mw')) ?></p>
                                </div>
                                <div class="detail-card">
                                    <h4>Phone</h4>
                                    <p><?= e((string) ($profile['phone'] ?? '+265 991 234 567')) ?></p>
                                </div>
                                <div class="detail-card">
                                    <h4>Service period</h4>
                                    <p><?= e((string) ($profile['servicePeriod'] ?? '5 years 2 months')) ?></p>
                                </div>
                                <div class="detail-card">
                                    <h4>Country</h4>
                                    <p><?= e((string) ($profile['country'] ?? 'Malawi')) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="section-header">
                                <h3>Account & security</h3>
                                <a href="<?= e(route_url('/profile/password')) ?>">Change password</a>
                            </div>
                            <div class="detail-grid">
                                <div class="detail-card">
                                    <h4>Last login</h4>
                                    <p><?= e((string) ($profile['lastLogin'] ?? 'Today, 08:42 AM')) ?></p>
                                </div>
                                <div class="detail-card">
                                    <h4>Authentication</h4>
                                    <p>Multi-factor enabled</p>
                                </div>
                                <div class="detail-card">
                                    <h4>Access level</h4>
                                    <p><?= e((string) ($profile['level'] ?? 'Level 7')) ?></p>
                                </div>
                                <div class="detail-card">
                                    <h4>Session security</h4>
                                    <p>Protected and compliant</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="section-header">
                                <h3>Notifications</h3>
                                <a href="#">View all</a>
                            </div>
                            <div class="list-stack">
                                <?php foreach (($notifications ?? []) as $notification): ?>
                                    <div class="mini-list-item">
                                        <div class="meta"><?= e((string) $notification) ?></div>
                                        <span class="tag">New</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="section-header">
                                <h3>Activity timeline</h3>
                                <a href="<?= e(route_url('/profile/activity')) ?>">Open log</a>
                            </div>
                            <div class="timeline">
                                <?php foreach (($timeline ?? []) as $item): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-time"><?= e((string) ($item['time'] ?? 'Now')) ?></div>
                                        <div class="timeline-body">
                                            <strong><?= e((string) ($item['title'] ?? 'Activity')) ?></strong>
                                            <span><?= e((string) ($item['detail'] ?? '')) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="section-header">
                                <h3>Access permissions</h3>
                                <a href="#">Review matrix</a>
                            </div>
                            <div class="list-stack">
                                <?php foreach (($permissions ?? []) as $permission): ?>
                                    <div class="mini-list-item">
                                        <div class="meta"><?= e((string) $permission) ?></div>
                                        <span class="tag">Granted</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="section-header">
                                <h3>Documents</h3>
                                <a href="#">Upload</a>
                            </div>
                            <div class="list-stack">
                                <?php foreach (($documents ?? []) as $document): ?>
                                    <div class="mini-list-item">
                                        <div class="meta"><?= e((string) ($document['name'] ?? 'Document')) ?></div>
                                        <span class="tag"><?= e((string) ($document['meta'] ?? 'Verified')) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const profileMenu = document.querySelector('.profile-menu-wrap');

        document.querySelector('.toggle-btn')?.addEventListener('click', () => {
            if (sidebar) {
                sidebar.classList.toggle('open');
            }
        });

        document.querySelector('.profile-pill')?.addEventListener('click', () => {
            if (profileMenu) {
                profileMenu.classList.toggle('open');
            }
        });
    </script>
</body>
</html>

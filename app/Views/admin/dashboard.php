<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'NRB Dashboard') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --nrb-dark: #2a3a24;
            --nrb-primary: #004aad;
            --nrb-bg: #f5f7fa;
            --nrb-card: #ffffff;
            --nrb-border: rgba(15, 23, 42, 0.08);
            --nrb-slate: #1f2937;
            --nrb-muted: #64748b;
            --nrb-success: #28a745;
            --nrb-warning: #ffc107;
            --nrb-danger: #dc3545;
            --nrb-sidebar: #11251f;
            --nrb-sidebar-alt: #17352d;
            --nrb-green-soft: #ecf8f1;
            --nrb-blue-soft: #edf5ff;
            --nrb-gold-soft: #fff4d8;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--nrb-bg);
            color: var(--nrb-slate);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--nrb-sidebar) 0%, var(--nrb-sidebar-alt) 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            border-right: 1px solid rgba(255,255,255,0.08);
            z-index: 50;
        }

        .sidebar-header {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .sidebar-logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,0.14);
            font-weight: 700;
            font-size: 0.85rem;
        }

        .sidebar-title {
            font-size: 0.8rem;
            color: rgba(255,255,255,.72);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0;
        }

        .sidebar-app {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .nav-group {
            padding: 1rem 0.75rem 0.5rem;
        }

        .nav-label {
            padding: 0.5rem 0.75rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,.48);
            margin-bottom: 0.25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.7rem 0.8rem;
            border-radius: 12px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            margin-bottom: 0.22rem;
            transition: 0.2s ease;
            background: transparent;
            border: none;
            text-align: left;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255,255,255,0.08);
            text-decoration: none;
            color: white;
        }

        .nav-item .left {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-weight: 500;
        }

        .nav-item .right {
            font-size: 0.72rem;
            opacity: 0.85;
        }

        .menu-group {
            margin-bottom: 0.45rem;
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.04);
        }

        .menu-button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: none;
            background: transparent;
            color: rgba(255,255,255,0.92);
            border-radius: 12px;
            padding: 0.72rem 0.8rem;
            text-align: left;
            font-weight: 600;
        }

        .menu-button.active {
            background: rgba(255,255,255,0.08);
        }

        .menu-button .left {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .menu-button .right {
            transition: transform 0.2s ease;
        }

        .menu-group.open .menu-button .right {
            transform: rotate(180deg);
        }

        .submenu {
            display: none;
            padding: 0 0.6rem 0.55rem;
        }

        .menu-group.open .submenu {
            display: block;
        }

        .submenu a {
            display: block;
            padding: 0.52rem 0.7rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.82rem;
            border-radius: 8px;
            margin-top: 0.2rem;
        }

        .submenu a:hover,
        .submenu a.active {
            background: rgba(255,255,255,0.06);
            color: white;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
        }

        .topbar {
            height: 74px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--nrb-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 700;
            color: var(--nrb-dark);
            font-size: 1.15rem;
        }

        .toggle-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--nrb-border);
            background: white;
            display: none;
            place-items: center;
            color: var(--nrb-dark);
            cursor: pointer;
        }

        .topbar-search {
            width: min(440px, 42vw);
            background: #f8fafc;
            border: 1px solid var(--nrb-border);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            color: var(--nrb-muted);
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .topbar-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            color: var(--nrb-slate);
            font-size: 0.95rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-action {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid var(--nrb-border);
            display: grid;
            place-items: center;
            color: var(--nrb-slate);
        }

        .header-action .badge {
            position: absolute;
            top: -4px;
            right: -2px;
            border-radius: 999px;
            font-size: 0.62rem;
            padding: 0.2rem 0.45rem;
            background: var(--nrb-danger);
            color: white;
        }

        .profile-menu-wrap {
            position: relative;
        }

        .profile-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #f8fafc;
            border: 1px solid var(--nrb-border);
            border-radius: 14px;
            padding: 0.5rem 0.8rem 0.5rem 0.55rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .profile-pill:hover {
            border-color: rgba(0,74,173,0.25);
            box-shadow: 0 8px 18px rgba(15,23,42,0.06);
        }

        .profile-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark));
            color: white;
            display: grid;
            place-items: center;
            font-size: 0.8rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .profile-meta small {
            color: var(--nrb-muted);
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 0.6rem);
            right: 0;
            min-width: 230px;
            background: white;
            border: 1px solid var(--nrb-border);
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(15,23,42,0.12);
            overflow: hidden;
            display: none;
            z-index: 60;
        }

        .profile-menu-wrap.open .profile-dropdown {
            display: grid;
        }

        .profile-dropdown a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.82rem 1rem;
            color: var(--nrb-slate);
            text-decoration: none;
            border-bottom: 1px solid rgba(15,23,42,0.05);
            font-size: 0.92rem;
            font-weight: 600;
            background: white;
        }

        .profile-dropdown a:last-child {
            border-bottom: none;
        }

        .profile-dropdown a:hover {
            background: #f8fafc;
            color: var(--nrb-primary);
            text-decoration: none;
        }

        .profile-dropdown a i {
            width: 16px;
            text-align: center;
            color: inherit;
        }

        .content {
            padding: 1.6rem 2rem 2.4rem;
        }

        .section-heading {
            margin: 0 0 1rem;
            font-weight: 700;
            color: var(--nrb-dark);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: var(--nrb-card);
            border: 1px solid var(--nrb-border);
            border-radius: 18px;
            padding: 1rem 1.1rem;
            box-shadow: 0 10px 20px rgba(15,23,42,0.04);
        }

        .summary-card .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .summary-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            color: white;
            background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark));
        }

        .summary-card .tag {
            font-size: 0.72rem;
            background: var(--nrb-green-soft);
            color: var(--nrb-success);
            padding: 0.37rem 0.6rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .summary-card h3 {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--nrb-muted);
            margin: 0 0 0.7rem;
        }

        .summary-card .value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--nrb-dark);
            line-height: 1;
            margin: 0;
        }

        .summary-card .trend {
            font-size: 0.82rem;
            color: var(--nrb-success);
            margin-top: 0.5rem;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .panel {
            background: var(--nrb-card);
            border: 1px solid var(--nrb-border);
            border-radius: 20px;
            padding: 1.1rem 1.2rem;
            box-shadow: 0 10px 20px rgba(15,23,42,0.03);
        }

        .panel .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .panel h4 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--nrb-dark);
        }

        .badge-soft {
            background: var(--nrb-blue-soft);
            color: var(--nrb-primary);
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .chart-wrap {
            position: relative;
            height: 320px;
        }

        .district-list {
            display: grid;
            gap: 0.8rem;
        }

        .district-row {
            display: grid;
            grid-template-columns: 90px 1fr 50px;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.92rem;
            color: var(--nrb-slate);
        }

        .district-bar {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: #edf2f7;
            overflow: hidden;
        }

        .district-fill {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--nrb-primary), #4da3ff);
        }

        .workflow-list {
            display: grid;
            gap: 0.75rem;
        }

        .workflow-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 0.9rem;
            border: 1px solid var(--nrb-border);
            border-radius: 12px;
            background: #f9fafb;
        }

        .workflow-item strong {
            display: block;
            font-size: 1.05rem;
            color: var(--nrb-dark);
        }

        .workflow-item span {
            color: var(--nrb-muted);
            font-size: 0.82rem;
        }

        .workflow-item .count {
            font-weight: 800;
            color: var(--nrb-dark);
            font-size: 1.1rem;
        }

        .btn-primary-nrb {
            background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark));
            border: none;
            color: white;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 700;
        }

        .two-col {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.8rem 0.6rem;
            text-align: left;
            border-bottom: 1px solid var(--nrb-border);
        }

        th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--nrb-muted);
            font-weight: 700;
        }

        td {
            font-size: 0.9rem;
            color: var(--nrb-slate);
        }

        .status-chip {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.35rem 0.6rem;
            border-radius: 999px;
        }

        .status-approved { background: rgba(40,167,69,0.12); color: var(--nrb-success); }
        .status-pending { background: rgba(255,193,7,0.12); color: #9a6700; }
        .status-review { background: rgba(0,74,173,0.10); color: var(--nrb-primary); }
        .status-printed { background: rgba(17,24,39,0.08); color: var(--nrb-slate); }

        .activity-list {
            display: grid;
            gap: 0.7rem;
            margin-top: 0.9rem;
        }

        .activity-item {
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
            padding: 0.7rem 0.8rem;
            border-left: 3px solid var(--nrb-primary);
            background: rgba(0,74,173,0.03);
            border-radius: 10px;
        }

        .activity-time {
            min-width: 42px;
            font-weight: 700;
            color: var(--nrb-primary);
            font-size: 0.82rem;
        }

        .activity-text {
            color: var(--nrb-slate);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .health-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.8rem;
            margin-top: 0.75rem;
        }

        .health-box {
            border: 1px solid var(--nrb-border);
            border-radius: 14px;
            padding: 0.9rem;
            background: #fafafa;
        }

        .health-box .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.45rem;
            background: var(--nrb-success);
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.12);
        }

        @media (max-width: 1200px) {
            .summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .main-grid, .two-col { grid-template-columns: 1fr; }
        }

        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 85%;
                max-width: 300px;
                height: 100vh;
                box-shadow: 0 25px 60px rgba(15,23,42,0.22);
                transition: left 0.25s ease;
                z-index: 100;
            }

            .sidebar.open {
                left: 0;
            }

            .main { width: 100%; margin-left: 0; }
            .app-shell { display: block; }
            .topbar { padding: 0 1rem; }
            .topbar-search { display: none; }
            .content { padding: 1rem; }
            .toggle-btn { display: grid; }
        }

        @media (max-width: 560px) {
            .summary-grid { grid-template-columns: 1fr; }
            .health-grid { grid-template-columns: 1fr 1fr; }
            .topbar { flex-wrap: wrap; height: auto; padding: 0.8rem 1rem; }
            .topbar-right { width: 100%; justify-content: flex-end; }
            .profile-pill { padding-right: 0.6rem; }
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
                    <span>NRB Dashboard</span>
                </div>

                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search citizens, applications or officers...">
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
                                <small><?= e($role ?? 'Administrator') ?></small>
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
                <div class="section-heading">Enterprise Overview</div>

                <div class="summary-grid">
                    <?php
                    $summaryMeta = [
                        'citizens' => ['label' => 'Citizens', 'icon' => 'fa-user', 'tag' => 'Live'],
                        'national_ids' => ['label' => 'National IDs', 'icon' => 'fa-id-card', 'tag' => 'Issued'],
                        'births' => ['label' => 'Births', 'icon' => 'fa-baby', 'tag' => 'Today'],
                        'deaths' => ['label' => 'Deaths', 'icon' => 'fa-skull', 'tag' => 'Recorded'],
                        'pending_approvals' => ['label' => 'Pending', 'icon' => 'fa-clock', 'tag' => 'Queue'],
                        'printed_cards' => ['label' => 'Printed', 'icon' => 'fa-print', 'tag' => 'Cards'],
                        'today_registrations' => ['label' => 'Registrations', 'icon' => 'fa-user-plus', 'tag' => 'Today'],
                        'users_online' => ['label' => 'Online Users', 'icon' => 'fa-users', 'tag' => 'Active'],
                    ];
                    ?>

                    <?php foreach ($stats as $key => $value): ?>
                        <?php if (!isset($summaryMeta[$key])) continue; ?>
                        <div class="summary-card">
                            <div class="top">
                                <div class="summary-icon"><i class="fa-solid <?= e($summaryMeta[$key]['icon']) ?>"></i></div>
                                <span class="tag"><?= e($summaryMeta[$key]['tag']) ?></span>
                            </div>
                            <h3><?= e($summaryMeta[$key]['label']) ?></h3>
                            <p class="value"><?= e((string) $value) ?></p>
                            <div class="trend"><i class="fa-solid fa-arrow-trend-up"></i> Stable performance</div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="main-grid">
                    <div class="panel">
                        <div class="panel-head">
                            <h4>Citizen Registration Statistics</h4>
                            <span class="badge-soft">12-month trend</span>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="registrationChart"></canvas>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-head">
                            <h4>Approval Workflow</h4>
                            <span class="badge-soft">Operational</span>
                        </div>
                        <div class="workflow-list">
                            <div class="workflow-item">
                                <div>
                                    <span>Pending Approval</span>
                                    <strong>340</strong>
                                </div>
                                <div class="count">340</div>
                            </div>
                            <div class="workflow-item">
                                <div>
                                    <span>Under Review</span>
                                    <strong>120</strong>
                                </div>
                                <div class="count">120</div>
                            </div>
                            <div class="workflow-item">
                                <div>
                                    <span>Approved</span>
                                    <strong>5,430</strong>
                                </div>
                                <div class="count">5,430</div>
                            </div>
                            <div class="workflow-item">
                                <div>
                                    <span>Rejected</span>
                                    <strong>32</strong>
                                </div>
                                <div class="count">32</div>
                            </div>
                            <button class="btn-primary-nrb mt-2">View Applications</button>
                        </div>
                    </div>
                </div>

                <div class="two-col">
                    <div class="panel">
                        <div class="panel-head">
                            <h4>Recent Registrations</h4>
                            <span class="badge-soft">Latest</span>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>NRN</th>
                                    <th>Name</th>
                                    <th>District</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentRegistrations as $entry): ?>
                                    <tr>
                                        <td><?= e($entry['nrn']) ?></td>
                                        <td><?= e($entry['name']) ?></td>
                                        <td><?= e($entry['district']) ?></td>
                                        <td>
                                            <?php
                                                $statusClass = 'status-approved';
                                                $status = strtolower((string) $entry['status']);
                                                if ($status === 'pending') { $statusClass = 'status-pending'; }
                                                elseif ($status === 'in review') { $statusClass = 'status-review'; }
                                                elseif ($status === 'printed') { $statusClass = 'status-printed'; }
                                            ?>
                                            <span class="status-chip <?= e($statusClass) ?>"><?= e($entry['status']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="panel">
                        <div class="panel-head">
                            <h4>Security Monitor</h4>
                            <span class="badge-soft">Live</span>
                        </div>
                        <div class="district-list">
                            <div class="district-row">
                                <span>Failed logins</span>
                                <div class="district-bar"><div class="district-fill" style="width: 26%;"></div></div>
                                <strong>24</strong>
                            </div>
                            <div class="district-row">
                                <span>Active users</span>
                                <div class="district-bar"><div class="district-fill" style="width: 72%;"></div></div>
                                <strong>356</strong>
                            </div>
                            <div class="district-row">
                                <span>Threats</span>
                                <div class="district-bar"><div class="district-fill" style="width: 18%;"></div></div>
                                <strong>3</strong>
                            </div>
                            <div class="district-row">
                                <span>Last backup</span>
                                <div class="district-bar"><div class="district-fill" style="width: 82%;"></div></div>
                                <strong>2:00</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="two-col">
                    <div class="panel">
                        <div class="panel-head">
                            <h4>Registrations by District</h4>
                            <span class="badge-soft">Coverage</span>
                        </div>
                        <div class="district-list">
                            <?php foreach ($districts as $district): ?>
                                <div class="district-row">
                                    <span><?= e($district['name']) ?></span>
                                    <div class="district-bar">
                                        <div class="district-fill" style="width: <?= e((string) $district['value']) ?>%;"></div>
                                    </div>
                                    <strong><?= e((string) $district['count']) ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-head">
                            <h4>Recent Activity</h4>
                            <span class="badge-soft">Audit</span>
                        </div>
                        <div class="activity-list">
                            <?php foreach ($activity as $item): ?>
                                <div class="activity-item">
                                    <div class="activity-time"><?= e($item['time']) ?></div>
                                    <div class="activity-text"><?= e($item['text']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <h4>System Health</h4>
                        <span class="badge-soft">All services online</span>
                    </div>
                    <div class="health-grid">
                        <div class="health-box"><span class="dot"></span>Database <strong>Online</strong></div>
                        <div class="health-box"><span class="dot"></span>Storage <strong>Normal</strong></div>
                        <div class="health-box"><span class="dot"></span>Backup <strong>Completed</strong></div>
                        <div class="health-box"><span class="dot"></span>Server <strong>Running</strong></div>
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

        const chartLabels = <?= json_encode($chartLabels ?? []) ?>;
        const chartData = <?= json_encode($chartData ?? []) ?>;

        const ctx = document.getElementById('registrationChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Registrations',
                            data: chartData.registrations || [],
                            borderColor: '#004aad',
                            backgroundColor: 'rgba(0,74,173,0.08)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 0,
                        },
                        {
                            label: 'Births',
                            data: chartData.births || [],
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40,167,69,0.08)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3,
                            pointRadius: 0,
                        },
                        {
                            label: 'ID Issuance',
                            data: chartData.ids || [],
                            borderColor: '#2a3a24',
                            backgroundColor: 'rgba(42,58,36,0.08)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3,
                            pointRadius: 0,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'top' } },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: { color: 'rgba(15, 23, 42, 0.07)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>

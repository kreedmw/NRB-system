<?php

declare(strict_types=1);

$user = $user ?? ['full_name' => 'Card Printing Officer', 'role' => 'Card Printing Officer'];
$stats = $stats ?? ['pending' => 0, 'printed_today' => 0, 'failed' => 0, 'quality_check' => 0, 'dispatched' => 0, 'cards_waiting' => 0];
$queueItems = $queueItems ?? [];
$history = $history ?? [];
$auditTrail = $auditTrail ?? [];
$printerInfo = $printerInfo ?? ['name' => 'HID Fargo HDP5000', 'status' => 'ONLINE', 'ribbon' => '75%', 'cards_remaining' => 0, 'last_maintenance' => 'N/A'];
$batches = $batches ?? [];
$statusOptions = $statusOptions ?? ['Pending', 'Approved', 'Queued', 'Printing', 'Printed', 'Quality Check', 'Rejected', 'Dispatched', 'Collected'];

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 3;
$totalRows = count($queueItems);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
$page = min($page, $totalPages);
$startIndex = ($page - 1) * $perPage;
$paginatedQueueItems = array_slice($queueItems, $startIndex, $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'National ID Card Printing Queue') ?></title>
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
        body { margin: 0; background: var(--nrb-bg); color: var(--nrb-slate); font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
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
        .page-hero { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        .eyebrow { display: inline-block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--nrb-primary); background: rgba(0,74,173,0.08); padding: 0.35rem 0.6rem; border-radius: 999px; }
        .page-hero h1 { margin: 0.5rem 0 0.2rem; font-size: 1.9rem; font-weight: 800; color: var(--nrb-dark); }
        .page-hero p { margin: 0; color: var(--nrb-muted); }
        .muted { color: var(--nrb-muted); }
        .kpis { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
        .kpi { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 16px; padding: 1rem 1.1rem; box-shadow: 0 10px 20px rgba(15,23,42,0.03); }
        .kpi .label { display: flex; justify-content: space-between; align-items: center; color: var(--nrb-muted); font-size: 0.8rem; letter-spacing: 0.04em; text-transform: uppercase; }
        .kpi .value { margin-top: 0.7rem; font-size: 2rem; font-weight: 800; color: var(--nrb-dark); }
        .kpi small { color: var(--nrb-muted); }
        .toolbar { display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem; padding: 1rem; background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 16px; }
        .toolbar .field { flex: 1 1 180px; display: grid; gap: 0.35rem; }
        .toolbar label { font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--nrb-muted); }
        .toolbar input, .toolbar select { width: 100%; border: 1px solid var(--nrb-border); border-radius: 10px; background: #f9fafc; padding: 0.7rem 0.8rem; }
        .workspace { display: grid; grid-template-columns: minmax(0,1.7fr) 320px; gap: 1.2rem; }
        .panel { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 18px; box-shadow: 0 10px 20px rgba(15,23,42,0.03); }
        .table-panel { overflow: hidden; }
        .panel-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.2rem; border-bottom: 1px solid var(--nrb-border); }
        .panel-header h3 { margin: 0; font-size: 1.05rem; font-weight: 800; }
        .panel-header .header-actions { display: flex; gap: 0.5rem; }
        .btn-blue { background: var(--nrb-primary); color: white; border: none; border-radius: 10px; padding: 0.65rem 0.9rem; font-weight: 700; }
        .btn-soft { background: #eef5ff; color: var(--nrb-primary); border: 1px solid rgba(0,74,173,0.18); border-radius: 10px; padding: 0.65rem 0.9rem; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { padding: 0.42rem 0.55rem; border-bottom: 1px solid rgba(15,23,42,0.05); vertical-align: middle; line-height: 1.2; }
        th { background: #f8fafc; color: var(--nrb-slate); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; }
        td { font-size: 0.8rem; }
        tbody tr { height: 52px; }
        .photo-badge { width: 30px; height: 30px; border-radius: 50%; display: grid; place-items: center; font-size: 0.72rem; font-weight: 700; color: var(--nrb-primary); background: rgba(0,74,173,0.08); }
        .badge-status { display: inline-flex; align-items: center; border-radius: 999px; padding: 0.38rem 0.7rem; font-size: 0.72rem; font-weight: 700; }
        .status-pending { background: rgba(255,193,7,0.12); color: #8a6400; }
        .status-approved { background: rgba(40,167,69,0.12); color: #0f7d35; }
        .status-printing { background: rgba(0,74,173,0.08); color: var(--nrb-primary); }
        .status-queued { background: rgba(23,53,45,0.08); color: #17352d; }
        .status-quality { background: rgba(220,53,69,0.08); color: var(--nrb-danger); }
        .status-dispatched { background: rgba(13,110,253,0.08); color: #0d6efd; }
        .status-collected { background: rgba(25,135,84,0.08); color: #198754; }
        .actions { display: flex; gap: 0.4rem; flex-wrap: wrap; }
        .tiny-btn { padding: 0.35rem 0.5rem; border-radius: 8px; border: 1px solid var(--nrb-border); background: white; font-size: 0.72rem; font-weight: 700; }
        .side-stack { display: grid; gap: 1rem; }
        .printer-box { padding: 1.1rem; }
        .printer-box .meta { display: grid; gap: 0.6rem; }
        .vertical-list { display: grid; gap: 0.6rem; }
        .mini-item { display: flex; justify-content: space-between; gap: 0.7rem; padding: 0.6rem 0.75rem; border: 1px solid var(--nrb-border); border-radius: 10px; background: #f9fafc; }
        .btn-block { width: 100%; margin-top: 0.5rem; }
        .history-table th, .history-table td { font-size: 0.8rem; }
        .audit-list { display: grid; gap: 0.7rem; }
        .audit-item { padding: 0.85rem; border: 1px solid var(--nrb-border); border-radius: 12px; background: #f8fafc; }
        .audit-item strong { display: block; margin-bottom: 0.2rem; }
        .pill { display: inline-flex; padding: 0.28rem 0.55rem; border-radius: 999px; background: rgba(0,74,173,0.08); color: var(--nrb-primary); font-size: 0.7rem; font-weight: 700; }
        @media (max-width: 1100px) { .kpis { grid-template-columns: repeat(2, minmax(0,1fr)); } .workspace { grid-template-columns: 1fr; } }
        @media (max-width: 900px) { .sidebar { position: static; width: 100%; } .main { margin-left: 0; width: 100%; } .app-shell { display: block; } .topbar { padding: 0 1rem; } .topbar-search { display: none; } .content { padding: 1rem; } .page-hero { display: block; } .kpis { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <main class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="toggle-btn" type="button"><i class="fa-solid fa-bars"></i></button>
                    <span><?= e($pageTitle ?? 'National ID Card Printing Queue') ?></span>
                </div>
                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search card by ID or name...">
                </div>
                <div class="topbar-right">
                    <div class="header-action"><i class="fa-solid fa-bell"></i><span class="badge">5</span></div>
                    <div class="header-action"><i class="fa-solid fa-print"></i></div>
                    <div class="profile-menu-wrap">
                        <div class="profile-pill" tabindex="0">
                            <div class="profile-avatar"><?= e(substr((string) ($user['full_name'] ?? 'AO'), 0, 2)) ?></div>
                            <div class="profile-meta">
                                <div style="font-weight:700; font-size:0.9rem; color:var(--nrb-slate);">
                                    <?= e((string) ($user['full_name'] ?? 'Card Printing Officer')) ?>
                                </div>
                                <small><?= e((string) ($role ?? $user['role'] ?? 'Card Printing Officer')) ?></small>
                            </div>
                        </div>
                        <div class="profile-dropdown">
                            <a href="<?= e(route_url('/profile')) ?>"><i class="fa-solid fa-user"></i> My Profile</a>
                            <a href="<?= e(route_url('/profile/password')) ?>"><i class="fa-solid fa-key"></i> Change Password</a>
                            <a href="<?= e(route_url('/logout')) ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="page-hero">
                    <div>
                        <span class="eyebrow">Card Production</span>
                        <h1><?= e($pageTitle ?? 'National ID Card Printing Queue') ?></h1>
                        <p><?= e($subtitle ?? 'Track approved registrations through printing, verification, dispatch and collection.') ?></p>
                    </div>
                    <div class="header-actions">
                        <button class="btn-soft" type="button"><i class="fa-solid fa-plus"></i> Create Print Batch</button>
                        <button class="btn-blue" type="button"><i class="fa-solid fa-print"></i> Print Selected</button>
                    </div>
                </div>

                <div class="panel" style="margin-bottom:1.2rem; padding:1rem 1.2rem;">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">Current user</div>
                            <strong><?= e((string) ($user['full_name'] ?? 'Card Printing Officer')) ?></strong>
                            <span class="pill" style="margin-left:0.5rem;"><?= e((string) ($role ?? $user['role'] ?? 'Card Printing Officer')) ?></span>
                        </div>
                        <div>
                            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">Printing centre</div>
                            <strong><?= e((string) ($printingCentre ?? 'Lilongwe Central')) ?></strong>
                        </div>
                        <div>
                            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">Printer</div>
                            <strong><?= e((string) ($printer ?? 'HID Fargo HDP5000')) ?></strong>
                        </div>
                        <div>
                            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">Status</div>
                            <strong><?= e((string) ($printerStatus ?? 'Online')) ?></strong>
                        </div>
                        <div>
                            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">Date</div>
                            <strong><?= e((string) ($today ?? date('d M Y'))) ?></strong>
                        </div>
                        <div>
                            <div class="muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">Cards waiting</div>
                            <strong><?= e((string) ($stats['cards_waiting'] ?? 0)) ?></strong>
                        </div>
                    </div>
                </div>

                <div class="kpis">
                    <div class="kpi">
                        <div class="label"><span>Pending Print</span><i class="fa-solid fa-clock"></i></div>
                        <div class="value"><?= e((string) ($stats['pending'] ?? 0)) ?></div>
                        <small>Cards waiting to be printed</small>
                    </div>
                    <div class="kpi">
                        <div class="label"><span>Printed Today</span><i class="fa-solid fa-check"></i></div>
                        <div class="value"><?= e((string) ($stats['printed_today'] ?? 0)) ?></div>
                        <small>Cards completed today</small>
                    </div>
                    <div class="kpi">
                        <div class="label"><span>Failed</span><i class="fa-solid fa-xmark"></i></div>
                        <div class="value"><?= e((string) ($stats['failed'] ?? 0)) ?></div>
                        <small>Rejected print attempts</small>
                    </div>
                    <div class="kpi">
                        <div class="label"><span>Quality Check</span><i class="fa-solid fa-magnifying-glass"></i></div>
                        <div class="value"><?= e((string) ($stats['quality_check'] ?? 0)) ?></div>
                        <small>Awaiting verification</small>
                    </div>
                    <div class="kpi">
                        <div class="label"><span>Dispatched</span><i class="fa-solid fa-truck-fast"></i></div>
                        <div class="value"><?= e((string) ($stats['dispatched'] ?? 0)) ?></div>
                        <small>Cards in centre network</small>
                    </div>
                </div>

                <div class="toolbar">
                    <div class="field">
                        <label>Search</label>
                        <input type="text" placeholder="National ID / Application / Citizen Name / Queue No">
                    </div>
                    <div class="field">
                        <label>Filter by Status</label>
                        <select>
                            <option>All</option>
                            <?php foreach ($statusOptions as $status): ?>
                                <option><?= e($status) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Filter by Date</label>
                        <select>
                            <option>Today</option>
                            <option>Yesterday</option>
                            <option>Last 7 days</option>
                            <option>Custom range</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Filter by Centre</label>
                        <select>
                            <option>All centres</option>
                            <option>Lilongwe Central</option>
                            <option>Blantyre City</option>
                            <option>Mzimba North</option>
                        </select>
                    </div>
                </div>

                <div class="workspace">
                    <div class="panel table-panel">
                        <div class="panel-header">
                            <h3>Printing Queue</h3>
                            <div class="header-actions">
                                <button class="btn-soft" type="button">Export</button>
                                <button class="btn-blue" type="button">Batch Actions</button>
                            </div>
                        </div>

                        <div style="overflow-x:auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox"></th>
                                        <th>Queue No</th>
                                        <th>Photo</th>
                                        <th>National ID</th>
                                        <th>Application No</th>
                                        <th>Registration Ref</th>
                                        <th>Full Name</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>District</th>
                                        <th>Registration Centre</th>
                                        <th>Collection Centre</th>
                                        <th>Date Approved</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($paginatedQueueItems as $item): ?>
                                        <?php
                                            $status = trim((string) ($item['status'] ?? 'Pending'));
                                            $statusClass = 'status-pending';
                                            if (strtolower($status) === 'approved') { $statusClass = 'status-approved'; }
                                            elseif (strtolower($status) === 'printing') { $statusClass = 'status-printing'; }
                                            elseif (strtolower($status) === 'queued') { $statusClass = 'status-queued'; }
                                            elseif (strtolower($status) === 'quality check') { $statusClass = 'status-quality'; }
                                            elseif (strtolower($status) === 'dispatched') { $statusClass = 'status-dispatched'; }
                                            elseif (strtolower($status) === 'collected') { $statusClass = 'status-collected'; }
                                        ?>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td><strong><?= e((string) ($item['queue_no'] ?? '')) ?></strong></td>
                                            <td><div class="photo-badge"><?= e((string) ($item['photo'] ?? 'NA')) ?></div></td>
                                            <td><?= e((string) ($item['national_id'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['application_no'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['registration_ref'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['full_name'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['gender'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['dob'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['district'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['registration_centre'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['collection_centre'] ?? '')) ?></td>
                                            <td><?= e((string) ($item['date_approved'] ?? '')) ?></td>
                                            <td><span class="badge-status <?= e($statusClass) ?>"><?= e($status) ?></span></td>
                                            <td>
                                                <div class="actions">
                                                    <button class="tiny-btn" type="button">View</button>
                                                    <button class="tiny-btn" type="button">Preview</button>
                                                    <button class="tiny-btn" type="button">Print</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; padding:0.9rem 1rem 1rem; border-top:1px solid rgba(15,23,42,0.06);">
                            <div class="muted" style="font-size:0.8rem;">
                                Showing <?= e((string) (min($totalRows, $startIndex + 1))) ?>-<?= e((string) (min($totalRows, $startIndex + count($paginatedQueueItems)))) ?> of <?= e((string) $totalRows) ?> records
                            </div>
                            <div class="actions" style="align-items:center;">
                                <?php if ($page > 1): ?>
                                    <a class="tiny-btn" href="<?= e(route_url('/printing/queue?page=' . ($page - 1))) ?>">Prev</a>
                                <?php endif; ?>
                                <span class="pill">Page <?= e((string) $page) ?> / <?= e((string) $totalPages) ?></span>
                                <?php if ($page < $totalPages): ?>
                                    <a class="tiny-btn" href="<?= e(route_url('/printing/queue?page=' . ($page + 1))) ?>">Next</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <aside class="side-stack">
                        <div class="panel printer-box">
                            <div class="panel-header" style="padding:0 0 1rem; border-bottom:1px solid var(--nrb-border); margin-bottom:1rem;">
                                <h3>Printer Information</h3>
                            </div>
                            <div class="meta">
                                <div class="mini-item"><span>Printer</span><strong><?= e((string) ($printerInfo['name'] ?? 'HID Fargo HDP5000')) ?></strong></div>
                                <div class="mini-item"><span>Status</span><strong><?= e((string) ($printerInfo['status'] ?? 'ONLINE')) ?></strong></div>
                                <div class="mini-item"><span>Ribbon</span><strong><?= e((string) ($printerInfo['ribbon'] ?? '75%')) ?></strong></div>
                                <div class="mini-item"><span>Cards Remaining</span><strong><?= e((string) ($printerInfo['cards_remaining'] ?? 3500)) ?></strong></div>
                                <div class="mini-item"><span>Last Maintenance</span><strong><?= e((string) ($printerInfo['last_maintenance'] ?? '20/10/2026')) ?></strong></div>
                                <div class="vertical-list" style="margin-top:0.5rem;">
                                    <button class="btn-soft btn-block" type="button">Test Print</button>
                                    <button class="btn-soft btn-block" type="button">Refresh Status</button>
                                    <button class="btn-soft btn-block" type="button">Pause Printer</button>
                                    <button class="btn-soft btn-block" type="button">Resume Printer</button>
                                </div>
                            </div>
                        </div>

                        <div class="panel printer-box">
                            <div class="panel-header" style="padding:0 0 1rem; border-bottom:1px solid var(--nrb-border); margin-bottom:1rem;">
                                <h3>Batch Printing</h3>
                            </div>
                            <?php foreach ($batches as $batch): ?>
                                <div class="mini-item" style="margin-bottom: 0.65rem;">
                                    <div>
                                        <strong><?= e((string) ($batch['id'] ?? '')) ?></strong><br>
                                        <small class="muted"><?= e((string) ($batch['cards'] ?? 0)) ?> cards</small>
                                    </div>
                                    <span class="pill"><?= e((string) ($batch['status'] ?? 'Queued')) ?></span>
                                </div>
                            <?php endforeach; ?>
                            <button class="btn-blue btn-block" type="button">Create Print Batch</button>
                        </div>
                    </aside>
                </div>

                <div class="panel" style="margin-top:1.2rem;">
                    <div class="panel-header">
                        <h3>Quality Assurance Queue</h3>
                        <div class="header-actions">
                            <button class="btn-soft" type="button">Approve</button>
                            <button class="btn-soft" type="button">Reject</button>
                        </div>
                    </div>
                    <div style="padding:1rem 1.2rem; display:grid; gap:0.8rem;">
                        <div class="mini-item"><span>Printed cards awaiting inspection</span><strong>85</strong></div>
                        <div class="mini-item"><span>Approved for dispatch</span><strong>62</strong></div>
                        <div class="mini-item"><span>Returned for reprint</span><strong>12</strong></div>
                    </div>
                </div>

                <div class="panel" style="margin-top:1.2rem;">
                    <div class="panel-header">
                        <h3>Printing History</h3>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Card ID</th>
                                    <th>Citizen</th>
                                    <th>Printed By</th>
                                    <th>Printer</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $entry): ?>
                                    <tr>
                                        <td><?= e((string) ($entry['card_id'] ?? '')) ?></td>
                                        <td><?= e((string) ($entry['citizen'] ?? '')) ?></td>
                                        <td><?= e((string) ($entry['printed_by'] ?? '')) ?></td>
                                        <td><?= e((string) ($entry['printer'] ?? '')) ?></td>
                                        <td><?= e((string) ($entry['date'] ?? '')) ?></td>
                                        <td><?= e((string) ($entry['time'] ?? '')) ?></td>
                                        <td><span class="pill"><?= e((string) ($entry['result'] ?? '')) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel" style="margin-top:1.2rem;">
                    <div class="panel-header">
                        <h3>Audit Trail</h3>
                    </div>
                    <div style="padding:1rem 1.2rem;">
                        <div class="audit-list">
                            <?php foreach ($auditTrail as $audit): ?>
                                <div class="audit-item">
                                    <div class="muted" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">
                                        <?= e((string) ($audit['time'] ?? '')) ?>
                                    </div>
                                    <strong><?= e((string) ($audit['actor'] ?? '')) ?></strong>
                                    <div><?= e((string) ($audit['action'] ?? '')) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profileMenu = document.querySelector('.profile-menu-wrap');
            const profilePill = document.querySelector('.profile-pill');
            if (profilePill && profileMenu) {
                profilePill.addEventListener('click', function () {
                    profileMenu.classList.toggle('open');
                });
                document.addEventListener('click', function (event) {
                    if (!profileMenu.contains(event.target)) {
                        profileMenu.classList.remove('open');
                    }
                });
            }

            const sidebarGroups = document.querySelectorAll('.menu-group');
            sidebarGroups.forEach(function (group) {
                const button = group.querySelector('.menu-button');
                if (!button) return;
                button.addEventListener('click', function () {
                    group.classList.toggle('open');
                });
            });
        });
    </script>
</body>
</html>

<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Super Administrator Dashboard') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --nrb-dark:#2a3a24; --nrb-primary:#004aad; --nrb-bg:#f5f7fa; --nrb-card:#ffffff; --nrb-border:rgba(15,23,42,.08); --nrb-slate:#1f2937; --nrb-muted:#64748b; --nrb-success:#28a745; --nrb-warning:#ffc107; --nrb-danger:#dc3545; --nrb-sidebar:#11251f; --nrb-sidebar-alt:#17352d; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--nrb-bg); color:var(--nrb-slate); font-family:"Segoe UI",sans-serif; }
        .app-shell { min-height:100vh; display:flex; }
        .sidebar { width:260px; background:linear-gradient(180deg, var(--nrb-sidebar) 0%, var(--nrb-sidebar-alt) 100%); color:white; position:fixed; top:0; left:0; bottom:0; overflow-y:auto; }
        .main { margin-left:260px; width:calc(100% - 260px); }
        .topbar { height:74px; background:rgba(255,255,255,.92); border-bottom:1px solid var(--nrb-border); display:flex; align-items:center; justify-content:space-between; padding:0 2rem; }
        .content { padding:1.6rem 2rem; }
        .summary-grid { display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:1rem; }
        .summary-card, .panel { background:var(--nrb-card); border:1px solid var(--nrb-border); border-radius:18px; box-shadow:0 10px 20px rgba(15,23,42,.03); }
        .summary-card { padding:1rem; }
        .section-heading { margin:0 0 1rem; font-weight:800; font-size:1.2rem; }
        .tag { background: rgba(0,74,173,.08); color:var(--nrb-primary); padding:.3rem .6rem; border-radius:999px; font-size:.7rem; font-weight:700; }
        .table { background:white; }
        .table th { font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--nrb-muted); }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
        <main class="main">
            <div class="topbar">
                <div class="topbar-left"><span>Super Administrator Dashboard</span></div>
                <div class="topbar-right">
                    <div class="profile-pill">
                        <div class="profile-avatar"><?= e(substr(($user['full_name'] ?? 'Admin'), 0, 2)) ?></div>
                        <div class="profile-meta">
                            <div style="font-weight:700; font-size:0.9rem; color:var(--nrb-slate);">
                                <?= e($user['full_name'] ?? 'Admin User') ?>
                            </div>
                            <small><?= e($role ?? 'Super Administrator') ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="section-heading">Enterprise Overview</div>
                <div class="summary-grid">
                    <?php foreach ($stats as $key => $value): ?>
                        <?php if (!in_array($key, ['citizens','national_ids','births','deaths','users','pending_approvals','printed_cards','users_online'], true)) continue; ?>
                        <div class="summary-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <i class="fa-solid fa-chart-line text-primary"></i>
                                <span class="tag">Live</span>
                            </div>
                            <h4 class="mb-1"><?= e($value) ?></h4>
                            <div class="text-muted"><?= e(str_replace('_', ' ', ucfirst((string) $key))) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

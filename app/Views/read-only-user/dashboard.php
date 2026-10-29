<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Read Only User Dashboard') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { margin:0; background:#f5f7fa; font-family:"Segoe UI",sans-serif; }
        .app-shell { display:flex; min-height:100vh; }
        .main { margin-left:260px; width:calc(100% - 260px); }
        .content { padding:2rem; }
        .card { background:white; border:1px solid rgba(15,23,42,.08); border-radius:16px; padding:1rem; }
        .summary { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:1rem; }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
        <main class="main">
            <div class="content">
                <h2>Read Only User Dashboard</h2>
                <div class="summary mt-3">
                    <div class="card"><h4><?= e($stats['citizens'] ?? 0) ?></h4><small>Citizens</small></div>
                    <div class="card"><h4><?= e($stats['national_ids'] ?? 0) ?></h4><small>National IDs</small></div>
                    <div class="card"><h4><?= e($stats['users_online'] ?? 0) ?></h4><small>System Activity</small></div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

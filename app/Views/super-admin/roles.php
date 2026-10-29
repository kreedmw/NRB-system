<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Super Admin Roles') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { margin:0; background:#f5f7fa; font-family:"Segoe UI",sans-serif; }
        .app-shell { display:flex; min-height:100vh; }
        .main { margin-left:260px; width:calc(100% - 260px); }
        .content { padding:2rem; }
        .panel { background:white; border:1px solid rgba(15,23,42,.08); border-radius:16px; padding:1rem; }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
        <main class="main">
            <div class="content">
                <div class="panel">
                    <h2>Super Administrator - Roles</h2>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($roles ?? []) as $roleItem): ?>
                                <tr>
                                    <td><?= e($roleItem['name'] ?? '') ?></td>
                                    <td><?= e($roleItem['permission_count'] ?? 0) ?></td>
                                    <td><?= e((string) ($roleItem['user_count'] ?? 0)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

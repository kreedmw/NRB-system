<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Citizen Status') ?></title>
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

        body { margin:0; background:var(--nrb-bg); color:var(--nrb-slate); font-family:"Segoe UI",sans-serif; }
        .app-shell { min-height:100vh; display:flex; }
        .sidebar { width:260px; background: linear-gradient(180deg, var(--nrb-sidebar) 0%, var(--nrb-sidebar-alt) 100%); color:white; position:fixed; top:0; left:0; bottom:0; overflow-y:auto; }
        .main { margin-left:260px; width: calc(100% - 260px); }
        .content { padding: 2rem; }
        .panel { background: var(--nrb-card); border:1px solid var(--nrb-border); border-radius:20px; box-shadow:0 10px 20px rgba(15,23,42,0.03); padding:1.5rem; }
        .header-row { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; }
        .page-title { margin:0; font-weight:800; color:var(--nrb-dark); }
        .badge-status { display:inline-block; padding:0.45rem 0.8rem; border-radius:999px; font-size:0.72rem; font-weight:700; background: rgba(0,74,173,0.08); color:var(--nrb-primary); }
        .profile-card { background: linear-gradient(135deg, rgba(0,74,173,0.03), rgba(42,58,36,0.02)); border:1px solid var(--nrb-border); border-radius:18px; padding:1.2rem; }
        .citizen-image { width:170px; height:170px; object-fit:cover; border-radius:16px; border:1px solid var(--nrb-border); background:#f3f4f6; }
        .meta-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; }
        .meta-item { padding:0.85rem 0.9rem; background:#f8fafc; border:1px solid var(--nrb-border); border-radius:12px; }
        .meta-item small { display:block; color:var(--nrb-muted); text-transform:uppercase; letter-spacing:0.05em; }
        .meta-item strong { font-size:0.98rem; }
        .timeline { position:relative; margin-top:1.5rem; }
        .timeline::before { content:""; position:absolute; left:14px; top:0; bottom:0; width:2px; background: rgba(0,74,173,0.12); }
        .timeline-item { position:relative; padding-left:3.2rem; margin-bottom:1.4rem; }
        .timeline-item::before { content:""; position:absolute; left:8px; top:3px; width:14px; height:14px; border-radius:50%; background: var(--nrb-primary); border:3px solid white; box-shadow:0 0 0 2px rgba(0,74,173,0.12); }
        .timeline-item.pending::before { background: var(--nrb-warning); }
        .timeline-item.completed::before { background: var(--nrb-success); }
        .timeline-item .box { background:#f8fafc; border:1px solid var(--nrb-border); border-radius:14px; padding:0.9rem 1rem; }
        .timeline-item .box strong { display:block; margin-bottom:0.25rem; }
        .timeline-item .box small { color:var(--nrb-muted); }
        @media (max-width: 900px) {
            .sidebar { position: static; width:100%; }
            .main { margin-left:0; width:100%; }
            .app-shell { display:block; }
            .content { padding:1rem; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
        <main class="main">
            <div class="content">
                <div class="panel">
                    <div class="header-row">
                        <div>
                            <h1 class="page-title">Citizen Status</h1>
                            <div class="text-muted mt-1">NRN: <?= e($citizen['national_registration_number'] ?? 'N/A') ?></div>
                        </div>
                        <span class="badge-status">Registration Submitted</span>
                    </div>

                    <div class="profile-card mb-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-3 text-center">
                                <?php $photoPath = $photo['file_path'] ?? 'assets/images/citizen.png'; ?>
                                <img src="<?= e(base_url($photoPath)) ?>" alt="Citizen photo" class="citizen-image" onerror="this.src='<?= e(base_url('assets/images/citizen.png')) ?>';">
                            </div>
                            <div class="col-md-9">
                                <h3 class="mb-1"><?= e(($citizen['first_name'] ?? '') . ' ' . ($citizen['middle_name'] ?? '') . ' ' . ($citizen['surname'] ?? '')) ?></h3>
                                <div class="text-muted mb-3"><?= e($citizen['gender'] ?? 'Other') ?> • <?= e($citizen['date_of_birth'] ?? '') ?></div>
                                <div class="meta-grid">
                                    <div class="meta-item">
                                        <small>Nationality</small>
                                        <strong><?= e($citizen['nationality'] ?? 'Malawian') ?></strong>
                                    </div>
                                    <div class="meta-item">
                                        <small>Place of Birth</small>
                                        <strong><?= e($citizen['district_name'] ?? $citizen['place_of_birth'] ?? '-') ?></strong>
                                    </div>
                                    <div class="meta-item">
                                        <small>Marital Status</small>
                                        <strong><?= e($citizen['marital_status'] ?? 'Single') ?></strong>
                                    </div>
                                    <div class="meta-item">
                                        <small>Occupation</small>
                                        <strong><?= e($citizen['occupation'] ?? '-') ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="panel" style="padding:1.1rem; border-radius:16px;">
                                <h5 class="mb-3">Application Timeline</h5>
                                <div class="timeline">
                                    <?php foreach (($stages ?? []) as $stage): ?>
                                        <?php $stateClass = ($stage['status'] ?? 'pending') === 'completed' ? 'completed' : 'pending'; ?>
                                        <div class="timeline-item <?= e($stateClass) ?>">
                                            <div class="box">
                                                <strong><?= e($stage['label'] ?? 'Stage') ?></strong>
                                                <small><?= e($stage['date'] ?? '') ?></small>
                                                <div class="mt-2"><?= e($stage['description'] ?? '') ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="panel" style="padding:1.1rem; border-radius:16px;">
                                <h5 class="mb-3">Biometric Status</h5>
                                <div class="meta-item mb-2">
                                    <small>Passport Photo</small>
                                    <strong><?= !empty($photo) ? 'Captured' : 'Not captured' ?></strong>
                                </div>
                                <div class="meta-item mb-2">
                                    <small>Fingerprints</small>
                                    <strong><?= !empty($fingerprint) ? 'Captured' : 'Not captured' ?></strong>
                                </div>
                                <div class="meta-item">
                                    <small>Submission Status</small>
                                    <strong>Awaiting Preview Approval</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

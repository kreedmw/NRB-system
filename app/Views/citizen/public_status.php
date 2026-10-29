<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Check NID Status') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= e(base_url('assets/css/landing.css')) ?>">
    <style>
        body {
            background: #f4f7fb;
        }

        .status-page {
            min-height: 100vh;
            padding: 2rem 0 4rem;
        }

        .status-shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .status-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 22px;
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.06);
            padding: 2rem;
        }

        .status-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(28, 142, 91, 0.08);
            color: #146c43;
            border-radius: 999px;
            padding: 0.5rem 0.9rem;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .search-form {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1 1 350px;
            min-height: 52px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            padding: 0.9rem 1rem;
            font-size: 1rem;
            background: #f9fbff;
        }

        .search-form button {
            min-width: 180px;
            min-height: 52px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #0b5ed7, #0a3d91);
            color: white;
            font-weight: 700;
            cursor: pointer;
        }

        .result-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .meta-item {
            background: #f7f9fc;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 14px;
            padding: 1rem;
        }

        .meta-item small {
            display: block;
            color: #64748b;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }

        .timeline {
            position: relative;
            margin-top: 1.5rem;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: rgba(11, 94, 215, 0.18);
        }

        .timeline-item {
            position: relative;
            padding-left: 3.25rem;
            margin-bottom: 1.25rem;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: 7px;
            top: 14px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #0b5ed7;
            border: 3px solid #ffffff;
            box-shadow: 0 0 0 3px rgba(11, 94, 215, 0.1);
        }

        .timeline-item.pending::before {
            background: #f4b740;
        }

        .timeline-item.completed::before {
            background: #1e9d73;
        }

        .timeline-box {
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 14px;
            padding: 1rem;
        }

        .timeline-box strong {
            display: block;
            margin-bottom: 0.35rem;
            color: #0f172a;
        }

        .timeline-box small {
            display: block;
            color: #64748b;
            margin-bottom: 0.4rem;
        }

        .status-panel {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            background: linear-gradient(180deg, #fbfcff, #f5f8ff);
            padding: 1.2rem;
            height: 100%;
        }

        .alert {
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #9a1c2d;
            border-radius: 12px;
            padding: 1rem 1.1rem;
            margin-top: 1.5rem;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .result-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a href="<?= e(route_url('/')) ?>" class="brand" aria-label="National Registration Bureau Home">
                <img src="<?= e(base_url('assets/images/logo-mw-67x75.png')) ?>" alt="" style="width:50px;height:50px;object-fit:contain;">
                <span class="brand-name"><span>Citizen Registry</span></span>
            </a>

            <nav class="desktop-nav" aria-label="Main navigation">
                <a href="<?= e(route_url('/')) ?>">Home</a>
                <a href="<?= e(route_url('/check-nid')) ?>">Check NID</a>
                <a href="<?= e(route_url('/login')) ?>">Sign In</a>
            </nav>

            <div class="topbar-actions">
                <a href="<?= e(route_url('/login')) ?>" class="primary-btn">Access Portal</a>
            </div>
        </div>
    </header>

    <main class="status-page">
        <div class="status-shell">
            <div class="status-card">
                <div class="status-header">
                    <div>
                        <small class="section-tag">Citizen services</small>
                        <h1 style="margin:0.35rem 0 0; font-size: clamp(2rem, 4vw, 3rem); color:#0f172a;">Check NID Status</h1>
                    </div>
                    <span class="status-badge"><i class="fa-solid fa-circle-check"></i> Public status lookup</span>
                </div>

                <form method="POST" action="<?= e(route_url('/check-nid')) ?>" class="search-form">
                    <input type="text" name="nid_number" value="<?= e($query ?? '') ?>" placeholder="Enter NID number" aria-label="NID number" required>
                    <button type="submit">Check Status</button>
                </form>

                <?php if (($query ?? '') !== '' && empty($citizen)): ?>
                    <div class="alert">
                        No record was found for this NID number. Please confirm the number and try again.
                    </div>
                <?php endif; ?>

                <?php if (!empty($citizen)): ?>
                    <div class="result-grid">
                        <div>
                            <div class="status-panel">
                                <h3 style="margin-top:0; margin-bottom:0.5rem; color:#0f172a;">
                                    <?= e(($citizen['first_name'] ?? '') . ' ' . ($citizen['middle_name'] ?? '') . ' ' . ($citizen['surname'] ?? '')) ?>
                                </h3>
                                <div style="color:#475569; margin-bottom:1rem;">
                                    National Registration Number: <strong><?= e($citizen['national_registration_number'] ?? 'N/A') ?></strong>
                                </div>

                                <div class="meta-grid">
                                    <div class="meta-item">
                                        <small>Date of Birth</small>
                                        <strong><?= e($citizen['date_of_birth'] ?? '-') ?></strong>
                                    </div>
                                    <div class="meta-item">
                                        <small>Gender</small>
                                        <strong><?= e($citizen['gender'] ?? '-') ?></strong>
                                    </div>
                                    <div class="meta-item">
                                        <small>Nationality</small>
                                        <strong><?= e($citizen['nationality'] ?? 'Malawian') ?></strong>
                                    </div>
                                    <div class="meta-item">
                                        <small>Place of Birth</small>
                                        <strong><?= e($citizen['district_name'] ?? $citizen['place_of_birth'] ?? '-') ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="status-panel" style="margin-top:1.5rem;">
                                <h4 style="margin-top:0; margin-bottom:0.5rem; color:#0f172a;">Application Timeline</h4>
                                <div class="timeline">
                                    <?php foreach (($stages ?? []) as $stage): ?>
                                        <?php $statusClass = (($stage['status'] ?? 'pending') === 'completed') ? 'completed' : 'pending'; ?>
                                        <div class="timeline-item <?= e($statusClass) ?>">
                                            <div class="timeline-box">
                                                <strong><?= e($stage['label'] ?? 'Stage') ?></strong>
                                                <small><?= e($stage['date'] ?? '') ?></small>
                                                <div><?= e($stage['description'] ?? '') ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="status-panel">
                                <h4 style="margin-top:0; color:#0f172a;">Application Status</h4>
                                <div class="meta-item" style="margin-bottom:0.9rem;">
                                    <small>Submission</small>
                                    <strong><?= !empty($citizen['registration_date'] ?? false) ? e($citizen['registration_date']) : 'Captured' ?></strong>
                                </div>
                                <div class="meta-item" style="margin-bottom:0.9rem;">
                                    <small>Current Stage</small>
                                    <strong><?= e((string) ($citizen['current_status'] ?? 'Submitted')) ?></strong>
                                </div>
                                <div class="meta-item">
                                    <small>Biometric Capture</small>
                                    <strong><?= !empty($citizen['photo_status']) || !empty($citizen['fingerprint_status']) ? 'Captured' : 'Pending' ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>

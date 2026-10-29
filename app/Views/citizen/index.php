<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Citizen Registry') ?></title>
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
        .sidebar-header { padding:1.25rem 1rem; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; align-items:center; gap:0.8rem; }
        .sidebar-logo { width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.12); display:grid; place-items:center; font-weight:700; }
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
        .topbar { height:74px; background: rgba(255,255,255,0.94); backdrop-filter: blur(10px); border-bottom:1px solid var(--nrb-border); display:flex; align-items:center; justify-content:space-between; padding:0 2rem; position:sticky; top:0; }
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
        .panel { background: var(--nrb-card); border:1px solid var(--nrb-border); border-radius:20px; padding:1.2rem; box-shadow:0 10px 20px rgba(15,23,42,0.03); }
        .panel-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
        .panel-head h4 { margin:0; font-weight:800; color: var(--nrb-dark); }
        .btn-primary-nrb { background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); border:none; color:white; border-radius:12px; padding:0.7rem 1rem; font-weight:700; }
        .registry-toolbar { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1rem; }
        .registry-search-wrap { display:flex; align-items:center; gap:0.75rem; flex:1; max-width:620px; min-width:0; }
        .registry-search { position:relative; flex:1 1 auto; min-width:0; }
        .registry-search input { width:100%; height:42px; border:1px solid rgba(15,23,42,0.12); border-radius:12px; padding:0.75rem 1rem 0.75rem 2.9rem; background:#f8fafc; box-shadow: inset 0 1px 2px rgba(15,23,42,0.03); outline:none; transition: all 0.2s ease; }
        .registry-search input:focus { border-color: rgba(0,74,173,0.6); box-shadow: 0 0 0 4px rgba(0,74,173,0.08); background:#fff; }
        .registry-search i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color: var(--nrb-muted); }
        .registry-search-btn { height:42px; min-width:110px; border:none; background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); color:white; border-radius:12px; padding:0 1.1rem; font-weight:700; flex-shrink:0; }
        .registry-meta { color: var(--nrb-muted); font-size:0.82rem; font-weight:600; }
        .table-actions { display:flex; align-items:center; gap:0.5rem; flex-wrap:nowrap; }
        .table-actions .btn { width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; padding:0; border-radius:10px; }
        .table thead th { font-size:0.76rem; text-transform: uppercase; letter-spacing:0.06em; color: var(--nrb-muted); }
        .status-chip { display:inline-block; padding:0.35rem 0.7rem; border-radius:999px; font-size:0.72rem; font-weight:700; }
        .status-submitted { background: rgba(0,74,173,0.08); color:var(--nrb-primary); }
        .status-approved { background: rgba(40,167,69,0.12); color: var(--nrb-success); }
        .status-pending { background: rgba(255,193,7,0.12); color: #9a6700; }
        .registry-pagination { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-top:1.25rem; flex-wrap:wrap; }
        .pagination-label { color: var(--nrb-muted); font-size:0.82rem; font-weight:700; }
        .pagination { margin:0; }
        .page-link { color: var(--nrb-slate); border-color: rgba(15,23,42,0.08); border-radius:10px; margin:0 0.2rem; }
        .page-item.active .page-link { background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); border-color: transparent; color:white; }
        .page-item.disabled .page-link { color: #94a3b8; pointer-events:none; }
        @media (max-width: 900px) { .sidebar { position: static; width:100%; } .main { margin-left:0; width:100%; } .app-shell { display:block; } .topbar { padding: 0 1rem; } .topbar-search { display:none; } .content { padding:1rem; } }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <main class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="toggle-btn"><i class="fa-solid fa-bars"></i></button>
                    <span>Citizen Registry</span>
                </div>

                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search citizen, NRN or district...">
                </div>

                <div class="topbar-right">
                    <div class="header-action">
                        <i class="fa-solid fa-bell"></i>
                        <span class="badge">12</span>
                    </div>
                    <div class="header-action"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="profile-menu-wrap">
                        <div class="profile-pill" tabindex="0">
                            <div class="profile-avatar"><?= e(substr(($user['full_name'] ?? 'Admin'), 0, 2)) ?></div>
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
                <div class="panel">
                    <div class="panel-head">
                        <h4>Citizen Registry</h4>
                        <a href="<?= e(route_url('/citizen/register')) ?>" class="btn-primary-nrb text-decoration-none">Add Citizen</a>
                    </div>
                    <div class="registry-toolbar justify-content-end">
                        <div class="registry-meta">
                            Showing <?= e((string) count($citizens ?? [])) ?> of <?= e((string) ($totalRecords ?? 0)) ?> records
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-striped table-hover align-middle" style="min-width: 1800px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NRN</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Surname</th>
                                    <th>Gender</th>
                                    <th>Date of Birth</th>
                                    <th>Place of Birth</th>
                                    <th>Nationality</th>
                                    <th>Marital Status</th>
                                    <th>Occupation</th>
                                    <th>Education</th>
                                    <th>Blood Group</th>
                                    <th>Disability</th>
                                    <th>Religion</th>
                                    <th>District</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($citizens)): ?>
                                    <tr>
                                        <td colspan="19" class="text-center text-muted py-4">No citizens found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach (($citizens ?? []) as $citizen): ?>
                                        <tr>
                                            <td><?= e((string) ($citizen['id'] ?? '')) ?></td>
                                            <td><?= e($citizen['national_registration_number'] ?? '') ?></td>
                                            <td><?= e($citizen['first_name'] ?? '') ?></td>
                                            <td><?= e($citizen['middle_name'] ?? '-') ?></td>
                                            <td><?= e($citizen['surname'] ?? '') ?></td>
                                            <td><?= e($citizen['gender'] ?? 'Other') ?></td>
                                            <td><?= e($citizen['date_of_birth'] ?? '') ?></td>
                                            <td><?= e($citizen['place_of_birth'] ?? '-') ?></td>
                                            <td><?= e($citizen['nationality'] ?? 'Malawian') ?></td>
                                            <td><?= e($citizen['marital_status'] ?? 'Single') ?></td>
                                            <td><?= e($citizen['occupation'] ?? '-') ?></td>
                                            <td><?= e($citizen['education_level'] ?? '-') ?></td>
                                            <td><?= e($citizen['blood_group'] ?? '-') ?></td>
                                            <td><?= e($citizen['disability_status'] ?? '-') ?></td>
                                            <td><?= e($citizen['religion'] ?? '-') ?></td>
                                            <td><?= e($citizen['district_name'] ?? $citizen['place_of_birth'] ?? '-') ?></td>
                                            <td>
                                                <?php
                                                    $status = strtolower((string) ($citizen['current_status'] ?? 'Active'));
                                                    $class = $status === 'active' ? 'status-approved' : ($status === 'suspended' ? 'status-pending' : 'status-submitted');
                                                ?>
                                                <span class="status-chip <?= e($class) ?>"><?= e(ucfirst((string) ($citizen['current_status'] ?? 'Active'))) ?></span>
                                            </td>
                                            <td><?= e($citizen['registration_date'] ?? $citizen['created_at'] ?? '') ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="<?= e(route_url('/citizen/status') . '?id=' . (int) ($citizen['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="fa-solid fa-eye"></i></a>
                                                    <a href="#" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (($totalPages ?? 1) > 1): ?>
                        <div class="registry-pagination">
                            <div class="pagination-label">
                                Page <?= e((string) ($currentPage ?? 1)) ?> of <?= e((string) ($totalPages ?? 1)) ?>
                            </div>
                            <nav aria-label="Citizen pagination">
                                <ul class="pagination mb-0">
                                    <li class="page-item <?= ($currentPage ?? 1) <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= e(route_url('/citizen') . '?page=' . max(1, ($currentPage ?? 1) - 1) . ($search !== '' ? '&search=' . urlencode($search) : '')) ?>">Previous</a>
                                    </li>
                                    <?php for ($pageNumber = 1; $pageNumber <= (int) ($totalPages ?? 1); $pageNumber++): ?>
                                        <li class="page-item <?= ($pageNumber === ($currentPage ?? 1)) ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= e(route_url('/citizen') . '?page=' . $pageNumber . ($search !== '' ? '&search=' . urlencode($search) : '')) ?>"><?= e((string) $pageNumber) ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= e(route_url('/citizen') . '?page=' . min((int) ($totalPages ?? 1), ($currentPage ?? 1) + 1) . ($search !== '' ? '&search=' . urlencode($search) : '')) ?>">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
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

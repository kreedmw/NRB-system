<?php

declare(strict_types=1);

$settingsSections = $settingsSections ?? [];
$user = $user ?? ['full_name' => 'Super Administrator'];
$role = $role ?? 'Super Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'General Settings') ?></title>
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
        .page-hero { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.6rem; }
        .eyebrow { display: inline-block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--nrb-primary); background: rgba(0,74,173,0.08); padding: 0.35rem 0.6rem; border-radius: 999px; }
        .page-hero h1 { margin: 0.5rem 0 0.2rem; font-size: 1.9rem; font-weight: 800; color: var(--nrb-dark); }
        .page-hero p { margin: 0; color: var(--nrb-muted); }
        .actions { display: flex; gap: 0.75rem; }
        .primary-btn { background: linear-gradient(135deg, var(--nrb-primary), var(--nrb-dark)); color: white; border: none; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; }
        .secondary-btn { background: #eef5ff; color: var(--nrb-primary); border: 1px solid rgba(0,74,173,0.18); border-radius: 12px; padding: 0.75rem 1rem; font-weight: 700; }

        .settings-layout { display: grid; grid-template-columns: 280px minmax(0,1fr); gap: 1.25rem; }
        .settings-sidebar { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 18px; padding: 1rem; box-shadow: 0 10px 20px rgba(15,23,42,0.03); position: sticky; top: 90px; height: fit-content; }
        .settings-nav { display: grid; gap: 0.45rem; }
        .settings-tab { display: block; width: 100%; text-align: left; padding: 0.8rem 0.9rem; border-radius: 12px; text-decoration: none; color: var(--nrb-slate); font-weight: 600; border: 1px solid transparent; background: transparent; }
        .settings-tab:hover, .settings-tab.active { background: #edf5ff; color: var(--nrb-primary); border-color: rgba(0,74,173,0.08); }
        .settings-main { display: grid; gap: 1.25rem; }
        .settings-card { background: var(--nrb-card); border: 1px solid var(--nrb-border); border-radius: 18px; box-shadow: 0 10px 20px rgba(15,23,42,0.03); overflow: hidden; }
        .settings-header { padding: 1.2rem 1.25rem 0.9rem; border-bottom: 1px solid var(--nrb-border); }
        .settings-header h3 { margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--nrb-dark); }
        .settings-header p { margin: 0.35rem 0 0; color: var(--nrb-muted); font-size: 0.9rem; }
        .settings-form { padding: 1.25rem; display: grid; gap: 1rem; }
        .field-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 1rem; }
        .field { display: grid; gap: 0.45rem; }
        .field label { font-size: 0.78rem; letter-spacing: 0.04em; text-transform: uppercase; color: var(--nrb-muted); font-weight: 700; }
        .field input, .field select, .field textarea {
            width: 100%; border: 1px solid var(--nrb-border); border-radius: 12px; background: #f9fafc; padding: 0.78rem 0.9rem; font-size: 0.95rem; color: var(--nrb-slate); outline: none;
        }
        .field textarea { min-height: 108px; resize: vertical; }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: rgba(0,74,173,0.45); box-shadow: 0 0 0 3px rgba(0,74,173,0.08); }
        .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 0.9rem 1rem; border: 1px solid var(--nrb-border); border-radius: 12px; background: #f8fafc; }
        .toggle-row .text { font-weight: 600; }
        .toggle-switch { position: relative; width: 52px; height: 30px; }
        .toggle-switch input { display: none; }
        .slider { position: absolute; inset: 0; border-radius: 999px; background: rgba(15,23,42,0.15); transition: 0.25s; }
        .slider::before { content: ""; position: absolute; width: 22px; height: 22px; left: 4px; top: 4px; border-radius: 50%; background: white; transition: 0.25s; box-shadow: 0 2px 10px rgba(15,23,42,0.12); }
        .toggle-switch input:checked + .slider { background: rgba(0,74,173,0.8); }
        .toggle-switch input:checked + .slider::before { transform: translateX(22px); }

        @media (max-width: 1000px) {
            .settings-layout { grid-template-columns: 1fr; }
            .settings-sidebar { position: static; }
        }

        @media (max-width: 900px) {
            .sidebar { position: static; width: 100%; }
            .main { margin-left: 0; width: 100%; }
            .app-shell { display: block; }
            .topbar { padding: 0 1rem; }
            .topbar-search { display: none; }
            .content { padding: 1rem; }
            .page-hero { display: block; }
            .actions { margin-top: 1rem; }
            .field-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <main class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="toggle-btn" type="button"><i class="fa-solid fa-bars"></i></button>
                    <span><?= e($pageTitle ?? 'General Settings') ?></span>
                </div>
                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search settings...">
                </div>
                <div class="topbar-right">
                    <div class="header-action"><i class="fa-solid fa-bell"></i><span class="badge">3</span></div>
                    <div class="header-action"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="profile-menu-wrap">
                        <div class="profile-pill" tabindex="0">
                            <div class="profile-avatar"><?= e(substr((string) ($user['full_name'] ?? 'Admin'), 0, 2)) ?></div>
                            <div class="profile-meta">
                                <div style="font-weight:700; font-size:0.9rem; color:var(--nrb-slate);">
                                    <?= e((string) ($user['full_name'] ?? 'Administrator')) ?>
                                </div>
                                <small><?= e((string) ($role ?? 'Super Administrator')) ?></small>
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
                        <span class="eyebrow">Configuration</span>
                        <h1><?= e($pageTitle ?? 'General Settings') ?></h1>
                        <p><?= e($subtitle ?? 'Configure organization-wide registration, operational and governance preferences.') ?></p>
                    </div>
                    <div class="actions">
                        <button class="secondary-btn" type="button"><i class="fa-solid fa-rotate"></i> Reset</button>
                        <button class="primary-btn" type="button"><i class="fa-solid fa-floppy-disk"></i> Save changes</button>
                    </div>
                </div>

                <div class="settings-layout">
                    <aside class="settings-sidebar">
                        <div class="settings-nav">
                            <?php foreach ($settingsSections as $index => $section): ?>
                                <a class="settings-tab <?= $index === 0 ? 'active' : '' ?>" href="#<?= e($section['id'] ?? 'section-' . $index) ?>">
                                    <?= e($section['label'] ?? 'Section') ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </aside>

                    <div class="settings-main">
                        <?php foreach ($settingsSections as $index => $section): ?>
                            <section id="<?= e($section['id'] ?? 'section-' . $index) ?>" class="settings-card">
                                <div class="settings-header">
                                    <h3><?= e($section['label'] ?? 'Section') ?></h3>
                                    <p>Update the operational configuration for this area.</p>
                                </div>
                                <div class="settings-form">
                                    <?php foreach ($section['items'] ?? [] as $field): ?>
                                        <?php
                                            $fieldType = $field['type'] ?? 'text';
                                            $fieldName = strtolower(str_replace([' ', '/', '&', '-'], '_', (string) ($field['label'] ?? 'field')));
                                            $fieldValue = $field['value'] ?? '';
                                        ?>

                                        <?php if (in_array($fieldType, ['text', 'email', 'tel', 'url', 'password', 'number', 'date', 'color'], true)): ?>
                                            <div class="field">
                                                <label for="<?= e($fieldName) ?>"><?= e($field['label'] ?? 'Field') ?></label>
                                                <input id="<?= e($fieldName) ?>" type="<?= e($fieldType) ?>" value="<?= e((string) $fieldValue) ?>">
                                            </div>
                                        <?php elseif ($fieldType === 'select'): ?>
                                            <div class="field">
                                                <label for="<?= e($fieldName) ?>"><?= e($field['label'] ?? 'Field') ?></label>
                                                <select id="<?= e($fieldName) ?>">
                                                    <?php foreach (($field['options'] ?? []) as $option): ?>
                                                        <option value="<?= e((string) $option) ?>" <?= ((string) $option === (string) $fieldValue) ? 'selected' : '' ?>><?= e((string) $option) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php elseif ($fieldType === 'textarea'): ?>
                                            <div class="field">
                                                <label for="<?= e($fieldName) ?>"><?= e($field['label'] ?? 'Field') ?></label>
                                                <textarea id="<?= e($fieldName) ?>"><?= e((string) $fieldValue) ?></textarea>
                                            </div>
                                        <?php elseif ($fieldType === 'file'): ?>
                                            <div class="field">
                                                <label for="<?= e($fieldName) ?>"><?= e($field['label'] ?? 'Field') ?></label>
                                                <input id="<?= e($fieldName) ?>" type="file">
                                            </div>
                                        <?php else: ?>
                                            <div class="field">
                                                <label for="<?= e($fieldName) ?>"><?= e($field['label'] ?? 'Field') ?></label>
                                                <input id="<?= e($fieldName) ?>" type="text" value="<?= e((string) $fieldValue) ?>">
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endforeach; ?>
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

            const tabs = document.querySelectorAll('.settings-tab');
            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    tabs.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    tab.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>

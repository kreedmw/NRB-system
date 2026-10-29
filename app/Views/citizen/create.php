<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'National ID Registration (NR 1)') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --nrb-green: #0d4f3b;
            --nrb-green-dark: #083d30;
            --nrb-green-soft: #edf8f2;
            --nrb-gold: #c89b3c;
            --nrb-blue: #0b5ea8;
            --nrb-bg: #edf3ef;
            --nrb-card: #ffffff;
            --nrb-line: #dfe9e3;
            --nrb-text: #1e2d27;
            --nrb-muted: #5f6c68;
            --nrb-shadow: rgba(15, 47, 38, 0.12);
            --nrb-dark: #2a3a24;
            --nrb-primary: #004aad;
            --nrb-sidebar: #11251f;
            --nrb-sidebar-alt: #17352d;
            --nrb-border: rgba(15,23,42,0.08);
            --nrb-slate: #1f2937;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: linear-gradient(180deg, #f3f7f4 0%, #edf2ee 100%);
            color: var(--nrb-text);
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

        .nav-group { padding: 1rem 0.75rem 0.5rem; }

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

        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.08);
            color: white;
            text-decoration: none;
        }

        .nav-item .left {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-weight: 500;
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

        .menu-button.active { background: rgba(255,255,255,0.08); }
        .menu-button .left { display: flex; align-items: center; gap: 0.7rem; }
        .menu-button .right { transition: transform 0.2s ease; }
        .menu-group.open .menu-button .right { transform: rotate(180deg); }

        .submenu {
            display: none;
            padding: 0 0.6rem 0.55rem;
        }

        .menu-group.open .submenu { display: block; }

        .submenu a {
            display: block;
            padding: 0.52rem 0.7rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.82rem;
            border-radius: 8px;
            margin-top: 0.2rem;
        }

        .submenu a:hover, .submenu a.active {
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
            display: flex;
            align-items: center;
            gap: 0.7rem;
            color: var(--nrb-muted);
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

        .paper-card {
            background: var(--nrb-card);
            border: 1px solid var(--nrb-line);
            border-radius: 18px;
            box-shadow: 0 18px 40px var(--nrb-shadow);
            overflow: hidden;
        }

        .nrb-form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: linear-gradient(135deg, var(--nrb-green-dark) 0%, var(--nrb-green) 100%);
            padding: 1.5rem 2rem;
            border-bottom: 4px solid var(--nrb-gold);
        }

        .eyebrow {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.8);
            margin-bottom: 0.45rem;
        }

        .nrb-form-header h2 {
            margin: 0;
            color: white;
            font-size: clamp(1.5rem, 2.2vw, 2.2rem);
            font-weight: 800;
            line-height: 1.2;
        }

        .form-badge {
            flex-shrink: 0;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.22);
            color: white;
            border-radius: 999px;
            padding: 0.6rem 0.9rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .form-body {
            padding: 2rem;
        }

        .intro-text {
            margin: 0 0 1.6rem;
            color: var(--nrb-muted);
            font-size: 0.97rem;
            font-weight: 500;
        }

        .stage-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            margin: 0 0 1.5rem;
            padding: 0.55rem;
            border-radius: 14px;
            background: #f3f7f4;
            border: 1px solid var(--nrb-line);
        }

        .stage-tab {
            border: 1px solid transparent;
            background: transparent;
            color: var(--nrb-slate);
            font-weight: 700;
            border-radius: 10px;
            padding: 0.72rem 1rem;
            min-width: 180px;
            text-align: left;
            transition: 0.2s ease;
        }

        .stage-tab.active {
            background: linear-gradient(135deg, var(--nrb-green) 0%, var(--nrb-green-dark) 100%);
            color: #fff;
            box-shadow: 0 12px 22px rgba(13,79,59,0.18);
        }

        .stage-tab.complete {
            background: rgba(13,79,59,0.08);
            border-color: rgba(13,79,59,0.14);
            color: var(--nrb-green-dark);
        }

        .stage-tab:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .camera-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .camera-picker .form-select {
            max-width: 260px;
        }

        .camera-preview-window {
            position: relative;
            width: 100%;
            min-height: 310px;
            background: linear-gradient(180deg, #121a1b 0%, #0d1718 100%);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: inset 0 0 24px rgba(255,255,255,0.04);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .camera-preview-window video {
            width: 100%;
            height: 100%;
            min-height: 310px;
            object-fit: cover;
            background: #0b0f12;
            display: block;
        }

        .camera-preview-placeholder {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            color: rgba(255,255,255,0.7);
            text-align: center;
            background: radial-gradient(circle at center, rgba(31,41,55,0.42), rgba(4,9,11,0.95));
            padding: 1rem;
        }

        .camera-preview-placeholder i {
            display: block;
            font-size: 2.8rem;
            margin-bottom: 0.7rem;
            color: rgba(255,255,255,0.8);
        }

        .passport-workspace {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .passport-panel {
            background: #fff;
            border: 1px solid var(--nrb-line);
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
        }

        .passport-panel-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.75rem;
        }

        .passport-step-badge {
            background: var(--nrb-primary);
            color: #fff;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .passport-panel-header h3 {
            margin: 0;
            font-size: 1.05rem;
            color: var(--nrb-text);
        }

        .passport-panel-header p {
            margin: 0.15rem 0 0;
            font-size: 0.72rem;
            color: var(--nrb-muted);
        }

        .passport-viewport-box {
            position: relative;
            width: 100%;
            aspect-ratio: 3 / 4;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.9rem;
        }

        .passport-viewport-box video,
        .passport-viewport-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .passport-status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: var(--nrb-muted);
            margin-bottom: 0.9rem;
        }

        .passport-status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #31a24c;
            border-radius: 50%;
            margin-right: 5px;
        }

        .passport-btn {
            background: var(--nrb-primary);
            color: #fff;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background 0.2s ease;
        }

        .passport-btn:hover {
            background: var(--nrb-green-dark);
        }

        .passport-btn-secondary {
            background: #e4e6eb;
            color: var(--nrb-text);
        }

        .passport-btn-secondary:hover {
            background: #d8dadf;
        }

        .passport-button-group {
            display: flex;
            gap: 0.65rem;
            margin-bottom: 0.9rem;
        }

        .passport-tools-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.5rem;
            margin-bottom: 0.8rem;
        }

        .passport-tool-btn {
            background: #f0f2f5;
            border: 1px solid var(--nrb-line);
            padding: 0.65rem 0.45rem;
            border-radius: 8px;
            font-size: 0.72rem;
            cursor: pointer;
            text-align: center;
            font-weight: 500;
            color: var(--nrb-text);
        }

        .passport-tool-btn:hover {
            background: #e4e6eb;
        }

        .passport-meta-list {
            font-size: 0.8rem;
            margin-bottom: 0.9rem;
            background: #f9fafb;
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid #edf0f2;
        }

        .passport-meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.35rem;
        }

        .passport-meta-row:last-child {
            margin-bottom: 0;
        }

        .passport-meta-label {
            color: var(--nrb-muted);
        }

        .passport-meta-value {
            font-weight: 600;
        }

        .passport-tips-banner {
            background: var(--nrb-card);
            padding: 0.9rem 1rem;
            border-radius: 12px;
            font-size: 0.8rem;
            color: var(--nrb-muted);
            display: flex;
            align-items: center;
            gap: 0.65rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .passport-tips-banner strong {
            color: var(--nrb-text);
        }

        canvas {
            display: none;
        }

        .stage-panel {
            display: none;
        }

        .stage-panel.active {
            display: block;
        }

        .review-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .review-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbf9 100%);
            border: 1px solid var(--nrb-line);
            border-radius: 14px;
            padding: 1rem 1.1rem;
            min-height: 120px;
        }

        .review-label {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--nrb-green-dark);
            margin-bottom: 0.5rem;
        }

        .review-value {
            font-size: 0.96rem;
            font-weight: 600;
            color: var(--nrb-slate);
            line-height: 1.6;
            white-space: pre-line;
        }

        .device-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-radius: 10px;
            padding: 0.7rem 0.9rem;
            margin-bottom: 1rem;
        }

        .device-status.connected {
            background: rgba(24, 160, 92, 0.12);
            color: #0f7a4c;
            border: 1px solid rgba(24, 160, 92, 0.18);
        }

        .device-status.not-connected {
            background: rgba(220, 53, 69, 0.08);
            color: #b42318;
            border: 1px solid rgba(220,53,69,0.15);
        }

        .biometric-box {
            background: linear-gradient(180deg, #f8fbf9 0%, #f3f7f4 100%);
            border: 1px solid var(--nrb-line);
            border-radius: 16px;
            padding: 1rem;
        }

        .fingerprint-demo {
            position: relative;
            height: 250px;
            background: linear-gradient(180deg, rgba(13,79,59,0.06), rgba(13,79,59,0.02));
            border: 1px solid var(--nrb-line);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .scan-beam {
            position: absolute;
            left: 12%;
            right: 12%;
            top: 12%;
            height: 2px;
            background: linear-gradient(90deg, rgba(0,94,133,0), rgba(12,188,169,0.9), rgba(0,94,133,0));
            box-shadow: 0 0 18px rgba(11, 159, 170, 0.8);
            animation: scanning 2.8s ease-in-out infinite alternate;
        }

        @keyframes scanning {
            0% { top: 14%; opacity: 0.3; }
            50% { opacity: 1; }
            100% { top: 72%; opacity: 0.8; }
        }

        .hand-pair {
            position: absolute;
            inset: 0;
        }

        .hand {
            position: absolute;
            bottom: 18px;
            width: 150px;
            height: 140px;
            border: 3px solid rgba(11, 94, 168, 0.22);
            border-radius: 55% 55% 38% 38% / 55% 55% 36% 36%;
            background: rgba(255,255,255,0.45);
            box-shadow: inset 0 0 20px rgba(13,79,59,0.06);
        }

        .hand.left { left: 12%; transform: rotate(-8deg); }
        .hand.right { right: 12%; transform: rotate(8deg); }

        .finger {
            position: absolute;
            width: 18px;
            height: 62px;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(31,41,55,0.86), rgba(31,41,55,0.4));
            top: 18px;
            box-shadow: inset 0 0 10px rgba(255,255,255,0.24);
        }

        .finger::before {
            content: "";
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(13,79,59,0.18);
        }

        .hand.left .finger:nth-child(1) { left: 16%; }
        .hand.left .finger:nth-child(2) { left: 30%; height: 70px; }
        .hand.left .finger:nth-child(3) { left: 46%; height: 76px; }
        .hand.left .finger:nth-child(4) { left: 62%; height: 68px; }
        .hand.left .finger:nth-child(5) { left: 78%; height: 54px; }

        .hand.right .finger:nth-child(1) { left: 16%; }
        .hand.right .finger:nth-child(2) { left: 30%; height: 70px; }
        .hand.right .finger:nth-child(3) { left: 46%; height: 76px; }
        .hand.right .finger:nth-child(4) { left: 62%; height: 68px; }
        .hand.right .finger:nth-child(5) { left: 78%; height: 54px; }

        .fingerprint-ring {
            position: absolute;
            inset: 18% 22%;
            border: 2px solid rgba(11,94,168,0.28);
            border-radius: 50%;
            animation: pulse 2.8s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(0.98); opacity: 0.5; }
            50% { transform: scale(1.04); opacity: 1; }
        }

        .form-section-title {
            background: var(--nrb-green-soft);
            border-left: 6px solid var(--nrb-green);
            padding: 0.9rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.25rem;
        }

        .form-section-title h4 {
            margin: 0;
            color: var(--nrb-green-dark);
            font-size: 1.02rem;
            font-weight: 800;
            line-height: 1.45;
        }

        .row {
            --bs-gutter-x: 1.1rem;
            --bs-gutter-y: 1rem;
        }

        .sub-header {
            display: block;
            width: 100%;
            font-size: 0.8rem;
            line-height: 1.4;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--nrb-green-dark);
            border-bottom: 2px solid var(--nrb-line);
            padding-bottom: 0.45rem;
            margin-top: 0.7rem;
            margin-bottom: 0.8rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.45rem;
            font-size: 0.76rem;
            font-weight: 800;
            line-height: 1.4;
            letter-spacing: 0.02em;
            color: var(--nrb-text);
        }

        .form-control, .form-select {
            width: 100%;
            min-height: 46px;
            border: 1px solid var(--nrb-line);
            border-radius: 10px;
            background: #fff;
            color: var(--nrb-text);
            font-size: 0.95rem;
            padding: 0.7rem 0.8rem;
            text-transform: uppercase;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control::placeholder { color: #8b9a93; }

        input[type="date"], input[type="email"], input[type="tel"], input[type="number"], input[type="password"] {
            text-transform: none;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--nrb-green);
            box-shadow: 0 0 0 0.25rem rgba(13, 79, 59, 0.12);
            outline: none;
        }

        .witness-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbf9 100%);
            border: 1px solid var(--nrb-line);
            border-radius: 14px;
            overflow: hidden;
        }

        .witness-card .card-body { padding: 1.1rem 1.2rem; }
        .witness-card h6 {
            margin: 0 0 1rem;
            color: var(--nrb-green-dark);
            font-weight: 800;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--nrb-green) 0%, var(--nrb-green-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.82rem 1.5rem;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(13, 79, 59, 0.18);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--nrb-green-dark) 0%, var(--nrb-green) 100%);
        }

        .btn-outline-secondary {
            border-radius: 10px;
            padding: 0.82rem 1.3rem;
            font-weight: 700;
            border-color: #b9c6c0;
            color: var(--nrb-text);
        }

        .wizard-footer {
            border-top: 1px solid var(--nrb-line);
            padding-top: 1.4rem;
            margin-top: 1.5rem !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .wizard-footer-left,
        .wizard-footer-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .wizard-footer .btn {
            min-width: 140px;
            justify-content: center;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-weight: 700;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .wizard-footer .btn:hover {
            transform: translateY(-1px);
        }

        .wizard-footer .btn i {
            font-size: 0.8rem;
        }

        .registering-modal {
            position: fixed;
            inset: 0;
            background: rgba(11, 20, 17, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            backdrop-filter: blur(3px);
        }

        .registering-modal.show {
            display: flex;
        }

        .registering-modal-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 2rem 2.2rem;
            min-width: min(92vw, 360px);
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.24);
            border: 1px solid rgba(15,23,42,0.08);
            text-align: center;
        }

        .registering-spinner {
            width: 64px;
            height: 64px;
            border: 6px solid rgba(13,79,59,0.12);
            border-top-color: var(--nrb-green);
            border-radius: 50%;
            margin: 0 auto 1rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .registering-modal-card h4 {
            margin: 0 0 0.5rem;
            font-size: 1.3rem;
            color: var(--nrb-slate);
        }

        .registering-modal-card p {
            margin: 0;
            color: var(--nrb-muted);
            font-size: 0.95rem;
        }

        .toast-container {
            position: fixed;
            right: 1.25rem;
            bottom: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            z-index: 2100;
        }

        .toast {
            min-width: min(88vw, 320px);
            background: #ffffff;
            color: var(--nrb-slate);
            border-left: 6px solid var(--nrb-green);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            box-shadow: 0 20px 45px rgba(15,23,42,0.15);
            border: 1px solid rgba(15,23,42,0.06);
            opacity: 0;
            transform: translateY(12px);
            transition: all 0.25s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.error {
            border-left-color: #dc3545;
        }

        .toast.success {
            border-left-color: var(--nrb-green);
        }

        .toast-title {
            font-weight: 800;
            margin-bottom: 0.15rem;
        }

        .toast-message {
            font-size: 0.85rem;
            color: var(--nrb-muted);
            line-height: 1.45;
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

            .sidebar.open { left: 0; }
            .main { margin-left: 0; width: 100%; }
            .topbar { padding: 0 1rem; }
            .topbar-search { display: none; }
            .content { padding: 1rem; }
            .toggle-btn { display: grid; }
        }

        @media (max-width: 767.98px) {
            .nrb-form-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.2rem 1rem;
            }

            .form-body { padding: 1rem; }
            .mt-4.d-flex { flex-direction: column; }
            .mt-4.d-flex .btn { width: 100%; }
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
                    <span>Citizen Registration</span>
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
                            <div class="profile-avatar"><?= e(substr(($user['full_name'] ?? 'Admin User'), 0, 2)) ?></div>
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
                <div class="paper-card">
                    <div class="nrb-form-header">
                        <div>
                            <span class="eyebrow">Citizenship Registration</span>
                            <h2>National ID Registration Form (NR 1)</h2>
                        </div>
                        <div class="form-badge">NRB / Malawi</div>
                    </div>

                    <div class="form-body">
                        <p class="intro-text">Registration of Citizens / Chiwona cha unzika</p>

                        <div class="stage-tabs" aria-label="Registration stages">
                            <button type="button" class="stage-tab active" data-stage-target="applicant">Applicant Details</button>
                            <button type="button" class="stage-tab" data-stage-target="parents" disabled>Parent Details</button>
                            <button type="button" class="stage-tab" data-stage-target="witnesses" disabled>Witness Details</button>
                            <button type="button" class="stage-tab" data-stage-target="biometric" disabled>Biometric Capture</button>
                            <button type="button" class="stage-tab" data-stage-target="passport_photo" disabled>Passport Photo</button>
                            <button type="button" class="stage-tab" data-stage-target="review" disabled>Final Review</button>
                        </div>

                        <form method="post" action="<?= e(route_url('/citizen/register')) ?>" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= e(App\Security\CSRF::token()) ?>">

                            <div class="stage-panel active" data-stage-panel="applicant">
                                <div class="pb-3 mb-4">
                                <div class="form-section-title">
                                    <h4>Gawo loyamba / Part 1: Mbiri ya ofuna chiphaso cha unzika / National ID Applicant Details</h4>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">1. Ndinu nzika ya dziko liti / Nationality</label>
                                        <input class="form-control" name="nationality" value="MALAWI" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">2. Ndinu nzika ya dziko lina liti / Second Nationality</label>
                                        <input class="form-control" name="second_nationality" placeholder="Second Nationality">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">3. Tsiku lobadwa / Date of Birth</label>
                                        <input class="form-control" type="date" name="date_of_birth" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">4. Mwamuna/Kazi / Gender</label>
                                        <select class="form-select" name="gender" required>
                                            <option value="" selected disabled>Select Gender</option>
                                            <option value="Male">Mwamuna / Male</option>
                                            <option value="Female">Mkazi / Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">5. Boma / District (Place of birth)</label>
                                        <input class="form-control" name="birth_district" placeholder="District" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mfumu yaikulu / TA</label>
                                        <input class="form-control" name="birth_ta" placeholder="T/A">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mudzi / Village</label>
                                        <input class="form-control" name="birth_village" placeholder="Village">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">6. Dzina loyamba / First Name</label>
                                        <input class="form-control" name="first_name" placeholder="First Name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Maina ena / Other Names</label>
                                        <input class="form-control" name="other_names" placeholder="Middle / Other Names">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Dzina la bambo / Surname</label>
                                        <input class="form-control" name="surname" placeholder="Surname" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">7. Nambala ya chiphaso choperekedwa pobadwa / Birth Certificate No.</label>
                                        <input class="form-control" name="birth_certificate_no" placeholder="Birth Certificate No. (if available)">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">8. Banja / Marital Status</label>
                                        <select class="form-select" name="marital_status">
                                            <option value="" selected disabled>Select Marital Status</option>
                                            <option value="Never Married">Sinumakhale naye pa banja / Never Married</option>
                                            <option value="Married">Muli pa Banja / Married</option>
                                            <option value="Divorced">Ukwati unatha / Divorced</option>
                                            <option value="Widowed">Wamasiye / Widowed</option>
                                            <option value="Separated">Munanyanyalitsana / Separated</option>
                                            <option value="Abandoned">Anangochoka / Abandoned</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">9. Mtundu wa maso / Eye Colour</label>
                                        <input class="form-control" name="eye_colour" placeholder="e.g. Brown">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">10. Kutalika / Height (meters)</label>
                                        <input class="form-control" type="number" step="0.01" name="height" placeholder="e.g. 1.60">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">11. Lamya / Mobile Phone</label>
                                        <input class="form-control" type="tel" name="phone" placeholder="Phone Number">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">12. Nambala ya chiphaso chayendera / Passport No.</label>
                                        <input class="form-control" name="passport_no" placeholder="Passport No. (if available)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">13. Ulumali ndi zina / Disability or any other observation</label>
                                        <input class="form-control" name="disability" placeholder="Disability / Observations">
                                    </div>

                                    <div class="col-12"><div class="sub-header">14. Dela lomwe mukukhala / Residential Address</div></div>
                                    <div class="col-md-4">
                                        <label class="form-label">Boma / District</label>
                                        <input class="form-control" name="residence_district" placeholder="District">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mfumu yaikulu / TA</label>
                                        <input class="form-control" name="residence_ta" placeholder="T/A">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mudzi / Village</label>
                                        <input class="form-control" name="residence_village" placeholder="Village">
                                    </div>

                                    <div class="col-12"><div class="sub-header">15. Kumudzi kochokera / Permanent Original Home</div></div>
                                    <div class="col-md-4">
                                        <label class="form-label">Boma / District</label>
                                        <input class="form-control" name="home_district" placeholder="District">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mfumu yaikulu / TA</label>
                                        <input class="form-control" name="home_ta" placeholder="T/A">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mudzi / Village</label>
                                        <input class="form-control" name="home_village" placeholder="Village">
                                    </div>
                                </div>
                            </div>

                            </div>

                            <div class="stage-panel" data-stage-panel="parents">
                                <div class="pb-3 mb-4">
                                <div class="form-section-title">
                                    <h4>Gawo lachiwiri / Part 2: Mbiri ya makolo / Parents Details</h4>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">1. Nambala ya chiphaso cha unzika cha mayi / Mother ID No.</label>
                                        <input class="form-control text-uppercase" name="mother_id_no" maxlength="8" placeholder="Mother National ID">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">2. Maina a mayi / Full Names of Mother</label>
                                        <input class="form-control" name="mother_full_name" placeholder="Mother Full Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">3. Mayi ndi nzika ya dziko liti / Mother Nationality</label>
                                        <input class="form-control" name="mother_nationality" value="MALAWI">
                                    </div>

                                    <div class="col-12"><div class="sub-header">4. Kumudzi kwa mayi / Mother's Permanent Home Address</div></div>
                                    <div class="col-md-4">
                                        <label class="form-label">Boma / District</label>
                                        <input class="form-control" name="mother_home_district" placeholder="District">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mfumu yaikulu / TA</label>
                                        <input class="form-control" name="mother_home_ta" placeholder="T/A">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mudzi / Village</label>
                                        <input class="form-control" name="mother_home_village" placeholder="Village">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">5. Nambala ya chiphaso cha unzika cha bambo / Father ID No.</label>
                                        <input class="form-control text-uppercase" name="father_id_no" maxlength="8" placeholder="Father National ID">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">6. Maina a bambo / Full Names of Father</label>
                                        <input class="form-control" name="father_full_name" placeholder="Father Full Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">7. Bambo ndi nzika ya dziko liti / Father's Nationality</label>
                                        <input class="form-control" name="father_nationality" value="MALAWI">
                                    </div>

                                    <div class="col-12"><div class="sub-header">8. Kumudzi kwa bambo / Father's Permanent Home Address</div></div>
                                    <div class="col-md-4">
                                        <label class="form-label">Boma / District</label>
                                        <input class="form-control" name="father_home_district" placeholder="District">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mfumu yaikulu / TA</label>
                                        <input class="form-control" name="father_home_ta" placeholder="T/A">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mudzi / Village</label>
                                        <input class="form-control" name="father_home_village" placeholder="Village">
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div class="stage-panel" data-stage-panel="witnesses">
                                <div class="pb-3 mb-4">
                                <div class="form-section-title">
                                    <h4>Recommendation by witnesses / Chitsimikizo cha mboni</h4>
                                </div>

                                <div class="alert alert-light border mb-3">
                                    <strong>Optional:</strong> Witness details are only required if both parents' information is not provided for the applicant.
                                </div>

                                <div class="card witness-card mb-3">
                                    <div class="card-body">
                                        <h6>Mboni yoyamba / First witness</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Ine / I (Full Name)</label>
                                                <input class="form-control" name="witness1_name" placeholder="First Witness Full Name">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">First Witness ID No.</label>
                                                <input class="form-control text-uppercase" name="witness1_id_no" maxlength="8" placeholder="ID Number">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tsiku / Date</label>
                                                <input class="form-control" type="date" name="witness1_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card witness-card">
                                    <div class="card-body">
                                        <h6>Mboni yachiwiri / Second witness</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Ine / I (Full Name)</label>
                                                <input class="form-control" name="witness2_name" placeholder="Second Witness Full Name">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Second Witness ID No.</label>
                                                <input class="form-control text-uppercase" name="witness2_id_no" maxlength="8" placeholder="ID Number">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tsiku / Date</label>
                                                <input class="form-control" type="date" name="witness2_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div class="stage-panel" data-stage-panel="biometric">
                                <div class="pb-3 mb-4">
                                    <div class="form-section-title">
                                        <h4>Biometric Enrollment / Photos and Fingerprints</h4>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="biometric-box">
                                                <div class="device-status connected" id="photoDeviceStatus">
                                                    <span><i class="fa-solid fa-camera"></i> Photo Camera</span>
                                                    <span id="photoDeviceText">Checking camera...</span>
                                                </div>

                                                <label class="form-label">Passport Photo (optional)</label>
                                                <div class="border rounded p-3 bg-white">
                                                    <video id="passportVideo" playsinline autoplay muted style="display:none; width:100%; max-height:220px; border-radius:10px; background:#111;"></video>
                                                    <canvas id="passportCanvas" style="display:none;"></canvas>
                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-camera-target="passport">Use Camera</button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-stop-camera="passport">Stop</button>
                                                    </div>
                                                    <input id="passportPhotoInput" class="form-control mt-2" type="file" name="passport_photo" accept="image/*" capture="environment">
                                                    <div class="form-text">Optional. Connected camera or scanner files are accepted here if available.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="biometric-box">
                                                <div class="device-status not-connected" id="fingerprintDeviceStatus">
                                                    <span><i class="fa-solid fa-fingerprint"></i> Fingerprint Scanner</span>
                                                    <span id="fingerprintDeviceText">Checking scanner...</span>
                                                </div>

                                                <div class="fingerprint-demo" aria-label="Fingerprint capture illustration">
                                                    <div class="scan-beam"></div>
                                                    <div class="hand-pair">
                                                        <div class="hand left">
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                        </div>
                                                        <div class="hand right">
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                            <span class="finger"></span>
                                                        </div>
                                                        <div class="fingerprint-ring"></div>
                                                    </div>
                                                </div>

                                                <label class="form-label">Fingerprint Capture (optional)</label>
                                                <div class="border rounded p-3 bg-white">
                                                    <video id="fingerprintVideo" playsinline autoplay muted style="display:none; width:100%; max-height:220px; border-radius:10px; background:#111;"></video>
                                                    <canvas id="fingerprintCanvas" style="display:none;"></canvas>
                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-camera-target="fingerprint">Use Camera</button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-stop-camera="fingerprint">Stop</button>
                                                    </div>
                                                    <input id="fingerprintImageInput" class="form-control mt-2" type="file" name="fingerprint_image" accept="image/*,.wsq,.bmp" capture="environment">
                                                    <div class="form-text">Optional. If no scanner is attached, you may leave this blank or upload a captured fingerprint manually.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Biometric Capture Method</label>
                                            <select class="form-select" name="capture_method">
                                                <option value="manual_upload" selected>Manual upload</option>
                                                <option value="mobile_capture">Mobile capture</option>
                                                <option value="scanner">Scanner</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Fingerprint Type</label>
                                            <select class="form-select" name="fingerprint_type">
                                                <option value="right_thumb" selected>Right Thumb</option>
                                                <option value="left_thumb">Left Thumb</option>
                                                <option value="right_index">Right Index</option>
                                                <option value="left_index">Left Index</option>
                                                <option value="all_ten">All Ten</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="stage-panel" data-stage-panel="passport_photo">
                                <div class="pb-3 mb-4">
                                    <div class="form-section-title">
                                        <h4>Passport Photo Capture</h4>
                                    </div>

                                    <div class="passport-workspace">
                                        <div class="passport-panel">
                                            <div class="passport-panel-header">
                                                <div class="passport-step-badge">1</div>
                                                <div>
                                                    <h3>Capture Photo</h3>
                                                    <p>Select camera and take your photo</p>
                                                </div>
                                            </div>

                                            <label for="cameraDeviceSelect">Camera Source</label>
                                            <select id="cameraDeviceSelect" class="form-select">
                                                <option value="auto">Auto detect</option>
                                                <option value="usb">USB Camera</option>
                                                <option value="bluetooth">Bluetooth Camera</option>
                                                <option value="embedded">Embedded Camera</option>
                                            </select>

                                            <div class="passport-viewport-box">
                                                <video id="passportStageVideo" autoplay playsinline muted></video>
                                            </div>

                                            <div class="passport-status-bar">
                                                <span><span class="passport-status-indicator"></span><span id="passportCameraStatusText">Camera ready</span></span>
                                            </div>

                                            <div class="passport-button-group">
                                                <button type="button" class="passport-btn passport-btn-secondary" id="openPassportCameraBtn">Open Camera</button>
                                                <button type="button" class="passport-btn passport-btn-secondary" id="switchPassportCameraBtn">Switch</button>
                                            </div>
                                            <button type="button" class="passport-btn" id="capturePassportBtn">Capture Photo</button>
                                            <div class="form-text mt-2" id="passportCameraPermissionHint">Click “Open Camera” to allow access and view the live feed.</div>
                                        </div>

                                        <div class="passport-panel">
                                            <div class="passport-panel-header">
                                                <div class="passport-step-badge">2</div>
                                                <div>
                                                    <h3>Edit &amp; Crop</h3>
                                                    <p>Auto-enhanced for profile standards</p>
                                                </div>
                                            </div>

                                            <div class="passport-viewport-box" style="background: #e4e6eb;">
                                                <img id="capturedPassportPreview" alt="Captured passport preview will appear here" style="display:none;" />
                                            </div>

                                            <div class="passport-tools-grid">
                                                <button type="button" class="passport-tool-btn" data-passport-effect="crop">Crop Ratio</button>
                                                <button type="button" class="passport-tool-btn" data-passport-effect="enhance">Auto Enhance</button>
                                                <button type="button" class="passport-tool-btn" data-passport-effect="reset">Reset</button>
                                            </div>

                                            <label for="passportBrightnessRange">Brightness Adjustment</label>
                                            <input type="range" id="passportBrightnessRange" min="50" max="150" value="100" />

                                            <label for="passportContrastRange">Contrast Adjustment</label>
                                            <input type="range" id="passportContrastRange" min="50" max="150" value="100" />
                                        </div>

                                        <div class="passport-panel">
                                            <div class="passport-panel-header">
                                                <div class="passport-step-badge">3</div>
                                                <div>
                                                    <h3>Preview &amp; Upload</h3>
                                                    <p>Verify details and push to server</p>
                                                </div>
                                            </div>

                                            <div class="passport-viewport-box" style="background: #e4e6eb;">
                                                <img id="finalPassportPreview" alt="Final render preview" style="display:none;" />
                                            </div>

                                            <div class="passport-meta-list">
                                                <div class="passport-meta-row"><span class="passport-meta-label">Format:</span><span class="passport-meta-value">JPG</span></div>
                                                <div class="passport-meta-row"><span class="passport-meta-label">Dimensions:</span><span class="passport-meta-value">413 x 531 px</span></div>
                                                <div class="passport-meta-row"><span class="passport-meta-label">Standard Size:</span><span class="passport-meta-value">3.5cm x 4.5cm</span></div>
                                            </div>

                                            <input type="hidden" name="image_data" id="passportImageDataInput">
                                            <button type="button" class="passport-btn" id="uploadPassportPhotoBtn">Upload Photo</button>
                                        </div>
                                    </div>

                                    <div class="passport-tips-banner">
                                        <span>💡</span>
                                        <div><strong>Tips:</strong> Look straight into the camera, ensure balanced indoor lighting, and keep a neutral expression.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="stage-panel" data-stage-panel="review">
                                <div class="pb-3 mb-4">
                                    <div class="form-section-title">
                                        <h4>Final Review / Verification</h4>
                                    </div>

                                    <div class="review-grid">
                                        <div class="review-card">
                                            <span class="review-label">Applicant</span>
                                            <div class="review-value" data-review="applicant"></div>
                                        </div>
                                        <div class="review-card">
                                            <span class="review-label">Parents</span>
                                            <div class="review-value" data-review="parents"></div>
                                        </div>
                                        <div class="review-card">
                                            <span class="review-label">Witnesses</span>
                                            <div class="review-value" data-review="witnesses"></div>
                                        </div>
                                        <div class="review-card">
                                            <span class="review-label">Biometrics</span>
                                            <div class="review-value" data-review="biometrics"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wizard-footer">
                                <div class="wizard-footer-left">
                                    <button type="button" class="btn btn-outline-secondary" id="prevBtn" style="display:none;"><i class="fa-solid fa-arrow-left"></i> Previous</button>
                                    <a href="<?= e(route_url('/citizen')) ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark"></i> Cancel</a>
                                </div>

                                <div class="wizard-footer-right">
                                    <button type="button" class="btn btn-primary" id="nextBtn">Next <i class="fa-solid fa-arrow-right"></i></button>
                                    <button type="submit" class="btn btn-primary shadow-sm" id="submitBtn" style="display:none;"><i class="fa-solid fa-check"></i> Save Registration</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const capitalizeFormText = () => {
            const form = document.querySelector('form[enctype="multipart/form-data"]');
            if (!form) return;

            form.querySelectorAll('input, textarea').forEach((field) => {
                const type = (field.type || '').toLowerCase();
                if (type === 'text' || type === 'search' || type === 'email' || type === 'tel' || type === 'password' || field.tagName.toLowerCase() === 'textarea') {
                    const oldValue = field.value || '';
                    field.value = oldValue.toUpperCase();

                    field.addEventListener('input', () => {
                        if (['date', 'number'].includes(type)) {
                            return;
                        }
                        field.value = field.value.toUpperCase();
                    });
                }
            });
        };

        const appBase = <?= json_encode(app_base_path()) ?> || '';
        const draftStorageKey = 'nrb_citizen_registration_draft';

        const saveDraftFormData = () => {
            const form = document.querySelector('form[enctype="multipart/form-data"]');
            if (!form) return;

            const draft = {};
            form.querySelectorAll('input, select, textarea').forEach((field) => {
                const name = field.name;
                if (!name || field.type === 'file' || field.type === 'button' || field.type === 'submit' || field.type === 'reset') {
                    return;
                }

                if (field.type === 'checkbox' || field.type === 'radio') {
                    draft[name] = field.checked ? '1' : '0';
                    return;
                }

                draft[name] = field.value;
            });

            try {
                localStorage.setItem(draftStorageKey, JSON.stringify(draft));
            } catch (error) {
                console.warn('Could not save draft form data.', error);
            }
        };

        const restoreDraftFormData = () => {
            const form = document.querySelector('form[enctype="multipart/form-data"]');
            if (!form) return;

            try {
                const rawDraft = localStorage.getItem(draftStorageKey);
                if (!rawDraft) return;

                const draft = JSON.parse(rawDraft);
                if (!draft || typeof draft !== 'object') return;

                Object.entries(draft).forEach(([name, value]) => {
                    const field = form.querySelector(`[name="${CSS.escape(name)}"]`);
                    if (!field) return;

                    if (field.type === 'checkbox' || field.type === 'radio') {
                        field.checked = String(value) === '1' || value === true;
                        return;
                    }

                    field.value = value ?? '';
                });
            } catch (error) {
                console.warn('Could not restore draft form data.', error);
            }
        };

        const clearDraftFormData = () => {
            try {
                localStorage.removeItem(draftStorageKey);
            } catch (error) {
                console.warn('Could not clear draft form data.', error);
            }
        };

        capitalizeFormText();
        restoreDraftFormData();

        const normalizePath = (value) => {
            const cleaned = String(value || '/').replace(/\/+$/, '') || '/';
            if (!cleaned || cleaned === '') return '/';
            if (appBase && cleaned.startsWith(appBase)) {
                const relative = cleaned.slice(appBase.length) || '/';
                return relative === '' ? '/' : relative;
            }
            return cleaned;
        };

        const cameraStreams = {};
        const passportCameraStreamKey = 'passport_stage';
        const wizardStages = ['applicant', 'parents', 'witnesses', 'biometric', 'passport_photo', 'review'];
        let currentStageIndex = 0;

        const getStageName = () => wizardStages[currentStageIndex];

        const setPassportCameraStatus = (message, connected = false) => {
            const statusText = document.getElementById('passportCameraStatusText');
            const hint = document.getElementById('passportCameraPermissionHint');
            const indicator = document.querySelector('#passportStageVideo')?.closest('.passport-panel')?.querySelector('.passport-status-indicator');

            if (statusText) {
                statusText.textContent = message;
            }

            if (hint) {
                hint.textContent = message;
            }

            if (indicator) {
                indicator.style.backgroundColor = connected ? '#31a24c' : '#d97706';
            }
        };

        const isLocalSecureContext = () => {
            try {
                const href = window.location.href || '';
                return window.isSecureContext || /^https?:\/\/(localhost|127\.0\.0\.1|\[::1\])(?::\d+)?(?:\/|$)/i.test(href);
            } catch (error) {
                return false;
            }
        };

        const getSelectedCameraDeviceId = () => {
            const select = document.getElementById('cameraDeviceSelect');
            if (!select) return null;
            const selectedValue = String(select.value || '').trim();
            return selectedValue && selectedValue !== 'auto' ? selectedValue : null;
        };

        const populateCameraDeviceSelect = async () => {
            const select = document.getElementById('cameraDeviceSelect');
            if (!select || !navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                return;
            }

            const previousSelection = getSelectedCameraDeviceId();
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter((device) => device.kind === 'videoinput');

            const existingRealOptions = Array.from(select.options).filter((option) => option.value !== 'auto');
            existingRealOptions.forEach((option) => option.remove());

            if (!videoDevices.length) {
                select.value = 'auto';
                return;
            }

            videoDevices.forEach((device, index) => {
                const option = document.createElement('option');
                option.value = device.deviceId || `camera-${index}`;
                option.textContent = device.label || `Camera ${index + 1}`;
                if (previousSelection && option.value === previousSelection) {
                    option.selected = true;
                }
                select.appendChild(option);
            });

            if (!select.value || select.value === 'auto') {
                select.value = videoDevices[0].deviceId || 'camera-0';
            }
        };

        const getCameraConstraints = () => {
            const selectedDeviceId = getSelectedCameraDeviceId();
            const base = {
                width: { ideal: 1280 },
                height: { ideal: 720 },
            };

            if (selectedDeviceId) {
                return {
                    video: { deviceId: { exact: selectedDeviceId }, ...base },
                    audio: false,
                };
            }

            return {
                video: { facingMode: 'environment', ...base },
                audio: false,
            };
        };

        const updateReviewSummary = () => {
            const applicantName = [
                document.querySelector('input[name="first_name"]')?.value,
                document.querySelector('input[name="surname"]')?.value
            ].filter(Boolean).join(' ') || 'Not provided';

            const applicantDetails = [
                `Name: ${applicantName}`,
                `Date of Birth: ${document.querySelector('input[name="date_of_birth"]')?.value || 'Not provided'}`,
                `Gender: ${document.querySelector('select[name="gender"]')?.value || 'Not provided'}`,
                `District: ${document.querySelector('input[name="birth_district"]')?.value || 'Not provided'}`
            ].join('\n');

            const parentDetails = [
                `Mother: ${document.querySelector('input[name="mother_full_name"]')?.value || 'Not provided'}`,
                `Father: ${document.querySelector('input[name="father_full_name"]')?.value || 'Not provided'}`
            ].join('\n');

            const witnessDetails = [
                `Witness 1: ${document.querySelector('input[name="witness1_name"]')?.value || 'Not provided'}`,
                `Witness 2: ${document.querySelector('input[name="witness2_name"]')?.value || 'Not provided'}`
            ].join('\n');

            const biometricDetails = [
                `Capture Method: ${document.querySelector('select[name="capture_method"]')?.value || 'Not provided'}`,
                `Fingerprint Type: ${document.querySelector('select[name="fingerprint_type"]')?.value || 'Not provided'}`,
                `Photo: ${document.getElementById('passportPhotoInput')?.files?.[0]?.name || document.getElementById('passportStageInput')?.files?.[0]?.name || 'No file selected'}`,
                `Fingerprint: ${document.getElementById('fingerprintImageInput')?.files?.[0]?.name || 'No file selected'}`
            ].join('\n');

            document.querySelector('[data-review="applicant"]')?.replaceChildren(document.createTextNode(applicantDetails));
            document.querySelector('[data-review="parents"]')?.replaceChildren(document.createTextNode(parentDetails));
            document.querySelector('[data-review="witnesses"]')?.replaceChildren(document.createTextNode(witnessDetails));
            document.querySelector('[data-review="biometrics"]')?.replaceChildren(document.createTextNode(biometricDetails));
        };

        const setStage = (nameOrIndex) => {
            const nextIndex = typeof nameOrIndex === 'number' ? nameOrIndex : wizardStages.indexOf(nameOrIndex);
            if (nextIndex < 0 || nextIndex >= wizardStages.length) return;

            currentStageIndex = nextIndex;
            updateReviewSummary();

            document.querySelectorAll('.stage-tab').forEach((button) => {
                const stageName = button.dataset.stageTarget;
                const stagePosition = wizardStages.indexOf(stageName);
                const isCurrent = stageName === getStageName();
                const isCompleted = stagePosition < currentStageIndex;
                button.classList.toggle('active', isCurrent);
                button.classList.toggle('complete', isCompleted && !isCurrent);
                button.disabled = stagePosition > currentStageIndex;
            });

            document.querySelectorAll('.stage-panel').forEach((panel) => {
                panel.classList.toggle('active', panel.dataset.stagePanel === getStageName());
            });

            const previousButton = document.getElementById('prevBtn');
            const nextButton = document.getElementById('nextBtn');
            const submitButton = document.getElementById('submitBtn');
            const isReview = getStageName() === 'review';

            if (previousButton) previousButton.style.display = currentStageIndex > 0 ? 'inline-block' : 'none';
            if (nextButton) nextButton.style.display = !isReview ? 'inline-block' : 'none';
            if (submitButton) submitButton.style.display = isReview ? 'inline-block' : 'none';

            if (currentStageIndex === 0) {
                previousButton && (previousButton.style.display = 'none');
            }
        };

        document.querySelectorAll('.stage-tab').forEach((button) => {
            button.addEventListener('click', () => {
                const index = wizardStages.indexOf(button.dataset.stageTarget);
                if (index <= currentStageIndex) {
                    setStage(index);
                }
            });
        });

        const registeringModal = document.createElement('div');
        registeringModal.className = 'registering-modal';
        registeringModal.innerHTML = `
            <div class="registering-modal-card">
                <div class="registering-spinner"></div>
                <h4>Registering citizen</h4>
                <p>Please wait while your submission is being processed.</p>
            </div>
        `;
        document.body.appendChild(registeringModal);

        const showRegisteringModal = () => registeringModal.classList.add('show');
        const hideRegisteringModal = () => registeringModal.classList.remove('show');

        const showToast = (type, title, message) => {
            const container = document.getElementById('toastContainer') || (() => {
                const el = document.createElement('div');
                el.id = 'toastContainer';
                el.className = 'toast-container';
                document.body.appendChild(el);
                return el;
            })();

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3200);
        };

        const registerCitizenForm = document.querySelector('form[method="post"][enctype="multipart/form-data"]');
        if (registerCitizenForm) {
            registerCitizenForm.addEventListener('submit', async (event) => {
                const isReviewStage = getStageName() === 'review';
                if (!isReviewStage) {
                    return;
                }

                event.preventDefault();
                const submitButton = document.getElementById('submitBtn');
                const submissionStartedAt = Date.now();

                showRegisteringModal();
                if (submitButton) {
                    submitButton.disabled = true;
                }

                const finishWithDelay = (callback) => {
                    const elapsed = Date.now() - submissionStartedAt;
                    const remaining = Math.max(0, 10000 - elapsed);
                    setTimeout(callback, remaining);
                };

                try {
                    const response = await fetch(registerCitizenForm.action, {
                        method: 'POST',
                        body: new FormData(registerCitizenForm),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });

                    const text = await response.text();
                    let payload = null;
                    try {
                        payload = JSON.parse(text);
                    } catch (error) {
                        payload = null;
                    }

                    const completeRequest = () => {
                        if (!response.ok) {
                            const message = payload?.message || 'Citizen registration failed. Please review the form and try again.';
                            showToast('error', 'Registration failed', message);
                            return;
                        }

                        const successMessage = payload?.message || 'Citizen registration completed successfully.';
                        clearDraftFormData();
                        if (window.localStorage) {
                            localStorage.removeItem(draftStorageKey);
                        }
                        showToast('success', 'Registration successful', successMessage);

                        setTimeout(() => {
                            const redirectUrl = payload?.redirect || (appBase ? appBase + '/citizen' : '/citizen');
                            const finalRedirect = redirectUrl.startsWith('http')
                                ? redirectUrl
                                : (appBase && !redirectUrl.startsWith(appBase) ? appBase + redirectUrl : redirectUrl);
                            window.location.href = finalRedirect;
                        }, 600);
                    };

                    finishWithDelay(() => {
                        hideRegisteringModal();
                        completeRequest();
                    });
                } catch (error) {
                    finishWithDelay(() => {
                        hideRegisteringModal();
                        showToast('error', 'Connection error', 'Unable to register citizen. Please check your connection and try again.');
                    });
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        }

        const updateDeviceStatus = (type, connected, message) => {
            const statusEl = document.getElementById(type === 'photo' ? 'photoDeviceStatus' : 'fingerprintDeviceStatus');
            const textEl = document.getElementById(type === 'photo' ? 'photoDeviceText' : 'fingerprintDeviceText');
            if (!statusEl || !textEl) return;

            statusEl.classList.toggle('connected', connected);
            statusEl.classList.toggle('not-connected', !connected);
            textEl.textContent = message;
        };

        const detectPhotoCamera = async () => {
            const supportsCamera = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia && navigator.mediaDevices.enumerateDevices);
            if (!supportsCamera) {
                updateDeviceStatus('photo', false, 'No camera support');
                return;
            }

            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const cameraFound = devices.some((device) => device.kind === 'videoinput');
                updateDeviceStatus('photo', cameraFound, cameraFound ? 'Camera connected' : 'No camera detected');
            } catch (error) {
                updateDeviceStatus('photo', false, 'Camera unavailable');
            }
        };

        const detectFingerprintScanner = async () => {
            const statusEl = document.getElementById('fingerprintDeviceStatus');
            const textEl = document.getElementById('fingerprintDeviceText');
            if (!statusEl || !textEl) return;

            let scannerDetected = false;
            let scannerLabel = 'No scanner detected';

            try {
                if (navigator.usb) {
                    const devices = await navigator.usb.getDevices();
                    const known = devices.some((device) => {
                        const product = (device.productName || '').toLowerCase();
                        return /fingerprint|biometric|digitalpersona|u.are.u|morpho|crossmatch|integrity|mantra|nextbiometrics|secugen|honeywell/i.test(product);
                    });
                    scannerDetected = known;
                    scannerLabel = known ? 'Scanner connected' : 'No scanner detected';
                }
            } catch (error) {
                scannerDetected = false;
                scannerLabel = 'Scanner not available';
            }

            if (!scannerDetected && window.location.protocol === 'file:') {
                scannerLabel = 'Manual upload fallback';
            }

            statusEl.classList.toggle('connected', scannerDetected);
            statusEl.classList.toggle('not-connected', !scannerDetected);
            textEl.textContent = scannerLabel;
        };

        const stopCamera = (target) => {
            const video = document.getElementById(target + 'Video');
            if (!video) return;
            if (cameraStreams[target]) {
                cameraStreams[target].getTracks().forEach(track => track.stop());
                delete cameraStreams[target];
            }
            video.srcObject = null;
            video.style.display = 'none';
        };

        const captureFromCamera = async (target) => {
            const input = document.getElementById(target === 'passport' ? 'passportPhotoInput' : 'fingerprintImageInput');
            const video = document.getElementById(target + 'Video');
            const canvas = document.getElementById(target + 'Canvas');

            if (!input || !video || !canvas) return;

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                input.click();
                return;
            }

            try {
                if (cameraStreams[target]) {
                    const stream = cameraStreams[target];
                    stream.getTracks().forEach(track => track.stop());
                    delete cameraStreams[target];
                }

                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' },
                    audio: false,
                });

                cameraStreams[target] = stream;
                video.srcObject = stream;
                video.style.display = 'block';
                await video.play();

                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;

                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                canvas.toBlob((blob) => {
                    if (!blob) return;
                    const fileName = target === 'passport' ? 'passport-photo.jpg' : 'fingerprint-capture.jpg';
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(new File([blob], fileName, { type: blob.type || 'image/jpeg' }));
                    input.files = dataTransfer.files;
                }, 'image/jpeg', 0.92);

                const selectedMethod = document.querySelector('select[name="capture_method"]');
                if (selectedMethod) {
                    selectedMethod.value = 'mobile_capture';
                }
            } catch (error) {
                input.click();
            }
        };

        const startPassportCamera = async () => {
            const video = document.getElementById('passportStageVideo');
            if (!video) return;

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setPassportCameraStatus('Camera not supported in this browser', false);
                return;
            }

            if (!isLocalSecureContext()) {
                const fileInput = document.getElementById('passportPhotoInput');
                setPassportCameraStatus('Use localhost or HTTPS, or choose a photo file from your device.', false);
                if (fileInput) fileInput.click();
                return;
            }

            try {
                await populateCameraDeviceSelect();
                const constraints = getCameraConstraints();
                const stream = await navigator.mediaDevices.getUserMedia(constraints);

                if (cameraStreams[passportCameraStreamKey]) {
                    cameraStreams[passportCameraStreamKey].getTracks().forEach(track => track.stop());
                }

                cameraStreams[passportCameraStreamKey] = stream;
                video.srcObject = stream;
                video.style.display = 'block';
                video.muted = true;
                await video.play();
                setPassportCameraStatus('Camera live - ready to capture', true);
            } catch (error) {
                console.warn('Passport camera failed to start:', error);
                setPassportCameraStatus('Camera permission needed. Click Open Camera to allow access.', false);
                video.srcObject = null;
                video.style.display = 'block';
                video.poster = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480"><rect width="100%" height="100%" fill="#0b0f12"/><text x="50%" y="50%" fill="#ffffff" font-size="22" font-family="Arial" text-anchor="middle">Camera permission needed</text></svg>');
            }
        };

        const capturePassportFrame = () => {
            const video = document.getElementById('passportStageVideo');
            const capturedPreview = document.getElementById('capturedPassportPreview');
            const finalPreview = document.getElementById('finalPassportPreview');
            const input = document.getElementById('passportImageDataInput');
            if (!video || !capturedPreview || !finalPreview || !input) return;

            const canvas = document.createElement('canvas');
            canvas.width = 413;
            canvas.height = 531;
            const context = canvas.getContext('2d');
            if (!context) return;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
            capturedPreview.src = dataUrl;
            capturedPreview.style.display = 'block';
            finalPreview.src = dataUrl;
            finalPreview.style.display = 'block';
            input.value = dataUrl;
            updateReviewSummary();
        };

        const applyPassportEffect = (effect) => {
            if (!effect) return;
            const preview = document.getElementById('capturedPassportPreview');
            const finalPreview = document.getElementById('finalPassportPreview');
            if (!preview || !finalPreview) return;

            const brightness = document.getElementById('passportBrightnessRange')?.value || 100;
            const contrast = document.getElementById('passportContrastRange')?.value || 100;
            const filterValue = `brightness(${brightness}%) contrast(${contrast}%)`;

            if (effect === 'enhance') {
                document.getElementById('passportBrightnessRange').value = 110;
                document.getElementById('passportContrastRange').value = 120;
                preview.style.filter = `brightness(110%) contrast(120%)`;
                finalPreview.style.filter = `brightness(110%) contrast(120%)`;
                return;
            }

            if (effect === 'reset') {
                document.getElementById('passportBrightnessRange').value = 100;
                document.getElementById('passportContrastRange').value = 100;
                preview.style.filter = 'none';
                finalPreview.style.filter = 'none';
                return;
            }

            preview.style.filter = filterValue;
            finalPreview.style.filter = filterValue;
        };

        document.querySelectorAll('[data-passport-effect]').forEach((button) => {
            button.addEventListener('click', () => applyPassportEffect(button.getAttribute('data-passport-effect')));
        });

        document.getElementById('passportBrightnessRange')?.addEventListener('input', () => {
            const brightness = document.getElementById('passportBrightnessRange').value;
            const contrast = document.getElementById('passportContrastRange').value;
            const preview = document.getElementById('capturedPassportPreview');
            const finalPreview = document.getElementById('finalPassportPreview');
            if (preview) preview.style.filter = `brightness(${brightness}%) contrast(${contrast}%)`;
            if (finalPreview) finalPreview.style.filter = `brightness(${brightness}%) contrast(${contrast}%)`;
        });

        document.getElementById('passportContrastRange')?.addEventListener('input', () => {
            const brightness = document.getElementById('passportBrightnessRange').value;
            const contrast = document.getElementById('passportContrastRange').value;
            const preview = document.getElementById('capturedPassportPreview');
            const finalPreview = document.getElementById('finalPassportPreview');
            if (preview) preview.style.filter = `brightness(${brightness}%) contrast(${contrast}%)`;
            if (finalPreview) finalPreview.style.filter = `brightness(${brightness}%) contrast(${contrast}%)`;
        });

        document.getElementById('cameraDeviceSelect')?.addEventListener('change', async () => {
            await startPassportCamera();
        });

        document.getElementById('switchPassportCameraBtn')?.addEventListener('click', async () => {
            const select = document.getElementById('cameraDeviceSelect');
            if (!select) return;

            const realDevices = Array.from(select.options).filter((option) => option.value !== 'auto');
            if (realDevices.length <= 1) {
                await startPassportCamera();
                return;
            }

            const currentIndex = realDevices.findIndex((option) => option.value === select.value);
            const nextIndex = currentIndex >= 0 ? (currentIndex + 1) % realDevices.length : 0;
            select.value = realDevices[nextIndex].value;
            await startPassportCamera();
        });

        document.querySelectorAll('[data-camera-target]').forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-camera-target');
                if (target === 'fingerprint') {
                    const fingerprintStatus = document.getElementById('fingerprintDeviceStatus');
                    const fingerprintText = document.getElementById('fingerprintDeviceText');
                    if (fingerprintStatus && fingerprintText) {
                        fingerprintStatus.classList.remove('not-connected');
                        fingerprintStatus.classList.add('connected');
                        fingerprintText.textContent = 'Scanner fallback ready';
                    }
                }
                captureFromCamera(target);
            });
        });

        document.querySelectorAll('[data-stop-camera]').forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-stop-camera');
                stopCamera(target);
            });
        });

        document.getElementById('openPassportCameraBtn')?.addEventListener('click', async () => {
            await startPassportCamera();
        });
        document.getElementById('capturePassportBtn')?.addEventListener('click', capturePassportFrame);

        document.getElementById('fingerprintImageInput')?.addEventListener('change', () => {
            const fingerprintStatus = document.getElementById('fingerprintDeviceStatus');
            const fingerprintText = document.getElementById('fingerprintDeviceText');
            if (fingerprintStatus && fingerprintText) {
                const hasFile = document.getElementById('fingerprintImageInput').files.length > 0;
                fingerprintStatus.classList.toggle('connected', hasFile);
                fingerprintStatus.classList.toggle('not-connected', !hasFile);
                fingerprintText.textContent = hasFile ? 'Scanner image loaded' : 'No scanner detected';
            }
            updateReviewSummary();
        });

        document.getElementById('passportPhotoInput')?.addEventListener('change', updateReviewSummary);
        document.getElementById('passportStageInput')?.addEventListener('change', updateReviewSummary);
        document.querySelectorAll('input, select').forEach((element) => {
            element.addEventListener('input', () => {
                updateReviewSummary();
                saveDraftFormData();
            });
            element.addEventListener('change', () => {
                updateReviewSummary();
                saveDraftFormData();
            });
        });

        document.getElementById('nextBtn')?.addEventListener('click', () => {
            if (currentStageIndex < wizardStages.length - 1) {
                setStage(currentStageIndex + 1);
            }
        });

        document.getElementById('prevBtn')?.addEventListener('click', () => {
            if (currentStageIndex > 0) {
                setStage(currentStageIndex - 1);
            }
        });

        detectPhotoCamera();
        detectFingerprintScanner();
        setStage(0);

        document.querySelector('.toggle-btn')?.addEventListener('click', () => {
            document.querySelector('.sidebar')?.classList.toggle('open');
        });

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
        document.querySelectorAll('.nav-item, .submenu a').forEach((link) => link.classList.remove('active'));
        document.querySelectorAll('.menu-button').forEach((button) => button.classList.remove('active'));

        const currentLink = [...document.querySelectorAll('.nav-item, .submenu a')].find((link) => {
            const href = normalizePath(link.getAttribute('href') || '');
            return href === currentPath;
        });

        if (currentLink) {
            currentLink.classList.add('active');
            const group = currentLink.closest('.menu-group');
            if (group) {
                group.classList.add('open');
                const button = group.querySelector('.menu-button');
                if (button) button.classList.add('active');
            }
        }

        document.querySelectorAll('.menu-button').forEach((button) => {
            button.addEventListener('click', () => {
                const group = button.closest('.menu-group');
                if (!group) return;
                const isOpen = group.classList.contains('open');
                group.classList.toggle('open');
                button.classList.toggle('active', !isOpen);
            });
        });
    </script>
</body>
</html>
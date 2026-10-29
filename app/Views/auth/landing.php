<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'National Registration Bureau') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= e(base_url('assets/css/landing.css')) ?>">
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a href="<?= e(route_url('/')) ?>" class="brand" aria-label="National Registration Bureau Home">
                <img src="<?=e(base_url('assets/images/logo-mw-67x75.png')) ?>" alt="" style="width: 50px; height: 50px; object-fit: contain;">
                <span class="brand-name">
                    
                            
                <span>Citizen Registry</span></span>
            </a>

            <nav class="desktop-nav" aria-label="Main navigation">
                <a href="#hero">Home</a>
                <a href="#features">Services</a>
                <a href="#workflow">Workflow</a>
                <button type="button" class="nav-trigger-link" data-open-nid-modal>Check NID</button>
                <a href="#contact">Contact</a>
            </nav>

            <div class="topbar-actions">
                <a href="<?= e(route_url('/login')) ?>" class="link-btn">Sign In</a>
                <a href="<?= e(route_url('/login')) ?>" class="primary-btn">Access Portal</a>
                <button class="mobile-toggle" type="button" aria-label="Open menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>

        <div class="mobile-nav" id="mobile-menu">
            <div class="container">
                <a href="#hero">Home</a>
                <a href="#features">Services</a>
                <a href="#workflow">Workflow</a>
                <button type="button" class="nav-trigger-link mobile-nav-trigger" data-open-nid-modal>Check NID</button>
                <a href="#contact">Contact</a>
                <a href="<?= e(route_url('/login')) ?>" class="primary-btn" style="margin-top:8px; width:100%;">Access Portal</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero" id="hero">
            <div class="hero-slide hero-slide-1" aria-hidden="true"></div>
            <div class="hero-slide hero-slide-2" aria-hidden="true"></div>
            <div class="hero-slide hero-slide-3" aria-hidden="true"></div>
            <div class="container hero-inner">
                <div class="hero-badge"> Digital identity and citizen registration platform</div>
                <h1>Secure citizen services <span class="gradient-text">for a modern nation</span></h1>
                <p>National Registration Bureau streamlines birth, identity, verification, and card issuance processes through a secure, transparent and citizen-centered digital workflow.</p>

                <div class="hero-actions">
                    <a href="<?= e(route_url('/login')) ?>" class="primary-btn">Open Secure Portal <i class="fa-solid fa-right-to-bracket"></i></a>
                    <a href="#features" class="secondary-btn"><i class="fa-solid fa-circle-play"></i> Explore Services</a>
                </div>

                <div class="hero-stats">
                    <div class="stat-box"><strong>1.2M+</strong><span>Records managed</span></div>
                    <div class="stat-box"><strong>97%</strong><span>Service accuracy</span></div>
                    <div class="stat-box"><strong>24/7</strong><span>Digital access</span></div>
                </div>
            </div>
        </section>

        <section class="section" id="features">
            <div class="container">
                <div class="section-header">
                    <small>Trusted citizen services</small>
                    <h2>Integrated systems for secure registration and identity management</h2>
                    <p>Every service is designed to improve reliability, accountability, and citizen trust across the national registration process.</p>
                </div>

                <div class="feature-grid">
                    <article class="feature-card"><div class="feature-icon primary"><i class="fa-solid fa-user-plus"></i></div><h3>Citizen Registration</h3><p>Capture identity records with secure intake, demographic validation, and unique national registration number generation.</p></article>
                    <article class="feature-card"><div class="feature-icon accent"><i class="fa-solid fa-fingerprint"></i></div><h3>Biometric Verification</h3><p>Support fingerprint and passport-photo evidence, ensuring identity checks meet compliance and review standards.</p></article>
                    <article class="feature-card"><div class="feature-icon cyan"><i class="fa-solid fa-id-card-clip"></i></div><h3>National ID Issuance</h3><p>Manage approval queues, preview verification, and later card production with clear workflow visibility.</p></article>
                    <article class="feature-card"><div class="feature-icon primary"><i class="fa-solid fa-chart-column"></i></div><h3>Performance Insights</h3><p>Monitor registrations, processing queues, and administrative performance across districts and offices.</p></article>
                    <article class="feature-card"><div class="feature-icon accent"><i class="fa-solid fa-shield-halved"></i></div><h3>Security &amp; Audit</h3><p>Protect sensitive records with role controls, audit trails, and secure access management for operators.</p></article>
                    <article class="feature-card"><div class="feature-icon cyan"><i class="fa-solid fa-users-gear"></i></div><h3>Officer Workflows</h3><p>Provide district administrators, supervisors, and registration officers with structured, role-specific process steps.</p></article>
                </div>
            </div>
        </section>

        <section class="section workflow-section" id="workflow">
            <div class="container workflow-grid">
                <div class="workflow-copy">
                    <small>Registration workflow</small>
                    <h2>A secure process from capture to issuance</h2>
                    <p>The citizen lifecycle is monitored through transparent approval stages, from initial registration to preview review and final card production.</p>
                    <div class="workflow-nav">
                        <button class="workflow-step active" data-panel="application" type="button"><span class="workflow-step-icon primary"><i class="fa-solid fa-file-lines"></i></span><span><h4>Application Intake</h4><p>Capture and validate citizen details</p></span></button>
                        <button class="workflow-step" data-panel="review" type="button"><span class="workflow-step-icon accent"><i class="fa-solid fa-user-check"></i></span><span><h4>Admin Review</h4><p>Approve submitted applications</p></span></button>
                        <button class="workflow-step" data-panel="print" type="button"><span class="workflow-step-icon cyan"><i class="fa-solid fa-print"></i></span><span><h4>Card Issuance</h4><p>Prepare and print approved IDs</p></span></button>
                    </div>
                </div>

                <div class="workflow-card">
                    <div class="workflow-panel active" data-panel-content="application">
                        <div class="panel-header"><div><h3>Citizen intake process</h3><p>Verified personal, biographic and household data</p></div><span class="panel-status green">Live capture</span></div>
                        <div class="chart-bars"><span style="height:42%;"></span><span style="height:58%;"></span><span style="height:82%;"></span><span style="height:48%;"></span><span style="height:72%;"></span><span style="height:96%;"></span></div>
                    </div>
                    <div class="workflow-panel" data-panel-content="review">
                        <div class="panel-header"><div><h3>Approval review status</h3><p>Stage-based supervision by authorized officers</p></div><span class="panel-status blue">Manual check</span></div>
                        <div class="panel-list"><div class="row"><span>Applicant details validated</span><strong>CONFIRMED</strong></div><div class="row"><span>Supporting documents verified</span><strong>CLEARED</strong></div><div class="row"><span>Officer approval match</span><strong>APPROVED</strong></div></div>
                    </div>
                    <div class="workflow-panel" data-panel-content="print">
                        <div class="panel-header"><div><h3>Card production workflow</h3><p>From approval to printed national ID</p></div><span class="panel-status indigo">Queue ready</span></div>
                        <div class="progress-block"><div><div class="label"><span>Approval queue</span><span>68%</span></div><div class="progress-track"><span class="progress-fill" style="width:68%;"></span></div></div><div><div class="label"><span>Printing station</span><span>54%</span></div><div class="progress-track"><span class="progress-fill" style="width:54%;"></span></div></div><div><div class="label"><span>Delivery &amp; issuance</span><span>32%</span></div><div class="progress-track"><span class="progress-fill" style="width:32%;"></span></div></div></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="stats">
            <div class="container">
                <div class="section-header">
                    <small>Public trust</small>
                    <h2>Recognized by institutions and citizens</h2>
                </div>
                <div class="testimonial-grid">
                    <article class="testimonial-card"><div class="star-row"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p>“The process is transparent and much faster...”</p><div class="person"><img src="<?=e(base_url('assets/images/paul-mukhitho.png')) ?>" alt="Nadia Chirwa"><div><strong>Nadia Chirwa</strong><span>District Registration Lead</span></div></div></article>
                    <article class="testimonial-card"><div class="star-row"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p>“The digital workflow gave us confidence...”</p><div class="person"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150&auto=format&fit=crop" alt="Joseph Banda"><div><strong>Joseph Banda</strong><span>National ID Supervisor</span></div></div></article>
                    <article class="testimonial-card"><div class="star-row"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p>“It is easier for citizens to know their application stage...”</p><div class="person"><img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=150&auto=format&fit=crop" alt="Grace Mponda"><div><strong>Grace Mponda</strong><span>Operations Officer</span></div></div></article>
                </div>
            </div>
        </section>

        <section class="cta-wrap section">
            <div class="container">
                <div class="cta-panel">
                    <h2>Ready to serve citizens with confidence?</h2>
                    <p>Access the secure portal and continue managing citizen registration, review, and issuance effortlessly.</p>
                    <a href="<?= e(route_url('/login')) ?>" class="primary-btn" style="background:#fff;color:#081b1b;">Login to System</a>
                </div>
            </div>
        </section>

        <section class="section" id="contact">
            <div class="container contact-grid">
                <div class="contact-copy">
                    <small>Get in touch</small>
                    <h2>Support for national registration services</h2>
                    <p>For support with citizen registration, data verification, workflow coordination, or system access, our team is ready to assist.</p>
                    <div class="contact-item"><div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div><div><h4>Head Office</h4><p>National Registration Bureau, Lilongwe, Malawi</p></div></div>
                    <div class="contact-item"><div class="contact-icon"><i class="fa-solid fa-envelope"></i></div><div><h4>Support Email</h4><p>support@nrb.gov.mw</p></div></div>
                    <div class="contact-item"><div class="contact-icon"><i class="fa-solid fa-phone"></i></div><div><h4>Help Line</h4><p>+265 1 234 567</p></div></div>
                </div>

                <div class="contact-panel">
                    <form class="contact-form" id="contact-form">
                        <div><label class="field-label" for="name">Full Name</label><input id="name" type="text" placeholder="John Doe" required></div>
                        <div><label class="field-label" for="email">Email Address</label><input id="email" type="email" placeholder="john@agency.mw" required></div>
                        <div><label class="field-label" for="message">Message</label><textarea id="message" rows="4" placeholder="Tell us how we can help..." required></textarea></div>
                        <button type="submit" class="primary-btn" style="width:100%;">Send Message <i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                    <div class="form-message" id="contact-message">Message sent successfully! Our team will respond shortly.</div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand"><span class="footer-brand-mark"><i class="fa-solid fa-id-card"></i></span> NRB</div>
                    <p class="footer-copy">Secure, transparent and reliable citizen registration services for identity management, approvals and national ID issuance.</p>
                    <div class="footer-socials"><a href="#"><i class="fa-brands fa-twitter"></i></a><a href="#"><i class="fa-brands fa-facebook-f"></i></a><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></div>
                </div>
                <div class="footer-links"><h4>Services</h4><ul><li><a href="#features">Registration</a></li><li><a href="#features">Biometrics</a></li><li><a href="#workflow">Approval Flow</a></li><li><a href="#features">ID Issuance</a></li></ul></div>
                <div class="footer-links"><h4>Resources</h4><ul><li><a href="#">Documentation</a></li><li><a href="#">FAQs</a></li><li><a href="#">Officer Guidance</a></li><li><a href="#">System Status</a></li></ul></div>
                <div class="footer-links"><h4>Company</h4><ul><li><a href="#">About NRB</a></li><li><a href="#">Public Policy</a></li><li><a href="#">Privacy Notice</a></li><li><a href="#">Terms</a></li></ul></div>
            </div>
            <div class="footer-bottom">&copy; <span id="current-year"></span> National Registration Bureau. All rights reserved.</div>
        </div>
    </footer>

    <div class="nid-modal-backdrop" id="nid-modal-backdrop" aria-hidden="true">
        <div class="nid-modal" role="dialog" aria-modal="true" aria-labelledby="nid-modal-title">
            <button type="button" class="nid-modal-close" id="nid-modal-close" aria-label="Close dialog">×</button>
            <div class="nid-modal-header">
                <small>Citizen self-service</small>
                <h3 id="nid-modal-title">Check NID Status</h3>
            </div>

            <form id="nid-search-form" class="nid-search-form" method="POST" action="<?= e(route_url('/check-nid')) ?>">
                <label class="field-label" for="nid_modal_input">NID Number</label>
                <div class="nid-search-row">
                    <input id="nid_modal_input" name="nid_number" type="text" placeholder="Enter NID number" required>
                    <button type="submit" class="primary-btn">Search</button>
                </div>
            </form>

            <div id="nid-search-loader" class="nid-loader hidden" aria-live="polite">
                <div class="spinner-ring"></div>
                <div class="spinner-text">Searching...</div>
            </div>

            <div id="nid-result-panel" class="nid-result-panel hidden" aria-live="polite"></div>
        </div>
    </div>

    <style>
        .nid-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            z-index: 9999;
        }
        .nid-modal-backdrop.open { display: flex; }
        body.modal-open { overflow: hidden; }
        .nid-modal {
            position: relative;
            width: min(920px, 94vw);
            background: #ffffff;
            border-radius: 22px;
            padding: 2rem 1.5rem 1.5rem;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.25);
            border: 1px solid rgba(15, 23, 42, 0.08);
        }
        .nid-modal-close {
            position: absolute;
            right: 1rem;
            top: 0.9rem;
            border: none;
            background: transparent;
            font-size: 2rem;
            color: #475569;
            cursor: pointer;
            line-height: 1;
        }
        .nav-trigger-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: rgba(255, 255, 255, 0.04);
            color: #e2e8f0;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            transition: all 0.2s ease;
        }
        .nav-trigger-link:hover {
            background: rgba(37, 99, 235, 0.12);
            border-color: rgba(96, 165, 250, 0.55);
            color: #ffffff;
        }
        .mobile-nav-trigger {
            width: 100%;
            margin: 0.35rem 0;
        }
        .nid-modal-header small {
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }
        .nid-modal-header h3 {
            margin: 0.35rem 0 0;
            font-size: clamp(1.8rem, 2vw, 2.3rem);
            color: #0f172a;
        }
        .nid-search-form { margin-top: 1.3rem; }
        .field-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #0f172a;
        }
        .nid-search-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .nid-search-row input {
            flex: 1 1 300px;
            min-height: 52px;
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            padding: 0.9rem 1rem;
            background: #f8fafc;
        }
        .nid-search-row button { min-height: 52px; }
        .nid-loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 2rem 1rem 1rem;
        }
        .spinner-ring {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 6px solid rgba(37, 99, 235, 0.16);
            border-top-color: #2563eb;
            animation: spin 1s linear infinite;
        }
        .spinner-text {
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.04em;
        }
        .hidden { display: none !important; }
        .nid-result-panel { margin-top: 1.2rem; }
        .nid-result-card {
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.09);
            border-radius: 18px;
            padding: 1.2rem;
        }
        .nid-result-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        .nid-result-head small {
            color: #2563eb;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
        }
        .nid-result-head h4 {
            margin: 0.35rem 0 0;
            color: #0f172a;
        }
        .nid-result-badge {
            background: rgba(21, 128, 61, 0.1);
            color: #166534;
            padding: 0.5rem 0.8rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.75rem;
        }
        .nid-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.8rem;
            margin-bottom: 1rem;
        }
        .nid-meta-item {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            padding: 0.9rem 0.85rem;
        }
        .nid-meta-item small {
            display: block;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.06em;
            margin-bottom: 0.45rem;
        }
        .nid-status-header h4 { margin: 0 0 0.75rem; color: #0f172a; }
        .nid-timeline { position: relative; margin-top: 0.5rem; }
        .nid-timeline::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 12px;
            bottom: 12px;
            width: 2px;
            background: rgba(37, 99, 235, 0.18);
        }
        .nid-timeline-item {
            position: relative;
            padding-left: 3.2rem;
            margin-bottom: 1rem;
        }
        .nid-timeline-item::before {
            content: "";
            position: absolute;
            left: 7px;
            top: 14px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid #fff;
            background: #f4b740;
            box-shadow: 0 0 0 3px rgba(244, 183, 64, 0.18);
        }
        .nid-timeline-item.completed::before {
            background: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }
        .nid-timeline-box {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 14px;
            padding: 0.9rem 1rem;
        }
        .nid-timeline-box strong {
            display: block;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }
        .nid-timeline-box small {
            display: block;
            color: #64748b;
            margin-bottom: 0.35rem;
        }
        .nid-not-found, .nid-empty-state {
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 14px;
            color: #9a1c2d;
            padding: 1rem 1.1rem;
            font-weight: 600;
        }

        .nid-card-shell {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            width: 100%;
            color: #f8fafc;
        }
        .nid-result-layout {
            display: flex;
            align-items: stretch;
            justify-content: center;
            gap: 18px;
            width: 100%;
        }
        .nid-status-panel {
            flex: 0 0 270px;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 18px;
            padding: 1rem 0.95rem;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
            color: #0f172a;
            min-height: 315px;
            display: flex;
            flex-direction: column;
        }
        .nid-status-header {
            margin-bottom: 0.85rem;
        }
        .nid-status-header small {
            display: block;
            color: #2563eb;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }
        .nid-status-header h4 {
            margin: 0;
            font-size: 1.05rem;
            color: #0f172a;
        }
        .nid-status-list {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        .nid-status-item {
            position: relative;
            padding-left: 2.2rem;
            color: #334155;
            line-height: 1.45;
        }
        .nid-status-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.2rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #cbd5e1;
            border: 3px solid #ffffff;
            box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.25);
        }
        .nid-status-item.completed::before {
            background: #22c55e;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.18);
        }
        .nid-status-item.active::before {
            background: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.18);
        }
        .nid-status-item strong {
            display: block;
            color: #0f172a;
            font-size: 0.86rem;
            margin-bottom: 0.18rem;
        }
        .nid-status-item span {
            display: block;
            color: #64748b;
            font-size: 0.76rem;
            line-height: 1.4;
        }
        .nid-card-scene {
            width: min(500px, 100%);
            height: 315px;
            perspective: 1200px;
            margin: 10px 0;
        }
        .nid-card-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.8s cubic-bezier(0.4, 0.2, 0.2, 1);
            cursor: pointer;
            border-radius: 16px;
        }
        .nid-card-wrapper.is-flipped {
            transform: rotateY(180deg);
        }
        .nid-card-face {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.1);
            background-color: #f6f8f5;
            color: #111111;
        }
        .nid-card-front {
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 2;
        }
        .nid-card-back {
            transform: rotateY(180deg);
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            background-color: #f4f6f0;
        }
        .nid-guilloche-bg {
            position: absolute;
            inset: 0;
            z-index: 1;
            opacity: 0.35;
            pointer-events: none;
        }
        .nid-watermark-emblem {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -40%);
            width: 220px;
            height: 220px;
            opacity: 0.08;
            z-index: 1;
            pointer-events: none;
        }
        .nid-hologram-overlay {
            position: absolute;
            inset: 0;
            z-index: 10;
            pointer-events: none;
            background: linear-gradient(135deg, rgba(255,255,255,0) 0%, rgba(255,0,150,0.05) 35%, rgba(0,200,255,0.08) 50%, rgba(255,255,0,0.05) 65%, rgba(255,255,255,0) 100%);
        }
        .nid-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 2;
            position: relative;
        }
        .nid-coat-of-arms {
            width: 48px;
            height: 48px;
        }
        .nid-header-titles {
            text-align: center;
            flex-grow: 1;
            padding: 0 8px;
        }
        .nid-header-titles h1 {
            font-size: 19px;
            font-weight: 900;
            color: #000000;
            letter-spacing: -0.2px;
            margin-bottom: 2px;
        }
        .nid-header-titles h2 {
            font-size: 10px;
            font-weight: 700;
            color: #222222;
        }
        .nid-malawi-flag {
            width: 48px;
            height: 30px;
            border-radius: 2px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .nid-card-body {
            display: flex;
            gap: 12px;
            z-index: 2;
            position: relative;
            margin-top: 4px;
        }
        .nid-photo-box {
            width: 110px;
            height: 140px;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
            box-shadow: inset 0 0 4px rgba(0,0,0,0.1);
        }
        .nid-photo-box svg {
            width: 100%;
            height: 100%;
            display: block;
        }
        .nid-fields-container {
            flex-grow: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 10px;
        }
        .nid-field-group {
            display: flex;
            flex-direction: column;
        }
        .nid-field-group.full-width {
            grid-column: span 2;
        }
        .nid-label {
            font-size: 7.5px;
            color: #475569;
            font-weight: 600;
            line-height: 1.1;
        }
        .nid-value {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .nid-value-highlight {
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        .nid-chip-graphic {
            width: 44px;
            height: 34px;
            position: absolute;
            right: 12px;
            top: 105px;
            z-index: 3;
        }
        .nid-mwi-hologram-text {
            position: absolute;
            right: 60px;
            top: 112px;
            font-size: 18px;
            font-weight: 900;
            color: rgba(180, 83, 9, 0.35);
            letter-spacing: 2px;
            z-index: 2;
        }
        .nid-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            z-index: 2;
            position: relative;
            padding-top: 4px;
        }
        .nid-signature-area {
            width: 110px;
            height: 28px;
        }
        .nid-dates-grid {
            display: flex;
            gap: 20px;
        }
        .nid-back-content-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 2;
        }
        .nid-qr-and-sig-group {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .nid-qr-box {
            width: 90px;
            height: 90px;
            background: white;
            padding: 4px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .nid-sig-and-thumb {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .nid-auth-sig-title {
            font-size: 8px;
            font-weight: 700;
            color: #334155;
        }
        .nid-fingerprint-icon {
            width: 38px;
            height: 48px;
            opacity: 0.8;
            margin-top: 2px;
        }
        .nid-bureau-title-right {
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.3px;
            color: #0f172a;
            text-align: right;
            max-width: 150px;
            line-height: 1.3;
        }
        .nid-mrz-zone {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 1.5px;
            line-height: 1.3;
            color: #000000;
            background: rgba(255, 255, 255, 0.5);
            padding: 6px 8px;
            border-radius: 4px;
            z-index: 2;
            width: 100%;
        }
        .nid-card-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            width: 100%;
        }
        .nid-card-flip-btn {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 20px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nid-card-flip-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }
        .nid-card-timer {
            width: 300px;
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }
        .nid-card-timer-bar {
            height: 100%;
            width: 100%;
            background: #38bdf8;
            border-radius: 3px;
            transform-origin: left;
        }
        .nid-card-status {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
        }
        @media (max-width: 740px) {
            .nid-result-layout {
                flex-direction: column;
            }
            .nid-status-panel {
                width: 100%;
                max-width: 500px;
                min-height: auto;
                flex-basis: auto;
            }
        }
        @media (max-width: 540px) {
            .nid-card-scene {
                width: 100%;
                height: 214px;
            }
            .nid-coat-of-arms, .nid-malawi-flag { width: 32px; height: 32px; }
            .nid-header-titles h1 { font-size: 13px; }
            .nid-header-titles h2 { font-size: 7px; }
            .nid-photo-box { width: 75px; height: 95px; }
            .nid-value { font-size: 9px; }
            .nid-value-highlight { font-size: 10px; }
            .nid-label { font-size: 5.5px; }
            .nid-chip-graphic { width: 30px; height: 23px; top: 75px; right: 8px; }
            .nid-mwi-hologram-text { font-size: 12px; top: 80px; right: 42px; }
            .nid-signature-area { width: 75px; height: 20px; }
            .nid-mrz-zone { font-size: 8.5px; letter-spacing: 0.8px; }
            .nid-qr-box { width: 60px; height: 60px; }
            .nid-fingerprint-icon { width: 26px; height: 34px; }
            .nid-bureau-title-right { font-size: 9.5px; }
            .nid-card-timer { width: 220px; }
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (max-width: 640px) {
            .nid-search-row { flex-direction: column; }
            .nid-search-row button { width: 100%; }
        }
    </style>

    <script>
        const yearEl = document.getElementById('current-year');
        if (yearEl) {
            yearEl.textContent = new Date().getFullYear();
        }

        const modalBackdrop = document.getElementById('nid-modal-backdrop');
        const closeModalButton = document.getElementById('nid-modal-close');
        const openModalButtons = document.querySelectorAll('[data-open-nid-modal]');

        function openNidModal() {
            if (!modalBackdrop) return;
            modalBackdrop.classList.add('open');
            modalBackdrop.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');

            const input = document.getElementById('nid_modal_input');
            if (input) {
                setTimeout(function () { input.focus(); }, 100);
            }
        }

        function closeNidModal() {
            if (!modalBackdrop) return;
            modalBackdrop.classList.remove('open');
            modalBackdrop.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');

            const form = document.getElementById('nid-search-form');
            const loader = document.getElementById('nid-search-loader');
            const resultPanel = document.getElementById('nid-result-panel');
            if (form) form.classList.remove('hidden');
            if (loader) loader.classList.add('hidden');
            if (resultPanel) {
                resultPanel.classList.add('hidden');
                resultPanel.innerHTML = '';
            }
        }

        if (openModalButtons.length) {
            openModalButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    openNidModal();
                });
            });
        }

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest && event.target.closest('[data-open-nid-modal]');
            if (trigger) {
                event.preventDefault();
                openNidModal();
            }
        });

        if (closeModalButton) {
            closeModalButton.addEventListener('click', closeNidModal);
        }

        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', function (event) {
                if (event.target === modalBackdrop) {
                    closeNidModal();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modalBackdrop && modalBackdrop.classList.contains('open')) {
                closeNidModal();
            }
        });

        const menuButton = document.querySelector('.mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('open');
            });
        }

        const workflowSteps = document.querySelectorAll('.workflow-step');
        const workflowPanels = document.querySelectorAll('.workflow-panel');
        workflowSteps.forEach(function (step) {
            step.addEventListener('click', function () {
                workflowSteps.forEach(function (item) { item.classList.remove('active'); });
                workflowPanels.forEach(function (panel) { panel.classList.remove('active'); });
                step.classList.add('active');
                const target = step.getAttribute('data-panel');
                const panel = document.querySelector('[data-panel-content="' + target + '"]');
                if (panel) { panel.classList.add('active'); }
            });
        });

        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', function (event) {
                event.preventDefault();
                const message = document.getElementById('contact-message');
                if (message) {
                    message.style.display = 'block';
                }
                event.target.reset();
                setTimeout(function () {
                    if (message) {
                        message.style.display = 'none';
                    }
                }, 3000);
            });
        }

        function formatDisplayDate(inputDate) {
            if (!inputDate) {
                return 'N/A';
            }

            const parsed = new Date(inputDate);
            if (Number.isNaN(parsed.getTime())) {
                return inputDate;
            }

            return parsed.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function formatIssueDate(inputDate) {
            const issueDate = inputDate ? new Date(inputDate) : new Date();
            if (Number.isNaN(issueDate.getTime())) {
                return 'N/A';
            }

            return issueDate.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function formatExpiryDate(inputDate) {
            const baseDate = inputDate ? new Date(inputDate) : new Date();
            if (Number.isNaN(baseDate.getTime())) {
                return 'N/A';
            }

            const expiry = new Date(baseDate);
            expiry.setFullYear(expiry.getFullYear() + 10);

            return expiry.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function renderNidResult(data) {
            const resultPanel = document.getElementById('nid-result-panel');
            const form = document.getElementById('nid-search-form');
            const loader = document.getElementById('nid-search-loader');

            if (!resultPanel || !form || !loader) return;

            form.classList.add('hidden');
            loader.classList.add('hidden');
            resultPanel.classList.remove('hidden');

            if (!data || !data.success || !data.citizen) {
                resultPanel.innerHTML = '<div class="nid-not-found">No record was found for this NID number. Please double-check the value and try again.</div>';
                return;
            }

            const citizen = data.citizen;
            const fullName = [citizen.first_name || '', citizen.middle_name || '', citizen.surname || ''].join(' ').replace(/\s+/g, ' ').trim();
            const idNumber = citizen.national_id_number || citizen.national_registration_number || 'N/A';
            const surname = citizen.surname || 'N/A';
            const givenNames = [citizen.first_name || '', citizen.middle_name || ''].filter(Boolean).join(', ') || 'N/A';
            const gender = (citizen.gender || 'Other').toString();
            const genderCode = gender.charAt(0).toUpperCase();
            const dob = formatDisplayDate(citizen.date_of_birth || '');
            const nationality = citizen.nationality || 'MWI';
            const issueDate = formatIssueDate(citizen.registration_date || new Date());
            const expiryDate = formatExpiryDate(citizen.registration_date || new Date());
            const mrzLine1 = `I<MWI${idNumber.replace(/[^A-Za-z0-9]/g, '').slice(0, 12)}<`;
            const mrzLine2 = `${dob.replace(/[^0-9]/g, '').slice(0, 6)}${genderCode === 'M' ? 'M' : 'F'}${expiryDate.replace(/[^0-9]/g, '').slice(0, 6)}MWI<<`;

const stageMarkup = Array.isArray(data.stages) && data.stages.length
                ? data.stages.map(function (stage, index) {
                    const stageStatus = (stage && stage.status) || 'pending';
                    const cssClass = stageStatus === 'completed' ? 'completed' : (stageStatus === 'current' ? 'active' : '');
                    const label = (stage && stage.label) || 'Application Step';
                    const description = (stage && stage.description) || '';
                    const date = (stage && stage.date) || '';
                    return `
                        <div class="nid-status-item ${cssClass}">
                            <strong>${index + 1}. ${label}</strong>
                            ${date ? `<span>${date}</span>` : ''}
                            ${description ? `<span>${description}</span>` : ''}
                        </div>
                    `;
                }).join('')
                : '<div class="nid-status-item"><strong>No status available</strong><span>Application updates will appear here.</span></div>';

            resultPanel.innerHTML = `
                <div class="nid-card-shell">
                    <div class="nid-result-layout">
                        <div class="nid-card-scene">
                            <div class="nid-card-wrapper" id="resultIdCard" title="Click to flip card">
                                <div class="nid-card-face nid-card-front">
                                    <svg class="nid-guilloche-bg" viewBox="0 0 500 315" xmlns="http://www.w3.org/2000/svg">
                                        <g stroke="#16a34a" stroke-width="0.3" fill="none">
                                            <circle cx="250" cy="157" r="80" />
                                            <circle cx="250" cy="157" r="100" />
                                            <circle cx="250" cy="157" r="120" />
                                            <path d="M0,0 Q250,300 500,0 M0,315 Q250,15 500,315" />
                                            <path d="M0,50 Q250,350 500,50 M0,265 Q250,-35 500,265" />
                                        </g>
                                        <g stroke="#dc2626" stroke-width="0.3" fill="none">
                                            <circle cx="250" cy="157" r="90" />
                                            <circle cx="250" cy="157" r="110" />
                                            <path d="M0,157 Q250,0 500,157 M0,157 Q250,315 500,157" />
                                        </g>
                                    </svg>
                                    <svg class="nid-watermark-emblem" viewBox="0 0 100 100" fill="#000">
                                        <path d="M50 10 L80 30 L80 70 L50 90 L20 70 L20 30 Z" fill="none" stroke="#000" stroke-width="2"/>
                                        <circle cx="50" cy="50" r="20" fill="none" stroke="#000" stroke-width="1.5"/>
                                    </svg>
                                    <div class="nid-hologram-overlay"></div>
                                    <div class="nid-card-header">
                                        <svg class="nid-coat-of-arms" viewBox="0 0 100 100">
                                            <g fill="#d97706">
                                                <path d="M50 5 L65 25 L85 25 L70 40 L75 60 L50 48 L25 60 L30 40 L15 25 L35 25 Z" />
                                                <text x="50" y="85" font-size="10" text-anchor="middle" font-weight="bold" fill="#1e293b">UNITY AND FREEDOM</text>
                                            </g>
                                        </svg>
                                        <div class="nid-header-titles">
                                            <h1>Republic of Malawi</h1>
                                            <h2>Chiphaso cha Nzika / Citizen Identification</h2>
                                        </div>
                                        <svg class="nid-malawi-flag" viewBox="0 0 60 36">
                                            <rect width="60" height="12" y="0" fill="#000000" />
                                            <rect width="60" height="12" y="12" fill="#dc2626" />
                                            <rect width="60" height="12" y="24" fill="#16a34a" />
                                            <circle cx="30" cy="12" r="7" fill="#dc2626" />
                                        </svg>
                                    </div>
                                    <div class="nid-card-body">
                                        <div class="nid-photo-box">
                                            <svg viewBox="0 0 110 140" preserveAspectRatio="none">
                                                <rect width="110" height="140" fill="#1e293b"/>
                                                <circle cx="55" cy="50" r="28" fill="#475569"/>
                                                <path d="M15,130 C15,90 35,80 55,80 C75,80 95,90 95,130 Z" fill="#475569"/>
                                            </svg>
                                        </div>
                                        <div class="nid-fields-container">
                                            <div class="nid-field-group full-width">
                                                <span class="nid-label">Dzina la Bambo / Surname</span>
                                                <span class="nid-value">${surname}</span>
                                            </div>
                                            <div class="nid-field-group full-width">
                                                <span class="nid-label">Dzina, Maina Ena / Name, Other Names</span>
                                                <span class="nid-value">${givenNames}</span>
                                            </div>
                                            <div class="nid-field-group">
                                                <span class="nid-label">Mwamuna-Mkazi / Sex</span>
                                                <span class="nid-value">${genderCode}</span>
                                            </div>
                                            <div class="nid-field-group">
                                                <span class="nid-label">Tsiku Lobadwa / Date of Birth</span>
                                                <span class="nid-value">${dob}</span>
                                            </div>
                                            <div class="nid-field-group">
                                                <span class="nid-label">Nambala ya Chiphaso / Identification No</span>
                                                <span class="nid-value nid-value-highlight">${idNumber}</span>
                                            </div>
                                            <div class="nid-field-group">
                                                <span class="nid-label">Unzika / Nationality</span>
                                                <span class="nid-value">${nationality}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <svg class="nid-chip-graphic" viewBox="0 0 44 34">
                                        <rect width="44" height="34" rx="4" fill="#d97706"/>
                                        <rect x="2" y="2" width="40" height="30" rx="3" fill="#fbbf24"/>
                                        <path d="M2,11 L42,11 M2,23 L42,23 M15,2 L15,32 M29,2 L29,32" stroke="#b45309" stroke-width="1.2"/>
                                    </svg>
                                    <span class="nid-mwi-hologram-text">MWI</span>
                                    <div class="nid-card-footer">
                                        <div class="nid-signature-area">
                                            <svg width="100%" height="100%" viewBox="0 0 110 28">
                                                <path d="M10,18 C25,5 35,25 45,12 C55,0 60,22 75,15 C85,10 95,20 100,8" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <div class="nid-dates-grid">
                                            <div class="nid-field-group">
                                                <span class="nid-label">Tsiku Lolandira Chiphaso / Date of Issue</span>
                                                <span class="nid-value" style="font-size: 11px;">${issueDate}</span>
                                            </div>
                                            <div class="nid-field-group">
                                                <span class="nid-label">Tsiku Lothera Chiphaso / Date of Expiry</span>
                                                <span class="nid-value" style="font-size: 11px;">${expiryDate}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nid-card-face nid-card-back">
                                    <svg class="nid-guilloche-bg" viewBox="0 0 500 315" xmlns="http://www.w3.org/2000/svg">
                                        <g stroke="#16a34a" stroke-width="0.3" fill="none">
                                            <circle cx="150" cy="157" r="110" />
                                            <path d="M0,0 Q250,300 500,0 M0,315 Q250,15 500,315" />
                                        </g>
                                    </svg>
                                    <div class="nid-back-content-top">
                                        <div class="nid-qr-and-sig-group">
                                            <div class="nid-qr-box">
                                                <svg width="100%" height="100%" viewBox="0 0 100 100">
                                                    <rect width="100" height="100" fill="#ffffff"/>
                                                    <rect x="5" y="5" width="25" height="25" fill="#000"/>
                                                    <rect x="9" y="9" width="17" height="17" fill="#fff"/>
                                                    <rect x="13" y="13" width="9" height="9" fill="#000"/>
                                                    <rect x="70" y="5" width="25" height="25" fill="#000"/>
                                                    <rect x="74" y="9" width="17" height="17" fill="#fff"/>
                                                    <rect x="78" y="13" width="9" height="9" fill="#000"/>
                                                    <rect x="5" y="70" width="25" height="25" fill="#000"/>
                                                    <rect x="9" y="74" width="17" height="17" fill="#fff"/>
                                                    <rect x="13" y="78" width="9" height="9" fill="#000"/>
                                                    <rect x="35" y="10" width="6" height="6" fill="#000"/>
                                                    <rect x="45" y="15" width="12" height="6" fill="#000"/>
                                                    <rect x="38" y="25" width="6" height="12" fill="#000"/>
                                                    <rect x="10" y="38" width="15" height="6" fill="#000"/>
                                                    <rect x="30" y="40" width="8" height="8" fill="#000"/>
                                                    <rect x="45" y="35" width="15" height="6" fill="#000"/>
                                                    <rect x="65" y="40" width="10" height="10" fill="#000"/>
                                                    <rect x="80" y="35" width="12" height="6" fill="#000"/>
                                                    <rect x="35" y="55" width="8" height="18" fill="#000"/>
                                                    <rect x="50" y="50" width="18" height="8" fill="#000"/>
                                                    <rect x="75" y="55" width="15" height="15" fill="#000"/>
                                                    <rect x="40" y="80" width="20" height="8" fill="#000"/>
                                                    <rect x="68" y="75" width="8" height="18" fill="#000"/>
                                                </svg>
                                            </div>
                                            <div class="nid-sig-and-thumb">
                                                <span class="nid-auth-sig-title">Authorization Signature</span>
                                                <svg width="110" height="26" viewBox="0 0 100 30">
                                                    <path d="M5,20 C15,5 25,25 40,10 C50,2 65,28 80,12 C88,5 95,15 98,10" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                                <svg class="nid-fingerprint-icon" viewBox="0 0 50 60">
                                                    <path d="M25,5 C15,5 8,13 8,25 C8,37 12,48 25,55 C38,48 42,37 42,25 C42,13 35,5 25,5 Z" fill="none" stroke="#475569" stroke-width="1.5" />
                                                    <path d="M25,10 C18,10 13,16 13,25 C13,35 16,43 25,48 C34,43 37,35 37,25 C37,16 32,10 25,10 Z" fill="none" stroke="#475569" stroke-width="1.5" />
                                                    <path d="M25,16 C20,16 17,20 17,25 C17,31 19,37 25,41 C31,37 33,31 33,25 C33,20 30,16 25,16 Z" fill="none" stroke="#475569" stroke-width="1.5" />
                                                    <path d="M25,21 C22,21 21,23 21,25 C21,28 22,31 25,34 C28,31 29,28 29,25 C29,23 28,21 25,21 Z" fill="none" stroke="#475569" stroke-width="1.5" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="nid-bureau-title-right">National Registration Bureau</div>
                                    </div>
                                    <div class="nid-mrz-zone">
                                        I&lt;MWI${(idNumber || 'N/A').replace(/[^A-Za-z0-9]/g, '').slice(0, 12)}&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;<br>
                                        ${dob.replace(/[^0-9]/g, '').slice(0, 6)}${genderCode === 'M' ? 'M' : 'F'}${expiryDate.replace(/[^0-9]/g, '').slice(0, 6)}MWI&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;4<br>
                                        ${surname.toUpperCase()}&lt;&lt;${givenNames.toUpperCase()}&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;
                                    </div>
                                </div>
                            </div>
                        </div>

                        <aside class="nid-status-panel">
                            <div class="nid-status-header">
                                <small>NID Status</small>
                                <h4>Application Timeline</h4>
                            </div>
                            <div class="nid-status-list">
                                ${stageMarkup}
                            </div>
                        </aside>
                    </div>

                    <div class="nid-card-controls">
                        <button type="button" class="nid-card-flip-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                            </svg>
                            Flip Card Side
                        </button>
                        <div class="nid-card-timer"><div class="nid-card-timer-bar" id="nidTimerBar"></div></div>
                        <span class="nid-card-status" id="nidStatusText">Showing Front Side (Auto-flipping in 10s)</span>
                    </div>
                </div>
            `;

            const resultCardWrapper = resultPanel.querySelector('#resultIdCard');
            const resultFlipButton = resultPanel.querySelector('.nid-card-flip-btn');
            const nidTimerBar = resultPanel.querySelector('#nidTimerBar');
            const nidStatusText = resultPanel.querySelector('#nidStatusText');

            if (resultCardWrapper && resultFlipButton && nidTimerBar && nidStatusText) {
                let isFlipped = false;
                let startTime = Date.now();
                let frameId = null;

                const resetTimer = function () {
                    if (frameId) {
                        cancelAnimationFrame(frameId);
                    }
                    startTime = Date.now();
                    frameId = requestAnimationFrame(updateTimer);
                };

                const flipCard = function () {
                    isFlipped = !isFlipped;
                    if (isFlipped) {
                        resultCardWrapper.classList.add('is-flipped');
                        nidStatusText.textContent = 'Showing Back Side (Auto-flipping in 10s)';
                    } else {
                        resultCardWrapper.classList.remove('is-flipped');
                        nidStatusText.textContent = 'Showing Front Side (Auto-flipping in 10s)';
                    }
                    resetTimer();
                };

                const updateTimer = function () {
                    const elapsed = Date.now() - startTime;
                    const remaining = Math.max(0, 10000 - elapsed);
                    const progressRatio = remaining / 10000;
                    nidTimerBar.style.transform = 'scaleX(' + progressRatio + ')';

                    if (elapsed >= 10000) {
                        flipCard();
                    } else {
                        frameId = requestAnimationFrame(updateTimer);
                    }
                };

                resultFlipButton.addEventListener('click', function () {
                    flipCard();
                });

                resultCardWrapper.addEventListener('click', function () {
                    flipCard();
                });

                resetTimer();
            }
        }

        const nidSearchForm = document.getElementById('nid-search-form');
        if (nidSearchForm) {
            nidSearchForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                const input = document.getElementById('nid_modal_input');
                const nidValue = input ? input.value.trim() : '';
                const loader = document.getElementById('nid-search-loader');
                const resultPanel = document.getElementById('nid-result-panel');

                if (!nidValue) {
                    if (input) input.focus();
                    return;
                }

                if (loader) loader.classList.remove('hidden');
                if (resultPanel) {
                    resultPanel.classList.add('hidden');
                    resultPanel.innerHTML = '';
                }

                await new Promise(function (resolve) { setTimeout(resolve, 7000); });

                try {
                    const response = await fetch('<?= e(route_url('/check-nid')) ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({ nid_number: nidValue })
                    });

                    const data = await response.json();
                    renderNidResult(data);
                } catch (error) {
                    if (loader) loader.classList.add('hidden');
                    if (resultPanel) {
                        resultPanel.classList.remove('hidden');
                        resultPanel.innerHTML = '<div class="nid-not-found">Unable to fetch the NID status right now. Please try again.</div>';
                    }
                }
            });
        }
    </script>
</body>
</html>
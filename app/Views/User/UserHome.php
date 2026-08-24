<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-pink: #ff6b8b;
        --primary-pink-dark: #e04869;
        --primary-blue: #56ccf2;
        --primary-blue-dark: #2f80ed;
        --accent-gradient: linear-gradient(135deg, #ff6b8b 0%, #ff8ea7 50%, #56ccf2 100%);
        --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
        --card-shadow-hover: 0 20px 30px -10px rgba(255, 107, 139, 0.25), 0 10px 15px -5px rgba(86, 204, 242, 0.2);
    }

    body {
        background-color: #f8fafc;
    }

    /* Full-Width Home Container Layout - Expansive & Fluid */
    .home-content-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto;
    }

    .container-xxl.container-p-y,
    .content-wrapper > .container-p-y {
        max-width: 100% !important;
        width: 100% !important;
        padding-left: 2rem !important;
        padding-right: 2rem !important;
        padding-top: 1.25rem !important;
    }

    @media (min-width: 1400px) {
        .container-xxl.container-p-y,
        .content-wrapper > .container-p-y {
            padding-left: 2.5rem !important;
            padding-right: 2.5rem !important;
        }

        .hero-banner {
            padding: 3.5rem 4rem !important;
            border-radius: 36px !important;
        }

        .action-card {
            padding: 1.8rem 1.4rem !important;
            border-radius: 24px !important;
        }

        .action-icon-wrap {
            width: 68px !important;
            height: 68px !important;
            font-size: 2rem !important;
        }
    }

    @media (min-width: 992px) and (max-width: 1399px) {
        .hero-banner {
            padding: 2.8rem 3rem !important;
            border-radius: 30px !important;
        }

        .action-card {
            padding: 1.5rem 1.2rem !important;
            border-radius: 22px !important;
        }

        .action-icon-wrap {
            width: 60px !important;
            height: 60px !important;
            font-size: 1.8rem !important;
        }
    }

    @media (max-width: 992px) {
        .container-xxl.container-p-y,
        .content-wrapper > .container-p-y {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .container-xxl.container-p-y,
        .content-wrapper > .container-p-y {
            padding-left: 6px !important;
            padding-right: 6px !important;
            padding-top: 4px !important;
            padding-bottom: 6px !important;
        }
    }

    /* ============================================================
       HERO SECTION (EXPANSIVE & LUXURIOUS)
       ============================================================ */
    .hero-banner {
        background: linear-gradient(135deg, #ff6b8b 0%, #e04869 45%, #56ccf2 100%);
        border-radius: 28px;
        padding: 2.4rem 1.8rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 20px 45px -10px rgba(255, 107, 139, 0.4), 0 10px 25px -5px rgba(86, 204, 242, 0.25);
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 550px;
        height: 550px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-banner::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -15%;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.88rem;
        font-weight: 700;
        border: 1px solid rgba(255, 255, 255, 0.45);
        margin-bottom: 1.2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .hero-badge .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #22c55e;
        box-shadow: 0 0 12px #22c55e;
        animation: pulse 1.8s infinite;
    }

    .hero-badge.closed .status-dot {
        background-color: #ef4444;
        box-shadow: 0 0 12px #ef4444;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.3); opacity: 0.7; }
    }

    .hero-title {
        font-size: clamp(1.75rem, 5vw, 2.85rem);
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 0.6rem;
        text-shadow: 0 2px 12px rgba(0, 0, 0, 0.18);
    }

    .hero-subtitle {
        font-size: clamp(0.95rem, 2.5vw, 1.2rem);
        font-weight: 500;
        opacity: 0.95;
        margin-bottom: 1.6rem;
        line-height: 1.55;
    }

    /* Live Countdown Box */
    .countdown-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 22px;
        padding: 1.2rem 1.4rem;
        margin-bottom: 1.6rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .countdown-title {
        font-size: 0.88rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.9rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .countdown-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .countdown-unit {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 10px 4px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.45);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .countdown-num {
        font-size: clamp(1.4rem, 4.5vw, 2.1rem);
        font-weight: 800;
        line-height: 1.1;
        font-family: 'K2D', sans-serif;
    }

    .countdown-label {
        font-size: 0.72rem;
        font-weight: 600;
        opacity: 0.92;
        margin-top: 3px;
    }

    .hero-btn-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .hero-btn {
        padding: 0.95rem 1.2rem;
        border-radius: 18px;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: none;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .hero-btn-primary {
        background: #ffffff;
        color: var(--primary-pink-dark);
    }

    .hero-btn-primary:hover {
        background: #fff0f3;
        color: var(--primary-pink-dark);
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.22);
    }

    .hero-btn-secondary {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        border: 1.5px solid rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(12px);
    }

    .hero-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.4);
        color: #ffffff;
        transform: translateY(-3px) scale(1.02);
    }

    /* ============================================================
       QUICK ACTION CARDS (SMART HUB - FULL WIDTH EXPANSIVE GRID)
       ============================================================ */
    .section-header {
        margin-bottom: 1.4rem;
    }

    .section-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary-pink);
        font-size: 1.6rem;
    }

    .section-desc {
        font-size: 0.88rem;
        color: #64748b;
        margin-top: 3px;
        margin-bottom: 0;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 2.2rem;
        width: 100%;
    }

    @media (min-width: 768px) {
        .action-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
    }

    @media (min-width: 1100px) {
        .action-grid {
            grid-template-columns: repeat(6, 1fr);
            gap: 18px;
        }
    }

    .action-card {
        background: #ffffff;
        border-radius: 22px;
        padding: 1.4rem 1.1rem;
        text-align: center;
        text-decoration: none;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 4px 10px -2px rgba(15, 23, 42, 0.02);
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .action-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 35px -8px rgba(225, 29, 72, 0.2), 0 8px 16px -4px rgba(2, 132, 199, 0.15);
        border-color: rgba(255, 117, 140, 0.45);
    }

    .action-icon-wrap {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        margin-bottom: 0.85rem;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
    }

    .action-card:hover .action-icon-wrap {
        transform: scale(1.12) rotate(6deg);
    }

    .icon-pink { background: linear-gradient(135deg, #ff6b8b 0%, #e04869 100%); }
    .icon-blue { background: linear-gradient(135deg, #56ccf2 0%, #2f80ed 100%); }
    .icon-purple { background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%); }
    .icon-emerald { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .icon-amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .icon-rose { background: linear-gradient(135deg, #ff6b8b 0%, #ff8ea7 100%); }

    .action-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
    }

    .action-subtitle {
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 500;
    }

    /* ============================================================
       ADMISSION STEP CARDS
       ============================================================ */
    .steps-card {
        background: #ffffff;
        border-radius: 26px;
        padding: 1.8rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05);
        margin-bottom: 2.2rem;
        width: 100%;
    }

    .step-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 14px;
    }

    @media (min-width: 768px) {
        .step-list {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }

    @media (min-width: 1200px) {
        .step-list {
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }
    }

    .step-item {
        background: #f8fafc;
        border-radius: 18px;
        padding: 1.2rem 1.1rem;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .step-item:hover {
        background: #ffffff;
        border-color: var(--primary-pink);
        box-shadow: 0 8px 20px rgba(255, 107, 139, 0.18);
        transform: translateY(-2px);
    }

    .step-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
        color: white;
        font-weight: 800;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(255, 107, 139, 0.25);
    }

    .step-text h6 {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
    }

    .step-text p {
        font-size: 0.78rem;
        color: #64748b;
        margin-bottom: 0;
        line-height: 1.45;
    }

    /* ============================================================
       QUOTA & COURSE EXPLORER (SEGMENTED TABS)
       ============================================================ */
    .explorer-card {
        background: #ffffff;
        border-radius: 26px;
        padding: 1.8rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05);
        margin-bottom: 2.2rem;
        width: 100%;
    }

    .nav-pills-custom {
        display: flex;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 18px;
        margin-bottom: 1.6rem;
        gap: 6px;
    }

    .nav-pills-custom .nav-link {
        flex: 1;
        text-align: center;
        padding: 11px 16px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.98rem;
        color: #64748b;
        transition: all 0.25s ease;
        border: none;
    }

    .nav-pills-custom .nav-link.active {
        background: linear-gradient(135deg, #ff6b8b 0%, #e04869 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(255, 107, 139, 0.35);
    }

    .course-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.1rem 1.3rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
        transition: all 0.25s ease;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.02);
        height: calc(100% - 12px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .course-card:hover {
        background: #ffffff;
        border-color: #56ccf2;
        box-shadow: 0 6px 18px rgba(86, 204, 242, 0.2);
        transform: translateY(-3px);
    }

    .course-title {
        font-weight: 700;
        font-size: 0.93rem;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: 0.4rem;
    }

    .branch-badge-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }

    .branch-pill {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
    }

    .course-tag {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 20px;
        background: rgba(86, 204, 242, 0.15);
        color: #2f80ed;
        white-space: nowrap;
    }

    /* ============================================================
       SCHEDULE TIMELINE
       ============================================================ */
    .timeline-card {
        background: #ffffff;
        border-radius: 26px;
        padding: 1.8rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05);
        margin-bottom: 2rem;
    }

    .timeline-item {
        position: relative;
        padding-left: 34px;
        padding-bottom: 22px;
        border-left: 2px dashed #cbd5e1;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
        border-left-color: transparent;
    }

    .timeline-dot {
        position: absolute;
        left: -9px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid var(--primary-pink);
        box-shadow: 0 0 0 3px rgba(255, 107, 139, 0.2);
    }

    .timeline-dot.active {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.25);
        background: #22c55e;
    }

    .timeline-date {
        font-size: 0.8rem;
        font-weight: 700;
        color: #2f80ed;
        margin-bottom: 2px;
    }

    .timeline-event {
        font-size: 0.98rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .timeline-desc {
        font-size: 0.82rem;
        color: #64748b;
        margin-bottom: 0;
    }

    /* ============================================================
       STATS BAR & CONTACT
       ============================================================ */
    .stats-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 26px;
        padding: 1.8rem;
        color: #ffffff;
        margin-bottom: 2rem;
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.25);
        border: 1.5px solid #334155;
        border-top: 3px solid #ff6b8b;
        border-bottom: 3px solid #56ccf2;
        position: relative;
        overflow: hidden;
    }

    .stat-box-item {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        padding: 1.1rem 0.8rem;
        text-align: center;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-box-item:hover {
        background: rgba(255, 255, 255, 0.09);
        transform: translateY(-2px);
    }

    .stat-val {
        font-size: clamp(1.5rem, 4vw, 2.2rem);
        font-weight: 800;
        font-family: 'K2D', sans-serif;
        line-height: 1.1;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    .stat-lbl {
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 6px;
    }

    /* Mobile Responsive Rules for Explorer and Stats */
    @media (max-width: 767.98px) {
        .explorer-card {
            padding: 1.25rem 1rem !important;
            border-radius: 20px !important;
        }

        .quota-badge-wrap {
            gap: 6px !important;
            margin-bottom: 1rem !important;
        }

        .quota-chip-pink, .quota-chip-blue {
            font-size: 0.78rem !important;
            padding: 5px 10px !important;
        }

        .nav-pills-custom {
            padding: 4px !important;
            border-radius: 16px !important;
            margin-bottom: 1rem !important;
        }

        .nav-pills-custom .nav-link {
            padding: 9px 10px !important;
            font-size: 0.88rem !important;
            border-radius: 12px !important;
        }

        .course-card-m1, .course-card-m4 {
            padding: 1rem !important;
            border-radius: 16px !important;
        }

        .course-group-title {
            font-size: 0.92rem !important;
        }

        .btn-apply-course-pink, .btn-apply-course-blue {
            width: 100% !important;
            text-align: center;
            padding: 8px 16px !important;
            font-size: 0.85rem !important;
            display: block !important;
        }

        .course-card-m1 .d-flex.justify-content-between,
        .course-card-m4 .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 8px !important;
            align-items: stretch !important;
        }

        .stats-card {
            padding: 1.25rem 1rem !important;
            border-radius: 20px !important;
        }

        .stat-box-item {
            padding: 0.8rem 0.4rem !important;
            border-radius: 14px !important;
        }

        .stat-val {
            font-size: 1.5rem !important;
        }

        .stat-lbl {
            font-size: 0.72rem !important;
        }
    }

    .contact-card {
        background: #ffffff;
        border-radius: 26px;
        padding: 1.8rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05);
        margin-bottom: 2rem;
    }

    .contact-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.95rem 1.3rem;
        border-radius: 18px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.98rem;
        margin-bottom: 12px;
        transition: all 0.25s ease;
    }

    .contact-btn-line {
        background: #06c755;
        color: white;
    }
    .contact-btn-line:hover {
        background: #05b04b;
        color: white;
        transform: translateY(-2px);
    }

    .contact-btn-phone {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }
    .contact-btn-phone:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-2px);
    }

    /* Modal Styling */
    .modal-content-modern {
        border-radius: 24px;
        border: none;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header-gradient {
        background: linear-gradient(135deg, #ff758c 0%, #38bdf8 100%);
        color: white;
        padding: 1.2rem 1.5rem;
        border: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$isOpen = ($systemStatus->onoff_regis ?? 'off') === 'on';
$currentYear = $checkYear->openyear_year ?? (date('Y') + 543);
$currentRound = $systemStatus->onoff_round ?? 1;

// Dates for Countdown
$openDateTime = !empty($systemStatus->onoff_datetime_regis_open) ? $systemStatus->onoff_datetime_regis_open : (!empty($systemStatus->onoff_open) ? $systemStatus->onoff_open . ' 08:30:00' : '');
$closeDateTime = !empty($systemStatus->onoff_datetime_regis_close) ? $systemStatus->onoff_datetime_regis_close : (!empty($systemStatus->onoff_close) ? $systemStatus->onoff_close . ' 16:30:00' : '');

$currentTimeHome = time();
$isTimeOpen = true;
if (!empty($openDateTime) && $currentTimeHome < strtotime($openDateTime)) {
    $isTimeOpen = false;
}
if (!empty($closeDateTime) && $currentTimeHome > strtotime($closeDateTime)) {
    $isTimeOpen = false;
}
$isActualOpen = $isOpen && $isTimeOpen;

// Extract Open Levels from Quotas or controller passed variable
if (!isset($openLevels)) {
    $openLevels = [];
    if ($isActualOpen && !empty($quotas)) {
        foreach ($quotas as $quota) {
            if (isset($quota->quota_status) && $quota->quota_status == 'on' && !empty($quota->quota_level)) {
                $levels = preg_split('/[|,]/', $quota->quota_level);
                foreach ($levels as $l) {
                    $num = preg_replace('/[^0-9]/', '', trim($l));
                    if (is_numeric($num) && !empty($num)) {
                        $openLevels[] = intval($num);
                    }
                }
            }
        }
    }
    $openLevels = array_values(array_unique($openLevels));
    sort($openLevels);
}
?>

<div class="w-100 home-content-wrapper">

    <!-- ============================================================
         1. HERO SECTION & LIVE COUNTDOWN (RESPONSIVE)
         ============================================================ -->
    <div class="hero-banner">
        <div class="row align-items-center">
            <div class="col-lg-7 col-xl-8">
                <!-- Status Badge -->
                <div class="hero-badge <?= ($isActualOpen && !empty($openLevels)) ? '' : 'closed' ?>">
                    <span class="status-dot"></span>
                    <span><?= ($isActualOpen && !empty($openLevels)) ? 'เปิดรับสมัครรอบที่ ' . esc($currentRound) . ' ปีการศึกษา ' . esc($currentYear) : 'ระบบปิดรับสมัครในขณะนี้' ?></span>
                </div>

                <h1 class="hero-title">
                    ระบบรับสมัครนักเรียนออนไลน์<br>
                    <span style="font-weight: 600; opacity: 0.95;">ปีการศึกษา <?= esc($currentYear) ?></span>
                </h1>

                <p class="hero-subtitle">
                    โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์<br>
                    <small class="opacity-75">สังกัดองค์การบริหารส่วนจังหวัดนครสวรรค์</small>
                </p>

                <!-- Countdown Timer -->
                <?php if (!empty($closeDateTime) || !empty($openDateTime)): ?>
                <div class="countdown-card">
                    <div class="countdown-title">
                        <i class='bx bx-stopwatch fs-5'></i>
                        <span id="countdownTitle"><?= $isActualOpen ? 'นับถอยหลังปิดรับสมัคร' : 'นับถอยหลังเปิดรับสมัคร' ?></span>
                    </div>
                    <div class="countdown-grid">
                        <div class="countdown-unit">
                            <div class="countdown-num" id="cdDays">00</div>
                            <div class="countdown-label">วัน</div>
                        </div>
                        <div class="countdown-unit">
                            <div class="countdown-num" id="cdHours">00</div>
                            <div class="countdown-label">ชั่วโมง</div>
                        </div>
                        <div class="countdown-unit">
                            <div class="countdown-num" id="cdMinutes">00</div>
                            <div class="countdown-label">นาที</div>
                        </div>
                        <div class="countdown-unit">
                            <div class="countdown-num" id="cdSeconds">00</div>
                            <div class="countdown-label">วินาที</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="hero-btn-group" id="apply-section" style="<?= count($openLevels) > 2 ? 'grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));' : '' ?>">
                    <?php if ($isActualOpen && !empty($openLevels)): ?>
                        <?php foreach ($openLevels as $l_num): 
                            $is_jr = $l_num <= 3;
                            $btn_color = $is_jr ? 'var(--primary-pink-dark)' : 'var(--primary-blue-dark)';
                            $icon_cls = $is_jr ? 'bx-user-plus' : 'bx-award';
                        ?>
                            <a href="javascript:void(0);" onclick="openRegisterPDPA(<?= $l_num ?>)" class="hero-btn hero-btn-primary" style="color: <?= $btn_color ?>;">
                                <i class='bx <?= $icon_cls ?>'></i> สมัคร ม.<?= $l_num ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <button type="button" class="hero-btn hero-btn-secondary w-100" style="grid-column: 1 / -1;" onclick="alertClosedSystem()">
                            <i class='bx bx-lock-alt'></i> ระบบปิดรับสมัครในขณะนี้
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mascot / Hero Graphic on Desktop -->
            <div class="col-lg-5 col-xl-4 d-none d-lg-flex justify-content-center">
                <img src="<?= base_url('public/images/mascot.png') ?>" alt="SKJ Admission Mascot" class="img-fluid" style="max-height: 420px; filter: drop-shadow(0 18px 30px rgba(0,0,0,0.25));" onerror="this.src='https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png';">
            </div>
        </div>
    </div>

    <!-- ============================================================
         2. SMART QUICK ACTIONS (TOUCH-FRIENDLY CARDS)
         ============================================================ -->
    <div class="section-header">
        <h2 class="section-title"><i class='bx bxs-grid-alt'></i> เมนูบริการหลัก</h2>
        <p class="section-desc">เข้าถึงบริการและระบบตรวจสอบต่างๆ ได้สะดวกรวดเร็ว</p>
    </div>

    <div class="action-grid">
        <!-- สมัครเรียน Action Cards (Dynamic) -->
        <?php if ($isActualOpen && !empty($openLevels)): ?>
            <?php if (count($openLevels) <= 3): ?>
                <?php foreach ($openLevels as $l_num): 
                    $is_jr = $l_num <= 3;
                    $icon_class = $is_jr ? 'icon-pink' : 'icon-blue';
                    $icon_i = $is_jr ? 'bx-user-voice' : 'bx-award';
                    $sub_label = ($l_num == 1) ? 'มัธยมศึกษาปีที่ 1' : (($l_num == 4) ? 'มัธยมศึกษาปีที่ 4' : 'มัธยมศึกษาปีที่ ' . $l_num);
                ?>
                <a href="javascript:void(0);" onclick="openRegisterPDPA(<?= $l_num ?>)" class="action-card">
                    <div class="action-icon-wrap <?= $icon_class ?>">
                        <i class='bx <?= $icon_i ?>'></i>
                    </div>
                    <div class="action-title">สมัคร ม.<?= $l_num ?></div>
                    <div class="action-subtitle"><?= $sub_label ?></div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="javascript:void(0);" onclick="showLevelChoiceModal()" class="action-card">
                    <div class="action-icon-wrap icon-pink">
                        <i class='bx bx-user-plus'></i>
                    </div>
                    <div class="action-title">สมัครเรียน</div>
                    <div class="action-subtitle">เลือก ม.<?= implode(', ม.', $openLevels) ?></div>
                </a>
            <?php endif; ?>
        <?php else: ?>
            <a href="javascript:void(0);" onclick="alertClosedSystem()" class="action-card">
                <div class="action-icon-wrap icon-pink" style="opacity: 0.6;">
                    <i class='bx bx-lock-alt'></i>
                </div>
                <div class="action-title">สมัครเรียน</div>
                <div class="action-subtitle">ปิดรับสมัคร</div>
            </a>
        <?php endif; ?>

        <!-- ตรวจสอบสถานะ -->
        <a href="<?= site_url('new-admission/status') ?>" class="action-card">
            <div class="action-icon-wrap icon-purple">
                <i class='bx bx-search-alt'></i>
            </div>
            <div class="action-title">ตรวจสถานะ</div>
            <div class="action-subtitle">เช็คผลการสมัคร</div>
        </a>

        <!-- รายงานตัว / มอบตัว -->
        <a href="<?= site_url('confirmation/login') ?>" class="action-card">
            <div class="action-icon-wrap icon-emerald">
                <i class='bx bx-id-card'></i>
            </div>
            <div class="action-title">รายงานตัว</div>
            <div class="action-subtitle">มอบตัวนักเรียนใหม่</div>
        </a>

        <!-- ประกาศผล -->
        <a href="<?= site_url('new-admission/announcements') ?>" class="action-card">
            <div class="action-icon-wrap icon-amber">
                <i class='bx bx-bell'></i>
            </div>
            <div class="action-title">ประกาศผล</div>
            <div class="action-subtitle">รายชื่อผู้มีสิทธิ์</div>
        </a>

        <!-- สถิติ -->
        <a href="<?= site_url('new-admission/statistics') ?>" class="action-card">
            <div class="action-icon-wrap icon-rose">
                <i class='bx bx-bar-chart-alt-2'></i>
            </div>
            <div class="action-title">สถิติผู้สมัคร</div>
            <div class="action-subtitle">ดูจำนวนผู้สมัครสด</div>
        </a>
    </div>

    <!-- ============================================================
         3. 4-STEP APPLICATION GUIDE
         ============================================================ -->
    <div class="steps-card">
        <div class="section-header mb-3">
            <h3 class="section-title"><i class='bx bx-list-check'></i> 4 ขั้นตอนการสมัครเรียนง่ายๆ</h3>
            <p class="section-desc">ทำความเข้าใจขั้นตอนเพื่อเตรียมเอกสารและสมัครได้อย่างถูกต้อง</p>
        </div>

        <div class="step-list">
            <div class="step-item">
                <div class="step-badge">1</div>
                <div class="step-text">
                    <h6>เลือกแผนการเรียน</h6>
                    <p>ตรวจสอบคุณสมบัติและเลือกแผนการเรียนที่ตรงกับความสนใจ</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-badge">2</div>
                <div class="step-text">
                    <h6>กรอกข้อมูลออนไลน์</h6>
                    <p>กรอกข้อมูลส่วนตัว ประวัติการศึกษา และอัปโหลดไฟล์หลักฐาน</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-badge">3</div>
                <div class="step-text">
                    <h6>ตรวจสอบสถานะ</h6>
                    <p>ติดตามผลการตรวจสอบเอกสารจากเจ้าหน้าที่ผ่านเมนูตรวจสถานะ</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-badge">4</div>
                <div class="step-text">
                    <h6>พิมพ์บัตรเข้าสอบ</h6>
                    <p>พิมพ์บัตรประจำตัวผู้สมัคร เพื่อใช้เป็นหลักฐานในวันสอบคัดเลือก</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         4. QUOTA & COURSE EXPLORER
         ============================================================ -->
    <div class="explorer-card">
        <div class="section-header">
            <h3 class="section-title" style="font-size: 1.25rem;"><i class='bx bx-book-open text-primary'></i> แผนการเรียนและโควต้าที่เปิดรับ</h3>
            <p class="section-desc mb-2">สำรวจแผนการเรียนและประเภทโควต้าประจำปีการศึกษา <?= esc($currentYear) ?></p>
        </div>

        <?php if (!empty($quotas)): ?>
        <div class="mb-3 d-flex flex-wrap gap-2">
            <?php 
            $uniqueQuotas = [];
            foreach ($quotas as $q) {
                $name = trim($q->quota_explain);
                if (!in_array($name, $uniqueQuotas) && !empty($name)) {
                    $uniqueQuotas[] = $name;
                }
            }
            ?>
            <?php foreach ($uniqueQuotas as $quotaName): ?>
                <span class="badge rounded-pill border shadow-sm" style="background: rgba(255, 107, 139, 0.1); color: #ff6b8b; border-color: rgba(255, 107, 139, 0.3) !important; font-size: 0.78rem; font-weight: 600; padding: 6px 12px;">
                    <?= esc($quotaName) ?>
                </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <ul class="nav nav-pills-custom" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link active w-100" id="pills-m1-tab" data-bs-toggle="pill" data-bs-target="#pills-m1" type="button" role="tab">
                    <i class='bx bx-user me-1'></i> ระดับชั้น ม.ต้น (ม.1 - ม.3)
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="pills-m4-tab" data-bs-toggle="pill" data-bs-target="#pills-m4" type="button" role="tab">
                    <i class='bx bx-award me-1'></i> ระดับชั้น ม.ปลาย (ม.4 - ม.6)
                </button>
            </li>
        </ul>

        <div class="tab-content pt-1">
            <!-- M.1 Tab -->
            <div class="tab-pane fade show active" id="pills-m1" role="tabpanel">
                <div class="row g-3">
                    <?php 
                    $m1Courses = array_filter($courses ?? [], function($c) {
                        $level = is_object($c) ? ($c->course_gradelevel ?? '') : ($c['course_gradelevel'] ?? '');
                        return strpos($level, 'ต้น') !== false || strpos($level, '1') !== false;
                    });
                    $m1Grouped = [];
                    foreach ($m1Courses as $c) {
                        $cFullname = is_object($c) ? ($c->course_fullname ?? '') : ($c['course_fullname'] ?? '');
                        $cInitials = is_object($c) ? ($c->course_initials ?? '') : ($c['course_initials'] ?? '');
                        $cBranch = is_object($c) ? ($c->course_branch ?? '') : ($c['course_branch'] ?? '');

                        $name = trim($cFullname);
                        if (!isset($m1Grouped[$name])) {
                            $m1Grouped[$name] = [
                                'fullname' => $cFullname,
                                'initials' => $cInitials,
                                'branches' => []
                            ];
                        }
                        if (!empty($cBranch)) {
                            $b = trim($cBranch);
                            if (!in_array($b, $m1Grouped[$name]['branches'])) {
                                $m1Grouped[$name]['branches'][] = $b;
                            }
                        }
                    }
                    ?>
                    <?php if (!empty($m1Grouped)): ?>
                        <?php foreach ($m1Grouped as $g): ?>
                            <div class="col-xxl-3 col-xl-4 col-md-6 col-12">
                                <div class="course-card">
                                    <div class="course-title">
                                        <i class='bx bx-book-bookmark text-primary me-1'></i> <?= esc($g['fullname']) ?>
                                    </div>
                                    <?php if (!empty($g['branches'])): ?>
                                        <div class="branch-badge-wrap">
                                            <?php foreach ($g['branches'] as $b): ?>
                                                <span class="branch-pill">
                                                    <i class='bx bx-chevron-right text-muted' style="font-size: 0.8rem;"></i> <?= esc($b) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-muted py-3" style="font-size: 0.85rem;">ไม่มีข้อมูลแผนการเรียน ม.ต้น</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- M.4 Tab -->
            <div class="tab-pane fade" id="pills-m4" role="tabpanel">
                <div class="row g-3">
                    <?php 
                    $m4Courses = array_filter($courses ?? [], function($c) {
                        $level = is_object($c) ? ($c->course_gradelevel ?? '') : ($c['course_gradelevel'] ?? '');
                        return strpos($level, 'ปลาย') !== false || strpos($level, '4') !== false;
                    });
                    $m4Grouped = [];
                    foreach ($m4Courses as $c) {
                        $cFullname = is_object($c) ? ($c->course_fullname ?? '') : ($c['course_fullname'] ?? '');
                        $cInitials = is_object($c) ? ($c->course_initials ?? '') : ($c['course_initials'] ?? '');
                        $cBranch = is_object($c) ? ($c->course_branch ?? '') : ($c['course_branch'] ?? '');

                        $name = trim($cFullname);
                        if (!isset($m4Grouped[$name])) {
                            $m4Grouped[$name] = [
                                'fullname' => $cFullname,
                                'initials' => $cInitials,
                                'branches' => []
                            ];
                        }
                        if (!empty($cBranch)) {
                            $b = trim($cBranch);
                            if (!in_array($b, $m4Grouped[$name]['branches'])) {
                                $m4Grouped[$name]['branches'][] = $b;
                            }
                        }
                    }
                    ?>
                    <?php if (!empty($m4Grouped)): ?>
                        <?php foreach ($m4Grouped as $g): ?>
                            <div class="col-xxl-3 col-xl-4 col-md-6 col-12">
                                <div class="course-card">
                                    <div class="course-title">
                                        <i class='bx bx-book-bookmark text-primary me-1'></i> <?= esc($g['fullname']) ?>
                                    </div>
                                    <?php if (!empty($g['branches'])): ?>
                                        <div class="branch-badge-wrap">
                                            <?php foreach ($g['branches'] as $b): ?>
                                                <span class="branch-pill">
                                                    <i class='bx bx-chevron-right text-muted' style="font-size: 0.8rem;"></i> <?= esc($b) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-muted py-3" style="font-size: 0.85rem;">ไม่มีข้อมูลแผนการเรียน ม.ปลาย</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         5. SCHEDULE & TIMELINE + STATS & CONTACT (FULL WIDTH GRID)
         ============================================================ -->
    <div class="row g-4">
        <div class="col-xxl-7 col-xl-7 col-lg-6 mb-4">
            <div class="timeline-card h-100 mb-0">
                <div class="section-header">
                    <h3 class="section-title"><i class='bx bx-calendar-event'></i> กำหนดการรับสมัคร</h3>
                    <p class="section-desc">ไทม์ไลน์และกำหนดการที่สำคัญของการรับสมัคร</p>
                </div>

                <div class="timeline-wrapper mt-4">
                    <?php if (!empty($schedules)): ?>
                        <?php 
                        $groupedSchedules = [];
                        foreach ($schedules as $sc) {
                            $hash = md5($sc->schedule_round . $sc->schedule_recruit_start . $sc->schedule_recruit_end . $sc->schedule_exam . $sc->schedule_announce . $sc->schedule_report);
                            if (!isset($groupedSchedules[$hash])) {
                                $sc->levels = [$sc->schedule_level];
                                $groupedSchedules[$hash] = $sc;
                            } else {
                                if (!in_array($sc->schedule_level, $groupedSchedules[$hash]->levels)) {
                                    $groupedSchedules[$hash]->levels[] = $sc->schedule_level;
                                }
                            }
                        }
                        $idx = 0;
                        ?>
                        <?php foreach ($groupedSchedules as $hash => $sc): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot <?= ($idx === 0) ? 'active' : '' ?>"></div>
                                <div class="timeline-date">
                                    <span class="badge bg-primary text-white me-1">ม.<?= esc(implode(', ม.', $sc->levels)) ?></span> รอบ <?= esc($sc->schedule_round) ?>
                                </div>
                                <div class="timeline-event">
                                    รับสมัคร: <?= $datethai->thai_date_short(strtotime($sc->schedule_recruit_start)) ?> - <?= $datethai->thai_date_short(strtotime($sc->schedule_recruit_end)) ?>
                                </div>
                                <p class="timeline-desc text-muted mt-1" style="font-size: 0.85rem;">
                                    <?php if ($sc->schedule_exam): ?>
                                        <strong>สอบ:</strong> <?= $datethai->thai_date_short(strtotime($sc->schedule_exam)) ?> <br>
                                    <?php endif; ?>
                                    <?php if ($sc->schedule_announce): ?>
                                        <strong>ประกาศผล:</strong> <?= $datethai->thai_date_short(strtotime($sc->schedule_announce)) ?> <br>
                                    <?php endif; ?>
                                    <?php if ($sc->schedule_report): ?>
                                        <strong>รายงานตัว:</strong> <?= $datethai->thai_date_short(strtotime($sc->schedule_report)) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php $idx++; endforeach; ?>
                    <?php else: ?>
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="timeline-date">รับสมัครออนไลน์</div>
                            <div class="timeline-event">เปิดรับสมัครผ่านระบบออนไลน์ตลอด 24 ชั่วโมง</div>
                            <p class="timeline-desc">กรอกข้อมูลและแนบเอกสารให้ครบถ้วนก่อนวันปิดรับสมัคร</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-date">ประกาศรายชื่อและสอบคัดเลือก</div>
                            <div class="timeline-event">ประกาศรายชื่อผู้มีสิทธิ์สอบ และดำเนินการสอบคัดเลือก</div>
                            <p class="timeline-desc">ณ โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-date">รายงานตัวและมอบตัว</div>
                            <div class="timeline-event">รายงานตัวและมอบตัวนักเรียนใหม่</div>
                            <p class="timeline-desc">ตามวันเวลาที่โรงเรียนกำหนด</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ============================================================
             6. STATS COUNTER & CONTACT
             ============================================================ -->
        <div class="col-xxl-5 col-xl-5 col-lg-6 mb-4">
            <!-- Stats -->
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white fw-bold mb-0 d-flex align-items-center" style="font-size: 1.05rem;">
                        <i class='bx bx-pulse text-info me-2 fs-4'></i> สถิติผู้สมัครล่าสุด
                    </h5>
                    <span class="badge rounded-pill bg-danger text-white px-2 py-1 small fw-bold" style="font-size: 0.72rem;">LIVE</span>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="stat-box-item">
                            <div class="stat-val" style="color: #56ccf2;"><?= number_format($stats->total ?? 0) ?></div>
                            <div class="stat-lbl text-white-50"><i class='bx bx-group me-1 text-info'></i>ผู้สมัครทั้งหมด</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box-item">
                            <div class="stat-val" style="color: #4ade80;"><?= number_format($stats->pass ?? 0) ?></div>
                            <div class="stat-lbl text-white-50"><i class='bx bx-check-circle me-1 text-success'></i>ผ่านการตรวจ</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box-item" style="border-left: 3px solid #ff6b8b;">
                            <div class="stat-val" style="color: #ff6b8b;"><?= number_format($stats->m1 ?? 0) ?></div>
                            <div class="stat-lbl text-white-50"><i class='bx bx-user me-1 text-danger'></i>ระดับชั้น ม.1</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box-item" style="border-left: 3px solid #56ccf2;">
                            <div class="stat-val" style="color: #56ccf2;"><?= number_format($stats->m4 ?? 0) ?></div>
                            <div class="stat-lbl text-white-50"><i class='bx bx-award me-1 text-primary'></i>ระดับชั้น ม.4</div>
                        </div>
                    </div>
                </div>

                <a href="<?= site_url('new-admission/statistics') ?>" class="btn btn-outline-info text-white w-100 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center" style="font-size: 0.88rem; border-color: rgba(86, 204, 242, 0.5);">
                    <i class='bx bx-bar-chart-alt-2 me-2'></i> ดูรายงานสถิติละเอียดทั้งหมด
                </a>
            </div>

            <!-- Contact -->
            <div class="contact-card">
                <h5 class="fw-bold text-dark mb-3"><i class='bx bx-headphone text-primary me-2'></i> ช่องทางติดต่อสอบถาม</h5>
                
                <a href="https://line.me/R/ti/p/<?= esc($contact_info['line_id'] ?? '@514kixba') ?>" target="_blank" class="contact-btn contact-btn-line">
                    <i class='bx bxl-line fs-4'></i>
                    <div>
                        <div style="font-size: 0.95rem;">LINE Official Account</div>
                        <small style="opacity: 0.9; font-weight: 400;"><?= esc($contact_info['line_id'] ?? '@514kixba') ?></small>
                    </div>
                </a>

                <a href="tel:056009667" class="contact-btn contact-btn-phone">
                    <i class='bx bx-phone-call fs-4 text-primary'></i>
                    <div>
                        <div style="font-size: 0.95rem;">โทรศัพท์ฝ่ายรับสมัคร</div>
                        <small class="text-muted"><?= esc($contact_info['phone'] ?? '056-009-667') ?></small>
                    </div>
                </a>

                <div class="mt-3 p-3 bg-light rounded-3 text-muted small">
                    <i class='bx bx-time-five me-1 text-primary'></i> <strong>เวลาทำการ:</strong> <?= esc($contact_info['office_hours'] ?? 'จันทร์ - ศุกร์ 08:30 - 16:30 น.') ?><br>
                    <i class='bx bx-map me-1 text-primary mt-1'></i> <?= esc($contact_info['school_name'] ?? 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์') ?>
                </div>
            </div>
        </div>
    </div>

</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Target DateTime for countdown
    const targetDateStr = '<?= $isOpen ? $closeDateTime : $openDateTime ?>';
    let targetDate = targetDateStr ? new Date(targetDateStr.replace(/-/g, '/')).getTime() : null;

    function updateCountdown() {
        if (!targetDate || isNaN(targetDate)) return;

        const now = new Date().getTime();
        const difference = targetDate - now;

        if (difference <= 0) {
            document.getElementById('cdDays').innerText = '00';
            document.getElementById('cdHours').innerText = '00';
            document.getElementById('cdMinutes').innerText = '00';
            document.getElementById('cdSeconds').innerText = '00';
            return;
        }

        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        document.getElementById('cdDays').innerText = String(days).padStart(2, '0');
        document.getElementById('cdHours').innerText = String(hours).padStart(2, '0');
        document.getElementById('cdMinutes').innerText = String(minutes).padStart(2, '0');
        document.getElementById('cdSeconds').innerText = String(seconds).padStart(2, '0');
    }

    if (targetDate) {
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // Closed System Alert
    function alertClosedSystem() {
        Swal.fire({
            icon: 'info',
            title: 'ระบบยังไม่เปิดรับสมัคร',
            text: 'กรุณาติดตามกำหนดการและประกาศวันรับสมัครอย่างเป็นทางการจากทางโรงเรียน',
            confirmButtonText: 'รับทราบ',
            confirmButtonColor: '#ff6b8b'
        });
    }

    // PDPA SweetAlert2 Handler for Registration
    function openRegisterPDPA(level) {
        Swal.fire({
            title: `<div style="font-size: 1.15rem; font-weight: 700; color: #0f172a;"><i class='bx bx-shield-quarter me-2 text-primary'></i> ข้อตกลงการสมัครระดับชั้น ม.${level}</div>`,
            html: `
                <div class="text-start p-3 border rounded-3 bg-light" style="max-height: 260px; overflow-y: auto; font-size: 0.88rem; line-height: 1.6; color: #1e293b;">
                    <p class="fw-bold mb-2">ข้อตกลงการใช้ข้อมูลส่วนบุคคล (PDPA)</p>
                    <p>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของผู้สมัคร ข้อมูลที่ท่านกรอกจะถูกนำไปใช้เพื่อการประมวลผลการรับสมัคร การจัดทำทะเบียนประวัติ และการติดต่อประสานงานเท่านั้น</p>
                    <ul class="ps-3 mb-2">
                        <li>ข้าพเจ้ารับรองว่าข้อมูลและเอกสารที่แนบเป็นความจริงทุกประการ</li>
                        <li>หากตรวจพบว่าข้อมูลหรือเอกสารเป็นเท็จ ทางโรงเรียนขอสงวนสิทธิ์ตัดสิทธิ์การสมัคร</li>
                    </ul>
                    <p class="mt-2 text-muted small">การกด "ยอมรับและดำเนินการต่อ" ถือว่าท่านได้ยอมรับข้อตกลงและเงื่อนไขทั้งหมด</p>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'ยอมรับและดำเนินการต่อ',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#ff6b8b',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('new-admission/pre-check/') ?>' + level;
            }
        });
    }

    // SweetAlert2 School Announcement
    <?php if (!empty($systemStatus->onoff_comment)): ?>
    function showAnnouncementSwal(forceShow = false) {
        const announceRaw = '<?= addslashes(preg_replace('/\s+/', ' ', $systemStatus->onoff_comment)) ?>';
        const storageKey = 'skj_hide_swal_announce_' + encodeURIComponent(announceRaw).substring(0, 32);

        if (!forceShow && localStorage.getItem(storageKey) === 'true') {
            return;
        }

        Swal.fire({
            title: `
                <div class="d-flex align-items-center justify-content-center gap-2" style="color: #ff6b8b; font-weight: 800; font-size: 1.25rem;">
                    <i class='bx bxs-megaphone bx-tada fs-3' style="color: #ff6b8b;"></i>
                    <span>ประกาศสำคัญจากทางโรงเรียน</span>
                </div>
            `,
            html: `
                <div class="text-start p-3 my-2 border rounded-3 bg-light shadow-sm" style="max-height: 280px; overflow-y: auto; font-size: 0.95rem; line-height: 1.8; color: #0f172a;">
                    <?= nl2br(addslashes(esc($systemStatus->onoff_comment))) ?>
                </div>
                <div class="form-check mt-3 pt-2 text-start border-top d-flex align-items-center gap-2">
                    <input class="form-check-input" type="checkbox" id="swalDontShowAgain" role="button">
                    <label class="form-check-label text-muted fw-bold small" for="swalDontShowAgain" role="button">
                        ไม่ต้องแสดงข้อความประกาศนี้อีก
                    </label>
                </div>
            `,
            confirmButtonText: 'รับทราบและปิด',
            confirmButtonColor: '#ff6b8b',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0',
                confirmButton: 'btn btn-primary px-5 py-2 rounded-pill fw-bold text-white'
            },
            buttonsStyling: false,
            preConfirm: () => {
                const chk = document.getElementById('swalDontShowAgain');
                if (chk && chk.checked) {
                    localStorage.setItem(storageKey, 'true');
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            showAnnouncementSwal(false);
        }, 300);
    });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>

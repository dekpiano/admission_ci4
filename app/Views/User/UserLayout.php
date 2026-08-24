<!DOCTYPE html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="<?= base_url('public/sneat-assets/') ?>"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= isset($title) ? $title : 'ระบบรับสมัครนักเรียนออนไลน์' ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet" />

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/core.css') ?>"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/theme-default.css') ?>"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/css/demo.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="<?= base_url('public/sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/libs/apex-charts/apex-charts.css') ?>" />


    <!-- Page CSS -->
    <style>
        *, body, button, input, select, textarea, h1, h2, h3, h4, h5, h6, .fw-bold, .btn, .nav, .form-control, .form-select, .badge, .swal2-popup, .modal, .dropdown-menu, .table {
            font-family: 'K2D', sans-serif !important;
        }

        /* Pastel Pink - Blue Theme Overrides */
        :root {
            /* Primary Pink (Pastel) */
            --bs-primary: #ff9eb5;
            --bs-primary-rgb: 255, 158, 181;

            /* Secondary Blue (Pastel) */
            --bs-info: #84d2f6;
            --bs-info-rgb: 132, 210, 246;

            --bs-link-color: #ff9eb5;
            --bs-link-hover-color: #ff7da0;
        }

        /* Primary = Pink */
        .text-primary {
            color: #ff9eb5 !important;
        }

        .bg-primary {
            background-color: #ff9eb5 !important;
        }

        .btn-primary {
            background-color: #ff9eb5;
            border-color: #ff9eb5;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #ff85a3 !important;
            border-color: #ff85a3 !important;
        }

        .btn-outline-primary {
            color: #ff9eb5;
            border-color: #ff9eb5;
        }

        .btn-outline-primary:hover {
            background-color: #ff9eb5;
            border-color: #ff9eb5;
            color: #fff;
        }

        /* Info = Blue (Pastel) */
        .text-info {
            color: #84d2f6 !important;
        }

        .bg-info {
            background-color: #84d2f6 !important;
        }

        .btn-info {
            background-color: #84d2f6;
            border-color: #84d2f6;
            color: #fff;
        }

        .btn-info:hover {
            background-color: #6cc3eb !important;
            border-color: #6cc3eb !important;
        }

        /* Custom Pastel Classes */
        .bg-pastel-pink {
            background-color: #ff9eb5 !important;
            color: #fff;
        }

        .text-pastel-pink {
            color: #ff9eb5 !important;
        }

        .bg-pastel-blue {
            background-color: #84d2f6 !important;
            color: #fff;
        }

        .text-pastel-blue {
            color: #84d2f6 !important;
        }

        /* Form Focus */
        .form-control:focus,
        .form-select:focus {
            border-color: #ff9eb5;
            box-shadow: 0 0 0 0.25rem rgba(255, 158, 181, 0.25);
        }

        .page-item.active .page-link {
            background-color: #ff9eb5;
            border-color: #ff9eb5;
        }

        /* ================================
           ENHANCED SIDEBAR DESIGN
           ================================ */

        /* Sidebar Base with Animated Gradient */
        #layout-menu {
            background: linear-gradient(180deg, #ff9eb5 0%, #f8a5b8 25%, #c9aed6 50%, #84d2f6 100%);
            background-size: 100% 200%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 0% 100%;
            }
        }

        /* Decorative Wave Overlay */
        #layout-menu::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.15' d='M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat bottom;
            background-size: cover;
            pointer-events: none;
            z-index: 0;
        }

        /* Floating Bubbles Animation */
        #layout-menu::after {
            content: '';
            position: absolute;
            top: 20%;
            right: 10px;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatBubble 8s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes floatBubble {

            0%,
            100% {
                transform: translateY(0) scale(1);
                opacity: 0.3;
            }

            50% {
                transform: translateY(-30px) scale(1.1);
                opacity: 0.5;
            }
        }

        /* App Brand / Logo Section */
        #layout-menu .app-brand {
            position: relative;
            z-index: 1;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        #layout-menu .app-brand-logo img {
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.15));
        }

        #layout-menu .app-brand:hover .app-brand-logo img {
            transform: rotate(10deg) scale(1.1);
        }

        #layout-menu .app-brand-text {
            color: #fff !important;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        #layout-menu .app-brand:hover .app-brand-text {
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Menu Inner Container */
        #layout-menu .menu-inner {
            position: relative;
            z-index: 1;
        }

        /* Menu Link Base Styles */
        #layout-menu .menu-link {
            color: #fff !important;
            border-radius: 12px;
            margin: 4px 12px;
            padding: 0.75rem 1rem !important;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        /* Menu Link Hover Effect */
        #layout-menu .menu-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        #layout-menu .menu-link:hover::before {
            left: 100%;
        }

        #layout-menu .menu-link:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Menu Icon Styles */
        #layout-menu .menu-icon {
            color: #fff !important;
            font-size: 1.3rem !important;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
        }

        #layout-menu .menu-link:hover .menu-icon {
            transform: scale(1.2) rotate(-5deg);
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        /* Active Menu Item */
        #layout-menu .menu-item.active>.menu-link {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%) !important;
            color: #ff9eb5 !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.5);
            transform: translateX(5px);
            border-left: 4px solid #ff9eb5;
        }

        #layout-menu .menu-item.active>.menu-link i {
            color: #ff9eb5 !important;
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.15);
            }
        }

        /* Menu Header / Section Divider */
        #layout-menu .menu-header {
            position: relative;
            padding: 1.25rem 1.5rem 0.5rem !important;
            margin-top: 0.5rem;
        }

        #layout-menu .menu-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            border-radius: 2px;
        }

        #layout-menu .menu-header-text {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600 !important;
            font-size: 0.7rem !important;
            letter-spacing: 1.5px !important;
            text-transform: uppercase;
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        #layout-menu .menu-header-text::after {
            content: '✦';
            margin-left: 8px;
            font-size: 0.6rem;
            animation: sparkle 1.5s ease-in-out infinite;
        }

        @keyframes sparkle {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.3);
            }
        }

        /* Badge Enhancements */
        #layout-menu .badge {
            font-size: 0.65rem !important;
            padding: 0.3em 0.6em;
            animation: badgePulse 2s ease-in-out infinite;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        @keyframes badgePulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        #layout-menu .badge.bg-success {
            background: linear-gradient(135deg, #28c76f 0%, #48da89 100%) !important;
        }

        #layout-menu .badge.bg-danger {
            background: linear-gradient(135deg, #ea5455 0%, #f08182 100%) !important;
        }

        /* ================================
           ENHANCED NAVBAR DESIGN
           ================================ */

        /* Navbar Base with Glassmorphism */
        #layout-navbar {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: none !important;
            border-radius: 16px !important;
            box-shadow:
                0 8px 32px rgba(255, 158, 181, 0.15),
                0 2px 8px rgba(132, 210, 246, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
            margin: 1rem !important;
            padding: 0.75rem 1.5rem !important;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        /* Navbar Gradient Border Effect */
        #layout-navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ff9eb5 0%, #c9aed6 50%, #84d2f6 100%);
            border-radius: 16px 16px 0 0;
        }

        /* Hover lift effect */
        #layout-navbar:hover {
            transform: translateY(-2px);
            box-shadow:
                0 12px 40px rgba(255, 158, 181, 0.2),
                0 4px 12px rgba(132, 210, 246, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.9) !important;
        }

        /* Menu Toggle Button */
        #layout-navbar .bx-menu {
            color: #ff9eb5 !important;
            font-size: 1.8rem;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            padding: 8px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(255, 158, 181, 0.1) 0%, rgba(132, 210, 246, 0.1) 100%);
        }

        #layout-navbar .bx-menu:hover {
            transform: scale(1.15) rotate(180deg);
            background: linear-gradient(135deg, rgba(255, 158, 181, 0.2) 0%, rgba(132, 210, 246, 0.2) 100%);
            box-shadow: 0 4px 15px rgba(255, 158, 181, 0.3);
        }

        /* Navbar Title Styles */
        #layout-navbar .navbar-title {
            background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 1.1rem;
            position: relative;
        }

        #layout-navbar .navbar-title i {
            -webkit-text-fill-color: #ff9eb5;
            margin-right: 8px;
        }

        /* Nav Links */
        #layout-navbar .nav-link {
            color: #566a7f !important;
            transition: all 0.3s ease;
        }

        #layout-navbar .nav-link:hover {
            color: #ff9eb5 !important;
        }

        #layout-navbar a {
            color: #566a7f !important;
            transition: all 0.3s ease;
        }

        #layout-navbar a:hover {
            color: #ff9eb5 !important;
        }

        /* Academic Year Badge Enhancement */
        #layout-navbar .badge.bg-label-primary {
            background: linear-gradient(135deg, rgba(255, 158, 181, 0.15) 0%, rgba(132, 210, 246, 0.15) 100%) !important;
            color: #ff9eb5 !important;
            border: 1px solid rgba(255, 158, 181, 0.3);
            font-weight: 600;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(255, 158, 181, 0.15);
        }

        #layout-navbar .badge.bg-label-primary:hover {
            background: linear-gradient(135deg, rgba(255, 158, 181, 0.25) 0%, rgba(132, 210, 246, 0.25) 100%) !important;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(255, 158, 181, 0.25);
        }

        #layout-navbar .badge.bg-label-primary i {
            animation: calendarPulse 2s ease-in-out infinite;
        }

        @keyframes calendarPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Mobile Logo Animation */
        #layout-navbar .mobile-logo-img {
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        #layout-navbar .mobile-logo-img:hover {
            transform: rotate(15deg) scale(1.1);
        }

        /* Menu Toggle Button (Mobile) */
        #layout-menu .layout-menu-toggle {
            transition: all 0.3s ease;
        }

        #layout-menu .layout-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-radius: 50%;
        }

        /* Disabled Link */
        .disabled-link {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(30%);
        }

        /* Perfect Scrollbar Custom */
        #layout-menu .ps__thumb-y {
            background-color: rgba(255, 255, 255, 0.4) !important;
            width: 4px !important;
        }

        #layout-menu .ps__rail-y:hover .ps__thumb-y {
            background-color: rgba(255, 255, 255, 0.6) !important;
        }

        /* ================================
           CONTENT AREA FIXES
           ================================ */

        /* Ensure layout page takes full width */
        .layout-page {
            flex: 1 1 auto;
            min-width: 0;
        }

        /* Content wrapper full width */
        .content-wrapper {
            width: 100% !important;
        }

        /* Container full width on large screens */
        .container-xxl {
            max-width: 100% !important;
            width: 100% !important;
        }

        /* Fix layout container */
        .layout-container {
            display: flex;
            flex: 1 1 auto;
            width: 100%;
        }

        /* Ensure main content doesn't overflow */
        .layout-wrapper {
            width: 100%;
            overflow-x: hidden;
        }

        /* Override Sneat template padding for full width content */
        @media (min-width: 1200px) {

            .layout-menu-fixed:not(.layout-menu-collapsed) .layout-page,
            .layout-menu-fixed-offcanvas:not(.layout-menu-collapsed) .layout-page {
                padding-inline-start: 0 !important;
                padding-left: 0 !important;
            }

            /* Ensure sidebar doesn't push content */
            .layout-menu-fixed .layout-page,
            .layout-menu-fixed-offcanvas .layout-page {
                margin-left: 0 !important;
            }
        }

        /* Mobile Responsive Improvements */
        @media (max-width: 1199px) {
            .layout-menu {
                width: 260px !important;
            }

            .menu-inner .menu-item .menu-link {
                padding: 0.75rem 1rem !important;
                font-size: 0.95rem !important;
            }

            .menu-icon {
                font-size: 1.2rem !important;
            }
        }

        @media (max-width: 767px) {
            .layout-menu {
                max-width: 280px !important;
            }

            .menu-inner .menu-item .menu-link {
                padding: 1rem 1.25rem !important;
                font-size: 1rem !important;
                min-height: 48px !important;
            }

            .menu-icon {
                font-size: 1.5rem !important;
                margin-right: 1rem !important;
            }

            .app-brand-logo img {
                width: 35px !important;
            }

            .app-brand-text {
                font-size: 1.1rem !important;
            }

            .menu-header {
                padding: 1rem 1.25rem 0.5rem !important;
            }

            .badge {
                font-size: 0.7rem !important;
            }

            /* Navbar Mobile Tweaks */
            #layout-navbar {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .navbar-nav-right {
                width: 100%;
            }
        }

        /* ============================================================
           TOP NAVIGATION BAR (VIBRANT PINK & BLUE THEME)
           ============================================================ */
        .layout-navbar {
            background: #ffffff !important;
            border-bottom: 3px solid #e11d48 !important;
            box-shadow: 0 4px 20px rgba(225, 29, 72, 0.08) !important;
        }

        .top-nav-item {
            color: #0f172a !important;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 8px 14px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            border: 1px solid transparent;
        }

        .top-nav-item i {
            color: #0284c7;
            font-size: 1.15rem;
        }

        .top-nav-item:hover {
            color: #e11d48 !important;
            background: #fff1f2;
            border-color: #fecdd3;
        }

        .top-nav-item.active,
        .top-nav-item.active *,
        .top-nav-item.active i,
        .top-nav-item.active span {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }

        .top-nav-item.active {
            background: linear-gradient(135deg, #e11d48 0%, #ff2d75 35%, #0284c7 85%, #0369a1 100%) !important;
            box-shadow: 0 4px 14px rgba(225, 29, 72, 0.4);
            border-color: transparent !important;
        }

        /* Mobile Drawer Styles */
        .menu-vertical .menu-item .menu-link {
            color: #0f172a !important;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .menu-vertical .menu-item.active > .menu-link,
        .menu-vertical .menu-item.active > .menu-link *,
        .menu-vertical .menu-item.active > .menu-link i,
        .menu-vertical .menu-item.active > .menu-link div {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }

        .menu-vertical .menu-item.active > .menu-link {
            background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%) !important;
            font-weight: 800;
            box-shadow: 0 4px 14px rgba(225, 29, 72, 0.35);
        }

        .mobile-menu-btn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, #fff1f2 0%, #f0f9ff 100%);
            border: 1.5px solid #e11d48;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e11d48 !important;
            font-size: 1.6rem;
            box-shadow: 0 4px 10px rgba(225, 29, 72, 0.15);
            transition: all 0.2s ease;
        }

        .mobile-menu-btn:hover,
        .mobile-menu-btn:active {
            background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
            color: #ffffff !important;
            transform: scale(1.05);
        }

        /* ============================================================
           MOBILE & UNIVERSAL BOTTOM NAVIGATION (APP BAR)
           ============================================================ */
        .mobile-bottom-bar {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 68px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1.5px solid #cbd5e1;
            box-shadow: 0 -6px 25px rgba(0, 0, 0, 0.08);
            z-index: 1040;
            justify-content: space-around;
            align-items: center;
            padding: 0 0.5rem;
        }

        body {
            padding-bottom: 85px !important;
        }

        .content-footer {
            margin-bottom: 25px;
        }

        .mobile-nav-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 5px 12px 7px 12px;
            border-radius: 16px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 60px;
            position: relative;
        }

        .mobile-nav-tab i {
            font-size: 1.45rem;
            margin-bottom: 2px;
            color: #64748b;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-nav-tab:hover {
            color: #e11d48;
        }

        .mobile-nav-tab:hover i {
            color: #e11d48;
            transform: translateY(-2px);
        }

        /* Active State - Rose Pink Gradient Glow & Capsule Highlight */
        .mobile-nav-tab.active {
            color: #e11d48 !important;
            font-weight: 800 !important;
            background: rgba(225, 29, 72, 0.08);
        }

        .mobile-nav-tab.active i {
            color: #e11d48 !important;
            transform: scale(1.18);
            filter: drop-shadow(0 2px 6px rgba(225, 29, 72, 0.35));
        }

        .mobile-nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: 3px;
            width: 16px;
            height: 3px;
            border-radius: 50px;
            background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        }

        /* Desktop Dock Floating Style (>= 992px) */
        @media (min-width: 992px) {
            .mobile-bottom-bar {
                left: 50% !important;
                right: auto !important;
                transform: translateX(-50%);
                width: 540px;
                border-radius: 34px;
                border: 1.5px solid #cbd5e1;
                bottom: 18px;
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.14);
            }
        }

        /* Unified Modern Navigation: Hide legacy Sneat sidebar & overlay */
        #layout-menu,
        .layout-overlay {
            display: none !important;
            pointer-events: none !important;
            visibility: hidden !important;
        }

        .layout-page {
            padding-left: 0 !important;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: calc(100vh - 85px);
            width: 100%;
        }

        .container-xxl,
        .container-p-y,
        .container-xxl.container-p-y,
        .content-wrapper > .container-p-y {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 2rem !important;
            padding-right: 2rem !important;
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: center !important;
            align-items: center;
        }

        .content-wrapper > .container-p-y > * {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* High-Contrast Crisp Pure White Inputs */
        .form-control,
        .form-select,
        .form-floating > .form-control,
        .form-floating > .form-select,
        .input-group-text {
            background-color: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            color: #1e293b !important;
        }

        .form-control:focus,
        .form-select:focus,
        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            background-color: #ffffff !important;
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.18) !important;
        }

        /* ============================================================
           EDGE-TO-EDGE NATIVE MOBILE APP FEEL (< 576px)
           ============================================================ */
        @media (max-width: 576px) {
            .layout-page,
            .content-wrapper {
                padding-top: 0 !important;
                margin-top: 0 !important;
            }

            .container-xxl,
            .container-p-y,
            .content-wrapper > .container-p-y {
                padding-left: 4px !important;
                padding-right: 4px !important;
                padding-top: 4px !important;
                padding-bottom: 6px !important;
                margin-top: 0 !important;
            }

            .row {
                --bs-gutter-x: 0.25rem !important;
                margin-left: -2px !important;
                margin-right: -2px !important;
            }

            .row > * {
                padding-left: 2px !important;
                padding-right: 2px !important;
            }

            .card {
                border-radius: 14px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-top: 0 !important;
                width: 100% !important;
            }

            .card-body {
                padding: 1rem 0.75rem !important;
            }

            .hero-banner {
                border-radius: 16px !important;
                padding: 1.25rem 0.85rem !important;
                margin-top: 0 !important;
                margin-bottom: 1rem !important;
            }

            .confirmation-viewport-wrapper,
            .status-viewport-container {
                max-width: 100% !important;
                padding: 0 !important;
                margin-top: 0 !important;
                width: 100% !important;
                min-height: auto !important;
            }
        }

        /* ============================================================
           MAGICUI ANIMATED GRID PATTERN BACKGROUND (FIXED Z-INDEX)
           ============================================================ */
        body {
            background-color: #f8fafc !important;
            position: relative;
            min-height: 100vh;
        }

        .animated-grid-container,
        .animated-grid-container *,
        .animated-grid-svg,
        .animated-grid-svg * {
            pointer-events: none !important;
            user-select: none !important;
        }

        .animated-grid-container {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none !important;
            z-index: -1 !important;
            overflow: hidden;
            mask-image: radial-gradient(ellipse at 50% 30%, rgba(0, 0, 0, 0.95), transparent 85%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 30%, rgba(0, 0, 0, 0.95), transparent 85%);
        }

        .animated-grid-svg {
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0;
            pointer-events: none !important;
        }

        .layout-wrapper {
            position: relative;
            z-index: 1;
        }

        @keyframes gridFlicker {
            0%, 100% {
                opacity: 0;
                transform: scale(0.92);
            }
            50% {
                opacity: 0.85;
                transform: scale(1);
            }
        }

        .grid-square-animated {
            animation: gridFlicker var(--duration, 4s) infinite ease-in-out;
            animation-delay: var(--delay, 0s);
            transform-origin: center;
            pointer-events: none !important;
        }
    </style>
    <style>
        .swal2-container {
            z-index: 100000 !important;
        }
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-dialog {
            z-index: 1065 !important;
        }
        .content-backdrop:not(.show),
        .layout-overlay:not(.show) {
            display: none !important;
            pointer-events: none !important;
        }
        @media (min-width: 992px) {
            .layout-overlay,
            .content-backdrop,
            .content-backdrop.fade,
            .content-backdrop.show {
                display: none !important;
                pointer-events: none !important;
                visibility: hidden !important;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>

    <!-- Helpers -->
    <script src="<?= base_url('public/sneat-assets/vendor/js/helpers.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/js/config.js') ?>"></script>
</head>

<body>
    <!-- MagicUI Animated Grid Pattern Background (Suankularb Pink & Blue Tint) -->
    <div class="animated-grid-container" aria-hidden="true">
        <svg class="animated-grid-svg" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="animated-grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse" x="-1" y="-1">
                    <path d="M.5 40V.5H40" fill="none" stroke="rgba(15, 23, 42, 0.08)" stroke-width="1" stroke-dasharray="0" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" stroke-width="0" fill="url(#animated-grid-pattern)" />
            <svg x="-1" y="-1" class="overflow-visible" id="animated-grid-squares">
                <!-- Dynamically injected animated SVG squares in Pink & Sky Blue -->
            </svg>
        </svg>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="<?= base_url('new-admission') ?>" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" width="40"
                                class="img-fluid">
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">SKJ Admission</span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item <?= uri_string() == 'new-admission' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">หน้าแรก</div>
                        </a>
                    </li>

                    <li class="menu-item <?= uri_string() == 'new-admission/manual' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/manual') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-book-open"></i>
                            <div data-i18n="Manual">คู่มือการสมัคร</div>
                        </a>
                    </li>

                    <li class="menu-item <?= uri_string() == 'new-admission/manual-report' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/manual-report') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-user-check"></i>
                            <div data-i18n="ManualReport">คู่มือการรายงานตัว</div>
                        </a>
                    </li>

                    <!-- Registration -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">สมัครเรียน</span>
                    </li>
                    <?php
                    // Logic to determine open levels
                    $open_levels = [];

                    // Check System Status & Date Time
                    $is_system_open = false;

                    if (isset($systemStatus) && $systemStatus->onoff_regis == 'on') {
                        $is_system_open = true;
                        $current_time_layout = time();

                        // Check Open Time
                        if (isset($systemStatus->onoff_datetime_regis_open) && !empty($systemStatus->onoff_datetime_regis_open)) {
                            if ($current_time_layout < strtotime($systemStatus->onoff_datetime_regis_open)) {
                                $is_system_open = false;
                            }
                        }

                        if (isset($systemStatus->onoff_datetime_regis_close) && !empty($systemStatus->onoff_datetime_regis_close)) {
                            if ($current_time_layout > strtotime($systemStatus->onoff_datetime_regis_close)) {
                                $is_system_open = false;
                            }
                        }
                    }

                    if ($is_system_open && !empty($quotas)) {
                        foreach ($quotas as $quota) {
                            if (isset($quota->quota_status) && $quota->quota_status == 'on' && !empty($quota->quota_level)) {
                                $levels_in_quota = preg_split('/[|,]/', $quota->quota_level);
                                foreach ($levels_in_quota as $level_str) {
                                    $level_num_char = preg_replace('/[^0-9]/', '', $level_str);
                                    if (is_numeric($level_num_char) && !empty($level_num_char)) {
                                        $open_levels[] = intval($level_num_char);
                                    }
                                }
                            }
                        }
                    }
                    $open_levels = array_values(array_unique($open_levels));
                    sort($open_levels);

                    foreach ($open_levels as $level_num):
                        $current_uri_level = (uri_string() == 'new-admission/pre-check/' . $level_num && service('request')->getGet('level') == $level_num) || uri_string() == 'new-admission/register/' . $level_num;

                        $destination_url = base_url('new-admission/pre-check/' . $level_num . '?level=' . $level_num);
                        ?>
                        <li class="menu-item <?= $current_uri_level ? 'active' : '' ?>">
                            <a href="<?= $destination_url ?>" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                                <div data-i18n="M.<?= $level_num ?>">ระดับชั้น ม.<?= $level_num ?></div>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <!-- Status -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">ตรวจสอบ</span>
                    </li>
                    <li class="menu-item <?= uri_string() == 'new-admission/status' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/status') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-search-alt"></i>
                            <div data-i18n="Status">ตรวจสอบสถานะ</div>
                        </a>
                    </li>
                    <li class="menu-item <?= uri_string() == 'new-admission/announcements' || strpos(uri_string(), 'new-admission/announcements') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/announcements') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-news"></i>
                            <div data-i18n="Announcements">ประกาศผล</div>
                        </a>
                    </li>
                    <li class="menu-item <?= uri_string() == 'new-admission/statistics' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/statistics') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                            <div data-i18n="Statistics">สถิติผู้สมัคร</div>
                        </a>
                    </li>
                    <?php
                    $is_confirmation_open = false;
                    if (isset($systemStatus) && isset($systemStatus->onoff_report) && $systemStatus->onoff_report == 'on') {
                        $is_confirmation_open = true;
                    }
                    ?>
                    <li class="menu-item <?= uri_string() == 'confirmation/login' ? 'active' : '' ?>">
                        <a href="<?= $is_confirmation_open ? base_url('confirmation/login') : 'javascript:void(0);' ?>" class="menu-link <?= !$is_confirmation_open ? 'disabled-link' : '' ?>" data-disabled-message="ระบบรายงานตัวยังไม่เปิดให้บริการ">
                            <i class="menu-icon tf-icons bx bx-user-check"></i>
                            <div data-i18n="Confirmation">
                                รายงานตัว
                                <?php if (!$is_confirmation_open): ?>
                                    <span class="badge bg-danger ms-2">ปิด</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>

                    <!-- Help & Contact -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">คู่มือและติดต่อ</span>
                    </li>
                    <li class="menu-item <?= uri_string() == 'new-admission/manual' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/manual') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-book-open"></i>
                            <div data-i18n="Manual">คู่มือการสมัคร</div>
                        </a>
                    </li>
                    <li class="menu-item <?= uri_string() == 'new-admission/manual-report' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/manual-report') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-file-blank"></i>
                            <div data-i18n="ManualReport">คู่มือการรายงานตัว</div>
                        </a>
                    </li>
                    <li class="menu-item <?= uri_string() == 'contact' ? 'active' : '' ?>">
                        <a href="<?= base_url('contact') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-support"></i>
                            <div data-i18n="Contact">ติดต่อสอบถาม</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column justify-content-center p-0 p-sm-3">
                        <?= $this->renderSection('content') ?>
                    </div>
                    <!-- / Content -->

                    <!-- Modern School Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3">
                            <div class="d-flex align-items-center">
                                <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" width="28" height="28" class="me-2 rounded-circle">
                                <div>
                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</span>
                                    <small class="d-block text-muted" style="font-size: 0.72rem;">สังกัดองค์การบริหารส่วนจังหวัดนครสวรรค์</small>
                                </div>
                            </div>
                            <div class="text-center text-md-end text-muted small" style="font-size: 0.76rem;">
                                <div>ระบบรับสมัครนักเรียนออนไลน์ &copy; <?= date('Y') + 543 ?></div>
                                <div class="text-muted opacity-75">Suankularb Wittayalai (Jiraprawat) Nakhonsawan School</div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- ============================================================
         MOBILE & UNIVERSAL BOTTOM NAVIGATION (APP BAR - MOBILE FIRST)
         ============================================================ -->
    <nav class="mobile-bottom-bar">
        <!-- 1. Home -->
        <a href="<?= base_url('new-admission') ?>" class="mobile-nav-tab <?= (uri_string() == '' || uri_string() == 'new-admission') ? 'active' : '' ?>">
            <i class='bx bx-home-alt'></i>
            <span>หน้าแรก</span>
        </a>

        <!-- 2. Apply (Dynamic Levels Unified) -->
        <a href="javascript:void(0);" onclick="showLevelChoiceModal()" class="mobile-nav-tab <?= (strpos(uri_string(), 'pre-check') !== false || strpos(uri_string(), 'register') !== false || strpos(uri_string(), 'apply') !== false) ? 'active' : '' ?>">
            <i class='bx bx-user-plus'></i>
            <span>สมัครเรียน</span>
        </a>

        <!-- 3. Status -->
        <a href="<?= base_url('new-admission/status') ?>" class="mobile-nav-tab <?= strpos(uri_string(), 'status') !== false ? 'active' : '' ?>">
            <i class='bx bx-search-alt'></i>
            <span>สถานะ</span>
        </a>

        <!-- 4. Confirmation / Report -->
        <a href="<?= base_url('confirmation/login') ?>" class="mobile-nav-tab <?= strpos(uri_string(), 'confirmation') !== false ? 'active' : '' ?>">
            <i class='bx bx-user-check'></i>
            <span>รายงานตัว</span>
        </a>

        <!-- 5. All Menu (Drawer Trigger) -->
        <a href="javascript:void(0);" class="mobile-nav-tab <?= (strpos(uri_string(), 'contact') !== false || strpos(uri_string(), 'schedule') !== false) ? 'active' : '' ?>" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvasDrawer">
            <i class='bx bx-grid-alt'></i>
            <span>เมนู</span>
        </a>
    </nav>

    <!-- ============================================================
         MOBILE OFFCANVAS DRAWER
         ============================================================ -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileOffcanvasDrawer" aria-labelledby="mobileOffcanvasDrawerLabel" style="width: 320px; border-radius: 0 24px 24px 0; border: none; box-shadow: 10px 0 35px rgba(0,0,0,0.18);">
        <div class="offcanvas-header p-3 text-white" style="background: linear-gradient(135deg, #e11d48 0%, #ff2d75 35%, #0284c7 80%, #0369a1 100%);">
            <div class="d-flex align-items-center">
                <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" width="46" height="46" class="me-2 rounded-circle bg-white p-1 shadow-sm">
                <div>
                    <h6 class="offcanvas-title text-white fw-bold mb-0" id="mobileOffcanvasDrawerLabel">SKJ Admission</h6>
                    <small style="font-size: 0.72rem; color: rgba(255,255,255,0.92);">รร.สวนกุหลาบวิทยาลัย (จิรประวัติ)</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-3 d-flex flex-column justify-content-between">
            <div class="list-group list-group-flush gap-1">
                <a href="<?= base_url('new-admission') ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= (uri_string() == '' || uri_string() == 'new-admission') ? 'active' : '' ?>">
                    <i class='bx bx-home-alt fs-5 me-3 text-primary'></i> หน้าแรกรับสมัคร
                </a>

                <div class="small fw-bold text-uppercase text-muted px-3 mt-3 mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">สมัครเรียนออนไลน์</div>
                
                <?php if ($is_system_open && !empty($open_levels)): ?>
                    <?php foreach ($open_levels as $l_num): 
                        $is_jr = $l_num <= 3;
                        $l_icon = $is_jr ? 'bx-user' : 'bx-award';
                        $l_color = $is_jr ? '#e11d48' : '#0284c7';
                    ?>
                    <a href="<?= base_url('new-admission/pre-check/' . $l_num . '?level=' . $l_num) ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= (strpos(uri_string(), 'pre-check/' . $l_num) !== false || strpos(uri_string(), 'register/' . $l_num) !== false) ? 'active' : '' ?>">
                        <i class='bx <?= $l_icon ?> fs-5 me-3' style="color: <?= $l_color ?>;"></i> สมัครระดับชั้น ม.<?= $l_num ?>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="px-3 py-2 text-muted small d-flex align-items-center"><i class='bx bx-lock-alt me-2 text-danger fs-5'></i> ปิดรับสมัครในขณะนี้</div>
                <?php endif; ?>

                <div class="small fw-bold text-uppercase text-muted px-3 mt-3 mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">บริการและตรวจสอบ</div>

                <a href="<?= base_url('new-admission/status') ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= uri_string() == 'new-admission/status' ? 'active' : '' ?>">
                    <i class='bx bx-search-alt fs-5 me-3 text-primary'></i> ตรวจสอบสถานะการสมัคร
                </a>
                <a href="<?= base_url('new-admission/announcements') ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= strpos(uri_string(), 'announcements') !== false ? 'active' : '' ?>">
                    <i class='bx bx-news fs-5 me-3 text-warning'></i> ประกาศผลการคัดเลือก
                </a>
                <a href="<?= base_url('new-admission/statistics') ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= uri_string() == 'new-admission/statistics' ? 'active' : '' ?>">
                    <i class='bx bx-bar-chart-alt-2 fs-5 me-3 text-success'></i> สถิติยอดผู้สมัคร
                </a>
                <a href="<?= $is_confirmation_open ? base_url('confirmation/login') : 'javascript:void(0);' ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= uri_string() == 'confirmation/login' ? 'active' : '' ?> <?= !$is_confirmation_open ? 'disabled-link' : '' ?>">
                    <i class='bx bx-user-check fs-5 me-3 text-info'></i> รายงานตัวออนไลน์
                </a>

                <div class="small fw-bold text-uppercase text-muted px-3 mt-3 mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">คู่มือและติดต่อ</div>

                <a href="<?= base_url('new-admission/manual') ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= uri_string() == 'new-admission/manual' ? 'active' : '' ?>">
                    <i class='bx bx-book-open fs-5 me-3 text-secondary'></i> คู่มือการสมัคร
                </a>
                <a href="<?= base_url('new-admission/manual-report') ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= uri_string() == 'new-admission/manual-report' ? 'active' : '' ?>">
                    <i class='bx bx-file-blank fs-5 me-3 text-secondary'></i> คู่มือการรายงานตัว
                </a>
                <a href="<?= base_url('contact') ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 py-2 px-3 fw-bold <?= uri_string() == 'contact' ? 'active' : '' ?>">
                    <i class='bx bx-support fs-5 me-3 text-primary'></i> ติดต่อสอบถาม / FAQs
                </a>
            </div>

            <div class="pt-3 border-top mt-3">
                <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center">
                    <i class='bx bx-lock-alt me-2'></i> เจ้าหน้าที่เข้าสู่ระบบ
                </a>
            </div>
        </div>
    </div>

    <!-- Live Chat Widget (Connected with Telegram) -->
    <?= view('User/Components/ChatWidget') ?>

    <!-- Core JS -->
    <script src="<?= base_url('public/sneat-assets/vendor/libs/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/libs/popper/popper.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/js/menu.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/libs/apex-charts/apexcharts.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/js/main.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?= $this->renderSection('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const disabledLinks = document.querySelectorAll('.disabled-link');
            disabledLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (this.classList.contains('disabled-link')) {
                        e.preventDefault();
                        const message = this.dataset.disabledMessage || 'ไม่สามารถใช้งานได้ในขณะนี้';
                        Swal.fire({
                            icon: 'warning',
                            title: 'ระบบยังไม่เปิด',
                            text: message,
                            confirmButtonText: 'ตกลง'
                        });
                    }
                });
            });
        });
    </script>
    <script>
        // Global SweetAlert2 Level Choice function
        function showLevelChoiceModal() {
            <?php if (!$is_system_open || empty($open_levels)): ?>
                Swal.fire({
                    icon: 'info',
                    title: 'ระบบยังไม่เปิดรับสมัคร',
                    text: 'ขณะนี้ระบบปิดรับสมัคร หรือไม่มีระดับชั้นที่เปิดรับสมัคร กรุณาติดตามประกาศจากทางโรงเรียน',
                    confirmButtonText: 'รับทราบ',
                    confirmButtonColor: '#e11d48'
                });
                return;
            <?php elseif (count($open_levels) === 1): ?>
                <?php $single_lvl = reset($open_levels); ?>
                showPdpaAgreementSwal('<?= base_url('new-admission/pre-check/' . $single_lvl . '?level=' . $single_lvl) ?>');
                return;
            <?php else: ?>
                Swal.fire({
                    title: '<div class="fw-bold text-dark fs-5"><i class="bx bx-select-multiple text-primary me-2"></i> เลือกระดับชั้นที่ต้องการสมัคร</div>',
                    html: `
                        <div class="d-flex flex-column gap-2 my-2 text-start">
                            <?php foreach ($open_levels as $l_num): 
                                $is_jr = $l_num <= 3;
                                $bg_tint = $is_jr ? 'rgba(225, 29, 72, 0.04)' : 'rgba(2, 132, 199, 0.04)';
                                $bd_tint = $is_jr ? 'rgba(225, 29, 72, 0.2)' : 'rgba(2, 132, 199, 0.2)';
                                $bg_icon = $is_jr ? '#e11d48' : '#0284c7';
                                $icon_cls = $is_jr ? 'bx-user' : 'bx-award';
                                $sub_desc = $is_jr ? ($l_num == 1 ? 'สำหรับนักเรียนจบ ป.6 หรือเทียบเท่า' : 'ระดับมัธยมศึกษาตอนต้น') : ($l_num == 4 ? 'สำหรับนักเรียนจบ ม.3 หรือเทียบเท่า' : 'ระดับมัธยมศึกษาตอนปลาย');
                            ?>
                            <button type="button" onclick="Swal.close(); showPdpaAgreementSwal('<?= base_url('new-admission/pre-check/' . $l_num . '?level=' . $l_num) ?>');" class="btn p-3 text-start d-flex align-items-center justify-content-between rounded-3 border w-100" style="background: <?= $bg_tint ?>; border-color: <?= $bd_tint ?> !important;">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px; background: <?= $bg_icon ?>; color: white;">
                                        <i class="bx <?= $icon_cls ?> fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">สมัครระดับชั้น มัธยมศึกษาปีที่ <?= $l_num ?></div>
                                        <small class="text-muted"><?= $sub_desc ?></small>
                                    </div>
                                </div>
                                <i class="bx bx-chevron-right fs-4 text-muted"></i>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true,
                    customClass: {
                        popup: 'rounded-4 shadow-lg'
                    }
                });
            <?php endif; ?>
        }

        // Global SweetAlert2 PDPA confirmation function
        function showPdpaAgreementSwal(targetUrl) {
            Swal.fire({
                title: '<div style="font-size: 1.15rem; font-weight: 700; color: #0f172a;"><i class="bx bx-shield-quarter me-2 text-primary"></i> ข้อตกลงการใช้ข้อมูลส่วนบุคคล (PDPA)</div>',
                html: `
                    <div class="text-start p-3 border rounded-3 bg-light" style="max-height: 260px; overflow-y: auto; font-size: 0.88rem; line-height: 1.6; color: #1e293b;">
                        <p class="fw-bold mb-2">ข้อตกลงการใช้ข้อมูลส่วนบุคคลในการลงทะเบียนและสมัครเข้าศึกษาต่อในระบบรับสมัครออนไลน์ของโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
                        <p>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของผู้สมัคร ข้อมูลของท่านจะถูกนำไปใช้เพื่อกระบวนการรับสมัคร ตรวจสอบคุณสมบัติ และจัดทำทะเบียนประวัติตามระเบียบของทางโรงเรียนเท่านั้น</p>
                        <ul class="ps-3 mb-2">
                            <li>ข้าพเจ้ารับรองว่าข้อมูลที่กรอกและเอกสารที่แนบเป็นความจริงทุกประการ</li>
                            <li>หากตรวจพบว่าข้อมูลหรือเอกสารเป็นเท็จ ทางโรงเรียนขอสงวนสิทธิ์ตัดสิทธิ์การสมัคร</li>
                        </ul>
                        <p class="mt-2 text-muted small">การกด "ยอมรับและดำเนินการต่อ" ถือว่าท่านได้อ่านและเข้าใจข้อความข้างต้นโดยละเอียด และยินยอมให้โรงเรียนเก็บรวบรวม ใช้ข้อมูลตามวัตถุประสงค์ทุกประการ</p>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'ยอมรับและดำเนินการต่อ',
                cancelButtonText: 'ไม่ยอมรับ',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed && targetUrl) {
                    window.location.href = targetUrl;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle Sidebar PDPA Navigation with SweetAlert2
            const sidebarPdpaBtns = document.querySelectorAll('.pdpa-sidebar-btn');
            sidebarPdpaBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const selectedSidebarHref = this.getAttribute('data-href');
                    if (selectedSidebarHref) {
                        showPdpaAgreementSwal(selectedSidebarHref);
                    }
                });
            });

            // Handle Closed Sidebar Links
            const closedSidebarBtns = document.querySelectorAll('.closed-sidebar-btn');
            closedSidebarBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'ระบบยังไม่เปิดรับสมัคร',
                        text: 'กรุณาติดตามกำหนดการรับสมัคร หรือรอประกาศจากทางโรงเรียน',
                        confirmButtonText: 'รับทราบ',
                        confirmButtonColor: '#e11d48'
                    });
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('form:not(.ajax-form):not(#skjUserChatForm)').on('submit', function (e) {
                if (e.isDefaultPrevented()) return;
                var $form = $(this);
                if ($form[0].checkValidity()) {
                    var $btn = $form.find('button[type="submit"]');
                    var $clickedBtn = $(document.activeElement);
                    if ($clickedBtn.length && $clickedBtn.is('button[type="submit"]') && $form.has($clickedBtn).length) {
                        $btn = $clickedBtn;
                    }

                    if ($btn.length > 0 && !$btn.hasClass('no-disable')) {
                        $btn.addClass('disabled');
                        $btn.css('pointer-events', 'none');
                        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังบันทึก...');
                    }
                }
            });
        });
    </script>

    <!-- MagicUI Animated Grid Pattern Script (SVG squares in Suankularb Pink & Blue) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const svgContainer = document.getElementById('animated-grid-squares');
            if (!svgContainer) return;

            const squareSize = 40;
            const colors = [
                { fill: 'rgba(225, 29, 72, 0.22)', stroke: 'rgba(225, 29, 72, 0.45)' },  // Rose Pink
                { fill: 'rgba(2, 132, 199, 0.22)', stroke: 'rgba(2, 132, 199, 0.45)' },  // Sky Blue
                { fill: 'rgba(255, 77, 109, 0.18)', stroke: 'rgba(255, 77, 109, 0.35)' }, // Light Pink
                { fill: 'rgba(56, 189, 248, 0.18)', stroke: 'rgba(56, 189, 248, 0.35)' }  // Light Sky Blue
            ];

            const numSquares = 45;
            const cols = Math.ceil(window.innerWidth / squareSize) + 2;
            const rows = Math.ceil(window.innerHeight / squareSize) + 2;
            const usedCoords = new Set();

            let svgContent = '';
            for (let i = 0; i < numSquares; i++) {
                let x = Math.floor(Math.random() * cols);
                let y = Math.floor(Math.random() * rows);
                let key = `${x},${y}`;

                if (usedCoords.has(key)) continue;
                usedCoords.add(key);

                const color = colors[Math.floor(Math.random() * colors.length)];
                const duration = (3.5 + Math.random() * 4.5).toFixed(2);
                const delay = (Math.random() * 6).toFixed(2);

                svgContent += `<rect 
                    x="${x * squareSize + 1}" 
                    y="${y * squareSize + 1}" 
                    width="${squareSize - 1}" 
                    height="${squareSize - 1}" 
                    fill="${color.fill}" 
                    stroke="${color.stroke}" 
                    stroke-width="1"
                    rx="4"
                    class="grid-square-animated"
                    style="--duration: ${duration}s; --delay: ${delay}s;" 
                />`;
            }
            svgContainer.innerHTML = svgContent;
        });
    </script>
</body>

</html>
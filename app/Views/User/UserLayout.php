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
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
        body {
            font-family: 'K2D', 'Prompt', 'Public Sans', sans-serif;
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
    </style>
    <style>
        .swal2-container {
            z-index: 100000 !important;
        }
    </style>
    <?= $this->renderSection('styles') ?>

    <!-- Helpers -->
    <script src="<?= base_url('public/sneat-assets/vendor/js/helpers.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/js/config.js') ?>"></script>
</head>

<body>
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

                        // Check Close Time
                        if (isset($systemStatus->onoff_datetime_regis_close) && !empty($systemStatus->onoff_datetime_regis_close)) {
                            if ($current_time_layout > strtotime($systemStatus->onoff_datetime_regis_close)) {
                                $is_system_open = false;
                            }
                        }
                    }

                    if ($is_system_open && !empty($quotas)) {
                        foreach ($quotas as $quota) {
                            if (isset($quota->quota_status) && $quota->quota_status == 'on' && !empty($quota->quota_level)) {
                                $level_string = $quota->quota_level;
                                $levels_in_quota = [];

                                if (strpos($level_string, '|') !== false) {
                                    $levels_in_quota = explode('|', $level_string);
                                } elseif (strpos($level_string, ',') !== false) {
                                    $levels_in_quota = explode(',', $level_string);
                                } else {
                                    $levels_in_quota = [$level_string];
                                }

                                foreach ($levels_in_quota as $level_str) {
                                    $level_num_char = preg_replace('/[^0-9]/', '', $level_str);
                                    if (is_numeric($level_num_char)) {
                                        $open_levels[] = intval($level_num_char);
                                    }
                                }
                            }
                        }
                    }
                    $open_levels = array_unique($open_levels);
                    sort($open_levels);

                    // Display menu items for open levels
                    foreach ($open_levels as $level_num):
                        $is_junior_high = $level_num <= 3;
                        $menu_link_level = $is_junior_high ? '1' : '4';
                        $current_uri_level = (uri_string() == 'new-admission/pre-check/' . $menu_link_level && service('request')->getGet('level') == $level_num) || uri_string() == 'new-admission/register/' . $menu_link_level;

                        // Destination URL
                        $destination_url = base_url('new-admission/pre-check/' . $menu_link_level . '?level=' . $level_num);
                        ?>
                        <li class="menu-item <?= $current_uri_level ? 'active' : '' ?>">
                            <a href="javascript:void(0);"
                                class="menu-link <?= !$is_system_open ? 'closed-sidebar-btn text-muted' : 'pdpa-sidebar-btn' ?>"
                                <?= $is_system_open ? 'data-href="' . $destination_url . '"' : '' ?>>
                                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                                <div data-i18n="M.<?= $level_num ?>">
                                    ระดับชั้น ม.<?= $level_num ?>
                                    <?php if (!$is_system_open): ?>
                                        <span class="badge bg-danger ms-2">ปิด</span>
                                    <?php endif; ?>
                                </div>
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
                    <li class="menu-item <?= uri_string() == 'new-admission/statistics' ? 'active' : '' ?>">
                        <a href="<?= base_url('new-admission/statistics') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                            <div data-i18n="Statistics">สถิติการรับสมัคร</div>
                        </a>
                    </li>
                    <?php
                    $is_confirmation_open = false;
                    $confirmation_status_text = 'ปิดรับรายงานตัว';
                    if (isset($systemStatus) && isset($systemStatus->onoff_report) && $systemStatus->onoff_report == 'on') {
                        $is_confirmation_open = true;
                        $confirmation_status_text = 'เปิดรับรายงานตัว';
                    }
                    ?>
                    <li class="menu-item <?= uri_string() == 'confirmation/login' ? 'active' : '' ?>">
                        <a href="<?= $is_confirmation_open ? base_url('confirmation/login') : 'javascript:void(0);' ?>"
                            class="menu-link <?= !$is_confirmation_open ? 'disabled-link' : '' ?>"
                            <?= !$is_confirmation_open ? 'data-bs-toggle="tooltip" data-bs-placement="right" title="ยังไม่เปิดรับรายงานตัว" data-disabled-message="ยังไม่เปิดรับรายงานตัว"' : '' ?>>
                            <i class="menu-icon tf-icons bx bx-user-check"></i>
                            <div data-i18n="Confirmation">รายงานตัวนักเรียนใหม่
                                <?php if (!$is_confirmation_open): ?>
                                    <span class="badge bg-danger ms-2">ปิด</span>
                                <?php else: ?>
                                    <span class="badge bg-success ms-2">เปิด</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>


                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">

                    <!-- Mobile Toggle Button -->
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Brand/Title Area -->
                        <div class="navbar-nav align-items-center flex-grow-1">
                            <div class="nav-item d-flex align-items-center">
                                <!-- Desktop Title with Gradient -->
                                <div class="d-none d-md-flex align-items-center">
                                    <div class="navbar-brand-icon me-3" style="
                      width: 45px; 
                      height: 45px; 
                      background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
                      border-radius: 12px;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                      box-shadow: 0 4px 15px rgba(255, 158, 181, 0.3);
                    ">
                                        <i class='bx bxs-school' style="font-size: 1.5rem; color: white;"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="navbar-title" style="
                        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        font-weight: 700;
                        font-size: 1.15rem;
                        line-height: 1.2;
                      ">ระบบรับสมัครนักเรียนออนไลน์</span>
                                        <span
                                            style="font-size: 0.8rem; color: #697a8d; font-weight: 500;">โรงเรียนสวนกุหลาบวิทยาลัย
                                            (จิรประวัติ) นครสวรรค์</span>
                                    </div>
                                </div>

                                <!-- Mobile Title with Logo -->
                                <div class="d-flex align-items-center d-md-none">
                                    <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="Logo" width="36"
                                        height="36" class="me-2 mobile-logo-img"
                                        style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold"
                                            style="font-size: 0.95rem; line-height: 1.1; background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">SKJ
                                            Admission</span>
                                        <span class="text-muted"
                                            style="font-size: 0.7rem;">ระบบรับสมัครนักเรียนใหม่</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Brand -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">
                            <!-- Status Indicator -->
                            <li class="nav-item d-none d-lg-block">
                                <?php
                                $status_open = isset($systemStatus) && $systemStatus->onoff_regis == 'on';
                                ?>
                                <span class="badge rounded-pill px-3 py-2" style="
                    background: <?= $status_open ? 'linear-gradient(135deg, rgba(40, 199, 111, 0.15) 0%, rgba(72, 218, 137, 0.15) 100%)' : 'linear-gradient(135deg, rgba(234, 84, 85, 0.15) 0%, rgba(240, 129, 130, 0.15) 100%)' ?>;
                    color: <?= $status_open ? '#28c76f' : '#ea5455' ?>;
                    border: 1px solid <?= $status_open ? 'rgba(40, 199, 111, 0.3)' : 'rgba(234, 84, 85, 0.3)' ?>;
                    font-weight: 600;
                    font-size: 0.8rem;
                  ">
                                    <i class='bx <?= $status_open ? 'bx-check-circle' : 'bx-x-circle' ?> me-1'></i>
                                    <?= $status_open ? 'เปิดรับสมัคร' : 'ปิดรับสมัคร' ?>
                                </span>
                            </li>

                            <!-- Academic Year Badge -->
                            <li class="nav-item lh-1 me-0">
                                <span class="badge bg-label-primary rounded-pill">
                                    <i class='bx bx-calendar me-1'></i>ปีการศึกษา <?php
                                    if (isset($checkYear) && is_array($checkYear) && isset($checkYear[0]->openyear_year)) {
                                        echo $checkYear[0]->openyear_year;
                                    } elseif (isset($checkYear->openyear_year)) {
                                        echo $checkYear->openyear_year;
                                    } else {
                                        echo date('Y') + 543;
                                    }
                                    ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <?= $this->renderSection('content') ?>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>document.write(new Date().getFullYear() + 543);</script>
                                <strong>ระบบรับสมัครนักเรียนออนไลน์</strong>
                                Developed by <a href="#" class="footer-link fw-medium ">Dekpiano</a>
                            </div>
                            <div>


                                <a href="<?= base_url('auth/login') ?>"
                                    class="footer-link me-3 btn btn-primary btn-sm text-white">เจ้าหน้าที่เข้าสู่ระบบ</a>
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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="<?= base_url('public/sneat-assets/vendor/libs/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/libs/popper/popper.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
    <script src="<?= base_url('public/sneat-assets/vendor/js/menu.js') ?>"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?= base_url('public/sneat-assets/vendor/libs/apex-charts/apexcharts.js') ?>"></script>

    <!-- Main JS -->
    <script src="<?= base_url('public/sneat-assets/js/main.js') ?>"></script>

    <!-- SweetAlert2 -->
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
    <!-- PDPA Modal (Global) -->
    <div class="modal fade" id="pdpaModalGlobal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i
                            class='bx bx-shield-quarter me-2'></i>ข้อตกลงและเงื่อนไขการใช้ข้อมูลส่วนบุคคล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ข้อตกลงการใช้ข้อมูลส่วนบุคคลในการลงทะเบียนและสมัครเข้าศึกษาต่อในระบบรับสมัครออนไลน์ของโรงเรียนสวนกุหลาบวิทยาลัย
                            (จิรประวัติ) นครสวรรค์</strong></p>
                    <p>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ ("โรงเรียน")
                        ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของผู้สมัคร ("ท่าน")
                        โรงเรียนจึงได้จัดทำข้อตกลงและเงื่อนไขการใช้ข้อมูลส่วนบุคคลฉบับนี้ขึ้น
                        เพื่อแจ้งให้ท่านทราบถึงวิธีการที่โรงเรียนเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูลส่วนบุคคลของท่าน
                        และสิทธิของท่านในฐานะเจ้าของข้อมูลส่วนบุคคล</p>

                    <h6>1. ข้อมูลส่วนบุคคลที่เก็บรวบรวม</h6>
                    <p>โรงเรียนจะเก็บรวบรวมข้อมูลส่วนบุคคลของท่านที่จำเป็นต่อการรับสมัครและการพิจารณาคัดเลือกเข้าศึกษาต่อ
                        ซึ่งรวมถึงแต่ไม่จำกัดเพียง:</p>
                    <ul>
                        <li>ข้อมูลระบุตัวตน เช่น ชื่อ-นามสกุล, เลขประจำตัวประชาชน, วันเดือนปีเกิด</li>
                        <li>ข้อมูลการติดต่อ เช่น ที่อยู่, หมายเลขโทรศัพท์, อีเมล</li>
                        <li>ข้อมูลการศึกษา เช่น ประวัติการศึกษา, ผลการเรียน</li>
                        <li>ข้อมูลผู้ปกครอง</li>
                        <li>ข้อมูลอื่นๆ ที่ท่านให้ไว้ในใบสมัคร</li>
                    </ul>

                    <h6>2. วัตถุประสงค์ในการเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูล</h6>
                    <p>โรงเรียนจะใช้ข้อมูลส่วนบุคคลของท่านเพื่อวัตถุประสงค์ดังต่อไปนี้:</p>
                    <ul>
                        <li>เพื่อดำเนินการตามกระบวนการรับสมัคร และตรวจสอบคุณสมบัติของผู้สมัคร</li>
                        <li>เพื่อใช้ในการติดต่อสื่อสารกับท่านและผู้ปกครองเกี่ยวกับการสมัคร</li>
                        <li>เพื่อใช้ในการพิจารณาคัดเลือกนักเรียนเข้าศึกษาต่อ</li>
                        <li>เพื่อจัดทำทะเบียนนักเรียน และใช้ในกิจกรรมที่เกี่ยวข้องกับการศึกษาของโรงเรียน
                            (กรณีที่ท่านผ่านการคัดเลือก)</li>
                        <li>เพื่อปฏิบัติตามกฎหมายและข้อบังคับที่เกี่ยวข้อง</li>
                    </ul>

                    <h6>3. การเปิดเผยข้อมูลส่วนบุคคล</h6>
                    <p>โรงเรียนจะไม่เปิดเผยข้อมูลส่วนบุคคลของท่านแก่บุคคลภายนอกโดยไม่ได้รับความยินยอมจากท่าน
                        เว้นแต่ในกรณีที่มีกฎหมายกำหนดให้สามารถกระทำได้</p>

                    <h6>4. ระยะเวลาในการเก็บรักษาข้อมูล</h6>
                    <p>โรงเรียนจะเก็บรักษาข้อมูลส่วนบุคคลของท่านไว้เป็นระยะเวลาเท่าที่จำเป็นเพื่อบรรลุวัตถุประสงค์ที่ได้แจ้งไว้
                        และตามที่กฏหมายกำหนด</p>

                    <h6>5. สิทธิของเจ้าของข้อมูล</h6>
                    <p>ท่านมีสิทธิตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 ซึ่งรวมถึงสิทธิในการขอเข้าถึง
                        ขอแก้ไข ขอให้ลบ หรือจำกัดการใช้ข้อมูลส่วนบุคคลของท่าน</p>

                    <p class="mt-4">การที่ท่านกดปุ่ม "ยอมรับ" และดำเนินการสมัครต่อไป
                        ถือว่าท่านได้อ่านและเข้าใจข้อความข้างต้นโดยละเอียด และยินยอมให้โรงเรียนเก็บรวบรวม ใช้
                        และเปิดเผยข้อมูลส่วนบุคคลของท่านตามวัตถุประสงค์ที่ระบุไว้ในข้อตกลงนี้ทุกประการ</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ไม่ยอมรับ</button>
                    <button type="button" class="btn btn-primary"
                        id="pdpa-global-accept-btn">ยอมรับและดำเนินการต่อ</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle Sidebar PDPA Navigation
            const sidebarPdpaBtns = document.querySelectorAll('.pdpa-sidebar-btn');
            const pdpaModalGlobal = new bootstrap.Modal(document.getElementById('pdpaModalGlobal'));
            let selectedSidebarHref = '';

            sidebarPdpaBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    selectedSidebarHref = this.getAttribute('data-href');
                    if (selectedSidebarHref) {
                        pdpaModalGlobal.show();
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
                        confirmButtonColor: '#ffc107',
                        customClass: {
                            confirmButton: 'btn btn-warning text-white'
                        }
                    });
                });
            });

            // Handle Accept Button in Global Modal
            const acceptBtn = document.getElementById('pdpa-global-accept-btn');
            if (acceptBtn) {
                acceptBtn.addEventListener('click', function () {
                    if (selectedSidebarHref) {
                        window.location.href = selectedSidebarHref;
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('form').on('submit', function () {
                var $form = $(this);
                if ($form[0].checkValidity()) {
                    var $btn = $form.find('button[type="submit"]');
                    var $clickedBtn = $(document.activeElement);
                    if ($clickedBtn.length && $clickedBtn.is('button[type="submit"]') && $form.has($clickedBtn).length) {
                        $btn = $clickedBtn;
                    }

                    if ($btn.length > 0) {
                        $btn.addClass('disabled');
                        $btn.css('pointer-events', 'none');
                        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังบันทึก...');
                    }
                }
            });
        });
    </script>
</body>

</html>
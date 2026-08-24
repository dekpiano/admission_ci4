<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="<?= base_url('public/sneat-assets/') ?>" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?= isset($title) ? $title : 'Dashboard' ?> - Admin | SKJ Admission</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= base_url('public/sneat-assets/img/favicon/favicon.ico') ?>" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
    rel="stylesheet" />
  <style>
    body, button, input, select, textarea, h1, h2, h3, h4, h5, h6, .fw-bold, .btn, .nav, .form-control, .form-select, .badge, .swal2-popup, .modal, .dropdown-menu, .table, p, span:not(.bx):not([class*="bx-"]), a:not(.bx), div {
      font-family: 'K2D', sans-serif !important;
    }

    /* Force Boxicons & Icon Fonts to NEVER be overridden by text font */
    i.bx, i.bxs, i.bxl, .bx, .bxs, .bxl, [class^="bx-"], [class*=" bx-"], .bx-fw,
    .fa, .fas, .far, .fab, .bi, .material-icons, [class^="bx"], [class*=" bx"] {
      font-family: 'boxicons' !important;
      font-style: normal;
      font-weight: normal;
      font-variant: normal;
      text-transform: none;
      line-height: 1;
      display: inline-block;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    :root {
      /* Primary: Suankularb Coral Pink (#ff6b8b) */
      --bs-primary: #ff6b8b;
      --bs-primary-rgb: 255, 107, 139;
      --bs-link-color: #ff6b8b;
      --bs-link-hover-color: #e04869;
      
      /* Secondary: Suankularb Sky Blue (#56ccf2) */
      --bs-secondary: #56ccf2;
      --bs-secondary-rgb: 86, 204, 242;
      --bs-info: #56ccf2;
      --bs-info-rgb: 86, 204, 242;

      /* Suankularb Brand Palette */
      --skj-pink: #ff6b8b;
      --skj-pink-light: #ff8fa7;
      --skj-pink-dark: #e04869;
      --skj-pink-surface: #fff0f3;
      --skj-pink-text: #9e1136;

      --skj-blue: #56ccf2;
      --skj-blue-light: #7be0ff;
      --skj-blue-dark: #249ecd;
      --skj-blue-surface: #eef9fe;
      --skj-blue-text: #075985;

      --skj-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
      --skj-gradient-pink: linear-gradient(135deg, #ff6b8b 0%, #e04869 100%);
      --skj-gradient-blue: linear-gradient(135deg, #56ccf2 0%, #2f80ed 100%);

      /* High Contrast Text */
      --skj-text-dark: #0f172a;
      --skj-text-body: #1e293b;
      --skj-text-muted: #64748b;
      --skj-text-light: #ffffff;

      --skj-card-border: 1px solid #e2e8f0;
      --skj-card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
    }

    body {
      background-color: #f8fafc;
      color: var(--skj-text-body) !important;
    }

    h1, h2, h3, h4, h5, h6, .fw-bold {
      color: var(--skj-text-dark);
      font-weight: 700;
    }

    /* Buttons with high-contrast text */
    .btn-primary {
      background: var(--skj-pink) !important;
      border: none !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
      box-shadow: 0 4px 12px rgba(255, 107, 139, 0.35);
      transition: all 0.25s ease;
    }

    .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
      background: var(--skj-pink-dark) !important;
      color: #ffffff !important;
      box-shadow: 0 6px 18px rgba(255, 107, 139, 0.5);
      transform: translateY(-1px);
    }

    .btn-secondary, .btn-info {
      background: var(--skj-blue) !important;
      border: none !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
      box-shadow: 0 4px 12px rgba(86, 204, 242, 0.35);
      transition: all 0.25s ease;
    }

    .btn-secondary:hover, .btn-info:hover {
      background: var(--skj-blue-dark) !important;
      color: #ffffff !important;
      box-shadow: 0 6px 18px rgba(86, 204, 242, 0.5);
      transform: translateY(-1px);
    }

    .btn-outline-primary {
      border: 2px solid var(--skj-pink) !important;
      color: var(--skj-pink-dark) !important;
      background: transparent;
      font-weight: 700 !important;
      transition: all 0.25s ease;
    }

    .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
      background: var(--skj-pink) !important;
      color: #ffffff !important;
      border-color: var(--skj-pink) !important;
      box-shadow: 0 4px 14px rgba(255, 107, 139, 0.35);
    }

    .btn-outline-secondary, .btn-outline-info {
      border: 2px solid var(--skj-blue-dark) !important;
      color: var(--skj-blue-dark) !important;
      background: transparent;
      font-weight: 700 !important;
      transition: all 0.25s ease;
    }

    .btn-outline-secondary:hover, .btn-outline-info:hover {
      background: var(--skj-blue) !important;
      color: #ffffff !important;
      border-color: var(--skj-blue) !important;
      box-shadow: 0 4px 14px rgba(86, 204, 242, 0.35);
    }

    /* Colors & Text Utilities */
    .text-primary {
      color: #ff6b8b !important;
    }

    .text-secondary, .text-info {
      color: #249ecd !important;
    }

    .text-muted {
      color: #64748b !important;
    }

    .text-dark {
      color: #0f172a !important;
    }

    /* Solid Badges */
    .badge.bg-primary {
      background-color: #ff6b8b !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    .badge.bg-secondary, .badge.bg-info {
      background-color: #56ccf2 !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    /* Soft High-Contrast Badges */
    .bg-label-primary {
      background-color: #fff0f3 !important;
      color: #9e1136 !important;
      font-weight: 700 !important;
      border: 1px solid #ffd1dc !important;
    }

    .bg-label-secondary, .bg-label-info {
      background-color: #eef9fe !important;
      color: #075985 !important;
      font-weight: 700 !important;
      border: 1px solid #bae6fd !important;
    }

    .bg-label-success {
      background-color: #dcfce7 !important;
      color: #166534 !important;
      font-weight: 700 !important;
      border: 1px solid #bbf7d0 !important;
    }

    .bg-label-warning {
      background-color: #fef3c7 !important;
      color: #92400e !important;
      font-weight: 700 !important;
      border: 1px solid #fde68a !important;
    }

    .bg-label-danger {
      background-color: #fee2e2 !important;
      color: #991b1b !important;
      font-weight: 700 !important;
      border: 1px solid #fecaca !important;
    }

    /* Sidebar Navigation (Suankularb Pink Active) */
    .app-brand {
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 0.75rem;
    }

    .app-brand-text {
      background: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 800 !important;
    }

    .menu-vertical .menu-item.active > .menu-link:not(.menu-toggle) {
      background: linear-gradient(135deg, #ff6b8b 0%, #e04869 100%) !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      box-shadow: 0 4px 14px rgba(255, 107, 139, 0.4) !important;
      border-radius: 12px !important;
    }

    .menu-vertical .menu-item.active > .menu-link:not(.menu-toggle) i,
    .menu-vertical .menu-item.active > .menu-link:not(.menu-toggle) div {
      color: #ffffff !important;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    .menu-vertical .menu-item:not(.active) > .menu-link {
      color: #475569 !important;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .menu-vertical .menu-item:not(.active) > .menu-link:hover {
      background-color: #fff0f3 !important;
      color: #9e1136 !important;
      border-radius: 12px;
    }

    .menu-vertical .menu-item:not(.active) > .menu-link:hover i {
      color: #9e1136 !important;
    }

    .menu-header-text {
      color: #249ecd !important;
      font-size: 0.74rem !important;
      font-weight: 800 !important;
      letter-spacing: 0.8px;
    }

    /* Form Controls, Inputs & Labels */
    label, .form-label {
      color: #0f172a !important;
      font-weight: 700 !important;
      margin-bottom: 0.35rem;
    }

    .form-control, .form-select, textarea.form-control, input.form-control {
      background-color: #ffffff !important;
      color: #0f172a !important;
      font-weight: 500;
      border: 1.5px solid #cbd5e1 !important;
      border-radius: 10px;
    }

    .form-control:focus, .form-select:focus, textarea.form-control:focus, input.form-control:focus {
      background-color: #ffffff !important;
      border-color: var(--skj-pink, #e11d48) !important;
      box-shadow: 0 0 0 0.2rem rgba(225, 29, 72, 0.15) !important;
      color: #0f172a !important;
    }

    .form-check-input:checked,
    .form-switch .form-check-input:checked {
      background-color: var(--skj-pink, #e11d48) !important;
      border-color: var(--skj-pink, #e11d48) !important;
      box-shadow: 0 2px 6px rgba(225, 29, 72, 0.3) !important;
    }

    /* Table Typography & Headers */
    .table thead th {
      background-color: #f1f5f9 !important;
      color: #0f172a !important;
      font-weight: 700 !important;
      font-size: 0.84rem !important;
      border-bottom: 2px solid #cbd5e1 !important;
      letter-spacing: 0.3px;
    }

    .table tbody td {
      color: #1e293b !important;
      font-size: 0.875rem;
      vertical-align: middle;
    }

    /* Pagination */
    .page-item.active .page-link {
      background: #ff6b8b !important;
      border-color: #ff6b8b !important;
      color: #ffffff !important;
      font-weight: 700;
      box-shadow: 0 2px 8px rgba(255, 107, 139, 0.35);
    }

    .page-link {
      color: #334155;
      font-weight: 600;
    }

    .page-link:hover {
      color: #ff6b8b;
    }

    /* Cards */
    .card {
      border-radius: 18px !important;
      border: var(--skj-card-border) !important;
      box-shadow: var(--skj-card-shadow) !important;
      background-color: #ffffff;
    }

    .card-header {
      background-color: #ffffff;
      border-bottom: 1px solid #f1f5f9;
      color: #0f172a !important;
      font-weight: 700;
    }

    /* SweetAlert2 Highest Priority */
    .swal2-container {
      z-index: 100000 !important;
    }

    .swal2-styled.swal2-confirm {
      background: linear-gradient(135deg, #ff6b8b 0%, #e04869 100%) !important;
      color: #ffffff !important;
      border-radius: 50px !important;
      padding: 0.6rem 2rem !important;
      font-weight: 700 !important;
      border: none !important;
      box-shadow: 0 4px 14px rgba(255, 107, 139, 0.4) !important;
    }

    .swal2-styled.swal2-cancel {
      border-radius: 50px !important;
      padding: 0.6rem 1.8rem !important;
      font-weight: 700 !important;
      background-color: #64748b !important;
      color: #ffffff !important;
    }

    /* Mobile Enhancements */
    @media (max-width: 991.98px) {
      .app-brand-text {
        font-size: 1.2rem !important;
      }
      
      .layout-navbar .navbar-nav-right {
        gap: 0.5rem;
      }

      .navbar-dropdown .avatar {
        width: 32px !important;
        height: 32px !important;
      }

      .badge {
        font-size: 0.7rem;
        padding: 0.4em 0.6em;
      }
      
      .container-xxl {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
      }

      .card-body {
        padding: 1rem !important;
      }
      
      .table-responsive {
        border: 0;
        margin-bottom: 0;
      }
    }

    /* Force scrollbar visibility on mobile for tables if needed */
    .table-responsive::-webkit-scrollbar {
      height: 4px;
      width: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
      background: #cbd5e0;
      border-radius: 10px;
    }
  </style>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

  <!-- Core CSS -->
  <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/core.css') ?>"
    class="template-customizer-core-css" />
  <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/theme-default.css') ?>"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="<?= base_url('public/sneat-assets/css/demo.css') ?>" />

  <!-- Vendors CSS -->
  <link rel="stylesheet"
    href="<?= base_url('public/sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" />

  <!-- Helpers -->
  <script src="<?= base_url('public/sneat-assets/vendor/js/helpers.js') ?>"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="<?= base_url('public/sneat-assets/js/config.js') ?>"></script>
  <?= $this->renderSection('styles') ?>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="<?= site_url('skjadmin/dashboard') ?>" class="app-brand-link">
            <span class="app-brand-logo demo">
              <img src="<?= base_url('public/assets/images/LogoSKJ_4.png') ?>" alt="Logo"
                style="width: 35px; height: auto;">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">SKJ Admission</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li
            class="menu-item <?= (strpos(uri_string(), 'skjadmin/dashboard') !== false || uri_string() == 'skjadmin' || uri_string() == 'skjadmin/') ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/dashboard') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Dashboard</div>
            </a>
          </li>

          <!-- ========== ข้อมูลการรับสมัคร ========== -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">ข้อมูลการรับสมัคร</span>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/recruits') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/recruits') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-user"></i>
              <div data-i18n="Recruits">ข้อมูลผู้สมัคร</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/surrender') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/surrender') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-id-card"></i>
              <div data-i18n="Surrender">ข้อมูลการรายงานตัว</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/statistics') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/statistics') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
              <div data-i18n="Statistics">สถิติการรับสมัคร</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/reports') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/reports') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-printer"></i>
              <div data-i18n="Reports">รายงาน/พิมพ์</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/announcements') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/announcements') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-bell"></i>
              <div data-i18n="Announcements">ประกาศผลคัดเลือก</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/live-chat') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/live-chat') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-conversation text-primary"></i>
              <div data-i18n="LiveChat">ระบบสนทนาสด (Live Chat)</div>
            </a>
          </li>

          <!-- ========== จัดการข้อมูลหลัก ========== -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">จัดการข้อมูลหลัก</span>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/quotas') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/quotas') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Quotas">จัดการโควต้า</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/courses') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/courses') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-book"></i>
              <div data-i18n="Courses">จัดการหลักสูตร</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/schedules') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/schedules') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-calendar-event"></i>
              <div data-i18n="Schedules">จัดการกำหนดการ</div>
            </a>
          </li>

          <li
            class="menu-item <?= (strpos(uri_string(), 'skjadmin/service-area-schools') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/service-area-schools') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-map-pin"></i>
              <div data-i18n="ServiceArea">โรงเรียนในเขตพื้นที่</div>
            </a>
          </li>

          <li
            class="menu-item <?= (strpos(uri_string(), 'skjadmin/schools') !== false && strpos(uri_string(), 'service-area-schools') === false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/schools') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-building"></i>
              <div data-i18n="Schools">จัดการข้อมูลโรงเรียน</div>
            </a>
          </li>

          <!-- ========== ตั้งค่าระบบ ========== -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">ตั้งค่าระบบ</span>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/settings') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/settings') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-cog"></i>
              <div data-i18n="Settings">เปิด/ปิดรับสมัคร</div>
            </a>
          </li>

          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/cleanup') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/cleanup') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-trash"></i>
              <div data-i18n="Cleanup">จัดการไฟล์ขยะ</div>
            </a>
          </li>
          
          <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/local-sync') !== false) ? 'active' : '' ?>">
            <a href="<?= site_url('skjadmin/local-sync') ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-sync"></i>
              <div data-i18n="LocalSync">จัดการไฟล์ Local</div>
            </a>
          </li>

          <!-- ========== ผู้ดูแลระบบ (Superadmin Only) ========== -->
          <?php if (session()->get('status') === 'superadmin'): ?>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">ผู้ดูแลระบบ</span>
            </li>

            <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/telegram-notify') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/telegram-notify') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bxl-telegram" style="color: #24A1DE;"></i>
                <div data-i18n="TelegramNotify">Telegram Notify</div>
              </a>
            </li>

            <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/users') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/users') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Users">จัดการผู้ใช้งาน</div>
              </a>
            </li>
          <?php endif; ?>


        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <span class="badge bg-label-primary rounded-pill px-3 py-2">สำหรับเจ้าหน้าที่</span>
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <?php
              // Retrieve user's position from tb_admin_rloes
              $db = \Config\Database::connect();
              $builderPos = $db->table('tb_admin_rloes');
              $builderPos->select('admin_rloes_academic_position');
              $builderPos->where('admin_rloes_userid', session()->get('user_id'));
              $posRow = $builderPos->get()->getRow();
              $userPosition = $posRow ? $posRow->admin_rloes_academic_position : '';
              ?>
              
              <!-- Notification Bell -->
              <?= $this->include('Admin/layout/_navbar_notifications') ?>
              
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <?php
                    $topUserImg = session()->get('user_img')
                      ? "https://personnel.skj.ac.th/uploads/admin/Personnal/" . session()->get('user_img')
                      : base_url('public/sneat-assets/img/avatars/1.png');
                    ?>
                    <img src="<?= $topUserImg ?>" alt class="w-px-40 h-auto rounded-circle"
                      onerror="this.src='<?= base_url('public/sneat-assets/img/avatars/1.png') ?>'" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="<?= $topUserImg ?>" alt class="w-px-40 h-auto rounded-circle"
                              onerror="this.src='<?= base_url('public/sneat-assets/img/avatars/1.png') ?>'" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block"><?= session()->get('pers_firstname') ?>
                            <?= session()->get('pers_lastname') ?></span>
                          <small class="text-muted"><?= session()->get('status') ?></small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="<?= site_url('admin/logout') ?>">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">ออกจากระบบ</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
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
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                ©
                <script>
                  document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by
                <a href="https://facebook.com/dekpiano" target="_blank" class="footer-link fw-bolder">Dekpiano</a>
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

  <!-- Main JS -->
  <script src="<?= base_url('public/sneat-assets/js/main.js') ?>"></script>

  <!-- Page JS -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  <!-- DataTables Buttons -->
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?= $this->renderSection('scripts') ?>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <script>
    $(document).ready(function () {
      // Skip forms with 'ajax-form' class or 'data-ajax' attribute - they handle their own button states
      $('form:not(.ajax-form):not([data-ajax]):not(#adminReplyForm):not(#skjUserChatForm)').on('submit', function (e) {
        if (e.isDefaultPrevented()) return;
        var $form = $(this);
        // Check HTML5 validation
        if ($form[0].checkValidity()) {
          var $btn = $form.find('button[type="submit"]');
          // Check if there is an active element (clicked button) to be more precise
          var $clickedBtn = $(document.activeElement);
          if ($clickedBtn.length && $clickedBtn.is('button[type="submit"]') && $form.has($clickedBtn).length) {
            $btn = $clickedBtn;
          }

          if ($btn.length > 0 && !$btn.hasClass('no-disable')) {
            $btn.addClass('disabled');
            $btn.css('pointer-events', 'none');
            // Keep original width if possible or just replace text
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังบันทึก...');
          }
        }
      });
    });
  </script>
</body>

</html>
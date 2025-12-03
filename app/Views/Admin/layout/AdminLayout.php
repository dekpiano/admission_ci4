<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?= base_url('public/sneat-assets/') ?>"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title><?= isset($title) ? $title : 'Dashboard' ?> - Admin | SKJ Admission</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('public/sneat-assets/img/favicon/favicon.ico') ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=K2D:wght@200;300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: 'K2D', sans-serif !important;
      }
    </style>

    <!-- Icons. Uncomment required icon fonts -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/core.css') ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/theme-default.css') ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/css/demo.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />

    <!-- Helpers -->
    <script src="<?= base_url('public/sneat-assets/vendor/js/helpers.js') ?>"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url('public/sneat-assets/js/config.js') ?>"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                
              </span>
              <span class="app-brand-text demo menu-text fw-bolder ms-2">SKJ Admin</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item <?= (uri_string() == 'skjadmin/dashboard' || uri_string() == 'skjadmin') ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/dashboard') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Apps</span>
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

            <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/reports') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/reports') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Reports">รายงาน/พิมพ์</div>
              </a>
            </li>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">System</span>
            </li>
              <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/quotas') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/quotas') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div data-i18n="Quotas">การจัดการโควต้า</div>
              </a>
            </li>
            <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/courses') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/courses') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book"></i>
                <div data-i18n="Courses">การจัดการหลักสูตร</div>
              </a>
            </li>

            <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/schedules') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/schedules') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                <div data-i18n="Schedules">จัดการกำหนดการ</div>
              </a>
            </li>
            
            <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/service-area-schools') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/service-area-schools') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map-pin"></i>
                <div data-i18n="ServiceArea">โรงเรียนในเขตพื้นที่</div>
              </a>
            </li>

            <li class="menu-item <?= (strpos(uri_string(), 'skjadmin/settings') !== false) ? 'active' : '' ?>">
              <a href="<?= site_url('skjadmin/settings') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Settings">ตั้งค่าระบบ</div>
              </a>
            </li>

<?php if (session()->get('status') === 'superadmin'): ?>
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
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
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
<li class="nav-item navbar-dropdown dropdown-user dropdown">
  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
    <div class="avatar avatar-online">
      <img src="<?= base_url('public/sneat-assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
    </div>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="#">
        <div class="d-flex">
          <div class="flex-shrink-0 me-3">
            <div class="avatar avatar-online">
              <img src="<?= base_url('public/sneat-assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
            </div>
          </div>
          <div class="flex-grow-1">
            <span class="fw-semibold d-block"><?= session()->get('pers_firstname') ?> <?= session()->get('pers_lastname') ?></span>
            <small class="text-muted"><?= session()->get('status') ?></small>
          </div>
        </div>
      </a>
    </li>
    <li><div class="dropdown-divider"></div></li>
    <li>
      <a class="dropdown-item" href="<?= site_url('admin/logout') ?>">
        <i class="bx bx-power-off me-2"></i>
        <span class="align-middle">Log Out</span>
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
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->renderSection('scripts') ?>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>

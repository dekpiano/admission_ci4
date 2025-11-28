<!DOCTYPE html>
<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="<?= base_url('public/sneat-assets/') ?>"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
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
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/core.css') ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/theme-default.css') ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/css/demo.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />
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
        .form-control:focus, .form-select:focus {
            border-color: #ff9eb5;
            box-shadow: 0 0 0 0.25rem rgba(255, 158, 181, 0.25);
        }
        
        .page-item.active .page-link {
            background-color: #ff9eb5;
            border-color: #ff9eb5;
        }
        
        /* Sidebar Customization */
        #layout-menu {
            background-color: #ff9eb5;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.2' d='M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E"), linear-gradient(180deg, #ff9eb5 0%, #84d2f6 100%);
            background-repeat: no-repeat, no-repeat;
            background-position: bottom, center;
            background-size: 100% 30%, cover;
        }
        
        #layout-menu .menu-link {
            color: #fff !important;
        }
        
        #layout-menu .menu-item.active > .menu-link {
            background-color: #fff !important;
            color: #ff9eb5 !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        #layout-menu .menu-item.active > .menu-link i {
            color: #ff9eb5 !important;
        }
        
        #layout-menu .menu-icon {
            color: #fff !important;
        }
        
        #layout-menu .menu-header-text {
            color: rgba(255,255,255,0.8) !important;
        }
        
        }
        
        #layout-navbar .bx-menu {
            color: #fff !important;
        }
        
        #layout-navbar .nav-link {
             color: #fff !important;
        }
        
        #layout-navbar a {
            color: #fff !important;
        }
        
        /* Menu Active State Override from previous step to match new sidebar */
        .menu-item.active > .menu-link {
            color: #ff9eb5 !important;
            background-color: #ffffff !important;
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
                <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" width="40" class="img-fluid">
              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2">SKJ Admission</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
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

            <!-- Registration -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">สมัครเรียน</span>
            </li>
            <?php
            // Logic to determine open levels, similar to UserHome.php
            $open_levels = [];
            
            // Check System Status & Date Time
            $is_system_open = false;
            if (isset($systemStatus) && $systemStatus->onoff_regis == 'on') {
                $is_system_open = true;
                if (isset($systemStatus->onoff_datetime_regis_close)) {
                    if (time() > strtotime($systemStatus->onoff_datetime_regis_close)) {
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
                $menu_link_level = $is_junior_high ? '1' : '4'; // The actual pre-check level (1 or 4)
                $current_uri_level = (uri_string() == 'new-admission/pre-check/' . $menu_link_level && service('request')->getGet('level') == $level_num) || uri_string() == 'new-admission/register/' . $menu_link_level;
            ?>
            <li class="menu-item <?= $current_uri_level ? 'active' : '' ?>">
              <a href="<?= base_url('new-admission/pre-check/' . $menu_link_level . '?level=' . $level_num) ?>" class="menu-link">
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
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <span class="fw-bold text-primary d-none d-md-block">ระบบรับสมัครนักเรียนออนไลน์ โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</span>
                  <span class="fw-bold text-primary d-block d-md-none">ระบบรับสมัครนักเรียนใหม่ SKJ</span>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                <li class="nav-item lh-1 me-3">
                  <a
                    class="github-button"
                    href="#"
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                    >ปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?></a
                  >
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
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  © <script>document.write(new Date().getFullYear() + 543);</script> 
                  <strong>ระบบรับสมัครนักเรียนออนไลน์</strong> 
                  Developed by <a href="#" class="footer-link fw-medium ">Dekpiano</a>
                </div>
                <div>
                  
                  
                  <a href="<?= base_url('auth/login') ?>" class="footer-link me-3 btn btn-primary btn-sm text-white">เจ้าหน้าที่เข้าสู่ระบบ</a>
                </div>
              </div>
            </footer>
            <!-- / Footer -->
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

    <!-- Page JS -->
    <script src="<?= base_url('public/sneat-assets/js/dashboards-analytics.js') ?>"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?= $this->renderSection('scripts') ?>
  </body>
</html>

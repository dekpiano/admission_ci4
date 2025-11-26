<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title text-primary mb-1">สวัสดีตอนเช้า! 🎉</h5>
                <p class="mb-0">
                  ยินดีต้อนรับเข้าสู่ระบบจัดการข้อมูลผู้สมัครเรียน ประจำปีการศึกษา <span class="fw-bold"><?= $selected_year ?></span>
                </p>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-calendar me-1"></i> ปีการศึกษา <?= $selected_year ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php foreach ($years as $y): ?>
                        <li><a class="dropdown-item" href="<?= site_url('skjadmin/dashboard/' . $y->recruit_year) ?>"><?= $y->recruit_year ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <!-- Total Students -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="<?= base_url('public/sneat-assets/img/icons/unicons/chart-success.png') ?>" alt="chart success" class="rounded">
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">ผู้สมัครทั้งหมด</span>
                <h3 class="card-title mb-2"><?= $stats->StuALL ?></h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> คน</small>
            </div>
        </div>
    </div>

    <!-- Passed -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="<?= base_url('public/sneat-assets/img/icons/unicons/wallet-info.png') ?>" alt="Credit Card" class="rounded">
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">ผ่านการตรวจสอบ</span>
                <h3 class="card-title text-nowrap mb-1"><?= $stats->Pass ?></h3>
                <small class="text-success fw-semibold"><i class="bx bx-check"></i> คน</small>
            </div>
        </div>
    </div>

    <!-- Not Passed -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="<?= base_url('public/sneat-assets/img/icons/unicons/paypal.png') ?>" alt="Credit Card" class="rounded">
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">รอการตรวจสอบ/แก้ไข</span>
                <h3 class="card-title text-nowrap mb-1"><?= $stats->NoPass ?></h3>
                <small class="text-danger fw-semibold"><i class="bx bx-time"></i> คน</small>
            </div>
        </div>
    </div>

    <!-- M.1 / M.4 -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="<?= base_url('public/sneat-assets/img/icons/unicons/cc-primary.png') ?>" alt="Credit Card" class="rounded">
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">แยกตามระดับชั้น</span>
                <h6 class="mb-0">ม.1: <span class="text-primary"><?= $stats->NumAllM1 ?></span> คน</h6>
                <h6 class="mb-0">ม.4: <span class="text-info"><?= $stats->NumAllM4 ?></span> คน</h6>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

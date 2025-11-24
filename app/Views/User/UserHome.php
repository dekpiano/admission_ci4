<?= $this->extend('User/UserLayout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">ยินดีต้อนรับสู่ระบบรับสมัครนักเรียนออนไลน์ 🎉</h5>
                        <p class="mb-4">
                            โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ <br>
                            ปีการศึกษา <span class="fw-bold"><?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?></span>
                        </p>

                        <a href="#apply-section" class="btn btn-sm btn-outline-primary">สมัครเรียนทันที</a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img
                            src="<?= base_url('public/sneat-assets/img/illustrations/man-with-laptop-light.png') ?>"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img
                                    src="<?= base_url('public/sneat-assets/img/icons/unicons/chart-success.png') ?>"
                                    alt="chart success"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">สถานะระบบ</span>
                        <h3 class="card-title mb-2">
                            <?php if(isset($systemStatus) && $systemStatus->onoff_regis == 'on'): ?>
                                <span class="text-success">เปิด</span>
                            <?php else: ?>
                                <span class="text-danger">ปิด</span>
                            <?php endif; ?>
                        </h3>
                        <small class="text-success fw-semibold"><i class="bx bx-check"></i> รับสมัคร</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img
                                    src="<?= base_url('public/sneat-assets/img/icons/unicons/wallet-info.png') ?>"
                                    alt="Credit Card"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">ประกาศผล</span>
                        <h3 class="card-title text-nowrap mb-1">
                             <?php if(isset($systemStatus) && $systemStatus->onoff_report == 'on'): ?>
                                <span class="text-success">ประกาศแล้ว</span>
                            <?php else: ?>
                                <span class="text-warning">รอประกาศ</span>
                            <?php endif; ?>
                        </h3>
                        <small class="text-success fw-semibold"><i class="bx bx-time"></i> ติดตาม</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="apply-section">
    <!-- M.1 -->
    <div class="col-md-6 col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">ระดับชั้นมัธยมศึกษาปีที่ 1</h5>
                <h6 class="card-subtitle text-muted mb-3">รับสมัครนักเรียนจบ ป.6</h6>
                <p class="card-text">
                    หลักสูตรห้องเรียนพิเศษ และ ห้องเรียนปกติ ประจำปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?>
                </p>
                <?php if(isset($systemStatus) && $systemStatus->onoff_regis == 'on'): ?>
                    <a href="<?= base_url('new-admission/pre-check/1') ?>" class="btn btn-primary">สมัครเรียน ม.1</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>ปิดรับสมัคร</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- M.4 -->
    <div class="col-md-6 col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">ระดับชั้นมัธยมศึกษาปีที่ 4</h5>
                <h6 class="card-subtitle text-muted mb-3">รับสมัครนักเรียนจบ ม.3</h6>
                <p class="card-text">
                    หลักสูตรห้องเรียนพิเศษ และ ห้องเรียนปกติ ประจำปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?>
                </p>
                <?php if(isset($systemStatus) && $systemStatus->onoff_regis == 'on'): ?>
                    <a href="<?= base_url('new-admission/pre-check/4') ?>" class="btn btn-info text-white">สมัครเรียน ม.4</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>ปิดรับสมัคร</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">กำหนดการรับสมัคร</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>กิจกรรม</th>
                            <th>วันที่</th>
                            <th>หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>เปิดรับสมัคร</strong></td>
                            <td><?= isset($systemStatus->onoff_datetime_regis_open) ? date('d/m/Y H:i', strtotime($systemStatus->onoff_datetime_regis_open)) : '-' ?></td>
                            <td><span class="badge bg-label-success me-1">Online</span></td>
                        </tr>
                        <tr>
                            <td><i class="fab fa-react fa-lg text-info me-3"></i> <strong>ปิดรับสมัคร</strong></td>
                            <td><?= isset($systemStatus->onoff_datetime_regis_close) ? date('d/m/Y H:i', strtotime($systemStatus->onoff_datetime_regis_close)) : '-' ?></td>
                            <td><span class="badge bg-label-danger me-1">System Close</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

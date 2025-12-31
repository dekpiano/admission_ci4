<?= $this->extend('User/UserLayout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-lg">
            <div class="card-body text-center p-5">
                <!-- Icon -->
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10"
                        style="width: 100px; height: 100px;">
                        <i class="bx bx-time-five display-3 text-warning"></i>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="fw-bold text-warning mb-3">
                    <i class="bx bx-error-circle me-2"></i>ยังไม่เปิดให้รายงานตัว
                </h3>

                <!-- Description -->
                <p class="text-muted mb-4 fs-5">
                    ระบบรายงานตัวนักเรียนใหม่ยังไม่เปิดให้บริการในขณะนี้
                </p>

                <!-- Info Box -->
                <div class="alert alert-light border mb-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-info-circle text-primary me-2 fs-4"></i>
                        <div class="text-start">
                            <p class="mb-0">กรุณาติดตามประกาศกำหนดการรายงานตัวจากทางโรงเรียน</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="bg-light rounded p-3">
                            <h6 class="mb-2"><i class="bx bx-bell text-primary me-1"></i> ช่องทางติดตามข่าวสาร</h6>
                            <ul class="list-unstyled mb-0 text-start small text-muted">
                                <li><i class="bx bx-check me-1 text-success"></i> เว็บไซต์โรงเรียน: skj.ac.th</li>
                                <li><i class="bx bx-check me-1 text-success"></i> Facebook: โรงเรียนสวนกุหลาบวิทยาลัย
                                    (จิรประวัติ) นครสวรรค์</li>
                                <li><i class="bx bx-check me-1 text-success"></i> ติดต่อโทร: 056-009-667 ในเวลาทำการ
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <a href="<?= base_url('new-admission/status') ?>" class="btn btn-primary btn-lg">
                        <i class="bx bx-search-alt me-1"></i> ตรวจสอบสถานะการสมัคร
                    </a>
                    <a href="<?= base_url('new-admission') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-home me-1"></i> กลับหน้าหลัก
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center mt-3">
            <small class="text-muted">
                <i class="bx bx-calendar me-1"></i>
                ปีการศึกษา
                <?= isset($checkYear) ? $checkYear->openyear_year : (date('Y') + 543) ?>
            </small>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
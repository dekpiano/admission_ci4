<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการระบบ /</span> รายงานและการพิมพ์</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">พิมพ์ใบสมัครรวม (Batch Print)</h5>
                    <small class="text-muted">เลือกเงื่อนไขเพื่อพิมพ์ใบสมัครเป็นชุด PDF</small>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('skjadmin/reports/print_all') ?>" method="get" target="_blank">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="year" class="form-label">ปีการศึกษา</label>
                                <select class="form-select" id="year" name="year" required>
                                    <option value="">เลือกปีการศึกษา...</option>
                                    <?php foreach ($years as $y): ?>
                                        <option value="<?= $y->recruit_year ?>"><?= $y->recruit_year ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="level" class="form-label">ระดับชั้น</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="">เลือกระดับชั้น...</option>
                                    <option value="1">มัธยมศึกษาปีที่ 1</option>
                                    <option value="4">มัธยมศึกษาปีที่ 4</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="type" class="form-label">ประเภทห้องเรียน/หลักสูตร</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="all">ทั้งหมด</option>
                                    <?php foreach ($courses as $c): ?>
                                        <option value="<?= urlencode($c->course_fullname) ?>">
                                            <?= $c->course_fullname ?> (<?= $c->course_gradelevel ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-printer me-1"></i> พิมพ์รายงาน PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Future Reports Placeholder -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-label-success rounded me-3">
                            <i class="bx bx-spreadsheet fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Export Excel</h5>
                            <small class="text-muted">ส่งออกข้อมูลผู้สมัครทั้งหมดเป็นไฟล์ Excel</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-outline-secondary btn-sm" disabled>เร็วๆ นี้</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-label-info rounded me-3">
                            <i class="bx bx-chart fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">สถิติการรับสมัคร</h5>
                            <small class="text-muted">ดูรายงานสรุปยอดผู้สมัครแบบกราฟ</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-outline-secondary btn-sm" disabled>เร็วๆ นี้</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

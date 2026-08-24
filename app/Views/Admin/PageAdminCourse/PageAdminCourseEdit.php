<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-edit fs-3 text-warning"></i>
                แก้ไขข้อมูลหลักสูตร
            </h4>
            <p class="text-muted mb-0 small">แก้ไขรายละเอียดแผนการเรียน ชื่อย่อ สาขาวิชา และระดับชั้น</p>
        </div>
        <a href="<?= site_url('skjadmin/courses') ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bx bx-arrow-back me-1"></i> ย้อนกลับ
        </a>
    </div>

    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden" style="max-width: 800px;">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold text-dark">
                หลักสูตร: <span class="text-primary"><?= esc($course['course_fullname']) ?></span>
            </h5>
            <span class="badge bg-label-primary rounded-pill px-3 py-1 font-monospace">
                ID: <?= esc($course['course_id']) ?>
            </span>
        </div>
        <div class="card-body p-4">
            <form action="<?= site_url('skjadmin/courses/update/' . $course['course_id']) ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="course_fullname" class="form-label fw-bold text-dark">ชื่อเต็มหลักสูตร <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-3" id="course_fullname" name="course_fullname" value="<?= esc($course['course_fullname']) ?>" placeholder="กรุณาป้อนชื่อเต็มหลักสูตร" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="course_initials" class="form-label fw-bold text-dark">ชื่อย่อหลักสูตร <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="course_initials" name="course_initials" value="<?= esc($course['course_initials']) ?>" placeholder="กรุณาป้อนชื่อย่อหลักสูตร" required>
                    </div>
                    <div class="col-md-6">
                        <label for="course_branch" class="form-label fw-bold text-dark">สาขาวิชา / ความเป็นเลิศ</label>
                        <input type="text" class="form-control rounded-3" id="course_branch" name="course_branch" value="<?= esc($course['course_branch']) ?>" placeholder="กรุณาป้อนสาขา">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="course_gradelevel" class="form-label fw-bold text-dark">ระดับชั้น <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="course_gradelevel" name="course_gradelevel" required>
                            <option value="">-- เลือกระดับชั้น --</option>
                            <option value="ม.ต้น" <?= $course['course_gradelevel'] == 'ม.ต้น' ? 'selected' : '' ?>>ม.ต้น (มัธยมศึกษาตอนต้น)</option>
                            <option value="ม.ปลาย" <?= $course['course_gradelevel'] == 'ม.ปลาย' ? 'selected' : '' ?>>ม.ปลาย (มัธยมศึกษาตอนปลาย)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="course_age" class="form-label fw-bold text-dark">ช่วงอายุผู้สมัคร (ปี)</label>
                        <input type="text" class="form-control rounded-3" id="course_age" name="course_age" value="<?= esc($course['course_age'] ?? '') ?>" placeholder="เช่น 12,13,14,15 (คั่นด้วยจุลภาค)">
                        <small class="text-muted">ปล่อยว่างไว้หากไม่จำกัดอายุ</small>
                    </div>
                </div>

                <div class="border-top pt-4 mt-4 text-end">
                    <a href="<?= site_url('skjadmin/courses') ?>" class="btn btn-light rounded-pill px-4 me-2">ยกเลิก</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="bx bx-save me-1"></i> บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

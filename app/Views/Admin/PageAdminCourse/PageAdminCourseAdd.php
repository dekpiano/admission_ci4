<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-plus-circle fs-3 text-primary"></i>
                เพิ่มหลักสูตรใหม่
            </h4>
            <p class="text-muted mb-0 small">กำหนดรายละเอียดหลักสูตร ชื่อเต็ม ชื่อย่อ สาขาวิชา และช่วงอายุผู้สมัคร</p>
        </div>
        <a href="<?= site_url('skjadmin/courses') ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bx bx-arrow-back me-1"></i> ย้อนกลับ
        </a>
    </div>

    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden" style="max-width: 800px;">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bx bx-edit-alt text-primary me-2"></i>กรอกข้อมูลหลักสูตร
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="<?= site_url('skjadmin/courses/create') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="course_fullname" class="form-label fw-bold text-dark">ชื่อเต็มหลักสูตร <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-3" id="course_fullname" name="course_fullname" placeholder="เช่น แผนการเรียนวิทยาศาสตร์ - คณิตศาสตร์" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="course_initials" class="form-label fw-bold text-dark">ชื่อย่อหลักสูตร <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="course_initials" name="course_initials" placeholder="เช่น วิทย์-คณิต, ศิลป์-ภาษา" required>
                    </div>
                    <div class="col-md-6">
                        <label for="course_branch" class="form-label fw-bold text-dark">สาขาวิชา / ความเป็นเลิศ</label>
                        <input type="text" class="form-control rounded-3" id="course_branch" name="course_branch" placeholder="เช่น วิทยาศาสตร์, ภาษาต่างประเทศ, ดนตรี">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="course_gradelevel" class="form-label fw-bold text-dark">ระดับชั้น <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="course_gradelevel" name="course_gradelevel" required>
                            <option value="">-- เลือกระดับชั้น --</option>
                            <option value="ม.ต้น">ม.ต้น (มัธยมศึกษาตอนต้น)</option>
                            <option value="ม.ปลาย">ม.ปลาย (มัธยมศึกษาตอนปลาย)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="course_age" class="form-label fw-bold text-dark">ช่วงอายุผู้สมัคร (ปี)</label>
                        <input type="text" class="form-control rounded-3" id="course_age" name="course_age" placeholder="เช่น 12,13,14,15 (คั่นด้วยจุลภาค)">
                        <small class="text-muted">ปล่อยว่างไว้หากไม่จำกัดอายุ</small>
                    </div>
                </div>

                <div class="border-top pt-4 mt-4 text-end">
                    <a href="<?= site_url('skjadmin/courses') ?>" class="btn btn-light rounded-pill px-4 me-2">ยกเลิก</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="bx bx-save me-1"></i> บันทึกหลักสูตร
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

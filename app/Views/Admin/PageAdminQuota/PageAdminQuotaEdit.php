<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* High contrast inputs */
    .form-control, .form-select, textarea.form-control {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus, textarea.form-control:focus {
        border-color: var(--skj-pink, #e11d48) !important;
        box-shadow: 0 0 0 3.5px rgba(225, 29, 72, 0.15) !important;
        background-color: #ffffff !important;
    }

    /* Grade Level Selection Cards */
    .level-check-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .level-check-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .level-check-card.checked {
        border-color: var(--skj-pink, #e11d48);
        background: #fff0f3;
    }
    .level-check-card .form-check-input {
        cursor: pointer;
        width: 1.2rem;
        height: 1.2rem;
    }

    /* Course Selection Item Cards */
    .course-item-box {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .course-item-box:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }
    .course-item-box.selected {
        border-color: var(--skj-blue, #0284c7);
        background: #f0f9ff;
    }

    .card-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-edit fs-3 text-primary"></i>
                แก้ไขข้อมูลโควต้า
            </h4>
            <p class="text-muted mb-0 small">แก้ไขรายละเอียดโควต้า ระดับชั้น และหลักสูตรที่เปิดรับสมัคร</p>
        </div>
        <a href="<?= site_url('skjadmin/quotas') ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="bx bx-arrow-back me-1"></i> ย้อนกลับ
        </a>
    </div>

    <form action="<?= site_url('skjadmin/quotas/update/' . $quota['quota_id']) ?>" method="post" id="quotaForm">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- ==================== LEFT COLUMN: INFO & LEVELS ==================== -->
            <div class="col-lg-6">
                
                <!-- Card 1: Basic Info -->
                <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-header-icon" style="background: rgba(225, 29, 72, 0.12); color: #e11d48;">
                                <i class="bx bx-edit-alt"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">ข้อมูลโควต้า: <span class="text-primary font-monospace"><?= esc($quota['quota_key']) ?></span></h5>
                                <small class="text-muted">ID โควต้า: #<?= esc($quota['quota_id']) ?></small>
                            </div>
                        </div>
                        <span class="badge bg-label-<?= $quota['quota_status'] == 'on' ? 'primary' : 'secondary' ?> rounded-pill px-3 py-1 fw-bold">
                            <?= $quota['quota_status'] == 'on' ? 'กำลังเปิดรับสมัคร' : 'ปิดรับสมัคร' ?>
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <!-- Quota Key -->
                        <div class="mb-3">
                            <label for="quota_key" class="form-label fw-bold text-dark small mb-1">
                                Key โควต้า (System Key) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bx bx-key"></i></span>
                                <input type="text" class="form-control rounded-end-3 font-monospace border-start-0" id="quota_key" name="quota_key" value="<?= esc($quota['quota_key']) ?>" placeholder="เช่น quota_sport" required>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="bx bx-info-circle me-1"></i> ใช้เป็นรหัสอ้างอิงของโควต้าในระบบ
                            </small>
                        </div>

                        <!-- Quota Description -->
                        <div class="mb-3">
                            <label for="quota_explain" class="form-label fw-bold text-dark small mb-1">
                                คำอธิบาย / ชื่อโควต้า <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control rounded-3" id="quota_explain" name="quota_explain" rows="3" placeholder="ระบุชื่อโควต้า" required><?= esc($quota['quota_explain']) ?></textarea>
                        </div>

                        <!-- Status Switch -->
                        <div>
                            <label class="form-label fw-bold text-dark small mb-2 d-block">สถานะการเปิดรับสมัคร</label>
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="border: 1.5px solid #e2e8f0; background: #ffffff;">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">เปิดรับสมัครโควตานี้</h6>
                                    <small class="text-muted">อนุญาตให้ผู้สมัครมองเห็นและเลือกโควตานี้ได้</small>
                                </div>
                                <div class="form-check form-switch m-0">
                                    <input type="hidden" name="quota_status" value="off">
                                    <input class="form-check-input" type="checkbox" id="quota_status_switch" name="quota_status" value="on" <?= $quota['quota_status'] == 'on' ? 'checked' : '' ?> style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Grade Levels -->
                <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-header-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                                <i class="bx bx-layer"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">ระดับชั้นที่เปิดรับสมัคร</h5>
                                <small class="text-muted">คลิกเลือกระดับชั้นที่เปิดรับโควตานี้</small>
                            </div>
                        </div>
                        <span class="badge bg-label-info rounded-pill px-3 py-1 fw-bold">เลือกอย่างน้อย 1 ชั้น</span>
                    </div>
                    <div class="card-body p-4">
                        <?php 
                            $selectedLevels = !empty($quota['quota_level']) ? explode('|', $quota['quota_level']) : [];
                            $levels = [
                                '1' => ['label' => 'ม.1 (มัธยมศึกษาปีที่ 1)', 'badge' => 'ม.ต้น', 'color' => 'info'],
                                '2' => ['label' => 'ม.2', 'badge' => 'ม.ต้น', 'color' => 'info'],
                                '3' => ['label' => 'ม.3', 'badge' => 'ม.ต้น', 'color' => 'info'],
                                '4' => ['label' => 'ม.4 (มัธยมศึกษาปีที่ 4)', 'badge' => 'ม.ปลาย', 'color' => 'primary'],
                                '5' => ['label' => 'ม.5', 'badge' => 'ม.ปลาย', 'color' => 'primary'],
                                '6' => ['label' => 'ม.6', 'badge' => 'ม.ปลาย', 'color' => 'primary']
                            ];
                        ?>
                        <div class="row g-2">
                            <?php foreach ($levels as $val => $info): ?>
                                <?php $isChecked = in_array((string)$val, $selectedLevels); ?>
                                <div class="col-sm-6">
                                    <div class="level-check-card <?= $isChecked ? 'checked' : '' ?>" onclick="toggleLevelCard('level_<?= $val ?>', this)">
                                        <input class="form-check-input" type="checkbox" name="quota_level[]" value="<?= $val ?>" id="level_<?= $val ?>" <?= $isChecked ? 'checked' : '' ?> onclick="event.stopPropagation(); syncLevelCardStyle(this);">
                                        <label class="form-check-label fw-bold text-dark small m-0 flex-grow-1 cursor-pointer" for="level_<?= $val ?>">
                                            <?= $info['label'] ?>
                                        </label>
                                        <span class="badge bg-label-<?= $info['color'] ?> rounded-pill px-2"><?= $info['badge'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ==================== RIGHT COLUMN: COURSES ==================== -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-header-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                                <i class="bx bx-book-open"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">หลักสูตรที่เปิดรับในโควตานี้</h5>
                                <small class="text-muted">เลือกหลักสูตรที่อนุญาตให้ผู้สมัครเลือกได้</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <?php 
                            $selectedCourses = !empty($quota['quota_course']) ? array_map('trim', explode('|', $quota['quota_course'])) : [];
                            $m1Courses = [];
                            $m4Courses = [];
                            
                            if (!empty($courses)) {
                                foreach ($courses as $c) {
                                    if (strpos($c['course_gradelevel'], '1') !== false || strpos($c['course_gradelevel'], 'ต้น') !== false) {
                                        $m1Courses[] = $c;
                                    } else {
                                        $m4Courses[] = $c;
                                    }
                                }
                            }
                        ?>

                        <!-- M.1 Courses Section -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-dark small d-flex align-items-center gap-1">
                                    <i class="bx bx-book text-info fs-5"></i> หลักสูตรระดับชั้น ม.ต้น (ม.1)
                                    <span class="badge bg-label-info rounded-pill ms-1"><?= count($m1Courses) ?> หลักสูตร</span>
                                </span>
                                <div>
                                    <button type="button" class="btn btn-xs btn-outline-info rounded-pill px-2 me-1" onclick="toggleAllCourses('m1', true)">
                                        <i class="bx bx-check-double"></i> เลือกทั้งหมด
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" onclick="toggleAllCourses('m1', false)">
                                        <i class="bx bx-x"></i> ล้าง
                                    </button>
                                </div>
                            </div>
                            <div class="row g-2" id="m1CourseContainer">
                                <?php if (!empty($m1Courses)): ?>
                                    <?php foreach ($m1Courses as $c): ?>
                                        <?php $isSelected = in_array((string)$c['course_id'], $selectedCourses); ?>
                                        <div class="col-12">
                                            <div class="course-item-box <?= $isSelected ? 'selected' : '' ?>" onclick="toggleCourseBox('course_<?= $c['course_id'] ?>', this)">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input class="form-check-input course-m1 m-0" type="checkbox" name="quota_course[]" value="<?= $c['course_id'] ?>" id="course_<?= $c['course_id'] ?>" <?= $isSelected ? 'checked' : '' ?> onclick="event.stopPropagation(); syncCourseBoxStyle(this);">
                                                    <div class="flex-grow-1">
                                                        <span class="badge bg-label-info rounded-pill me-1"><?= esc($c['course_initials']) ?></span>
                                                        <span class="fw-semibold text-dark small"><?= esc($c['course_fullname']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12 text-muted small p-2">ไม่พบหลักสูตร ม.ต้น ในระบบ</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="my-3 text-muted">

                        <!-- M.4 Courses Section -->
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-dark small d-flex align-items-center gap-1">
                                    <i class="bx bx-graduation text-primary fs-5"></i> หลักสูตรระดับชั้น ม.ปลาย (ม.4)
                                    <span class="badge bg-label-primary rounded-pill ms-1"><?= count($m4Courses) ?> หลักสูตร</span>
                                </span>
                                <div>
                                    <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2 me-1" onclick="toggleAllCourses('m4', true)">
                                        <i class="bx bx-check-double"></i> เลือกทั้งหมด
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" onclick="toggleAllCourses('m4', false)">
                                        <i class="bx bx-x"></i> ล้าง
                                    </button>
                                </div>
                            </div>
                            <div class="row g-2" id="m4CourseContainer">
                                <?php if (!empty($m4Courses)): ?>
                                    <?php foreach ($m4Courses as $c): ?>
                                        <?php $isSelected = in_array((string)$c['course_id'], $selectedCourses); ?>
                                        <div class="col-12">
                                            <div class="course-item-box <?= $isSelected ? 'selected' : '' ?>" onclick="toggleCourseBox('course_<?= $c['course_id'] ?>', this)">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input class="form-check-input course-m4 m-0" type="checkbox" name="quota_course[]" value="<?= $c['course_id'] ?>" id="course_<?= $c['course_id'] ?>" <?= $isSelected ? 'checked' : '' ?> onclick="event.stopPropagation(); syncCourseBoxStyle(this);">
                                                    <div class="flex-grow-1">
                                                        <span class="badge bg-label-primary rounded-pill me-1"><?= esc($c['course_initials']) ?></span>
                                                        <span class="fw-semibold text-dark small"><?= esc($c['course_fullname']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12 text-muted small p-2">ไม่พบหลักสูตร ม.ปลาย ในระบบ</div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="card border-0 rounded-4 shadow-sm bg-white mt-3 p-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="text-muted small">
                    <i class="bx bx-check-circle text-success me-1"></i> บันทึกการเปลี่ยนแปลงข้อมูลโควต้าและหลักสูตร
                </span>
                <div class="d-flex gap-2">
                    <a href="<?= site_url('skjadmin/quotas') ?>" class="btn btn-outline-secondary rounded-pill px-4">ยกเลิก</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">
                        <i class="bx bx-save me-1"></i> บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Level Card Toggle
    function toggleLevelCard(checkboxId, cardElement) {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            syncLevelCardStyle(checkbox);
        }
    }

    function syncLevelCardStyle(checkbox) {
        const card = checkbox.closest('.level-check-card');
        if (card) {
            if (checkbox.checked) {
                card.classList.add('checked');
            } else {
                card.classList.remove('checked');
            }
        }
    }

    // Course Item Box Toggle
    function toggleCourseBox(checkboxId, boxElement) {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            syncCourseBoxStyle(checkbox);
        }
    }

    function syncCourseBoxStyle(checkbox) {
        const box = checkbox.closest('.course-item-box');
        if (box) {
            if (checkbox.checked) {
                box.classList.add('selected');
            } else {
                box.classList.remove('selected');
            }
        }
    }

    // Select All / Deselect All Courses for M.1 or M.4
    function toggleAllCourses(group, isSelectAll) {
        const checkboxes = document.querySelectorAll('.course-' + group);
        checkboxes.forEach(cb => {
            cb.checked = isSelectAll;
            syncCourseBoxStyle(cb);
        });
    }
</script>
<?= $this->endSection() ?>

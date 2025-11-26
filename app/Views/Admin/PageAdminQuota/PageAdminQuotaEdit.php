<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">การจัดการโควต้า /</span> แก้ไขโควต้า
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">ข้อมูลโควต้า: <span class="text-primary"><?= esc($quota['quota_key']) ?></span></h5>
                <div class="card-body">
                    <form action="<?= site_url('skjadmin/quotas/update/' . $quota['quota_id']) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <!-- Left Column: Basic Info -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quota_key" class="form-label">Key โควต้า</label>
                                    <input type="text" class="form-control" id="quota_key" name="quota_key" value="<?= esc($quota['quota_key']) ?>" placeholder="เช่น quota_m1_normal" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label d-block">ระดับชั้นที่รับสมัคร</label>
                                    <?php 
                                        $selectedLevels = !empty($quota['quota_level']) ? explode('|', $quota['quota_level']) : [];
                                        $levels = [
                                            '1' => 'ม.1',
                                            '2' => 'ม.2',
                                            '3' => 'ม.3',
                                            '4' => 'ม.4',
                                            '5' => 'ม.5',
                                            '6' => 'ม.6'
                                        ];
                                    ?>
                                    <div class="row g-2">
                                        <?php foreach ($levels as $val => $label): ?>
                                            <div class="col-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="quota_level[]" value="<?= $val ?>" id="level_<?= $val ?>" <?= in_array((string)$val, $selectedLevels) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="level_<?= $val ?>">
                                                        <?= $label ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="quota_explain" class="form-label">คำอธิบาย</label>
                                    <textarea class="form-control" id="quota_explain" name="quota_explain" rows="3" placeholder="รายละเอียดของโควต้า"><?= esc($quota['quota_explain']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label d-block">สถานะการใช้งาน</label>
                                    <div class="form-check form-switch mb-2">
                                        <input type="hidden" name="quota_status" value="off">
                                        <input class="form-check-input" type="checkbox" id="quota_status_switch" name="quota_status" value="on" <?= $quota['quota_status'] == 'on' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="quota_status_switch">เปิดใช้งาน</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Course Selection -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-3">หลักสูตรที่เกี่ยวข้อง</label>
                                <?php 
                                    // Explode and trim to ensure clean values
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

                                <!-- M.1 Courses -->
                                <div class="card shadow-none bg-transparent border border-primary mb-3">
                                    <div class="card-header p-2 bg-label-primary text-primary">
                                        <i class="bx bx-user me-1"></i> ระดับชั้น ม.ต้น
                                    </div>
                                    <div class="card-body p-3">
                                        <?php if(!empty($m1Courses)): ?>
                                            <?php foreach($m1Courses as $c): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="quota_course[]" value="<?= $c['course_id'] ?>" id="course_<?= $c['course_id'] ?>" <?= in_array((string)$c['course_id'], $selectedCourses) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="course_<?= $c['course_id'] ?>">
                                                        <?= esc($c['course_fullname']) ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <small class="text-muted">ไม่มีข้อมูลหลักสูตร ม.ต้น</small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- M.4 Courses -->
                                <div class="card shadow-none bg-transparent border border-warning mb-3">
                                    <div class="card-header p-2 bg-label-warning text-warning">
                                        <i class="bx bx-user-plus me-1"></i> ระดับชั้น ม.ปลาย
                                    </div>
                                    <div class="card-body p-3">
                                        <?php if(!empty($m4Courses)): ?>
                                            <?php foreach($m4Courses as $c): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="quota_course[]" value="<?= $c['course_id'] ?>" id="course_<?= $c['course_id'] ?>" <?= in_array((string)$c['course_id'], $selectedCourses) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="course_<?= $c['course_id'] ?>">
                                                        <?= esc($c['course_fullname']) ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <small class="text-muted">ไม่มีข้อมูลหลักสูตร ม.ปลาย</small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="<?= site_url('skjadmin/quotas') ?>" class="btn btn-outline-secondary me-2">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

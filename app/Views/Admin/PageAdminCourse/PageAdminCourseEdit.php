<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<div class="card">
    <h5 class="card-header">แก้ไขหลักสูตร: <?= esc($course['course_fullname']) ?></h5>
    <div class="card-body">
        <form action="<?= site_url('skjadmin/courses/update/' . $course['course_id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="course_fullname" name="course_fullname" value="<?= esc($course['course_fullname']) ?>" placeholder="กรุณาป้อนชื่อเต็มหลักสูตร">
                <label for="course_fullname">ชื่อเต็มหลักสูตร</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="course_initials" name="course_initials" value="<?= esc($course['course_initials']) ?>" placeholder="กรุณาป้อนชื่อย่อหลักสูตร">
                <label for="course_initials">ชื่อย่อหลักสูตร</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="course_branch" name="course_branch" value="<?= esc($course['course_branch']) ?>" placeholder="กรุณาป้อนสาขา">
                <label for="course_branch">สาขา</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="course_gradelevel" name="course_gradelevel">
                    <option value="">เลือกระดับชั้น</option>
                    <option value="ม.ต้น" <?= $course['course_gradelevel'] == 'ม.ต้น' ? 'selected' : '' ?>>ม.ต้น</option>
                    <option value="ม.ปลาย" <?= $course['course_gradelevel'] == 'ม.ปลาย' ? 'selected' : '' ?>>ม.ปลาย</option>
                </select>
                <label for="course_gradelevel">ระดับชั้น</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="course_age" name="course_age" value="<?= esc($course['course_age'] ?? '') ?>" placeholder="ระบุอายุเป็นตัวเลข คั่นด้วยเครื่องหมายจุลภาค (,) เช่น 13,14,15,16">
                <label for="course_age">ช่วงอายุ (ระบุตัวเลข เช่น 13,14,15)</label>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                <a href="<?= site_url('skjadmin/courses') ?>" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

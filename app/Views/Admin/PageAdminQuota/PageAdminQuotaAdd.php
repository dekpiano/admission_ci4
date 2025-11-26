<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<div class="card">
    <h5 class="card-header">เพิ่มโควต้าใหม่</h5>
    <div class="card-body">
        <form action="<?= site_url('skjadmin/quotas/create') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="quota_key" name="quota_key" placeholder="กรุณาป้อน Key โควต้า">
                <label for="quota_key">Key โควต้า</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="quota_level" name="quota_level" placeholder="กรุณาป้อนระดับ">
                <label for="quota_level">ระดับ (เช่น 1, 4)</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" id="quota_explain" name="quota_explain" placeholder="กรุณาป้อนคำอธิบาย" style="height: 100px"></textarea>
                <label for="quota_explain">คำอธิบาย</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="quota_status" name="quota_status">
                    <option value="on">เปิดใช้งาน</option>
                    <option value="off">ปิดใช้งาน</option>
                </select>
                <label for="quota_status">สถานะ</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="quota_course" name="quota_course" placeholder="กรุณาป้อนหลักสูตรที่เกี่ยวข้อง (เช่น 1|3|5)">
                <label for="quota_course">หลักสูตรที่เกี่ยวข้อง (คั่นด้วย |)</label>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="<?= site_url('skjadmin/quotas') ?>" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการระบบ /</span> ตั้งค่าระบบ</h4>

    <div class="row">
        <!-- System Control -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">สถานะระบบ (System Status)</h5>
                    <small class="text-muted">ควบคุมการเปิด-ปิดระบบต่างๆ</small>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h6 class="mb-0">ระบบรับสมัคร (Registration)</h6>
                            <small class="text-muted">เปิด/ปิด ให้ผู้เรียนลงทะเบียนสมัครเรียน</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchRegis" 
                                <?= ($settings->onoff_regis == 'on') ? 'checked' : '' ?> 
                                onchange="updateStatus('onoff_regis', this.checked)">
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h6 class="mb-0">ระบบประกาศผล (Report/Result)</h6>
                            <small class="text-muted">เปิด/ปิด การประกาศผลสอบ/สถานะ</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchReport" 
                                <?= ($settings->onoff_report == 'on') ? 'checked' : '' ?>
                                onchange="updateStatus('onoff_report', this.checked)">
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-0">ระบบหลัก (Main System)</h6>
                            <small class="text-muted">ปิดปรับปรุงระบบทั้งหมด (Maintenance Mode)</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchSystem" 
                                <?= ($settings->onoff_system == 'on') ? 'checked' : '' ?>
                                onchange="updateStatus('onoff_system', this.checked)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Year -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">ปีการศึกษา (Academic Year)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="selectYear" class="form-label">ปีการศึกษาปัจจุบันที่เปิดรับ</label>
                        <select class="form-select" id="selectYear" onchange="updateYear(this.value)">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y->recruit_year ?>" <?= ($yearConfig->openyear_year == $y->recruit_year) ? 'selected' : '' ?>>
                                    <?= $y->recruit_year ?>
                                </option>
                            <?php endforeach; ?>
                            <!-- Add next year option automatically if not exists -->
                            <?php $nextYear = date('Y') + 543 + 1; ?>
                            <?php if (!in_array($nextYear, array_column($years, 'recruit_year'))): ?>
                                <option value="<?= $nextYear ?>"><?= $nextYear ?></option>
                            <?php endif; ?>
                        </select>
                        <div class="form-text">การเปลี่ยนปีการศึกษาจะส่งผลต่อการแสดงผลข้อมูลในหน้าแรกและการออกเลขที่ใบสมัคร</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Message -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">ข้อความแจ้งเตือน (System Message)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="systemComment" class="form-label">ข้อความเมื่อปิดระบบรับสมัคร</label>
                        <textarea class="form-control" id="systemComment" rows="3"><?= $settings->onoff_comment ?></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="updateComment()">บันทึกข้อความ</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateStatus(field, mode) {
        fetch('<?= base_url('skjadmin/settings/update_status') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `field=${field}&mode=${mode}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: 'อัปเดตสถานะเรียบร้อยแล้ว'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    }

    function updateYear(year) {
        Swal.fire({
            title: 'ยืนยันการเปลี่ยนปีการศึกษา?',
            text: "การเปลี่ยนปีการศึกษาจะส่งผลต่อระบบทันที",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, เปลี่ยนเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url('skjadmin/settings/update_year') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `year=${year}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('สำเร็จ!', data.msg, 'success');
                    }
                });
            } else {
                // Revert selection if cancelled (optional, requires storing previous value)
                location.reload(); // Simple way to revert
            }
        });
    }

    function updateComment() {
        const comment = document.getElementById('systemComment').value;
        fetch('<?= base_url('skjadmin/settings/update_comment') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `comment=${comment}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('สำเร็จ!', data.msg, 'success');
            }
        });
    }
</script>
<?= $this->endSection() ?>

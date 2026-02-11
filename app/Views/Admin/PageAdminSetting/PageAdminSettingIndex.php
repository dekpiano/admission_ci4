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
                    
                    <!-- Date Settings -->
                    <div class="bg-label-secondary p-3 rounded mb-4">
                        <small class="fw-bold d-block mb-2"><i class='bx bx-time-five'></i> กำหนดช่วงเวลาเปิดรับสมัครอัตโนมัติ</small>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="dateOpen" class="form-label text-muted small mb-1">วันเปิดรับสมัคร</label>
                                <input type="datetime-local" class="form-control form-control-sm" id="dateOpen" 
                                    value="<?= isset($settings->onoff_datetime_regis_open) ? date('Y-m-d\TH:i', strtotime($settings->onoff_datetime_regis_open)) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="dateClose" class="form-label text-muted small mb-1">วันปิดรับสมัคร</label>
                                <input type="datetime-local" class="form-control form-control-sm" id="dateClose" 
                                    value="<?= isset($settings->onoff_datetime_regis_close) ? date('Y-m-d\TH:i', strtotime($settings->onoff_datetime_regis_close)) : '' ?>">
                            </div>
                            <div class="col-12 text-end mt-2">
                                <button type="button" class="btn btn-sm btn-primary" onclick="updateDates()">
                                    <i class='bx bx-save me-1'></i> บันทึกเวลา
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h6 class="mb-0">ระบบรายงานตัว (Confirmation)</h6>
                            <small class="text-muted">เปิด/ปิด การรายงานตัวนักเรียนใหม่</small>
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
                    <div class="mt-3 bg-label-primary p-3 rounded" id="announceTextContainer" style="<?= ($settings->onoff_system == 'on') ? '' : 'display:none;' ?>">
                        <label for="selectAnnounceText" class="form-label fw-bold"><i class='bx bx-edit'></i> หัวข้อที่ต้องการประกาศ (หน้าแรก)</label>
                        <div class="input-group">
                            <select class="form-select" id="selectAnnounceText" onchange="checkCustomAnnounce(this.value)">
                                <?php 
                                $options = [
                                    "ประกาศรายชื่อนักเรียนมีสิทธิ์สอบ",
                                    "ประกาศผลการคัดเลือก"
                                ];
                                $currentText = $settings->onoff_system_text ?? 'ประกาศผลการคัดเลือก';
                                $isCustom = !in_array($currentText, $options);
                                ?>
                                <?php foreach($options as $opt): ?>
                                    <option value="<?= $opt ?>" <?= ($currentText == $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                                <option value="custom" <?= $isCustom ? 'selected' : '' ?>>กำหนดเอง...</option>
                            </select>
                            <input type="text" class="form-control" id="customAnnounceText" 
                                placeholder="ระบุหัวข้อเอง..." 
                                value="<?= $isCustom ? $currentText : '' ?>"
                                style="<?= $isCustom ? '' : 'display:none;' ?>">
                            <button class="btn btn-primary" type="button" onclick="updateSystemText()">บันทึก</button>
                        </div>
                        <small class="text-muted mt-1 d-block">ข้อความนี้จะไปแสดงในกล่องสีม่วงที่หน้าแรกของระะบบ</small>
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
                            <?php 
                            // Collect all available years
                            $available_years = [];
                            if(!empty($years)) {
                                foreach($years as $y) {
                                    $available_years[] = $y->recruit_year;
                                }
                            }
                            
                            // Add current config year
                            if(isset($yearConfig->openyear_year)) {
                                $available_years[] = $yearConfig->openyear_year;
                            }
                            
                            // Add current Thai year and next year
                            $currentThaiYear = date('Y') + 543;
                            $available_years[] = $currentThaiYear;
                            $available_years[] = $currentThaiYear + 1;
                            
                            // Unique and Sort Descending
                            $available_years = array_unique($available_years);
                            rsort($available_years);
                            
                            foreach ($available_years as $y_val): 
                            ?>
                                <option value="<?= $y_val ?>" <?= (isset($yearConfig->openyear_year) && $yearConfig->openyear_year == $y_val) ? 'selected' : '' ?>>
                                    <?= $y_val ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">การเปลี่ยนปีการศึกษาจะส่งผลต่อการแสดงผลข้อมูลในหน้าแรกและการออกเลขที่ใบสมัคร</div>
                    </div>

                    <?php if (isset($yearConfig->openyear_year) && $yearConfig->openyear_year >= 2569): ?>
                    <div class="mb-3">
                        <label for="selectRound" class="form-label">รอบที่เปิดรับสมัคร</label>
                        <select class="form-select" id="selectRound" onchange="updateRound(this.value)">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <option value="<?= $i ?>" <?= (isset($settings->onoff_round) && $settings->onoff_round == $i) ? 'selected' : '' ?>>
                                    รอบที่ <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <div class="form-text">ระบุรอบการรับสมัครที่ต้องการให้บันทึกในข้อมูลผู้สมัคร</div>
                    </div>
                    <?php endif; ?>
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
    function updateDates() {
        const dateOpen = document.getElementById('dateOpen').value;
        const dateClose = document.getElementById('dateClose').value;

        fetch('<?= base_url('skjadmin/settings/update_dates') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `dateOpen=${dateOpen}&dateClose=${dateClose}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ',
                    text: data.msg,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', 'เกิดข้อผิดพลาด', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    }

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
                        Swal.fire('สำเร็จ!', data.msg, 'success').then(() => {
                            location.reload();
                        });
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

    function updateRound(round) {
        fetch('<?= base_url('skjadmin/settings/update_round') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `round=${round}`
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
                    title: 'เปลี่ยนรอบการรับสมัครเรียบร้อยแล้ว'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    }

    function checkCustomAnnounce(val) {
        const customInput = document.getElementById('customAnnounceText');
        if (val === 'custom') {
            customInput.style.display = 'block';
            customInput.focus();
        } else {
            customInput.style.display = 'none';
        }
    }

    function updateSystemText() {
        const select = document.getElementById('selectAnnounceText');
        let text = select.value;
        if (text === 'custom') {
            text = document.getElementById('customAnnounceText').value;
        }

        if (!text) {
            Swal.fire('คำเตือน', 'กรุณาระบุข้อความประกาศ', 'warning');
            return;
        }

        fetch('<?= base_url('skjadmin/settings/update_system_text') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `text=${text}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: data.msg,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    // Update updateStatus to show/hide announce text container
    const originalUpdateStatus = updateStatus;
    updateStatus = function(field, mode) {
        originalUpdateStatus(field, mode);
        if (field === 'onoff_system') {
            document.getElementById('announceTextContainer').style.display = mode ? 'block' : 'none';
        }
    }
</script>
<?= $this->endSection() ?>

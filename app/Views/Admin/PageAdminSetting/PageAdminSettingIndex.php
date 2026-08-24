<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<!-- Flatpickr with Thai Buddhist Era -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<style>
    /* Clean, crisp inputs with high contrast */
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
    .form-control::placeholder {
        color: #94a3b8 !important;
        font-weight: 400;
    }

    /* Card header accents */
    .card-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .form-check-input.setting-switch {
        width: 3.2rem;
        height: 1.6rem;
        cursor: pointer;
        border: 1.5px solid #cbd5e1;
    }
    .form-check-input.setting-switch:checked {
        background-color: var(--skj-pink, #e11d48);
        border-color: var(--skj-pink, #e11d48);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-cog fs-3 text-primary"></i>
                ตั้งค่าระบบการรับสมัคร
            </h4>
            <p class="text-muted mb-0 small">จัดการการเปิด-ปิดระบบ ปีการศึกษา รอบการรับสมัคร และข้อความประกาศหน้าแรก</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- ==================== LEFT COLUMN: STATUS & TIMERS ==================== -->
        <div class="col-lg-6">
            
            <!-- Card 1: ระบบรับสมัคร (Registration) -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="card-header-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                            <i class="bx bx-user-plus"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ระบบรับสมัครออนไลน์</h5>
                            <small class="text-muted">เปิด/ปิด ให้ผู้เรียนลงทะเบียนสมัครเรียน</small>
                        </div>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input setting-switch" type="checkbox" id="switchRegis" 
                            <?= ($settings->onoff_regis == 'on') ? 'checked' : '' ?> 
                            onchange="updateStatus('onoff_regis', this.checked)">
                    </div>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark small mb-3 d-flex align-items-center gap-1">
                        <i class="bx bx-time-five text-primary fs-5"></i> กำหนดช่วงเวลาเปิด-ปิดรับสมัครอัตโนมัติ
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="dateOpen" class="form-label text-dark fw-bold small">วัน-เวลาเริ่มเปิดรับสมัคร</label>
                            <input type="text" class="form-control rounded-3 thai-datetimepicker" id="dateOpen" 
                                value="<?= isset($settings->onoff_datetime_regis_open) ? $settings->onoff_datetime_regis_open : '' ?>"
                                placeholder="เลือกวัน-เวลาเปิด">
                        </div>
                        <div class="col-md-6">
                            <label for="dateClose" class="form-label text-dark fw-bold small">วัน-เวลาปิดรับสมัคร</label>
                            <input type="text" class="form-control rounded-3 thai-datetimepicker" id="dateClose" 
                                value="<?= isset($settings->onoff_datetime_regis_close) ? $settings->onoff_datetime_regis_close : '' ?>"
                                placeholder="เลือกวัน-เวลาปิด">
                        </div>
                    </div>
                    <div class="text-end mt-3 pt-2 border-top">
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="updateDates()">
                            <i class='bx bx-save me-1'></i> บันทึกช่วงเวลารับสมัคร
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2: ระบบรายงานตัว (Confirmation) -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="card-header-icon" style="background: rgba(225, 29, 72, 0.12); color: #e11d48;">
                            <i class="bx bx-check-shield"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ระบบยืนยันสิทธิ์และรายงานตัว</h5>
                            <small class="text-muted">เปิด/ปิด ให้นักเรียนที่ผ่านการคัดเลือกยืนยันสิทธิ์</small>
                        </div>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input setting-switch" type="checkbox" id="switchReport" 
                            <?= ($settings->onoff_report == 'on') ? 'checked' : '' ?> 
                            onchange="updateStatus('onoff_report', this.checked)">
                    </div>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-0">
                        <i class="bx bx-info-circle me-1 text-primary"></i>
                        เมื่อเปิดระบบ นักเรียนที่ผ่านการคัดเลือกจะสามารถเข้าสู่ระบบเพื่อพิมพ์ใบมอบตัวและยืนยันสิทธิ์เข้าศึกษาต่อได้
                    </p>
                </div>
            </div>

            <!-- Card 3: ปิดปรับปรุงระบบ & ประกาศหน้าแรก (Maintenance Mode) -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="card-header-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
                            <i class="bx bx-power-off"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ปิดปรับปรุงระบบทั้งหมด</h5>
                            <small class="text-muted">Maintenance Mode ชั่วคราว</small>
                        </div>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input setting-switch" type="checkbox" id="switchSystem" 
                            <?= ($settings->onoff_system == 'on') ? 'checked' : '' ?> 
                            onchange="updateStatus('onoff_system', this.checked)">
                    </div>
                </div>
                <div class="card-body p-4" id="announceTextContainer" style="<?= ($settings->onoff_system == 'on') ? '' : 'display:none;' ?>">
                    <label for="selectAnnounceText" class="form-label fw-bold text-dark small mb-2 d-flex align-items-center gap-1">
                        <i class='bx bx-edit text-primary'></i> หัวข้อที่ต้องการประกาศในหน้าแรก
                    </label>
                    <div class="input-group mb-2">
                        <select class="form-select rounded-start-3" id="selectAnnounceText" onchange="checkCustomAnnounce(this.value)">
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
                            <option value="custom" <?= $isCustom ? 'selected' : '' ?>>กำหนดข้อความเอง...</option>
                        </select>
                        <input type="text" class="form-control" id="customAnnounceText" 
                            placeholder="ระบุหัวข้อเอง..." 
                            value="<?= $isCustom ? $currentText : '' ?>"
                            style="<?= $isCustom ? '' : 'display:none;' ?>">
                        <button class="btn btn-primary rounded-end-3" type="button" onclick="updateSystemText()">
                            <i class="bx bx-save me-1"></i> บันทึกหัวข้อ
                        </button>
                    </div>
                    <small class="text-muted d-block">ข้อความนี้จะแสดงในกล่องแบนเนอร์หลักที่หน้าแรกของระบบ</small>
                </div>
            </div>

        </div>

        <!-- ==================== RIGHT COLUMN: ACADEMIC CONFIG & NOTICES ==================== -->
        <div class="col-lg-6">

            <!-- Card 4: ปีการศึกษาที่เปิดรับสมัคร (Academic Year) -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="card-header-icon" style="background: rgba(225, 29, 72, 0.12); color: #e11d48;">
                            <i class="bx bx-calendar"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ปีการศึกษาที่เปิดรับสมัคร</h5>
                            <small class="text-muted">กำหนดปีการศึกษาปัจจุบันของระบบ</small>
                        </div>
                    </div>
                    <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold">
                        ปี <?= esc($yearConfig->openyear_year ?? date('Y')+543) ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <label for="selectYear" class="form-label fw-bold text-dark small mb-2">
                        เลือกปีการศึกษาที่ต้องการเปิดใช้งาน <span class="text-danger">*</span>
                    </label>
                    <select class="form-select rounded-3 mb-2" id="selectYear" onchange="updateYear(this.value)">
                        <?php 
                        $available_years = [];
                        if(!empty($years)) {
                            foreach($years as $y) {
                                $available_years[] = $y->recruit_year;
                            }
                        }
                        if(isset($yearConfig->openyear_year)) {
                            $available_years[] = $yearConfig->openyear_year;
                        }
                        $currentThaiYear = date('Y') + 543;
                        $available_years[] = $currentThaiYear;
                        $available_years[] = $currentThaiYear + 1;
                        $available_years = array_unique($available_years);
                        rsort($available_years);
                        
                        foreach ($available_years as $y_val): 
                        ?>
                            <option value="<?= $y_val ?>" <?= (isset($yearConfig->openyear_year) && $yearConfig->openyear_year == $y_val) ? 'selected' : '' ?>>
                                ปีการศึกษา <?= $y_val ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted d-block">
                        <i class="bx bx-info-circle me-1"></i>
                        การเปลี่ยนปีการศึกษาจะปรับเปลี่ยนชุดข้อมูลผู้สมัครและเลขที่ใบสมัครอัตโนมัติ
                    </small>
                </div>
            </div>

            <!-- Card 5: รอบที่เปิดรับสมัคร (Recruitment Round) -->
            <?php if (isset($yearConfig->openyear_year) && $yearConfig->openyear_year >= 2569): ?>
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="card-header-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                            <i class="bx bx-layer"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">รอบการรับสมัคร</h5>
                            <small class="text-muted">กำหนดรอบการสมัครปัจจุบัน</small>
                        </div>
                    </div>
                    <span class="badge bg-label-warning rounded-pill px-3 py-1 fw-bold">
                        รอบที่ <?= esc($settings->onoff_round ?? 1) ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <label for="selectRound" class="form-label fw-bold text-dark small mb-2">
                        เลือกรอบการรับสมัครที่เปิดใช้งาน <span class="text-danger">*</span>
                    </label>
                    <select class="form-select rounded-3 mb-2" id="selectRound" onchange="updateRound(this.value)">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <option value="<?= $i ?>" <?= (isset($settings->onoff_round) && $settings->onoff_round == $i) ? 'selected' : '' ?>>
                                รอบที่ <?= $i ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <small class="text-muted d-block">
                        <i class="bx bx-info-circle me-1"></i>
                        ระบบจะบันทึกรอบนี้ลงในข้อมูลของผู้สมัครที่สมัครใหม่โดยอัตโนมัติ
                    </small>
                </div>
            </div>
            <?php endif; ?>

            <!-- Card 6: ข้อความแจ้งเตือนเมื่อระบบปิด (Closed System Message) -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="card-header-icon" style="background: rgba(100, 116, 139, 0.12); color: #475569;">
                            <i class="bx bx-message-alt-detail"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ข้อความเมื่อระบบปิดรับสมัคร</h5>
                            <small class="text-muted">ข้อความชี้แจงผู้สมัครเมื่อเข้าสู่ระบบนอกเวลา</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <label for="systemComment" class="form-label fw-bold text-dark small mb-2">ข้อความประกาศแจ้งเตือน</label>
                    <textarea class="form-control rounded-3 mb-3" id="systemComment" rows="4" placeholder="ระบุข้อความแจ้งเตือนผู้สมัครเมื่อระบบปิดรับสมัคร"><?= esc($settings->onoff_comment) ?></textarea>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="updateComment()">
                            <i class="bx bx-save me-1"></i> บันทึกข้อความปิดระบบ
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Flatpickr JS & Thai Buddhist Era -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
<script>
    function applyThaiBuddhistEra(fp) {
        if (!fp || !fp.calendarContainer) return;
        
        const updateYearElement = () => {
            const yearInput = fp.calendarContainer.querySelector('.cur-year');
            if (yearInput) {
                const adYear = fp.currentYear;
                const beYear = adYear + 543;
                yearInput.value = beYear;

                if (!yearInput.dataset.buddhistBound) {
                    yearInput.dataset.buddhistBound = "true";
                    
                    yearInput.addEventListener('change', function() {
                        let val = parseInt(this.value, 10);
                        if (!isNaN(val)) {
                            if (val > 2400) {
                                fp.changeYear(val - 543);
                            } else {
                                fp.changeYear(val);
                            }
                            this.value = fp.currentYear + 543;
                        }
                    });

                    yearInput.addEventListener('input', function() {
                        let val = parseInt(this.value, 10);
                        if (!isNaN(val) && val > 2400 && val < 2700) {
                            fp.currentYear = val - 543;
                            fp.redraw();
                            this.value = val;
                        }
                    });
                }
            }
        };

        updateYearElement();
        setTimeout(updateYearElement, 15);
    }

    $(document).ready(function() {
        flatpickr.localize(flatpickr.l10ns.th);

        $(".thai-datetimepicker").flatpickr({
            locale: "th",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i:S",
            altInput: true,
            altFormat: "j F Y H:i น.",
            formatDate: function(date, format, locale) {
                const thaiMonths = (locale && locale.months) ? locale.months.longhand : [
                    "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
                ];
                const d = date.getDate();
                const m = thaiMonths[date.getMonth()];
                const y = date.getFullYear() + 543;
                const h = String(date.getHours()).padStart(2, '0');
                const min = String(date.getMinutes()).padStart(2, '0');
                return `${d} ${m} ${y} เวลา ${h}:${min} น.`;
            },
            onReady: function(selectedDates, dateStr, instance) {
                applyThaiBuddhistEra(instance);
            },
            onMonthChange: function(selectedDates, dateStr, instance) {
                applyThaiBuddhistEra(instance);
            },
            onYearChange: function(selectedDates, dateStr, instance) {
                applyThaiBuddhistEra(instance);
            },
            onOpen: function(selectedDates, dateStr, instance) {
                applyThaiBuddhistEra(instance);
            },
            onValueUpdate: function(selectedDates, dateStr, instance) {
                applyThaiBuddhistEra(instance);
            }
        });
    });

    function updateDates() {
        const dateOpen = document.getElementById('dateOpen').value;
        const dateClose = document.getElementById('dateClose').value;

        fetch('<?= base_url('skjadmin/settings/update_dates') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `dateOpen=${encodeURIComponent(dateOpen)}&dateClose=${encodeURIComponent(dateClose)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเวลาสำเร็จ',
                    text: data.msg,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('ข้อผิดพลาด', data.msg || 'เกิดข้อผิดพลาดในการบันทึกเวลา', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message, 'error');
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
                    timer: 2500,
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

        if (field === 'onoff_system') {
            document.getElementById('announceTextContainer').style.display = mode ? 'block' : 'none';
        }
    }

    function updateYear(year) {
        Swal.fire({
            title: 'ยืนยันการเปลี่ยนปีการศึกษา?',
            text: "การเปลี่ยนปีการศึกษาจะมีผลต่อระบบทันที",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, เปลี่ยนเลย',
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
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: data.msg,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            } else {
                location.reload();
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
            body: `comment=${encodeURIComponent(comment)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: data.msg,
                    timer: 1500,
                    showConfirmButton: false
                });
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
                    timer: 2500,
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
            body: `text=${encodeURIComponent(text)}`
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
</script>
<?= $this->endSection() ?>

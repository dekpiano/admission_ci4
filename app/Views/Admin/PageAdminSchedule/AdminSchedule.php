<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<!-- Flatpickr with Thai Buddhist Era -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<style>
    .schedule-card-header {
        background: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
    }
    .date-badge {
        font-size: 0.82rem;
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-calendar-event fs-3 text-primary"></i>
                จัดการกำหนดการรับสมัคร
            </h4>
            <p class="text-muted mb-0 small">กำหนดวันและเวลาเปิด-ปิดรับสมัคร วันสอบ ประกาศผล และวันรายงานตัว</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="bx bx-plus-circle me-1"></i> เพิ่มกำหนดการใหม่
        </button>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold text-dark small">
                        <i class="bx bx-calendar me-1 text-primary"></i>ปีการศึกษา
                    </label>
                    <select class="form-select rounded-3" id="filterYear">
                        <option value="">ทุกปีการศึกษา</option>
                        <?php for($i = 2567; $i <= 2571; $i++): ?>
                            <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>>ปีการศึกษา <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold text-dark small">
                        <i class="bx bx-layer me-1 text-primary"></i>ระดับชั้น
                    </label>
                    <select class="form-select rounded-3" id="filterLevel">
                        <option value="">ทุกระดับชั้น</option>
                        <option value="ม.1">มัธยมศึกษาปีที่ 1 (ม.1)</option>
                        <option value="ม.4">มัธยมศึกษาปีที่ 4 (ม.4)</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <button class="btn btn-outline-secondary w-100 rounded-3" id="resetFilter">
                        <i class="bx bx-refresh me-1"></i> รีเซ็ตตัวกรอง
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Table Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-list-ul text-primary fs-4"></i>
                รายการกำหนดการทั้งหมด
            </h5>
            <span class="badge bg-label-primary rounded-pill px-3 py-1" id="totalSchedules">0 รายการ</span>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle" id="scheduleTable" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th style="width: 90px;" class="text-center">ปีการศึกษา</th>
                            <th style="width: 90px;" class="text-center">ระดับชั้น</th>
                            <th style="min-width: 140px;">รอบการรับสมัคร</th>
                            <th style="min-width: 200px;" class="text-center">ช่วงเวลารับสมัคร</th>
                            <th style="width: 110px;" class="text-center">วันสอบ</th>
                            <th style="width: 110px;" class="text-center">วันประกาศผล</th>
                            <th style="width: 110px;" class="text-center">วันรายงานตัว</th>
                            <th style="width: 90px;" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody">
                        <!-- Loaded via Ajax -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-5" style="display: none;">
                <div class="mb-3">
                    <i class="bx bx-calendar-x fs-1 text-muted opacity-50"></i>
                </div>
                <h6 class="fw-bold text-dark">ไม่พบข้อมูลกำหนดการ</h6>
                <p class="text-muted small mb-0">กรุณาเพิ่มกำหนดการใหม่หรือเปลี่ยนเงื่อนไขการกรอง</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white" style="background: var(--primary-gradient);">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bx bx-plus-circle fs-4"></i> เพิ่มกำหนดการใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addScheduleForm">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                ปีการศึกษา <span class="text-danger">*</span>
                            </label>
                            <select class="form-select rounded-3" name="schedule_year" required>
                                <?php for($i = 2567; $i <= 2571; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                ระดับชั้น <span class="text-danger">*</span>
                            </label>
                            <select class="form-select rounded-3" name="schedule_level" required>
                                <option value="">-- เลือกระดับชั้น --</option>
                                <option value="ม.1">มัธยมศึกษาปีที่ 1 (ม.1)</option>
                                <option value="ม.4">มัธยมศึกษาปีที่ 4 (ม.4)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                รอบการรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3" name="schedule_round" placeholder="เช่น รอบห้องเรียนพิเศษ, รอบทั่วไป" required>
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-1 d-flex align-items-center gap-1">
                                <i class="bx bx-time-five"></i> ช่วงเวลารับสมัคร
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                วันและเวลาเริ่มรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3 thai-datetimepicker" name="schedule_recruit_start" placeholder="เลือกวัน-เวลาเริ่มรับสมัคร" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                วันและเวลาปิดรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3 thai-datetimepicker" name="schedule_recruit_end" placeholder="เลือกวัน-เวลาปิดรับสมัคร" required>
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-1 d-flex align-items-center gap-1">
                                <i class="bx bx-calendar"></i> วันที่สำคัญอื่นๆ (ถ้ามี)
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">วันสอบ</label>
                            <input type="text" class="form-control rounded-3 thai-datepicker" name="schedule_exam" placeholder="เลือกวันสอบ">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">วันประกาศผล</label>
                            <input type="text" class="form-control rounded-3 thai-datepicker" name="schedule_announce" placeholder="เลือกวันประกาศผล">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">วันรายงานตัว</label>
                            <input type="text" class="form-control rounded-3 thai-datepicker" name="schedule_report" placeholder="เลือกวันรายงานตัว">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="bx bx-save me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2 text-dark">
                    <i class="bx bx-edit fs-4"></i> แก้ไขกำหนดการ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduleForm">
                <input type="hidden" name="schedule_id" id="editScheduleId">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                ปีการศึกษา <span class="text-danger">*</span>
                            </label>
                            <select class="form-select rounded-3" name="schedule_year" id="editScheduleYear" required>
                                <?php for($i = 2567; $i <= 2571; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                ระดับชั้น <span class="text-danger">*</span>
                            </label>
                            <select class="form-select rounded-3" name="schedule_level" id="editScheduleLevel" required>
                                <option value="ม.1">มัธยมศึกษาปีที่ 1 (ม.1)</option>
                                <option value="ม.4">มัธยมศึกษาปีที่ 4 (ม.4)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                รอบการรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3" name="schedule_round" id="editScheduleRound" required>
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-1 d-flex align-items-center gap-1">
                                <i class="bx bx-time-five"></i> ช่วงเวลารับสมัคร
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                วันและเวลาเริ่มรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3 thai-datetimepicker" name="schedule_recruit_start" id="editScheduleRecruitStart" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                วันและเวลาปิดรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3 thai-datetimepicker" name="schedule_recruit_end" id="editScheduleRecruitEnd" required>
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-1 d-flex align-items-center gap-1">
                                <i class="bx bx-calendar"></i> วันที่สำคัญอื่นๆ
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">วันสอบ</label>
                            <input type="text" class="form-control rounded-3 thai-datepicker" name="schedule_exam" id="editScheduleExam">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">วันประกาศผล</label>
                            <input type="text" class="form-control rounded-3 thai-datepicker" name="schedule_announce" id="editScheduleAnnounce">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">วันรายงานตัว</label>
                            <input type="text" class="form-control rounded-3 thai-datepicker" name="schedule_report" id="editScheduleReport">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-5 shadow-sm text-dark fw-bold">
                        <i class="bx bx-save me-1"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Flatpickr JS & Thai Buddhist Era -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
<script>
    const baseUrl = '<?= base_url() ?>';
    let allSchedules = [];

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

    // Thai Buddhist Era formatter for Flatpickr
    function initThaiDatepickers() {
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

        $(".thai-datepicker").flatpickr({
            locale: "th",
            enableTime: false,
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            formatDate: function(date, format, locale) {
                const thaiMonths = (locale && locale.months) ? locale.months.longhand : [
                    "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
                ];
                const d = date.getDate();
                const m = thaiMonths[date.getMonth()];
                const y = date.getFullYear() + 543;
                return `${d} ${m} ${y}`;
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
    }

    $(document).ready(function() {
        loadSchedules();
        initThaiDatepickers();

        $('#filterYear, #filterLevel').on('change', function() {
            filterSchedules();
        });

        $('#resetFilter').on('click', function() {
            $('#filterYear, #filterLevel').val('');
            filterSchedules();
        });
    });

    function loadSchedules() {
        $.ajax({
            url: baseUrl + '/skjadmin/schedules/get',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    allSchedules = response.data;
                    filterSchedules();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถโหลดข้อมูลกำหนดการได้'
                });
            }
        });
    }

    function filterSchedules() {
        const yearFilter = $('#filterYear').val();
        const levelFilter = $('#filterLevel').val();

        const filtered = allSchedules.filter(schedule => {
            const matchYear = !yearFilter || schedule.schedule_year == yearFilter;
            const matchLevel = !levelFilter || schedule.schedule_level === levelFilter;
            return matchYear && matchLevel;
        });

        renderSchedules(filtered);
    }

    function renderSchedules(schedules) {
        const tbody = $('#scheduleTableBody');
        tbody.empty();

        $('#totalSchedules').text(schedules.length + ' รายการ');

        if (schedules.length === 0) {
            $('#emptyState').show();
            return;
        }

        $('#emptyState').hide();

        schedules.forEach((schedule, index) => {
            const row = `
                <tr>
                    <td class="text-center fw-bold text-muted">${index + 1}</td>
                    <td class="text-center">
                        <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold">${schedule.schedule_year}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${schedule.schedule_level === 'ม.1' ? 'bg-label-info' : 'bg-label-warning'} rounded-pill px-3 py-1 fw-bold">${schedule.schedule_level}</span>
                    </td>
                    <td>
                        <span class="fw-bold text-dark">${schedule.schedule_round}</span>
                    </td>
                    <td class="text-center">
                        <div class="p-2 rounded-3 bg-light border text-start d-inline-block">
                            <div class="small text-success fw-bold"><i class="bx bx-play-circle me-1"></i>${formatDateTime(schedule.schedule_recruit_start)}</div>
                            <div class="small text-danger fw-bold"><i class="bx bx-stop-circle me-1"></i>${formatDateTime(schedule.schedule_recruit_end)}</div>
                        </div>
                    </td>
                    <td class="text-center"><span class="small fw-semibold text-secondary">${schedule.schedule_exam ? formatDate(schedule.schedule_exam) : '-'}</span></td>
                    <td class="text-center"><span class="small fw-semibold text-secondary">${schedule.schedule_announce ? formatDate(schedule.schedule_announce) : '-'}</span></td>
                    <td class="text-center"><span class="small fw-semibold text-secondary">${schedule.schedule_report ? formatDate(schedule.schedule_report) : '-'}</span></td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded fs-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                <a class="dropdown-item py-2" href="javascript:void(0);" onclick="editSchedule(${schedule.schedule_id})">
                                    <i class="bx bx-edit-alt text-warning me-2"></i> แก้ไข
                                </a>
                                <a class="dropdown-item py-2 text-danger" href="javascript:void(0);" onclick="deleteSchedule(${schedule.schedule_id})">
                                    <i class="bx bx-trash me-2"></i> ลบ
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return '-';
        const thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        return date.getDate() + ' ' + thaiMonths[date.getMonth()] + ' ' + (date.getFullYear() + 543);
    }

    function formatDateTime(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return '-';
        const thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        const h = String(date.getHours()).padStart(2, '0');
        const m = String(date.getMinutes()).padStart(2, '0');
        return `${date.getDate()} ${thaiMonths[date.getMonth()]} ${date.getFullYear() + 543} (${h}:${m} น.)`;
    }

    $('#addScheduleForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: baseUrl + '/skjadmin/schedules/add',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: response.message,
                        timer: 1500
                    });
                    $('#addScheduleModal').modal('hide');
                    $('#addScheduleForm')[0].reset();
                    loadSchedules();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้'
                });
            }
        });
    });

    function editSchedule(id) {
        const schedule = allSchedules.find(s => s.schedule_id == id);
        if (!schedule) return;

        $('#editScheduleId').val(schedule.schedule_id);
        $('#editScheduleYear').val(schedule.schedule_year);
        $('#editScheduleLevel').val(schedule.schedule_level);
        $('#editScheduleRound').val(schedule.schedule_round);
        
        const setPickerValue = (selector, val) => {
            const input = document.querySelector(selector);
            if (input && input._flatpickr) {
                input._flatpickr.setDate(val || '', true);
            } else {
                $(selector).val(val || '');
            }
        };

        setPickerValue('#editScheduleRecruitStart', schedule.schedule_recruit_start);
        setPickerValue('#editScheduleRecruitEnd', schedule.schedule_recruit_end);
        setPickerValue('#editScheduleExam', schedule.schedule_exam);
        setPickerValue('#editScheduleAnnounce', schedule.schedule_announce);
        setPickerValue('#editScheduleReport', schedule.schedule_report);

        $('#editScheduleModal').modal('show');
    }

    $('#editScheduleForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: baseUrl + '/skjadmin/schedules/update',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: response.message,
                        timer: 1500
                    });
                    $('#editScheduleModal').modal('hide');
                    loadSchedules();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้'
                });
            }
        });
    });

    function deleteSchedule(id) {
        Swal.fire({
            title: 'ยืนยันการลบกำหนดการ?',
            text: 'คุณต้องการลบกำหนดการนี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseUrl + '/skjadmin/schedules/delete',
                    method: 'POST',
                    data: { schedule_id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ!',
                                text: response.message,
                                timer: 1500
                            });
                            loadSchedules();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถลบข้อมูลได้'
                        });
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>

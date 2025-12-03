<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <!-- Header Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">
                            <i class="bi bi-calendar-event text-primary me-2"></i>
                            จัดการกำหนดการรับสมัคร
                        </h4>
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            จัดการกำหนดการสำคัญต่างๆ ในระบบรับสมัครนักเรียน
                        </p>
                    </div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        <i class="bi bi-plus-circle me-2"></i>เพิ่มกำหนดการ
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-funnel me-1"></i>ปีการศึกษา
                        </label>
                        <select class="form-select" id="filterYear">
                            <option value="">ทุกปีการศึกษา</option>
                            <?php for($i = 2567; $i <= 2570; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-layers me-1"></i>ระดับชั้น
                        </label>
                        <select class="form-select" id="filterLevel">
                            <option value="">ทุกระดับ</option>
                            <option value="ม.1">ม.1</option>
                            <option value="ม.4">ม.4</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" id="resetFilter">
                            <i class="bi bi-arrow-clockwise me-2"></i>รีเซ็ตตัวกรอง
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Table Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    รายการกำหนดการทั้งหมด
                </h5>
                <span class="badge bg-label-primary" id="totalSchedules">0 รายการ</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="scheduleTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th width="5%">#</th>
                                <th width="10%">ปีการศึกษา</th>
                                <th width="10%">ระดับชั้น</th>
                                <th width="15%">รอบการรับสมัคร</th>
                                <th width="15%">รับสมัคร</th>
                                <th width="10%">สอบ</th>
                                <th width="10%">ประกาศผล</th>
                                <th width="10%">รายงานตัว</th>
                                <th width="15%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <!-- Data will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <!-- Empty State -->
                <div id="emptyState" class="text-center py-5" style="display: none;">
                    <i class="bi bi-calendar-x" style="font-size: 4rem; color: #ddd;"></i>
                    <h5 class="mt-3 text-muted">ไม่พบข้อมูลกำหนดการ</h5>
                    <p class="text-muted">กรุณาเพิ่มกำหนดการใหม่หรือปรับเปลี่ยนตัวกรอง</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>เพิ่มกำหนดการใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addScheduleForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar3 me-1 text-primary"></i>
                                ปีการศึกษา <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="schedule_year" required>
                                <?php for($i = 2567; $i <= 2570; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-layers me-1 text-primary"></i>
                                ระดับชั้น <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="schedule_level" required>
                                <option value="">-- เลือกระดับชั้น --</option>
                                <option value="ม.1">ม.1</option>
                                <option value="ม.4">ม.4</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bookmark me-1 text-primary"></i>
                                รอบการรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="schedule_round" placeholder="เช่น รอบที่ 1" required>
                        </div>
                        
                        <div class="col-12"><hr></div>
                        <div class="col-12">
                            <h6 class="text-primary">
                                <i class="bi bi-calendar-check me-2"></i>วันที่รับสมัคร
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                วันเริ่มรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" name="schedule_recruit_start" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                วันปิดรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" name="schedule_recruit_end" required>
                        </div>
                        
                        <div class="col-12"><hr></div>
                        <div class="col-12">
                            <h6 class="text-primary">
                                <i class="bi bi-calendar-event me-2"></i>วันที่สำคัญอื่นๆ (ถ้ามี)
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                วันสอบ
                            </label>
                            <input type="date" class="form-control" name="schedule_exam">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                วันประกาศผล
                            </label>
                            <input type="date" class="form-control" name="schedule_announce">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                วันรายงานตัว
                            </label>
                            <input type="date" class="form-control" name="schedule_report">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>แก้ไขกำหนดการ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduleForm">
                <input type="hidden" name="schedule_id" id="editScheduleId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar3 me-1 text-warning"></i>
                                ปีการศึกษา <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="schedule_year" id="editScheduleYear" required>
                                <?php for($i = 2567; $i <= 2570; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-layers me-1 text-warning"></i>
                                ระดับชั้น <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="schedule_level" id="editScheduleLevel" required>
                                <option value="ม.1">ม.1</option>
                                <option value="ม.4">ม.4</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bookmark me-1 text-warning"></i>
                                รอบการรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="schedule_round" id="editScheduleRound" required>
                        </div>
                        
                        <div class="col-12"><hr></div>
                        <div class="col-12">
                            <h6 class="text-warning">
                                <i class="bi bi-calendar-check me-2"></i>วันที่รับสมัคร
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                วันเริ่มรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" name="schedule_recruit_start" id="editScheduleRecruitStart" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                วันปิดรับสมัคร <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" name="schedule_recruit_end" id="editScheduleRecruitEnd" required>
                        </div>
                        
                        <div class="col-12"><hr></div>
                        <div class="col-12">
                            <h6 class="text-warning">
                                <i class="bi bi-calendar-event me-2"></i>วันที่สำคัญอื่นๆ
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                วันสอบ
                            </label>
                            <input type="date" class="form-control" name="schedule_exam" id="editScheduleExam">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                วันประกาศผล
                            </label>
                            <input type="date" class="form-control" name="schedule_announce" id="editScheduleAnnounce">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                วันรายงานตัว
                            </label>
                            <input type="date" class="form-control" name="schedule_report" id="editScheduleReport">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-2"></i>บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const baseUrl = '<?= base_url() ?>';
    let allSchedules = [];

    // Load schedules on page load
    $(document).ready(function() {
        loadSchedules();
        
        // Bootstrap Icons CDN (if not already included)
        if (!$('link[href*="bootstrap-icons"]').length) {
            $('head').append('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">');
        }

        // Filter listeners
        $('#filterYear, #filterLevel').on('change', function() {
            filterSchedules();
        });

        $('#resetFilter').on('click', function() {
            $('#filterYear, #filterLevel').val('');
            filterSchedules();
        });
    });

    // Load all schedules
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
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถโหลดข้อมูลได้',
                    confirmButtonColor: '#696cff'
                });
            }
        });
    }

    // Filter and render schedules
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

    // Render schedules to table
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
                    <td class="text-center text-muted">${index + 1}</td>
                    <td class="text-center">
                        <span class="badge bg-label-primary">${schedule.schedule_year}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${schedule.schedule_level === 'ม.1' ? 'bg-label-info' : 'bg-label-success'}">${schedule.schedule_level}</span>
                    </td>
                    <td class="text-center fw-semibold">${schedule.schedule_round}</td>
                    <td class="text-center">
                        <small class="d-block">${formatDate(schedule.schedule_recruit_start)}</small>
                        <small class="text-muted">ถึง</small>
                        <small class="d-block">${formatDate(schedule.schedule_recruit_end)}</small>
                    </td>
                    <td class="text-center">${schedule.schedule_exam ? formatDate(schedule.schedule_exam) : '<span class="text-muted">-</span>'}</td>
                    <td class="text-center">${schedule.schedule_announce ? formatDate(schedule.schedule_announce) : '<span class="text-muted">-</span>'}</td>
                    <td class="text-center">${schedule.schedule_report ? formatDate(schedule.schedule_report) : '<span class="text-muted">-</span>'}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-warning" onclick="editSchedule(${schedule.schedule_id})" title="แก้ไข">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSchedule(${schedule.schedule_id})" title="ลบ">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Format date to Thai
    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        const thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        return date.getDate() + ' ' + thaiMonths[date.getMonth()] + ' ' + (date.getFullYear() + 543);
    }

    // Add schedule form submit
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
                        confirmButtonColor: '#696cff',
                        timer: 1500
                    });
                    $('#addScheduleModal').modal('hide');
                    $('#addScheduleForm')[0].reset();
                    loadSchedules();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message,
                        confirmButtonColor: '#696cff'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้',
                    confirmButtonColor: '#696cff'
                });
            }
        });
    });

    // Edit schedule
    function editSchedule(id) {
        const schedule = allSchedules.find(s => s.schedule_id == id);
        if (!schedule) return;

        $('#editScheduleId').val(schedule.schedule_id);
        $('#editScheduleYear').val(schedule.schedule_year);
        $('#editScheduleLevel').val(schedule.schedule_level);
        $('#editScheduleRound').val(schedule.schedule_round);
        
        // Format datetime for datetime-local input (YYYY-MM-DDTHH:mm)
        $('#editScheduleRecruitStart').val(formatDateTimeLocal(schedule.schedule_recruit_start));
        $('#editScheduleRecruitEnd').val(formatDateTimeLocal(schedule.schedule_recruit_end));
        
        // Format date only for date input (YYYY-MM-DD)
        $('#editScheduleExam').val(formatDateOnly(schedule.schedule_exam));
        $('#editScheduleAnnounce').val(formatDateOnly(schedule.schedule_announce));
        $('#editScheduleReport').val(formatDateOnly(schedule.schedule_report));

        $('#editScheduleModal').modal('show');
    }

    // Format datetime from database to datetime-local input format
    function formatDateTimeLocal(dateStr) {
        if (!dateStr) return '';
        // Database format: "2024-12-01 08:00:00"
        // Input format needed: "2024-12-01T08:00"
        const date = new Date(dateStr);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Format date from database to date input format (YYYY-MM-DD)
    function formatDateOnly(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return ''; // Invalid date
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Edit schedule form submit
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
                        confirmButtonColor: '#696cff',
                        timer: 1500
                    });
                    $('#editScheduleModal').modal('hide');
                    loadSchedules();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message,
                        confirmButtonColor: '#696cff'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้',
                    confirmButtonColor: '#696cff'
                });
            }
        });
    });

    // Delete schedule
    function deleteSchedule(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: 'คุณต้องการลบกำหนดการนี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'ใช่, ลบเลย!',
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
                                confirmButtonColor: '#696cff',
                                timer: 1500
                            });
                            loadSchedules();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message,
                                confirmButtonColor: '#696cff'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถลบข้อมูลได้',
                            confirmButtonColor: '#696cff'
                        });
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>

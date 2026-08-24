<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<style>
    /* Color Variables - Suankularb Pink & Sky Blue Theme */
    :root {
        --primary-color: #ff6b8b;
        --primary-dark: #e04869;
        --primary-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
        --primary-light: rgba(255, 107, 139, 0.15);
    }

    /* Report Type Cards */
    .report-card {
        border-radius: 16px;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .report-card.active {
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(255, 107, 139, 0.35);
    }

    .report-card .card-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1rem;
    }

    .report-card h5 {
        font-weight: 700;
    }

    /* Year Selector */
    .year-selector-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--primary-gradient);
        padding: 12px 24px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.35);
    }

    .year-selector-label {
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .year-selector-label i {
        font-size: 1.25rem;
    }

    .year-selector {
        border: none;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 700;
        font-size: 1.1rem;
        min-width: 100px;
        background: white;
        color: var(--primary-color);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .year-selector:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
    }

    /* Main Card */
    .main-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
    }

    .main-card .card-header {
        background: white;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.25rem 1.5rem;
    }

    /* Table Styling */
    #studentsTable {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    #studentsTable thead th {
        border: none;
        background: var(--primary-gradient);
        color: white;
        padding: 14px 16px;
        font-weight: 600;
    }

    #studentsTable thead th:first-child {
        border-radius: 10px 0 0 10px;
    }

    #studentsTable thead th:last-child {
        border-radius: 0 10px 10px 0;
    }

    #studentsTable tbody tr {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }

    #studentsTable tbody tr:hover {
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
    }

    #studentsTable tbody td {
        border: none;
        padding: 12px 16px;
        vertical-align: middle;
    }

    #studentsTable tbody td:first-child {
        border-radius: 10px 0 0 10px;
    }

    #studentsTable tbody td:last-child {
        border-radius: 0 10px 10px 0;
    }

    /* Recruit Avatar */
    .recruit-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }

    /* Print Button */
    .btn-print {
        background: var(--primary-gradient);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-print:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .btn-print:disabled {
        background: #ccc;
        transform: none;
        box-shadow: none;
    }

    /* Checkbox Styling */
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Page Title Icon */
    .text-primary {
        color: var(--primary-color) !important;
    }

    /* Selection Info */
    .selection-info {
        background: var(--primary-light);
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .selection-count {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
    }
</style>

<!-- Page Header with Year Selector -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bx bx-printer text-primary me-2"></i>
            รายงานและการพิมพ์
        </h4>
        <p class="text-muted mb-0">พิมพ์ใบสมัครและใบรายงานตัวแบบรวม</p>
    </div>
</div>

<!-- Report Type Selection -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card report-card active" data-type="application" id="cardApplication">
            <div class="card-body text-center py-4">
                <div class="card-icon" style="background: rgba(255, 107, 139, 0.15); color: #ff6b8b;">
                    <i class="bx bx-file"></i>
                </div>
                <h5 class="mb-2">พิมพ์ใบสมัคร</h5>
                <p class="text-muted mb-0">พิมพ์ใบสมัครเข้าเรียนของผู้สมัคร</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card report-card" data-type="confirmation" id="cardConfirmation">
            <div class="card-body text-center py-4">
                <div class="card-icon" style="background: rgba(86, 204, 242, 0.15); color: #249ecd;">
                    <i class="bx bx-id-card"></i>
                </div>
                <h5 class="mb-2">พิมพ์ใบรายงานตัว</h5>
                <p class="text-muted mb-0">พิมพ์ใบรายงานตัวของนักเรียนที่รายงานตัวแล้ว</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters Row -->
<div class="card main-card mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="year" class="form-label">ปีการศึกษา</label>
                <select name="year" id="year" class="form-select">
                    <?php foreach ($years as $y): ?>
                        <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == ($selected_year ?? '') ? 'selected' : '' ?>><?= $y->recruit_year ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="level" class="form-label">ระดับชั้น</label>
                <select class="form-select" id="level" name="level">
                    <option value="">ทั้งหมด</option>
                    <option value="1">มัธยมศึกษาปีที่ 1</option>
                    <option value="4">มัธยมศึกษาปีที่ 4</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="course" class="form-label">หลักสูตร/แผนการเรียน</label>
                <select class="form-select" id="course" name="course">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= $c->course_id ?>" data-level="<?= $c->course_gradelevel ?>">
                            <?= $c->course_initials ?? $c->course_fullname ?> (ม.<?= $c->course_gradelevel ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-outline-success w-100" id="btnLoadData">
                    <i class="bx bx-search me-1"></i> โหลด
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Selection Info & Print Button -->
<div class="selection-info mb-4" id="selectionInfo" style="display: none;">
    <div class="d-flex align-items-center gap-3">
        <div>
            <span class="text-muted">เลือกแล้ว:</span>
            <span class="selection-count" id="selectedCount">0</span>
            <span class="text-muted">คน</span>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-success" id="btnSelectAll">
                <i class="bx bx-check-double"></i> เลือกทั้งหมด
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDeselectAll">
                <i class="bx bx-x"></i> ยกเลิกทั้งหมด
            </button>
        </div>
    </div>
    <div>
        <button type="button" class="btn btn-print" id="btnPrint" disabled>
            <i class="bx bx-printer me-1"></i> พิมพ์ที่เลือก
        </button>
        <button type="button" class="btn btn-print ms-2" id="btnPrintAll">
            <i class="bx bx-printer me-1"></i> พิมพ์ทั้งหมด
        </button>
    </div>
</div>

<!-- Students Table -->
<div class="card main-card">
    <div class="card-header">
        <h5 class="mb-0 d-flex align-items-center gap-2">
            <i class="bx bx-table text-primary"></i>
            รายชื่อผู้สมัคร
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table" id="studentsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                        </th>
                        <th style="width: 80px;">รูป</th>
                        <th>ชื่อ - นามสกุล</th>
                        <th>รหัส</th>
                        <th>แผนการเรียน</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody id="studentsBody">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bx bx-info-circle bx-lg mb-2"></i>
                            <p>กรุณาเลือกเงื่อนไขและกดปุ่ม "โหลดข้อมูล"</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        var reportType = 'application'; // default
        var studentsData = [];
        var dataTable = null; // DataTable instance

        // Store all course options for filtering
        var allCourseOptions = $('#course option').clone();

        // Filter courses when level changes
        $('#level').on('change', function () {
            var selectedLevel = $(this).val();
            var $courseSelect = $('#course');

            // Reset course dropdown
            $courseSelect.empty();
            $courseSelect.append('<option value="">ทั้งหมด</option>');

            // Add filtered options
            allCourseOptions.each(function () {
                var $option = $(this);
                var optionLevel = $option.data('level');

                // Skip the "ทั้งหมด" option (no data-level)
                if (!optionLevel) return;

                // If no level selected, show all. Otherwise filter by level
                if (!selectedLevel || optionLevel == selectedLevel) {
                    $courseSelect.append($option.clone());
                }
            });
        });

        // Report type selection
        $('.report-card').on('click', function () {
            $('.report-card').removeClass('active');
            $(this).addClass('active');
            reportType = $(this).data('type');

            // Reload data if already loaded
            if (studentsData.length > 0) {
                $('#btnLoadData').click();
            }
        });

        // Load Data
        $('#btnLoadData').on('click', function () {
            var year = $('#year').val();
            var level = $('#level').val();
            var course = $('#course').val();

            if (!year) {
                Swal.fire('กรุณาเลือกปีการศึกษา', '', 'warning');
                return;
            }

            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> กำลังโหลด...');

            $.ajax({
                url: '<?= site_url('skjadmin/reports/get-students') ?>',
                type: 'POST',
                data: {
                    year: year,
                    level: level,
                    course: course,
                    type: reportType,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function (response) {
                    $btn.prop('disabled', false).html('<i class="bx bx-search me-1"></i> โหลด');

                    if (response.success) {
                        studentsData = response.data;
                        renderTable(studentsData);
                        $('#selectionInfo').show();
                        updateSelectionCount();
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', response.message || 'ไม่สามารถโหลดข้อมูลได้', 'error');
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).html('<i class="bx bx-search me-1"></i> โหลด');
                    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
                }
            });
        });

        // Render Table with DataTable
        function renderTable(data) {
            // Destroy existing DataTable if exists
            if (dataTable !== null) {
                dataTable.destroy();
                dataTable = null;
            }

            var html = '';
            var defaultImg = '<?= base_url('public/sneat-assets/img/avatars/1.png') ?>';

            if (data.length === 0) {
                html = '<tr><td colspan="6" class="text-center text-muted py-5"><i class="bx bx-info-circle bx-lg mb-2"></i><p>ไม่พบข้อมูล</p></td></tr>';
                $('#studentsBody').html(html);
            } else {
                data.forEach(function (student) {
                    var statusClass = student.can_print ? 'bg-label-success' : 'bg-label-warning';
                    var statusText = student.status_text;

                    html += '<tr data-id="' + student.id + '">';
                    html += '<td><input type="checkbox" class="form-check-input student-check" value="' + student.id + '" ' + (student.can_print ? '' : 'disabled') + '></td>';
                    html += '<td><img src="' + student.avatar + '" class="recruit-avatar" alt="Avatar" loading="lazy" onerror="this.onerror=null;this.src=\'' + defaultImg + '\'"></td>';
                    html += '<td><div class="fw-semibold">' + student.name + '</div><small class="text-muted">' + student.lastname + '</small></td>';
                    html += '<td><span class="badge bg-label-secondary">' + student.recruit_id + '</span></td>';
                    html += '<td><span class="badge bg-label-info">' + student.course + '</span></td>';
                    html += '<td><span class="badge ' + statusClass + '">' + statusText + '</span></td>';
                    html += '</tr>';
                });

                $('#studentsBody').html(html);

                // Initialize DataTable
                dataTable = $('#studentsTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"]],
                    language: {
                        search: "ค้นหา:",
                        lengthMenu: "แสดง _MENU_ รายการ",
                        info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                        infoEmpty: "แสดง 0 ถึง 0 จาก 0 รายการ",
                        infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
                        paginate: {
                            first: "หน้าแรก",
                            last: "หน้าสุดท้าย",
                            next: "ถัดไป",
                            previous: "ก่อนหน้า"
                        },
                        zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
                        emptyTable: "ไม่มีข้อมูลในตาราง"
                    },
                    columnDefs: [
                        { orderable: false, targets: [0, 1] }, // Disable sorting on checkbox and image columns
                        { searchable: false, targets: [0, 1] }  // Disable search on checkbox and image columns
                    ],
                    order: [[2, 'asc']] // Order by name column
                });
            }

            $('#checkAll').prop('checked', false); // Reset check all checkbox
        }

        // Check All
        // Check All Checkbox
        $('#checkAll').on('change', function () {
            var isChecked = $(this).prop('checked');
            $('.student-check:not(:disabled)').prop('checked', isChecked);
            updateSelectionCount();
        });

        // Button Select All
        $('#btnSelectAll').on('click', function () {
            $('.student-check:not(:disabled)').prop('checked', true);
            $('#checkAll').prop('checked', true);
            updateSelectionCount();
        });

        // Deselect All
        $('#btnDeselectAll').on('click', function () {
            $('.student-check').prop('checked', false);
            $('#checkAll').prop('checked', false);
            updateSelectionCount();
        });

        // Individual checkbox change
        $(document).on('change', '.student-check', function () {
            updateSelectionCount();
        });

        // Update selection count
        function updateSelectionCount() {
            var count = $('.student-check:checked').length;
            $('#selectedCount').text(count);
            $('#btnPrint').prop('disabled', count === 0);
        }

        // Print Selected
        $('#btnPrint').on('click', function () {
            var selectedIds = [];
            $('.student-check:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire('กรุณาเลือกรายการที่ต้องการพิมพ์', '', 'warning');
                return;
            }

            printStudents(selectedIds);
        });

        // Print All
        $('#btnPrintAll').on('click', function () {
            if (studentsData.length === 0) {
                Swal.fire('กรุณาโหลดข้อมูลก่อน', '', 'warning');
                return;
            }

            var allIds = [];
            studentsData.forEach(function (student) {
                if (student.can_print) {
                    allIds.push(student.id);
                }
            });

            if (allIds.length === 0) {
                Swal.fire('ไม่มีรายการที่สามารถพิมพ์ได้', '', 'warning');
                return;
            }

            printStudents(allIds);
        });

        // Print function
        async function printStudents(ids) {
            if (ids.length === 0) return;

            // If fewer than 10 students, use direct method (faster UX)
            if (ids.length < 2) {
                var year = $('#year').val();
                var url = '<?= site_url('skjadmin/reports/print-batch') ?>?type=' + reportType + '&year=' + year + '&ids=' + ids.join(',');
                window.open(url, '_blank');
                return;
            }

            const BATCH_SIZE = 5; // Valid size per request
            const total = ids.length;
            let processed = 0;

            // CSRF handling
            let csrfName = '<?= csrf_token() ?>';
            let csrfHash = '<?= csrf_hash() ?>';
            let isCancelled = false;
            let activeBatchId = null;

            Swal.fire({
                title: 'กำลังสร้างไฟล์ PDF...',
                html: `
                <div class="text-center mb-2">สร้างแล้ว <b id="progress-pdf">0</b> จาก <b>${total}</b> ไฟล์</div>
                <div class="progress mb-3" style="height: 25px;">
                    <div id="progress-bar-pdf" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%; font-weight:bold;">0%</div>
                </div>
                <button type="button" class="btn btn-danger btn-sm" id="btn-cancel-pdf">
                    <i class="bx bx-x-circle me-1"></i> ยกเลิก
                </button>
            `,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: async () => {
                    // Attach Cancel Event
                    document.getElementById('btn-cancel-pdf').addEventListener('click', function () {
                        isCancelled = true;
                        // Disable button to show feedback
                        this.disabled = true;
                        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> กำลังยกเลิก...';
                    });

                    try {
                        // Step 1: Init Batch
                        const initData = {};
                        initData[csrfName] = csrfHash;

                        if (isCancelled) throw new Error('Cancelled');

                        const initRes = await $.ajax({
                            url: '<?= site_url('skjadmin/reports/init-batch') ?>',
                            type: 'POST',
                            data: initData,
                            dataType: 'json'
                        });

                        if (!initRes.success) throw new Error('Cannot init batch');
                        const batchId = initRes.batch_id;
                        activeBatchId = batchId; // Store for cancellation

                        // Step 2: Process Chunks
                        for (let i = 0; i < total; i += BATCH_SIZE) {
                            if (isCancelled) break; // Check cancellation before each request

                            const chunk = ids.slice(i, i + BATCH_SIZE);
                            const data = {
                                batch_id: batchId,
                                ids: chunk,
                                type: reportType
                            };
                            data[csrfName] = csrfHash;

                            const res = await $.ajax({
                                url: '<?= site_url('skjadmin/reports/process-batch') ?>',
                                type: 'POST',
                                data: data,
                                dataType: 'json'
                            });

                            if (res.success) {
                                processed += chunk.length;
                                if (processed > total) processed = total;

                                const percent = Math.round((processed / total) * 100);

                                // Update UI
                                const content = Swal.getHtmlContainer();
                                if (content && !isCancelled) {
                                    content.querySelector('#progress-pdf').textContent = processed;
                                    const bar = content.querySelector('#progress-bar-pdf');
                                    bar.style.width = percent + '%';
                                    bar.textContent = percent + '%';
                                }
                            } else {
                                throw new Error(res.message || 'Error processing chunk');
                            }
                        }

                        if (isCancelled) {
                            // User cancelled - Cleanup
                            if (activeBatchId) {
                                $.ajax({
                                    url: '<?= site_url('skjadmin/reports/cancel-batch') ?>',
                                    type: 'POST',
                                    data: { batch_id: activeBatchId }
                                });
                            }
                            Swal.fire('ยกเลิกแล้ว', 'การดาวน์โหลดถูกยกเลิกและลบไฟล์ชั่วคราวเรียบร้อยแล้ว', 'info');
                            return;
                        }

                        // Step 3: Finish & Download
                        window.location.href = '<?= site_url('skjadmin/reports/finish-batch') ?>?batch_id=' + batchId + '&type=' + reportType;

                        Swal.fire({
                            title: 'สร้างไฟล์สำเร็จ!',
                            text: 'กำลังเริ่มการดาวน์โหลด...',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                    } catch (error) {
                        if (error.message === 'Cancelled') {
                            Swal.fire('ยกเลิกแล้ว', '', 'info');
                            return;
                        }
                        console.error('Batch Print Error:', error);
                        // ... Error Handling ...
                        let errorMsg = 'Unknown Error';
                        if (error instanceof Error) errorMsg = error.message;
                        if (error.status) errorMsg = `HTTP ${error.status}`;

                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            html: `ไม่สามารถสร้างไฟล์เอกสารได้<br><small class="text-danger">${errorMsg}</small>`,
                            footer: 'กรุณาลองใหม่ หรือติดต่อผู้ดูแลระบบ'
                        });
                    }
                }
            });
        }



    });
</script>
<?= $this->endSection() ?>
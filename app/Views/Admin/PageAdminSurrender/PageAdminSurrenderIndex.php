<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<style>
    /* Color Variables - Green Theme */
    :root {
        --primary-color: #28a745;
        --primary-dark: #1e7e34;
        --primary-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        --primary-light: rgba(40, 167, 69, 0.15);
    }

    /* Stats Cards */
    .stat-card {
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .stat-card .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-card .stat-label {
        color: #697a8d;
        font-size: 0.9rem;
    }

    /* Filter Buttons */
    .filter-btn {
        border-radius: 20px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        transform: scale(1.05);
    }

    .filter-btn.active {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    /* Table Styling - Green Theme */
    #surrenderTable {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    #surrenderTable thead th {
        border: none;
        background: var(--primary-gradient);
        color: white;
        padding: 14px 16px;
        font-weight: 600;
    }

    #surrenderTable thead th:first-child {
        border-radius: 10px 0 0 10px;
    }

    #surrenderTable thead th:last-child {
        border-radius: 0 10px 10px 0;
    }

    #surrenderTable tbody tr {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }

    #surrenderTable tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
    }

    #surrenderTable tbody td {
        border: none;
        padding: 12px 16px;
        vertical-align: middle;
    }

    #surrenderTable tbody td:first-child {
        border-radius: 10px 0 0 10px;
    }

    #surrenderTable tbody td:last-child {
        border-radius: 0 10px 10px 0;
    }

    /* Action Buttons */
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
    }

    .action-btn:hover {
        transform: scale(1.15);
    }

    .action-btn.print-btn {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .action-btn.print-btn:hover {
        background: #28a745;
        color: white;
    }

    .action-btn.view-btn {
        background: rgba(32, 201, 151, 0.15);
        color: #20c997;
    }

    .action-btn.view-btn:hover {
        background: #20c997;
        color: white;
    }

    /* Avatar in Table */
    .recruit-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-confirmed {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .status-pending {
        background: rgba(255, 171, 0, 0.15);
        color: #ffab00;
    }

    .status-approved {
        background: rgba(113, 221, 55, 0.15);
        color: #71dd37;
    }

    .status-rejected {
        background: rgba(255, 62, 29, 0.15);
        color: #ff3e1d;
    }

    /* Card Header */
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

    /* Year Selector - Green Theme */
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

    /* Search Box - Green Theme */
    .dataTables_filter input {
        border-radius: 10px !important;
        padding: 0.5rem 1rem !important;
        border: 2px solid #e9ecef !important;
    }

    .dataTables_filter input:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
    }

    /* Page Title Icon */
    .text-primary {
        color: var(--primary-color) !important;
    }
</style>

<!-- Page Header with Year Selector -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bx bx-id-card text-primary me-2"></i>
            ข้อมูลการรายงานตัว
        </h4>
        <p class="text-muted mb-0">จัดการข้อมูลการรายงานตัวนักเรียน</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form method="get" action="<?= site_url('skjadmin/surrender') ?>" id="yearForm">
            <div class="year-selector-wrapper">
                <label class="year-selector-label">
                    <i class="bx bx-calendar"></i>
                    ปีการศึกษา
                </label>
                <select name="year" id="year" class="form-select year-selector" onchange="this.form.submit()">
                    <?php foreach ($years as $y): ?>
                        <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selected_year ? 'selected' : '' ?>>
                            <?= $y->recruit_year ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php
// Filter only students who passed selection or quiz
$students = array_filter($students ?? [], function($student) {
    $statusQuiz = $student->recruit_StatusQuiz ?? '';
    $isSurrendered = !empty($student->recruit_statusSurrender ?? '');
    $isFinal = ($student->recruit_statusFinal ?? '') == 'เสร็จสิ้น';
    
    // แสดงเฉพาะผู้ที่ผ่านการคัดเลือก หรือผู้ที่ได้มอบตัว/อนุมัติเป็นนักเรียนแล้ว (สำหรับข้อมูลย้อนหลัง)
    return $statusQuiz == 'ผ่านการคัดเลือก' || $statusQuiz == 'สอบผ่าน' || $isSurrendered || $isFinal;
});

// Calculate stats
$totalStudents = count($students);
$confirmedCount = 0;
$approvedCount = $totalStudents; // Since we filtered, all are approved

foreach ($students as $student) {
    // Check confirmed
    $isConfirmed = (!empty($student->stu_UpdateConfirm) && $student->stu_UpdateConfirm == $student->recruit_year);
    if ($isConfirmed) {
        $confirmedCount++;
    }
}

// Pending = Passed - Confirmed
$pendingCount = $approvedCount - $confirmedCount;
if ($pendingCount < 0) $pendingCount = 0;
?>

<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value text-success"><?= $approvedCount ?></div>
                        <div class="stat-label">ผู้ที่ผ่านการคัดเลือก</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(3, 195, 236, 0.15); color: #03c3ec;">
                        <i class="bx bx-check-double"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color: #03c3ec;"><?= $confirmedCount ?></div>
                        <div class="stat-label">รายงานตัวแล้ว</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-xl-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255, 171, 0, 0.15); color: #ffab00;">
                        <i class="bx bx-time-five"></i>
                    </div>
                    <div>
                        <div class="stat-value text-warning"><?= $pendingCount ?></div>
                        <div class="stat-label">รอรายงานตัว</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card main-card">
    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bx bx-table text-primary"></i>
                รายการรายงานตัว
            </h5>
            <!-- Filter Buttons -->
            <div class="btn-group" role="group" id="statusFilter">
                <button type="button" class="btn filter-btn btn-outline-success active" data-status="">
                    ทั้งหมด
                </button>
                <button type="button" class="btn filter-btn btn-outline-success" data-status="confirmed">
                    <i class="bx bx-check-double me-1"></i> รายงานตัวแล้ว
                </button>
                <button type="button" class="btn filter-btn btn-outline-warning" data-status="pending">
                    <i class="bx bx-time-five me-1"></i> รอรายงานตัว
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover" id="surrenderTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">รูป</th>
                        <th data-priority="1">ชื่อ - นามสกุล</th>
                        <th data-priority="4">รหัส / แผน</th>
                        <th data-priority="2" class="text-center">รายงานตัว</th>
                        <th data-priority="3" class="text-center">มอบตัว</th>
                        <th data-priority="5" class="text-center">อนุมัติ</th>
                        <th style="width: 120px;" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                            <?php
                            $isConfirmed = (!empty($student->stu_UpdateConfirm) && $student->stu_UpdateConfirm == $student->recruit_year);
                            $isSurrendered = !empty($student->recruit_statusSurrender ?? '');
                            $isFinalApproved = (($student->recruit_statusFinal ?? '') == 'เสร็จสิ้น');
                            
                            $rQuiz = $student->recruit_StatusQuiz ?? 'รอผล';
                            $quizClass = ($rQuiz == 'ผ่านการคัดเลือก' || $rQuiz == 'สอบผ่าน') ? 'bg-label-success' : ($rQuiz == 'ไม่ผ่านการคัดเลือก' || $rQuiz == 'สอบไม่ผ่าน' ? 'bg-label-danger' : 'bg-label-secondary');

                            // Generate avatar
                            $imgSrc = get_recruit_file_url($student->recruit_img ?? 'default.png', $student->recruit_regLevel ?? '1', 'img');
                            ?>
                            <tr data-status="<?= $isConfirmed ? 'confirmed' : 'pending' ?>">
                                <td>
                                    <img src="<?= $imgSrc ?>" class="recruit-avatar" alt="Avatar" loading="lazy"
                                        onerror="this.onerror=null;this.src='<?= base_url('public/sneat-assets/img/avatars/1.png') ?>';">
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= esc($student->recruit_prefix . $student->recruit_firstName) ?></div>
                                    <div class="d-flex align-items-center gap-1">
                                        <small class="text-muted"><?= esc($student->recruit_lastName) ?></small>
                                        <span class="badge <?= $quizClass ?> p-0 px-1" style="font-size: 0.65rem;"><?= esc($rQuiz) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold text-secondary">#<?= esc(sprintf('%04d', $student->recruit_id)) ?></div>
                                    <div class="small text-info"><?= esc($student->course_initials ?? $student->recruit_tpyeRoom) ?></div>
                                    <?php if (!empty($student->recruit_major)): ?>
                                        <div class="small text-muted" style="font-size: 0.7rem;"><?= esc($student->recruit_major) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($isConfirmed): ?>
                                        <span class="badge bg-label-success" data-bs-toggle="tooltip" title="รายงานตัวออนไลน์แล้วเมื่อคราวปี <?= $student->recruit_year ?>">
                                            <i class="bx bx-check-double"></i>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-label-warning" data-bs-toggle="tooltip" title="ยังไม่ดำเนินการรายงานตัวออนไลน์">
                                            <i class="bx bx-time-five"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input surrender-toggle" type="checkbox" 
                                            data-id="<?= $student->recruit_id ?>" 
                                            <?= $isSurrendered ? 'checked' : '' ?>
                                            <?= !$isConfirmed ? 'disabled' : '' ?>>
                                    </div>
                                    <?php if ($isSurrendered): ?>
                                        <small class="text-muted d-block" style="font-size: 0.6rem;"><?= date('d/m/y', strtotime($student->recruit_statusSurrender)) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                        class="btn btn-sm btn-icon final-approve-btn <?= $isFinalApproved ? 'btn-success' : 'btn-outline-secondary' ?>"
                                        data-id="<?= $student->recruit_id ?>"
                                        data-status="<?= $isFinalApproved ? '1' : '0' ?>"
                                        <?= !$isSurrendered ? 'disabled' : '' ?>
                                        data-bs-toggle="tooltip" title="<?= $isFinalApproved ? 'เป็นนักเรียนแล้ว' : 'กดเพื่ออนุมัติเป็นนักเรียน' ?>">
                                        <i class="bx <?= $isFinalApproved ? 'bx-user-check' : 'bx-user-plus' ?>"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <?php if ($isConfirmed): ?>
                                            <a href="<?= site_url('skjadmin/surrender/print/' . $student->recruit_id) ?>"
                                                target="_blank" class="btn btn-icon btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="พิมพ์ใบรายงานตัว">
                                                <i class="bx bx-printer"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= site_url('skjadmin/recruits/view/' . $student->recruit_id) ?>"
                                            class="btn btn-icon btn-sm btn-outline-info" data-bs-toggle="tooltip" title="ดูข้อมูล">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        var table = $('#surrenderTable').DataTable({
            responsive: true,
            stateSave: true,
            pageLength: 15,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });

        // Surrender Toggle (Using event delegation for DataTables)
        $(document).on('change', '.surrender-toggle', function() {
            var $this = $(this);
            var id = $this.attr('data-id');
            var status = $this.is(':checked') ? 1 : 0;
            
            console.log('Surrender toggle clicked - ID:', id, 'Status:', status);
            
            if (!id) {
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่พบรหัสนักเรียน (data-id)', 'error');
                return;
            }

            $.ajax({
                url: '<?= site_url('skjadmin/surrender/update') ?>',
                type: 'POST',
                data: {
                    recruit_id: id,
                    status: status,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(res) {
                    console.log('Response:', res);
                    if(res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'ปรับปรุงสถานะมอบตัวเรียบร้อย',
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', res.message || 'ไม่สามารถปรับปรุงข้อมูลได้', 'error');
                        $this.prop('checked', !status);
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.status, xhr.responseText);
                    Swal.fire('Error ' + xhr.status, xhr.responseText || 'เกิดข้อผิดพลาดในการส่งข้อมูล', 'error');
                    $this.prop('checked', !status);
                }
            });
        });

        // Final Approval Button (Using event delegation)
        $(document).on('click', '.final-approve-btn', function() {
            var $this = $(this);
            var id = $this.attr('data-id');
            var currentStatus = $this.attr('data-status');
            var newStatus = (currentStatus == '1') ? 0 : 1;
            
            Swal.fire({
                title: (newStatus == 1) ? 'ยืนยันการอนุมัติ?' : 'ยกเลิกการอนุมัติ?',
                text: (newStatus == 1) ? "นักเรียนคนนี้จะเปลี่ยนสถานะเป็นนักเรียนของโรงเรียนโดยสมบูรณ์" : "ต้องการยกเลิกการอนุมัติเป็นนักเรียนใช่หรือไม่?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url('skjadmin/surrender/update-final') ?>',
                        type: 'POST',
                        data: {
                            recruit_id: id,
                            status: newStatus,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        success: function(res) {
                            if(res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ดำเนินการเรียบร้อย',
                                    timer: 1000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถดำเนินการได้', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error ' + xhr.status, 'เกิดข้อผิดพลาดในการส่งข้อมูล', 'error');
                        }
                    });
                }
            });
        });

        // Filter buttons functionality
        $('#statusFilter .filter-btn').on('click', function () {
            $('#statusFilter .filter-btn').removeClass('active');
            $(this).addClass('active');

            var filterStatus = $(this).data('status');

            if (filterStatus === '') {
                $.fn.dataTable.ext.search.pop();
            } else {
                $.fn.dataTable.ext.search.pop();
                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    var row = table.row(dataIndex).node();
                    var rowStatus = $(row).data('status');
                    return rowStatus === filterStatus;
                });
            }
            table.draw();
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>
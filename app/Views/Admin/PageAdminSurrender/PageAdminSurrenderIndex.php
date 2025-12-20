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
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
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
    .status-confirmed { background: rgba(40, 167, 69, 0.15); color: #28a745; }
    .status-pending { background: rgba(255, 171, 0, 0.15); color: #ffab00; }
    .status-approved { background: rgba(113, 221, 55, 0.15); color: #71dd37; }
    .status-rejected { background: rgba(255, 62, 29, 0.15); color: #ff3e1d; }
    
    /* Card Header */
    .main-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 25px rgba(0,0,0,0.05);
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .year-selector:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255,255,255,0.5);
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
                    <?php foreach ($years as $y) : ?>
                        <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selected_year ? 'selected' : '' ?>><?= $y->recruit_year ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php 
    // Calculate stats
    $totalStudents = count($students ?? []);
    $confirmedCount = 0;
    $pendingCount = 0;
    $approvedCount = 0;
    
    foreach ($students ?? [] as $student) {
        if (!empty($student->stu_UpdateConfirm)) {
            $confirmedCount++;
        } else {
            $pendingCount++;
        }
        if ($student->recruit_status == 'ผ่านการตรวจสอบ') {
            $approvedCount++;
        }
    }
?>

<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <i class="bx bx-user"></i>
                    </div>
                    <div>
                        <div class="stat-value text-success"><?= $totalStudents ?></div>
                        <div class="stat-label">ผู้สมัครทั้งหมด</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(113, 221, 55, 0.15); color: #71dd37;">
                        <i class="bx bx-check-double"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color: #71dd37;"><?= $confirmedCount ?></div>
                        <div class="stat-label">รายงานตัวแล้ว</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
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
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(3, 195, 236, 0.15); color: #03c3ec;">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color: #03c3ec;"><?= $approvedCount ?></div>
                        <div class="stat-label">ผ่านการตรวจสอบ</div>
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
                        <th data-priority="4">รหัส</th>
                        <th data-priority="5">แผนการเรียน</th>
                        <th data-priority="6">สถานะผู้สมัคร</th>
                        <th data-priority="2">สถานะรายงานตัว</th>
                        <th data-priority="3" style="width: 120px;" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php if (!empty($students)) : ?>
                        <?php foreach ($students as $student) : ?>
                            <?php 
                                $isConfirmed = !empty($student->stu_UpdateConfirm);
                                $rStatus = $student->recruit_status ?? 'รอตรวจสอบ';
                                $rClass = ($rStatus == 'ผ่านการตรวจสอบ') ? 'status-approved' : (($rStatus == 'ไม่ผ่าน' || strpos($rStatus, 'ไม่ผ่าน') !== false) ? 'status-rejected' : 'status-pending');
                                
                                // Generate avatar
                                $imgSrc = base_url('image-proxy?file=recruitstudent/m' . ($student->recruit_regLevel ?? '1') . '/img/' . ($student->recruit_img ?? 'default.png'));
                                $defaultImg = base_url('sneat-assets/img/avatars/1.png');
                            ?>
                            <tr data-status="<?= $isConfirmed ? 'confirmed' : 'pending' ?>">
                                <td>
                                    <img src="<?= $imgSrc ?>" class="recruit-avatar" alt="Avatar" loading="lazy" onerror="this.onerror=null;this.src='<?= $defaultImg ?>';">
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= esc($student->recruit_prefix . $student->recruit_firstName) ?></div>
                                    <small class="text-muted"><?= esc($student->recruit_lastName) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary"><?= esc(sprintf('%04d', $student->recruit_id)) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-label-info"><?= esc($student->course_initials ?? $student->recruit_tpyeRoom) ?></span>
                                </td>
                                <td>
                                    <span class="status-badge <?= $rClass ?>"><?= esc($rStatus) ?></span>
                                </td>
                                <td>
                                    <?php if ($isConfirmed) : ?>
                                        <span class="status-badge status-confirmed">
                                            <i class="bx bx-check-double me-1"></i>รายงานตัวแล้ว
                                        </span>
                                        <br><small class="text-muted"><?= esc($student->stu_UpdateConfirm) ?></small>
                                    <?php else : ?>
                                        <span class="status-badge status-pending">
                                            <i class="bx bx-time-five me-1"></i>รอรายงานตัว
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($isConfirmed) : ?>
                                        <a href="<?= site_url('skjadmin/surrender/print/' . $student->recruit_id) ?>" target="_blank" class="action-btn print-btn" data-bs-toggle="tooltip" title="พิมพ์ใบรายงานตัว">
                                            <i class="bx bx-printer"></i>
                                        </a>
                                        <a href="<?= site_url('skjadmin/recruits/view/' . $student->recruit_id) ?>" class="action-btn view-btn" data-bs-toggle="tooltip" title="ดูรายละเอียด">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    <?php else : ?>
                                        <span class="badge bg-label-secondary">รอรายงานตัว</span>
                                    <?php endif; ?>
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
    $(document).ready(function() {
        var table = $('#surrenderTable').DataTable({
            responsive: true,
            stateSave: true,
            pageLength: 15,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });
        
        // Filter buttons functionality
        $('#statusFilter .filter-btn').on('click', function() {
            $('#statusFilter .filter-btn').removeClass('active');
            $(this).addClass('active');
            
            var filterStatus = $(this).data('status');
            
            if (filterStatus === '') {
                // Show all
                $.fn.dataTable.ext.search.pop();
                table.draw();
            } else {
                // Filter by status
                $.fn.dataTable.ext.search.pop();
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var row = table.row(dataIndex).node();
                    var rowStatus = $(row).data('status');
                    return rowStatus === filterStatus;
                });
                table.draw();
            }
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>

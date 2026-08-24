<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<style>
    /* Color Variables - Suankularb Pink & Sky Blue Theme */
    :root {
        --primary-color: #ff6b8b;
        --primary-dark: #e04869;
        --primary-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
        --primary-light: rgba(255, 107, 139, 0.12);
        --secondary-color: #56ccf2;
        --secondary-dark: #0284c7;
    }

    /* Disable Bootstrap auto-backdrop — use #modal-overlay instead */
    #quickViewModal {
        z-index: 1055 !important;
    }
    #modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        z-index: 1050;
        transition: opacity 0.15s linear;
    }
    #modal-overlay.active {
        display: block;
    }

    /* Stats Cards */
    .stat-card {
        border-radius: 16px;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    .stat-card .stat-value {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .stat-card .stat-label {
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Filter Buttons */
    .filter-btn {
        border-radius: 12px;
        padding: 0.45rem 1rem;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        background: #f8fafc;
        color: var(--primary-dark);
        border-color: #cbd5e1;
    }

    .filter-btn.active {
        background: var(--primary-gradient) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 4px 14px rgba(255, 107, 139, 0.3);
    }

    /* Table Styling */
    #surrenderTable {
        border-collapse: separate;
        border-spacing: 0 6px;
    }

    #surrenderTable thead th {
        border: none;
        background: #f8fafc;
        color: #475569;
        padding: 12px 14px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #surrenderTable tbody tr {
        background: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }

    #surrenderTable tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    #surrenderTable tbody td {
        border: none;
        padding: 12px 14px;
        vertical-align: middle;
    }

    #surrenderTable tbody td:first-child {
        border-radius: 12px 0 0 12px;
    }

    #surrenderTable tbody td:last-child {
        border-radius: 0 12px 12px 0;
    }

    /* Action Buttons */
    .action-btn-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        transition: all 0.2s ease;
        border: none;
        font-size: 1.1rem;
    }

    .action-btn-pill:hover {
        transform: scale(1.1);
    }

    /* Avatar in Table */
    .recruit-avatar {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    /* Year Selector */
    .year-selector-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--primary-gradient);
        padding: 8px 18px;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(255, 107, 139, 0.25);
    }

    .year-selector-label {
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .year-selector {
        border: none;
        border-radius: 10px;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 1rem;
        background: white;
        color: #e11d48;
        cursor: pointer;
        outline: none;
    }

    /* Custom Switch for Surrender */
    .form-switch .form-check-input.surrender-switch {
        width: 2.75rem;
        height: 1.4rem;
        cursor: pointer;
    }
    .form-switch .form-check-input.surrender-switch:checked {
        background-color: var(--skj-pink, #e11d48);
        border-color: var(--skj-pink, #e11d48);
    }

    /* Final approval button */
    .btn-final-enrolled {
        border-radius: 10px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
</style>

<!-- Page Header with Year Selector -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: #1e293b;">
            <i class="bx bx-id-card text-primary me-2"></i>
            ข้อมูลการรายงานตัว & มอบตัว
        </h4>
        <p class="text-muted mb-0 small">จัดการตรวจสอบสถานะการรายงานตัวออนไลน์ การมอบตัวที่โรงเรียน และการอนุมัติเป็นนักเรียน</p>
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
$eligibleStudents = array_values(array_filter($students ?? [], function($student) {
    $statusQuiz = $student->recruit_StatusQuiz ?? '';
    $isSurrendered = !empty($student->recruit_statusSurrender ?? '');
    $isFinal = ($student->recruit_statusFinal ?? '') == 'เสร็จสิ้น';
    
    // แสดงเฉพาะผู้ที่ผ่านการคัดเลือก หรือผู้ที่ได้มอบตัว/อนุมัติเป็นนักเรียนแล้ว
    return $statusQuiz == 'ผ่านการคัดเลือก' || $statusQuiz == 'สอบผ่าน' || $isSurrendered || $isFinal;
}));

// Calculate stats
$totalEligible = count($eligibleStudents);
$confirmedCount = 0;
$surrenderedCount = 0;
$finalApprovedCount = 0;

foreach ($eligibleStudents as $student) {
    $isConfirmed = !empty($student->stu_UpdateConfirm);
    if ($isConfirmed) $confirmedCount++;

    $isSurrendered = !empty($student->recruit_statusSurrender);
    if ($isSurrendered) $surrenderedCount++;

    $isFinal = (($student->recruit_statusFinal ?? '') == 'เสร็จสิ้น');
    if ($isFinal) $finalApprovedCount++;
}

$pendingConfirmCount = max(0, $totalEligible - $confirmedCount);
?>

<!-- Stats Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100 bg-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="stat-label d-block mb-1">ผ่านการคัดเลือกทั้งหมด</span>
                        <h3 class="stat-value text-dark mb-0" id="stat_total"><?= number_format($totalEligible) ?></h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                        <i class="bx bx-user-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100 bg-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="stat-label d-block mb-1">รายงานตัวออนไลน์แล้ว</span>
                        <h3 class="stat-value text-success mb-0" id="stat_confirmed"><?= number_format($confirmedCount) ?></h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <i class="bx bx-check-double"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100 bg-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="stat-label d-block mb-1">มอบตัวที่โรงเรียนแล้ว</span>
                        <h3 class="stat-value mb-0" style="color: #ff6b8b;" id="stat_surrendered"><?= number_format($surrenderedCount) ?></h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(255, 107, 139, 0.15); color: #ff6b8b;">
                        <i class="bx bx-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100 bg-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="stat-label d-block mb-1">อนุมัติเป็นนักเรียนแล้ว</span>
                        <h3 class="stat-value text-primary mb-0" id="stat_final"><?= number_format($finalApprovedCount) ?></h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                        <i class="bx bx-id-card"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-3" style="background: var(--primary-light); color: var(--primary-dark);">
                    <i class="bx bx-list-check fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">รายชื่อผู้มีสิทธิ์รายงานตัวและมอบตัว</h5>
                    <small class="text-muted">ปีการศึกษา <?= esc($selected_year) ?></small>
                </div>
            </div>

            <!-- Grade Level Filter Buttons -->
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <div class="btn-group" role="group" id="levelFilter">
                    <button type="button" class="btn filter-btn active" data-level="">ทุกระดับชั้น</button>
                    <button type="button" class="btn filter-btn" data-level="1">ม.1</button>
                    <button type="button" class="btn filter-btn" data-level="4">ม.4</button>
                </div>
            </div>
        </div>

        <!-- Status Filter Toolbar -->
        <div class="d-flex flex-wrap gap-2 mt-3 pt-2 border-top align-items-center justify-content-between">
            <div class="d-flex flex-wrap gap-2" id="statusFilter">
                <button type="button" class="btn filter-btn active" data-status="">
                    ทั้งหมด (<span id="count_all"><?= $totalEligible ?></span>)
                </button>
                <button type="button" class="btn filter-btn" data-status="confirmed">
                    <i class="bx bx-check-double text-success me-1"></i>รายงานตัวออนไลน์แล้ว
                </button>
                <button type="button" class="btn filter-btn" data-status="unconfirmed">
                    <i class="bx bx-time text-warning me-1"></i>ยังไม่รายงานตัว
                </button>
                <button type="button" class="btn filter-btn" data-status="surrendered">
                    <i class="bx bx-receipt text-danger me-1"></i>มอบตัวแล้ว
                </button>
                <button type="button" class="btn filter-btn" data-status="unsurrendered">
                    <i class="bx bx-x text-muted me-1"></i>ยังไม่มอบตัว
                </button>
                <button type="button" class="btn filter-btn" data-status="final_approved">
                    <i class="bx bx-user-check text-primary me-1"></i>อนุมัติเป็นนักเรียนแล้ว
                </button>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle" id="surrenderTable" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center">รูป</th>
                        <th style="min-width: 200px;">ข้อมูลผู้สมัคร</th>
                        <th style="min-width: 180px;">ระดับ / แผนการเรียน</th>
                        <th style="width: 120px;" class="text-center">รายงานตัวออนไลน์</th>
                        <th style="width: 130px;" class="text-center">มอบตัว</th>
                        <th style="width: 130px;" class="text-center">อนุมัติเป็นนักเรียน</th>
                        <th style="width: 110px;" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($eligibleStudents)): ?>
                        <?php foreach ($eligibleStudents as $student): ?>
                            <?php
                            $isConfirmed = !empty($student->stu_UpdateConfirm);
                            $isSurrendered = !empty($student->recruit_statusSurrender ?? '');
                            $isFinalApproved = (($student->recruit_statusFinal ?? '') == 'เสร็จสิ้น');
                            
                            $rQuiz = $student->recruit_StatusQuiz ?? 'ผ่านการคัดเลือก';
                            $imgSrc = get_recruit_file_url($student->recruit_img ?? 'default.png', $student->recruit_regLevel ?? '1', 'img');

                            // Status attributes for filtering
                            $statusFlags = [];
                            if ($isConfirmed) $statusFlags[] = 'confirmed';
                            else $statusFlags[] = 'unconfirmed';

                            if ($isSurrendered) $statusFlags[] = 'surrendered';
                            else $statusFlags[] = 'unsurrendered';

                            if ($isFinalApproved) $statusFlags[] = 'final_approved';

                            $statusString = implode(' ', $statusFlags);
                            $regLevel = $student->recruit_regLevel ?? '1';
                            ?>
                            <tr data-level="<?= esc($regLevel) ?>" data-status="<?= esc($statusString) ?>" id="row_<?= $student->recruit_id ?>">
                                <!-- Avatar -->
                                <td class="text-center">
                                    <img src="<?= $imgSrc ?>" class="recruit-avatar" alt="Avatar" loading="lazy"
                                        onerror="this.onerror=null;this.src='<?= base_url('public/sneat-assets/img/avatars/1.png') ?>';">
                                </td>

                                <!-- Applicant Info -->
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="fw-bold text-dark fs-6"><?= esc($student->recruit_prefix . $student->recruit_firstName . ' ' . $student->recruit_lastName) ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-1 small">
                                        <span class="badge bg-light text-secondary border font-monospace">
                                            <?= esc($student->recruit_idCard ?? '-') ?>
                                        </span>
                                        <?php if (!empty($student->recruit_phone)): ?>
                                            <span class="text-muted small"><i class="bx bx-phone me-1"></i><?= esc($student->recruit_phone) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Grade Level & Major Course -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill <?= $regLevel == '1' ? 'bg-label-primary' : 'bg-label-info' ?> fw-bold">
                                            <?= $regLevel == '1' ? 'ม.1' : 'ม.4' ?>
                                        </span>
                                        <span class="fw-semibold text-dark small">
                                            <?= esc($student->course_initials ?? $student->recruit_tpyeRoom ?? '-') ?>
                                        </span>
                                    </div>
                                    <div class="small text-muted mt-1 text-truncate" style="max-width: 220px;">
                                        <?= esc($student->quota_explain ?? $student->recruit_category ?? '-') ?>
                                    </div>
                                </td>

                                <!-- Confirmation Status (Online) -->
                                <td class="text-center">
                                    <?php if ($isConfirmed): ?>
                                        <span class="badge bg-label-success px-3 py-2 rounded-pill fw-bold" data-bs-toggle="tooltip" title="รายงานตัวออนไลน์เรียบร้อยแล้ว">
                                            <i class="bx bx-check-circle me-1"></i>รายงานตัวแล้ว
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-label-warning px-3 py-2 rounded-pill fw-bold" data-bs-toggle="tooltip" title="ยังไม่ดำเนินการกรอกข้อมูลรายงานตัวออนไลน์">
                                            <i class="bx bx-time-five me-1"></i>รอรายงานตัว
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Surrender Toggle (At School) -->
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input surrender-switch surrender-toggle" type="checkbox" 
                                            data-id="<?= $student->recruit_id ?>" 
                                            id="surrender_chk_<?= $student->recruit_id ?>"
                                            <?= $isSurrendered ? 'checked' : '' ?>
                                            <?= !$isConfirmed ? 'disabled' : '' ?>
                                            data-bs-toggle="tooltip" title="<?= !$isConfirmed ? 'ต้องรายงานตัวออนไลน์ก่อนจึงจะมอบตัวได้' : 'คลิกเพื่อเปลี่ยนสถานะมอบตัว' ?>">
                                    </div>
                                    <div class="surrender-date-badge mt-1">
                                        <?php if ($isSurrendered): ?>
                                            <span class="badge bg-label-secondary font-monospace" style="font-size: 0.68rem;">
                                                <i class="bx bx-calendar me-1"></i><?= date('d/m/y', strtotime($student->recruit_statusSurrender)) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Final Approval Status -->
                                <td class="text-center">
                                    <button type="button" 
                                        class="btn btn-sm btn-final-enrolled final-approve-btn <?= $isFinalApproved ? 'btn-success text-white' : 'btn-outline-secondary' ?>"
                                        id="final_btn_<?= $student->recruit_id ?>"
                                        data-id="<?= $student->recruit_id ?>"
                                        data-status="<?= $isFinalApproved ? '1' : '0' ?>"
                                        <?= !$isSurrendered ? 'disabled' : '' ?>
                                        data-bs-toggle="tooltip" title="<?= $isFinalApproved ? 'เป็นนักเรียนสมบูรณ์แล้ว (คลิกเพื่อยกเลิก)' : 'คลิกเพื่ออนุมัติเป็นนักเรียน' ?>">
                                        <i class="bx <?= $isFinalApproved ? 'bx-check-circle' : 'bx-user-plus' ?> me-1"></i>
                                        <span><?= $isFinalApproved ? 'อนุมัติแล้ว' : 'รออนุมัติ' ?></span>
                                    </button>
                                </td>

                                <!-- Action Buttons -->
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Quick View Modal Button -->
                                        <button type="button" class="action-btn-pill btn btn-sm btn-outline-info" 
                                            onclick="openQuickViewModal(<?= $student->recruit_id ?>)"
                                            data-bs-toggle="tooltip" title="ดูข้อมูลผู้สมัครอย่างละเอียด">
                                            <i class="bx bx-show"></i>
                                        </button>

                                        <!-- Print Confirmation PDF Button -->
                                        <?php if ($isConfirmed): ?>
                                            <a href="<?= site_url('skjadmin/surrender/print/' . $student->recruit_id) ?>"
                                                target="_blank" class="action-btn-pill btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="tooltip" title="พิมพ์ใบรายงานตัว (PDF)">
                                                <i class="bx bx-printer"></i>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="action-btn-pill btn btn-sm btn-outline-secondary opacity-50" disabled 
                                                data-bs-toggle="tooltip" title="พิมพ์ได้เมื่อรายงานตัวออนไลน์แล้ว">
                                                <i class="bx bx-printer"></i>
                                            </button>
                                        <?php endif; ?>
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

<!-- Manual single overlay (replaces Bootstrap auto-backdrop) -->
<div id="modal-overlay"></div>

<!-- Quick View Bootstrap Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" id="quickViewModalContent">
            <!-- Loaded dynamically via AJAX -->
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        var currentLevelFilter = '';
        var currentStatusFilter = '';

        // Clear any saved DataTable state to prevent column mismatch
        localStorage.removeItem('DataTables_surrenderTable_' + window.location.pathname);

        var table = $('#surrenderTable').DataTable({
            responsive: true,
            stateSave: false,
            pageLength: 15,
            order: [], // เรียงตามลำดับข้อมูลจากเซิร์ฟเวอร์
            dom: '<"row align-items-center mb-3"<"col-auto"l><"col"f><"col-auto"B>>rtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="bx bx-download me-1"></i> Excel',
                    className: 'btn btn-success btn-sm fw-bold rounded-3 shadow-sm',
                    title: 'รายงานการรายงานตัวและมอบตัว_ปีการศึกษา_<?= $selected_year ?>',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
                search: "_INPUT_",
                searchPlaceholder: "ค้นหาชื่อ, เลขบัตร, แผนการเรียน..."
            }
        });

        // Combined Filter Function (Level + Status)
        function applyTableFilters() {
            $.fn.dataTable.ext.search.pop(); // Clear previous custom filter
            
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var row = table.row(dataIndex).node();
                if (!row) return true;

                var rowLevel = $(row).data('level') ? $(row).data('level').toString() : '';
                var rowStatuses = $(row).data('status') ? $(row).data('status').toString().split(' ') : [];

                // Filter by Level
                if (currentLevelFilter !== '' && rowLevel !== currentLevelFilter) {
                    return false;
                }

                // Filter by Status
                if (currentStatusFilter !== '' && !rowStatuses.includes(currentStatusFilter)) {
                    return false;
                }

                return true;
            });

            table.draw();
        }

        // Level Filter Click
        $('#levelFilter .filter-btn').on('click', function () {
            $('#levelFilter .filter-btn').removeClass('active');
            $(this).addClass('active');
            currentLevelFilter = $(this).data('level') ? $(this).data('level').toString() : '';
            applyTableFilters();
        });

        // Status Filter Click
        $('#statusFilter .filter-btn').on('click', function () {
            $('#statusFilter .filter-btn').removeClass('active');
            $(this).addClass('active');
            currentStatusFilter = $(this).data('status') || '';
            applyTableFilters();
        });

        // -------------------------------------------------------------
        // In-Place AJAX Surrender Toggle (มอบตัว)
        // -------------------------------------------------------------
        $(document).on('change', '.surrender-toggle', function() {
            var $switch = $(this);
            var id = $switch.attr('data-id');
            var status = $switch.is(':checked') ? 1 : 0;
            var $row = $('#row_' + id);
            var $finalBtn = $('#final_btn_' + id);
            var $dateBadgeContainer = $row.find('.surrender-date-badge');

            if (!id) return;

            // Optimistic UI feedback or disable switch while saving
            $switch.prop('disabled', true);

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
                    $switch.prop('disabled', false);

                    if (res.success) {
                        // Update Data Attributes for Filter
                        var statuses = ($row.data('status') || '').split(' ');
                        statuses = statuses.filter(s => s !== 'surrendered' && s !== 'unsurrendered');
                        statuses.push(status == 1 ? 'surrendered' : 'unsurrendered');
                        $row.data('status', statuses.join(' ')).attr('data-status', statuses.join(' '));

                        // Update Date Badge
                        if (status == 1) {
                            var today = new Date();
                            var d = String(today.getDate()).padStart(2, '0');
                            var m = String(today.getMonth() + 1).padStart(2, '0');
                            var y = String(today.getFullYear() + 543).slice(-2);
                            $dateBadgeContainer.html(`
                                <span class="badge bg-label-secondary font-monospace" style="font-size: 0.68rem;">
                                    <i class="bx bx-calendar me-1"></i>${d}/${m}/${y}
                                </span>
                            `);
                            $finalBtn.prop('disabled', false);
                        } else {
                            $dateBadgeContainer.empty();
                            // If un-surrendered, disable final approval
                            if ($finalBtn.attr('data-status') !== '1') {
                                $finalBtn.prop('disabled', true);
                            }
                        }

                        // Update Counter
                        var currentSurrendered = parseInt($('#stat_surrendered').text().replace(/,/g, '')) || 0;
                        $('#stat_surrendered').text(status == 1 ? currentSurrendered + 1 : Math.max(0, currentSurrendered - 1));

                        // Toast Notification
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: status == 1 ? 'บันทึกมอบตัวสำเร็จ' : 'ยกเลิกการมอบตัวแล้ว'
                        });
                    } else {
                        $switch.prop('checked', !status);
                        Swal.fire('เกิดข้อผิดพลาด', res.message || 'ไม่สามารถปรับปรุงข้อมูลได้', 'error');
                    }
                },
                error: function(xhr) {
                    $switch.prop('disabled', false);
                    $switch.prop('checked', !status);
                    Swal.fire('Error ' + xhr.status, 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                }
            });
        });

        // -------------------------------------------------------------
        // In-Place AJAX Final Student Approval (อนุมัติเป็นนักเรียน)
        // -------------------------------------------------------------
        $(document).on('click', '.final-approve-btn', function() {
            var $btn = $(this);
            var id = $btn.attr('data-id');
            var currentStatus = $btn.attr('data-status');
            var newStatus = (currentStatus == '1') ? 0 : 1;
            var $row = $('#row_' + id);

            Swal.fire({
                title: (newStatus == 1) ? 'ยืนยันการอนุมัติเป็นนักเรียน?' : 'ยกเลิกการอนุมัติ?',
                text: (newStatus == 1) ? "นักเรียนคนนี้จะได้รับการบันทึกสถานะเป็นนักเรียนของโรงเรียนโดยสมบูรณ์" : "ต้องการยกเลิกสถานะนักเรียนใช่หรือไม่?",
                icon: (newStatus == 1) ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonColor: (newStatus == 1) ? '#10b981' : '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: (newStatus == 1) ? '<i class="bx bx-check me-1"></i>อนุมัติ' : '<i class="bx bx-x me-1"></i>ยกเลิกการอนุมัติ',
                cancelButtonText: 'ปิด'
            }).then((result) => {
                if (result.isConfirmed) {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> กำลังบันทึก...');

                    $.ajax({
                        url: '<?= site_url('skjadmin/surrender/update-final') ?>',
                        type: 'POST',
                        data: {
                            recruit_id: id,
                            status: newStatus,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        dataType: 'json',
                        success: function(res) {
                            $btn.prop('disabled', false);

                            if (res.success) {
                                $btn.attr('data-status', newStatus);

                                // Update Button Appearance
                                if (newStatus == 1) {
                                    $btn.removeClass('btn-outline-secondary').addClass('btn-success text-white');
                                    $btn.html('<i class="bx bx-check-circle me-1"></i><span>อนุมัติแล้ว</span>');
                                    $btn.attr('data-bs-original-title', 'เป็นนักเรียนสมบูรณ์แล้ว (คลิกเพื่อยกเลิก)');
                                } else {
                                    $btn.removeClass('btn-success text-white').addClass('btn-outline-secondary');
                                    $btn.html('<i class="bx bx-user-plus me-1"></i><span>รออนุมัติ</span>');
                                    $btn.attr('data-bs-original-title', 'คลิกเพื่ออนุมัติเป็นนักเรียน');
                                }

                                // Update Data Attribute for Filter
                                var statuses = ($row.data('status') || '').split(' ');
                                statuses = statuses.filter(s => s !== 'final_approved');
                                if (newStatus == 1) statuses.push('final_approved');
                                $row.data('status', statuses.join(' ')).attr('data-status', statuses.join(' '));

                                // Update Counter
                                var currentFinal = parseInt($('#stat_final').text().replace(/,/g, '')) || 0;
                                $('#stat_final').text(newStatus == 1 ? currentFinal + 1 : Math.max(0, currentFinal - 1));

                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: newStatus == 1 ? 'อนุมัติเป็นนักเรียนเรียบร้อย' : 'ยกเลิกการอนุมัติแล้ว'
                                });
                            } else {
                                $btn.html(currentStatus == '1' ? '<i class="bx bx-check-circle me-1"></i><span>อนุมัติแล้ว</span>' : '<i class="bx bx-user-plus me-1"></i><span>รออนุมัติ</span>');
                                Swal.fire('เกิดข้อผิดพลาด', res.message || 'ไม่สามารถดำเนินการได้', 'error');
                            }
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false);
                            $btn.html(currentStatus == '1' ? '<i class="bx bx-check-circle me-1"></i><span>อนุมัติแล้ว</span>' : '<i class="bx bx-user-plus me-1"></i><span>รออนุมัติ</span>');
                            Swal.fire('Error ' + xhr.status, 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                        }
                    });
                }
            });
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // -------------------------------------------------------------
    // Quick View Modal
    // -------------------------------------------------------------
    function openQuickViewModal(recruitId) {
        const modalEl = document.getElementById('quickViewModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

        $('#quickViewModalContent').html(`
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="fw-bold text-muted">กำลังโหลดข้อมูลผู้สมัคร...</h6>
            </div>
        `);
        modal.show();

        $.ajax({
            url: '<?= site_url('skjadmin/recruits/quick-view/') ?>' + recruitId,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    $('#quickViewModalContent').html(res.html);
                } else {
                    $('#quickViewModalContent').html(`
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title text-danger">เกิดข้อผิดพลาด</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <p class="text-danger fw-bold mb-0">${res.message || 'ไม่สามารถโหลดข้อมูลได้'}</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#quickViewModalContent').html(`
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-danger">เกิดข้อผิดพลาด</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <p class="text-danger fw-bold mb-0">ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้</p>
                    </div>
                `);
            }
        });
    }

    // Modal Overlay Management
    $(document).on('show.bs.modal', function () {
        $('#modal-overlay').addClass('active');
        $('body').addClass('modal-open');
    });

    $(document).on('hidden.bs.modal', function () {
        if ($('.modal.show').length === 0) {
            $('.modal-backdrop').remove();
            $('#modal-overlay').removeClass('active');
            $('body').removeClass('modal-open').css({ 'overflow': '', 'padding-right': '' });
        }
    });

    $('#modal-overlay').on('click', function () {
        const vm = bootstrap.Modal.getInstance(document.getElementById('quickViewModal'));
        if (vm) vm.hide();
    });
</script>
<?= $this->endSection() ?>
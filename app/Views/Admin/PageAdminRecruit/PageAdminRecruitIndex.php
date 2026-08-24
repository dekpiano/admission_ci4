<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />

<style>
    /* Disable Bootstrap's auto-backdrop entirely — use #modal-overlay instead */
    #quickViewModal, #quickEditModal {
        z-index: 1055 !important;
    }
    #cropModal {
        z-index: 1075 !important;
    }
    /* Single manual overlay shared by all modals */
    #modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        transition: opacity 0.15s linear;
    }
    #modal-overlay.active {
        display: block;
    }

    /* Flatpickr Calendar */
    .flatpickr-calendar {
        z-index: 99999 !important;
    }
    .flatpickr-wrapper {
        display: block !important;
        width: 100% !important;
    }

    /* Select2 Dropdowns */
    .select2-container {
        z-index: 99999 !important;
    }
    .select2-container--open {
        z-index: 99999 !important;
    }
    .select2-dropdown {
        z-index: 99999 !important;
        border-radius: 10px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    .input-group > .select2-container--bootstrap-5 {
        flex: 1 1 auto;
        width: 1% !important;
    }
    .input-group > .select2-container--bootstrap-5 .select2-selection {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        min-height: 31px !important;
        font-size: 0.82rem !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single {
        padding: 2px 8px !important;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple {
        padding: 2px 4px !important;
    }

    /* jQuery.Thailand.js Typeahead Dropdown */
    .tt-menu, .tt-dropdown-menu {
        z-index: 99999 !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        max-height: 220px !important;
        overflow-y: auto !important;
    }
    .tt-suggestion {
        padding: 6px 12px !important;
        font-size: 0.85rem !important;
        cursor: pointer !important;
        color: #1e293b !important;
    }
    .tt-suggestion:hover, .tt-suggestion.tt-cursor {
        background-color: rgba(255, 107, 139, 0.1) !important;
        color: #ff6b8b !important;
    }

    /* Cropper Container */
    .cropper-container {
        direction: ltr;
        font-size: 0;
        line-height: 0;
        position: relative;
        user-select: none;
        z-index: 1095 !important;
    }

    /* Color Variables - Suankularb Pink & Sky Blue Theme */
    :root {
        --primary-color: #ff6b8b;
        --primary-dark: #e04869;
        --secondary-color: #56ccf2;
        --secondary-dark: #249ecd;
        --primary-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
        --primary-light: rgba(255, 107, 139, 0.12);
    }

    /* Stat Cards - Balanced Grid */
    .stat-card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .stat-card.active-filter {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 2px var(--primary-color), 0 8px 20px rgba(255, 107, 139, 0.2) !important;
    }

    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .stat-card .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        line-height: 1.2;
        font-family: 'K2D', sans-serif;
    }

    .stat-card .stat-label {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Main Table Card */
    .main-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    .main-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem 1.25rem;
    }

    /* Filter Toolbar Segmented Controls */
    .filter-btn-group .btn {
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .filter-btn-group .btn.active {
        background: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(255, 107, 139, 0.35);
    }

    /* Table Styling */
    #recruitsTable {
        width: 100% !important;
        border-collapse: collapse;
    }

    #recruitsTable thead th {
        background: #f8fafc;
        color: #0f172a;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.3px;
        padding: 12px 14px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    #recruitsTable tbody tr {
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    #recruitsTable tbody tr:hover {
        background-color: #fff9fa !important;
    }

    #recruitsTable tbody td {
        padding: 10px 14px;
        vertical-align: middle;
        font-size: 0.86rem;
    }

    /* Avatar in Table */
    .recruit-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #f1f5f9;
        background-color: #f8fafc;
    }

    .recruit-name-link {
        color: #0f172a;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .recruit-name-link:hover {
        color: var(--primary-color) !important;
    }

    /* Status Badges */
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .status-approved {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Level Badges */
    .badge-level-m1 {
        background: rgba(255, 107, 139, 0.15);
        color: #ff6b8b;
        font-weight: 800;
        border: 1px solid rgba(255, 107, 139, 0.3);
        font-size: 0.74rem;
        padding: 2px 7px;
    }

    .badge-level-m4 {
        background: rgba(86, 204, 242, 0.15);
        color: #0284c7;
        font-weight: 800;
        border: 1px solid rgba(86, 204, 242, 0.3);
        font-size: 0.74rem;
        padding: 2px 7px;
    }

    /* Year Selector */
    .year-selector-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--primary-gradient);
        padding: 6px 14px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(255, 107, 139, 0.3);
    }

    .year-selector-label {
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }

    .year-selector {
        border: none;
        border-radius: 8px;
        padding: 4px 10px;
        font-weight: 700;
        font-size: 0.95rem;
        background: #ffffff;
        color: var(--primary-color);
        cursor: pointer;
    }

    /* Action Buttons in Row */
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-icon:hover {
        transform: translateY(-1px);
    }

    /* DataTables Custom Controls */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px !important;
        padding: 0.35rem 0.75rem !important;
        border: 1.5px solid #cbd5e1 !important;
        font-size: 0.85rem;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--secondary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(86, 204, 242, 0.25) !important;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px !important;
        padding: 0.35rem 1.75rem 0.35rem 0.75rem !important;
        border: 1.5px solid #cbd5e1 !important;
        font-size: 0.85rem;
    }
</style>

<!-- Page Header with Year Selector & Quick Actions -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center">
            <i class="bx bx-user-pin text-primary me-2 fs-3"></i>
            จัดการข้อมูลผู้สมัคร
        </h4>
        <p class="text-muted mb-0 small">ตรวจสอบเอกสาร ผลการคัดเลือก และพิมพ์รายงานการรับสมัคร</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="<?= site_url('skjadmin/recruits/register') ?>" class="btn btn-primary d-flex align-items-center" style="border-radius: 10px; font-weight: 700; padding: 7px 16px;">
            <i class="bx bx-plus-circle me-1 fs-5"></i> ลงทะเบียน Walk-in
        </a>
        <div class="year-selector-wrapper">
            <label class="year-selector-label mb-0">
                <i class="bx bx-calendar"></i> ปี:
            </label>
            <select name="year" id="year" class="form-select year-selector">
                <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<!-- Stats Cards Row (5 Balanced Pillars) -->
<div class="row g-3 mb-4" id="statsRow">
    <div class="col-6 col-md-4 col-xl">
        <div class="card stat-card h-100 cursor-pointer active-filter" data-filter="">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255, 107, 139, 0.15); color: #ff6b8b;">
                        <i class="bx bx-user"></i>
                    </div>
                    <div>
                        <div class="stat-value text-primary" id="totalCount">-</div>
                        <div class="stat-label">ผู้สมัครทั้งหมด</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card stat-card h-100 cursor-pointer" data-filter="ผ่านการตรวจสอบ">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value text-success" id="approvedCount">-</div>
                        <div class="stat-label">ผ่านการตรวจสอบ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card stat-card h-100 cursor-pointer" data-filter="รอการตรวจสอบ">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                        <i class="bx bx-time-five"></i>
                    </div>
                    <div>
                        <div class="stat-value text-warning" id="pendingCount">-</div>
                        <div class="stat-label">รอการตรวจสอบ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card stat-card h-100 cursor-pointer" data-filter="ไม่ผ่าน">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                        <i class="bx bx-x-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value text-danger" id="rejectedCount">-</div>
                        <div class="stat-label">ไม่ผ่านการตรวจ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card stat-card h-100 cursor-pointer" id="passedSelectionCard">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(86, 204, 242, 0.15); color: #0284c7;">
                        <i class="bx bx-trophy"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color: #0284c7;" id="passedSelectionCount">-</div>
                        <div class="stat-label">ผ่านการคัดเลือก</div>
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
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                    <i class="bx bx-list-ul text-primary me-2"></i>
                    รายชื่อผู้สมัคร
                </h5>
            </div>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Status Filter Buttons -->
                <div class="btn-group filter-btn-group" role="group" id="statusFilter">
                    <button type="button" class="btn btn-outline-secondary active" data-status="">ทั้งหมด</button>
                    <button type="button" class="btn btn-outline-success" data-status="ผ่านการตรวจสอบ"><i class="bx bx-check-circle me-1"></i>ผ่าน</button>
                    <button type="button" class="btn btn-outline-warning" data-status="รอการตรวจสอบ"><i class="bx bx-time-five me-1"></i>รอตรวจ</button>
                    <button type="button" class="btn btn-outline-danger" data-status="ไม่ผ่าน"><i class="bx bx-x-circle me-1"></i>ไม่ผ่าน</button>
                </div>

                <!-- Round Filter (Year >= 2569) -->
                <?php if ($selected_year >= 2569): ?>
                <div class="d-flex align-items-center gap-1 ms-1">
                    <select id="roundFilter" class="form-select form-select-sm" style="width: 105px; border-radius: 8px; font-weight: 600;">
                        <option value="">ทุกรอบ</option>
                        <?php if(isset($rounds)): ?>
                            <?php foreach($rounds as $r): ?>
                                <option value="<?= $r ?>">รอบที่ <?= $r ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="1">รอบที่ 1</option>
                            <option value="2">รอบที่ 2</option>
                        <?php endif; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="recruitsTable">
                <thead>
                    <tr>
                        <th style="min-width: 220px;">ข้อมูลผู้สมัคร (ID / ชื่อ-สกุล)</th>
                        <th style="width: 130px;">ระดับ / โควตา</th>
                        <th style="min-width: 180px;">อันดับแผนการเรียน</th>
                        <th style="width: 140px;" class="text-center">สถานะเอกสาร</th>
                        <th style="width: 145px;" class="text-center">ผลการคัดเลือก</th>
                        <th style="width: 110px;" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data is loaded via AJAX -->
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

<!-- Quick Edit Bootstrap Modal -->
<div class="modal fade" id="quickEditModal" tabindex="-1" aria-labelledby="quickEditModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" id="quickEditModalContent">
            <!-- Loaded dynamically via AJAX -->
        </div>
    </div>
</div>

<!-- Crop Image Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, rgba(255, 107, 139, 0.08) 0%, rgba(86, 204, 242, 0.08) 100%);">
                <h5 class="modal-title fw-bold text-dark mb-0">
                    <i class="bx bx-crop text-primary me-2"></i>ครอบตัดรูปถ่ายผู้สมัคร (สัดส่วน 3:4)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center" style="background: #1e293b;">
                <div style="max-height: 420px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <img id="image_to_crop" src="" alt="Image to crop" style="max-width: 100%; max-height: 420px; display: block;">
                </div>
            </div>
            <div class="modal-footer border-top d-flex justify-content-between p-3" style="background: #f8fafc;">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-sm btn-primary px-4 fw-bold" id="crop_btn" style="background: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%); border: none;">
                    <i class="bx bx-check me-1"></i>ยืนยันการครอบรูป
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<!-- Thailand Auto-complete JS -->
<script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    $(document).ready(function () {
        var currentStatusFilter = '';

        // Clear any saved DataTable state to prevent column mismatch errors
        localStorage.removeItem('DataTables_recruitsTable_' + window.location.pathname);

        var table = $('#recruitsTable').DataTable({
            stateSave: false,
            responsive: true,
            processing: true,
            serverSide: false,
            pageLength: 15,
            deferRender: true,
            order: [[0, 'desc']], // เรียงลำดับล่าสุดก่อนเสมอ
            dom: '<"row align-items-center mb-3"<"col-auto"l><"col"f><"col-auto"B>>rtip',
            buttons: [
                {
                    text: '<i class="bx bx-download me-1"></i> Excel',
                    className: 'btn btn-success btn-sm fw-bold',
                    action: function (e, dt, node, config) {
                        var params = new URLSearchParams({
                            year: $('#year').val(),
                            status_filter: currentStatusFilter || '',
                            round_filter: $('#roundFilter').val() || '',
                            search: dt.search() || '',
                            format: 'excel'
                        });
                        window.location.href = '<?= site_url('skjadmin/recruits/export') ?>?' + params.toString();
                    }
                },
                {
                    text: '<i class="bx bx-file me-1"></i> CSV',
                    className: 'btn btn-outline-success btn-sm fw-bold',
                    action: function (e, dt, node, config) {
                        var params = new URLSearchParams({
                            year: $('#year').val(),
                            status_filter: currentStatusFilter || '',
                            round_filter: $('#roundFilter').val() || '',
                            search: dt.search() || '',
                            format: 'csv'
                        });
                        window.location.href = '<?= site_url('skjadmin/recruits/export') ?>?' + params.toString();
                    }
                }
            ],
            ajax: {
                url: '<?= site_url('skjadmin/recruits/ajax-all') ?>',
                type: 'POST',
                data: function (d) {
                    d.year = $('#year').val();
                    d.status_filter = currentStatusFilter;
                    d.round_filter = $('#roundFilter').length ? $('#roundFilter').val() : '';
                    d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                },
                dataSrc: 'data'
            },
            columns: [
                { data: 'applicant', searchable: true },
                { data: 'level_quota', searchable: true },
                { data: 'course', searchable: true },
                { data: 'status', searchable: true },
                { data: 'selection_result', orderable: false, searchable: false },
                { data: 'actions', orderable: false, searchable: false }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
                "processing": '<div class="text-center my-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-dark fw-bold">กำลังโหลดข้อมูลผู้สมัคร...</p></div>'
            },
            "drawCallback": function (settings) {
                updateStats();
            }
        });

        // Reload table when year is changed
        $('#year').on('change', function () {
            window.location.href = '<?= site_url('skjadmin/recruits?year=') ?>' + $(this).val();
        });

        // Reload table when round filter is changed
        $('#roundFilter').on('change', function () {
            table.ajax.reload();
        });

        // Status filter toolbar buttons
        $('#statusFilter .btn').on('click', function () {
            $('#statusFilter .btn').removeClass('active');
            $(this).addClass('active');
            currentStatusFilter = $(this).data('status');

            // Sync stat cards highlight
            $('.stat-card').removeClass('active-filter');
            $('.stat-card[data-filter="' + currentStatusFilter + '"]').addClass('active-filter');

            table.ajax.reload();
        });

        // Stats card click filter
        $('.stat-card[data-filter]').on('click', function () {
            var filterStatus = $(this).data('filter');
            currentStatusFilter = filterStatus;

            $('.stat-card').removeClass('active-filter');
            $(this).addClass('active-filter');

            // Update active button
            $('#statusFilter .btn').removeClass('active');
            $('#statusFilter .btn[data-status="' + filterStatus + '"]').addClass('active');

            table.ajax.reload();
        });

        // Update statistics counters
        function updateStats() {
            $.ajax({
                url: '<?= site_url('skjadmin/recruits/stats') ?>',
                type: 'POST',
                data: {
                    year: $('#year').val(),
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function (response) {
                    if (response) {
                        $('#totalCount').text(response.total || 0);
                        $('#approvedCount').text(response.approved || 0);
                        $('#passedSelectionCount').text(response.passedSelection || 0);
                        $('#pendingCount').text(response.pending || 0);
                        $('#rejectedCount').text(response.rejected || 0);
                    }
                }
            });
        }

        // Initial stats load
        updateStats();

        // Sport Result Dropdown Change Handler
        $(document).on('change', '.sport-result-select', function () {
            var $select = $(this);
            var recruitId = $select.data('id');
            var newResult = $select.val();
            var originalValue = $select.data('original') || $select.find('option:selected').val();

            $select.prop('disabled', true);

            $.ajax({
                url: '<?= site_url('skjadmin/recruits/update-sport-result') ?>',
                type: 'POST',
                data: {
                    id: recruitId,
                    result: newResult,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'อัปเดตผลคัดเลือกสำเร็จ',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        table.ajax.reload(null, false);
                    } else {
                        $select.val(originalValue);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function () {
                    $select.val(originalValue);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                complete: function () {
                    $select.prop('disabled', false);
                }
            });
        });

        // Quiz Result Dropdown Change Handler
        $(document).on('change', '.quiz-result-select', function () {
            var $select = $(this);
            var recruitId = $select.data('id');
            var newResult = $select.val();
            var originalValue = $select.data('original') || $select.find('option:selected').val();

            $select.prop('disabled', true);

            $.ajax({
                url: '<?= site_url('skjadmin/recruits/update-quiz-result') ?>',
                type: 'POST',
                data: {
                    id: recruitId,
                    result: newResult,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'อัปเดตผลสอบสำเร็จ',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        table.ajax.reload(null, false);
                    } else {
                        $select.val(originalValue);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function () {
                    $select.val(originalValue);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                complete: function () {
                    $select.prop('disabled', false);
                }
            });
        });
    });

    // Delete confirmation
    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลผู้สมัครนี้?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('skjadmin/recruits/delete/') ?>' + id;
            }
        });
    }

    // ==========================================
    // Bootstrap 5 Modal: Quick View & Fast Verification
    // ==========================================
    function openQuickViewModal(recruitId) {
        // If edit modal is open, close it first
        const editModalEl = document.getElementById('quickEditModal');
        const editModal = bootstrap.Modal.getInstance(editModalEl);
        if (editModal) {
            editModal.hide();
        }

        const viewModalEl = document.getElementById('quickViewModal');
        const viewModal = bootstrap.Modal.getOrCreateInstance(viewModalEl);
        
        $('#quickViewModalContent').html(`
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="fw-bold text-muted">กำลังโหลดข้อมูลผู้สมัคร...</h6>
            </div>
        `);
        viewModal.show();

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
                        <h5 class="modal-title text-danger">ข้อผิดพลาด</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <p class="text-danger fw-bold mb-0">ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้</p>
                    </div>
                `);
            }
        });
    }

    // Quick Status Change (Approved / Pending)
    function handleQuickStatusChange(recruitId, status) {
        $.ajax({
            url: '<?= site_url('skjadmin/recruits/update-status') ?>',
            type: 'POST',
            data: {
                id: recruitId,
                status: status,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {
                    openQuickViewModal(recruitId);
                    $('#recruitsTable').DataTable().ajax.reload(null, false);
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'อัปเดตสถานะเป็น: ' + status
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: response.message || 'ไม่สามารถอัปเดตสถานะได้' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้' });
            }
        });
    }

    // Quick Status Reject (with Reason Prompt)
    function handleQuickStatusReject(recruitId) {
        Swal.fire({
            title: '<span style="color: #e11d48; font-size: 1.15rem;"><i class="bx bx-x-circle me-1"></i>ระบุเหตุผลที่ไม่ผ่านการตรวจสอบ</span>',
            input: 'textarea',
            inputPlaceholder: 'กรุณาระบุเหตุผล เช่น รูปถ่ายไม่ชัดเจน, เอกสาร ปพ.1 ไม่ครบถ้วน...',
            inputAttributes: {
                'aria-label': 'ระบุเหตุผลที่ไม่ผ่านการตรวจสอบ',
                'style': 'font-size: 0.9rem; border-radius: 10px;'
            },
            showCancelButton: true,
            confirmButtonText: 'บันทึกสถานะไม่ผ่าน',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            inputValidator: (value) => {
                if (!value || !value.trim()) {
                    return 'กรุณาระบุเหตุผลที่ไม่ผ่านการตรวจสอบ!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value.trim();
                handleQuickStatusChange(recruitId, 'ไม่ผ่านการตรวจสอบ: ' + reason);
            }
        });
    }

    // ==========================================
    // Bootstrap 5 Modal: Quick Edit
    // ==========================================
    function openQuickEditModal(recruitId) {
        // If view modal is open, close it first
        const viewModalEl = document.getElementById('quickViewModal');
        const viewModal = bootstrap.Modal.getInstance(viewModalEl);
        if (viewModal) {
            viewModal.hide();
        }

        const editModalEl = document.getElementById('quickEditModal');
        const editModal = bootstrap.Modal.getOrCreateInstance(editModalEl);
        
        $('#quickEditModalContent').html(`
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="fw-bold text-muted">กำลังโหลดแบบฟอร์มแก้ไข...</h6>
            </div>
        `);
        editModal.show();

        $.ajax({
            url: '<?= site_url('skjadmin/recruits/quick-edit/') ?>' + recruitId,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    $('#quickEditModalContent').html(res.html);
                    initQuickEditComponents(res.courses, res.major_order_ids, res.recruit);
                } else {
                    $('#quickEditModalContent').html(`
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title text-danger">เกิดข้อผิดพลาด</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <p class="text-danger fw-bold mb-0">${res.message || 'ไม่สามารถโหลดฟอร์มแก้ไขได้'}</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#quickEditModalContent').html(`
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-danger">ข้อผิดพลาด</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <p class="text-danger fw-bold mb-0">ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้</p>
                    </div>
                `);
            }
        });
    }

    function initQuickEditComponents(coursesData, currentMajorOrder, recruitData) {
        const modalContainer = $('#quickEditModal');

        // 1. Flatpickr Thai Buddhist Era Calendar
        flatpickr("#quick_recruit_birthday", {
            locale: "th",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            allowInput: true,
            disableMobile: "true",
            static: true,
            onReady: function(d, s, fp) { if (fp.currentYearElement) fp.currentYearElement.value = fp.currentYear + 543; },
            onYearChange: function(d, s, fp) { if (fp.currentYearElement) fp.currentYearElement.value = fp.currentYear + 543; },
            onMonthChange: function(d, s, fp) { if (fp.currentYearElement) fp.currentYearElement.value = fp.currentYear + 543; },
            onOpen: function(d, s, fp) { if (fp.currentYearElement) fp.currentYearElement.value = fp.currentYear + 543; },
            formatDate: (date, format, locale) => {
                const year = date.getFullYear() + 543;
                const month = locale.months.longhand[date.getMonth()];
                const day = date.getDate();
                if (format === "j F Y") { return `${day} ${month} ${year}`; }
                return flatpickr.formatDate(date, format, locale);
            }
        });

        // 2. Select2 Tags for Nationality, Race, Religion
        if ($.fn.select2) {
            $('.quick-select2-tags').select2({
                theme: 'bootstrap-5',
                width: '100%',
                tags: true,
                dropdownParent: modalContainer,
                placeholder: '-- เลือกหรือพิมพ์เพื่อเพิ่ม --'
            });
        }

        // 3. School Search via Select2 AJAX (Search database)
        if ($.fn.select2) {
            $('#quick_recruit_oldSchool_select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --',
                allowClear: true,
                dropdownParent: modalContainer,
                ajax: {
                    url: '<?= base_url('new-admission/school-search') ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });

            $('#quick_recruit_oldSchool_select').on('select2:select', function (e) {
                var data = e.params.data;
                $('#quick_recruit_oldSchool').val(data.text); 
                $('#quick_recruit_district').val(data.amphur); 
                $('#quick_recruit_province').val(data.province);
            });
        }

        // 4. Thailand Auto-complete Address
        if ($.Thailand) {
            $.Thailand({
                $district: $('#quick_recruit_homeSubdistrict'),
                $amphoe: $('#quick_recruit_homedistrict'),
                $province: $('#quick_recruit_homeProvince'),
                $zipcode: $('#quick_recruit_homePostcode'),
            });
        }

        // 5. ID Card auto format
        $('#quick_recruit_idCard').on('input', function(e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
            e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
        }).trigger('input');

        // 6. Cropper.js for Student Photo (Aspect Ratio 3:4)
        let cropper = null;

        $('#quick_recruit_img').off('change').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_to_crop').attr('src', e.target.result);
                    // Hide Edit Modal first (Bootstrap 5 best practice for multiple modals)
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('quickEditModal'));
                    if (editModal) editModal.hide();
                    
                    // Delay slightly to ensure backdrop transitions don't conflict
                    setTimeout(() => {
                        const cropModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cropModal'));
                        cropModal.show();
                    }, 400);
                };
                reader.readAsDataURL(file);
            }
        });

        $('#cropModal').off('shown.bs.modal').on('shown.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropper = new Cropper(document.getElementById('image_to_crop'), {
                aspectRatio: 3 / 4,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
                background: false
            });
        }).off('hidden.bs.modal').on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            // Clear file input so the same file can be selected again
            $('#quick_recruit_img').val('');
            
            // Re-open Edit Modal
            setTimeout(() => {
                const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('quickEditModal'));
                editModal.show();
            }, 400);
        });

        $('#crop_btn').off('click').on('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 450,
                    height: 600
                });
                const b64 = canvas.toDataURL('image/jpeg', 0.9);
                $('#quick_preview_avatar').attr('src', b64);
                $('#quick_recruit_img_cropped').val(b64);
                bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
            }
        });

        // 7. Course dropdown updater
        function updateQuickCourses(level) {
            const quotaOption = $('#quick_recruit_category option:selected');
            const allowedCoursesStr = quotaOption.data('courses') || '';
            const allowedCourses = allowedCoursesStr.split('|').filter(id => id !== '');
            const gradeLevel = (level == '1') ? 'ม.ต้น' : 'ม.ปลาย';

            $('.quick-course-select').each(function(index) {
                const $select = $(this);
                const currentValue = $select.val() || (currentMajorOrder[index] || '');
                $select.empty();

                const placeholder = $select.attr('id') === 'quick_recruit_tpyeRoom1' ? '-- เลือกอันดับ 1 (หลัก) --' : '-- ไม่ระบุ --';
                $select.append(`<option value="" ${$select.prop('required') ? 'disabled' : ''}>${placeholder}</option>`);

                const filtered = coursesData.filter(c => {
                    return c.course_gradelevel === gradeLevel && (allowedCourses.length === 0 || allowedCourses.includes(c.course_id.toString()));
                });

                filtered.forEach(c => {
                    const isSelected = (c.course_id == currentValue) ? 'selected' : '';
                    const courseDisplayName = c.course_initials ? c.course_initials : (c.course_branch || c.course_fullname);
                    $select.append(`<option value="${c.course_id}" data-branch="${c.course_branch || ''}" ${isSelected}>${courseDisplayName}</option>`);
                });

                $select.val(currentValue).trigger('change');
            });

            updateQuickSportVisibility();
        }

        function updateQuickSportVisibility() {
            const selectedOption = $('#quick_recruit_category option:selected');
            const isQuotaSport = selectedOption.data('key') === 'sport' || selectedOption.text().includes('กีฬา');
            const selectedCourse1Id = $('#quick_recruit_tpyeRoom1').val();
            const selectedCourse1Obj = coursesData.find(c => c.course_id == selectedCourse1Id);
            const isCourseSport = selectedCourse1Obj && (
                (selectedCourse1Obj.course_fullname && selectedCourse1Obj.course_fullname.includes('กีฬา')) ||
                (selectedCourse1Obj.course_branch && selectedCourse1Obj.course_branch.includes('กีฬา'))
            );
            // Also check if course_age exists (sport-type course)
            const isCourseWithAge = selectedCourse1Obj && selectedCourse1Obj.course_age && selectedCourse1Obj.course_age.toString().trim() !== '';

            if (isQuotaSport || isCourseSport || isCourseWithAge) {
                $('#quick_rank2_container, #quick_rank3_container').hide();
                $('#quick_recruit_tpyeRoom2, #quick_recruit_tpyeRoom3').val('');
                $('#quick_sport_age_section').show();
                $('#quick_sport_extra_fields').show();

                // Set sport & parent fields as required
                $('#quick_recruit_sportPosition, #quick_recruit_nickname, #quick_recruit_weight, #quick_recruit_height, #quick_recruit_fatherName, #quick_recruit_fatherJob, #quick_recruit_motherName, #quick_recruit_motherJob').prop('required', true);

                const container = $('#quick_age_radio_container').empty();
                const hiddenInput = $('#quick_recruit_agegroup');
                const savedAge = hiddenInput.val() || (recruitData.recruit_agegroup || '');

                let ages = [];
                if (selectedCourse1Obj && selectedCourse1Obj.course_age) {
                    ages = selectedCourse1Obj.course_age.toString().split(',').map(a => a.trim()).filter(a => a);
                }
                if (ages.length === 0) ages = ['12', '13', '14', '15', '16', '17', '18'];

                ages.forEach(age => {
                    const isChecked = (age == savedAge) ? 'checked' : '';
                    const radio = $(`
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="quick_age_radio" id="qage_${age}" value="${age}" ${isChecked} required>
                            <label class="form-check-label small" for="qage_${age}">${age} ปี</label>
                        </div>
                    `);
                    radio.find('input').on('change', function() { hiddenInput.val(this.value); });
                    container.append(radio);
                });
            } else {
                $('#quick_rank2_container, #quick_rank3_container').show();
                $('#quick_sport_age_section').hide();
                $('#quick_sport_extra_fields').hide();

                // Remove required from sport & parent fields
                $('#quick_recruit_sportPosition, #quick_recruit_nickname, #quick_recruit_weight, #quick_recruit_height, #quick_recruit_fatherName, #quick_recruit_fatherJob, #quick_recruit_motherName, #quick_recruit_motherJob').prop('required', false);
                $('#quick_age_radio_container').empty();
            }
        }

        $('#quick_recruit_regLevel, #quick_recruit_category').on('change', function() {
            updateQuickCourses($('#quick_recruit_regLevel').val());
        });

        $('.quick-course-select').on('change', function() {
            const currentId = $(this).attr('id');
            const val = $(this).val();
            if (!val) return;

            let duplicated = false;
            $('.quick-course-select').each(function() {
                if ($(this).attr('id') !== currentId && $(this).val() == val) { duplicated = true; }
            });

            if (duplicated) {
                Swal.fire({
                    icon: 'warning',
                    title: 'เลือกอันดับซ้ำ',
                    text: 'ท่านได้เลือกแผนการเรียนนี้ไปแล้วในอันดับอื่น'
                });
                $(this).val('').trigger('change');
                return;
            }

            if (currentId === 'quick_recruit_tpyeRoom1') {
                updateQuickSportVisibility();
                const selectedBranch = $(this).find('option:selected').data('branch');
                const selectedText = $(this).find('option:selected').text();
                if (selectedBranch && selectedBranch.trim() !== '') {
                    $('#quick_recruit_major').val(selectedBranch);
                } else if (selectedText && !selectedText.includes('--')) {
                    $('#quick_recruit_major').val(selectedText);
                }
            }
        });

        // Initial setup for courses
        updateQuickCourses($('#quick_recruit_regLevel').val());

        // 8. Form Submission via AJAX FormData
        $('#quickEditForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const submitBtn = $(form).find('button[type="submit"]');

            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> กำลังบันทึก...');

            $.ajax({
                url: '<?= site_url('skjadmin/recruits/update/') ?>' + recruitData.recruit_id,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Close edit modal
                        const editModal = bootstrap.Modal.getInstance(document.getElementById('quickEditModal'));
                        if (editModal) {
                            editModal.hide();
                        }

                        // Refresh table in-place
                        $('#recruitsTable').DataTable().ajax.reload(null, false);
                        
                        // Show success toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'บันทึกการแก้ไขข้อมูลสำเร็จ'
                        });

                        // Reopen quick view modal
                        openQuickViewModal(recruitData.recruit_id);
                    } else {
                        submitBtn.prop('disabled', false).html('<i class="bx bx-save me-1"></i>บันทึกการแก้ไข');
                        Swal.fire({
                            icon: 'error',
                            title: 'บันทึกไม่สำเร็จ',
                            text: response.message || 'โปรดตรวจสอบความถูกต้องของข้อมูล'
                        });
                    }
                },
                error: function() {
                    submitBtn.prop('disabled', false).html('<i class="bx bx-save me-1"></i>บันทึกการแก้ไข');
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                    });
                }
            });
        });
    }

    // ---------- Manual overlay management ----------
    // Show overlay whenever ANY modal opens; hide when ALL modals close
    $(document).on('show.bs.modal', function () {
        $('#modal-overlay').addClass('active');
        $('body').addClass('modal-open');
    });
    $(document).on('hidden.bs.modal', function () {
        if ($('.modal.show').length === 0) {
            // Force-remove any stray Bootstrap backdrops (safety net)
            $('.modal-backdrop').remove();
            $('#modal-overlay').removeClass('active');
            $('body').removeClass('modal-open').css({ 'overflow': '', 'padding-right': '' });
        }
    });

    // Close quickViewModal when clicking the overlay
    $('#modal-overlay').on('click', function () {
        const vm = bootstrap.Modal.getInstance(document.getElementById('quickViewModal'));
        if (vm) vm.hide();
    });
</script>
<?= $this->endSection() ?>
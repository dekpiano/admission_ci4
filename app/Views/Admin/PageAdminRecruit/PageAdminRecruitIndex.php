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
    #recruitsTable {
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    #recruitsTable thead th {
        border: none;
        background: var(--primary-gradient);
        color: white;
        padding: 14px 16px;
        font-weight: 600;
    }
    #recruitsTable thead th:first-child {
        border-radius: 10px 0 0 10px;
    }
    #recruitsTable thead th:last-child {
        border-radius: 0 10px 10px 0;
    }
    #recruitsTable tbody tr {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
    }
    #recruitsTable tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
    }
    #recruitsTable tbody td {
        border: none;
        padding: 12px 16px;
        vertical-align: middle;
    }
    #recruitsTable tbody td:first-child {
        border-radius: 10px 0 0 10px;
    }
    #recruitsTable tbody td:last-child {
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
    .action-btn.view-btn {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }
    .action-btn.view-btn:hover {
        background: #28a745;
        color: white;
    }
    .action-btn.edit-btn {
        background: rgba(32, 201, 151, 0.15);
        color: #20c997;
    }
    .action-btn.edit-btn:hover {
        background: #20c997;
        color: white;
    }
    .action-btn.print-btn {
        background: rgba(3, 195, 236, 0.15);
        color: #03c3ec;
    }
    .action-btn.print-btn:hover {
        background: #03c3ec;
        color: white;
    }
    .action-btn.delete-btn {
        background: rgba(255, 62, 29, 0.15);
        color: #ff3e1d;
    }
    .action-btn.delete-btn:hover {
        background: #ff3e1d;
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
    
    /* Status Badge - Green for pending */
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .status-pending { background: rgba(40, 167, 69, 0.15); color: #28a745; }
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
            <i class="bx bx-user-pin text-primary me-2"></i>
            ข้อมูลผู้สมัคร
        </h4>
        <p class="text-muted mb-0">จัดการข้อมูลผู้สมัครเข้าศึกษาต่อ</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="year-selector-wrapper">
            <label class="year-selector-label">
                <i class="bx bx-calendar"></i>
                ปีการศึกษา
            </label>
            <select name="year" id="year" class="form-select year-selector">
                <?php foreach ($years as $y) : ?>
                    <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-4 mb-4" id="statsRow">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(105, 108, 255, 0.15); color: #696cff;">
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
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100 cursor-pointer" data-filter="ผ่านการตรวจสอบ">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(113, 221, 55, 0.15); color: #71dd37;">
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
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100 cursor-pointer" data-filter="รอตรวจสอบ">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255, 171, 0, 0.15); color: #ffab00;">
                        <i class="bx bx-time-five"></i>
                    </div>
                    <div>
                        <div class="stat-value text-warning" id="pendingCount">-</div>
                        <div class="stat-label">รอตรวจสอบ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100 cursor-pointer" data-filter="ไม่ผ่านการตรวจสอบ">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255, 62, 29, 0.15); color: #ff3e1d;">
                        <i class="bx bx-x-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value text-danger" id="rejectedCount">-</div>
                        <div class="stat-label">ไม่ผ่านการตรวจสอบ</div>
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
                รายการผู้สมัคร
            </h5>
            <!-- Filter Buttons -->
            <div class="btn-group" role="group" id="statusFilter">
                <button type="button" class="btn filter-btn btn-outline-primary active" data-status="">
                    ทั้งหมด
                </button>
                <button type="button" class="btn filter-btn btn-outline-success" data-status="ผ่านการตรวจสอบ">
                    <i class="bx bx-check-circle me-1"></i> ผ่าน
                </button>
                <button type="button" class="btn filter-btn btn-outline-warning" data-status="รอตรวจสอบ">
                    <i class="bx bx-time-five me-1"></i> รอ
                </button>
                <button type="button" class="btn filter-btn btn-outline-danger" data-status="ไม่ผ่าน">
                    <i class="bx bx-x-circle me-1"></i> ไม่ผ่าน
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover" id="recruitsTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">รูป</th>
                        <th data-priority="1">ชื่อ - นามสกุล</th>
                        <th data-priority="4">รหัส</th>
                        <th data-priority="5">หลักสูตร</th>
                        <th data-priority="2">สถานะ</th>
                        <th data-priority="3" style="width: 180px;" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <!-- Data is loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        var currentStatusFilter = '';
        
        var table = $('#recruitsTable').DataTable({
            stateSave: true,
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 15,
            ajax: {
                url: '<?= site_url('skjadmin/recruits/ajax') ?>',
                type: 'POST',
                data: function(d) {
                    d.year = $('#year').val();
                    d.status_filter = currentStatusFilter;
                    d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                }
            },
            columns: [
                { data: 'avatar', orderable: false, searchable: false },
                { data: 'name' },
                { data: 'recruit_id' },
                { data: 'course' },
                { data: 'status' },
                { data: 'actions', orderable: false, searchable: false }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
                "processing": '<div class="text-center my-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">กำลังโหลดข้อมูล...</p></div>'
            },
            "drawCallback": function(settings) {
                // Update stats after draw
                updateStats();
            }
        });

        // Reload table when year is changed
        $('#year').on('change', function() {
            table.ajax.reload();
        });
        
        // Status filter buttons
        $('#statusFilter .filter-btn').on('click', function() {
            $('#statusFilter .filter-btn').removeClass('active');
            $(this).addClass('active');
            currentStatusFilter = $(this).data('status');
            table.ajax.reload();
        });
        
        // Stats card click filter
        $('.stat-card[data-filter]').on('click', function() {
            var filterStatus = $(this).data('filter');
            currentStatusFilter = filterStatus;
            
            // Update active button
            $('#statusFilter .filter-btn').removeClass('active');
            $('#statusFilter .filter-btn').each(function() {
                var btnStatus = $(this).data('status');
                if (filterStatus.includes(btnStatus) || (btnStatus === 'ไม่ผ่าน' && filterStatus.includes('ไม่ผ่าน'))) {
                    $(this).addClass('active');
                }
            });
            
            table.ajax.reload();
        });
        
        // Update statistics
        function updateStats() {
            $.ajax({
                url: '<?= site_url('skjadmin/recruits/stats') ?>',
                type: 'POST',
                data: {
                    year: $('#year').val(),
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response) {
                        $('#totalCount').text(response.total || 0);
                        $('#approvedCount').text(response.approved || 0);
                        $('#pendingCount').text(response.pending || 0);
                        $('#rejectedCount').text(response.rejected || 0);
                    }
                }
            });
        }
        
        // Initial stats load
        updateStats();
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
</script>
<?= $this->endSection() ?>

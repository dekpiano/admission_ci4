<?= $this->extend('Admin/layout/AdminLayout') ?>
<?php helper('upload'); ?>

<?= $this->section('styles') ?>
<style>
    .cleanup-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .nav-tabs .nav-link {
        border-radius: 10px 10px 0 0;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-trash-alt fs-3 text-primary"></i>
                ระบบจัดการและล้างไฟล์ขยะ
            </h4>
            <p class="text-muted mb-0 small">ตรวจสอบความถูกต้องของไฟล์แนบ พื้นที่จัดเก็บ และการล้างข้อมูลผู้สมัครที่ไม่สมบูรณ์</p>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold d-block mb-1">กรอกข้อมูลไม่ครบ</span>
                            <h3 class="fw-bold text-warning mb-0">
                                <?= array_search('กรอกข้อมูลไม่ครบถ้วน', array_column($counts, 'recruit_status')) !== false ? $counts[array_search('กรอกข้อมูลไม่ครบถ้วน', array_column($counts, 'recruit_status'))]->total : 0 ?>
                            </h3>
                            <small class="text-muted">รายการ</small>
                        </div>
                        <div class="cleanup-stat-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                            <i class="bx bx-user-minus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold d-block mb-1">พฤติกรรมไม่เหมาะสม</span>
                            <h3 class="fw-bold text-danger mb-0">
                                <?= (array_search('พฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status')) !== false ? $counts[array_search('พฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status'))]->total : 0) + (array_search('มีพฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status')) !== false ? $counts[array_search('มีพฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status'))]->total : 0) ?>
                            </h3>
                            <small class="text-muted">รายการ</small>
                        </div>
                        <div class="cleanup-stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                            <i class="bx bx-user-x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold d-block mb-1">ไฟล์ชั่วคราว (Local)</span>
                            <h3 class="fw-bold text-info mb-0">
                                <?= $local_temp_count + $local_cache_count ?>
                            </h3>
                            <small class="text-muted">ไฟล์</small>
                        </div>
                        <div class="cleanup-stat-icon" style="background: rgba(6, 182, 212, 0.15); color: #06b6d4;">
                            <i class="bx bx-file"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold d-block mb-1">ขยะในฐานข้อมูล</span>
                            <h3 class="fw-bold text-primary mb-0">
                                <?= $total_junk ?>
                            </h3>
                            <small class="text-muted">รายการรวม</small>
                        </div>
                        <div class="cleanup-stat-icon" style="background: rgba(225, 29, 72, 0.15); color: #e11d48;">
                            <i class="bx bx-trash"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cleanup Navigation Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs border-0 px-4 pt-3 gap-2" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active d-flex align-items-center gap-2" role="tab" data-bs-toggle="tab" data-bs-target="#tab-db">
                        <i class="bx bx-data fs-5"></i> ล้างตามสถานะ DB
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link d-flex align-items-center gap-2" role="tab" data-bs-toggle="tab" data-bs-target="#tab-trash">
                        <i class="bx bx-trash fs-5"></i> ถังขยะ <span class="badge bg-warning rounded-pill" id="trashBadge">0</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link d-flex align-items-center gap-2" role="tab" data-bs-toggle="tab" data-bs-target="#tab-orphans">
                        <i class="bx bx-link-external fs-5"></i> ไฟล์ไม่ตรง DB
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link d-flex align-items-center gap-2" role="tab" data-bs-toggle="tab" data-bs-target="#tab-local">
                        <i class="bx bx-server fs-5"></i> ไฟล์ชั่วคราว Local
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content border-0 p-0">
                <!-- Tab 1: Database Cleanup -->
                <div class="tab-pane fade show active" id="tab-db" role="tabpanel">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnScan">
                            <i class="bx bx-search-alt-2 me-1"></i> สแกนขยะ DB
                        </button>
                        <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm disabled" id="btnDeleteBatch">
                            <i class="bx bx-trash-alt me-1"></i> ลบที่เลือก (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" style="width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">
                                        <input type="checkbox" class="form-check-input check-all" data-target=".junk-check">
                                    </th>
                                    <th style="width: 100px;">รหัส</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th style="width: 180px;">สถานะ</th>
                                    <th style="width: 160px;">วันที่สมัคร</th>
                                </tr>
                            </thead>
                            <tbody id="junkList">
                                <tr><td colspan="5" class="text-center py-5 text-muted">กดปุ่มสแกนขยะ DB ด้านบนเพื่อเริ่มค้นหา</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Trash (ถังขยะ) -->
                <div class="tab-pane fade" id="tab-trash" role="tabpanel">
                    <div class="alert alert-info border-0 rounded-3 mb-4" style="background: rgba(2, 132, 199, 0.08);">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-info-circle fs-4 text-info me-2"></i>
                            <div class="text-dark small">
                                <strong>ถังขยะระบบ:</strong> ไฟล์ที่ถูกลบจะถูกเก็บไว้ที่นี่ 30 วัน ก่อนถูกลบถาวร สามารถกดกู้คืนได้ตลอดเวลา
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnLoadTrash">
                            <i class="bx bx-refresh me-1"></i> โหลดรายการถังขยะ
                        </button>
                        <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm disabled" id="btnRestoreSelected">
                            <i class="bx bx-undo me-1"></i> กู้คืนที่เลือก (<span id="restoreCount">0</span>)
                        </button>
                        <button type="button" class="btn btn-outline-danger rounded-pill px-4" id="btnEmptyExpired">
                            <i class="bx bx-trash me-1"></i> ลบไฟล์หมดอายุถาวร
                        </button>
                    </div>
                    
                    <!-- Trash Stats -->
                    <div class="row g-3 mb-4" id="trashStats" style="display: none;">
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-file fs-3 text-primary me-2"></i>
                                    <div>
                                        <h5 class="mb-0 fw-bold" id="trashTotalCount">0</h5>
                                        <small class="text-muted">ไฟล์ในถังขยะ</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-hdd fs-3 text-info me-2"></i>
                                    <div>
                                        <h5 class="mb-0 fw-bold" id="trashTotalSize">0 MB</h5>
                                        <small class="text-muted">พื้นที่ใช้งาน</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-time-five fs-3 text-danger me-2"></i>
                                    <div>
                                        <h5 class="mb-0 fw-bold" id="trashExpiredCount">0</h5>
                                        <small class="text-muted">ไฟล์หมดอายุ (พร้อมลบ)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" style="width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">
                                        <input type="checkbox" class="form-check-input check-all" data-target=".trash-check">
                                    </th>
                                    <th>ชื่อไฟล์เดิม</th>
                                    <th>ตำแหน่งเดิม</th>
                                    <th style="width: 140px;">วันที่ลบ</th>
                                    <th style="width: 140px;">หมดอายุ</th>
                                    <th style="width: 100px;">ขนาด</th>
                                    <th style="width: 80px;" class="text-center">กู้คืน</th>
                                </tr>
                            </thead>
                            <tbody id="trashList">
                                <tr><td colspan="7" class="text-center py-5 text-muted">กดปุ่มโหลดรายการถังขยะด้านบน</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: Orphans Cleanup -->
                <div class="tab-pane fade" id="tab-orphans" role="tabpanel">
                    <div class="alert alert-warning border-0 rounded-3 mb-4" style="background: rgba(245, 158, 11, 0.08);">
                        <div class="d-flex align-items-start">
                            <i class="bx bx-error-circle fs-3 text-warning me-3 mt-1"></i>
                            <div>
                                <strong class="fs-6 text-dark">ระบบตรวจสอบไฟล์กำพร้า (Orphaned Files)</strong>
                                <p class="mb-0 mt-1 small text-secondary">ระบบจะเทียบรายชื่อไฟล์บน Cloud Storage กับฐานข้อมูลเพื่อค้นหาไฟล์ที่ไม่มีรายการอยู่ในระบบ</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mb-4">
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnScanOrphans">
                            <i class="bx bx-cloud-download me-1"></i> สแกนหาไฟล์กำพร้า
                        </button>
                        <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm disabled" id="btnDeleteOrphans">
                            <i class="bx bx-trash me-1"></i> ลบไฟล์ที่เลือก (<span id="orphanCount">0</span>)
                        </button>
                    </div>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" style="width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">
                                        <input type="checkbox" class="form-check-input check-all" data-target=".orphan-check">
                                    </th>
                                    <th>ชื่อไฟล์</th>
                                    <th>ตำแหน่ง (Path)</th>
                                    <th style="width: 100px;" class="text-center">ลิงก์</th>
                                </tr>
                            </thead>
                            <tbody id="orphanList">
                                <tr><td colspan="4" class="text-center py-5 text-muted">กดปุ่มสแกนหาไฟล์กำพร้าด้านบน</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 4: Local Temp Files -->
                <div class="tab-pane fade" id="tab-local" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-4 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
                                        <i class="bx bx-folder-minus text-warning fs-5"></i> Temp Files
                                    </h6>
                                    <span class="badge bg-label-warning rounded-pill"><?= $local_temp_count ?> ไฟล์</span>
                                </div>
                                <button class="btn btn-outline-danger rounded-pill px-4 btn-clean-local" data-type="temp">
                                    <i class="bx bx-trash me-1"></i> ล้าง Temp
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
                                        <i class="bx bx-refresh text-info fs-5"></i> Cache Files
                                    </h6>
                                    <span class="badge bg-label-info rounded-pill"><?= $local_cache_count ?> ไฟล์</span>
                                </div>
                                <button class="btn btn-outline-danger rounded-pill px-4 btn-clean-local" data-type="cache">
                                    <i class="bx bx-trash me-1"></i> ล้าง Cache
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.check-all').on('change', function() {
        $($(this).data('target')).prop('checked', $(this).prop('checked')).trigger('change');
    });

    // 1. Database Cleanup
    $('#btnScan').on('click', function() {
        const $btn = $(this);
        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> สแกนอยู่...').addClass('disabled');
        $.post('<?= site_url('skjadmin/cleanup/scan') ?>', function(res) {
            $btn.html('<i class="bx bx-search-alt-2 me-1"></i> สแกนขยะ DB').removeClass('disabled');
            let html = '';
            if(res.data && res.data.length > 0) {
                res.data.forEach(item => {
                    html += `<tr>
                        <td class="text-center"><input type="checkbox" class="form-check-input junk-check" value="${item.recruit_id}"></td>
                        <td class="fw-bold">${item.recruit_id}</td>
                        <td>${item.recruit_prefix}${item.recruit_firstName} ${item.recruit_lastName}</td>
                        <td><span class="badge bg-label-${item.recruit_status.includes('ไม่ครบ') ? 'warning' : 'danger'} rounded-pill">${item.recruit_status}</span></td>
                        <td>${item.recruit_date}</td>
                    </tr>`;
                });
            } else { html = '<tr><td colspan="5" class="text-center py-5 text-success"><i class="bx bx-check-circle me-1"></i> ไม่พบรายการข้อมูลขยะในฐานข้อมูล</td></tr>'; }
            $('#junkList').html(html);
        });
    });

    $(document).on('change', '.junk-check', function() {
        let count = $('.junk-check:checked').length;
        $('#selectedCount').text(count);
        $('#btnDeleteBatch').toggleClass('disabled', count === 0);
    });

    $('#btnDeleteBatch').on('click', function() {
        let ids = $('.junk-check:checked').map(function(){ return $(this).val(); }).get();
        
        Swal.fire({
            title: 'ลบข้อมูลผู้สมัครและไฟล์แนบ?',
            text: `จำนวน ${ids.length} รายการ`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ตกลง, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                deleteWithProgress(ids, 'db');
            }
        });
    });

    // 2. Orphans Cleanup
    $('#btnScanOrphans').on('click', function() {
        const $btn = $(this);
        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังเทียบ DB...').addClass('disabled');
        
        $.post('<?= site_url('skjadmin/cleanup/scan_orphans') ?>')
            .done(function(res) {
                $btn.html('<i class="bx bx-cloud-download me-1"></i> สแกนหาไฟล์กำพร้า').removeClass('disabled');
                
                if(res.status === 'success') {
                    let html = '';
                    if(res.orphans && res.orphans.length > 0) {
                        res.orphans.forEach(file => {
                            html += `<tr>
                                <td class="text-center"><input type="checkbox" class="form-check-input orphan-check" data-name="${file.name}" data-path="${file.path}"></td>
                                <td class="text-primary fw-semibold">${file.name}</td>
                                <td><small class="text-muted">${file.path}</small></td>
                                <td class="text-center"><a href="<?= get_upload_base_url() ?>${file.path}" target="_blank" class="btn btn-sm btn-icon btn-light rounded-circle"><i class="bx bx-link-external"></i></a></td>
                            </tr>`;
                        });
                    } else { 
                        html = '<tr><td colspan="4" class="text-center py-5 text-success"><i class="bx bx-check-circle me-1"></i> ยอดเยี่ยม! ไม่มีไฟล์ขยะที่เกินมาจากฐานข้อมูล</td></tr>'; 
                    }
                    $('#orphanList').html(html);
                } else {
                    Swal.fire('Error', res.message || 'เกิดข้อผิดพลาดในการสแกน', 'error');
                }
            })
            .fail(function() {
                $btn.html('<i class="bx bx-cloud-download me-1"></i> สแกนหาไฟล์กำพร้า').removeClass('disabled');
                Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            });
    });

    $(document).on('change', '.orphan-check', function() {
        let count = $('.orphan-check:checked').length;
        $('#orphanCount').text(count);
        $('#btnDeleteOrphans').toggleClass('disabled', count === 0);
    });

    $('#btnDeleteOrphans').on('click', function() {
        let files = $('.orphan-check:checked').map(function(){ 
            return { name: $(this).data('name'), path: $(this).data('path') }; 
        }).get();
        
        Swal.fire({
            title: 'ลบไฟล์กำพร้า?',
            text: `ไฟล์จำนวน ${files.length} รายการนี้ไม่มีชื่ออยู่ในฐานข้อมูล`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ตกลง, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                deleteWithProgress(files, 'orphan');
            }
        });
    });

    // 3. Local Cleanup
    $('.btn-clean-local').on('click', function() {
        let type = $(this).data('type');
        Swal.fire({
            title: `ล้างไฟล์ ${type} ในเครื่อง?`,
            text: 'ไฟล์ชั่วคราวจะถูกลบออกทั้งหมด',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ล้างเลย',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.post('<?= site_url('skjadmin/cleanup/clean_local') ?>', function() {
                    location.reload();
                });
            }
        });
    });

    async function deleteWithProgress(items, type) {
        const total = items.length;
        let completed = 0;
        let success = 0;
        let errors = 0;

        Swal.fire({
            title: 'กำลังลบข้อมูล...',
            html: `
                <div class="mb-3">
                    <div class="progress" style="height: 20px; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" 
                             role="progressbar" style="width: 0%" id="deleteProgress">0%</div>
                    </div>
                </div>
                <div class="text-muted small">
                    <span id="deleteStatus">เตรียมลบ...</span><br>
                    สำเร็จ: <span id="successCount" class="text-success fw-bold">0</span> | ผิดพลาด: <span id="errorCount" class="text-danger fw-bold">0</span>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); }
        });

        const url = type === 'db' 
            ? '<?= site_url('skjadmin/cleanup/delete_single') ?>'
            : '<?= site_url('skjadmin/cleanup/delete_orphan_single') ?>';

        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            $('#deleteStatus').text(type === 'db' 
                ? `กำลังลบ ID: ${item} (${i + 1}/${total})`
                : `กำลังลบ: ${item.name} (${i + 1}/${total})`
            );

            try {
                const postData = type === 'db' 
                    ? { id: item }
                    : { name: item.name, path: item.path };

                const res = await $.post(url, postData);
                if (res.status === 'success') {
                    success++;
                    $('#successCount').text(success);
                } else {
                    errors++;
                    $('#errorCount').text(errors);
                }
            } catch (e) {
                errors++;
                $('#errorCount').text(errors);
            }

            completed++;
            const percent = Math.round((completed / total) * 100);
            $('#deleteProgress').css('width', percent + '%').text(percent + '%');
        }

        Swal.fire({
            icon: errors > 0 ? 'warning' : 'success',
            title: 'เสร็จสิ้น!',
            html: `<div class="text-center">ลบข้อมูลสำเร็จ <strong class="text-success">${success}</strong> รายการ</div>`,
            confirmButtonText: 'ตกลง'
        }).then(() => {
            location.reload();
        });
    }

    // 4. Trash Management
    $('#btnLoadTrash').on('click', function() {
        loadTrashFiles();
    });
    
    function loadTrashFiles() {
        const $btn = $('#btnLoadTrash');
        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังโหลด...').addClass('disabled');
        
        $.post('<?= site_url('skjadmin/cleanup/list_trash') ?>')
            .done(function(res) {
                $btn.html('<i class="bx bx-refresh me-1"></i> โหลดรายการถังขยะ').removeClass('disabled');
                
                if(res.status === 'success') {
                    $('#trashStats').show();
                    $('#trashTotalCount').text(res.count || 0);
                    $('#trashTotalSize').text((res.total_size_mb || 0) + ' MB');
                    $('#trashExpiredCount').text(res.expired_count || 0);
                    $('#trashBadge').text(res.count || 0);
                    
                    let html = '';
                    if(res.files && res.files.length > 0) {
                        res.files.forEach(file => {
                            const isExpired = new Date(file.delete_after) <= new Date();
                            const sizeKB = Math.round(file.size / 1024);
                            const sizeText = sizeKB > 1024 ? (sizeKB / 1024).toFixed(2) + ' MB' : sizeKB + ' KB';
                            
                            html += `<tr class="${isExpired ? 'table-warning' : ''}">
                                <td class="text-center"><input type="checkbox" class="form-check-input trash-check" 
                                    data-name="${file.trash_name}" 
                                    data-path="${file.trash_path}"
                                    ${!file.can_restore ? 'disabled' : ''}></td>
                                <td><small class="text-primary fw-semibold">${file.original_name || file.trash_name}</small></td>
                                <td><small class="text-muted">${file.original_path || '-'}</small></td>
                                <td><small>${file.deleted_at || '-'}</small></td>
                                <td><small class="${isExpired ? 'text-danger fw-bold' : ''}">${file.delete_after || '-'} ${isExpired ? '(หมดอายุ)' : ''}</small></td>
                                <td><small>${sizeText}</small></td>
                                <td class="text-center">
                                    ${file.can_restore ? `<button class="btn btn-sm btn-icon btn-outline-success btn-restore rounded-circle" 
                                        data-name="${file.trash_name}" 
                                        data-path="${file.trash_path}" title="กู้คืนไฟล์">
                                        <i class="bx bx-undo"></i>
                                    </button>` : '<span class="text-muted">-</span>'}
                                </td>
                            </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center py-5 text-success"><i class="bx bx-check-circle me-1"></i> ถังขยะว่างเปล่า</td></tr>';
                    }
                    $('#trashList').html(html);
                } else {
                    Swal.fire('Error', res.message || 'เกิดข้อผิดพลาด', 'error');
                }
            })
            .fail(function() {
                $btn.html('<i class="bx bx-refresh me-1"></i> โหลดรายการถังขยะ').removeClass('disabled');
                Swal.fire('Error', 'ไม่สามารถเชื่อมต่อ Server ได้', 'error');
            });
    }

    $(document).on('change', '.trash-check', function() {
        let count = $('.trash-check:checked').length;
        $('#restoreCount').text(count);
        $('#btnRestoreSelected').toggleClass('disabled', count === 0);
    });

    $(document).on('click', '.btn-restore', function() {
        const $btn = $(this);
        const name = $btn.data('name');
        const path = $btn.data('path');
        
        Swal.fire({
            title: 'กู้คืนไฟล์?',
            text: 'ไฟล์จะถูกย้ายกลับไปตำแหน่งเดิม',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'กู้คืน',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                $.post('<?= site_url('skjadmin/cleanup/restore_file') ?>', { name: name, path: path })
                    .done(function(res) {
                        if (res.status === 'success') {
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'กู้คืนสำเร็จ', showConfirmButton: false, timer: 1500 });
                            loadTrashFiles();
                        } else {
                            $btn.prop('disabled', false).html('<i class="bx bx-undo"></i>');
                            Swal.fire('Error', res.message || 'กู้คืนไม่สำเร็จ', 'error');
                        }
                    });
            }
        });
    });

    $('#btnRestoreSelected').on('click', function() {
        const files = $('.trash-check:checked').map(function() {
            return { name: $(this).data('name'), path: $(this).data('path') };
        }).get();
        if (files.length === 0) return;
        
        Swal.fire({
            title: `กู้คืน ${files.length} ไฟล์?`,
            text: 'ไฟล์จะถูกย้ายกลับไปตำแหน่งเดิม',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'กู้คืนทั้งหมด',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                restoreWithProgress(files);
            }
        });
    });

    async function restoreWithProgress(files) {
        const total = files.length;
        let completed = 0;
        let success = 0;
        let errors = 0;
        
        Swal.fire({
            title: 'กำลังกู้คืนไฟล์...',
            html: `
                <div class="mb-3">
                    <div class="progress" style="height: 20px; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" style="width: 0%" id="restoreProgress">0%</div>
                    </div>
                </div>
                <div class="text-muted small">
                    <span id="restoreStatus">เตรียมกู้คืน...</span><br>
                    สำเร็จ: <span id="restoreSuccessCount" class="text-success fw-bold">0</span> | ผิดพลาด: <span id="restoreErrorCount" class="text-danger fw-bold">0</span>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            $('#restoreStatus').text(`กำลังกู้คืน: ${file.name} (${i + 1}/${total})`);
            try {
                const res = await $.post('<?= site_url('skjadmin/cleanup/restore_file') ?>', { name: file.name, path: file.path });
                if (res.status === 'success') { success++; $('#restoreSuccessCount').text(success); } 
                else { errors++; $('#restoreErrorCount').text(errors); }
            } catch (e) {
                errors++;
                $('#restoreErrorCount').text(errors);
            }
            completed++;
            const percent = Math.round((completed / total) * 100);
            $('#restoreProgress').css('width', percent + '%').text(percent + '%');
        }
        
        Swal.fire({ icon: errors > 0 ? 'warning' : 'success', title: 'เสร็จสิ้น!', html: `<div class="text-center">กู้คืนสำเร็จ <strong class="text-success">${success}</strong> ไฟล์</div>`, confirmButtonText: 'ตกลง' })
            .then(() => loadTrashFiles());
    }

    $('#btnEmptyExpired').on('click', function() {
        Swal.fire({
            title: 'ลบไฟล์หมดอายุถาวร?',
            text: 'ไฟล์ที่อยู่ในถังขยะเกิน 30 วันจะถูกลบถาวรและไม่สามารถกู้คืนได้',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'ลบถาวร',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.post('<?= site_url('skjadmin/cleanup/empty_expired') ?>').done(res => {
                    Swal.fire('สำเร็จ!', `ลบไฟล์หมดอายุ ${res.deleted_count || 0} ไฟล์`, 'success').then(() => loadTrashFiles());
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
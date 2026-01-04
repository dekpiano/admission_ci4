<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2 text-primary fw-bold"><i class="bx bx-trash me-2"></i>ระบบจัดการและล้างไฟล์ขยะ</h5>
                    <small class="text-muted">ตรวจสอบความถูกต้องของไฟล์และพื้นที่จัดเก็บ</small>
                </div>
            </div>
            <div class="card-body mt-4">
                <!-- Summary Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-label-warning shadow-none border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-warning p-2"><i class="bx bx-user-minus fs-4"></i></span>
                                </div>
                                <h4 class="mb-1"><?= array_search('กรอกข้อมูลไม่ครบถ้วน', array_column($counts, 'recruit_status')) !== false ? $counts[array_search('กรอกข้อมูลไม่ครบถ้วน', array_column($counts, 'recruit_status'))]->total : 0 ?></h4>
                                <p class="mb-0 fw-semibold text-warning">กรอกข้อมูลไม่ครบ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-label-danger shadow-none border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-danger p-2"><i class="bx bx-user-x fs-4"></i></span>
                                </div>
                                <h4 class="mb-1"><?= (array_search('พฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status')) !== false ? $counts[array_search('พฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status'))]->total : 0) + (array_search('มีพฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status')) !== false ? $counts[array_search('มีพฤติกรรมไม่เหมาะสม', array_column($counts, 'recruit_status'))]->total : 0) ?></h4>
                                <p class="mb-0 fw-semibold text-danger">พฤติกรรมไม่เหมาะสม</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-label-info shadow-none border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-info p-2"><i class="bx bx-file fs-4"></i></span>
                                </div>
                                <h4 class="mb-1"><?= $local_temp_count + $local_cache_count ?></h4>
                                <p class="mb-0 fw-semibold text-info">ไฟล์ชั่วคราว (Local)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-label-primary shadow-none border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary p-2"><i class="bx bx-trash fs-4"></i></span>
                                </div>
                                <h4 class="mb-1"><?= $total_junk ?></h4>
                                <p class="mb-0 fw-semibold text-primary">ขยะในฐานข้อมูล</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-db">
                                <i class="bx bx-data me-1"></i> ล้างตามสถานะ DB
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-orphans">
                                <i class="bx bx-link-external me-1"></i> ไฟล์ไม่ตรงฐานข้อมูล (Orphans)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-local">
                                <i class="bx bx-server me-1"></i> ไฟล์ชั่วคราว (Local)
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content border-0 px-0">
                        <!-- Tab Database -->
                        <div class="tab-pane fade show active" id="tab-db" role="tabpanel">
                            <div class="d-flex gap-3 mb-4 mt-2">
                                <button type="button" class="btn btn-primary" id="btnScan"><i class="bx bx-search-alt-2 me-1"></i> สแกนขยะ DB</button>
                                <button type="button" class="btn btn-danger disabled" id="btnDeleteBatch"><i class="bx bx-trash-alt me-1"></i> ลบที่เลือก (<span id="selectedCount">0</span>)</button>
                            </div>
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;"><input type="checkbox" class="form-check-input check-all" data-target=".junk-check"></th>
                                            <th>รหัส</th>
                                            <th>ชื่อ-นามสกุล</th>
                                            <th>สถานะ</th>
                                            <th>วันที่สมัคร</th>
                                        </tr>
                                    </thead>
                                    <tbody id="junkList">
                                        <tr><td colspan="5" class="text-center py-5 text-muted">กดปุ่มสแกนด้างบน</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Orphans -->
                        <div class="tab-pane fade" id="tab-orphans" role="tabpanel">
                            <div class="alert alert-info border-info mt-2 mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bx bx-help-circle fs-4 me-2"></i>
                                    <strong>วิธีการทำงาน:</strong>
                                </div>
                                <div>ระบบจะเทียบรายชื่อไฟล์บน Cloud กับใน DB หากไม่พบชื่อไฟล์ใน DB จะถือว่าเป็น "ไฟล์ขยะ"</div>
                            </div>
                            
                            <div class="alert alert-warning border-warning mb-4" id="alertSetupRequired" style="display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="bx bx-error-circle fs-4 me-2 mt-1"></i>
                                    <div>
                                        <strong>ต้องติดตั้ง API บน Server ปลายทาง</strong>
                                        <p class="mb-2 mt-1">กรุณาอัพโหลดไฟล์ <code>list_files.php</code> ไปที่ Server <code>skj.nsnpao.go.th</code> ตาม path:</p>
                                        <code class="d-block bg-dark text-light p-2 rounded">/token/list_files.php</code>
                                        <p class="mt-2 mb-0"><small>ไฟล์นี้อยู่ในโปรเจคที่ <code>public/token/list_files.php</code></small></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-3 mb-4">
                                <button type="button" class="btn btn-primary" id="btnScanOrphans"><i class="bx bx-cloud-download me-1"></i> สแกนหาไฟล์กำพร้า</button>
                                <button type="button" class="btn btn-danger disabled" id="btnDeleteOrphans"><i class="bx bx-trash me-1"></i> ลบไฟล์ที่เลือก (<span id="orphanCount">0</span>)</button>
                            </div>
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;"><input type="checkbox" class="form-check-input check-all" data-target=".orphan-check"></th>
                                            <th>ชื่อไฟล์</th>
                                            <th>ตำแหน่ง (Path)</th>
                                            <th style="width: 100px;">ลิงก์</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orphanList">
                                        <tr><td colspan="4" class="text-center py-5 text-muted">กดปุ่มสแกนหาไฟล์กำพร้า</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Local -->
                        <div class="tab-pane fade" id="tab-local" role="tabpanel">
                            <div class="row mt-2 g-3">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between">
                                        <div><h6 class="mb-1">Temp Files</h6><small><?= $local_temp_count ?> ไฟล์</small></div>
                                        <button class="btn btn-outline-danger btn-clean-local" data-type="temp">ล้าง</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between">
                                        <div><h6 class="mb-1">Cache Files</h6><small><?= $local_cache_count ?> ไฟล์</small></div>
                                        <button class="btn btn-outline-danger btn-clean-local" data-type="cache">ล้าง</button>
                                    </div>
                                </div>
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
    // Check All functionality
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
                        <td><input type="checkbox" class="form-check-input junk-check" value="${item.recruit_id}"></td>
                        <td>${item.recruit_id}</td>
                        <td>${item.recruit_prefix}${item.recruit_firstName} ${item.recruit_lastName}</td>
                        <td><span class="badge bg-label-${item.recruit_status.includes('ไม่ครบ') ? 'warning' : 'danger'}">${item.recruit_status}</span></td>
                        <td>${item.recruit_date}</td>
                    </tr>`;
                });
            } else { html = '<tr><td colspan="5" class="text-center py-4">ไม่พบข้อมูล</td></tr>'; }
            $('#junkList').html(html);
        });
    });

    // Count selected junk
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
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                deleteWithProgress(ids, 'db');
            }
        });
    });

    // 2. Orphans Cleanup (ไฟล์ไม่ตรง DB)
    $('#btnScanOrphans').on('click', function() {
        const $btn = $(this);
        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังเทียบ DB...').addClass('disabled');
        
        $.post('<?= site_url('skjadmin/cleanup/scan_orphans') ?>')
            .done(function(res) {
                $btn.html('<i class="bx bx-cloud-download me-1"></i> สแกนหาไฟล์กำพร้า').removeClass('disabled');
                
                if(res.status === 'success') {
                    $('#alertSetupRequired').hide();
                    let html = '';
                    if(res.orphans.length > 0) {
                        res.orphans.forEach(file => {
                            html += `<tr>
                                <td><input type="checkbox" class="form-check-input orphan-check" data-name="${file.name}" data-path="${file.path}"></td>
                                <td class="text-primary">${file.name}</td>
                                <td><small>${file.path}</small></td>
                                <td><a href="https://skj.nsnpao.go.th/uploads/${file.path}" target="_blank"><i class="bx bx-link-external"></i></a></td>
                            </tr>`;
                        });
                    } else { 
                        html = '<tr><td colspan="4" class="text-center py-5 text-success"><i class="bx bx-check-circle me-1"></i> ยอดเยี่ยม! ไม่มีไฟล์ขยะที่เกินมาจากฐานข้อมูล</td></tr>'; 
                    }
                    $('#orphanList').html(html);
                } else {
                    // Check if error is 404 (API not installed)
                    if (res.message && res.message.includes('404')) {
                        $('#alertSetupRequired').show();
                        $('#orphanList').html('<tr><td colspan="4" class="text-center py-5 text-warning"><i class="bx bx-error me-1"></i> ยังไม่ได้ติดตั้ง API บน Server ปลายทาง</td></tr>');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            })
            .fail(function(xhr) {
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
            title: 'ลบไฟล์ (Orphans) ข้าม Cloud?',
            text: `ไฟล์จำนวน ${files.length} รายการนี้ไม่มีชื่ออยู่ในฐานข้อมูล`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ตกลง',
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
        confirmAndAction(`ล้างไฟล์ ${type} ในเครื่อง?`, 'ไฟล์ชั่วคราวจะถูกลบออกทั้งหมด', () => {
            $.post('<?= site_url('skjadmin/cleanup/clean_local') ?>', res => location.reload());
        });
    });

    function confirmAndAction(title, text, callback) {
        Swal.fire({ title, text, icon: 'warning', showCancelButton: true, confirmButtonText: 'ตกลง', cancelButtonText: 'ยกเลิก' })
            .then(result => { if(result.isConfirmed) { Swal.showLoading(); callback(); } });
    }

    /**
     * Delete items with progress bar
     * @param {Array} items - Array of items to delete (IDs for DB, or file objects for orphans)
     * @param {string} type - 'db' or 'orphan'
     */
    async function deleteWithProgress(items, type) {
        const total = items.length;
        let completed = 0;
        let success = 0;
        let errors = 0;

        // Show progress modal
        Swal.fire({
            title: 'กำลังลบข้อมูล...',
            html: `
                <div class="mb-3">
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" 
                             role="progressbar" style="width: 0%" id="deleteProgress">0%</div>
                    </div>
                </div>
                <div class="text-muted">
                    <span id="deleteStatus">เตรียมลบ...</span><br>
                    <small>สำเร็จ: <span id="successCount">0</span> | ผิดพลาด: <span id="errorCount">0</span></small>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = type === 'db' 
            ? '<?= site_url('skjadmin/cleanup/delete_single') ?>'
            : '<?= site_url('skjadmin/cleanup/delete_orphan_single') ?>';

        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            
            // Update status
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

        // Complete
        Swal.fire({
            icon: errors > 0 ? 'warning' : 'success',
            title: 'เสร็จสิ้น!',
            html: `
                <div class="text-center">
                    <p class="mb-2">ลบข้อมูลสำเร็จ <strong class="text-success">${success}</strong> รายการ</p>
                    ${errors > 0 ? `<p class="mb-0 text-danger">ผิดพลาด <strong>${errors}</strong> รายการ</p>` : ''}
                </div>
            `,
            confirmButtonText: 'รีเฟรชหน้า'
        }).then(() => {
            location.reload();
        });
    }
});
</script>
<?= $this->endSection() ?>
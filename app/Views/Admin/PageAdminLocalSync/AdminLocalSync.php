<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="text-white mb-1">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                                จัดการไฟล์ Local Sync
                            </h2>
                            <p class="text-white-50 mb-0">Sync ไฟล์ที่บันทึกใน Local กลับไป Remote Server อัตโนมัติ</p>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light btn-lg" id="btnRefresh">
                                <i class="bi bi-arrow-clockwise me-1"></i> รีเฟรช
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Status Cards -->
    <div class="row mb-4">
        <?php foreach ($serverStatus as $name => $info): ?>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <?php if ($info['available']): ?>
                                <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                    <i class="bi bi-check-circle-fill text-success fs-2"></i>
                                </div>
                            <?php else: ?>
                                <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                                    <i class="bi bi-x-circle-fill text-danger fs-2"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1"><?= $name ?></h5>
                            <p class="text-muted mb-0 small"><?= $info['url'] ?></p>
                            <span class="badge <?= $info['available'] ? 'bg-success' : 'bg-danger' ?> mt-2">
                                <?= $info['available'] ? 'พร้อมใช้งาน' : 'ไม่พร้อมใช้งาน' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pending Files Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="bi bi-folder2-open me-2 text-warning"></i>
                            ไฟล์ที่รอ Sync
                            <span class="badge bg-warning ms-2" id="pendingCount"><?= count($pendingFiles) ?></span>
                        </h5>
                    </div>
                    <div>
                        <?php 
                        $hasAvailableServer = false;
                        foreach ($serverStatus as $info) {
                            if ($info['available']) {
                                $hasAvailableServer = true;
                                break;
                            }
                        }
                        ?>
                        <button type="button" class="btn btn-success" id="btnSyncAll" 
                            <?= (!$hasAvailableServer || empty($pendingFiles)) ? 'disabled' : '' ?>>
                            <i class="bi bi-cloud-upload me-1"></i> Sync ทั้งหมด
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($pendingFiles)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">ไม่มีไฟล์ที่รอ Sync</h5>
                            <p class="text-muted">ไฟล์ทั้งหมดถูกอัปโหลดไปยัง Remote Server แล้ว</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="filesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>ไฟล์</th>
                                        <th>Path</th>
                                        <th>ขนาด</th>
                                        <th>แก้ไขล่าสุด</th>
                                        <th style="width: 150px;">การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingFiles as $index => $file): ?>
                                    <tr data-path="<?= esc($file['path']) ?>">
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($file['type'] === 'image'): ?>
                                                    <img src="<?= base_url('uploads/' . $file['path']) ?>" 
                                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-secondary bg-opacity-10 rounded p-2 me-2">
                                                        <i class="bi bi-file-earmark text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <span class="text-truncate" style="max-width: 200px;" title="<?= esc($file['filename']) ?>">
                                                    <?= esc($file['filename']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="small"><?= esc($file['path']) ?></code>
                                        </td>
                                        <td><?= $file['size_formatted'] ?></td>
                                        <td><?= $file['modified'] ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-success btn-sync-single" 
                                                data-path="<?= esc($file['path']) ?>"
                                                <?= !$hasAvailableServer ? 'disabled' : '' ?>>
                                                <i class="bi bi-cloud-upload"></i> Sync
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto Sync Settings -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-gear me-2"></i>การตั้งค่า Auto Sync</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="autoSyncEnabled" checked>
                        <label class="form-check-label" for="autoSyncEnabled">
                            เปิดใช้งาน Auto Sync (ตรวจสอบทุก 5 นาที)
                        </label>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        เมื่อเปิดใช้งาน ระบบจะตรวจสอบและ Sync ไฟล์อัตโนมัติเมื่อ Server พร้อมใช้งาน
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>สถานะ Auto Sync</h6>
                </div>
                <div class="card-body">
                    <div id="autoSyncStatus">
                        <span class="text-muted">กำลังตรวจสอบ...</span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">ตรวจสอบล่าสุด: <span id="lastCheck">-</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-cloud-upload me-2"></i>กำลัง Sync ไฟล์</h5>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-3">
                    <div class="display-4 fw-bold text-primary" id="progressPercent">0%</div>
                    <p class="text-muted mb-0" id="progressText">กำลังเตรียมข้อมูล...</p>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                         role="progressbar" id="progressBar" 
                         style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2 small text-muted">
                    <span id="progressCurrent">0</span>
                    <span id="progressTotal">/ 0 ไฟล์</span>
                </div>
                <!-- Current file info -->
                <div class="mt-3 p-2 bg-light rounded" id="currentFileInfo" style="display:none;">
                    <small class="text-muted"><i class="bi bi-file-earmark me-1"></i>กำลัง sync:</small>
                    <code class="d-block text-truncate" id="currentFileName"></code>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0" id="loadingText">กำลังดำเนินการ...</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let autoSyncInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    // Refresh button
    document.getElementById('btnRefresh').addEventListener('click', function() {
        location.reload();
    });

    // Sync All button
    document.getElementById('btnSyncAll').addEventListener('click', function() {
        syncAllFiles();
    });

    // Sync Single buttons
    document.querySelectorAll('.btn-sync-single').forEach(btn => {
        btn.addEventListener('click', function() {
            syncSingleFile(this.dataset.path);
        });
    });

    // Auto Sync toggle
    const autoSyncCheckbox = document.getElementById('autoSyncEnabled');
    autoSyncCheckbox.addEventListener('change', function() {
        if (this.checked) {
            startAutoSync();
        } else {
            stopAutoSync();
        }
    });

    // Start auto sync on page load
    if (autoSyncCheckbox.checked) {
        startAutoSync();
    }
});

// Get all file paths from the table
function getAllFilePaths() {
    const paths = [];
    document.querySelectorAll('#filesTable tbody tr').forEach(row => {
        const path = row.dataset.path;
        if (path) paths.push(path);
    });
    return paths;
}

function syncAllFiles() {
    const filePaths = getAllFilePaths();
    
    if (filePaths.length === 0) {
        Swal.fire('ไม่มีไฟล์', 'ไม่มีไฟล์ที่รอ Sync', 'info');
        return;
    }
    
    Swal.fire({
        title: 'ยืนยันการ Sync',
        html: `<p>ต้องการ Sync ไฟล์ทั้งหมด <strong>${filePaths.length}</strong> ไฟล์ไปยัง Remote Server หรือไม่?</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-cloud-upload me-1"></i>Sync ทั้งหมด',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            syncFilesWithProgress(filePaths);
        }
    });
}

async function syncFilesWithProgress(filePaths) {
    const total = filePaths.length;
    let success = 0;
    let failed = 0;
    const failedDetails = [];
    
    // Show progress modal
    const progressModal = new bootstrap.Modal(document.getElementById('progressModal'));
    progressModal.show();
    
    document.getElementById('progressPercent').textContent = '0%';
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('progressCurrent').textContent = '0';
    document.getElementById('progressTotal').textContent = `/ ${total} ไฟล์`;
    document.getElementById('currentFileInfo').style.display = 'block';
    
    for (let i = 0; i < total; i++) {
        const filePath = filePaths[i];
        const fileName = filePath.split('/').pop();
        
        // Update progress text
        document.getElementById('progressText').textContent = `กำลัง Sync ไฟล์ที่ ${i + 1} จาก ${total}...`;
        document.getElementById('currentFileName').textContent = fileName;
        document.getElementById('progressCurrent').textContent = i + 1;
        
        try {
            const formData = new FormData();
            formData.append('file_path', filePath);
            
            const response = await fetch('<?= base_url('skjadmin/local-sync/sync-single') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                success++;
                // Remove row from table
                const row = document.querySelector(`tr[data-path="${filePath}"]`);
                if (row) row.remove();
            } else {
                failed++;
                failedDetails.push({
                    filename: fileName,
                    path: filePath,
                    error: data.message || 'ไม่ทราบสาเหตุ'
                });
            }
        } catch (error) {
            failed++;
            failedDetails.push({
                filename: fileName,
                path: filePath,
                error: error.message || 'Network error'
            });
        }
        
        // Update progress
        const percent = Math.round(((i + 1) / total) * 100);
        document.getElementById('progressPercent').textContent = percent + '%';
        document.getElementById('progressBar').style.width = percent + '%';
        
        // Change progress bar color based on errors
        if (failed > 0) {
            document.getElementById('progressBar').classList.remove('bg-success');
            document.getElementById('progressBar').classList.add('bg-warning');
        }
    }
    
    // Hide progress modal
    progressModal.hide();
    
    // Update pending count
    const countBadge = document.getElementById('pendingCount');
    const currentCount = parseInt(countBadge.textContent);
    countBadge.textContent = currentCount - success;
    
    // Show results
    showSyncResults(total, success, failed, failedDetails);
}

function showSyncResults(total, success, failed, failedDetails) {
    if (failed === 0) {
        // All success
        Swal.fire({
            icon: 'success',
            title: 'Sync สำเร็จทั้งหมด!',
            html: `<p class="mb-0">Sync สำเร็จ <strong class="text-success">${success}</strong> ไฟล์</p>`,
            timer: 2500,
            showConfirmButton: false
        }).then(() => {
            if (success > 0) location.reload();
        });
    } else if (success === 0) {
        // All failed
        let errorHtml = buildErrorList(failedDetails);
        Swal.fire({
            icon: 'error',
            title: 'Sync ล้มเหลวทั้งหมด!',
            html: `<p>ไม่สามารถ Sync ได้ <strong class="text-danger">${failed}</strong> ไฟล์</p>${errorHtml}`,
            width: 600,
            confirmButtonText: 'ตกลง'
        });
    } else {
        // Partial success
        let errorHtml = buildErrorList(failedDetails);
        Swal.fire({
            icon: 'warning',
            title: 'Sync บางส่วน',
            html: `
                <div class="d-flex justify-content-center gap-4 mb-3">
                    <div class="text-center">
                        <div class="display-6 text-success">${success}</div>
                        <small class="text-muted">สำเร็จ</small>
                    </div>
                    <div class="text-center">
                        <div class="display-6 text-danger">${failed}</div>
                        <small class="text-muted">ล้มเหลว</small>
                    </div>
                </div>
                ${errorHtml}
            `,
            width: 600,
            confirmButtonText: 'ตกลง'
        }).then(() => {
            if (success > 0) location.reload();
        });
    }
}

function buildErrorList(failedDetails) {
    if (failedDetails.length === 0) return '';
    
    let html = `<div class="text-start mt-3" style="max-height: 200px; overflow-y: auto;">
        <p class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle me-1"></i>รายละเอียดข้อผิดพลาด:</p>
        <ul class="list-unstyled small">`;
    
    failedDetails.forEach((item, index) => {
        html += `
            <li class="mb-2 p-2 bg-light rounded">
                <strong>${index + 1}. ${item.filename}</strong>
                <br><span class="text-danger"><i class="bi bi-x-circle me-1"></i>${item.error}</span>
                <br><code class="small text-muted">${item.path}</code>
            </li>`;
    });
    
    html += '</ul></div>';
    return html;
}

function syncSingleFile(filePath) {
    showLoading('กำลัง Sync ไฟล์...');
    
    const formData = new FormData();
    formData.append('file_path', filePath);
    
    fetch('<?= base_url('skjadmin/local-sync/sync-single') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.status === 'success') {
            // Remove row from table
            const row = document.querySelector(`tr[data-path="${filePath}"]`);
            if (row) {
                row.remove();
            }
            
            // Update count
            const countBadge = document.getElementById('pendingCount');
            const currentCount = parseInt(countBadge.textContent);
            countBadge.textContent = currentCount - 1;
            
            Swal.fire({
                icon: 'success',
                title: 'Sync สำเร็จ!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        Swal.fire('เกิดข้อผิดพลาด', error.message, 'error');
    });
}

function startAutoSync() {
    checkAndSync();
    autoSyncInterval = setInterval(checkAndSync, 5 * 60 * 1000); // Every 5 minutes
    document.getElementById('autoSyncStatus').innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>กำลังทำงาน</span>';
}

function stopAutoSync() {
    if (autoSyncInterval) {
        clearInterval(autoSyncInterval);
        autoSyncInterval = null;
    }
    document.getElementById('autoSyncStatus').innerHTML = '<span class="text-muted"><i class="bi bi-pause-circle me-1"></i>หยุดชั่วคราว</span>';
}

function checkAndSync() {
    fetch('<?= base_url('skjadmin/local-sync/api-check-status') ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('lastCheck').textContent = data.checked_at;
            
            if (data.can_sync) {
                // Auto sync if there are pending files and server is available
                console.log('Auto sync triggered: ' + data.pending_files + ' files pending');
                
                // Perform sync
                fetch('<?= base_url('skjadmin/local-sync/sync-all') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(syncResult => {
                    if (syncResult.status === 'success') {
                        console.log('Auto sync completed: ' + syncResult.message);
                        // Refresh page if files were synced
                        if (syncResult.results && syncResult.results.success > 0) {
                            location.reload();
                        }
                    }
                });
            }
        });
}

function showLoading(text) {
    document.getElementById('loadingText').textContent = text || 'กำลังดำเนินการ...';
    new bootstrap.Modal(document.getElementById('loadingModal')).show();
}

function hideLoading() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
    if (modal) modal.hide();
}
</script>
<?= $this->endSection() ?>

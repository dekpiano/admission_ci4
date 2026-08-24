<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bx-sync fs-3 text-primary"></i>
                จัดการไฟล์ Local Sync
            </h4>
            <p class="text-muted mb-0 small">ระบบ Sync ไฟล์รูปถ่ายและเอกสารที่บันทึกไว้ใน Local กลับไปยัง Remote Server อัตโนมัติ</p>
        </div>
        <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="btnRefresh">
            <i class="bx bx-refresh me-1"></i> รีเฟรชสถานะ
        </button>
    </div>

    <!-- Server Status Cards -->
    <div class="row g-3 mb-4">
        <?php foreach ($serverStatus as $name => $info): ?>
        <div class="col-md-6">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-circle" style="background: <?= $info['available'] ? 'rgba(2, 132, 199, 0.12)' : 'rgba(239, 68, 68, 0.12)' ?>; color: <?= $info['available'] ? '#0284c7' : '#ef4444' ?>;">
                                <i class="bx <?= $info['available'] ? 'bx-check-shield' : 'bx-x-circle' ?> fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark"><?= esc($name) ?></h6>
                                <code class="small text-muted"><?= esc($info['url']) ?></code>
                            </div>
                        </div>
                        <div>
                            <span class="badge <?= $info['available'] ? 'bg-label-info' : 'bg-label-danger' ?> rounded-pill px-3 py-1">
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
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-folder-open text-warning fs-4"></i>
                รายการไฟล์ที่รอ Sync ไปยังเซิร์ฟเวอร์
                <span class="badge bg-warning rounded-pill ms-2 px-3 py-1 text-dark" id="pendingCount"><?= count($pendingFiles) ?></span>
            </h5>
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
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnSyncAll" 
                    <?= (!$hasAvailableServer || empty($pendingFiles)) ? 'disabled' : '' ?>>
                    <i class="bx bx-cloud-upload me-1"></i> Sync ไฟล์ทั้งหมด
                </button>
            </div>
        </div>
        <div class="card-body p-4">
            <?php if (empty($pendingFiles)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bx bx-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">ไม่มีไฟล์ที่รอ Sync</h5>
                    <p class="text-muted small mb-0">ไฟล์รูปถ่ายและเอกสารทั้งหมดถูกอัปโหลดไปยัง Remote Server เรียบร้อยแล้ว</p>
                </div>
            <?php else: ?>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle mb-0" id="filesTable" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th style="min-width: 250px;">ไฟล์</th>
                                <th>ตำแหน่ง Path</th>
                                <th style="width: 120px;">ขนาด</th>
                                <th style="width: 160px;">แก้ไขล่าสุด</th>
                                <th style="width: 100px;" class="text-center">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingFiles as $index => $file): ?>
                            <tr data-path="<?= esc($file['path']) ?>">
                                <td class="text-center fw-bold text-muted"><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($file['type'] === 'image'): ?>
                                            <img src="<?= base_url('uploads/' . $file['path']) ?>" 
                                                 class="rounded-3 border shadow-none" style="width: 42px; height: 42px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                                <i class="bx bx-file text-secondary fs-4"></i>
                                            </div>
                                        <?php endif; ?>
                                        <span class="text-truncate fw-semibold text-dark" style="max-width: 220px;" title="<?= esc($file['filename']) ?>">
                                            <?= esc($file['filename']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <code class="small text-muted"><?= esc($file['path']) ?></code>
                                </td>
                                <td><span class="small fw-semibold"><?= $file['size_formatted'] ?></span></td>
                                <td><span class="small text-muted"><?= $file['modified'] ?></span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-sync-single" 
                                        data-path="<?= esc($file['path']) ?>"
                                        <?= !$hasAvailableServer ? 'disabled' : '' ?>>
                                        <i class="bx bx-cloud-upload me-1"></i> Sync
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

    <!-- Auto Sync Settings Card -->
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-cog text-primary fs-5"></i> การตั้งค่า Auto Sync
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-check form-switch mb-3 p-3 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="autoSyncEnabled" checked style="cursor: pointer;">
                        <label class="form-check-label fw-bold text-dark" for="autoSyncEnabled">
                            เปิดใช้งาน Auto Sync (ตรวจสอบทุก 5 นาที)
                        </label>
                    </div>
                    <small class="text-muted d-block">
                        <i class="bx bx-info-circle me-1"></i>
                        เมื่อเปิดใช้งาน ระบบจะตรวจสอบและซิงก์ไฟล์ขึ้นเซิร์ฟเวอร์หลักอัตโนมัติเมื่อตรวจพบการเชื่อมต่อ
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-time-five text-primary fs-5"></i> สถานะ Auto Sync
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div id="autoSyncStatus" class="p-3 bg-light rounded-3 border mb-3">
                        <span class="text-muted small">กำลังตรวจสอบระบบ...</span>
                    </div>
                    <small class="text-muted d-block">
                        ตรวจสอบล่าสุด: <span id="lastCheck" class="fw-semibold text-dark">-</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white" style="background: var(--primary-gradient);">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bx bx-cloud-upload fs-4"></i> กำลัง Sync ไฟล์
                </h5>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="display-5 fw-bold text-primary" id="progressPercent">0%</div>
                    <p class="text-muted mb-0 small" id="progressText">กำลังเตรียมข้อมูล...</p>
                </div>
                <div class="progress" style="height: 20px; border-radius: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                         role="progressbar" id="progressBar" style="width: 0%">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2 small text-muted">
                    <span id="progressCurrent">0</span>
                    <span id="progressTotal">/ 0 ไฟล์</span>
                </div>
                <div class="mt-3 p-3 bg-light rounded-3 border" id="currentFileInfo" style="display:none;">
                    <small class="text-muted d-block mb-1"><i class="bx bx-file me-1"></i>กำลัง sync ไฟล์:</small>
                    <code class="d-block text-truncate fw-semibold text-dark" id="currentFileName"></code>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let autoSyncInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btnRefresh').addEventListener('click', function() {
        location.reload();
    });

    document.getElementById('btnSyncAll').addEventListener('click', function() {
        syncAllFiles();
    });

    document.querySelectorAll('.btn-sync-single').forEach(btn => {
        btn.addEventListener('click', function() {
            syncSingleFile(this.dataset.path);
        });
    });

    const autoSyncCheckbox = document.getElementById('autoSyncEnabled');
    autoSyncCheckbox.addEventListener('change', function() {
        if (this.checked) {
            startAutoSync();
        } else {
            stopAutoSync();
        }
    });

    if (autoSyncCheckbox.checked) {
        startAutoSync();
    }
});

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
        title: 'ยืนยันการ Sync ไฟล์?',
        html: `<p>ต้องการ Sync ไฟล์ทั้งหมด <strong>${filePaths.length}</strong> ไฟล์ไปยัง Remote Server หรือไม่?</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'ใช่, Sync เลย',
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
        
        const percent = Math.round(((i + 1) / total) * 100);
        document.getElementById('progressPercent').textContent = percent + '%';
        document.getElementById('progressBar').style.width = percent + '%';
        
        if (failed > 0) {
            document.getElementById('progressBar').classList.remove('bg-success');
            document.getElementById('progressBar').classList.add('bg-warning');
        }
    }
    
    progressModal.hide();
    
    const countBadge = document.getElementById('pendingCount');
    if (countBadge) {
        const currentCount = parseInt(countBadge.textContent);
        countBadge.textContent = Math.max(0, currentCount - success);
    }
    
    showSyncResults(total, success, failed, failedDetails);
}

function showSyncResults(total, success, failed, failedDetails) {
    if (failed === 0) {
        Swal.fire({
            icon: 'success',
            title: 'Sync สำเร็จทั้งหมด!',
            html: `<p class="mb-0">Sync สำเร็จ <strong class="text-success">${success}</strong> ไฟล์</p>`,
            confirmButtonText: 'ตกลง'
        }).then(() => {
            location.reload();
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Sync เสร็จสิ้นแต่มีข้อผิดพลาด',
            html: `<p>สำเร็จ: <strong class="text-success">${success}</strong> / ล้มเหลว: <strong class="text-danger">${failed}</strong></p>`,
            confirmButtonText: 'ตกลง'
        }).then(() => {
            location.reload();
        });
    }
}

async function syncSingleFile(filePath) {
    Swal.fire({
        title: 'กำลัง Sync...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const formData = new FormData();
        formData.append('file_path', filePath);
        
        const response = await fetch('<?= base_url('skjadmin/local-sync/sync-single') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Sync สำเร็จ!',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message || 'ไม่สามารถ Sync ได้', 'error');
        }
    } catch (e) {
        Swal.fire('Error', 'เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว', 'error');
    }
}

function startAutoSync() {
    updateAutoSyncStatus('เปิดใช้งาน - ตรวจสอบอัตโนมัติทุก 5 นาที');
    if (!autoSyncInterval) {
        autoSyncInterval = setInterval(checkAndAutoSync, 5 * 60 * 1000);
    }
}

function stopAutoSync() {
    updateAutoSyncStatus('ปิดการทำงาน');
    if (autoSyncInterval) {
        clearInterval(autoSyncInterval);
        autoSyncInterval = null;
    }
}

function updateAutoSyncStatus(status) {
    const el = document.getElementById('autoSyncStatus');
    if (el) el.innerHTML = `<span class="fw-semibold text-primary"><i class="bx bx-check-circle me-1"></i>${status}</span>`;
    const last = document.getElementById('lastCheck');
    if (last) last.textContent = new Date().toLocaleTimeString('th-TH');
}

function checkAndAutoSync() {
    updateAutoSyncStatus('กำลังตรวจสอบไฟล์ใหม่...');
}
</script>
<?= $this->endSection() ?>

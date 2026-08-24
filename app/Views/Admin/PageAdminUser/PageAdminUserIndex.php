<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 10px !important;
        min-height: 42px !important;
        border-color: #cbd5e1 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-user-badge fs-3 text-primary"></i>
                จัดการผู้ใช้งานระบบ (Admin Users)
            </h4>
            <p class="text-muted mb-0 small">จัดการสิทธิ์การเข้าใช้งานระบบรับสมัครนักเรียนของผู้ดูแลระบบและบุคลากร</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bx bx-plus me-1"></i> เพิ่มผู้ดูแลระบบ
        </button>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-list-ul text-primary fs-4"></i>
                รายชื่อผู้ดูแลระบบทั้งหมด
            </h5>
            <span class="badge bg-label-primary rounded-pill px-3 py-1"><?= count($users ?? []) ?> บัญชี</span>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle mb-0" id="usersTable" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 250px;">ผู้ใช้งาน</th>
                            <th style="width: 150px;" class="text-center">สถานะ / บทบาท</th>
                            <th style="min-width: 180px;">ตำแหน่ง</th>
                            <th style="width: 100px;" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php
                            $userImg = !empty($user->pers_img)
                                ? "https://personnel.skj.ac.th/uploads/admin/Personnal/" . $user->pers_img
                                : base_url('public/sneat-assets/img/avatars/1.png');
                            $isSuper = (stripos($user->admin_rloes_status, 'super') !== false);
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= $userImg ?>" alt="Avatar" class="rounded-circle border" 
                                             width="44" height="44" style="object-fit: cover;" loading="lazy"
                                             onerror="this.src='<?= base_url('public/sneat-assets/img/avatars/1.png') ?>'">
                                        <div>
                                            <div class="fw-bold text-dark mb-0"><?= esc($user->pers_prefix . $user->pers_firstname . ' ' . $user->pers_lastname) ?></div>
                                            <code class="small text-muted font-monospace"><?= esc($user->pers_username) ?></code>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-label-<?= $isSuper ? 'danger' : 'primary' ?> rounded-pill px-3 py-1 fw-bold">
                                        <?= esc($user->admin_rloes_status) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-secondary fw-semibold"><?= esc($user->admin_rloes_academic_position ?: '-') ?></span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                        onclick="confirmDeleteUser('<?= esc($user->admin_rloes_id) ?>', '<?= esc($user->pers_firstname . ' ' . $user->pers_lastname) ?>')">
                                        <i class="bx bx-trash me-1"></i> ลบสิทธิ์
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white" style="background: var(--primary-gradient);">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bx bx-user-plus fs-4"></i> เพิ่มผู้ดูแลระบบใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="searchPersonnel" class="form-label fw-bold text-dark small">ค้นหาบุคลากร (ชื่อ-นามสกุล) <span class="text-danger">*</span></label>
                    <select id="searchPersonnel" class="form-select" style="width: 100%;"></select>
                </div>
                <div class="mb-3">
                    <label for="userRole" class="form-label fw-bold text-dark small">สิทธิ์การใช้งาน <span class="text-danger">*</span></label>
                    <select id="userRole" class="form-select rounded-3">
                        <option value="Admin">Admin (ผู้ดูแลระบบทั่วไป)</option>
                        <option value="Super Admin">Super Admin (ผู้ดูแลระบบสูงสุด)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="userPosition" class="form-label fw-bold text-dark small">ตำแหน่ง / ฝ่ายงาน</label>
                    <input type="text" id="userPosition" class="form-control rounded-3" placeholder="เช่น เจ้าหน้าที่รับสมัคร, หัวหน้างานทะเบียน">
                </div>
            </div>
            <div class="modal-footer border-top p-3 bg-light">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary rounded-pill px-5 shadow-sm" onclick="saveUser()">
                    <i class="bx bx-save me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#usersTable').DataTable({
            responsive: true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });

        $('#searchPersonnel').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#addUserModal'),
            placeholder: 'พิมพ์ชื่อหรือนามสกุลเพื่อค้นหา...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '<?= base_url('skjadmin/users/search') ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { term: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });
    });

    function saveUser() {
        const userId = $('#searchPersonnel').val();
        const role = $('#userRole').val();
        const position = $('#userPosition').val();

        if (!userId) {
            Swal.fire('แจ้งเตือน', 'กรุณาค้นหาและเลือกบุคลากร', 'warning');
            return;
        }

        Swal.fire({
            title: 'กำลังบันทึก...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: '<?= base_url('skjadmin/users/create') ?>',
            method: 'POST',
            data: {
                user_id: userId,
                role: role,
                position: position
            },
            success: function (response) {
                if (response.success) {
                    $('#addUserModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: response.msg,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', response.msg, 'error');
                }
            },
            error: function () {
                Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
            }
        });
    }

    function confirmDeleteUser(id, name) {
        Swal.fire({
            title: 'ยืนยันการลบสิทธิ์?',
            html: `คุณต้องการลบสิทธิ์ผู้ดูแลระบบของ <br><strong class="text-primary">${name}</strong><br> ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('skjadmin/users/delete/') ?>/' + id;
            }
        });
    }
</script>
<?= $this->endSection() ?>
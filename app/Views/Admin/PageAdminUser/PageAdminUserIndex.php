<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการระบบ /</span> ผู้ใช้งาน (Users)</h4>

    <div class="card p-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">รายชื่อผู้ดูแลระบบ</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bx bx-plus me-1"></i> เพิ่มผู้ใช้งาน
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th>ผู้ใช้งาน</th>
                        <th>สถานะ/บทบาท</th>
                        <th>ตำแหน่ง</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <?php if(!empty($user->pers_img)): ?>
                                        <img src="https://skj.ac.th/uploads/personnel/<?= $user->pers_img ?>" alt="Avatar" class="rounded-circle">
                                    <?php else: ?>
                                        <span class="avatar-initial rounded-circle bg-label-primary"><?= mb_substr($user->pers_firstname, 0, 1) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong><?= $user->pers_prefix . $user->pers_firstname . ' ' . $user->pers_lastname ?></strong>
                                    <div class="text-muted small"><?= $user->pers_username ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-label-primary me-1"><?= $user->admin_rloes_status ?></span></td>
                        <td><?= $user->admin_rloes_academic_position ?></td>
                        <td>
                            <a href="<?= base_url('skjadmin/users/delete/' . $user->admin_rloes_id) ?>" 
                               class="btn btn-sm btn-icon btn-outline-danger"
                               onclick="return confirm('ยืนยันการลบสิทธิ์ผู้ใช้งานนี้?');">
                                <i class="bx bx-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">เพิ่มผู้ดูแลระบบ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="searchPersonnel" class="form-label">ค้นหาบุคลากร (ชื่อ-สกุล)</label>
                    <select id="searchPersonnel" class="form-select" style="width: 100%;"></select>
                </div>
                <div class="mb-3">
                    <label for="userRole" class="form-label">สิทธิ์การใช้งาน</label>
                    <select id="userRole" class="form-select">
                        <option value="Admin">Admin (ผู้ดูแลทั่วไป)</option>
                        <option value="Super Admin">Super Admin (ผู้ดูแลสูงสุด)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="userPosition" class="form-label">ตำแหน่ง</label>
                    <input type="text" id="userPosition" class="form-control" placeholder="ระบุตำแหน่ง">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });

        // Initialize Select2
        $('#searchPersonnel').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#addUserModal'),
            placeholder: 'พิมพ์ชื่อเพื่อค้นหา...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '<?= base_url('skjadmin/users/search') ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
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
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกบุคลากร', 'warning');
            return;
        }

        $.ajax({
            url: '<?= base_url('skjadmin/users/create') ?>',
            method: 'POST',
            data: {
                user_id: userId,
                role: role,
                position: position
            },
            success: function(response) {
                if (response.success) {
                    $('#addUserModal').modal('hide');
                    Swal.fire('สำเร็จ', response.msg, 'success').then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', response.msg, 'error');
                }
            },
            error: function() {
                Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            }
        });
    }
</script>
<?= $this->endSection() ?>

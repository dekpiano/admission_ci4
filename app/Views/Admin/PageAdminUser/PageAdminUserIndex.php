<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการระบบ /</span> ผู้ใช้งาน (Users)</h4>

    <div class="card">
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
                    <input type="text" id="searchPersonnel" class="form-control" placeholder="พิมพ์ชื่อเพื่อค้นหา...">
                    <div id="searchResults" class="list-group mt-2" style="max-height: 200px; overflow-y: auto; display: none;"></div>
                </div>
                <input type="hidden" id="selectedUserId">
                <div class="mb-3">
                    <label for="userRole" class="form-label">สิทธิ์การใช้งาน</label>
                    <select id="userRole" class="form-select">
                        <option value="Admin">Admin (ผู้ดูแลทั่วไป)</option>
                        <option value="Super Admin">Super Admin (ผู้ดูแลสูงสุด)</option>
                    </select>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });
    });

    let searchTimeout;
    const searchInput = document.getElementById('searchPersonnel');
    const resultsDiv = document.getElementById('searchResults');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const term = this.value;
        
        if (term.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`<?= base_url('skjadmin/users/search') ?>?term=${term}`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const a = document.createElement('a');
                            a.className = 'list-group-item list-group-item-action';
                            a.href = '#';
                            a.textContent = item.text;
                            a.onclick = (e) => {
                                e.preventDefault();
                                selectUser(item.id, item.text);
                            };
                            resultsDiv.appendChild(a);
                        });
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.style.display = 'none';
                    }
                });
        }, 500);
    });

    function selectUser(id, name) {
        document.getElementById('selectedUserId').value = id;
        searchInput.value = name;
        resultsDiv.style.display = 'none';
    }

    function saveUser() {
        const userId = document.getElementById('selectedUserId').value;
        const role = document.getElementById('userRole').value;

        if (!userId) {
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกบุคลากร', 'warning');
            return;
        }

        fetch('<?= base_url('skjadmin/users/create') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `user_id=${userId}&role=${role}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('สำเร็จ', data.msg, 'success').then(() => location.reload());
            } else {
                Swal.fire('ผิดพลาด', data.msg, 'error');
            }
        });
    }
</script>
<?= $this->endSection() ?>

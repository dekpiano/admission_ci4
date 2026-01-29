<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div>
                    <h5 class="mb-1"><i class="bx bxl-line me-2 text-success"></i>จัดการ LINE แจ้งเตือน Admin</h5>
                    <small class="text-muted">ส่งแจ้งเตือนเข้า LINE ส่วนตัวของ Admin ที่ลงทะเบียน</small>
                </div>
                <?php if (isset($tableExists) && $tableExists): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bx bx-plus me-1"></i> เพิ่มด้วยตนเอง
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (!isset($tableExists) || !$tableExists): ?>
                    <!-- ยังไม่มีตาราง -->
                    <div class="text-center py-5">
                        <i class="bx bx-data text-muted" style="font-size: 5rem;"></i>
                        <h5 class="mt-3">ยังไม่มีตาราง tb_line_admins</h5>
                        <p class="text-muted mb-4">คลิกปุ่มด้านล่างเพื่อสร้างตารางในฐานข้อมูล</p>
                        <button type="button" class="btn btn-primary btn-lg" id="btnCreateTable">
                            <i class="bx bx-plus-circle me-2"></i> สร้างตาราง
                        </button>
                    </div>
                <?php else: ?>
                    <!-- วิธีลงทะเบียน -->
                    <div class="alert alert-success mb-4" role="alert">
                        <h6 class="alert-heading fw-bold mb-2"><i class="bx bx-info-circle me-1"></i> วิธีลงทะเบียนรับแจ้งเตือน</h6>
                        <ol class="mb-0 ps-3">
                            <li>เพิ่มเพื่อน LINE OA: <strong>@830norfz</strong> (Admission SKJ)</li>
                            <li>พิมพ์ "<strong>ลงทะเบียน</strong>" ใน LINE Chat</li>
                            <li>ระบบจะบันทึกและเริ่มส่งแจ้งเตือนให้อัตโนมัติ</li>
                        </ol>
                    </div>

                    <!-- Webhook URL (สำหรับ Dev) -->
                    <?php if (isset($webhookUrl)): ?>
                        <div class="alert alert-secondary mb-4" role="alert">
                            <h6 class="alert-heading fw-bold mb-2"><i class="bx bx-code-alt me-1"></i> Webhook URL (สำหรับตั้งค่า LINE Developers)</h6>
                            <code class="user-select-all"><?= esc($webhookUrl) ?></code>
                        </div>
                    <?php endif; ?>

                    <!-- ตารางแสดง Admins -->
                    <?php if (empty($admins)): ?>
                        <div class="text-center py-5">
                            <i class="bx bx-user-x text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">ยังไม่มี Admin ลงทะเบียน</h5>
                            <p class="text-muted">ให้ Admin เพิ่มเพื่อน LINE OA และพิมพ์ "ลงทะเบียน"</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>โปรไฟล์</th>
                                        <th>ชื่อ</th>
                                        <th>LINE User ID</th>
                                        <th class="text-center">สถานะ</th>
                                        <th class="text-center">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($admins as $index => $admin): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <?php if (!empty($admin->line_picture_url)): ?>
                                                    <img src="<?= esc($admin->line_picture_url) ?>" 
                                                        alt="Profile" 
                                                        class="rounded-circle" 
                                                        width="40" height="40"
                                                        style="object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bx bx-user text-white"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= esc($admin->line_display_name) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($admin->line_created)) ?></small>
                                            </td>
                                            <td>
                                                <code class="user-select-all small"><?= esc($admin->line_user_id) ?></code>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input toggle-status" type="checkbox" 
                                                        data-id="<?= $admin->line_admin_id ?>" 
                                                        <?= $admin->line_status ? 'checked' : '' ?>>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-success btn-test" 
                                                    data-id="<?= $admin->line_admin_id ?>" title="ทดสอบส่ง">
                                                    <i class="bx bx-send"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" 
                                                    data-id="<?= $admin->line_admin_id ?>" 
                                                    data-name="<?= esc($admin->line_display_name) ?>" title="ลบ">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal เพิ่มด้วยตนเอง -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-plus-circle me-2 text-primary"></i>เพิ่ม Admin ด้วยตนเอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAdd">
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <small><i class="bx bx-info-circle me-1"></i> ใช้กรณีที่รู้ LINE User ID แล้ว</small>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="line_user_id" name="line_user_id" placeholder="LINE User ID" required>
                        <label for="line_user_id">LINE User ID (เริ่มด้วย U...)</label>
                    </div>
                    <div class="form-floating mb-0">
                        <input type="text" class="form-control" id="line_display_name" name="line_display_name" placeholder="ชื่อ">
                        <label for="line_display_name">ชื่อ (ไม่บังคับ)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        // สร้างตาราง
        $('#btnCreateTable').on('click', function () {
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>กำลังสร้าง...');

            $.ajax({
                url: '<?= site_url('skjadmin/line-notify/create-table') ?>',
                type: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                        btn.prop('disabled', false).html('<i class="bx bx-plus-circle me-2"></i> สร้างตาราง');
                    }
                },
                error: function () {
                    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์', 'error');
                    btn.prop('disabled', false).html('<i class="bx bx-plus-circle me-2"></i> สร้างตาราง');
                }
            });
        });

        // เพิ่มด้วยตนเอง
        $('#formAdd').on('submit', function (e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>กำลังบันทึก...');

            $.ajax({
                url: '<?= site_url('skjadmin/line-notify/add-manual') ?>',
                type: 'POST',
                data: $(this).serialize(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึก');
                    }
                },
                error: function () {
                    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์', 'error');
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึก');
                }
            });
        });

        // Toggle สถานะ
        $('.toggle-status').on('change', function () {
            const id = $(this).data('id');
            const status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '<?= site_url('skjadmin/line-notify/toggle-status') ?>',
                type: 'POST',
                data: { id: id, status: status },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (res) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: res.status === 'success' ? 'success' : 'error',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        });

        // ทดสอบส่ง
        $('.btn-test').on('click', function () {
            const btn = $(this);
            const id = btn.data('id');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: '<?= site_url('skjadmin/line-notify/test') ?>/' + id,
                type: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (res) {
                    Swal.fire({
                        icon: res.status === 'success' ? 'success' : 'error',
                        title: res.status === 'success' ? 'สำเร็จ!' : 'เกิดข้อผิดพลาด',
                        text: res.message
                    });
                    btn.prop('disabled', false).html('<i class="bx bx-send"></i>');
                },
                error: function () {
                    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์', 'error');
                    btn.prop('disabled', false).html('<i class="bx bx-send"></i>');
                }
            });
        });

        // ลบ
        $('.btn-delete').on('click', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'ยืนยันการลบ?',
                html: `คุณต้องการลบ <strong>${name}</strong> ออกจากรายการหรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url('skjadmin/line-notify/delete') ?>/' + id,
                        type: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function (res) {
                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบสำเร็จ!',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

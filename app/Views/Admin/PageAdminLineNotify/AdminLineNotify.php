<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxl-line fs-3" style="color: #00c300;"></i>
                จัดการ LINE แจ้งเตือน Admin
            </h4>
            <p class="text-muted mb-0 small">ส่งข้อความแจ้งเตือนเมื่อมีนักเรียนสมัครใหม่หรือรายงานตัวเข้าสู่ LINE ส่วนตัวของผู้ดูแลระบบ</p>
        </div>
        <?php if (isset($tableExists) && $tableExists): ?>
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bx bx-plus me-1"></i> เพิ่ม Admin ด้วยตนเอง
            </button>
        <?php endif; ?>
    </div>

    <?php if (!isset($tableExists) || !$tableExists): ?>
        <!-- Setup Table State -->
        <div class="card border-0 rounded-4 shadow-sm bg-white text-center py-5">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bx bx-data text-muted opacity-50" style="font-size: 5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark">ยังไม่มีตาราง tb_line_admins ในระบบ</h5>
                <p class="text-muted small mb-4">คลิกปุ่มด้านล่างเพื่อสร้างตารางจัดเก็บข้อมูลผู้รับการแจ้งเตือน LINE</p>
                <button type="button" class="btn btn-primary rounded-pill px-5 shadow-sm" id="btnCreateTable">
                    <i class="bx bx-plus-circle me-2"></i> สร้างตารางข้อมูล
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4 mb-4">
            <!-- How to register Card -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-info-circle text-success fs-5"></i> ขั้นตอนการลงทะเบียนรับแจ้งเตือน
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <ol class="mb-0 ps-3 text-secondary">
                            <li class="mb-2">เพิ่มเพื่อน LINE Official Account: <strong class="text-dark">@830norfz</strong> (Admission SKJ)</li>
                            <li class="mb-2">พิมพ์คำว่า "<strong>ลงทะเบียน</strong>" ในช่องแชท LINE</li>
                            <li>ระบบจะบันทึก LINE User ID และเริ่มส่งแจ้งเตือนให้อัตโนมัติทันที</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Webhook Card -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-code-alt text-primary fs-5"></i> Webhook URL (สำหรับ LINE Developers)
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2">URL สำหรับนำไปตั้งค่า Webhook ใน LINE Developers Console:</p>
                        <div class="p-3 bg-light rounded-3 border">
                            <code class="user-select-all small fw-bold text-primary d-block text-break">
                                <?= esc($webhookUrl ?? base_url('api/line/webhook')) ?>
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admins Table Card -->
        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bx bx-user-check text-primary fs-4"></i>
                    รายชื่อ Admin ที่เปิดรับแจ้งเตือน
                </h5>
                <span class="badge bg-label-success rounded-pill px-3 py-1"><?= count($admins ?? []) ?> คน</span>
            </div>
            <div class="card-body p-4">
                <?php if (empty($admins)): ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bx bx-user-x text-muted opacity-50" style="font-size: 4rem;"></i>
                        </div>
                        <h6 class="fw-bold text-dark">ยังไม่มี Admin ลงทะเบียน</h6>
                        <p class="text-muted small mb-0">ให้ผู้ดูแลระบบเพิ่มเพื่อน LINE OA แล้วพิมพ์ "ลงทะเบียน"</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover align-middle mb-0" style="width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;" class="text-center">#</th>
                                    <th style="width: 70px;" class="text-center">รูปโปรไฟล์</th>
                                    <th>ชื่อแสดงใน LINE</th>
                                    <th>LINE User ID</th>
                                    <th style="width: 120px;" class="text-center">สถานะรับแจ้งเตือน</th>
                                    <th style="width: 120px;" class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $index => $admin): ?>
                                    <tr>
                                        <td class="text-center fw-bold text-muted"><?= $index + 1 ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($admin->line_picture_url)): ?>
                                                <img src="<?= esc($admin->line_picture_url) ?>" 
                                                    alt="Profile" 
                                                    class="rounded-circle border" 
                                                    width="42" height="42"
                                                    style="object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                                    style="width: 42px; height: 42px;">
                                                    <i class="bx bx-user text-secondary fs-4"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= esc($admin->line_display_name) ?></div>
                                            <small class="text-muted">ลงทะเบียนเมื่อ: <?= date('d/m/Y H:i', strtotime($admin->line_created)) ?></small>
                                        </td>
                                        <td>
                                            <code class="user-select-all small text-secondary font-monospace"><?= esc($admin->line_user_id) ?></code>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input toggle-status" type="checkbox" 
                                                    data-id="<?= $admin->line_admin_id ?>" 
                                                    <?= $admin->line_status ? 'checked' : '' ?> style="cursor: pointer;">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-test me-1" 
                                                data-id="<?= $admin->line_admin_id ?>" title="ทดสอบส่งข้อความ">
                                                <i class="bx bx-send"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-delete" 
                                                data-id="<?= $admin->line_admin_id ?>" 
                                                data-name="<?= esc($admin->line_display_name) ?>" title="ลบผู้รับแจ้งเตือน">
                                                <i class="bx bx-trash"></i>
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
    <?php endif; ?>
</div>

<!-- Modal เพิ่มด้วยตนเอง -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white" style="background: #00c300;">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bx bx-plus-circle fs-4"></i> เพิ่ม Admin ด้วยตนเอง
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAdd">
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 mb-3" style="background: rgba(2, 132, 199, 0.08);">
                        <small class="text-dark"><i class="bx bx-info-circle me-1 text-info"></i> ใช้สำหรับกรณีที่ทราบ LINE User ID ของผู้ดูแลระบบแล้ว</small>
                    </div>
                    <div class="mb-3">
                        <label for="line_user_id" class="form-label fw-bold text-dark small">LINE User ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3 font-monospace" id="line_user_id" name="line_user_id" placeholder="เช่น U1234567890abcdef..." required>
                    </div>
                    <div class="mb-3">
                        <label for="line_display_name" class="form-label fw-bold text-dark small">ชื่อที่ต้องการแสดง (ไม่บังคับ)</label>
                        <input type="text" class="form-control rounded-3" id="line_display_name" name="line_display_name" placeholder="ชื่อ-นามสกุล">
                    </div>
                </div>
                <div class="modal-footer border-top p-3 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 shadow-sm">
                        <i class="bx bx-save me-1"></i> บันทึกข้อมูล
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

        $('.btn-delete').on('click', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'ยืนยันการลบ?',
                html: `คุณต้องการลบ <strong>${name}</strong> ออกจากรายการหรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ใช่, ลบเลย',
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

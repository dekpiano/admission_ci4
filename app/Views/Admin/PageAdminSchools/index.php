<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<style>
    .school-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxs-building fs-3 text-primary"></i>
                จัดการฐานข้อมูลโรงเรียนทั้งหมด
            </h4>
            <p class="text-muted mb-0 small">ฐานข้อมูลโรงเรียนเดิมของผู้สมัครทั่วประเทศสำหรับระบบแนะนำอัตโนมัติ</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Schools Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold d-block mb-1">โรงเรียนทั้งหมดในฐานข้อมูล</span>
                            <h2 class="fw-bold text-primary mb-0"><?= number_format($stats['total'] ?? 0) ?></h2>
                            <small class="text-muted">แห่งทั่วประเทศ</small>
                        </div>
                        <div class="school-stat-icon" style="background: rgba(255, 107, 139, 0.15); color: #ff6b8b;">
                            <i class="bx bxs-school"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Provinces Card -->
        <div class="col-lg-8 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-2 px-4 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-map-pin text-primary"></i>
                        จังหวัดที่มีโรงเรียนมากที่สุด (10 อันดับแรก)
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <?php foreach (($stats['by_province'] ?? []) as $index => $prov): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="d-flex align-items-center p-2 rounded-3 bg-light border">
                                <span class="badge bg-label-primary rounded-pill me-2 px-2"><?= $index + 1 ?></span>
                                <div class="overflow-hidden">
                                    <span class="d-block text-truncate fw-semibold text-dark small" title="<?= esc($prov->schoola_province) ?>">
                                        <?= esc($prov->schoola_province ?: 'ไม่ระบุ') ?>
                                    </span>
                                    <small class="text-muted"><?= number_format($prov->count) ?> แห่ง</small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add School Form Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-plus-circle text-primary fs-4"></i>
                เพิ่มโรงเรียนใหม่เข้าสู่ฐานข้อมูล
            </h5>
            <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle" data-bs-toggle="collapse" data-bs-target="#addSchoolForm" title="ย่อ/ขยายฟอร์ม">
                <i class="bx bx-chevron-down fs-5"></i>
            </button>
        </div>
        <div class="collapse show" id="addSchoolForm">
            <div class="card-body p-4">
                <form id="formAddSchool" class="ajax-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="add_school_name" class="form-label fw-bold text-dark small">ชื่อโรงเรียน <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="add_school_name" name="school_name" placeholder="เช่น โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์" required>
                        </div>
                        <div class="col-md-6">
                            <label for="add_school_province" class="form-label fw-bold text-dark small">จังหวัด</label>
                            <input type="text" class="form-control rounded-3" id="add_school_province" name="school_province" placeholder="เช่น นครสวรรค์">
                        </div>
                        <div class="col-md-6">
                            <label for="add_school_amphur" class="form-label fw-bold text-dark small">อำเภอ / เขต</label>
                            <input type="text" class="form-control rounded-3" id="add_school_amphur" name="school_amphur" placeholder="เช่น เมืองนครสวรรค์">
                        </div>
                        <div class="col-md-6">
                            <label for="add_school_district" class="form-label fw-bold text-dark small">ตำบล / แขวง</label>
                            <input type="text" class="form-control rounded-3" id="add_school_district" name="school_district" placeholder="เช่น นครสวรรค์ออก">
                        </div>
                    </div>
                    <div class="text-end mt-4 pt-2 border-top">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-2" id="btnResetAdd">
                            <i class="bx bx-refresh me-1"></i> ล้างฟอร์ม
                        </button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                            <i class="bx bx-plus me-1"></i> เพิ่มโรงเรียน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Schools Table Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-list-ul text-primary fs-4"></i>
                รายชื่อโรงเรียนทั้งหมด
            </h5>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" id="btnRefreshTable">
                <i class="bx bx-refresh me-1"></i> รีเฟรชข้อมูล
            </button>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle" id="tableSchools" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;" class="text-center">รหัส</th>
                            <th style="min-width: 250px;">ชื่อโรงเรียน</th>
                            <th style="width: 140px;">อำเภอ / เขต</th>
                            <th style="width: 140px;">ตำบล / แขวง</th>
                            <th style="width: 140px;">จังหวัด</th>
                            <th style="width: 100px;" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loaded via Ajax DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit School Modal -->
<div class="modal fade" id="editSchoolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <form id="formEditSchool" class="ajax-form">
                <div class="modal-header text-white" style="background: var(--primary-gradient);">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                        <i class="bx bx-edit fs-4"></i> แก้ไขข้อมูลโรงเรียน
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="edit_school_id" name="school_id">
                    <div class="mb-3">
                        <label for="edit_school_name" class="form-label fw-bold text-dark small">ชื่อโรงเรียน <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="edit_school_name" name="school_name" placeholder="ชื่อโรงเรียน" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_school_province" class="form-label fw-bold text-dark small">จังหวัด</label>
                        <input type="text" class="form-control rounded-3" id="edit_school_province" name="school_province" placeholder="จังหวัด">
                    </div>
                    <div class="mb-3">
                        <label for="edit_school_amphur" class="form-label fw-bold text-dark small">อำเภอ / เขต</label>
                        <input type="text" class="form-control rounded-3" id="edit_school_amphur" name="school_amphur" placeholder="อำเภอ/เขต">
                    </div>
                    <div class="mb-3">
                        <label for="edit_school_district" class="form-label fw-bold text-dark small">ตำบล / แขวง</label>
                        <input type="text" class="form-control rounded-3" id="edit_school_district" name="school_district" placeholder="ตำบล/แขวง">
                    </div>
                </div>
                <div class="modal-footer border-top p-3 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="bx bx-save me-1"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const table = $('#tableSchools').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('skjadmin/schools/ajax') ?>',
            type: 'POST'
        },
        columns: [
            { data: 'schoola_id', className: 'text-center fw-bold text-muted' },
            { 
                data: 'schoola_name',
                render: function(data) {
                    return `<span class="fw-bold text-dark">${data}</span>`;
                }
            },
            { data: 'schoola_amphur', render: function(data) { return data || '-'; } },
            { data: 'schoola_district', render: function(data) { return data || '-'; } },
            { 
                data: 'schoola_province',
                render: function(data) {
                    return data ? `<span class="badge bg-label-info rounded-pill px-3 py-1">${data}</span>` : '-';
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded fs-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                <a class="dropdown-item py-2 btn-edit" href="javascript:void(0);" data-id="${row.schoola_id}">
                                    <i class="bx bx-edit-alt text-warning me-2"></i> แก้ไขข้อมูล
                                </a>
                                <a class="dropdown-item py-2 text-danger btn-delete" href="javascript:void(0);" data-id="${row.schoola_id}" data-name="${row.schoola_name}">
                                    <i class="bx bx-trash me-2"></i> ลบโรงเรียน
                                </a>
                            </div>
                        </div>
                    `;
                }
            }
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
        },
        pageLength: 25,
        order: [[0, 'asc']]
    });

    $('#btnRefreshTable').click(function() {
        table.ajax.reload();
    });

    $('#btnResetAdd').click(function() {
        $('#formAddSchool')[0].reset();
    });

    $('#formAddSchool').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const $btn = $('#formAddSchool button[type="submit"]');
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>กำลังบันทึก...');

        $.ajax({
            url: '<?= base_url('skjadmin/schools/add') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $btn.prop('disabled', false).html('<i class="bx bx-plus me-1"></i>เพิ่มโรงเรียน');
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    $('#formAddSchool')[0].reset();
                    table.ajax.reload(null, false);
                    $('#add_school_name').focus();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="bx bx-plus me-1"></i>เพิ่มโรงเรียน');
                const response = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: response?.message || 'ไม่สามารถเพิ่มข้อมูลได้'
                });
            }
        });
    });

    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url('skjadmin/schools/get') ?>/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const school = response.data;
                    $('#edit_school_id').val(school.schoola_id);
                    $('#edit_school_name').val(school.schoola_name);
                    $('#edit_school_province').val(school.schoola_province);
                    $('#edit_school_amphur').val(school.schoola_amphur);
                    $('#edit_school_district').val(school.schoola_district);
                    $('#editSchoolModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message
                    });
                }
            }
        });
    });

    $('#formEditSchool').submit(function(e) {
        e.preventDefault();
        const id = $('#edit_school_id').val();
        const formData = $(this).serialize();

        $.ajax({
            url: '<?= base_url('skjadmin/schools/update') ?>/' + id,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#editSchoolModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: response?.message || 'ไม่สามารถแก้ไขข้อมูลได้'
                });
            }
        });
    });

    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบ?',
            html: `คุณต้องการลบโรงเรียน<br><strong class="text-primary">"${name}"</strong><br>ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('skjadmin/schools/delete') ?>/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: response?.message || 'ไม่สามารถลบข้อมูลได้'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

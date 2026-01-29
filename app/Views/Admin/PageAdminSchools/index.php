<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">จัดการข้อมูล /</span> โรงเรียนทั้งหมด
    </h4>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-label-primary rounded-pill mb-2">สถิติ</span>
                            <h4 class="card-title mb-1 fw-bold text-primary"><?= number_format($stats['total'] ?? 0) ?></h4>
                            <small class="text-muted">โรงเรียนทั้งหมดในระบบ</small>
                        </div>
                        <div class="avatar avatar-md bg-primary rounded-circle">
                            <i class="bx bx-building fs-4 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <h6 class="mb-0">จังหวัดที่มีโรงเรียนมากที่สุด (10 อันดับ)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php foreach (($stats['by_province'] ?? []) as $index => $prov): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-<?= ['primary', 'success', 'info', 'warning', 'danger', 'secondary', 'dark', 'primary', 'success', 'info'][$index % 10] ?> me-2"><?= $index + 1 ?></span>
                                <div>
                                    <small class="d-block text-truncate" style="max-width: 120px;" title="<?= $prov->schoola_province ?>"><?= $prov->schoola_province ?: 'ไม่ระบุ' ?></small>
                                    <small class="text-muted"><?= number_format($prov->count) ?> โรง</small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add School Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-plus-circle me-2"></i>เพิ่มโรงเรียนใหม่</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#addSchoolForm">
                <i class="bx bx-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="addSchoolForm">
            <div class="card-body">
                <form id="formAddSchool" class="ajax-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="add_school_name" name="school_name" placeholder="ชื่อโรงเรียน" required>
                                <label for="add_school_name">ชื่อโรงเรียน <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="add_school_province" name="school_province" placeholder="จังหวัด">
                                <label for="add_school_province">จังหวัด</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="add_school_amphur" name="school_amphur" placeholder="อำเภอ/เขต">
                                <label for="add_school_amphur">อำเภอ/เขต</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="add_school_district" name="school_district" placeholder="ตำบล/แขวง">
                                <label for="add_school_district">ตำบล/แขวง</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary me-2" id="btnResetAdd">
                            <i class="bx bx-x-circle me-1"></i>ล้างฟอร์ม
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>เพิ่มโรงเรียน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Schools Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i>รายชื่อโรงเรียนทั้งหมด</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefreshTable">
                    <i class="bx bx-refresh me-1"></i>รีเฟรช
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="tableSchools">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">รหัส</th>
                        <th>ชื่อโรงเรียน</th>
                        <th>อำเภอ/เขต</th>
                        <th>ตำบล/แขวง</th>
                        <th>จังหวัด</th>
                        <th style="width: 120px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit School Modal -->
<div class="modal fade" id="editSchoolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditSchool" class="ajax-form">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bx bx-edit me-2"></i>แก้ไขข้อมูลโรงเรียน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_school_id" name="school_id">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="edit_school_name" name="school_name" placeholder="ชื่อโรงเรียน" required>
                            <label for="edit_school_name">ชื่อโรงเรียน <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="edit_school_province" name="school_province" placeholder="จังหวัด">
                            <label for="edit_school_province">จังหวัด</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="edit_school_amphur" name="school_amphur" placeholder="อำเภอ/เขต">
                            <label for="edit_school_amphur">อำเภอ/เขต</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="edit_school_district" name="school_district" placeholder="ตำบล/แขวง">
                            <label for="edit_school_district">ตำบล/แขวง</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i>บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(105, 108, 255, 0.08);
    }
    .dataTables_wrapper .dataTables_length select {
        padding-right: 2rem !important;
    }
    .avatar-md {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#tableSchools').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('skjadmin/schools/ajax') ?>',
            type: 'POST'
        },
        columns: [
            { data: 'schoola_id' },
            { data: 'schoola_name' },
            { data: 'schoola_amphur', render: function(data) { return data || '-'; } },
            { data: 'schoola_district', render: function(data) { return data || '-'; } },
            { data: 'schoola_province', render: function(data) { return data || '-'; } },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-icon btn-outline-primary btn-edit me-1" data-id="${row.schoola_id}" title="แก้ไข">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-outline-danger btn-delete" data-id="${row.schoola_id}" data-name="${row.schoola_name}" title="ลบ">
                            <i class="bx bx-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: {
            processing: "กำลังประมวลผล...",
            search: "ค้นหา:",
            lengthMenu: "แสดง _MENU_ รายการ",
            info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            infoEmpty: "แสดง 0 ถึง 0 จาก 0 รายการ",
            infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
            infoPostFix: "",
            loadingRecords: "กำลังโหลดข้อมูล...",
            zeroRecords: "ไม่พบข้อมูล",
            emptyTable: "ไม่มีข้อมูลในตาราง",
            paginate: {
                first: "หน้าแรก",
                previous: "ก่อนหน้า",
                next: "ถัดไป",
                last: "หน้าสุดท้าย"
            }
        },
        pageLength: 25,
        order: [[0, 'asc']]
    });

    // Refresh table
    $('#btnRefreshTable').click(function() {
        table.ajax.reload();
    });

    // Reset add form
    $('#btnResetAdd').click(function() {
        $('#formAddSchool')[0].reset();
    });

    // Add school
    $('#formAddSchool').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const $btn = $('#formAddSchool button[type="submit"]');
        
        // Disable button and show loading
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>กำลังบันทึก...');

        $.ajax({
            url: '<?= base_url('skjadmin/schools/add') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Re-enable button first
                $btn.prop('disabled', false).html('<i class="bx bx-plus me-1"></i>เพิ่มโรงเรียน');
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Reset form completely
                    $('#formAddSchool')[0].reset();
                    $('#add_school_name, #add_school_province, #add_school_amphur, #add_school_district').val('');
                    
                    // Reload table without page refresh
                    table.ajax.reload(null, false);
                    
                    // Focus on first input
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
                // Re-enable button on error
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



    // Edit school - load data
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

    // Edit school - submit
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
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.ajax.reload();
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

    // Delete school
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบ?',
            html: `คุณต้องการลบโรงเรียน<br><strong>"${name}"</strong><br>ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bx bx-trash me-1"></i>ใช่, ลบเลย!',
            cancelButtonText: '<i class="bx bx-x me-1"></i>ยกเลิก'
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
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload();
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

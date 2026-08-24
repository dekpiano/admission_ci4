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
                <i class="bx bxs-map-pin fs-3 text-primary"></i>
                โรงเรียนในเขตพื้นที่บริการ
            </h4>
            <p class="text-muted mb-0 small">จัดการรายชื่อโรงเรียนที่อยู่ในเขตพื้นที่บริการของโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
        </div>
    </div>

    <!-- Add School Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-plus-circle text-primary fs-4"></i>
                เพิ่มโรงเรียนในเขตพื้นที่บริการ
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-9">
                    <label for="schoolSearch" class="form-label fw-bold text-dark small">
                        ค้นหาโรงเรียนจากฐานข้อมูลกลาง <span class="text-danger">*</span>
                    </label>
                    <select id="schoolSearch" class="form-select"></select>
                    <small class="text-muted">พิมพ์ชื่อโรงเรียนเพื่อค้นหาและเลือกเพิ่มเข้าเขตพื้นที่บริการ</small>
                </div>
                <div class="col-12 col-md-3">
                    <button type="button" class="btn btn-primary w-100 rounded-pill shadow-sm" id="btnAddSchool">
                        <i class="bx bx-plus me-1"></i> เพิ่มเข้าเขตพื้นที่
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-list-ul text-primary fs-4"></i>
                รายชื่อโรงเรียนในเขตพื้นที่บริการทั้งหมด
            </h5>
            <span class="badge bg-label-primary rounded-pill px-3 py-1"><?= count($schools ?? []) ?> โรงเรียน</span>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle" id="tableSchools" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">#</th>
                            <th style="min-width: 250px;">ชื่อโรงเรียน</th>
                            <th style="width: 150px;">อำเภอ</th>
                            <th style="width: 150px;">จังหวัด</th>
                            <th style="width: 100px;" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($schools)): ?>
                            <?php foreach($schools as $key => $school): ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($school->school_name) ?></span>
                                </td>
                                <td>
                                    <span class="text-secondary"><?= esc($school->school_amphur ?: '-') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-label-info rounded-pill px-3 py-1"><?= esc($school->school_province ?: '-') ?></span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-delete" data-id="<?= $school->id ?>">
                                        <i class="bx bx-trash me-1"></i> ลบ
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#tableSchools').DataTable({
        responsive: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
        }
    });

    $('#schoolSearch').select2({
        theme: 'bootstrap-5',
        placeholder: 'พิมพ์ชื่อโรงเรียนเพื่อค้นหา...',
        allowClear: true,
        ajax: {
            url: '<?= base_url('skjadmin/service-area-schools/search-all') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.label + ' (อ.' + item.amphur + ' จ.' + item.province + ')',
                            id: item.label,
                            amphur: item.amphur,
                            province: item.province
                        };
                    })
                };
            },
            cache: true
        }
    });

    $('#btnAddSchool').click(function() {
        var data = $('#schoolSearch').select2('data');
        if(data && data.length > 0) {
            var schoolName = data[0].id;
            var schoolAmphur = data[0].amphur;
            var schoolProvince = data[0].province;

            $.ajax({
                url: '<?= base_url('skjadmin/service-area-schools/add') ?>',
                type: 'POST',
                data: {
                    school_name: schoolName,
                    school_amphur: schoolAmphur,
                    school_province: schoolProvince
                },
                success: function(response) {
                    if(response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'เพิ่มสำเร็จ',
                            text: response.message,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'แจ้งเตือน',
                            text: response.message
                        });
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'แจ้งเตือน',
                text: 'กรุณาเลือกโรงเรียนก่อน'
            });
        }
    });

    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบรายชื่อโรงเรียนนี้ออกจากเขตพื้นที่บริการใช่หรือไม่",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('skjadmin/service-area-schools/delete') ?>/' + id,
                    type: 'POST',
                    success: function(response) {
                        if(response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ',
                                text: response.message,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

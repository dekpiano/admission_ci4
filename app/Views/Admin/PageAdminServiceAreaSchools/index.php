<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการข้อมูล /</span> โรงเรียนในเขตพื้นที่บริการ</h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">เพิ่มโรงเรียนในเขตพื้นที่บริการ</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="schoolSearch" class="form-label">ค้นหาโรงเรียน (จากฐานข้อมูลทั้งหมด)</label>
                    <select id="schoolSearch" class="form-select"></select>
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" id="btnAddSchool">
                        <i class="bx bx-plus me-1"></i> เพิ่มรายการ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">รายชื่อโรงเรียนในเขตพื้นที่บริการ</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="tableSchools">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อโรงเรียน</th>
                        <th>อำเภอ</th>
                        <th>จังหวัด</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($schools)): ?>
                        <?php foreach($schools as $key => $school): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $school->school_name ?></td>
                            <td><?= $school->school_amphur ?></td>
                            <td><?= $school->school_province ?></td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $school->id ?>">
                                    <i class="bx bx-trash"></i> ลบ
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">ไม่พบข้อมูล</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#schoolSearch').select2({
        theme: 'bootstrap-5',
        placeholder: 'พิมพ์ชื่อโรงเรียนเพื่อค้นหา...',
        allowClear: true,
        ajax: {
            url: '<?= base_url('skjadmin/service-area-schools/search-all') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.label + ' (อ.' + item.amphur + ' จ.' + item.province + ')',
                            id: item.label, // We use name as ID for saving
                            amphur: item.amphur,
                            province: item.province
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('#btnAddSchool').click(function() {
        var data = $('#schoolSearch').select2('data');
        if(data && data.length > 0) {
            var schoolName = data[0].id; // using label as id from processResults
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
                        Swal.fire('สำเร็จ', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('แจ้งเตือน', response.message, 'warning');
                    }
                }
            });
        } else {
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกโรงเรียนก่อน', 'warning');
        }
    });

    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบรายชื่อโรงเรียนนี้ใช่หรือไม่",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('skjadmin/service-area-schools/delete') ?>/' + id,
                    type: 'POST',
                    success: function(response) {
                        if(response.status === 'success') {
                            Swal.fire('ลบสำเร็จ', response.message, 'success').then(() => {
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

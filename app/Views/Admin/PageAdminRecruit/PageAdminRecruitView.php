<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<?php $datethai = new \App\Libraries\Datethai(); ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">ข้อมูลผู้สมัคร /</span> รายละเอียด
    </h4>

    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <img class="img-fluid rounded my-4" 
                                 src="<?= base_url('uploads/recruitstudent/' . ($recruit['recruit_year'] ?? '')) . '/' . ($recruit['recruit_img'] ?? 'default.png') ?>" 
                                 height="110" width="110" 
                                 alt="User avatar"
                                 style="object-fit: cover;"
                                 onerror="this.onerror=null;this.src='<?= base_url('sneat-assets/img/avatars/1.png') ?>';">
                            <div class="user-info text-center">
                                <h4 class="mb-2"><?= esc($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']) ?></h4>
                                <span class="badge bg-label-secondary mt-1">รหัสผู้สมัคร: <?= esc($recruit['recruit_id']) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around flex-wrap my-4 py-3 border-top border-bottom">
                        <?php 
                          $status = $recruit['recruit_status'] ?? 'รอตรวจสอบ';
                          $statusClass = 'bg-label-primary'; // Default for "รอตรวจสอบ"

                          if ($status === 'ผ่านการตรวจสอบ') {
                              $statusClass = 'bg-label-success';
                          } elseif ($status !== 'รอตรวจสอบ') {
                              // Any status other than 'ผ่านการตรวจสอบ' or 'รอตรวจสอบ' is considered a variation of "not passed"
                              $statusClass = 'bg-label-danger';
                          }
                        ?>
                        <div class="d-flex align-items-start me-4 mt-3 gap-3">
                            <span class="badge <?= $statusClass ?> p-2 rounded"><i class="bx bx-check"></i></span>
                            <div>
                                <h5 class="mb-0">สถานะ</h5>
                                <span class="badge <?= $statusClass ?>"><?= esc($status) ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mt-3 gap-3">
                            <span class="badge bg-label-primary p-2 rounded"><i class="bx bx-calendar"></i></span>
                            <div>
                                <h5 class="mb-0">วันที่สมัคร</h5>
                                <span><?= !empty($recruit['recruit_date']) ? esc($datethai->thai_date_fullmonth(strtotime($recruit['recruit_date']))) : '' ?></span>
                            </div>
                        </div>
                    </div>
                    <h5 class="pb-2 border-bottom mb-4">ข้อมูลเบื้องต้น</h5>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <span class="fw-bold me-2">ประเภท:</span>
                                <span><?= esc($recruit['recruit_category']) ?></span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">ระดับชั้น:</span>
                                <span><?= esc($recruit['recruit_regLevel']) ?></span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">เบอร์โทร:</span>
                                <span><?= esc($recruit['recruit_phone']) ?></span>
                            </li>
                        </ul>
                        <div class="d-grid gap-2 mb-3">
                            <button class="btn btn-success" onclick="updateStatus('ผ่านการตรวจสอบ')">
                                <i class="bx bx-check-circle me-1"></i> ยืนยันตรวจสอบผ่าน
                            </button>
                            <button class="btn btn-danger" onclick="updateStatus('ไม่ผ่านการตรวจสอบ')">
                                <i class="bx bx-x-circle me-1"></i> แจ้งไม่ผ่านการตรวจสอบ
                            </button>
                        </div>
                        <div class="d-flex justify-content-center pt-3 border-top">
                            <a href="<?= site_url('skjadmin/recruits/edit/' . $recruit['recruit_id']) ?>" class="btn btn-primary me-3">แก้ไขข้อมูล</a>
                            <a href="<?= site_url('skjadmin/recruits') ?>" class="btn btn-label-secondary">ย้อนกลับ</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Card -->
        </div>
        <!--/ User Sidebar -->

        <!-- User Content -->
        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <!-- User Tabs -->
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-personal" aria-controls="navs-pills-top-personal" aria-selected="true">
                            <i class="bx bx-user me-1"></i> ข้อมูลส่วนตัว
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-education" aria-controls="navs-pills-top-education" aria-selected="false">
                            <i class="bx bx-book-open me-1"></i> การศึกษา/การสมัคร
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-documents" aria-controls="navs-pills-top-documents" aria-selected="false">
                            <i class="bx bx-file me-1"></i> เอกสารหลักฐาน
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Personal Tab -->
                    <div class="tab-pane fade show active" id="navs-pills-top-personal" role="tabpanel">
                        <h5 class="card-header">ข้อมูลส่วนตัว</h5>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">เลขบัตรประชาชน</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_idCard']) ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">วันเกิด</label>
                                    <p class="form-control-static"><?= !empty($recruit['recruit_birthday']) ? esc($datethai->thai_date_fullmonth(strtotime($recruit['recruit_birthday']))) : '' ?></p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">เชื้อชาติ</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_race']) ?></p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">สัญชาติ</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_nationality']) ?></p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">ศาสนา</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_religion']) ?></p>
                                </div>
                            </div>
                            <hr class="my-0" />
                            <h5 class="card-header mt-3">ที่อยู่ตามทะเบียนบ้าน</h5>
                            <div class="card-body px-0">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">บ้านเลขที่</label>
                                        <p class="form-control-static"><?= esc($recruit['recruit_homeNumber']) ?></p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">หมู่</label>
                                        <p class="form-control-static"><?= esc($recruit['recruit_homeGroup']) ?></p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">ถนน</label>
                                        <p class="form-control-static"><?= esc($recruit['recruit_homeRoad'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">ตำบล/แขวง</label>
                                        <p class="form-control-static"><?= esc($recruit['recruit_homeSubdistrict']) ?></p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">อำเภอ/เขต</label>
                                        <p class="form-control-static"><?= esc($recruit['recruit_homedistrict']) ?></p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">จังหวัด</label>
                                        <p class="form-control-static"><?= esc($recruit['recruit_homeProvince']) ?></p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">รหัสไปรษณีย์</label>
                                        <p class="form-control-static"><?= esc($recruit['recruit_homePostcode']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Education Tab -->
                    <div class="tab-pane fade" id="navs-pills-top-education" role="tabpanel">
                        <h5 class="card-header">ข้อมูลการศึกษาเดิม</h5>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">โรงเรียนเดิม</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_oldSchool']) ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_province']) ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">เกรดเฉลี่ยสะสม (GPAX)</label>
                                    <p class="form-control-static"><span class="badge bg-label-info fs-6"><?= esc($recruit['recruit_grade']) ?></span></p>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <h5 class="card-header mt-3">ข้อมูลการสมัครเรียน</h5>
                        <div class="card-body px-0">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ปีการศึกษา</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_year']) ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ระดับชั้น</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_regLevel']) ?></p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">แผนการเรียนที่สมัคร (หลัก)</label>
                                    <p class="form-control-static text-primary fw-bold"><?= esc($recruit['recruit_tpyeRoom']) ?></p>
                                </div>
                                <?php if (!empty($recruit['recruit_major'])): ?>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">สาขาวิชา</label>
                                    <p class="form-control-static"><?= esc($recruit['recruit_major']) ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($recruit['majorOrderList'])): ?>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">อันดับแผนการเรียนที่เลือก</label>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($recruit['majorOrderList'] as $index => $major): ?>
                                            <li class="list-group-item d-flex align-items-center">
                                                <span class="badge bg-primary rounded-pill me-3"><?= $index + 1 ?></span>
                                                <?= esc($major) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="navs-pills-top-documents" role="tabpanel">
                        <h5 class="card-header">เอกสารหลักฐานประกอบการสมัคร</h5>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php 
                                $docs = [
                                    ['name' => 'ปพ.1', 'file' => $recruit['recruit_certificateEdu'], 'icon' => 'bx-file'],
                                    ['name' => 'ปพ.7', 'file' => $recruit['recruit_certificateEduB'], 'icon' => 'bx-file'],
                                    ['name' => 'สำเนาบัตรประชาชน', 'file' => $recruit['recruit_copyidCard'], 'icon' => 'bx-id-card'],
                                    ['name' => 'สำเนาทะเบียนบ้าน', 'file' => $recruit['recruit_copyAddress'], 'icon' => 'bx-home'],
                                    ['name' => 'เอกสารความสามารถพิเศษ', 'file' => $recruit['recruit_certificateAbility'], 'icon' => 'bx-medal'],
                                ];
                                ?>
                                <?php foreach ($docs as $doc): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border">
                                            <div class="card-body text-center">
                                                <i class="bx <?= $doc['icon'] ?> bx-lg mb-3 text-secondary"></i>
                                                <h6 class="card-title"><?= $doc['name'] ?></h6>
                                                <?php if (!empty($doc['file'])): ?>
                                                    <a href="<?= base_url('uploads/recruitstudent/' . $recruit['recruit_year'] . '/' . $doc['file']) ?>" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-primary">
                                                       <i class="bx bx-show me-1"></i> ดูเอกสาร
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-label-secondary">ไม่มีเอกสาร</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Tabs -->
        </div>
        <!--/ User Content -->
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateStatus(status) {
        if (status === 'ไม่ผ่านการตรวจสอบ') {
            Swal.fire({
                title: 'ระบุสาเหตุที่ไม่ผ่าน',
                input: 'textarea',
                inputLabel: 'กรุณาระบุเหตุผล (ข้อมูลนี้จะถูกบันทึกในสถานะ)',
                inputPlaceholder: 'เช่น เอกสารไม่ครบ, รูปถ่ายไม่ถูกต้อง...',
                inputAttributes: {
                    'aria-label': 'Type your message here'
                },
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#ff3e1d',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('กรุณาระบุเหตุผล');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Combine status and reason
                    const fullStatus = status + ': ' + result.value;
                    submitStatusUpdate(fullStatus);
                }
            });
        } else {
            let title = status === 'ผ่านการตรวจสอบ' ? 'ยืนยันการตรวจสอบผ่าน?' : 'ยืนยันการเปลี่ยนสถานะ?';
            let icon = status === 'ผ่านการตรวจสอบ' ? 'success' : 'warning';
            let confirmBtnColor = status === 'ผ่านการตรวจสอบ' ? '#71dd37' : '#8592a3';

            Swal.fire({
                title: title,
                text: "คุณต้องการเปลี่ยนสถานะเป็น '" + status + "' ใช่หรือไม่?",
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'ใช่, ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitStatusUpdate(status);
                }
            });
        }
    }

    function submitStatusUpdate(statusValue) {
        $.ajax({
            url: '<?= site_url('skjadmin/recruits/update-status') ?>',
            type: 'POST',
            data: {
                id: '<?= $recruit['recruit_id'] ?>',
                status: statusValue
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire(
                        'สำเร็จ!',
                        'สถานะถูกอัปเดตเรียบร้อยแล้ว',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'ผิดพลาด!',
                        'ไม่สามารถอัปเดตสถานะได้',
                        'error'
                    );
                }
            },
            error: function() {
                Swal.fire(
                    'ผิดพลาด!',
                    'เกิดข้อผิดพลาดในการเชื่อมต่อ',
                    'error'
                );
            }
        });
    }
</script>
<?= $this->endSection() ?>

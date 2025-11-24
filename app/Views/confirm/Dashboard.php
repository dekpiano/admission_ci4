<?= $this->extend('User/UserLayout') ?>
<?= $this->section('content') ?>

<?php
// Main student data from tb_recruitstudent
$student = isset($stu[0]) ? $stu[0] : null;

// Confirmed student data from skjpers.tb_students
$student_confirmed = isset($stuConf[0]) ? $stuConf[0] : null;

// Check counts
$has_confirmed_student = isset($Ckeckstu) && $Ckeckstu > 0;
$has_confirmed_father = isset($FatherCkeck) && $FatherCkeck > 0;
$has_confirmed_mother = isset($MatherCkeck) && $MatherCkeck > 0;
$has_confirmed_other = isset($OtherCkeck) && $OtherCkeck > 0;

// Parent data
$father_data = isset($FatherConf[0]) ? $FatherConf[0] : null;
$mother_data = isset($MatherConf[0]) ? $MatherConf[0] : null;
$other_data = isset($OtherConf[0]) ? $OtherConf[0] : null;

?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-3 mx-sm-0 mx-auto">
                    <img src="<?= base_url() ?>/uploads/students/<?= $student->recruit_img ?? 'default.png' ?>" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4><?= $student->recruit_prefix . $student->recruit_firstName . ' ' . $student->recruit_lastName ?></h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-pen'></i> ระดับชั้น: <?= $student->recruit_regLevel == '1' ? 'ม.1' : 'ม.4' ?>
                                </li>
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-map'></i> ประเภท: <?= $student->recruit_tpyeRoom ?>
                                </li>
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-calendar-alt'></i> ปีการศึกษา: <?= $student->recruit_year ?>
                                </li>
                            </ul>
                        </div>
                        <a href="<?= base_url('Confirm/Logout') ?>" class="btn btn-danger text-nowrap">
                            <i class='bx bx-log-out'></i> ออกจากระบบ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3 nav-fill">
            <li class="nav-item">
                <a class="nav-link active" href="#student-info" data-bs-toggle="tab"><i class="bx bx-user me-1"></i> ข้อมูลนักเรียน</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#parent-info" data-bs-toggle="tab"><i class="bx bx-group me-1"></i> ข้อมูลผู้ปกครอง</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#address-info" data-bs-toggle="tab"><i class='bx bx-home-alt me-1'></i> ข้อมูลที่อยู่</a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="#print-docs" data-bs-toggle="tab"><i class="bx bx-printer me-1"></i> พิมพ์เอกสาร</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Student Info Tab -->
            <div class="tab-pane fade show active" id="student-info">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">ข้อมูลส่วนตัวนักเรียน</h5>
                        <small class="text-muted">กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้องตามความเป็นจริง</small>
                    </div>
                    <div class="card-body">
                        <?php if ($has_confirmed_student) : ?>
                            <div class="alert alert-success" role="alert">
                                <h6 class="alert-heading fw-bold mb-1">ยืนยันข้อมูลแล้ว</h6>
                                <p class="mb-0">คุณได้ยืนยันข้อมูลส่วนตัวนักเรียนเรียบร้อยแล้วเมื่อวันที่ <?= $this->datethai->FullDate($student_confirmed->stu_createDate) ?> หากต้องการแก้ไข กรุณาติดต่อห้องทะเบียน</p>
                            </div>
                        <?php else : ?>
                             <div class="alert alert-warning" role="alert">
                                <h6 class="alert-heading fw-bold mb-1">ยังไม่ได้ยืนยันข้อมูล</h6>
                                <p class="mb-0">กรุณากรอกข้อมูลและกดปุ่ม "บันทึกข้อมูล" ด้านล่างเพื่อยืนยันข้อมูล</p>
                            </div>
                        <?php endif; ?>

                        <form id="formStudentInfo" class="mt-4">
                            <input type="hidden" name="stu_iden" value="<?= $student->recruit_idCard ?>">
                            <input type="hidden" name="stu_img" value="<?= $student->recruit_img ?>">
                            <input type="hidden" name="stu_UpdateConfirm" value="<?= date('Y-m-d H:i:s') ?>">
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="stu_prefix" id="stu_prefix" value="<?= $student_confirmed->stu_prefix ?? $student->recruit_prefix ?>" placeholder="คำนำหน้า">
                                        <label for="stu_prefix">คำนำหน้า</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                     <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="stu_fristName" id="stu_fristName" value="<?= $student_confirmed->stu_fristName ?? $student->recruit_firstName ?>" placeholder="ชื่อ">
                                        <label for="stu_fristName">ชื่อ</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                     <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="stu_lastName" id="stu_lastName" value="<?= $student_confirmed->stu_lastName ?? $student->recruit_lastName ?>" placeholder="นามสกุล">
                                        <label for="stu_lastName">นามสกุล</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                     <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="stu_nickName" id="stu_nickName" value="<?= $student_confirmed->stu_nickName ?? '' ?>" placeholder="ชื่อเล่น">
                                        <label for="stu_nickName">ชื่อเล่น</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                     <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="stu_diseaes" id="stu_diseaes" value="<?= $student_confirmed->stu_diseaes ?? '' ?>" placeholder="โรคประจำตัว (ถ้าไม่มีให้ใส่ -)">
                                        <label for="stu_diseaes">โรคประจำตัว (ถ้าไม่มีให้ใส่ -)</label>
                                    </div>
                                </div>
                            </div>
                            <!-- More fields will be added in subsequent steps -->

                            <div class="mt-4">
                               <button type="submit" class="btn btn-primary" <?= $has_confirmed_student ? 'disabled' : '' ?>>
                                   <i class="bx bx-save me-1"></i> บันทึกข้อมูลนักเรียน
                               </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Parent Info Tab -->
             <div class="tab-pane fade" id="parent-info">
                 <div class="card">
                     <div class="card-body">
                         <p>Parent form will go here</p>
                     </div>
                 </div>
             </div>

             <!-- Address Info Tab -->
            <div class="tab-pane fade" id="address-info">
                <div class="card">
                    <div class="card-body">
                        <p>Address form will go here</p>
                    </div>
                </div>
            </div>

            <!-- Print Docs Tab -->
            <div class="tab-pane fade" id="print-docs">
                <div class="card">
                     <div class="card-header">
                        <h5 class="card-title mb-0">พิมพ์เอกสารรายงานตัว</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($has_confirmed_student && $has_confirmed_father && $has_confirmed_mother) : ?>
                             <div class="alert alert-success" role="alert">
                                <h6 class="alert-heading fw-bold mb-1">พร้อมพิมพ์เอกสาร</h6>
                                <p class="mb-0">คุณได้กรอกข้อมูลที่จำเป็นครบถ้วนแล้ว สามารถพิมพ์เอกสารได้</p>
                            </div>
                            <a href="<?= base_url('Confirm/Print') ?>" target="_blank" class="btn btn-lg btn-success w-100">
                                <i class="bx bx-printer bx-lg me-2"></i>
                                พิมพ์ใบมอบตัวนักเรียน
                            </a>
                        <?php else : ?>
                             <div class="alert alert-danger" role="alert">
                                <h6 class="alert-heading fw-bold mb-1">ยังไม่สามารถพิมพ์ได้</h6>
                                <p class="mb-0">กรุณากรอกข้อมูลนักเรียน บิดา และมารดา ให้ครบถ้วนก่อน</p>
                            </div>
                            <button class="btn btn-lg btn-secondary w-100" disabled>
                                <i class="bx bx-printer bx-lg me-2"></i>
                                พิมพ์ใบมอบตัวนักเรียน
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Add JS for form submission here later
</script>
<?= $this->endSection() ?>

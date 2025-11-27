<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<form action="<?= site_url('skjadmin/recruits/update/' . $recruit['recruit_id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <h5 class="card-header">แก้ไขข้อมูลผู้สมัคร: <?= esc($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']) ?></h5>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_id" name="recruit_id" value="<?= esc($recruit['recruit_id']) ?>" readonly>
                        <label for="recruit_id">รหัสผู้สมัคร</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_idCard" name="recruit_idCard" value="<?= esc($recruit['recruit_idCard']) ?>">
                        <label for="recruit_idCard">เลขบัตรประชาชน</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="recruit_prefix" name="recruit_prefix" value="<?= esc($recruit['recruit_prefix']) ?>">
                                <label for="recruit_prefix">คำนำหน้า</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="recruit_firstName" name="recruit_firstName" value="<?= esc($recruit['recruit_firstName']) ?>">
                                <label for="recruit_firstName">ชื่อ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="recruit_lastName" name="recruit_lastName" value="<?= esc($recruit['recruit_lastName']) ?>">
                                <label for="recruit_lastName">นามสกุล</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="recruit_birthday" name="recruit_birthday" value="<?= esc($recruit['recruit_birthday']) ?>">
                        <label for="recruit_birthday">วันเกิด</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_phone" name="recruit_phone" value="<?= esc($recruit['recruit_phone']) ?>">
                        <label for="recruit_phone">เบอร์โทรศัพท์</label>
                    </div>

                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_oldSchool" name="recruit_oldSchool" value="<?= esc($recruit['recruit_oldSchool']) ?>">
                        <label for="recruit_oldSchool">โรงเรียนเดิม</label>
                    </div>

                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_grade" name="recruit_grade" value="<?= esc($recruit['recruit_grade']) ?>">
                        <label for="recruit_grade">เกรดเฉลี่ย</label>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="recruit_regLevel" name="recruit_regLevel">
                            <option value="1" <?= $recruit['recruit_regLevel'] == '1' ? 'selected' : '' ?>>ม.1</option>
                            <option value="4" <?= $recruit['recruit_regLevel'] == '4' ? 'selected' : '' ?>>ม.4</option>
                            <option value="2" <?= $recruit['recruit_regLevel'] == '2' ? 'selected' : '' ?>>ปวช.1</option>
                        </select>
                        <label for="recruit_regLevel">ระดับชั้นที่สมัคร</label>
                    </div>

                     <div class="form-floating mb-3">
                        <select class="form-select" id="recruit_category" name="recruit_category">
                            <option value="normal" <?= $recruit['recruit_category'] == 'normal' ? 'selected' : '' ?>>ปกติ</option>
                            <option value="quotaM1" <?= $recruit['recruit_category'] == 'quotaM1' ? 'selected' : '' ?>>โควต้า ม.1</option>
                            <option value="quotaM4" <?= $recruit['recruit_category'] == 'quotaM4' ? 'selected' : '' ?>>โควต้า ม.4</option>
                             <option value="quotasport" <?= $recruit['recruit_category'] == 'quotasport' ? 'selected' : '' ?>>โควต้านักกีฬา</option>
                        </select>
                        <label for="recruit_category">ประเภทการสมัคร</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="recruit_tpyeRoom_id" name="recruit_tpyeRoom_id">
                            <option value="">-- เลือกแผนการเรียน --</option>
                            <?php if(isset($courses)): ?>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course->course_id ?>" <?= (isset($recruit['recruit_tpyeRoom_id']) && $recruit['recruit_tpyeRoom_id'] == $course->course_id) ? 'selected' : '' ?>>
                                        <?= esc($course->course_fullname) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label for="recruit_tpyeRoom_id">แผนการเรียน</label>
                    </div>

                     <div class="form-floating mb-3">
                        <select class="form-select" id="recruit_status" name="recruit_status">
                            <option value="ผ่านการตรวจสอบ" <?= $recruit['recruit_status'] == 'ผ่านการตรวจสอบ' ? 'selected' : '' ?>>ผ่านการตรวจสอบ</option>
                            <option value="กรอกข้อมูลไม่ครบถ้วน" <?= $recruit['recruit_status'] == 'กรอกข้อมูลไม่ครบถ้วน' ? 'selected' : '' ?>>กรอกข้อมูลไม่ครบถ้วน</option>
                            <option value="ไม่มีรูปภาพ หรือรูปภาพไม่ผ่านการตรวจสอบ" <?= $recruit['recruit_status'] == 'ไม่มีรูปภาพ หรือรูปภาพไม่ผ่านการตรวจสอบ' ? 'selected' : '' ?>>รูปภาพไม่ถูกต้อง</option>
                        </select>
                        <label for="recruit_status">สถานะการสมัคร</label>
                    </div>

                    <div class="mb-3">
                        <label for="recruit_img" class="form-label">รูปถ่ายนักเรียน</label>
                        <input class="form-control" type="file" id="recruit_img" name="recruit_img">
                        <div class="mt-2">
                             <img src="<?= base_url('uploads/recruitstudent/' . ($recruit['recruit_year'] ?? '')) . '/' . ($recruit['recruit_img'] ?? 'default.png') ?>" 
                                 alt="Applicant Photo" 
                                 class="img-fluid rounded" 
                                 style="width: 100px; height: 100px; object-fit: cover;"
                                 onerror="this.onerror=null;this.src='<?= base_url('sneat-assets/img/avatars/1.png') ?>';">
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mt-4">ที่อยู่ตามทะเบียนบ้าน</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_homeNumber" name="recruit_homeNumber" value="<?= esc($recruit['recruit_homeNumber'] ?? '') ?>">
                        <label for="recruit_homeNumber">บ้านเลขที่</label>
                    </div>
                </div>
                <div class="col-md-3">
                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_homeGroup" name="recruit_homeGroup" value="<?= esc($recruit['recruit_homeGroup'] ?? '') ?>">
                        <label for="recruit_homeGroup">หมู่</label>
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_homeRoad" name="recruit_homeRoad" value="<?= esc($recruit['recruit_homeRoad'] ?? '') ?>">
                        <label for="recruit_homeRoad">ถนน</label>
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_homeSubdistrict" name="recruit_homeSubdistrict" value="<?= esc($recruit['recruit_homeSubdistrict'] ?? '') ?>">
                        <label for="recruit_homeSubdistrict">ตำบล/แขวง</label>
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_homedistrict" name="recruit_homedistrict" value="<?= esc($recruit['recruit_homedistrict'] ?? '') ?>">
                        <label for="recruit_homedistrict">อำเภอ/เขต</label>
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_homeProvince" name="recruit_homeProvince" value="<?= esc($recruit['recruit_homeProvince'] ?? '') ?>">
                        <label for="recruit_homeProvince">จังหวัด</label>
                    </div>
                </div>
                 <div class="col-md-4">
                     <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="recruit_homePostcode" name="recruit_homePostcode" value="<?= esc($recruit['recruit_homePostcode'] ?? '') ?>">
                        <label for="recruit_homePostcode">รหัสไปรษณีย์</label>
                    </div>
                </div>
            </div>


            <div class="mt-4">
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                <a href="<?= site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) ?>" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

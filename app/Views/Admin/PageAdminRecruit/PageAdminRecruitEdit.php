<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* Sneat-specific adjustments */
    .photo-preview-wrapper {
        position: relative;
        margin: 0 auto;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 3px solid #fff;
        box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
        width: 100%;
        max-width: 200px;
        aspect-ratio: 3/4;
        background: #f5f5f9;
    }
    .photo-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .doc-preview-container {
        height: 150px;
        background-color: #f5f5f9;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px dashed #d9dee3;
        margin-bottom: 0.5rem;
    }

    /* Fix Select2 inside Input Group for Sneat */
    .input-group > .select2-container--bootstrap-5 {
        flex: 1 1 auto;
        width: 1% !important;
    }
    .input-group-merge > .select2-container--bootstrap-5 .select2-selection {
        border-right: 1px solid #d9dee3 !important;
        border-left: 0 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .input-group-merge > .input-group-text {
        border-right: 0 !important;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">จัดการข้อมูล /</span> แก้ไขข้อมูลผู้สมัคร
            </h4>
        </div>
    </div>

    <form action="<?= site_url('skjadmin/recruits/update/' . $recruit['recruit_id']) ?>" method="post" enctype="multipart/form-data" id="editForm">
        <?= csrf_field() ?>
        <input type="hidden" name="recruit_id" value="<?= esc($recruit['recruit_id']) ?>">

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">1. ข้อมูลการสมัครและแผนการเรียน</h5>
                        <small class="text-muted float-end">ID: <?= esc($recruit['recruit_id']) ?></small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 text-start">
                                <label for="recruit_year" class="form-label">ปีการศึกษา</label>
                                <input type="text" class="form-control" id="recruit_year" value="<?= $recruit['recruit_year'] ?>" readonly>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_regLevel" class="form-label">ระดับชั้น <span class="text-danger">*</span></label>
                                <select class="form-select" id="recruit_regLevel" name="recruit_regLevel" required>
                                    <option value="1" <?= $recruit['recruit_regLevel'] == '1' ? 'selected' : '' ?>>มัธยมศึกษาปีที่ 1</option>
                                    <option value="4" <?= $recruit['recruit_regLevel'] == '4' ? 'selected' : '' ?>>มัธยมศึกษาปีที่ 4</option>
                                </select>
                            </div>
                            <div class="col-md-12 text-start">
                                <label for="recruit_category" class="form-label">ประเภทโควตา / รอบการสมัคร <span class="text-danger">*</span></label>
                                <select class="form-select" id="recruit_category" disabled required>
                                    <?php if (isset($quotas)) : ?>
                                        <?php foreach ($quotas as $quota) : ?>
                                            <option value="<?= $quota->quota_id ?>" data-courses="<?= $quota->quota_course ?? '' ?>" <?= $recruit['recruit_category'] == $quota->quota_id ? 'selected' : '' ?>>
                                                <?= esc($quota->quota_explain) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <input type="hidden" name="recruit_category" value="<?= esc($recruit['recruit_category']) ?>">
                            </div>

                            <div class="col-12 mt-3 text-start">
                                <label class="form-label d-block mb-2" id="course_main_label">เลือกแผนการเรียน (เลือกได้สูงสุด 3 อันดับ) <span class="text-danger">*</span></label>
                                <small id="level_badge_info" class="text-primary d-block mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <?= ($recruit['recruit_regLevel'] == '1') ? 'ม.ต้น เลือกได้ 3 อันดับ' : 'ม.ปลาย เลือกได้ 3 อันดับ (ตามคุณสมบัติ)' ?>
                                </small>
                                
                                <div class="mb-3 input-group input-group-merge" id="rank_container_1">
                                    <span class="input-group-text">อันดับ 1</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom1" id="recruit_tpyeRoom1" required>
                                        <option value="" disabled selected>-- เลือกอันดับ 1 --</option>
                                    </select>
                                </div>
                                <div class="mb-3 input-group input-group-merge" id="rank_container_2">
                                    <span class="input-group-text">อันดับ 2</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom2" id="recruit_tpyeRoom2">
                                        <option value="">-- ไม่ระบุ --</option>
                                    </select>
                                </div>
                                <div class="mb-3 input-group input-group-merge" id="rank_container_3">
                                    <span class="input-group-text">อันดับ 3</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom3" id="recruit_tpyeRoom3">
                                        <option value="">-- ไม่ระบุ --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sports Info Block -->
                        <div id="sports_info_block" style="display:none;" class="mt-4 border-top pt-4">
                            <h6 class="fw-bold mb-3 text-info"><i class="bi bi-person-walking me-1"></i> ข้อมูลเพิ่มเติมสำหรับนักกีฬา</h6>
                            
                            <div id="age_radio_container" class="mb-4 text-start">
                                <!-- Populated via JS -->
                            </div>
                            <input type="hidden" name="recruit_agegroup" id="recruit_agegroup" value="<?= esc($recruit['recruit_agegroup'] ?? '') ?>">

                            <div class="row g-3">
                                <div class="col-md-12 text-start">
                                    <label for="recruit_sportPosition" class="form-label">สมัครชนิดกีฬาในตำแหน่ง <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="recruit_sportPosition" id="recruit_sportPosition" value="<?= esc($recruit['recruit_sportPosition'] ?? '') ?>" placeholder="ระบุตำแหน่งที่สมัคร">
                                </div>
                                <div class="col-md-4 text-start">
                                    <label for="recruit_nickname" class="form-label">ชื่อเล่น <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="recruit_nickname" id="recruit_nickname" value="<?= esc($recruit['recruit_nickname'] ?? '') ?>" placeholder="ชื่อเล่น">
                                </div>
                                <div class="col-md-4 text-start">
                                    <label for="recruit_weight" class="form-label">น้ำหนัก (กก.) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control" name="recruit_weight" id="recruit_weight" value="<?= esc($recruit['recruit_weight'] ?? '') ?>" placeholder="0.0">
                                </div>
                                <div class="col-md-4 text-start">
                                    <label for="recruit_height" class="form-label">ส่วนสูง (ซม.) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control" name="recruit_height" id="recruit_height" value="<?= esc($recruit['recruit_height'] ?? '') ?>" placeholder="0.0">
                                </div>
                                <div class="col-md-6 text-start">
                                    <label for="recruit_fatherName" class="form-label">ชื่อ-นามสกุล บิดา <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="recruit_fatherName" id="recruit_fatherName" value="<?= esc($recruit['recruit_fatherName'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 text-start">
                                    <label for="recruit_fatherJob" class="form-label">อาชีพ บิดา <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="recruit_fatherJob" id="recruit_fatherJob" value="<?= esc($recruit['recruit_fatherJob'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 text-start">
                                    <label for="recruit_motherName" class="form-label">ชื่อ-นามสกุล มารดา <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="recruit_motherName" id="recruit_motherName" value="<?= esc($recruit['recruit_motherName'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 text-start">
                                    <label for="recruit_motherJob" class="form-label">อาชีพ มารดา <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="recruit_motherJob" id="recruit_motherJob" value="<?= esc($recruit['recruit_motherJob'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Personal Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">2. ข้อมูลส่วนตัวนักเรียน</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4 text-start">
                                <label for="recruit_prefix" class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                                <select class="form-select" id="recruit_prefix" name="recruit_prefix" required>
                                    <option value="เด็กชาย" <?= $recruit['recruit_prefix'] == 'เด็กชาย' ? 'selected' : '' ?>>เด็กชาย</option>
                                    <option value="เด็กหญิง" <?= $recruit['recruit_prefix'] == 'เด็กหญิง' ? 'selected' : '' ?>>เด็กหญิง</option>
                                    <option value="นาย" <?= $recruit['recruit_prefix'] == 'นาย' ? 'selected' : '' ?>>นาย</option>
                                    <option value="นางสาว" <?= $recruit['recruit_prefix'] == 'นางสาว' ? 'selected' : '' ?>>นางสาว</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-start">
                                <label for="recruit_firstName" class="form-label">ชื่อจริง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="recruit_firstName" name="recruit_firstName" placeholder="ชื่อจริง" value="<?= esc($recruit['recruit_firstName']) ?>" required>
                            </div>
                            <div class="col-md-4 text-start">
                                <label for="recruit_lastName" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="recruit_lastName" name="recruit_lastName" placeholder="นามสกุล" value="<?= esc($recruit['recruit_lastName']) ?>" required>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_idCard" class="form-label">เลขบัตรประชาชน (13 หลัก) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="recruit_idCard" name="recruit_idCard" placeholder="เลขบัตรประชาชน" value="<?= esc($recruit['recruit_idCard']) ?>" maxlength="17" required>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_birthday" class="form-label">วันเดือนปีเกิด <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                    <input type="text" class="form-control" id="recruit_birthday" name="recruit_birthday" placeholder="วว/ดด/ปปปป" value="<?= esc($recruit['recruit_birthday']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4 text-start">
                                <?php 
                                    $commonRaces = ['ไทย', 'จีน', 'พม่า', 'กัมพูชา', 'ลาว', 'เวียดนาม', 'มาเลเซีย', 'ญี่ปุ่น', 'เกาหลี', 'อเมริกา', 'อังกฤษ', 'ฝรั่งเศส', 'เยอรมนี'];
                                    $currentRace = $recruit['recruit_race'] ?? 'ไทย';
                                ?>
                                <label for="recruit_race" class="form-label">เชื้อชาติ <span class="text-danger">*</span></label>
                                <select class="form-select select2-tags" id="recruit_race" name="recruit_race" required>
                                    <?php foreach ($commonRaces as $race) : ?>
                                        <option value="<?= $race ?>" <?= $currentRace == $race ? 'selected' : '' ?>><?= $race ?></option>
                                    <?php endforeach; ?>
                                    <?php if (!empty($currentRace) && !in_array($currentRace, $commonRaces)) : ?>
                                        <option value="<?= esc($currentRace) ?>" selected><?= esc($currentRace) ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-4 text-start">
                                <?php 
                                    $commonNats = ['ไทย', 'จีน', 'พม่า', 'กัมพูชา', 'ลาว', 'เวียดนาม', 'มาเลเซีย', 'ญี่ปุ่น', 'เกาหลี', 'อเมริกา', 'อังกฤษ', 'ฝรั่งเศส', 'เยอรมนี'];
                                    $currentNat = $recruit['recruit_nationality'] ?? 'ไทย';
                                ?>
                                <label for="recruit_nationality" class="form-label">สัญชาติ <span class="text-danger">*</span></label>
                                <select class="form-select select2-tags" id="recruit_nationality" name="recruit_nationality" required>
                                    <?php foreach ($commonNats as $nat) : ?>
                                        <option value="<?= $nat ?>" <?= $currentNat == $nat ? 'selected' : '' ?>><?= $nat ?></option>
                                    <?php endforeach; ?>
                                    <?php if (!empty($currentNat) && !in_array($currentNat, $commonNats)) : ?>
                                        <option value="<?= esc($currentNat) ?>" selected><?= esc($currentNat) ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-4 text-start">
                                <label for="recruit_religion" class="form-label">ศาสนา <span class="text-danger">*</span></label>
                                <select class="form-select select2-tags" id="recruit_religion" name="recruit_religion" required>
                                    <option value="พุทธ" <?= ($recruit['recruit_religion'] ?? 'พุทธ') == 'พุทธ' ? 'selected' : '' ?>>พุทธ</option>
                                    <option value="คริสต์" <?= ($recruit['recruit_religion'] ?? '') == 'คริสต์' ? 'selected' : '' ?>>คริสต์</option>
                                    <option value="อิสลาม" <?= ($recruit['recruit_religion'] ?? '') == 'อิสลาม' ? 'selected' : '' ?>>อิสลาม</option>
                                    <option value="ฮินดู" <?= ($recruit['recruit_religion'] ?? '') == 'ฮินดู' ? 'selected' : '' ?>>ฮินดู</option>
                                    <option value="ซิกข์" <?= ($recruit['recruit_religion'] ?? '') == 'ซิกข์' ? 'selected' : '' ?>>ซิกข์</option>
                                    <?php if (!empty($recruit['recruit_religion']) && !in_array($recruit['recruit_religion'], ['พุทธ', 'คริสต์', 'อิสลาม', 'ฮินดู', 'ซิกข์'])) : ?>
                                        <option value="<?= esc($recruit['recruit_religion']) ?>" selected><?= esc($recruit['recruit_religion']) ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-12 text-start">
                                <label for="recruit_phone" class="form-label">เบอร์โทรศัพท์สำหรับติดต่อ <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="recruit_phone" name="recruit_phone" placeholder="08xxxxxxxx" value="<?= esc($recruit['recruit_phone']) ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">3. ที่อยู่ตามทะเบียนบ้าน</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4 text-start">
                                <label for="recruit_homeNumber" class="form-label">บ้านเลขที่ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeNumber" id="recruit_homeNumber" placeholder="บ้านเลขที่" value="<?= esc($recruit['recruit_homeNumber'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4 text-start">
                                <label for="recruit_homeGroup" class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeGroup" id="recruit_homeGroup" placeholder="หมู่ที่" value="<?= esc($recruit['recruit_homeGroup'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4 text-start">
                                <label for="recruit_homeRoad" class="form-label">ถนน / ซอย</label>
                                <input type="text" class="form-control" name="recruit_homeRoad" id="recruit_homeRoad" placeholder="ถนน / ซอย" value="<?= esc($recruit['recruit_homeRoad'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_homeSubdistrict" class="form-label">ตำบล / แขวง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeSubdistrict" id="recruit_homeSubdistrict" placeholder="ตำบล/แขวง" value="<?= esc($recruit['recruit_homeSubdistrict'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_homedistrict" class="form-label">อำเภอ / เขต <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homedistrict" id="recruit_homedistrict" placeholder="อำเภอ/เขต" value="<?= esc($recruit['recruit_homedistrict'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_homeProvince" class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeProvince" id="recruit_homeProvince" placeholder="จังหวัด" value="<?= esc($recruit['recruit_homeProvince'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_homePostcode" class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homePostcode" id="recruit_homePostcode" placeholder="รหัสไปรษณีย์" value="<?= esc($recruit['recruit_homePostcode'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Education -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">4. ข้อมูลการศึกษาเดิม</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 text-start">
                                <label for="recruit_oldSchool_select" class="form-label">ชื่อโรงเรียนเดิม <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-buildings"></i></span>
                                    <select class="form-select" id="recruit_oldSchool_select">
                                        <?php if (!empty($recruit['recruit_oldSchool'])): ?>
                                            <option value="<?= esc($recruit['recruit_oldSchool']) ?>" selected><?= esc($recruit['recruit_oldSchool']) ?></option>
                                        <?php else: ?>
                                            <option value="">-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="recruit_oldSchool" id="recruit_oldSchool" value="<?= esc($recruit['recruit_oldSchool'] ?? '') ?>" required>
                                <div class="form-text text-muted small">* พิมพ์ชื่อโรงเรียนเพื่อค้นหาและเลือกจากรายการ ระบบจะกรอกอำเภอและจังหวัดให้อัตโนมัติ</div>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_district" class="form-label">อำเภอที่ตั้งโรงเรียน</label>
                                <input type="text" class="form-control" name="recruit_district" id="recruit_district" placeholder="อำเภอที่ตั้ง" value="<?= esc($recruit['recruit_district'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_province" class="form-label">จังหวัดที่ตั้งโรงเรียน</label>
                                <input type="text" class="form-control" name="recruit_province" id="recruit_province" placeholder="จังหวัดที่ตั้ง" value="<?= esc($recruit['recruit_province'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_grade" class="form-label">ผลการเรียนเฉลี่ย (GPAX) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="4.00" class="form-control" name="recruit_grade" id="recruit_grade" placeholder="GPAX" value="<?= esc($recruit['recruit_grade']) ?>" required>
                            </div>
                            <div class="col-md-6 text-start">
                                <label for="recruit_major" class="form-label">สาขาวิชา / แผนการเรียน (อันดับ 1)</label>
                                <input type="text" class="form-control" name="recruit_major" id="recruit_major" placeholder="ระบุสาขาวิชา" value="<?= esc($recruit['recruit_major'] ?? '') ?>" readonly>
                                <div class="form-text small">เปลี่ยนตามแผนการเรียนอันดับ 1 ที่เลือก</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Documents -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">5. เอกสารหลักฐานประกอบ</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php
                            $docFields = [
                                ['id' => 'recruit_certificateEdu', 'name' => 'ปพ.1 (ด้านหน้า)', 'dir' => 'certificate', 'icon' => 'bx bx-file'],
                                ['id' => 'recruit_certificateEduB', 'name' => 'ปพ.1 (ด้านหลัง)', 'dir' => 'certificateB', 'icon' => 'bx bx-file'],
                                ['id' => 'recruit_copyidCard', 'name' => 'สำเนาบัตรประชาชน', 'dir' => 'copyidCard', 'icon' => 'bx bx-id-card'],
                            ];
                            foreach ($docFields as $df) : 
                            ?>
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center h-100 bg-light">
                                    <h6 class="fw-bold mb-3 small"><?= $df['name'] ?></h6>
                                    <div class="doc-preview-container">
                                        <?php if (!empty($recruit[$df['id']])) : ?>
                                            <a href="<?= get_recruit_file_url($recruit[$df['id']], $recruit['recruit_regLevel'], $df['dir']) ?>" target="_blank" class="d-block w-100 h-100">
                                                <?php if (strpos($recruit[$df['id']], '.pdf') !== false) : ?>
                                                    <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                                        <i class="bx bxs-file-pdf text-danger fs-1"></i>
                                                        <span class="small mt-2 text-dark">PDF</span>
                                                    </div>
                                                <?php else : ?>
                                                    <img src="<?= get_recruit_file_url($recruit[$df['id']], $recruit['recruit_regLevel'], $df['dir']) ?>" class="img-fluid" style="max-height: 120px;" alt="<?= $df['name'] ?>">
                                                <?php endif; ?>
                                            </a>
                                        <?php else : ?>
                                            <i class="<?= $df['icon'] ?> text-muted fs-1 opacity-25"></i>
                                        <?php endif; ?>
                                    </div>
                                    <input class="form-control form-control-sm mb-2 mt-2" type="file" name="<?= $df['id'] ?>" accept="image/*,.pdf">
                                    <div class="text-center">
                                        <?php if (!empty($recruit[$df['id']])) : ?>
                                            <span class="badge bg-label-success rounded-pill">เรียบร้อย</span>
                                        <?php else : ?>
                                            <span class="badge bg-label-secondary rounded-pill">ยังไม่แนบ</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sticky-top" style="top: 1rem;">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="photo-preview-wrapper mb-3">
                                <img id="preview_img_display" src="<?= get_recruit_file_url($recruit['recruit_img'] ?? '', $recruit['recruit_regLevel'], 'img') ?>" alt="Student Photo" onerror="this.onerror=null;this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png';">
                            </div>
                            <h5 class="mb-1 fw-bold"><?= esc($recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']) ?></h5>
                            <p class="text-muted small mb-3">รหัสประจำตัว: <?= esc($recruit['recruit_id']) ?></p>
                            
                            <input type="file" class="d-none" name="recruit_img" id="recruit_img" accept="image/*">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="document.getElementById('recruit_img').click()">
                                <i class='bx bx-camera me-1'></i> เปลี่ยนรูปถ่าย
                            </button>
                        </div>
                        <hr class="m-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 text-start"><i class="bx bx-star text-warning me-2"></i>ผลการสมัคร</h6>
                            
                            <div class="mb-3 text-start">
                                <label for="recruit_status" class="form-label">ผลการตรวจเอกสาร</label>
                                <select class="form-select" id="recruit_status" name="recruit_status" required>
                                    <option value="รอตรวจสอบ" <?= $recruit['recruit_status'] == 'รอตรวจสอบ' ? 'selected' : '' ?>>⏳ รอตรวจสอบ</option>
                                    <option value="ผ่านการตรวจสอบ" <?= $recruit['recruit_status'] == 'ผ่านการตรวจสอบ' ? 'selected' : '' ?>>✅ ผ่านการตรวจสอบ</option>
                                    <option value="ไม่ผ่านการตรวจสอบ" <?= strpos($recruit['recruit_status'], 'ไม่ผ่าน') !== false ? 'selected' : '' ?>>❌ ไม่ผ่านการตรวจสอบ</option>
                                </select>
                            </div>

                            <div id="sportSelectionResultSection" style="display:none;" class="text-start">
                                <div class="mb-3">
                                    <label for="recruit_sportSelectionResult" class="form-label">ผลการคัดนักกีฬา</label>
                                    <select class="form-select" name="recruit_sportSelectionResult" id="recruit_sportSelectionResult">
                                        <option value="รอคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] ?? '') == 'รอคัดเลือก' || empty($recruit['recruit_sportSelectionResult']) ? 'selected' : '' ?>>⏳ รอคัดเลือก</option>
                                        <option value="ผ่านการคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] ?? '') == 'ผ่านการคัดเลือก' ? 'selected' : '' ?>>✅ ผ่านการคัดเลือก</option>
                                        <option value="ไม่ผ่านการคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] ?? '') == 'ไม่ผ่านการคัดเลือก' ? 'selected' : '' ?>>❌ ไม่ผ่านการคัดเลือก</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary d-grid w-100 mb-2">
                                    <span class="d-flex align-items-center justify-content-center">
                                        <i class="bx bx-save me-2"></i> บันทึกข้อมูล
                                    </span>
                                </button>
                                
                                <a href="<?= site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) ?>" class="btn btn-outline-secondary d-grid w-100">
                                    ยกเลิก
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 with Sneat-friendly style
        $('.form-select:not(#recruit_prefix, #recruit_status, #recruit_sportSelectionResult, #recruit_regLevel, #recruit_category, .select2-tags)').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('.select2-tags').select2({
            theme: 'bootstrap-5',
            width: '100%',
            tags: true,
            placeholder: '-- เลือกหรือพิมพ์เพื่อเพิ่ม --'
        });

        // Initialize School Search (Select2 AJAX)
        $('#recruit_oldSchool_select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --',
            allowClear: true,
            ajax: {
                url: '<?= base_url('new-admission/school-search') ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        // Handle school selection
        $('#recruit_oldSchool_select').on('select2:select', function (e) {
            var data = e.params.data;
            $('#recruit_oldSchool').val(data.text); 
            $('#recruit_district').val(data.amphur); 
            $('#recruit_province').val(data.province);
        });

        // Initialize Flatpickr for Birthday (Buddhist Era Style)
        flatpickr("#recruit_birthday", {
            locale: "th",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y", 
            allowInput: true,
            disableMobile: "true",
            formatDate: (date, format, locale) => {
                const year = date.getFullYear() + 543;
                const month = locale.months.longhand[date.getMonth()];
                const day = date.getDate();
                if (format === "j F Y") {
                    return `${day} ${month} ${year}`;
                }
                return flatpickr.formatDate(date, format, locale);
            }
        });

        // 1. Thailand Auto-complete
        $.Thailand({
            $district: $('#recruit_homeSubdistrict'),
            $amphoe: $('#recruit_homedistrict'),
            $province: $('#recruit_homeProvince'),
            $zipcode: $('#recruit_homePostcode'),
        });

        // 2. Dynamic Courses Filtering
        const courses = <?= $courses_json ?>;
        const currentMajorOrder = <?= json_encode($major_order_ids) ?>;

        function updateCourses(level) {
            const selectedQuota = $('#recruit_category').val();
            const quotaOption = $('#recruit_category option:selected');
            const allowedCoursesStr = quotaOption.data('courses') || '';
            const allowedCourses = allowedCoursesStr.split('|').filter(id => id !== '');
            const gradeLevel = (level == '1') ? 'ม.ต้น' : 'ม.ปลาย';

            let infoText = (level == '1') ?
                '<i class="bi bi-info-circle me-1"></i> เงื่อนไข: ม.ต้น เลือกได้ 3 อันดับ' :
                '<i class="bi bi-info-circle me-1"></i> เงื่อนไข: ม.ปลาย เลือกได้ 3 อันดับ (ตามคุณสมบัติ)';
            $('#level_badge_info').html(infoText);

            $('.course-select').each(function(index) {
                const $select = $(this);
                const currentValue = $select.val() || (currentMajorOrder[index] || '');
                $select.empty();

                const placeholder = $select.attr('id') === 'recruit_tpyeRoom1' ? '-- เลือกอันดับ 1 --' : '-- ไม่ระบุ --';
                $select.append(`<option value="" ${$select.prop('required') ? 'disabled' : ''}>${placeholder}</option>`);

                const filtered = courses.filter(c => {
                    return c.course_gradelevel === gradeLevel && (allowedCourses.length === 0 || allowedCourses.includes(c.course_id.toString()));
                });

                filtered.forEach(c => {
                    const isSelected = (c.course_id == currentValue) ? 'selected' : '';
                    $select.append(`<option value="${c.course_id}" ${isSelected}>${c.course_branch}</option>`);
                });

                $select.val(currentValue).trigger('change');
            });
            
            updateSportSelectionVisibility();
        }

        $('#recruit_regLevel').on('change', function() {
            updateCourses($(this).val());
        });

        // 3. Image Preview
        $('#recruit_img').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_img_display').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // 4. Sport Selection Visibility
        const quotaData = {};
        <?php if (isset($quotas)) : ?>
            <?php foreach ($quotas as $q) : ?>
                quotaData[<?= $q->quota_id ?>] = '<?= $q->quota_key ?>';
            <?php endforeach; ?>
        <?php endif; ?>

        function updateSportSelectionVisibility() {
            const selectedQuota = $('#recruit_category').val();
            const quotaOption = $('#recruit_category option:selected');
            const quotaText = quotaOption.text();
            const quotaKey = quotaData[selectedQuota] || '';
            const sportPositionValue = $('#recruit_sportPosition').val();
            const selectedCourseId = $('#recruit_tpyeRoom1').val();
            const selectedCourse = courses.find(c => c.course_id == selectedCourseId);

            const isSportsQuotaCategory = (quotaKey === 'sport' || quotaText.includes('กีฬา') || quotaText.includes('นักกีฬา'));

            if (isSportsQuotaCategory) {
                $('#rank_container_2, #rank_container_3').hide();
                $('#course_main_label').text('ประเภทกีฬาที่สมัคร');
                $('#rank_container_1 .input-group-text').text('กีฬา');
                $('#sports_info_block').slideDown();
                
                $('#recruit_sportPosition, #recruit_nickname, #recruit_weight, #recruit_height, #recruit_fatherName, #recruit_motherName, #recruit_fatherJob, #recruit_motherJob').prop('required', true);
                $('#sportSelectionResultSection').slideDown();
                
                handleAgeRadios(selectedCourse);
            } else {
                $('#rank_container_2, #rank_container_3').show();
                $('#course_main_label').text('เลือกแผนการเรียน (เลือกได้สูงสุด 3 อันดับ)');
                $('#rank_container_1 .input-group-text').text('อันดับ 1');
                
                if (selectedCourse && selectedCourse.course_age && selectedCourse.course_age.trim() !== '') {
                    $('#rank_container_2, #rank_container_3').hide();
                    $('#sports_info_block').slideDown();
                    $('#recruit_sportPosition, #recruit_nickname, #recruit_weight, #recruit_height, #recruit_fatherName, #recruit_motherName, #recruit_fatherJob, #recruit_motherJob').prop('required', true);
                    handleAgeRadios(selectedCourse);
                } else {
                    $('#sports_info_block').slideUp();
                    $('#recruit_sportPosition, #recruit_nickname, #recruit_weight, #recruit_height, #recruit_fatherName, #recruit_motherName, #recruit_fatherJob, #recruit_motherJob').prop('required', false);
                    $('#age_radio_container').empty();
                }

                if (sportPositionValue && sportPositionValue !== '-' && sportPositionValue !== '') {
                     $('#sportSelectionResultSection').slideDown();
                } else {
                     $('#sportSelectionResultSection').slideUp();
                }
            }
        }

        function handleAgeRadios(course) {
            const container = $('#age_radio_container');
            const hiddenInput = $('#recruit_agegroup');
            container.empty();
            
            if (course && course.course_age) {
                const ages = course.course_age.split(',').map(s => s.trim()).filter(s => s !== '');
                if (ages.length > 0) {
                    container.append('<label class="form-label d-block text-start">เลือกรุ่นอายุ <span class="text-danger">*</span></label>');
                    const row = $('<div class="row g-2 justify-content-start"></div>');
                    ages.forEach(age => {
                        const col = $('<div class="col-auto"></div>');
                        const isChecked = (hiddenInput.val() == age) ? 'checked' : '';
                        const radio = $(`
                            <div class="form-check form-check-inline border rounded px-3 py-1 mb-0" style="cursor: pointer; background: #fff;">
                                <input class="form-check-input" type="radio" name="age_radio_group" id="age_${age}" value="${age}" ${isChecked} required>
                                <label class="form-check-label mb-0" for="age_${age}" style="cursor: pointer;">รุ่น ${age} ปี</label>
                            </div>
                        `);
                        radio.on('click', function() { $(this).find('input').prop('checked', true).trigger('change'); });
                        radio.find('input').on('change', function() { hiddenInput.val(this.value); });
                        col.append(radio);
                        row.append(col);
                    });
                    container.append(row);
                }
            }
        }

        $('.course-select').on('change', function() {
            const currentId = $(this).attr('id');
            const val = $(this).val();
            if(!val) return;

            let duplicated = false;
            $('.course-select').each(function() {
                if($(this).attr('id') !== currentId && $(this).val() == val) { duplicated = true; }
            });

            if(duplicated) {
                Swal.fire({ icon: 'warning', title: 'เลือกอันดับซ้ำ', text: 'ท่านได้เลือกแผนการเรียนนี้ไปแล้วในอันดับอื่น' });
                $(this).val('').trigger('change');
                return;
            }

            if(currentId === 'recruit_tpyeRoom1') { 
                updateSportSelectionVisibility(); 
                
                //Sync recruit_major with selected course branch
                const selectedText = $(this).find('option:selected').text();
                if(selectedText && !selectedText.includes('--')) {
                    $('#recruit_major').val(selectedText);
                }
            }
        });

        $('#recruit_category').on('change', updateSportSelectionVisibility);
        
        // Initial setup
        updateCourses($('#recruit_regLevel').val());

        // ID Card formatting
        $('#recruit_idCard').on('input', function(e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
            e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
        }).trigger('input');

        // Submit Handling
        $('#editForm').on('submit', function(e) {
            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                html: 'กรุณารอสักครู่ ระบบกำลังอัปโหลดไฟล์และประมวลผล',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });
    });
</script>
<?= $this->endSection() ?>
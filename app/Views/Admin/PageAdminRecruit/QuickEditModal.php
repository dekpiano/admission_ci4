<?php
$regLevel = $recruit['recruit_regLevel'] ?? '1';
$imgSrc = get_recruit_file_url(($recruit['recruit_img'] ?? 'default.png'), $regLevel, 'img');
$defaultAvatar = base_url('sneat-assets/img/avatars/1.png');

$certEduUrl = !empty($recruit['recruit_certificateEdu']) ? get_recruit_file_url($recruit['recruit_certificateEdu'], $regLevel, 'certificate') : '';
$certEduBUrl = !empty($recruit['recruit_certificateEduB']) ? get_recruit_file_url($recruit['recruit_certificateEduB'], $regLevel, 'certificateB') : '';
$copyIdUrl = !empty($recruit['recruit_copyidCard']) ? get_recruit_file_url($recruit['recruit_copyidCard'], $regLevel, 'copyidCard') : '';
?>

<form id="quickEditForm" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="recruit_id" value="<?= esc($recruit['recruit_id']) ?>">
    <input type="hidden" id="quick_recruit_major" name="recruit_major" value="<?= esc($recruit['recruit_major'] ?? '') ?>">
    <input type="hidden" name="recruit_img_cropped" id="quick_recruit_img_cropped">

    <!-- Modal Header -->
    <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, rgba(255, 107, 139, 0.08) 0%, rgba(86, 204, 242, 0.08) 100%);">
        <div class="d-flex align-items-center gap-2">
            <span class="badge font-monospace fw-bold" style="background: #2b3445; color: #fff;">#<?= sprintf('%04d', $recruit['recruit_id']) ?></span>
            <h5 class="modal-title fw-bold text-dark mb-0">
                แก้ไขข้อมูล: <?= esc(($recruit['recruit_prefix'] ?? '') . ($recruit['recruit_firstName'] ?? '') . ' ' . ($recruit['recruit_lastName'] ?? '')) ?>
            </h5>
            <span class="badge <?= $regLevel === '1' ? 'bg-primary' : 'bg-info' ?>">ม.<?= esc($regLevel) ?></span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body p-4" style="color: #2b3445; font-family: 'K2D', sans-serif;">
        <!-- Nav Tabs -->
        <ul class="nav nav-pills nav-fill mb-3 p-1 rounded-3" style="background: #f1f5f9; gap: 4px;" id="quickEditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active py-2 rounded-2 fw-semibold" id="qtab-general-btn" data-bs-toggle="pill" data-bs-target="#qtab-general" type="button" role="tab" style="font-size: 0.82rem;">
                    <i class="bx bx-user me-1"></i>1. แผนการเรียน & ส่วนตัว
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 rounded-2 fw-semibold" id="qtab-address-btn" data-bs-toggle="pill" data-bs-target="#qtab-address" type="button" role="tab" style="font-size: 0.82rem;">
                    <i class="bx bx-home me-1"></i>2. ที่อยู่ & การศึกษา
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 rounded-2 fw-semibold" id="qtab-docs-btn" data-bs-toggle="pill" data-bs-target="#qtab-docs" type="button" role="tab" style="font-size: 0.82rem;">
                    <i class="bx bx-paperclip me-1"></i>3. รูปถ่าย & เอกสาร & สถานะ
                </button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="quickEditTabsContent" style="padding-right: 4px;">
            
            <!-- Tab 1: General & Major -->
            <div class="tab-pane fade show active" id="qtab-general" role="tabpanel">
                <div class="row g-2">
                    <!-- Level & Quota -->
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">ระดับชั้นที่สมัคร <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" name="recruit_regLevel" id="quick_recruit_regLevel" required>
                            <option value="1" <?= $regLevel == '1' ? 'selected' : '' ?>>มัธยมศึกษาปีที่ 1 (ม.1)</option>
                            <option value="4" <?= $regLevel == '4' ? 'selected' : '' ?>>มัธยมศึกษาปีที่ 4 (ม.4)</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">ประเภทโควตา <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" name="recruit_category" id="quick_recruit_category" required>
                            <?php foreach ($quotas as $q): ?>
                                <option value="<?= esc($q->quota_id) ?>" data-key="<?= esc($q->quota_key) ?>" data-courses="<?= esc($q->quota_course ?? '') ?>" <?= ($recruit['recruit_category'] == $q->quota_id) ? 'selected' : '' ?>>
                                    <?= esc($q->quota_explain) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Course Ranking 1, 2, 3 -->
                    <div class="col-12">
                        <div class="p-2 rounded-3 border mt-1" style="background: rgba(255, 107, 139, 0.04); border-color: rgba(255, 107, 139, 0.2) !important;">
                            <span class="small fw-bold text-dark d-block mb-2"><i class="bx bx-list-check me-1 text-primary"></i>เลือกอันดับแผนการเรียน</span>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small mb-1 fw-bold text-primary">อันดับ 1 (หลัก) <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm quick-course-select" id="quick_recruit_tpyeRoom1" name="recruit_tpyeRoom1" required>
                                        <option value="" disabled>-- เลือกอันดับ 1 --</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="quick_rank2_container">
                                    <label class="form-label small mb-1">อันดับ 2</label>
                                    <select class="form-select form-select-sm quick-course-select" id="quick_recruit_tpyeRoom2" name="recruit_tpyeRoom2">
                                        <option value="">-- ไม่ระบุ --</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="quick_rank3_container">
                                    <label class="form-label small mb-1">อันดับ 3</label>
                                    <select class="form-select form-select-sm quick-course-select" id="quick_recruit_tpyeRoom3" name="recruit_tpyeRoom3">
                                        <option value="">-- ไม่ระบุ --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Sport Age Selection (Conditional) -->
                            <div id="quick_sport_age_section" class="mt-2 pt-2 border-top" style="display: none;">
                                <label class="form-label small fw-bold mb-1" style="color: #ff6b8b;"><i class="bx bx-run me-1"></i>รุ่นอายุ (โควตากีฬา) <span class="text-danger">*</span></label>
                                <div id="quick_age_radio_container" class="d-flex gap-3 flex-wrap"></div>
                                <input type="hidden" name="recruit_agegroup" id="quick_recruit_agegroup" value="<?= esc($recruit['recruit_agegroup'] ?? '') ?>">
                            </div>

                            <!-- Sport Extra Fields (Conditional) -->
                            <div id="quick_sport_extra_fields" class="mt-2 pt-2 border-top" style="display: none;">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold" style="color: #ff6b8b;"><i class="bx bx-run me-1"></i>ชนิดกีฬา / ตำแหน่ง <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="recruit_sportPosition" id="quick_recruit_sportPosition" value="<?= esc($recruit['recruit_sportPosition'] ?? '') ?>" placeholder="ระบุชนิดกีฬาหรือตำแหน่ง">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label small fw-bold">ชื่อเล่น <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="recruit_nickname" id="quick_recruit_nickname" value="<?= esc($recruit['recruit_nickname'] ?? '') ?>" placeholder="ชื่อเล่น">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label small fw-bold">น้ำหนัก (กก.) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.1" class="form-control form-control-sm" name="recruit_weight" id="quick_recruit_weight" value="<?= esc($recruit['recruit_weight'] ?? '') ?>" placeholder="0.0">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label small fw-bold">ส่วนสูง (ซม.) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.1" class="form-control form-control-sm" name="recruit_height" id="quick_recruit_height" value="<?= esc($recruit['recruit_height'] ?? '') ?>" placeholder="0.0">
                                    </div>
                                    <!-- Parents (Sports Only) -->
                                    <div class="col-sm-6 mt-1">
                                        <label class="form-label small fw-bold">ชื่อ-สกุล บิดา <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="recruit_fatherName" id="quick_recruit_fatherName" value="<?= esc($recruit['recruit_fatherName'] ?? '') ?>" placeholder="ชื่อ-นามสกุล บิดา">
                                    </div>
                                    <div class="col-sm-6 mt-1">
                                        <label class="form-label small fw-bold">อาชีพ บิดา <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="recruit_fatherJob" id="quick_recruit_fatherJob" value="<?= esc($recruit['recruit_fatherJob'] ?? '') ?>" placeholder="อาชีพบิดา">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label small fw-bold">ชื่อ-สกุล มารดา <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="recruit_motherName" id="quick_recruit_motherName" value="<?= esc($recruit['recruit_motherName'] ?? '') ?>" placeholder="ชื่อ-นามสกุล มารดา">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label small fw-bold">อาชีพ มารดา <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="recruit_motherJob" id="quick_recruit_motherJob" value="<?= esc($recruit['recruit_motherJob'] ?? '') ?>" placeholder="อาชีพมารดา">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <div class="col-sm-3">
                        <label class="form-label small fw-bold">คำนำหน้า <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" name="recruit_prefix" required>
                            <option value="เด็กชาย" <?= ($recruit['recruit_prefix'] == 'เด็กชาย') ? 'selected' : '' ?>>เด็กชาย</option>
                            <option value="เด็กหญิง" <?= ($recruit['recruit_prefix'] == 'เด็กหญิง') ? 'selected' : '' ?>>เด็กหญิง</option>
                            <option value="นาย" <?= ($recruit['recruit_prefix'] == 'นาย') ? 'selected' : '' ?>>นาย</option>
                            <option value="นางสาว" <?= ($recruit['recruit_prefix'] == 'นางสาว') ? 'selected' : '' ?>>นางสาว</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-bold">ชื่อจริง <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="recruit_firstName" value="<?= esc($recruit['recruit_firstName'] ?? '') ?>" required>
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label small fw-bold">นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="recruit_lastName" value="<?= esc($recruit['recruit_lastName'] ?? '') ?>" required>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">เลขบัตรประชาชน (13 หลัก) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm font-monospace" id="quick_recruit_idCard" name="recruit_idCard" value="<?= esc($recruit['recruit_idCard'] ?? '') ?>" maxlength="17" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">วันเดือนปีเกิด (พ.ศ.) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="quick_recruit_birthday" name="recruit_birthday" value="<?= esc($recruit['recruit_birthday'] ?? '') ?>" required placeholder="วว/ดด/ปปปป">
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">เบอร์โทรศัพท์ติดต่อ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="recruit_phone" value="<?= esc($recruit['recruit_phone'] ?? '') ?>" required>
                    </div>
                    <?php 
                        $commonRaces = ['ไทย', 'จีน', 'พม่า', 'กัมพูชา', 'ลาว', 'เวียดนาม', 'มาเลเซีย', 'ญี่ปุ่น', 'เกาหลี', 'อเมริกา', 'อังกฤษ', 'ฝรั่งเศส', 'เยอรมนี'];
                        $currentRace = $recruit['recruit_race'] ?? 'ไทย';
                        $commonNats = ['ไทย', 'จีน', 'พม่า', 'กัมพูชา', 'ลาว', 'เวียดนาม', 'มาเลเซีย', 'ญี่ปุ่น', 'เกาหลี', 'อเมริกา', 'อังกฤษ', 'ฝรั่งเศส', 'เยอรมนี'];
                        $currentNat = $recruit['recruit_nationality'] ?? 'ไทย';
                    ?>
                    <div class="col-sm-2">
                        <label class="form-label small">สัญชาติ</label>
                        <select class="form-select form-select-sm quick-select2-tags" id="quick_recruit_nationality" name="recruit_nationality" required>
                            <?php foreach ($commonNats as $nat) : ?>
                                <option value="<?= $nat ?>" <?= $currentNat == $nat ? 'selected' : '' ?>><?= $nat ?></option>
                            <?php endforeach; ?>
                            <?php if (!empty($currentNat) && !in_array($currentNat, $commonNats)) : ?>
                                <option value="<?= esc($currentNat) ?>" selected><?= esc($currentNat) ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="form-label small">เชื้อชาติ</label>
                        <select class="form-select form-select-sm quick-select2-tags" id="quick_recruit_race" name="recruit_race" required>
                            <?php foreach ($commonRaces as $race) : ?>
                                <option value="<?= $race ?>" <?= $currentRace == $race ? 'selected' : '' ?>><?= $race ?></option>
                            <?php endforeach; ?>
                            <?php if (!empty($currentRace) && !in_array($currentRace, $commonRaces)) : ?>
                                <option value="<?= esc($currentRace) ?>" selected><?= esc($currentRace) ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="form-label small">ศาสนา</label>
                        <select class="form-select form-select-sm quick-select2-tags" id="quick_recruit_religion" name="recruit_religion" required>
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
                </div>
            </div>

            <!-- Tab 2: Address & Education -->
            <div class="tab-pane fade" id="qtab-address" role="tabpanel">
                <div class="row g-2">
                    <!-- Education -->
                    <div class="col-sm-8">
                        <label class="form-label small fw-bold">โรงเรียนเดิมที่จบการศึกษา <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bx bx-buildings text-muted"></i></span>
                            <select class="form-select form-select-sm" id="quick_recruit_oldSchool_select">
                                <?php if (!empty($recruit['recruit_oldSchool'])): ?>
                                    <option value="<?= esc($recruit['recruit_oldSchool']) ?>" selected><?= esc($recruit['recruit_oldSchool']) ?></option>
                                <?php else: ?>
                                    <option value="">-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <input type="hidden" name="recruit_oldSchool" id="quick_recruit_oldSchool" value="<?= esc($recruit['recruit_oldSchool'] ?? '') ?>" required>
                        <span class="text-muted" style="font-size: 0.7rem;">* พิมพ์ชื่อโรงเรียนเพื่อค้นหา ระบบจะกรอกอำเภอ/จังหวัดให้อัตโนมัติ</span>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-bold">เกรดเฉลี่ย (GPAX)</label>
                        <input type="number" step="0.01" min="0" max="4.00" class="form-control form-control-sm" name="recruit_grade" value="<?= esc($recruit['recruit_grade'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small">อำเภอของโรงเรียนเดิม</label>
                        <input type="text" class="form-control form-control-sm" id="quick_recruit_district" name="recruit_district" value="<?= esc($recruit['recruit_district'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small">จังหวัดของโรงเรียนเดิม</label>
                        <input type="text" class="form-control form-control-sm" id="quick_recruit_province" name="recruit_province" value="<?= esc($recruit['recruit_province'] ?? '') ?>">
                    </div>

                    <!-- Address -->
                    <div class="col-12 mt-2 pt-2 border-top">
                        <span class="small fw-bold text-dark d-block mb-1"><i class="bx bx-map-pin me-1 text-primary"></i>ที่อยู่ตามทะเบียนบ้าน</span>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small">บ้านเลขที่ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="recruit_homeNumber" value="<?= esc($recruit['recruit_homeNumber'] ?? '') ?>" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small">หมู่ที่</label>
                        <input type="text" class="form-control form-control-sm" name="recruit_homeGroup" value="<?= esc($recruit['recruit_homeGroup'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small">ถนน / ซอย</label>
                        <input type="text" class="form-control form-control-sm" name="recruit_homeRoad" value="<?= esc($recruit['recruit_homeRoad'] ?? '') ?>">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small">ตำบล/แขวง <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="quick_recruit_homeSubdistrict" name="recruit_homeSubdistrict" value="<?= esc($recruit['recruit_homeSubdistrict'] ?? '') ?>" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small">อำเภอ/เขต <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="quick_recruit_homedistrict" name="recruit_homedistrict" value="<?= esc($recruit['recruit_homedistrict'] ?? '') ?>" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small">จังหวัด <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="quick_recruit_homeProvince" name="recruit_homeProvince" value="<?= esc($recruit['recruit_homeProvince'] ?? '') ?>" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="quick_recruit_homePostcode" name="recruit_homePostcode" value="<?= esc($recruit['recruit_homePostcode'] ?? '') ?>" required>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Documents & Status -->
            <div class="tab-pane fade" id="qtab-docs" role="tabpanel">
                <div class="row g-2">
                    <!-- Status Controls -->
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">สถานะเอกสาร <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm fw-bold" name="recruit_status" id="quick_recruit_status" required>
                            <option value="ผ่านการตรวจสอบ" <?= ($recruit['recruit_status'] === 'ผ่านการตรวจสอบ') ? 'selected' : '' ?>>✅ ผ่านการตรวจสอบ</option>
                            <option value="รอการตรวจสอบ" <?= ($recruit['recruit_status'] === 'รอการตรวจสอบ' || empty($recruit['recruit_status'])) ? 'selected' : '' ?>>⏳ รอการตรวจสอบ</option>
                            <option value="ไม่ผ่านการตรวจสอบ" <?= (strpos($recruit['recruit_status'] ?? '', 'ไม่ผ่าน') !== false) ? 'selected' : '' ?>>❌ ไม่ผ่านการตรวจสอบ</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">ผลการคัดเลือก (กีฬา/ข้อเขียน)</label>
                        <select class="form-select form-select-sm" name="recruit_sportSelectionResult">
                            <option value="รอคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] === 'รอคัดเลือก' || empty($recruit['recruit_sportSelectionResult'])) ? 'selected' : '' ?>>⏳ รอคัดเลือก / รอสอบ</option>
                            <option value="ผ่านการคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] === 'ผ่านการคัดเลือก') ? 'selected' : '' ?>>✅ ผ่านการคัดเลือก / สอบผ่าน</option>
                            <option value="ไม่ผ่านการคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] === 'ไม่ผ่านการคัดเลือก') ? 'selected' : '' ?>>❌ ไม่ผ่าน</option>
                            <option value="ไม่มาคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] === 'ไม่มาคัดเลือก') ? 'selected' : '' ?>>🚫 ขาดสอบ / ไม่มา</option>
                        </select>
                    </div>

                    <!-- Avatar Upload -->
                    <div class="col-12 mt-2 pt-2 border-top">
                        <span class="small fw-bold text-dark d-block mb-1"><i class="bx bx-image me-1 text-primary"></i>รูปถ่ายประจำตัวผู้สมัคร</span>
                        <div class="d-flex align-items-center gap-3">
                            <img id="quick_preview_avatar" src="<?= $imgSrc ?>" class="rounded-3 shadow-sm object-fit-cover" style="width: 60px; height: 75px; border: 2px solid #e2e8f0;" onerror="this.onerror=null;this.src='<?= $defaultAvatar ?>';">
                            <div class="flex-grow-1">
                                <input type="file" class="form-control form-control-sm" name="recruit_img" id="quick_recruit_img" accept="image/*">
                                <span class="text-muted" style="font-size: 0.72rem;">รองรับ JPG, PNG, WEBP (ขนาดไม่เกิน 5MB)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Document Files -->
                    <div class="col-sm-6 mt-2 pt-2 border-top">
                        <label class="form-label small fw-semibold">ไฟล์ ปพ.1 (ด้านหน้า)</label>
                        <input type="file" class="form-control form-control-sm" name="recruit_certificateEdu" accept=".pdf,image/*">
                        <?php if (!empty($certEduUrl)): ?>
                            <span class="text-success small" style="font-size: 0.72rem;"><i class="bx bx-check me-1"></i>มีไฟล์เดิมในระบบแล้ว</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6 mt-2 pt-2 border-top">
                        <label class="form-label small fw-semibold">ไฟล์ ปพ.1 (ด้านหลัง)</label>
                        <input type="file" class="form-control form-control-sm" name="recruit_certificateEduB" accept=".pdf,image/*">
                        <?php if (!empty($certEduBUrl)): ?>
                            <span class="text-success small" style="font-size: 0.72rem;"><i class="bx bx-check me-1"></i>มีไฟล์เดิมในระบบแล้ว</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">สำเนาบัตรประชาชน</label>
                        <input type="file" class="form-control form-control-sm" name="recruit_copyidCard" accept=".pdf,image/*">
                        <?php if (!empty($copyIdUrl)): ?>
                            <span class="text-success small" style="font-size: 0.72rem;"><i class="bx bx-check me-1"></i>มีไฟล์เดิมในระบบแล้ว</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">สำเนาทะเบียนบ้าน</label>
                        <input type="file" class="form-control form-control-sm" name="recruit_copyAddress" accept=".pdf,image/*">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer border-top d-flex align-items-center justify-content-between p-3" style="background: #f8fafc;">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="openQuickViewModal(<?= $recruit['recruit_id'] ?>)">
            <i class="bx bx-arrow-back me-1"></i>กลับไปดูข้อมูล
        </button>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-sm px-4 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #ff6b8b 0%, #ff8e53 100%); border: none;">
                <i class="bx bx-save me-1"></i>บันทึกการแก้ไข
            </button>
        </div>
    </div>
</form>

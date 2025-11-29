<?php  
    $guardian = $OtherConf[0] ?? null;
    if($OtherCkeck == 1){
        $Action = 'FormConfirmOtherUpdate';
    }else{
        $Action = 'FormConfirmOther';
    }
?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation FormConfirmOther" novalidate>
    <input type="hidden" id="par_stuIDO" name="par_stuIDO" value="<?php echo $stu[0]->recruit_idCard; ?>" readonly>
    <input type="hidden" id="par_relationKeyO" name="par_relationKeyO" value="ผู้ปกครอง" readonly>
    <input type="hidden" id="par_idO" name="par_idO" value="<?=$guardian->par_id ?? ''?>" readonly>

    <!-- Section 0: เลือกผู้ปกครอง -->
    <div class="card mb-4 shadow-sm border-0 rounded-3 bg-label-primary">
        <div class="card-body">
            <h5 class="mb-3 text-primary fw-bold"><i class="bx bx-select-multiple me-2"></i>เลือกผู้ปกครอง</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label mb-2">ผู้ปกครองคือนักเรียนอาศัยอยู่ด้วยปัจจุบัน:</label>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check custom-option custom-option-basic">
                            <label class="form-check-label custom-option-content" for="useFather">
                                <input name="guardianOption" class="form-check-input" type="radio" value="father" id="useFather" onchange="selectGuardian('father')" <?=empty($FatherConf) ? 'disabled' : ''?> <?= ($guardian->par_relation ?? '') == 'บิดา' ? 'checked' : '' ?> />
                                <span class="custom-option-header">
                                    <span class="h6 mb-0">บิดา</span>
                                    <small class="text-muted">ใช้ข้อมูลบิดา <?=empty($FatherConf) ? '<span class="text-danger">(ยังไม่กรอกข้อมูล)</span>' : ''?></small>
                                </span>
                            </label>
                        </div>

                        <div class="form-check custom-option custom-option-basic">
                            <label class="form-check-label custom-option-content" for="useMother">
                                <input name="guardianOption" class="form-check-input" type="radio" value="mother" id="useMother" onchange="selectGuardian('mother')" <?=empty($MotherConf) ? 'disabled' : ''?> <?= ($guardian->par_relation ?? '') == 'มารดา' ? 'checked' : '' ?> />
                                <span class="custom-option-header">
                                    <span class="h6 mb-0">มารดา</span>
                                    <small class="text-muted">ใช้ข้อมูลมารดา <?=empty($MotherConf) ? '<span class="text-danger">(ยังไม่กรอกข้อมูล)</span>' : ''?></small>
                                </span>
                            </label>
                        </div>

                        <div class="form-check custom-option custom-option-basic">
                            <label class="form-check-label custom-option-content" for="useOther">
                                <input name="guardianOption" class="form-check-input" type="radio" value="other" id="useOther" onchange="selectGuardian('other')" <?= ($guardian->par_relation ?? '') != 'บิดา' && ($guardian->par_relation ?? '') != 'มารดา' ? 'checked' : '' ?> />
                                <span class="custom-option-header">
                                    <span class="h6 mb-0">บุคคลอื่น</span>
                                    <small class="text-muted">กรอกข้อมูลใหม่</small>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Data for Copying -->
    <?php if(!empty($FatherConf)): $f = $FatherConf[0]; ?>
    <div id="fatherData" style="display:none;"
        data-prefix="<?=$f->par_prefix?>" data-firstname="<?=$f->par_firstName?>" data-lastname="<?=$f->par_lastName?>"
        data-ago="<?=$f->par_ago?>" data-idnumber="<?=$f->par_IdNumber?>" data-phone="<?=$f->par_phone?>"
        data-race="<?=$f->par_race?>" data-national="<?=$f->par_national?>" data-religion="<?=$f->par_religion?>"
        data-career="<?=$f->par_career?>" data-education="<?=$f->par_education?>" data-salary="<?=$f->par_salary?>"
        data-position="<?=$f->par_positionJob?>" data-decease="<?=$f->par_decease?>"
        data-hnumber="<?=$f->par_hNumber?>" data-hmoo="<?=$f->par_hMoo?>" data-htambon="<?=$f->par_hTambon?>"
        data-hdistrict="<?=$f->par_hDistrict?>" data-hprovince="<?=$f->par_hProvince?>" data-hpostcode="<?=$f->par_hPostcode?>"
        data-cnumber="<?=$f->par_cNumber?>" data-cmoo="<?=$f->par_cMoo?>" data-ctambon="<?=$f->par_cTambon?>"
        data-cdistrict="<?=$f->par_cDistrict?>" data-cprovince="<?=$f->par_cProvince?>" data-cpostcode="<?=$f->par_cPostcode?>"
        data-rest="<?=$f->par_rest?>" data-restorthor="<?=$f->par_restOrthor?>"
        data-service="<?=$f->par_service?>" data-servicename="<?=$f->par_serviceName?>" data-claim="<?=$f->par_claim?>"
    ></div>
    <?php endif; ?>

    <?php if(!empty($MotherConf)): $m = $MotherConf[0]; ?>
    <div id="motherData" style="display:none;"
        data-prefix="<?=$m->par_prefix?>" data-firstname="<?=$m->par_firstName?>" data-lastname="<?=$m->par_lastName?>"
        data-ago="<?=$m->par_ago?>" data-idnumber="<?=$m->par_IdNumber?>" data-phone="<?=$m->par_phone?>"
        data-race="<?=$m->par_race?>" data-national="<?=$m->par_national?>" data-religion="<?=$m->par_religion?>"
        data-career="<?=$m->par_career?>" data-education="<?=$m->par_education?>" data-salary="<?=$m->par_salary?>"
        data-position="<?=$m->par_positionJob?>" data-decease="<?=$m->par_decease?>"
        data-hnumber="<?=$m->par_hNumber?>" data-hmoo="<?=$m->par_hMoo?>" data-htambon="<?=$m->par_hTambon?>"
        data-hdistrict="<?=$m->par_hDistrict?>" data-hprovince="<?=$m->par_hProvince?>" data-hpostcode="<?=$m->par_hPostcode?>"
        data-cnumber="<?=$m->par_cNumber?>" data-cmoo="<?=$m->par_cMoo?>" data-ctambon="<?=$m->par_cTambon?>"
        data-cdistrict="<?=$m->par_cDistrict?>" data-cprovince="<?=$m->par_cProvince?>" data-cpostcode="<?=$m->par_cPostcode?>"
        data-rest="<?=$m->par_rest?>" data-restorthor="<?=$m->par_restOrthor?>"
        data-service="<?=$m->par_service?>" data-servicename="<?=$m->par_serviceName?>" data-claim="<?=$m->par_claim?>"
    ></div>
    <?php endif; ?>

    <!-- Section 1: ข้อมูลส่วนตัวผู้ปกครอง -->
    <div class="card mb-4 shadow-sm border-0 rounded-3" id="guardianFormSection">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-user me-2"></i>ข้อมูลส่วนตัวผู้ปกครอง</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="par_relationO" class="form-label">ความสัมพันธ์กับนักเรียน</label>
                    <input type="text" class="form-control" id="par_relationO" name="par_relationO"
                        placeholder="" value="<?=$guardian->par_relation ?? ''?>" required>
                    <small class="text-muted mt-1 d-block"><i class="bx bx-info-circle me-1"></i>กรณีที่ไม่ใช่ บิดา หรือ มารดา ให้ระบุ เช่น ตา, ยาย, ลุง, ป้า, พี่สาว เป็นต้น</small>
                </div>
                <div class="col-md-3">
                    <label for="par_prefixO" class="form-label">คำนำหน้า</label>
                    <input type="text" class="form-control" placeholder="" id="par_prefixO"
                        name="par_prefixO" value="<?=$guardian->par_prefix ?? ''?>" required>
                </div>
                <div class="col-md-3">
                    <label for="par_firstNameO" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" placeholder="" id="par_firstNameO"
                        name="par_firstNameO" required value="<?=$guardian->par_firstName ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_lastNameO" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" placeholder="" id="par_lastNameO"
                        name="par_lastNameO" required value="<?=$guardian->par_lastName ?? ''?>">
                </div>
                
                <div class="col-md-2">
                    <label for="par_agoO" class="form-label">อายุ (ปี)</label>
                    <input type="text" class="form-control" id="par_agoO" name="par_agoO" placeholder=""
                        value="<?=$guardian->par_ago ?? ''?>" required>
                </div>
                <div class="col-md-4">
                    <label for="par_IdNumberO" class="form-label">รหัสประจำตัวประชาชน</label>
                    <input type="text" class="form-control" id="par_IdNumberO" placeholder=""
                        name="par_IdNumberO" data-inputmask="'mask': '9-9999-99999-99-9'" required
                        value="<?=$guardian->par_IdNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_phoneO" class="form-label">เบอร์โทรศัพท์</label>
                    <input type="text" class="form-control" id="par_phoneO" name="par_phoneO"
                        placeholder="" data-inputmask="'mask': '999-999-9999'" required
                        value="<?=$guardian->par_phone ?? ''?>">
                </div>
                 <div class="col-md-3">
                    <label for="par_deceaseO" class="form-label">วันที่ถึงแก่กรรม (ถ้ามี)</label>
                    <input type="date" class="form-control" id="par_deceaseO" name="par_deceaseO"
                        placeholder="" value="<?=$guardian->par_decease ?? ''?>">
                </div>

                <?php 
                    $nation_options = ["ไทย", "จีน", "ญี่ปุ่น", "เกาหลี", "เวียดนาม", "ลาว", "กัมพูชา", "พม่า", "มาเลเซีย", "สิงคโปร์", "อินเดีย", "อื่นๆ"];
                    $religion_options = ["พุทธ", "อิสลาม", "คริสต์", "ฮินดู", "ซิกข์", "ไม่นับถือศาสนา", "อื่นๆ"];
                ?>
                <div class="col-md-4">
                    <label for="par_raceO" class="form-label">เชื้อชาติ</label>
                    <select class="form-select" id="par_raceO" name="par_raceO" required>
                        <option value="">เลือกเชื้อชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($guardian->par_race ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="par_nationalO" class="form-label">สัญชาติ</label>
                    <select class="form-select" id="par_nationalO" name="par_nationalO" required>
                        <option value="">เลือกสัญชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($guardian->par_national ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="par_religionO" class="form-label">ศาสนา</label>
                    <select class="form-select" id="par_religionO" name="par_religionO" required>
                        <option value="">เลือกศาสนา</option>
                        <?php foreach($religion_options as $op): ?>
                            <option value="<?=$op?>" <?=($guardian->par_religion ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: ข้อมูลอาชีพ -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-briefcase me-2"></i>ข้อมูลอาชีพ</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="par_careerO" class="form-label">อาชีพ</label>
                    <input type="text" class="form-control" id="par_careerO" name="par_careerO" placeholder=""
                        required value="<?=$guardian->par_career ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_educationO" class="form-label">วุฒิการศึกษา</label>
                    <input type="text" class="form-control" id="par_educationO" name="par_educationO"
                        placeholder="" required value="<?=$guardian->par_education ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_salaryO" class="form-label">รายได้ต่อเดือน (บาท)</label>
                    <input type="number" class="form-control" id="par_salaryO" name="par_salaryO" placeholder=""
                        required value="<?=$guardian->par_salary ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_positionJobO" class="form-label">ตำแหน่งงาน</label>
                    <input type="text" class="form-control" id="par_positionJobO" name="par_positionJobO"
                        placeholder="" required value="<?=$guardian->par_positionJob ?? ''?>">
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">กรณีรับราชการ</label>
                    <div class="row row-cols-auto g-2">
                        <?php $Name = array('กระทรวง','กรม','กอง','ฝ่าย/แผนก');
                        foreach ($Name as $key => $v_Name) : ?>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input par_serviceO" type="radio" name="par_serviceO"
                                        id="par_serviceO<?=$key?>" value="<?=$v_Name?>"
                                        <?php if(isset($guardian->par_service) && $guardian->par_service == $v_Name) echo "checked"; ?>>
                                    <label class="form-check-label" for="par_serviceO<?=$key?>"><?=$v_Name?></label>
                                </div>
                                <input type="text" class="form-control form-control-sm w-auto" 
                                    id="par_serviceNameO<?=$key?>" name="par_serviceNameO[]" placeholder="ระบุชื่อ<?=$v_Name?>"
                                    value="<?=($guardian->par_service ?? '') == $v_Name ? ($guardian->par_serviceName ?? '') : ''?>"
                                    style="<?=($guardian->par_service ?? '') == $v_Name ? '' : 'display:none;'?>">
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input par_serviceO" type="radio" name="par_serviceO"
                                    id="par_serviceO99" value="ไม่ได้รับราชการ"
                                    <?php if(!isset($guardian->par_service) || $guardian->par_service == "ไม่ได้รับราชการ") echo "checked"; ?>>
                                <label class="form-check-label" for="par_serviceO99">ไม่ได้รับราชการ</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">สิทธิ์ในการเบิกค่าเล่าเรียนบุตร</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="par_claimO" id="par_claimO1"
                                value="เบิกได้" <?php if(isset($guardian->par_claim) && $guardian->par_claim == "เบิกได้") echo "checked"; ?>>
                            <label class="form-check-label" for="par_claimO1">เบิกได้</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="par_claimO" id="par_claimO2"
                                value="เบิกไม่ได้" <?php if(!isset($guardian->par_claim) || $guardian->par_claim == "เบิกไม่ได้") echo "checked"; ?>>
                            <label class="form-check-label" for="par_claimO2">เบิกไม่ได้</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: ที่อยู่ตามทะเบียนบ้าน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-home me-2"></i>ที่อยู่ตามทะเบียนบ้าน</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="par_hNumberO" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_hNumberO"
                        name="par_hNumberO" required value="<?=$guardian->par_hNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hMooO" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_hMooO"
                        name="par_hMooO" required value="<?=$guardian->par_hMoo ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hTambonO" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="par_hTambonO"
                        name="par_hTambonO" required value="<?=$guardian->par_hTambon ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hDistrictO" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="par_hDistrictO"
                        name="par_hDistrictO" required value="<?=$guardian->par_hDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hProvinceO" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="par_hProvinceO"
                        name="par_hProvinceO" required value="<?=$guardian->par_hProvince ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hPostcodeO" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="" id="par_hPostcodeO"
                        name="par_hPostcodeO" required value="<?=$guardian->par_hPostcode ?? ''?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: ที่อยู่ปัจจุบัน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-building-house me-2"></i>ที่อยู่ปัจจุบัน</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkPerO">
                <label class="form-check-label text-muted" for="checkPerO">เหมือนทะเบียนบ้าน</label>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="par_cNumberO" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_cNumberO"
                        name="par_cNumberO" required value="<?=$guardian->par_cNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cMooO" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_cMooO"
                        name="par_cMooO" required value="<?=$guardian->par_cMoo ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cTambonO" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="par_cTambonO"
                        name="par_cTambonO" required value="<?=$guardian->par_cTambon ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cDistrictO" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="par_cDistrictO"
                        name="par_cDistrictO" required value="<?=$guardian->par_cDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cProvinceO" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="par_cProvinceO"
                        name="par_cProvinceO" required value="<?=$guardian->par_cProvince ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cPostcodeO" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="" id="par_cPostcodeO"
                        name="par_cPostcodeO" required value="<?=$guardian->par_cPostcode ?? ''?>">
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">ลักษณะที่พัก</label>
                    <div class="row row-cols-auto g-2 align-items-center">
                        <?php $Name = array('บ้านตนเอง','เช่าบ้าน','อาศัยผู้อื่นอยู่','บ้านพักสวัสดิการ');
                        foreach ($Name as $key => $v_Name) : ?>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input par_restO" type="radio" name="par_restO"
                                    id="par_restO<?=$key;?>" value="<?=$v_Name;?>"
                                    <?php if((!isset($guardian->par_rest) && $v_Name == 'บ้านตนเอง') || (isset($guardian->par_rest) && $guardian->par_rest == $v_Name)) echo "checked"; ?>>
                                <label class="form-check-label" for="par_restO<?=$key;?>"><?=$v_Name;?></label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input par_restO" type="radio" name="par_restO" id="par_restO99"
                                        value="อื่นๆ" <?php if(isset($guardian->par_rest) && $guardian->par_rest == "อื่นๆ") echo "checked"; ?>>
                                    <label class="form-check-label" for="par_restO99">อื่นๆ</label>
                                </div>
                                <input type="text" class="form-control form-control-sm w-auto par_restOrthorO" placeholder="ระบุ"
                                    id="par_restOrthorO" name="par_restOrthorO" 
                                    value="<?=$guardian->par_restOrthor ?? '' ?>"
                                    style="<?=($guardian->par_rest ?? 'อื่นๆ') == 'อื่นๆ' ? '' : 'display:none;'?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
            <i class="bx bx-save me-2"></i>บันทึกข้อมูลผู้ปกครอง
        </button>
    </div>

</form>

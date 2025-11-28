<?php  
    $mother = $MotherConf[0] ?? null;
    if(($MatherCkeck) == 1){
        $Action = 'FormConfirmMatherUpdate';
    }else{
        $Action = 'FormConfirmMather';
    }
?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation" novalidate>
    <input type="hidden" id="par_stuIDM" name="par_stuIDM" value="<?php echo $stu[0]->recruit_idCard; ?>" readonly>
    <input type="hidden" id="par_relationKeyM" name="par_relationKeyM" value="แม่" readonly>
    <input type="hidden" id="par_idM" name="par_idM" value="<?=$mother->par_id ?? ''?>" readonly>

    <!-- Section 1: ข้อมูลส่วนตัวมารดา -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-female me-2"></i>ข้อมูลส่วนตัวมารดา</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="par_relationM" class="form-label">ความสัมพันธ์</label>
                    <input type="text" class="form-control" id="par_relationM" name="par_relationM"
                        placeholder="" value="<?=$mother->par_relation ?? 'มารดา'?>" required readonly style="background-color: #e9ecef;">
                </div>
                <div class="col-md-3">
                    <label for="par_prefixM" class="form-label">คำนำหน้า</label>
                    <input type="text" class="form-control" placeholder="" id="par_prefixM"
                        name="par_prefixM" value="<?=$mother->par_prefix ?? ''?>" required>
                </div>
                <div class="col-md-3">
                    <label for="par_firstNameM" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" placeholder="" id="par_firstNameM"
                        name="par_firstNameM" required value="<?=$mother->par_firstName ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_lastNameM" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" placeholder="" id="par_lastNameM"
                        name="par_lastNameM" required value="<?=$mother->par_lastName ?? ''?>">
                </div>
                
                <div class="col-md-2">
                    <label for="par_agoM" class="form-label">อายุ (ปี)</label>
                    <input type="text" class="form-control" id="par_agoM" name="par_agoM" placeholder=""
                        value="<?=$mother->par_ago ?? ''?>" required>
                </div>
                <div class="col-md-4">
                    <label for="par_IdNumberM" class="form-label">รหัสประจำตัวประชาชน</label>
                    <input type="text" class="form-control" id="par_IdNumberM" placeholder=""
                        name="par_IdNumberM" data-inputmask="'mask': '9-9999-99999-99-9'" required
                        value="<?=$mother->par_IdNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_phoneM" class="form-label">เบอร์โทรศัพท์</label>
                    <input type="text" class="form-control" id="par_phoneM" name="par_phoneM"
                        placeholder="" data-inputmask="'mask': '999-999-9999'" required
                        value="<?=$mother->par_phone ?? ''?>">
                </div>
                 <div class="col-md-3">
                    <label for="par_deceaseM" class="form-label">วันที่ถึงแก่กรรม (ถ้ามี)</label>
                    <input type="date" class="form-control" id="par_deceaseM" name="par_deceaseM"
                        placeholder="" value="<?=$mother->par_decease ?? ''?>">
                </div>

                <?php 
                    $nation_options = ["ไทย", "จีน", "ญี่ปุ่น", "เกาหลี", "เวียดนาม", "ลาว", "กัมพูชา", "พม่า", "มาเลเซีย", "สิงคโปร์", "อินเดีย", "อื่นๆ"];
                    $religion_options = ["พุทธ", "อิสลาม", "คริสต์", "ฮินดู", "ซิกข์", "ไม่นับถือศาสนา", "อื่นๆ"];
                ?>
                <div class="col-md-4">
                    <label for="par_raceM" class="form-label">เชื้อชาติ</label>
                    <select class="form-select" id="par_raceM" name="par_raceM" required>
                        <option value="">เลือกเชื้อชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($mother->par_race ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="par_nationalM" class="form-label">สัญชาติ</label>
                    <select class="form-select" id="par_nationalM" name="par_nationalM" required>
                        <option value="">เลือกสัญชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($mother->par_national ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="par_religionM" class="form-label">ศาสนา</label>
                    <select class="form-select" id="par_religionM" name="par_religionM" required>
                        <option value="">เลือกศาสนา</option>
                        <?php foreach($religion_options as $op): ?>
                            <option value="<?=$op?>" <?=($mother->par_religion ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
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
                    <label for="par_careerM" class="form-label">อาชีพ</label>
                    <input type="text" class="form-control" id="par_careerM" name="par_careerM" placeholder=""
                        required value="<?=$mother->par_career ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_educationM" class="form-label">วุฒิการศึกษา</label>
                    <input type="text" class="form-control" id="par_educationM" name="par_educationM"
                        placeholder="" required value="<?=$mother->par_education ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_salaryM" class="form-label">รายได้ต่อเดือน (บาท)</label>
                    <input type="number" class="form-control" id="par_salaryM" name="par_salaryM" placeholder=""
                        required value="<?=$mother->par_salary ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_positionJobM" class="form-label">ตำแหน่งงาน</label>
                    <input type="text" class="form-control" id="par_positionJobM" name="par_positionJobM"
                        placeholder="" required value="<?=$mother->par_positionJob ?? ''?>">
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">กรณีรับราชการ</label>
                    <div class="d-flex flex-column gap-2">
                        <?php $Name = array('กระทรวง','กรม','กอง','ฝ่าย/แผนก');
                        foreach ($Name as $key => $v_Name) : ?>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check">
                                <input class="form-check-input par_serviceM" type="radio" name="par_serviceM"
                                    id="par_serviceM<?=$key?>" value="<?=$v_Name?>"
                                    <?=($mother->par_service ?? '') ==$v_Name?"checked":""?>>
                                <label class="form-check-label" for="par_serviceM<?=$key?>"><?=$v_Name?></label>
                            </div>
                            <input type="text" class="form-control form-control-sm w-auto" 
                                id="par_serviceNameM<?=$key?>" name="par_serviceNameM[]" placeholder="ระบุชื่อ<?=$v_Name?>"
                                value="<?=$mother->par_serviceName ?? '';?>"
                                style="<?=($mother->par_service ?? '') == $v_Name ? '' : 'display:none;'?>">
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="form-check">
                            <input class="form-check-input par_serviceM" type="radio" name="par_serviceM"
                                id="par_serviceM99" value="ไม่ได้รับราชการ"
                                <?=($mother->par_service ?? 'ไม่ได้รับราชการ') =="ไม่ได้รับราชการ"?"checked":""?>>
                            <label class="form-check-label" for="par_serviceM99">ไม่ได้รับราชการ</label>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">สิทธิ์ในการเบิกค่าเล่าเรียนบุตร</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="par_claimM" id="par_claimM1"
                                value="เบิกได้" <?=($mother->par_claim ?? '') =="เบิกได้"?"checked":""?>>
                            <label class="form-check-label" for="par_claimM1">เบิกได้</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="par_claimM" id="par_claimM2"
                                value="เบิกไม่ได้" <?=($mother->par_claim ?? 'เบิกไม่ได้') =="เบิกไม่ได้"?"checked":""?>>
                            <label class="form-check-label" for="par_claimM2">เบิกไม่ได้</label>
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
                    <label for="par_hNumberM" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_hNumberM"
                        name="par_hNumberM" required value="<?=$mother->par_hNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hMooM" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_hMooM"
                        name="par_hMooM" required value="<?=$mother->par_hMoo ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hTambonM" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="par_hTambonM"
                        name="par_hTambonM" required value="<?=$mother->par_hTambon ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hDistrictM" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="par_hDistrictM"
                        name="par_hDistrictM" required value="<?=$mother->par_hDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hProvinceM" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="par_hProvinceM"
                        name="par_hProvinceM" required value="<?=$mother->par_hProvince ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hPostcodeM" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="" id="par_hPostcodeM"
                        name="par_hPostcodeM" required value="<?=$mother->par_hPostcode ?? ''?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: ที่อยู่ปัจจุบัน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-building-house me-2"></i>ที่อยู่ปัจจุบัน</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkPerM">
                <label class="form-check-label text-muted" for="checkPerM">เหมือนทะเบียนบ้าน</label>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="par_cNumberM" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_cNumberM"
                        name="par_cNumberM" required value="<?=$mother->par_cNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cMooM" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_cMooM"
                        name="par_cMooM" required value="<?=$mother->par_cMoo ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cTambonM" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="par_cTambonM"
                        name="par_cTambonM" required value="<?=$mother->par_cTambon ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cDistrictM" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="par_cDistrictM"
                        name="par_cDistrictM" required value="<?=$mother->par_cDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cProvinceM" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="par_cProvinceM"
                        name="par_cProvinceM" required value="<?=$mother->par_cProvince ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cPostcodeM" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="" id="par_cPostcodeM"
                        name="par_cPostcodeM" required value="<?=$mother->par_cPostcode ?? ''?>">
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">ลักษณะที่พัก</label>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <?php $Name = array('บ้านตนเอง','เช่าบ้าน','อาศัยผู้อื่นอยู่','บ้านพักสวัสดิการ');
                        foreach ($Name as $key => $v_Name) : ?>
                        <div class="form-check">
                            <input class="form-check-input par_restM" type="radio" name="par_restM"
                                id="par_restM<?=$key;?>" value="<?=$v_Name;?>"
                                <?=($mother->par_rest ?? 'บ้านตนเอง') ==$v_Name?"checked":""?>>
                            <label class="form-check-label" for="par_restM<?=$key;?>"><?=$v_Name;?></label>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check">
                                <input class="form-check-input par_restM" type="radio" name="par_restM" id="par_restM99"
                                    value="อื่นๆ" <?=($mother->par_rest ?? 'อื่นๆ') =="อื่นๆ"?"checked":""?>>
                                <label class="form-check-label" for="par_restM99">อื่นๆ</label>
                            </div>
                            <input type="text" class="form-control form-control-sm w-auto par_restOrthor" placeholder="ระบุ"
                                id="par_restOrthorM" name="par_restOrthorM" 
                                value="<?=$mother->par_restOrthor ?? '' ?>"
                                style="<?=($mother->par_rest ?? 'อื่นๆ') == 'อื่นๆ' ? '' : 'display:none;'?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
            <i class="bx bx-save me-2"></i>บันทึกข้อมูลมารดา
        </button>
    </div>

</form>

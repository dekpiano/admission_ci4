<?php  
    $father = $FatherConf[0] ?? null;
    if($FatherCkeck == 1){
        $Action = 'FormConfirmFatherUpdate';
    }else{
        $Action = 'FormConfirmFather';
    }
?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation" novalidate>
    <input type="hidden" id="par_stuID" name="par_stuID" value="<?php echo $stu[0]->recruit_idCard; ?>" readonly>
    <input type="hidden" id="par_relationKey" name="par_relationKey" value="พ่อ" readonly>
    <input type="hidden" id="par_id" name="par_id" value="<?=$father->par_id ?? ''?>" readonly>

    <!-- Section 1: ข้อมูลส่วนตัวบิดา -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-male me-2"></i>ข้อมูลส่วนตัวบิดา</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="par_relation" class="form-label">ความสัมพันธ์</label>
                    <input type="text" class="form-control" id="par_relation" name="par_relation"
                        placeholder="" value="<?=$father->par_relation ?? 'บิดา'?>" required readonly style="background-color: #e9ecef;">
                </div>
                <div class="col-md-3">
                    <label for="par_prefix" class="form-label">คำนำหน้า</label>
                    <input type="text" class="form-control" placeholder="" id="par_prefix"
                        name="par_prefix" value="<?=$father->par_prefix ?? ''?>" required>
                </div>
                <div class="col-md-3">
                    <label for="par_firstName" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" placeholder="" id="par_firstName"
                        name="par_firstName" required value="<?=$father->par_firstName ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_lastName" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" placeholder="" id="par_lastName"
                        name="par_lastName" required value="<?=$father->par_lastName ?? ''?>">
                </div>
                
                <div class="col-md-2">
                    <label for="par_ago" class="form-label">อายุ (ปี)</label>
                    <input type="text" class="form-control" id="par_ago" name="par_ago" placeholder=""
                        value="<?=$father->par_ago ?? ''?>" required>
                </div>
                <div class="col-md-4">
                    <label for="par_IdNumber" class="form-label">รหัสประจำตัวประชาชน</label>
                    <input type="text" class="form-control" id="par_IdNumber" placeholder=""
                        name="par_IdNumber" data-inputmask="'mask': '9-9999-99999-99-9'" required
                        value="<?=$father->par_IdNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_phone" class="form-label">เบอร์โทรศัพท์</label>
                    <input type="text" class="form-control" id="par_phone" name="par_phone"
                        placeholder="" data-inputmask="'mask': '999-999-9999'" required
                        value="<?=$father->par_phone ?? ''?>">
                </div>
                 <div class="col-md-3">
                    <label for="par_decease" class="form-label">วันที่ถึงแก่กรรม (ถ้ามี)</label>
                    <input type="date" class="form-control" id="par_decease" name="par_decease"
                        placeholder="" value="<?=$father->par_decease ?? ''?>">
                </div>

                <?php 
                    $nation_options = ["ไทย", "จีน", "ญี่ปุ่น", "เกาหลี", "เวียดนาม", "ลาว", "กัมพูชา", "พม่า", "มาเลเซีย", "สิงคโปร์", "อินเดีย", "อื่นๆ"];
                    $religion_options = ["พุทธ", "อิสลาม", "คริสต์", "ฮินดู", "ซิกข์", "ไม่นับถือศาสนา", "อื่นๆ"];
                ?>
                <div class="col-md-4">
                    <label for="par_race" class="form-label">เชื้อชาติ</label>
                    <select class="form-select" id="par_race" name="par_race" required>
                        <option value="">เลือกเชื้อชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($father->par_race ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="par_national" class="form-label">สัญชาติ</label>
                    <select class="form-select" id="par_national" name="par_national" required>
                        <option value="">เลือกสัญชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($father->par_national ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="par_religion" class="form-label">ศาสนา</label>
                    <select class="form-select" id="par_religion" name="par_religion" required>
                        <option value="">เลือกศาสนา</option>
                        <?php foreach($religion_options as $op): ?>
                            <option value="<?=$op?>" <?=($father->par_religion ?? '') == $op ? 'selected' : ''?>><?=$op?></option>
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
                    <label for="par_career" class="form-label">อาชีพ</label>
                    <input type="text" class="form-control" id="par_career" name="par_career" placeholder=""
                        required value="<?=$father->par_career ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_education" class="form-label">วุฒิการศึกษา</label>
                    <input type="text" class="form-control" id="par_education" name="par_education"
                        placeholder="" required value="<?=$father->par_education ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_salary" class="form-label">รายได้ต่อเดือน (บาท)</label>
                    <input type="number" class="form-control" id="par_salary" name="par_salary" placeholder=""
                        required value="<?=$father->par_salary ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_positionJob" class="form-label">ตำแหน่งงาน</label>
                    <input type="text" class="form-control" id="par_positionJob" name="par_positionJob"
                        placeholder="" required value="<?=$father->par_positionJob ?? ''?>">
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">กรณีรับราชการ</label>
                    <div class="d-flex flex-column gap-2">
                        <?php $Name = array('กระทรวง','กรม','กอง','ฝ่าย/แผนก');
                        foreach ($Name as $key => $v_Name) : ?>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check">
                                <input class="form-check-input par_service" type="radio" name="par_service"
                                    id="par_service<?=$key?>" value="<?=$v_Name?>"
                                    <?=($father->par_service ?? '') ==$v_Name?"checked":""?>>
                                <label class="form-check-label" for="par_service<?=$key?>"><?=$v_Name?></label>
                            </div>
                            <input type="text" class="form-control form-control-sm w-auto" 
                                id="par_serviceName<?=$key?>" name="par_serviceName[]" placeholder="ระบุชื่อ<?=$v_Name?>"
                                value="<?=$father->par_serviceName ?? '';?>"
                                style="<?=($father->par_service ?? '') == $v_Name ? '' : 'display:none;'?>">
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="form-check">
                            <input class="form-check-input par_service" type="radio" name="par_service"
                                id="par_service99" value="ไม่ได้รับราชการ"
                                <?=($father->par_service ?? 'ไม่ได้รับราชการ') =="ไม่ได้รับราชการ"?"checked":""?>>
                            <label class="form-check-label" for="par_service99">ไม่ได้รับราชการ</label>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">สิทธิ์ในการเบิกค่าเล่าเรียนบุตร</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="par_claim" id="par_claim1"
                                value="เบิกได้" <?=($father->par_claim ?? '') =="เบิกได้"?"checked":""?>>
                            <label class="form-check-label" for="par_claim1">เบิกได้</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="par_claim" id="par_claim2"
                                value="เบิกไม่ได้" <?=($father->par_claim ?? 'เบิกไม่ได้') =="เบิกไม่ได้"?"checked":""?>>
                            <label class="form-check-label" for="par_claim2">เบิกไม่ได้</label>
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
                    <label for="par_hNumber" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_hNumber"
                        name="par_hNumber" required value="<?=$father->par_hNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hMoo" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_hMoo"
                        name="par_hMoo" required value="<?=$father->par_hMoo ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hTambon" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="par_hTambon"
                        name="par_hTambon" required value="<?=$father->par_hTambon ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hDistrict" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="par_hDistrict"
                        name="par_hDistrict" required value="<?=$father->par_hDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hProvince" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="par_hProvince"
                        name="par_hProvince" required value="<?=$father->par_hProvince ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_hPostcode" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="" id="par_hPostcode"
                        name="par_hPostcode" required value="<?=$father->par_hPostcode ?? ''?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: ที่อยู่ปัจจุบัน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-building-house me-2"></i>ที่อยู่ปัจจุบัน</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkPer">
                <label class="form-check-label text-muted" for="checkPer">เหมือนทะเบียนบ้าน</label>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="par_cNumber" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_cNumber"
                        name="par_cNumber" required value="<?=$father->par_cNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cMoo" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="par_cMoo"
                        name="par_cMoo" required value="<?=$father->par_cMoo ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cTambon" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="par_cTambon"
                        name="par_cTambon" required value="<?=$father->par_cTambon ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cDistrict" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="par_cDistrict"
                        name="par_cDistrict" required value="<?=$father->par_cDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cProvince" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="par_cProvince"
                        name="par_cProvince" required value="<?=$father->par_cProvince ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="par_cPostcode" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="" id="par_cPostcode"
                        name="par_cPostcode" required value="<?=$father->par_cPostcode ?? ''?>">
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">ลักษณะที่พัก</label>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <?php $Name = array('บ้านตนเอง','เช่าบ้าน','อาศัยผู้อื่นอยู่','บ้านพักสวัสดิการ');
                        foreach ($Name as $key => $v_Name) : ?>
                        <div class="form-check">
                            <input class="form-check-input par_rest" type="radio" name="par_rest"
                                id="par_rest<?=$key;?>" value="<?=$v_Name;?>"
                                <?=($father->par_rest ?? 'บ้านตนเอง') ==$v_Name?"checked":""?>>
                            <label class="form-check-label" for="par_rest<?=$key;?>"><?=$v_Name;?></label>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check">
                                <input class="form-check-input par_rest" type="radio" name="par_rest" id="par_rest99"
                                    value="อื่นๆ" <?=($father->par_rest ?? 'อื่นๆ') =="อื่นๆ"?"checked":""?>>
                                <label class="form-check-label" for="par_rest99">อื่นๆ</label>
                            </div>
                            <input type="text" class="form-control form-control-sm w-auto par_restOrthor" placeholder="ระบุ"
                                id="par_restOrthor" name="par_restOrthor" 
                                value="<?=$father->par_restOrthor ?? '' ?>"
                                style="<?=($father->par_rest ?? 'อื่นๆ') == 'อื่นๆ' ? '' : 'display:none;'?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
            <i class="bx bx-save me-2"></i>บันทึกข้อมูลบิดา
        </button>
    </div>

</form>

<hr class="my-4 border-light">
<?php  
    if(isset($stuConf[0]->stu_iden)){
        $Action = 'FormConfirmStudentUpdate';
    }else{
        $Action = 'FormConfirmStudent';
    }
?>
<form id="<?=$Action;?>" method="post" class="check-needs-validation" novalidate>
    <input type="hidden" id="stu_img" name="stu_img" class="stu_img" value="<?=$stu[0]->recruit_img;?>">
    <input type="hidden" id="stu_UpdateConfirm" name="stu_UpdateConfirm" value="<?=$checkYear[0]->openyear_year;?>">

    <!-- Section 1: ข้อมูลพื้นฐาน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-user-circle me-2"></i>ข้อมูลพื้นฐาน</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="stu_iden" class="form-label">รหัสประจำตัวประชาชน</label>
                    <input type="text" class="form-control" id="stu_iden" placeholder="" required
                        name="stu_iden" data-inputmask="'mask': '9-9999-99999-99-9'"
                        value="<?=$stuConf[0]->stu_iden ?? $stu[0]->recruit_idCard?>" readonly style="background-color: #e9ecef;">
                </div>
                <div class="col-md-2">
                    <label for="stu_prefix" class="form-label">คำนำหน้า</label>
                    <select name="stu_prefix" id="stu_prefix" class="form-select" required>
                        <option value="">เลือกคำนำหน้า</option>
                        <?php 
                        $fix = array("เด็กหญิง","เด็กชาย","นาย","นางสาว"); 
                        foreach ($fix as $key => $v_fix) :
                        ?>
                        <option <?=($stuConf[0]->stu_prefix ?? $stu[0]->recruit_prefix) === $v_fix ?"selected":"" ?>
                            value="<?=$v_fix;?>">
                            <?=$v_fix;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="stu_fristName" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" placeholder="" id="stu_fristName"
                        name="stu_fristName" required
                        value="<?=$stuConf[0]->stu_fristName ?? $stu[0]->recruit_firstName ?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_lastName" class="form-label">นามสกุลจริง</label>
                    <input type="text" class="form-control" placeholder="" id="stu_lastName"
                        name="stu_lastName" required
                        value="<?=$stuConf[0]->stu_lastName ?? $stu[0]->recruit_lastName?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_nickName" class="form-label">ชื่อเล่น</label>
                    <input type="text" class="form-control" id="stu_nickName" name="stu_nickName"
                        placeholder="" required value="<?=$stuConf[0]->stu_nickName ?? ''?>">
                </div>
                
                <?php 
                if(@$stuConf[0]->stu_birthDay){
                    $birt =  explode("-",$stuConf[0]->stu_birthDay); 
                    // Database format is YYYY-MM-DD
                    $stuYear = intval(@$birt[0]) + 543; // Convert AD to BE
                    $stuMount = intval(@$birt[1]);
                    $stuDay = intval(@$birt[2]);
                }else{
                    $birt =  explode("-",$stu[0]->recruit_birthday); 
                    // Recruit format might be YYYY-MM-DD as well, check if year > 2400 (BE) or < 2100 (AD)
                    // Assuming recruit_birthday is also YYYY-MM-DD (AD)
                    $year = intval(@$birt[0]);
                    if ($year < 2400) {
                         $stuYear = $year + 543;
                    } else {
                         $stuYear = $year;
                    }
                    $stuMount = intval(@$birt[1]);
                    $stuDay = intval(@$birt[2]);            
                }
                ?>
                
                <div class="col-md-3">
                    <label for="stu_day" class="form-label">วันเกิด</label>
                    <select class="form-select" id="stu_day" name="stu_day" required>
                        <option value="">วัน</option>
                        <?php for ($i=1; $i <= 31 ; $i++) : ?>
                        <option <?=$stuDay==$i?"selected":"" ?> value="<?=sprintf("%02d",$i)?>"><?=$i;?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="stu_month" class="form-label">เดือนเกิด</label>
                    <select class="form-select" id="stu_month" name="stu_month" required>
                        <option value="">เดือน</option>
                        <?php 
                            $monthTH = [null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
                            for ($i=1; $i <= 12 ; $i++) : 
                            ?>
                        <option <?=$stuMount==$i?"selected":"" ?> value="<?=sprintf("%02d",$i)?>">
                            <?=$monthTH[$i];?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="stu_year" class="form-label">ปีเกิด</label>
                    <select class="form-select" id="stu_year" name="stu_year" required>
                        <option value="">ปี พ.ศ.</option>
                        <?php 
                            $d = date("Y")+543;
                            $startYear = $d - 25;
                            for ($i=$startYear; $i <= $d ; $i++) : 
                        ?>
                        <option <?=$stuYear==$i?"selected":"" ?> value="<?=$i?>"><?=$i;?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: ข้อมูลการติดต่อ & สุขภาพ -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-phone-call me-2"></i>ข้อมูลการติดต่อ & สุขภาพ</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="stu_phone" class="form-label">เบอร์โทรศัพท์มือถือ</label>
                    <input type="text" class="form-control" id="stu_phone" name="stu_phone" placeholder=""
                        required data-inputmask="'mask': '999-999-9999'"
                        value="<?=$stuConf[0]->stu_phone ?? $stu[0]->recruit_phone?>">
                </div>
                <div class="col-md-4">
                    <label for="stu_email" class="form-label">อีเมล (ถ้ามี)</label>
                    <input type="email" class="form-control" placeholder="" id="stu_email" name="stu_email"
                            value="<?=$stuConf[0]->stu_email ?? ''?>">
                </div>
                    <div class="col-md-4">
                    <label for="stu_bloodType" class="form-label">กรุ๊ปเลือด</label>
                    <select class="form-select" id="stu_bloodType" name="stu_bloodType" required>
                        <option value="">เลือกกรุ๊ปเลือด</option>
                        <?php 
                            $bloodType = array('A','B','AB','O','ไม่ทราบกรุ๊ปเลือด');
                            foreach ($bloodType as $key => $value):
                        ?>
                        <option <?=($stuConf[0]->stu_bloodType ?? '') == $value?"selected":"" ?>
                            value="<?=$value?>">
                            <?=$value;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="stu_diseaes" class="form-label">โรคประจำตัว (ถ้าไม่มีให้ระบุว่า "ไม่มี")</label>
                    <input type="text" class="form-control" id="stu_diseaes" name="stu_diseaes" placeholder=""
                        required value="<?=$stuConf[0]->stu_diseaes ?? 'ไม่มี'?>">
                </div>
                <div class="col-md-6">
                    <label for="stu_disablde" class="form-label">ความพิการ (ถ้าไม่มีให้ระบุว่า "ไม่มี")</label>
                    <input type="text" class="form-control" id="stu_disablde" name="stu_disablde"
                        placeholder="" required value="<?=$stuConf[0]->stu_disablde ?? 'ไม่มี'?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_wieght" class="form-label">น้ำหนัก (กก.)</label>
                    <input type="number" class="form-control" id="stu_wieght" name="stu_wieght" placeholder=""
                        required value="<?=$stuConf[0]->stu_wieght ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hieght" class="form-label">ส่วนสูง (ซม.)</label>
                    <input type="number" class="form-control" id="stu_hieght" name="stu_hieght" placeholder=""
                        required value="<?=$stuConf[0]->stu_hieght ?? ''?>">
                </div>
                    <div class="col-md-6">
                    <label for="stu_talent" class="form-label">ความสามารถพิเศษ</label>
                    <input type="text" class="form-control" id="stu_talent" name="stu_talent" placeholder=""
                        required value="<?=$stuConf[0]->stu_talent ?? 'ไม่มี'?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: ข้อมูลการเกิด & สัญชาติ -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-map-pin me-2"></i>ข้อมูลการเกิด & สัญชาติ</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="stu_birthHospital" class="form-label">โรงพยาบาลที่เกิด</label>
                    <input type="text" class="form-control" placeholder="" id="stu_birthHospital"
                        name="stu_birthHospital" required value="<?=$stuConf[0]->stu_birthHospital ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_birthTambon" class="form-label">ตำบลที่เกิด</label>
                    <input type="text" class="form-control" placeholder="" id="stu_birthTambon"
                        name="stu_birthTambon" required value="<?=$stuConf[0]->stu_birthTambon ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_birthDistrict" class="form-label">อำเภอที่เกิด</label>
                    <input type="text" class="form-control" placeholder="" id="stu_birthDistrict"
                        name="stu_birthDistrict" required value="<?=$stuConf[0]->stu_birthDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_birthProvirce" class="form-label">จังหวัดที่เกิด</label>
                    <input type="text" class="form-control" placeholder="" id="stu_birthProvirce"
                        name="stu_birthProvirce" required value="<?=$stuConf[0]->stu_birthProvirce ?? ''?>">
                </div>
                <?php 
                    $nation_options = ["ไทย", "จีน", "ญี่ปุ่น", "เกาหลี", "เวียดนาม", "ลาว", "กัมพูชา", "พม่า", "มาเลเซีย", "สิงคโปร์", "อินเดีย", "อื่นๆ"];
                    $religion_options = ["พุทธ", "อิสลาม", "คริสต์", "ฮินดู", "ซิกข์", "ไม่นับถือศาสนา", "อื่นๆ"];
                ?>
                <div class="col-md-4">
                    <label for="stu_nationality" class="form-label">เชื้อชาติ</label>
                    <select class="form-select" id="stu_nationality" name="stu_nationality" required>
                        <option value="">เลือกเชื้อชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($stuConf[0]->stu_nationality ?? $stu[0]->recruit_nationality) == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="stu_race" class="form-label">สัญชาติ</label>
                    <select class="form-select" id="stu_race" name="stu_race" required>
                        <option value="">เลือกสัญชาติ</option>
                        <?php foreach($nation_options as $op): ?>
                            <option value="<?=$op?>" <?=($stuConf[0]->stu_race ?? $stu[0]->recruit_race) == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="stu_religion" class="form-label">ศาสนา</label>
                    <select class="form-select" id="stu_religion" name="stu_religion" required>
                        <option value="">เลือกศาสนา</option>
                        <?php foreach($religion_options as $op): ?>
                            <option value="<?=$op?>" <?=($stuConf[0]->stu_religion ?? $stu[0]->recruit_religion) == $op ? 'selected' : ''?>><?=$op?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: ข้อมูลครอบครัว -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-group me-2"></i>ข้อมูลครอบครัว</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="stu_numberSibling" class="form-label">จำนวนพี่น้องทั้งหมด (รวมนักเรียน)</label>
                    <input type="number" class="form-control" id="stu_numberSibling" name="stu_numberSibling"
                        placeholder="" required value="<?=$stuConf[0]->stu_numberSibling ?? ''?>">
                </div>
                <div class="col-md-4">
                    <label for="stu_firstChild" class="form-label">นักเรียนเป็นลูกคนที่</label>
                    <input type="number" class="form-control" id="stu_firstChild" name="stu_firstChild"
                        placeholder="" required value="<?=$stuConf[0]->stu_firstChild ?? ''?>">
                </div>
                <div class="col-md-4">
                    <label for="stu_numberSiblingSkj" class="form-label">พี่น้องที่เรียน รร.สกจ. (รวมนักเรียน)</label>
                    <input type="number" class="form-control" id="stu_numberSiblingSkj"
                        name="stu_numberSiblingSkj" placeholder="" required
                        value="<?=$stuConf[0]->stu_numberSiblingSkj ?? ''?>">
                </div>
                
                <div class="col-12">
                    <label class="form-label fw-bold text-muted mb-2">สถานภาพบิดา-มารดา</label>
                    <div class="row row-cols-auto g-2">
                        <?php $parstu = array("อยู่ด้วยกัน","แยกกันอยู่","หย่าร้าง","บิดาถึงแก่กรรม","มารดาถึงแก่กรรม","บิดาและมารดาถึงแก่กรรม","บิดาหรือมารดาแต่งงานใหม่"); 
                        foreach ($parstu as $key => $v_parstu) : ?>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stu_parenalStatus" id="stu_parenalStatus<?=$key?>"
                                    value="<?=$v_parstu;?>" <?php if(isset($stuConf[0]->stu_parenalStatus) && trim($stuConf[0]->stu_parenalStatus) == $v_parstu) echo "checked"; ?> required>
                                <label class="form-check-label" for="stu_parenalStatus<?=$key?>"><?=$v_parstu;?></label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold text-muted mb-2">สภาพความเป็นอยู่ปัจจุบัน</label>
                    <div class="row row-cols-auto g-2 align-items-center">
                        <?php $pars = array("อยู่กับบิดาและมารดา","อยู่กับบิดาหรือมารดา","บุคคลอื่น"); 
                        foreach ($pars as $key => $v_pars) : ?>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stu_presentLife" id="stu_presentLife<?=$key?>"
                                    value="<?=$v_pars;?>" <?php if(isset($stuConf[0]->stu_presentLife) && trim($stuConf[0]->stu_presentLife) == $v_pars) echo "checked"; ?> required>
                                <label class="form-check-label" for="stu_presentLife<?=$key?>"><?=$v_pars;?></label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="col">
                            <input type="text" id="stu_personOther" name="stu_personOther" class="form-control form-control-sm"
                            value="<?=($stuConf[0]->stu_personOther ?? '')?>" 
                            style="<?=trim($stuConf[0]->stu_presentLife ?? '') == "บุคคลอื่น" ? '' : 'display:none;'?>" 
                            placeholder="ระบุบุคคลอื่น">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: ที่อยู่ตามทะเบียนบ้าน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-home me-2"></i>ที่อยู่ตามทะเบียนบ้าน</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="stu_hCode" class="form-label">รหัสประจำบ้าน</label>
                    <input type="text" class="form-control" placeholder="" id="stu_hCode" name="stu_hCode"
                        required value="<?=$stuConf[0]->stu_hCode ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hNumber" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="stu_hNumber" name="stu_hNumber"
                        required value="<?=$stuConf[0]->stu_hNumber ?? $stu[0]->recruit_homeNumber?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hMoo" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="stu_hMoo" name="stu_hMoo"
                        required value="<?=$stuConf[0]->stu_hMoo ?? $stu[0]->recruit_homeGroup?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hRoad" class="form-label">ถนน</label>
                    <input type="text" class="form-control" id="stu_hRoad" name="stu_hRoad" placeholder=""
                        required value="<?=$stuConf[0]->stu_hRoad ?? $stu[0]->recruit_homeRoad?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hTambon" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="stu_hTambon"
                        name="stu_hTambon" required
                        value="<?=$stuConf[0]->stu_hTambon ?? $stu[0]->recruit_homeSubdistrict?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hDistrict" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="stu_hDistrict"
                        name="stu_hDistrict" required
                        value="<?=$stuConf[0]->stu_hDistrict ?? $stu[0]->recruit_homedistrict?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hProvince" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="stu_hProvince"
                        name="stu_hProvince" required
                        value="<?=$stuConf[0]->stu_hProvince ?? $stu[0]->recruit_homeProvince?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_hPostCode" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="" id="stu_hPostCode"
                        name="stu_hPostCode" required
                        value="<?=$stuConf[0]->stu_hPostCode ?? $stu[0]->recruit_homePostcode?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: ที่อยู่ปัจจุบัน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-building-house me-2"></i>ที่อยู่ปัจจุบัน</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="clickLike" name="clickLike">
                <label class="form-check-label text-muted" for="clickLike">เหมือนทะเบียนบ้าน</label>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="stu_cNumber" class="form-label">บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="" id="stu_cNumber" name="stu_cNumber"
                        required value="<?=$stuConf[0]->stu_cNumber ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_cMoo" class="form-label">หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="" id="stu_cMoo" name="stu_cMoo"
                        required value="<?=$stuConf[0]->stu_cMoo ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_cRoad" class="form-label">ถนน</label>
                    <input type="text" class="form-control" id="stu_cRoad" name="stu_cRoad" placeholder=""
                        required value="<?=$stuConf[0]->stu_cRoad ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_cTumbao" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="stu_cTumbao"
                        name="stu_cTumbao" required value="<?=$stuConf[0]->stu_cTumbao ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_cDistrict" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="stu_cDistrict"
                        name="stu_cDistrict" required value="<?=$stuConf[0]->stu_cDistrict ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_cProvince" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="stu_cProvince"
                        name="stu_cProvince" required value="<?=$stuConf[0]->stu_cProvince ?? ''?>">
                </div>
                <div class="col-md-3">
                    <label for="stu_cPostcode" class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" id="stu_cPostcode" name="stu_cPostcode"
                        placeholder="" required value="<?=$stuConf[0]->stu_cPostcode ?? ''?>">
                </div>
                
                <div class="col-12 mt-3">
                    <label class="form-label fw-bold text-muted mb-2">ลักษณะที่อยู่อาศัย</label>
                        <div class="row row-cols-auto g-2">
                            <?php $addr = array("บ้านตนเอง","เช่าอยู่","อาศัยผู้อื่นอยู่","บ้านพักราชการ","วัด","หอพัก"); 
                            foreach ($addr as $key => $v_addr) : 
                                $isChecked = ($stuConf[0]->stu_natureRoom ?? '') == $v_addr ? "checked" : "";
                            ?>
                            <div class="col">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="stu_natureRoom" id="natureRoom<?=$key?>" value="<?=$v_addr;?>" <?php if(isset($stuConf[0]->stu_natureRoom) && $stuConf[0]->stu_natureRoom == $v_addr) echo "checked"; ?> required>
                                    <label class="form-check-label" for="natureRoom<?=$key?>"><?=$v_addr;?></label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Section 7: การเดินทาง & การศึกษาเดิม -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-bus me-2"></i>การเดินทาง & การศึกษาเดิม</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="stu_farSchool" class="form-label">ระยะทางจากบ้านถึง รร. (กม.)</label>
                    <input type="number" class="form-control" id="stu_farSchool" name="stu_farSchool"
                        placeholder="" required value="<?=$stuConf[0]->stu_farSchool ?? ''?>">
                </div>
                <div class="col-md-8">
                    <label for="stu_travel" class="form-label">เดินทางโดยพาหนะ</label>
                    <input type="text" class="form-control" id="stu_travel" name="stu_travel" placeholder=""
                        required value="<?=$stuConf[0]->stu_travel ?? ''?>">
                </div>
                
                <div class="col-md-3">
                    <label for="stu_gradLevel" class="form-label">จบการศึกษาชั้น</label>
                    <select class="form-select" id="stu_gradLevel" name="stu_gradLevel" required>
                        <option value="">เลือกระดับชั้น</option>
                        <?php $gradLevel = array('ป.6','ม.1','ม.2','ม.3','ม.4','ม.5'); 
                        foreach ($gradLevel as $key => $v_gradLevel): ?>
                        <option <?=($stuConf[0]->stu_gradLevel ?? '') == $v_gradLevel ?"selected":""?>
                            value="<?=$v_gradLevel;?>"><?=$v_gradLevel;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-9">
                    <label for="stu_schoolfrom" class="form-label">จากโรงเรียน</label>
                    <select class="form-select" id="stu_schoolfrom" name="stu_schoolfrom" required>
                        <?php if(!empty($stuConf[0]->stu_schoolfrom) || !empty($stu[0]->recruit_oldSchool)): ?>
                            <option value="<?=$stuConf[0]->stu_schoolfrom ?? $stu[0]->recruit_oldSchool?>" selected>
                                <?=$stuConf[0]->stu_schoolfrom ?? $stu[0]->recruit_oldSchool?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="stu_schoolTambao" class="form-label">ตำบล</label>
                    <input type="text" class="form-control" placeholder="" id="stu_schoolTambao"
                        name="stu_schoolTambao" required value="<?php echo $stuConf[0]->stu_schoolTambao ?? '';?>">
                </div>
                <div class="col-md-4">
                    <label for="stu_schoolDistrict" class="form-label">อำเภอ</label>
                    <input type="text" class="form-control" placeholder="" id="stu_schoolDistrict"
                        name="stu_schoolDistrict" required
                        value="<?php echo $stuConf[0]->stu_schoolDistrict ?? $stu[0]->recruit_district ;?>">
                </div>
                <div class="col-md-4">
                        <label for="stu_schoolProvince" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control" placeholder="" id="stu_schoolProvince"
                        name="stu_schoolProvince" required
                        value="<?php echo $stuConf[0]->stu_schoolProvince ?? $stu[0]->recruit_province;?>">
                </div>
                
                
            </div>
        </div>
    </div>

    <!-- Section 8: ประวัติในโรงเรียนสวนกุหลาบ -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-history me-2"></i>ประวัติในโรงเรียนสวนกุหลาบฯ (จิรประวัติ)</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <label class="form-label mb-2">เคยเป็นนักเรียนโรงเรียนสวนกุหลาบวิทยาลัย(จิรประวัติ) นครสวรรค์ หรือไม่?</label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="stu_usedStudent" id="stu_usedStudent1" value="ไม่เคย" <?php if(isset($stuConf[0]->stu_usedStudent) && $stuConf[0]->stu_usedStudent == "ไม่เคย") echo "checked"; ?> required>
                                <label class="form-check-label" for="stu_usedStudent1">ไม่เคย</label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="stu_usedStudent" id="stu_usedStudent2" value="เคย" <?php if(isset($stuConf[0]->stu_usedStudent) && $stuConf[0]->stu_usedStudent == "เคย") echo "checked"; ?> required>
                                <label class="form-check-label" for="stu_usedStudent2">เคย</label>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center ms-2">
                            <label class="form-label me-2 mb-0" for="stu_inputLevel">ระบุระดับชั้น:</label>
                            <select class="form-select w-auto" id="stu_inputLevel" name="stu_inputLevel">
                                <option value="">เลือก</option>
                                <?php for ($i=1; $i <= 6 ; $i++) : ?>
                                <option <?=($stuConf[0]->stu_inputLevel ?? '')==$i?"selected":"" ?> value="<?=$i;?>">ม.<?=$i;?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Section 9: ผู้ติดต่อฉุกเฉิน -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-phone-incoming me-2"></i>ผู้ติดต่อฉุกเฉิน</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="stu_phoneUrgent" class="form-label">เบอร์โทรศัพท์ติดต่อฉุกเฉิน</label>
                    <input type="text" class="form-control" id="stu_phoneUrgent" name="stu_phoneUrgent"
                        value="<?php echo $stuConf[0]->stu_phoneUrgent ?? '';?>" placeholder="" required
                        data-inputmask="'mask': '999-999-9999'">
                </div>
                <div class="col-md-6">
                    <label for="stu_phoneFriend" class="form-label">เบอร์โทรศัพท์เพื่อนบ้านใกล้เคียง</label>
                    <input type="text" class="form-control" id="stu_phoneFriend" name="stu_phoneFriend"
                        value="<?php echo $stuConf[0]->stu_phoneFriend ?? '';?>" placeholder="" required
                        data-inputmask="'mask': '999-999-9999'">
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm ReLoading">
            <i class="bx bx-save me-2"></i>บันทึกข้อมูลนักเรียน
        </button>
    </div>

</form>

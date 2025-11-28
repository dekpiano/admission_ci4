<form id="FormConfirmFather" method="post" action="#" class="check-needs-validation" novalidate>
<input type="hidden" class="form-control form-control" id="par_stuID" name="par_stuID" placeholder="ระบุอายุ" value="<?php echo $stu[0]->recruit_idCard; ?>" readonly   required11>
<input type="hidden" class="form-control form-control" id="par_relationKey" name="par_relationKey" placeholder="ระบุอายุ" value="พ่อ" readonly   required11>


<div class="form-group row">
        <label for="par_ago" class="col-sm-3 col-form-label col-form-label">ความสัมพันธ์เป็น</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_relation" name="par_relation" placeholder="ระบุอายุ" value="บิดา" readonly
                required11>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ชื่อ-นามสกุลบิดา</label>
        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="คำนำหน้า" id="par_prefix" name="par_prefix"
                        required>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="ชื่อจริง" id="par_firstName"
                        name="par_firstName" required>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="นามสกุลจริง" id="par_lastName"
                        name="par_lastName" required>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_ago" class="col-sm-3 col-form-label col-form-label">อายุ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_ago" name="par_ago" placeholder="ระบุอายุ"
                required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_IdNumber" class="col-sm-3 col-form-label col-form-label">รหัสประจำตัวประชาชน
            13 หลัก</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_IdNumber" placeholder="ระบุเลข 13 หลัก"
                name="par_IdNumber" data-inputmask="'mask': '9-9999-99999-99-9'" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_race" class="col-sm-3 col-form-label col-form-label">เชื้อชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_race" name="par_race"
                placeholder="ระบุเชื้อชาติ" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_national" class="col-sm-3 col-form-label col-form-label">สัญชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_national" name="par_national"
                placeholder="ระบุสัญชาติ" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="par_religion" class="col-sm-3 col-form-label col-form-label">ศาสนา</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_religion" name="par_religion"
                placeholder="ระบุศาสนา" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_career" class="col-sm-3 col-form-label col-form-label">อาชีพ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_career" name="par_career"
                placeholder="ระบุลักษณะอาชีพ" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_education" class="col-sm-3 col-form-label col-form-label">วุฒิการศึกษา</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_education" name="par_education"
                placeholder="ระบุวุฒิการศึกษา" required11>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_salary" class="col-sm-3 col-form-label col-form-label">เงินเดือน/รายได้</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_salary" name="par_salary"
                placeholder="ระบุเงินเดือน/รายได้" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_positionJob" class="col-sm-3 col-form-label col-form-label">ตำแหน่งหน้าที่การงาน</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_positionJob" name="par_positionJob"
                placeholder="ระบุตำแหน่งหน้าที่การงาน" required11>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_phone" class="col-sm-3 col-form-label col-form-label">หมายเลขโทรศัพท์มือถือ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_phone" name="par_phone"
                placeholder="ระบุโทรศัพท์มือถือ" data-inputmask="'mask': '999-999-9999'" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ถึงแก่กรรมเมื่อวันที่ <div class="text-danger">ไม่มีไม่ต้องกรอก</div> </label>
        <div class="col-sm-3">
            <input type="date" class="form-control form-control" id="par_decease" name="par_decease"
                placeholder="ยังไม่ถึงแก่กรรม ไม่ต้องระบุ" required11>
        </div>
    </div>

    <hr>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ที่อยู่ตามทะเบียนบ้าน</label>
        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-3 mb-2">
                <label>บ้านเลขที่</label>  
                    <input type="text" class="form-control" placeholder="บ้านเลขที่" id="par_hNumber" name="par_hNumber"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>หมู่ที่</label>  
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_hMoo" name="par_hMoo"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>ตำบล</label>  
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_hTambon" name="par_hTambon"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>อำเภอ</label>  
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_hDistrict" name="par_hDistrict"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>จังหวัด</label>  
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_hProvince"
                        name="par_hProvince" required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>รหัสไปรษณีย์</label>  
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_hPostcode"
                        name="par_hPostcode" required>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ทีอยู่ปัจจุบัน ( <div
                class="custom-control custom-checkbox custom-control-inline">
                <input class="custom-control-input" type="checkbox" id="checkPer" value="option1">
                <label class="custom-control-label" for="checkPer">ตามทะเบียนบ้าน</label>
            </div>)</label>

        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-3 mb-2">
                <label>บ้านเลขที่</label>  
                    <input type="text" class="form-control" placeholder="บ้านเลขที่" id="par_cNumber" name="par_cNumber"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>หมู่ที่</label>  
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_cMoo" name="par_cMoo"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>ตำบล</label>  
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_cTambon" name="par_cTambon"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>อำเภอ</label>  
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_cDistrict" name="par_cDistrict"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>จังหวัด</label>  
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_cProvince"
                        name="par_cProvince" required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>รหัสไปรษณีย์</label>  
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_cPostcode"
                        name="par_cPostcode" required>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ลักษณะที่พัก</label>
        <div class="col-sm-9">

            <?php $Name = array('บ้านตนเอง','เช่าบ้าน','อาศัยผู้อื่นอยู่','บ้านพักสวัสดิการ','อื่นๆ');
        foreach ($Name as $key => $v_Name) :
        ?>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input par_rest" type="radio" name="par_rest" id="par_rest<?=$key;?>" value="<?=$v_Name;?>" required>
                <label class="custom-control-label" for="par_rest<?=$key;?>"><?=$v_Name;?></label>
            </div>           
                <input type="text" style="display:none;"  class="form-control" placeholder="ระบุที่พักอื่น ๆ" id="par_restOrthor<?=$key;?>"
                    name="par_restOrthor" >
         
            <?php endforeach; ?>
            
        </div>
    </div>

    <hr>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">กรณีรับราชการ
            <br><small class="text-danger">ถ้าไม่ได้รับราชการไม่ต้องเลือก</small>
        </label>
        <div class="col-sm-9">
            <?php $Name = array('กระทรวง','กรม','กอง','ฝ่าย/แผนก');
        foreach ($Name as $key => $v_Name) :
        ?>
            <div class="custom-control custom-radio ">
                <input class="custom-control-input par_service" type="radio" name="par_service"
                    id="par_service<?=$key?>" value="<?=$v_Name?>">
                <label class="custom-control-label" for="par_service<?=$key?>"><?=$v_Name?></label>
            </div>
            <input type="text" style="display:none;" class="form-control" id="par_serviceName<?=$key?>"
                name="par_serviceName[]" placeholder="ระบุ" required11>

            <?php endforeach; ?>
            <div class="custom-control custom-radio ">
                <input class="custom-control-input par_service" type="radio" name="par_service"
                    id="par_service99" value="ไม่ได้รับราชการ" checked>
                <label class="custom-control-label" for="par_service99">ไม่ได้รับราชการ</label>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg"
            class="col-sm-3 col-form-label col-form-label">สิทธ์ในการเบิกค่าเล่าเรียนบุตร</label>
        <div class="col-sm-9">
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claim" id="par_claim1"
                    value="เบิกได้" required>
                <label class="custom-control-label" for="par_claim1">เบิกได้</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claim" id="par_claim2"
                    value="เบิกไม่ได้" required>
                <label class="custom-control-label" for="par_claim2">เบิกไม่ได้
                </label>
            </div>

        </div>
    </div>
    <hr>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
    </div>

</form>
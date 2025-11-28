<form id="FormConfirmOther" method="post" action="#" class="check-needs-validation FormConfirmOther" novalidate>
<input type="hidden" class="form-control form-control" id="par_stuIDO" name="par_stuIDO" placeholder="ระบุอายุ" value="<?php echo $stu[0]->recruit_idCard; ?>" readonly   required11>
<input type="hidden" class="form-control form-control" id="par_relationKeyO" name="par_relationKeyO" placeholder="ระบุอายุ" value="ผู้ปกครอง" readonly   required11>
<div class="form-group row">
        <label for="par_ago" class="col-sm-3 col-form-label col-form-label">ความสัมพันธ์เป็น</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_relationO" name="par_relationO" placeholder="บุคคลอื่น เช่น ตา ป้า ลุง" value="" 
                required>
        </div>
    </div>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ชื่อ-นามสกุลผู้ปกครอง</label>
        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="คำนำหน้า" id="par_prefixO" name="par_prefixO"
                        required>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="ชื่อจริง" id="par_firstNameO"
                        name="par_firstNameO" required>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="นามสกุลจริง" id="par_lastNameO"
                        name="par_lastNameO" required>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_agoO" class="col-sm-3 col-form-label col-form-label">อายุ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_agoO" name="par_agoO" placeholder="ระบุอายุ"
                required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_IdNumberO" class="col-sm-3 col-form-label col-form-label">รหัสประจำตัวประชาชน
            13 หลัก</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_IdNumberO" placeholder="ระบุเลข 13 หลัก"
                name="par_IdNumberO" data-inputmask="'mask': '9-9999-99999-99-9'" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_raceO" class="col-sm-3 col-form-label col-form-label">เชื้อชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_raceO" name="par_raceO"
                placeholder="ระบุเชื้อชาติ" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_nationalO" class="col-sm-3 col-form-label col-form-label">สัญชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_nationalO" name="par_nationalO"
                placeholder="ระบุสัญชาติ" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="par_religionO" class="col-sm-3 col-form-label col-form-label">ศาสนา</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_religionO" name="par_religionO"
                placeholder="ระบุศาสนา" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_careerO" class="col-sm-3 col-form-label col-form-label">อาชีพ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_careerO" name="par_careerO"
                placeholder="ระบุลักษณะอาชีพ" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_educationO" class="col-sm-3 col-form-label col-form-label">วุฒิการศึกษา</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_educationO" name="par_educationO"
                placeholder="ระบุวุฒิการศึกษา" required11>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_salaryO" class="col-sm-3 col-form-label col-form-label">เงินเดือน/รายได้</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_salaryO" name="par_salaryO"
                placeholder="ระบุเงินเดือน/รายได้" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_positionJobO" class="col-sm-3 col-form-label col-form-label">ตำแหน่งหน้าที่การงาน</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_positionJobO" name="par_positionJobO"
                placeholder="ระบุตำแหน่งหน้าที่การงาน" required11>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_phoneO" class="col-sm-3 col-form-label col-form-label">หมายเลขโทรศัพท์มือถือ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_phoneO" name="par_phoneO"
                placeholder="ระบุโทรศัพท์มือถือ" data-inputmask="'mask': '999-999-9999'" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_deceaseO" class="col-sm-3 col-form-label col-form-label">ถึงแก่กรรมเมื่อวันที่ <div class="text-danger">ไม่มีไม่ต้องกรอก</div> </label>
        <div class="col-sm-3">
            <input type="date" class="form-control form-control" id="par_deceaseO" name="par_deceaseO"
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
                    <input type="text" class="form-control" placeholder="บ้านเลขที่" id="par_hNumberO" name="par_hNumberO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>หมู่ที่</label>  
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_hMooO" name="par_hMooO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>ตำบล</label>  
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_hTambonO" name="par_hTambonO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>อำเภอ</label>  
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_hDistrictO" name="par_hDistrictO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>จังหวัด</label>  
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_hProvinceO"
                        name="par_hProvinceO" required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>รหัสไปรษณีย์</label>  
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_hPostcodeO"
                        name="par_hPostcodeO" required>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ทีอยู่ปัจจุบัน ( <div
                class="custom-control custom-checkbox custom-control-inline">
                <input class="custom-control-input" type="checkbox" id="checkPerO" value="option1">
                <label class="custom-control-label" for="checkPerO">ตามทะเบียนบ้าน</label>
            </div>)</label>

        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-3 mb-2">
                <label>บ้านเลขที่</label>  
                    <input type="text" class="form-control" placeholder="บ้านเลขที่" id="par_cNumberO" name="par_cNumberO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>หมู่ที่</label>  
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_cMooO" name="par_cMooO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>ตำบล</label>  
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_cTambonO" name="par_cTambonO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>อำเภอ</label>  
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_cDistrictO" name="par_cDistrictO"
                        required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>จังหวัด</label>  
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_cProvinceO"
                        name="par_cProvinceO" required>
                </div>
                <div class="col-12 col-md-3 mb-2">
                <label>รหัสไปรษณีย์</label>  
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_cPostcodeO"
                        name="par_cPostcodeO" required>
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
                <input class="custom-control-input par_restO" type="radio" name="par_restO" id="par_restO<?=$key;?>" value="<?=$v_Name;?>" required>
                <label class="custom-control-label" for="par_restO<?=$key;?>"><?=$v_Name;?></label>
                <input type="text" style="display:none;"  class="form-control" placeholder="ระบุที่พักอื่น ๆ" id="par_restOrthorO<?=$key;?>"
                    name="par_restOrthorO" >
            </div>           
              
         
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
                <input class="custom-control-input par_serviceO" type="radio" name="par_serviceO"
                    id="par_serviceO<?=$key?>" value="<?=$v_Name?>">
                <label class="custom-control-label" for="par_serviceO<?=$key?>"><?=$v_Name?></label>
            </div>
            <input type="text" style="display:none;" class="form-control" id="par_serviceNameO<?=$key?>"
                name="par_serviceNameO[]" placeholder="ระบุ" required11>

            <?php endforeach; ?>
            <div class="custom-control custom-radio ">
                <input class="custom-control-input par_serviceO" type="radio" name="par_serviceO"
                    id="par_serviceO99" value="ไม่ได้รับราชการ" checked>
                <label class="custom-control-label" for="par_serviceO99">ไม่ได้รับราชการ</label>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg"
            class="col-sm-3 col-form-label col-form-label">สิทธ์ในการเบิกค่าเล่าเรียนบุตร</label>
        <div class="col-sm-9">
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claimO" id="par_claimO0"
                    value="เบิกได้" required>
                <label class="custom-control-label" for="par_claimO0">เบิกได้</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claimO" id="par_claimO1"
                    value="เบิกไม่ได้" required>
                <label class="custom-control-label" for="par_claimO1">เบิกไม่ได้
                </label>
            </div>

        </div>
    </div>
    <hr>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
    </div>

</form>
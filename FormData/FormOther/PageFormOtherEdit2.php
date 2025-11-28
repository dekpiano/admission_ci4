<?php  
        if($OtherCkeck == 1){
            $Action = 'FormConfirmOtherUpdate';
        }else{
            $Action = 'FormConfirmOther';
        }
    ?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation FormConfirmOther" novalidate>
    <input type="hidden" class="form-control form-control" id="par_stuIDO" name="par_stuIDO" placeholder="ระบุอายุ"
        value="<?php echo $stu[0]->recruit_idCard; ?>" readonly required11>
    <input type="hidden" class="form-control form-control" id="par_relationKeyO" name="par_relationKeyO"
        placeholder="ระบุอายุ" value="ผู้ปกครอง" readonly required11>
    <input type="hidden" class="form-control form-control" id="par_idO" name="par_idO" placeholder="ระบุอายุ"
        value="<?=$OtherConf[0]->par_id ?? ''?>" readonly required11>
    <div class="form-group row">
        <label for="par_ago" class="col-sm-3 col-form-label col-form-label">ความสัมพันธ์เป็น</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_relationO" name="par_relationO"
                placeholder="ระบุความสัมพันธ์" value="<?=$OtherConf[0]->par_relation ?? ''?>" required >
                <small class="text-muted">ตัวอย่าง เช่น ตา ยา ลุง ป้า พี่สาว เป็นต้น</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ชื่อ-นามสกุล</label>
        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="คำนำหน้า" id="par_prefixO" name="par_prefixO"
                        value="<?=$OtherConf[0]->par_prefix ?? ''?>" required>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="ชื่อจริง" id="par_firstNameO"
                        name="par_firstNameO" required value="<?=$OtherConf[0]->par_firstName ?? ''?>">
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="นามสกุลจริง" id="par_lastNameO"
                        name="par_lastNameO" required value="<?=$OtherConf[0]->par_lastName ?? ''?>">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_agoO" class="col-sm-3 col-form-label col-form-label">อายุ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_agoO" name="par_agoO" placeholder="ระบุอายุ"
                value="<?=$OtherConf[0]->par_ago ?? ''?>" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_IdNumberO" class="col-sm-3 col-form-label col-form-label">รหัสประจำตัวประชาชน
            13 หลัก</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_IdNumberO" placeholder="ระบุเลข 13 หลัก"
                name="par_IdNumberO" data-inputmask="'mask': '9-9999-99999-99-9'" required
                value="<?=$OtherConf[0]->par_IdNumber ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_raceO" class="col-sm-3 col-form-label col-form-label">เชื้อชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_raceO" name="par_raceO"
                placeholder="ระบุเชื้อชาติ" required value="<?=$OtherConf[0]->par_race ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_nationalO" class="col-sm-3 col-form-label col-form-label">สัญชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_nationalO" name="par_nationalO"
                placeholder="ระบุสัญชาติ" required value="<?=$OtherConf[0]->par_national ?? ''?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="par_religionO" class="col-sm-3 col-form-label col-form-label">ศาสนา</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_religionO" name="par_religionO"
                placeholder="ระบุศาสนา" required value="<?=$OtherConf[0]->par_religion ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_careerO" class="col-sm-3 col-form-label col-form-label">อาชีพ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_careerO" name="par_careerO"
                placeholder="ระบุลักษณะอาชีพ" required value="<?=$OtherConf[0]->par_career ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_educationO" class="col-sm-3 col-form-label col-form-label">วุฒิการศึกษา</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_educationO" name="par_educationO"
                placeholder="ระบุวุฒิการศึกษา" required value="<?=$OtherConf[0]->par_education ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_salaryO" class="col-sm-3 col-form-label col-form-label">เงินเดือน/รายได้</label>
        <div class="col-sm-3">
            <input type="number" class="form-control form-control" id="par_salaryO" name="par_salaryO"
                placeholder="ระบุเงินเดือน/รายได้" required value="<?=$OtherConf[0]->par_salary ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_positionJobO" class="col-sm-3 col-form-label col-form-label">ตำแหน่งหน้าที่การงาน</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_positionJobO" name="par_positionJobO"
                placeholder="ระบุตำแหน่งหน้าที่การงาน" required11 value="<?=$OtherConf[0]->par_positionJob ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_phoneO" class="col-sm-3 col-form-label col-form-label">หมายเลขโทรศัพท์มือถือ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_phoneO" name="par_phoneO"
                placeholder="ระบุโทรศัพท์มือถือ" data-inputmask="'mask': '999-999-9999'" required
                value="<?=$OtherConf[0]->par_phone ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ถึงแก่กรรมเมื่อวันที่ <div
                class="text-danger">ไม่มีไม่ต้องกรอก</div> </label>
        <div class="col-sm-3">
            <input type="date" class="form-control form-control" id="par_deceaseO" name="par_deceaseO"
                placeholder="ยังไม่ถึงแก่กรรม ไม่ต้องระบุ" required11 value="<?=$OtherConf[0]->par_decease ?? ''?>">
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
                        required value="<?=$OtherConf[0]->par_hNumber ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_hMooO" name="par_hMooO"
                        required value="<?=$OtherConf[0]->par_hMoo ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>ตำบล</label>
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_hTambonO" name="par_hTambonO"
                        required value="<?=$OtherConf[0]->par_hTambon ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>อำเภอ</label>
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_hDistrictO" name="par_hDistrictO"
                        required value="<?=$OtherConf[0]->par_hDistrict ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>จังหวัด</label>
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_hProvinceO"
                        name="par_hProvinceO" required value="<?=$OtherConf[0]->par_hProvince ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_hPostcodeO"
                        name="par_hPostcodeO" required value="<?=$OtherConf[0]->par_hPostcode ?? ''?>">
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
                        required value="<?=$OtherConf[0]->par_cNumber ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_cMooO" name="par_cMooO"
                        required value="<?=$OtherConf[0]->par_cMoo ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>ตำบล</label>
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_cTambonO" name="par_cTambonO"
                        required value="<?=$OtherConf[0]->par_cTambon ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>อำเภอ</label>
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_cDistrictO" name="par_cDistrictO"
                        required value="<?=$OtherConf[0]->par_cDistrict ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>จังหวัด</label>
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_cProvinceO"
                        name="par_cProvinceO" required value="<?=$OtherConf[0]->par_cProvince ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_cPostcodeO"
                        name="par_cPostcodeO" required value="<?=$OtherConf[0]->par_cPostcode ?? ''?>">
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ลักษณะที่พัก</label>
        <div class="col-sm-9">

            <?php $Name = array('บ้านตนเอง','เช่าบ้าน','อาศัยผู้อื่นอยู่','บ้านพักสวัสดิการ');
        foreach ($Name as $key => $v_Name) :
        ?>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input par_restO" type="radio" name="par_restO" id="par_restO<?=$key;?>"
                    value="<?=$v_Name;?>" <?=($OtherConf[0]->par_rest ?? 'บ้านตนเอง') ==$v_Name?"checked":""?>>
                <label class="custom-control-label" for="par_restO<?=$key;?>"><?=$v_Name;?></label>            
            </div>
            <?php endforeach; ?>
            <!-- อยู่ที่พักอื่น ๆ -->
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input par_restO" type="radio" name="par_restO" id="par_restO99"
                    value="อื่นๆ" <?=($OtherConf[0]->par_rest ?? 'อื่นๆ') =="อื่นๆ"?"checked":""?>>
                <label class="custom-control-label align-self-center mr-2" for="par_restO99"> อื่นๆ </label>                         
                <input type="text" class="form-control par_restOrthorO" placeholder="ระบุที่พักอื่น ๆ" id="par_restOrthorO" name="par_restOrthorO" required11 value="<?=$OtherConf[0]->par_restOrthor ?? '' ?>">     
            </div>
        </div>
    </div>

    <hr>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">กรณีรับราชการ
        </label>
        <div class="col-sm-9">
            <?php $Name = array('กระทรวง','กรม','กอง','ฝ่าย/แผนก');
        foreach ($Name as $key => $v_Name) :
        ?>
            <div class="custom-control custom-radio ">
                <input class="custom-control-input par_serviceO" type="radio" name="par_serviceO"
                    id="par_serviceO<?=$key?>" value="<?=$v_Name?>"
                    <?=($OtherConf[0]->par_service ?? '') ==$v_Name?"checked":""?>>
                <label class="custom-control-label" for="par_serviceO<?=$key?>"><?=$v_Name?></label>
            </div>
            <?php  if(@$OtherConf[0]->par_service==$v_Name) : ?>
            <input type="text" class="form-control" id="par_serviceNameO<?=$key?>" name="par_serviceNameO[]"
                placeholder="ระบุ" required11 value="<?=$OtherConf[0]->par_serviceName ?? '';?>">
            <?php else :?>
            <input type="text" style="display:none;" class="form-control" id="par_serviceNameO<?=$key?>"
                name="par_serviceNameO[]" placeholder="ระบุ" required11>
            <?php endif; ?>

            <?php endforeach; ?>

            <div class="custom-control custom-radio ">
                <input class="custom-control-input par_serviceMO" type="radio" name="par_serviceO" id="par_serviceO99"
                    value="ไม่ได้รับราชการ" <?=($OtherConf[0]->par_service ?? 'ไม่ได้รับราชการ') =="ไม่ได้รับราชการ"?"checked":""?>>
                <label class="custom-control-label" for="par_serviceO99">ไม่ได้รับราชการ</label>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg"
            class="col-sm-3 col-form-label col-form-label">สิทธ์ในการเบิกค่าเล่าเรียนบุตร</label>
        <div class="col-sm-9">
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claimO" id="par_claimO1" value="เบิกได้"
                    <?=($OtherConf[0]->par_claim ?? '') =="เบิกได้"?"checked":""?>>
                <label class="custom-control-label" for="par_claimO1">เบิกได้</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claimO" id="par_claimO2" value="เบิกไม่ได้"
                    <?=($OtherConf[0]->par_claim ?? 'เบิกไม่ได้') =="เบิกไม่ได้"?"checked":""?>>
                <label class="custom-control-label" for="par_claimO2">เบิกไม่ได้
                </label>
            </div>

        </div>
    </div>
    <hr>
    <div class="text-center">
        <button type="submit" class="btn btn-warning">บันทึกข้อมูล</button>
    </div>

</form>
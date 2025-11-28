<?php  
        if(($MatherCkeck) == 1){
            $Action = 'FormConfirmMatherUpdate';
        }else{
            $Action = 'FormConfirmMather';
        }
    ?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation" novalidate>
    <input type="hidden" class="form-control form-control" id="par_stuIDM" name="par_stuIDM" placeholder="ระบุอายุ"
        value="<?php echo $stu[0]->recruit_idCard; ?>" readonly required11>
    <input type="hidden" class="form-control form-control" id="par_relationKeyM" name="par_relationKeyM"
        placeholder="ระบุอายุ" value="แม่" readonly required11>
    <input type="hidden" class="form-control form-control" id="par_idM" name="par_idM" placeholder="ระบุอายุ"
        value="<?=$MatherConf[0]->par_id ?? ''?>" readonly required11>
    <div class="form-group row">
        <label for="par_ago" class="col-sm-3 col-form-label col-form-label">ความสัมพันธ์เป็น</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_relationM" name="par_relationM"
                placeholder="ระบุอายุ" value="<?=$MatherConf[0]->par_relation ?? 'มารดา'?>" required readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ชื่อ-นามสกุลมารดา</label>
        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="คำนำหน้า" id="par_prefixM" name="par_prefixM"
                        value="<?=$MatherConf[0]->par_prefix ?? ''?>" required11>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="ชื่อจริง" id="par_firstNameM"
                        name="par_firstNameM" required11 value="<?=$MatherConf[0]->par_firstName ?? ''?>">
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <input type="text" class="form-control" placeholder="นามสกุลจริง" id="par_lastNameM"
                        name="par_lastNameM" required11 value="<?=$MatherConf[0]->par_lastName ?? ''?>">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_agoM" class="col-sm-3 col-form-label col-form-label">อายุ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_agoM" name="par_agoM" placeholder="ระบุอายุ"
                value="<?=$MatherConf[0]->par_ago ?? ''?>" required11>
        </div>
    </div>
    <div class="form-group row">
        <label for="par_IdNumberM" class="col-sm-3 col-form-label col-form-label">รหัสประจำตัวประชาชน
            13 หลัก</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_IdNumberM" placeholder="ระบุเลข 13 หลัก"
                name="par_IdNumberM" data-inputmask="'mask': '9-9999-99999-99-9'" required11
                value="<?=$MatherConf[0]->par_IdNumber ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_raceM" class="col-sm-3 col-form-label col-form-label">เชื้อชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_raceM" name="par_raceM"
                placeholder="ระบุเชื้อชาติ" required11 value="<?=$MatherConf[0]->par_race ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_nationalM" class="col-sm-3 col-form-label col-form-label">สัญชาติ</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_nationalM" name="par_nationalM"
                placeholder="ระบุสัญชาติ" required11 value="<?=$MatherConf[0]->par_national ?? ''?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="par_religionM" class="col-sm-3 col-form-label col-form-label">ศาสนา</label>
        <div class="col-sm-2">
            <input type="text" class="form-control form-control" id="par_religionM" name="par_religionM"
                placeholder="ระบุศาสนา" required11 value="<?=$MatherConf[0]->par_religion ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_careerM" class="col-sm-3 col-form-label col-form-label">อาชีพ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_careerM" name="par_careerM"
                placeholder="ระบุลักษณะอาชีพ" required11 value="<?=$MatherConf[0]->par_career ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_educationM" class="col-sm-3 col-form-label col-form-label">วุฒิการศึกษา</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_educationM" name="par_educationM"
                placeholder="ระบุวุฒิการศึกษา" required11 value="<?=$MatherConf[0]->par_education ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_salaryM" class="col-sm-3 col-form-label col-form-label">เงินเดือน/รายได้</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_salaryM" name="par_salaryM"
                placeholder="ระบุเงินเดือน/รายได้" required11 value="<?=$MatherConf[0]->par_salary ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_positionJobM" class="col-sm-3 col-form-label col-form-label">ตำแหน่งหน้าที่การงาน</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_positionJobM" name="par_positionJobM"
                placeholder="ระบุตำแหน่งหน้าที่การงาน" required11 value="<?=$MatherConf[0]->par_positionJob ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="par_phoneM" class="col-sm-3 col-form-label col-form-label">หมายเลขโทรศัพท์มือถือ</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control" id="par_phoneM" name="par_phoneM"
                placeholder="ระบุโทรศัพท์มือถือ" data-inputmask="'mask': '999-999-9999'" required11
                value="<?=$MatherConf[0]->par_phone ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ถึงแก่กรรมเมื่อวันที่ <div
                class="text-danger">ไม่มีไม่ต้องกรอก</div> </label>
        <div class="col-sm-3">
            <input type="date" class="form-control form-control" id="par_deceaseM" name="par_deceaseM"
                placeholder="ยังไม่ถึงแก่กรรม ไม่ต้องระบุ" required11 value="<?=$MatherConf[0]->par_decease ?? ''?>">
        </div>
    </div>

    <hr>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ที่อยู่ตามทะเบียนบ้าน</label>
        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-3 mb-2">
                    <label>บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="บ้านเลขที่" id="par_hNumberM" name="par_hNumberM"
                        required11 value="<?=$MatherConf[0]->par_hNumber ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_hMooM" name="par_hMooM"
                        required11 value="<?=$MatherConf[0]->par_hMoo ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>ตำบล</label>
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_hTambonM" name="par_hTambonM"
                        required11 value="<?=$MatherConf[0]->par_hTambon ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>อำเภอ</label>
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_hDistrictM" name="par_hDistrictM"
                        required11 value="<?=$MatherConf[0]->par_hDistrict ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>จังหวัด</label>
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_hProvinceM"
                        name="par_hProvinceM" required11 value="<?=$MatherConf[0]->par_hProvince ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_hPostcodeM"
                        name="par_hPostcodeM" required11 value="<?=$MatherConf[0]->par_hPostcode ?? ''?>">
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ทีอยู่ปัจจุบัน ( <div
                class="custom-control custom-checkbox custom-control-inline">
                <input class="custom-control-input" type="checkbox" id="checkPerM" value="option1">
                <label class="custom-control-label" for="checkPerM">ตามทะเบียนบ้าน</label>
            </div>)</label>

        <div class="col-sm-9">
            <div class="form-row">
                <div class="col-12 col-md-3 mb-2">
                    <label>บ้านเลขที่</label>
                    <input type="text" class="form-control" placeholder="บ้านเลขที่" id="par_cNumberM" name="par_cNumberM"
                        required11 value="<?=$MatherConf[0]->par_cNumber ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>หมู่ที่</label>
                    <input type="text" class="form-control" placeholder="หมู่ที่" id="par_cMooM" name="par_cMooM"
                        required11 value="<?=$MatherConf[0]->par_cMoo ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>ตำบล</label>
                    <input type="text" class="form-control" placeholder="ตำบล" id="par_cTambonM" name="par_cTambonM"
                        required11 value="<?=$MatherConf[0]->par_cTambon ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>อำเภอ</label>
                    <input type="text" class="form-control" placeholder="อำเภอ" id="par_cDistrictM" name="par_cDistrictM"
                        required11 value="<?=$MatherConf[0]->par_cDistrict ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>จังหวัด</label>
                    <input type="text" class="form-control" placeholder="จังหวัด" id="par_cProvinceM"
                        name="par_cProvinceM" required11 value="<?=$MatherConf[0]->par_cProvince ?? ''?>">
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="par_cPostcodeM"
                        name="par_cPostcodeM" required11 value="<?=$MatherConf[0]->par_cPostcode ?? ''?>">
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
                <input class="custom-control-input par_restM" type="radio" name="par_restM" id="par_restM<?=$key;?>"
                    value="<?=$v_Name;?>" <?=($MatherConf[0]->par_rest ?? 'บ้านตนเอง') ==$v_Name?"checked":""?>>
                <label class="custom-control-label" for="par_restM<?=$key;?>"><?=$v_Name;?></label>
                <?php if(@$MatherConf[0]->par_rest == 'อื่นๆ'): ?>                    
                <input type="text" class="form-control par_restOrthorM" placeholder="ระบุที่พักอื่น ๆ" id="par_restOrthorM" name="par_restOrthorM" required11 value="<?=$MatherConf[0]->par_restOrthor ?? '' ?>">         
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <!-- อยู่ที่พักอื่น ๆ -->
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input par_restM" type="radio" name="par_restM" id="par_restM99"
                    value="อื่นๆ" <?=($MatherConf[0]->par_rest ?? 'อื่นๆ') =="อื่นๆ"?"checked":""?>>
                <label class="custom-control-label align-self-center mr-2" for="par_restM99"> อื่นๆ </label>                         
                <input type="text" class="form-control par_restOrthorM" placeholder="ระบุที่พักอื่น ๆ" id="par_restOrthorM" name="par_restOrthorM" required11 value="<?=$MatherConf[0]->par_restOrthor ?? '' ?>">     
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
                <input class="custom-control-input par_serviceM" type="radio" name="par_serviceM"
                    id="par_serviceM<?=$key?>" value="<?=$v_Name?>"
                    <?=($MatherConf[0]->par_service ?? '') ==$v_Name?"checked":""?>>
                <label class="custom-control-label" for="par_serviceM<?=$key?>"><?=$v_Name?></label>
            </div>
            <?php  if(@$MatherConf[0]->par_service==$v_Name) : ?>
            <input type="text" class="form-control" id="par_serviceNameM<?=$key?>" name="par_serviceNameM[]"
                placeholder="ระบุ" required11 value="<?=$MatherConf[0]->par_serviceName ?? '';?>">
            <?php else :?>
            <input type="text" style="display:none;" class="form-control" id="par_serviceNameM<?=$key?>"
                name="par_serviceNameM[]" placeholder="ระบุ" required11>
            <?php endif; ?>

            <?php endforeach; ?>

            <div class="custom-control custom-radio ">
                <input class="custom-control-input par_serviceM" type="radio" name="par_serviceM" id="par_serviceM99"
                    value="ไม่ได้รับราชการ" <?=($MatherConf[0]->par_service ?? 'ไม่ได้รับราชการ') =="ไม่ได้รับราชการ"?"checked":""?>>
                <label class="custom-control-label" for="par_serviceM99">ไม่ได้รับราชการ</label>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg"
            class="col-sm-3 col-form-label col-form-label">สิทธ์ในการเบิกค่าเล่าเรียนบุตร</label>
        <div class="col-sm-9">
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claimM" id="par_claimM1" value="เบิกได้"
                    <?=($MatherConf[0]->par_claim ?? '') =="เบิกได้"?"checked":""?>>
                <label class="custom-control-label" for="par_claimM1">เบิกได้</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="par_claimM" id="par_claimM2" value="เบิกไม่ได้"
                    <?=($MatherConf[0]->par_claim ?? 'เบิกไม่ได้') =="เบิกไม่ได้"?"checked":""?>>
                <label class="custom-control-label" for="par_claimM2">เบิกไม่ได้
                </label>
            </div>

        </div>
    </div>
    <hr>
    <div class="text-center">
        <button type="submit" class="btn btn-warning">บันทึกข้อมูล</button>
    </div>

</form>
<?php  
         if(($MatherCkeck) == 1){
            $Action = 'FormConfirmMatherUpdate';
        }else{
            $Action = 'FormConfirmMather';
        }
    ?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation" novalidate>
    <input type="hidden" class="dek-floating-input" id="par_stuIDM" name="par_stuIDM" placeholder="ระบุอายุ"
        value="<?php echo $stu[0]->recruit_idCard; ?>" readonly required11>
    <input type="hidden" class="dek-floating-input" id="par_relationKeyM" name="par_relationKeyM" placeholder="ระบุอายุ"
        value="แม่" readonly required11>
    <input type="hidden" class="dek-floating-input" id="par_idM" name="par_idM" placeholder="ระบุอายุ"
        value="<?=$MatherConf[0]->par_id ?? ''?>" readonly required11>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-row">
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_relationM" name="par_relationM"
                                    placeholder="ระบุอายุ" value="<?=$MatherConf[0]->par_relation ?? 'มารดา'?>" required
                                    readonly>
                                <label for="">ความสัมพันธ์เป็น</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_prefixM"
                                    name="par_prefixM" value="<?=$MatherConf[0]->par_prefix ?? ''?>" required11>
                                <label for="">คำนำหน้ามารดา</label>
                            </div>

                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_firstNameM"
                                    name="par_firstNameM" required11 value="<?=$MatherConf[0]->par_firstName ?? ''?>">
                                <label for="">ชื่อจริงมารดา</label>
                            </div>

                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_lastNameM"
                                    name="par_lastNameM" required11 value="<?=$MatherConf[0]->par_lastName ?? ''?>">
                                <label for="">นามสกุลจริงมารดา</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_agoM" name="par_agoM" placeholder=""
                                    value="<?=$MatherConf[0]->par_ago ?? ''?>" required11>
                                <label for="">อายุ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_IdNumberM" placeholder=""
                                    name="par_IdNumberM" data-inputmask="'mask': '9-9999-99999-99-9'" required11
                                    value="<?=$MatherConf[0]->par_IdNumber ?? ''?>">
                                <label for="">รหัสประจำตัวประชาชน
                                    13 หลัก</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_phoneM" name="par_phoneM"
                                    placeholder="" data-inputmask="'mask': '999-999-9999'" required11
                                    value="<?=$MatherConf[0]->par_phone ?? ''?>">
                                <label for="">หมายเลขโทรศัพท์มือถือ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_raceM" name="par_raceM"
                                    placeholder="" required11 value="<?=$MatherConf[0]->par_race ?? ''?>">
                                <label for="par_race">เชื้อชาติ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_nationalM" name="par_nationalM"
                                    placeholder="" required11 value="<?=$MatherConf[0]->par_national ?? ''?>">
                                <label for="par_race">สัญชาติ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_religionM" name="par_religionM"
                                    placeholder="" required11 value="<?=$MatherConf[0]->par_religion ?? ''?>">
                                <label for="par_race">ศาสนา</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-row row">
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="par_careerM" name="par_careerM" placeholder=""
                            required11 value="<?=$MatherConf[0]->par_career ?? ''?>">
                        <label for="par_career">อาชีพ</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="par_educationM" name="par_educationM"
                            placeholder="" required11 value="<?=$MatherConf[0]->par_education ?? ''?>">
                        <label for="par_career">วุฒิการศึกษา</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="par_salaryM" name="par_salaryM" placeholder=""
                            required11 value="<?=$MatherConf[0]->par_salary ?? ''?>">
                        <label for="par_career">เงินเดือน/รายได้</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="par_positionJobM" name="par_positionJobM"
                            placeholder="" required11 value="<?=$MatherConf[0]->par_positionJob ?? ''?>">
                        <label for="par_career">ตำแหน่งหน้าที่การงาน</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ถึงแก่กรรมเมื่อวันที่ <div
                        class="text-danger">ไม่มีไม่ต้องกรอก</div> </label>
                <div class="col-sm-3">
                    <div class="dek-floating-label">
                        <input type="date" class="dek-floating-input" id="par_deceaseM" name="par_deceaseM"
                            placeholder="ยังไม่ถึงแก่กรรม ไม่ต้องระบุ" required11
                            value="<?=$MatherConf[0]->par_decease ?? ''?>">
                        <label for="">วันที่</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ที่อยู่ตามทะเบียนบ้าน</label>
                <div class="col-sm-9">
                    <div class="form-row">
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_hNumberM"
                                    name="par_hNumberM" required11 value="<?=$MatherConf[0]->par_hNumber ?? ''?>">
                                <label>บ้านเลขที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_hMooM"
                                    name="par_hMooM" required11 value="<?=$MatherConf[0]->par_hMoo ?? ''?>">
                                <label>หมู่ที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hTambonM"
                                    name="par_hTambonM" required11 value="<?=$MatherConf[0]->par_hTambon ?? ''?>">
                                <label>ตำบล</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hDistrictM"
                                    name="par_hDistrictM" required11 value="<?=$MatherConf[0]->par_hDistrict ?? ''?>">
                                <label>อำเภอ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hProvinceM"
                                    name="par_hProvinceM" required11 value="<?=$MatherConf[0]->par_hProvince ?? ''?>">
                                <label>จังหวัด</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hPostcodeM"
                                    name="par_hPostcodeM" required11 value="<?=$MatherConf[0]->par_hPostcode ?? ''?>">
                                <label>รหัสไปรษณีย์</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ทีอยู่ปัจจุบัน ( <div
                        class="custom-control custom-checkbox custom-control-inline">
                        <input class="custom-control-input" type="checkbox" id="checkPerM" value="option1">
                        <label class="custom-control-label" for="checkPerM">ตามทะเบียนบ้าน</label>
                    </div>)</label>

                <div class="col-sm-9">
                    <div class="form-row">
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_cNumberM"
                                    name="par_cNumberM" required11 value="<?=$MatherConf[0]->par_cNumber ?? ''?>">
                                <label>บ้านเลขที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_cMooM"
                                    name="par_cMooM" required11 value="<?=$MatherConf[0]->par_cMoo ?? ''?>">
                                <label>หมู่ที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cTambonM"
                                    name="par_cTambonM" required11 value="<?=$MatherConf[0]->par_cTambon ?? ''?>">
                                <label>ตำบล</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cDistrictM"
                                    name="par_cDistrictM" required11 value="<?=$MatherConf[0]->par_cDistrict ?? ''?>">
                                <label>อำเภอ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cProvinceM"
                                    name="par_cProvinceM" required11 value="<?=$MatherConf[0]->par_cProvince ?? ''?>">
                                <label>จังหวัด</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cPostcodeM"
                                    name="par_cPostcodeM" required11 value="<?=$MatherConf[0]->par_cPostcode ?? ''?>">
                                <label>รหัสไปรษณีย์</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ลักษณะที่พัก</label>
                <div class="col-sm-9">

                    <?php $Name = array('บ้านตนเอง','เช่าบ้าน','อาศัยผู้อื่นอยู่','บ้านพักสวัสดิการ');
        foreach ($Name as $key => $v_Name) :
        ?>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input par_restM" type="radio" name="par_restM"
                            id="par_restM<?=$key;?>" value="<?=$v_Name;?>"
                            <?=($MatherConf[0]->par_rest ?? 'บ้านตนเอง') ==$v_Name?"checked":""?>>
                        <label class="custom-control-label" for="par_restM<?=$key;?>"><?=$v_Name;?></label>
                      
                    </div>
                    <?php endforeach; ?>
                    <!-- อยู่ที่พักอื่น ๆ -->
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input par_restM" type="radio" name="par_restM" id="par_restM99"
                            value="อื่นๆ" <?=($MatherConf[0]->par_rest ?? 'อื่นๆ') =="อื่นๆ"?"checked":""?>>
                        <label class="custom-control-label align-self-center mr-2" for="par_restM99"> อื่นๆ </label>
                        <input type="text" class="dek-floating-input par_restOrthor" placeholder="ระบุที่พักอื่น ๆ"
                            id="par_restOrthorM" name="par_restOrthorM" required11
                            value="<?=$MatherConf[0]->par_restOrthor ?? '' ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="card">
        <div class="card-body">
            <div class="row">
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
                    <input type="text" class="dek-floating-input col-md-3" id="par_serviceNameM<?=$key?>"
                        name="par_serviceNameM[]" placeholder="ระบุ" required11
                        value="<?=$MatherConf[0]->par_serviceName ?? '';?>">
                    <?php else :?>
                    <input type="text" style="display:none;" class="dek-floating-input col-md-3"
                        id="par_serviceNameM<?=$key?>" name="par_serviceNameM[]" placeholder="ระบุ" required11>
                    <?php endif; ?>

                    <?php endforeach; ?>

                    <div class="custom-control custom-radio ">
                        <input class="custom-control-input par_serviceM" type="radio" name="par_serviceM"
                            id="par_serviceM99" value="ไม่ได้รับราชการ"
                            <?=($MatherConf[0]->par_service ?? 'ไม่ได้รับราชการ') =="ไม่ได้รับราชการ"?"checked":""?>>
                        <label class="custom-control-label" for="par_serviceM99">ไม่ได้รับราชการ</label>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <label for="colFormLabelLg"
                    class="col-sm-3 col-form-label col-form-label">สิทธ์ในการเบิกค่าเล่าเรียนบุตร</label>
                <div class="col-sm-9">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="par_claimM" id="par_claimM1"
                            value="เบิกได้" <?=($MatherConf[0]->par_claim ?? '') =="เบิกได้"?"checked":""?>>
                        <label class="custom-control-label" for="par_claimM1">เบิกได้</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="par_claimM" id="par_claimM2"
                            value="เบิกไม่ได้"
                            <?=($MatherConf[0]->par_claim ?? 'เบิกไม่ได้') =="เบิกไม่ได้"?"checked":""?>>
                        <label class="custom-control-label" for="par_claimM2">เบิกไม่ได้
                        </label>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-warning">บันทึกข้อมูล</button>
    </div>

</form>
<?php  
          if($OtherCkeck == 1){
            $Action = 'FormConfirmOtherUpdate';
        }else{
            $Action = 'FormConfirmOther';
        }
    ?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation FormConfirmOther" novalidate>
    <input type="hidden" class="dek-floating-input" id="par_stuIDO" name="par_stuIDO" placeholder="ระบุอายุ"
        value="<?php echo $stu[0]->recruit_idCard; ?>" readonly required11>
    <input type="hidden" class="dek-floating-input" id="par_relationKeyO" name="par_relationKeyO" placeholder="ระบุอายุ"
        value="ผู้ปกครอง" readonly required11>
    <input type="hidden" class="dek-floating-input" id="par_idO" name="par_idO" placeholder="ระบุอายุ"
        value="<?=$OtherConf[0]->par_id ?? ''?>" readonly required11>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-row">
                        <div class="col-12 col-md-12 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_relationO" name="par_relationO"
                                    placeholder="ระบุอายุ" value="<?=$OtherConf[0]->par_relation ?? ''?>" required
                                    >
                                <label for="">ความสัมพันธ์เป็น</label>
                                <small class="text-muted">กรณีที่ไม่ใช่ บิดา หรือ มาหาร ให้ระบุข้อมูลด้วย ตัวอย่าง เช่น ตา ยา ลุง ป้า พี่สาว เป็นต้น</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_prefixO"
                                    name="par_prefixO" value="<?=$OtherConf[0]->par_prefix ?? ''?>" required11>
                                <label for="">คำนำหน้าผู้ปกครอง</label>
                            </div>

                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_firstNameO"
                                    name="par_firstNameO" required11 value="<?=$OtherConf[0]->par_firstName ?? ''?>">
                                <label for="">ชื่อจริงผู้ปกครอง</label>
                            </div>

                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_lastNameO"
                                    name="par_lastNameO" required11 value="<?=$OtherConf[0]->par_lastName ?? ''?>">
                                <label for="">นามสกุลจริงผู้ปกครอง</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_agoO" name="par_agoO" placeholder=""
                                    value="<?=$OtherConf[0]->par_ago ?? ''?>" required11>
                                <label for="">อายุ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_IdNumberO" placeholder=""
                                    name="par_IdNumberO" data-inputmask="'mask': '9-9999-99999-99-9'" required11
                                    value="<?=$OtherConf[0]->par_IdNumber ?? ''?>">
                                <label for="">รหัสประจำตัวประชาชน
                                    13 หลัก</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_phoneO" name="par_phoneO"
                                    placeholder="" data-inputmask="'mask': '999-999-9999'" required11
                                    value="<?=$OtherConf[0]->par_phone ?? ''?>">
                                <label for="">หมายเลขโทรศัพท์มือถือ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_raceO" name="par_raceO"
                                    placeholder="" required11 value="<?=$OtherConf[0]->par_race ?? ''?>">
                                <label for="par_race">เชื้อชาติ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_nationalO" name="par_nationalO"
                                    placeholder="" required11 value="<?=$OtherConf[0]->par_national ?? ''?>">
                                <label for="par_race">สัญชาติ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_religionO" name="par_religionO"
                                    placeholder="" required11 value="<?=$OtherConf[0]->par_religion ?? ''?>">
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
                        <input type="text" class="dek-floating-input" id="par_careerO" name="par_careerO" placeholder=""
                            required11 value="<?=$OtherConf[0]->par_career ?? ''?>">
                        <label for="par_career">อาชีพ</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="par_educationO" name="par_educationO"
                            placeholder="" required11 value="<?=$OtherConf[0]->par_education ?? ''?>">
                        <label for="par_career">วุฒิการศึกษา</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="par_salaryO" name="par_salaryO" placeholder=""
                            required11 value="<?=$OtherConf[0]->par_salary ?? ''?>">
                        <label for="par_career">เงินเดือน/รายได้</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="par_positionJobO" name="par_positionJobO"
                            placeholder="" required11 value="<?=$OtherConf[0]->par_positionJob ?? ''?>">
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
                        <input type="date" class="dek-floating-input" id="par_deceaseO" name="par_deceaseO"
                            placeholder="ยังไม่ถึงแก่กรรม ไม่ต้องระบุ" required11
                            value="<?=$OtherConf[0]->par_decease ?? ''?>">
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
                                <input type="text" class="dek-floating-input" placeholder="" id="par_hNumberO"
                                    name="par_hNumberO" required11 value="<?=$OtherConf[0]->par_hNumber ?? ''?>">
                                <label>บ้านเลขที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_hMooO"
                                    name="par_hMooO" required11 value="<?=$OtherConf[0]->par_hMoo ?? ''?>">
                                <label>หมู่ที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hTambonO"
                                    name="par_hTambonO" required11 value="<?=$OtherConf[0]->par_hTambon ?? ''?>">
                                <label>ตำบล</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hDistrictO"
                                    name="par_hDistrictO" required11 value="<?=$OtherConf[0]->par_hDistrict ?? ''?>">
                                <label>อำเภอ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hProvinceO"
                                    name="par_hProvinceO" required11 value="<?=$OtherConf[0]->par_hProvince ?? ''?>">
                                <label>จังหวัด</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hPostcodeO"
                                    name="par_hPostcodeO" required11 value="<?=$OtherConf[0]->par_hPostcode ?? ''?>">
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
                        <input class="custom-control-input" type="checkbox" id="checkPerO" value="option1">
                        <label class="custom-control-label" for="checkPerO">ตามทะเบียนบ้าน</label>
                    </div>)</label>

                <div class="col-sm-9">
                    <div class="form-row">
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_cNumberO"
                                    name="par_cNumberO" required11 value="<?=$OtherConf[0]->par_cNumber ?? ''?>">
                                <label>บ้านเลขที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_cMooO"
                                    name="par_cMooO" required11 value="<?=$OtherConf[0]->par_cMoo ?? ''?>">
                                <label>หมู่ที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cTambonO"
                                    name="par_cTambonO" required11 value="<?=$OtherConf[0]->par_cTambon ?? ''?>">
                                <label>ตำบล</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cDistrictO"
                                    name="par_cDistrictO" required11 value="<?=$OtherConf[0]->par_cDistrict ?? ''?>">
                                <label>อำเภอ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cProvinceO"
                                    name="par_cProvinceO" required11 value="<?=$OtherConf[0]->par_cProvince ?? ''?>">
                                <label>จังหวัด</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cPostcodeO"
                                    name="par_cPostcodeO" required11 value="<?=$OtherConf[0]->par_cPostcode ?? ''?>">
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
                        <input class="custom-control-input par_restO" type="radio" name="par_restO"
                            id="par_restO<?=$key;?>" value="<?=$v_Name;?>"
                            <?=($OtherConf[0]->par_rest ?? 'บ้านตนเอง') ==$v_Name?"checked":""?>>
                        <label class="custom-control-label" for="par_restO<?=$key;?>"><?=$v_Name;?></label>                        
                    </div>
                    <?php endforeach; ?>
                    <!-- อยู่ที่พักอื่น ๆ -->
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input par_restO" type="radio" name="par_restO" id="par_restO99"
                            value="อื่นๆ" <?=($OtherConf[0]->par_rest ?? 'อื่นๆ') =="อื่นๆ"?"checked":""?>>
                        <label class="custom-control-label align-self-center mr-2" for="par_restO99"> อื่นๆ </label>
                        <input type="text" class="dek-floating-input par_restOrthorO" placeholder="ระบุที่พักอื่น ๆ"
                            id="par_restOrthorO" name="par_restOrthorO" required11
                            value="<?=$OtherConf[0]->par_restOrthor ?? '' ?>">
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
                        <input class="custom-control-input par_serviceO" type="radio" name="par_serviceO"
                            id="par_serviceO<?=$key?>" value="<?=$v_Name?>"
                            <?=($OtherConf[0]->par_service ?? '') ==$v_Name?"checked":""?>>
                        <label class="custom-control-label" for="par_serviceO<?=$key?>"><?=$v_Name?></label>
                    </div>
                    <?php  if(@$OtherConf[0]->par_service==$v_Name) : ?>
                    <input type="text" class="dek-floating-input col-md-3" id="par_serviceNameO<?=$key?>"
                        name="par_serviceNameO[]" placeholder="ระบุ" required11
                        value="<?=$OtherConf[0]->par_serviceName ?? '';?>">
                    <?php else :?>
                    <input type="text" style="display:none;" class="dek-floating-input col-md-3"
                        id="par_serviceNameO<?=$key?>" name="par_serviceNameO[]" placeholder="ระบุ" required11>
                    <?php endif; ?>

                    <?php endforeach; ?>

                    <div class="custom-control custom-radio ">
                        <input class="custom-control-input par_serviceO" type="radio" name="par_serviceO"
                            id="par_serviceO99" value="ไม่ได้รับราชการ"
                            <?=($OtherConf[0]->par_service ?? 'ไม่ได้รับราชการ') =="ไม่ได้รับราชการ"?"checked":""?>>
                        <label class="custom-control-label" for="par_serviceO99">ไม่ได้รับราชการ</label>
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
                        <input class="custom-control-input" type="radio" name="par_claimO" id="par_claimO1"
                            value="เบิกได้" <?=($OtherConf[0]->par_claim ?? '') =="เบิกได้"?"checked":""?>>
                        <label class="custom-control-label" for="par_claimO1">เบิกได้</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="par_claimO" id="par_claimO2"
                            value="เบิกไม่ได้"
                            <?=($OtherConf[0]->par_claim ?? 'เบิกไม่ได้') =="เบิกไม่ได้"?"checked":""?>>
                        <label class="custom-control-label" for="par_claimO2">เบิกไม่ได้
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
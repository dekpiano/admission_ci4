<?php  
        if($FatherCkeck == 1){
            $Action = 'FormConfirmFatherUpdate';
        }else{
            $Action = 'FormConfirmFather';
        }
    ?>

<form id="<?=$Action?>" method="post" action="#" class="check-needs-validation" novalidate>
    <input type="hidden" class="dek-floating-input" id="par_stuID" name="par_stuID" placeholder="ระบุอายุ"
        value="<?php echo $stu[0]->recruit_idCard; ?>" readonly required>
    <input type="hidden" class="dek-floating-input" id="par_relationKey" name="par_relationKey" placeholder="ระบุอายุ"
        value="พ่อ" readonly required>
    <input type="hidden" class="dek-floating-input" id="par_id" name="par_id" placeholder="ระบุอายุ"
        value="<?=$FatherConf[0]->par_id ?? ''?>" readonly required>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-row">
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_relation" name="par_relation"
                                    placeholder="ระบุอายุ" value="<?=$FatherConf[0]->par_relation ?? 'บิดา'?>" required
                                    readonly>
                                <label for="">ความสัมพันธ์เป็น</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_prefix"
                                    name="par_prefix" value="<?=$FatherConf[0]->par_prefix ?? ''?>" required>
                                <label for="">คำนำหน้าบิดา</label>
                            </div>

                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_firstName"
                                    name="par_firstName" required value="<?=$FatherConf[0]->par_firstName ?? ''?>">
                                <label for="">ชื่อจริงบิดา</label>
                            </div>

                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_lastName"
                                    name="par_lastName" required value="<?=$FatherConf[0]->par_lastName ?? ''?>">
                                <label for="">นามสกุลจริงบิดา</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_ago" name="par_ago" placeholder=""
                                    value="<?=$FatherConf[0]->par_ago ?? ''?>" required>
                                <label for="">อายุ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_IdNumber" placeholder=""
                                    name="par_IdNumber" data-inputmask="'mask': '9-9999-99999-99-9'" required
                                    value="<?=$FatherConf[0]->par_IdNumber ?? ''?>">
                                <label for="">รหัสประจำตัวประชาชน
                                    13 หลัก</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_phone" name="par_phone"
                                    placeholder="" data-inputmask="'mask': '999-999-9999'" required
                                    value="<?=$FatherConf[0]->par_phone ?? ''?>">
                                <label for="">หมายเลขโทรศัพท์มือถือ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_race" name="par_race"
                                    placeholder="" required value="<?=$FatherConf[0]->par_race ?? ''?>">
                                <label for="par_race">เชื้อชาติ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_national" name="par_national"
                                    placeholder="" required value="<?=$FatherConf[0]->par_national ?? ''?>">
                                <label for="par_race">สัญชาติ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" id="par_religion" name="par_religion"
                                    placeholder="" required value="<?=$FatherConf[0]->par_religion ?? ''?>">
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
                        <input type="text" class="dek-floating-input" id="par_career" name="par_career" placeholder=""
                            required value="<?=$FatherConf[0]->par_career ?? ''?>">
                        <label for="par_career">อาชีพ</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="par_education" name="par_education"
                            placeholder="" required value="<?=$FatherConf[0]->par_education ?? ''?>">
                        <label for="par_career">วุฒิการศึกษา</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="par_salary" name="par_salary" placeholder=""
                            required value="<?=$FatherConf[0]->par_salary ?? ''?>">
                        <label for="par_career">เงินเดือน/รายได้</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="par_positionJob" name="par_positionJob"
                            placeholder="" required11 value="<?=$FatherConf[0]->par_positionJob ?? ''?>">
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
                        <input type="date" class="dek-floating-input" id="par_decease" name="par_decease"
                            placeholder="ยังไม่ถึงแก่กรรม ไม่ต้องระบุ" required11
                            value="<?=$FatherConf[0]->par_decease ?? ''?>">
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
                                <input type="text" class="dek-floating-input" placeholder="" id="par_hNumber"
                                    name="par_hNumber" required value="<?=$FatherConf[0]->par_hNumber ?? ''?>">
                                <label>บ้านเลขที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_hMoo"
                                    name="par_hMoo" required value="<?=$FatherConf[0]->par_hMoo ?? ''?>">
                                <label>หมู่ที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hTambon"
                                    name="par_hTambon" required value="<?=$FatherConf[0]->par_hTambon ?? ''?>">
                                <label>ตำบล</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hDistrict"
                                    name="par_hDistrict" required value="<?=$FatherConf[0]->par_hDistrict ?? ''?>">
                                <label>อำเภอ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hProvince"
                                    name="par_hProvince" required value="<?=$FatherConf[0]->par_hProvince ?? ''?>">
                                <label>จังหวัด</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_hPostcode"
                                    name="par_hPostcode" required value="<?=$FatherConf[0]->par_hPostcode ?? ''?>">
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
                        <input class="custom-control-input" type="checkbox" id="checkPer" value="option1">
                        <label class="custom-control-label" for="checkPer">ตามทะเบียนบ้าน</label>
                    </div>)</label>

                <div class="col-sm-9">
                    <div class="form-row">
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_cNumber"
                                    name="par_cNumber" required value="<?=$FatherConf[0]->par_cNumber ?? ''?>">
                                <label>บ้านเลขที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek-floating-label">
                                <input type="text" class="dek-floating-input" placeholder="" id="par_cMoo"
                                    name="par_cMoo" required value="<?=$FatherConf[0]->par_cMoo ?? ''?>">
                                <label>หมู่ที่</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cTambon"
                                    name="par_cTambon" required value="<?=$FatherConf[0]->par_cTambon ?? ''?>">
                                <label>ตำบล</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cDistrict"
                                    name="par_cDistrict" required value="<?=$FatherConf[0]->par_cDistrict ?? ''?>">
                                <label>อำเภอ</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cProvince"
                                    name="par_cProvince" required value="<?=$FatherConf[0]->par_cProvince ?? ''?>">
                                <label>จังหวัด</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <div class="dek1-floating-label">
                                <input type="text" class="dek1-floating-input" placeholder="" id="par_cPostcode"
                                    name="par_cPostcode" required value="<?=$FatherConf[0]->par_cPostcode ?? ''?>">
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
                        <input class="custom-control-input par_rest" type="radio" name="par_rest"
                            id="par_rest<?=$key;?>" value="<?=$v_Name;?>"
                            <?=($FatherConf[0]->par_rest ?? 'บ้านตนเอง') ==$v_Name?"checked":""?>>
                        <label class="custom-control-label" for="par_rest<?=$key;?>"><?=$v_Name;?></label>
                      
                    </div>
                    <?php endforeach; ?>
                    <!-- อยู่ที่พักอื่น ๆ -->
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input par_rest" type="radio" name="par_rest" id="par_rest99"
                            value="อื่นๆ" <?=($FatherConf[0]->par_rest ?? 'อื่นๆ') =="อื่นๆ"?"checked":""?>>
                        <label class="custom-control-label align-self-center mr-2" for="par_rest99"> อื่นๆ </label>
                        <input type="text" class="dek-floating-input par_restOrthor" placeholder="ระบุที่พักอื่น ๆ"
                            id="par_restOrthor" name="par_restOrthor" required11
                            value="<?=$FatherConf[0]->par_restOrthor ?? '' ?>">
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
                        <input class="custom-control-input par_service" type="radio" name="par_service"
                            id="par_service<?=$key?>" value="<?=$v_Name?>"
                            <?=($FatherConf[0]->par_service ?? '') ==$v_Name?"checked":""?>>
                        <label class="custom-control-label" for="par_service<?=$key?>"><?=$v_Name?></label>
                    </div>
                    <?php  if(@$FatherConf[0]->par_service==$v_Name) : ?>
                    <input type="text" class="dek-floating-input col-md-3" id="par_serviceName<?=$key?>"
                        name="par_serviceName[]" placeholder="ระบุ" required11
                        value="<?=$FatherConf[0]->par_serviceName ?? '';?>">
                    <?php else :?>
                    <input type="text" style="display:none;" class="dek-floating-input col-md-3"
                        id="par_serviceName<?=$key?>" name="par_serviceName[]" placeholder="ระบุ" required11>
                    <?php endif; ?>

                    <?php endforeach; ?>

                    <div class="custom-control custom-radio ">
                        <input class="custom-control-input par_service" type="radio" name="par_service"
                            id="par_service99" value="ไม่ได้รับราชการ"
                            <?=($FatherConf[0]->par_service ?? 'ไม่ได้รับราชการ') =="ไม่ได้รับราชการ"?"checked":""?>>
                        <label class="custom-control-label" for="par_service99">ไม่ได้รับราชการ</label>
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
                        <input class="custom-control-input" type="radio" name="par_claim" id="par_claim1"
                            value="เบิกได้" <?=($FatherConf[0]->par_claim ?? '') =="เบิกได้"?"checked":""?>>
                        <label class="custom-control-label" for="par_claim1">เบิกได้</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="par_claim" id="par_claim2"
                            value="เบิกไม่ได้"
                            <?=($FatherConf[0]->par_claim ?? 'เบิกไม่ได้') =="เบิกไม่ได้"?"checked":""?>>
                        <label class="custom-control-label" for="par_claim2">เบิกไม่ได้
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
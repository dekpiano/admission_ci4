<hr>
<div class="h6">
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

        <div class="card p-3">

            <div class="row form-row">
                <div class="col-sm-4">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_iden" placeholder="" required
                            name="stu_iden" data-inputmask="'mask': '9-9999-99999-99-9'"
                            value="<?=$stuConf[0]->stu_iden ?? $stu[0]->recruit_idCard?>" readonly>
                        <label for="stu_prefix">รหัสประจำตัวประชาชน 13 หลัก</label>
                    </div>
                </div>
            </div>

        </div>


        <?php 
        if(@$stuConf[0]->stu_birthDay){
            $birt =  explode("-",$stuConf[0]->stu_birthDay); 
            $stuYear = intval(@$birt[2]);$stuMount = intval(@$birt[1]);$stuDay = intval(@$birt[0]);
        }else{
            $birt =  explode("-",$stu[0]->recruit_birthday); 
            $stuYear = intval(@$birt[0]+543);$stuMount = intval(@$birt[1]);$stuDay = intval(@$birt[2]);            
        }
        ?>

        <div class="card p-3">
            <div class="row form-row">
                <div class="col-12 col-md-3">
                    <div class="dek-floating-label">
                        <select name="stu_prefix" id="stu_prefix" class="dek-floating-input">
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
                        <label for="stu_prefix">คำนำหน้า</label>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_fristName"
                            name="stu_fristName" required
                            value="<?=$stuConf[0]->stu_fristName ?? $stu[0]->recruit_firstName ?>">
                        <label for="">ชื่อจริง</label>
                    </div>

                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_lastName"
                            name="stu_lastName" required
                            value="<?=$stuConf[0]->stu_lastName ?? $stu[0]->recruit_lastName?>">
                        <label for="">นามสกุลจริง</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_nickName" name="stu_nickName"
                            placeholder="" required value="<?=$stuConf[0]->stu_nickName ?? ''?>">
                        <label for="stu_nickName">ชื่อเล่นของนักเรียน</label>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="dek-floating-label">
                        <select class="dek-floating-input" id="stu_day" name="stu_day">
                            <option value="">เลือกวัน</option>
                            <?php for ($i=1; $i <= 31 ; $i++) : ?>
                            <option <?=$stuDay==$i?"selected":"" ?> value="<?=sprintf("%02d",$i)?>"><?=$i;?>
                            </option>
                            <?php endfor; ?>
                        </select>
                        <label for="">วันที่เกิด</label>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="dek-floating-label">
                        <select class="dek-floating-input" id="stu_month" name="stu_month">
                            <option value="">เลือกเดือน</option>
                            <?php 
                                $monthTH = [null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
                                for ($i=1; $i <= 12 ; $i++) : 
                             ?>
                            <option <?=$stuMount==$i?"selected":"" ?> value="<?=sprintf("%02d",$i)?>">
                                <?=$monthTH[$i];?>
                            </option>
                            <?php endfor; ?>
                        </select>
                        <label for="">เดือนเกิด</label>
                    </div>

                </div>
                <div class="col-12 col-md-4">
                    <div class="dek-floating-label">
                        <select class="dek-floating-input" id="stu_year" name="stu_year">
                            <option value="">เลือกปี</option>
                            <?php 
                                $d = date("Y")+543;
                                for ($i=$d-25; $i <= $d ; $i++) : 
                            ?>
                            <option <?=$stuYear==$i?"selected":"" ?> value="<?=$i?>"><?=$i;?></option>
                            <?php endfor; ?>
                        </select>
                        <label for="">ปีเกิด</label>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_phone" name="stu_phone" placeholder=""
                            required data-inputmask="'mask': '999-999-9999'"
                            value="<?=$stuConf[0]->stu_phone ?? $stu[0]->recruit_phone?>">
                        <label>เบอร์โทรศัพท์ (นักเรียน)</label>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="dek-floating-label">
                        <input type="email" class="dek-floating-input" placeholder="" id="stu_email" name="stu_email"
                            required11 value="<?=$stuConf[0]->stu_email ?? ''?>">
                        <label>อีเมล (ถ้ามี...)</label>
                    </div>
                </div>


            </div>
        </div>

        <div class="card p-3">
            <div class="row form-row">
                <div class="col-12 col-md-3 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_birthHospital"
                            name="stu_birthHospital" required value="<?=$stuConf[0]->stu_birthHospital ?? ''?>">
                        <label>โรงพยาบาลที่เกิด</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_birthTambon"
                            name="stu_birthTambon" required value="<?=$stuConf[0]->stu_birthTambon ?? ''?>">
                        <label for="">ตำบลที่เกิด</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_birthDistrict"
                            name="stu_birthDistrict" required value="<?=$stuConf[0]->stu_birthDistrict ?? ''?>">
                        <label>อำเภอที่เกิด</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_birthProvirce"
                            name="stu_birthProvirce" required value="<?=$stuConf[0]->stu_birthProvirce ?? ''?>">
                        <label>จังหวัดที่เกิด</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="row form-row">
                <div class="col-sm-4">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_nationality" name="stu_nationality"
                            placeholder="" required
                            value="<?=$stuConf[0]->stu_nationality ?? $stu[0]->recruit_nationality?>">
                        <label for="stu_nationality">เชื้อชาติ</label>
                    </div>
                </div>

                <div class="col-sm-4 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_race" name="stu_race" placeholder=""
                            required value="<?=$stuConf[0]->stu_race ?? $stu[0]->recruit_race?>">
                        <label for="stu_race">สัญชาติ</label>
                    </div>
                </div>
                <div class="col-sm-4 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_religion" name="stu_religion"
                            placeholder="" required value="<?=$stuConf[0]->stu_religion ?? $stu[0]->recruit_religion?>">
                        <label for="colFormLabelLg">ศาสนา</label>
                    </div>
                </div>

                <div class="col-sm-4 ">
                    <div class="dek-floating-label">
                        <select class="dek-floating-input" id="stu_bloodType" name="stu_bloodType" required>
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
                        <label for="stu_bloodType">กรุ๊ปเลือด</label>
                    </div>
                </div>
                <div class="col-sm-8 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_diseaes" name="stu_diseaes" placeholder=""
                            required11 value="<?=$stuConf[0]->stu_diseaes ?? ''?>">
                        <label for="stu_diseaes">โรคประจำตัว (ระบุถ้ามี..)</label>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_disablde" name="stu_disablde"
                            placeholder="" required value="<?=$stuConf[0]->stu_disablde ?? ''?>">
                        <label for="stu_disablde">ความพการ (ระบุถ้ามี...)</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="stu_wieght" name="stu_wieght" placeholder=""
                            required value="<?=$stuConf[0]->stu_wieght ?? ''?>">
                        <label for="stu_wieght">น้ำหนัก (ระบุเป็นกิโลกรัม)</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="stu_hieght" name="stu_hieght" placeholder=""
                            required value="<?=$stuConf[0]->stu_hieght ?? ''?>">
                        <label for="stu_hieght">ส่วนสูง (ระบุเป็น ซม.)</label>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_talent" name="stu_talent" placeholder=""
                            required value="<?=$stuConf[0]->stu_talent ?? ''?>">
                        <label for="stu_talent">ความสามารถพิเศษ</label>
                    </div>
                </div>


            </div>
        </div>

        <div class="card p-3">
            <div class="form-row row">
                <div class="col-sm-4 ">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="stu_numberSibling" name="stu_numberSibling"
                            placeholder="" required value="<?=$stuConf[0]->stu_numberSibling ?? ''?>">
                        <label for="stu_numberSibling">จำนวนพี่น้องทั้งหมด <small> (รวมทั้งตัวนักเรียนเองด้วย)</small>
                        </label>
                    </div>

                </div>
                <div class="col-sm-4 ">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="stu_firstChild" name="stu_firstChild"
                            placeholder="" required value="<?=$stuConf[0]->stu_firstChild ?? ''?>">
                        <label for="stu_firstChild">นักเรียนเป็นลูกคนที่</label>
                    </div>
                </div>
                <div class="col-sm-4 ">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="stu_numberSiblingSkj"
                            name="stu_numberSiblingSkj" placeholder="" required
                            value="<?=$stuConf[0]->stu_numberSiblingSkj ?? ''?>">
                        <label for="stu_numberSiblingSkj">พี่น้องเรียนใน รร.สกจ (รวมตัวนักเรียนเองด้วย)</label>
                    </div>

                </div>
            </div>
        </div>
        <div class="card p-3">
            <div class="form-row row">
                <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">สภาพบิดา
                    -
                    มารดา</label>
                <div class="col-sm-9">
                    <?php $parstu = array("อยู่ด้วยกัน","แยกกันอยู่","หย่าร้าง","บิดาถึงแก่กรรม","มารดาถึงแก่กรรม","บิดาและมารดาถึงแก่กรรม","บิดาหรือมารดาแต่งงานใหม่"); 
                                                foreach ($parstu as $key => $v_parstu) :
                                                ?>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input <?=($stuConf[0]->stu_parenalStatus ?? '') ==$v_parstu?"checked":""?>
                            class="custom-control-input" type="radio" id="stu_parenalStatus<?=$key?>"
                            value="<?=$v_parstu;?>" name="stu_parenalStatus" required>
                        <label class="custom-control-label" for="stu_parenalStatus<?=$key?>"><?=$v_parstu;?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="form-row row">
                <label for="colFormLabelLg"
                    class="col-sm-3 col-form-label col-form-label">สภาพความเป็นอยู่ปัจจุบัน</label>
                <div class="col-sm-9">
                    <?php $pars = array("อยู่กับบิดาและมารดา","อยู่กับบิดาหรือมารดา","บุคคลอื่น"); 
                                                foreach ($pars as $key => $v_pars) :
                                                ?>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input <?=($stuConf[0]->stu_presentLife ?? '') ==$v_pars?"checked":""?>
                            class="custom-control-input" type="radio" name="stu_presentLife"
                            id="stu_presentLife<?=$key?>" value="<?=$v_pars;?>" required>
                        <label class="custom-control-label" for="stu_presentLife<?=$key?>"><?=$v_pars;?></label>
                    </div>
                    <?php endforeach; ?>
                    <?php if(($stuConf[0]->stu_presentLife ?? '') == "บุคคลอื่น"):?>
                    <input type="text" id="stu_personOther" name="stu_personOther" class="ml-2 textbox form-control"
                        value="<?=($stuConf[0]->stu_personOther ?? '')?>" placeholder="">
                    <?php else :?>
                    <input type="text" id="stu_personOther" name="stu_personOther" class="ml-2 textbox form-control"
                        value="<?=($stuConf[0]->stu_personOther ?? '')?>" style="display:none;" placeholder="">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <p>ที่อยู่ตามทะเบียนบ้าน</p>
            <div class="form-row row">
                <div class="col-12 col-md-4 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_hCode" name="stu_hCode"
                            required11 value="<?=$stuConf[0]->stu_hCode ?? ''?>">
                        <label>รหัสประจำบ้าน</label>
                    </div>

                </div>
                <div class="col-12 col-md-4 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_hNumber" name="stu_hNumber"
                            required value="<?=$stuConf[0]->stu_hNumber ?? $stu[0]->recruit_homeNumber?>">
                        <label>บ้านเลขที่</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_hMoo" name="stu_hMoo"
                            required value="<?=$stuConf[0]->stu_hMoo ?? $stu[0]->recruit_homeGroup?>">
                        <label>หมู่ที่</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_hRoad" name="stu_hRoad" placeholder=""
                            required value="<?=$stuConf[0]->stu_hRoad ?? $stu[0]->recruit_homeRoad?>">
                        <label>ถนน</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_hTambon"
                            name="stu_hTambon" required
                            value="<?=$stuConf[0]->stu_hTambon ?? $stu[0]->recruit_homeSubdistrict?>">
                        <label>ตำบล</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_hDistrict"
                            name="stu_hDistrict" required
                            value="<?=$stuConf[0]->stu_hDistrict ?? $stu[0]->recruit_homedistrict?>">
                        <label>อำเภอ</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_hProvince"
                            name="stu_hProvince" required
                            value="<?=$stuConf[0]->stu_hProvince ?? $stu[0]->recruit_homeProvince?>">
                        <label>จังหวัด</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_hPostCode"
                            name="stu_hPostCode" required
                            value="<?=$stuConf[0]->stu_hPostCode ?? $stu[0]->recruit_homePostcode?>">
                        <label>รหัสไปรษณีย์</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <label for="colFormLabelLg">ทีอยู่ปัจจุบัน ( <div
                    class="custom-control custom-checkbox custom-control-inline">
                    <input class="custom-control-input" type="checkbox" id="clickLike" name="clickLike" value="option1">
                    <label class="custom-control-label" for="clickLike">ตามทะเบียนบ้าน</label>
                </div>)</label>

            <div class="form-group row">
                <div class="col-12 col-md-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_cNumber" name="stu_cNumber"
                            required value="<?=$stuConf[0]->stu_cNumber ?? ''?>">
                        <label>บ้านเลขที่</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_cMoo" name="stu_cMoo"
                            required value="<?=$stuConf[0]->stu_cMoo ?? ''?>">
                        <label>หมู่ที่</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_cRoad" name="stu_cRoad" placeholder=""
                            required value="<?=$stuConf[0]->stu_cRoad ?? ''?>">
                        <label>ถนน</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_cTumbao"
                            name="stu_cTumbao" required value="<?=$stuConf[0]->stu_cTumbao ?? ''?>">
                        <label>ตำบล</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_cDistrict"
                            name="stu_cDistrict" required value="<?=$stuConf[0]->stu_cDistrict ?? ''?>">
                        <label>อำเภอ</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_cProvince"
                            name="stu_cProvince" required value="<?=$stuConf[0]->stu_cProvince ?? ''?>">
                        <label>จังหวัด</label>
                    </div>
                </div>
                <div class="col-12 col-md-3 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" id="stu_cPostcode" name="stu_cPostcode"
                            placeholder="" required value="<?=$stuConf[0]->stu_cPostcode ?? ''?>">
                        <label>รหัสไปรษณีย์</label>
                    </div>
                </div>

            </div>
        </div>

        <div class="card p-3">
            <div class="form-row row">
                <label for="colFormLabelLg"
                    class="col-sm-3 col-form-label col-form-label">สภาพความเป็นอยู่ปัจจุบัน</label>
                <div class="col-sm-9">
                    <?php $addr = array("บ้านตนเอง","เช่าอยู่","อาศัยผู้อื่นอยู่","บ้านพักราชการ","วัด","หอพัก"); 
                                                foreach ($addr as $key => $v_addr) :
                                                ?>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="stu_natureRoom" id="natureRoom<?=$key?>"
                            <?=($stuConf[0]->stu_natureRoom ?? '')==$v_addr?"checked":""?> value="<?=$v_addr;?>"
                            required>
                        <label class="custom-control-label" for="natureRoom<?=$key?>"><?=$v_addr;?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="form-row row">
                <div class="col-sm-4">
                    <div class="dek-floating-label">
                        <input type="number" class="dek-floating-input" id="stu_farSchool" name="stu_farSchool"
                            placeholder="" required value="<?=$stuConf[0]->stu_farSchool ?? ''?>">
                        <label for="stu_farSchool">ระยะทางอยู่ห่างจากโรงเรียน</label>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_travel" name="stu_travel" placeholder=""
                            required value="<?=$stuConf[0]->stu_travel ?? ''?>">
                        <label for="stu_travel">เดินทางมาโรงเรียน สกจ. โดย</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="form-row row">
                <div class="col-sm-2">
                    <div class="dek-floating-label">
                        <?php $gradLevel = array('ป.6','ม.1','ม.2','ม.3','ม.4','ม.5'); ?>
                        <select class="dek-floating-input" id="stu_gradLevel" name="stu_gradLevel">
                            <?php foreach ($gradLevel as $key => $v_gradLevel): ?>
                            <option <?=($stuConf[0]->stu_gradLevel ?? '') == $v_gradLevel ?"selected":""?>
                                value="<?=$v_gradLevel;?>"><?=$v_gradLevel;?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="stu_gradLevel">จบการศึกษาชั้น </label>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" placeholder="" id="stu_schoolfrom"
                            name="stu_schoolfrom" required
                            value="<?php echo $stuConf[0]->stu_schoolfrom ?? $stu[0]->recruit_oldSchool;?>">
                        <label for="">จากโรงเรียน</label>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_schoolTambao"
                            name="stu_schoolTambao" required value="<?php echo $stuConf[0]->stu_schoolTambao ?? '';?>">
                        <label for="">ตำบล</label>
                    </div>
                </div>
                <div class="col-12 col-md-2 ">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_schoolDistrict"
                            name="stu_schoolDistrict" required
                            value="<?php echo $stuConf[0]->stu_schoolDistrict ?? $stu[0]->recruit_district ;?>">
                        <label for="">อำเภอ</label>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="dek1-floating-label">
                        <input type="text" class="dek1-floating-input" placeholder="" id="stu_schoolProvince"
                            name="stu_schoolProvince" required
                            value="<?php echo $stuConf[0]->stu_schoolProvince ?? $stu[0]->recruit_province;?>">
                        <label for="">จังหวัด</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="row">
                <label for="colFormLabelLg"
                    class="col-sm-3 col-form-label col-form-label">เคยเป็นนักเรียนโรงเรียนสวนกุหลาบวิทยาลัย(จิรประวัติ)
                    นครสวรรค์</label>
                <div class="col-sm-9">

                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="stu_usedStudent" id="stu_usedStudent1"
                            <?=$stuConf[0]->stu_usedStudent ?? ''=="ไม่เคย"?"checked":""?> value="ไม่เคย" required>
                        <label class="custom-control-label align-self-center" for="stu_usedStudent1">ไม่เคย
                        </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mr-3">
                        <input class="custom-control-input" type="radio" name="stu_usedStudent" id="stu_usedStudent2"
                            <?=$stuConf[0]->stu_usedStudent ?? ''=="เคย"?"checked":""?> value="เคย" required>
                        <label class="custom-control-label align-self-center" for="stu_usedStudent2"> เคย
                        </label>

                       
                        <div class="dek-floating-label">
                            <select class="dek-floating-input ml-3" id="stu_inputLevel" name="stu_inputLevel">
                                <option value="">เลือกระดับชั้น</option>
                                <?php for ($i=1; $i <= 6 ; $i++) : ?>
                                <option <?=($stuConf[0]->stu_inputLevel ?? '')==$i?"selected":"" ?> value="<?=$i;?>">
                                    ม.<?=$i;?>
                                </option>
                                <?php endfor; ?>
                            </select>
                            <!-- <label>ระดับชั้น</label> -->
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="form-row row">
                <div class="col-sm-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_phoneUrgent" name="stu_phoneUrgent"
                            value="<?php echo $stuConf[0]->stu_phoneUrgent ?? '';?>" placeholder="" required
                            data-inputmask="'mask': '999-999-9999'">
                        <label for="stu_phoneUrgent">โทรศัพท์ติดต่อฉุกเฉิน</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="dek-floating-label">
                        <input type="text" class="dek-floating-input" id="stu_phoneFriend" name="stu_phoneFriend"
                            value="<?php echo $stuConf[0]->stu_phoneFriend ?? '';?>" placeholder="" required11
                            data-inputmask="'mask': '999-999-9999'">
                        <label for="stu_phoneFriend">โทรศัพท์เพื่อนบ้านใกล้เคียง</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-center">
            <button type="submit" id="" class="btn btn-primary ReLoading">บันทึกข้อมูล</button>
        </div>

    </form>
</div>
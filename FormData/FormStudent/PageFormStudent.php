<hr>
<div class="h6">
    <form id="FormConfirmStudent" method="post" class="check-needs-validation" novalidate>
        <input type="hidden" id="stu_img" name="stu_img" class="stu_img" value="<?=$stu[0]->recruit_img;?>">
        <input type="hidden" id="stu_UpdateConfirm" name="stu_UpdateConfirm"  value="<?=$checkYear[0]->openyear_year;?>">
        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ชื่อ - นามสกุลจริง</label>
            <div class="col-sm-9">
                <div class="form-row">
                    <div class="col-12 col-md-3 mb-2">
                        <select name="stu_prefix" id="stu_prefix" class="form-control">
                            <option value="">เลือกคำนำหน้า</option>
                            <?php 
                            $fix = array("เด็กหญิง","เด็กชาย","นาย","นางสาว"); 
                            foreach ($fix as $key => $v_fix) :
                            ?>
                            <option <?=$stu[0]->recruit_prefix==$v_fix?"selected":"" ?> value="<?=$v_fix;?>">
                                <?=$v_fix;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <input type="text" class="form-control" placeholder="ระบุชื่อจริง" id="stu_fristName"
                            name="stu_fristName" required value="<?=$stu[0]->recruit_firstName?>">
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <input type="text" class="form-control" placeholder="ระบุนามสกุลจริง" id="stu_lastName"
                            name="stu_lastName" required value="<?=$stu[0]->recruit_lastName?>">
                    </div>
                </div>
            </div>
        </div>       
        <div class="form-group row">
            <label for="stu_iden" class="col-sm-3 col-form-label col-form-label">รหัสประจำตัวประชาชน
                13 หลัก</label>
            <div class="col-sm-9">
                <input type="text" class="form-control form-control" id="stu_iden"
                    placeholder="รหัสประจำตัวประชาชน 13 หลัก" required name="stu_iden"
                    data-inputmask="'mask': '9-9999-99999-99-9'" value="<?php echo $stu[0]->recruit_idCard; ?>"
                    readonly>
            </div>
        </div>
        <?php $birt =  explode("-",$stu[0]->recruit_birthday); 
                        $stuYear = $birt[0]+543;$stuMount = $birt[1];$stuDay = $birt[2];
                    ?>
        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">วันที่เกิด</label>
            <div class="col-sm-9">
                <div class="form-row">
                    <div class="col-12 col-md-3 mb-2">
                        <select class="form-control" id="stu_day" name="stu_day">
                            <option value="">เลือกวัน</option>
                            <?php for ($i=1; $i <= 31 ; $i++) : ?>
                            <option <?=$stuDay==$i?"selected":"" ?> value="<?=sprintf("%02d",$i)?>"><?=$i;?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <select class="form-control" id="stu_month" name="stu_month">
                            <option value="">เลือกเดือน</option>
                            <?php 
                                                            $monthTH = [null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
                                                            for ($i=1; $i <= 12 ; $i++) : 
                                                            ?>
                            <option <?=$stuMount==$i?"selected":"" ?> value="<?=sprintf("%02d",$i)?>"><?=$monthTH[$i];?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <select class="form-control" id="stu_year" name="stu_year">
                            <option value="">เลือกปี</option>
                            <?php 
                                                            $d = date("Y")+543;
                                                            for ($i=$d-25; $i <= $d ; $i++) : 
                                                            ?>
                            <option <?=$stuYear==$i?"selected":"" ?> value="<?=$i?>"><?=$i;?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">สถานที่เกิด</label>
            <div class="col-sm-9">
                <div class="form-row">
                    <div class="col-12 col-md-3 mb-2">
                    <label>ตำบล</label>
                        <input type="text" class="form-control" placeholder="ระบุตำบล" id="stu_birthTambon"
                            name="stu_birthTambon" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>อำเภอ</label>  
                        <input type="text" class="form-control" placeholder="ระบุอำเภอ" id="stu_birthDistrict"
                            name="stu_birthDistrict" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>จังหวัด</label>  
                        <input type="text" class="form-control" placeholder="ระบุจังหวัด" id="stu_birthProvirce"
                            name="stu_birthProvirce" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>โรงพยาบาล</label>  
                        <input type="text" class="form-control" placeholder="ระบุชื่อโรงพยาบาล" id="stu_birthHospital"
                            name="stu_birthHospital" required11>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="stu_nationality" class="col-sm-3 col-form-label col-form-label">เชื้อชาติ</label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_nationality" name="stu_nationality"
                    placeholder="ระบุเชื้อชาติ" required value="<?=$stu[0]->recruit_nationality?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_race" class="col-sm-3 col-form-label col-form-label">สัญชาติ</label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_race" name="stu_race"
                    placeholder="ระบุสัญชาติ" requiredvalue="<?=$stu[0]->recruit_race?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ศาสนา</label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_religion" name="stu_religion"
                    placeholder="ระบุศาสนา" required value="<?=$stu[0]->recruit_religion?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="stu_bloodType" class="col-sm-3 col-form-label col-form-label">กรุ๊ปเลือด</label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_bloodType" name="stu_bloodType"
                    placeholder="ระบุกรุ๊ปเลือด" required>
                    <small id="stu_bloodType" class="form-text text-muted">กรอกเป็นภาษาอังกฤษ เช่น A B O AB</small>
            </div>
        </div>

        <div class="form-group row">
            <label for="stu_diseaes" class="col-sm-3 col-form-label col-form-label">โรคประจำตัว
                (ระบุ)</label>
            <div class="col-sm-9">
                <input type="text" class="form-control form-control" id="stu_diseaes" name="stu_diseaes"
                    placeholder="ระบุโรคประจำตัว" required11>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="stu_numberSibling" class="col-sm-3 col-form-label col-form-label">จำนวนพี่น้องทั้งหมด
                <br> <small> (รวมทั้งตัวนักเรียนเองด้วย)</small> </label>
            <div class="col-sm-2">
                <input type="number" class="form-control form-control" id="stu_numberSibling" name="stu_numberSibling"
                    placeholder="ระบุจำนวนพี่น้องทั้งหมด" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_firstChild" class="col-sm-3 col-form-label col-form-label">นักเรียนเป็นลูกคนที่</label>
            <div class="col-sm-2">
                <input type="number" class="form-control form-control" id="stu_firstChild" name="stu_firstChild"
                    placeholder="ระบุจำนวน" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_numberSiblingSkj"
                class="col-sm-7 col-form-label col-form-label">พี่น้องเรียนในโรงเรียนสวนกุหลาบวิทยาลัย
                (จิรประวัติ) นครสวรรค์ (รวมตัวนักเรียนเองด้วย)</label>
            <div class="col-sm-2">
                <input type="number" class="form-control form-control" id="stu_numberSiblingSkj"
                    name="stu_numberSiblingSkj" placeholder="ระบุจำนวน" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_nickName" class="col-sm-3 col-form-label col-form-label">ชื่อเล่นของนักเรียน</label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_nickName" name="stu_nickName"
                    placeholder="ระบุชื่อเล่น" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_disablde" class="col-sm-3 col-form-label col-form-label">ความพิการ</label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_disablde" name="stu_disablde"
                    placeholder="ระบุความพการ ถ้ามี..." required11>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_wieght" class="col-sm-3 col-form-label col-form-label">น้ำหนัก</label>
            <div class="col-sm-2">
                <input type="number" class="form-control form-control" id="stu_wieght" name="stu_wieght"
                    placeholder="ระบุน้ำหนัก กิโลกรัม" required11>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_hieght" class="col-sm-3 col-form-label col-form-label">ส่วนสูง</label>
            <div class="col-sm-2">
                <input type="number" class="form-control form-control" id="stu_hieght" name="stu_hieght"
                    placeholder="ระบุส่วนสูง ซม." required11>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_talent" class="col-sm-3 col-form-label col-form-label">ความสามารถพิเศษ</label>
            <div class="col-sm-5">
                <input type="text" class="form-control form-control" id="stu_talent" name="stu_talent"
                    placeholder="ระบุความสามารถพิเศษ" required11>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">สภาพบิดา
                -
                มารดา</label>
                <div class="col-sm-9">
                <?php $parstu = array("อยู่ด้วยกัน","แยกกันอยู่","หย่าร้าง","บิดาถึงแก่กรรม","มารดาถึงแก่กรรม","บิดาและมารดาถึงแก่กรรม","บิดาหรือมารดาแต่งงานใหม่"); 
                                                foreach ($parstu as $key => $v_parstu) :
                                                ?>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" id="stu_parenalStatus<?=$key?>"
                        value="<?=$v_parstu;?>" name="stu_parenalStatus" required>
                    <label class="custom-control-label" for="stu_parenalStatus<?=$key?>"><?=$v_parstu;?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">สภาพความเป็นอยู่ปัจจุบัน</label>
            <div class="col-sm-9">
                <?php $pars = array("อยู่กับบิดาและมารดา","อยู่กับบิดาหรือมารดา","บุคคลอื่น"); 
                                                foreach ($pars as $key => $v_pars) :
                                                ?>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="stu_presentLife" id="stu_presentLife<?=$key?>"
                        value="<?=$v_pars;?>" required>
                    <label class="custom-control-label" for="stu_presentLife<?=$key?>"><?=$v_pars;?></label>
                </div>                
                <?php endforeach; ?>
                <input type="text" id="stu_personOther" name="stu_personOther" class="ml-2 textbox form-control" style="display:none;"
                        placeholder="ระบุชื่อบุคคลอื่น เช่น ปู่ ย่า ตา ยาย พี่ น้อง">
            </div>
        </div>

        <hr>

        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ทีอยู่ตามทะเบียนบ้าน</label>
            <div class="col-sm-9">
                <div class="form-row">
                    <div class="col-12 col-md-3 mb-2">
                    <label>รหัสประจำบ้าน</label>  
                        <input type="text" class="form-control" placeholder="รหัสประจำบ้าน" id="stu_hCode"
                            name="stu_hCode" required11>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>บ้านเลขที่</label>  
                        <input type="text" class="form-control" placeholder="บ้านเลขที่" id="stu_hNumber"
                            name="stu_hNumber" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>หมูที่</label>  
                        <input type="text" class="form-control" placeholder="หมู่ที่" id="stu_hMoo" name="stu_hMoo"
                        required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>ถนน</label>  
                        <input type="text" class="form-control" id="stu_hRoad" name="stu_hRoad" placeholder="ถนน"
                            required11>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>ตำบล</label>  
                        <input type="text" class="form-control" placeholder="ตำบล" id="stu_hTambon" name="stu_hTambon"
                        required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>อำเภอ</label>  
                        <input type="text" class="form-control" placeholder="อำเภอ" id="stu_hDistrict"
                            name="stu_hDistrict" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>จังหวัด</label>  
                        <input type="text" class="form-control" placeholder="จังหวัด" id="stu_hProvince"
                            name="stu_hProvince" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>รหัสไปรษณีย์</label>  
                        <input type="text" class="form-control" placeholder="รหัสไปรษณีย์" id="stu_hPostCode"
                            name="stu_hPostCode" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>เบอร์โทรศัพท์</label>  
                        <input type="text" class="form-control" id="stu_phone" name="stu_phone"
                            placeholder="เบอร์โทรศัพท์ (นักเรียน)" required data-inputmask="'mask': '999-999-9999'">
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>อีเมล</label>  
                        <input type="email" class="form-control" placeholder="อีเมล" id="stu_email" name="stu_email"
                            required11>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">ทีอยู่ปัจจุบัน ( <div
                    class="custom-control custom-checkbox custom-control-inline">
                    <input class="custom-control-input" type="checkbox" id="clickLike" name="clickLike" value="option1">
                    <label class="custom-control-label" for="clickLike">ตามทะเบียนบ้าน</label>
                </div>)</label>

            <div class="col-sm-9">
                <div class="form-row">
                    <div class="col-12 col-md-3 mb-2">
                    <label>บ้านเลขที่</label>  
                        <input type="text" class="form-control" placeholder="บ้านเลขที่" id="stu_cNumber"
                            name="stu_cNumber" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>หมู่ที่</label>  
                        <input type="text" class="form-control" placeholder="หมู่ที่" id="stu_cMoo" name="stu_cMoo"
                        required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>ถนน</label>  
                        <input type="text" class="form-control" id="stu_cRoad" name="stu_cRoad" placeholder="ถนน"
                            required11>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>ตำบล</label>  
                        <input type="text" class="form-control" placeholder="ตำบล" id="stu_cTumbao" name="stu_cTumbao"
                        required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>อำเภอ</label>  
                        <input type="text" class="form-control" placeholder="อำเภอ" id="stu_cDistrict"
                            name="stu_cDistrict" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>จังหวัด</label>  
                        <input type="text" class="form-control" placeholder="จังหวัด" id="stu_cProvince"
                            name="stu_cProvince" required>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                    <label>รหัสไปรษณีย์</label>  
                        <input type="text" class="form-control" id="stu_cPostcode" name="stu_cPostcode"
                            placeholder="รหัสไปรษณีย์" required>
                    </div>
                    <!-- <div class="col-12 col-md-3 mb-2">
                                                        <input type="text" class="form-control"
                                                            placeholder="เบอร์โทรศัพท์" required11>
                                                    </div> -->
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">สภาพความเป็นอยู่ปัจจุบัน</label>
            <div class="col-sm-9">
                <?php $addr = array("บ้านตนเอง","เช่าอยู่","อาศัยผู้อื่นอยู่","บ้านพักราชการ","วัด","หอพัก"); 
                                                foreach ($addr as $key => $v_addr) :
                                                ?>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="stu_natureRoom" id="natureRoom<?=$key?>"
                        value="<?=$v_addr;?>" required>
                    <label class="custom-control-label" for="natureRoom<?=$key?>"><?=$v_addr;?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="stu_farSchool" class="col-sm-3 col-form-label col-form-label">ระยะทางอยู่ห่างจากโรงเรียน
            </label>
            <div class="col-sm-2">
                <input type="number" class="form-control form-control" id="stu_farSchool" name="stu_farSchool"
                    placeholder="ใส่เป็นกิโลเมตร" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_travel" class="col-sm-3 col-form-label col-form-label">เดินทางโดย
            </label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_travel" name="stu_travel"
                    placeholder="เช่น รถยนต์ เรือ เดิน" required>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="stu_gradLevel" class="col-sm-3 col-form-label col-form-label">จบการศึกษาชั้น
            </label>
            <div class="col-sm-2">
                <input type="text" class="form-control form-control" id="stu_gradLevel" name="stu_gradLevel"
                    placeholder="ระดับชั้น" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label">จากโรงเรียน
            </label>
            <div class="col-sm-9">
                <div class="form-row">
                    <div class="col-12 col-md-12 mb-2">
                        <input type="text" class="form-control" placeholder="ชื่อโรงเรียน" id="stu_schoolfrom"
                            name="stu_schoolfrom" required value="<?php echo $stu[0]->recruit_oldSchool;?>">
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <input type="text" class="form-control" placeholder="ตำบล" id="stu_schoolTambao"
                            name="stu_schoolTambao" required value="">
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <input type="text" class="form-control" placeholder="อำเภอ" id="stu_schoolDistrict"
                            name="stu_schoolDistrict" required value="<?php echo $stu[0]->recruit_district;?>">
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <input type="text" class="form-control" placeholder="จังหวัด" id="stu_schoolProvince"
                            name="stu_schoolProvince" required value="<?php echo $stu[0]->recruit_province;?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="colFormLabelLg"
                class="col-sm-3 col-form-label col-form-label">เคยเป็นนักเรียนโรงเรียนสวนกุหลาบวิทยาลัย(จิรประวัติ)
                นครสวรรค์</label>
            <div class="col-sm-9">

                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="stu_usedStudent" id="stu_usedStudent1"
                        value="ไม่เคย" required>
                    <label class="custom-control-label align-self-center" for="stu_usedStudent1">ไม่เคย
                    </label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="stu_usedStudent" id="stu_usedStudent2"
                        value="เคย" required>
                    <label class="custom-control-label align-self-center" for="stu_usedStudent2"> เคย
                    </label>
                    <select class="form-control ml-3" id="stu_inputLevel" name="stu_inputLevel" style="display:none;">
                            <option value="">เลือกระดับชั้น</option>
                            <?php for ($i=1; $i <= 6 ; $i++) : ?>
                            <option <?=$stuDay==$i?"selected":"" ?> value="<?=$i;?>">ม.<?=$i;?></option>
                            <?php endfor; ?>
                        </select>
                   
                </div>
            </div>
        </div>

        <hr>
        <div class="form-group row">
            <label for="stu_phoneUrgent" class="col-sm-3 col-form-label col-form-label">โทรศัพท์ติดต่อฉุกเฉิน
            </label>
            <div class="col-sm-3">
                <input type="text" class="form-control form-control" id="stu_phoneUrgent" name="stu_phoneUrgent"
                    placeholder="ระบุเบอร์โทรศัพท์" required11 data-inputmask="'mask': '999-999-9999'">
            </div>
        </div>
        <div class="form-group row">
            <label for="stu_phoneFriend" class="col-sm-3 col-form-label col-form-label">โทรศัพท์เพื่อนบ้านใกล้เคียง
            </label>
            <div class="col-sm-3">
                <input type="text" class="form-control form-control" id="stu_phoneFriend" name="stu_phoneFriend"
                    placeholder="ระบุเบอร์โทรศัพท์" required11 data-inputmask="'mask': '999-999-9999'">
            </div>
        </div>
        <hr>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
        </div>

    </form>
</div>
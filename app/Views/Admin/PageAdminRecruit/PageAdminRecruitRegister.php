<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .student-img-preview {
        width: 100%; max-width: 120px;
        aspect-ratio: 3/4;
        border: 2px dashed #d9dee3;
        border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        background-color: #f5f5f9;
        overflow: hidden;
        margin: 0 auto;
        position: relative;
    }
    .student-img-preview img { width: 100%; height: 100%; object-fit: cover; }
    .doc-preview { width: 100%; height: auto; border-radius: 0.375rem; margin-top: 10px; border: 1px solid #d9dee3; }
    
   
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">ระบบรับสมัคร /</span> ลงทะเบียน Walk-in
        </h4>
    </div>

    <form action="<?= site_url('skjadmin/recruits/save-register') ?>" method="post" enctype="multipart/form-data" id="regisForm" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="recruit_regLevel" value="<?= $level ?>">
        
        <!-- Hidden sports fields -->
        <input type="hidden" name="recruit_agegroup" id="recruit_agegroup_hidden">
        <input type="hidden" name="recruit_sportPosition" id="recruit_sportPosition_hidden">
        <input type="hidden" name="recruit_nickname" id="recruit_nickname_hidden">
        <input type="hidden" name="recruit_weight" id="recruit_weight_hidden">
        <input type="hidden" name="recruit_height" id="recruit_height_hidden">
        <input type="hidden" name="recruit_fatherName" id="recruit_fatherName_hidden">
        <input type="hidden" name="recruit_motherName" id="recruit_motherName_hidden">
        <input type="hidden" name="recruit_fatherJob" id="recruit_fatherJob_hidden">
        <input type="hidden" name="recruit_motherJob" id="recruit_motherJob_hidden">

        <!-- Card 1: เลือกแผนการเรียน -->
        <div class="card mb-4">
            <h5 class="card-header"><i class="bx bx-list-check me-2"></i>ประเภทการสมัครและแผนการเรียน</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">ระดับชั้นที่สมัคร</label>
                        <div class="btn-group w-100 shadow-none">
                            <a href="?level=1" class="btn <?= $level == '1' ? 'btn-primary' : 'btn-outline-primary' ?>">ม.1</a>
                            <a href="?level=4" class="btn <?= $level == '4' ? 'btn-primary' : 'btn-outline-primary' ?>">ม.4</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="recruit_category">ประเภทโควตา <span class="text-danger">*</span></label>
                        <select class="form-select" name="recruit_category" id="recruit_category" required>
                            <option value="">-- เลือกประเภทโควตา --</option>
                            <?php foreach ($quotas as $quota): ?>
                                <?php if ($quota->quota_status == 'on' && strpos($quota->quota_level, (string)$level) !== false): ?>
                                    <option value="<?= $quota->quota_id ?>" data-courses="<?= $quota->quota_course ?>"><?= $quota->quota_explain ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div id="course_section" class="mt-4" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-4" id="rank_container_1">
                            <label class="form-label text-primary fw-bold">เลือกแผนการเรียน อันดับ 1 <span class="text-danger">*</span></label>
                            <select class="form-select course-select shadow-none" name="recruit_tpyeRoom1" id="recruit_tpyeRoom1" required>
                                <option value="">-- เลือกแผนการเรียน --</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="rank_container_2">
                            <label class="form-label">อันดับ 2</label>
                            <select class="form-select course-select shadow-none" name="recruit_tpyeRoom2" id="recruit_tpyeRoom2">
                                <option value="">-- เลือกอันดับ 2 --</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="rank_container_3">
                            <label class="form-label">อันดับ 3</label>
                            <select class="form-select course-select shadow-none" name="recruit_tpyeRoom3" id="recruit_tpyeRoom3">
                                <option value="">-- เลือกอันดับ 3 --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sports Additional Info -->
                    <div id="sports_info_section" class="mt-4 p-4 border rounded bg-white shadow-sm" style="display:none;">
                        <h6 class="fw-bold mb-3"><i class="bx bx-run me-2"></i>ข้อมูลเพิ่มเติม (นักกีฬา)</h6>
                        <div id="age_radio_container" class="mb-3 d-flex flex-wrap gap-2"></div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">ตำแหน่ง/ความสามารถ</label><input type="text" class="form-control sport-field" id="recruit_sportPosition_input" data-field="recruit_sportPosition"></div>
                            <div class="col-md-6"><label class="form-label">ชื่อเล่น</label><input type="text" class="form-control sport-field" id="recruit_nickname_input" data-field="recruit_nickname"></div>
                            <div class="col-md-3"><label class="form-label">น้ำหนัก (กก.)</label><input type="number" step="0.1" class="form-control sport-field" id="recruit_weight_input" data-field="recruit_weight"></div>
                            <div class="col-md-3"><label class="form-label">ส่วนสูง (ซม.)</label><input type="number" step="0.1" class="form-control sport-field" id="recruit_height_input" data-field="recruit_height"></div>
                            <div class="col-md-6"><label class="form-label">ชื่อบิดา</label><input type="text" class="form-control sport-field" id="recruit_fatherName_input" data-field="recruit_fatherName"></div>
                            <div class="col-md-6"><label class="form-label">อาชีพบิดา</label><input type="text" class="form-control sport-field" id="recruit_fatherJob_input" data-field="recruit_fatherJob"></div>
                            <div class="col-md-6"><label class="form-label">ชื่อมารดา</label><input type="text" class="form-control sport-field" id="recruit_motherName_input" data-field="recruit_motherName"></div>
                            <div class="col-md-6"><label class="form-label">อาชีพมารดา</label><input type="text" class="form-control sport-field" id="recruit_motherJob_input" data-field="recruit_motherJob"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: ข้อมูลส่วนตัว -->
        <div class="card mb-4">
            <h5 class="card-header"><i class="bx bx-user me-2"></i>ข้อมูลส่วนตัวนักเรียน</h5>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3 text-center">
                        <div class="student-img-preview mb-2">
                            <img id="preview_img_display" class="img-fluid" src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('recruit_img_input').click()">
                            <i class="bx bx-upload me-1"></i>อัปโหลดรูป
                        </button>
                        <input type="file" id="recruit_img_input" accept="image/*" class="d-none" onchange="handleImageSelect(this)">
                        <input type="hidden" name="recruit_img_cropped" id="recruit_img_cropped">
                        <input type="text" id="recruit_img_validator" style="opacity:0; position:absolute; width:1px; height:1px;" required>
                    </div>
                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-3"><label class="form-label">คำนำหน้า <span class="text-danger">*</span></label><select class="form-select" name="recruit_prefix" required><option value="">เลือก</option><option value="เด็กชาย">เด็กชาย</option><option value="เด็กหญิง">เด็กหญิง</option><option value="นาย">นาย</option><option value="นางสาว">นางสาว</option></select></div>
                            <div class="col-md-4"><label class="form-label">ชื่อ <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_firstName" required></div>
                            <div class="col-md-5"><label class="form-label">นามสกุล <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_lastName" required></div>
                            <div class="col-md-6"><label class="form-label">เลขบัตรประชาชน <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_idCard" id="recruit_idCard" maxlength="17" required></div>
                            <div class="col-md-6">
                                <label class="form-label">วันเกิด (พ.ศ.) <span class="text-danger">*</span></label>
                                <div class="row g-1">
                                    <div class="col-3"><select class="form-select" name="recruit_birthdayD" required><option value="">วัน</option><?php for($i=1;$i<=31;$i++): ?><option value="<?= sprintf('%02d',$i) ?>"><?= $i ?></option><?php endfor; ?></select></div>
                                    <div class="col-5"><select class="form-select" name="recruit_birthdayM" required><option value="">เดือน</option><?php $ms=['','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']; for($i=1;$i<=12;$i++): ?><option value="<?= sprintf('%02d',$i) ?>"><?= $ms[$i] ?></option><?php endfor; ?></select></div>
                                    <div class="col-4"><select class="form-select" name="recruit_birthdayY" required><option value="">ปี</option><?php $cy=date('Y')+543; for($i=$cy-20;$i<=$cy-10;$i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?></select></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">เชื้อชาติ</label>
                                <select class="form-select" name="recruit_race">
                                    <option value="ไทย" selected>ไทย</option>
                                    <option value="พม่า">พม่า</option>
                                    <option value="จีน">จีน</option>
                                    <option value="อื่น ๆ">อื่น ๆ</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">สัญชาติ</label>
                                <select class="form-select" name="recruit_nationality">
                                    <option value="ไทย" selected>ไทย</option>
                                    <option value="ลาว">ลาว</option>
                                    <option value="พม่า">พม่า</option>
                                    <option value="กัมพูชา">กัมพูชา</option>
                                    <option value="อื่น ๆ">อื่น ๆ</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ศาสนา</label>
                                <select class="form-select" name="recruit_religion">
                                    <option value="พุทธ" selected>พุทธ</option>
                                    <option value="อิสลาม">อิสลาม</option>
                                    <option value="คริสต์">คริสต์</option>
                                    <option value="อื่น ๆ">อื่น ๆ</option>
                                </select>
                            </div>
                            <div class="col-md-12"><label class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label><input type="tel" class="form-control" name="recruit_phone" id="recruit_phone" placeholder="0X-XXXX-XXXX" required></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: ที่อยู่และโรงเรียน -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header"><i class="bx bx-home me-2"></i>ที่อยู่ตามทะเบียนบ้าน</h5>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2"><label class="form-label">บ้านเลขที่ <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_homeNumber" required></div>
                            <div class="col-md-2"><label class="form-label">หมู่ที่ <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_homeGroup" required></div>
                            <div class="col-md-5"><label class="form-label">ถนน/ซอย</label><input type="text" class="form-control" name="recruit_homeRoad"></div>
                            <div class="col-md-3"><label class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_homeSubdistrict" id="recruit_homeSubdistrict" required></div>
                            <div class="col-md-4"><label class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_homedistrict" id="recruit_homedistrict" required></div>
                            <div class="col-md-4"><label class="form-label">จังหวัด <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_homeProvince" id="recruit_homeProvince" required></div>
                            <div class="col-md-4"><label class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label><input type="text" class="form-control" name="recruit_homePostcode" id="recruit_homePostcode" required></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header"><i class="bx bx-book-content me-2"></i>ประวัติการศึกษาเดิม</h5>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12"><label class="form-label">ชื่อโรงเรียนเดิม <span class="text-danger">*</span></label>
                                <select class="form-select" id="recruit_oldSchool_select" required style="width:100%;">
                                    <option value="">-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --</option>
                                </select>
                                <input type="hidden" name="recruit_oldSchool" id="recruit_oldSchool" required>
                            </div>
                            <div class="col-md-5"><label class="form-label">อำเภอ (โรงเรียน)</label><input type="text" class="form-control" name="recruit_district" id="recruit_district" readonly></div>
                            <div class="col-md-5"><label class="form-label">จังหวัด (โรงเรียน)</label><input type="text" class="form-control" name="recruit_province" id="recruit_province" readonly></div>
                            <div class="col-md-2"><label class="form-label">เกรดเฉลี่ย</label><input type="number" step="0.01" min="0" max="4" class="form-control" name="recruit_grade" ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: เอกสารหลักฐาน -->
        <div class="card mb-4">
            <h5 class="card-header"><i class="bx bx-file me-2"></i>เอกสารหลักฐาน (อัปโหลดไฟล์ภาพ)</h5>
            <div class="card-body">
                <div class="row g-4 text-center">
                    <div class="col-md-4 px-3 py-2 border rounded">
                        <label class="form-label d-block mb-2 fw-bold">1. ปพ.1 (หน้า)</label>
                        <input class="form-control form-control-sm mb-2" type="file" name="recruit_certificateEdu" accept="image/*" required onchange="previewImage(this,'p_cert1','p_cert1_cont')">
                        <div id="p_cert1_cont" class="d-none"><img id="p_cert1" class="doc-preview img-fluid"></div>
                    </div>
                    <div class="col-md-4 px-3 py-2 border rounded">
                        <label class="form-label d-block mb-2 fw-bold">2. ปพ.1 (หลัง)</label>
                        <input class="form-control form-control-sm mb-2" type="file" name="recruit_certificateEduB" accept="image/*" required onchange="previewImage(this,'p_cert2','p_cert2_cont')">
                        <div id="p_cert2_cont" class="d-none"><img id="p_cert2" class="doc-preview img-fluid"></div>
                    </div>
                    <div class="col-md-4 px-3 py-2 border rounded">
                        <label class="form-label d-block mb-2 fw-bold">3. บัตรประชาชน</label>
                        <input class="form-control form-control-sm mb-2" type="file" name="recruit_copyidCard" accept="image/*" required onchange="previewImage(this,'p_id','p_id_cont')">
                        <div id="p_id_cont" class="d-none"><img id="p_id" class="doc-preview img-fluid"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4 mb-5">
            <div class="col-12">
                <div class="card bg-lighter">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <p class="mb-0 text-muted"><i class="bx bx-info-circle me-1"></i> กรุณาตรวจสอบความถูกต้องของข้อมูลก่อนการบันทึก</p>
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow" id="submitBtn">
                            <i class="bx bx-check-circle me-1"></i> บันทึกข้อมูล Walk-in
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal สำหรับ Crop รูป -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ปรับสัดส่วนรูปถ่ายนักเรียน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background:#000; min-height:400px;">
                <img id="image_to_crop" class="img-fluid" src="" style="max-width:100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="crop_btn">ใช้รูปภาพนี้</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const coursesData = <?= json_encode($courses) ?>;
    let cropper;

    $(document).ready(function() {
        $.Thailand({
            $district: $('#recruit_homeSubdistrict'),
            $amphoe: $('#recruit_homedistrict'),
            $province: $('#recruit_homeProvince'),
            $zipcode: $('#recruit_homePostcode')
        });
        
        // Initialize Select2 with BS5 Theme - Exactly like User Page
        $('#recruit_category, .course-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: function() {
                return $(this).find('option:first').text();
            },
            allowClear: true
        });

        $('#recruit_oldSchool_select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --',
            ajax: {
                url: '<?= base_url('new-admission/school-search') ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var quotaText = $('#recruit_category option:selected').text();
                    var isServiceArea = quotaText.includes('เขตพื้นที่บริการ');
                    return {
                        q: params.term,
                        is_service_area: isServiceArea
                    };
                },
                processResults: function (data) {
                    return { results: data.results };
                },
                cache: true
            }
        });

        $('#recruit_oldSchool_select').on('select2:select', function (e) {
            var data = e.params.data;
            $('#recruit_oldSchool').val(data.text);
            $('#recruit_district').val(data.amphur);
            $('#recruit_province').val(data.province);
            
            $('#recruit_oldSchool').trigger('change');
            $('#recruit_district').trigger('change');
            $('#recruit_province').trigger('change');
        });

        $('#regisForm').on('submit', function(e) {
            e.preventDefault();
            if(!this.checkValidity()) {
                this.classList.add('was-validated');
                
                // --- เพิ่มส่วนนี้เพื่อ Debug ---
                console.warn('❌ ฟอร์มไม่ผ่านการตรวจสอบ! ช่องที่ยังไม่ถูกต้อง:');
                $(this).find(':invalid').each(function() {
                    let label = $(this).siblings('label').text() || $(this).closest('.col-md-*').find('label').first().text() || 'ไม่พบ Label';
                    console.log({
                        'Field ID/Name': $(this).attr('id') || $(this).attr('name'),
                        'Label': label.replace('*', '').trim(),
                        'Reason': this.validationMessage
                    });
                });
                console.log('-----------------------------------');
                // -------------------------

                Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลในช่องที่จำเป็นให้ครบถ้วน', 'warning');
                return;
            }

            Swal.fire({
                title: 'ยืนยันการบันทึกข้อมูล?',
                text: "กรุณาตรวจสอบความถูกต้องก่อนกดบันทึก",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#71dd37'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.sport-field').each(function() {
                        const f = $(this).data('field');
                        if(f) $('#'+f+'_hidden').val($(this).val());
                    });
                    const formData = new FormData(this);
                    Swal.fire({ title: 'กำลังบันทึกข้อมูล...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: (res) => {
                            if(res.status === 'success') {
                                Swal.fire('สำเร็จ', res.message, 'success').then(() => window.location.href = res.redirect_url);
                            } else {
                                Swal.fire('ผิดพลาด', res.message, 'error');
                            }
                        }
                    });
                }
            });
        });

        $('#recruit_category').on('change', function() {
            const opt = $(this).find(':selected');
            const ids = opt.data('courses') ? opt.data('courses').split('|') : [];
            const isSport = opt.text().includes('กีฬา');

            $('.course-select').each(function(i) {
                $(this).empty().append(`<option value="" selected disabled>เลือกแผนการเรียน</option>`);
                coursesData.forEach(c => {
                    if(ids.includes(c.course_id.toString())) {
                        $(this).append(`<option value="${c.course_id}" data-age="${c.course_age}">${c.course_initials} (${c.course_branch})</option>`);
                    }
                });
            });

            if(isSport) {
                $('#rank_container_2, #rank_container_3').hide();
                $('#recruit_tpyeRoom2, #recruit_tpyeRoom3').val('');
            } else {
                $('#rank_container_2, #rank_container_3').show();
            }
            $('#course_section').slideDown();
            $('#sports_info_section').hide();
        });

        $('#recruit_tpyeRoom1').on('change', function() {
            const age = $(this).find(':selected').data('age');
            if(age) {
                const ages = age.toString().split(',').map(a => a.trim()).filter(a => a);
                const cont = $('#age_radio_container').empty();
                ages.forEach(a => {
                    cont.append(`<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="recruit_agegroup_radio" id="age_${a}" value="${a}" required><label class="form-check-label" for="age_${a}">รุ่น ${a} ปี</label></div>`);
                });
                $('#sports_info_section').slideDown();
            } else {
                $('#sports_info_section').hide();
            }
        });

        $(document).on('change', 'input[name="recruit_agegroup_radio"]', function() {
            $('#recruit_agegroup_hidden').val($(this).val());
        });
    });

    function handleImageSelect(i) {
        if(i.files && i.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $('#image_to_crop').attr('src', e.target.result);
                new bootstrap.Modal('#cropModal').show();
            };
            reader.readAsDataURL(i.files[0]);
        }
    }

    $('#cropModal').on('shown.bs.modal', function() {
        cropper = new Cropper(document.getElementById('image_to_crop'), { aspectRatio: 3/4, viewMode: 1 });
    }).on('hidden.bs.modal', function() {
        if(cropper) cropper.destroy();
    });

    $('#crop_btn').on('click', function() {
        const b64 = cropper.getCroppedCanvas({ width: 450, height: 600 }).toDataURL('image/jpeg');
        $('#preview_img_display').attr('src', b64);
        $('#recruit_img_cropped').val(b64);
        $('#recruit_img_validator').val('ok');
        bootstrap.Modal.getInstance('#cropModal').hide();
    });

    function previewImage(i, p, c) {
        if(i.files && i.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $('#'+p).attr('src', e.target.result);
                $('#'+c).removeClass('d-none');
            };
            reader.readAsDataURL(i.files[0]);
        }
    }

    $('#recruit_phone').on('input', function() {
        let v = $(this).val().replace(/\D/g, '').substring(0, 10);
        if (v.length > 6) {
            $(this).val(v.substring(0, 3) + '-' + v.substring(3, 6) + '-' + v.substring(6, 10));
        } else if (v.length > 3) {
            $(this).val(v.substring(0, 3) + '-' + v.substring(3, 6));
        } else {
            $(this).val(v);
        }
    });

    function checkThaiID(id) {
        if (id.length != 13) return false;
        let sum = 0;
        for (let i = 0; i < 12; i++) {
            sum += parseFloat(id.charAt(i)) * (13 - i);
        }
        if ((11 - (sum % 11)) % 10 != parseFloat(id.charAt(12))) return false;
        return true;
    }

    $('#recruit_idCard').on('input', function() {
        let v = $(this).val().replace(/\D/g, '').substring(0, 13);
        
        // Validation check for ID card duplicates
        if (v.length === 13) {
            if (!checkThaiID(v)) {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
                $(this).after('<div class="invalid-feedback">เลขบัตรประชาชนไม่ถูกต้องตามหลักการตรวจสอบ</div>');
            } else {
                $(this).removeClass('is-invalid');
                // Check duplicate via AJAX
                $.post('<?= site_url('skjadmin/recruits/check-id-ajax') ?>', { idCard: v }, function(res) {
                    if (res.exists) {
                        $('#recruit_idCard').addClass('is-invalid');
                        $('#recruit_idCard').siblings('.invalid-feedback').remove();
                        $('#recruit_idCard').after('<div class="invalid-feedback">เลขบัตรประชาชนนี้มีการลงทะเบียนแล้ว</div>');
                    } else {
                        $('#recruit_idCard').removeClass('is-invalid');
                    }
                });
            }
        } else {
            $(this).removeClass('is-invalid');
        }

        let x = v.match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
        this.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
    });
</script>
<?= $this->endSection() ?>

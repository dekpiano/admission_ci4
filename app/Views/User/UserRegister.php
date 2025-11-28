<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    /* Custom Wizard CSS */
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
        padding: 0 20px;
    }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 3px;
        background: #e9ecef;
        z-index: 0;
        margin: 0 40px;
    }
    .step {
        position: relative;
        z-index: 1;
        text-align: center;
        width: 20%;
    }
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e9ecef;
        color: #a1acb8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        transition: all 0.3s;
        font-size: 1.2rem;
    }
    .step.active .step-icon {
        border-color: #74b9ff;
        background: #74b9ff;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(116, 185, 255, 0.2);
    }
    .step.completed .step-icon {
        border-color: #71dd37;
        background: #71dd37;
        color: #fff;
    }
    .step-label {
        font-size: 0.85rem;
        color: #697a8d;
        font-weight: 500;
        display: block;
    }
    .step.active .step-label {
        color: #74b9ff;
        font-weight: 700;
    }
    .step.completed .step-label {
        color: #71dd37;
    }
    .form-step {
        display: none;
        animation: fadeIn 0.5s;
    }
    .form-step.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Spacing & Typography Balance */
    .card-body {
        padding: 2.5rem; /* More breathing room on desktop */
    }

    .divider {
        margin: 2rem 0 1.5rem 0;
    }

    .form-label {
        margin-bottom: 0.5rem;
        color: #566a7f; /* Soft text color for readability */
        font-weight: 500;
    }

    /* Consistent Row Spacing */
    .row.mb-3 {
        margin-bottom: 1.5rem !important; /* Increase gap between rows for better separation */
    }
    
    /* Input Group Styling */
    .input-group-text {
        background-color: #f5f7f9;
        border-color: #d9dee3;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 576px) {
        .step-indicator {
            padding: 0;
            margin-bottom: 2rem;
        }
        .step-indicator::before {
            top: 15px;
            margin: 0 10px;
        }
        .step {
            width: auto;
            flex: 1;
        }
        .step-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
            margin-bottom: 5px;
            border-width: 2px;
        }
        .step-label {
            display: none;
        }
        .step.active .step-label {
            display: block;
            font-size: 0.8rem;
            white-space: nowrap;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: max-content;
            bottom: -25px;
        }
        
        /* Refined Mobile Spacing */
        .card-body {
            padding: 1.5rem !important; /* Balanced mobile padding */
        }
        
        .divider {
            margin: 1.5rem 0 1rem 0;
        }
        
        .row.mb-3 {
            margin-bottom: 1.25rem !important; /* Optimal spacing for mobile scrolling */
        }
        
        /* Button Adjustments */
        .row.justify-content-between.mt-4 {
            flex-direction: column-reverse;
            gap: 12px;
            margin-top: 1.5rem !important;
        }
        .row.justify-content-between.mt-4 .col-auto {
            width: 100%;
        }
        #prevBtn, #nextBtn, #submitBtn {
            width: 100%;
            padding: 0.8rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        
        /* Mobile Friendly Radio Buttons for Age Selection */
        .form-check-inline {
            display: flex;
            align-items: center;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 50rem;
            border: 1px solid #d9dee3;
        }
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0;
            margin-right: 0.5rem;
        }
        .form-check-label {
            font-size: 1rem;
            cursor: pointer;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 text-primary"><i class='bx bx-edit me-2'></i>แบบฟอร์มสมัครเรียน ชั้นมัธยมศึกษาปีที่ <?= $level ?> (Registration Form)</h5>
                <small class="text-muted">กรุณากรอกข้อมูลให้ครบถ้วน</small>
            </div>
            <div class="card-body pt-4">
                
                <!-- Step Indicators -->
                <div class="step-indicator">
                    <div class="step active" id="step-indicator-1">
                        <div class="step-icon"><i class='bx bx-category'></i></div>
                        <span class="step-label">แผนการเรียน</span>
                    </div>
                    <div class="step" id="step-indicator-2">
                        <div class="step-icon"><i class='bx bx-user'></i></div>
                        <span class="step-label">ข้อมูลส่วนตัว</span>
                    </div>
                    <div class="step" id="step-indicator-3">
                        <div class="step-icon"><i class='bx bx-home'></i></div>
                        <span class="step-label">ที่อยู่</span>
                    </div>
                    <div class="step" id="step-indicator-4">
                        <div class="step-icon"><i class='bx bx-book'></i></div>
                        <span class="step-label">การศึกษาเดิม</span>
                    </div>
                    <div class="step" id="step-indicator-5">
                        <div class="step-icon"><i class='bx bx-file'></i></div>
                        <span class="step-label">เอกสาร</span>
                    </div>
                </div>

                <form action="<?= base_url('new-admission/save') ?>" method="post" enctype="multipart/form-data" id="regisForm" class="needs-validation" novalidate>
                    <input type="hidden" name="recruit_regLevel" value="<?= $level ?>">

                    <!-- Step 1: Quota & Program -->
                    <div class="form-step active" id="step-1">
                        <div class="divider text-start">
                            <div class="divider-text text-primary fw-bold fs-5">1. เลือกประเภทโควตาและแผนการเรียน</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_category" class="form-label">ประเภทโควตา <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-star'></i></span>
                                    <select class="form-select" name="recruit_category" id="recruit_category" required>
                                        <option value="" disabled selected>-- กรุณาเลือกประเภทโควตา --</option>
                                        <?php foreach($quotas as $quota): ?>
                                            <?php if($quota->quota_status == 'on' && strpos($quota->quota_level, (string)$level) !== false): ?>
                                                <option value="<?= $quota->quota_key ?>" data-courses="<?= $quota->quota_course ?>">
                                                    <?= $quota->quota_explain ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Service Area School Search (Hidden by default) -->
                        <div class="row mb-3" id="service_area_section" style="display:none;">
                            <div class="col-sm-12">
                                <div class="alert alert-info">
                                    <i class='bx bx-info-circle me-1'></i> สำหรับโควตาเขตพื้นที่บริการ กรุณาระบุโรงเรียนเดิมของท่านเพื่อตรวจสอบสิทธิ์
                                </div>
                                <label for="service_area_school_search" class="form-label">ค้นหาโรงเรียนเดิม (เฉพาะในเขตพื้นที่บริการ) <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bxs-school'></i></span>
                                    <select class="form-select" id="service_area_school_search" style="width: 100%;">
                                        <option value="">-- พิมพ์ชื่อโรงเรียนเพื่อค้นหา --</option>
                                    </select>
                                </div>
                                <div class="form-text text-danger">* ต้องเลือกโรงเรียนจากรายการที่ปรากฏเท่านั้น หากไม่พบแสดงว่าไม่อยู่ในเขตพื้นที่บริการ</div>
                            </div>
                        </div>

                        <div class="row mb-3" id="course_section" style="display:none;">
                            <div class="col-sm-12">
                                <label class="form-label">เลือกแผนการเรียน (เลือกได้สูงสุด 3 อันดับ) <span class="text-danger">*</span></label>
                                
                                <div class="mb-2 input-group flex-nowrap">
                                    <span class="input-group-text" style="min-width: 80px;">อันดับ 1</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom1" id="recruit_tpyeRoom1" required>
                                        <option value="" selected disabled>-- เลือกอันดับ 1 --</option>
                                    </select>
                                </div>

                                <div class="mb-2 input-group flex-nowrap">
                                    <span class="input-group-text" style="min-width: 80px;">อันดับ 2</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom2" id="recruit_tpyeRoom2">
                                        <option value="" selected disabled>-- เลือกอันดับ 2 --</option>
                                    </select>
                                </div>

                                <div class="mb-2 input-group flex-nowrap">
                                    <span class="input-group-text" style="min-width: 80px;">อันดับ 3</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom3" id="recruit_tpyeRoom3">
                                        <option value="" selected disabled>-- เลือกอันดับ 3 --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Student Info -->
                    <div class="form-step" id="step-2">
                        <div class="divider text-start">
                            <div class="divider-text text-primary fw-bold fs-5">2. ข้อมูลส่วนตัวนักเรียน</div>
                        </div>

                        <!-- Student Photo Upload (Top Center) -->
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-4 text-center">
                                <label class="form-label fw-bold">รูปถ่ายนักเรียน (ชุดนักเรียน) <span class="text-danger">*</span></label>
                                <div class="card shadow-sm">
                                    <div class="card-body text-center p-3">
                                        <div class="mb-3">
                                            <img id="preview_img_display" src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="รูปถ่ายนักเรียน" class="d-block rounded mx-auto" style="width: 150px; height: 200px; object-fit: contain; border: 2px dashed #d9dee3;">
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="document.getElementById('recruit_img_input').click()">
                                            <i class='bx bx-camera me-1'></i> อัปโหลดรูปถ่าย
                                        </button>
                                        <input type="file" id="recruit_img_input" accept="image/*" class="d-none" onchange="handleImageSelect(this)">
                                        <input type="hidden" name="recruit_img_cropped" id="recruit_img_cropped">
                                        <!-- Hidden input for validation -->
                                        <input type="text" id="recruit_img_validator" name="recruit_img_validator" style="opacity: 0; position: absolute; width: 1px; height: 1px;" required>
                                    </div>
                                </div>
                                <div class="form-text mt-2">รูปถ่ายหน้าตรง ชุดนักเรียน ขนาด 1.5 นิ้ว</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <label for="recruit_prefix" class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_prefix" id="recruit_prefix" required>
                                    <option value="">เลือก</option>
                                    <option value="เด็กชาย">เด็กชาย</option>
                                    <option value="เด็กหญิง">เด็กหญิง</option>
                                    <option value="นาย">นาย</option>
                                    <option value="นางสาว">นางสาว</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="recruit_firstName" class="form-label">ชื่อ <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                                    <input type="text" class="form-control" name="recruit_firstName" id="recruit_firstName" placeholder="ชื่อจริง" required>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <label for="recruit_lastName" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                                    <input type="text" class="form-control" name="recruit_lastName" id="recruit_lastName" placeholder="นามสกุล" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_idCard" class="form-label">เลขบัตรประชาชน (13 หลัก) <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-id-card'></i></span>
                                    <input type="text" class="form-control" name="recruit_idCard" id="recruit_idCard" maxlength="13" required placeholder="เลขบัตรประชาชน 13 หลัก" value="<?= isset($preCheckIdCard) ? $preCheckIdCard : '' ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="form-label mb-2">วันเดือนปีเกิด <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-select" name="recruit_birthdayD" id="recruit_birthdayD" required>
                                    <option value="">วัน</option>
                                    <?php for($i=1; $i<=31; $i++): ?>
                                        <option value="<?= sprintf('%02d', $i) ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <select class="form-select" name="recruit_birthdayM" id="recruit_birthdayM" required>
                                    <option value="">เดือน</option>
                                    <option value="01">มกราคม</option>
                                    <option value="02">กุมภาพันธ์</option>
                                    <option value="03">มีนาคม</option>
                                    <option value="04">เมษายน</option>
                                    <option value="05">พฤษภาคม</option>
                                    <option value="06">มิถุนายน</option>
                                    <option value="07">กรกฎาคม</option>
                                    <option value="08">สิงหาคม</option>
                                    <option value="09">กันยายน</option>
                                    <option value="10">ตุลาคม</option>
                                    <option value="11">พฤศจิกายน</option>
                                    <option value="12">ธันวาคม</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select class="form-select" name="recruit_birthdayY" id="recruit_birthdayY" required>
                                    <option value="">ปี (พ.ศ.)</option>
                                    <?php $curYear = date('Y')+543; for($i=$curYear-20; $i<=$curYear-10; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <label for="recruit_race" class="form-label">เชื้อชาติ <span class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_race" id="recruit_race" required>
                                    <option value="ไทย" selected>ไทย</option>
                                    <option value="จีน">จีน</option>
                                    <option value="ญี่ปุ่น">ญี่ปุ่น</option>
                                    <option value="เกาหลี">เกาหลี</option>
                                    <option value="เวียดนาม">เวียดนาม</option>
                                    <option value="ลาว">ลาว</option>
                                    <option value="กัมพูชา">กัมพูชา</option>
                                    <option value="พม่า">พม่า</option>
                                    <option value="มาเลเซีย">มาเลเซีย</option>
                                    <option value="อินเดีย">อินเดีย</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="recruit_nationality" class="form-label">สัญชาติ <span class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_nationality" id="recruit_nationality" required>
                                    <option value="ไทย" selected>ไทย</option>
                                    <option value="จีน">จีน</option>
                                    <option value="ญี่ปุ่น">ญี่ปุ่น</option>
                                    <option value="เกาหลี">เกาหลี</option>
                                    <option value="เวียดนาม">เวียดนาม</option>
                                    <option value="ลาว">ลาว</option>
                                    <option value="กัมพูชา">กัมพูชา</option>
                                    <option value="พม่า">พม่า</option>
                                    <option value="มาเลเซีย">มาเลเซีย</option>
                                    <option value="อินเดีย">อินเดีย</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="recruit_religion" class="form-label">ศาสนา <span class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_religion" id="recruit_religion" required>
                                    <option value="พุทธ" selected>พุทธ</option>
                                    <option value="อิสลาม">อิสลาม</option>
                                    <option value="คริสต์">คริสต์</option>
                                    <option value="ฮินดู">ฮินดู</option>
                                    <option value="ซิกข์">ซิกข์</option>
                                    <option value="ไม่นับถือศาสนา">ไม่นับถือศาสนา</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_phone" class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-phone'></i></span>
                                    <input type="tel" class="form-control" name="recruit_phone" id="recruit_phone" placeholder="08xxxxxxxx" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Address -->
                    <div class="form-step" id="step-3">
                        <div class="divider text-start">
                            <div class="divider-text text-primary fw-bold fs-5">3. ที่อยู่ตามทะเบียนบ้าน</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homeNumber" class="form-label">บ้านเลขที่ <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-home'></i></span>
                                    <input type="text" class="form-control" name="recruit_homeNumber" id="recruit_homeNumber" placeholder="บ้านเลขที่" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeGroup" class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeGroup" id="recruit_homeGroup" placeholder="หมู่ที่" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homeRoad" class="form-label">ถนน</label>
                                <input type="text" class="form-control" name="recruit_homeRoad" id="recruit_homeRoad" placeholder="ถนน">
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeSubdistrict" class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeSubdistrict" id="recruit_homeSubdistrict" placeholder="ตำบล/แขวง" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homedistrict" class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homedistrict" id="recruit_homedistrict" placeholder="อำเภอ/เขต" required>
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeProvince" class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeProvince" id="recruit_homeProvince" placeholder="จังหวัด" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                             <div class="col-md-6">
                                <label for="recruit_homePostcode" class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-map-pin'></i></span>
                                    <input type="text" class="form-control" name="recruit_homePostcode" id="recruit_homePostcode" placeholder="รหัสไปรษณีย์" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Old School -->
                    <div class="form-step" id="step-4">
                        <div class="divider text-start">
                            <div class="divider-text text-primary fw-bold fs-5">4. ข้อมูลการศึกษาเดิม</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_oldSchool_select" class="form-label">ค้นหาโรงเรียนเดิม <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bxs-school'></i></span>
                                    <?php if(isset($preCheckOldSchool) && !empty($preCheckOldSchool)): ?>
                                        <input type="text" class="form-control" value="<?= $preCheckOldSchool ?>" readonly>
                                        <input type="hidden" name="recruit_oldSchool" id="recruit_oldSchool" value="<?= $preCheckOldSchool ?>">
                                    <?php else: ?>
                                        <select class="form-select" id="recruit_oldSchool_select" required>
                                            <option value="">-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --</option>
                                        </select>
                                        <input type="hidden" name="recruit_oldSchool" id="recruit_oldSchool" required>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label for="recruit_district" class="form-label">อำเภอที่ตั้งโรงเรียน <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_district" id="recruit_district" placeholder="อำเภอ" required value="<?= isset($preCheckDistrict) ? $preCheckDistrict : '' ?>" <?= isset($preCheckDistrict) && !empty($preCheckDistrict) ? 'readonly' : '' ?>>
                            </div>
                            <div class="col-sm-6">
                                <label for="recruit_province" class="form-label">จังหวัดที่ตั้งโรงเรียน <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_province" id="recruit_province" placeholder="จังหวัด" required value="<?= isset($preCheckProvince) ? $preCheckProvince : '' ?>" <?= isset($preCheckProvince) && !empty($preCheckProvince) ? 'readonly' : '' ?>>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_grade" class="form-label">เกรดเฉลี่ยสะสม (GPAX) <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-bar-chart-alt-2'></i></span>
                                    <input type="number" step="0.01" min="0" max="4.00" class="form-control" name="recruit_grade" id="recruit_grade" placeholder="เช่น 3.50" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Documents -->
                    <div class="form-step" id="step-5">
                        <div class="divider text-start">
                            <div class="divider-text text-primary fw-bold fs-5">5. เอกสารหลักฐาน</div>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <i class='bx bx-info-circle me-1'></i> กรุณาอัปโหลดไฟล์ภาพ (.jpg, .png) หรือ PDF ขนาดไม่เกิน 2MB
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="recruit_certificateEdu" class="form-label">ปพ.1 (หน้า) <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="recruit_certificateEdu" name="recruit_certificateEdu" accept="image/*,.pdf" required onchange="previewImage(this, 'preview_certificate')">
                                <div class="mt-2 text-center">
                                    <img id="preview_certificate" src="#" alt="ตัวอย่าง ปพ.1 (หน้า)" class="img-thumbnail d-none" style="max-height: 200px;">
                                    <p id="preview_certificate_name" class="d-none text-muted small"></p>
                                </div>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="recruit_certificateEduB" class="form-label">ปพ.1 (หลัง) <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="recruit_certificateEduB" name="recruit_certificateEduB" accept="image/*,.pdf" required onchange="previewImage(this, 'preview_certificateB')">
                                <div class="mt-2 text-center">
                                    <img id="preview_certificateB" src="#" alt="ตัวอย่าง ปพ.1 (หลัง)" class="img-thumbnail d-none" style="max-height: 200px;">
                                    <p id="preview_certificateB_name" class="d-none text-muted small"></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recruit_copyidCard" class="form-label">สำเนาบัตรประชาชน <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="recruit_copyidCard" name="recruit_copyidCard" accept="image/*,.pdf" required onchange="previewImage(this, 'preview_idcard')">
                                <div class="mt-2 text-center">
                                    <img id="preview_idcard" src="#" alt="ตัวอย่างบัตรประชาชน" class="img-thumbnail d-none" style="max-height: 200px;">
                                    <p id="preview_idcard_name" class="d-none text-muted small"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="row justify-content-between mt-4">
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-secondary" id="prevBtn" style="display:none;">
                                <i class='bx bx-chevron-left'></i> ย้อนกลับ
                            </button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" id="nextBtn">
                                ถัดไป <i class='bx bx-chevron-right'></i>
                            </button>
                            <button type="button" class="btn btn-success" id="submitBtn" style="display:none;" onclick="if(validateStep(currentStep)) { showConfirmationModal(); } else { Swal.fire({ icon: 'warning', title: 'ข้อมูลยังไม่ครบถ้วน', text: 'กรุณาตรวจสอบข้อมูลในขั้นตอนสุดท้ายให้ครบถ้วนก่อนยืนยัน', confirmButtonText: 'ตกลง' }); }">
                                <i class='bx bx-check-circle'></i> ยืนยันการสมัครเรียน
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Crop Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ปรับแต่งรูปถ่าย (Crop Image)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container" style="max-height: 500px;">
                    <img id="image_to_crop" src="" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="crop_btn">ยืนยันการตัดรูป</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Thailand Address Auto Complete Dependencies -->
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>

<!-- Cropper.js Dependencies -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        $('#recruit_oldSchool_select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- พิมพ์เพื่อค้นหาชื่อโรงเรียน --',
            ajax: {
                url: '<?= base_url('new-admission/school-search') ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var selectedQuotaText = $('#recruit_category option:selected').text();
                    var isServiceArea = selectedQuotaText.includes('เขตพื้นที่บริการ');
                    return {
                        q: params.term, // search term
                        is_service_area: isServiceArea
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        $('#recruit_oldSchool_select').on('select2:select', function (e) {
            var data = e.params.data;
            $('#recruit_oldSchool').val(data.text); // Set hidden input with school name
            $('#recruit_district').val(data.amphur); // Set district
            $('#recruit_province').val(data.province); // Set province

            // Trigger change event for validation or other listeners if any
            $('#recruit_oldSchool').trigger('change');
            $('#recruit_district').trigger('change');
            $('#recruit_province').trigger('change');
        });

        $('#confirmSubmitBtn').on('click', function() {
            $('#regisForm').submit();
        });

        $('#submitBtn').on('click', function(e) {
            e.preventDefault(); // Prevent default button action
            if (validateStep(currentStep)) {
                showConfirmationModal(confirmModal);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'ข้อมูลยังไม่ครบถ้วน',
                    text: 'กรุณาตรวจสอบข้อมูลในขั้นตอนสุดท้ายให้ครบถ้วนก่อนยืนยัน',
                    confirmButtonText: 'ตกลง'
                });
            }
        });
    });

    // Image Cropping Logic
    let cropper;
    const imageToCrop = document.getElementById('image_to_crop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    function handleImageSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                cropModal.show();
            }
            reader.readAsDataURL(file);
            
            input.value = ''; 
        }
    }

    document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 3 / 4, 
            viewMode: 1,
            autoCropArea: 1,
        });
    });

    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    document.getElementById('crop_btn').addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 450,
                height: 600,
            });

            const croppedImage = canvas.toDataURL('image/jpeg');
            document.getElementById('preview_img_display').src = croppedImage;
            document.getElementById('recruit_img_cropped').value = croppedImage;
            document.getElementById('recruit_img_validator').value = 'uploaded';
            document.getElementById('recruit_img_validator').classList.remove('is-invalid');
            document.getElementById('recruit_img_validator').classList.add('is-valid');
            cropModal.hide();
        }
    });

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const namePreview = document.getElementById(previewId + '_name');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            if (file.type.match('image.*')) {
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if(namePreview) namePreview.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
                if(namePreview) {
                    namePreview.textContent = 'ไฟล์ที่เลือก: ' + file.name;
                    namePreview.classList.remove('d-none');
                }
            }
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
            if(namePreview) namePreview.classList.add('d-none');
        }
    }

    $.Thailand({
        $district: $('#recruit_homeSubdistrict'), 
        $amphoe: $('#recruit_homedistrict'),
        $province: $('#recruit_homeProvince'),
        $zipcode: $('#recruit_homePostcode'),
    });

    const coursesData = <?= json_encode($courses) ?>;
    
    // Initialize Service Area School Search (Step 1)
    $('#service_area_school_search').select2({
        theme: 'bootstrap-5',
        placeholder: '-- พิมพ์ชื่อโรงเรียนเพื่อค้นหา --',
        allowClear: true,
        width: '100%',
        ajax: {
            url: '<?= base_url('new-admission/school-search') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    is_service_area: true // Force service area search
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        }
    });

    // Handle Service Area School Selection
    $('#service_area_school_search').on('select2:select', function (e) {
        var data = e.params.data;
        
        // Auto-fill Step 4 fields
        var step4Select = $('#recruit_oldSchool_select');
        if (step4Select.find("option[value='" + data.id + "']").length) {
            step4Select.val(data.id).trigger('change');
        } else { 
            var newOption = new Option(data.text, data.id, true, true);
            step4Select.append(newOption).trigger('change');
        }

        $('#recruit_oldSchool').val(data.text);
        $('#recruit_district').val(data.amphur);
        $('#recruit_province').val(data.province);
        
        // Show Course Section after school is selected (if quota is service area)
        const quotaText = $('#recruit_category option:selected').text();
        if (quotaText.includes('เขตพื้นที่บริการ')) {
             $('#course_section').slideDown();
        }
    });

    document.getElementById('recruit_category').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const quotaText = selectedOption.text;
        const allowedCourses = selectedOption.getAttribute('data-courses').split('|');
        
        // Handle Service Area Logic
        const serviceAreaSection = document.getElementById('service_area_section');
        const serviceAreaSearch = document.getElementById('service_area_school_search');
        const courseSection = document.getElementById('course_section');
        
        // Reset Step 4 fields state first
        $('#recruit_oldSchool').val('');
        $('#recruit_district').val('');
        $('#recruit_province').val('');
        $('#recruit_district').prop('readonly', false);
        $('#recruit_province').prop('readonly', false);
        $('#recruit_oldSchool_select').val(null).trigger('change');
        $('#recruit_oldSchool_select').prop('disabled', false);
        // If it was replaced by a text input (from pre-check), we might need to handle that, 
        // but here we assume standard flow or that pre-check data is already handled on load.
        // For simplicity, we target the select2.

        if (quotaText.includes('เขตพื้นที่บริการ')) {
            // --- Service Area Quota ---
            serviceAreaSection.style.display = 'block';
            serviceAreaSearch.setAttribute('data-required', 'true'); 
            
            if (!$('#service_area_school_search').val()) {
                courseSection.style.display = 'none';
            } else {
                courseSection.style.display = 'block';
            }

        } else if (quotaText.includes('โรงเรียนเดิม')) {
            // --- Old School Quota (M.3 Original School) ---
            serviceAreaSection.style.display = 'none';
            serviceAreaSearch.removeAttribute('data-required');
            
            // Auto-fill Step 4 with Fixed School
            const fixedSchoolName = "สวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";
            const fixedDistrict = "เมืองนครสวรรค์";
            const fixedProvince = "นครสวรรค์";

            // Set Hidden Fields
            $('#recruit_oldSchool').val(fixedSchoolName);
            $('#recruit_district').val(fixedDistrict);
            $('#recruit_province').val(fixedProvince);

            // Set Visual Fields (Step 4)
            // Update Select2
            var step4Select = $('#recruit_oldSchool_select');
            if (step4Select.find("option[value='" + fixedSchoolName + "']").length) {
                step4Select.val(fixedSchoolName).trigger('change');
            } else { 
                var newOption = new Option(fixedSchoolName, fixedSchoolName, true, true);
                step4Select.append(newOption).trigger('change');
            }
            step4Select.prop('disabled', true); // Lock the select

            // Lock District and Province inputs
            $('#recruit_district').val(fixedDistrict).prop('readonly', true);
            $('#recruit_province').val(fixedProvince).prop('readonly', true);

            // Show Course Section immediately
            courseSection.style.display = 'block';

        } else {
            // --- Other Quotas ---
            serviceAreaSection.style.display = 'none';
            serviceAreaSearch.removeAttribute('data-required');
            $('#service_area_school_search').val(null).trigger('change'); 
            
            // Show course section immediately
            courseSection.style.display = 'block';
        }

        const courseSelects = [
            document.getElementById('recruit_tpyeRoom1'),
            document.getElementById('recruit_tpyeRoom2'),
            document.getElementById('recruit_tpyeRoom3')
        ];
        // Get the containers for each select to hide/show them
        const courseContainers = [
            courseSelects[0].closest('.input-group'),
            courseSelects[1].closest('.input-group'),
            courseSelects[2].closest('.input-group')
        ];
        

        const courseLabel = courseSection.querySelector('label.form-label'); // Get the label element
        
        // Create container for age radio buttons if it doesn't exist
        let ageRadioContainer = document.getElementById('age_radio_container');
        if (!ageRadioContainer) {
            ageRadioContainer = document.createElement('div');
            ageRadioContainer.id = 'age_radio_container';
            ageRadioContainer.className = 'mb-3';
            ageRadioContainer.style.display = 'none';
            courseSection.appendChild(ageRadioContainer);
        }
        
        // Create hidden input for recruit_agegroup if it doesn't exist
        let ageGroupInput = document.querySelector('input[name="recruit_agegroup"]');
        if (!ageGroupInput) {
            ageGroupInput = document.createElement('input');
            ageGroupInput.type = 'hidden';
            ageGroupInput.name = 'recruit_agegroup';
            document.getElementById('regisForm').appendChild(ageGroupInput);
        }

        // Reset all selects
        courseSelects.forEach((select, index) => {
            select.innerHTML = `<option value="" selected disabled>-- เลือก${index === 0 ? '' : 'อันดับ ' + (index + 1)} --</option>`;
            select.value = "";
            // Remove any existing event listeners by cloning (simple way)
            const newSelect = select.cloneNode(true);
            select.parentNode.replaceChild(newSelect, select);
            courseSelects[index] = newSelect; // Update reference
        });
        
        // Clear radio container
        ageRadioContainer.innerHTML = '';
        ageGroupInput.value = '';

        let hasCourses = false;
        let isSportsQuota = false; // Flag to check if it's a sports quota
        let sportsData = {}; // Object to store sports and their ages/ids: { 'Football': {id: 1, ages: ['13', '14']} }

        // First pass: Check quota type and organize data
        coursesData.forEach(course => {
            if (allowedCourses.includes(course.course_id.toString())) {
                hasCourses = true;
                if (course.course_age && course.course_age.trim() !== '') {
                    isSportsQuota = true;
                    // Assuming one course per sport branch for simplicity based on user request "13,14,15 in one course_age"
                    // If multiple courses have same branch, we might need to handle differently, but here we assume grouping by branch.
                    sportsData[course.course_branch] = {
                        id: course.course_id,
                        ages: course.course_age.split(',').map(s => s.trim()).filter(s => s !== ''),
                        fullname: course.course_fullname
                    };
                }
            }
        });
        
        if (hasCourses) {
            courseSection.style.display = 'block'; 
            courseSelects[0].required = true;
            
            if (isSportsQuota) {
                // --- Sports Quota Logic ---
                courseLabel.innerHTML = 'เลือกชนิดกีฬาและรุ่นอายุ <span class="text-danger">*</span>';
                
                // Setup Dropdown 1: Sport Type (Branch)
                courseContainers[0].style.display = 'flex';
                courseContainers[0].querySelector('.input-group-text').style.display = 'none'; // Hide "Rank 1" label
                courseSelects[0].innerHTML = '<option value="" selected disabled>-- เลือกชนิดกีฬา --</option>';
                
                Object.keys(sportsData).forEach(sport => {
                    const option = document.createElement('option');
                    option.value = sportsData[sport].id; // Store course_id directly here
                    option.text = sport;
                    courseSelects[0].appendChild(option);
                });

                // Hide Dropdown 2 & 3
                courseContainers[1].style.display = 'none';
                courseContainers[2].style.display = 'none';
                courseSelects[1].required = false;
                courseSelects[2].required = false;
                
                // Ensure Dropdown 1 submits as recruit_tpyeRoom1
                courseSelects[0].setAttribute('name', 'recruit_tpyeRoom1');
                courseSelects[1].removeAttribute('name');
                courseSelects[2].removeAttribute('name');

                // Add Event Listener to Dropdown 1 to show Age Radios
                courseSelects[0].addEventListener('change', function() {
                    const selectedCourseId = this.value;
                    // Find the sport data based on ID (a bit inefficient but works)
                    let selectedSportData = null;
                    for (const sport in sportsData) {
                        if (sportsData[sport].id == selectedCourseId) {
                            selectedSportData = sportsData[sport];
                            break;
                        }
                    }
                    
                    ageRadioContainer.innerHTML = '<label class="form-label d-block">เลือกรุ่นอายุ <span class="text-danger">*</span></label>';
                    ageGroupInput.value = ''; // Reset hidden input
                    
                    if (selectedSportData && selectedSportData.ages.length > 0) {
                        const rowDiv = document.createElement('div');
                        rowDiv.className = 'row g-2';
                        
                        selectedSportData.ages.forEach(age => {
                            const colDiv = document.createElement('div');
                            colDiv.className = 'col-auto';
                            
                            const radioDiv = document.createElement('div');
                            radioDiv.className = 'form-check form-check-inline';
                            
                            const radioInput = document.createElement('input');
                            radioInput.className = 'form-check-input';
                            radioInput.type = 'radio';
                            radioInput.name = 'age_radio_group'; // Temporary name for radio group
                            radioInput.id = 'age_' + age;
                            radioInput.value = age;
                            radioInput.required = true;
                            
                            radioInput.addEventListener('change', function() {
                                ageGroupInput.value = this.value;
                            });
                            
                            const radioLabel = document.createElement('label');
                            radioLabel.className = 'form-check-label';
                            radioLabel.htmlFor = 'age_' + age;
                            radioLabel.innerText = age + ' ปี';
                            
                            radioDiv.appendChild(radioInput);
                            radioDiv.appendChild(radioLabel);
                            colDiv.appendChild(radioDiv);
                            rowDiv.appendChild(colDiv);
                        });
                        
                        ageRadioContainer.appendChild(rowDiv);
                        ageRadioContainer.style.display = 'block';
                    } else {
                        ageRadioContainer.style.display = 'none';
                    }
                });

            } else {
                // --- Normal Quota Logic ---
                courseLabel.innerHTML = 'เลือกแผนการเรียน (เลือกได้สูงสุด 3 อันดับ) <span class="text-danger">*</span>';
                ageRadioContainer.style.display = 'none';
                
                // Restore names
                courseSelects[0].setAttribute('name', 'recruit_tpyeRoom1');
                courseSelects[1].setAttribute('name', 'recruit_tpyeRoom2');
                courseSelects[2].setAttribute('name', 'recruit_tpyeRoom3');

                // Show all ranks
                courseContainers[0].style.display = 'flex';
                courseContainers[1].style.display = 'flex';
                courseContainers[2].style.display = 'flex';
                
                // Restore labels
                courseContainers[0].querySelector('.input-group-text').style.display = 'block';
                courseContainers[1].querySelector('.input-group-text').style.display = 'block';
                courseContainers[2].querySelector('.input-group-text').style.display = 'block';

                // Populate Dropdown 1 (and others similarly if we wanted dynamic filtering, but for now just list all)
                courseSelects.forEach((select, index) => {
                    select.innerHTML = `<option value="" selected disabled>-- เลือกอันดับ ${index + 1} --</option>`;
                    coursesData.forEach(course => {
                        if (allowedCourses.includes(course.course_id.toString())) {
                            const option = document.createElement('option');
                            option.value = course.course_id;
                            // Display: Initials - Branch (e.g., วิทย์-คณิต - ห้องเรียนพิเศษ)
                            const initials = course.course_initials || course.course_fullname; // Fallback to fullname if initials missing
                            const branch = course.course_branch || '';
                            option.text = `${initials} ${branch ? '(' + branch + ')' : ''}`;
                            select.appendChild(option);
                        }
                    });
                });
                
                courseSelects[0].required = true;
                courseSelects[1].required = false;
                courseSelects[2].required = false;
            }
        } else {
            courseSection.style.display = 'none';
            courseSelects[0].required = false;
            if(ageRadioContainer) ageRadioContainer.style.display = 'none';
            if(ageRadioContainer) ageRadioContainer.style.display = 'none';
        }
        
        // Re-attach duplicate prevention logic
        setupCourseSelectionLogic();
    });

    function setupCourseSelectionLogic() {
        const selects = document.querySelectorAll('.course-select');
        
        function updateOptions() {
            const selectedValues = Array.from(selects)
                .map(s => s.value)
                .filter(v => v !== "");

            selects.forEach(s => {
                const currentVal = s.value;
                Array.from(s.options).forEach(opt => {
                    if (opt.value === "") return; 
                    
                    // Disable if selected in OTHER selects
                    // But keep enabled if it's the currently selected value of THIS select
                    if (selectedValues.includes(opt.value) && opt.value !== currentVal) {
                        opt.disabled = true;
                    } else {
                        opt.disabled = false;
                    }
                });
            });
        }

        selects.forEach(select => {
            // Remove old listeners to avoid duplicates if called multiple times (though replaceChild handles that mostly)
            // But since we use anonymous function, we can't easily remove. 
            // Better to just ensure we are attaching to fresh elements or handle it.
            // Since we replace elements in the quota change logic, we just need to attach 'change' event.
            select.addEventListener('change', updateOptions);
        });
    }

    // Initial setup
    setupCourseSelectionLogic();

    // Wizard Logic
    let currentStep = 1;
    const totalSteps = 5;

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    function showConfirmationModal(confirmModal) {
        const formData = new FormData(document.getElementById('regisForm'));
        const dataList = document.getElementById('confirm-data-list');
        dataList.innerHTML = ''; 

        const fieldLabels = {
            recruit_category: 'ประเภทโควตา',
            recruit_tpyeRoom1: 'แผนการเรียนอันดับ 1',
            recruit_tpyeRoom2: 'แผนการเรียนอันดับ 2',
            recruit_tpyeRoom3: 'แผนการเรียนอันดับ 3',
            recruit_img_validator: 'รูปถ่ายนักเรียน',
            recruit_prefix: 'คำนำหน้า',
            recruit_firstName: 'ชื่อ',
            recruit_lastName: 'นามสกุล',
            recruit_idCard: 'เลขบัตรประชาชน',
            recruit_birthday: 'วันเกิด',
            recruit_race: 'เชื้อชาติ',
            recruit_nationality: 'สัญชาติ',
            recruit_religion: 'ศาสนา',
            recruit_phone: 'เบอร์โทรศัพท์',
            recruit_homeNumber: 'บ้านเลขที่',
            recruit_homeGroup: 'หมู่ที่',
            recruit_homeRoad: 'ถนน',
            recruit_homeSubdistrict: 'ตำบล/แขวง',
            recruit_homedistrict: 'อำเภอ/เขต',
            recruit_homeProvince: 'จังหวัด',
            recruit_homePostcode: 'รหัสไปรษณีย์',
            recruit_oldSchool: 'โรงเรียนเดิม',
            recruit_district: 'อำเภอ รร. เดิม',
            recruit_province: 'จังหวัด รร. เดิม',
            recruit_grade: 'เกรดเฉลี่ย',
            recruit_certificateEdu: 'ปพ.1 (หน้า)',
            recruit_certificateEduB: 'ปพ.1 (หลัง)',
            recruit_copyidCard: 'สำเนาบัตรประชาชน',
        };

        let html = '<dl class="row">';

        const birthday = formData.get('recruit_birthdayD') + '/' + formData.get('recruit_birthdayM') + '/' + formData.get('recruit_birthdayY');

        for (const [key, label] of Object.entries(fieldLabels)) {
            let value;
            const element = document.querySelector(`[name="${key}"]`);
            
            if (key === 'recruit_birthday') {
                value = birthday;
            } else if(element && element.tagName === 'SELECT') {
                 if (element.value !== "") {
                    // Check if it's a course selection
                    if (key.startsWith('recruit_tpyeRoom')) {
                        const courseId = element.value;
                        const course = coursesData.find(c => c.course_id == courseId);
                        if (course) {
                            // Display: Initials - Branch (e.g., วิทย์-คณิต - ห้องเรียนพิเศษ)
                            // Handle potential null/empty values gracefully
                            const initials = course.course_initials || course.course_fullname;
                            const branch = course.course_branch || '';
                            value = `${initials} ${branch ? '(' + branch + ')' : ''}`;
                        } else {
                            value = element.options[element.selectedIndex].text;
                        }
                    } else {
                        value = element.options[element.selectedIndex].text;
                    }
                 } else {
                    value = 'ไม่ได้เลือก';
                 }
            } else if (key.endsWith('_validator')) {
                 value = formData.get('recruit_img_cropped') ? '<span class="text-success">อัปโหลดแล้ว</span>' : '<span class="text-danger">ยังไม่ได้อัปโหลด</span>';
            } else if (element && element.type === 'file') {
                 value = element.files.length > 0 ? `<span class="text-success">ไฟล์: ${element.files[0].name}</span>` : 'ไม่ได้เลือก';
            } else {
                value = formData.get(key) || '-';
            }
            
            html += `<dt class="col-sm-4">${label}</dt><dd class="col-sm-8">${value}</dd>`;
        }
        html += '</dl>';
        dataList.innerHTML = html;

        // Set image preview in modal
        const imgElem = document.getElementById('confirm_image');
        const croppedVal = document.getElementById('recruit_img_cropped').value;
        if (croppedVal) {
            imgElem.src = croppedVal;
            imgElem.classList.remove('d-none');
        } else {
            imgElem.classList.add('d-none');
        }

        // Helper function to set document previews
        const setDocPreview = (srcImgId, srcTxtId, targetImgId, targetTxtId) => {
             const srcImg = document.getElementById(srcImgId);
             const srcTxt = document.getElementById(srcTxtId);
             const targetImg = document.getElementById(targetImgId);
             const targetTxt = document.getElementById(targetTxtId);
             
             if(srcImg && !srcImg.classList.contains('d-none')) {
                 targetImg.src = srcImg.src;
                 targetImg.classList.remove('d-none');
                 targetTxt.classList.add('d-none');
             } else if(srcTxt && !srcTxt.classList.contains('d-none')) {
                 targetTxt.textContent = srcTxt.textContent;
                 targetTxt.classList.remove('d-none');
                 targetImg.classList.add('d-none');
             } else {
                 targetImg.classList.add('d-none');
                 targetTxt.classList.add('d-none');
             }
        };

        setDocPreview('preview_certificate', 'preview_certificate_name', 'confirm_certificate', 'confirm_certificate_name');
        setDocPreview('preview_certificateB', 'preview_certificateB_name', 'confirm_certificateB', 'confirm_certificateB_name');
        setDocPreview('preview_idcard', 'preview_idcard_name', 'confirm_idcard', 'confirm_idcard_name');

        confirmModal.show();
    }

    function showStep(step) {
        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');
        
        document.querySelectorAll('.step').forEach((el, index) => {
            if (index + 1 < step) {
                el.classList.add('completed');
                el.classList.remove('active');
            } else if (index + 1 === step) {
                el.classList.add('active');
                el.classList.remove('completed');
            } else {
                el.classList.remove('active', 'completed');
            }
        });

        prevBtn.style.display = (step === 1) ? 'none' : 'inline-block';
        nextBtn.style.display = (step === totalSteps) ? 'none' : 'inline-block';
        submitBtn.style.display = (step === totalSteps) ? 'inline-block' : 'none';
    }

    function validateStep(step) {
        const stepEl = document.getElementById('step-' + step);
        const inputs = stepEl.querySelectorAll('input, select, textarea');
        let valid = true;

        // Custom Validation for Service Area School in Step 1
        if (step === 1) {
            const serviceAreaSearch = document.getElementById('service_area_school_search');
            if (serviceAreaSearch && serviceAreaSearch.hasAttribute('data-required')) {
                // Check if value is selected (Select2 uses the select element's value)
                if (!$(serviceAreaSearch).val()) {
                    // Show error (maybe using SweetAlert or adding class)
                    // Since it's a select2, adding is-invalid to the select might not show visually on the select2 container
                    // We can use the container:
                    $(serviceAreaSearch).next('.select2-container').find('.select2-selection').addClass('border-danger');
                    valid = false;
                } else {
                    $(serviceAreaSearch).next('.select2-container').find('.select2-selection').removeClass('border-danger');
                }
            }
        }

        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value) {
                input.classList.add('is-invalid');
                valid = false;
            } else {
                input.classList.remove('is-invalid');
            }
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                valid = false;
            }
        });
        return valid;
    }

    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
            window.scrollTo(0, 0);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                text: 'โปรดตรวจสอบข้อมูลในช่องที่มีเครื่องหมาย *',
                confirmButtonText: 'ตกลง'
            });
        }
    });

    prevBtn.addEventListener('click', () => {
        currentStep--;
        showStep(currentStep);
        window.scrollTo(0, 0);
    });

    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });
    });

    showStep(1);

</script>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">โปรดตรวจสอบข้อมูลการสมัครของท่าน</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-danger">**กรุณาตรวจสอบข้อมูลให้ถูกต้องครบถ้วน หากยืนยันการสมัครแล้ว จะไม่สามารถกลับมาแก้ไขได้**</p>
        <div id="confirm-data-list" class="list-group">
            <!-- Data will be injected here by JS -->
        </div>
        <!-- Image preview placeholder -->
        <div class="mt-3">
            <h6 class="fw-bold border-bottom pb-2">รูปถ่ายนักเรียน</h6>
            <div class="text-center" id="confirm-image-wrapper">
                <img id="confirm_image" src="#" alt="รูปถ่ายนักเรียน" class="img-thumbnail d-none" style="max-width:200px; max-height:250px; object-fit:contain;" />
            </div>
        </div>

        <div class="mt-3">
            <h6 class="fw-bold border-bottom pb-2">เอกสารหลักฐาน</h6>
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <p class="small mb-1 fw-bold">ปพ.1 (หน้า)</p>
                    <img id="confirm_certificate" src="#" class="img-thumbnail d-none" style="max-height:150px; width: auto;">
                    <p id="confirm_certificate_name" class="small text-muted d-none"></p>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <p class="small mb-1 fw-bold">ปพ.1 (หลัง)</p>
                    <img id="confirm_certificateB" src="#" class="img-thumbnail d-none" style="max-height:150px; width: auto;">
                    <p id="confirm_certificateB_name" class="small text-muted d-none"></p>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <p class="small mb-1 fw-bold">สำเนาบัตรประชาชน</p>
                    <img id="confirm_idcard" src="#" class="img-thumbnail d-none" style="max-height:150px; width: auto;">
                    <p id="confirm_idcard_name" class="small text-muted d-none"></p>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">แก้ไขข้อมูล</button>
        <button type="button" class="btn btn-success" id="confirmSubmitBtn">ยืนยันและสมัครเรียน</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

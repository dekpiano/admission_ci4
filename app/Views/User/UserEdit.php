<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
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
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 text-primary"><i class='bx bx-edit me-2'></i>แก้ไขข้อมูลการสมัคร (Edit Registration)</h5>
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

                <form action="<?= base_url('admission/update') ?>" method="post" enctype="multipart/form-data" id="regisForm" class="needs-validation" novalidate>
                    <input type="hidden" name="recruit_id" value="<?= esc($student['recruit_id']) ?>">
                    <input type="hidden" name="recruit_regLevel" value="<?= esc($level) ?>">

                    <!-- Step 1: Quota & Program -->
                    <div class="form-step active" id="step-1">
                        <div class="divider text-start">
                            <div class="divider-text text-primary fw-bold fs-5">1. เลือกประเภทโควตาและแผนการเรียน</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_category" class="form-label">ประเภทโควตา <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-star'></i></span>
                                    <select class="form-select" name="recruit_category" id="recruit_category" required>
                                        <option value="" selected disabled>-- กรุณาเลือกประเภทโควตา --</option>
                                        <?php foreach($quotas as $quota): ?>
                                            <?php if($quota->quota_status == 'on' && strpos($quota->quota_level, (string)$level) !== false): ?>
                                                <option value="<?= $quota->quota_key ?>" data-courses="<?= $quota->quota_course ?>" <?= (isset($student['recruit_category']) && $student['recruit_category'] == $quota->quota_key) ? 'selected' : '' ?>>
                                                    <?= $quota->quota_explain ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3" id="course_section" style="display:none;">
                            <div class="col-sm-12">
                                <label class="form-label">เลือกแผนการเรียน (เลือกได้สูงสุด 3 อันดับ) <span class="text-danger">*</span></label>
                                
                                <div class="mb-2 input-group">
                                    <span class="input-group-text">อันดับ 1</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom1" id="recruit_tpyeRoom1" required>
                                        <option value="" selected disabled>-- เลือกอันดับ 1 --</option>
                                    </select>
                                </div>

                                <div class="mb-2 input-group">
                                    <span class="input-group-text">อันดับ 2</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom2" id="recruit_tpyeRoom2">
                                        <option value="" selected disabled>-- เลือกอันดับ 2 --</option>
                                    </select>
                                </div>

                                <div class="mb-2 input-group">
                                    <span class="input-group-text">อันดับ 3</span>
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
                                <label class="form-label fw-bold">รูปถ่ายนักเรียน (ชุดนักเรียน)</label>
                                <div class="card shadow-sm">
                                    <div class="card-body text-center p-3">
                                        <div class="mb-3">
                                            <?php 
                                                $student_img_path = base_url('uploads/recruitstudent/m' . $level . '/img/' . $student['recruit_img']);
                                                $default_img = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
                                            ?>
                                            <img id="preview_img_display" src="<?= !empty($student['recruit_img']) ? $student_img_path : $default_img ?>" alt="รูปถ่ายนักเรียน" class="d-block rounded mx-auto" style="width: 150px; height: 200px; object-fit: contain; border: 2px dashed #d9dee3;">
                                            <?php if (!empty($student['recruit_img'])): ?>
                                                <small class="text-muted mt-1 d-block">รูปปัจจุบัน: <?= esc($student['recruit_img']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="document.getElementById('recruit_img_input').click()">
                                            <i class='bx bx-camera me-1'></i> <?= !empty($student['recruit_img']) ? 'เปลี่ยนรูปถ่าย' : 'อัปโหลดรูปถ่าย' ?>
                                        </button>
                                        <input type="file" id="recruit_img_input" accept="image/*" class="d-none" onchange="handleImageSelect(this)">
                                        <input type="hidden" name="recruit_img_cropped" id="recruit_img_cropped">
                                        <!-- Hidden input for validation -->
                                        <input type="text" id="recruit_img_validator" name="recruit_img_validator" style="opacity: 0; position: absolute; width: 1px; height: 1px;" <?= empty($student['recruit_img']) ? 'required' : '' ?> value="<?= !empty($student['recruit_img']) ? 'uploaded' : '' ?>">
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
                                    <option value="เด็กชาย" <?= $student['recruit_prefix'] == 'เด็กชาย' ? 'selected' : '' ?>>เด็กชาย</option>
                                    <option value="เด็กหญิง" <?= $student['recruit_prefix'] == 'เด็กหญิง' ? 'selected' : '' ?>>เด็กหญิง</option>
                                    <option value="นาย" <?= $student['recruit_prefix'] == 'นาย' ? 'selected' : '' ?>>นาย</option>
                                    <option value="นางสาว" <?= $student['recruit_prefix'] == 'นางสาว' ? 'selected' : '' ?>>นางสาว</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="recruit_firstName" class="form-label">ชื่อ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                                    <input type="text" class="form-control" name="recruit_firstName" id="recruit_firstName" placeholder="ชื่อจริง" value="<?= esc($student['recruit_firstName']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <label for="recruit_lastName" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                                    <input type="text" class="form-control" name="recruit_lastName" id="recruit_lastName" placeholder="นามสกุล" value="<?= esc($student['recruit_lastName']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_idCard" class="form-label">เลขบัตรประชาชน (13 หลัก) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-id-card'></i></span>
                                    <input type="text" class="form-control" name="recruit_idCard" id="recruit_idCard" maxlength="13" required placeholder="เลขบัตรประชาชน 13 หลัก" value="<?= esc($student['recruit_idCard']) ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <?php
                            $birth_date = new DateTime($student['recruit_birthday']);
                            $birth_day = $birth_date->format('d');
                            $birth_month = $birth_date->format('m');
                            $birth_year_be = $birth_date->format('Y') + 543;
                        ?>
                        <div class="row mb-3">
                            <label class="form-label mb-2">วันเดือนปีเกิด <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-select" name="recruit_birthdayD" id="recruit_birthdayD" required>
                                    <option value="">วัน</option>
                                    <?php for($i=1; $i<=31; $i++): ?>
                                        <option value="<?= sprintf('%02d', $i) ?>" <?= $birth_day == sprintf('%02d', $i) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <select class="form-select" name="recruit_birthdayM" id="recruit_birthdayM" required>
                                    <option value="">เดือน</option>
                                    <option value="01" <?= $birth_month == '01' ? 'selected' : '' ?>>มกราคม</option>
                                    <option value="02" <?= $birth_month == '02' ? 'selected' : '' ?>>กุมภาพันธ์</option>
                                    <option value="03" <?= $birth_month == '03' ? 'selected' : '' ?>>มีนาคม</option>
                                    <option value="04" <?= $birth_month == '04' ? 'selected' : '' ?>>เมษายน</option>
                                    <option value="05" <?= $birth_month == '05' ? 'selected' : '' ?>>พฤษภาคม</option>
                                    <option value="06" <?= $birth_month == '06' ? 'selected' : '' ?>>มิถุนายน</option>
                                    <option value="07" <?= $birth_month == '07' ? 'selected' : '' ?>>กรกฎาคม</option>
                                    <option value="08" <?= $birth_month == '08' ? 'selected' : '' ?>>สิงหาคม</option>
                                    <option value="09" <?= $birth_month == '09' ? 'selected' : '' ?>>กันยายน</option>
                                    <option value="10" <?= $birth_month == '10' ? 'selected' : '' ?>>ตุลาคม</option>
                                    <option value="11" <?= $birth_month == '11' ? 'selected' : '' ?>>พฤศจิกายน</option>
                                    <option value="12" <?= $birth_month == '12' ? 'selected' : '' ?>>ธันวาคม</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select class="form-select" name="recruit_birthdayY" id="recruit_birthdayY" required>
                                    <option value="">ปี (พ.ศ.)</option>
                                    <?php $curYear = date('Y')+543; for($i=$curYear-20; $i<=$curYear-10; $i++): ?>
                                        <option value="<?= $i ?>" <?= $birth_year_be == $i ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <label for="recruit_race" class="form-label">เชื้อชาติ <span class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_race" id="recruit_race" required>
                                    <option value="ไทย" <?= $student['recruit_race'] == 'ไทย' ? 'selected' : '' ?>>ไทย</option>
                                    <option value="จีน" <?= $student['recruit_race'] == 'จีน' ? 'selected' : '' ?>>จีน</option>
                                    <option value="อื่นๆ" <?= $student['recruit_race'] == 'อื่นๆ' ? 'selected' : '' ?>>อื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="recruit_nationality" class="form-label">สัญชาติ <span class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_nationality" id="recruit_nationality" required>
                                    <option value="ไทย" <?= $student['recruit_nationality'] == 'ไทย' ? 'selected' : '' ?>>ไทย</option>
                                    <option value="จีน" <?= $student['recruit_nationality'] == 'จีน' ? 'selected' : '' ?>>จีน</option>
                                    <option value="อื่นๆ" <?= $student['recruit_nationality'] == 'อื่นๆ' ? 'selected' : '' ?>>อื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="recruit_religion" class="form-label">ศาสนา <span class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_religion" id="recruit_religion" required>
                                    <option value="พุทธ" <?= $student['recruit_religion'] == 'พุทธ' ? 'selected' : '' ?>>พุทธ</option>
                                    <option value="อิสลาม" <?= $student['recruit_religion'] == 'อิสลาม' ? 'selected' : '' ?>>อิสลาม</option>
                                    <option value="คริสต์" <?= $student['recruit_religion'] == 'คริสต์' ? 'selected' : '' ?>>คริสต์</option>
                                    <option value="อื่นๆ" <?= $student['recruit_religion'] == 'อื่นๆ' ? 'selected' : '' ?>>อื่นๆ</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_phone" class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-phone'></i></span>
                                    <input type="tel" class="form-control" name="recruit_phone" id="recruit_phone" placeholder="08xxxxxxxx" value="<?= esc($student['recruit_phone']) ?>" required>
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
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-home'></i></span>
                                    <input type="text" class="form-control" name="recruit_homeNumber" id="recruit_homeNumber" placeholder="บ้านเลขที่" value="<?= esc($student['recruit_homeNumber']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeGroup" class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeGroup" id="recruit_homeGroup" placeholder="หมู่ที่" value="<?= esc($student['recruit_homeGroup']) ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homeRoad" class="form-label">ถนน</label>
                                <input type="text" class="form-control" name="recruit_homeRoad" id="recruit_homeRoad" placeholder="ถนน" value="<?= esc($student['recruit_homeRoad']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeSubdistrict" class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeSubdistrict" id="recruit_homeSubdistrict" placeholder="ตำบล/แขวง" value="<?= esc($student['recruit_homeSubdistrict']) ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homedistrict" class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homedistrict" id="recruit_homedistrict" placeholder="อำเภอ/เขต" value="<?= esc($student['recruit_homedistrict']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeProvince" class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeProvince" id="recruit_homeProvince" placeholder="จังหวัด" value="<?= esc($student['recruit_homeProvince']) ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                             <div class="col-md-6">
                                <label for="recruit_homePostcode" class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-map-pin'></i></span>
                                    <input type="text" class="form-control" name="recruit_homePostcode" id="recruit_homePostcode" placeholder="รหัสไปรษณีย์" value="<?= esc($student['recruit_homePostcode']) ?>" required>
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
                                <label for="recruit_oldSchool" class="form-label">โรงเรียนเดิม <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bxs-school'></i></span>
                                    <input type="text" class="form-control" name="recruit_oldSchool" id="recruit_oldSchool" placeholder="ชื่อโรงเรียนเดิม" value="<?= esc($student['recruit_oldSchool']) ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label for="recruit_district" class="form-label">อำเภอที่ตั้งโรงเรียน <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_district" id="recruit_district" placeholder="อำเภอ" value="<?= esc($student['recruit_district']) ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="recruit_province" class="form-label">จังหวัดที่ตั้งโรงเรียน <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_province" id="recruit_province" placeholder="จังหวัด" value="<?= esc($student['recruit_province']) ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_grade" class="form-label">เกรดเฉลี่ยสะสม (GPAX) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-bar-chart-alt-2'></i></span>
                                    <input type="number" step="0.01" min="0" max="4.00" class="form-control" name="recruit_grade" id="recruit_grade" placeholder="เช่น 3.50" value="<?= esc($student['recruit_grade']) ?>" required>
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
                                <label for="recruit_certificateEdu" class="form-label">ปพ.1 (หน้า-หลัง) <?= empty($student['recruit_certificateEdu']) ? '<span class="text-danger">*</span>' : '' ?></label>
                                <input class="form-control" type="file" id="recruit_certificateEdu" name="recruit_certificateEdu" accept="image/*,.pdf" <?= empty($student['recruit_certificateEdu']) ? 'required' : '' ?> onchange="previewImage(this, 'preview_certificate')">
                                <div class="mt-2 text-center">
                                    <?php if (!empty($student['recruit_certificateEdu'])): ?>
                                        <?php 
                                            $file_path = base_url('uploads/recruitstudent/m' . $level . '/certificate/' . $student['recruit_certificateEdu']);
                                            $file_extension = pathinfo($student['recruit_certificateEdu'], PATHINFO_EXTENSION);
                                        ?>
                                        <?php if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                            <img id="preview_certificate" src="<?= $file_path ?>" alt="ตัวอย่าง ปพ.1" class="img-thumbnail" style="max-height: 200px;">
                                        <?php else: ?>
                                            <i class='bx bxs-file-pdf display-4 text-danger'></i>
                                            <p class="text-muted small">ไฟล์ปัจจุบัน: <a href="<?= $file_path ?>" target="_blank"><?= esc($student['recruit_certificateEdu']) ?></a></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <img id="preview_certificate" src="#" alt="ตัวอย่าง ปพ.1" class="img-thumbnail d-none" style="max-height: 200px;">
                                    <?php endif; ?>
                                    <p id="preview_certificate_name" class="<?= !empty($student['recruit_certificateEdu']) && !in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif']) ? '' : 'd-none' ?> text-muted small"></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recruit_copyidCard" class="form-label">สำเนาบัตรประชาชน <?= empty($student['recruit_copyidCard']) ? '<span class="text-danger">*</span>' : '' ?></label>
                                <input class="form-control" type="file" id="recruit_copyidCard" name="recruit_copyidCard" accept="image/*,.pdf" <?= empty($student['recruit_copyidCard']) ? 'required' : '' ?> onchange="previewImage(this, 'preview_idcard')">
                                <div class="mt-2 text-center">
                                    <?php if (!empty($student['recruit_copyidCard'])): ?>
                                        <?php 
                                            $file_path = base_url('uploads/recruitstudent/m' . $level . '/copyidCard/' . $student['recruit_copyidCard']);
                                            $file_extension = pathinfo($student['recruit_copyidCard'], PATHINFO_EXTENSION);
                                        ?>
                                        <?php if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                            <img id="preview_idcard" src="<?= $file_path ?>" alt="ตัวอย่างบัตรประชาชน" class="img-thumbnail" style="max-height: 200px;">
                                        <?php else: ?>
                                            <i class='bx bxs-file-pdf display-4 text-danger'></i>
                                            <p class="text-muted small">ไฟล์ปัจจุบัน: <a href="<?= $file_path ?>" target="_blank"><?= esc($student['recruit_copyidCard']) ?></a></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <img id="preview_idcard" src="#" alt="ตัวอย่างบัตรประชาชน" class="img-thumbnail d-none" style="max-height: 200px;">
                                    <?php endif; ?>
                                    <p id="preview_idcard_name" class="<?= !empty($student['recruit_copyidCard']) && !in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif']) ? '' : 'd-none' ?> text-muted small"></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recruit_copyAddress" class="form-label">สำเนาทะเบียนบ้าน <?= empty($student['recruit_copyAddress']) ? '<span class="text-danger">*</span>' : '' ?></label>
                                <input class="form-control" type="file" id="recruit_copyAddress" name="recruit_copyAddress" accept="image/*,.pdf" <?= empty($student['recruit_copyAddress']) ? 'required' : '' ?> onchange="previewImage(this, 'preview_address')">
                                <div class="mt-2 text-center">
                                    <?php if (!empty($student['recruit_copyAddress'])): ?>
                                        <?php 
                                            $file_path = base_url('uploads/recruitstudent/m' . $level . '/copyAddress/' . $student['recruit_copyAddress']);
                                            $file_extension = pathinfo($student['recruit_copyAddress'], PATHINFO_EXTENSION);
                                        ?>
                                        <?php if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                            <img id="preview_address" src="<?= $file_path ?>" alt="ตัวอย่างทะเบียนบ้าน" class="img-thumbnail" style="max-height: 200px;">
                                        <?php else: ?>
                                            <i class='bx bxs-file-pdf display-4 text-danger'></i>
                                            <p class="text-muted small">ไฟล์ปัจจุบัน: <a href="<?= $file_path ?>" target="_blank"><?= esc($student['recruit_copyAddress']) ?></a></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <img id="preview_address" src="#" alt="ตัวอย่างทะเบียนบ้าน" class="img-thumbnail d-none" style="max-height: 200px;">
                                    <?php endif; ?>
                                    <p id="preview_address_name" class="<?= !empty($student['recruit_copyAddress']) && !in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif']) ? '' : 'd-none' ?> text-muted small"></p>
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
                            <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">
                                <i class='bx bx-check-circle'></i> บันทึกการแก้ไข
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

<script>
    // --- Start of Correct Script ---

    // Image Cropping Logic (unchanged)
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
        cropper = new Cropper(imageToCrop, { aspectRatio: 3 / 4, viewMode: 1, autoCropArea: 1 });
    });
    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) { cropper.destroy(); cropper = null; }
    });
    document.getElementById('crop_btn').addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({ width: 450, height: 600 });
            const croppedImage = canvas.toDataURL('image/jpeg');
            document.getElementById('preview_img_display').src = croppedImage;
            document.getElementById('recruit_img_cropped').value = croppedImage;
            document.getElementById('recruit_img_validator').value = 'uploaded';
            document.getElementById('recruit_img_validator').classList.remove('is-invalid');
            cropModal.hide();
        }
    });

    // General File Preview Function (unchanged)
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
                    if (namePreview) namePreview.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
                if (namePreview) {
                    namePreview.textContent = 'ไฟล์ที่เลือก: ' + file.name;
                    namePreview.classList.remove('d-none');
                }
            }
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
            if (namePreview) namePreview.classList.add('d-none');
        }
    }

    // Initialize ONE Thailand Address Auto Complete
    $.Thailand({
        $district: $('#recruit_homeSubdistrict'),
        $amphoe: $('#recruit_homedistrict'),
        $province: $('#recruit_homeProvince'),
        $zipcode: $('#recruit_homePostcode'),
    });

    // --- Start of Corrected Course Logic ---

    // 1. Declare variables ONCE
    const coursesData = <?= json_encode($courses) ?>;
    const studentMajorOrder = "<?= esc($student['recruit_majorOrder'] ?? '') ?>".split('|').filter(n => n);
    const courseSelects = document.querySelectorAll('.course-select');

    // 2. Function to populate courses based on quota
    function populateCourses() {
        const recruitCategorySelect = document.getElementById('recruit_category');
        const selectedOption = recruitCategorySelect.options[recruitCategorySelect.selectedIndex];
        const allowedCourses = selectedOption.getAttribute('data-courses').split('|');
        const courseSection = document.getElementById('course_section');
        
        let hasCourses = false;

        courseSelects.forEach((select, index) => {
            // Reset select but keep the default disabled option
            select.innerHTML = `<option value="" selected disabled>-- เลือกอันดับ ${index + 1} --</option>`;
        });
        
        coursesData.forEach(course => {
            if (allowedCourses.includes(course.course_id.toString())) {
                courseSelects.forEach(select => {
                    const option = document.createElement('option');
                    option.value = course.course_id;
                    option.text = course.course_fullname;
                    select.appendChild(option);
                });
                hasCourses = true;
            }
        });
        
        if (hasCourses) {
            courseSection.style.display = 'block';
            document.getElementById('recruit_tpyeRoom1').required = true;
        } else {
            courseSection.style.display = 'none';
            document.getElementById('recruit_tpyeRoom1').required = false;
        }
    }

    // 3. Event listener for quota change
    document.getElementById('recruit_category').addEventListener('change', populateCourses);

    // 4. Logic to prevent duplicate course selections
    courseSelects.forEach(select => {
        select.addEventListener('change', function() {
            const selectedValues = Array.from(courseSelects).map(s => s.value).filter(v => v !== "");
            courseSelects.forEach(s => {
                const currentVal = s.value;
                Array.from(s.options).forEach(opt => {
                    if (opt.value === "") return;
                    opt.disabled = selectedValues.includes(opt.value) && opt.value !== currentVal;
                });
            });
        });
    });

    // 5. Run everything on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        // Populate courses based on the pre-selected quota
        populateCourses();
        
        // Use a timeout to pre-select the student's choices after options are rendered
        setTimeout(() => {
            studentMajorOrder.forEach((courseId, index) => {
                if (index < courseSelects.length) {
                    const select = courseSelects[index];
                    if (Array.from(select.options).some(opt => opt.value === courseId)) {
                        select.value = courseId;
                        // Dispatch change to trigger duplicate prevention
                        select.dispatchEvent(new Event('change'));
                    }
                }
            });
        }, 100); // 100ms delay to ensure DOM update
    });

    // --- End of Corrected Course Logic ---


    // Wizard Logic (unchanged)
    let currentStep = 1;
    const totalSteps = 5;
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showStep(step) {
        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');
        document.querySelectorAll('.step').forEach((el, index) => {
            el.classList.remove('active', 'completed');
            if (index + 1 < step) { el.classList.add('completed'); } 
            else if (index + 1 === step) { el.classList.add('active'); }
        });
        prevBtn.style.display = (step === 1) ? 'none' : 'inline-block';
        nextBtn.style.display = (step === totalSteps) ? 'none' : 'inline-block';
        submitBtn.style.display = (step === totalSteps) ? 'inline-block' : 'none';
    }

    function validateStep(step) {
        const stepEl = document.getElementById('step-' + step);
        const inputs = stepEl.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if (!input.value) {
                 input.classList.add('is-invalid');
                 valid = false;
            } else if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                valid = false;
            } else {
                input.classList.remove('is-invalid');
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
            Swal.fire({ icon: 'warning', title: 'กรุณากรอกข้อมูลให้ครบถ้วน', text: 'โปรดตรวจสอบข้อมูลในช่องที่มีเครื่องหมาย *', confirmButtonText: 'ตกลง' });
        }
    });

    prevBtn.addEventListener('click', () => {
        currentStep--;
        showStep(currentStep);
        window.scrollTo(0, 0);
    });

    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
            }
        });
    });

    showStep(1); // Initialize

</script>
<?= $this->endSection() ?>

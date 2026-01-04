<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Spacing & Typography Balance */
    .card-body {
        padding: 2.5rem;
        /* More breathing room on desktop */
    }

    .divider {
        margin: 2rem 0 1.5rem 0;
    }

    .form-label {
        margin-bottom: 0.5rem;
        color: #566a7f;
        /* Soft text color for readability */
        font-weight: 500;
    }

    /* Consistent Row Spacing */
    .row.mb-3 {
        margin-bottom: 1.5rem !important;
        /* Increase gap between rows for better separation */
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
            padding: 1.5rem !important;
            /* Balanced mobile padding */
        }

        .divider {
            margin: 1.5rem 0 1rem 0;
        }

        .row.mb-3 {
            margin-bottom: 1.25rem !important;
            /* Optimal spacing for mobile scrolling */
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

        #prevBtn,
        #nextBtn,
        #submitBtn {
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
                <h5 class="mb-0 text-primary"><i class='bx bx-edit me-2'></i>แบบฟอร์มสมัครเรียน ชั้นมัธยมศึกษาปีที่
                    <?= $level ?> (Registration Form)
                </h5>
                <div class="text-end">
                    <small class="text-muted d-block">ปีการศึกษา <?= $checkYear->openyear_year ?? '-' ?>
                        <?php if (isset($checkYear->openyear_year) && $checkYear->openyear_year >= 2569): ?>
                            (รอบที่ <?= $systemStatus->onoff_round ?? '1' ?>)
                        <?php endif; ?>
                    </small>
                    <small class="text-muted">กรุณากรอกข้อมูลให้ครบถ้วน</small>
                </div>
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

                <form action="<?= base_url('new-admission/save') ?>" method="post" enctype="multipart/form-data"
                    id="regisForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="recruit_regLevel" value="<?= $level ?>">

                    <!-- Hidden inputs for sports fields - always submitted with form -->
                    <input type="hidden" name="recruit_agegroup" id="recruit_agegroup_hidden" value="">
                    <input type="hidden" name="recruit_sportPosition" id="recruit_sportPosition_hidden" value="">
                    <input type="hidden" name="recruit_nickname" id="recruit_nickname_hidden" value="">
                    <input type="hidden" name="recruit_weight" id="recruit_weight_hidden" value="">
                    <input type="hidden" name="recruit_height" id="recruit_height_hidden" value="">
                    <input type="hidden" name="recruit_fatherName" id="recruit_fatherName_hidden" value="">
                    <input type="hidden" name="recruit_fatherJob" id="recruit_fatherJob_hidden" value="">
                    <input type="hidden" name="recruit_motherName" id="recruit_motherName_hidden" value="">
                    <input type="hidden" name="recruit_motherJob" id="recruit_motherJob_hidden" value="">

                    <!-- Step 1: Quota & Program -->
                    <div class="form-step active" id="step-1">
                        <div class="divider text-start">
                            <div class="divider-text text-primary fw-bold fs-5">1. เลือกประเภทโควตาและแผนการเรียน</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_category" class="form-label">ประเภทโควตา <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-star'></i></span>
                                    <select class="form-select" name="recruit_category" id="recruit_category" required>
                                        <option value="" disabled selected>-- กรุณาเลือกประเภทโควตา --</option>
                                        <?php foreach ($quotas as $quota): ?>
                                            <?php if ($quota->quota_status == 'on' && strpos($quota->quota_level, (string) $level) !== false): ?>
                                                <option value="<?= $quota->quota_id ?>"
                                                    data-courses="<?= $quota->quota_course ?>">
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
                                    <i class='bx bx-info-circle me-1'></i> สำหรับโควตาเขตพื้นที่บริการ
                                    กรุณาระบุโรงเรียนเดิมของท่านเพื่อตรวจสอบสิทธิ์
                                </div>
                                <label for="service_area_school_search" class="form-label">ค้นหาโรงเรียนเดิม
                                    (เฉพาะในเขตพื้นที่บริการ) <span class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bxs-school'></i></span>
                                    <select class="form-select" id="service_area_school_search" style="width: 100%;">
                                        <option value="">-- พิมพ์ชื่อโรงเรียนเพื่อค้นหา --</option>
                                    </select>
                                </div>
                                <div class="form-text text-danger">* ต้องเลือกโรงเรียนจากรายการที่ปรากฏเท่านั้น
                                    หากไม่พบแสดงว่าไม่อยู่ในเขตพื้นที่บริการ</div>
                            </div>
                        </div>

                        <div class="row mb-3" id="course_section" style="display:none;">
                            <div class="col-sm-12">
                                <label class="form-label">เลือกแผนการเรียน (เลือกได้สูงสุด 3 อันดับ/ นักกีฬาเลือกได้ 1 อันดับ) <span
                                        class="text-danger">*</span></label>

                                <div class="mb-2 input-group flex-nowrap" id="rank_container_1">
                                    <span class="input-group-text" style="min-width: 80px;">อันดับ 1</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom1"
                                        id="recruit_tpyeRoom1" required>
                                        <option value="" selected disabled>-- เลือกอันดับ 1 --</option>
                                    </select>
                                </div>

                                <div class="mb-2 input-group flex-nowrap" id="rank_container_2">
                                    <span class="input-group-text" style="min-width: 80px;">อันดับ 2</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom2"
                                        id="recruit_tpyeRoom2">
                                        <option value="" selected disabled>-- เลือกอันดับ 2 --</option>
                                    </select>
                                </div>

                                <div class="mb-2 input-group flex-nowrap" id="rank_container_3">
                                    <span class="input-group-text" style="min-width: 80px;">อันดับ 3</span>
                                    <select class="form-select course-select" name="recruit_tpyeRoom3"
                                        id="recruit_tpyeRoom3">
                                        <option value="" selected disabled>-- เลือกอันดับ 3 --</option>
                                    </select>
                                </div>
                                <!-- Sports Info Section (Hidden by default, shown when sports course is selected) -->
                                <div id="sports_info_section" style="display:none;" class="mt-4">
                                    <div class="divider text-start">
                                        <div class="divider-text text-primary fw-bold">
                                            <i class='bx bx-run me-1'></i> ข้อมูลเพิ่มเติมสำหรับนักกีฬา
                                        </div>
                                    </div>
                                    <div id="age_radio_container" style="display:none;" class="mb-4">
                                        <!-- Will be populated by JS -->
                                    </div>


                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <label for="recruit_sportPosition" class="form-label">สมัครชนิดกีฬาในตำแหน่ง
                                                <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class='bx bx-trophy'></i></span>
                                                <input type="text" class="form-control sport-field"
                                                    data-field="recruit_sportPosition" id="recruit_sportPosition_input"
                                                    placeholder="ระบุตำแหน่งที่สมัคร (เช่น กองหน้า, ผู้รักษาประตู)">
                                            </div>
                                            <div class="form-text text-muted small">* ระบุเฉพาะกีฬาฟุตบอล/ฟุตซอล
                                                กีฬาชนิดอื่นใส่เครื่องหมาย -</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label for="recruit_nickname" class="form-label">ชื่อเล่น <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class='bx bx-smile'></i></span>
                                                <input type="text" class="form-control sport-field"
                                                    data-field="recruit_nickname" id="recruit_nickname_input"
                                                    placeholder="ชื่อเล่น">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="recruit_weight" class="form-label">น้ำหนัก <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.1" class="form-control sport-field"
                                                    data-field="recruit_weight" id="recruit_weight_input"
                                                    placeholder="0.0">
                                                <span class="input-group-text">กก.</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="recruit_height" class="form-label">ส่วนสูง <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.1" class="form-control sport-field"
                                                    data-field="recruit_height" id="recruit_height_input"
                                                    placeholder="0.0">
                                                <span class="input-group-text">ซม.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <label for="recruit_fatherName" class="form-label">ชื่อ-นามสกุล บิดา <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class='bx bx-user'></i></span>
                                                <input type="text" class="form-control sport-field"
                                                    data-field="recruit_fatherName" id="recruit_fatherName_input"
                                                    placeholder="ชื่อ-นามสกุล บิดา">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="recruit_fatherJob" class="form-label">อาชีพ บิดา <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control sport-field"
                                                    data-field="recruit_fatherJob" id="recruit_fatherJob_input"
                                                    placeholder="อาชีพ">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <label for="recruit_motherName" class="form-label">ชื่อ-นามสกุล มารดา <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class='bx bx-user-voice'></i></span>
                                                <input type="text" class="form-control sport-field"
                                                    data-field="recruit_motherName" id="recruit_motherName_input"
                                                    placeholder="ชื่อ-นามสกุล มารดา">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="recruit_motherJob" class="form-label">อาชีพ มารดา <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control sport-field"
                                                    data-field="recruit_motherJob" id="recruit_motherJob_input"
                                                    placeholder="อาชีพ">
                                            </div>
                                        </div>
                                    </div>
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
                                <label class="form-label fw-bold">รูปถ่ายนักเรียน (ชุดนักเรียน) <span
                                        class="text-danger">*</span></label>
                                <div class="card shadow-sm">
                                    <div class="card-body text-center p-3">
                                        <div class="mb-3">
                                            <img id="preview_img_display"
                                                src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                                alt="รูปถ่ายนักเรียน" class="d-block rounded mx-auto"
                                                style="width: 150px; height: 200px; object-fit: contain; border: 2px dashed #d9dee3;">
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm w-100"
                                            onclick="document.getElementById('recruit_img_input').click()">
                                            <i class='bx bx-camera me-1'></i> อัปโหลดรูปถ่าย
                                        </button>
                                        <input type="file" id="recruit_img_input" accept="image/*" class="d-none"
                                            onchange="handleImageSelect(this)">
                                        <input type="hidden" name="recruit_img_cropped" id="recruit_img_cropped">
                                        <!-- Hidden input for validation -->
                                        <input type="text" id="recruit_img_validator" name="recruit_img_validator"
                                            style="opacity: 0; position: absolute; width: 1px; height: 1px;" required>
                                    </div>
                                </div>
                                <div class="form-text mt-2">รูปถ่ายหน้าตรง ชุดนักเรียน ขนาด 1.5 นิ้ว</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <label for="recruit_prefix" class="form-label">คำนำหน้า <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_prefix" id="recruit_prefix" required>
                                    <option value="">เลือก</option>
                                    <option value="เด็กชาย">เด็กชาย</option>
                                    <option value="เด็กหญิง">เด็กหญิง</option>
                                    <option value="นาย">นาย</option>
                                    <option value="นางสาว">นางสาว</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="recruit_firstName" class="form-label">ชื่อ <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                                    <input type="text" class="form-control" name="recruit_firstName"
                                        id="recruit_firstName" placeholder="ชื่อจริง" required>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <label for="recruit_lastName" class="form-label">นามสกุล <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                                    <input type="text" class="form-control" name="recruit_lastName"
                                        id="recruit_lastName" placeholder="นามสกุล" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_idCard" class="form-label">เลขบัตรประชาชน (13 หลัก) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-id-card'></i></span>
                                    <input type="text" class="form-control" name="recruit_idCard" id="recruit_idCard"
                                        maxlength="17" required placeholder="เลขบัตรประชาชน 13 หลัก"
                                        value="<?= isset($preCheckIdCard) ? $preCheckIdCard : '' ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="form-label mb-2">วันเดือนปีเกิด <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-select" name="recruit_birthdayD" id="recruit_birthdayD" required>
                                    <option value="">วัน</option>
                                    <?php for ($i = 1; $i <= 31; $i++): ?>
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
                                    <?php $curYear = date('Y') + 543;
                                    for ($i = $curYear - 20; $i <= $curYear - 10; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <label for="recruit_race" class="form-label">เชื้อชาติ <span
                                        class="text-danger">*</span></label>
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
                                <label for="recruit_nationality" class="form-label">สัญชาติ <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" name="recruit_nationality" id="recruit_nationality"
                                    required>
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
                                <label for="recruit_religion" class="form-label">ศาสนา <span
                                        class="text-danger">*</span></label>
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
                                <label for="recruit_phone" class="form-label">เบอร์โทรศัพท์ <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-phone'></i></span>
                                    <input type="tel" class="form-control" name="recruit_phone" id="recruit_phone"
                                        placeholder="0x-xxxx-xxxx" maxlength="12" required>
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
                                <label for="recruit_homeNumber" class="form-label">บ้านเลขที่ <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-home'></i></span>
                                    <input type="text" class="form-control" name="recruit_homeNumber"
                                        id="recruit_homeNumber" placeholder="บ้านเลขที่" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeGroup" class="form-label">หมู่ที่ <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeGroup" id="recruit_homeGroup"
                                    placeholder="หมู่ที่" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homeRoad" class="form-label">ถนน</label>
                                <input type="text" class="form-control" name="recruit_homeRoad" id="recruit_homeRoad"
                                    placeholder="ถนน">
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeSubdistrict" class="form-label">ตำบล/แขวง <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeSubdistrict"
                                    id="recruit_homeSubdistrict" placeholder="ตำบล/แขวง" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homedistrict" class="form-label">อำเภอ/เขต <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homedistrict"
                                    id="recruit_homedistrict" placeholder="อำเภอ/เขต" required>
                            </div>
                            <div class="col-md-6">
                                <label for="recruit_homeProvince" class="form-label">จังหวัด <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_homeProvince"
                                    id="recruit_homeProvince" placeholder="จังหวัด" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recruit_homePostcode" class="form-label">รหัสไปรษณีย์ <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-map-pin'></i></span>
                                    <input type="text" class="form-control" name="recruit_homePostcode"
                                        id="recruit_homePostcode" placeholder="รหัสไปรษณีย์" required>
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
                                <label for="recruit_oldSchool_select" class="form-label">ค้นหาโรงเรียนเดิม <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bxs-school'></i></span>
                                    <?php if (isset($preCheckOldSchool) && !empty($preCheckOldSchool)): ?>
                                            <input type="text" class="form-control" value="<?= $preCheckOldSchool ?>" readonly>
                                            <input type="hidden" name="recruit_oldSchool" id="recruit_oldSchool"
                                                value="<?= $preCheckOldSchool ?>">
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
                                <label for="recruit_district" class="form-label">อำเภอที่ตั้งโรงเรียน <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_district" id="recruit_district"
                                    placeholder="อำเภอ" required
                                    value="<?= isset($preCheckDistrict) ? $preCheckDistrict : '' ?>"
                                    <?= isset($preCheckDistrict) && !empty($preCheckDistrict) ? 'readonly' : '' ?>>
                            </div>
                            <div class="col-sm-6">
                                <label for="recruit_province" class="form-label">จังหวัดที่ตั้งโรงเรียน <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="recruit_province" id="recruit_province"
                                    placeholder="จังหวัด" required
                                    value="<?= isset($preCheckProvince) ? $preCheckProvince : '' ?>"
                                    <?= isset($preCheckProvince) && !empty($preCheckProvince) ? 'readonly' : '' ?>>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label for="recruit_grade" class="form-label">เกรดเฉลี่ยสะสม (GPAX) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class='bx bx-bar-chart-alt-2'></i></span>
                                    <input type="number" step="0.01" min="0" max="4.00" class="form-control"
                                        name="recruit_grade" id="recruit_grade" placeholder="เช่น 3.50" required>
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
                            <i class='bx bx-info-circle me-1'></i> กรุณาอัปโหลดไฟล์ภาพเฉพาะฟอร์แมต <b>.jpg, .jpeg, .png</b> หรือ <b>.pdf</b> เท่านั้น (ไม่รองรับไฟล์ HEIC จาก iPhone)
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="recruit_certificateEdu" class="form-label">ปพ.1 (หน้า) <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file"
                                    name="recruit_certificateEdu" accept=".jpg,.jpeg,.png,.pdf" required
                                    onchange="previewImage(this, 'preview_certificate')">
                                <div class="mt-2 text-center">
                                    <img id="preview_certificate" src="#" alt="ตัวอย่าง ปพ.1 (หน้า)"
                                        class="img-thumbnail d-none" style="max-height: 200px;">
                                    <p id="preview_certificate_name" class="d-none text-muted small"></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recruit_certificateEduB" class="form-label">ปพ.1 (หลัง) <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="recruit_certificateEduB"
                                    name="recruit_certificateEduB" accept=".jpg,.jpeg,.png,.pdf" required
                                    onchange="previewImage(this, 'preview_certificateB')">
                                <div class="mt-2 text-center">
                                    <img id="preview_certificateB" src="#" alt="ตัวอย่าง ปพ.1 (หลัง)"
                                        class="img-thumbnail d-none" style="max-height: 200px;">
                                    <p id="preview_certificateB_name" class="d-none text-muted small"></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recruit_copyidCard" class="form-label">สำเนาบัตรประชาชน <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="recruit_copyidCard"
                                    name="recruit_copyidCard" accept=".jpg,.jpeg,.png,.pdf" required
                                    onchange="previewImage(this, 'preview_idcard')">
                                <div class="mt-2 text-center">
                                    <img id="preview_idcard" src="#" alt="ตัวอย่างบัตรประชาชน"
                                        class="img-thumbnail d-none" style="max-height: 200px;">
                                    <p id="preview_idcard_name" class="d-none text-muted small"></p>
                                </div>
                            </div>
                        </div>

                        <!-- CAPTCHA Section -->
                        <div class="mt-4 p-4"
                            style="background: linear-gradient(135deg, rgba(255, 158, 181, 0.1) 0%, rgba(132, 210, 246, 0.1) 100%); border-radius: 15px; border: 2px solid rgba(255, 158, 181, 0.2);">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <label class="form-label fw-bold text-primary mb-3">
                                        <i class='bx bx-shield-quarter me-2'></i>ยืนยันว่าคุณไม่ใช่โปรแกรมอัตโนมัติ
                                        (CAPTCHA) <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="captcha-question p-3 rounded-3 text-center"
                                            style="background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%); min-width: 180px;">
                                            <span class="text-white fw-bold fs-4"
                                                id="captcha_question"><?= $captcha_num1 ?> + <?= $captcha_num2 ?> =
                                                ?</span>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            id="refreshCaptchaBtn" title="รีเฟรช CAPTCHA">
                                            <i class='bx bx-refresh fs-5'></i>
                                        </button>
                                    </div>
                                    <input type="hidden" id="captcha_num1" value="<?= $captcha_num1 ?>">
                                    <input type="hidden" id="captcha_num2" value="<?= $captcha_num2 ?>">
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bx-calculator'></i></span>
                                        <input type="number" class="form-control" name="captcha_answer"
                                            id="captcha_answer" placeholder="กรอกคำตอบ" required min="0" max="100">
                                    </div>
                                    <small class="text-muted">กรุณากรอกผลลัพธ์ของการคำนวณ</small>
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
                            <button type="button" class="btn btn-success" id="submitBtn" style="display:none;">
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
<script type="text/javascript"
    src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script type="text/javascript"
    src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<link rel="stylesheet"
    href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script type="text/javascript"
    src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>

<!-- Cropper.js Dependencies -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        // CAPTCHA Refresh Functionality
        $('#refreshCaptchaBtn').on('click', function () {
            const num1 = Math.floor(Math.random() * 10) + 1;
            const num2 = Math.floor(Math.random() * 10) + 1;
            const answer = num1 + num2;

            // Update UI
            $('#captcha_question').text(num1 + ' + ' + num2 + ' = ?');
            $('#captcha_num1').val(num1);
            $('#captcha_num2').val(num2);
            $('#captcha_answer').val('');

            // Disable submit button immediately
            if (typeof checkCaptchaClientSide === 'function') {
                checkCaptchaClientSide();
            } else {
                // Fallback if function not defined yet (should be fine as it's defined later but hoisted)
                // Or we can just disable manually
                $('#submitBtn').prop('disabled', true);
            }

            // Update server session via AJAX
            $.ajax({
                url: '<?= base_url('new-admission/refresh-captcha') ?>',
                type: 'POST',
                data: { num1: num1, num2: num2 },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        // Animate refresh button
                        $('#refreshCaptchaBtn i').addClass('bx-spin');
                        setTimeout(() => {
                            $('#refreshCaptchaBtn i').removeClass('bx-spin');
                        }, 500);
                    }
                },
                error: function () {
                    console.log('Failed to refresh CAPTCHA on server');
                }
            });
        });

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

        $('#confirmSubmitBtn').on('click', function () {
            const $btn = $(this);
            const originalBtnText = $btn.html();

            // Sync visible sport fields to hidden inputs before submit
            document.querySelectorAll('.sport-field').forEach(input => {
                const fieldName = input.dataset.field;
                if (fieldName) {
                    const hiddenInput = document.getElementById(fieldName + '_hidden');
                    if (hiddenInput) {
                        hiddenInput.value = input.value;
                    }
                }
            });

            // Disable button and show loading
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>กำลังบันทึก...');

            // Hide modal
            confirmModal.hide();

            // Show loading
            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                html: '<div class="mb-3"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div></div><p class="mb-0">กรุณารอสักครู่ ระบบกำลังอัปโหลดไฟล์และบันทึกข้อมูล</p>',
                allowOutsideClick: false,
                showConfirmButton: false
            });

            var formData = new FormData($('#regisForm')[0]);


            $.ajax({
                url: $('#regisForm').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    } else {
                        // Check if it's a CAPTCHA error
                        var isCaptchaError = response.message && (
                            response.message.includes('CAPTCHA') ||
                            response.message.includes('รหัสยืนยัน') ||
                            response.message.includes('เซสชัน')
                        );

                        Swal.fire({
                            icon: 'error',
                            title: isCaptchaError ? 'CAPTCHA ไม่ถูกต้อง' : 'เกิดข้อผิดพลาด',
                            text: response.message,
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            if (isCaptchaError) {
                                // Refresh CAPTCHA
                                $('#refreshCaptchaBtn').click();
                                // Clear CAPTCHA input
                                $('#captcha_answer').val('').focus();
                            }
                        });

                        // Reset button
                        $btn.prop('disabled', false).html(originalBtnText);
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้: ' + error,
                        confirmButtonText: 'ตกลง'
                    });

                    // Reset button
                    $btn.prop('disabled', false).html(originalBtnText);
                }
            });
        });

        $('#submitBtn').on('click', function (e) {
            e.preventDefault(); // Prevent default button action
            const $btn = $(this);
            const originalText = $btn.html();

            // Show loading
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>กำลังตรวจสอบ...');

            setTimeout(() => {
                if (validateStep(currentStep)) {
                    // Reset button before showing modal
                    $btn.prop('disabled', false).html(originalText);
                    showConfirmationModal(confirmModal);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ข้อมูลยังไม่ครบถ้วน',
                        text: 'กรุณาตรวจสอบข้อมูลในขั้นตอนสุดท้ายให้ครบถ้วนก่อนยืนยัน',
                        confirmButtonText: 'ตกลง'
                    });
                    $btn.prop('disabled', false).html(originalText);
                }
            }, 300);
        });
    });

    // Image Cropping Logic
    let cropper;
    const imageToCrop = document.getElementById('image_to_crop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    function handleImageSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                console.error('Invalid file type selected:', file.type);
                Swal.fire({
                    icon: 'error',
                    title: 'ชนิดไฟล์รูปภาพไม่ถูกต้อง',
                    text: 'กรุณาเลือกไฟล์รูปภาพที่เป็น JPG, PNG หรือ GIF เท่านั้น',
                    confirmButtonText: 'ตกลง'
                });
                input.value = ''; // Clear the input
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                imageToCrop.src = e.target.result;
                cropModal.show();
            };

            reader.onerror = function (error) {
                console.error('Error reading image file:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาดในการอ่านไฟล์รูปภาพ',
                    text: 'ไม่สามารถอ่านไฟล์รูปภาพได้ โปรดลองใหม่อีกครั้ง หรือเลือกไฟล์อื่น',
                    confirmButtonText: 'ตกลง'
                });
                input.value = ''; // Clear the input
            };

            reader.readAsDataURL(file);

            input.value = ''; // Clear the input after processing to allow selecting same file again if needed
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

    document.getElementById('crop_btn').addEventListener('click', function () {
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
                reader.onload = function (e) {
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

    function previewMultipleFiles(input, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const col = document.createElement('div');
                col.className = 'col-3 text-center';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        col.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="height: 60px; width: 100%; object-fit: cover;">
                            <p class="small text-truncate mb-0" style="font-size: 10px;">${file.name}</p>
                        `;
                    }
                    reader.readAsDataURL(file);
                } else {
                    col.innerHTML = `
                        <div class="p-1 border rounded bg-light" style="height: 60px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <i class='bx bxs-file-pdf fs-3 text-danger'></i>
                        </div>
                        <p class="small text-truncate mb-0" style="font-size: 10px;">${file.name}</p>
                    `;
                }
                container.appendChild(col);
            });
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

    document.getElementById('recruit_category').addEventListener('change', function () {
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

        // Get existing elements from HTML (now inside sports_info_section)
        let ageRadioContainer = document.getElementById('age_radio_container');
        let ageGroupInput = document.getElementById('recruit_agegroup_hidden');

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

            // ตรวจสอบว่าเป็นโควตานักกีฬาหรือไม่จากการเลือก recruit_category
            const selectedQuotaOption = document.getElementById('recruit_category').selectedOptions[0];
            const selectedQuotaName = selectedQuotaOption ? selectedQuotaOption.text : '';
            // ใช้ regex หรือตรวจสอบคำว่า "กีฬา" เพื่อความครอบคลุม
            const isSportsQuotaCategory = selectedQuotaName.includes('กีฬา') || selectedQuotaName.includes('นักกีฬา');

            console.log('Is Sports Quota:', isSportsQuotaCategory);

            if (isSportsQuotaCategory) {
                Swal.fire({
                    icon: 'info',
                    title: 'คำแนะนำสำหรับโควตานักกีฬา',
                    html: '<div class="text-start">' +
                        'เมื่อเลือกประเภทโควตาความสามารถพิเศษทางกีฬา:<br>' +
                        '1. ท่านจะเลือกได้เพียง 1 ประเภทกีฬาเท่านั้น (ไม่มีการจัดอันดับ)<br>' +
                        '2. ท่านจะไม่สามารถเลือกแผนการเรียนปกติในอันดับอื่นได้<br>' +
                        '<br><b>ขอให้ท่านพิจารณาอย่างถี่ถ้วนก่อนเลือกประเภทโควตานี้ครับ</b>' +
                        '</div>',
                    confirmButtonText: 'รับทราบ',
                    confirmButtonColor: '#3085d6'
                });
            }

            // Set Label and Visibility based on Quota Type
            const rank1 = document.getElementById('rank_container_1');
            const rank2 = document.getElementById('rank_container_2');
            const rank3 = document.getElementById('rank_container_3');

            console.log('rank1 element:', rank1);
            console.log('rank2 element:', rank2);
            console.log('rank3 element:', rank3);

            if (isSportsQuotaCategory) {
                console.log('>>> Entering SPORTS quota branch - HIDING ranks');
                // โควตานักกีฬา - ไม่ต้องเลือกแผนการเรียน (ซ่อนทั้งหมด)
                courseLabel.style.display = 'none';

                // ซ่อนทั้งอันดับ 1, 2 และ 3 แบบเจาะจง (ใช้ important เพื่อบดบัง CSS เดิม)
                if (rank1) rank1.style.setProperty('display', 'none', 'important');
                if (rank2) rank2.style.setProperty('display', 'none', 'important');
                if (rank3) rank3.style.setProperty('display', 'none', 'important');

                // ปิด required และเคลียร์ค่า หรือเลือกอันแรกให้อัตโนมัติถ้ามี
                courseSelects[0].required = false;
                courseSelects[1].required = false;
                courseSelects[2].required = false;

                if (allowedCourses.length > 0 && allowedCourses[0] !== '') {
                    courseSelects[0].value = allowedCourses[0];
                }
            } else {
                // โควตาปกติ - เลือกได้สูงสุด 3 อันดับ
                courseLabel.style.display = 'block';
                courseLabel.innerHTML = 'เลือกแผนการเรียน (เลือกได้สูงสุด 3 อันดับ) <span class="text-danger">*</span>';

                // แสดงทั้ง 3 อันดับ
                if (rank1) rank1.style.setProperty('display', 'flex', 'important');
                if (rank2) rank2.style.setProperty('display', 'flex', 'important');
                if (rank3) rank3.style.setProperty('display', 'flex', 'important');

                // เปิด required
                courseSelects[0].required = true;
                courseSelects[1].required = true;
                courseSelects[2].required = true;
            }

            // ซ่อนข้อมูลกีฬาเมื่อเปลี่ยนโควตา (จะแสดงเมื่อเลือกแผนการเรียนกีฬาใน อันดับ 1)
            const sportsInfoSection = document.getElementById('sports_info_section');
            if (sportsInfoSection) sportsInfoSection.style.display = 'none';
            ['sportPosition', 'nickname', 'weight', 'height', 'fatherName', 'motherName', 'fatherJob', 'motherJob'].forEach(field => {
                const el = document.getElementById('recruit_' + field + '_input');
                if (el) {
                    el.required = false;
                    el.value = '';
                }
            });

            // Restore names (in case they were modified elsewhere, good practice)
            courseSelects[0].setAttribute('name', 'recruit_tpyeRoom1');
            courseSelects[1].setAttribute('name', 'recruit_tpyeRoom2');
            courseSelects[2].setAttribute('name', 'recruit_tpyeRoom3');

            // Restore input group text visibility (just in case - for normal quota)
            if (!isSportsQuotaCategory) {
                if (rank1) rank1.querySelector('.input-group-text').style.display = 'block';
                if (rank2) rank2.querySelector('.input-group-text').style.display = 'block';
                if (rank3) rank3.querySelector('.input-group-text').style.display = 'block';
            }

            ageRadioContainer.style.display = 'none';

            // Populate all dropdowns (Logic populate ยังเหมือนเดิม)
            courseSelects.forEach((select, index) => {
                // ถ้าเป็นโควตานักกีฬา และเป็น index 1 หรือ 2 (อันดับ 2-3) ข้ามการ populate ก็ได้ หรือ populate ทิ้งไว้แต่ซ่อน
                if (isSportsQuotaCategory && index > 0) {
                    select.innerHTML = `<option value="" selected disabled>-- เลือกอันดับ ${index + 1} --</option>`;
                    return;
                }

                select.innerHTML = `<option value="" selected disabled>-- เลือกอันดับ ${index + 1} --</option>`;
                coursesData.forEach(course => {
                    if (allowedCourses.includes(course.course_id.toString())) {
                        const option = document.createElement('option');
                        option.value = course.course_id;
                        // Display: Initials - Branch
                        const initials = course.course_initials || course.course_fullname;
                        const branch = course.course_branch || '';
                        option.text = `${initials} ${branch ? '(' + branch + ')' : ''}`;
                        select.appendChild(option);
                    }
                });
            });

            // After population, if it's Sports Quota, auto-select the first allowed course
            if (isSportsQuotaCategory && allowedCourses.length > 0 && allowedCourses[0] !== '') {
                courseSelects[0].value = allowedCourses[0];
                // Trigger change to handle any side effects (like age group radios)
                const event = new Event('change');
                courseSelects[0].dispatchEvent(event);
            }

            // Event Listeners และ Logic อื่นๆ (เหมือนเดิม)

            // เพิ่ม event listener สำหรับอันดับ 1 เพื่อตรวจสอบว่าเป็นกีฬาหรือไม่
            courseSelects[0].addEventListener('change', function () {
                const selectedCourseId = this.value;
                const selectElement = this;

                const selectedCourse = coursesData.find(c => c.course_id == selectedCourseId);

                if (!selectedCourse) return;

                // ตรวจสอบว่าแผนการเรียนที่เลือกเป็น "กีฬา" หรือไม่
                const courseName = selectedCourse.course_initials || selectedCourse.course_fullname || '';
                const courseBranch = selectedCourse.course_branch || '';
                const isSportsCourse = courseName.includes('กีฬา') || courseBranch.includes('กีฬา');

                console.log('=== DEBUG Course Selection ===');
                console.log('Course Name:', courseName);
                console.log('Course Branch:', courseBranch);
                console.log('Is Sports Course:', isSportsCourse);

                // Get rank containers and sports info section
                const rank2 = document.getElementById('rank_container_2');
                const rank3 = document.getElementById('rank_container_3');
                const sportsInfoSection = document.getElementById('sports_info_section');

                if (isSportsCourse) {
                    console.log('>>> SPORTS course selected - HIDING rank 2 & 3');
                    // ซ่อนอันดับ 2 และ 3
                    if (rank2) rank2.style.setProperty('display', 'none', 'important');
                    if (rank3) rank3.style.setProperty('display', 'none', 'important');
                    courseSelects[1].required = false;
                    courseSelects[2].required = false;
                    courseSelects[1].value = '';
                    courseSelects[2].value = '';

                    // แสดงช่องข้อมูลกีฬา (Section ใหม่)
                    if (sportsInfoSection) sportsInfoSection.style.display = 'block';

                    // ตั้ง required สำหรับ fields ในกีฬา
                    ['sportPosition', 'nickname', 'weight', 'height', 'fatherName', 'motherName', 'fatherJob', 'motherJob'].forEach(field => {
                        const el = document.getElementById('recruit_' + field + '_input');
                        if (el) el.required = true;
                    });
                } else {
                    console.log('>>> Normal course selected - SHOWING rank 2 & 3');
                    // แสดงอันดับ 2 และ 3
                    if (rank2) rank2.style.setProperty('display', 'flex', 'important');
                    if (rank3) rank3.style.setProperty('display', 'flex', 'important');
                    courseSelects[1].required = true;
                    courseSelects[2].required = true;

                    // ซ่อนช่องข้อมูลกีฬา
                    if (sportsInfoSection) sportsInfoSection.style.display = 'none';

                    // ปิด required และเคลียร์ค่า
                    ['sportPosition', 'nickname', 'weight', 'height', 'fatherName', 'motherName', 'fatherJob', 'motherJob'].forEach(field => {
                        const el = document.getElementById('recruit_' + field + '_input');
                        if (el) {
                            el.required = false;
                            el.value = '';
                        }
                    });
                }

                // ตรวจสอบ duplicate (เฉพาะถ้าไม่ใช่กีฬา)
                if (!isSportsCourse) {
                    const otherSelects = [courseSelects[1], courseSelects[2]];
                    const isDuplicate = otherSelects.some(s => s.value === selectedCourseId && selectedCourseId !== '');
                    if (isDuplicate) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เลือกซ้ำ',
                            text: 'ท่านได้เลือกแผนการเรียนนี้ไปแล้ว กรุณาเลือกแผนการเรียนอื่น',
                            confirmButtonText: 'ตกลง'
                        });
                        selectElement.value = '';
                        return;
                    }
                }

                // Logic ตรวจสอบเกรด
                let gradeRequirement = null;
                let requiredGPA = 0;

                if (courseName.includes('วิทย์-คณิต') || courseName.includes('วิทย์ - คณิต')) {
                    gradeRequirement = 'วิทย์-คณิต';
                    requiredGPA = 3.00;
                } else if (courseName.includes('วิทย์-เทคโน') || courseName.includes('วิทย์ - เทคโน')) {
                    gradeRequirement = 'วิทย์-เทคโน';
                    requiredGPA = 2.75;
                }

                // ... (SweetAlert เกรดเฉลี่ย) ...
                if (gradeRequirement) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'เงื่อนไขเกรดเฉลี่ย',
                        html: `
                            <div class="text-start">
                                <p class="mb-2">แผนการเรียน <strong>${gradeRequirement}</strong> มีเงื่อนไขดังนี้:</p>
                                <ul class="mb-3">
                                    <li>เกรดเฉลี่ย 5 ภาคเรียน ต้อง <strong>${requiredGPA.toFixed(2)} ขึ้นไป</strong></li>
                                </ul>
                                <p class="text-danger mb-0">
                                    <i class="bx bx-info-circle"></i> 
                                    หากเกรดเฉลี่ยของท่านไม่ถึงเกณฑ์ กรุณาเลือกแผนการเรียนอื่น
                                </p>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน (เกรดของฉันถึงเกณฑ์)',
                        cancelButtonText: 'ย้อนกลับเลือกแผนอื่น',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            selectElement.value = '';
                            if (!isSportsQuotaCategory) {
                                courseSelects[1].value = '';
                                courseSelects[2].value = '';
                            }
                            ageRadioContainer.style.display = 'none';
                            ageRadioContainer.innerHTML = '';
                            ageGroupInput.value = '';
                            return;
                        }
                        processCourseSelection(selectedCourse);
                    });
                } else {
                    processCourseSelection(selectedCourse);
                }

                function processCourseSelection(selectedCourse) {
                    // Logic เดิมของการแสดง Age (ถ้า Course มี course_age)

                    if (selectedCourse && selectedCourse.course_age && selectedCourse.course_age.trim() !== '') {
                        // ถ้า Course มีอายุ (เช่นแผนนักกีฬา)
                        // แสดงช่วงอายุ
                        ageRadioContainer.innerHTML = '<label class="form-label d-block">เลือกรุ่นอายุ <span class="text-danger">*</span></label>';
                        ageGroupInput.value = '';

                        const ages = selectedCourse.course_age.split(',').map(s => s.trim()).filter(s => s !== '');

                        if (ages.length > 0) {
                            const rowDiv = document.createElement('div');
                            rowDiv.className = 'row g-2';

                            ages.forEach(age => {
                                const colDiv = document.createElement('div');
                                colDiv.className = 'col-auto';

                                const radioDiv = document.createElement('div');
                                radioDiv.className = 'form-check form-check-inline';

                                const radioInput = document.createElement('input');
                                radioInput.className = 'form-check-input';
                                radioInput.type = 'radio';
                                radioInput.name = 'age_radio_group';
                                radioInput.id = 'age_' + age;
                                radioInput.value = age;
                                radioInput.required = true;

                                radioInput.addEventListener('change', function () {
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
                        }
                    } else {
                        // ถ้า Course ปกติ
                        ageRadioContainer.style.display = 'none';
                        ageRadioContainer.innerHTML = '';
                        ageGroupInput.value = '';
                    }

                    // ถ้าไม่ใช่โควตานักกีฬา (isSportsQuotaCategory = false) เราต้องจัดการ reset options อันดับ 2-3 ด้วย
                    if (!isSportsQuotaCategory) {
                        // Populate อันดับ 2 และ 3 ใหม่ โดยกรองแผนการเรียนกีฬาออก (เหมือนเดิม)
                        [courseSelects[1], courseSelects[2]].forEach((select, index) => {
                            const currentValue = select.value;
                            select.innerHTML = `<option value="" selected disabled>-- เลือกอันดับ ${index + 2} --</option>`;
                            coursesData.forEach(course => {
                                if (allowedCourses.includes(course.course_id.toString())) {
                                    const isSportsCourse = course.course_age && course.course_age.trim() !== '';
                                    if (!isSportsCourse) {
                                        const option = document.createElement('option');
                                        option.value = course.course_id;
                                        const initials = course.course_initials || course.course_fullname;
                                        const branch = course.course_branch || '';
                                        option.text = `${initials} ${branch ? '(' + branch + ')' : ''}`;
                                        select.appendChild(option);
                                    }
                                }
                            });
                            if (currentValue && select.querySelector(`option[value="${currentValue}"]`)) {
                                select.value = currentValue;
                            }
                        });
                    }
                }
            });

            // ฟังก์ชันตรวจสอบเงื่อนไขเกรดเฉลี่ย (ใช้ร่วมกันสำหรับทุกอันดับ)
            function checkGradeRequirement(selectedCourse, selectElement, callback) {
                if (!selectedCourse) {
                    callback(false);
                    return;
                }

                const courseName = selectedCourse.course_initials || selectedCourse.course_fullname || '';
                let gradeRequirement = null;
                let requiredGPA = 0;

                if (courseName.includes('วิทย์-คณิต') || courseName.includes('วิทย์ - คณิต')) {
                    gradeRequirement = 'วิทย์-คณิต';
                    requiredGPA = 3.00;
                } else if (courseName.includes('วิทย์-เทคโน') || courseName.includes('วิทย์ - เทคโน')) {
                    gradeRequirement = 'วิทย์-เทคโน';
                    requiredGPA = 2.75;
                }

                if (gradeRequirement) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'เงื่อนไขเกรดเฉลี่ย',
                        html: `
                            <div class="text-start">
                                <p class="mb-2">แผนการเรียน <strong>${gradeRequirement}</strong> มีเงื่อนไขดังนี้:</p>
                                <ul class="mb-3">
                                    <li>เกรดเฉลี่ย 5 ภาคเรียน ต้อง <strong>${requiredGPA.toFixed(2)} ขึ้นไป</strong></li>
                                </ul>
                                <p class="text-danger mb-0">
                                    <i class="bx bx-info-circle"></i> 
                                    หากเกรดเฉลี่ยของท่านไม่ถึงเกณฑ์ กรุณาเลือกแผนการเรียนอื่น
                                </p>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน (เกรดของฉันถึงเกณฑ์)',
                        cancelButtonText: 'ย้อนกลับเลือกแผนอื่น',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            selectElement.value = '';
                            callback(false);
                        } else {
                            callback(true);
                        }
                    });
                } else {
                    callback(true);
                }
            }

            // สร้าง updateOptions function ที่จะใช้ร่วมกัน
            let updateOptionsFunc = null;

            // เพิ่ม event listener สำหรับอันดับ 2
            courseSelects[1].addEventListener('change', function () {
                const selectedCourseId = this.value;
                const selectElement = this;
                const selectedCourse = coursesData.find(c => c.course_id == selectedCourseId);

                // ตรวจสอบว่าเลือกซ้ำหรือไม่
                const otherSelects = [courseSelects[0], courseSelects[2]];
                const isDuplicate = otherSelects.some(s => s.value === selectedCourseId && selectedCourseId !== '');

                if (isDuplicate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เลือกซ้ำ',
                        text: 'ท่านได้เลือกแผนการเรียนนี้ไปแล้ว กรุณาเลือกแผนการเรียนอื่น',
                        confirmButtonText: 'ตกลง'
                    });
                    selectElement.value = '';
                    return;
                }

                checkGradeRequirement(selectedCourse, selectElement, function (confirmed) {
                    if (confirmed && updateOptionsFunc) {
                        updateOptionsFunc();
                    }
                });
            });

            // เพิ่ม event listener สำหรับอันดับ 3
            courseSelects[2].addEventListener('change', function () {
                const selectedCourseId = this.value;
                const selectElement = this;
                const selectedCourse = coursesData.find(c => c.course_id == selectedCourseId);

                // ตรวจสอบว่าเลือกซ้ำหรือไม่
                const otherSelects = [courseSelects[0], courseSelects[1]];
                const isDuplicate = otherSelects.some(s => s.value === selectedCourseId && selectedCourseId !== '');

                if (isDuplicate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เลือกซ้ำ',
                        text: 'ท่านได้เลือกแผนการเรียนนี้ไปแล้ว กรุณาเลือกแผนการเรียนอื่น',
                        confirmButtonText: 'ตกลง'
                    });
                    selectElement.value = '';
                    return;
                }

                checkGradeRequirement(selectedCourse, selectElement, function (confirmed) {
                    if (confirmed && updateOptionsFunc) {
                        updateOptionsFunc();
                    }
                });
            });
        } else {
            courseSection.style.display = 'none';
            courseSelects[0].required = false;
            if (ageRadioContainer) ageRadioContainer.style.display = 'none';
            if (ageRadioContainer) ageRadioContainer.style.display = 'none';
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

        // เชื่อม updateOptionsFunc กับ updateOptions เพื่อให้ event listener อื่นเรียกใช้ได้
        updateOptionsFunc = updateOptions;

        selects.forEach(select => {
            // Remove old listeners to avoid duplicates if called multiple times (though replaceChild handles that mostly)
            // But since we use anonymous function, we can't easily remove. 
            // Better to just ensure we are attaching to fresh elements or handle it.
            // Since we replace elements in the quota change logic, we just need to attach 'change' event.
            select.addEventListener('change', updateOptions);
        });

        // เรียก updateOptions ทันทีเพื่อ initialize
        updateOptions();
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

        const birthday = formData.get('recruit_birthdayD') + '/' + formData.get('recruit_birthdayM') + '/' + formData.get('recruit_birthdayY');

        // Helper function to get field value
        function getFieldValue(key) {
            // For sport fields, check by data-field or _input suffix
            const sportFields = ['recruit_sportPosition', 'recruit_nickname', 'recruit_weight', 'recruit_height', 'recruit_fatherName', 'recruit_motherName', 'recruit_fatherJob', 'recruit_motherJob'];
            if (sportFields.includes(key)) {
                const el = document.getElementById(key + '_input');
                return el ? el.value || '<span class="text-muted">-</span>' : '<span class="text-muted">-</span>';
            }

            const element = document.querySelector(`[name="${key}"]`);
            if (key === 'recruit_birthday') {
                return birthday;
            } else if (element && element.tagName === 'SELECT') {
                if (element.value !== "") {
                    if (key.startsWith('recruit_tpyeRoom')) {
                        const courseId = element.value;
                        const course = coursesData.find(c => c.course_id == courseId);
                        if (course) {
                            const initials = course.course_initials || course.course_fullname;
                            const branch = course.course_branch || '';
                            return `${initials} ${branch ? '(' + branch + ')' : ''}`;
                        }
                    }
                    return element.options[element.selectedIndex].text;
                }
                return '<span class="text-muted">-</span>';
            } else if (key.endsWith('_validator')) {
                return formData.get('recruit_img_cropped') ? '<i class="bx bx-check-circle text-success"></i>' : '<i class="bx bx-x-circle text-danger"></i>';
            } else if (element && element.type === 'file') {
                return element.files.length > 0 ? '<i class="bx bx-check-circle text-success"></i>' : '<span class="text-muted">-</span>';
            }
            return formData.get(key) || '<span class="text-muted">-</span>';
        }

        // Create data item HTML
        function createDataItem(label, value) {
            return `<div class="data-item"><span class="data-label">${label}</span><span class="data-value">${value}</span></div>`;
        }

        // Build grouped HTML
        let html = '';
        const quotaName = getFieldValue('recruit_category');
        const courseId1 = document.querySelector('[name="recruit_tpyeRoom1"]').value;
        const selectedCourse1 = coursesData.find(c => c.course_id == courseId1);
        const isSports = quotaName.includes('นักกีฬา') ||
            (selectedCourse1 && (
                (selectedCourse1.course_fullname && selectedCourse1.course_fullname.includes('กีฬา')) ||
                (selectedCourse1.course_branch && selectedCourse1.course_branch.includes('กีฬา'))
            ));

        // Group 1: ข้อมูลการสมัคร
        html += '<div class="data-group">';
        html += '<div class="data-group-title"><i class="bx bx-bookmark text-primary"></i>ข้อมูลการสมัคร</div>';
        html += createDataItem('ประเภทโควตา', quotaName);
        if (isSports) {
            const ageGroup = formData.get('recruit_agegroup');
            if (ageGroup) {
                html += createDataItem('รุ่นอายุ', ageGroup + ' ปี');
            }
            html += createDataItem('ตำแหน่งที่สมัคร', getFieldValue('recruit_sportPosition'));
        }
        html += createDataItem('แผนการเรียน 1', getFieldValue('recruit_tpyeRoom1'));
        const plan2 = getFieldValue('recruit_tpyeRoom2');
        const plan3 = getFieldValue('recruit_tpyeRoom3');
        if (plan2 && !plan2.includes('text-muted')) html += createDataItem('แผนการเรียน 2', plan2);
        if (plan3 && !plan3.includes('text-muted')) html += createDataItem('แผนการเรียน 3', plan3);
        html += '</div>';

        // Group 2: ข้อมูลส่วนตัว
        html += '<div class="data-group">';
        html += '<div class="data-group-title"><i class="bx bx-user text-success"></i>ข้อมูลส่วนตัว</div>';
        html += createDataItem('ชื่อ-นามสกุล', getFieldValue('recruit_prefix') + getFieldValue('recruit_firstName') + ' ' + getFieldValue('recruit_lastName'));
        if (isSports) {
            html += createDataItem('ชื่อเล่น', getFieldValue('recruit_nickname'));
        }
        html += createDataItem('เลขบัตรประชาชน', getFieldValue('recruit_idCard'));
        html += createDataItem('วันเกิด', getFieldValue('recruit_birthday'));
        if (isSports) {
            html += createDataItem('น้ำหนัก / ส่วนสูง', getFieldValue('recruit_weight') + ' กก. / ' + getFieldValue('recruit_height') + ' ซม.');
        }
        html += createDataItem('เบอร์โทรศัพท์', getFieldValue('recruit_phone'));
        html += createDataItem('เชื้อชาติ/สัญชาติ', getFieldValue('recruit_race') + '/' + getFieldValue('recruit_nationality'));
        html += createDataItem('ศาสนา', getFieldValue('recruit_religion'));
        if (isSports) {
            html += createDataItem('บิดา', getFieldValue('recruit_fatherName') + ' (อาชีพ: ' + getFieldValue('recruit_fatherJob') + ')');
            html += createDataItem('มารดา', getFieldValue('recruit_motherName') + ' (อาชีพ: ' + getFieldValue('recruit_motherJob') + ')');
        }
        html += '</div>';

        // Group 3: ที่อยู่
        html += '<div class="data-group">';
        html += '<div class="data-group-title"><i class="bx bx-home text-warning"></i>ที่อยู่ปัจจุบัน</div>';
        const homeNumber = getFieldValue('recruit_homeNumber');
        const homeGroup = getFieldValue('recruit_homeGroup');
        const homeRoad = getFieldValue('recruit_homeRoad');
        let address = homeNumber;
        if (homeGroup && !homeGroup.includes('text-muted')) address += ' หมู่ ' + homeGroup;
        if (homeRoad && !homeRoad.includes('text-muted')) address += ' ถ.' + homeRoad;
        html += createDataItem('บ้านเลขที่', address);
        html += createDataItem('ตำบล/อำเภอ', getFieldValue('recruit_homeSubdistrict') + '/' + getFieldValue('recruit_homedistrict'));
        html += createDataItem('จังหวัด', getFieldValue('recruit_homeProvince') + ' ' + getFieldValue('recruit_homePostcode'));
        html += '</div>';

        // Group 4: โรงเรียนเดิม
        html += '<div class="data-group">';
        html += '<div class="data-group-title"><i class="bx bx-building text-info"></i>โรงเรียนเดิม</div>';
        html += createDataItem('โรงเรียน', getFieldValue('recruit_oldSchool'));
        html += createDataItem('อำเภอ/จังหวัด', getFieldValue('recruit_district') + ', ' + getFieldValue('recruit_province'));
        html += createDataItem('เกรดเฉลี่ย', '<strong class="text-primary">' + getFieldValue('recruit_grade') + '</strong>');
        html += '</div>';

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

            if (srcImg && !srcImg.classList.contains('d-none')) {
                targetImg.src = srcImg.src;
                targetImg.classList.remove('d-none');
                targetTxt.classList.add('d-none');
            } else if (srcTxt && !srcTxt.classList.contains('d-none')) {
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

        if (step === totalSteps) {
            submitBtn.style.display = 'inline-block';
            checkCaptchaClientSide(); // Initial check when showing step
        } else {
            submitBtn.style.display = 'none';
        }
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
        const originalText = nextBtn.innerHTML;
        nextBtn.disabled = true;
        nextBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>กำลังตรวจสอบ...';

        // Small delay for visual feedback
        setTimeout(() => {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    text: 'โปรดตรวจสอบข้อมูลในช่องที่มีเครื่องหมาย *',
                    confirmButtonText: 'ตกลง'
                });
            }
            nextBtn.disabled = false;
            nextBtn.innerHTML = originalText;
        }, 300);
    });

    prevBtn.addEventListener('click', () => {
        const originalText = prevBtn.innerHTML;
        prevBtn.disabled = true;
        prevBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>กำลังโหลด...';

        // Small delay for visual feedback
        setTimeout(() => {
            currentStep--;
            showStep(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
            prevBtn.disabled = false;
            prevBtn.innerHTML = originalText;
        }, 200);
    });

    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function () {
            if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });
    });

    // Client-side CAPTCHA Check to Enable/Disable Submit Button
    // Client-side CAPTCHA Check to Enable/Disable Submit Button
    function checkCaptchaClientSide() {
        const num1 = parseInt($('#captcha_num1').val()) || 0;
        const num2 = parseInt($('#captcha_num2').val()) || 0;
        const userAnswer = parseInt($('#captcha_answer').val());
        const expectedAnswer = num1 + num2;

        const submitBtn = document.getElementById('submitBtn');
        if (!submitBtn) return;

        if (!isNaN(userAnswer) && userAnswer === expectedAnswer) {
            submitBtn.disabled = false;
            $('#captcha_answer').removeClass('is-invalid').addClass('is-valid');
        } else {
            submitBtn.disabled = true;
            if ($('#captcha_answer').val().length > 0) {
                if (userAnswer.toString().length >= expectedAnswer.toString().length) {
                    $('#captcha_answer').addClass('is-invalid');
                }
            } else {
                $('#captcha_answer').removeClass('is-invalid is-valid');
            }
        }
    }

    // Listen to CAPTCHA input
    $('#captcha_answer').on('input keyup', function () {
        checkCaptchaClientSide();
    });

    // Initialize step
    showStep(1);

    // Phone number formatting
    const phoneInput = document.getElementById('recruit_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            const input = e.target.value.replace(/\D/g, '').substring(0, 10);
            let formatted = '';
            if (input.length > 0) {
                formatted = input.substring(0, 2);
            }
            if (input.length > 2) {
                formatted += '-' + input.substring(2, 6);
            }
            if (input.length > 6) {
                formatted += '-' + input.substring(6, 10);
            }
            e.target.value = formatted;
        });
    }

</script>

<!-- Confirmation Modal - Mobile Friendly -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen-sm-down modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <div>
                    <h5 class="modal-title mb-1" id="confirmModalLabel">
                        <i class="bx bx-check-shield me-2"></i>ตรวจสอบข้อมูลการสมัคร
                    </h5>
                    <small class="opacity-75">กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนยืนยัน</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Warning Alert -->
                <div class="alert alert-warning rounded-0 mb-0 py-2 px-3 border-0 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle fs-4 me-2"></i>
                        <small class="fw-medium">หากยืนยันการสมัครแล้ว จะไม่สามารถกลับมาแก้ไขได้</small>
                    </div>
                </div>

                <!-- Student Photo Section -->
                <div class="text-center p-3 bg-light border-bottom">
                    <img id="confirm_image" src="#" alt="รูปถ่ายนักเรียน"
                        class="rounded-circle border border-3 border-primary shadow-sm d-none"
                        style="width:100px; height:100px; object-fit:cover;" />
                    <p class="mb-0 mt-2 fw-bold text-primary small">รูปถ่ายนักเรียน</p>
                </div>

                <!-- Data List -->
                <div id="confirm-data-list" class="px-3 py-2">
                    <!-- Data will be injected here by JS -->
                </div>

                <!-- Documents Section -->
                <div class="px-3 pb-3">
                    <div class="bg-light rounded-3 p-3">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="bx bx-file text-primary me-2"></i>เอกสารหลักฐาน
                        </h6>
                        <div class="row g-2">
                            <div class="col-4 text-center">
                                <div class="bg-white rounded p-2 h-100">
                                    <img id="confirm_certificate" src="#" class="img-fluid rounded d-none mb-1"
                                        style="max-height:80px; width: auto;">
                                    <p id="confirm_certificate_name" class="small text-muted d-none mb-0"></p>
                                    <small class="text-muted d-block">ปพ.1 (หน้า)</small>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="bg-white rounded p-2 h-100">
                                    <img id="confirm_certificateB" src="#" class="img-fluid rounded d-none mb-1"
                                        style="max-height:80px; width: auto;">
                                    <p id="confirm_certificateB_name" class="small text-muted d-none mb-0"></p>
                                    <small class="text-muted d-block">ปพ.1 (หลัง)</small>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="bg-white rounded p-2 h-100">
                                    <img id="confirm_idcard" src="#" class="img-fluid rounded d-none mb-1"
                                        style="max-height:80px; width: auto;">
                                    <p id="confirm_idcard_name" class="small text-muted d-none mb-0"></p>
                                    <small class="text-muted d-block">สำเนาบัตร</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-column flex-sm-row gap-2 p-3 bg-light">
                <button type="button" class="btn btn-outline-secondary w-100 w-sm-auto order-2 order-sm-1"
                    data-bs-dismiss="modal">
                    <i class="bx bx-edit me-1"></i>แก้ไขข้อมูล
                </button>
                <button type="button" class="btn btn-success w-100 w-sm-auto order-1 order-sm-2 py-2"
                    id="confirmSubmitBtn">
                    <i class="bx bx-check-circle me-1"></i>ยืนยันและสมัครเรียน
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Mobile-friendly Modal Styles */
    @media (max-width: 575.98px) {
        #confirmModal .modal-footer {
            position: sticky;
            bottom: 0;
            z-index: 10;
        }

        #confirmModal .modal-footer .btn {
            font-size: 1rem;
            padding: 0.75rem;
        }
    }

    /* Data List Styles */
    #confirm-data-list .data-group {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }

    #confirm-data-list .data-group-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    #confirm-data-list .data-group-title i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    #confirm-data-list .data-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.35rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        gap: 0.5rem;
    }

    #confirm-data-list .data-item:last-child {
        border-bottom: none;
    }

    #confirm-data-list .data-label {
        font-size: 0.8rem;
        color: #6c757d;
        flex-shrink: 0;
        max-width: 45%;
    }

    #confirm-data-list .data-value {
        font-size: 0.85rem;
        font-weight: 500;
        color: #212529;
        text-align: right;
        word-break: break-word;
    }
</style><?= $this->endSection() ?>
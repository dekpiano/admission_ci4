<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    :root {
        --skj-primary: #5e72e4;
        --skj-secondary: #8392ab;
        --skj-success: #2dce89;
        --skj-info: #11cdef;
        --skj-warning: #fb6340;
        --skj-danger: #f5365c;
    }

    /* Page Layout */
    .container-p-y {
        padding-top: 1.5rem !important;
        padding-bottom: 2rem !important;
    }

    /* Card Styling */
    .card {
        border: 0;
        box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .card-header {
        background-color: transparent;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, .05);
    }

    .card-header .h5 {
        font-weight: 700;
        color: #32325d;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0;
    }

    .card-header .h5 i {
        font-size: 1.5rem;
        color: var(--skj-primary);
    }

    /* Form Elements */
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #525f7f;
        margin-bottom: 0.4rem;
    }

    .form-control,
    .form-select {
        border-radius: 0.5rem;
        padding: 0.6rem 0.75rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--skj-primary);
        box-shadow: 0 3px 9px rgba(5e, 72, e4, .08);
    }

    .input-group-text {
        background-color: #f6f9fc;
        color: var(--skj-secondary);
        border-color: #dee2e6;
    }

    /* Course Selection Alert */
    .alert-premium {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
        border: 0;
        color: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08);
    }

    .alert-premium h6 {
        color: #fff;
    }

    .course-select-row {
        background: #f8f9fe;
        border-radius: 0.75rem;
        padding: 1.5rem;
        border: 1px dashed #cad1d7;
    }

    /* Profile Side Styling */
    .profile-card {
        background: linear-gradient(to bottom, #f8f9fe 0%, #ffffff 100%);
    }

    .photo-preview-wrapper {
        position: relative;
        width: 140px;
        height: 180px;
        margin: 0 auto;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 4px solid #fff;
    }

    .photo-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-edit-btn {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 8px;
        font-size: 0.75rem;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .photo-preview-wrapper:hover .photo-edit-btn {
        opacity: 1;
    }

    /* Documents Grid */
    .doc-card-premium {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1rem;
        height: 100%;
        transition: all 0.2s;
    }

    .doc-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(50, 50, 93, .1), 0 5px 15px rgba(0, 0, 0, .07);
    }

    .doc-preview-container {
        height: 120px;
        background: #f8f9fe;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .doc-preview-container i {
        font-size: 3rem;
        color: #adb5bd;
    }

    /* Status Badge in Select */
    .status-badge-select {
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Actions Card */
    .actions-card {
        background: #32325d;
        color: #fff;
    }

    .actions-card p {
        color: rgba(255, 255, 255, 0.7);
    }

    /* Custom Scrollbar for selects */
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 0.5rem;
        min-height: 42px;
        padding-top: 5px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class='bx bx-edit-alt text-primary me-2'></i>แก้ไขข้อมูลผู้สมัคร</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('skjadmin') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('skjadmin/recruits') ?>">รายชื่อปี
                            <?= $recruit['recruit_year'] ?></a></li>
                    <li class="breadcrumb-item active">ID: <?= esc($recruit['recruit_id']) ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) ?>"
                class="btn btn-label-secondary">
                <i class='bx bx-show me-1'></i> ดูรายละเอียด
            </a>
            <button type="submit" form="editForm" class="btn btn-primary shadow-sm">
                <i class='bx bx-save me-1'></i> บันทึกข้อมูล
            </button>
        </div>
    </div>

    <form action="<?= site_url('skjadmin/recruits/update/' . $recruit['recruit_id']) ?>" method="post"
        enctype="multipart/form-data" id="editForm">
        <?= csrf_field() ?>
        <input type="hidden" name="recruit_id" value="<?= esc($recruit['recruit_id']) ?>">

        <div class="row">
            <!-- Left Side: Data -->
            <div class="col-lg-8">
                <!-- 1. Registration Details -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class='bx bx-id-card'></i> 1. ข้อมูลการสมัครและแผนการเรียน</h5>
                        <span class="badge bg-label-primary fs-6 py-2 px-3">Student ID:
                            <?= $recruit['recruit_id'] ?></span>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">ปีการศึกษา</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-calendar-event'></i></span>
                                    <input type="text" class="form-control bg-light fw-bold"
                                        value="<?= $recruit['recruit_year'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ระดับชั้น <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-layer'></i></span>
                                    <select class="form-select fw-bold text-primary" name="recruit_regLevel" required>
                                        <option value="1" <?= $recruit['recruit_regLevel'] == '1' ? 'selected' : '' ?>>
                                            มัธยมศึกษาปีที่ 1</option>
                                        <option value="4" <?= $recruit['recruit_regLevel'] == '4' ? 'selected' : '' ?>>
                                            มัธยมศึกษาปีที่ 4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">สถานะตรวจสอบ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-check-shield'></i></span>
                                    <select class="form-select fw-bold status-badge-select" name="recruit_status"
                                        required>
                                        <option value="รอตรวจสอบ" <?= $recruit['recruit_status'] == 'รอตรวจสอบ' ? 'selected' : '' ?> class="text-warning">⏳ รอตรวจสอบ</option>
                                        <option value="ผ่านการตรวจสอบ" <?= $recruit['recruit_status'] == 'ผ่านการตรวจสอบ' ? 'selected' : '' ?> class="text-success">✅ ผ่านการตรวจสอบ</option>
                                        <option value="ไม่ผ่านการตรวจสอบ" <?= strpos($recruit['recruit_status'], 'ไม่ผ่าน') !== false ? 'selected' : '' ?> class="text-danger">❌
                                            ไม่ผ่านการตรวจสอบ</option>
                                    </select>
                                </div>
                            </div>

                            <?php
                            // Check if this is a sport applicant
                            $isSportApplicant = false;
                            if (isset($quotas)) {
                                foreach ($quotas as $q) {
                                    if ($q->quota_id == $recruit['recruit_category'] && $q->quota_key === 'sport') {
                                        $isSportApplicant = true;
                                        break;
                                    }
                                }
                            }
                            if (!empty($recruit['recruit_sportPosition']) && $recruit['recruit_sportPosition'] !== '-') {
                                $isSportApplicant = true;
                            }
                            ?>
                            <div class="col-md-12" id="sportSelectionResultSection"
                                style="<?= !$isSportApplicant ? 'display:none;' : '' ?>">
                                <div class="alert alert-warning bg-label-warning border-0 py-3 mb-0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class='bx bx-run fs-3 me-2'></i>
                                        <h6 class="mb-0 fw-bold">ผลการคัดเลือกรอบนักกีฬา</h6>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class='bx bx-trophy text-warning'></i></span>
                                        <select class="form-select fw-bold" name="recruit_sportSelectionResult"
                                            id="recruit_sportSelectionResult">
                                            <option value="รอคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] ?? '') == 'รอคัดเลือก' || empty($recruit['recruit_sportSelectionResult']) ? 'selected' : '' ?>>⏳ รอคัดเลือก</option>
                                            <option value="ผ่านการคัดเลือก" <?= ($recruit['recruit_sportSelectionResult'] ?? '') == 'ผ่านการคัดเลือก' ? 'selected' : '' ?>>✅ ผ่านการคัดเลือก
                                            </option>
                                            <option value="ไม่ผ่านการคัดเลือก"
                                                <?= ($recruit['recruit_sportSelectionResult'] ?? '') == 'ไม่ผ่านการคัดเลือก' ? 'selected' : '' ?>>❌ ไม่ผ่านการคัดเลือก
                                            </option>
                                        </select>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class='bx bx-info-circle me-1'></i>
                                        สถานะนี้ใช้สำหรับบันทึกผลการคัดเลือกรอบความสามารถพิเศษด้านกีฬา
                                    </small>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">ประเภทโควตา / รอบการสมัคร <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class='bx bx-trophy text-warning'></i></span>
                                    <select class="form-select" name="recruit_category" id="recruit_category" required>
                                        <?php if (isset($quotas)): ?>
                                            <?php foreach ($quotas as $quota): ?>
                                                <option value="<?= $quota->quota_id ?>"
                                                    data-courses="<?= $quota->quota_course ?? '' ?>"
                                                    <?= $recruit['recruit_category'] == $quota->quota_id ? 'selected' : '' ?>>
                                                    <?= esc($quota->quota_explain) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="alert alert-premium py-3 px-4 mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white rounded-circle p-2 me-3">
                                            <i class='bx bx-list-ul text-primary fs-4'></i>
                                        </div>
                                        <div>
                                            <h6 class="alert-heading fw-bold mb-1">ลำดับแผนการเรียนที่เลือก</h6>
                                            <div id="level_badge_info" class="small opacity-75">
                                                <?php if ($recruit['recruit_regLevel'] == '1'): ?>
                                                    ม.ต้น เลือกได้ 3 อันดับ
                                                <?php else: ?>
                                                    ม.ปลาย เลือกได้ 3 อันดับ
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="course-select-row">
                                    <div id="course_selection_container">
                                        <div class="mb-3 row align-items-center">
                                            <label class="col-sm-3 col-form-label fw-bold text-dark">อันดับที่ 1 <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-9 text-center">
                                                <select class="form-select course-select" name="recruit_tpyeRoom1"
                                                    id="recruit_tpyeRoom1" required>
                                                    <option value="" disabled>-- เลือกอันดับ 1 --</option>
                                                    <?php
                                                    $currentGrade = ($recruit['recruit_regLevel'] == '1') ? 'ม.ต้น' : 'ม.ปลาย';
                                                    foreach ($courses as $course):
                                                        if ($course->course_gradelevel == $currentGrade):
                                                            ?>
                                                            <option value="<?= $course->course_id ?>"
                                                                <?= (isset($major_order_ids[0]) && $major_order_ids[0] == $course->course_id) ? 'selected' : '' ?>>
                                                                <?= esc($course->course_branch) ?>
                                                            </option>
                                                            <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </select>
                                                <small class="text-primary mt-1 d-block"><i
                                                        class='bx bx-star me-1'></i>แผนการเรียนหลักสำหรับใช้ประมวลผล</small>
                                            </div>
                                        </div>

                                        <div class="mb-3 row align-items-center border-top pt-3">
                                            <label class="col-sm-3 col-form-label fw-semibold text-muted">อันดับที่
                                                2</label>
                                            <div class="col-sm-9">
                                                <select class="form-select course-select" name="recruit_tpyeRoom2"
                                                    id="recruit_tpyeRoom2">
                                                    <option value="">-- ไม่ระบุ --</option>
                                                    <?php foreach ($courses as $course):
                                                        if ($course->course_gradelevel == $currentGrade):
                                                            ?>
                                                            <option value="<?= $course->course_id ?>"
                                                                <?= (isset($major_order_ids[1]) && $major_order_ids[1] == $course->course_id) ? 'selected' : '' ?>>
                                                                <?= esc($course->course_branch) ?>
                                                            </option>
                                                            <?php
                                                        endif;
                                                    endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row align-items-center border-top pt-3">
                                            <label class="col-sm-3 col-form-label fw-semibold text-muted">อันดับที่
                                                3</label>
                                            <div class="col-sm-9">
                                                <select class="form-select course-select" name="recruit_tpyeRoom3"
                                                    id="recruit_tpyeRoom3">
                                                    <option value="">-- ไม่ระบุ --</option>
                                                    <?php foreach ($courses as $course):
                                                        if ($course->course_gradelevel == $currentGrade):
                                                            ?>
                                                            <option value="<?= $course->course_id ?>"
                                                                <?= (isset($major_order_ids[2]) && $major_order_ids[2] == $course->course_id) ? 'selected' : '' ?>>
                                                                <?= esc($course->course_branch) ?>
                                                            </option>
                                                            <?php
                                                        endif;
                                                    endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Student Info -->
            <div class="card mb-4 border-0">
                <h5 class="card-header py-3 bg-lighter d-flex align-items-center">
                    <i class='bx bx-user-circle me-3 fs-4 text-primary'></i> 2. ข้อมูลส่วนตัวของนักเรียน
                </h5>
                <div class="card-body pt-4">
                    <div class="row g-4">
                        <div class="col-md-2">
                            <label class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                            <select class="form-select" name="recruit_prefix" required>
                                <option value="เด็กชาย" <?= $recruit['recruit_prefix'] == 'เด็กชาย' ? 'selected' : '' ?>>
                                    เด็กชาย</option>
                                <option value="เด็กหญิง" <?= $recruit['recruit_prefix'] == 'เด็กหญิง' ? 'selected' : '' ?>>
                                    เด็กหญิง</option>
                                <option value="นาย" <?= $recruit['recruit_prefix'] == 'นาย' ? 'selected' : '' ?>>นาย
                                </option>
                                <option value="นางสาว" <?= $recruit['recruit_prefix'] == 'นางสาว' ? 'selected' : '' ?>>
                                    นางสาว</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">ชื่อจริง <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recruit_firstName"
                                value="<?= esc($recruit['recruit_firstName']) ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recruit_lastName"
                                value="<?= esc($recruit['recruit_lastName']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">เลขบัตรประชาชน (13 หลัก) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-credit-card-front'></i></span>
                                <input type="text" class="form-control" name="recruit_idCard"
                                    value="<?= esc($recruit['recruit_idCard']) ?>" maxlength="13" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">วันเดือนปีเกิด <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-cake'></i></span>
                                <input type="date" class="form-control" name="recruit_birthday"
                                    value="<?= esc($recruit['recruit_birthday']) ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">เชื้อชาติ</label>
                            <input type="text" class="form-control" name="recruit_race"
                                value="<?= esc($recruit['recruit_race'] ?? 'ไทย') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">สัญชาติ</label>
                            <input type="text" class="form-control" name="recruit_nationality"
                                value="<?= esc($recruit['recruit_nationality'] ?? 'ไทย') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ศาสนา</label>
                            <input type="text" class="form-control" name="recruit_religion"
                                value="<?= esc($recruit['recruit_religion'] ?? 'พุทธ') ?>">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">เบอร์โทรศัพท์สำหรับติดต่อ <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-phone-call'></i></span>
                                <input type="tel" class="form-control" name="recruit_phone"
                                    value="<?= esc($recruit['recruit_phone']) ?>" required placeholder="08XXXXXXXX">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Address -->
            <div class="card mb-4">
                <h5 class="card-header py-3 bg-lighter d-flex align-items-center">
                    <i class='bx bx-map-pin me-3 fs-4 text-primary'></i> 3. ที่อยู่ตามทะเบียนบ้าน
                </h5>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">บ้านเลขที่ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recruit_homeNumber"
                                value="<?= esc($recruit['recruit_homeNumber'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recruit_homeGroup"
                                value="<?= esc($recruit['recruit_homeGroup'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">ถนน / ซอย</label>
                            <input type="text" class="form-control" name="recruit_homeRoad"
                                value="<?= esc($recruit['recruit_homeRoad'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">ตำบล / แขวง <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-map'></i></span>
                                <input type="text" class="form-control" name="recruit_homeSubdistrict"
                                    id="recruit_homeSubdistrict"
                                    value="<?= esc($recruit['recruit_homeSubdistrict'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">อำเภอ / เขต <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recruit_homedistrict"
                                id="recruit_homedistrict" value="<?= esc($recruit['recruit_homedistrict'] ?? '') ?>"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">จังหวัด <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recruit_homeProvince"
                                id="recruit_homeProvince" value="<?= esc($recruit['recruit_homeProvince'] ?? '') ?>"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold font-monospace">รหัสไปรษณีย์ <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class='bx bx-navigation'></i></span>
                                <input type="text" class="form-control fw-bold" name="recruit_homePostcode"
                                    id="recruit_homePostcode" value="<?= esc($recruit['recruit_homePostcode'] ?? '') ?>"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Sidebar -->
        <div class="col-lg-4">
            <!-- Photo Card / Profile Stats -->
            <div class="card mb-4 profile-card">
                <div class="card-body p-4">
                    <div class="photo-preview-wrapper mb-3">
                        <img id="preview_img_display"
                            src="<?= base_url('image-proxy?file=recruitstudent/m' . $recruit['recruit_regLevel'] . '/img/' . ($recruit['recruit_img'] ?? 'default.png')) ?>"
                            alt="Student Photo"
                            onerror="this.onerror=null;this.src='<?= base_url('public/sneat-assets/img/avatars/1.png') ?>';">

                        <div class="photo-edit-btn" onclick="document.getElementById('recruit_img').click()">
                            <i class='bx bx-camera me-1'></i> คลิกเพื่อเปลี่ยนรูปถ่าย
                        </div>
                    </div>
                    <h6 class="fw-bold mb-1">รูปถ่ายนักเรียน</h6>
                    <p class="text-muted small mb-3">PNG, JPG ขนาดไม่เกิน 2MB</p>

                    <input type="file" class="form-control d-none" name="recruit_img" id="recruit_img" accept="image/*">
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary"
                            onclick="document.getElementById('recruit_img').click()">
                            <i class='bx bx-upload me-2'></i> เลือกรูปภาพใหม่
                        </button>
                    </div>
                </div>
            </div>

            <!-- Education Summary Card -->
            <div class="card mb-4">
                <h5 class="card-header bg-lighter"><i class='bx bx-graduation me-2'></i> 4. ประวัติการศึกษาเดิม</h5>
                <div class="card-body pt-4">
                    <div class="mb-4">
                        <label class="form-label">โรงเรียนเดิม</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bxs-school text-info'></i></span>
                            <select class="form-select" id="recruit_oldSchool_select" style="width: 80%;">
                                <option value="" selected disabled>-- ค้นหาโรงเรียน --</option>
                                <?php if (!empty($recruit['recruit_oldSchool'])): ?>
                                    <option value="current" selected><?= esc($recruit['recruit_oldSchool']) ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <input type="hidden" name="recruit_oldSchool" id="recruit_oldSchool"
                            value="<?= esc($recruit['recruit_oldSchool'] ?? '') ?>" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label small">อำเภอที่ตั้ง</label>
                            <input type="text" class="form-control" name="recruit_district" id="recruit_district"
                                value="<?= esc($recruit['recruit_district'] ?? '') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">จังหวัดที่ตั้ง</label>
                            <input type="text" class="form-control" name="recruit_province" id="recruit_province"
                                value="<?= esc($recruit['recruit_province'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">ผลการเรียนเฉลี่ย (GPAX)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text fw-bold text-primary">GPA</span>
                            <input type="number" step="0.01" min="0" max="4.00" class="form-control fw-bold"
                                name="recruit_grade" value="<?= esc($recruit['recruit_grade']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-muted small">สาขาวิชา / แผนการเรียนเดิม</label>
                        <input type="text" class="form-control bg-lighter" name="recruit_major"
                            value="<?= esc($recruit['recruit_major'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="col-12">
            <div class="card mb-4 border-0">
                <h5 class="card-header py-3 bg-lighter d-flex align-items-center">
                    <i class='bx bx-file me-3 fs-4 text-primary'></i> 5. เอกสารหลักฐานประกอบการสมัคร
                </h5>
                <div class="card-body pt-4">
                    <?php
                    $docFields = [
                        ['id' => 'recruit_certificateEdu', 'name' => 'ปพ.1 (หน้า)', 'dir' => 'certificate', 'icon' => 'bx-file'],
                        ['id' => 'recruit_certificateEduB', 'name' => 'ปพ.1 (หลัง)', 'dir' => 'certificateB', 'icon' => 'bx-file-blank'],
                        ['id' => 'recruit_copyidCard', 'name' => 'สำเนาบัตรประชาชน', 'dir' => 'copyidCard', 'icon' => 'bx-id-card'],
                    ];
                    ?>
                    <div class="row g-4">
                        <?php foreach ($docFields as $df): ?>
                            <div class="col-md-4">
                                <div class="doc-card-premium">
                                    <div class="text-center mb-2">
                                        <span class="badge bg-label-secondary mb-1">เอกสารประกอบ</span>
                                        <h6 class="fw-bold mb-0"><?= $df['name'] ?></h6>
                                    </div>

                                    <div class="doc-preview-container">
                                        <?php if (!empty($recruit[$df['id']])): ?>
                                            <?php if (strpos($recruit[$df['id']], '.pdf') !== false): ?>
                                                <div class="text-center">
                                                    <i class='bx bxs-file-pdf text-danger fs-1'></i>
                                                    <p class="small mt-1 mb-0 text-muted">คลิกเพื่อดูไฟล์ PDF</p>
                                                </div>
                                            <?php else: ?>
                                                <a href="<?= base_url('image-proxy?file=recruitstudent/m' . $recruit['recruit_regLevel'] . '/' . $df['dir'] . '/' . $recruit[$df['id']]) ?>"
                                                    target="_blank" class="d-block w-100 h-100">
                                                    <img src="<?= base_url('image-proxy?file=recruitstudent/m' . $recruit['recruit_regLevel'] . '/' . $df['dir'] . '/' . $recruit[$df['id']]) ?>"
                                                        class="w-100 h-100" style="object-fit: contain;" alt="<?= $df['name'] ?>"
                                                        onerror="this.src='<?= base_url('public/sneat-assets/img/illustrations/page-misc-error-light.png') ?>'">
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <i class='bx <?= $df['icon'] ?> opacity-25'></i>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-auto">
                                        <input class="form-control form-control-sm border-dashed" type="file"
                                            name="<?= $df['id'] ?>" accept="image/*,.pdf">

                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="small text-muted">Max 5MB</span>
                                            <?php if (!empty($recruit[$df['id']])): ?>
                                                <span class="badge bg-label-success px-2 border-0">
                                                    <i class='bx bx-check me-1'></i>เรียบร้อย
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-label-warning px-2 border-0">
                                                    <i class='bx bx-time me-1'></i>รอกดเลือก
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Actions Footer -->
        <div class="col-12">
            <div class="card actions-card mb-5">
                <div
                    class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-4 text-center text-md-start">
                    <div>
                        <h4 class="mb-1 text-white fw-bold">บันทึกการเปลี่ยนแปลง</h4>
                        <p class="mb-0">ตรวจสอบความถูกต้องอีกครั้ง ก่อนกดยืนยันการแก้ไขข้อมูล</p>
                    </div>
                    <div class="d-flex gap-3 w-100 w-md-auto">
                        <a href="<?= site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) ?>"
                            class="btn btn-outline-light px-4 flex-grow-1 flex-md-grow-0">
                            <i class='bx bx-undo me-2'></i> ยกเลิก
                        </a>
                        <button type="submit" form="editForm"
                            class="btn btn-success px-5 flex-grow-1 flex-md-grow-0 py-3 shadow-lg">
                            <i class='bx bx-save me-2'></i> บันทึกข้อมูลทั้งหมด
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript"
    src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script type="text/javascript"
    src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<link rel="stylesheet"
    href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script type="text/javascript"
    src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // 1. Thailand Auto-complete
        $.Thailand({
            $district: $('#recruit_homeSubdistrict'),
            $amphoe: $('#recruit_homedistrict'),
            $province: $('#recruit_homeProvince'),
            $zipcode: $('#recruit_homePostcode'),
        });

        // 2. Select2 Initialize
        $('.form-select:not(.fw-bold, [name="recruit_regLevel"])').select2({
            theme: 'bootstrap-5',
            placeholder: 'เลือกข้อมูล'
        });

        // Dynamic Courses Filtering
        const courses = <?= $courses_json ?>;
        const currentMajorOrder = <?= json_encode($major_order_ids) ?>;

        function updateCourses(level) {
            const courseSelects = $('.course-select');
            const gradeLevel = (level == '1') ? 'ม.ต้น' : 'ม.ปลาย';

            // Update Info Badge
            if (level == '1') {
                $('#level_badge_info').html('<span class="badge bg-info"><i class=\'bx bx-info-circle me-1\'></i> เงื่อนไข: ม.ต้น เลือกได้ 3 อันดับ</span>');
            } else {
                $('#level_badge_info').html('<span class="badge bg-warning text-dark"><i class=\'bx bx-info-circle me-1\'></i> เงื่อนไข: ม.ปลาย เลือกได้ 3 อันดับ (ตามคุณสมบัติ)</span>');
            }

            courseSelects.each(function (index) {
                const $select = $(this);
                const currentValue = $select.val() || (currentMajorOrder[index] || '');
                $select.empty();

                if ($select.attr('id') !== 'recruit_tpyeRoom1') {
                    $select.append('<option value="">-- ไม่ระบุ --</option>');
                } else {
                    $select.append('<option value="" disabled>-- เลือกอันดับ 1 --</option>');
                }

                const filtered = courses.filter(c => c.course_gradelevel === gradeLevel);
                filtered.forEach(c => {
                    const isSelected = (c.course_id == currentValue) ? 'selected' : '';
                    $select.append(`<option value="${c.course_id}" ${isSelected}>${c.course_initials}</option>`);
                });

                $select.trigger('change.select2');
            });
        }

        $('[name="recruit_regLevel"]').on('change', function () {
            updateCourses($(this).val());
        });

        // 3. School Search Ajax (FIXED)
        $('#recruit_oldSchool_select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- พิมพ์ชื่อโรงเรียนเพื่อค้นหา --',
            ajax: {
                url: '<?= base_url('new-admission/school-search') ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });

        $('#recruit_oldSchool_select').on('select2:select', function (e) {
            var data = e.params.data;
            $('#recruit_oldSchool').val(data.text);
            $('#recruit_district').val(data.amphur || '');
            $('#recruit_province').val(data.province || '');
        });

        // Handle manual entry if search fails or user wants custom
        $('#recruit_oldSchool_select').on('select2:open', function () {
            if (!$('.select2-results__option--manual').length) {
                $(".select2-results").append('<div class="select2-results__option select2-results__option--manual p-2 border-top text-center"><small class="text-muted">ไม่พบโรงเรียน? พิมพ์ชื่อเองในช่องค้นหาแล้วกด Enter</small></div>');
            }
        });

        // 4. Image Preview
        $('#recruit_img').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) { $('#preview_img_display').attr('src', e.target.result); }
                reader.readAsDataURL(file);
            }
        });

        // 4.5. Sport Selection Result - Show/Hide based on Quota
        // Store quota_key mappings
        const quotaData = {};
        <?php if (isset($quotas)): ?>
            <?php foreach ($quotas as $q): ?>
                quotaData[<?= $q->quota_id ?>] = '<?= $q->quota_key ?>';
            <?php endforeach; ?>
        <?php endif; ?>

        function updateSportSelectionVisibility() {
            const selectedQuota = $('#recruit_category').val();
            const quotaKey = quotaData[selectedQuota] || '';
            const sportPosition = '<?= esc($recruit['recruit_sportPosition'] ?? '') ?>';

            // Show if quota is 'sport' or if applicant has a sport position
            if (quotaKey === 'sport' || (sportPosition && sportPosition !== '-')) {
                $('#sportSelectionResultSection').slideDown();
            } else {
                $('#sportSelectionResultSection').slideUp();
            }
        }

        // Bind to quota dropdown change
        $('#recruit_category').on('change', updateSportSelectionVisibility);

        // 5. Submit Handling
        $('#editForm').on('submit', function (e) {
            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                html: 'กรุณารอสักครู่ ระบบกำลังอัปโหลดไฟล์และประมวลผล',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });
    });
</script>
<?= $this->endSection() ?>
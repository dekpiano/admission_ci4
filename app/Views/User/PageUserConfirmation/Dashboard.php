<?= $this->extend('User/UserLayout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-3 mx-sm-0 mx-auto">
                    <?php if (!empty($stu[0]->recruit_img)): ?>
                        <img src="<?= recruit_image_url($stu[0]->recruit_img, $stu[0]->recruit_regLevel, 'img') ?>" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" style="max-width: 100px;" />
                    <?php else: ?>
                        <img src="<?= base_url() ?>/uploads/students/default.png" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" style="max-width: 100px;" />
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>รายงานตัวนักเรียนใหม่ ปีการศึกษา <?= $checkYear[0]->openyear_year ?? '' ?></h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-user'></i> ชื่อ-สกุล: <?= !empty($stuConf) ? ($stuConf[0]->stu_prefix . $stuConf[0]->stu_fristName . ' ' . $stuConf[0]->stu_lastName) : ($stu[0]->recruit_prefix . $stu[0]->recruit_firstName . ' ' . $stu[0]->recruit_lastName) ?>
                                </li>
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-id-card'></i> เลขประจำตัวประชาชน: <?= !empty($stuConf) ? $stuConf[0]->stu_iden : $stu[0]->recruit_idCard ?>
                                </li>
                            </ul>
                        </div>
                        <a href="<?= base_url('confirmation/logout') ?>" class="btn btn-danger text-nowrap">
                            <i class='bx bx-log-out'></i> ออกจากระบบ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 1.25rem;
    }
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.12)!important;
        border-color: var(--bs-primary);
    }
    
    /* Icon Design Refinement */
    .icon-box {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .icon-box i {
        font-size: 2.5rem;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .icon-box::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0.15;
        z-index: 1;
    }

    /* Floating Animation for icons on hover */
    .hover-card:hover .icon-box i {
        transform: scale(1.1) rotate(5deg);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* Category Specific Visuals */
    .bg-soft-primary { background-color: #e7e7ff; color: #696cff; }
    .bg-soft-info { background-color: #d7f5fc; color: #03c3ec; }
    .bg-soft-danger { background-color: #ffe0db; color: #ff3e1d; }
    .bg-soft-warning { background-color: #fff2d6; color: #ffab00; }

    @media (max-width: 576px) {
        .icon-box {
            width: 50px !important;
            height: 50px !important;
            border-radius: 12px !important;
            margin-bottom: 0.75rem !important;
        }
        .icon-box i {
            font-size: 1.5rem !important;
        }
        .card-body {
            padding: 1rem 0.5rem !important;
        }
        .card-body h5 {
            font-size: 1rem !important;
        }
        .card-body p {
            font-size: 0.7rem !important;
            margin-bottom: 0.5rem !important;
        }
        .card-body .badge {
            font-size: 0.65rem !important;
            padding: 0.4rem 0.6rem !important;
        }
        .card-body button {
            font-size: 0.75rem !important;
        }
    }
</style>

<div class="row">
    <div class="col-md-12">

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- News Ticker Announcement -->
        <div class="news-ticker shadow-sm mb-4">
            <div class="ticker-header">
                <i class='bx bxs-megaphone me-1'></i> ประกาศล่าสุด
            </div>
            <div class="ticker-content">
                <div class="ticker-text">
                    <span>📢 ยินดีต้อนรับสู่ระบบรายงานตัวนักเรียนใหม่ ปีการศึกษา <?= $checkYear[0]->openyear_year ?? '' ?></span>
                    <span>✨ กรุณาตรวจสอบข้อมูลให้ถูกต้องครบถ้วนก่อนพิมพ์เอกสาร</span>
                    <span>⚠️ หากพบปัญหาในการใช้งาน ติดต่อสอบถามได้ที่ฝ่ายรับสมัครของโรงเรียน</span>
                </div>
            </div>
        </div>

        <style>
            .news-ticker {
                display: flex;
                background: white;
                border-radius: 50px;
                overflow: hidden;
                border: 1px solid rgba(255, 158, 181, 0.2);
            }
            .ticker-header {
                background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
                color: white;
                padding: 10px 20px;
                font-weight: 700;
                font-size: 0.9rem;
                white-space: nowrap;
                display: flex;
                align-items: center;
                z-index: 2;
                box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            }
            .ticker-content {
                flex-grow: 1;
                overflow: hidden;
                display: flex;
                align-items: center;
                background: #fff;
                position: relative;
            }
            .ticker-text {
                display: flex;
                white-space: nowrap;
                animation: ticker 30s linear infinite;
                padding-left: 100%;
            }
            .ticker-text span {
                padding: 0 40px;
                color: #566a7f;
                font-weight: 500;
                font-size: 0.9rem;
            }
            @keyframes ticker {
                0% { transform: translate3d(0, 0, 0); }
                100% { transform: translate3d(-100%, 0, 0); }
            }
            .news-ticker:hover .ticker-text {
                animation-play-state: paused;
            }
            @media (max-width: 576px) {
                .ticker-header {
                    padding: 8px 12px;
                    font-size: 0.75rem;
                }
                .ticker-text span {
                    font-size: 0.8rem;
                    padding: 0 20px;
                }
            }
        </style>

        <!-- Instruction & Announcement Alert -->
        <div class="alert alert-primary border-0 shadow-sm mb-4 overflow-hidden position-relative" role="alert" style="border-radius: 15px; background: linear-gradient(135deg, #fff5f7 0%, #f0f7ff 100%); border-left: 5px solid #ff9eb5 !important;">
            <div class="d-flex align-items-center mb-2">
                <div class="flex-shrink-0 bg-white shadow-sm rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bxs-bell-ring bx-tada text-primary fs-4'></i>
                </div>
                <div>
                    <h5 class="alert-heading fw-bold text-dark mb-0">ประกาศแจ้งเตือนและคำชี้แจง</h5>
                    <small class="text-muted">โปรดอ่านคำชี้แจงก่อนดำเนินการกรอกข้อมูล</small>
                </div>
            </div>
            <hr class="my-3 opacity-10">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex align-items-center">
                            <i class='bx bxs-check-circle text-primary me-2'></i>
                            <span><strong>ข้อมูลนักเรียน:</strong> <span class="text-danger fw-bold">จำเป็นต้องกรอก</span></span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class='bx bxs-check-circle text-info me-2'></i>
                            <span><strong>ข้อมูลผู้ปกครอง:</strong> <span class="text-danger fw-bold">จำเป็นต้องกรอก</span></span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex align-items-center">
                            <i class='bx bxs-help-circle text-secondary me-2'></i>
                            <span><strong>บิดา/มารดา:</strong> ถ้าไม่มีข้อมูลคนใดคนหนึ่ง ให้กรอกข้อมูลอีกคนแทนได้</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Menu Selection -->
        <div id="menu-selection" class="row g-3 g-md-4 mb-4">
            <!-- Student Info Button -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card border-0" onclick="showForm('student-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center">
                        <div class="icon-box bg-soft-primary shadow-sm">
                            <i class="bx bx-user"></i>
                        </div>
                        <h5 class="fw-bold mb-1">ข้อมูลนักเรียน</h5>
                        <p class="text-danger fw-bold small mb-3">** จำเป็นต้องกรอก **</p>
                        
                        <div class="mb-3">
                            <?php if ($isStudentSaved): ?>
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    <i class="bx bxs-x-circle me-1"></i> รอดำเนินการ
                                </span>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-outline-primary rounded-pill btn-sm w-100 mt-auto">
                            <i class='bx bx-edit-alt me-1'></i> <?= $isStudentSaved ? 'แก้ไขข้อมูล' : 'คลิกเพื่อกรอกข้อมูล' ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Father Info Button -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card border-0" onclick="showForm('father-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center">
                        <div class="icon-box bg-soft-info shadow-sm">
                            <i class="bx bx-male"></i>
                        </div>
                        <h5 class="fw-bold mb-1">ข้อมูลบิดา</h5>
                        <p class="text-muted small mb-3">(** ถ้าไม่มีไม่ต้องกรอก **)</p>
                        
                        <div class="mb-3">
                            <?php if ($FatherCkeck): ?>
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว
                                </span>
                            <?php else: ?>
                                <span class="badge bg-label-secondary border rounded-pill px-3 py-2">
                                    <i class="bx bx-circle me-1"></i> ยังไม่ระบุ
                                </span>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-outline-info rounded-pill btn-sm w-100 mt-auto">
                            <i class='bx bx-edit-alt me-1'></i> <?= $FatherCkeck ? 'แก้ไขข้อมูล' : 'คลิกเพื่อระบุข้อมูล' ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mother Info Button -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card border-0" onclick="showForm('mother-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center">
                        <div class="icon-box bg-soft-danger shadow-sm">
                            <i class="bx bx-female"></i>
                        </div>
                        <h5 class="fw-bold mb-1">ข้อมูลมารดา</h5>
                        <p class="text-muted small mb-3">(** ถ้าไม่มีไม่ต้องกรอก **)</p>
                        
                        <div class="mb-3">
                            <?php if ($MatherCkeck): ?>
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว
                                </span>
                            <?php else: ?>
                                <span class="badge bg-label-secondary border rounded-pill px-3 py-2">
                                    <i class="bx bx-circle me-1"></i> ยังไม่ระบุ
                                </span>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-outline-danger rounded-pill btn-sm w-100 mt-auto">
                            <i class='bx bx-edit-alt me-1'></i> <?= $MatherCkeck ? 'แก้ไขข้อมูล' : 'คลิกเพื่อระบุข้อมูล' ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Guardian Info Button -->
            <div class="col-6 col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card border-0" onclick="showForm('guardian-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center">
                        <div class="icon-box bg-soft-warning shadow-sm">
                            <i class="bx bx-body"></i>
                        </div>
                        <h5 class="fw-bold mb-1">ข้อมูลผู้ปกครอง</h5>
                        <p class="text-danger fw-bold small mb-3">** จำเป็นต้องกรอก **</p>
                        
                        <div class="mb-3">
                            <?php if ($OtherCkeck): ?>
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    <i class="bx bxs-x-circle me-1"></i> รอดำเนินการ
                                </span>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-outline-warning rounded-pill btn-sm w-100 mt-auto">
                            <i class='bx bx-edit-alt me-1'></i> <?= $OtherCkeck ? 'แก้ไขข้อมูล' : 'คลิกเพื่อกรอกข้อมูล' ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Print Document Button -->
            <div class="col-12 mt-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                         <h5 class="card-title mb-3">พิมพ์เอกสารมอบตัว</h5>
                         <p class="card-text text-muted mb-1">เมื่อกรอกข้อมูลครบถ้วนแล้ว สามารถพิมพ์เอกสารมอบตัวได้ที่นี่</p>
                         <div class="mb-4">
                             <span class="badge bg-label-secondary px-3 py-2 w-100 text-wrap" style="line-height: 1.6;">
                                 <i class="bx bx-info-circle me-1"></i> <span class="fw-bold text-dark">หมายเหตุ:</span> 
                                 <?php if ($stu[0]->recruit_regLevel == '1'): ?>
                                    สำหรับการมอบตัว <span class="text-primary fw-bold">ม.1 (ม.ต้น)</span> กรุณาใช้<span class="badge bg-pastel-pink ms-1">กระดาษสีชมพู</span> ในการพิมพ์
                                 <?php elseif ($stu[0]->recruit_regLevel == '4'): ?>
                                    สำหรับการมอบตัว <span class="text-info fw-bold">ม.4 (ม.ปลาย)</span> กรุณาใช้<span class="badge bg-pastel-blue ms-1">กระดาษสีฟ้า</span> ในการพิมพ์
                                 <?php else: ?>
                                    ม.ต้น ใช้<span class="text-primary fw-bold">กระดาษสีชมพู</span> | ม.ปลาย ใช้<span class="text-info fw-bold">กระดาษสีฟ้า</span>
                                 <?php endif; ?>
                             </span>
                         </div>
                         
                         <?php if($isStudentSaved && $OtherCkeck): ?>
                            <a href="<?= base_url('confirmation/pdf') ?>" target="_blank" class="btn btn-primary btn-lg px-5 rounded-pill"><i class="bx bx-printer me-2"></i> พิมพ์ใบมอบตัว</a>
                         <?php else: ?>
                            <div class="alert alert-warning d-inline-block mb-0">
                                <i class="bx bx-info-circle me-1"></i> กรุณากรอกข้อมูลนักเรียนและผู้ปกครองให้ครบถ้วนก่อนพิมพ์เอกสาร
                            </div>
                         <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container (Hidden by default) -->
        <div id="form-container" style="display: none;">
            <div class="mb-3">
                <button type="button" class="btn btn-secondary" onclick="showMenu()">
                    <i class="bx bx-arrow-back me-1"></i> กลับไปหน้าเมนู
                </button>
            </div>

            <!-- Student Form -->
            <div id="student-info-form" class="form-section" style="display: none;">
                <div class="">
                    <div class="card-header border-bottom mb-4">
                        <h5 class="card-title mb-0 text-primary"><i class="bx bx-user me-2"></i>ฟอร์มข้อมูลส่วนตัวนักเรียน</h5>
                        <small class="text-muted">กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้องตามความเป็นจริง</small>
                    </div>
                    <div class="card-body">
                        <?= view('User/PageUserConfirmation/FormStudent') ?>
                    </div>
                </div>
            </div>

            <!-- Father Form -->
            <div id="father-info-form" class="form-section" style="display: none;">
                <div class="">
                    <div class="card-header border-bottom mb-4">
                        <h5 class="card-title mb-0 text-info"><i class="bx bx-male me-2"></i>ฟอร์มข้อมูลบิดา</h5>
                    </div>
                    <div class="card-body">
                        <?= view('User/PageUserConfirmation/FormFather') ?>
                    </div>
                </div>
            </div>

            <!-- Mother Form -->
            <div id="mother-info-form" class="form-section" style="display: none;">
                <div class="">
                    <div class="card-header border-bottom mb-4">
                        <h5 class="card-title mb-0 text-danger"><i class="bx bx-female me-2"></i>ฟอร์มข้อมูลมารดา</h5>
                    </div>
                    <div class="card-body">
                        <?= view('User/PageUserConfirmation/FormMother') ?>
                    </div>
                </div>
            </div>

            <!-- Guardian Form -->
            <div id="guardian-info-form" class="form-section" style="display: none;">
                <div class="">
                    <div class="card-header border-bottom mb-4">
                        <h5 class="card-title mb-0 text-warning"><i class="bx bx-body me-2"></i>ฟอร์มข้อมูลผู้ปกครอง</h5>
                    </div>
                    <div class="card-body">
                        <?= view('User/PageUserConfirmation/FormGuardian') ?>
                    </div>
                </div>
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

<!-- Select2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script>
    function showForm(formId) {
        $('#menu-selection').hide();
        $('#form-container').fadeIn();
        $('.form-section').hide();
        $('#' + formId + '-form').show();
        window.scrollTo(0, 0);
    }

    function showMenu() {
        $('#form-container').hide();
        $('#menu-selection').fadeIn();
        window.scrollTo(0, 0);
    }

    function selectGuardian(type) {
        if (type === 'father') {
            var data = $('#fatherData').data();
            if(data) {
                fillGuardianForm(data, 'บิดา');
            }
        } else if (type === 'mother') {
            var data = $('#motherData').data();
            if(data) {
                fillGuardianForm(data, 'มารดา');
            }
        } else {
            // Reset form for 'Other'
            $('#guardianFormSection input[type="text"]').val('');
            $('#guardianFormSection input[type="number"]').val('');
            $('#guardianFormSection select').val('').trigger('change');
            $('#par_relationO').val('');
            $('input[name="par_serviceO"]').prop('checked', false);
            $('input[name="par_claimO"]').prop('checked', false);
            $('input[name="par_restO"]').prop('checked', false);
            // Default check
            $('#par_serviceO99').prop('checked', true);
            $('#par_restO0').prop('checked', true);
        }
    }

    function fillGuardianForm(data, relation) {
        $('#par_relationO').val(relation);
        $('#par_prefixO').val(data.prefix);
        $('#par_firstNameO').val(data.firstname);
        $('#par_lastNameO').val(data.lastname);
        $('#par_agoO').val(data.ago);
        $('#par_IdNumberO').val(data.idnumber);
        $('#par_phoneO').val(data.phone);
        $('#par_deceaseO').val(data.decease);
        
        $('#par_raceO').val(data.race).trigger('change');
        $('#par_nationalO').val(data.national).trigger('change');
        $('#par_religionO').val(data.religion).trigger('change');
        
        $('#par_careerO').val(data.career);
        $('#par_educationO').val(data.education);
        $('#par_salaryO').val(data.salary);
        $('#par_positionJobO').val(data.position);
        
        // Address
        $('#par_hNumberO').val(data.hnumber);
        $('#par_hMooO').val(data.hmoo);
        $('#par_hTambonO').val(data.htambon);
        $('#par_hDistrictO').val(data.hdistrict);
        $('#par_hProvinceO').val(data.hprovince);
        $('#par_hPostcodeO').val(data.hpostcode);
        
        $('#par_cNumberO').val(data.cnumber);
        $('#par_cMooO').val(data.cmoo);
        $('#par_cTambonO').val(data.ctambon);
        $('#par_cDistrictO').val(data.cdistrict);
        $('#par_cProvinceO').val(data.cprovince);
        $('#par_cPostcodeO').val(data.cpostcode);
        
        // Radio buttons
        // Service
        $('input[name="par_serviceO"][value="' + data.service + '"]').prop('checked', true).trigger('change');
        if(data.service != 'ไม่ได้รับราชการ') {
             $('input[name="par_serviceNameO[]"]').val(data.servicename);
        }
        
        // Claim
        $('input[name="par_claimO"][value="' + data.claim + '"]').prop('checked', true);
        
        // Rest
        $('input[name="par_restO"][value="' + data.rest + '"]').prop('checked', true).trigger('change');
        if(data.rest == 'อื่นๆ') {
            $('#par_restOrthorO').val(data.restorthor);
        }
    }

    $(document).ready(function() {
        // Initialize Inputmask
        $(":input").inputmask();

        // Select2 for School Search
        $('#stu_schoolfrom').select2({
            theme: 'bootstrap-5',
            placeholder: "พิมพ์ชื่อโรงเรียนเพื่อค้นหา...",
            allowClear: true,
            ajax: {
                url: "<?=base_url('control_admission/SchoolList')?>",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term // search term
                    };
                },
                processResults: function (data) {
                    // Map data to Select2 format if needed, but the API seems to return compatible format
                    // API returns: [{value, label, amphur, province}, ...]
                    // Select2 expects: id, text
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.label,
                                id: item.label, // Use name as ID to save the name directly
                                province: item.province,
                                amphur: item.amphur,
                                district: item.district
                            }
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
            language: {
                inputTooShort: function() {
                    return 'กรุณาพิมพ์อย่างน้อย 2 ตัวอักษรเพื่อค้นหา';
                },
                noResults: function() {
                    return 'ไม่พบโรงเรียนที่ค้นหา';
                },
                searching: function() {
                    return 'กำลังค้นหา...';
                }
            }
        });

        // Auto-fill Province and District when school is selected
        $('#stu_schoolfrom').on('select2:select', function (e) {
            var data = e.params.data;
            $('#stu_schoolProvince').val(data.province);
            $('#stu_schoolDistrict').val(data.amphur);
            $('#stu_schoolTambao').val(data.district);
        });

        // Initialize Thailand Address Auto Complete
        // Student Home Address
        $.Thailand({
            $district: $('#stu_hTambon'),
            $amphoe: $('#stu_hDistrict'),
            $province: $('#stu_hProvince'),
            $zipcode: $('#stu_hPostCode'),
        });
        // Student Current Address
        $.Thailand({
            $district: $('#stu_cTumbao'),
            $amphoe: $('#stu_cDistrict'),
            $province: $('#stu_cProvince'),
            $zipcode: $('#stu_cPostcode'),
        });

        // Student Birth Address
        $.Thailand({
            $district: $('#stu_birthTambon'),
            $amphoe: $('#stu_birthDistrict'),
            $province: $('#stu_birthProvirce'),
        });

        // Student School Address
        $.Thailand({
            $district: $('#stu_schoolTambao'),
            $amphoe: $('#stu_schoolDistrict'),
            $province: $('#stu_schoolProvince'),
        });

        // Father Home Address
        $.Thailand({
            $district: $('#par_hTambon'),
            $amphoe: $('#par_hDistrict'),
            $province: $('#par_hProvince'),
            $zipcode: $('#par_hPostcode'),
        });
        // Father Current Address
        $.Thailand({
            $district: $('#par_cTambon'),
            $amphoe: $('#par_cDistrict'),
            $province: $('#par_cProvince'),
            $zipcode: $('#par_cPostcode'),
        });

        // Mother Home Address
        $.Thailand({
            $district: $('#par_hTambonM'),
            $amphoe: $('#par_hDistrictM'),
            $province: $('#par_hProvinceM'),
            $zipcode: $('#par_hPostcodeM'),
        });
        // Mother Current Address
        $.Thailand({
            $district: $('#par_cTambonM'),
            $amphoe: $('#par_cDistrictM'),
            $province: $('#par_cProvinceM'),
            $zipcode: $('#par_cPostcodeM'),
        });

        // Guardian Home Address
        $.Thailand({
            $district: $('#par_hTambonO'),
            $amphoe: $('#par_hDistrictO'),
            $province: $('#par_hProvinceO'),
            $zipcode: $('#par_hPostcodeO'),
        });
        // Guardian Current Address
        $.Thailand({
            $district: $('#par_cTambonO'),
            $amphoe: $('#par_cDistrictO'),
            $province: $('#par_cProvinceO'),
            $zipcode: $('#par_cPostcodeO'),
        });

        // Handle Form Submission via AJAX
        $('form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var originalBtnText = btn.html();
            
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> กำลังบันทึก...');

            $.ajax({
                type: "POST",
                url: "<?= base_url('confirmation/save') ?>",
                data: form.serialize(),
                dataType: "json",
                success: function(response) {
                    btn.prop('disabled', false).html(originalBtnText);
                    if (response.status === 'success') {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            alert(response.message);
                            location.reload();
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message
                            });
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    btn.prop('disabled', false).html(originalBtnText);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error);
                }
            });
        });
        
        // Conditional fields logic
        // Father
        $('input[name="par_rest"]').change(function(){
            if($(this).val() == 'อื่นๆ'){
                $('#par_restOrthor').show().focus();
            }else{
                $('#par_restOrthor').hide().val('');
            }
        });
        
        // Father
        $('input[name="par_service"]').change(function(e, isInit){
            var name = $(this).val();
            var textInputs = $('input[name="par_serviceName[]"]');
            
            if (!isInit) {
                textInputs.hide().val('');
            } else {
                textInputs.hide();
            }
            
            if(name != 'ไม่ได้รับราชการ'){
                 var target = $(this).closest('.form-check').next('input[name="par_serviceName[]"]');
                 target.show();
                 if(!isInit) target.focus();
            }
        });

        // Mother
         $('input[name="par_serviceM"]').change(function(e, isInit){
            var name = $(this).val();
            var textInputs = $('input[name="par_serviceNameM[]"]');
            
            if (!isInit) {
                textInputs.hide().val('');
            } else {
                textInputs.hide();
            }

            if(name != 'ไม่ได้รับราชการ'){
                 var target = $(this).closest('.form-check').next('input[name="par_serviceNameM[]"]');
                 target.show();
                 if(!isInit) target.focus();
            }
        });

        // Guardian
         $('input[name="par_serviceO"]').change(function(e, isInit){
            var name = $(this).val();
            var textInputs = $('input[name="par_serviceNameO[]"]');
            
            if (!isInit) {
                textInputs.hide().val('');
            } else {
                textInputs.hide();
            }

            if(name != 'ไม่ได้รับราชการ'){
                 var target = $(this).closest('.form-check').next('input[name="par_serviceNameO[]"]');
                 target.show();
                 if(!isInit) target.focus();
            }
        });
        
        // Student
        $('input[name="stu_presentLife"]').change(function(){
             if($(this).val() == 'บุคคลอื่น'){
                $('#stu_personOther').show().focus();
            }else{
                $('#stu_personOther').hide().val('');
            }
        });

        // Student - Used to be student here
        $('input[name="stu_usedStudent"]').change(function(){
            if($(this).val() == 'เคย'){
                $('#usedStudentLevelSection').show();
            }else{
                $('#usedStudentLevelSection').hide();
                $('#stu_inputLevel').val('');
            }
        });

        // Trigger change events on load to set initial state
        $('input[name="par_rest"]:checked').trigger('change');
        $('input[name="par_service"]:checked').trigger('change', [true]);
        $('input[name="par_restM"]:checked').trigger('change');
        $('input[name="par_serviceM"]:checked').trigger('change', [true]);
        $('input[name="par_restO"]:checked').trigger('change');
        $('input[name="par_serviceO"]:checked').trigger('change', [true]);
        $('input[name="stu_presentLife"]:checked').trigger('change');
        $('input[name="stu_usedStudent"]:checked').trigger('change');

        // Checkbox "Same as Home Address"
        $('#clickLike').change(function(){
            if(this.checked) {
                $('#stu_cNumber').val($('#stu_hNumber').val());
                $('#stu_cMoo').val($('#stu_hMoo').val());
                $('#stu_cRoad').val($('#stu_hRoad').val());
                $('#stu_cTumbao').val($('#stu_hTambon').val());
                $('#stu_cDistrict').val($('#stu_hDistrict').val());
                $('#stu_cProvince').val($('#stu_hProvince').val());
                $('#stu_cPostcode').val($('#stu_hPostCode').val());
            } else {
                // Optional: Clear fields
            }
        });

        $('#checkPer').change(function(){
            if(this.checked) {
                $('#par_cNumber').val($('#par_hNumber').val());
                $('#par_cMoo').val($('#par_hMoo').val());
                $('#par_cTambon').val($('#par_hTambon').val());
                $('#par_cDistrict').val($('#par_hDistrict').val());
                $('#par_cProvince').val($('#par_hProvince').val());
                $('#par_cPostcode').val($('#par_hPostcode').val());
            }
        });

        $('#checkPerM').change(function(){
            if(this.checked) {
                $('#par_cNumberM').val($('#par_hNumberM').val());
                $('#par_cMooM').val($('#par_hMooM').val());
                $('#par_cTambonM').val($('#par_hTambonM').val());
                $('#par_cDistrictM').val($('#par_hDistrictM').val());
                $('#par_cProvinceM').val($('#par_hProvinceM').val());
                $('#par_cPostcodeM').val($('#par_hPostcodeM').val());
            }
        });

        $('#checkPerO').change(function(){
            if(this.checked) {
                $('#par_cNumberO').val($('#par_hNumberO').val());
                $('#par_cMooO').val($('#par_hMooO').val());
                $('#par_cTambonO').val($('#par_hTambonO').val());
                $('#par_cDistrictO').val($('#par_hDistrictO').val());
                $('#par_cProvinceO').val($('#par_hProvinceO').val());
                $('#par_cPostcodeO').val($('#par_hPostcodeO').val());
            }
        });

        // Thai National ID Checksum Validation
        function script_check_id(id) {
            if (id.length != 13) return false;
            for (i = 0, sum = 0; i < 12; i++)
                sum += parseFloat(id.charAt(i)) * (13 - i);
            if ((11 - sum % 11) % 10 != parseFloat(id.charAt(12))) return false;
            return true;
        }

        $('#stu_iden, #par_IdNumber, #par_IdNumberM, #par_IdNumberO').on('blur', function() {
            var id = $(this).val().replace(/-/g, '');
            if (id !== '' && id.length === 13) {
                if (!script_check_id(id)) {
                    $(this).addClass('is-invalid');
                    Swal.fire({
                        icon: 'error',
                        title: 'เลขประจำตัวประชาชนไม่ถูกต้อง',
                        text: 'กรุณาตรวจสอบเลขประจำตัวประชาชนอีกครั้ง',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        $(this).val('').focus();
                    });
                } else {
                    // Check for duplicates within the current view
                    var isDuplicate = false;
                    var studentId = $('#stu_iden').val() ? $('#stu_iden').val().replace(/-/g, '') : '';
                    var currentId = $(this).attr('id');

                    // If it's a parent field, check against student field
                    if (currentId !== 'stu_iden' && id === studentId) {
                        isDuplicate = true;
                        var label = "นักเรียน";
                    }

                    if (isDuplicate) {
                        $(this).addClass('is-invalid');
                        Swal.fire({
                            icon: 'warning',
                            title: 'เลขประจำตัวประชาชนซ้ำ',
                            text: 'เลขประจำตัวประชาชนต้องไม่ซ้ำกับของ' + label,
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            $(this).val('').focus();
                        });
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    }
                }
            } else if (id !== '' && id.length < 13) {
                $(this).addClass('is-invalid');
            }
        });

    });
</script>
<?= $this->endSection() ?>

<?= $this->extend('User/UserLayout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-3 mx-sm-0 mx-auto">
                    <?php if (!empty($stu[0]->recruit_img)): ?>
                        <img src="<?= $stu[0]->recruit_img ?>" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" style="max-width: 100px;" />
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
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border-color: var(--bs-primary);
    }
    .cursor-pointer {
        cursor: pointer;
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

        <!-- Instruction Alert -->
        <div class="alert alert-warning border-start border-5 border-warning shadow-sm mb-4" role="alert">
            <h5 class="alert-heading fw-bold text-dark"><i class='bx bxs-bell-ring bx-tada me-2 text-warning'></i>คำชี้แจงก่อนกรอกข้อมูล</h5>
            <hr>
            <ul class="list-unstyled mb-0 mt-2">
                <li class="mb-2"><i class='bx bxs-circle text-danger me-2' style="font-size: 8px; vertical-align: middle;"></i><strong>ข้อมูลนักเรียน:</strong> <span class="text-danger fw-bold">** จำเป็นต้องกรอก **</span></li>
                <li class="mb-2"><i class='bx bxs-circle text-secondary me-2' style="font-size: 8px; vertical-align: middle;"></i><strong>ข้อมูลบิดา:</strong> ถ้าไม่มีข้อมูลบิดา ให้ไปกรอกเมนูของมารดาได้</li>
                <li class="mb-2"><i class='bx bxs-circle text-secondary me-2' style="font-size: 8px; vertical-align: middle;"></i><strong>ข้อมูลมารดา:</strong> ถ้าไม่มีข้อมูลมารดา ให้ไปกรอกเมนูของบิดาได้</li>
                <li class="mb-0"><i class='bx bxs-circle text-danger me-2' style="font-size: 8px; vertical-align: middle;"></i><strong>ข้อมูลผู้ปกครอง:</strong> <span class="text-danger fw-bold">** จำเป็นต้องกรอก **</span> (คนที่นักเรียนอาศัยอยู่ด้วยในปัจจุบัน)</li>
            </ul>
        </div>

        <!-- Menu Selection -->
        <div id="menu-selection" class="row g-4 mb-4">
            <!-- Student Info Button -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card" onclick="showForm('student-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center gap-3">
                        <div class="avatar avatar-xl bg-label-primary rounded-circle mb-2">
                            <i class="bx bx-user fs-1"></i>
                        </div>
                        <h5 class="card-title mb-0">ข้อมูลนักเรียน</h5>
                        <small class="text-danger fw-bold">(** จำเป็นต้องกรอก **)</small>
                        <div class="status-icon">
                            <?php if ($isStudentSaved): ?>
                                <span class="badge bg-label-success rounded-pill"><i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-label-danger rounded-pill"><i class="bx bxs-x-circle me-1"></i> ยังไม่กรอก</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Father Info Button -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card" onclick="showForm('father-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center gap-3">
                        <div class="avatar avatar-xl bg-label-info rounded-circle mb-2">
                            <i class="bx bx-male fs-1"></i>
                        </div>
                        <h5 class="card-title mb-0">ข้อมูลบิดา</h5>
                        <small class="text-muted" style="font-size: 0.8rem;">(** ถ้าไม่มีข้อมูลบิดา ให้ไปกรอกเมนูของมารดาได้ **)</small>
                        <div class="status-icon">
                            <?php if ($FatherCkeck): ?>
                                <span class="badge bg-label-success rounded-pill"><i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-label-danger rounded-pill"><i class="bx bxs-x-circle me-1"></i> ยังไม่กรอก</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mother Info Button -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card" onclick="showForm('mother-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center gap-3">
                        <div class="avatar avatar-xl bg-label-danger rounded-circle mb-2">
                            <i class="bx bx-female fs-1"></i>
                        </div>
                        <h5 class="card-title mb-0">ข้อมูลมารดา</h5>
                        <small class="text-muted" style="font-size: 0.8rem;">(** ถ้าไม่มีข้อมูลมารดา ให้ไปกรอกเมนูของบิดาได้ **)</small>
                        <div class="status-icon">
                            <?php if ($MatherCkeck): ?>
                                <span class="badge bg-label-success rounded-pill"><i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-label-danger rounded-pill"><i class="bx bxs-x-circle me-1"></i> ยังไม่กรอก</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardian Info Button -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 cursor-pointer shadow-sm hover-card" onclick="showForm('guardian-info')">
                    <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center gap-3">
                        <div class="avatar avatar-xl bg-label-warning rounded-circle mb-2">
                            <i class="bx bx-body fs-1"></i>
                        </div>
                        <h5 class="card-title mb-0">ข้อมูลผู้ปกครอง</h5>
                        <small class="text-danger fw-bold" style="font-size: 0.8rem;">(** จำเป็นต้องกรอก คนที่นักเรียนอาศัยอยู่ด้วย **)</small>
                        <div class="status-icon">
                            <?php if ($OtherCkeck): ?>
                                <span class="badge bg-label-success rounded-pill"><i class="bx bxs-check-circle me-1"></i> กรอกครบแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-label-danger rounded-pill"><i class="bx bxs-x-circle me-1"></i> ยังไม่กรอก</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Print Document Button -->
            <div class="col-12 mt-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                         <h5 class="card-title mb-3">พิมพ์เอกสารมอบตัว</h5>
                         <p class="card-text text-muted mb-3">เมื่อกรอกข้อมูลครบถ้วนแล้ว สามารถพิมพ์เอกสารมอบตัวได้ที่นี่</p>
                         
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
        
        $('input[name="par_service"]').change(function(){
            var name = $(this).val();
            // Hide all service inputs for Father
            $('input[name="par_serviceName[]"]').hide().val('');
            
            if(name != 'ไม่ได้รับราชการ'){
                 // Find the input associated with this radio
                 // Structure: .d-flex > .form-check > input[radio] ... input[text]
                 $(this).closest('.form-check').next('input[name="par_serviceName[]"]').show().focus();
            }
        });

        // Mother
        $('input[name="par_restM"]').change(function(){
            if($(this).val() == 'อื่นๆ'){
                $('#par_restOrthorM').show().focus();
            }else{
                $('#par_restOrthorM').hide().val('');
            }
        });
        
         $('input[name="par_serviceM"]').change(function(){
            var name = $(this).val();
            $('input[name="par_serviceNameM[]"]').hide().val('');
            if(name != 'ไม่ได้รับราชการ'){
                 $(this).closest('.form-check').next('input[name="par_serviceNameM[]"]').show().focus();
            }
        });

        // Guardian
        $('input[name="par_restO"]').change(function(){
            if($(this).val() == 'อื่นๆ'){
                $('#par_restOrthorO').show().focus();
            }else{
                $('#par_restOrthorO').hide().val('');
            }
        });
        
         $('input[name="par_serviceO"]').change(function(){
            var name = $(this).val();
            $('input[name="par_serviceNameO[]"]').hide().val('');
            if(name != 'ไม่ได้รับราชการ'){
                 $(this).closest('.form-check').next('input[name="par_serviceNameO[]"]').show().focus();
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

        // Trigger change events on load to set initial state
        $('input[name="par_rest"]:checked').trigger('change');
        $('input[name="par_service"]:checked').trigger('change');
        $('input[name="par_restM"]:checked').trigger('change');
        $('input[name="par_serviceM"]:checked').trigger('change');
        $('input[name="par_restO"]:checked').trigger('change');
        $('input[name="par_serviceO"]:checked').trigger('change');
        $('input[name="stu_presentLife"]:checked').trigger('change');

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

    });
</script>
<?= $this->endSection() ?>

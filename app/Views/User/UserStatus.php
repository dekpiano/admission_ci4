<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    .status-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #cbd5e1;
        border-top: 4px solid #e11d48;
        border-bottom: 4px solid #0284c7;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .status-header-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem auto;
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(225, 29, 72, 0.25);
    }

    .btn-status-search {
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        letter-spacing: 0.3px;
        transition: all 0.25s ease;
        box-shadow: 0 8px 18px rgba(225, 29, 72, 0.25);
        border-radius: 14px;
        padding: 0.8rem 1.5rem;
        min-height: 48px;
    }

    .btn-status-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(225, 29, 72, 0.35);
        color: #ffffff !important;
    }

    .btn-status-search:active {
        transform: scale(0.98);
    }

    .result-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.09);
        overflow: hidden;
    }

    .result-header {
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff;
        padding: 1rem 1.25rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.15);
    }

    .info-badge-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 50px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.76rem;
        font-weight: 600;
    }

    .status-viewport-container {
        min-height: calc(100vh - 155px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
        margin: 0 auto;
    }

    /* Mobile First Adjustments */
    @media (max-width: 576px) {
        .status-viewport-container {
            min-height: auto;
            padding-top: 0.5rem;
        }

        .status-card {
            border-radius: 16px;
        }

        .status-card .card-body {
            padding: 1.25rem 1rem !important;
        }

        .status-header-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            margin-bottom: 0.5rem;
        }

        .status-header-icon i {
            font-size: 1.4rem !important;
        }

        .page-title-text {
            font-size: 1.1rem !important;
        }

        .form-floating > label {
            font-size: 0.8rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="status-viewport-container">
    <div class="row justify-content-center w-100 mx-auto">
        <div class="col-12 col-lg-6 px-0 px-sm-2">
            <!-- Breadcrumb -->
            <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100 flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark page-title-text" style="font-size: 1rem;">
                    <span class="text-muted fw-light">ตรวจสอบ /</span> สถานะการสมัคร
                </h5>
                <span class="badge rounded-pill text-white px-2.5 py-1.5 fw-bold" style="background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%); font-size: 0.75rem;">
                    ระบบรับสมัคร
                </span>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible rounded-4 mb-3 shadow-sm" role="alert">
                    <i class='bx bx-check-circle me-1'></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible rounded-4 mb-3 shadow-sm" role="alert">
                    <i class='bx bx-error-circle me-1'></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Search Card -->
            <div class="card status-card mb-4">
                <div class="card-body p-3 p-sm-4 p-md-5">
                    <div class="text-center mb-3 mb-md-4">
                        <div class="status-header-icon">
                            <i class="bx bx-search-alt fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1 page-title-text" style="font-size: 1.15rem;">ตรวจสอบสถานะการสมัคร</h5>
                        
                        <div class="d-flex justify-content-center flex-wrap gap-1 my-1.5">
                            <div class="info-badge-chip">
                                <i class='bx bx-calendar text-primary'></i>
                                <span>ปีการศึกษา <?= esc($checkYear->openyear_year ?? '-') ?></span>
                            </div>
                            <?php if (isset($checkYear->openyear_year) && $checkYear->openyear_year >= 2569): ?>
                                <div class="info-badge-chip">
                                    <i class='bx bx-award text-info'></i>
                                    <span>รอบที่ <?= esc($systemStatus->onoff_round ?? '1') ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-muted small mb-0 mt-1" style="font-size: 0.78rem;">
                            กรอกเลขบัตรประชาชนและวันเดือนปีเกิดเพื่อตรวจสอบ
                        </p>
                    </div>

                    <form id="checkStatusForm">
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control rounded-3 fw-bold text-dark border-2" id="search_idcard" name="search_idcard"
                                    maxlength="17" required placeholder="เลขบัตรประชาชน 13 หลัก" autofocus>
                                <label for="search_idcard" class="text-muted">เลขประจำตัวประชาชน (13 หลัก)</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label mb-2 fw-bold text-dark small"><i class='bx bx-calendar-event me-1 text-primary'></i> วันเดือนปีเกิดของผู้สมัคร</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="form-floating">
                                        <select class="form-select rounded-3 border-2" name="search_day" id="search_day" required>
                                            <option value="">วัน</option>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <option value="<?= sprintf('%02d', $i) ?>"><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <label for="search_day">วัน</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating">
                                        <select class="form-select rounded-3 border-2" name="search_month" id="search_month" required>
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
                                        <label for="search_month">เดือน</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating">
                                        <select class="form-select rounded-3 border-2" name="search_year" id="search_year" required>
                                            <option value="">ปี (พ.ศ.)</option>
                                            <?php
                                            $curYear = date('Y') + 543;
                                            $startYear = $curYear - 25;
                                            $endYear = $curYear - 9;
                                            for ($i = $startYear; $i <= $endYear; $i++):
                                                ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <label for="search_year">ปี พ.ศ.</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-status-search w-100 btn-lg rounded-pill py-3">
                            <i class='bx bx-search-alt me-2 fs-5'></i> ตรวจสอบสถานะ
                        </button>
                    </form>
                </div>
            </div>

            <!-- Result Section -->
            <div id="resultSection" style="display: none;">
                <div class="card result-card mb-4 animate__animated animate__fadeIn">
                    <div class="result-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white fw-bold d-flex align-items-center">
                            <i class='bx bx-id-card me-2 fs-4'></i> ผลการตรวจสอบสถานะ
                        </h5>
                        <span class="badge rounded-pill px-3 py-2 fw-bold" id="res_status_badge"></span>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-4 text-muted small fw-bold">ชื่อ-นามสกุล:</div>
                                <div class="col-8 fw-bold text-dark fs-6" id="res_name"></div>
                            </div>
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-4 text-muted small fw-bold">ระดับชั้น:</div>
                                <div class="col-8 text-dark" id="res_level"></div>
                            </div>
                            <div class="row g-2 align-items-center">
                                <div class="col-4 text-muted small fw-bold">แผนการเรียน:</div>
                                <div class="col-8 text-dark fw-medium" id="res_program"></div>
                            </div>
                        </div>

                        <div class="alert alert-secondary rounded-3" id="res_message_box" style="display:none;">
                            <i class="bx bx-info-circle me-1"></i> <span id="res_message"></span>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <a href="#" class="btn btn-outline-primary rounded-pill py-2 fw-bold" id="print_btn" style="display:none;" target="_blank">
                                <i class="bx bx-printer me-2 fs-5"></i> พิมพ์ใบสมัคร (PDF)
                            </a>
                            <a href="#" class="btn btn-warning rounded-pill py-2 fw-bold text-dark" id="edit_btn" style="display:none;">
                                <i class="bx bx-edit me-2 fs-5"></i> แก้ไขข้อมูลใบสมัคร
                            </a>
                            <a href="<?= base_url('confirmation/login') ?>" class="btn btn-success rounded-pill py-2 fw-bold" id="confirmation_btn" style="display:none;">
                                <i class="bx bx-user-check me-2 fs-5"></i> รายงานตัวออนไลน์
                            </a>

                            <div class="alert alert-warning rounded-3 mt-2 mb-0" id="confirmation_closed_alert" style="display:none;">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error-circle me-2 fs-4"></i>
                                    <div>
                                        <strong>ยังไม่เปิดให้รายงานตัว</strong>
                                        <p class="mb-0 small">ระบบรายงานตัวยังไม่เปิดให้บริการในขณะนี้ กรุณาติดตามประกาศจากทางโรงเรียน</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('search_idcard').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
        e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
    });

    document.getElementById('checkStatusForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;

        const buddhistYear = parseInt(formData.get('search_year'));
        const gregorianYear = buddhistYear - 543;

        const dob = `${gregorianYear}-${formData.get('search_month')}-${formData.get('search_day')}`;
        formData.append('search_dob', dob);
        formData.set('search_year', gregorianYear);

        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> กำลังตรวจสอบ...';
        btn.disabled = true;

        fetch('<?= base_url('admission/get_student_status') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('disabled');
                btn.style.pointerEvents = 'auto';

                if (data.success) {
                    const student = data.student;
                    document.getElementById('resultSection').style.display = 'block';
                    document.getElementById('res_name').textContent = (student.recruit_prefix || '') + (student.recruit_firstName || '') + ' ' + (student.recruit_lastName || '');
                    document.getElementById('res_level').textContent = 'มัธยมศึกษาปีที่ ' + student.recruit_regLevel;
                    document.getElementById('res_program').textContent = student.recruit_tpyeRoom || '-';

                    const statusBadge = document.getElementById('res_status_badge');
                    statusBadge.textContent = student.recruit_status;
                    statusBadge.className = 'badge rounded-pill px-3 py-2 fw-bold';

                    document.getElementById('print_btn').style.display = 'none';
                    document.getElementById('edit_btn').style.display = 'none';
                    document.getElementById('confirmation_btn').style.display = 'none';
                    document.getElementById('confirmation_closed_alert').style.display = 'none';

                    if (student.recruit_status === 'ผ่านการตรวจสอบ') {
                        statusBadge.classList.add('bg-success');
                        document.getElementById('print_btn').style.display = 'block';
                        document.getElementById('print_btn').href = '<?= base_url('control_admission/pdf/') ?>' + student.recruit_id;

                        if (data.is_confirmation_open) {
                            document.getElementById('confirmation_btn').style.display = 'block';
                            document.getElementById('confirmation_closed_alert').style.display = 'none';
                        } else {
                            document.getElementById('confirmation_btn').style.display = 'none';
                            document.getElementById('confirmation_closed_alert').style.display = 'block';
                        }
                    } else {
                        if (student.recruit_status.includes('ไม่ผ่านการตรวจสอบ') || student.recruit_status.includes('แก้ไข')) {
                            statusBadge.classList.add('bg-danger');
                            document.getElementById('edit_btn').style.display = 'block';
                            document.getElementById('edit_btn').href = '<?= base_url('admission/edit/') ?>' + student.recruit_id;
                        } else if (student.recruit_status.includes('รอการตรวจสอบ')) {
                            statusBadge.classList.add('bg-warning', 'text-dark');
                        } else {
                            statusBadge.classList.add('bg-info');
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'พบข้อมูลการสมัคร',
                        text: 'แสดงรายละเอียดข้อมูลผู้สมัครของคุณแล้ว',
                        timer: 1500,
                        showConfirmButton: false
                    });

                } else {
                    document.getElementById('resultSection').style.display = 'none';
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่พบข้อมูล',
                        text: data.message || 'ไม่พบข้อมูลในระบบ กรุณาตรวจสอบเลขบัตรและวันเกิดอีกครั้ง',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#e11d48'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('disabled');
                btn.style.pointerEvents = 'auto';
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#e11d48'
                });
            });
    });
</script>
<?= $this->endSection() ?>
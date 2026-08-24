<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    .confirmation-viewport-wrapper {
        min-height: calc(100vh - 160px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin: 0 auto;
        padding: 1rem 0.75rem;
    }

    .confirmation-card {
        background: #ffffff;
        border-radius: 26px;
        border: 1.5px solid #cbd5e1;
        border-top: 4px solid #e11d48;
        border-bottom: 4px solid #0284c7;
        box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        width: 100%;
    }

    .confirmation-card .card-body {
        padding: 2.5rem 2.25rem !important;
    }

    .confirmation-header-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem auto;
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff;
        box-shadow: 0 10px 24px rgba(225, 29, 72, 0.28);
    }

    .btn-confirmation-login {
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        letter-spacing: 0.3px;
        transition: all 0.25s ease;
        box-shadow: 0 8px 22px rgba(225, 29, 72, 0.25);
        border-radius: 14px;
        padding: 0.85rem 1.5rem;
        min-height: 52px;
        font-size: 1rem;
    }

    .btn-confirmation-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(225, 29, 72, 0.35);
        color: #ffffff !important;
    }

    .btn-confirmation-login:active {
        transform: scale(0.98);
    }

    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px;
        color: #1e293b !important;
        padding-top: 1.45rem;
        padding-bottom: 0.45rem;
        font-size: 0.95rem;
    }

    .form-floating > .form-control:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.18) !important;
    }

    .form-floating > label {
        padding: 1rem 0.95rem;
        font-size: 0.88rem;
        color: #64748b;
        font-weight: 500;
    }

    .info-badge-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 50px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .helpline-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px;
        border-radius: 50px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 600;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 576px) {
        .confirmation-viewport-wrapper {
            min-height: auto;
            padding: 0.75rem 0.25rem;
        }

        .confirmation-card {
            border-radius: 20px;
        }

        .confirmation-card .card-body {
            padding: 1.75rem 1.35rem !important;
        }

        .confirmation-header-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            margin-bottom: 0.85rem;
        }

        .confirmation-header-icon i {
            font-size: 1.6rem !important;
        }

        .page-title-text {
            font-size: 1.2rem !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="confirmation-viewport-wrapper">
    <div class="row justify-content-center w-100 mx-auto">
        <div class="col-12 col-lg-6 px-0 px-sm-2">
            <!-- Breadcrumb -->
            <div class="d-flex align-items-center justify-content-between mb-3 w-100 flex-wrap gap-2">
                <h6 class="fw-bold mb-0 text-dark page-title-text" style="font-size: 0.95rem;">
                    <span class="text-muted fw-light">รายงานตัวออนไลน์ /</span> ยืนยันสิทธิ์
                </h6>
                <span class="badge rounded-pill text-white px-3 py-1.5 fw-bold" style="background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%); font-size: 0.75rem;">
                    ระบบรายงานตัว
                </span>
            </div>

            <!-- Confirmation Login Card -->
            <div class="card confirmation-card">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="confirmation-header-icon">
                    <i class="bx bxs-user-check fs-2"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2 page-title-text" style="font-size: 1.3rem;">รายงานตัวนักเรียนใหม่</h4>
                
                <div class="d-flex justify-content-center flex-wrap gap-2 my-2">
                    <div class="info-badge-chip">
                        <i class='bx bx-calendar text-primary'></i>
                        <span>ปีการศึกษา <?= esc($checkYear->openyear_year ?? '-') ?></span>
                    </div>
                    <div class="info-badge-chip">
                        <i class='bx bx-award text-info'></i>
                        <span>รอบที่ <?= esc($systemStatus->onoff_round ?? '1') ?></span>
                    </div>
                </div>

                <p class="text-muted small mb-0 mt-2" style="font-size: 0.82rem; line-height: 1.5;">
                    กรอกข้อมูลเพื่อเข้าสู่ระบบยืนยันสิทธิ์และรายงานตัวออนไลน์
                </p>
            </div>

            <form id="confirmationLoginForm" action="<?= base_url('confirmation/check') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control fw-bold text-dark" id="idenStu" name="idenStu" maxlength="17" required
                            placeholder="เลขบัตรประชาชน 13 หลัก" autofocus>
                        <label for="idenStu"><i class='bx bx-id-card me-1.5 text-primary'></i>เลขประจำตัวประชาชน (13 หลัก)</label>
                    </div>
                </div>

                <div class="mb-3.5">
                    <div class="form-floating">
                        <input type="tel" class="form-control fw-bold text-dark" id="recruit_phone" name="recruit_phone" maxlength="12" required
                            placeholder="เบอร์โทรศัพท์">
                        <label for="recruit_phone"><i class='bx bx-phone me-1.5 text-info'></i>เบอร์โทรศัพท์ (ที่ใช้สมัคร)</label>
                    </div>
                </div>

                <div class="d-grid mb-3 mt-1">
                    <button type="submit" class="btn btn-confirmation-login w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bx bx-log-in-circle fs-4"></i>
                        <span>เข้าสู่ระบบรายงานตัว</span>
                    </button>
                </div>
            </form>

            <div class="text-center pt-1.5">
                <a href="<?= base_url('new-admission/status') ?>" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 hover-primary" style="font-size: 0.82rem; font-weight: 500;">
                    <i class="bx bx-left-arrow-alt fs-5"></i> กลับไปหน้าตรวจสอบสถานะ
                </a>
            </div>

            <!-- Inlined Help Note -->
            <div class="text-center mt-4 pt-3 border-top">
                <div class="helpline-chip">
                    <i class='bx bx-support text-primary fs-5'></i>
                    <span>ติดต่อสอบถาม: <strong>056-009-667</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // SweetAlert2 Toast & Alerts for session messages
    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'ไม่สามารถเข้าสู่ระบบได้',
            html: '<?= addslashes(session()->getFlashdata('error')) ?>',
            confirmButtonText: 'เข้าใจแล้ว',
            confirmButtonColor: '#e11d48',
            customClass: {
                popup: 'rounded-4 shadow-lg'
            }
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            html: '<?= addslashes(session()->getFlashdata('success')) ?>',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#0284c7',
            customClass: {
                popup: 'rounded-4 shadow-lg'
            }
        });
    <?php endif; ?>

    // Input mask for ID Card (X-XXXX-XXXXX-XX-X)
    document.getElementById('idenStu').addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
        e.target.value = !v[2] ? v[1] : v[1] + '-' + v[2] + (v[3] ? '-' + v[3] : '') + (v[4] ? '-' + v[4] : '') + (v[5] ? '-' + v[5] : '');
    });

    // Input mask for Phone (XXX-XXX-XXXX)
    document.getElementById('recruit_phone').addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !v[2] ? v[1] : v[1] + '-' + v[2] + (v[3] ? '-' + v[3] : '');
    });

    // Form submit clean-up to submit raw numbers
    document.getElementById('confirmationLoginForm').addEventListener('submit', function (e) {
        const idCardInput = document.getElementById('idenStu');
        const phoneInput = document.getElementById('recruit_phone');
        
        const plainIdCard = idCardInput.value.replace(/\D/g, '');
        const plainPhone = phoneInput.value.replace(/\D/g, '');

        // Replace values with clean unmasked strings before sending
        idCardInput.value = plainIdCard;
        phoneInput.value = plainPhone;
    });
</script>
<?= $this->endSection() ?>
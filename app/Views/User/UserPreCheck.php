<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    .precheck-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1.5px solid #cbd5e1;
        border-top: 4px solid <?= ($level >= 4) ? '#0284c7' : '#e11d48' ?>;
        border-bottom: 4px solid <?= ($level >= 4) ? '#e11d48' : '#0284c7' ?>;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .precheck-header-icon {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem auto;
        background: <?= ($level >= 4) ? 'linear-gradient(135deg, #0284c7 0%, #38bdf8 100%)' : 'linear-gradient(135deg, #e11d48 0%, #ff2d75 100%)' ?>;
        color: #ffffff;
        box-shadow: 0 10px 25px <?= ($level >= 4) ? 'rgba(2, 132, 199, 0.35)' : 'rgba(225, 29, 72, 0.35)' ?>;
    }

    .btn-precheck-submit {
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(225, 29, 72, 0.25);
    }

    .btn-precheck-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(225, 29, 72, 0.35);
        color: #ffffff !important;
    }

    .btn-precheck-back {
        border: 1.5px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-precheck-back:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #0284c7;
        font-weight: 700;
    }

    .form-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.15);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center w-100 mx-auto">
    <div class="col-12 col-lg-6 px-0 px-sm-2">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center justify-content-between mb-3 w-100 flex-wrap gap-2">
            <h5 class="fw-bold mb-0 text-dark">
                <span class="text-muted fw-light">สมัครเรียน /</span> ตรวจสอบสิทธิ์การสมัคร
            </h5>
            <span class="badge rounded-pill text-white px-3 py-1.5 fw-bold" style="background: <?= ($level >= 4) ? '#0284c7' : '#e11d48' ?>; font-size: 0.82rem;">
                ระดับชั้น มัธยมศึกษาปีที่ <?= esc($level) ?>
            </span>
        </div>

        <div class="card precheck-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="precheck-header-icon">
                            <i class="bx bx-id-card fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">ตรวจสอบเลขบัตรประชาชน</h4>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border text-muted small mb-2">
                            <i class='bx bx-calendar text-primary'></i>
                            <span>ปีการศึกษา <?= isset($checkYear->openyear_year) ? esc($checkYear->openyear_year) : '-' ?></span>
                            <?php if (isset($checkYear->openyear_year) && $checkYear->openyear_year >= 2569): ?>
                                <span>• รอบที่ <?= esc($systemStatus->onoff_round ?? '1') ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small mb-0">กรุณากรอกเลขประจำตัวประชาชน 13 หลักของผู้สมัคร เพื่อตรวจสอบสิทธิ์ก่อนดำเนินการ</p>
                    </div>

                    <form action="<?= base_url('new-admission/check-id') ?>" method="post">
                        <input type="hidden" name="level" value="<?= esc($level) ?>">

                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control rounded-4 fw-bold fs-5 text-center text-dark border-2" name="recruit_idCard" id="recruit_idCard"
                                    maxlength="17" required placeholder="เลขบัตรประชาชน 13 หลัก" autofocus style="letter-spacing: 2px;">
                                <label for="recruit_idCard" class="w-100 text-center text-muted">เลขประจำตัวประชาชน (13 หลัก)</label>
                            </div>
                        </div>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger rounded-3 d-flex align-items-center mb-4" role="alert">
                                <i class="bx bx-error-circle fs-4 me-2"></i> 
                                <div><?= session()->getFlashdata('error') ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-precheck-submit btn-lg rounded-pill py-3" id="checkIdBtn">
                                <i class='bx bx-search-alt me-2 fs-5'></i> ตรวจสอบสิทธิ์การสมัคร
                            </button>
                            <a href="<?= base_url('new-admission') ?>" class="btn btn-precheck-back rounded-pill py-2 mt-2" id="backBtn">
                                <i class='bx bx-arrow-back me-1'></i> กลับหน้าหลัก
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function checkThaiID(id) {
        if (id.length != 13) return false;
        let sum = 0;
        for (let i = 0; i < 12; i++) {
            sum += parseFloat(id.charAt(i)) * (13 - i);
        }
        if ((11 - sum % 11) % 10 != parseFloat(id.charAt(12))) {
            return false;
        }
        return true;
    }

    document.getElementById('recruit_idCard').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
        e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');

        // Real-time validation visual feedback
        const rawId = e.target.value.replace(/-/g, '');
        if (rawId.length === 13) {
            if (checkThaiID(rawId)) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            } else {
                e.target.classList.remove('is-valid');
                e.target.classList.add('is-invalid');
            }
        } else {
            e.target.classList.remove('is-valid', 'is-invalid');
        }
    });

    document.querySelector('form').addEventListener('submit', function (e) {
        const form = this;
        const idInput = document.getElementById('recruit_idCard');
        const submitBtn = form.querySelector('button[type="submit"]');
        const backBtn = form.querySelector('a.btn-precheck-back');
        const rawId = idInput.value.replace(/-/g, '');

        if (!checkThaiID(rawId)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'เลขบัตรประชาชนไม่ถูกต้อง',
                text: 'กรุณาตรวจสอบเลขบัตรประชาชนอีกครั้ง',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#e11d48'
            });
            idInput.classList.add('is-invalid');

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bx bx-search-alt me-2 fs-5"></i> ตรวจสอบสิทธิ์การสมัคร';

            if (backBtn) {
                backBtn.classList.remove('disabled');
                backBtn.style.pointerEvents = 'auto';
            }
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>กำลังตรวจสอบ...';

        if (backBtn) {
            backBtn.classList.add('disabled');
            backBtn.style.pointerEvents = 'none';
        }

        idInput.readOnly = true;
        idInput.style.backgroundColor = '#f1f5f9';
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('User/UserLayout') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">สมัครเรียน /</span> ตรวจสอบสิทธิ์การสมัคร</h4>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bx bx-id-card display-4 text-primary"></i>
                    <h5 class="mt-2">ตรวจสอบเลขบัตรประชาชน</h5>
                    <p class="text-muted">ปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : '-' ?></p>
                    <p class="text-muted">กรุณากรอกเลขบัตรประชาชนเพื่อตรวจสอบสิทธิ์ก่อนสมัคร</p>
                </div>

                <form action="<?= base_url('new-admission/check-id') ?>" method="post">
                    <input type="hidden" name="level" value="<?= $level ?>">
                    


                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="recruit_idCard" id="recruit_idCard" maxlength="17" required placeholder="เลขบัตรประชาชน 13 หลัก">
                            <label for="recruit_idCard">เลขบัตรประชาชน (13 หลัก)</label>
                        </div>
                    </div>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bx bx-error-circle me-1"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">ตรวจสอบ</button>
                    <a href="<?= base_url('new-admission') ?>" class="btn btn-outline-secondary w-100 mt-2">ย้อนกลับ</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>



<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function checkThaiID(id) {
        if(id.length != 13) return false;
        for(i=0, sum=0; i < 12; i++)
            sum += parseFloat(id.charAt(i))*(13-i);
        if((11-sum%11)%10!=parseFloat(id.charAt(12)))
            return false;
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

    document.querySelector('form').addEventListener('submit', function(e) {
        const idInput = document.getElementById('recruit_idCard');
        const rawId = idInput.value.replace(/-/g, '');
        
        if (!checkThaiID(rawId)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'เลขบัตรประชาชนไม่ถูกต้อง',
                text: 'กรุณาตรวจสอบเลขบัตรประชาชนอีกครั้ง',
                confirmButtonText: 'ตกลง'
            });
            idInput.classList.add('is-invalid');
        }
    });


</script>
<?= $this->endSection() ?>

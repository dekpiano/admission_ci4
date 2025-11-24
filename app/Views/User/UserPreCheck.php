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
                            <input type="text" class="form-control" name="recruit_idCard" id="recruit_idCard" maxlength="17" required placeholder="เลขบัตรประชาชน 13 หลัก" autofocus>
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

<?= $this->section('scripts') ?>
<script>
    document.getElementById('recruit_idCard').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
        e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
    });
</script>
<?= $this->endSection() ?>

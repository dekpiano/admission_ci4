<?= $this->extend('User/UserLayout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bx bxs-user-check display-4 text-primary"></i>
                    <h4 class="mt-2">รายงานตัวนักเรียนใหม่</h4>
                    <p class="text-muted">สำหรับผู้ที่ผ่านการคัดเลือกและต้องการยืนยันสิทธิ์</p>
                </div>

                <form id="confirmationLoginForm" action="<?= base_url('confirmation/check') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="idenStu" name="idenStu" maxlength="17" required placeholder="เลขบัตรประชาชน 13 หลัก">
                            <label for="idenStu">เลขประจำตัวประชาชน</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                         <div class="form-floating">
                            <input type="tel" class="form-control" id="recruit_phone" name="recruit_phone" required placeholder="เบอร์โทรศัพท์">
                            <label for="recruit_phone">เบอร์โทรศัพท์ (ที่ใช้ในการสมัคร)</label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bx bx-log-in-circle me-1"></i> เข้าสู่ระบบเพื่อรายงานตัว
                        </button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                     <a href="<?= base_url('new-admission/status') ?>"><i class="bx bx-left-arrow-alt me-1"></i>กลับไปหน้าตรวจสอบสถานะ</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Input mask for ID card
    document.getElementById('idenStu').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,4})(\d{0,5})(\d{0,2})(\d{0,1})/);
        e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
    });

    document.getElementById('confirmationLoginForm').addEventListener('submit', function(e) {
        const idCardInput = document.getElementById('idenStu');
        const plainIdCard = idCardInput.value.replace(/-/g, '');
        // Create a hidden input to hold the plain value
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'idenStu';
        hiddenInput.value = plainIdCard;
        this.appendChild(hiddenInput);
    });
</script>
<?= $this->endSection() ?>

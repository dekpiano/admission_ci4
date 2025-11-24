<?= $this->extend('User/UserLayout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bx bxs-user-check display-4 text-primary"></i>
                    <h4 class="mt-2">รายงานตัวนักเรียนใหม่</h4>
                    <p class="text-muted">สำหรับผู้ที่ผ่านการตรวจสอบสิทธิ์แล้ว</p>
                </div>

                <form id="confirmLoginForm">
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
                            <i class="bx bx-log-in-circle me-1"></i> เข้าสู่ระบบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="<?= base_url() ?>"><i class="bx bx-home me-1"></i> กลับหน้าแรก</a>
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

    // Form submission
    document.getElementById('confirmLoginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;

        // Add plain ID card number for backend processing
        const idCardInput = document.getElementById('idenStu').value;
        const plainIdCard = idCardInput.replace(/-/g, '');
        formData.set('idenStu', plainIdCard);

        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังตรวจสอบ...';
        btn.disabled = true;

        fetch('<?= base_url('login/confirm_student') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(text => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if (text.trim() === '1') {
                Swal.fire({
                    icon: 'success',
                    title: 'เข้าสู่ระบบสำเร็จ',
                    text: 'กำลังนำท่านไปยังหน้ากรอกข้อมูลรายงานตัว',
                    timer: 1500,
                    showConfirmButton: false,
                    willClose: () => {
                        window.location.href = '<?= base_url('Confirm') ?>';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อมูลไม่ถูกต้อง',
                    text: 'ไม่พบข้อมูลในระบบ หรือยังไม่ผ่านการตรวจสอบสิทธิ์ กรุณาตรวจสอบข้อมูลและลองอีกครั้ง',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

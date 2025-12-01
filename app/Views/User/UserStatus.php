<?= $this->extend('User/UserLayout') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตรวจสอบ /</span> สถานะการสมัคร</h4>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bx bx-search-alt display-4 text-primary"></i>
                    <h5 class="mt-2">ตรวจสอบสถานะการสมัคร</h5>
                    <p class="text-muted">กรอกเลขบัตรประชาชนและวันเดือนปีเกิด</p>
                </div>

                <form id="checkStatusForm">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="search_idcard" name="search_idcard" maxlength="17" required placeholder="เลขบัตรประชาชน 13 หลัก">
                            <label for="search_idcard">เลขประจำตัวประชาชน</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label mb-2">วันเดือนปีเกิด</label>
                        <div class="row g-2">
                            <div class="col-3">
                                <div class="form-floating">
                                    <select class="form-select" name="search_day" id="search_day" required>
                                        <option value="">วัน</option>
                                        <?php for($i=1; $i<=31; $i++): ?>
                                            <option value="<?= sprintf('%02d', $i) ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <label for="search_day">วัน</label>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-floating">
                                    <select class="form-select" name="search_month" id="search_month" required>
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
                                    <select class="form-select" name="search_year" id="search_year" required>
                                        <option value="">ปี (พ.ศ.)</option>
                                        <?php 
                                            $curYear = date('Y')+543; 
                                            // Expand range: 9 years old to 25 years old
                                            $startYear = $curYear - 25;
                                            $endYear = $curYear - 9;
                                            for($i=$startYear; $i<=$endYear; $i++): 
                                        ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <label for="search_year">ปี</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">ตรวจสอบสถานะ</button>
                </form>
            </div>
        </div>

        <!-- Result Section -->
        <div id="resultSection" style="display: none;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">ผลการตรวจสอบ</h5>
                    <span class="badge" id="res_status_badge"></span>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-4 text-muted">ชื่อ-นามสกุล:</div>
                        <div class="col-8 fw-bold" id="res_name"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">ระดับชั้น:</div>
                        <div class="col-8" id="res_level"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">แผนการเรียน:</div>
                        <div class="col-8" id="res_program"></div>
                    </div>
                    
                    <div class="alert alert-secondary mt-3" id="res_message_box" style="display:none;">
                        <i class="bx bx-info-circle me-1"></i> <span id="res_message"></span>
                    </div>

                    <div class="d-grid mt-3">
                        <a href="#" class="btn btn-outline-primary" id="print_btn" style="display:none;" target="_blank">
                            <i class="bx bx-printer me-1"></i> พิมพ์ใบสมัคร
                        </a>
                        <a href="#" class="btn btn-warning mt-2" id="edit_btn" style="display:none;">
                            <i class="bx bx-edit me-1"></i> แก้ไขข้อมูล
                        </a>
                        <a href="<?= base_url('confirmation/login') ?>" class="btn btn-success mt-2" id="confirmation_btn" style="display:none;">
                            <i class="bx bx-user-check me-1"></i> รายงานตัวออนไลน์
                        </a>
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

    document.getElementById('checkStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        
        // Convert Buddhist Year to Gregorian Year
        const buddhistYear = parseInt(formData.get('search_year'));
        const gregorianYear = buddhistYear - 543;
        
        // Construct Date string (YYYY-MM-DD) for backend
        const dob = `${gregorianYear}-${formData.get('search_month')}-${formData.get('search_day')}`;
        formData.append('search_dob', dob); // Send as search_dob or handle individual fields in backend
        
        // Update year in formData to Gregorian for individual field check if needed
        formData.set('search_year', gregorianYear);

        // Debug: Log data being sent
        console.log('--- Data sending to server ---');
        console.log('ID Card:', formData.get('search_idcard'));
        console.log('DOB (Gregorian):', dob);
        console.log('Year (Gregorian):', gregorianYear);
        console.log('Month:', formData.get('search_month'));
        console.log('Day:', formData.get('search_day'));
        console.log('------------------------------');

        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังตรวจสอบ...';
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
            console.log(data);
            
            if (data.success) {
                const student = data.student;
                document.getElementById('resultSection').style.display = 'block';
                document.getElementById('res_name').textContent = student.recruit_prefix + student.recruit_firstName + ' ' + student.recruit_lastName;
                document.getElementById('res_level').textContent = 'มัธยมศึกษาปีที่ ' + student.recruit_regLevel;
                document.getElementById('res_program').textContent = student.recruit_tpyeRoom;
                
                const statusBadge = document.getElementById('res_status_badge');
                statusBadge.textContent = student.recruit_status;
                
                // Reset classes
                statusBadge.className = 'badge';
                
                document.getElementById('print_btn').style.display = 'none';
                document.getElementById('edit_btn').style.display = 'none';
                document.getElementById('confirmation_btn').style.display = 'none';

                if (student.recruit_status === 'ผ่านการตรวจสอบ') {
                    statusBadge.classList.add('bg-success'); // Green
                    document.getElementById('print_btn').style.display = 'block';
                    document.getElementById('print_btn').href = '<?= base_url('control_admission/pdf/') ?>' + student.recruit_id;
                    document.getElementById('confirmation_btn').style.display = 'block'; // Show confirmation button
                } else { // Status is not 'ผ่านการตรวจสอบ'
                    // Determine badge color for non-verified statuses
                    if (student.recruit_status.includes('ไม่ผ่านการตรวจสอบ') || student.recruit_status.includes('แก้ไข')) {
                        statusBadge.classList.add('bg-danger'); // Red
                        document.getElementById('edit_btn').style.display = 'block'; // Show edit button
                        document.getElementById('edit_btn').href = '<?= base_url('admission/edit/') ?>' + student.recruit_id;
                    } else if (student.recruit_status.includes('รอการตรวจสอบ')) {
                        statusBadge.classList.add('bg-warning'); // Yellow/Orange (Waiting)
                    }
                     else {
                        statusBadge.classList.add('bg-info'); // Default for other non-verified statuses
                    }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'พบข้อมูล',
                    text: 'แสดงรายละเอียดการสมัครของคุณ',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });

            } else {
                document.getElementById('resultSection').style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่พบข้อมูล',
                    text: data.message || 'ไม่พบข้อมูลในระบบ กรุณาตรวจสอบอีกครั้ง',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
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
                },
                buttonsStyling: false
            });
        });
    });
</script>
<?= $this->endSection() ?>

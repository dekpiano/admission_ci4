<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding: 2rem 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 50px;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 10px;
    }
    .timeline-item {
        position: relative;
        padding-left: 100px;
        margin-bottom: 3rem;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 8px 20px rgba(255, 158, 181, 0.3);
        z-index: 2;
        transition: all 0.3s ease;
    }
    .timeline-item:nth-child(even) .timeline-icon {
        background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%);
        box-shadow: 0 8px 20px rgba(132, 210, 246, 0.3);
    }
    .timeline-icon:hover {
        transform: scale(1.1) rotate(5deg);
    }
    .timeline-number {
        position: absolute;
        bottom: -8px;
        right: -8px;
        width: 32px;
        height: 32px;
        background: #fff;
        color: #ff9eb5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .timeline-item:nth-child(even) .timeline-number {
        color: #84d2f6;
    }
    .timeline-content {
        background: #fff;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-left: 5px solid #ff9eb5;
        transition: all 0.3s ease;
    }
    .timeline-item:nth-child(even) .timeline-content {
        border-left-color: #84d2f6;
    }
    .timeline-content:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    .timeline-title {
        color: #ff9eb5;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }
    .timeline-item:nth-child(even) .timeline-title {
        color: #84d2f6;
    }
    .doc-badge {
        display: inline-block;
        background: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        margin: 0.25rem;
        border-left: 3px solid #ff9eb5;
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .timeline::before {
            left: 35px;
        }
        .timeline-item {
            padding-left: 80px;
        }
        .timeline-icon {
            width: 70px;
            height: 70px;
            font-size: 1.8rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">หน้าหลัก /</span> คู่มือการสมัครเรียน</h4>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-center" style="background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);">
                <div class="card-body py-5">
                    <h2 class="text-white fw-bold mb-2"><i class='bx bx-book-reader me-2'></i>คู่มือการสมัครเรียนออนไลน์</h2>
                    <p class="text-white fs-5 mb-0">ขั้นตอนง่ายๆ ในการสมัครเข้าศึกษาต่อ โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    
                    <div class="timeline">
                        
                        <!-- Step 1 -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-file-find'></i>
                                <div class="timeline-number">1</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">ตรวจสอบคุณสมบัติและยอมรับข้อตกลง PDPA</h5>
                                <p class="text-muted mb-3">เข้าสู่ระบบและยอมรับนโยบายการใช้ข้อมูลส่วนบุคคล</p>
                                <ul class="mb-0">
                                    <li>เข้าหน้าแรกของระบบรับสมัคร <a href="<?= base_url('new-admission') ?>">คลิกที่นี่</a></li>
                                    <li>เลือกระดับชั้นที่ต้องการสมัคร (ม.1 หรือ ม.4)</li>
                                    <li>อ่านและยอมรับข้อตกลง <strong>PDPA</strong></li>
                                    <li>กดปุ่ม <span class="badge bg-primary">"ยอมรับและดำเนินการต่อ"</span></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-id-card'></i>
                                <div class="timeline-number">2</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">ตรวจสอบสิทธิ์ด้วยเลขบัตรประชาชน</h5>
                                <p class="text-muted mb-3">ป้องกันการสมัครซ้ำและตรวจสอบคุณสมบัติ</p>
                                <ul class="mb-0">
                                    <li>กรอกเลขประจำตัวประชาชน <strong>13 หลัก</strong> ของผู้สมัคร</li>
                                    <li>กดปุ่ม <span class="badge bg-info">"ตรวจสอบ"</span></li>
                                    <li>หากยังไม่เคยสมัคร ระบบจะนำไปหน้ากรอกใบสมัคร</li>
                                    <li>หากเคยสมัครแล้ว ระบบจะแจ้งเตือน</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-edit-alt'></i>
                                <div class="timeline-number">3</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">กรอกข้อมูลใบสมัครให้ครบถ้วน</h5>
                                <p class="text-muted mb-3">กรอกข้อมูลนักเรียน และแผนการเรียน</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">ข้อมูลนักเรียน</h6>
                                        <ul class="small">
                                            <li>แผนการเรียน/สาขาวิชา</li>
                                            <li>ชื่อ-สกุล, วันเดือนปีเกิด</li>
                                            <li>สัญชาติ, ศาสนา, เชื้อชาติ</li>
                                            <li>ที่อยู่, เบอร์โทรศัพท์</li>
                                            <li>โรงเรียนเดิม, GPAX</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-cloud-upload'></i>
                                <div class="timeline-number">4</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">อัปโหลดเอกสารประกอบการสมัคร</h5>
                                <p class="text-muted mb-3">เตรียมเอกสารในรูปแบบไฟล์ .jpg, .png หรือ .pdf (ไม่เกิน 2MB)</p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="doc-badge"><i class='bx bx-image me-1'></i> รูปถ่ายนักเรียน</div>
                                    <div class="doc-badge"><i class='bx bx-file me-1'></i> ปพ.1 (หน้า-หลัง)</div>
                                    <div class="doc-badge"><i class='bx bx-id-card me-1'></i> สำเนาบัตรประชาชน</div>
                                </div>
                                <div class="alert alert-warning mb-0" role="alert">
                                    <i class='bx bx-error-circle me-1'></i> <strong>สำคัญ!</strong> <span class="badge bg-danger">เซ็นสำเนาถูกต้องทุกใบ</span> ตรวจสอบความชัดเจนของไฟล์ก่อนอัปโหลด
                                </div>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-check-circle'></i>
                                <div class="timeline-number">5</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">ตรวจสอบและยืนยันข้อมูล</h5>
                                <p class="text-muted mb-3">ตรวจสอบความถูกต้องก่อนส่งใบสมัคร</p>
                                <ul class="mb-0">
                                    <li>ตรวจสอบข้อมูลทั้งหมดอีกครั้ง</li>
                                    <li>แก้ไขหากพบข้อผิดพลาด</li>
                                    <li>กดปุ่ม <span class="badge bg-success">"บันทึกข้อมูลการสมัคร"</span></li>
                                    <li>รอหน้ายืนยันการสมัครจากระบบ</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-printer'></i>
                                <div class="timeline-number">6</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">พิมพ์ใบสมัครและเก็บไว้เป็นหลักฐาน</h5>
                                <p class="text-muted mb-3">ดาวน์โหลดและเก็บใบสมัครไว้สำหรับยื่นในวันสอบ</p>
                                <ul class="mb-0">
                                    <li>เข้าหน้า <strong>"ตรวจสอบสถานะ"</strong> จากเมนูหลัก</li>
                                    <li>กรอกข้อมูลเพื่อล็อกอิน</li>
                                    <li>คลิกปุ่ม <span class="badge bg-danger">"พิมพ์ใบสมัคร"</span></li>
                                    <li>พิมพ์และนำมายื่นในวันสอบคัดเลือก</li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="card-footer text-center bg-light py-3">
                    <a href="<?= base_url('new-admission') ?>" class="btn btn-primary btn-lg px-5 rounded-pill">
                        <i class='bx bx-home-alt me-2'></i> กลับสู่หน้าหลัก
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
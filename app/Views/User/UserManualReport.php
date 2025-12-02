<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* Reporting Timeline Styles */
    .report-timeline {
        position: relative;
        padding: 2rem 0;
    }
    .report-step {
        display: flex;
        align-items: flex-start;
        margin-bottom: 3rem;
        position: relative;
    }
    .report-step::before {
        content: '';
        position: absolute;
        left: 60px;
        top: 80px;
        bottom: -50px;
        width: 3px;
        background: linear-gradient(180deg, #ff9eb5 0%, #84d2f6 100%);
    }
    .report-step:last-child::before {
        display: none;
    }
    .report-icon-wrapper {
        min-width: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-right: 2rem;
    }
    .report-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(255, 158, 181, 0.4);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        position: relative;
        z-index: 2;
    }
    .report-step:nth-child(2) .report-icon {
        background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%);
        box-shadow: 0 10px 30px rgba(132, 210, 246, 0.4);
    }
    .report-step:nth-child(3) .report-icon {
        background: linear-gradient(135deg, #a8e6cf  0%, #c4f5da 100%);
        box-shadow: 0 10px 30px rgba(168, 230, 207, 0.4);
    }
    .report-step:nth-child(4) .report-icon {
        background: linear-gradient(135deg, #ffd89b 0%, #ffe9b8 100%);
        box-shadow: 0 10px 30px rgba(255, 216, 155, 0.4);
    }
    .report-icon:hover {
        transform: scale(1.15) rotate(-5deg);
    }
    .report-step-number {
        margin-top: 1rem;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        color: #ff9eb5;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .report-step:nth-child(2) .report-step-number {
        color: #84d2f6;
    }
    .report-step:nth-child(3) .report-step-number {
        color: #a8e6cf;
    }
    .report-step:nth-child(4) .report-step-number {
        color: #ffd89b;
    }
    .report-content {
        flex: 1;
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .report-content:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .report-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ff9eb5;
        margin-bottom: 1rem;
    }
    .report-step:nth-child(2) .report-title {
        color: #84d2f6;
    }
    .report-step:nth-child(3) .report-title {
        color: #a8e6cf;
    }
    .report-step:nth-child(4) .report-title {
        color: #ffd89b;
    }
    
    @media (max-width: 768px) {
        .report-step {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .report-step::before {
            display: none;
        }
        .report-icon-wrapper {
            margin-right: 0;
            margin-bottom: 1.5rem;
        }
        .report-icon {
            width: 90px;
            height: 90px;
            font-size: 2.5rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">หน้าหลัก /</span> คู่มือการรายงานตัวและมอบตัว</h4>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-center" style="background: linear-gradient(135deg, #84d2f6 0%, #ff9eb5 100%);">
                <div class="card-body py-5">
                    <h2 class="text-white fw-bold mb-2"><i class='bx bx-user-check me-2'></i>คู่มือการรายงานตัวและมอบตัวนักเรียนใหม่</h2>
                    <p class="text-white fs-5 mb-0">ขอแสดงความยินดี! กรุณาดำเนินการตามขั้นตอนเพื่อรักษาสิทธิ์การเข้าศึกษา</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-md-5 p-3">
                    
                    <div class="report-timeline">
                        
                        <!-- Step 1: Check Results -->
                        <div class="report-step">
                            <div class="report-icon-wrapper">
                                <div class="report-icon">
                                    <i class='bx bx-search-alt'></i>
                                </div>
                                <div class="report-step-number">1</div>
                            </div>
                            <div class="report-content">
                                <h3 class="report-title">ตรวจสอบรายชื่อผู้ผ่านการคัดเลือก</h3>
                                <p class="text-muted mb-3">เข้าสู่เว็บไซต์โรงเรียนเพื่อตรวจสอบประกาศผลสอบคัดเลือก</p>
                                <ul class="mb-3">
                                    <li>เข้าหน้าเว็บไซต์ <a href="<?= base_url() ?>">ระบบรับสมัครนักเรียนใหม่</a></li>
                                    <li>คลิกเมนู <a href="<?= base_url('new-admission/status') ?>"><strong>"ตรวจสอบสถานะ"</strong></a></li>
                                    <li>กรอกเลขบัตรประชาชนและวันเกิด เพื่อดูรายละเอียด</li>
                                    <li>ตรวจสอบว่าสถานะเป็น <span class="badge bg-success">"ผ่านการคัดเลือก"</span> ถ้าไม่ใช่ ให้กลับไป <span class="badge bg-warning">"แก้ไขข้อมูล"</span></li>
                                </ul>
                                <div class="alert alert-info" role="alert">
                                    <i class='bx bx-info-circle me-1'></i> หากพบชื่อในประกาศ ให้ดำเนินการตามขั้นตอนถัดไป
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Confirm Rights Online -->
                        <div class="report-step">
                            <div class="report-icon-wrapper">
                                <div class="report-icon">
                                    <i class='bx bx-check-double'></i>
                                </div>
                                <div class="report-step-number">2</div>
                            </div>
                            <div class="report-content">
                                <h3 class="report-title">รายงานตัวออนไลน์</h3>
                                <p class="text-muted mb-3">ยืนยันการรับสิทธิ์เข้าศึกษาผ่านระบบออนไลน์</p>
                                <ul class="mb-3">
                                    <li>เข้าระบบรายงานตัว (ใช้เลขบัตรประชาชนและวันเดือนปีเกิด)</li>
                                    <li>อ่านรายละเอียดเงื่อนไขการรายงานตัว</li>
                                    <li>กดปุ่ม <a href="<?= base_url('confirmation/login') ?>"><span class="badge bg-primary">"รายงานตัวนักเรียนใหม่"</span></a></li>
                                    <li>รอข้อความยืนยันจากระบบ</li>
                                </ul>
                                <div class="alert alert-warning" role="alert">
                                    <i class='bx bx-time-five me-1'></i> <strong>สำคัญ!</strong> ต้องยืนยันสิทธิ์ภายในระยะเวลาที่กำหนด มิฉะนั้นจะถือว่าสละสิทธิ์
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Print Documents -->
                        <div class="report-step">
                            <div class="report-icon-wrapper">
                                <div class="report-icon">
                                    <i class='bx bx-printer'></i>
                                </div>
                                <div class="report-step-number">3</div>
                            </div>
                            <div class="report-content">
                                <h3 class="report-title">พิมพ์เอกสารมอบตัว</h3>
                                <p class="text-muted mb-3">ดาวน์โหลดและพิมพ์เอกสารที่จำเป็นสำหรับวันมอบตัว</p>
                                <ul class="mb-3">
                                    <li>ล็อกอินเข้าระบบรายงานตัวอีกครั้ง</li>
                                    <li>คลิกปุ่ม <span class="badge bg-danger">"พิมพ์ใบมอบตัว"</span></li>
                                    <li>ตรวจสอบข้อมูลให้ถูกต้อง</li>
                                    <li>พิมพ์ออกมา</li>
                                </ul>
                                <h6 class="fw-bold mt-4 mb-2"> <i class='bx bx-folder-open me-1'></i>เอกสารประกอบที่ต้องเตรียม:</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start p-2 bg-light rounded">
                                            <i class='bx bx-check text-success me-2 fs-5'></i>
                                            <small>ใบมอบตัว (พิมพ์จากระบบ)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start p-2 bg-light rounded">
                                            <i class='bx bx-check text-success me-2 fs-5'></i>
                                            <small>ปพ.1 ฉบับจริง + สำเนา</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start p-2 bg-light rounded">
                                            <i class='bx bx-check text-success me-2 fs-5'></i>
                                            <small>สำเนาทะเบียนบ้าน (นักเรียน, บิดา, มารดา)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start p-2 bg-light rounded">
                                            <i class='bx bx-check text-success me-2 fs-5'></i>
                                            <small>สำเนาบัตรประชาชน (นักเรียน, บิดา, มารดา)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start p-2 bg-light rounded">
                                            <i class='bx bx-check text-success me-2 fs-5'></i>
                                            <small>หลักฐานการเปลี่ยนชื่อ-สกุล (ถ้ามี)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Report to School -->
                        <div class="report-step">
                            <div class="report-icon-wrapper">
                                <div class="report-icon">
                                    <i class='bx bx-home-heart'></i>
                                </div>
                                <div class="report-step-number">4</div>
                            </div>
                            <div class="report-content">
                                <h3 class="report-title">เดินทางมามอบตัวที่โรงเรียน</h3>
                                <p class="text-muted mb-3">นำเอกสารทั้งหมดมายื่นในวันและเวลาที่กำหนด</p>
                                <ul class="mb-3">
                                    <li>นักเรียนและผู้ปกครองเดินทางมาโรงเรียนตามวันเวลาที่ระบุในประกาศ</li>
                                    <li>แต่งกาย <strong>ชุดนักเรียนโรงเรียนเดิม</strong> หรือชุดสุภาพ</li>
                                    <li>นำเอกสารทั้งหมดมาส่งที่จุดลงทะเบียน</li>
                                    <li>รับเอกสารและคำแนะนำจากเจ้าหน้าที่</li>
                                </ul>
                                <div class="alert alert-danger" role="alert">
                                    <h6 class="alert-heading fw-bold mb-1"><i class='bx bx-error-circle me-1'></i> หมายเหตุสำคัญ</h6>
                                    <p class="mb-0">หากไม่มารายงานตัวและมอบตัวตามวันเวลาที่กำหนด จะถือว่า <strong>"สละสิทธิ์"</strong> การเข้าศึกษาต่อโดยอัตโนมัติ</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="card-footer text-center bg-light py-3">
                    <a href="<?= base_url('new-admission') ?>" class="btn btn-outline-secondary me-2 rounded-pill px-4">
                        <i class='bx bx-arrow-back me-1'></i> กลับหน้าหลัก
                    </a>
                    <!-- <a href="<?= base_url('new-admission/report-login') ?>" class="btn btn-primary rounded-pill px-4">
                        <i class='bx bx-log-in-circle me-1'></i> เข้าสู่ระบบรายงานตัว
                    </a> -->
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>


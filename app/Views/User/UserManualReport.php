<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* Timeline Styles (Compact & Mobile Friendly) */
    .timeline {
        position: relative;
        padding: 1rem 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 40px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 10px;
    }
    .timeline-item {
        position: relative;
        padding-left: 80px;
        margin-bottom: 2.5rem;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 4px 15px rgba(255, 158, 181, 0.3);
        z-index: 2;
        transition: all 0.3s ease;
        border: 4px solid #fff;
    }
    .timeline-item:nth-child(even) .timeline-icon {
        background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%);
        box-shadow: 0 4px 15px rgba(132, 210, 246, 0.3);
    }
    .timeline-item:nth-child(3) .timeline-icon {
        background: linear-gradient(135deg, #a8e6cf 0%, #c4f5da 100%);
        box-shadow: 0 4px 15px rgba(168, 230, 207, 0.3);
    }
    .timeline-item:nth-child(4) .timeline-icon {
        background: linear-gradient(135deg, #ffd89b 0%, #ffe9b8 100%);
        box-shadow: 0 4px 15px rgba(255, 216, 155, 0.3);
    }
    
    .timeline-number {
        position: absolute;
        top: 0;
        right: 0;
        width: 24px;
        height: 24px;
        background: #fff;
        color: #ff9eb5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border: 2px solid #ff9eb5;
    }
    .timeline-item:nth-child(even) .timeline-number {
        color: #84d2f6;
        border-color: #84d2f6;
    }
    .timeline-item:nth-child(3) .timeline-number {
        color: #a8e6cf;
        border-color: #a8e6cf;
    }
    .timeline-item:nth-child(4) .timeline-number {
        color: #ffd89b;
        border-color: #ffd89b;
    }

    .timeline-content {
        background: #fff;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-left: 4px solid #ff9eb5;
        transition: all 0.3s ease;
    }
    .timeline-item:nth-child(even) .timeline-content {
        border-left-color: #84d2f6;
    }
    .timeline-item:nth-child(3) .timeline-content {
        border-left-color: #a8e6cf;
    }
    .timeline-item:nth-child(4) .timeline-content {
        border-left-color: #ffd89b;
    }

    .timeline-title {
        color: #ff9eb5;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    .timeline-item:nth-child(even) .timeline-title {
        color: #84d2f6;
    }
    .timeline-item:nth-child(3) .timeline-title {
        color: #a8e6cf;
    }
    .timeline-item:nth-child(4) .timeline-title {
        color: #ffd89b;
    }
    
    /* Mobile Optimization */
    @media (max-width: 767.98px) {
        .timeline::before {
            left: 20px;
        }
        .timeline-item {
            padding-left: 55px;
            margin-bottom: 2rem;
        }
        .timeline-icon {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
            border-width: 3px;
        }
        .timeline-number {
            width: 18px;
            height: 18px;
            font-size: 0.6rem;
            right: -5px;
            top: -5px;
            border-width: 1px;
        }
        .timeline-content {
            padding: 1rem;
            border-radius: 10px;
        }
        .timeline-title {
            font-size: 1rem;
        }
        .card-body {
            padding: 1rem !important;
        }
        h2 {
            font-size: 1.5rem;
        }
        p.fs-5 {
            font-size: 1rem !important;
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
                <div class="card-body p-4">
                    
                    <div class="timeline">
                        
                        <!-- Step 1: Check Results -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-search-alt'></i>
                                <div class="timeline-number">1</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">ตรวจสอบรายชื่อผู้ผ่านการคัดเลือก</h5>
                                <p class="text-muted mb-3">เข้าสู่เว็บไซต์โรงเรียนเพื่อตรวจสอบประกาศผลสอบคัดเลือก</p>
                                <ul class="mb-3 ps-3">
                                    <li>เข้าหน้าเว็บไซต์ <a href="<?= base_url() ?>">ระบบรับสมัครนักเรียนใหม่</a></li>
                                    <li>คลิกเมนู <a href="<?= base_url('new-admission/status') ?>"><strong>"ตรวจสอบสถานะ"</strong></a></li>
                                    <li>กรอกเลขบัตรประชาชนและวันเกิด เพื่อดูรายละเอียด</li>
                                    <li>ตรวจสอบว่าสถานะเป็น <span class="badge bg-success">"ผ่านการคัดเลือก"</span> ถ้าไม่ใช่ ให้กลับไป <span class="badge bg-warning">"แก้ไขข้อมูล"</span></li>
                                </ul>
                                <div class="alert alert-info py-2 px-3 mb-0" role="alert">
                                    <i class='bx bx-info-circle me-1'></i> หากพบชื่อในประกาศ ให้ดำเนินการตามขั้นตอนถัดไป
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Confirm Rights Online -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-check-double'></i>
                                <div class="timeline-number">2</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">รายงานตัวออนไลน์</h5>
                                <p class="text-muted mb-3">ยืนยันการรับสิทธิ์เข้าศึกษาผ่านระบบออนไลน์</p>
                                <ul class="mb-3 ps-3">
                                    <li>เข้าระบบรายงานตัว (ใช้เลขบัตรประชาชนและวันเดือนปีเกิด)</li>
                                    <li>อ่านรายละเอียดเงื่อนไขการรายงานตัว</li>
                                    <li>กดปุ่ม <a href="<?= base_url('confirmation/login') ?>"><span class="badge bg-primary">"รายงานตัวนักเรียนใหม่"</span></a></li>
                                    <li>รอข้อความยืนยันจากระบบ</li>
                                </ul>
                                <div class="alert alert-warning py-2 px-3 mb-0" role="alert">
                                    <i class='bx bx-time-five me-1'></i> <strong>สำคัญ!</strong> ต้องยืนยันสิทธิ์ภายในระยะเวลาที่กำหนด มิฉะนั้นจะถือว่าสละสิทธิ์
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Print Documents -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-printer'></i>
                                <div class="timeline-number">3</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">พิมพ์เอกสารมอบตัว</h5>
                                <p class="text-muted mb-3">ดาวน์โหลดและพิมพ์เอกสารที่จำเป็นสำหรับวันมอบตัว</p>
                                <ul class="mb-3 ps-3">
                                    <li>ล็อกอินเข้าระบบรายงานตัวอีกครั้ง</li>
                                    <li>คลิกปุ่ม <span class="badge bg-danger">"พิมพ์ใบมอบตัว"</span></li>
                                    <li>ตรวจสอบข้อมูลให้ถูกต้อง</li>
                                    <li>พิมพ์ออกมา</li>
                                </ul>
                                <h6 class="fw-bold mt-3 mb-2"> <i class='bx bx-folder-open me-1'></i>เอกสารประกอบที่ต้องเตรียม:</h6>
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
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class='bx bx-home-heart'></i>
                                <div class="timeline-number">4</div>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">เดินทางมามอบตัวที่โรงเรียน</h5>
                                <p class="text-muted mb-3">นำเอกสารทั้งหมดมายื่นในวันและเวลาที่กำหนด</p>
                                <ul class="mb-3 ps-3">
                                    <li>นักเรียนและผู้ปกครองเดินทางมาโรงเรียนตามวันเวลาที่ระบุในประกาศ</li>
                                    <li>แต่งกาย <strong>ชุดนักเรียนโรงเรียนเดิม</strong> หรือชุดสุภาพ</li>
                                    <li>นำเอกสารทั้งหมดมาส่งที่จุดลงทะเบียน</li>
                                    <li>รับเอกสารและคำแนะนำจากเจ้าหน้าที่</li>
                                </ul>
                                <div class="alert alert-danger py-2 px-3 mb-0" role="alert">
                                    <h6 class="alert-heading fw-bold mb-1 fs-6"><i class='bx bx-error-circle me-1'></i> หมายเหตุสำคัญ</h6>
                                    <p class="mb-0 small">หากไม่มารายงานตัวและมอบตัวตามวันเวลาที่กำหนด จะถือว่า <strong>"สละสิทธิ์"</strong> การเข้าศึกษาต่อโดยอัตโนมัติ</p>
                                </div>
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

<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* ===== Premium Hero Banner (Suankularb Style) ===== */
    .manual-banner {
        background: linear-gradient(135deg, #e11d48 0%, #ff2d75 35%, #0284c7 80%, #0369a1 100%);
        border-radius: 24px;
        padding: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(225, 29, 72, 0.25);
    }

    .manual-banner::before {
        content: '';
        position: absolute;
        top: -100%;
        right: -50%;
        width: 300%;
        height: 300%;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .manual-banner .breadcrumb-nav {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .manual-banner .breadcrumb-nav a {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        transition: color 0.2s;
    }

    .manual-banner .breadcrumb-nav a:hover {
        color: #fff;
    }

    .manual-banner .breadcrumb-nav span {
        opacity: 0.7;
        margin: 0 0.5rem;
    }

    .manual-banner .hero-content {
        position: relative;
        z-index: 2;
    }

    .manual-banner .hero-icon {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.25rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .manual-banner h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .manual-banner p {
        opacity: 0.9;
        margin-bottom: 0;
        font-size: 1rem;
    }

    /* ===== Step Cards (Booking Style) ===== */
    .step-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }

    .step-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(180deg, #ff9eb5 0%, #f77062 100%);
        border-radius: 0 5px 5px 0;
    }

    .step-header {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .step-number-bubble {
        width: 60px;
        height: 60px;
        min-width: 60px;
        background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.5rem;
        box-shadow: 0 6px 20px rgba(255, 158, 181, 0.35);
    }

    .step-header-text h4 {
        font-weight: 700;
        font-size: 1.25rem;
        color: #334155;
        margin-bottom: 0.35rem;
    }

    .step-header-text p {
        color: #64748b;
        margin: 0;
        font-size: 0.95rem;
    }

    .step-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .step-instructions {
        padding-left: 0.5rem;
    }

    .step-instructions ul {
        padding-left: 1.2rem;
        margin: 0;
    }

    .step-instructions li {
        color: #475569;
        margin-bottom: 0.6rem;
        line-height: 1.6;
    }

    .step-instructions li:last-child {
        margin-bottom: 0;
    }

    /* ===== Mockup Wrapper (Browser Frame Style) ===== */
    .mockup-wrapper {
        background: #1e293b;
        border-radius: 12px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .mockup-header {
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mockup-dots {
        display: flex;
        gap: 6px;
    }

    .mockup-dots span {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .mockup-dots span:nth-child(1) { background: #ef4444; }
    .mockup-dots span:nth-child(2) { background: #fbbf24; }
    .mockup-dots span:nth-child(3) { background: #22c55e; }

    .mockup-url {
        flex: 1;
        background: #475569;
        border-radius: 6px;
        padding: 0.4rem 0.75rem;
        margin-left: 0.75rem;
        font-size: 0.75rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mockup-url i {
        color: #22c55e;
    }

    .mockup-body {
        background: #f8fafc;
        padding: 1rem;
        min-height: 200px;
    }

    /* Mini UI Elements Inside Mockup */
    .mini-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 0.75rem;
    }

    .mini-form-group {
        margin-bottom: 0.5rem;
    }

    .mini-label {
        font-size: 0.65rem;
        color: #64748b;
        margin-bottom: 0.25rem;
        display: block;
    }

    .mini-input {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.4rem 0.6rem;
        font-size: 0.7rem;
        color: #334155;
        width: 100%;
    }

    .mini-btn {
        background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-align: center;
    }

    .mini-photo-box {
        width: 45px;
        height: 55px;
        background: #e2e8f0;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ===== Preparation Section ===== */
    .prep-section {
        margin-bottom: 2rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .section-title .icon-box {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
    }

    .section-title h3 {
        font-weight: 700;
        font-size: 1.4rem;
        margin: 0;
        color: #334155;
    }

    .tips-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        border-left: 4px solid #f59e0b;
        margin-bottom: 1.5rem;
    }

    .tips-box h6 {
        color: #92400e;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .tips-box ul {
        margin: 0;
        padding-left: 1.1rem;
    }

    .tips-box li {
        color: #78350f;
        font-size: 0.875rem;
        margin-bottom: 0.3rem;
    }

    /* Mobile Responsive */
    @media (max-width: 991.98px) {
        .step-body {
            grid-template-columns: 1fr;
        }
        .manual-banner {
            padding: 1.5rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Premium Hero Banner -->
    <div class="manual-banner">
        <div class="hero-content">
            <div class="breadcrumb-nav">
                <a href="<?= base_url('new-admission') ?>">หน้าหลัก</a>
                <span>/</span>
                <a href="<?= base_url('new-admission/manual') ?>">คู่มือการใช้งาน</a>
                <span>/</span>
                คู่มือการรายงานตัว
            </div>
            <div class="hero-icon">
                <i class='bx bx-user-check'></i>
            </div>
            <h2>คู่มือการรายงานตัวและมอบตัว</h2>
            <p>ขั้นตอนและวิธีการรายงานตัวออนไลน์ สำหรับนักเรียนที่ผ่านการคัดเลือก</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-12">
            
            <div class="section-title">
                <div class="icon-box">
                    <i class='bx bx-list-ol'></i>
                </div>
                <h3>ขั้นตอนการรายงานตัว</h3>
            </div>

            <!-- Step 1 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-number-bubble">1</div>
                    <div class="step-header-text">
                        <h4>ตรวจสอบรายชื่อและสถานะ</h4>
                        <p>เช็คผลการคัดเลือกผ่านระบบออนไลน์</p>
                    </div>
                </div>
                <div class="step-body">
                    <div class="step-instructions">
                        <ul>
                            <li>เข้าหน้า <a href="<?= base_url('new-admission/status') ?>" class="fw-bold text-primary">ตรวจสอบสถานะ</a></li>
                            <li>กรอกเลขบัตรประชาชน และวันเดือนปีเกิด</li>
                            <li>หากผ่านการคัดเลือก สถานะจะแสดงเป็น <span class="badge bg-success">ผ่านการคัดเลือก</span></li>
                            <li>หากสถานะเป็น <span class="badge bg-danger">ไม่ผ่านการคัดเลือก</span> จะไม่สามารถรายงานตัวได้</li>
                        </ul>
                    </div>
                    <div class="mockup-wrapper">
                        <div class="mockup-header">
                            <div class="mockup-dots"><span></span><span></span><span></span></div>
                            <div class="mockup-url"><i class='bx bx-lock-alt'></i> skj.ac.th/new-admission/status</div>
                        </div>
                        <div class="mockup-body">
                            <div class="mini-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="mini-photo-box"><i class='bx bx-user' style="font-size: 1rem; color: #94a3b8;"></i></div>
                                    <div style="font-size: 0.65rem;">
                                        <strong>เด็กชาย ทดสอบ มุ่งมั่น</strong><br>
                                        <span style="color: #166534; font-weight: 600;">ผ่านการคัดเลือก</span>
                                    </div>
                                </div>
                                <div class="mini-btn w-100">รายงานตัวออนไลน์</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-number-bubble">2</div>
                    <div class="step-header-text">
                        <h4>รายงานตัวออนไลน์</h4>
                        <p>ยืนยันสิทธิ์ทางการศึกษาผ่านระบบ</p>
                    </div>
                </div>
                <div class="step-body">
                    <div class="step-instructions">
                        <ul>
                            <li>อ่านรายละเอียดและเงื่อนไขการรายงานตัวให้ครบถ้วน</li>
                            <li>คลิกปุ่ม <strong>"รายงานตัวออนไลน์"</strong> ในหน้ารายละเอียดข้อมูล</li>
                            <li>ตรวจสอบข้อมูลส่วนตัวและข้อมูลผู้ปกครอง</li>
                            <li>กดปุ่มบันทึกเพื่อยืนยันการรายงานตัว</li>
                        </ul>
                        <div class="alert alert-warning py-2 px-3 mt-3" style="border-radius: 12px; font-size: 0.85rem;">
                            <i class='bx bx-time-five me-1'></i> ต้องดำเนินการภายในระยะเวลาที่กำหนดเท่านั้น
                        </div>
                    </div>
                    <div class="mockup-wrapper">
                        <div class="mockup-header">
                            <div class="mockup-dots"><span></span><span></span><span></span></div>
                            <div class="mockup-url"><i class='bx bx-lock-alt'></i> skj.ac.th/new-admission/report</div>
                        </div>
                        <div class="mockup-body">
                            <div class="mini-card">
                                <div style="font-size: 0.7rem; font-weight: 600; margin-bottom: 0.5rem;">ยืนยันสิทธิ์การเข้าศึกษา</div>
                                <div class="mini-form-group">
                                    <div class="mini-label">ขอยืนยันสิทธิ์เข้าศึกษาต่อ</div>
                                    <div class="d-flex gap-2">
                                        <div style="font-size: 0.65rem;"><i class='bx bx-check-circle text-success'></i> ยืนยันสิทธิ์</div>
                                    </div>
                                </div>
                                <div class="mini-btn w-100">บันทึกข้อมูลรายงานตัว</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-number-bubble">3</div>
                    <div class="step-header-text">
                        <h4>พิมพ์ใบมอบตัว</h4>
                        <p>ดาวน์โหลดเอกสารสำหรับนำมายื่นที่โรงเรียน</p>
                    </div>
                </div>
                <div class="step-body">
                    <div class="step-instructions">
                        <ul>
                            <li>เมื่อรายงานตัวสำเร็จ ระบบจะแสดงปุ่มสำหรับพิมพ์เอกสาร</li>
                            <li>คลิกปุ่ม <span class="badge bg-danger">พิมพ์ใบมอบตัว</span> (PDF)</li>
                            <li>พิมพ์เอกสารใส่กระดาษ A4 เพื่อนำมาส่งในวันมอบตัว</li>
                        </ul>
                        <h6 class="fw-bold mt-3 mb-2" style="font-size: 0.9rem;">เอกสารที่ต้องเตรียมเพิ่ม:</h6>
                        <ul class="small">
                            <li>ปพ.1 ฉบับจริง + สำเนา</li>
                            <li>สำเนาทะเบียนบ้าน (นักเรียน/บิดา/มารดา)</li>
                            <li>สำเนาบัตรประชาชน (นักเรียน/บิดา/มารดา)</li>
                        </ul>
                    </div>
                    <div class="mockup-wrapper">
                        <div class="mockup-header">
                            <div class="mockup-dots"><span></span><span></span><span></span></div>
                            <div class="mockup-url"><i class='bx bx-lock-alt'></i> skj.ac.th/new-admission/report-success</div>
                        </div>
                        <div class="mockup-body">
                            <div class="mini-card text-center">
                                <i class='bx bx-check-circle text-success' style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                                <div style="font-size: 0.7rem; font-weight: 600; margin-bottom: 0.75rem;">รายงานตัวสำเร็จ!</div>
                                <div class="mini-btn" style="background: #ef4444; width: 100%;">พิมพ์ใบมอบตัว (PDF)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-number-bubble">4</div>
                    <div class="step-header-text">
                        <h4>มอบตัว ณ โรงเรียน</h4>
                        <p>ส่งเอกสารและทำสัญญา ณ หอประชุมโรงเรียน</p>
                    </div>
                </div>
                <div class="step-body">
                    <div class="step-instructions">
                        <ul>
                            <li>เดินทางมาโรงเรียนตามวันเวลาที่กำหนดในประกาศ</li>
                            <li>แต่งกายด้วย <strong>ชุดนักเรียนเดิม</strong></li>
                            <li>นำเอกสารทั้งหมด (ข้อ 3) มายื่น ณ จุดลงทะเบียน</li>
                            <li>หากไม่มาตามกำหนด จะถือว่า <strong>สละสิทธิ์</strong></li>
                        </ul>
                    </div>
                    <div class="mockup-wrapper">
                        <div class="mockup-header">
                            <div class="mockup-dots"><span></span><span></span><span></span></div>
                            <div class="mockup-url"><i class='bx bx-map'></i> โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ)</div>
                        </div>
                        <div class="mockup-body">
                            <div class="mini-card">
                                <div class="text-center py-2">
                                    <i class='bx bx-buildings' style="font-size: 2rem; color: #696cff;"></i>
                                    <div style="font-size: 0.65rem; margin-top: 0.5rem; color: #64748b;">วันมอบตัว: ตรวจสอบในประกาศ</div>
                                    <div style="font-size: 0.7rem; font-weight: 700; color: #334155;">หอประชุมอาคารเจ้าพระยา</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tips-box">
                <h6><i class='bx bx-bulb'></i> คำแนะนำเพิ่มเติม</h6>
                <ul>
                    <li>ควรตรวจสอบวันเวลาการรายงานตัวจากประกาศของโรงเรียนอย่างละเอียด</li>
                    <li>หากมีข้อสงสัยหรือติดปัญหาการใช้งานระบบ ติดต่อได้ที่ <strong>056-009-667</strong></li>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="<?= base_url('new-admission') ?>" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                    <i class='bx bx-home-alt me-2'></i> กลับสู่หน้าหลัก
                </a>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

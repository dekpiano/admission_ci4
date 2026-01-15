<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* ===== Premium Hero Banner (Booking Style) ===== */
    .manual-banner {
        background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
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

    /* ===== Role Tabs ===== */
    .role-tabs {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .role-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        background: white;
        color: #64748b;
    }

    .role-tab:hover {
        border-color: #696cff;
        color: #696cff;
    }

    .role-tab.active {
        background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(105, 108, 255, 0.35);
    }

    .role-tab i {
        font-size: 1.1rem;
    }

    /* ===== Tab Content ===== */
    .tab-content-panel {
        display: none;
    }

    .tab-content-panel.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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

    .prep-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .prep-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .prep-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border-color: #e2e8f0;
    }

    .prep-card .card-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: white;
        margin-bottom: 1rem;
    }

    .prep-card .card-icon.documents { background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%); }
    .prep-card .card-icon.devices { background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%); }
    .prep-card .card-icon.info { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
    .prep-card .card-icon.time { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }

    .prep-card h6 {
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.75rem;
    }

    .prep-card ul {
        padding-left: 1.1rem;
        margin: 0;
    }

    .prep-card li {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0.35rem;
    }

    .prep-card li:last-child {
        margin-bottom: 0;
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

    .step-visual {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        border: 2px dashed #e2e8f0;
    }

    .step-visual i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 0.75rem;
    }

    .step-visual span {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .step-visual img {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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

    .mini-header {
        background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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

    .mini-select {
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
    }

    .mini-btn.secondary {
        background: #e2e8f0;
        color: #64748b;
    }

    .mini-photo-box {
        width: 60px;
        height: 75px;
        background: #e2e8f0;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
    }

    .mini-photo-box i {
        font-size: 1.5rem;
        color: #94a3b8;
    }

    .mini-doc-box {
        background: #f1f5f9;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 0.75rem;
        text-align: center;
    }

    .mini-doc-box i {
        font-size: 1.25rem;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }

    .mini-doc-box span {
        font-size: 0.65rem;
        color: #64748b;
        display: block;
    }

    .mini-wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding: 0 0.5rem;
    }

    .mini-wizard-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .mini-wizard-step .step-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        font-size: 0.65rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mini-wizard-step.active .step-circle {
        background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%);
        color: white;
    }

    .mini-wizard-step.completed .step-circle {
        background: #22c55e;
        color: white;
    }

    .mini-wizard-step span {
        font-size: 0.55rem;
        color: #94a3b8;
    }

    .mini-wizard-step.active span {
        color: #696cff;
        font-weight: 600;
    }

    /* ===== Tips Box ===== */
    .tips-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        border-left: 4px solid #f59e0b;
        margin-top: 1rem;
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

    .tips-box li:last-child {
        margin-bottom: 0;
    }

    /* ===== Document Badges ===== */
    .doc-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .doc-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        color: #6366f1;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid #c7d2fe;
    }

    .doc-badge i {
        font-size: 1rem;
    }

    /* ===== Status Badges ===== */
    .status-list {
        margin-top: 0.75rem;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-badge.pending { background: #fef3c7; color: #92400e; }
    .status-badge.success { background: #dcfce7; color: #166534; }
    .status-badge.danger { background: #fee2e2; color: #991b1b; }

    /* ===== FAQ Section ===== */
    .faq-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        margin-bottom: 2rem;
    }

    .faq-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 0;
    }

    .faq-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .faq-item:first-child {
        padding-top: 0;
    }

    .faq-question {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
    }

    .faq-question i {
        color: #696cff;
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-top: 0.1rem;
    }

    .faq-answer {
        padding-left: 2rem;
        margin-top: 0.75rem;
        color: #64748b;
        line-height: 1.7;
    }

    /* ===== Quick Links ===== */
    .quick-links-section {
        text-align: center;
        margin-top: 2rem;
    }

    .quick-links-section h5 {
        font-weight: 700;
        color: #334155;
        margin-bottom: 1rem;
    }

    .quick-links {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: center;
    }

    .quick-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .quick-link.primary {
        background: linear-gradient(135deg, #696cff 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(105, 108, 255, 0.25);
    }

    .quick-link.secondary {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.25);
    }

    .quick-link.outline {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }

    .quick-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .quick-link.primary:hover,
    .quick-link.secondary:hover {
        color: white;
    }

    .quick-link.outline:hover {
        border-color: #696cff;
        color: #696cff;
    }

    /* ===== Responsive ===== */
    @media (max-width: 991.98px) {
        .step-body {
            grid-template-columns: 1fr;
        }

        .step-visual {
            order: -1;
        }
    }

    @media (max-width: 767.98px) {
        .manual-banner {
            padding: 1.75rem;
            border-radius: 16px;
        }

        .manual-banner h2 {
            font-size: 1.35rem;
        }

        .manual-banner .hero-icon {
            width: 55px;
            height: 55px;
            font-size: 1.5rem;
        }

        .step-card {
            padding: 1.5rem 1.25rem;
            border-radius: 16px;
        }

        .step-number-bubble {
            width: 50px;
            height: 50px;
            min-width: 50px;
            font-size: 1.25rem;
        }

        .step-header {
            gap: 1rem;
        }

        .step-header-text h4 {
            font-size: 1.1rem;
        }

        .role-tab {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
        }

        .prep-grid {
            grid-template-columns: 1fr;
        }

        .quick-links {
            flex-direction: column;
            align-items: center;
        }

        .quick-link {
            width: 100%;
            max-width: 280px;
            justify-content: center;
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
                <span>คู่มือการสมัครเรียน</span>
            </div>
            <div class="hero-icon">
                <i class='bx bx-book-reader'></i>
            </div>
            <h2>📖 คู่มือการสมัครเรียนออนไลน์</h2>
            <p>ขั้นตอนง่ายๆ ในการสมัครเข้าศึกษาต่อ <strong>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</strong></p>
        </div>
    </div>

    <!-- Role Toggle Tabs -->
    <div class="role-tabs">
        <div class="role-tab active" data-tab="applicant">
            <i class='bx bx-user'></i>
            <span>สำหรับผู้สมัคร/ผู้ปกครอง</span>
        </div>
        <div class="role-tab" data-tab="after">
            <i class='bx bx-check-circle'></i>
            <span>หลังสมัครเสร็จแล้ว</span>
        </div>
    </div>

    <!-- Tab Content: For Applicants -->
    <div id="applicant" class="tab-content-panel active">

        <!-- Preparation Section -->
        <div class="prep-section">
            <div class="section-title">
                <div class="icon-box">
                    <i class='bx bx-list-check'></i>
                </div>
                <h3>สิ่งที่ต้องเตรียมก่อนสมัคร</h3>
            </div>

            <div class="prep-grid">
                <div class="prep-card">
                    <div class="card-icon documents">
                        <i class='bx bx-file'></i>
                    </div>
                    <h6>เอกสารที่ต้องใช้</h6>
                    <ul>
                        <li>รูปถ่ายชุดนักเรียน (1.5 นิ้ว)</li>
                        <li>ปพ.1 ด้านหน้าและหลัง</li>
                        <li>สำเนาบัตรประชาชน</li>
                    </ul>
                </div>
                <div class="prep-card">
                    <div class="card-icon devices">
                        <i class='bx bx-devices'></i>
                    </div>
                    <h6>อุปกรณ์ที่ใช้</h6>
                    <ul>
                        <li>คอมพิวเตอร์ / โน้ตบุ๊ค / มือถือ</li>
                        <li>กล้องถ่ายรูป (สำหรับถ่ายเอกสาร)</li>
                        <li>อินเตอร์เน็ต</li>
                    </ul>
                </div>
                <div class="prep-card">
                    <div class="card-icon info">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <h6>ข้อมูลที่ต้องกรอก</h6>
                    <ul>
                        <li>เลขบัตรประชาชน 13 หลัก</li>
                        <li>ข้อมูลส่วนตัว, ที่อยู่</li>
                        <li>โรงเรียนเดิม, GPAX</li>
                    </ul>
                </div>
                <div class="prep-card">
                    <div class="card-icon time">
                        <i class='bx bx-time-five'></i>
                    </div>
                    <h6>เวลาที่ใช้</h6>
                    <ul>
                        <li>ประมาณ <strong>10-15 นาที</strong></li>
                        <li>แนะนำกรอกให้เสร็จในครั้งเดียว</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Steps Section -->
        <div class="section-title">
            <div class="icon-box">
                <i class='bx bx-list-ol'></i>
            </div>
            <h3>ขั้นตอนการสมัครเรียน</h3>
        </div>

        <!-- Step 1 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-bubble">1</div>
                <div class="step-header-text">
                    <h4>เข้าสู่หน้าหลักระบบรับสมัคร</h4>
                    <p>เริ่มต้นที่หน้าแรกของระบบรับสมัครนักเรียนออนไลน์</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <ul>
                        <li>เข้าเว็บไซต์ <a href="<?= base_url('new-admission') ?>" class="fw-bold text-primary">ระบบรับสมัครนักเรียน</a></li>
                        <li>ดูประกาศและกำหนดการรับสมัคร</li>
                        <li>อ่านระเบียบการรับสมัครตามประเภทความเป็นเลิศ</li>
                        <li>คลิกปุ่ม <span class="badge bg-primary">สมัครเรียน ม.1</span> หรือ <span class="badge bg-info">สมัครเรียน ม.4</span></li>
                    </ul>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-header">
                            <i class='bx bx-school'></i> ระบบรับสมัครนักเรียนออนไลน์
                        </div>
                        <div class="d-flex gap-2 mb-2">
                            <div class="mini-btn">สมัครเรียน ม.1</div>
                            <div class="mini-btn" style="background: #0ea5e9;">สมัครเรียน ม.4</div>
                        </div>
                        <div style="font-size: 0.65rem; color: #64748b;">📢 ประกาศรับสมัคร ปีการศึกษา 2569</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-bubble">2</div>
                <div class="step-header-text">
                    <h4>ตรวจสอบเลขบัตรประชาชน</h4>
                    <p>ระบบจะตรวจสอบว่าเลขบัตรประชาชนนี้เคยสมัครแล้วหรือไม่</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <ul>
                        <li>กรอกเลขประจำตัวประชาชน <strong>13 หลัก</strong> ของผู้สมัคร</li>
                        <li>กดปุ่ม <span class="badge bg-info">ตรวจสอบ</span></li>
                        <li>หาก <strong class="text-success">ยังไม่เคยสมัคร</strong> → ระบบจะนำไปหน้ากรอกใบสมัคร</li>
                        <li>หาก <strong class="text-danger">เคยสมัครแล้ว</strong> → ระบบจะแจ้งเตือน</li>
                    </ul>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/precheck
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-card">
                            <div class="mini-form-group">
                                <div class="mini-label">เลขบัตรประชาชน 13 หลัก</div>
                                <div class="mini-input">1-2345-67890-12-3</div>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <div class="mini-btn">ตรวจสอบ</div>
                            </div>
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
                    <h4>เลือกประเภทโควตาและแผนการเรียน</h4>
                    <p>เลือกประเภทการสมัครและแผนการเรียนที่ต้องการ</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <ul>
                        <li>เลือก <strong>ประเภทโควตา</strong> ที่ต้องการสมัคร (ทั่วไป, นักกีฬา, เขตพื้นที่บริการ ฯลฯ)</li>
                        <li>เลือก <strong>แผนการเรียน</strong> ได้สูงสุด 3 อันดับ (นักกีฬาเลือกได้ 1 อันดับ)</li>
                        <li>หากสมัครนักกีฬา จะมีช่องกรอกข้อมูลเพิ่มเติม (รุ่นอายุ, ตำแหน่ง, น้ำหนัก, ส่วนสูง)</li>
                    </ul>
                    <div class="tips-box">
                        <h6><i class='bx bx-bulb'></i> เคล็ดลับ</h6>
                        <ul>
                            <li>เลือกแผนการเรียนที่ถนัดเป็นอันดับ 1</li>
                            <li>เลือกแผนสำรองเป็นอันดับ 2-3 เผื่อไม่ผ่านอันดับแรก</li>
                        </ul>
                    </div>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/register
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-wizard-steps">
                            <div class="mini-wizard-step active"><div class="step-circle">1</div><span>แผน</span></div>
                            <div class="mini-wizard-step"><div class="step-circle">2</div><span>ส่วนตัว</span></div>
                            <div class="mini-wizard-step"><div class="step-circle">3</div><span>ที่อยู่</span></div>
                            <div class="mini-wizard-step"><div class="step-circle">4</div><span>ศึกษา</span></div>
                            <div class="mini-wizard-step"><div class="step-circle">5</div><span>เอกสาร</span></div>
                        </div>
                        <div class="mini-card">
                            <div class="mini-form-group">
                                <div class="mini-label">ประเภทโควตา</div>
                                <div class="mini-select">-- เลือกประเภทโควตา --</div>
                            </div>
                            <div class="mini-form-group">
                                <div class="mini-label">แผนการเรียนอันดับ 1</div>
                                <div class="mini-select">-- เลือกแผนการเรียน --</div>
                            </div>
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
                    <h4>กรอกข้อมูลส่วนตัวนักเรียน</h4>
                    <p>กรอกข้อมูลส่วนตัว ที่อยู่ และการศึกษาเดิม</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <strong class="small d-block mb-2 text-dark">ข้อมูลส่วนตัว:</strong>
                            <ul class="small">
                                <li>อัปโหลดรูปถ่าย</li>
                                <li>คำนำหน้า, ชื่อ-นามสกุล</li>
                                <li>วันเดือนปีเกิด</li>
                                <li>เชื้อชาติ, สัญชาติ, ศาสนา</li>
                                <li>เบอร์โทรศัพท์</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <strong class="small d-block mb-2 text-dark">ที่อยู่:</strong>
                            <ul class="small">
                                <li>บ้านเลขที่, หมู่ที่</li>
                                <li>ตำบล, อำเภอ</li>
                                <li>จังหวัด, รหัสไปรษณีย์</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <strong class="small d-block mb-2 text-dark">การศึกษาเดิม:</strong>
                            <ul class="small">
                                <li>ชื่อโรงเรียนเดิม</li>
                                <li>อำเภอ/จังหวัดของโรงเรียน</li>
                                <li>เกรดเฉลี่ยสะสม (GPAX)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/register
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-wizard-steps">
                            <div class="mini-wizard-step completed"><div class="step-circle"><i class='bx bx-check' style="font-size: 0.7rem;"></i></div><span>แผน</span></div>
                            <div class="mini-wizard-step active"><div class="step-circle">2</div><span>ส่วนตัว</span></div>
                            <div class="mini-wizard-step"><div class="step-circle">3</div><span>ที่อยู่</span></div>
                            <div class="mini-wizard-step"><div class="step-circle">4</div><span>ศึกษา</span></div>
                            <div class="mini-wizard-step"><div class="step-circle">5</div><span>เอกสาร</span></div>
                        </div>
                        <div class="mini-card">
                            <div class="text-center mb-2">
                                <div class="mini-photo-box"><i class='bx bx-user'></i></div>
                                <div style="font-size: 0.6rem; color: #64748b;">รูปถ่ายนักเรียน</div>
                            </div>
                            <div class="row g-1">
                                <div class="col-4"><div class="mini-input">เด็กชาย</div></div>
                                <div class="col-4"><div class="mini-input">ชื่อ</div></div>
                                <div class="col-4"><div class="mini-input">นามสกุล</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-bubble">5</div>
                <div class="step-header-text">
                    <h4>อัปโหลดเอกสารหลักฐาน</h4>
                    <p>อัปโหลดไฟล์เอกสารประกอบการสมัคร</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <div class="doc-badges">
                        <div class="doc-badge"><i class='bx bx-image'></i> รูปถ่ายนักเรียน</div>
                        <div class="doc-badge"><i class='bx bx-file'></i> ปพ.1 ด้านหน้า</div>
                        <div class="doc-badge"><i class='bx bx-file'></i> ปพ.1 ด้านหลัง</div>
                        <div class="doc-badge"><i class='bx bx-id-card'></i> สำเนาบัตรประชาชน</div>
                    </div>
                    <div class="tips-box">
                        <h6><i class='bx bx-error-circle'></i> ข้อกำหนดไฟล์เอกสาร</h6>
                        <ul>
                            <li>รองรับไฟล์ <strong>.jpg, .jpeg, .png, .pdf</strong> เท่านั้น (ไม่รองรับ HEIC จาก iPhone)</li>
                            <li>ขนาดไฟล์ไม่เกิน <strong>2MB</strong> ต่อไฟล์</li>
                            <li><strong class="text-danger">เซ็นสำเนาถูกต้อง</strong>ทุกใบก่อนถ่ายรูป</li>
                            <li>ถ่ายรูปให้ชัดเจน อ่านตัวหนังสือได้</li>
                        </ul>
                    </div>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/register
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-wizard-steps">
                            <div class="mini-wizard-step completed"><div class="step-circle"><i class='bx bx-check' style="font-size: 0.7rem;"></i></div><span>แผน</span></div>
                            <div class="mini-wizard-step completed"><div class="step-circle"><i class='bx bx-check' style="font-size: 0.7rem;"></i></div><span>ส่วนตัว</span></div>
                            <div class="mini-wizard-step completed"><div class="step-circle"><i class='bx bx-check' style="font-size: 0.7rem;"></i></div><span>ที่อยู่</span></div>
                            <div class="mini-wizard-step completed"><div class="step-circle"><i class='bx bx-check' style="font-size: 0.7rem;"></i></div><span>ศึกษา</span></div>
                            <div class="mini-wizard-step active"><div class="step-circle">5</div><span>เอกสาร</span></div>
                        </div>
                        <div class="mini-card">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="mini-doc-box"><i class='bx bx-file'></i><span>ปพ.1 หน้า</span></div>
                                </div>
                                <div class="col-6">
                                    <div class="mini-doc-box"><i class='bx bx-file'></i><span>ปพ.1 หลัง</span></div>
                                </div>
                                <div class="col-6">
                                    <div class="mini-doc-box"><i class='bx bx-id-card'></i><span>บัตรประชาชน</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 6 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-bubble">6</div>
                <div class="step-header-text">
                    <h4>ตรวจสอบและยืนยันข้อมูล</h4>
                    <p>ตรวจสอบความถูกต้องก่อนกดบันทึก</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <ul>
                        <li>ตรวจสอบข้อมูลทั้งหมดให้ถูกต้องครบถ้วน</li>
                        <li>หากพบข้อผิดพลาด กดปุ่ม <span class="badge bg-secondary">ย้อนกลับ</span> เพื่อแก้ไข</li>
                        <li>ตอบคำถาม <strong>CAPTCHA</strong> เพื่อยืนยันตัวตน</li>
                        <li>กดปุ่ม <span class="badge bg-success">บันทึกข้อมูลการสมัคร</span></li>
                        <li>รอหน้ายืนยันผลการสมัครจากระบบ</li>
                    </ul>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/register
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-card">
                            <div style="font-size: 0.7rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">ตรวจสอบข้อมูลก่อนบันทึก</div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="mini-photo-box" style="width: 40px; height: 50px;"><i class='bx bx-user' style="font-size: 1rem;"></i></div>
                                <div style="font-size: 0.65rem; color: #64748b;">เด็กชาย ทดสอบ ตัวอย่าง<br>แผน: วิทย์-คณิต</div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="mini-btn secondary">ย้อนกลับ</div>
                                <div class="mini-btn">บันทึกข้อมูล</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Tab Content: After Registration -->
    <div id="after" class="tab-content-panel">

        <div class="section-title">
            <div class="icon-box">
                <i class='bx bx-task'></i>
            </div>
            <h3>สิ่งที่ต้องทำหลังสมัครแล้ว</h3>
        </div>

        <!-- Step 1 - After -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-bubble">1</div>
                <div class="step-header-text">
                    <h4>ติดตามสถานะการสมัคร</h4>
                    <p>เข้าระบบเพื่อตรวจสอบสถานะใบสมัครของคุณ</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <ul>
                        <li>เข้าหน้า <a href="<?= base_url('new-admission/status') ?>" class="fw-bold text-primary">ตรวจสอบสถานะ</a></li>
                        <li>กรอกเลขบัตรประชาชนและรหัสผ่านเพื่อเข้าสู่ระบบ</li>
                        <li>รหัสผ่านเริ่มต้นคือ <strong>เลขบัตรประชาชน 13 หลัก</strong></li>
                    </ul>
                    <div class="status-list">
                        <div class="status-item">
                            <span class="status-badge pending">รอตรวจสอบ</span>
                            <span class="small text-muted">รอเจ้าหน้าที่ตรวจสอบเอกสาร</span>
                        </div>
                        <div class="status-item">
                            <span class="status-badge success">ผ่านการตรวจสอบ</span>
                            <span class="small text-muted">เอกสารครบถ้วน รอสอบคัดเลือก</span>
                        </div>
                        <div class="status-item">
                            <span class="status-badge danger">ไม่ผ่านการตรวจสอบ</span>
                            <span class="small text-muted">ต้องแก้ไขเอกสาร</span>
                        </div>
                    </div>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/status
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-card">
                            <div class="mini-form-group">
                                <div class="mini-label">เลขบัตรประชาชน</div>
                                <div class="mini-input">1-2345-67890-12-3</div>
                            </div>
                            <div class="mini-form-group">
                                <div class="mini-label">รหัสผ่าน</div>
                                <div class="mini-input">••••••••</div>
                            </div>
                            <div class="mini-btn mt-2">เข้าสู่ระบบ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 - After -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-bubble">2</div>
                <div class="step-header-text">
                    <h4>แก้ไขข้อมูล (ถ้าจำเป็น)</h4>
                    <p>หากเอกสารไม่ครบหรือไม่ถูกต้อง สามารถแก้ไขได้</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <ul>
                        <li>เข้าสู่ระบบที่หน้า <strong>ตรวจสอบสถานะ</strong></li>
                        <li>ดูหมายเหตุจากเจ้าหน้าที่ว่าต้องแก้ไขส่วนใด</li>
                        <li>คลิกปุ่ม <span class="badge bg-warning text-dark">แก้ไขข้อมูล</span></li>
                        <li>อัปโหลดเอกสารใหม่หรือแก้ไขข้อมูล</li>
                        <li>กดบันทึกเพื่อส่งให้เจ้าหน้าที่ตรวจสอบอีกครั้ง</li>
                    </ul>
                    <div class="tips-box">
                        <h6><i class='bx bx-info-circle'></i> หมายเหตุ</h6>
                        <ul>
                            <li>สามารถแก้ไขได้เฉพาะเมื่อสถานะยังเป็น "รอตรวจสอบ"</li>
                            <li>หลังผ่านการตรวจสอบแล้วจะไม่สามารถแก้ไขได้</li>
                        </ul>
                    </div>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/status
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-card">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.6rem; font-weight: 600;">รอตรวจสอบ</div>
                            </div>
                            <div style="font-size: 0.6rem; color: #ef4444; margin-bottom: 0.5rem;">หมายเหตุ: กรุณาอัปโหลด ปพ.1 ใหม่</div>
                            <div class="mini-btn" style="background: #fbbf24;">แก้ไขข้อมูล</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 - After -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number-bubble">3</div>
                <div class="step-header-text">
                    <h4>พิมพ์ใบสมัคร</h4>
                    <p>ดาวน์โหลดและพิมพ์ใบสมัครเมื่อผ่านการตรวจสอบ</p>
                </div>
            </div>
            <div class="step-body">
                <div class="step-instructions">
                    <ul>
                        <li>เมื่อสถานะเป็น <span class="badge bg-success">ผ่านการตรวจสอบ</span></li>
                        <li>คลิกปุ่ม <span class="badge bg-danger">พิมพ์ใบสมัคร</span></li>
                        <li>ดาวน์โหลดไฟล์ PDF และพิมพ์ออกมา</li>
                        <li><strong>นำใบสมัครมายื่นในวันสอบคัดเลือก</strong></li>
                    </ul>
                </div>
                <div class="mockup-wrapper">
                    <div class="mockup-header">
                        <div class="mockup-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="mockup-url">
                            <i class='bx bx-lock-alt'></i>
                            skj.ac.th/new-admission/status
                        </div>
                    </div>
                    <div class="mockup-body">
                        <div class="mini-card">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.6rem; font-weight: 600;">ผ่านการตรวจสอบ</div>
                            </div>
                            <div style="font-size: 0.6rem; color: #64748b; margin-bottom: 0.5rem;">เอกสารครบถ้วน รอสอบคัดเลือก</div>
                            <div class="mini-btn" style="background: #ef4444;">พิมพ์ใบสมัคร</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <div class="section-title">
            <div class="icon-box">
                <i class='bx bx-help-circle'></i>
            </div>
            <h3>คำถามที่พบบ่อย (FAQ)</h3>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class='bx bx-question-mark'></i>
                <span>สมัครได้กี่แผนการเรียน?</span>
            </div>
            <div class="faq-answer">
                สมัครทั่วไปสามารถเลือกแผนการเรียนได้สูงสุด <strong>3 อันดับ</strong> หากสมัครประเภทนักกีฬาจะเลือกได้เพียง <strong>1 อันดับ</strong> เท่านั้น
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class='bx bx-question-mark'></i>
                <span>ไฟล์ HEIC จาก iPhone อัปโหลดไม่ได้ทำอย่างไร?</span>
            </div>
            <div class="faq-answer">
                ระบบไม่รองรับไฟล์ HEIC กรุณาแปลงเป็น .jpg หรือ .png ก่อนอัปโหลด หรือใช้ App กล้องถ่ายรูปอื่นที่บันทึกเป็น JPG
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class='bx bx-question-mark'></i>
                <span>สถานะ "รอตรวจสอบ" นานเท่าไหร่?</span>
            </div>
            <div class="faq-answer">
                เจ้าหน้าที่จะตรวจสอบเอกสารภายใน <strong>1-3 วันทำการ</strong> กรุณาหมั่นเข้ามาตรวจสอบสถานะบ่อยๆ
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class='bx bx-question-mark'></i>
                <span>แก้ไขข้อมูลหลังสมัครแล้วได้ไหม?</span>
            </div>
            <div class="faq-answer">
                สามารถแก้ไขได้หากสถานะยังเป็น "รอตรวจสอบ" โดยเข้าสู่ระบบที่หน้า <strong>ตรวจสอบสถานะ</strong> แล้วคลิกแก้ไขข้อมูล
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class='bx bx-question-mark'></i>
                <span>ลืมรหัสผ่านทำอย่างไร?</span>
            </div>
            <div class="faq-answer">
                รหัสผ่านเริ่มต้นคือ <strong>เลขบัตรประชาชน 13 หลัก</strong> หากยังเข้าไม่ได้ กรุณาติดต่อเจ้าหน้าที่
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="quick-links-section">
        <h5>เริ่มสมัครเรียนเลย!</h5>
        <div class="quick-links">
            <a href="<?= base_url('new-admission') ?>" class="quick-link primary">
                <i class='bx bx-home-alt'></i> หน้าหลักรับสมัคร
            </a>
            <a href="<?= base_url('new-admission/status') ?>" class="quick-link secondary">
                <i class='bx bx-search-alt'></i> ตรวจสอบสถานะ
            </a>
            <a href="<?= base_url('new-admission/statistics') ?>" class="quick-link outline">
                <i class='bx bx-bar-chart-alt-2'></i> สถิติการสมัคร
            </a>
            <a href="<?= base_url('contact') ?>" class="quick-link outline">
                <i class='bx bx-phone'></i> ติดต่อเจ้าหน้าที่
            </a>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.role-tab');
        const panels = document.querySelectorAll('.tab-content-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetId = this.getAttribute('data-tab');

                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Update active panel
                panels.forEach(p => p.classList.remove('active'));
                document.getElementById(targetId).classList.add('active');
            });
        });
    });
</script>
<?= $this->endSection() ?>
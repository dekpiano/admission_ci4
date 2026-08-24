<!doctype html>
<html lang="th" class="layout-wide">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>เข้าสู่ระบบผู้ดูแลระบบ | ระบบรับสมัครนักเรียน SKJ Admission</title>
    <meta name="description" content="ระบบรับสมัครนักเรียนออนไลน์ โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('public/sneat-assets/img/favicon/favicon.ico') ?>" />

    <!-- Google Sign-In (GSI Client) -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- Google Fonts: K2D & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/fonts/boxicons.css') ?>" />

    <!-- Core & Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/css/core.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/sneat-assets/css/demo.css') ?>" />

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

    <style>
        :root {
            --skj-pink: #ff6b8b;
            --skj-pink-dark: #e04869;
            --skj-blue: #56ccf2;
            --skj-blue-dark: #2f80ed;
            --skj-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
            --skj-gradient-reverse: linear-gradient(135deg, #56ccf2 0%, #ff6b8b 100%);
        }

        *, body, button, input, select, textarea, h1, h2, h3, h4, h5, h6, .fw-bold, .btn {
            font-family: 'K2D', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        body {
            background-color: #0b1120;
            margin: 0;
            padding: 0;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            color: #1e293b;
        }

        /* Ambient Animated Background Glows */
        .ambient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
            background: radial-gradient(circle at 10% 20%, rgba(255, 107, 139, 0.18) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(86, 204, 242, 0.18) 0%, transparent 45%),
                        radial-gradient(circle at 50% 50%, rgba(15, 23, 42, 0.95) 0%, #070d18 100%);
        }

        .ambient-orb-1 {
            position: absolute;
            top: -10%;
            right: 15%;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 107, 139, 0.35) 0%, rgba(255, 107, 139, 0) 70%);
            filter: blur(60px);
            animation: orbFloat 12s ease-in-out infinite alternate;
        }

        .ambient-orb-2 {
            position: absolute;
            bottom: -10%;
            left: 10%;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(86, 204, 242, 0.3) 0%, rgba(86, 204, 242, 0) 70%);
            filter: blur(70px);
            animation: orbFloat 14s ease-in-out infinite alternate-reverse;
        }

        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -40px) scale(1.08); }
            100% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* Subtle Grid Mesh Overlay */
        .ambient-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1.2px, transparent 1.2px);
            background-size: 28px 28px;
            opacity: 0.7;
        }

        /* Navigation Top Pill (Back to Website) */
        .top-nav-bar {
            position: absolute;
            top: 24px;
            left: 24px;
            right: 24px;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-back-home {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 30px;
            backdrop-filter: blur(12px);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .btn-back-home:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            transform: translateX(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .system-status-pill {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #4ade80;
            font-size: 0.76rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(10px);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 8px #22c55e;
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.25); }
        }

        /* Main Login Container Card */
        .auth-container {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 980px;
            margin: auto;
            padding: 20px;
        }

        .auth-card-frame {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 32px;
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45), 0 0 35px rgba(255, 107, 139, 0.12);
            overflow: hidden;
            display: flex;
            backdrop-filter: blur(20px);
        }

        /* Left Side: Brand Visual Showcase (Desktop) */
        .auth-showcase-panel {
            flex: 1.1;
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            padding: 44px 38px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .auth-showcase-panel::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -30%;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 107, 139, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .auth-showcase-panel::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(86, 204, 242, 0.22) 0%, transparent 70%);
            pointer-events: none;
        }

        .school-logo-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            z-index: 2;
        }

        .school-logo-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: var(--skj-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(255, 107, 139, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.8);
            flex-shrink: 0;
        }

        .school-brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1.2;
            color: #ffffff;
            margin: 0;
        }

        .school-brand-subtitle {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2px;
        }

        .showcase-content {
            z-index: 2;
            margin: 36px 0;
        }

        .showcase-badge {
            background: rgba(255, 107, 139, 0.15);
            border: 1px solid rgba(255, 107, 139, 0.35);
            color: #ff8ea7;
            font-size: 0.76rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
        }

        .showcase-heading {
            font-size: 1.7rem;
            font-weight: 800;
            line-height: 1.35;
            color: #ffffff;
            margin-bottom: 14px;
        }

        .showcase-heading span {
            background: var(--skj-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .showcase-desc {
            font-size: 0.86rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .feature-check-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feature-check-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.84rem;
            color: #cbd5e1;
        }

        .feature-check-item i {
            font-size: 1.15rem;
            color: var(--skj-pink);
            background: rgba(255, 107, 139, 0.12);
            padding: 3px;
            border-radius: 50%;
        }

        .showcase-footer {
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 18px;
            font-size: 0.74rem;
            color: #64748b;
        }

        /* Right Side: Login Action Panel */
        .auth-form-panel {
            flex: 1;
            padding: 44px 40px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .form-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .form-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0;
        }

        /* Google Sign-In Card Box */
        .google-auth-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px 20px;
            text-align: center;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
            margin-bottom: 20px;
        }

        .google-auth-box:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        }

        .google-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            margin-bottom: 12px;
        }

        .google-hint-text {
            font-size: 0.8rem;
            color: #475569;
            margin-bottom: 16px;
            font-weight: 500;
        }

        /* Google Sign-In Container Override */
        .gsi-button-wrap {
            display: flex;
            justify-content: center;
            min-height: 44px;
        }

        /* Domain constraint banner */
        .domain-lock-badge {
            background: #f0fdf4;
            border: 1px dashed #86efac;
            color: #166534;
            font-size: 0.76rem;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 12px;
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Collapsible Manual Admin Login Section */
        .manual-login-toggle {
            text-align: center;
            margin-top: 10px;
        }

        .btn-toggle-manual {
            background: none;
            border: none;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            padding: 4px 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-toggle-manual:hover {
            color: var(--skj-pink);
        }

        .manual-form-container {
            display: none;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
            animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-floating-custom {
            margin-bottom: 14px;
        }

        .form-floating-custom label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 4px;
            display: block;
        }

        .input-group-modern {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-modern i {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 1.15rem;
            pointer-events: none;
        }

        .input-group-modern input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.9rem;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
        }

        .input-group-modern input:focus {
            background: #ffffff;
            border-color: var(--skj-pink);
            box-shadow: 0 0 0 3.5px rgba(255, 107, 139, 0.18);
        }

        .btn-submit-manual {
            width: 100%;
            padding: 11px;
            background: var(--skj-gradient);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(255, 107, 139, 0.35);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-submit-manual:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 139, 0.5);
            color: #ffffff;
        }

        /* Mobile Adjustments */
        @media (max-width: 991.98px) {
            .auth-card-frame {
                flex-direction: column;
                max-width: 480px;
                margin: 60px auto 20px;
                border-radius: 26px;
            }

            .auth-showcase-panel {
                padding: 32px 24px;
                text-align: center;
            }

            .school-logo-wrap {
                justify-content: center;
            }

            .showcase-content {
                margin: 20px 0;
            }

            .showcase-heading {
                font-size: 1.35rem;
            }

            .feature-check-list {
                display: none;
            }

            .showcase-footer {
                display: none;
            }

            .auth-form-panel {
                padding: 32px 24px;
            }

            .top-nav-bar {
                top: 14px;
                left: 14px;
                right: 14px;
            }

            .btn-back-home {
                padding: 6px 14px;
                font-size: 0.78rem;
            }
        }
    </style>
</head>

<body>
    <!-- Ambient Dynamic Background -->
    <div class="ambient-bg">
        <div class="ambient-orb-1"></div>
        <div class="ambient-orb-2"></div>
        <div class="ambient-grid"></div>
    </div>

    <!-- Top Navigation Header -->
    <div class="top-nav-bar">
        <a href="<?= base_url('new-admission') ?>" class="btn-back-home">
            <i class="bx bx-arrow-back fs-5"></i>
            <span>กลับหน้าหลักรับสมัคร</span>
        </a>
        <div class="system-status-pill">
            <span class="status-dot"></span>
            <span>ระบบออนไลน์ 24 ชม.</span>
        </div>
    </div>

    <!-- Main Auth Container -->
    <div class="auth-container">
        <div class="auth-card-frame">
            
            <!-- Left Showcase Visual -->
            <div class="auth-showcase-panel">
                <div class="school-logo-wrap">
                    <div class="school-logo-icon">
                        <i class="bx bxs-school"></i>
                    </div>
                    <div>
                        <h4 class="school-brand-title">SKJ ADMISSION</h4>
                        <div class="school-brand-subtitle">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</div>
                    </div>
                </div>

                <div class="showcase-content">
                    <div class="showcase-badge">
                        <i class="bx bx-shield-quarter"></i>
                        <span>ระบบสารสนเทศและการรับสมัครนักเรียน</span>
                    </div>
                    <h2 class="showcase-heading">
                        ศูนย์ควบคุมและจัดการ <br>
                        <span>ระบบรับสมัครนักเรียน</span>
                    </h2>
                    <p class="showcase-desc">
                        เข้าสู่ระบบเพื่อตรวจสอบข้อมูลผู้สมัคร จัดการหลักสูตร โควตา และสนทนาสดตอบคำถามนักเรียนและผู้ปกครอง
                    </p>

                    <div class="feature-check-list">
                        <div class="feature-check-item">
                            <i class="bx bx-check"></i>
                            <span>ตรวจสอบเอกสารและสถานะการสมัครแบบเรียลไทม์</span>
                        </div>
                        <div class="feature-check-item">
                            <i class="bx bx-check"></i>
                            <span>Live Chat & Telegram Bot สนทนากับผู้ปกครอง</span>
                        </div>
                        <div class="feature-check-item">
                            <i class="bx bx-check"></i>
                            <span>รายงานผลสถิติและพิมพ์บัตรประจำตัวสอบอัตโนมัติ</span>
                        </div>
                    </div>
                </div>

                <div class="showcase-footer">
                    <span>© <?= date('Y') + 543 ?> SKJ Admission System</span>
                    <span>Single Sign-On Security</span>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="auth-form-panel">
                <div class="form-header">
                    <h3 class="form-title">ยินดีต้อนรับเข้าสู่ระบบ 👋</h3>
                    <p class="form-subtitle">เข้าสู่ระบบด้วยบัญชี Google ของโรงเรียน เพื่อเข้าสู่ศูนย์ควบคุม</p>
                </div>

                <!-- Google Sign-In Primary Card -->
                <div class="google-auth-box">
                    <div class="google-icon-wrapper">
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                    </div>
                    <div class="google-hint-text">
                        กดปุ่มด้านล่างเพื่อเข้าสู่ระบบทันที
                    </div>

                    <!-- Google GSI Standard Button -->
                    <div class="gsi-button-wrap">
                        <div id="g_id_onload"
                            data-client_id="<?= (new \Config\Google())->clientId ?>"
                            data-context="signin"
                            data-ux_mode="popup"
                            data-callback="handleCredentialResponse"
                            data-auto_prompt="false">
                        </div>
                        <div class="g_id_signin"
                            data-type="standard"
                            data-shape="pill"
                            data-theme="outline"
                            data-text="signin_with"
                            data-size="large"
                            data-logo_alignment="left"
                            data-width="260">
                        </div>
                    </div>

                    <div class="domain-lock-badge">
                        <i class="bx bx-lock-alt"></i>
                        <span>อนุญาตเฉพาะอีเมลโดเมน <b>@skj.ac.th</b> เท่านั้น</span>
                    </div>
                </div>

                <!-- Fallback Password Login Toggle -->
                <div class="manual-login-toggle">
                    <button type="button" class="btn-toggle-manual" onclick="toggleManualLogin()">
                        <i class="bx bx-key"></i>
                        <span>เข้าสู่ระบบด้วยรหัสผ่านผู้ดูแล (Admin Login)</span>
                        <i class="bx bx-chevron-down" id="toggleIcon"></i>
                    </button>
                </div>

                <!-- Collapsible Manual Form -->
                <div class="manual-form-container" id="manualFormBox">
                    <form action="<?= base_url('UserControlLogin/validlogin') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="form-floating-custom">
                            <label>ชื่อผู้ใช้งาน (Username)</label>
                            <div class="input-group-modern">
                                <i class="bx bx-user"></i>
                                <input type="text" name="username" placeholder="กรอกชื่อผู้ใช้..." required>
                            </div>
                        </div>

                        <div class="form-floating-custom">
                            <label>รหัสผ่าน (Password)</label>
                            <div class="input-group-modern">
                                <i class="bx bx-lock-alt"></i>
                                <input type="password" name="password" placeholder="กรอกรหัสผ่าน..." required>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit-manual">
                            <span>เข้าสู่ระบบ</span>
                            <i class="bx bx-log-in-circle"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <!-- SweetAlert2 Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (session()->getFlashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'เข้าสู่ระบบไม่สำเร็จ',
            text: '<?= addslashes(session()->getFlashdata('error')) ?>',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#ff6b8b',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0'
            }
        });
    </script>
    <?php endif; ?>

    <script>
        function handleCredentialResponse(response) {
            Swal.fire({
                title: 'กำลังเข้าสู่ระบบ...',
                text: 'กำลังยืนยันตัวตนกับระบบ Google',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('auth/googleLogin') ?>';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'credential';
            input.value = response.credential;
            form.appendChild(input);
            
            document.body.appendChild(form);
            form.submit();
        }

        function toggleManualLogin() {
            const box = document.getElementById('manualFormBox');
            const icon = document.getElementById('toggleIcon');
            if (box.style.display === 'block') {
                box.style.display = 'none';
                icon.className = 'bx bx-chevron-down';
            } else {
                box.style.display = 'block';
                icon.className = 'bx bx-chevron-up';
            }
        }
    </script>
</body>
</html>
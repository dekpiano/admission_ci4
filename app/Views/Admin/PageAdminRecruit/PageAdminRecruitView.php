<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<?php 
$datethai = new \App\Libraries\Datethai(); 

// Status setup
$status = $recruit['recruit_status'] ?? 'รอการตรวจสอบ';
$statusClass = 'status-pending';
$statusBadgeBg = '#fef3c7';
$statusBadgeColor = '#92400e';
$statusIcon = 'bx-time-five';
$statusText = 'รอการตรวจสอบ';

if ($status === 'ผ่านการตรวจสอบ') {
    $statusClass = 'status-approved';
    $statusBadgeBg = '#dcfce7';
    $statusBadgeColor = '#166534';
    $statusIcon = 'bx-check-circle';
    $statusText = 'ผ่านการตรวจสอบแล้ว';
} elseif (strpos($status, 'ไม่ผ่าน') !== false) {
    $statusClass = 'status-rejected';
    $statusBadgeBg = '#fee2e2';
    $statusBadgeColor = '#991b1b';
    $statusIcon = 'bx-x-circle';
    $statusText = $status;
}

$regLevel = esc($recruit['recruit_regLevel'] ?? '1');
$avatarUrl = get_recruit_file_url(($recruit['recruit_img'] ?? 'default.png'), $regLevel, 'img', true);
$defaultAvatar = base_url('sneat-assets/img/avatars/1.png');

$isSport = (
    (!empty($recruit['recruit_sportPosition']) && $recruit['recruit_sportPosition'] !== '-') ||
    (isset($recruit['course_fullname']) && mb_strpos($recruit['course_fullname'], 'กีฬา') !== false) ||
    (isset($recruit['course_branch']) && mb_strpos($recruit['course_branch'], 'กีฬา') !== false) ||
    (isset($recruit['quota_key']) && ($recruit['quota_key'] === 'sport' || strpos($recruit['quota_key'], 'A') === 0))
);
?>

<style>
    /* Suankularb Rose Pink & Sky Blue Design Tokens */
    :root {
        --skj-pink: #ff6b8b;
        --skj-pink-dark: #e04869;
        --skj-blue: #56ccf2;
        --skj-blue-dark: #0284c7;
        --skj-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
    }

    /* Hero Profile Card */
    .profile-hero-card {
        border-radius: 18px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .profile-hero-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--skj-gradient);
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 105px;
        height: 105px;
        flex-shrink: 0;
    }

    .profile-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 18px;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        background-color: #f8fafc;
    }

    .level-badge-floating {
        position: absolute;
        bottom: -6px;
        right: -6px;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    /* Verification Status Banner */
    .status-pill-lg {
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.2px;
    }

    /* Action Toolbar Cards */
    .action-toolbar-card {
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    /* Detail Grid Tiles */
    .info-tile {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        height: 100%;
        transition: all 0.2s ease;
    }

    .info-tile:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }

    .info-tile .tile-label {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-tile .tile-value {
        font-size: 0.96rem;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
    }

    /* Section Cards */
    .content-section-card {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
    }

    .content-section-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.1rem 1.4rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Document Cards */
    .doc-preview-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        transition: all 0.25s ease;
        overflow: hidden;
    }

    .doc-preview-card:hover {
        border-color: var(--skj-pink);
        box-shadow: 0 8px 20px rgba(255, 107, 139, 0.12);
        transform: translateY(-3px);
    }

    .doc-preview-card .doc-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 12px;
    }

    /* Navigation Pills */
    .custom-pills .nav-link {
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.88rem;
        color: #475569;
        padding: 9px 18px;
        transition: all 0.2s ease;
    }

    .custom-pills .nav-link.active {
        background: var(--skj-gradient) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(255, 107, 139, 0.35);
    }

    /* Preference List Steps */
    .pref-step-item {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 12px 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
    }

    .pref-step-item:hover {
        background: #ffffff;
        border-color: var(--skj-pink);
    }

    .pref-step-number {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .pref-step-number.step-1 {
        background: rgba(255, 107, 139, 0.15);
        color: #ff6b8b;
    }

    .pref-step-number.step-2 {
        background: rgba(86, 204, 242, 0.15);
        color: #0284c7;
    }

    .pref-step-number.step-3 {
        background: rgba(100, 116, 139, 0.15);
        color: #475569;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Top Breadcrumb & Navigation -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('skjadmin/recruits') ?>" class="btn btn-sm btn-outline-secondary d-flex align-items-center" style="border-radius: 8px;">
                <i class="bx bx-arrow-back me-1"></i> กลับหน้ารายการ
            </a>
            <span class="text-muted">/</span>
            <span class="fw-bold text-dark">รหัส #<?= esc(sprintf('%04d', $recruit['recruit_id'])) ?></span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Print Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                    <i class="bx bx-printer me-1"></i> พิมพ์เอกสาร
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 12px;">
                    <?php if ($isSport): ?>
                        <li>
                            <a class="dropdown-item" href="<?= site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) ?>" target="_blank">
                                <i class="bx bx-run text-primary me-2"></i> พิมพ์ใบสมัครกีฬา
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= site_url('skjadmin/recruits/print-normal/' . $recruit['recruit_id']) ?>" target="_blank">
                                <i class="bx bx-printer text-info me-2"></i> พิมพ์ใบสมัครธรรมดา
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a class="dropdown-item" href="<?= site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) ?>" target="_blank">
                                <i class="bx bx-printer text-primary me-2"></i> พิมพ์ใบสมัคร
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Edit Button -->
            <a href="<?= site_url('skjadmin/recruits/edit/' . $recruit['recruit_id']) ?>" class="btn btn-sm btn-info fw-bold text-white d-flex align-items-center" style="border-radius: 8px;">
                <i class="bx bx-edit me-1"></i> แก้ไขข้อมูล
            </a>
        </div>
    </div>

    <!-- 1. Hero Profile Header Card -->
    <div class="card profile-hero-card mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">
                
                <!-- Profile Left: Photo + Name + Key Badges -->
                <div class="d-flex align-items-center gap-3 gap-md-4">
                    <div class="profile-avatar-wrapper">
                        <img src="<?= $avatarUrl ?>" alt="Student Photo" class="profile-avatar-img" onerror="this.onerror=null;this.src='<?= $defaultAvatar ?>';">
                        <span class="level-badge-floating <?= $regLevel === '1' ? 'bg-primary text-white' : 'bg-info text-white' ?>">
                            ม.<?= $regLevel ?>
                        </span>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="badge bg-label-secondary font-monospace fw-bold fs-6">
                                #<?= esc(sprintf('%04d', $recruit['recruit_id'])) ?>
                            </span>
                            <?php if (!empty($recruit['recruit_round'])): ?>
                                <span class="badge bg-label-dark fw-bold">รอบที่ <?= esc($recruit['recruit_round']) ?></span>
                            <?php endif; ?>
                            <span class="badge bg-label-primary fw-bold"><?= esc($recruit['quota_explain'] ?? $recruit['recruit_category']) ?></span>
                        </div>

                        <h3 class="fw-bold mb-1 text-dark">
                            <?= esc($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']) ?>
                        </h3>

                        <div class="d-flex align-items-center gap-3 text-muted flex-wrap small">
                            <span class="d-flex align-items-center gap-1">
                                <i class="bx bx-id-card text-primary"></i> <?= esc($recruit['recruit_idCard']) ?>
                            </span>
                            <span class="d-flex align-items-center gap-1">
                                <i class="bx bx-phone" style="color: #ff6b8b;"></i> <?= esc($recruit['recruit_phone']) ?>
                            </span>
                            <span class="d-flex align-items-center gap-1">
                                <i class="bx bx-calendar text-info"></i> วันที่สมัคร: <?= !empty($recruit['recruit_date']) ? esc($datethai->thai_date_fullmonth(strtotime($recruit['recruit_date']))) : '-' ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Profile Right: Status Badge & Quick Audit -->
                <div class="d-flex flex-column align-items-start align-items-lg-end">
                    <div class="status-pill-lg mb-2" style="background: <?= $statusBadgeBg ?>; color: <?= $statusBadgeColor ?>; border: 1px solid rgba(0,0,0,0.06);">
                        <i class="bx <?= $statusIcon ?> fs-5"></i>
                        <span><?= esc($statusText) ?></span>
                    </div>

                    <?php if (!empty($recruit['verifier_fname'])): ?>
                        <div class="small text-muted text-lg-end">
                            <i class="bx bx-user-check text-success"></i> ผู้ตรวจสอบ: <strong class="text-dark"><?= esc($recruit['verifier_prefix'] . $recruit['verifier_fname'] . ' ' . $recruit['verifier_lname']) ?></strong>
                            <?php if (!empty($recruit['recruit_dateUpdate'])): ?>
                                <div class="text-muted" style="font-size: 0.74rem;">
                                    (<?= esc($datethai->thai_date_fullmonth(strtotime($recruit['recruit_dateUpdate']))) ?> เวลา <?= date('H:i', strtotime($recruit['recruit_dateUpdate'])) ?> น.)
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="small text-muted">
                            <i class="bx bx-time text-warning"></i> ยังไม่มีเจ้าหน้าที่บันทึกผลตรวจสอบ
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <!-- 2. Fast Verification Action Bar -->
    <div class="card action-toolbar-card mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-shield-quarter text-primary fs-4"></i>
                    <div>
                        <div class="fw-bold text-dark">การดำเนินการตรวจสอบเอกสาร</div>
                        <div class="small text-muted">กดปุ่มด้านล่างเพื่อเปลี่ยนสถานะของผู้สมัครทันที</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-success fw-bold d-flex align-items-center" onclick="updateStatus('ผ่านการตรวจสอบ')" style="border-radius: 10px; padding: 7px 18px;">
                        <i class="bx bx-check-circle me-1 fs-5"></i> อนุมัติผ่านการตรวจสอบ
                    </button>
                    <button type="button" class="btn btn-warning fw-bold d-flex align-items-center" onclick="updateStatus('รอการตรวจสอบ')" style="border-radius: 10px; padding: 7px 18px;">
                        <i class="bx bx-time-five me-1 fs-5"></i> ตั้งเป็นรอตรวจสอบ
                    </button>
                    <button type="button" class="btn btn-outline-danger fw-bold d-flex align-items-center" onclick="updateStatus('ไม่ผ่านการตรวจสอบ')" style="border-radius: 10px; padding: 7px 18px;">
                        <i class="bx bx-x-circle me-1 fs-5"></i> แจ้งไม่ผ่าน (ระบุเหตุผล)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Main Detail Tabs -->
    <div class="nav-align-top mb-4">
        <ul class="nav nav-pills custom-pills mb-3 gap-2" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-personal" aria-selected="true">
                    <i class="bx bx-user me-1"></i> ข้อมูลส่วนตัว & ที่อยู่
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-education" aria-selected="false">
                    <i class="bx bx-book-open me-1"></i> การศึกษา & แผนการเรียนที่สมัคร
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-documents" aria-selected="false">
                    <i class="bx bx-file me-1"></i> เอกสารหลักฐานแนบ
                </button>
            </li>
        </ul>

        <div class="tab-content p-0 bg-transparent shadow-none">
            
            <!-- TAB 1: ข้อมูลส่วนตัว & ที่อยู่ -->
            <div class="tab-pane fade show active" id="tab-personal" role="tabpanel">
                
                <!-- Personal Info Card -->
                <div class="card content-section-card">
                    <div class="card-header">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-id-card text-primary fs-5"></i> ข้อมูลทั่วไปของผู้สมัคร
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-6 col-lg-4">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-id-card"></i> เลขประจำตัวประชาชน</div>
                                    <div class="tile-value font-monospace"><?= esc($recruit['recruit_idCard']) ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-calendar"></i> วันเดือนปีเกิด</div>
                                    <div class="tile-value">
                                        <?= !empty($recruit['recruit_birthday']) ? esc($datethai->thai_date_fullmonth(strtotime($recruit['recruit_birthday']))) : '-' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-user"></i> ชื่อเล่น / ฉายา</div>
                                    <div class="tile-value"><?= !empty($recruit['recruit_nickname']) ? esc($recruit['recruit_nickname']) : '-' ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-flag"></i> เชื้อชาติ</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_race'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-globe"></i> สัญชาติ</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_nationality'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-heart"></i> ศาสนา</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_religion'] ?? '-') ?></div>
                                </div>
                            </div>
                            <?php if (!empty($recruit['recruit_height']) || !empty($recruit['recruit_weight'])): ?>
                            <div class="col-sm-6 col-lg-6">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-ruler"></i> ส่วนสูง</div>
                                    <div class="tile-value"><?= !empty($recruit['recruit_height']) ? esc($recruit['recruit_height']) . ' ซม.' : '-' ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-body"></i> น้ำหนัก</div>
                                    <div class="tile-value"><?= !empty($recruit['recruit_weight']) ? esc($recruit['recruit_weight']) . ' กก.' : '-' ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Address Card -->
                <div class="card content-section-card">
                    <div class="card-header">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-home text-info fs-5"></i> ที่อยู่ตามทะเบียนบ้าน
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="info-tile bg-white border-2" style="border-color: #e2e8f0;">
                                    <div class="tile-label"><i class="bx bx-map-pin text-danger"></i> ที่อยู่แบบเต็ม</div>
                                    <div class="tile-value fs-6 text-primary">
                                        <?= esc($recruit['recruit_address'] ?? 'เลขที่ ' . $recruit['recruit_homeNumber'] . ' หมู่ ' . ($recruit['recruit_homeGroup'] ?? '-') . ' ต.' . $recruit['recruit_homeSubdistrict'] . ' อ.' . $recruit['recruit_homedistrict'] . ' จ.' . $recruit['recruit_homeProvince'] . ' ' . $recruit['recruit_homePostcode']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="info-tile">
                                    <div class="tile-label">บ้านเลขที่ / หมู่</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_homeNumber']) ?> หมู่ <?= esc($recruit['recruit_homeGroup'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="info-tile">
                                    <div class="tile-label">ถนน / ซอย</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_homeRoad'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="info-tile">
                                    <div class="tile-label">ตำบล / แขวง</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_homeSubdistrict']) ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="info-tile">
                                    <div class="tile-label">อำเภอ / เขต</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_homedistrict']) ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="info-tile">
                                    <div class="tile-label">จังหวัด</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_homeProvince']) ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <div class="info-tile">
                                    <div class="tile-label">รหัสไปรษณีย์</div>
                                    <div class="tile-value font-monospace"><?= esc($recruit['recruit_homePostcode']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parents Info Card -->
                <?php if (!empty($recruit['recruit_fatherName']) || !empty($recruit['recruit_motherName'])): ?>
                <div class="card content-section-card">
                    <div class="card-header">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-group text-success fs-5"></i> ข้อมูลบิดา - มารดา
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-male"></i> ชื่อ-นามสกุล บิดา</div>
                                    <div class="tile-value"><?= !empty($recruit['recruit_fatherName']) ? esc($recruit['recruit_fatherName']) : '-' ?></div>
                                    <div class="small text-muted mt-1">อาชีพ: <?= !empty($recruit['recruit_fatherJob']) ? esc($recruit['recruit_fatherJob']) : '-' ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-female"></i> ชื่อ-นามสกุล มารดา</div>
                                    <div class="tile-value"><?= !empty($recruit['recruit_motherName']) ? esc($recruit['recruit_motherName']) : '-' ?></div>
                                    <div class="small text-muted mt-1">อาชีพ: <?= !empty($recruit['recruit_motherJob']) ? esc($recruit['recruit_motherJob']) : '-' ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- TAB 2: การศึกษา & แผนการเรียนที่สมัคร -->
            <div class="tab-pane fade" id="tab-education" role="tabpanel">
                
                <!-- Education Background -->
                <div class="card content-section-card">
                    <div class="card-header">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-buildings text-primary fs-5"></i> ข้อมูลประวัติการศึกษาเดิม
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-school"></i> โรงเรียนเดิม</div>
                                    <div class="tile-value fs-6"><?= esc($recruit['recruit_oldSchool']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-map"></i> จังหวัดโรงเรียนเดิม</div>
                                    <div class="tile-value"><?= esc($recruit['recruit_province']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-tile">
                                    <div class="tile-label"><i class="bx bx-award"></i> เกรดเฉลี่ยสะสม (GPAX)</div>
                                    <div class="tile-value">
                                        <span class="badge bg-label-primary fs-6"><?= esc($recruit['recruit_grade']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Selection / Application Details -->
                <div class="card content-section-card">
                    <div class="card-header">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-list-check text-info fs-5"></i> อันดับแผนการเรียนที่เลือกสมัคร
                        </span>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Quota & Round Overview -->
                        <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <span class="badge bg-primary fs-6 mb-1">ม.<?= $regLevel ?></span>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">
                                    ประเภท: <?= esc($recruit['quota_explain'] ?? $recruit['recruit_category']) ?>
                                    <?php if (!empty($recruit['recruit_round'])): ?>
                                        <span class="badge bg-label-dark ms-1">รอบที่ <?= esc($recruit['recruit_round']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted">ปีการศึกษา <?= esc($recruit['recruit_year']) ?></div>
                            </div>
                        </div>

                        <!-- Major Order Ranking List -->
                        <?php if (!empty($recruit['major_order_list'])): ?>
                            <h6 class="fw-bold text-dark mb-3"><i class="bx bx-sort-down text-primary me-1"></i> ลำดับแผนการเรียนที่เลือก:</h6>
                            <div class="row g-2 mb-3">
                                <?php foreach ($recruit['major_order_list'] as $idx => $major): ?>
                                    <div class="col-12">
                                        <div class="pref-step-item">
                                            <div class="pref-step-number step-<?= ($idx + 1) ?>">
                                                <?= $idx + 1 ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark fs-6"><?= esc($major) ?></div>
                                                <div class="small text-muted">อันดับที่ <?= $idx + 1 ?></div>
                                            </div>
                                            <?php if ($idx === 0): ?>
                                                <span class="badge bg-label-primary fw-bold">อันดับ 1 (หลัก)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="info-tile">
                                <div class="tile-label">แผนการเรียนที่เลือก</div>
                                <div class="tile-value fs-6 text-primary"><?= esc($recruit['recruit_tpyeRoom']) ?></div>
                                <?php if (!empty($recruit['recruit_major'])): ?>
                                    <div class="small text-muted mt-1">สาขาวิชา: <?= esc($recruit['recruit_major']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Sports Info if applicable -->
                        <?php if ($isSport): ?>
                            <div class="mt-4 p-3 rounded-3" style="background: rgba(255, 107, 139, 0.06); border: 1px dashed var(--skj-pink);">
                                <h6 class="fw-bold text-dark d-flex align-items-center gap-1 mb-2">
                                    <i class="bx bx-run text-primary"></i> ข้อมูลความสามารถพิเศษด้านกีฬา
                                </h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="small text-muted">ตำแหน่ง / ประเภทกีฬา:</div>
                                        <div class="fw-bold text-dark"><?= !empty($recruit['recruit_sportPosition']) ? esc($recruit['recruit_sportPosition']) : '-' ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">ผลการคัดเลือกความสามารถพิเศษ:</div>
                                        <div class="fw-bold text-primary"><?= !empty($recruit['recruit_sportSelectionResult']) ? esc($recruit['recruit_sportSelectionResult']) : 'รอคัดเลือก' ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>

            <!-- TAB 3: เอกสารหลักฐานแนบ -->
            <div class="tab-pane fade" id="tab-documents" role="tabpanel">
                <div class="card content-section-card">
                    <div class="card-header">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-folder-open text-primary fs-5"></i> รายการเอกสารหลักฐานแนบ
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        $docs = [
                            ['name' => 'รูปถ่ายนักเรียน (หน้าตรง)', 'file' => $recruit['recruit_img'], 'icon' => 'bx-image', 'folder' => 'img', 'color' => '#ff6b8b'],
                            ['name' => 'ระเบียนแสดงผลการเรียน (ปพ.1 ด้านหน้า)', 'file' => $recruit['recruit_certificateEdu'], 'icon' => 'bx-file', 'folder' => 'certificate', 'color' => '#0284c7'],
                            ['name' => 'ระเบียนแสดงผลการเรียน (ปพ.1 ด้านหลัง)', 'file' => $recruit['recruit_certificateEduB'], 'icon' => 'bx-file-blank', 'folder' => 'certificateB', 'color' => '#0284c7'],
                            ['name' => 'สำเนาบัตรประจำตัวประชาชน', 'file' => $recruit['recruit_copyidCard'], 'icon' => 'bx-id-card', 'folder' => 'copyidCard', 'color' => '#e11d48'],
                            ['name' => 'สำเนาทะเบียนบ้าน', 'file' => $recruit['recruit_copyAddress'], 'icon' => 'bx-home', 'folder' => 'copyAddress', 'color' => '#f59e0b'],
                            ['name' => 'หลักฐานความสามารถพิเศษ (กีฬา/อื่นๆ)', 'file' => $recruit['recruit_certificateAbility'], 'icon' => 'bx-medal', 'folder' => 'certificateAbility', 'color' => '#8b5cf6'],
                        ];
                        ?>

                        <div class="row g-3">
                            <?php foreach ($docs as $doc): ?>
                                <?php 
                                $hasFile = !empty($doc['file']);
                                $fileUrl = $hasFile ? get_recruit_file_url($doc['file'], $regLevel, $doc['folder'], true) : '';
                                ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="doc-preview-card h-100 p-3 text-center d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="doc-icon-box" style="background: <?= $hasFile ? 'rgba(86, 204, 242, 0.15)' : '#f1f5f9' ?>; color: <?= $hasFile ? $doc['color'] : '#94a3b8' ?>;">
                                                <i class="bx <?= $doc['icon'] ?>"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem;"><?= $doc['name'] ?></h6>
                                            <div class="mb-3">
                                                <?php if ($hasFile): ?>
                                                    <span class="badge bg-label-success" style="font-size: 0.72rem;"><i class="bx bx-check me-1"></i> มีไฟล์แนบ</span>
                                                <?php else: ?>
                                                    <span class="badge bg-label-secondary" style="font-size: 0.72rem;">ไม่มีเอกสาร</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center gap-2">
                                            <?php if ($hasFile): ?>
                                                <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-primary d-flex align-items-center gap-1" style="border-radius: 8px;">
                                                    <i class="bx bx-show"></i> ดูเอกสาร
                                                </a>
                                                <a href="<?= $fileUrl ?>" download class="btn btn-sm btn-outline-secondary d-flex align-items-center" style="border-radius: 8px;" title="ดาวน์โหลด">
                                                    <i class="bx bx-download"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-light text-muted" disabled style="border-radius: 8px;">
                                                    <i class="bx bx-block me-1"></i> ไม่มีไฟล์
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateStatus(status) {
        if (status === 'ไม่ผ่านการตรวจสอบ') {
            Swal.fire({
                title: 'ระบุสาเหตุที่ไม่ผ่าน',
                input: 'textarea',
                inputLabel: 'กรุณาระบุเหตุผลที่ต้องแก้ไข (ข้อความนี้จะแสดงให้นักเรียนเห็น):',
                inputPlaceholder: 'เช่น รูปถ่ายไม่ชัดเจน, เอกสาร ปพ.1 ยังไม่ได้รับรองสำเนาถูกต้อง...',
                inputAttributes: {
                    'aria-label': 'Type your reason here'
                },
                showCancelButton: true,
                confirmButtonText: 'บันทึกสถานะ',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    if (!reason || reason.trim() === '') {
                        Swal.showValidationMessage('กรุณาระบุเหตุผลที่ไม่ผ่าน');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const fullStatus = status + ': ' + result.value.trim();
                    submitStatusUpdate(fullStatus);
                }
            });
        } else {
            let title = status === 'ผ่านการตรวจสอบ' ? 'ยืนยันอนุมัติผ่านการตรวจสอบ?' : 'ยืนยันเปลี่ยนเป็นรอการตรวจสอบ?';
            let icon = status === 'ผ่านการตรวจสอบ' ? 'success' : 'question';
            let confirmBtnColor = status === 'ผ่านการตรวจสอบ' ? '#10b981' : '#f59e0b';

            Swal.fire({
                title: title,
                text: "ต้องการปรับสถานะผู้สมัครรายนี้เป็น '" + status + "' ใช่หรือไม่?",
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ใช่, บันทึกทันที',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitStatusUpdate(status);
                }
            });
        }
    }

    function submitStatusUpdate(statusValue) {
        $.ajax({
            url: '<?= site_url('skjadmin/recruits/update-status') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id: '<?= $recruit['recruit_id'] ?>',
                status: statusValue,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'อัปเดตสถานะการตรวจสอบเรียบร้อยแล้ว',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'ผิดพลาด!',
                        response.message || 'ไม่สามารถอัปเดตสถานะได้',
                        'error'
                    );
                }
            },
            error: function () {
                Swal.fire(
                    'ผิดพลาด!',
                    'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                    'error'
                );
            }
        });
    }
</script>
<?= $this->endSection() ?>
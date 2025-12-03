<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    /* Stats Cards */
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 5px solid;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .stat-card.primary { border-left-color: #ff9eb5; }
    .stat-card.success { border-left-color: #84d2f6; }
    .stat-card.warning { border-left-color: #ffc4d6; }
    .stat-card.info { border-left-color: #a8e0ff; }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    .stat-icon.primary { background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%); color: white; }
    .stat-icon.success { background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%); color: white; }
    .stat-icon.warning { background: linear-gradient(135deg, #ffb3c6 0%, #ffd4e0 100%); color: white; }
    .stat-icon.info { background: linear-gradient(135deg, #b8e5fb 0%, #d4f1ff 100%); color: white; }
    
    /* Announcement Card */
    .announcement-card {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(255, 158, 181, 0.3);
        position: relative;
        overflow: hidden;
    }
    .announcement-card::after {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    /* Countdown */
    .countdown-container {
        font-family: 'Prompt', sans-serif;
        color: #fff;
        display: inline-block;
        text-align: center;
    }
    .countdown-container ul {
        padding: 0;
        margin: 0;
        display: flex;
        gap: 15px;
        justify-content: center;
    }
    .countdown-container li {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        list-style-type: none;
        padding: 1rem 0.75rem;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        min-width: 80px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .countdown-container li span {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }
    .countdown-container li .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-top: 8px;
        opacity: 0.95;
        font-weight: 600;
    }
    
    /* Application Cards */
    .app-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border: 2px solid transparent;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .app-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #ff9eb5 0%, #84d2f6 100%);
    }
    .app-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        border-color: #ff9eb5;
    }
    .app-card.m4:hover {
        border-color: #84d2f6;
    }
    .app-card.m4::before {
        background: linear-gradient(90deg, #84d2f6 0%, #ff9eb5 100%);
    }
    
    .level-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(255, 158, 181, 0.3);
    }
    .app-card.m4 .level-badge {
        background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%);
        box-shadow: 0 4px 15px rgba(132, 210, 246, 0.3);
    }
    
    .apply-button {
        width: 100%;
        padding: 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        border: none;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .apply-button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .apply-button:hover::before {
        width: 300px;
        height: 300px;
    }
    .apply-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }
    
    /* Custom Button Colors - Pastel Pink/Blue */
    .btn-primary {
        background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%);
        border: none;
        color: white;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #ff89a8 0%, #ffb3c6 100%);
    }
    .btn-info {
        background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%);
        border: none;
        color: white;
    }
    .btn-info:hover {
        background: linear-gradient(135deg, #6ec6f0 0%, #95d9ff 100%);
    }
    
    /* Schedule Table */
    .schedule-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .schedule-header {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        color: white;
        padding: 1.5rem;
    }
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    .table-hover tbody tr:hover {
        background: linear-gradient(90deg, rgba(255, 158, 181, 0.1) 0%, rgba(132, 210, 246, 0.1) 100%);
        transform: scale(1.01);
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 1rem;
        }
        .countdown-container li {
            min-width: 60px;
            padding: 0.75rem 0.5rem;
        }
        .countdown-container li span {
            font-size: 1.5rem;
        }
        .app-card {
            margin-bottom: 1.5rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content text-center">
        <h1 class="display-4 fw-bold mb-3">
            <i class='bx bx-graduation'></i> ระบบรับสมัครนักเรียนออนไลน์
        </h1>
        <p class="lead mb-4">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?= base_url('new-admission/manual') ?>" class="btn btn-light btn-lg rounded-pill px-4">
                <i class='bx bx-book-open me-2'></i> คู่มือการสมัคร
            </a>
            <a href="<?= base_url('new-admission/status') ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                <i class='bx bx-search-alt me-2'></i> ตรวจสอบสถานะ
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6 col-6">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class='bx bx-calendar'></i>
            </div>
            <h6 class="text-muted mb-1">ปีการศึกษา</h6>
            <h3 class="fw-bold mb-0"><?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?></h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-6">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class='bx bx-user-check'></i>
            </div>
            <h6 class="text-muted mb-1">สถานะระบบ</h6>
            <h5 class="fw-bold mb-0">
                <?php if(isset($systemStatus->onoff_regis) && $systemStatus->onoff_regis == 'on'): ?>
                    <span class="text-success">เปิดรับสมัคร</span>
                <?php else: ?>
                    <span class="text-danger">ปิดรับสมัคร</span>
                <?php endif; ?>
            </h5>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-6">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class='bx bx-folder-open'></i>
            </div>
            <h6 class="text-muted mb-1">รอบที่เปิด</h6>
            <h6 class="fw-bold mb-0">
                <?php
                $activeRounds = [];
                if (!empty($quotas)) {
                    foreach ($quotas as $quota) {
                        if (isset($quota->quota_status) && $quota->quota_status == 'on') {
                            $activeRounds[] = $quota->quota_explain;
                        }
                    }
                }
                
                if (!empty($activeRounds)) {
                    // Display first round name
                    echo '<span class="text-success">' . $activeRounds[0] . '</span>';
                } else {
                    echo '<span class="text-muted">ไม่มีรอบเปิด</span>';
                }
                ?>
            </h6>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-6">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class='bx bx-clipboard'></i>
            </div>
            <h6 class="text-muted mb-1">การรายงานตัว</h6>
            <h5 class="fw-bold mb-0">
                <?php
                if(isset($systemStatus->onoff_report) && $systemStatus->onoff_report == 'on') {
                    echo '<span class="text-success">เปิดรายงานตัว</span>';
                } else {
                    echo '<span class="text-muted">ยังไม่เปิด</span>';
                }
                ?>
            </h5>
        </div>
    </div>
</div>

<!-- Announcement -->
<div class="row mb-4">
    <div class="col-12">
        <?php 
        $hasActiveQuota = false;
        if (!empty($quotas)) {
            foreach ($quotas as $quota) {
                if (isset($quota->quota_status) && $quota->quota_status == 'on') {
                    $hasActiveQuota = true;
                    $is_closed_announce = false;
                    if (isset($systemStatus->onoff_datetime_regis_close)) {
                        if (time() > strtotime($systemStatus->onoff_datetime_regis_close)) {
                            $is_closed_announce = true;
                        }
                    }
                    ?>
                    <?php if ($systemStatus->onoff_regis == 'on'): ?>
                    <div class="announcement-card">
                        <div class="text-center">
                            <h2 class="fw-bold mb-3">
                                <i class='bx bx-broadcast me-2'></i> <?= $quota->quota_explain ?>
                                <?php if($is_closed_announce): ?>
                                    <span class="badge bg-danger ms-2">ปิดรับสมัครแล้ว</span>
                                <?php elseif(isset($systemStatus->onoff_datetime_regis_open) && time() < strtotime($systemStatus->onoff_datetime_regis_open)): ?>
                                    <span class="badge bg-warning text-dark ms-2">รอเปิดรับสมัคร</span>
                                <?php else: ?>
                                    <span class="badge bg-success ms-2">กำลังรับสมัคร</span>
                                <?php endif; ?>
                            </h2>
                            
                            <?php if(isset($systemStatus->onoff_datetime_regis_open) && time() < strtotime($systemStatus->onoff_datetime_regis_open)): ?>
                                <p class="fs-5 mb-3"><i class='bx bx-time-five'></i> กำลังจะเปิดรับสมัครในอีก...</p>
                                <div class="countdown-container mb-4" data-target="<?= $systemStatus->onoff_datetime_regis_open ?>">
                                    <ul>
                                        <li><span class="days">0</span><div class="label">วัน</div></li>
                                        <li><span class="hours">0</span><div class="label">ชั่วโมง</div></li>
                                        <li><span class="minutes">0</span><div class="label">นาที</div></li>
                                        <li><span class="seconds">0</span><div class="label">วินาที</div></li>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap mt-3">
                                <div class="text-white d-flex align-items-center gap-2 px-3 py-2" style="background: rgba(72, 187, 120, 0.3); border: 2px solid rgba(72, 187, 120, 0.5); border-radius: 10px; backdrop-filter: blur(5px);">
                                    <i class='bx bx-calendar-check' style="font-size: 1.5rem;"></i>
                                    <div>
                                        <div style="font-size: 0.85rem; font-weight: 600;">เปิดรับสมัคร</div>
                                        <div class="fw-bold" style="font-size: 0.95rem;">
                                            <?= isset($systemStatus->onoff_datetime_regis_open) ? $datethai->thai_date_fullmonth(strtotime($systemStatus->onoff_datetime_regis_open)) : '-' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-white d-flex align-items-center gap-2 px-3 py-2" style="background: rgba(245, 101, 101, 0.3); border: 2px solid rgba(245, 101, 101, 0.5); border-radius: 10px; backdrop-filter: blur(5px);">
                                    <i class='bx bx-calendar-x' style="font-size: 1.5rem;"></i>
                                    <div>
                                        <div style="font-size: 0.85rem; font-weight: 600;">ปิดรับสมัคร</div>
                                        <div class="fw-bold" style="font-size: 0.95rem;">
                                            <?= isset($systemStatus->onoff_datetime_regis_close) ? $datethai->thai_date_fullmonth(strtotime($systemStatus->onoff_datetime_regis_close)) : '-' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php
                    break;
                }
            }
        }
       
            if (!$hasActiveQuota) {
                if (isset($systemStatus->onoff_datetime_regis_open) && time() < strtotime($systemStatus->onoff_datetime_regis_open)) {
                    ?>
                    <div class="announcement-card">
                        <div class="text-center">
                        <h2 class="fw-bold mb-3">
                            <i class='bx bx-time-five'></i> ระบบรับสมัครนักเรียนออนไลน์
                            <span class="badge bg-warning text-dark ms-2">รอเปิดรับสมัคร</span>
                        </h2>
                        <p class="fs-5 mb-3">กำลังจะเปิดรับสมัครในอีก...</p>
                        <div class="countdown-container" data-target="<?= $systemStatus->onoff_datetime_regis_open ?>">
                            <ul>
                                <li><span class="days">0</span><div class="label">วัน</div></li>
                                <li><span class="hours">0</span><div class="label">ชั่วโมง</div></li>
                                <li><span class="minutes">0</span><div class="label">นาที</div></li>
                                <li><span class="seconds">0</span><div class="label">วินาที</div></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="announcement-card text-center">
                    <i class='bx bx-info-circle fs-1 mb-3'></i>
                    <h3 class="fw-bold">ยังไม่มีประกาศรับสมัครในขณะนี้</h3>
                    <p class="mb-0">กรุณาติดตามประกาศจากทางโรงเรียน</p>
                </div>
                <?php
            }
        }
    
        ?>
    </div>
</div>

<!-- Application Cards -->
<div class="row justify-content-center g-4 mb-4" id="apply-section">
    <?php
    // --- Determine which levels are open based on active quotas ---
    $open_levels = []; 
    if (!empty($quotas)) {
        foreach ($quotas as $quota) {
            if (isset($quota->quota_status) && $quota->quota_status == 'on' && !empty($quota->quota_level)) {
                $level_string = $quota->quota_level;
                $levels_in_quota = [];

                if (strpos($level_string, '|') !== false) {
                    $levels_in_quota = explode('|', $level_string);
                } elseif (strpos($level_string, ',') !== false) {
                    $levels_in_quota = explode(',', $level_string);
                } else {
                    $levels_in_quota = [$level_string];
                }

                foreach ($levels_in_quota as $level_str) {
                    $level_num_char = preg_replace('/[^0-9]/', '', $level_str);
                    if (is_numeric($level_num_char)) {
                        $open_levels[] = intval($level_num_char);
                    }
                }
            }
        }
    }
    $open_levels = array_unique($open_levels);
    sort($open_levels); 
    ?>

    <?php if (isset($systemStatus) && $systemStatus->onoff_regis == 'on'): ?>
        <?php if (!empty($open_levels)): ?>
            <?php foreach ($open_levels as $level_num): ?>
                <?php
                    $is_junior_high = $level_num <= 3;
                    $pre_check_url_level = $is_junior_high ? '1' : '4';
                    $subtitle = $is_junior_high ? 'สำหรับนักเรียนที่จบการศึกษาชั้น ป.6 หรือเทียบเท่า' : 'สำหรับนักเรียนที่จบการศึกษาชั้น ม.3 หรือเทียบเท่า';
                    $btn_class = $is_junior_high ? 'btn-primary' : 'btn-info text-white';
                    $card_class = $is_junior_high ? '' : 'm4';
                ?>
                <div class="col-md-6 col-lg-5">
                    <div class="app-card <?= $card_class ?>">
                        <div class="text-center">
                            <div class="level-badge">
                                <i class='bx bx-bookmark me-1'></i> มัธยมศึกษาปีที่ <?= $level_num ?>
                            </div>
                            <p class="text-muted mb-3"><?= $subtitle ?></p>
                            <div class="mb-4">
                                <i class='bx bx-calendar fs-1 text-primary mb-2'></i>
                                <p class="fw-bold text-dark mb-0">
                                    ปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?>
                                </p>
                            </div>
                            
                            <?php 
                                $is_closed = false;
                                $is_not_open = false;
                                $open_time = 0;

                                if (isset($systemStatus->onoff_datetime_regis_open)) {
                                    $open_time = strtotime($systemStatus->onoff_datetime_regis_open);
                                    if (time() < $open_time) {
                                        $is_not_open = true;
                                    }
                                }

                                if (isset($systemStatus->onoff_datetime_regis_close)) {
                                    $close_time = strtotime($systemStatus->onoff_datetime_regis_close);
                                    if (time() > $close_time) {
                                        $is_closed = true;
                                    }
                                }
                            ?>
                            
                            <?php if ($is_closed): ?>
                                <button class="apply-button btn btn-secondary" disabled>
                                    <i class='bx bx-x-circle me-2'></i> ปิดรับสมัครแล้ว
                                </button>
                            <?php elseif ($is_not_open): ?>
                                <button class="apply-button btn btn-warning" disabled>
                                    <i class='bx bx-time-five me-2'></i> ยังไม่ถึงวันรับสมัคร
                                </button>
                            <?php else: ?>
                                <button type="button" class="apply-button btn <?= $btn_class ?> apply-btn" data-href="<?= base_url('new-admission/pre-check/' . $pre_check_url_level . '?level=' . $level_num) ?>">
                                    <i class='bx bx-edit-alt me-2'></i> สมัครเรียน ม.<?= $level_num ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="announcement-card text-center">
                    <i class='bx bx-info-circle fs-1 mb-3'></i>
                    <h4 class="fw-bold">ยังไม่เปิดรับสมัคร</h4>
                    <p class="mb-0">ยังไม่มีระดับชั้นที่เปิดรับสมัครในขณะนี้ กรุณาติดตามประกาศจากทางโรงเรียน</p>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="col-12">
            <div class="announcement-card text-center">
                <i class='bx bx-lock-alt fs-1 mb-3'></i>
                <h4 class="fw-bold">ปิดระบบรับสมัคร</h4>
                <p class="mb-0">ระบบรับสมัครนักเรียนออนไลน์ยังไม่เปิดให้บริการ กรุณาติดตามประกาศจากทางโรงเรียน</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Schedule Table -->
<div class="row g-4">
    <?php
    $grouped_schedules = [];
    if (!empty($schedules)) {
        foreach ($schedules as $schedule) {
            $level = $schedule->schedule_level;
            if (!isset($grouped_schedules[$level])) {
                $grouped_schedules[$level] = [];
            }
            $grouped_schedules[$level][] = $schedule;
        }
    }
    ?>

    <?php if (!empty($grouped_schedules)): ?>
        <?php foreach ($grouped_schedules as $level => $level_schedules): ?>
            <div class="col-md-12">
                <div class="schedule-card h-100">
                    <div class="schedule-header">
                        <h5 class="mb-0 fw-bold"><i class='bx bx-calendar-event me-2'></i> กำหนดการ: <?= $level ?></h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th width="25%"><i class='bx bx-bookmark me-2'></i> รอบการรับสมัคร</th>
                                    <th width="20%"><i class='bx bx-edit me-2'></i> รับสมัคร</th>
                                    <th width="15%"><i class='bx bx-pencil me-2'></i> สอบ</th>
                                    <th width="20%"><i class='bx bx-broadcast me-2'></i> ประกาศผล</th>
                                    <th width="20%"><i class='bx bx-id-card me-2'></i> รายงานตัว</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($level_schedules as $schedule): ?>
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="fw-bold text-primary"><?= $schedule->schedule_round ?></div>
                                        </td>
                                        
                                        <!-- Recruit -->
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_recruit_start)) ?> - 
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_recruit_end)) ?>
                                                </span>
                                                <small class="text-muted" style="font-size: 0.75rem;">Online</small>
                                            </div>
                                        </td>

                                        <!-- Exam -->
                                        <td class="align-middle text-center">
                                            <?php if($schedule->schedule_exam): ?>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_exam)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Announce -->
                                        <td class="align-middle text-center">
                                            <?php if($schedule->schedule_announce): ?>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_announce)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Report -->
                                        <td class="align-middle text-center">
                                            <?php if($schedule->schedule_report): ?>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_report)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="schedule-card">
                <div class="schedule-header">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-calendar-event me-2'></i> กำหนดการรับสมัคร</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class='bx bx-calendar-x fs-1 text-muted mb-3'></i>
                                        <h5 class="fw-bold text-secondary">ยังไม่มีกำหนดการ</h5>
                                        <p class="text-muted mb-0">กรุณาติดตามประกาศจากทางโรงเรียนในภายหลัง</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- PDPA Modal -->
<div class="modal fade" id="pdpaModal" tabindex="-1" aria-labelledby="pdpaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pdpaModalLabel">ข้อตกลงและเงื่อนไขการใช้ข้อมูลส่วนบุคคล</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ข้อตกลงการใช้ข้อมูลส่วนบุคคลในการลงทะเบียนและสมัครเข้าศึกษาต่อในระบบรับสมัครออนไลน์ของโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</strong></p>
        <p>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ ("โรงเรียน") ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของผู้สมัคร ("ท่าน") โรงเรียนจึงได้จัดทำข้อตกลงและเงื่อนไขการใช้ข้อมูลส่วนบุคคลฉบับนี้ขึ้น เพื่อแจ้งให้ท่านทราบถึงวิธีการที่โรงเรียนเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูลส่วนบุคคลของท่าน และสิทธิของท่านในฐานะเจ้าของข้อมูลส่วนบุคคล</p>
        
        <h6>1. ข้อมูลส่วนบุคคลที่เก็บรวบรวม</h6>
        <p>โรงเรียนจะเก็บรวบรวมข้อมูลส่วนบุคคลของท่านที่จำเป็นต่อการรับสมัครและการพิจารณาคัดเลือกเข้าศึกษาต่อ ซึ่งรวมถึงแต่ไม่จำกัดเพียง:</p>
        <ul>
            <li>ข้อมูลระบุตัวตน เช่น ชื่อ-นามสกุล, เลขประจำตัวประชาชน, วันเดือนปีเกิด</li>
            <li>ข้อมูลการติดต่อ เช่น ที่อยู่, หมายเลขโทรศัพท์, อีเมล</li>
            <li>ข้อมูลการศึกษา เช่น ประวัติการศึกษา, ผลการเรียน</li>
            <li>ข้อมูลผู้ปกครอง</li>
            <li>ข้อมูลอื่นๆ ที่ท่านให้ไว้ในใบสมัคร</li>
        </ul>

        <h6>2. วัตถุประสงค์ในการเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูล</h6>
        <p>โรงเรียนจะใช้ข้อมูลส่วนบุคคลของท่านเพื่อวัตถุประสงค์ดังต่อไปนี้:</p>
        <ul>
            <li>เพื่อดำเนินการตามกระบวนการรับสมัคร และตรวจสอบคุณสมบัติของผู้สมัคร</li>
            <li>เพื่อใช้ในการติดต่อสื่อสารกับท่านและผู้ปกครองเกี่ยวกับการสมัคร</li>
            <li>เพื่อใช้ในการพิจารณาคัดเลือกนักเรียนเข้าศึกษาต่อ</li>
            <li>เพื่อจัดทำทะเบียนนักเรียน และใช้ในกิจกรรมที่เกี่ยวข้องกับการศึกษาของโรงเรียน (กรณีที่ท่านผ่านการคัดเลือก)</li>
            <li>เพื่อปฏิบัติตามกฎหมายและข้อบังคับที่เกี่ยวข้อง</li>
        </ul>

        <h6>3. การเปิดเผยข้อมูลส่วนบุคคล</h6>
        <p>โรงเรียนจะไม่เปิดเผยข้อมูลส่วนบุคคลของท่านแก่บุคคลภายนอกโดยไม่ได้รับความยินยอมจากท่าน เว้นแต่ในกรณีที่มีกฎหมายกำหนดให้สามารถกระทำได้</p>

        <h6>4. ระยะเวลาในการเก็บรักษาข้อมูล</h6>
        <p>โรงเรียนจะเก็บรักษาข้อมูลส่วนบุคคลของท่านไว้เป็นระยะเวลาเท่าที่จำเป็นเพื่อบรรลุวัตถุประสงค์ที่ได้แจ้งไว้ และตามที่กฏหมายกำหนด</p>

        <h6>5. สิทธิของเจ้าของข้อมูล</h6>
        <p>ท่านมีสิทธิตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 ซึ่งรวมถึงสิทธิในการขอเข้าถึง ขอแก้ไข ขอให้ลบ หรือจำกัดการใช้ข้อมูลส่วนบุคคลของท่าน</p>

        <p class="mt-4">การที่ท่านกดปุ่ม "ยอมรับ" และดำเนินการสมัครต่อไป ถือว่าท่านได้อ่านและเข้าใจข้อความข้างต้นโดยละเอียด และยินยอมให้โรงเรียนเก็บรวบรวม ใช้ และเปิดเผยข้อมูลส่วนบุคคลของท่านตามวัตถุประสงค์ที่ระบุไว้ในข้อตกลงนี้ทุกประการ</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ไม่ยอมรับ</button>
        <button type="button" class="btn btn-primary" id="pdpa-accept-btn">ยอมรับและดำเนินการต่อ</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdowns = document.querySelectorAll('.countdown-container');

    countdowns.forEach(timer => {
        const targetDateStr = timer.dataset.target;
        if (!targetDateStr) return;

        const targetDate = new Date(targetDateStr.replace(' ', 'T')).getTime();
        
        const daysEl = timer.querySelector('.days');
        const hoursEl = timer.querySelector('.hours');
        const minutesEl = timer.querySelector('.minutes');
        const secondsEl = timer.querySelector('.seconds');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                daysEl.textContent = '0';
                hoursEl.textContent = '0';
                minutesEl.textContent = '0';
                secondsEl.textContent = '0';
                setTimeout(() => location.reload(), 1000);
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            daysEl.textContent = days;
            hoursEl.textContent = hours.toString().padStart(2, '0');
            minutesEl.textContent = minutes.toString().padStart(2, '0');
            secondsEl.textContent = seconds.toString().padStart(2, '0');
        }

        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
    });

    // PDPA Modal
    const applyButtons = document.querySelectorAll('.apply-btn');
    const pdpaModal = new bootstrap.Modal(document.getElementById('pdpaModal'));
    let selectedHref = '';

    applyButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            selectedHref = this.dataset.href;
            pdpaModal.show();
        });
    });

    document.getElementById('pdpa-accept-btn').addEventListener('click', function() {
        if (selectedHref) {
            window.location.href = selectedHref;
        }
    });
});
</script>
<?= $this->endSection() ?>

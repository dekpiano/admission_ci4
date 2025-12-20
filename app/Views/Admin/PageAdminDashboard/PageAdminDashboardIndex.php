<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<style>
    /* Color Variables - Green Theme (Same as Surrender page) */
    :root {
        --primary-color: #28a745;
        --primary-dark: #1e7e34;
        --primary-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        --primary-light: rgba(40, 167, 69, 0.15);
    }

    /* Welcome Banner - Green Gradient */
    .welcome-card {
        background: var(--primary-gradient);
        border-radius: 16px;
        border: none;
        overflow: hidden;
        position: relative;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.35);
    }
    
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 350px;
        height: 350px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-label {
        color: #697a8d;
        font-size: 0.9rem;
    }

    /* Year Selector - Green Theme */
    .year-selector-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.2);
        padding: 10px 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    .year-selector-label {
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .year-selector {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 700;
        font-size: 1rem;
        background: white;
        color: var(--primary-color);
        cursor: pointer;
    }
    
    .year-selector:focus {
        outline: none;
    }

    /* Level Item */
    .level-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Progress Bar */
    .progress-slim {
        height: 6px;
        border-radius: 3px;
        background: #e9ecef;
    }

    /* Text Primary Override */
    .text-primary {
        color: var(--primary-color) !important;
    }
</style>

<!-- Page Header with Year Selector in Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card welcome-card text-white p-4">
            <div class="row align-items-center position-relative" style="z-index: 1;">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-2">
                        <i class="bx bx-home-circle me-2"></i>
                        แดชบอร์ด
                    </h4>
                    <p class="mb-0 opacity-75">ยินดีต้อนรับเข้าสู่ระบบจัดการข้อมูลผู้สมัครเรียน ปีการศึกษา <?= $selected_year ?></p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <form method="get" action="<?= site_url('skjadmin/dashboard') ?>" id="yearForm">
                        <div class="year-selector-wrapper d-inline-flex">
                            <label class="year-selector-label">
                                <i class="bx bx-calendar"></i>
                                ปีการศึกษา
                            </label>
                            <select name="year" class="form-select year-selector" onchange="window.location.href='<?= site_url('skjadmin/dashboard') ?>/' + this.value">
                                <?php foreach ($years as $y) : ?>
                                    <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selected_year ? 'selected' : '' ?>><?= $y->recruit_year ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <!-- Total -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <i class="bx bx-user-plus"></i>
                    </div>
                    <div>
                        <div class="stat-value text-success"><?= number_format($stats->StuALL) ?></div>
                        <div class="stat-label">ผู้สมัครทั้งหมด</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Passed -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: rgba(113, 221, 55, 0.15); color: #71dd37;">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color: #71dd37;"><?= number_format($stats->Pass) ?></div>
                        <div class="stat-label">ผ่านการตรวจสอบ</div>
                    </div>
                </div>
                <div class="progress progress-slim">
                    <div class="progress-bar" style="width: <?= $stats->StuALL > 0 ? ($stats->Pass / $stats->StuALL) * 100 : 0 ?>%; background: #71dd37;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: rgba(255, 171, 0, 0.15); color: #ffab00;">
                        <i class="bx bx-time-five"></i>
                    </div>
                    <div>
                        <div class="stat-value text-warning"><?= number_format($stats->NoPass) ?></div>
                        <div class="stat-label">รอตรวจสอบ/แก้ไข</div>
                    </div>
                </div>
                <div class="progress progress-slim">
                    <div class="progress-bar bg-warning" style="width: <?= $stats->StuALL > 0 ? ($stats->NoPass / $stats->StuALL) * 100 : 0 ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Levels -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: rgba(3, 195, 236, 0.15); color: #03c3ec;">
                        <i class="bx bx-bar-chart-alt-2"></i>
                    </div>
                    <div>
                        <div class="stat-label fw-semibold">แยกตามระดับชั้น</div>
                    </div>
                </div>
                
                <div class="level-item">
                    <span class="fw-medium">
                        <i class="bx bxs-circle text-primary me-1" style="font-size: 8px;"></i> ม.1
                    </span>
                    <span class="fw-bold text-primary"><?= number_format($stats->NumAllM1) ?></span>
                </div>
                
                <div class="level-item mb-0">
                    <span class="fw-medium">
                        <i class="bx bxs-circle text-info me-1" style="font-size: 8px;"></i> ม.4
                    </span>
                    <span class="fw-bold text-info"><?= number_format($stats->NumAllM4) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Info -->
<div class="row">
    <div class="col-12 text-center">
        <small class="text-muted">
            <i class="bx bx-refresh me-1"></i> อัปเดตล่าสุด: <?= date('d/m/Y H:i') ?> น.
        </small>
    </div>
</div>

<?= $this->endSection() ?>

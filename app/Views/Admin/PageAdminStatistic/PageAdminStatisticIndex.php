<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/libs/apex-charts/apex-charts.css') ?>" />
<style>
    :root {
        --primary-rose: #ff6b8b;
        --primary-rose-dark: #e11d48;
        --primary-sky: #56ccf2;
        --primary-sky-dark: #0284c7;
        --primary-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
        --rose-gradient: linear-gradient(135deg, #ff6b8b 0%, #e11d48 100%);
        --sky-gradient: linear-gradient(135deg, #56ccf2 0%, #0284c7 100%);
    }

    body, .card, table, h1, h2, h3, h4, h5, h6, .btn, .nav-link, .badge {
        font-family: 'K2D', sans-serif;
    }

    i.bx, i.bxs, i.bxl, .bx, .bxs, .bxl, [class^="bx-"], [class*=" bx-"], .bx-fw {
        font-family: 'boxicons' !important;
        font-style: normal;
    }

    /* Stats Hero Cards */
    .stat-hero-card {
        border-radius: 16px;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        background: #ffffff;
        position: relative;
    }

    .stat-hero-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08) !important;
    }

    .stat-hero-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    .stat-hero-val {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
    }

    /* Course Room Card */
    .course-stat-card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 1.25rem;
        transition: all 0.25s ease;
        height: 100%;
    }

    .course-stat-card:hover {
        border-color: var(--primary-rose);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 107, 139, 0.12);
    }

    /* Custom Modern Nav Tabs */
    .nav-tabs-modern {
        border-bottom: 2px solid #f1f5f9;
        gap: 8px;
    }

    .nav-tabs-modern .nav-link {
        border: none;
        border-radius: 12px 12px 0 0;
        color: #64748b;
        font-weight: 700;
        padding: 0.85rem 1.4rem;
        transition: all 0.2s ease;
        background: transparent;
    }

    .nav-tabs-modern .nav-link:hover {
        color: var(--primary-rose-dark);
        background: rgba(255, 107, 139, 0.05);
    }

    .nav-tabs-modern .nav-link.active {
        color: #ffffff !important;
        background: var(--primary-gradient) !important;
        box-shadow: 0 4px 14px rgba(255, 107, 139, 0.3);
    }

    /* Pivot Table Vertical Headers */
    .th-vertical {
        vertical-align: bottom !important;
        padding: 12px 4px !important;
        height: 160px;
        min-width: 38px !important;
    }

    .th-vertical .vertical-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        display: inline-block;
        text-align: left;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
    }

    /* Filter Select styling */
    .filter-select {
        border-radius: 10px;
        padding: 6px 12px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background-color: rgba(255, 255, 255, 0.9);
        color: #1e293b;
        cursor: pointer;
        backdrop-filter: blur(4px);
    }

    .filter-select:focus {
        background-color: #ffffff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.4);
    }

    /* Sticky Pivot Table Container */
    .pivot-scroll-container {
        max-height: 520px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        position: relative;
        background: #ffffff;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .pivot-scroll-container::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }
    .pivot-scroll-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .pivot-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Fixed Sticky Table Header & Footer */
    .table-sticky-pivot {
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    .table-sticky-pivot thead th {
        position: sticky;
        top: 0;
        z-index: 15;
        background: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%) !important;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
        border: none !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.4) !important;
    }

    .table-sticky-pivot tfoot td {
        position: sticky;
        bottom: 0;
        z-index: 15;
        background: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%) !important;
        box-shadow: 0 -3px 6px rgba(0, 0, 0, 0.08);
        border: none !important;
        border-top: 2px solid rgba(255, 255, 255, 0.4) !important;
    }

    /* Sticky First Column (วันที่สมัคร) */
    .table-sticky-pivot th:first-child,
    .table-sticky-pivot td:first-child {
        position: sticky;
        left: 0;
        z-index: 10;
        background-color: #f8fafc;
        border-right: 2px solid #e2e8f0 !important;
    }

    .table-sticky-pivot thead th:first-child {
        z-index: 25;
        background: #ff6b8b !important;
    }

    .table-sticky-pivot tfoot td:first-child {
        z-index: 25;
        background: #ff6b8b !important;
    }

    /* Sticky Last Column (รวม) */
    .table-sticky-pivot th:last-child,
    .table-sticky-pivot td:last-child {
        position: sticky;
        right: 0;
        z-index: 10;
        background-color: #fff1f2;
        border-left: 2px solid #fecdd3 !important;
    }

    .table-sticky-pivot thead th:last-child {
        z-index: 25;
        background: #e11d48 !important;
    }

    .table-sticky-pivot tfoot td:last-child {
        z-index: 25;
        background: #e11d48 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Banner with Interactive Filter -->
<div class="card border-0 rounded-4 shadow-sm mb-4 text-white overflow-hidden" 
    style="background: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);">
    <div class="card-body p-4 position-relative">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-white bg-opacity-25 rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                        <i class='bx bxs-bar-chart-alt-2 fs-2 text-white'></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-white mb-1">
                            สรุปสถิติการรับสมัครนักเรียน ปีการศึกษา <?= esc($selectedYear) ?>
                        </h4>
                        <p class="mb-0 text-white text-opacity-90 small">
                            รายงานสถิติจำนวนผู้สมัครแยกตามระดับชั้น สายการเรียน และสถานะการตรวจสอบเอกสาร
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <form method="GET" action="<?= site_url('skjadmin/statistics') ?>" class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
                    <!-- Year Select -->
                    <select name="year" class="filter-select shadow-sm" onchange="this.form.submit()" title="เลือกปีการศึกษา">
                        <?php foreach ($years as $y): ?>
                            <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selectedYear ? 'selected' : '' ?>>
                                ปี <?= $y->recruit_year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Round Select -->
                    <?php if (!empty($rounds)): ?>
                        <select name="round" class="filter-select shadow-sm" onchange="this.form.submit()" title="เลือกรอบการรับสมัคร">
                            <option value="">ทุกรอบ</option>
                            <?php foreach ($rounds as $r): ?>
                                <option value="<?= $r->recruit_round ?>" <?= $selectedRound == $r->recruit_round ? 'selected' : '' ?>>
                                    รอบ <?= $r->recruit_round ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <!-- Date Select -->
                    <?php if (!empty($dates)): ?>
                        <select name="date" class="filter-select shadow-sm" onchange="this.form.submit()" title="เลือกวันที่สมัคร">
                            <option value="">ทุกวันที่</option>
                            <?php foreach ($dates as $d): ?>
                                <option value="<?= $d->recruit_date ?>" <?= $selectedDate == $d->recruit_date ? 'selected' : '' ?>>
                                    <?= $datethai->thai_date_short(strtotime($d->recruit_date)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <!-- Reset Button -->
                    <a href="<?= site_url('skjadmin/statistics') ?>" class="btn btn-light btn-icon rounded-3 shadow-sm d-flex align-items-center justify-content-center" title="รีเซ็ตตัวกรอง">
                        <i class='bx bx-refresh text-primary fs-4'></i>
                    </a>
                </form>
            </div>
        </div>

        <!-- Decorative background shapes -->
        <div class="position-absolute end-0 top-0 mt-n4 me-n4 opacity-25" 
            style="width: 220px; height: 220px; border-radius: 50%; border: 35px solid white; z-index: 0; pointer-events: none;"></div>
    </div>
</div>

<!-- 4 Key Hero Stats Cards -->
<?php
$m1_data = ['total' => 0, 'male' => 0, 'female' => 0];
$m4_data = ['total' => 0, 'male' => 0, 'female' => 0];
foreach ($stats['total_by_level'] as $l) {
    if ($l->recruit_regLevel == 1) $m1_data = ['total' => $l->total, 'male' => $l->male, 'female' => $l->female];
    if ($l->recruit_regLevel == 4) $m4_data = ['total' => $l->total, 'male' => $l->male, 'female' => $l->female];
}
$verified = 0;
foreach ($statusByLevel as $s) {
    if ($s->recruit_status == 'ผ่านการตรวจสอบ') $verified += $s->total;
}
$verifiedPercentage = ($stats['grand_total'] > 0) ? round(($verified / $stats['grand_total']) * 100) : 0;
?>

<div class="row g-3 mb-4">
    <!-- Total Applicants -->
    <div class="col-6 col-xl-3">
        <div class="card stat-hero-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">ผู้สมัครทั้งหมด</span>
                    <div class="stat-hero-icon" style="background: rgba(255, 107, 139, 0.15); color: #ff6b8b;">
                        <i class="bx bxs-group"></i>
                    </div>
                </div>
                <h3 class="stat-hero-val text-dark mb-1"><?= number_format($stats['grand_total']) ?></h3>
                <div class="small text-muted d-flex align-items-center gap-1">
                    <i class="bx bxs-check-circle text-success"></i>
                    <span>รวมทุกระดับชั้น</span>
                </div>
            </div>
        </div>
    </div>

    <!-- M.1 Applicants -->
    <div class="col-6 col-xl-3">
        <div class="card stat-hero-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">ระดับชั้น ม.1</span>
                    <div class="stat-hero-icon" style="background: rgba(86, 204, 242, 0.15); color: #0284c7;">
                        <i class="bx bxs-book-bookmark"></i>
                    </div>
                </div>
                <h3 class="stat-hero-val text-dark mb-2"><?= number_format($m1_data['total']) ?></h3>
                <div class="d-flex gap-2">
                    <span class="badge bg-label-info rounded-pill px-2 py-1 small">ชาย: <?= number_format($m1_data['male']) ?></span>
                    <span class="badge bg-label-danger rounded-pill px-2 py-1 small">หญิง: <?= number_format($m1_data['female']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- M.4 Applicants -->
    <div class="col-6 col-xl-3">
        <div class="card stat-hero-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">ระดับชั้น ม.4</span>
                    <div class="stat-hero-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <i class="bx bxs-graduation"></i>
                    </div>
                </div>
                <h3 class="stat-hero-val text-dark mb-2"><?= number_format($m4_data['total']) ?></h3>
                <div class="d-flex gap-2">
                    <span class="badge bg-label-info rounded-pill px-2 py-1 small">ชาย: <?= number_format($m4_data['male']) ?></span>
                    <span class="badge bg-label-danger rounded-pill px-2 py-1 small">หญิง: <?= number_format($m4_data['female']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Verified Status -->
    <div class="col-6 col-xl-3">
        <div class="card stat-hero-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">ผ่านการตรวจเอกสาร</span>
                    <div class="stat-hero-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                        <i class="bx bxs-check-shield"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <h3 class="stat-hero-val text-dark mb-0"><?= number_format($verified) ?></h3>
                    <span class="small fw-bold text-success">(<?= $verifiedPercentage ?>%)</span>
                </div>
                <div class="progress rounded-pill" style="height: 6px;">
                    <div class="progress-bar" style="background: var(--primary-gradient); width: <?= $verifiedPercentage ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <!-- Application Trend Chart -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm h-100 bg-white">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class='bx bx-line-chart text-primary fs-4'></i>
                        แนวโน้มการสมัครเรียนรายวัน
                    </h5>
                    <small class="text-muted">เปรียบเทียบจำนวนผู้สมัครระหว่าง ม.1 และ ม.4 ในแต่ละวัน</small>
                </div>
            </div>
            <div class="card-body p-4 pt-2">
                <div id="registrationTimeline" style="min-height: 330px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Status Distribution Donut Chart -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm h-100 bg-white">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class='bx bx-pie-chart-alt-2 text-primary fs-4'></i>
                    สัดส่วนสถานะการตรวจสอบ
                </h5>
                <small class="text-muted">สถานะเอกสารการสมัครทั้งหมด</small>
            </div>
            <div class="card-body p-4">
                <div id="statusChart" class="mx-auto" style="min-height: 230px;"></div>
                <div class="mt-3 border-top pt-3">
                    <?php foreach ($stats['total_by_status'] as $s): ?>
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <div class="d-flex align-items-center">
                                <span class="badge badge-dot bg-<?= $s->recruit_status == 'ผ่านการตรวจสอบ' ? 'success' : ($s->recruit_status == 'รอการตรวจสอบ' ? 'warning' : 'danger') ?> me-2"></span>
                                <span class="small text-secondary fw-semibold"><?= esc($s->recruit_status) ?></span>
                            </div>
                            <span class="fw-bold text-dark"><?= number_format($s->total) ?> <small class="text-muted fw-normal">คน</small></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Data Tabs Section -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden bg-white">
    <div class="card-header bg-white border-bottom pt-3 pb-0 px-4">
        <ul class="nav nav-tabs nav-tabs-modern" id="statsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="m1-tab" data-bs-toggle="tab" data-bs-target="#m1-pane" type="button" role="tab" aria-selected="true">
                    <i class='bx bx-book-bookmark me-1'></i>แผนการเรียน ม.1
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="m4-tab" data-bs-toggle="tab" data-bs-target="#m4-pane" type="button" role="tab" aria-selected="false">
                    <i class='bx bx-graduation me-1'></i>แผนการเรียน ม.4
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline-pane" type="button" role="tab" aria-selected="false">
                    <i class='bx bx-calendar-event me-1'></i>ตารางสรุปรายวัน
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content border-0 p-0" id="statsTabContent">
            <!-- M1 Courses Pane -->
            <div class="tab-pane fade show active" id="m1-pane" role="tabpanel" aria-labelledby="m1-tab">
                <div class="row g-3">
                    <?php
                    $m1_rooms = array_filter($stats['total_by_room'], function ($r) { return $r->recruit_regLevel == 1; });
                    if (empty($m1_rooms)): ?>
                        <div class="col-12 text-center py-5">
                            <i class='bx bx-folder-open fs-1 text-muted opacity-50 mb-2'></i>
                            <p class="text-muted mb-0">ยังไม่พบข้อมูลผู้สมัครในระดับชั้นนี้</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($m1_rooms as $room): ?>
                            <div class="col-md-6 col-xl-4">
                                <div class="course-stat-card shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0 text-dark"><?= esc($room->recruit_tpyeRoom) ?></h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold rounded-pill px-3 py-1 fs-6">
                                            <?= number_format($room->total) ?>
                                        </span>
                                    </div>
                                    <div class="row g-2 mb-3 mt-1">
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 bg-light text-center border">
                                                <div class="small text-muted"><i class='bx bx-male-sign text-info me-1'></i>ชาย</div>
                                                <div class="fw-bold text-dark"><?= number_format($room->male) ?></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 bg-light text-center border">
                                                <div class="small text-muted"><i class='bx bx-female-sign text-danger me-1'></i>หญิง</div>
                                                <div class="fw-bold text-dark"><?= number_format($room->female) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress rounded-pill overflow-hidden" style="height: 8px;">
                                        <?php
                                        $male_p = ($room->total > 0) ? ($room->male / $room->total) * 100 : 0;
                                        $female_p = ($room->total > 0) ? ($room->female / $room->total) * 100 : 0;
                                        ?>
                                        <div class="progress-bar bg-info" style="width: <?= $male_p ?>%" title="ชาย <?= round($male_p) ?>%"></div>
                                        <div class="progress-bar bg-danger" style="width: <?= $female_p ?>%" title="หญิง <?= round($female_p) ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- M4 Courses Pane -->
            <div class="tab-pane fade" id="m4-pane" role="tabpanel" aria-labelledby="m4-tab">
                <div class="row g-3">
                    <?php
                    $m4_rooms = array_filter($stats['total_by_room'], function ($r) { return $r->recruit_regLevel == 4; });
                    if (empty($m4_rooms)): ?>
                        <div class="col-12 text-center py-5">
                            <i class='bx bx-folder-open fs-1 text-muted opacity-50 mb-2'></i>
                            <p class="text-muted mb-0">ยังไม่พบข้อมูลผู้สมัครในระดับชั้นนี้</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($m4_rooms as $room): ?>
                            <div class="col-md-6 col-xl-4">
                                <div class="course-stat-card shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0 text-dark"><?= esc($room->recruit_tpyeRoom) ?></h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold rounded-pill px-3 py-1 fs-6">
                                            <?= number_format($room->total) ?>
                                        </span>
                                    </div>
                                    <div class="row g-2 mb-3 mt-1">
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 bg-light text-center border">
                                                <div class="small text-muted"><i class='bx bx-male-sign text-info me-1'></i>ชาย</div>
                                                <div class="fw-bold text-dark"><?= number_format($room->male) ?></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded-3 bg-light text-center border">
                                                <div class="small text-muted"><i class='bx bx-female-sign text-danger me-1'></i>หญิง</div>
                                                <div class="fw-bold text-dark"><?= number_format($room->female) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress rounded-pill overflow-hidden" style="height: 8px;">
                                        <?php
                                        $male_p = ($room->total > 0) ? ($room->male / $room->total) * 100 : 0;
                                        $female_p = ($room->total > 0) ? ($room->female / $room->total) * 100 : 0;
                                        ?>
                                        <div class="progress-bar bg-info" style="width: <?= $male_p ?>%" title="ชาย <?= round($male_p) ?>%"></div>
                                        <div class="progress-bar bg-danger" style="width: <?= $female_p ?>%" title="หญิง <?= round($female_p) ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Timeline Table Pane -->
            <div class="tab-pane fade" id="timeline-pane" role="tabpanel" aria-labelledby="timeline-tab">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>วันที่สมัคร</th>
                                <th class="text-center">ม.1</th>
                                <th class="text-center">ม.4</th>
                                <th class="text-center">ชาย</th>
                                <th class="text-center">หญิง</th>
                                <th class="text-center">รวม (คน)</th>
                                <th style="width: 20%">สัดส่วน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dailyStats)): ?>
                                <tr><td colspan="7" class="text-center py-5 text-muted">ไม่พบข้อมูลสถิติรายวัน</td></tr>
                            <?php else: 
                                $max_daily = max(array_column($dailyStats, 'total'));
                                foreach (array_reverse($dailyStats) as $day): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary fs-tiny">
                                                    <i class='bx bx-calendar'></i>
                                                </span>
                                            </div>
                                            <span class="fw-bold text-dark"><?= $datethai->thai_date_short(strtotime($day->date)) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-semibold"><?= number_format($day->m1) ?></td>
                                    <td class="text-center fw-semibold"><?= number_format($day->m4) ?></td>
                                    <td class="text-center text-info fw-semibold"><?= number_format($day->male) ?></td>
                                    <td class="text-center text-danger fw-semibold"><?= number_format($day->female) ?></td>
                                    <td class="text-center fw-bold fs-6 text-primary"><?= number_format($day->total) ?></td>
                                    <td>
                                        <div class="progress rounded-pill" style="height: 7px;">
                                            <div class="progress-bar" style="background: var(--primary-gradient); width: <?= ($day->total / $max_daily) * 100 ?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Special Pivot Table: สาขาวิชาและความเป็นเลิศ -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden bg-white">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-3" style="background: rgba(255, 107, 139, 0.15); color: #e11d48;">
                <i class="bx bx-table fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">ตารางสรุปแผนการสมัครแยกตามสาขาวิชาและความเป็นเลิศ</h5>
                <small class="text-muted">สรุปจำนวนผู้สมัครรายวันแยกตามประเภทและสายการเรียน</small>
            </div>
        </div>
    </div>
    
    <div class="card-body p-4">
        <?php
        // 1. Data Preparation
        $headerRows = []; // [level => [branch_names]]
        $dailyPivot = []; // [date => [level => [branch => total]]]
        $totalsByCat = []; // [level => [branch => total]]
        $availableLevels = []; // List of levels found in data

        // Function to normalize level string to integer
        $normalizeLevel = function($l_str) {
            if (is_numeric($l_str)) return (int)$l_str;
            if (preg_match('/\d+/', $l_str, $matches)) return (int)$matches[0];
            if (strpos($l_str, 'ต้น') !== false) return 1;
            if (strpos($l_str, 'ปลาย') !== false) return 4;
            return 0;
        };

        // 2. Build headers from allCourses
        foreach ($allCourses as $c) {
            $level_num = $normalizeLevel($c->course_gradelevel ?? '');
            if ($level_num > 0) {
                if (!isset($headerRows[$level_num])) $headerRows[$level_num] = [];
                $branch = $c->course_branch ?: 'ทั่วไป/อื่นๆ';
                if (!in_array($branch, $headerRows[$level_num])) $headerRows[$level_num][] = $branch;
            }
        }

        // 3. Process registration data
        foreach ($dailyExcellenceStats as $row) {
            $date = $row->date;
            $level = (int)$row->recruit_regLevel;
            $branch = $row->course_branch ?: 'ทั่วไป/อื่นๆ';
            
            if (!in_array($level, $availableLevels)) $availableLevels[] = $level;
            
            if (!isset($headerRows[$level])) $headerRows[$level] = [];
            if (!in_array($branch, $headerRows[$level])) $headerRows[$level][] = $branch;

            if (!isset($dailyPivot[$date])) $dailyPivot[$date] = [];
            if (!isset($dailyPivot[$date][$level])) $dailyPivot[$date][$level] = [];
            $dailyPivot[$date][$level][$branch] = ($dailyPivot[$date][$level][$branch] ?? 0) + $row->total;
            $totalsByCat[$level][$branch] = ($totalsByCat[$level][$branch] ?? 0) + $row->total;
        }
        sort($availableLevels);
        krsort($dailyPivot);
        ?>

        <!-- Dynamic Tab Navigation for Pivot Table -->
        <ul class="nav nav-tabs nav-tabs-modern mb-4" id="pivotTabs" role="tablist">
            <?php foreach ($availableLevels as $index => $l): ?>
            <li class="nav-item">
                <button class="nav-link <?= $index === 0 ? 'active' : '' ?> fw-bold" id="pivot-m<?= $l ?>-tab" data-bs-toggle="tab" data-bs-target="#pivot-m<?= $l ?>-pane" type="button" role="tab">
                    <i class='bx bx-award me-1'></i>ระดับชั้น มัธยมศึกษาปีที่ <?= $l ?>
                </button>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content border-0 p-0" id="pivotTabContent">
            <?php foreach ($availableLevels as $index => $currentL): ?>
            <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="pivot-m<?= $currentL ?>-pane" role="tabpanel">
                <div class="pivot-scroll-container">
                    <table class="table table-bordered text-center align-middle table-sticky-pivot">
                        <thead class="text-white fw-bold">
                            <tr>
                                <th style="width: 150px; min-width: 130px; vertical-align: middle;" class="text-white">วันที่สมัคร</th>
                                <?php 
                                $branches = $headerRows[$currentL] ?? [];
                                foreach ($branches as $branch): ?>
                                    <th class="th-vertical text-white">
                                        <span class="vertical-text text-white"><?= esc($branch) ?></span>
                                    </th>
                                <?php endforeach; ?>
                                <th style="width: 85px; min-width: 85px; vertical-align: middle;" class="text-white border-0">รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $levelGrandTotal = 0;
                            $levelHasData = false;
                            foreach ($dailyPivot as $date => $levelData): 
                                if (!isset($levelData[$currentL])) continue;
                                $levelHasData = true;
                                $daySum = 0;
                            ?>
                                <tr>
                                    <td class="bg-light fw-bold">
                                        <?= $datethai->thai_date_short(strtotime($date)) ?><br>
                                        <small class="text-muted">(<?= date('Y', strtotime($date)) + 543 ?>)</small>
                                    </td>
                                    <?php 
                                    foreach ($branches as $branch): 
                                        $val = $levelData[$currentL][$branch] ?? 0;
                                        $daySum += $val;
                                        ?>
                                        <td class="<?= $val > 0 ? 'fw-bold text-dark' : 'text-muted opacity-25' ?>">
                                            <?= $val > 0 ? number_format($val) : '-' ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="fw-bold" style="background: rgba(225, 29, 72, 0.08); color: #e11d48;"><?= number_format($daySum) ?></td>
                                </tr>
                            <?php $levelGrandTotal += $daySum; endforeach; ?>
                            
                            <?php if (!$levelHasData): ?>
                                <tr><td colspan="<?= count($branches) + 2 ?>" class="py-5 text-muted">ไม่พบข้อมูลสถิติของชั้น ม.<?= $currentL ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="fw-bold">
                            <tr class="text-white">
                                <td class="text-white">รวม ม.<?= $currentL ?></td>
                                <?php 
                                foreach ($branches as $branch): 
                                    $val = $totalsByCat[$currentL][$branch] ?? 0;
                                    ?>
                                    <td class="text-white"><?= number_format($val) ?></td>
                                <?php endforeach; ?>
                                <td class="text-white border-0 fs-6"><?= number_format($levelGrandTotal) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('public/sneat-assets/vendor/libs/apex-charts/apexcharts.js') ?>"></script>
<script>
$(function() {
    const chartColors = {
        primary: '#ff6b8b',
        sky: '#56ccf2',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        gray: '#94a3b8'
    };

    // 1. Application Trend Chart
    const dailyStats = <?= json_encode($dailyStats) ?>;
    const timelineOptions = {
        series: [
            {
                name: 'มัธยมศึกษาปีที่ 1',
                data: dailyStats.map(d => parseInt(d.m1))
            },
            {
                name: 'มัธยมศึกษาปีที่ 4',
                data: dailyStats.map(d => parseInt(d.m4))
            }
        ],
        chart: {
            height: 330,
            type: 'area',
            toolbar: { show: false },
            fontFamily: 'K2D, sans-serif'
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#ff6b8b', '#56ccf2'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 95, 100]
            }
        },
        xaxis: {
            categories: dailyStats.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString('th-TH', { day: 'numeric', month: 'short' });
            }),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: chartColors.gray, fontFamily: 'K2D' } }
        },
        yaxis: { 
            labels: { 
                style: { colors: chartColors.gray, fontFamily: 'K2D' },
                formatter: (val) => Math.floor(val)
            } 
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
            padding: { bottom: -10 }
        },
        legend: { 
            position: 'top', 
            horizontalAlign: 'right',
            fontFamily: 'K2D'
        }
    };
    new ApexCharts(document.querySelector("#registrationTimeline"), timelineOptions).render();

    // 2. Status Donut Chart
    const statusData = <?= json_encode($stats['total_by_status']) ?>;
    const statusColors = {
        'ผ่านการตรวจสอบ': chartColors.success,
        'รอการตรวจสอบ': chartColors.warning,
        'ไม่ผ่านการตรวจสอบ': chartColors.danger
    };
    
    const statusOptions = {
        series: statusData.map(s => parseInt(s.total)),
        labels: statusData.map(s => s.recruit_status),
        chart: {
            type: 'donut',
            height: 240,
            fontFamily: 'K2D, sans-serif'
        },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'รวมทั้งหมด',
                            fontSize: '14px',
                            fontFamily: 'K2D',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString() + ' คน';
                            }
                        }
                    }
                }
            }
        },
        legend: { show: false },
        colors: statusData.map(s => statusColors[s.recruit_status] || chartColors.gray),
        responsive: [{
            breakpoint: 480,
            options: { chart: { height: 220 } }
        }]
    };
    new ApexCharts(document.querySelector("#statusChart"), statusOptions).render();
});
</script>
<?= $this->endSection() ?>
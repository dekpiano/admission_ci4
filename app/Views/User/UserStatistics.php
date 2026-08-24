<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    .stats-container {
        padding: 2rem 0;
    }

    :root {
        --skj-pink: #e11d48;
        --skj-blue: #0284c7;
        --skj-pink-dark: #be123c;
        --skj-blue-dark: #0369a1;
        --skj-gradient: linear-gradient(135deg, #e11d48 0%, #ff2d75 35%, #0284c7 80%, #0369a1 100%);
        --male-color: #0284c7;
        --female-color: #e11d48;
    }

    .stats-hero {
        background: var(--skj-gradient);
        border-radius: 24px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(225, 29, 72, 0.25);
        position: relative;
        overflow: hidden;
    }

    .stats-hero::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }

    .stats-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: #ffffff;
    }

    .stats-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.95);
    }

    /* Summary Cards */
    .card-stat {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    }

    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        border-color: var(--skj-pink);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.total { background: #fff1f2; color: #e11d48; }
    .stat-icon.m1 { background: #f0f9ff; color: #0284c7; }
    .stat-icon.verified { background: rgba(16, 185, 129, 0.12); color: #10b981; }

    .stat-label {
        font-weight: 800;
        color: #475569;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 1rem;
    }

    .gender-split {
        display: flex;
        gap: 0.5rem;
        width: 100%;
    }

    .gender-box {
        flex: 1;
        padding: 0.5rem;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .gender-box.male { background: #f0f9ff; color: var(--male-color); }
    .gender-box.female { background: #fff1f2; color: var(--female-color); }

    .gender-val { font-size: 1.1rem; font-weight: 800; }

    /* Main Stats Cards */
    .stats-card-main {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        height: 100%;
    }

    .stats-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #edf2f7;
        background: #fafafa;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stats-card-title {
        font-weight: 800;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0;
    }

    /* Program Table/Grid */
    .program-item {
        padding: 1.25rem;
        border-radius: 16px;
        background: #f8fafc;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .program-item:hover {
        background: white;
        border-color: var(--skj-blue);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .program-title {
        font-weight: 800;
        color: #2d3748;
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .program-total-badge {
        background: var(--skj-gradient);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .mini-gender-bar {
        height: 6px;
        display: flex;
        border-radius: 10px;
        overflow: hidden;
        background: #e2e8f0;
        margin: 10px 0;
    }

    .bg-male { background-color: var(--male-color); }
    .bg-female { background-color: var(--female-color); }

    .nav-skj {
        background: #f1f5f9;
        padding: 0.4rem;
        border-radius: 50px;
        display: inline-flex;
    }
    .nav-skj .nav-link {
        border-radius: 50px;
        padding: 0.6rem 2rem;
        font-weight: 700;
        color: #475569;
        border: none;
        transition: all 0.2s ease;
    }
    .nav-skj .nav-link.active {
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(225, 29, 72, 0.25);
    }

    /* Filter Bar */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 20px rgba(0,0,0,0.02);
    }

    .filter-label {
        font-weight: 700;
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-select-skj {
        border-radius: 12px;
        border: 2px solid #f1f5f9;
        padding: 0.6rem 1rem;
        font-weight: 600;
        color: #475569;
        transition: all 0.2s;
    }

    .form-select-skj:focus {
        border-color: var(--skj-pink);
        box-shadow: 0 0 0 4px rgba(255, 158, 181, 0.1);
    }

    .btn-filter {
        background: var(--skj-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 2rem;
        font-weight: 700;
        transition: all 0.3s;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 158, 181, 0.3);
        color: white;
    }

    .btn-reset {
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-weight: 700;
        transition: all 0.2s;
    }

    .btn-reset:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 158, 181, 0.1);
        color: var(--skj-pink-dark);
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .stats-hero { padding: 2rem 1.5rem; }
        .stats-title { font-size: 1.8rem; }
    }

    /* Pivot Table Specific Styles */
    .th-vertical {
        height: 160px;
        vertical-align: bottom !important;
        padding-bottom: 1.5rem !important;
        width: 35px !important;
        min-width: 35px !important;
        background: #f8fafc;
    }
    .vertical-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #475569;
    }
    .tab-skj-pill .nav-link {
        border-radius: 12px;
        margin-right: 8px;
        font-weight: 700;
        color: #64748b;
        background: #f1f5f9;
        border: none;
        padding: 0.6rem 1.5rem;
    }
    .tab-skj-pill .nav-link.active {
        background: var(--skj-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 158, 181, 0.3);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stats-container">
    <div class="container">
        <!-- Hero Section -->
        <div class="stats-hero">
            <h1 class="stats-title">สรุปสถิติการรับสมัคร</h1>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <p class="stats-subtitle mb-0">
                    <i class='bx bxs-school me-1'></i> สถิติจำนวนผู้สมัครแยกตามระดับชั้นและเพศ (หญิง/ชาย)
                    <span class="mx-2 opacity-50">|</span>
                    <i class='bx bx-calendar me-1'></i> ปีการศึกษา <?= $selectedYear ?>
                </p>
                <?php if ($selectedRound): ?>
                    <span class="badge bg-white text-pink rounded-pill py-2 px-3 text-dark">
                        <i class='bx bx-git-branch me-1'></i> รอบที่ <?= $selectedRound ?>
                    </span>
                <?php endif; ?>
                <?php if ($selectedCategory): ?>
                    <?php 
                    $catName = "ทั่วไป";
                    foreach($allQuotas as $q) { if($q->quota_key == $selectedCategory) { $catName = $q->quota_explain; break; } }
                    ?>
                    <span class="badge bg-white text-pink rounded-pill py-2 px-3 text-dark">
                        <i class='bx bx-category me-1'></i> <?= $catName ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="filter-card border-0 shadow-sm">
            <form action="<?= base_url('new-admission/statistics') ?>" method="GET" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-3">
                    <label class="filter-label"><i class='bx bx-calendar-star me-1'></i>ปีการศึกษา</label>
                    <select name="year" class="form-select form-select-skj" onchange="this.form.submit()">
                        <?php foreach($years as $y): ?>
                            <option value="<?= $y->recruit_year ?>" <?= $selectedYear == $y->recruit_year ? 'selected' : '' ?>>
                                ปีการศึกษา <?= $y->recruit_year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="filter-label"><i class='bx bx-git-branch me-1'></i>รอบการสมัคร</label>
                    <select name="round" class="form-select form-select-skj" onchange="this.form.submit()">
                        <option value="">ทุกรอบการสมัคร</option>
                        <?php foreach($rounds as $r): ?>
                            <?php if($r->recruit_round): ?>
                                <option value="<?= $r->recruit_round ?>" <?= $selectedRound == $r->recruit_round ? 'selected' : '' ?>>
                                    รอบที่ <?= $r->recruit_round ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="filter-label"><i class='bx bx-time-five me-1'></i>ระบุวันที่สมัคร</label>
                    <select name="date" class="form-select form-select-skj" onchange="this.form.submit()">
                        <option value="">ทุกวันที่</option>
                        <?php if(!empty($dates)): ?>
                            <?php foreach($dates as $d): ?>
                                <option value="<?= $d->recruit_date ?>" <?= $selectedDate == $d->recruit_date ? 'selected' : '' ?>>
                                    <?= $datethai->thai_date_short(strtotime($d->recruit_date)) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter flex-grow-1">
                            <i class='bx bx-search-alt me-2'></i> ค้นหา
                        </button>
                        <a href="<?= base_url('new-admission/statistics') ?>" class="btn btn-reset" title="คืนค่าเดิม">
                            <i class='bx bx-refresh fs-4'></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

       

        <!-- Summary Row: Dynamic by all Levels present in data -->
        <div class="row g-4 mb-5">
            <?php if (empty($stats['total_by_level'])): ?>
                <div class="col-12 text-center text-muted py-5">ยังไม่มีข้อมูลสถิติสำหรับเงื่อนไขที่ระบุ</div>
            <?php else: ?>
                <?php foreach ($stats['total_by_level'] as $l): ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="card-stat">
                            <div class="stat-header">
                                <div class="stat-icon" style="background: rgba(132, 210, 246, 0.15); color: var(--skj-blue-dark);">
                                    <i class='bx <?= ($l->recruit_regLevel == 1 || $l->recruit_regLevel == 4) ? "bxs-book-content" : "bx-book-open" ?>'></i>
                                </div>
                                <div class="stat-label">มัธยมศึกษาปีที่ <?= $l->recruit_regLevel ?> <?= ($l->recruit_regLevel <= 3) ? "(ม.ต้น)" : "(ม.ปลาย)" ?></div>
                            </div>
                            <div class="stat-number"><?= number_format($l->total) ?></div>
                            <div class="gender-split">
                                <div class="gender-box male">
                                    <i class='bx bx-male-sign mb-1'></i>
                                    <span>ชาย</span>
                                    <span class="gender-val"><?= number_format($l->male) ?></span>
                                </div>
                                <div class="gender-box female">
                                    <i class='bx bx-female-sign mb-1'></i>
                                    <span>หญิง</span>
                                    <span class="gender-val"><?= number_format($l->female) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Grand Total Card -->
            <div class="col-md-12 col-xl-4">
                <div class="card-stat" style="background: var(--skj-gradient); color: white;">
                    <div class="stat-header">
                        <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white;"><i class='bx bxs-user-detail'></i></div>
                        <div class="stat-label text-white">ยอดผู้สมัครรวมทุกระดับชั้น</div>
                    </div>
                    <div class="stat-number text-white"><?= number_format($stats['grand_total']) ?></div>
                    <?php
                    // Dynamic calculation for all levels to ensure accuracy
                    $total_male = array_sum(array_column($stats['total_by_level'], 'male'));
                    $total_female = array_sum(array_column($stats['total_by_level'], 'female'));
                    ?>
                    <div class="gender-split">
                        <div class="gender-box" style="background: rgba(255,255,255,0.15); color: white;">
                            <span>ชายทั้งหมด</span>
                            <span class="gender-val"><?= number_format($total_male) ?></span>
                        </div>
                        <div class="gender-box" style="background: rgba(255,255,255,0.15); color: white;">
                            <span>หญิงทั้งหมด</span>
                            <span class="gender-val"><?= number_format($total_female) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detailed Section -->
        <div class="row g-4 mb-5">
            <!-- Pivot Table Card (New) -->
            <div class="col-12">
                <div class="stats-card-main">
                    <div class="stats-card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="stats-card-title"><i class='bx bx-table'></i> สรุปแผนการสมัครแยกตามสาขาและความเป็นเลิศ</h5>
                            <small class="text-muted ms-4 d-none d-md-inline">วิเคราะห์จำนวนผู้สมัครสะสมรายวันแยกตามแผนการเรียน</small>
                        </div>
                        <ul class="nav nav-pills tab-skj-pill" id="pivotTabs" role="tablist">
                            <?php 
                            // 1. Data Preparation (Same as Admin)
                            $headerRows = [];
                            $dailyPivot = [];
                            $totalsByCat = [];
                            $availableLevels = [];

                            $normalizeLevel = function($l_str) {
                                if (is_numeric($l_str)) return (int)$l_str;
                                if (preg_match('/\d+/', $l_str, $matches)) return (int)$matches[0];
                                if (strpos($l_str, 'ต้น') !== false) return 1;
                                if (strpos($l_str, 'ปลาย') !== false) return 4;
                                return 0;
                            };

                            foreach ($allCourses as $c) {
                                $level_num = $normalizeLevel($c->course_gradelevel ?? '');
                                if ($level_num > 0) {
                                    if (!isset($headerRows[$level_num])) $headerRows[$level_num] = [];
                                    $branch = $c->course_branch ?: 'ทั่วไป/อื่นๆ';
                                    if (!in_array($branch, $headerRows[$level_num])) $headerRows[$level_num][] = $branch;
                                }
                            }

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

                            foreach ($availableLevels as $index => $l): ?>
                                <li class="nav-item">
                                    <button class="nav-link <?= $index === 0 ? 'active' : '' ?>" 
                                            data-bs-toggle="tab" data-bs-target="#pivot-m<?= $l ?>-pane" type="button">ม.<?= $l ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="p-4 pt-3">
                        <div class="tab-content">
                            <?php foreach ($availableLevels as $index => $currentL): ?>
                            <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="pivot-m<?= $currentL ?>-pane">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="bg-light text-secondary fw-bold">
                                            <tr>
                                                <th style="width: 150px; vertical-align: middle;">วันที่สมัคร</th>
                                                <?php 
                                                $branches = $headerRows[$currentL] ?? [];
                                                foreach ($branches as $branch): ?>
                                                    <th class="th-vertical">
                                                        <span class="vertical-text"><?= $branch ?></span>
                                                    </th>
                                                <?php endforeach; ?>
                                                <th style="background: var(--skj-pink); color: white; width: 65px; vertical-align: middle;" class="border-0">รวม</th>
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
                                                    <td class="bg-light fw-bold text-secondary">
                                                        <small><i class='bx bx-calendar me-1 opacity-50'></i><?= $datethai->thai_date_short(strtotime($date)) ?></small>
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
                                                    <td class="fw-bold fs-5" style="color: var(--skj-pink-dark); background: rgba(255, 158, 181, 0.05);"><?= number_format($daySum) ?></td>
                                                </tr>
                                            <?php $levelGrandTotal += $daySum; endforeach; ?>
                                        </tbody>
                                        <tfoot class="fw-bold">
                                            <tr style="background: var(--skj-gradient); color: white;">
                                                <td class="text-white">รวมสะสม ม.<?= $currentL ?></td>
                                                <?php 
                                                foreach ($branches as $branch): 
                                                    $val = $totalsByCat[$currentL][$branch] ?? 0;
                                                    ?>
                                                    <td class="text-white"><?= number_format($val) ?></td>
                                                <?php endforeach; ?>
                                                <td style="background: var(--skj-pink-dark); color: white;" class="border-0"><?= number_format($levelGrandTotal) ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Daily Log Table - Full Width or Wider for Many Columns -->
            <div class="col-12">
                <div class="stats-card-main">
                    <div class="stats-card-header">
                        <h5 class="stats-card-title"><i class='bx bx-history'></i> ความเคลื่อนไหวรายวัน (แยกตามประเภท)</h5>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0 text-center" style="font-size: 0.95rem; border-collapse: separate; border-spacing: 0;">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th class="text-start ps-4" style="background: #f8f9fa;">วันที่สมัคร</th>
                                    <th style="color: var(--skj-blue-dark); background: #f8f9fa;">ม.1 (คน)</th>
                                    <th style="color: #48bb78; background: #f8f9fa;">ม.4 (คน)</th>
                                    <th style="color: var(--male-color); background: #f8f9fa;"><i class='bx bx-male-sign'></i> ชาย</th>
                                    <th style="color: var(--female-color); background: #f8f9fa;"><i class='bx bx-female-sign'></i> หญิง</th>
                                    <th class="bg-light fw-bold text-dark" style="background: #f8f9fa !important;">รวม (คน)</th>
                                    <th class="pe-4" style="width: 15%; background: #f8f9fa;">สัดส่วน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($dailyStats)): ?>
                                    <tr><td colspan="7" class="text-center py-5">ยังไม่มีข้อมูล</td></tr>
                                <?php else: ?>
                                    <?php 
                                    $max_daily = max(array_column($dailyStats, 'total')); 
                                    foreach(array_reverse($dailyStats) as $day): 
                                    ?>
                                        <tr>
                                            <td class="text-start ps-4 fw-bold text-secondary">
                                                <i class='bx bx-calendar-event me-1 opacity-50'></i>
                                                <?= $datethai->thai_date_short(strtotime($day->date)) ?>
                                            </td>
                                            <td class="fw-bold"><?= number_format($day->m1) ?></td>
                                            <td class="fw-bold"><?= number_format($day->m4) ?></td>
                                            <td class="fw-bold text-primary"><?= number_format($day->male) ?></td>
                                            <td class="fw-bold" style="color: var(--female-color);"><?= number_format($day->female) ?></td>
                                            <td class="bg-light fw-bolder fs-5"><?= number_format($day->total) ?></td>
                                            <td class="pe-4">
                                                <div class="progress skj-progress" style="height: 8px;">
                                                    <div class="progress-bar skj-progress-bar" style="width: <?= ($day->total / $max_daily) * 100 ?>%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Program/Room Detail Section -->
            <div class="col-12">
                <div class="stats-card-main">
                    <div class="stats-card-header d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                        <h5 class="stats-card-title me-auto"><i class='bx bx-receipt'></i> จำนวนผู้สมัครแยกตามแผนงาน</h5>
                        <ul class="nav nav-skj" id="detailTab" role="tablist">
                            <?php foreach($stats['total_by_level'] as $index => $l): ?>
                                <li class="nav-item">
                                    <button class="nav-link <?= $index === 0 ? 'active' : '' ?>" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#m<?= $l->recruit_regLevel ?>-detail">
                                        ม.<?= $l->recruit_regLevel ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="p-4">
                        <div class="tab-content">
                            <?php foreach($stats['total_by_level'] as $index => $levelData): ?>
                                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="m<?= $levelData->recruit_regLevel ?>-detail">
                                    <?php
                                    $rooms = array_filter($stats['total_by_room'], function ($r) use ($levelData) {
                                        return $r->recruit_regLevel == $levelData->recruit_regLevel; 
                                    });
                                    ?>
                                    <?php if (empty($rooms)): ?>
                                            <div class="text-center py-5 text-muted">ยังไม่มีข้อมูลแผนงานสำหรับ ม.<?= $levelData->recruit_regLevel ?></div>
                                    <?php else: ?>
                                            <div class="row">
                                                <?php foreach ($rooms as $room): ?>
                                                        <div class="col-md-6">
                                                            <div class="program-item">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <div class="program-title pe-2"><?= $room->recruit_tpyeRoom ?></div>
                                                                    <span class="program-total-badge"><?= $room->total ?></span>
                                                                </div>
                                                                <div class="mini-gender-bar">
                                                                    <?php
                                                                    $male_p = ($room->total > 0) ? ($room->male / $room->total) * 100 : 0;
                                                                    $female_p = ($room->total > 0) ? ($room->female / $room->total) * 100 : 0;
                                                                    ?>
                                                                    <div class="bg-male" style="width: <?= $male_p ?>%"></div>
                                                                    <div class="bg-female" style="width: <?= $female_p ?>%"></div>
                                                                </div>
                                                                <div class="d-flex justify-content-between small fw-bold">
                                                                    <span class="text-primary"><i class='bx bx-male-sign'></i> ช: <?= $room->male ?></span>
                                                                    <span style="color: var(--female-color);"><i class='bx bx-female-sign'></i> ญ: <?= $room->female ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php endforeach; ?>
                                            </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="<?= base_url('new-admission') ?>" class="btn btn-outline-secondary rounded-pill px-5 border-2 fw-bold">
                <i class='bx bx-home-alt me-2'></i> กลับหน้าหลักระบบรับสมัคร
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const val = +counter.innerText.replace(/,/g, '');
            if(isNaN(val)) return;
            let start = 0;
            const step = val / 60;
            const update = () => {
                start += step;
                if(start < val) {
                    counter.innerText = Math.ceil(start).toLocaleString();
                    requestAnimationFrame(update);
                } else { counter.innerText = val.toLocaleString(); }
            };
            update();
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('public/sneat-assets/vendor/libs/apex-charts/apex-charts.css') ?>" />
<style>
    .card-stats {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none !important;
    }
    .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .avatar-gradient-primary {
        background: linear-gradient(135deg, #696cff 0%, #3f51b5 100%);
    }
    .avatar-gradient-info {
        background: linear-gradient(135deg, #03c3ec 0%, #00acc1 100%);
    }
    .avatar-gradient-success {
        background: linear-gradient(135deg, #71dd37 0%, #43a047 100%);
    }
    .avatar-gradient-warning {
        background: linear-gradient(135deg, #ffab00 0%, #fb8c00 100%);
    }
    .progress-thick {
        height: 10px;
        border-radius: 10px;
    }
    .table-v-middle td {
        vertical-align: middle;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        color: #a1acb8;
        padding: 1rem 1.5rem;
    }
    .nav-tabs-custom .nav-link.active {
        border-bottom-color: #696cff;
        color: #696cff;
        background: rgba(105, 108, 255, 0.05);
    }
    .room-card {
        border: 1px solid #e7eaf0;
        border-radius: 12px;
        padding: 1.25rem;
        background: #fff;
        height: 100%;
        transition: all 0.2s ease;
    }
    .room-card:hover {
        border-color: #696cff;
        background: #f8f9ff;
    }
    /* Vertical Header Styling */
    .th-vertical {
        vertical-align: bottom !important;
        padding: 15px 5px !important;
        height: 180px;
        min-width: 35px !important;
    }
    .th-vertical .vertical-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        display: inline-block;
        text-align: left;
        line-height: 1;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white shadow-none border-0 overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="text-white fw-bold mb-2">
                            <i class='bx bx-bar-chart-square me-2'></i>สถิติการรับสมัครปีการศึกษา <?= $selectedYear ?>
                        </h3>
                        <p class="mb-0 opacity-75">ข้อมูลสถิติจำนวนผู้สมัครแยกตามระดับชั้น และสายการเรียนแบบอัปเดตทันที</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <form method="GET" action="<?= site_url('skjadmin/statistics') ?>" class="d-flex flex-wrap align-items-center justify-content-md-end gap-2">
                            <select name="year" class="form-select w-px-120 bg-white border-0 shadow-sm" onchange="this.form.submit()">
                                <?php foreach ($years as $y): ?>
                                    <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selectedYear ? 'selected' : '' ?>>
                                        ปี <?= $y->recruit_year ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($rounds)): ?>
                            <select name="round" class="form-select w-px-120 bg-white border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">ทุกรอบ</option>
                                <?php foreach ($rounds as $r): ?>
                                    <option value="<?= $r->recruit_round ?>" <?= $selectedRound == $r->recruit_round ? 'selected' : '' ?>>
                                        รอบ <?= $r->recruit_round ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                            <?php if (!empty($dates)): ?>
                            <select name="date" class="form-select w-px-150 bg-white border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">ทุกวันที่</option>
                                <?php foreach ($dates as $d): ?>
                                    <option value="<?= $d->recruit_date ?>" <?= $selectedDate == $d->recruit_date ? 'selected' : '' ?>>
                                        <?= $datethai->thai_date_short(strtotime($d->recruit_date)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                            <a href="<?= site_url('skjadmin/statistics') ?>" class="btn btn-light btn-icon rounded-circle shadow-sm" title="รีเซ็ต">
                                <i class='bx bx-refresh text-primary'></i>
                            </a>
                        </form>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="position-absolute end-0 top-0 mt-n4 me-n4 opacity-25" style="width: 200px; height: 200px; border-radius: 50%; border: 30px solid white;z-index: -99;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
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
    ?>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stats h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-gradient-primary rounded p-2 me-3">
                        <i class="bx bx-group text-white fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small uppercase fw-bold">ผู้สมัครทั้งหมด</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($stats['grand_total']) ?></h2>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-primary me-1"><i class="bx bx-up-arrow-alt"></i> ยอดรวม</span>
                    <span class="text-muted small">รวมทุกสถานะ</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stats h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-gradient-info rounded p-2 me-3">
                        <i class="bx bx-book-content text-white fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small uppercase fw-bold">ระดับชั้น ม.1</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($m1_data['total']) ?></h2>
                    </div>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="badge bg-label-info">ชาย: <?= $m1_data['male'] ?></span>
                    <span class="badge bg-label-danger">หญิง: <?= $m1_data['female'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stats h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-gradient-success rounded p-2 me-3">
                        <i class="bx bx-graduation text-white fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small uppercase fw-bold">ระดับชั้น ม.4</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($m4_data['total']) ?></h2>
                    </div>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="badge bg-label-info">ชาย: <?= $m4_data['male'] ?></span>
                    <span class="badge bg-label-danger">หญิง: <?= $m4_data['female'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stats h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-gradient-warning rounded p-2 me-3">
                        <i class="bx bx-user-check text-white fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small uppercase fw-bold">ตรวจสอบแล้ว</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($verified) ?></h2>
                    </div>
                </div>
                <div class="progress progress-thick mt-2">
                    <div class="progress-bar bg-warning shadow-none" role="progressbar" 
                        style="width: <?= ($stats['grand_total'] > 0) ? round(($verified / $stats['grand_total']) * 100) : 0 ?>%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Application Trend Chart -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0 fw-bold"><i class='bx bx-line-chart me-2 text-primary'></i>แนวโน้มการสมัครรายวัน</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="javascript:void(0);">ดาวน์โหลดภาพ</a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div id="registrationTimeline"></div>
            </div>
        </div>
    </div>
    
    <!-- Status Distribution -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0 fw-bold"><i class='bx bx-pie-chart-alt-2 me-2 text-primary'></i>สถานะการสมัคร</h5>
            </div>
            <div class="card-body">
                <div id="statusChart" class="mx-auto" style="min-height: 250px;"></div>
                <div class="mt-4">
                    <?php foreach ($stats['total_by_status'] as $s): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge badge-dot bg-<?= $s->recruit_status == 'ผ่านการตรวจสอบ' ? 'success' : ($s->recruit_status == 'รอการตรวจสอบ' ? 'warning' : 'secondary') ?> me-2"></span>
                                <span class="text-muted small"><?= $s->recruit_status ?></span>
                            </div>
                            <span class="fw-bold fs-6"><?= number_format($s->total) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Data Tabs -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header p-0">
                <ul class="nav nav-tabs nav-tabs-custom nav-fill border-0" id="statsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="m1-tab" data-bs-toggle="tab" data-bs-target="#m1-pane" type="button" role="tab" aria-selected="true">
                            <i class='bx bx-book-bookmark me-2'></i>สถิติมัธยมศึกษาปีที่ 1
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="m4-tab" data-bs-toggle="tab" data-bs-target="#m4-pane" type="button" role="tab" aria-selected="false">
                            <i class='bx bx-book-reader me-2'></i>สถิติมัธยมศึกษาปีที่ 4
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline-pane" type="button" role="tab" aria-selected="false">
                            <i class='bx bx-list-ol me-2'></i>สถิตรายวัน (สรุป)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline-pane" type="button" role="tab" aria-selected="false">
                            <i class='bx bx-list-ol me-2'></i>สถิตรายวัน (สรุป)
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content border-0 p-0" id="statsTabContent">
                    <!-- M1 Pane -->
                    <div class="tab-pane fade show active" id="m1-pane" role="tabpanel" aria-labelledby="m1-tab">
                        <div class="row g-4">
                            <?php
                            $m1_rooms = array_filter($stats['total_by_room'], function ($r) { return $r->recruit_regLevel == 1; });
                            if (empty($m1_rooms)): ?>
                                <div class="col-12 text-center py-5">
                                    <div class="mb-3"><i class='bx bx-file-blank fs-huge opacity-25'></i></div>
                                    <p class="text-muted">ยังไม่พบข้อมูลผู้สมัครในระดับชั้นนี้</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($m1_rooms as $room): ?>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="room-card">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="fw-bold mb-0 text-dark"><?= $room->recruit_tpyeRoom ?></h6>
                                                <span class="badge bg-primary rounded-pill"><?= number_format($room->total) ?></span>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="p-2 border rounded text-center">
                                                        <div class="small text-muted"><i class='bx bx-male-sign text-info'></i> ชาย</div>
                                                        <div class="fw-bold"><?= $room->male ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2 border rounded text-center">
                                                        <div class="small text-muted"><i class='bx bx-female-sign text-danger'></i> หญิง</div>
                                                        <div class="fw-bold"><?= $room->female ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress progress-thick overflow-hidden">
                                                <?php
                                                $male_p = ($room->total > 0) ? ($room->male / $room->total) * 100 : 0;
                                                $female_p = ($room->total > 0) ? ($room->female / $room->total) * 100 : 0;
                                                ?>
                                                <div class="progress-bar bg-info" style="width: <?= $male_p ?>%"></div>
                                                <div class="progress-bar bg-danger" style="width: <?= $female_p ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- M4 Pane -->
                    <div class="tab-pane fade" id="m4-pane" role="tabpanel" aria-labelledby="m4-tab">
                        <div class="row g-4">
                            <?php
                            $m4_rooms = array_filter($stats['total_by_room'], function ($r) { return $r->recruit_regLevel == 4; });
                            if (empty($m4_rooms)): ?>
                                <div class="col-12 text-center py-5">
                                    <div class="mb-3"><i class='bx bx-file-blank fs-huge opacity-25'></i></div>
                                    <p class="text-muted">ยังไม่พบข้อมูลผู้สมัครในระดับชั้นนี้</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($m4_rooms as $room): ?>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="room-card">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="fw-bold mb-0 text-dark"><?= $room->recruit_tpyeRoom ?></h6>
                                                <span class="badge bg-primary rounded-pill"><?= number_format($room->total) ?></span>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="p-2 border rounded text-center">
                                                        <div class="small text-muted"><i class='bx bx-male-sign text-info'></i> ชาย</div>
                                                        <div class="fw-bold"><?= $room->male ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2 border rounded text-center">
                                                        <div class="small text-muted"><i class='bx bx-female-sign text-danger'></i> หญิง</div>
                                                        <div class="fw-bold"><?= $room->female ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress progress-thick overflow-hidden">
                                                <?php
                                                $male_p = ($room->total > 0) ? ($room->male / $room->total) * 100 : 0;
                                                $female_p = ($room->total > 0) ? ($room->female / $room->total) * 100 : 0;
                                                ?>
                                                <div class="progress-bar bg-info" style="width: <?= $male_p ?>%"></div>
                                                <div class="progress-bar bg-danger" style="width: <?= $female_p ?>%"></div>
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
                            <table class="table table-hover table-v-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>วันที่สมัคร</th>
                                        <th class="text-center">ม.1</th>
                                        <th class="text-center">ม.4</th>
                                        <th class="text-center">ชาย</th>
                                        <th class="text-center">หญิง</th>
                                        <th class="text-center">รวม</th>
                                        <th style="width: 15%">สัดส่วนสูงสุด</th>
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
                                                    <div class="avatar avatar-xs me-2"><span class="avatar-initial rounded bg-label-primary fs-tiny"><i class='bx bx-calendar'></i></span></div>
                                                    <span class="fw-bold"><?= $datethai->thai_date_short(strtotime($day->date)) ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center"><?= number_format($day->m1) ?></td>
                                            <td class="text-center"><?= number_format($day->m4) ?></td>
                                            <td class="text-center text-info"><?= number_format($day->male) ?></td>
                                            <td class="text-center text-danger"><?= number_format($day->female) ?></td>
                                            <td class="text-center fw-bold fs-5"><?= number_format($day->total) ?></td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: <?= ($day->total / $max_daily) * 100 ?>%"></div>
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
        </div>
    </div>
</div>

<!-- Special Pivot Card -->
<div class="container-xxl">
    <div class="row g-4 mb-4">
        <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md bg-label-primary me-3">
                        <span class="avatar-initial rounded-circle"><i class="bx bx-table fs-3"></i></span>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">ตารางสรุปแผนการสมัครนักเรียน</h5>
                        <small class="text-muted">แยกตามสาขาวิชาและความเป็นเลิศ</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="<?= site_url('skjadmin/statistics') ?>" class="d-none d-md-flex align-items-center gap-2">
                        <input type="hidden" name="year" value="<?= $selectedYear ?>">
                        <select name="round" class="form-select form-select-sm w-px-120 border-light" onchange="this.form.submit()">
                            <option value="">ทุกรอบ</option>
                            <?php foreach ($rounds as $r): ?>
                                <option value="<?= $r->recruit_round ?>" <?= $selectedRound == $r->recruit_round ? 'selected' : '' ?>>รอบ <?= $r->recruit_round ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="date" class="form-select form-select-sm w-px-150 border-light" onchange="this.form.submit()">
                            <option value="">ทุกวัน</option>
                            <?php foreach ($dates as $d): ?>
                                <option value="<?= $d->recruit_date ?>" <?= $selectedDate == $d->recruit_date ? 'selected' : '' ?>><?= $datethai->thai_date_short(strtotime($d->recruit_date)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body">
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
                    
                    // Also ensure headers exist for branches that might not be in allCourses but are in data
                    if (!isset($headerRows[$level])) $headerRows[$level] = [];
                    if (!in_array($branch, $headerRows[$level])) $headerRows[$level][] = $branch;

                    if (!isset($dailyPivot[$date])) $dailyPivot[$date] = [];
                    if (!isset($dailyPivot[$date][$level])) $dailyPivot[$date][$level] = [];
                    $dailyPivot[$date][$level][$branch] = ($dailyPivot[$date][$level][$branch] ?? 0) + $row->total;
                    $totalsByCat[$level][$branch] = ($totalsByCat[$level][$branch] ?? 0) + $row->total;
                }
                sort($availableLevels);
                krsort($dailyPivot);

                // Define colors for each level
                $levelColors = [
                    1 => 'bg-primary', 2 => 'bg-info', 3 => 'bg-secondary',
                    4 => 'bg-warning', 5 => 'bg-success', 6 => 'bg-danger'
                ];
                ?>

                <!-- Dynamic Tab Navigation -->
                <ul class="nav nav-tabs nav-fill mb-4 border-bottom-0" id="pivotTabs" role="tablist">
                    <?php foreach ($availableLevels as $index => $l): ?>
                    <li class="nav-item">
                        <button class="nav-link <?= $index === 0 ? 'active' : '' ?> fw-bold" id="pivot-m<?= $l ?>-tab" data-bs-toggle="tab" data-bs-target="#pivot-m<?= $l ?>-pane" type="button" role="tab">
                            มัธยมศึกษาปีที่ <?= $l ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content border-0 p-0" id="pivotTabContent">
                    <!-- Tabs: Individual Levels (Detailed Excellence) -->
                    <?php foreach ($availableLevels as $index => $currentL): ?>
                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="pivot-m<?= $currentL ?>-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="<?= $levelColors[$currentL] ?? 'bg-primary' ?> text-white fw-bold">
                                    <tr class="text-white">
                                        <th style="width: 150px; vertical-align: middle;" class="text-white">วันที่สมัคร</th>
                                        <?php 
                                        $branches = $headerRows[$currentL] ?? [];
                                        foreach ($branches as $branch): ?>
                                            <th class="th-vertical text-white">
                                                <span class="vertical-text text-white"><?= $branch ?></span>
                                            </th>
                                        <?php endforeach; ?>
                                        <th style="background: #ef3e1d; width: 65px; vertical-align: middle;" class="text-white border-0">รวม</th>
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
                                                <td class="<?= $val > 0 ? 'fw-bold' : 'text-muted opacity-25' ?>">
                                                    <?= $val > 0 ? number_format($val) : '-' ?>
                                                </td>
                                            <?php endforeach; ?>
                                            <td class="bg-label-danger fw-bold"><?= number_format($daySum) ?></td>
                                        </tr>
                                    <?php $levelGrandTotal += $daySum; endforeach; ?>
                                    
                                    <?php if (!$levelHasData): ?>
                                        <tr><td colspan="<?= count($branches) + 2 ?>" class="py-5 text-muted">ไม่พบข้อมูลสถิติของชั้น ม.<?= $currentL ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="fw-bold">
                                    <tr class="<?= $levelColors[$currentL] ?? 'bg-primary' ?> text-white font-weight-bold">
                                        <td class="text-white">รวม ม.<?= $currentL ?></td>
                                        <?php 
                                        foreach ($branches as $branch): 
                                            $val = $totalsByCat[$currentL][$branch] ?? 0;
                                            ?>
                                            <td class="text-white"><?= number_format($val) ?></td>
                                        <?php endforeach; ?>
                                        <td style="background: #ef3e1d;" class="text-white border-0"><?= number_format($levelGrandTotal) ?></td>
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
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('public/sneat-assets/vendor/libs/apex-charts/apexcharts.js') ?>"></script>
<script>
$(function() {
    // Colors
    const chartColors = {
        primary: '#696cff',
        info: '#03c3ec',
        success: '#71dd37',
        warning: '#ffab00',
        danger: '#ff3e1d',
        gray: '#a1acb8'
    };

    // 1. Application Trend Chart
    const dailyStats = <?= json_encode($dailyStats) ?>;
    const timelineOptions = {
        series: [
            {
                name: 'มัธยมศึกษาปีที่ 1',
                data: dailyStats.map(d => d.m1)
            },
            {
                name: 'มัธยมศึกษาปีที่ 4',
                data: dailyStats.map(d => d.m4)
            }
        ],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false },
            fontFamily: 'K2D'
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        colors: [chartColors.info, chartColors.success],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.5,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: dailyStats.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString('th-TH', { day: 'numeric', month: 'short' });
            }),
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: { labels: { style: { colors: chartColors.gray } } },
        grid: {
            borderColor: '#e7eaf0',
            strokeDashArray: 5,
            padding: { bottom: -10 }
        },
        legend: { position: 'top', horizontalAlign: 'right' }
    };
    new ApexCharts(document.querySelector("#registrationTimeline"), timelineOptions).render();

    // 2. Status Chart
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
            height: 300,
            fontFamily: 'K2D'
        },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'ทั้งหมด',
                            fontSize: '15px',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString();
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
            options: { chart: { height: 250 } }
        }]
    };
    new ApexCharts(document.querySelector("#statusChart"), statusOptions).render();
});
</script>
<?= $this->endSection() ?>
         
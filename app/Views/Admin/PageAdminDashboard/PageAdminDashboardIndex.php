<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --primary-color: #28a745;
        --secondary-color: #697a8d;
        --success-color: #71dd37;
        --warning-color: #ffab00;
        --danger-color: #ff3e1d;
        --info-color: #03c3ec;
        --card-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
        --primary-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    /* Welcome Banner */
    .welcome-card {
        background: var(--primary-gradient);
        border-radius: 16px;
        border: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Stat Cards */
    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(67, 89, 113, 0.15);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Chart Containers */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Action Cards */
    .action-card {
        border: 1px dashed #d9dee3;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .action-card:hover {
        background-color: rgba(40, 167, 69, 0.05);
        border-color: var(--primary-color);
        transform: scale(1.02);
    }

    .action-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    /* Custom Table */
    .table-custom thead th {
        background-color: #f5f7f9;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 700;
        border: none;
    }

    /* Year Selector */
    .year-selector-wrapper {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 15px;
        border-radius: 10px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .year-select {
        background: transparent;
        border: none;
        color: white;
        font-weight: 700;
        cursor: pointer;
    }

    .year-select option {
        color: #333;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* Dashboard Mobile Adjustments */
    @media (max-width: 767.98px) {
        .welcome-card {
            padding: 1.5rem !important;
        }
        
        .welcome-card h3 {
            font-size: 1.25rem;
        }

        .stat-card .card-body {
            padding: 1rem;
        }

        .stat-card h3 {
            font-size: 1.25rem;
        }

        .icon-box {
            width: 38px;
            height: 38px;
            font-size: 1.2rem;
        }

        .chart-container {
            height: 220px !important;
        }

        .action-card {
            padding: 15px 10px;
        }

        .action-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .action-card h6 {
            font-size: 0.85rem;
        }

        .action-card small {
            font-size: 0.7rem;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .table-custom thead th {
            font-size: 0.65rem;
            padding: 0.5rem 0.25rem !important;
        }
        
        .table-custom tbody td {
            font-size: 0.8rem;
            padding: 0.75rem 0.25rem !important;
        }
    }
</style>

<!-- Banner section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card welcome-card text-white p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="fw-bold mb-1">ยินดีต้อนรับกลับมา, <?= session()->get('pers_firstname') ?>! 👋</h3>
                    <p class="mb-0 opacity-75">สรุปภาพรวมระบบรับสมัครนักเรียนปีการศึกษา <?= $selected_year ?></p>
                </div>
                <div class="year-selector-wrapper">
                    <label class="small mb-0 me-2 text-white-50"><i class='bx bx-calendar'></i> ปีการศึกษา:</label>
                    <select class="year-select" onchange="window.location.href='<?= site_url('skjadmin/dashboard') ?>/' + this.value">
                        <?php foreach ($years as $y): ?>
                            <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selected_year ? 'selected' : '' ?>><?= $y->recruit_year ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mini Stats Section -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1 text-muted">ผู้สมัครทั้งหมด</h6>
                        <h3 class="mb-0 fw-bold"><?= number_format($stats->StuALL) ?></h3>
                    </div>
                    <div class="icon-box bg-label-primary">
                        <i class='bx bx-group'></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-primary fw-medium me-1"><i class='bx bx-trending-up'></i> ม.1: <?= number_format($stats->NumAllM1) ?></span>
                    <span class="text-info fw-medium ms-2"><i class='bx bx-trending-up'></i> ม.4: <?= number_format($stats->NumAllM4) ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1 text-muted">ผ่านการตรวจสอบ</h6>
                        <h3 class="mb-0 fw-bold text-success"><?= number_format($stats->Pass) ?></h3>
                    </div>
                    <div class="icon-box bg-label-success">
                        <i class='bx bx-check-double'></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: <?= $stats->StuALL > 0 ? ($stats->Pass / $stats->StuALL) * 100 : 0 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1 text-muted">รอการตรวจสอบ</h6>
                        <h3 class="mb-0 fw-bold text-warning"><?= number_format($stats->Pending) ?></h3>
                    </div>
                    <div class="icon-box bg-label-warning">
                        <i class='bx bx-time-five'></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: <?= $stats->StuALL > 0 ? ($stats->Pending / $stats->StuALL) * 100 : 0 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1 text-muted">ต้องแก้ไขข้อมูล</h6>
                        <h3 class="mb-0 fw-bold text-danger"><?= number_format($stats->Edit) ?></h3>
                    </div>
                    <div class="icon-box bg-label-danger">
                        <i class='bx bx-error-circle'></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: <?= $stats->StuALL > 0 ? ($stats->Edit / $stats->StuALL) * 100 : 0 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart: Daily Trend -->
    <div class="col-md-8">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class='bx bx-line-chart me-2'></i>สถิติจำนวนผู้สมัครรายวัน (14 วันล่าสุด)</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="dailyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart: Status Distribution -->
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-bold"><i class='bx bx-pie-chart-alt-2 me-2'></i>สัดส่วนสถานะการสมัคร</h5>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="mt-3 w-100">
                    <div class="d-flex justify-content-between mb-1">
                        <small>ผ่านการตรวจสอบ</small>
                        <small class="fw-bold"><?= $stats->StuALL > 0 ? round(($stats->Pass / $stats->StuALL) * 100, 1) : 0 ?>%</small>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small>รอตรวจสอบ/แก้ไข</small>
                        <small class="fw-bold"><?= $stats->StuALL > 0 ? round((($stats->Pending + $stats->Edit) / $stats->StuALL) * 100, 1) : 0 ?>%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quota Breakdown -->
    <div class="col-lg-7">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class='bx bx-list-check me-2'></i>จำนวนผู้สมัครแยกตามประเภทโควตา</h5>
                <a href="<?= site_url('skjadmin/recruits') ?>" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ประเภทโควตา</th>
                                <th>ระดับชั้น</th>
                                <th class="text-center">จำนวน (คน)</th>
                                <th class="text-center">สัดส่วน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($quota_stats)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">ไม่มีข้อมูลโควตา</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($quota_stats as $qs): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-medium"><?= esc($qs->quota_explain) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-secondary">ม.<?= esc($qs->quota_level) ?></span>
                                        </td>
                                        <td class="text-center fw-bold"><?= number_format($qs->count) ?></td>
                                        <td class="pe-4">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="progress w-100" style="height: 6px;">
                                                    <div class="progress-bar" style="width: <?= $stats->StuALL > 0 ? ($qs->count / $stats->StuALL) * 100 : 0 ?>%; background-color: var(--primary-color);"></div>
                                                </div>
                                                <small><?= $stats->StuALL > 0 ? round(($qs->count / $stats->StuALL) * 100, 1) : 0 ?>%</small>
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
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-5">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="mb-0 fw-bold"><i class='bx bx-history me-2'></i>ผู้สมัครล่าสุด 5 รายการ</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($recent_registrations)): ?>
                        <li class="list-group-item text-center py-4 text-muted">ไม่มีรายการส่งเข้ามาล่าสุด</li>
                    <?php else: ?>
                        <?php foreach ($recent_registrations as $reg): ?>
                            <?php
                            $status_class = 'bg-label-warning';
                            if ($reg->recruit_status == 'ผ่านการตรวจสอบ') $status_class = 'bg-label-success';
                            if ($reg->recruit_status == 'ไม่ผ่านการตรวจสอบ (รอแก้ไข)') $status_class = 'bg-label-danger';
                            ?>
                            <li class="list-group-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-primary"><?= mb_substr($reg->recruit_firstName, 0, 1) ?></span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?= esc($reg->recruit_prefix . $reg->recruit_firstName . ' ' . $reg->recruit_lastName) ?></h6>
                                            <small class="text-muted">ม.<?= esc($reg->recruit_regLevel) ?> • เมื่อ <?= date('d/m/H:i', strtotime($reg->recruit_date)) ?></small>
                                        </div>
                                    </div>
                                    <span class="badge <?= $status_class ?> badge-status"><?= esc($reg->recruit_status ?: 'รอการตรวจสอบ') ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="<?= site_url('skjadmin/recruits') ?>" class="btn btn-primary btn-sm w-100">ดูข้อมูลผู้สมัครทั้งหมด</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <h5 class="fw-bold mb-3"><i class='bx bx-rocket me-2'></i>เครื่องมือจัดการระบบด่วน</h5>
    </div>
    <div class="col-md-3 col-6">
        <a href="<?= site_url('skjadmin/settings') ?>" class="text-decoration-none">
            <div class="action-card bg-white shadow-sm">
                <div class="action-icon"><i class='bx bx-power-off'></i></div>
                <h6 class="mb-1 fw-bold text-dark">เปิด-ปิดรับสมัคร</h6>
                <small class="text-muted">จัดการวันเวลาเปิดรับสมัคร</small>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="<?= site_url('skjadmin/quotas') ?>" class="text-decoration-none">
            <div class="action-card bg-white shadow-sm">
                <div class="action-icon"><i class='bx bx-collection'></i></div>
                <h6 class="mb-1 fw-bold text-dark">จัดการโควตา</h6>
                <small class="text-muted">ตั้งค่าโควตาประเภทต่างๆ</small>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="<?= site_url('skjadmin/local-sync') ?>" class="text-decoration-none">
            <div class="action-card bg-white shadow-sm">
                <div class="action-icon"><i class='bx bx-sync'></i></div>
                <h6 class="mb-1 fw-bold text-dark">Sync ข้อมูล</h6>
                <small class="text-muted">สำรองและส่งไฟล์ไปเครื่องหลัก</small>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="<?= site_url('skjadmin/cleanup') ?>" class="text-decoration-none">
            <div class="action-card bg-white shadow-sm">
                <div class="action-icon"><i class='bx bx-trash'></i></div>
                <h6 class="mb-1 fw-bold text-dark">เครียร์ไฟล์ขยะ</h6>
                <small class="text-muted">ลบไฟล์ที่ไม่ได้ใช้งานออก</small>
            </div>
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Daily Trend Chart ---
        const dailyLabels = <?= json_encode(array_map(function($d) { return date('d/m', strtotime($d->date)); }, $daily_trend)) ?>;
        const dailyData = <?= json_encode(array_map(function($d) { return (int)$d->count; }, $daily_trend)) ?>;
        
        const ctxTrend = document.getElementById('dailyTrendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'จำนวนผู้สมัคร (คน)',
                    data: dailyData,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: '#28a745',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // --- 2. Status Doughnut Chart ---
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['ผ่านการตรวจสอบ', 'รอตรวจสอบ', 'ต้องแก้ไข'],
                datasets: [{
                    data: [<?= $stats->Pass ?>, <?= $stats->Pending ?>, <?= $stats->Edit ?>],
                    backgroundColor: ['#71dd37', '#ffab00', '#ff3e1d'],
                    hoverOffset: 4,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
<?= $this->endSection() ?>

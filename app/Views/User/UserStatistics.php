<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    .stats-container {
        padding: 2rem 0;
    }

    :root {
        --skj-pink: #ff9eb5;
        --skj-blue: #84d2f6;
        --skj-pink-dark: #ff7da0;
        --skj-blue-dark: #5cbbf2;
        --skj-gradient: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        --male-color: #3b82f6;
        --female-color: #f472b6;
    }

    .stats-hero {
        background: var(--skj-gradient);
        border-radius: 24px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(255, 158, 181, 0.2);
        position: relative;
        overflow: hidden;
    }

    .stats-hero::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }

    .stats-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .stats-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
    }

    /* Summary Cards */
    .card-stat {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 20px rgba(0,0,0,0.02);
    }

    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
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

    .stat-icon.total { background: rgba(255, 158, 181, 0.15); color: var(--skj-pink-dark); }
    .stat-icon.m1 { background: rgba(132, 210, 246, 0.15); color: var(--skj-blue-dark); }
    .stat-icon.verified { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    .stat-label {
        font-weight: 800;
        color: #4a5568;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2d3748;
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

    .gender-box.male { background: rgba(59, 130, 246, 0.08); color: var(--male-color); }
    .gender-box.female { background: rgba(244, 114, 182, 0.08); color: var(--female-color); }

    .gender-val { font-size: 1.1rem; font-weight: 800; }

    /* Main Stats Cards */
    .stats-card-main {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
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
        color: #64748b;
        border: none;
    }
    .nav-skj .nav-link.active {
        background: white;
        color: var(--skj-pink);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .stats-hero { padding: 2rem 1.5rem; }
        .stats-title { font-size: 1.8rem; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stats-container">
    <div class="container">
        <!-- Hero Section -->
        <div class="stats-hero">
            <h1 class="stats-title">สรุปสถิติการรับสมัคร</h1>
            <p class="stats-subtitle">
                <i class='bx bxs-school me-1'></i> สถิติจำนวนผู้สมัครแยกตามระดับชั้นและเพศ (หญิง/ชาย)
                <span class="mx-2 opacity-50">|</span>
                <i class='bx bx-calendar me-1'></i> ปีการศึกษา <?= $checkYear->openyear_year ?>
            </p>
        </div>

        <!-- Upper Summary Row: M.Link (Junior) & M.ปลาย (Senior) -->
        <div class="row g-4 mb-5">
            <?php
            $m1_data = ['total' => 0, 'male' => 0, 'female' => 0];
            $m4_data = ['total' => 0, 'male' => 0, 'female' => 0];
            foreach ($stats['total_by_level'] as $l) {
                if ($l->recruit_regLevel == 1) {
                    $m1_data = ['total' => $l->total, 'male' => $l->male, 'female' => $l->female];
                }
                if ($l->recruit_regLevel == 4) {
                    $m4_data = ['total' => $l->total, 'male' => $l->male, 'female' => $l->female];
                }
            }
            ?>
            <!-- Junior High Card -->
            <div class="col-md-6 col-xl-4">
                <div class="card-stat">
                    <div class="stat-header">
                        <div class="stat-icon m1"><i class='bx bxs-book-content'></i></div>
                        <div class="stat-label">มัธยมศึกษาปีที่ 1 (ม.ต้น)</div>
                    </div>
                    <div class="stat-number"><?= number_format($m1_data['total']) ?></div>
                    <div class="gender-split">
                        <div class="gender-box male">
                            <i class='bx bx-male-sign mb-1'></i>
                            <span>ชาย</span>
                            <span class="gender-val"><?= number_format($m1_data['male']) ?></span>
                        </div>
                        <div class="gender-box female">
                            <i class='bx bx-female-sign mb-1'></i>
                            <span>หญิง</span>
                            <span class="gender-val"><?= number_format($m1_data['female']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Senior High Card -->
            <div class="col-md-6 col-xl-4">
                <div class="card-stat">
                    <div class="stat-header">
                        <div class="stat-icon total" style="background: rgba(132, 210, 246, 0.15); color: #5cbbf2;"><i class='bx bxs-book-heart'></i></div>
                        <div class="stat-label">มัธยมศึกษาปีที่ 4 (ม.ปลาย)</div>
                    </div>
                    <div class="stat-number"><?= number_format($m4_data['total']) ?></div>
                    <div class="gender-split">
                        <div class="gender-box male">
                            <i class='bx bx-male-sign mb-1'></i>
                            <span>ชาย</span>
                            <span class="gender-val"><?= number_format($m4_data['male']) ?></span>
                        </div>
                        <div class="gender-box female">
                            <i class='bx bx-female-sign mb-1'></i>
                            <span>หญิง</span>
                            <span class="gender-val"><?= number_format($m4_data['female']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grand Total Card -->
            <div class="col-md-12 col-xl-4">
                <div class="card-stat" style="background: var(--skj-gradient); color: white;">
                    <div class="stat-header">
                        <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white;"><i class='bx bxs-user-detail'></i></div>
                        <div class="stat-label text-white">ยอดผู้สมัครรวมทุกระดับชั้น</div>
                    </div>
                    <div class="stat-number text-white"><?= number_format($stats['grand_total']) ?></div>
                    <?php
                    $total_male = $m1_data['male'] + $m4_data['male'];
                    $total_female = $m1_data['female'] + $m4_data['female'];
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
            <!-- Daily Log Table - Full Width or Wider for Many Columns -->
            <div class="col-12">
                <div class="stats-card-main">
                    <div class="stats-card-header">
                        <h5 class="stats-card-title"><i class='bx bx-history'></i> ความเคลื่อนไหวรายวัน (แยกตามประเภท)</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center" style="font-size: 0.95rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start ps-4">วันที่สมัคร</th>
                                    <th style="color: var(--skj-blue-dark);">ม.1 (คน)</th>
                                    <th style="color: #48bb78;">ม.4 (คน)</th>
                                    <th style="color: var(--male-color);"><i class='bx bx-male-sign'></i> ชาย</th>
                                    <th style="color: var(--female-color);"><i class='bx bx-female-sign'></i> หญิง</th>
                                    <th class="bg-light fw-bold text-dark">รวม (คน)</th>
                                    <th class="pe-4" style="width: 15%;">สัดส่วน</th>
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
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#m1-detail">ม.ต้น (1)</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#m4-detail">ม.ปลาย (4)</button></li>
                        </ul>
                    </div>
                    <div class="p-4">
                        <div class="tab-content">
                            <!-- M1 Detail -->
                            <div class="tab-pane fade show active" id="m1-detail">
                                <?php
                                $m1_rooms = array_filter($stats['total_by_room'], function ($r) {
                                    return $r->recruit_regLevel == 1; });
                                ?>
                                <?php if (empty($m1_rooms)): ?>
                                        <div class="text-center py-5 text-muted">ยังไม่มีข้อมูล</div>
                                <?php else: ?>
                                        <div class="row">
                                            <?php foreach ($m1_rooms as $room): ?>
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

                            <!-- M4 Detail -->
                            <div class="tab-pane fade" id="m4-detail">
                                <?php
                                $m4_rooms = array_filter($stats['total_by_room'], function ($r) {
                                    return $r->recruit_regLevel == 4; });
                                ?>
                                <?php if (empty($m4_rooms)): ?>
                                        <div class="text-center py-5 text-muted">ยังไม่มีข้อมูล</div>
                                <?php else: ?>
                                        <div class="row">
                                            <?php foreach ($m4_rooms as $room): ?>
                                                    <div class="col-md-6">
                                                        <div class="program-item">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <div class="program-title pe-2 text-info"><?= $room->recruit_tpyeRoom ?></div>
                                                                <span class="program-total-badge" style="background: var(--skj-blue);"><?= $room->total ?></span>
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
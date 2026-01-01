<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-1">
                            <i class='bx bx-bar-chart-alt-2 me-2 text-primary'></i> สรุปสถิติการรับสมัครปีการศึกษา
                            <?= $selectedYear ?>
                        </h4>
                        <p class="text-muted mb-0">ข้อมูลอัปเดตแบบ Real-time แยกตามเพศและระดับชั้น</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <label for="yearFilter" class="form-label mb-0 text-nowrap">เลือกปีการศึกษา:</label>
                        <select class="form-select w-px-150" id="yearFilter"
                            onchange="window.location.href='<?= site_url('skjadmin/statistics/') ?>' + this.value">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selectedYear ? 'selected' : '' ?>>
                                    ปีการศึกษา
                                    <?= $y->recruit_year ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards Row -->
<div class="row g-4 mb-4">
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
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 overflow-hidden border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group bx-sm"></i></span>
                    </div>
                    <div>
                        <small class="text-muted d-block">จำนวนผู้สมัครทั้งหมด</small>
                        <h3 class="card-title mb-0 fw-bold">
                            <?= number_format($stats['grand_total']) ?>
                        </h3>
                    </div>
                </div>
                <div class="text-muted small mt-3">
                    <span class="text-primary fw-bold">
                        <?= number_format($m1_data['total'] + $m4_data['total']) ?>
                    </span> บุคคลภายนอกสมัครผ่านระบบ
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info"><i
                                class="bx bx-book-content bx-sm"></i></span>
                    </div>
                    <div>
                        <small class="text-muted d-block">สมัครเข้าเรียน ม.1</small>
                        <h3 class="card-title mb-0 fw-bold">
                            <?= number_format($m1_data['total']) ?>
                        </h3>
                    </div>
                </div>
                <div class="d-flex mt-3 gap-2">
                    <span class="badge bg-label-secondary w-100">ช:
                        <?= $m1_data['male'] ?>
                    </span>
                    <span class="badge bg-label-secondary w-100">ญ:
                        <?= $m1_data['female'] ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success"><i
                                class="bx bx-graduation bx-sm"></i></span>
                    </div>
                    <div>
                        <small class="text-muted d-block">สมัครเข้าเรียน ม.4</small>
                        <h3 class="card-title mb-0 fw-bold">
                            <?= number_format($m4_data['total']) ?>
                        </h3>
                    </div>
                </div>
                <div class="d-flex mt-3 gap-2">
                    <span class="badge bg-label-secondary w-100">ช:
                        <?= $m4_data['male'] ?>
                    </span>
                    <span class="badge bg-label-secondary w-100">ญ:
                        <?= $m4_data['female'] ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <?php
        $verified = 0;
        foreach ($statusByLevel as $s)
            if ($s->recruit_status == 'ตรวจสอบแล้ว' || $s->recruit_status == 'ยืนยันสิทธิ์')
                $verified += $s->total;
        ?>
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning"><i
                                class="bx bx-check-shield bx-sm"></i></span>
                    </div>
                    <div>
                        <small class="text-muted d-block">ผ่านการตรวจสอบแล้ว</small>
                        <h3 class="card-title mb-0 fw-bold">
                            <?= number_format($verified) ?>
                        </h3>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar"
                        style="width: <?= ($stats['grand_total'] > 0) ? ($verified / $stats['grand_total']) * 100 : 0 ?>%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Daily Trend Table -->
    <div class="col-12 col-xl-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0"><i class='bx bx-history me-2'></i> ความเคลื่อนไหวการสมัครรายวัน</h5>
                <span class="badge bg-label-primary">เรียงจากล่าสุด</span>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light text-center">
                        <tr>
                            <th class="text-start">วันที่สมัคร</th>
                            <th>ม.1 (คน)</th>
                            <th>ม.4 (คน)</th>
                            <th class="text-primary"><i class='bx bx-male-sign'></i> ชาย</th>
                            <th class="text-danger"><i class='bx bx-female-sign'></i> หญิง</th>
                            <th class="fw-bold">ยอดสมัครรวม (วัน)</th>
                            <th style="width: 200px;">สัดส่วน</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php if (empty($dailyStats)): ?>
                            <tr>
                                <td colspan="7" class="py-5 text-muted">ยังไม่มีข้อมูลการสมัคร</td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $max_daily = max(array_column($dailyStats, 'total'));
                            foreach (array_reverse($dailyStats) as $day):
                                ?>
                                <tr>
                                    <td class="text-start fw-bold">
                                        <span class="badge bg-label-secondary mx-2">
                                            <?= $datethai->thai_date_short(strtotime($day->date)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= number_format($day->m1) ?>
                                    </td>
                                    <td>
                                        <?= number_format($day->m4) ?>
                                    </td>
                                    <td class="text-primary fw-bold">
                                        <?= number_format($day->male) ?>
                                    </td>
                                    <td class="text-danger fw-bold">
                                        <?= number_format($day->female) ?>
                                    </td>
                                    <td class="fw-extrabold fs-5 text-dark">
                                        <?= number_format($day->total) ?>
                                    </td>
                                    <td class="pe-4">
                                        <div class="progress" style="height: 10px; border-radius: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: <?= ($day->total / $max_daily) * 100 ?>%; border-radius: 5px;">
                                            </div>
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

    <!-- Detailed Column breakdown by Programs -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs nav-fill" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#navs-m1">
                                <i class="bx bx-book-content me-1"></i> ผู้สมัครแยกตามแผนงาน (ม.1)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#navs-m4">
                                <i class="bx bx-graduation me-1"></i> ผู้สมัครแยกตามแผนงาน (ม.4)
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="tab-content p-0 shadow-none border-0">
                    <div class="tab-pane fade show active" id="navs-m1" role="tabpanel">
                        <div class="row g-4">
                            <?php
                            $m1_rooms = array_filter($stats['total_by_room'], function ($r) {
                                return $r->recruit_regLevel == 1; });
                            if (empty($m1_rooms)): ?>
                                <div class="col-12 text-center py-5">
                                    <img src="<?= base_url('public/sneat-assets/img/illustrations/man-with-laptop-light.png') ?>"
                                        width="150" class="mb-3 opacity-50">
                                    <p class="text-muted">ไม่พบข้อมูลผู้สมัคร</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($m1_rooms as $room): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="p-3 border rounded shadow-xs h-100 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 fw-bold text-truncate" style="max-width: 80%;">
                                                    <?= $room->recruit_tpyeRoom ?>
                                                </h6>
                                                <span class="badge bg-primary rounded-pill">
                                                    <?= $room->total ?>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 small">
                                                <span class="text-primary fw-bold"><i class='bx bx-male-sign me-1'></i>ชาย:
                                                    <?= $room->male ?>
                                                </span>
                                                <span class="text-danger fw-bold"><i class='bx bx-female-sign me-1'></i>หญิง:
                                                    <?= $room->female ?>
                                                </span>
                                            </div>
                                            <div class="progress" style="height: 6px; border-radius: 10px; overflow: hidden;">
                                                <?php
                                                $male_p = ($room->total > 0) ? ($room->male / $room->total) * 100 : 0;
                                                $female_p = ($room->total > 0) ? ($room->female / $room->total) * 100 : 0;
                                                ?>
                                                <div class="progress-bar"
                                                    style="width: <?= $male_p ?>%; background-color: #3b82f6;"></div>
                                                <div class="progress-bar"
                                                    style="width: <?= $female_p ?>%; background-color: #f472b6;"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-m4" role="tabpanel">
                        <div class="row g-4">
                            <?php
                            $m4_rooms = array_filter($stats['total_by_room'], function ($r) {
                                return $r->recruit_regLevel == 4; });
                            if (empty($m4_rooms)): ?>
                                <div class="col-12 text-center py-5 text-muted">ไม่พบข้อมูลผู้สมัคร</div>
                            <?php else: ?>
                                <?php foreach ($m4_rooms as $room): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="p-3 border rounded shadow-xs h-100 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 fw-bold text-truncate" style="max-width: 80%;">
                                                    <?= $room->recruit_tpyeRoom ?>
                                                </h6>
                                                <span class="badge bg-info rounded-pill">
                                                    <?= $room->total ?>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 small">
                                                <span class="text-primary fw-bold"><i class='bx bx-male-sign me-1'></i>ชาย:
                                                    <?= $room->male ?>
                                                </span>
                                                <span class="text-danger fw-bold"><i class='bx bx-female-sign me-1'></i>หญิง:
                                                    <?= $room->female ?>
                                                </span>
                                            </div>
                                            <div class="progress" style="height: 6px; border-radius: 10px; overflow: hidden;">
                                                <?php
                                                $male_p = ($room->total > 0) ? ($room->male / $room->total) * 100 : 0;
                                                $female_p = ($room->total > 0) ? ($room->female / $room->total) * 100 : 0;
                                                ?>
                                                <div class="progress-bar"
                                                    style="width: <?= $male_p ?>%; background-color: #3b82f6;"></div>
                                                <div class="progress-bar"
                                                    style="width: <?= $female_p ?>%; background-color: #f472b6;"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
    // Any specific admin chart logic can go here
</script>
<?= $this->endSection() ?>
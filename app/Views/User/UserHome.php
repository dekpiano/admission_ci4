<?= $this->extend('User/UserLayout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8 mb-4 order-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0"><i class='bx bx-broadcast me-2'></i>ประกาศรับสมัครที่เปิดอยู่ในขณะนี้</h5>
                    <span class="badge bg-white text-primary shadow-sm">ปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?></span>
                </div>
                
                <?php 
                $hasActiveQuota = false;
                if (!empty($quotas)) {
                    foreach ($quotas as $quota) {
                        if (isset($quota->quota_status) && $quota->quota_status == 'on') {
                            $hasActiveQuota = true;
                            ?>
                            <?php
                            $is_closed_announce = false;
                            if (isset($systemStatus->onoff_datetime_regis_close)) {
                                if (time() > strtotime($systemStatus->onoff_datetime_regis_close)) {
                                    $is_closed_announce = true;
                                }
                            }
                            ?>
                            <div class="d-flex align-items-center mb-2 p-3 rounded <?= $is_closed_announce ? 'bg-secondary' : 'bg-primary' ?>" style="background-color: rgba(255,255,255,0.1);">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-white <?= $is_closed_announce ? 'text-secondary' : 'text-primary' ?>"><i class='bx bx-news'></i></span>
                                </div>
                                <div class="flex-grow-1 ">
                                    <h6 class="mb-1 text-white fw-bold">
                                        <?= $quota->quota_explain ?> 
                                        <?php if($is_closed_announce): ?>
                                            <span class="badge bg-danger ms-2">ปิดรับสมัครแล้ว</span>
                                        <?php endif; ?>
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <small class="text-white opacity-90">
                                            <i class='bx bx-calendar-check'></i> เปิดรับ: 
                                            <span class="bg-white text-info fw-bold px-2 py-0 rounded shadow-sm" style="font-size: 0.85rem;">
                                                <?= isset($systemStatus->onoff_datetime_regis_open) ? $datethai->thai_date_fullmonth(strtotime($systemStatus->onoff_datetime_regis_open)) : '-' ?>
                                            </span>
                                        </small>
                                        <small class="text-white opacity-90">
                                            <i class='bx bx-calendar-x'></i> ปิดรับ: 
                                            <span class="bg-white text-danger fw-bold px-2 py-0 rounded shadow-sm" style="font-size: 0.85rem;">
                                                <?= isset($systemStatus->onoff_datetime_regis_close) ? $datethai->thai_date_fullmonth(strtotime($systemStatus->onoff_datetime_regis_close)) : '-' ?>
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                
                if (!$hasActiveQuota) {
                    echo '<div class="text-center py-4"><p class="text-muted mb-0 fs-5">ยังไม่มีประกาศรับสมัครในขณะนี้</p></div>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img
                                    src="<?= base_url('public/sneat-assets/img/icons/unicons/wallet-info.png') ?>"
                                    alt="Credit Card"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">ประกาศผล</span>
                        <h3 class="card-title text-nowrap mb-1">
                             <?php if(isset($systemStatus) && $systemStatus->onoff_report == 'on'): ?>
                                <span class="text-primary">ประกาศแล้ว</span>
                            <?php else: ?>
                                <span class="text-warning">รอประกาศ</span>
                            <?php endif; ?>
                        </h3>
                        <small class="text-primary fw-semibold"><i class="bx bx-time"></i> ติดตาม</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="apply-section">
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
                     // Convert 'ม.1' to '1' etc. and ensure it's a number
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
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title">ระดับชั้นมัธยมศึกษาปีที่ <?= $level_num ?></h5>
                            <h6 class="card-subtitle text-muted mb-3"><?= $subtitle ?></h6>
                            <p class="card-text flex-grow-1">
                                ปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y')+543 ?>
                            </p>
                            <?php 
                                $is_closed = false;
                                if (isset($systemStatus->onoff_datetime_regis_close)) {
                                    $close_time = strtotime($systemStatus->onoff_datetime_regis_close);
                                    if (time() > $close_time) {
                                        $is_closed = true;
                                    }
                                }
                            ?>
                            
                            <?php if ($is_closed): ?>
                                <button class="btn btn-secondary mt-auto" disabled>ปิดรับสมัครแล้ว</button>
                            <?php else: ?>
                                <a href="<?= base_url('new-admission/pre-check/' . $pre_check_url_level . '?level=' . $level_num) ?>" class="btn <?= $btn_class ?> mt-auto">สมัครเรียน ม.<?= $level_num ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- No levels open based on quota status -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">ยังไม่เปิดรับสมัคร</h5>
                        <p class="text-muted">ยังไม่มีระดับชั้นที่เปิดรับสมัครในขณะนี้ กรุณาติดตามประกาศจากทางโรงเรียน</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- System is off -->
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">ปิดระบบรับสมัคร</h5>
                    <p class="text-muted">ระบบรับสมัครนักเรียนออนไลน์ยังไม่เปิดให้บริการ กรุณาติดตามประกาศจากทางโรงเรียน</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">กำหนดการรับสมัคร</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>กิจกรรม</th>
                            <th>วันที่</th>
                            <th>หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>เปิดรับสมัคร</strong></td>
                            <td><?= isset($systemStatus->onoff_datetime_regis_open) ? date('d/m/Y H:i', strtotime($systemStatus->onoff_datetime_regis_open)) : '-' ?></td>
                            <td><span class="badge bg-label-success me-1">Online</span></td>
                        </tr>
                        <tr>
                            <td><i class="fab fa-react fa-lg text-info me-3"></i> <strong>ปิดรับสมัคร</strong></td>
                            <td><?= isset($systemStatus->onoff_datetime_regis_close) ? date('d/m/Y H:i', strtotime($systemStatus->onoff_datetime_regis_close)) : '-' ?></td>
                            <td><span class="badge bg-label-danger me-1">System Close</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
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
        gap: 10px;
    }
    .countdown-container li {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        list-style-type: none;
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        min-width: 60px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .countdown-container li span {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }
    .countdown-container li .label {
        font-size: 0.65rem;
        text-transform: uppercase;
        margin-top: 4px;
        opacity: 0.9;
    }
    @media (max-width: 576px) {
        .countdown-container li {
            min-width: 50px;
            padding: 0.25rem;
        }
        .countdown-container li span {
            font-size: 1.2rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12 mb-4 order-0">
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
                             
                                <div class="flex-grow-1 text-center">
                                    <h4 class="mb-1 text-white fw-bold">
                                        <?= $quota->quota_explain ?> 
                                        <?php if($is_closed_announce): ?>
                                            <span class="badge bg-danger ms-2">ปิดรับสมัครแล้ว</span>
                                        <?php elseif(isset($systemStatus->onoff_datetime_regis_open) && time() < strtotime($systemStatus->onoff_datetime_regis_open)): ?>
                                            <span class="badge bg-warning text-dark ms-2">รอเปิดรับสมัคร</span>
                                        <?php endif; ?>
                                    </h4>
                                    
                                    <?php if(isset($systemStatus->onoff_datetime_regis_open) && time() < strtotime($systemStatus->onoff_datetime_regis_open)): ?>
                                        <div class="text-center w-100 mt-3 mb-3">
                                            <p class="text-white mb-2 opacity-90"><i class='bx bx-time-five'></i> กำลังจะเปิดรับสมัครในอีก...</p>
                                            <div class="countdown-container" data-target="<?= $systemStatus->onoff_datetime_regis_open ?>">
                                                <ul>
                                                    <li><span class="days">0</span><div class="label">วัน</div></li>
                                                    <li><span class="hours">0</span><div class="label">ชั่วโมง</div></li>
                                                    <li><span class="minutes">0</span><div class="label">นาที</div></li>
                                                    <li><span class="seconds">0</span><div class="label">วินาที</div></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <small class="text-white opacity-90">
                                            <i class='bx bx-calendar-check'></i> เปิดรับ: 
                                            <span class="bg-white text-success fw-bold px-2 py-0 rounded shadow-sm" style="font-size: 0.85rem;">
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
                    if (isset($systemStatus->onoff_datetime_regis_open) && time() < strtotime($systemStatus->onoff_datetime_regis_open)) {
                        ?>
                        <div class="d-flex align-items-center mb-2 p-3 rounded bg-primary" style="background-color: rgba(255,255,255,0.1);">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded-circle bg-white text-primary"><i class='bx bx-time-five'></i></span>
                            </div>
                            <div class="flex-grow-1 text-center">
                                <h6 class="mb-1 text-white fw-bold">
                                    ระบบรับสมัครนักเรียนออนไลน์
                                    <span class="badge bg-warning text-dark ms-2">รอเปิดรับสมัคร</span>
                                </h6>
                                
                                <div class="text-center w-100 mt-3 mb-3">
                                    <p class="text-white mb-2 opacity-90"><i class='bx bx-time-five'></i> กำลังจะเปิดรับสมัครในอีก...</p>
                                    <div class="countdown-container" data-target="<?= $systemStatus->onoff_datetime_regis_open ?>">
                                        <ul>
                                            <li><span class="days">0</span><div class="label">วัน</div></li>
                                            <li><span class="hours">0</span><div class="label">ชั่วโมง</div></li>
                                            <li><span class="minutes">0</span><div class="label">นาที</div></li>
                                            <li><span class="seconds">0</span><div class="label">วินาที</div></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <small class="text-white opacity-90">
                                        <i class='bx bx-calendar-check'></i> เปิดรับ: 
                                        <span class="bg-white text-info fw-bold px-2 py-0 rounded shadow-sm" style="font-size: 0.85rem;">
                                            <?= isset($systemStatus->onoff_datetime_regis_open) ? $datethai->thai_date_fullmonth(strtotime($systemStatus->onoff_datetime_regis_open)) : '-' ?>
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo '<div class="text-center py-4"><p class="text-muted mb-0 fs-5">ยังไม่มีประกาศรับสมัครในขณะนี้</p></div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center" id="apply-section">
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
                                <button class="btn btn-secondary mt-auto" disabled>ปิดรับสมัครแล้ว</button>
                            <?php elseif ($is_not_open): ?>
                                <button class="btn btn-warning mt-auto" disabled>ยังไม่ถึงวันรับสมัคร</button>
                            <?php else: ?>
                                <button type="button" class="btn <?= $btn_class ?> mt-auto apply-btn" data-href="<?= base_url('new-admission/pre-check/' . $pre_check_url_level . '?level=' . $level_num) ?>">สมัครเรียน ม.<?= $level_num ?></button>
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
        <p>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ (“โรงเรียน”) ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของผู้สมัคร ("ท่าน") โรงเรียนจึงได้จัดทำข้อตกลงและเงื่อนไขการใช้ข้อมูลส่วนบุคคลฉบับนี้ขึ้น เพื่อแจ้งให้ท่านทราบถึงวิธีการที่โรงเรียนเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูลส่วนบุคคลของท่าน และสิทธิของท่านในฐานะเจ้าของข้อมูลส่วนบุคคล</p>
        
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
        <p>โรงเรียนจะเก็บรักษาข้อมูลส่วนบุคคลของท่านไว้เป็นระยะเวลาเท่าที่จำเป็นเพื่อบรรลุวัตถุประสงค์ที่ได้แจ้งไว้ และตามที่กฎหมายกำหนด</p>

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

        const updateCountdown = () => {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                location.reload(); 
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if(daysEl) daysEl.innerText = days;
            if(hoursEl) hoursEl.innerText = hours;
            if(minutesEl) minutesEl.innerText = minutes;
            if(secondsEl) secondsEl.innerText = seconds;
        };

        updateCountdown(); 
        setInterval(updateCountdown, 1000); 
    });

    // --- PDPA Modal Logic ---
    const pdpaModalEl = document.getElementById('pdpaModal');
    if (pdpaModalEl) {
        const pdpaModal = new bootstrap.Modal(pdpaModalEl);
        const acceptBtn = document.getElementById('pdpa-accept-btn');
        let targetUrl = '';

        document.querySelectorAll('.apply-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                targetUrl = this.dataset.href;
                pdpaModal.show();
            });
        });

        if (acceptBtn) {
            acceptBtn.addEventListener('click', function() {
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            });
        }
    }
});
</script>
<?= $this->endSection() ?>

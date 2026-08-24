<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<style>
    /* Color Variables - Suankularb Pink & Sky Blue Theme */
    :root {
        --primary-color: #ff6b8b;
        --primary-dark: #e04869;
        --primary-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
        --primary-light: rgba(255, 107, 139, 0.15);
    }

    /* Full Width Override */
    @media (min-width: 1400px) {
        .container-xxl {
            max-width: 95% !important;
        }
    }

    /* Announcement Cards */
    .announce-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .announce-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
    }

    .announce-card .card-body {
        padding: 1.5rem;
    }

    .announce-card .type-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .type-exam-normal { background: rgba(3, 195, 236, 0.15); color: #03c3ec; }
    .type-exam-sports { background: rgba(255, 171, 0, 0.15); color: #ffab00; }
    .type-passed-normal { background: rgba(113, 221, 55, 0.15); color: #71dd37; }
    .type-passed-sports { background: rgba(32, 201, 151, 0.15); color: #20c997; }

    .announce-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin: 0.75rem 0 0.5rem;
    }

    .announce-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 1rem;
    }

    .announce-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.82rem;
        color: #697a8d;
    }

    .announce-meta-item i {
        font-size: 1rem;
    }

    /* File Preview */
    .file-preview {
        width: 100%;
        height: 200px;
        border-radius: 12px;
        overflow: hidden;
        background: #f5f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        border: 2px dashed #e0e0e0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-preview:hover {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }

    .file-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .file-preview .pdf-icon {
        font-size: 4rem;
        color: #dc3545;
    }

    /* Toggle Switch */
    .toggle-status {
        cursor: pointer;
    }

    /* Stats */
    .announce-stats {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .announce-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        flex: 1;
    }

    .announce-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .announce-stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
    }

    .announce-stat-label {
        font-size: 0.85rem;
        color: #697a8d;
    }

    /* Upload Zone */
    .upload-zone {
        border: 2px dashed #ccc;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }

    .upload-zone i {
        font-size: 3rem;
        color: var(--primary-color);
    }

    /* Year Selector */
    .year-selector-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--primary-gradient);
        padding: 12px 24px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.35);
    }

    .year-selector-label {
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .year-selector {
        border: none;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 700;
        font-size: 1.1rem;
        min-width: 100px;
        background: white;
        color: var(--primary-color);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Action Buttons */
    .announce-actions {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
    }

    .announce-actions .btn {
        border-radius: 10px;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.2s ease;
    }

    .announce-actions .btn:hover {
        transform: scale(1.1);
    }

    /* Status Strip */
    .status-strip {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .status-strip.active {
        background: linear-gradient(90deg, #ff6b8b, #56ccf2);
    }

    .status-strip.inactive {
        background: linear-gradient(90deg, #ff3e1d, #ff6a3d);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .announce-stats {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bx bx-megaphone text-primary me-2"></i>
            ประกาศผลการคัดเลือก
        </h4>
        <p class="text-muted mb-0">จัดการไฟล์ประกาศผลสิทธิ์สอบ / สิทธิ์เรียน</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="year-selector-wrapper">
            <label class="year-selector-label">
                <i class="bx bx-calendar"></i>
                ปีการศึกษา
            </label>
            <select name="year" id="year" class="form-select year-selector">
                <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addModal"
            style="border-radius: 14px; padding: 12px 24px;">
            <i class="bx bx-plus me-1"></i> เพิ่มประกาศ
        </button>
    </div>
</div>

<!-- Stats Row -->
<div class="announce-stats mb-4">
    <div class="announce-stat-item">
        <div class="announce-stat-icon" style="background: rgba(255, 107, 139, 0.15); color: #ff6b8b;">
            <i class="bx bx-file"></i>
        </div>
        <div>
            <div class="announce-stat-value text-primary" id="totalAnnounce"><?= count($announcements) ?></div>
            <div class="announce-stat-label">ประกาศทั้งหมด</div>
        </div>
    </div>
    
    <!-- New Combined Stats Grouped by Main Type -->
    <div class="announce-stat-item">
        <div class="announce-stat-icon type-exam-normal"><i class="bx bx-edit-alt"></i></div>
        <div>
            <div class="announce-stat-value" id="examCount">
                <?= count(array_filter($announcements, fn($a) => in_array($a['announce_type'], ['exam_normal', 'exam_sports']))) ?>
            </div>
            <div class="announce-stat-label">รายชื่อผู้มีสิทธิ์</div>
        </div>
    </div>

    <div class="announce-stat-item">
        <div class="announce-stat-icon type-passed-normal"><i class="bx bx-check-shield"></i></div>
        <div>
            <div class="announce-stat-value" id="studyCount">
                <?= count(array_filter($announcements, fn($a) => in_array($a['announce_type'], ['passed_normal', 'passed_sports']))) ?>
            </div>
            <div class="announce-stat-label">รายชื่อผู้ผ่านการคัดเลือก</div>
        </div>
    </div>
    <div class="announce-stat-item">
        <div class="announce-stat-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
            <i class="bx bx-show"></i>
        </div>
        <div>
            <div class="announce-stat-value text-info" id="activeCount">
                <?= count(array_filter($announcements, fn($a) => $a['announce_status'] === 'on')) ?>
            </div>
            <div class="announce-stat-label">เปิดแสดงผล</div>
        </div>
    </div>
</div>

<!-- System Sync Condition - New Section -->
<div class="row mb-4">
    <div class="col-xl-12">
        <div class="card h-100 border-primary" style="border-left: 5px solid #ff6b8b; border-radius: 16px;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md flex-shrink-0">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-sync bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">เงื่อนไขการแสดงผลหน้าแรก (Home Page Alert)</h5>
                            <p class="mb-0 text-muted small">กำหนดหัวข้อที่จะให้น้อง ๆ เห็นจากปุ่มประกาศผลที่หน้าแรก</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-4">
                        <div class="schedule-info d-none d-xl-flex flex-column align-items-end me-3">
                            <?php 
                            $currentRound = $systemSettings->onoff_round ?? '1';
                            $matchedSchedule = array_filter($schedules, fn($s) => $s['schedule_round'] == $currentRound);
                            $matchedSchedule = array_values($matchedSchedule)[0] ?? null;
                            ?>
                            <?php if($matchedSchedule): ?>
                                <div class="small fw-bold text-dark text-end">กำหนดการรอบเลือกล่าสุด (รอบ <?= $currentRound ?>):</div>
                                <div class="small text-primary text-end">
                                    <i class='bx bx-time'></i> <?= $matchedSchedule['schedule_announce'] ? date('d/m/Y H:i', strtotime($matchedSchedule['schedule_announce'])) : 'ไม่ได้ระบุ' ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-check form-switch me-3">
                            <input class="form-check-input" type="checkbox" id="mainSystemToggle" 
                                <?= ($systemSettings->onoff_system == 'on') ? 'checked' : '' ?>
                                onchange="toggleMainSystem(this.checked)"
                                style="cursor: pointer; width: 3.5rem; height: 1.7rem;">
                            <label class="form-check-label ms-1 fw-bold" for="mainSystemToggle">ระบบประกาศผล (ON/OFF)</label>
                        </div>
                        
                        <div class="vr mx-2 h-100"></div>
                        
                        <div style="min-width: 250px;">
                            <label class="small text-muted mb-1 d-block"><i class='bx bx-edit text-primary'></i> หัวข้อที่กำลังแสดงอยู่:</label>
                            <div class="input-group input-group-sm">
                                <select class="form-select border-primary" id="systemAnnounceText" onchange="updateSystemTextQuick(this.value)">
                                    <?php 
                                    $options = [
                                        "ประกาศรายชื่อนักเรียนมีสิทธิ์สอบ",
                                        "ประกาศผลการคัดเลือก"
                                    ];
                                    $currentText = $systemSettings->onoff_system_text ?? 'ประกาศผลการคัดเลือก';
                                    ?>
                                    <?php foreach($options as $opt): ?>
                                        <option value="<?= $opt ?>" <?= ($currentText == $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                    <?php if(!in_array($currentText, $options)): ?>
                                        <option value="<?= $currentText ?>" selected><?= $currentText ?> (กำหนดเอง)</option>
                                    <?php endif; ?>
                                </select>
                                <button class="btn btn-primary" type="button" onclick="Swal.fire({
                                    title: 'กำหนดข้อความเอง',
                                    input: 'text',
                                    inputValue: '<?= $currentText ?>',
                                    showCancelButton: true,
                                    confirmButtonText: 'ตั้งค่า',
                                    cancelButtonText: 'ยกเลิก'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        updateSystemTextQuick(result.value);
                                    }
                                })">
                                    <i class='bx bx-pencil'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcements Grid -->
<div class="row g-4" id="announcementsGrid">
    <?php if (empty($announcements)): ?>
        <div class="col-12">
            <div class="card" style="border-radius: 16px; border: none;">
                <div class="card-body text-center py-5">
                    <i class="bx bx-folder-open" style="font-size: 5rem; color: #ccc;"></i>
                    <h5 class="text-muted mt-3">ยังไม่มีประกาศ</h5>
                    <p class="text-muted">กดปุ่ม "เพิ่มประกาศ" เพื่อเพิ่มไฟล์ประกาศผลใหม่</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($announcements as $announce): ?>
            <div class="col-12" id="announce-card-<?= $announce['announce_id'] ?>">
                <?php $fileUrl = \App\Controllers\Admin\AdminControlAnnouncement::getFileUrl($announce['announce_file']); ?>
                <div class="card announce-card mb-3">
                    <div class="status-strip <?= $announce['announce_status'] === 'on' ? 'active' : 'inactive' ?>"></div>
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-8 col-lg-9">
                                <!-- Type Badge & Toggle -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <?php 
                                    $typeMap = [
                                        'exam_normal' => ['class' => 'type-exam-normal', 'text' => 'มีสิทธิ์สอบเข้า', 'icon' => 'bx-edit-alt'],
                                        'exam_sports' => ['class' => 'type-exam-sports', 'text' => 'มีสิทธิ์คัดตัวนักกีฬา', 'icon' => 'bx-run'],
                                        'passed_normal' => ['class' => 'type-passed-normal', 'text' => 'ผ่านการสอบเข้า', 'icon' => 'bx-check-circle'],
                                        'passed_sports' => ['class' => 'type-passed-sports', 'text' => 'ผ่านการคัดเลือกนักกีฬา', 'icon' => 'bx-trophy'],
                                    ];
                                    $typeData = $typeMap[$announce['announce_type']] ?? $typeMap['exam_normal'];
                                    ?>
                                    <span class="type-badge <?= $typeData['class'] ?> mb-0">
                                        <i class="bx <?= $typeData['icon'] ?>"></i>
                                        <?= $typeData['text'] ?>
                                    </span>
                                    <div class="form-check form-switch ms-auto me-3">
                                        <input class="form-check-input toggle-status" type="checkbox"
                                            data-id="<?= $announce['announce_id'] ?>"
                                            <?= $announce['announce_status'] === 'on' ? 'checked' : '' ?>
                                            style="cursor: pointer; width: 2.5rem; height: 1.2rem;">
                                    </div>
                                </div>

                                <!-- Title -->
                                <h5 class="announce-title mt-0 mb-2"><?= esc($announce['announce_title']) ?></h5>

                                <!-- Meta -->
                                <div class="announce-meta mb-2">
                                    <span class="announce-meta-item">
                                        <i class="bx bx-calendar"></i> <?= esc($announce['announce_year']) ?>
                                    </span>
                                    <span class="announce-meta-item">
                                        <i class="bx bx-repeat"></i> รอบที่ <?= esc($announce['announce_round'] ?? '1') ?>
                                    </span>
                                    <?php if (!empty($announce['announce_reg_level'])): ?>
                                        <span class="announce-meta-item">
                                            <i class="bx bx-graduation"></i> ม.<?= esc($announce['announce_reg_level']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="announce-meta-item">
                                        <i class="bx bx-file"></i> 
                                        <?php 
                                            if ($announce['announce_file_type'] === 'pdf') echo 'PDF';
                                            elseif ($announce['announce_file_type'] === 'link') echo 'ลิงก์';
                                            else echo 'รูปภาพ';
                                        ?>
                                    </span>
                                </div>

                                <?php if (!empty($announce['announce_description'])): ?>
                                    <p class="text-muted small mb-3"><?= esc($announce['announce_description']) ?></p>
                                <?php endif; ?>

                                <!-- Actions -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">
                                        <i class="bx bx-time-five"></i>
                                        <?= date('d/m/Y H:i', strtotime($announce['announce_created_at'])) ?>
                                    </small>
                                    <div class="announce-actions">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="setAsMainAnnounce('<?= addslashes(esc($announce['announce_title'])) ?>')"
                                            title="ตั้งเป็นประกาศหลักที่หน้าแรก">
                                            <i class="bx bx-pin"></i>
                                        </button>
                                        <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-outline-info btn-sm"
                                            title="ดูไฟล์">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-warning btn-sm edit-btn"
                                            data-id="<?= $announce['announce_id'] ?>"
                                            data-title="<?= esc($announce['announce_title']) ?>"
                                            data-type="<?= $announce['announce_type'] ?>"
                                            data-file="<?= $announce['announce_file'] ?>"
                                            data-file-type="<?= $announce['announce_file_type'] ?>"
                                            data-year="<?= $announce['announce_year'] ?>"
                                            data-round="<?= $announce['announce_round'] ?>"
                                            data-level="<?= $announce['announce_reg_level'] ?>"
                                            data-description="<?= esc($announce['announce_description']) ?>"
                                            title="แก้ไข">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-btn"
                                            data-id="<?= $announce['announce_id'] ?>"
                                            data-title="<?= esc($announce['announce_title']) ?>"
                                            title="ลบ">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <!-- File Preview -->
                                <div class="file-preview mb-0" style="height: 140px;" onclick="window.open('<?= $fileUrl ?>', '_blank')">
                                    <?php if ($announce['announce_file_type'] === 'image'): ?>
                                        <img src="<?= $fileUrl ?>" alt="<?= esc($announce['announce_title']) ?>" loading="lazy">
                                    <?php elseif ($announce['announce_file_type'] === 'link'): ?>
                                        <div class="text-center" style="color: #0284c7;">
                                            <i class="bx bx-link-external" style="font-size: 2.5rem;"></i>
                                            <p class="mt-1 mb-0 small">เปิดลิงก์</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="bx bxs-file-pdf pdf-icon" style="font-size: 2.5rem;"></i>
                                            <p class="text-muted mt-1 mb-0 small">เปิด PDF</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: var(--primary-gradient); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="bx bx-plus-circle me-2"></i>เพิ่มประกาศใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addForm" action="<?= site_url('skjadmin/announcements/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="add_title" name="announce_title"
                                    placeholder="ชื่อประกาศ" required>
                                <label for="add_title"><i class="bx bx-tag me-1"></i>ชื่อประกาศ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="add_type" name="announce_type" required>
                                    <optgroup label="ภาคปกติ">
                                        <option value="exam_normal">📝 ประกาศรายชื่อนักเรียนมีสิทธิ์สอบเข้า</option>
                                        <option value="passed_normal">✅ ประกาศรายชื่อนักเรียนผ่านการสอบเข้า</option>
                                    </optgroup>
                                    <optgroup label="ความสามารถพิเศษกีฬา">
                                        <option value="exam_sports">🏀 ประกาศรายชื่อนักเรียนมีสิทธิ์คัดตัวนักกีฬา</option>
                                        <option value="passed_sports">🏆 ประกาศรายชื่อนักเรียนผ่านการคัดเลือกนักกีฬา</option>
                                    </optgroup>
                                </select>
                                <label for="add_type"><i class="bx bx-category me-1"></i>ประเภทประกาศ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="add_year" name="announce_year"
                                    placeholder="ปีการศึกษา" value="<?= $selected_year ?>" required>
                                <label for="add_year"><i class="bx bx-calendar me-1"></i>ปีการศึกษา</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="add_round" name="announce_round">
                                    <option value="1">รอบที่ 1</option>
                                    <option value="2">รอบที่ 2</option>
                                    <option value="3">รอบที่ 3</option>
                                </select>
                                <label for="add_round"><i class="bx bx-repeat me-1"></i>รอบที่</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="add_level" name="announce_reg_level">
                                    <option value="">ทุกระดับชั้น</option>
                                    <option value="1">ม.1</option>
                                    <option value="4">ม.4</option>
                                </select>
                                <label for="add_level"><i class="bx bx-graduation me-1"></i>ระดับชั้น</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="add_desc" name="announce_description"
                                    placeholder="รายละเอียดเพิ่มเติม" style="height: 80px;"></textarea>
                                <label for="add_desc"><i class="bx bx-detail me-1"></i>รายละเอียดเพิ่มเติม (ถ้ามี)</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold d-block mb-2"><i class="bx bx-file-find me-1"></i>รูปแบบข้อมูลประกาศ</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="is_link_toggle" id="modeFileAdd" value="false" checked onchange="toggleAddMode(false)">
                                <label class="btn btn-outline-primary" for="modeFileAdd"><i class="bx bx-upload me-1"></i>อัปโหลดไฟล์ (PDF/รูปภาพ)</label>
                                
                                <input type="radio" class="btn-check" name="is_link_toggle" id="modeLinkAdd" value="true" onchange="toggleAddMode(true)">
                                <label class="btn btn-outline-primary" for="modeLinkAdd"><i class="bx bx-link-external me-1"></i>ใช้ลิงก์ภายนอก (URL)</label>
                            </div>
                            <input type="hidden" name="is_link" id="isLinkAddInput" value="false">
                        </div>

                        <div class="col-12" id="fileSectionAdd">
                            <label class="form-label fw-bold small text-muted">ไฟล์ประกาศ</label>
                            <div class="upload-zone" id="addUploadZone">
                                <i class="bx bx-cloud-upload"></i>
                                <p class="mt-2 mb-1 fw-semibold">คลิกเพื่อเลือกไฟล์ หรือ ลากไฟล์มาวาง</p>
                                <p class="text-muted small mb-0">รองรับ PDF, JPG, PNG (สูงสุด 10MB)</p>
                            </div>
                            <input type="file" class="d-none" id="add_file" name="announce_file"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <div id="addFilePreview" class="mt-2 d-none">
                                <div class="alert alert-success d-flex align-items-center gap-2 mb-0">
                                    <i class="bx bx-check-circle"></i>
                                    <span id="addFileName"></span>
                                    <button type="button" class="btn-close ms-auto" id="addFileClear"></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-none" id="linkSectionAdd">
                            <label class="form-label fw-bold small text-muted">ลิงก์ประกาศ (URL)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-link"></i></span>
                                <input type="url" class="form-control" id="add_link" name="announce_link" placeholder="https://example.com/result.pdf">
                            </div>
                            <div class="form-text">สามารถนำลิงก์จาก Google Drive, Canva หรือเว็บอื่น ๆ มาวางได้เลยครับ</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-primary" id="addSubmitBtn">
                        <i class="bx bx-save me-1"></i>บันทึกประกาศ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ffab00 0%, #ffd740 100%); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="bx bx-edit me-2"></i>แก้ไขประกาศ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" action="<?= site_url('skjadmin/announcements/update') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_id" name="announce_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="edit_title" name="announce_title"
                                    placeholder="ชื่อประกาศ" required>
                                <label for="edit_title"><i class="bx bx-tag me-1"></i>ชื่อประกาศ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="edit_type" name="announce_type" required>
                                    <optgroup label="ภาคปกติ">
                                        <option value="exam_normal">📝 ประกาศรายชื่อนักเรียนมีสิทธิ์สอบเข้า</option>
                                        <option value="passed_normal">✅ ประกาศรายชื่อนักเรียนผ่านการสอบเข้า</option>
                                    </optgroup>
                                    <optgroup label="ความสามารถพิเศษกีฬา">
                                        <option value="exam_sports">🏀 ประกาศรายชื่อนักเรียนมีสิทธิ์คัดตัวนักกีฬา</option>
                                        <option value="passed_sports">🏆 ประกาศรายชื่อนักเรียนผ่านการคัดเลือกนักกีฬา</option>
                                    </optgroup>
                                </select>
                                <label for="edit_type"><i class="bx bx-category me-1"></i>ประเภทประกาศ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="edit_year" name="announce_year"
                                    placeholder="ปีการศึกษา" required>
                                <label for="edit_year"><i class="bx bx-calendar me-1"></i>ปีการศึกษา</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="edit_round" name="announce_round">
                                    <option value="1">รอบที่ 1</option>
                                    <option value="2">รอบที่ 2</option>
                                    <option value="3">รอบที่ 3</option>
                                </select>
                                <label for="edit_round"><i class="bx bx-repeat me-1"></i>รอบที่</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="edit_level" name="announce_reg_level">
                                    <option value="">ทุกระดับชั้น</option>
                                    <option value="1">ม.1</option>
                                    <option value="4">ม.4</option>
                                </select>
                                <label for="edit_level"><i class="bx bx-graduation me-1"></i>ระดับชั้น</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="edit_desc" name="announce_description"
                                    placeholder="รายละเอียดเพิ่มเติม" style="height: 80px;"></textarea>
                                <label for="edit_desc"><i class="bx bx-detail me-1"></i>รายละเอียดเพิ่มเติม (ถ้ามี)</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold d-block mb-2"><i class="bx bx-file-find me-1"></i>รูปแบบข้อมูลประกาศ</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="is_link_toggle_edit" id="modeFileEdit" value="false" onchange="toggleEditMode(false)">
                                <label class="btn btn-outline-warning" for="modeFileEdit"><i class="bx bx-upload me-1"></i>ไฟล์ประกาศ</label>
                                
                                <input type="radio" class="btn-check" name="is_link_toggle_edit" id="modeLinkEdit" value="true" onchange="toggleEditMode(true)">
                                <label class="btn btn-outline-warning" for="modeLinkEdit"><i class="bx bx-link-external me-1"></i>ใช้ลิงก์ภายนอก</label>
                            </div>
                            <input type="hidden" name="is_link" id="isLinkEditInput">
                        </div>

                        <div class="col-12" id="fileSectionEdit">
                            <label class="form-label fw-bold small text-muted">เปลี่ยนไฟล์ประกาศ (ไม่บังคับ)</label>
                            <input type="file" class="form-control" id="edit_file" name="announce_file"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">หากไม่ต้องการเปลี่ยนไฟล์ ให้ปล่อยว่างไว้</div>
                        </div>

                        <div class="col-12 d-none" id="linkSectionEdit">
                            <label class="form-label fw-bold small text-muted">ลิงก์ประกาศ (URL)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-link"></i></span>
                                <input type="url" class="form-control" id="edit_link" name="announce_link" placeholder="https://">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-warning text-white" id="editSubmitBtn">
                        <i class="bx bx-save me-1"></i>บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {

        // Year change
        $('#year').on('change', function() {
            window.location.href = '<?= site_url('skjadmin/announcements?year=') ?>' + $(this).val();
        });

        // =================== Upload Zone ===================
        var uploadZone = document.getElementById('addUploadZone');
        var fileInput = document.getElementById('add_file');

        uploadZone.addEventListener('click', function() {
            fileInput.click();
        });

        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', function() {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                showFilePreview(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                showFilePreview(this.files[0]);
            }
        });

        function showFilePreview(file) {
            $('#addFileName').text(file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)');
            $('#addFilePreview').removeClass('d-none');
            $('#addUploadZone').addClass('d-none');
        }

        $('#addFileClear').on('click', function() {
            fileInput.value = '';
            $('#addFilePreview').addClass('d-none');
            $('#addUploadZone').removeClass('d-none');
        });

        // =================== Add Form Submit ===================
        $('#addForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var $btn = $('#addSubmitBtn');
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>กำลังบันทึก...');

            $.ajax({
                url: '<?= site_url('skjadmin/announcements/store') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด', res.message, 'error');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'เกิดข้อผิดพลาดในการเชื่อมต่อ';
                    if (xhr.status === 500) errorMsg = 'Server Error (500) - โปรดตรวจสอบ Log';
                    if (xhr.status === 403) errorMsg = 'CSRF Token หมดอายุหรือไม่มีสิทธิ์ (403)';
                    Swal.fire('ผิดพลาด (' + xhr.status + ')', errorMsg, 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i>บันทึกประกาศ');
                }
            });
        });

        // =================== Edit Button ===================
        $(document).on('click', '.edit-btn', function() {
            var btn = $(this);
            var id = btn.data('id');
            var fileType = btn.data('file-type');
            var filePath = btn.data('file');

            $('#edit_id').val(id);
            $('#edit_title').val(btn.data('title'));
            $('#edit_type').val(btn.data('type'));
            $('#edit_year').val(btn.data('year'));
            $('#edit_round').val(btn.data('round'));
            $('#edit_level').val(btn.data('level'));
            $('#edit_desc').val(btn.data('description'));

            if (fileType === 'link') {
                $('#modeLinkEdit').prop('checked', true);
                $('#edit_link').val(filePath);
                toggleEditMode(true);
            } else {
                $('#modeFileEdit').prop('checked', true);
                $('#edit_link').val('');
                toggleEditMode(false);
            }
            
            $('#editModal').modal('show');
        });

        // =================== Edit Form Submit ===================
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $('#edit_id').val();

            var $btn = $('#editSubmitBtn');
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>กำลังบันทึก...');

            $.ajax({
                url: '<?= site_url('skjadmin/announcements/update/') ?>' + id,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i>บันทึกการแก้ไข');
                }
            });
        });

        // =================== Toggle Status ===================
        $(document).on('change', '.toggle-status', function() {
            var $toggle = $(this);
            var id = $toggle.data('id');
            var newStatus = $toggle.is(':checked') ? 'on' : 'off';

            $.ajax({
                url: '<?= site_url('skjadmin/announcements/toggle-status') ?>',
                type: 'POST',
                data: {
                    id: id,
                    status: newStatus,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(res) {
                    if (res.success) {
                        var card = $toggle.closest('.announce-card');
                        var strip = card.find('.status-strip');
                        strip.removeClass('active inactive').addClass(newStatus === 'on' ? 'active' : 'inactive');

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: newStatus === 'on' ? 'เปิดแสดงผลประกาศแล้ว' : 'ปิดแสดงผลประกาศแล้ว',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        $toggle.prop('checked', !$toggle.is(':checked'));
                        Swal.fire('ผิดพลาด', res.message, 'error');
                    }
                },
                error: function() {
                    $toggle.prop('checked', !$toggle.is(':checked'));
                }
            });
        });

        // =================== Delete ===================
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');

            Swal.fire({
                title: 'ยืนยันการลบ?',
                html: 'คุณต้องการลบประกาศ <b>"' + title + '"</b> หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: '<i class="bx bx-trash me-1"></i>ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url('skjadmin/announcements/delete/') ?>' + id,
                        type: 'POST',
                        data: {
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        success: function(res) {
                            if (res.success) {
                                $('#announce-card-' + id).fadeOut(300, function() {
                                    $(this).remove();
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบสำเร็จ!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('ผิดพลาด', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                        }
                    });
                }
            });
        });
    });

    // =================== Global System Functions ===================
    function toggleAddMode(isLink) {
        $('#isLinkAddInput').val(isLink);
        if (isLink) {
            $('#fileSectionAdd').addClass('d-none');
            $('#linkSectionAdd').removeClass('d-none');
            $('#add_file').prop('required', false);
            $('#add_link').prop('required', true);
        } else {
            $('#fileSectionAdd').removeClass('d-none');
            $('#linkSectionAdd').addClass('d-none');
            $('#add_file').prop('required', true);
            $('#add_link').prop('required', false);
        }
    }

    function toggleEditMode(isLink) {
        $('#isLinkEditInput').val(isLink);
        if (isLink) {
            $('#fileSectionEdit').addClass('d-none');
            $('#linkSectionEdit').removeClass('d-none');
            $('#edit_link').prop('required', true);
        } else {
            $('#fileSectionEdit').removeClass('d-none');
            $('#linkSectionEdit').addClass('d-none');
            $('#edit_link').prop('required', false);
        }
    }

    function toggleMainSystem(mode) {
        $.ajax({
            url: '<?= site_url('skjadmin/settings/update_status') ?>',
            type: 'POST',
            data: {
                field: 'onoff_system',
                mode: mode ? 'true' : 'false',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(res) {
                if (res.success) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: mode ? 'เปิดระบบประกาศผลแล้ว' : 'ปิดระบบประกาศผลแล้ว'
                    });
                }
            }
        });
    }

    function updateSystemTextQuick(text) {
        $.ajax({
            url: '<?= site_url('skjadmin/settings/update_system_text') ?>',
            type: 'POST',
            data: {
                text: text,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(res) {
                if (res.success) {
                    // Automatically turn on the system when a title is selected
                    $.ajax({
                        url: '<?= site_url('skjadmin/settings/update_status') ?>',
                        type: 'POST',
                        data: {
                            field: 'onoff_system',
                            mode: 'true',
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        success: function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'อัปเดตและเปิดระบบสำเร็จ',
                                text: 'เปลี่ยนหัวข้อเป็น: ' + text,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            }
        });
    }

    function setAsMainAnnounce(title) {
        Swal.fire({
            title: 'ตั้งเป็นประกาศหลัก?',
            text: `ต้องการให้หัวข้อ "${title}" แสดงที่หน้าแรกใช่หรือไม่? (จะเปิดระบบประกาศผลให้อัตโนมัติ)`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#ff6b8b'
        }).then((result) => {
            if (result.isConfirmed) {
                // 1. Update text
                $.ajax({
                    url: '<?= site_url('skjadmin/settings/update_system_text') ?>',
                    type: 'POST',
                    data: {
                        text: title,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(res) {
                        if (res.success) {
                            // 2. Update status to ON
                            toggleMainSystem(true);
                            // Refresh after a delay to show results
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>

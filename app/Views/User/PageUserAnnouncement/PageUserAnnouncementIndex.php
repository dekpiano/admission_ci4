<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* ================================
       Announcement Page Styles
       ================================ */

    /* Page Header */
    .announce-hero {
        background: linear-gradient(135deg, #ff9eb5 0%, #c9aed6 50%, #84d2f6 100%);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .announce-hero::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .announce-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .announce-hero h2 {
        font-weight: 800;
        font-size: 2rem;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
    }

    .announce-hero p {
        font-size: 1.1rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        border: 2px solid #e0e0e0;
        background: white;
        color: #555;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-tab:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        color: #555;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 6px 20px rgba(255, 158, 181, 0.35);
    }

    .filter-tab .badge-count {
        background: rgba(255, 255, 255, 0.3);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .filter-tab.active .badge-count {
        background: rgba(255, 255, 255, 0.4);
    }

    /* Year Selector */
    .year-filter-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        padding: 10px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(255, 158, 181, 0.25);
    }

    .year-filter-wrapper label {
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .year-filter-wrapper select {
        border: none;
        border-radius: 10px;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 1rem;
        background: white;
        color: #ff9eb5;
        min-width: 90px;
    }

    /* Announcement Cards */
    .announce-item {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: pointer;
        height: 100%;
        border: 2px solid transparent;
    }

    .announce-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(255, 158, 181, 0.2);
        border-color: rgba(255, 158, 181, 0.3);
    }

    .announce-item .preview-area {
        width: 100%;
        height: 220px;
        overflow: hidden;
        position: relative;
        background: #f5f5f9;
    }

    .announce-item .preview-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .announce-item:hover .preview-area img {
        transform: scale(1.05);
    }

    .announce-item .preview-area .pdf-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
    }

    .announce-item .preview-area .pdf-placeholder i {
        font-size: 4rem;
        color: #dc3545;
        margin-bottom: 8px;
    }

    .announce-item .preview-area .pdf-placeholder span {
        color: #888;
        font-size: 0.85rem;
    }

    /* Type Ribbon */
    .type-ribbon {
        position: absolute;
        top: 16px;
        left: 0;
        padding: 6px 20px 6px 14px;
        font-weight: 700;
        font-size: 0.82rem;
        color: white;
        border-radius: 0 20px 20px 0;
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 2;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    .type-ribbon.exam_normal { background: linear-gradient(135deg, #03c3ec 0%, #0dcaf0 100%); }
    .type-ribbon.exam_sports { background: linear-gradient(135deg, #ffab00 0%, #ffd740 100%); }
    .type-ribbon.passed_normal { background: linear-gradient(135deg, #71dd37 0%, #28a745 100%); }
    .type-ribbon.passed_sports { background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); }

    .announce-item .card-content {
        padding: 1.25rem 1.5rem;
    }

    .announce-item .card-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: #333;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .announce-item .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 0.75rem;
    }

    .announce-item .meta-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #f5f5f9;
        color: #697a8d;
    }

    .announce-item .card-desc {
        font-size: 0.88rem;
        color: #888;
        margin-bottom: 0.5rem;
    }

    .announce-item .card-date {
        font-size: 0.8rem;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .announce-item .view-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.88rem;
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        margin-top: 0.75rem;
    }

    .announce-item .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 158, 181, 0.4);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #888;
        font-weight: 700;
    }

    .empty-state p {
        color: #aaa;
    }

    /* Animation */
    .announce-item {
        animation: fadeInUp 0.6s ease-out both;
    }

    .announce-item:nth-child(1) { animation-delay: 0.05s; }
    .announce-item:nth-child(2) { animation-delay: 0.1s; }
    .announce-item:nth-child(3) { animation-delay: 0.15s; }
    .announce-item:nth-child(4) { animation-delay: 0.2s; }
    .announce-item:nth-child(5) { animation-delay: 0.25s; }
    .announce-item:nth-child(6) { animation-delay: 0.3s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .announce-hero {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .announce-hero h2 {
            font-size: 1.4rem;
        }

        .announce-hero p {
            font-size: 0.95rem;
        }

        .filter-tabs {
            gap: 6px;
        }

        .filter-tab {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        .announce-item .preview-area {
            height: 180px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<div class="announce-hero">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2><i class="bx bx-megaphone me-2"></i>ประกาศผลการคัดเลือก</h2>
            <p class="mb-0">ตรวจสอบรายชื่อผู้มีสิทธิ์สอบ และผู้มีสิทธิ์เข้าเรียน</p>
        </div>
        <div class="year-filter-wrapper">
            <label><i class="bx bx-calendar me-1"></i>ปี</label>
            <select id="yearFilter">
                <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="<?= base_url('new-admission/announcements?year=' . $selected_year) ?>"
        class="filter-tab <?= empty($selected_type) ? 'active' : '' ?>">
        <i class="bx bx-grid-alt"></i> ทั้งหมด
    </a>
    <a href="<?= base_url('new-admission/announcements?year=' . $selected_year . '&type=exam_normal') ?>"
        class="filter-tab <?= $selected_type === 'exam_normal' ? 'active' : '' ?>">
        <i class="bx bx-edit-alt"></i> มีสิทธิ์สอบเข้า
    </a>
    <a href="<?= base_url('new-admission/announcements?year=' . $selected_year . '&type=exam_sports') ?>"
        class="filter-tab <?= $selected_type === 'exam_sports' ? 'active' : '' ?>">
        <i class="bx bx-run"></i> มีสิทธิ์คัดตัวนักกีฬา
    </a>
    <a href="<?= base_url('new-admission/announcements?year=' . $selected_year . '&type=passed_normal') ?>"
        class="filter-tab <?= $selected_type === 'passed_normal' ? 'active' : '' ?>">
        <i class="bx bx-check-circle"></i> ผ่านการสอบเข้า
    </a>
    <a href="<?= base_url('new-admission/announcements?year=' . $selected_year . '&type=passed_sports') ?>"
        class="filter-tab <?= $selected_type === 'passed_sports' ? 'active' : '' ?>">
        <i class="bx bx-trophy"></i> ผ่านการคัดเลือกนักกีฬา
    </a>
</div>

<!-- Announcements Grid -->
<div class="row g-4">
    <?php if (empty($announcements)): ?>
        <div class="col-12">
            <div class="empty-state">
                <i class="bx bx-folder-open"></i>
                <h5>ยังไม่มีประกาศ</h5>
                <p>ยังไม่มีประกาศผลสำหรับปีการศึกษา <?= esc($selected_year) ?></p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($announcements as $announce): ?>
            <div class="col-md-6 col-lg-4">
                <?php
                $isExternal = ($announce['announce_file_type'] === 'link');
                $fileUrl = $isExternal ? $announce['announce_file'] : \App\Controllers\Admin\AdminControlAnnouncement::getFileUrl($announce['announce_file']);
                ?>
                <div class="announce-item" onclick="window.open('<?= $fileUrl ?>', '_blank')">
                    <!-- Preview Area -->
                    <div class="preview-area">
                        <!-- Type Ribbon -->
                        <?php 
                        $typeMap = [
                            'exam_normal' => ['text' => 'มีสิทธิ์สอบเข้า', 'icon' => 'bx-edit-alt'],
                            'exam_sports' => ['text' => 'มีสิทธิ์คัดตัวนักกีฬา', 'icon' => 'bx-run'],
                            'passed_normal' => ['text' => 'ผ่านการสอบเข้า', 'icon' => 'bx-check-circle'],
                            'passed_sports' => ['text' => 'ผ่านการคัดเลือกนักกีฬา', 'icon' => 'bx-trophy'],
                        ];
                        $typeData = $typeMap[$announce['announce_type']] ?? $typeMap['exam_normal'];
                        ?>
                        <div class="type-ribbon <?= $announce['announce_type'] ?>">
                            <i class="bx <?= $typeData['icon'] ?>"></i>
                            <?= $typeData['text'] ?>
                        </div>

                        <?php if ($announce['announce_file_type'] === 'image'): ?>
                            <img src="<?= $fileUrl ?>" alt="<?= esc($announce['announce_title']) ?>" loading="lazy">
                        <?php elseif ($announce['announce_file_type'] === 'link'): ?>
                            <div class="pdf-placeholder" style="background: rgba(105, 108, 255, 0.1); color: #696cff;">
                                <i class="bx bx-link-external"></i>
                                <span>ลิงก์ภายนอก - คลิกเพื่อเปิด</span>
                            </div>
                        <?php else: ?>
                            <div class="pdf-placeholder">
                                <i class="bx bxs-file-pdf"></i>
                                <span>ไฟล์ PDF - คลิกเพื่อเปิดดู</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Card Content -->
                    <div class="card-content">
                        <h6 class="card-title"><?= esc($announce['announce_title']) ?></h6>

                        <div class="card-meta">
                            <span class="meta-tag">
                                <i class="bx bx-calendar"></i> <?= esc($announce['announce_year']) ?>
                            </span>
                            <span class="meta-tag">
                                <i class="bx bx-repeat"></i> รอบที่ <?= esc($announce['announce_round'] ?? '1') ?>
                            </span>
                            <?php if (!empty($announce['announce_reg_level'])): ?>
                                <span class="meta-tag">
                                    <i class="bx bx-graduation"></i> ม.<?= esc($announce['announce_reg_level']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($announce['announce_description'])): ?>
                            <p class="card-desc"><?= esc($announce['announce_description']) ?></p>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="card-date">
                                <i class="bx bx-time-five"></i>
                                <?= date('d/m/Y', strtotime($announce['announce_created_at'])) ?>
                            </span>
                            <a href="<?= $fileUrl ?>" target="_blank" class="view-btn"
                                onclick="event.stopPropagation()">
                                <i class="bx bx-show"></i> ดูประกาศ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Year filter change
    document.getElementById('yearFilter').addEventListener('change', function() {
        var type = '<?= $selected_type ?>';
        var url = '<?= base_url('new-admission/announcements') ?>?year=' + this.value;
        if (type) url += '&type=' + type;
        window.location.href = url;
    });
</script>
<?= $this->endSection() ?>

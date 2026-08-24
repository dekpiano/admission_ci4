<?php
$status = $recruit['recruit_status'] ?? 'รอการตรวจสอบ';
$isApproved = ($status === 'ผ่านการตรวจสอบ');
$isRejected = (strpos($status, 'ไม่ผ่าน') !== false);
$isPending = (!$isApproved && !$isRejected);

$regLevel = $recruit['recruit_regLevel'] ?? '1';
$imgSrc = get_recruit_file_url(($recruit['recruit_img'] ?? 'default.png'), $regLevel, 'img');
$defaultAvatar = base_url('sneat-assets/img/avatars/1.png');

$certEduUrl = !empty($recruit['recruit_certificateEdu']) ? get_recruit_file_url($recruit['recruit_certificateEdu'], $regLevel, 'certificate') : '';
$certEduBUrl = !empty($recruit['recruit_certificateEduB']) ? get_recruit_file_url($recruit['recruit_certificateEduB'], $regLevel, 'certificateB') : '';
$copyIdUrl = !empty($recruit['recruit_copyidCard']) ? get_recruit_file_url($recruit['recruit_copyidCard'], $regLevel, 'copyidCard') : '';
$copyAddressUrl = !empty($recruit['recruit_copyAddress']) ? get_recruit_file_url($recruit['recruit_copyAddress'], $regLevel, 'copyAddress') : '';

$isSport = (
    (!empty($recruit['recruit_sportPosition']) && $recruit['recruit_sportPosition'] !== '-') ||
    (isset($recruit['course_name_joined']) && mb_strpos($recruit['course_name_joined'], 'กีฬา') !== false) ||
    (isset($recruit['course_branch']) && mb_strpos($recruit['course_branch'], 'กีฬา') !== false) ||
    (isset($recruit['quota_key']) && ($recruit['quota_key'] === 'sport' || strpos($recruit['quota_key'], 'A') === 0))
);
?>

<!-- Modal Header -->
<div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, rgba(255, 107, 139, 0.08) 0%, rgba(86, 204, 242, 0.08) 100%);">
    <div class="d-flex align-items-center gap-2">
        <span class="badge font-monospace fw-bold" style="background: #2b3445; color: #fff;">#<?= sprintf('%04d', $recruit['recruit_id']) ?></span>
        <h5 class="modal-title fw-bold text-dark mb-0">รายละเอียดข้อมูลผู้สมัคร</h5>
        <span class="badge <?= $regLevel === '1' ? 'bg-primary' : 'bg-info' ?>">ม.<?= esc($regLevel) ?></span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<!-- Modal Body -->
<div class="modal-body p-4" style="color: #2b3445; font-family: 'K2D', sans-serif;">
    <!-- Profile Hero Card -->
    <div class="p-3 mb-3 rounded-4 shadow-sm" style="background: linear-gradient(135deg, rgba(255, 107, 139, 0.08) 0%, rgba(86, 204, 242, 0.08) 100%); border: 1px solid rgba(255, 107, 139, 0.15);">
        <div class="d-flex align-items-center gap-3 flex-wrap flex-sm-nowrap">
            <div class="position-relative flex-shrink-0 mx-auto mx-sm-0">
                <img src="<?= $imgSrc ?>" class="rounded-4 shadow-sm object-fit-cover" style="width: 80px; height: 95px; border: 3px solid #fff;" onerror="this.onerror=null;this.src='<?= $defaultAvatar ?>';">
                <span class="position-absolute bottom-0 end-0 badge rounded-pill" style="background: <?= $regLevel === '1' ? '#ff6b8b' : '#0284c7' ?>; font-size: 0.72rem;">
                    ม.<?= esc($regLevel) ?>
                </span>
            </div>
            <div class="flex-grow-1 text-center text-sm-start">
                <div class="d-flex align-items-center justify-content-center justify-content-sm-start gap-2 flex-wrap mb-1">
                    <span class="badge font-monospace fw-bold" style="background: #2b3445; color: #fff; font-size: 0.75rem;">#<?= sprintf('%04d', $recruit['recruit_id']) ?></span>
                    <h5 class="mb-0 fw-bold" style="color: #1e293b;">
                        <?= esc(($recruit['recruit_prefix'] ?? '') . ($recruit['recruit_firstName'] ?? '') . ' ' . ($recruit['recruit_lastName'] ?? '')) ?>
                    </h5>
                    <?php if (!empty($recruit['recruit_round'])): ?>
                        <span class="badge bg-label-dark" style="font-size: 0.72rem;">รอบที่ <?= esc($recruit['recruit_round']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center justify-content-center justify-content-sm-start gap-2 flex-wrap text-muted small mb-2">
                    <span><i class="bx bx-id-card me-1 text-primary"></i><?= esc($recruit['recruit_idCard'] ?? '-') ?></span>
                    <span>•</span>
                    <span><i class="bx bx-phone me-1 text-info"></i><?= esc($recruit['recruit_phone'] ?? '-') ?></span>
                    <span>•</span>
                    <span class="fw-semibold" style="color: #0284c7;"><?= esc($recruit['quota_explain'] ?? $recruit['recruit_category'] ?? 'ทั่วไป') ?></span>
                </div>
                <div class="d-flex align-items-center justify-content-center justify-content-sm-start gap-2 flex-wrap">
                    <?php if ($isApproved): ?>
                        <span class="badge bg-success px-2 py-1 rounded-pill" style="font-size: 0.78rem;"><i class="bx bx-check-circle me-1"></i>ผ่านการตรวจสอบ</span>
                    <?php elseif ($isRejected): ?>
                        <span class="badge bg-danger px-2 py-1 rounded-pill" style="font-size: 0.78rem;"><i class="bx bx-x-circle me-1"></i><?= esc($status) ?></span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.78rem;"><i class="bx bx-time-five me-1"></i>รอการตรวจสอบ</span>
                    <?php endif; ?>

                    <?php if (!empty($recruit['verifier_fname'])): ?>
                        <span class="text-muted small" style="font-size: 0.72rem;">
                            (ผู้ตรวจ: <?= esc($recruit['verifier_prefix'] . $recruit['verifier_fname']) ?> <?= !empty($recruit['recruit_dateUpdate']) ? date('d/m/y H:i', strtotime($recruit['recruit_dateUpdate'])) : '' ?>)
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 1-Click Fast Verification Toolbar -->
    <div class="p-2 mb-3 rounded-3 d-flex align-items-center justify-content-between gap-2 flex-wrap" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
        <span class="small fw-bold text-muted ps-2"><i class="bx bx-check-shield me-1 text-primary"></i>ปรับสถานะด่วน:</span>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-success fw-bold <?= $isApproved ? 'active' : '' ?>" onclick="handleQuickStatusChange(<?= $recruit['recruit_id'] ?>, 'ผ่านการตรวจสอบ')">
                <i class="bx bx-check me-1"></i>อนุมัติผ่าน
            </button>
            <button type="button" class="btn btn-outline-warning fw-bold text-dark <?= $isPending ? 'active' : '' ?>" onclick="handleQuickStatusChange(<?= $recruit['recruit_id'] ?>, 'รอการตรวจสอบ')">
                <i class="bx bx-time-five me-1"></i>รอตรวจ
            </button>
            <button type="button" class="btn btn-outline-danger fw-bold <?= $isRejected ? 'active' : '' ?>" onclick="handleQuickStatusReject(<?= $recruit['recruit_id'] ?>)">
                <i class="bx bx-x me-1"></i>ไม่ผ่าน
            </button>
        </div>
    </div>

    <!-- Nav Tabs -->
    <ul class="nav nav-pills nav-fill mb-3 p-1 rounded-3" style="background: #f1f5f9; gap: 4px;" id="quickViewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active py-2 rounded-2 fw-semibold" id="tab-personal-btn" data-bs-toggle="pill" data-bs-target="#tab-personal" type="button" role="tab" style="font-size: 0.82rem;">
                <i class="bx bx-user me-1"></i>ข้อมูลส่วนตัว
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link py-2 rounded-2 fw-semibold" id="tab-edu-btn" data-bs-toggle="pill" data-bs-target="#tab-edu" type="button" role="tab" style="font-size: 0.82rem;">
                <i class="bx bx-book-bookmark me-1"></i>แผนการเรียน & โรงเรียน
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link py-2 rounded-2 fw-semibold" id="tab-docs-btn" data-bs-toggle="pill" data-bs-target="#tab-docs" type="button" role="tab" style="font-size: 0.82rem;">
                <i class="bx bx-paperclip me-1"></i>หลักฐานแนบ
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="quickViewTabsContent" style="padding-right: 4px;">
        <!-- Tab 1: Personal Info -->
        <div class="tab-pane fade show active" id="tab-personal" role="tabpanel">
            <div class="row g-2">
                <div class="col-sm-6">
                    <div class="p-2 rounded-3 bg-light border">
                        <span class="text-muted small d-block">วันเดือนปีเกิด (พ.ศ.)</span>
                        <span class="fw-semibold text-dark">
                            <?= !empty($recruit['recruit_birthday']) ? esc($datethai->thai_date_fullmonth(strtotime($recruit['recruit_birthday']))) : '-' ?>
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-2 rounded-3 bg-light border">
                        <span class="text-muted small d-block">สัญชาติ / เชื้อชาติ / ศาสนา</span>
                        <span class="fw-semibold text-dark">
                            <?= esc($recruit['recruit_nationality'] ?? '-') ?> / <?= esc($recruit['recruit_race'] ?? '-') ?> / <?= esc($recruit['recruit_religion'] ?? '-') ?>
                        </span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-2 rounded-3 bg-light border">
                        <span class="text-muted small d-block"><i class="bx bx-home-alt me-1 text-primary"></i>ที่อยู่ตามทะเบียนบ้าน</span>
                        <span class="fw-semibold text-dark">
                            <?= esc($recruit['recruit_address'] ?? "เลขที่ {$recruit['recruit_homeNumber']} หมู่ {$recruit['recruit_homeGroup']} ต.{$recruit['recruit_homeSubdistrict']} อ.{$recruit['recruit_homedistrict']} จ.{$recruit['recruit_homeProvince']} {$recruit['recruit_homePostcode']}") ?>
                        </span>
                    </div>
                </div>
                <?php if ($isSport): ?>
                    <div class="col-12">
                        <div class="p-2 rounded-3 border" style="background: rgba(255, 107, 139, 0.05); border-color: rgba(255, 107, 139, 0.2) !important;">
                            <span class="fw-bold small d-block mb-1" style="color: #ff6b8b;"><i class="bx bx-run me-1"></i>ข้อมูลโควตากีฬา & ผู้ปกครอง</span>
                            <div class="d-flex gap-3 flex-wrap small mb-2">
                                <span><strong>รุ่นอายุ:</strong> <?= !empty($recruit['recruit_agegroup']) ? $recruit['recruit_agegroup'] . ' ปี' : '-' ?></span>
                                <span><strong>ตำแหน่ง/ประเภท:</strong> <?= esc($recruit['recruit_sportPosition'] ?? '-') ?></span>
                                <span><strong>ส่วนสูง/น้ำหนัก:</strong> <?= esc($recruit['recruit_height'] ?? '-') ?> ซม. / <?= esc($recruit['recruit_weight'] ?? '-') ?> กก.</span>
                                <span><strong>ชื่อเล่น:</strong> <?= esc($recruit['recruit_nickname'] ?? '-') ?></span>
                            </div>
                            <div class="row g-2 border-top pt-2 mt-1">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">ชื่อ-สกุล บิดา:</span>
                                    <span class="fw-semibold text-dark small"><?= esc($recruit['recruit_fatherName'] ?? '-') ?> (อาชีพ: <?= esc($recruit['recruit_fatherJob'] ?? '-') ?>)</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">ชื่อ-สกุล มารดา:</span>
                                    <span class="fw-semibold text-dark small"><?= esc($recruit['recruit_motherName'] ?? '-') ?> (อาชีพ: <?= esc($recruit['recruit_motherJob'] ?? '-') ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab 2: Major & School -->
        <div class="tab-pane fade" id="tab-edu" role="tabpanel">
            <div class="row g-2 mb-3">
                <div class="col-sm-8">
                    <div class="p-2 rounded-3 bg-light border">
                        <span class="text-muted small d-block"><i class="bx bx-buildings me-1 text-primary"></i>โรงเรียนเดิม</span>
                        <span class="fw-bold text-dark"><?= esc($recruit['recruit_oldSchool'] ?? '-') ?></span>
                        <span class="text-muted small d-block">อ.<?= esc($recruit['recruit_district'] ?? '-') ?> จ.<?= esc($recruit['recruit_province'] ?? '-') ?></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="p-2 rounded-3 bg-light border text-center">
                        <span class="text-muted small d-block">ผลการเรียนเฉลี่ย (GPAX)</span>
                        <span class="fw-bold fs-5" style="color: #ff6b8b;"><?= !empty($recruit['recruit_grade']) ? number_format((float)$recruit['recruit_grade'], 2) : '-' ?></span>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-2 small text-muted"><i class="bx bx-list-ol me-1 text-primary"></i>อันดับแผนการเรียนที่สมัคร</h6>
            <div class="d-flex flex-column gap-2">
                <?php if (!empty($recruit['major_order_list'])): ?>
                    <?php foreach ($recruit['major_order_list'] as $idx => $major): ?>
                        <div class="p-2 rounded-3 d-flex align-items-center justify-content-between border <?= $idx === 0 ? 'border-primary bg-label-primary' : 'bg-light' ?>">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-circle <?= $idx === 0 ? 'bg-primary text-white' : 'bg-secondary' ?>" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                    <?= $idx + 1 ?>
                                </span>
                                <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><?= esc($major) ?></span>
                            </div>
                            <?php if ($idx === 0): ?>
                                <span class="badge bg-primary" style="font-size: 0.68rem;">อันดับ 1 (หลัก)</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-2 rounded-3 bg-light border text-center text-muted small">
                        <?= esc($recruit['course_name_joined'] ?? $recruit['recruit_tpyeRoom'] ?? 'ไม่ได้ระบุอันดับแผนการเรียน') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab 3: Documents Preview Grid -->
        <div class="tab-pane fade" id="tab-docs" role="tabpanel">
            <div class="row g-2">
                <!-- PP1 Front -->
                <div class="col-6 col-md-4">
                    <div class="p-2 rounded-3 border text-center h-100 d-flex flex-column justify-content-between <?= !empty($certEduUrl) ? 'bg-white shadow-sm' : 'bg-light' ?>">
                        <div>
                            <span class="fw-semibold d-block text-truncate small mb-1">ปพ.1 ด้านหน้า</span>
                            <?php if (!empty($certEduUrl)): ?>
                                <a href="<?= $certEduUrl ?>" target="_blank" class="d-block position-relative rounded overflow-hidden mb-1" style="height: 100px; background: #f8fafc;">
                                    <?php if (preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $recruit['recruit_certificateEdu'])): ?>
                                        <img src="<?= $certEduUrl ?>" class="w-100 h-100 object-fit-cover" alt="ปพ.1 หน้า">
                                    <?php else: ?>
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-danger">
                                            <i class="bx bxs-file-pdf fs-1"></i>
                                            <span style="font-size: 0.68rem;">PDF</span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            <?php else: ?>
                                <div class="rounded d-flex flex-column align-items-center justify-content-center text-muted mb-1" style="height: 100px; background: #f1f5f9;">
                                    <i class="bx bx-x-circle fs-3 text-muted"></i>
                                    <span style="font-size: 0.72rem;">ไม่มีไฟล์</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($certEduUrl)): ?>
                            <a href="<?= $certEduUrl ?>" target="_blank" class="btn btn-xs btn-outline-primary w-100 py-1" style="font-size: 0.72rem;">
                                <i class="bx bx-show me-1"></i>เปิดดูไฟล์
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- PP1 Back -->
                <div class="col-6 col-md-4">
                    <div class="p-2 rounded-3 border text-center h-100 d-flex flex-column justify-content-between <?= !empty($certEduBUrl) ? 'bg-white shadow-sm' : 'bg-light' ?>">
                        <div>
                            <span class="fw-semibold d-block text-truncate small mb-1">ปพ.1 ด้านหลัง</span>
                            <?php if (!empty($certEduBUrl)): ?>
                                <a href="<?= $certEduBUrl ?>" target="_blank" class="d-block position-relative rounded overflow-hidden mb-1" style="height: 100px; background: #f8fafc;">
                                    <?php if (preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $recruit['recruit_certificateEduB'])): ?>
                                        <img src="<?= $certEduBUrl ?>" class="w-100 h-100 object-fit-cover" alt="ปพ.1 หลัง">
                                    <?php else: ?>
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-danger">
                                            <i class="bx bxs-file-pdf fs-1"></i>
                                            <span style="font-size: 0.68rem;">PDF</span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            <?php else: ?>
                                <div class="rounded d-flex flex-column align-items-center justify-content-center text-muted mb-1" style="height: 100px; background: #f1f5f9;">
                                    <i class="bx bx-x-circle fs-3 text-muted"></i>
                                    <span style="font-size: 0.72rem;">ไม่มีไฟล์</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($certEduBUrl)): ?>
                            <a href="<?= $certEduBUrl ?>" target="_blank" class="btn btn-xs btn-outline-primary w-100 py-1" style="font-size: 0.72rem;">
                                <i class="bx bx-show me-1"></i>เปิดดูไฟล์
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ID Card Copy -->
                <div class="col-6 col-md-4">
                    <div class="p-2 rounded-3 border text-center h-100 d-flex flex-column justify-content-between <?= !empty($copyIdUrl) ? 'bg-white shadow-sm' : 'bg-light' ?>">
                        <div>
                            <span class="fw-semibold d-block text-truncate small mb-1">สำเนาบัตร ปชช.</span>
                            <?php if (!empty($copyIdUrl)): ?>
                                <a href="<?= $copyIdUrl ?>" target="_blank" class="d-block position-relative rounded overflow-hidden mb-1" style="height: 100px; background: #f8fafc;">
                                    <?php if (preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $recruit['recruit_copyidCard'])): ?>
                                        <img src="<?= $copyIdUrl ?>" class="w-100 h-100 object-fit-cover" alt="สำเนาบัตร">
                                    <?php else: ?>
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-danger">
                                            <i class="bx bxs-file-pdf fs-1"></i>
                                            <span style="font-size: 0.68rem;">PDF</span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            <?php else: ?>
                                <div class="rounded d-flex flex-column align-items-center justify-content-center text-muted mb-1" style="height: 100px; background: #f1f5f9;">
                                    <i class="bx bx-x-circle fs-3 text-muted"></i>
                                    <span style="font-size: 0.72rem;">ไม่มีไฟล์</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($copyIdUrl)): ?>
                            <a href="<?= $copyIdUrl ?>" target="_blank" class="btn btn-xs btn-outline-primary w-100 py-1" style="font-size: 0.72rem;">
                                <i class="bx bx-show me-1"></i>เปิดดูไฟล์
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Footer -->
<div class="modal-footer border-top d-flex align-items-center justify-content-between p-3" style="background: #f8fafc;">
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm btn-info text-white fw-bold" onclick="openQuickEditModal(<?= $recruit['recruit_id'] ?>)">
            <i class="bx bx-edit me-1"></i>แก้ไขข้อมูล
        </button>
        <a href="<?= site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-printer me-1"></i>พิมพ์ใบสมัคร
        </a>
    </div>
    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
        ปิด
    </button>
</div>

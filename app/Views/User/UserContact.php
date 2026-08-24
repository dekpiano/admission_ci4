<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    .contact-hero {
        background: linear-gradient(135deg, #e11d48 0%, #ff2d75 35%, #0284c7 80%, #0369a1 100%);
        border-radius: 24px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(225, 29, 72, 0.25);
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }

    /* Modern Accordion */
    .contact-accordion .accordion-item {
        background-color: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px !important;
        margin-bottom: 14px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .contact-accordion .accordion-item:hover {
        border-color: #e11d48;
        box-shadow: 0 8px 20px rgba(225, 29, 72, 0.08);
    }

    .contact-accordion .accordion-button {
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        background-color: #ffffff;
    }

    .contact-accordion .accordion-button:not(.collapsed) {
        color: #e11d48;
        background-color: #fff1f2;
        box-shadow: none;
        border-bottom: 1.5px solid #ffe4e6;
    }

    .contact-accordion .accordion-body {
        padding: 1.5rem;
        line-height: 1.8;
        color: #334155;
        font-size: 0.95rem;
        background-color: #ffffff;
    }

    /* Contact Info Cards */
    .contact-info-card {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .contact-info-card:hover {
        border-color: #0284c7;
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(2, 132, 199, 0.12);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .map-rounded {
        border-radius: 20px;
        overflow: hidden;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100 contact-page-wrapper">
    
    <!-- Hero Banner -->
    <div class="contact-hero text-center shadow-lg">
        <h1 class="display-6 fw-bold mb-2 text-white">ศูนย์ช่วยเหลือและติดต่อสอบถาม</h1>
        <p class="lead mb-0 text-white" style="opacity: 0.95;">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ • ยินดีให้บริการและตอบทุกข้อสงสัย</p>
    </div>

    <div class="row g-4">
        <!-- FAQ Section -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-none bg-transparent">
                <div class="card-header px-0 bg-transparent border-0 d-flex align-items-center mb-3">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center text-white me-2" style="background: #e11d48; width: 36px; height: 36px;">
                        <i class="bx bx-help-circle fs-5"></i>
                    </div>
                    <h4 class="fw-bold m-0 text-dark">คำถามที่พบบ่อย (FAQs)</h4>
                </div>
                <div class="card-body px-0">
                    <div class="accordion contact-accordion" id="faqAccordion">
                        <?php foreach ($faqs as $index => $faq): ?>
                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header" id="heading<?= $index ?>">
                                <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" 
                                    aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>">
                                    <?= esc($faq['question']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                aria-labelledby="heading<?= $index ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <div class="d-flex">
                                        <i class="bx bx-check-circle text-primary me-2 mt-1 fs-5"></i>
                                        <div><?= esc($faq['answer']) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info & Map Section -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-none bg-transparent mb-4">
                <div class="card-header px-0 bg-transparent border-0 d-flex align-items-center mb-3">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center text-white me-2" style="background: #0284c7; width: 36px; height: 36px;">
                        <i class="bx bx-phone-call fs-5"></i>
                    </div>
                    <h4 class="fw-bold m-0 text-dark">ช่องทางติดต่อเรา</h4>
                </div>
                <div class="card-body px-0">
                    <div class="row g-3">
                        <!-- Phone -->
                        <div class="col-12">
                            <div class="card contact-info-card h-100 shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box text-white me-3" style="background: #0284c7;">
                                        <i class="bx bx-phone fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark">เบอร์โทรศัพท์ฝ่ายรับสมัคร</h6>
                                        <small class="text-muted">โทร : <?= esc($contact_info['phone']) ?></small>
                                        <?php if (!empty($contact_info['phone2'])): ?>
                                            <br><small class="text-muted"><?= esc($contact_info['phone2']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <a href="tel:<?= esc($contact_info['phone']) ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">โทรออก</a>
                                </div>
                            </div>
                        </div>
                        <!-- Facebook -->
                        <div class="col-12">
                            <div class="card contact-info-card h-100 shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box text-white me-3" style="background: #1877f2;">
                                        <i class="bx bxl-facebook fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark">Facebook Page</h6>
                                        <small class="text-muted">สอบถามผ่าน Messenger</small>
                                    </div>
                                    <a href="<?= esc($contact_info['facebook']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">ส่งข้อความ</a>
                                </div>
                            </div>
                        </div>
                        <!-- Line -->
                        <div class="col-6">
                            <div class="card contact-info-card h-100 shadow-sm p-3 text-center">
                                <div class="icon-box text-white mx-auto mb-2" style="background: #06c755;">
                                    <i class="bx bxl-line fs-3"></i>
                                </div>
                                <h6 class="mb-1 fw-bold text-dark">Line Official</h6>
                                <small class="text-muted d-block mb-2"><?= esc($contact_info['line_id']) ?></small>
                                <a href="https://line.me/R/ti/p/<?= esc($contact_info['line_id']) ?>" target="_blank" class="btn btn-xs btn-outline-success w-100 rounded-pill fw-bold">เพิ่มเพื่อน</a>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="col-6">
                            <div class="card contact-info-card h-100 shadow-sm p-3 text-center">
                                <div class="icon-box text-white mx-auto mb-2" style="background: #e11d48;">
                                    <i class="bx bx-envelope fs-4"></i>
                                </div>
                                <h6 class="mb-1 fw-bold text-dark">อีเมล</h6>
                                <small class="text-muted d-block text-truncate mb-2"><?= esc($contact_info['email']) ?></small>
                                <a href="mailto:<?= esc($contact_info['email']) ?>" class="btn btn-xs btn-outline-danger w-100 rounded-pill fw-bold">ส่งอีเมล</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Card -->
            <div class="card shadow-sm map-rounded">
                <div class="card-body p-0">
                    <div style="height: 260px;">
                        <iframe 
                            src="<?= esc($contact_info['google_map']) ?>" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                    <div class="p-3 bg-white">
                        <h6 class="fw-bold mb-1 text-dark"><i class="bx bx-map-pin text-danger me-1"></i> สถานที่ตั้งโรงเรียน</h6>
                        <small class="text-muted d-block mb-3"><?= esc($contact_info['address']) ?></small>
                        <a href="https://maps.google.com/?q=โรงเรียนสวนกุหลาบวิทยาลัย(จิรประวัติ)นครสวรรค์" target="_blank" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold text-white" style="background: #e11d48; border: none;">
                            <i class='bx bx-navigation me-1'></i> นำทางใน Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

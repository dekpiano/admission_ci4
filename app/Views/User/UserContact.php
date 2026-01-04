<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* Clean Premium Design */
    .hero-clean {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 1.5rem;
        padding: 3rem 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }

    /* Modern Accordion */
    .contact-accordion .accordion-item {
        background-color: #fcfcfd;
        border: 1px solid #eef0f2;
        border-radius: 12px !important;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .contact-accordion .accordion-item:hover {
        border-color: #ff9eb5;
        box-shadow: 0 4px 12px rgba(255, 158, 181, 0.1);
    }

    .contact-accordion .accordion-button {
        padding: 1.25rem;
        font-weight: 600;
        font-size: 1.05rem;
        color: #4b4b4b;
        background-color: white;
    }

    .contact-accordion .accordion-button:not(.collapsed) {
        color: #ff9eb5;
        background-color: rgba(255, 158, 181, 0.05);
        box-shadow: none;
        border-bottom: 1px solid rgba(255, 158, 181, 0.1);
    }

    .contact-accordion .accordion-button::after {
        background-size: 1rem;
    }

    .contact-accordion .accordion-body {
        padding: 1.5rem;
        line-height: 1.7;
        color: #5d5d5d;
        font-size: 0.95rem;
        background-color: white;
    }

    /* Contact Info Cards */
    .contact-info-card {
        border: 1px solid #eef0f2;
        transition: all 0.3s ease;
    }

    .contact-info-card:hover {
        border-color: #84d2f6;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(132, 210, 246, 0.1);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .map-rounded {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #eef0f2;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Hero Clean -->
    <div class="hero-clean text-center shadow-sm">
        <h1 class="display-6 fw-bold mb-2 text-white">ศูนย์ช่วยเหลือ (Support Center)</h1>
        <p class="lead mb-0 text-white opacity-75">ค้นหาคำตอบที่คุณสงสัย หรือติดต่อเราผ่านช่องทางต่างๆ</p>
    </div>

    <div class="row g-4">
        <!-- FAQ Section -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-none bg-transparent">
                <div class="card-header px-0 bg-transparent border-0 d-flex align-items-center mb-2">
                    <i class="bx bx-help-circle fs-3 text-primary me-2"></i>
                    <h4 class="fw-bold m-0">คำถามที่พบบ่อย (FAQ)</h4>
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
                                <div class="accordion-body border-top">
                                    <div class="d-flex">
                                        <i class="bx bx-chevron-right text-primary me-2 mt-1"></i>
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
                <div class="card-header px-0 bg-transparent border-0 d-flex align-items-center mb-2">
                    <i class="bx bx-phone-call fs-3 text-info me-2"></i>
                    <h4 class="fw-bold m-0">ช่องทางติดต่อเรา</h4>
                </div>
                <div class="card-body px-0">
                    <div class="row g-3">
                        <!-- Phone -->
                        <div class="col-12">
                            <div class="card contact-info-card h-100 shadow-sm px-3 py-1">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-label-success me-3">
                                        <i class="bx bx-phone fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">เบอร์โทรศัพท์</h6>
                                        <small class="text-muted">โรงเรียน : <?= esc($contact_info['phone']) ?></small><br>
                                        <small class="text-muted"><?= esc($contact_info['phone2']) ?></small>
                                    </div>
                                    <a href="tel:<?= esc($contact_info['phone']) ?>" class="btn btn-sm btn-label-success rounded-pill">โทร</a>
                                </div>
                            </div>
                        </div>
                        <!-- Facebook -->
                        <div class="col-12">
                            <div class="card contact-info-card h-100 shadow-sm px-3 py-1">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-label-primary me-3">
                                        <i class="bx bxl-facebook fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">Facebook Page</h6>
                                        <small class="text-muted">สอบถามผ่าน Messenger</small>
                                    </div>
                                    <a href="<?= esc($contact_info['facebook']) ?>" target="_blank" class="btn btn-sm btn-label-primary rounded-pill">แชท</a>
                                </div>
                            </div>
                        </div>
                        <!-- Line -->
                        <div class="col-6">
                            <div class="card contact-info-card h-100 shadow-sm p-2 text-center">
                                <div class="card-body">
                                    <div class="icon-box bg-label-success mx-auto mb-2" style="background-color: rgba(0, 195, 0, 0.1) !important; color: #008f00 !important;">
                                        <i class="bi bi-line fs-4"></i>
                                    </div>
                                    <h6 class="mb-1 fw-bold">Line Official</h6>
                                    <small class="text-muted d-block mb-2"><?= esc($contact_info['line_id']) ?></small>
                                    <a href="https://line.me/R/ti/p/<?= esc($contact_info['line_id']) ?>" target="_blank" class="btn btn-xs btn-outline-success w-100 rounded-pill">เพิ่มเพื่อน</a>
                                </div>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="col-6">
                            <div class="card contact-info-card h-100 shadow-sm p-2 text-center">
                                <div class="card-body">
                                    <div class="icon-box bg-label-secondary mx-auto mb-2">
                                        <i class="bx bx-envelope fs-4"></i>
                                    </div>
                                    <h6 class="mb-1 fw-bold">อีเมล</h6>
                                    <small class="text-muted d-block text-truncate mb-2"><?= esc($contact_info['email']) ?></small>
                                    <a href="mailto:<?= esc($contact_info['email']) ?>" class="btn btn-xs btn-outline-secondary w-100 rounded-pill">ส่งเมล</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Card -->
            <div class="card shadow-sm map-rounded">
                <div class="card-body p-0">
                    <div style="height: 300px;">
                        <iframe 
                            src="<?= esc($contact_info['google_map']) ?>" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                    <div class="p-3 bg-white">
                        <h6 class="fw-bold mb-1"><i class="bx bx-map-pin text-danger me-1"></i>สถานที่ตั้งโรงเรียน</h6>
                        <small class="text-muted d-block mb-3"><?= esc($contact_info['address']) ?></small>
                        <a href="https://maps.google.com/?q=โรงเรียนสวนกุหลาบวิทยาลัย(จิรประวัติ)นครสวรรค์" target="_blank" class="btn btn-label-danger btn-sm w-100">
                             เปิดใน Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

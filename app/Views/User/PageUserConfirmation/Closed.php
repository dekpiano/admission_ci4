<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    .closed-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1.5px solid #cbd5e1;
        border-top: 4px solid #f59e0b;
        border-bottom: 4px solid #0284c7;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07);
        overflow: hidden;
    }

    .closed-header-icon {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem auto;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
    }

    .btn-status-action {
        background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(225, 29, 72, 0.25);
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
    }

    .btn-status-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(225, 29, 72, 0.35);
        color: #ffffff !important;
    }

    @media (max-width: 576px) {
        .closed-card {
            border-radius: 18px;
        }

        .closed-card .card-body {
            padding: 1.25rem 1rem !important;
        }

        .closed-header-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            margin-bottom: 0.75rem;
        }

        .closed-header-icon i {
            font-size: 1.75rem !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center w-100 mx-auto">
    <div class="col-12 col-lg-6 px-0 px-sm-2">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h5 class="fw-bold mb-0 text-dark">
                <span class="text-muted fw-light">รายงานตัวออนไลน์ /</span> สถานะระบบ
            </h5>
            <span class="badge rounded-pill text-white px-3 py-1.5 fw-bold" style="background: linear-gradient(135deg, #e11d48 0%, #0284c7 100%); font-size: 0.82rem;">
                ระบบรายงานตัวออนไลน์
            </span>
        </div>

        <div class="card closed-card mb-4">
                <div class="card-body text-center p-4 p-md-5">
                    <!-- Icon -->
                    <div class="closed-header-icon">
                        <i class="bx bx-time-five fs-1"></i>
                    </div>

                    <!-- Title -->
                    <h4 class="fw-bold text-dark mb-2">ยังไม่เปิดให้รายงานตัว</h4>

                    <!-- Description -->
                    <p class="text-muted mb-4 small">
                        ระบบรายงานตัวนักเรียนใหม่ยังไม่เปิดให้บริการในขณะนี้ หรือปิดรับการรายงานตัวแล้ว
                    </p>

                    <!-- Info Box -->
                    <div class="alert border rounded-3 mb-4 text-start" style="background: rgba(2, 132, 199, 0.05); border-color: rgba(2, 132, 199, 0.2) !important;">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-info-circle text-primary me-2 fs-4 flex-shrink-0"></i>
                            <div class="small text-muted">
                                กรุณาติดตามประกาศกำหนดการและรายชื่อผู้มีสิทธิ์รายงานตัวจากทางโรงเรียน
                            </div>
                        </div>
                    </div>

                    <!-- Contact Channels -->
                    <div class="rounded-4 p-3 text-start mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <h6 class="fw-bold mb-2 small text-dark"><i class="bx bx-bell text-primary me-1"></i> ช่องทางติดต่อสอบถาม</h6>
                        <ul class="list-unstyled mb-0 small text-muted d-flex flex-column gap-1">
                            <li><i class="bx bx-check-circle text-success me-1"></i> เว็บไซต์โรงเรียน: <strong>skj.ac.th</strong></li>
                            <li><i class="bx bx-check-circle text-success me-1"></i> Facebook: <strong>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</strong></li>
                            <li><i class="bx bx-check-circle text-success me-1"></i> ติดต่อโทร: <strong>056-009-667</strong> (วันและเวลาทำการ)</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('new-admission/status') ?>" class="btn btn-status-action">
                            <i class="bx bx-search-alt me-1"></i> ตรวจสอบสถานะการสมัคร
                        </a>
                        <a href="<?= base_url('new-admission') ?>" class="btn btn-light border rounded-3 py-2 text-muted fw-semibold">
                            <i class="bx bx-home me-1"></i> กลับหน้าหลัก
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Year -->
            <div class="text-center">
                <small class="text-muted">
                    <i class="bx bx-calendar me-1"></i>
                    ปีการศึกษา <?= isset($checkYear) ? esc($checkYear->openyear_year) : (date('Y') + 543) ?>
                </small>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
      <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
        <i class="bx bxs-book fs-3 text-primary"></i>
        การจัดการหลักสูตรและแผนการเรียน
      </h4>
      <p class="text-muted mb-0 small">กำหนดแผนการเรียน ชื่อเต็ม ชื่อย่อ สาขาวิชา และระดับชั้นที่เปิดสอน</p>
    </div>
    <a href="<?= site_url('skjadmin/courses/add') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
      <i class="bx bx-plus-circle me-1"></i> เพิ่มหลักสูตรใหม่
    </a>
  </div>

  <!-- Course Table Card -->
  <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
      <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
        <i class="bx bx-list-ul text-primary fs-4"></i>
        รายชื่อหลักสูตรทั้งหมด
      </h5>
      <span class="badge bg-label-primary rounded-pill px-3 py-1"><?= count($courses ?? []) ?> หลักสูตร</span>
    </div>
    <div class="card-body p-4">
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle" id="coursesTable" style="width: 100%;">
          <thead class="table-light">
            <tr>
              <th style="width: 60px;" class="text-center">ID</th>
              <th style="min-width: 250px;">ชื่อเต็มหลักสูตร</th>
              <th style="width: 120px;" class="text-center">ชื่อย่อ</th>
              <th style="width: 140px;">สาขาวิชา</th>
              <th style="width: 110px;" class="text-center">ระดับชั้น</th>
              <th style="width: 130px;" class="text-center">ช่วงอายุ (ปี)</th>
              <th style="width: 100px;" class="text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($courses)) : ?>
              <?php foreach ($courses as $course) : ?>
                <?php 
                  $isJunior = (strpos($course['course_gradelevel'], '1') !== false || strpos($course['course_gradelevel'], 'ต้น') !== false);
                ?>
                <tr>
                  <td class="text-center fw-bold text-muted"><?= esc($course['course_id']) ?></td>
                  <td>
                    <div class="fw-bold text-dark mb-0"><?= esc($course['course_fullname']) ?></div>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold">
                      <?= esc($course['course_initials']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="text-secondary fw-semibold"><?= esc($course['course_branch'] ?: '-') ?></span>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-label-<?= $isJunior ? 'info' : 'warning' ?> rounded-pill px-3 py-1">
                      <?= esc($course['course_gradelevel']) ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="small text-muted fw-semibold">
                      <?= !empty($course['course_age']) ? esc($course['course_age']) . ' ปี' : '-' ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded fs-5"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                        <a class="dropdown-item py-2" href="<?= site_url('skjadmin/courses/edit/' . $course['course_id']) ?>">
                          <i class="bx bx-edit-alt text-warning me-2"></i> แก้ไขข้อมูล
                        </a>
                        <a class="dropdown-item py-2 text-danger" href="javascript:void(0);" onclick="confirmDeleteCourse(<?= $course['course_id'] ?>)">
                          <i class="bx bx-trash me-2"></i> ลบหลักสูตร
                        </a>
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    $('#coursesTable').DataTable({
      responsive: true,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
      }
    });
  });

  function confirmDeleteCourse(id) {
    Swal.fire({
      title: 'ยืนยันการลบหลักสูตร?',
      text: "คุณแน่ใจหรือไม่ว่าต้องการลบหลักสูตรนี้ การลบอาจส่งผลกระทบต่อข้อมูลการสมัครที่เกี่ยวข้อง",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e11d48',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'ใช่, ลบเลย',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '<?= site_url('skjadmin/courses/delete/') ?>/' + id;
      }
    });
  }
</script>
<?= $this->endSection() ?>

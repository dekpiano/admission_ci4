<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
      <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
        <i class="bx bxs-collection fs-3" style="color: var(--skj-pink);"></i>
        การจัดการโควต้า
      </h4>
      <p class="text-muted mb-0 small">กำหนดประเภทโควต้า ระดับชั้น และหลักสูตรที่เปิดรับสมัคร</p>
    </div>
    <a href="<?= site_url('skjadmin/quotas/add') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
      <i class="bx bx-plus-circle me-1"></i> เพิ่มโควต้าใหม่
    </a>
  </div>

  <?php
    $activeNames = [];
    $activeLevels = [];
    $levelMap = ['1'=>'ม.1', '2'=>'ม.2', '3'=>'ม.3', '4'=>'ม.4', '5'=>'ม.5', '6'=>'ม.6'];

    if (!empty($quotas)) {
      foreach ($quotas as $q) {
        if ($q['quota_status'] == 'on') {
          $activeNames[] = $q['quota_key'];
          
          $levels = explode('|', $q['quota_level']);
          foreach ($levels as $l) {
              $l = trim($l);
              if (isset($levelMap[$l])) {
                  $activeLevels[] = $levelMap[$l];
              } else {
                  $activeLevels[] = $l;
              }
          }
        }
      }
    }
    
    $activeNames = array_unique($activeNames);
    $activeLevels = array_unique($activeLevels);
    natsort($activeLevels);

    $isOpen = !empty($activeNames);
  ?>

  <!-- System Status Banner -->
  <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" 
       style="background: <?= $isOpen ? 'linear-gradient(135deg, rgba(225, 29, 72, 0.06) 0%, rgba(2, 132, 199, 0.06) 100%)' : 'linear-gradient(135deg, rgba(245, 158, 11, 0.06) 0%, rgba(239, 68, 68, 0.06) 100%)' ?>; border-left: 5px solid <?= $isOpen ? 'var(--skj-pink, #e11d48)' : '#f59e0b' ?> !important;">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex align-items-center gap-3">
        <div class="p-3 rounded-circle shadow-sm" style="background: <?= $isOpen ? 'var(--skj-pink, #e11d48)' : '#f59e0b' ?>; color: #ffffff;">
          <i class="bx <?= $isOpen ? 'bx-check-circle' : 'bx-error-circle' ?> fs-3"></i>
        </div>
        <div>
          <h6 class="fw-bold mb-1 text-dark">สถานะระบบเปิดรับสมัครโควต้า</h6>
          <p class="mb-0 small text-secondary">
            <?php if ($isOpen): ?>
              กำลังเปิดรับสมัคร: <strong class="text-primary"><?= implode(', ', $activeNames) ?></strong>
              <?php if (!empty($activeLevels)): ?>
                ในระดับชั้น <span class="badge bg-label-info rounded-pill"><?= implode(' ', $activeLevels) ?></span>
              <?php endif; ?>
            <?php else: ?>
              <span class="text-danger fw-semibold">ขณะนี้ยังไม่มีโควตาที่เปิดรับสมัคร</span> (สามารถเปิดใช้งานได้ที่ตารางด้านล่าง)
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Quotas Table Card -->
  <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
      <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
        <i class="bx bx-list-ul text-primary fs-4"></i>
        รายการโควต้าทั้งหมด
      </h5>
      <span class="badge bg-label-primary rounded-pill px-3 py-1"><?= count($quotas ?? []) ?> รายการ</span>
    </div>
    <div class="card-body p-4">
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle" id="quotasTable" style="width: 100%;">
          <thead class="table-light">
            <tr>
              <th style="width: 60px;" class="text-center">ID</th>
              <th style="min-width: 140px;">Key โควต้า</th>
              <th style="width: 100px;" class="text-center">ระดับชั้น</th>
              <th style="min-width: 220px;">คำอธิบายโควต้า</th>
              <th style="width: 120px;" class="text-center">สถานะเปิด/ปิด</th>
              <th style="width: 140px;" class="text-center">หลักสูตร</th>
              <th style="width: 100px;" class="text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($quotas)) : ?>
              <?php foreach ($quotas as $quota) : ?>
                <tr>
                  <td class="text-center fw-bold text-muted"><?= esc($quota['quota_id']) ?></td>
                  <td>
                    <span class="badge bg-label-primary font-monospace fs-tiny px-2 py-1">
                      <?= esc($quota['quota_key']) ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <?php 
                      $qLevels = explode('|', $quota['quota_level']);
                      foreach ($qLevels as $ql):
                        $lvlText = $levelMap[trim($ql)] ?? $ql;
                    ?>
                      <span class="badge bg-label-info rounded-pill px-2 py-1 me-1"><?= esc($lvlText) ?></span>
                    <?php endforeach; ?>
                  </td>
                  <td>
                    <span class="fw-semibold text-dark"><?= esc($quota['quota_explain']) ?></span>
                  </td>
                  <td class="text-center">
                    <div class="form-check form-switch d-inline-block">
                      <input class="form-check-input" type="checkbox" id="statusSwitch_<?= $quota['quota_id'] ?>" 
                             <?= $quota['quota_status'] == 'on' ? 'checked' : '' ?>
                             onchange="toggleStatus(<?= $quota['quota_id'] ?>, this.checked)" style="cursor: pointer;">
                      <label class="form-check-label small fw-semibold" for="statusSwitch_<?= $quota['quota_id'] ?>">
                        <?= $quota['quota_status'] == 'on' ? '<span class="text-primary fw-bold">เปิด</span>' : '<span class="text-muted">ปิด</span>' ?>
                      </label>
                    </div>
                  </td>
                  <td class="text-center">
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary rounded-pill px-3" 
                            data-bs-toggle="modal" 
                            data-bs-target="#courseModal"
                            data-explain="<?= esc($quota['quota_explain']) ?>"
                            data-courses="<?= esc($quota['course_list_html']) ?>">
                      <i class="bx bx-show me-1"></i> ดูหลักสูตร
                    </button>
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded fs-5"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                        <a class="dropdown-item py-2" href="<?= site_url('skjadmin/quotas/edit/' . $quota['quota_id']) ?>">
                          <i class="bx bx-edit-alt text-warning me-2"></i> แก้ไขข้อมูล
                        </a>
                        <a class="dropdown-item py-2 text-danger" href="javascript:void(0);" onclick="confirmDeleteQuota(<?= $quota['quota_id'] ?>)">
                          <i class="bx bx-trash me-2"></i> ลบโควต้า
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

<!-- Course Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header text-white" style="background: var(--primary-gradient);">
        <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="courseModalTitle">
          <i class="bx bx-book-open fs-4"></i>
          หลักสูตรที่เปิดรับสมัคร
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <h6 class="fw-bold mb-3 text-primary" id="modalQuotaName"></h6>
        <div class="p-3 bg-light rounded-3 border" id="modalCourseContent">
          <!-- Content loaded via JS -->
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    $('#quotasTable').DataTable({
      responsive: true,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
      }
    });

    var courseModal = document.getElementById('courseModal');
    if (courseModal) {
      courseModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var courses = button.getAttribute('data-courses');
        var explain = button.getAttribute('data-explain');
        
        var modalTitle = courseModal.querySelector('#modalQuotaName');
        var modalBody = courseModal.querySelector('#modalCourseContent');
        
        modalTitle.textContent = explain;
        modalBody.innerHTML = courses ? courses : '<span class="text-muted">ไม่มีข้อมูลหลักสูตร</span>';
      });
    }
  });

  function toggleStatus(id, isChecked) {
    const status = isChecked ? 'on' : 'off';
    const label = document.querySelector(`label[for="statusSwitch_${id}"]`);
    
    label.innerHTML = isChecked ? '<span class="text-success">เปิด</span>' : '<span class="text-muted">ปิด</span>';

    Swal.fire({
      title: 'กำลังบันทึก...',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
      url: '<?= site_url('skjadmin/quotas/updateStatus') ?>',
      type: 'POST',
      data: {
        id: id,
        status: status,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
      },
      success: function(response) {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: 'อัปเดตสถานะสำเร็จ',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1000
          }).then(() => {
            location.reload();
          });
        } else {
          document.getElementById(`statusSwitch_${id}`).checked = !isChecked;
          label.innerHTML = !isChecked ? '<span class="text-success">เปิด</span>' : '<span class="text-muted">ปิด</span>';
          Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: response.message || 'ไม่สามารถอัปเดตสถานะได้'
          });
        }
      },
      error: function() {
        document.getElementById(`statusSwitch_${id}`).checked = !isChecked;
        label.innerHTML = !isChecked ? '<span class="text-success">เปิด</span>' : '<span class="text-muted">ปิด</span>';
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว'
        });
      }
    });
  }

  function confirmDeleteQuota(id) {
    Swal.fire({
      title: 'ยืนยันการลบโควต้า?',
      text: "คุณแน่ใจหรือไม่ว่าต้องการลบโควต้านี้ การลบจะไม่สามารถย้อนกลับได้",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e11d48',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'ใช่, ลบเลย',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '<?= site_url('skjadmin/quotas/delete/') ?>/' + id;
      }
    });
  }
</script>
<?= $this->endSection() ?>

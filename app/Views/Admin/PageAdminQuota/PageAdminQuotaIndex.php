<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">การจัดการโควต้า /</span> รายการโควต้า
  </h4>

  <?php
    $activeNames = [];
    $activeLevels = [];
    $levelMap = ['1'=>'ม.1', '2'=>'ม.2', '3'=>'ม.3', '4'=>'ม.4', '5'=>'ม.5', '6'=>'ม.6'];

    if (!empty($quotas)) {
      foreach ($quotas as $q) {
        if ($q['quota_status'] == 'on') {
          $activeNames[] = $q['quota_key'];
          
          // Parse levels (handle both '1|2' and 'ม.1' formats)
          $levels = explode('|', $q['quota_level']);
          foreach ($levels as $l) {
              $l = trim($l);
              if (isset($levelMap[$l])) {
                  $activeLevels[] = $levelMap[$l];
              } else {
                  $activeLevels[] = $l; // Fallback for legacy data
              }
          }
        }
      }
    }
    
    $activeNames = array_unique($activeNames);
    $activeLevels = array_unique($activeLevels);
    
    // Sort levels naturally (M.1, M.2, M.4...)
    natsort($activeLevels);

    if (!empty($activeNames)) {
        $alertClass = "alert-success";
        $icon = "bx-check-circle";
        $msg = "กำลังเปิดรับสมัคร: <strong>" . implode(', ', $activeNames) . "</strong>";
        if (!empty($activeLevels)) {
            $msg .= " ในระดับชั้น <strong>" . implode(' ', $activeLevels) . "</strong>";
        }
    } else {
        $alertClass = "alert-warning";
        $icon = "bx-error";
        $msg = "ขณะนี้ยังไม่มีโควตาที่เปิดรับสมัคร";
    }
  ?>

  <div class="alert <?= $alertClass ?> alert-dismissible" role="alert">
    <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">
      <i class="bx <?= $icon ?> me-2"></i> สถานะระบบรับสมัคร
    </h6>
    <p class="mb-0"><?= $msg ?></p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>



<div class="card">
  <h5 class="card-header">การจัดการโควต้า</h5>
  <div class="card-body">
      <a href="<?= site_url('skjadmin/quotas/add') ?>" class="btn btn-primary mb-3">เพิ่มโควต้าใหม่</a>
      <div class="table-responsive">
        <table class="table table-bordered" id="quotasTable">
          <thead>
            <tr>
              <th data-priority="7">ID</th>
              <th data-priority="5">Key</th>
              <th data-priority="4">ระดับ</th>
              <th data-priority="1">คำอธิบาย</th>
              <th data-priority="2">สถานะ</th>
              <th data-priority="6">หลักสูตรที่เกี่ยวข้อง</th>
              <th data-priority="3">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            <?php if (!empty($quotas)) : ?>
              <?php foreach ($quotas as $quota) : ?>
                <tr>
                  <td><?= esc($quota['quota_id']) ?></td>
                  <td><?= esc($quota['quota_key']) ?></td>
                  <td><span class="badge bg-label-info"><?= esc($quota['quota_level']) ?></span></td>
                  <td><?= esc($quota['quota_explain']) ?></td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="statusSwitch_<?= $quota['quota_id'] ?>" 
                             <?= $quota['quota_status'] == 'on' ? 'checked' : '' ?>
                             onchange="toggleStatus(<?= $quota['quota_id'] ?>, this.checked)">
                      <label class="form-check-label" for="statusSwitch_<?= $quota['quota_id'] ?>">
                        <?= $quota['quota_status'] == 'on' ? 'เปิด' : 'ปิด' ?>
                      </label>
                    </div>
                  </td>
                  <td>
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#courseModal"
                            data-explain="<?= esc($quota['quota_explain']) ?>"
                            data-courses="<?= esc($quota['course_list_html']) ?>">
                      <i class="bx bx-list-ul me-1"></i> ดูหลักสูตร
                    </button>
                  </td>
                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= site_url('skjadmin/quotas/edit/' . $quota['quota_id']) ?>"><i class="bx bx-edit-alt me-1"></i> แก้ไข</a>
                        <a class="dropdown-item" href="<?= site_url('skjadmin/quotas/delete/' . $quota['quota_id']) ?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโควต้านี้?')"><i class="bx bx-trash me-1"></i> ลบ</a>
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

<!-- Course Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="courseModalTitle">หลักสูตรที่เกี่ยวข้อง</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6 class="fw-bold mb-3" id="modalQuotaName"></h6>
        <div class="alert alert-secondary mb-0" role="alert" id="modalCourseContent">
          <!-- Content will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ปิด</button>
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

    // Handle Modal Data
    var courseModal = document.getElementById('courseModal');
    courseModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var courses = button.getAttribute('data-courses');
      var explain = button.getAttribute('data-explain');
      
      var modalTitle = courseModal.querySelector('#modalQuotaName');
      var modalBody = courseModal.querySelector('#modalCourseContent');
      
      modalTitle.textContent = explain;
      modalBody.innerHTML = courses ? courses : 'ไม่มีข้อมูลหลักสูตร';
    });
  });

  function toggleStatus(id, isChecked) {
    const status = isChecked ? 'on' : 'off';
    const label = document.querySelector(`label[for="statusSwitch_${id}"]`);
    
    // Optimistic UI update
    label.textContent = isChecked ? 'เปิด' : 'ปิด';

    // Show loading toast
    const loadingToast = Swal.fire({
      title: 'กำลังบันทึกข้อมูล...',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      }
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
            text: 'กำลังรีโหลดหน้าเว็บ...',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1000
          }).then(() => {
            location.reload(); // Reload to update the top alert
          });
        } else {
          // Revert on failure
          document.getElementById(`statusSwitch_${id}`).checked = !isChecked;
          label.textContent = !isChecked ? 'เปิด' : 'ปิด';
          Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: response.message || 'ไม่สามารถอัปเดตสถานะได้'
          });
        }
      },
      error: function() {
        // Revert on error
        document.getElementById(`statusSwitch_${id}`).checked = !isChecked;
        label.textContent = !isChecked ? 'เปิด' : 'ปิด';
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว'
        });
      }
    });
  }
</script>
<?= $this->endSection() ?>

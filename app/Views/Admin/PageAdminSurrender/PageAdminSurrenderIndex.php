<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">ข้อมูลการรายงานตัว</h5>
      <form method="get" action="<?= site_url('skjadmin/surrender') ?>" class="d-flex align-items-center">
          <label for="year" class="form-label me-2 mb-0">ปีการศึกษา:</label>
          <select name="year" id="year" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
              <?php foreach ($years as $y) : ?>
                  <option value="<?= $y->recruit_year ?>" <?= $y->recruit_year == $selected_year ? 'selected' : '' ?>><?= $y->recruit_year ?></option>
              <?php endforeach; ?>
          </select>
      </form>
  </div>
  <div class="table-responsive p-3">
    <table class="table" id="surrenderTable">
      <thead>
        <tr>
          <th data-priority="1">รหัสผู้สมัคร</th>
          <th data-priority="2">ชื่อ - นามสกุล</th>
          <th data-priority="4">ประเภท</th>
          <th data-priority="5">แผนการเรียน</th>
          <th data-priority="7">สถานะผู้สมัคร</th>
          <th data-priority="3">สถานะรายงานตัว</th>
          <th data-priority="6">จัดการ</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php if (!empty($students)) : ?>
          <?php foreach ($students as $student) : ?>
            <tr>
              <td><?= esc($student->recruit_id) ?></td>
              <td><strong><?= esc($student->recruit_prefix . $student->recruit_firstName . ' ' . $student->recruit_lastName) ?></strong></td>
              <td><?= esc($student->quota_explain ?? $student->recruit_category) ?></td>
              <td><?= esc($student->recruit_tpyeRoom) ?></td>
              <td>
                <?php 
                  $rStatus = $student->recruit_status ?? 'รอตรวจสอบ';
                  $rClass = ($rStatus == 'ผ่านการตรวจสอบ') ? 'bg-label-success' : (($rStatus == 'ไม่ผ่าน') ? 'bg-label-danger' : 'bg-label-warning');
                ?>
                <span class="badge <?= $rClass ?>"><?= esc($rStatus) ?></span>
              </td>
              <td>
                <?php if (!empty($student->stu_UpdateConfirm)) : ?>
                    <span class="badge bg-label-success">รายงานตัวแล้ว</span>
                    <br><small class="text-muted"><?= esc($student->stu_UpdateConfirm) ?></small>
                <?php else : ?>
                    <span class="badge bg-label-warning">ยังไม่รายงานตัว</span>
                <?php endif; ?>
              </td>
              <td>
                 <?php if (!empty($student->stu_UpdateConfirm)) : ?>
                     <a href="<?= site_url('skjadmin/surrender/print/' . $student->recruit_id) ?>" target="_blank" class="btn btn-sm btn-info">
                        <i class="bx bx-printer me-1"></i> พิมพ์ใบรายงานตัว
                     </a>
                 <?php else : ?>
                     <span class="text-muted badge bg-label-danger">รอรายงานตัว</span>
                 <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    $('#surrenderTable').DataTable({
      responsive: true,
      stateSave: true,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
      }
    });
  });

  function confirmSurrender(id, name) {
    Swal.fire({
      title: 'ยืนยันการรายงานตัว?',
      text: `คุณต้องการยืนยันการรายงานตัวของ ${name} ใช่หรือไม่?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ใช่, ยืนยัน!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '<?= site_url('skjadmin/surrender/update') ?>',
          type: 'POST',
          data: {
            recruit_id: id,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
          },
          success: function(response) {
            Swal.fire(
              'สำเร็จ!',
              'บันทึกการรายงานตัวเรียบร้อยแล้ว',
              'success'
            ).then(() => {
              location.reload();
            });
          },
          error: function() {
            Swal.fire(
              'เกิดข้อผิดพลาด!',
              'ไม่สามารถบันทึกข้อมูลได้',
              'error'
            );
          }
        });
      }
    });
  }
</script>
<?= $this->endSection() ?>

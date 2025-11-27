<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<div class="card">
  <h5 class="card-header">การจัดการหลักสูตร</h5>
  <div class="card-body">
      <a href="<?= site_url('skjadmin/courses/add') ?>" class="btn btn-primary mb-3">เพิ่มหลักสูตรใหม่</a>
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered" id="coursesTable">
          <thead>
            <tr>
              <th style="width: 5%;">ID</th>
              <th style="width: 30%;">ชื่อเต็มหลักสูตร</th>
              <th style="width: 15%;">ชื่อย่อ</th>
              <th style="width: 10%;">สาขา</th>
              <th style="width: 10%;">ระดับชั้น</th>
              <th style="width: 15%;">ช่วงอายุ</th>
              <th style="width: 5%;">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            <?php if (!empty($courses)) : ?>
              <?php foreach ($courses as $course) : ?>
                <tr>
                  <td><?= esc($course['course_id']) ?></td>
                  <td style="white-space: normal;"><?= esc($course['course_fullname']) ?></td>
                  <td style="white-space: normal;"><?= esc($course['course_initials']) ?></td>
                  <td><?= esc($course['course_branch']) ?></td>
                  <td><?= esc($course['course_gradelevel']) ?></td>
                  <td style="white-space: normal;"><?= esc($course['course_age'] ?? '-') ?></td>
                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= site_url('skjadmin/courses/edit/' . $course['course_id']) ?>"><i class="bx bx-edit-alt me-1"></i> แก้ไข</a>
                        <a class="dropdown-item" href="<?= site_url('skjadmin/courses/delete/' . $course['course_id']) ?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหลักสูตรนี้?')"><i class="bx bx-trash me-1"></i> ลบ</a>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    $('#coursesTable').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
      }
    });
  });
</script>
<?= $this->endSection() ?>

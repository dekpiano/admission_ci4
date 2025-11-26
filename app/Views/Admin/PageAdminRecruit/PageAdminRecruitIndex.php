<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">ข้อมูลผู้สมัคร</h5>
      <div class="d-flex align-items-center">
          <label for="year" class="form-label me-2 mb-0">ปีการศึกษา:</label>
          <select name="year" id="year" class="form-select form-select-sm" style="width: auto;">
              <?php foreach ($years as $y) : ?>
                  <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?></option>
              <?php endforeach; ?>
          </select>
      </div>
  </div>
  <div class="table-responsive p-3">
    <table class="table" id="recruitsTable">
      <thead>
        <tr>
          <th data-priority="4">รหัสผู้สมัคร</th>
          <th data-priority="1">ชื่อ - นามสกุล</th>
          <th data-priority="6">ประเภท</th>
          <th data-priority="5">หลักสูตร</th>
          <th data-priority="2">สถานะ</th>
          <th data-priority="3">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <!-- Data is loaded via AJAX -->
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    var table = $('#recruitsTable').DataTable({
      stateSave: true,
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: '<?= site_url('skjadmin/recruits/ajax') ?>',
        type: 'POST',
        data: function(d) {
          // Add selected year to the request
          d.year = $('#year').val();
          d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
        }
      },
      columns: [
        { data: 'recruit_id' },
        { data: 'name' },
        { data: 'category' },
        { data: 'course' },
        { data: 'status' },
        { data: 'actions', orderable: false, searchable: false }
      ],
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
        "processing": '<div class="text-center my-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p>กำลังโหลดข้อมูล...</p></div>'
      }
    });

    // Reload table when year is changed
    $('#year').on('change', function() {
      table.ajax.reload();
    });
  });
</script>
<?= $this->endSection() ?>

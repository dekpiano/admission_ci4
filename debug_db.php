<?php
$db = mysqli_connect('skj2025_db', 'root', 'rootpassword', 'skjacth_admission');
if (!$db) {
    // try localhost if skj2025_db fails (depending on where this runs)
    $db = mysqli_connect('localhost', 'root', 'rootpassword', 'skjacth_admission');
}
if (!$db)
    die(mysqli_connect_error());
$q = mysqli_query($db, 'SELECT r.*, q.quota_key, q.quota_explain, c.course_fullname, c.course_branch FROM tb_recruitstudent r LEFT JOIN tb_quota q ON r.recruit_category = q.quota_id LEFT JOIN tb_course c ON r.recruit_tpyeRoom_id = c.course_id ORDER BY r.recruit_id DESC LIMIT 1');
print_r(mysqli_fetch_assoc($q));

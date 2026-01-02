<?php
$db = mysqli_connect('skj2025_db', 'root', 'rootpassword', 'skjacth_admission');
if (!$db) {
    // Try localhost if skj2025_db fails
    $db = mysqli_connect('localhost', 'root', 'rootpassword', 'skjacth_admission');
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
$result = mysqli_query($db, "DESC tb_recruitstudent");
$fields = [];
while ($row = mysqli_fetch_assoc($result)) {
    $fields[] = $row['Field'];
}
echo implode(",", $fields);
mysqli_close($db);

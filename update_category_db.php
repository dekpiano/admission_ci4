<?php
$mysqli = new mysqli("localhost", "root", "", "skjacth_admission");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mysqli->set_charset("utf8");

$updates = [
    "UPDATE tb_recruitstudent SET recruit_category = 'normal' WHERE recruit_category = 'ปกติ'",
    "UPDATE tb_recruitstudent SET recruit_category = 'quotaM4' WHERE recruit_category = 'โควตา'",
    "UPDATE tb_recruitstudent SET recruit_category = 'normal-between' WHERE recruit_category = 'normal-bet'"
];

foreach ($updates as $sql) {
    if ($mysqli->query($sql) === TRUE) {
        echo "Executed: $sql\n";
        echo "Affected rows: " . $mysqli->affected_rows . "\n\n";
    } else {
        echo "Error executing: $sql\n";
        echo "Error: " . $mysqli->error . "\n\n";
    }
}

echo "Update complete.\n";

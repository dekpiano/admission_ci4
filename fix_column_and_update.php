<?php
$mysqli = new mysqli("localhost", "root", "", "skjacth_admission");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mysqli->set_charset("utf8");

// 1. Alter table
$sql = "ALTER TABLE tb_recruitstudent MODIFY recruit_category VARCHAR(50) NOT NULL";
if ($mysqli->query($sql) === TRUE) {
    echo "Column recruit_category modified to VARCHAR(50).\n";
} else {
    echo "Error altering table: " . $mysqli->error . "\n";
    exit();
}

// 2. Update normal-bet
$sql = "UPDATE tb_recruitstudent SET recruit_category = 'normal-between' WHERE recruit_category = 'normal-bet'";
if ($mysqli->query($sql) === TRUE) {
    echo "Executed: $sql\n";
    echo "Affected rows: " . $mysqli->affected_rows . "\n";
} else {
    echo "Error updating: " . $mysqli->error . "\n";
}

<?php
$mysqli = new mysqli("localhost", "root", "", "skjacth_admission");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mysqli->set_charset("utf8");

// Check if they still exist
$result = $mysqli->query("SELECT COUNT(*) as count FROM tb_recruitstudent WHERE recruit_category = 'normal-bet'");
$row = $result->fetch_assoc();
echo "Exact match count: " . $row['count'] . "\n";

$result = $mysqli->query("SELECT COUNT(*) as count FROM tb_recruitstudent WHERE recruit_category LIKE 'normal-bet%'");
$row = $result->fetch_assoc();
echo "Like match count: " . $row['count'] . "\n";

// Try update with LIKE
$sql = "UPDATE tb_recruitstudent SET recruit_category = 'normal-between' WHERE recruit_category LIKE 'normal-bet%'";
if ($mysqli->query($sql) === TRUE) {
    echo "Executed: $sql\n";
    echo "Affected rows: " . $mysqli->affected_rows . "\n\n";
} else {
    echo "Error: " . $mysqli->error . "\n";
}

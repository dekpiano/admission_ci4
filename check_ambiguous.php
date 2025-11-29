<?php
$mysqli = new mysqli("localhost", "root", "", "skjacth_admission");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mysqli->set_charset("utf8");

echo "Checking 'โควตา' records:\n";
$result = $mysqli->query("SELECT recruit_regLevel, COUNT(*) as count FROM tb_recruitstudent WHERE recruit_category = 'โควตา' GROUP BY recruit_regLevel");
while ($row = $result->fetch_assoc()) {
    echo "Level " . $row['recruit_regLevel'] . ": " . $row['count'] . "\n";
}

echo "\nChecking 'normal-bet' records:\n";
$result = $mysqli->query("SELECT recruit_regLevel, COUNT(*) as count FROM tb_recruitstudent WHERE recruit_category = 'normal-bet' GROUP BY recruit_regLevel");
while ($row = $result->fetch_assoc()) {
    echo "Level " . $row['recruit_regLevel'] . ": " . $row['count'] . "\n";
}

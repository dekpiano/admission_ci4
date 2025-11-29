<?php
$mysqli = new mysqli("localhost", "root", "", "skjacth_admission");
$result = $mysqli->query("DESCRIBE tb_recruitstudent recruit_category");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}

<?php
// We'll use a simple way to get columns
require 'app/Config/Database.php';
$db = \Config\Database::connect();
$query = $db->query("SHOW COLUMNS FROM tb_onoffsys");
$columns = $query->getResult();
foreach ($columns as $col) {
    echo $col->Field . "\n";
}

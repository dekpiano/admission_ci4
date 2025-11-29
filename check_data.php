<?php
$mysqli = new mysqli("localhost", "root", "", "skjacth_admission");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mysqli->set_charset("utf8");

// Get Quotas
$quotas = [];
$result = $mysqli->query("SELECT quota_key, quota_explain FROM tb_quota");
while ($row = $result->fetch_assoc()) {
    $quotas[$row['quota_key']] = $row['quota_explain'];
}

echo "Existing Quotas (tb_quota):\n";
print_r($quotas);

// Get distinct categories in recruits
$categories = [];
$result = $mysqli->query("SELECT DISTINCT recruit_category, COUNT(*) as count FROM tb_recruitstudent GROUP BY recruit_category");
while ($row = $result->fetch_assoc()) {
    $categories[$row['recruit_category']] = $row['count'];
}

echo "\nCategories in tb_recruitstudent:\n";
print_r($categories);

// Check for mismatches
echo "\nMismatches (Category in recruit but not in quota keys):\n";
foreach ($categories as $cat => $count) {
    if (!isset($quotas[$cat])) {
        echo " - '$cat' (Count: $count)\n";
    }
}

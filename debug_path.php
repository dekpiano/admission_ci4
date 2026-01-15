<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
$fileName = 'recruitstudent/m1/img/2569-1-6088-00050-15-1-69646526e84bc.png';
$localPath = FCPATH . 'uploads/admission/' . ltrim($fileName, '/');
echo "Local Path: " . $localPath . PHP_EOL;
echo "Exists: " . (file_exists($localPath) ? 'Yes' : 'No') . PHP_EOL;
echo "Is File: " . (is_file($localPath) ? 'Yes' : 'No') . PHP_EOL;

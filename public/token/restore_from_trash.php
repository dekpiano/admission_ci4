<?php
/**
 * Restore Files from Trash API
 * This file should be placed on the remote server at: /token/restore_from_trash.php
 * 
 * Purpose: Restores files from trash back to their original location
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: X-Auth-Token, Authorization, Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Token validation
$validToken = 'Dekpiano2025!!';
$authHeader = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$authHeader = str_replace('Bearer ', '', $authHeader);

if (empty($authHeader) || $authHeader !== $validToken) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Get JSON body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['trash_file']) || !isset($data['trash_path'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields: trash_file, trash_path']);
    exit;
}

$trashFile = $data['trash_file'];
$trashPath = $data['trash_path'];

// Base directories
$baseDir = dirname(__DIR__) . '/uploads';
$trashDir = dirname(__DIR__) . '/uploads/_trash';

// Full paths
$trashFilePath = $trashDir . '/' . $trashPath . '/' . $trashFile;
$metaFilePath = $trashFilePath . '.meta.json';

// Check if trash file exists
if (!file_exists($trashFilePath)) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบไฟล์ในถังขยะ']);
    exit;
}

// Read metadata
$originalPath = null;
$originalName = null;

if (file_exists($metaFilePath)) {
    $metaData = json_decode(file_get_contents($metaFilePath), true);
    $originalPath = $metaData['original_path'] ?? null;
    $originalName = $metaData['original_name'] ?? null;
}

if (!$originalPath || !$originalName) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูล metadata']);
    exit;
}

// Restore file
$destPath = $baseDir . '/' . $originalPath;
$destDir = dirname($destPath);

// Ensure destination directory exists
if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

// Check if original location already has a file
if (file_exists($destPath)) {
    http_response_code(409);
    echo json_encode(['status' => 'error', 'message' => 'มีไฟล์อยู่ในตำแหน่งเดิมแล้ว']);
    exit;
}

// Move file back
if (rename($trashFilePath, $destPath)) {
    // Remove metadata file
    if (file_exists($metaFilePath)) {
        unlink($metaFilePath);
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => 'กู้คืนไฟล์สำเร็จ',
        'restored_to' => $originalPath
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถกู้คืนไฟล์ได้']);
}

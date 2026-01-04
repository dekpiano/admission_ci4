<?php
/**
 * Move Files to Trash API
 * This file should be placed on the remote server at: /token/move_to_trash.php
 * 
 * Purpose: Moves files to a _trash directory instead of deleting them permanently
 * Files in trash will be permanently deleted after 30 days
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

if (!$data || !isset($data['files']) || !isset($data['path'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields: files, path']);
    exit;
}

$files = $data['files'];
$subPath = $data['path'];

// Validate subPath
if (strpos($subPath, '..') !== false) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid path']);
    exit;
}

// Base directories
$baseDir = dirname(__DIR__) . '/uploads';
$trashDir = dirname(__DIR__) . '/uploads/_trash';

// Ensure trash directory exists
if (!is_dir($trashDir)) {
    mkdir($trashDir, 0755, true);
}

// Ensure files is an array
if (!is_array($files)) {
    $files = [$files];
}

$moved = [];
$failed = [];

foreach ($files as $filename) {
    // Validate filename
    if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
        $failed[] = ['file' => $filename, 'reason' => 'Invalid filename'];
        continue;
    }
    
    $sourcePath = $baseDir . '/' . $subPath . '/' . $filename;
    
    if (!file_exists($sourcePath)) {
        $failed[] = ['file' => $filename, 'reason' => 'File not found'];
        continue;
    }
    
    // Create trash subdirectory structure
    $trashSubDir = $trashDir . '/' . $subPath;
    if (!is_dir($trashSubDir)) {
        mkdir($trashSubDir, 0755, true);
    }
    
    // Add timestamp to filename to prevent overwriting and track deletion time
    $timestamp = date('Ymd_His');
    $pathInfo = pathinfo($filename);
    $trashFilename = $pathInfo['filename'] . '_deleted_' . $timestamp . '.' . ($pathInfo['extension'] ?? '');
    $destPath = $trashSubDir . '/' . $trashFilename;
    
    // Create metadata file
    $metaData = [
        'original_path' => $subPath . '/' . $filename,
        'original_name' => $filename,
        'deleted_at' => date('Y-m-d H:i:s'),
        'delete_after' => date('Y-m-d H:i:s', strtotime('+30 days'))
    ];
    
    if (rename($sourcePath, $destPath)) {
        // Save metadata
        file_put_contents($destPath . '.meta.json', json_encode($metaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $moved[] = [
            'original' => $filename,
            'trash_name' => $trashFilename,
            'delete_after' => $metaData['delete_after']
        ];
    } else {
        $failed[] = ['file' => $filename, 'reason' => 'Failed to move'];
    }
}

// Return response
if (count($failed) === 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'ย้ายไฟล์ไปถังขยะเรียบร้อย (จะลบถาวรใน 30 วัน)',
        'moved' => $moved
    ], JSON_UNESCAPED_UNICODE);
} elseif (count($moved) > 0) {
    echo json_encode([
        'status' => 'partial_success',
        'message' => 'ย้ายบางไฟล์ไปถังขยะ',
        'moved' => $moved,
        'failed' => $failed
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'ไม่สามารถย้ายไฟล์ได้',
        'failed' => $failed
    ], JSON_UNESCAPED_UNICODE);
}

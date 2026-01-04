<?php
/**
 * Delete Files API for Remote Upload System
 * This file should be placed on the remote server at: /token/delete.php
 * 
 * Purpose: Deletes specified files from the uploads directory
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

// Remove "Bearer " prefix if present
$authHeader = str_replace('Bearer ', '', $authHeader);

if (empty($authHeader) || $authHeader !== $validToken) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized: Invalid or missing token']);
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

// Validate subPath (prevent directory traversal)
if (strpos($subPath, '..') !== false) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid path']);
    exit;
}

// Base directory for uploads
$baseDir = dirname(__DIR__) . '/uploads';

// Ensure files is an array
if (!is_array($files)) {
    $files = [$files];
}

$deleted = [];
$failed = [];

foreach ($files as $filename) {
    // Validate filename (prevent directory traversal)
    if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
        $failed[] = ['file' => $filename, 'reason' => 'Invalid filename'];
        continue;
    }
    
    $fullPath = $baseDir . '/' . $subPath . '/' . $filename;
    
    if (file_exists($fullPath)) {
        if (unlink($fullPath)) {
            $deleted[] = $filename;
        } else {
            $failed[] = ['file' => $filename, 'reason' => 'Failed to delete'];
        }
    } else {
        // File doesn't exist - consider it as already deleted (success)
        $deleted[] = $filename;
    }
}

// Return response
if (count($failed) === 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'All files deleted successfully',
        'deleted' => $deleted
    ], JSON_UNESCAPED_UNICODE);
} elseif (count($deleted) > 0) {
    echo json_encode([
        'status' => 'partial_success',
        'message' => 'Some files deleted',
        'deleted' => $deleted,
        'failed' => $failed
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete files',
        'failed' => $failed
    ], JSON_UNESCAPED_UNICODE);
}

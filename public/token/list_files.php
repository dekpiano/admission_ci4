<?php
/**
 * List Files API for Cleanup System
 * This file should be placed on the remote server at: /token/list_files.php
 * 
 * Purpose: Returns a list of all files in the admission uploads directory
 * for comparison with database records to find orphan files.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Auth-Token, Authorization, Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Token validation
$validToken = 'Dekpiano2025!!'; // Same token as RemoteUpload library
$authHeader = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';

// Remove "Bearer " prefix if present
$authHeader = str_replace('Bearer ', '', $authHeader);

if (empty($authHeader) || $authHeader !== $validToken) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Invalid or missing token'
    ]);
    exit;
}

// Base directory for uploads
$baseDir = dirname(__DIR__) . '/uploads/admission/recruitstudent';

if (!is_dir($baseDir)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Upload directory not found'
    ]);
    exit;
}

/**
 * Recursively scan directory and return all files
 */
function scanDirectory($dir, $basePath = '') {
    $files = [];
    
    if (!is_dir($dir)) {
        return $files;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = $basePath ? $basePath . '/' . $item : $item;
        
        if (is_dir($fullPath)) {
            // Recursively scan subdirectory
            $files = array_merge($files, scanDirectory($fullPath, $relativePath));
        } else {
            // Add file to list
            $files[] = [
                'name' => $item,
                'path' => 'admission/recruitstudent/' . $relativePath,
                'size' => filesize($fullPath),
                'modified' => date('Y-m-d H:i:s', filemtime($fullPath))
            ];
        }
    }
    
    return $files;
}

try {
    $files = scanDirectory($baseDir);
    
    echo json_encode([
        'status' => 'success',
        'count' => count($files),
        'files' => $files
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error scanning files: ' . $e->getMessage()
    ]);
}

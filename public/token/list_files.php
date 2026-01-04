<?php
/**
 * List Files API for Multiple Systems
 * This file should be placed on the remote server at: /token/list_files.php
 * 
 * Purpose: Returns a list of all files in the specified directory
 * Can be used by any system on the same server.
 * 
 * Parameters:
 *   - path (optional): Subdirectory path under /uploads/ (default: entire uploads folder)
 *   
 * Examples:
 *   - list_files.php (lists all files under /uploads/)
 *   - list_files.php?path=admission/recruitstudent (lists admission files)
 *   - list_files.php?path=news/images (lists news images)
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
$validToken = 'Dekpiano2025!!';
$authHeader = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$authHeader = str_replace('Bearer ', '', $authHeader);

if (empty($authHeader) || $authHeader !== $validToken) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Invalid or missing token'
    ]);
    exit;
}

// Get path parameter (optional)
$subPath = $_GET['path'] ?? $_POST['path'] ?? '';

// Validate path (prevent directory traversal)
if (strpos($subPath, '..') !== false) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid path']);
    exit;
}

// Base directory for uploads
$uploadsDir = dirname(__DIR__) . '/uploads';
$baseDir = $subPath ? $uploadsDir . '/' . trim($subPath, '/') : $uploadsDir;

// Exclude system directories
$excludeDirs = ['_trash', '.git', 'cache', 'temp'];

if (!is_dir($baseDir)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Directory not found: ' . $subPath
    ]);
    exit;
}

/**
 * Recursively scan directory and return all files
 */
function scanDirectory($dir, $basePath = '', $excludeDirs = []) {
    $files = [];
    
    if (!is_dir($dir)) {
        return $files;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        // Skip excluded directories
        if (in_array($item, $excludeDirs)) {
            continue;
        }
        
        // Skip hidden files and system files
        if (in_array($item, ['index.html', '.htaccess', '.gitkeep'])) {
            continue;
        }
        
        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = $basePath ? $basePath . '/' . $item : $item;
        
        if (is_dir($fullPath)) {
            $files = array_merge($files, scanDirectory($fullPath, $relativePath, $excludeDirs));
        } else {
            $files[] = [
                'name' => $item,
                'path' => $relativePath,
                'size' => filesize($fullPath),
                'modified' => date('Y-m-d H:i:s', filemtime($fullPath))
            ];
        }
    }
    
    return $files;
}

try {
    $files = scanDirectory($baseDir, $subPath ?: '', $excludeDirs);
    
    // Calculate total size
    $totalSize = array_sum(array_column($files, 'size'));
    
    echo json_encode([
        'status' => 'success',
        'base_path' => $subPath ?: '(all uploads)',
        'count' => count($files),
        'total_size' => $totalSize,
        'total_size_mb' => round($totalSize / 1024 / 1024, 2),
        'files' => $files
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error scanning files: ' . $e->getMessage()
    ]);
}

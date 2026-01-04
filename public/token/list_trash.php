<?php
/**
 * List Trash Files API
 * This file should be placed on the remote server at: /token/list_trash.php
 * 
 * Purpose: Lists all files in the trash directory with their metadata
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
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
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Trash directory
$trashDir = dirname(__DIR__) . '/uploads/_trash';

if (!is_dir($trashDir)) {
    echo json_encode([
        'status' => 'success',
        'count' => 0,
        'files' => [],
        'message' => 'ถังขยะว่างเปล่า'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Recursively scan trash directory
 */
function scanTrash($dir, $basePath = '') {
    $files = [];
    
    if (!is_dir($dir)) {
        return $files;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        // Skip metadata files
        if (strpos($item, '.meta.json') !== false) {
            continue;
        }
        
        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = $basePath ? $basePath . '/' . $item : $item;
        
        if (is_dir($fullPath)) {
            $files = array_merge($files, scanTrash($fullPath, $relativePath));
        } else {
            // Read metadata if exists
            $metaFile = $fullPath . '.meta.json';
            $meta = null;
            if (file_exists($metaFile)) {
                $meta = json_decode(file_get_contents($metaFile), true);
            }
            
            $files[] = [
                'trash_name' => $item,
                'trash_path' => $basePath,
                'original_name' => $meta['original_name'] ?? $item,
                'original_path' => $meta['original_path'] ?? '',
                'deleted_at' => $meta['deleted_at'] ?? date('Y-m-d H:i:s', filemtime($fullPath)),
                'delete_after' => $meta['delete_after'] ?? '',
                'size' => filesize($fullPath),
                'can_restore' => !empty($meta)
            ];
        }
    }
    
    return $files;
}

try {
    $files = scanTrash($trashDir);
    
    // Sort by deleted_at descending (newest first)
    usort($files, function($a, $b) {
        return strtotime($b['deleted_at']) - strtotime($a['deleted_at']);
    });
    
    // Calculate stats
    $totalSize = array_sum(array_column($files, 'size'));
    $expiredCount = 0;
    $now = time();
    
    foreach ($files as $file) {
        if (!empty($file['delete_after']) && strtotime($file['delete_after']) <= $now) {
            $expiredCount++;
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'count' => count($files),
        'total_size' => $totalSize,
        'expired_count' => $expiredCount,
        'files' => $files
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

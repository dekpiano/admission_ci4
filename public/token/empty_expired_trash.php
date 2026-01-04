<?php
/**
 * Empty Expired Trash API
 * This file should be placed on the remote server at: /token/empty_expired_trash.php
 * 
 * Purpose: Permanently deletes files that have been in trash for more than 30 days
 * This can be called via cron job or manually
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
        'message' => 'ถังขยะว่างเปล่า',
        'deleted' => 0
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Recursively find and delete expired files
 */
function deleteExpired($dir, $now) {
    $deleted = [];
    
    if (!is_dir($dir)) {
        return $deleted;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        
        if (is_dir($fullPath)) {
            $deleted = array_merge($deleted, deleteExpired($fullPath, $now));
            
            // Remove empty directories
            if (count(scandir($fullPath)) == 2) { // Only . and ..
                rmdir($fullPath);
            }
        } else {
            // Skip metadata files (they will be deleted with their main file)
            if (strpos($item, '.meta.json') !== false) {
                continue;
            }
            
            // Check metadata for expiration
            $metaFile = $fullPath . '.meta.json';
            $shouldDelete = false;
            
            if (file_exists($metaFile)) {
                $meta = json_decode(file_get_contents($metaFile), true);
                if (!empty($meta['delete_after']) && strtotime($meta['delete_after']) <= $now) {
                    $shouldDelete = true;
                }
            } else {
                // No metadata - check file age (delete if older than 30 days)
                if (filemtime($fullPath) <= strtotime('-30 days')) {
                    $shouldDelete = true;
                }
            }
            
            if ($shouldDelete) {
                $originalName = $meta['original_name'] ?? $item;
                
                if (unlink($fullPath)) {
                    $deleted[] = $originalName;
                    
                    // Remove metadata file
                    if (file_exists($metaFile)) {
                        unlink($metaFile);
                    }
                }
            }
        }
    }
    
    return $deleted;
}

try {
    $now = time();
    $deleted = deleteExpired($trashDir, $now);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'ลบไฟล์ที่หมดอายุเรียบร้อย',
        'deleted_count' => count($deleted),
        'deleted_files' => $deleted
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

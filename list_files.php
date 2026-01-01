<?php
/**
 * list_files.php
 * Script for listing files in the upload directory to compare with the database.
 * This should be placed on the remote server where 'upload.php' and 'delete.php' are located.
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Auth-Token");
header("Content-Type: application/json");

// --- CONFIGURATION (Must match upload.php) ---
$SECRET_TOKEN = 'Dekpiano2025!!';
$BASE_UPLOAD_DIR = '/var/www/html/uploads/admission/recruitstudent/'; // Root for scanning
// --- END CONFIGURATION ---

// Handle Preflight Request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Function for JSON error
function return_error($message, $http_code = 500)
{
    http_response_code($http_code);
    echo json_encode(["status" => "error", "message" => $message]);
    exit();
}

// 1. Authenticate (Matching the header in controller)
$token = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
if (empty($token) || !hash_equals($SECRET_TOKEN, $token)) {
    return_error("Authentication failed.", 403);
}

// 2. Scan Files
$files_result = [];
if (is_dir($BASE_UPLOAD_DIR)) {
    try {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($BASE_UPLOAD_DIR));
        foreach ($it as $file) {
            if ($file->isFile()) {
                $filename = $file->getFilename();

                // Skip system/hidden files
                if (in_array($filename, ['index.html', '.htaccess', 'default.png']) || substr($filename, 0, 1) === '.') {
                    continue;
                }

                // Get path relative to the root 'uploads/' folder 
                // e.g., 'admission/recruitstudent/m1/img/file.png'
                $fullPath = $file->getPathname();
                $relativePath = str_replace('/var/www/html/uploads/', '', str_replace('\\', '/', $fullPath));

                $files_result[] = [
                    'name' => $filename,
                    'path' => $relativePath,
                    'size' => $file->getSize()
                ];
            }
        }

        echo json_encode([
            'status' => 'success',
            'count' => count($files_result),
            'files' => $files_result
        ]);

    } catch (Exception $e) {
        return_error("Scan failed: " . $e->getMessage());
    }
} else {
    return_error("Base directory not found: " . $BASE_UPLOAD_DIR);
}

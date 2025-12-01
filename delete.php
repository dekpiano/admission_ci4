<?php
// Your CodeIgniter App's Domain. Replace '*' with this for better security.
// Example: header("Access-Control-Allow-Origin: http://my-main-app.com");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Auth-Token");

// --- IMPORTANT SECURITY CONFIGURATION ---
// You MUST set the same secret token that you defined in your CodeIgniter .env file.
$SECRET_TOKEN = 'Dekpiano2025!!'; // <-- PASTE THE TOKEN FROM YOUR .env FILE

// Base directory for all uploads. MUST end with a slash.
// Make sure this path is correct and writable on your server.
$BASE_UPLOAD_DIR = '/var/www/html/uploads/documentcenter/'; 

// --- END OF CONFIGURATION ---

// Handle Preflight Request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');

// Function to return a JSON error
function return_error($message, $http_code = 500) {
    http_response_code($http_code);
    echo json_encode(["status" => "error", "message" => $message]);
    exit();
}

// 1. Authenticate the request
$token = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
if (empty($token) || !hash_equals($SECRET_TOKEN, $token)) {
    return_error("Authentication failed.", 403);
}

// 2. Process the request
try {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (isset($data['files']) && is_array($data['files']) && isset($data['path'])) {
        $subDir = trim($data['path'], " /\ ");
        
        // ================== START: MODIFICATION ==================
        $current_base_dir = $BASE_UPLOAD_DIR; // Default base directory

        // Check if the path is for the 'admission' system
        if (substr($subDir, 0, 10) === 'admission/') {
            // If it is, change the base directory to the root uploads folder
            $current_base_dir = '/var/www/html/uploads/';
        }
        // =================== END: MODIFICATION ===================
        
        // Security check: ensure path does not contain '..' and is not empty.
        if (strpos($subDir, '..') !== false || $subDir === '') {
            return_error("Invalid path specified.", 400);
        }
        
        $targetDir = rtrim($current_base_dir, '/') . '/' . $subDir;

        $deletedFiles = [];
        $failedFiles = [];

        foreach ($data['files'] as $filename) {
            // Security check to prevent directory traversal in the filename itself
            if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
                $failedFiles[] = ['filename' => $filename, 'error' => 'Invalid filename (directory traversal attempt).'];
                continue;
            }

            $filePath = $targetDir . '/' . $filename;
            
            // Final security check: ensure the resolved path is within the base directory
            $realBasePath = realpath($current_base_dir);
            $realFilePath = realpath($filePath);

            if ($realFilePath === false || strpos($realFilePath, $realBasePath) !== 0) {
                 $failedFiles[] = ['filename' => $filename, 'error' => 'Security violation: file is outside the base upload directory.'];
                 continue;
            }

            if (file_exists($filePath) && is_file($filePath)) {
                if (unlink($filePath)) {
                    $deletedFiles[] = $filename;
                } else {
                    $lastError = error_get_last();
                    $failedFiles[] = ['filename' => $filename, 'error' => 'Failed to delete file (permissions issue?). Error: ' . ($lastError['message'] ?? 'Unknown')];
                }
            } else {
                // It's not an error if the file is already gone. We consider it a success.
                $deletedFiles[] = $filename;
            }
        }
        
        $status = 'success';
        $message = 'Delete operation completed.';
        if (!empty($failedFiles)) {
            $status = empty($deletedFiles) ? 'error' : 'partial_success';
            $message = 'Some files could not be deleted.';
        }

        $response = [
            'status' => $status,
            'deleted' => $deletedFiles,
            'failed' => $failedFiles,
            'message' => $message
        ];
        http_response_code(200);

    } else {
        throw new Exception('Invalid request data. Expected JSON with \'files\' (array) and \'path\' (string).');
    }

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
    http_response_code(400);
}

echo json_encode($response);
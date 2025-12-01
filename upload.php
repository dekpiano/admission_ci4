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
$BASE_UPLOAD_DIR = '/var/www/html/uploads/documentcenter/'; // <-- IMPORTANT: This is the absolute path on your remote server.

// --- END OF CONFIGURATION ---

// Handle Preflight Request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

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

// 2. Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (!isset($_FILES['file']) || !isset($_POST['path'])) {
        return_error("Required parameters are missing (file or path).", 400);
    }

    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => "File exceeds server's upload_max_filesize.",
            UPLOAD_ERR_FORM_SIZE  => "File exceeds form's MAX_FILE_SIZE.",
            UPLOAD_ERR_PARTIAL    => "File was only partially uploaded.",
            UPLOAD_ERR_NO_FILE    => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder on server.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the file upload.",
        ];
        $error_message = $upload_errors[$_FILES['file']['error']] ?? "Unknown upload error.";
        return_error($error_message);
    }

    // 3. Validate and create the target directory
    $sub_path = trim($_POST['path'], " /\\"); // Clean the path
    
    $current_base_dir = $BASE_UPLOAD_DIR; // Default base directory

    // Check if the path is for the 'admission' system
    if (substr($sub_path, 0, 10) === 'admission/') {
        // If it is, change the base directory to the root uploads folder
        $current_base_dir = '/var/www/html/uploads/';
    }

    // Security check: ensure path does not contain '..' and is not empty.
    if (strpos($sub_path, '..') !== false || $sub_path === '') {
        return_error("Invalid path specified.", 400);
    }
    
    // Use the determined base directory
    $target_dir = rtrim($current_base_dir, '/') . '/' . $sub_path;

    if (!file_exists($target_dir)) {
        // Create directory with secure permissions (0755)
        if (!mkdir($target_dir, 0755, true)) {
            return_error("Failed to create directory: " . $target_dir);
        }
        // ================== START: MODIFICATION ==================
        // Explicitly set permissions again to override potential umask issues.
        @chmod($target_dir, 0755);
        // =================== END: MODIFICATION ===================
    }

    if (!is_writable($target_dir)) {
        return_error("The directory is not writable: " . $target_dir);
    }

    // 4. Determine filename and move the file
    if (!empty($_POST['desired_filename'])) {
        // Use the filename provided by the main application, but sanitize it again for security
        $rawFilename = basename($_POST['desired_filename']); // Use basename to strip any path info
        $newFileName = preg_replace('/[^a-zA-Z0-9_\-\\.]/', '', $rawFilename); // Remove potentially unsafe characters
        $newFileName = str_replace(' ', '_', $newFileName);
        
        // Final check in case sanitization results in an empty name
        if (empty($newFileName) || $newFileName === '.' || $newFileName === '..') {
            $newFileName = 'document_' . uniqid() . '.dat'; // Fallback to a safe unique name
        }

    } else {
        // Fallback to old method if desired_filename is not provided
        $originalName = basename($_FILES["file"]["name"]);
        
        // Use pathinfo for robust extension handling
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        // Sanitize the filename part
        $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
        if (empty($filename)) {
            $filename = "uploaded_file";
        }
        
        $newFileName = $filename . '_' . uniqid();
        if (!empty($extension)) {
             $newFileName .= '.' . $extension;
        }
    }

    $target_file = $target_dir . '/' . $newFileName;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Set secure file permissions (0644)
        @chmod($target_file, 0644);
        http_response_code(200);
        echo json_encode(["status" => "success", "filename" => $newFileName]);
    } else {
        return_error("Sorry, there was an error moving the uploaded file.");
    }
    
    exit();
}

// Handle other request methods
return_error("Method Not Allowed. Use POST.", 405);
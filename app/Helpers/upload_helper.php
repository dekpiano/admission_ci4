<?php

if (!function_exists('format_id_card')) {
    /**
     * Format Thai Citizen ID (x-xxxx-xxxxx-xx-x)
     * 
     * @param string $id The 13-digit ID
     * @return string Formatted ID
     */
    function format_id_card($id)
    {
        $id = str_replace('-', '', $id ?? '');
        if (strlen($id) == 13) {
            return substr($id, 0, 1) . '-' . substr($id, 1, 4) . '-' . substr($id, 5, 5) . '-' . substr($id, 10, 2) . '-' . substr($id, 12, 1);
        }
        return $id;
    }
}

/**
 * Upload Helper Functions
 * 
 * Helper functions for getting the active upload server URL
 * with automatic fallback support.
 */

if (!function_exists('get_active_upload_server')) {
    /**
     * Get the active upload server URL with fallback support
     * Reads from cache file created by RemoteUpload library
     * ลำดับใหม่: HTTP ก่อน (เพราะ HTTPS มีปัญหา SSL)
     * 
     * NOTE: ฟังก์ชันนี้ใช้สำหรับดึง URL แสดงรูปเท่านั้น
     * ไม่ควร curl ตรวจสอบ server ทุกครั้ง เพราะจะทำให้หน้าเว็บช้า
     * 
     * @return string The active server URL
     */
    function get_active_upload_server(): string
    {
        // สลับลำดับ: HTTP ก่อน
        $primaryServer = "http://118.172.140.151:8000";
        
        // Check cache first (same cache as RemoteUpload library)
        $cacheFile = WRITEPATH . 'cache/active_upload_server.txt';
        
        // Use cache if valid (less than 5 minutes old) - ไม่ต้อง curl ตรวจสอบ
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 300) {
            $cached = trim(file_get_contents($cacheFile));
            if (!empty($cached)) {
                return $cached;
            }
        }
        
        // ถ้าไม่มี cache ให้ใช้ primary server
        // การตรวจสอบ server จริงจะทำใน RemoteUpload library ตอน upload เท่านั้น
        return $primaryServer;
    }
}

if (!function_exists('get_upload_base_url')) {
    /**
     * Get the base URL for uploaded files (admission folder)
     * Supports both remote and local storage
     * 
     * @param bool $preferLocal If true, returns local URL
     * @return string The base URL for admission uploads
     */
    function get_upload_base_url(bool $preferLocal = false): string
    {
        // Check if we should use local URL
        if ($preferLocal) {
            return base_url('uploads/admission/');
        }
        
        $envUrl = getenv('upload.server.baseurl');
        if ($envUrl) {
            return $envUrl;
        }
        
        return get_active_upload_server() . "/uploads/admission/";
    }
}

if (!function_exists('get_token_url')) {
    /**
     * Get the token URL for API endpoints
     * 
     * @param string $endpoint The endpoint filename (e.g., 'upload.php', 'list_files.php')
     * @return string The full token URL
     */
    function get_token_url(string $endpoint = ''): string
    {
        return get_active_upload_server() . "/token/" . $endpoint;
    }
}

if (!function_exists('is_server_available')) {
    /**
     * Check if a server is available
     * 
     * @param string $serverUrl The server URL to check
     * @return bool True if server is available
     */
    function is_server_available(string $serverUrl): bool
    {
        try {
            $ch = curl_init($serverUrl . "/token/upload.php");
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);  // Reduced from 5 to 2 seconds
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);  // Reduced from 3 to 2 seconds
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_errno($ch);
            curl_close($ch);

            return ($error === 0 && $httpCode > 0);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('cache_active_server')) {
    /**
     * Cache the active server to file
     * 
     * @param string $cacheFile Path to cache file
     * @param string $server Server URL to cache
     */
    function cache_active_server(string $cacheFile, string $server): void
    {
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, $server);
    }
}

if (!function_exists('get_recruit_file_url')) {
    /**
     * Generate Direct URL for recruit files (images, documents)
     * ใช้แทน image-proxy เมื่อ server ไม่สามารถ curl ออกได้
     * 
     * Logic: 
     * 1. ตรวจสอบ Local ก่อน
     * 2. อ่าน Cache ว่า server ไหนใช้งานได้ 
     * 3. ถ้าไม่มี cache ให้ลอง HTTPS ก่อน ถ้าไม่ได้ใช้ HTTP
     * 
     * @param string $filename The filename
     * @param int|string $level The student level (1 or 4)
     * @param string $folder The folder name (img, certificate, certificateB, copyidCard)
     * @return string The full URL to the file
     */
    function get_recruit_file_url(?string $filename, $level = 1, string $folder = 'img', bool $forceDirect = false): string
    {
        if (empty($filename)) {
            return base_url('sneat-assets/img/avatars/1.png');
        }
        
        $subPath = "recruitstudent/m{$level}/{$folder}/{$filename}";
        $fullPath = "admission/" . $subPath;

        // 1. ตรวจสอบไฟล์ในเครื่องตัวเองก่อน (รวดเร็วที่สุด)
        $localPath = FCPATH . 'uploads/' . $fullPath;
        if (file_exists($localPath)) {
            return base_url('uploads/' . $fullPath);
        }

        // 2. กรณีพิเศษ: หากต้องการลิงก์ตรงจาก IP จริงๆ (ข้าม Proxy)
        if ($forceDirect) {
            return "http://118.172.140.151:8000/uploads/admission/" . $subPath;
        }

        // 3. ใช้ Image Proxy เป็นตัวช่วยหลัก (สำรองกรณีโดเมนหลักล่ม)
        // Proxy จะไปหาไฟล์จากทั้ง Local และ IP Server ให้เองโดยอัตโนมัติ
        // และจะส่งกลับมาเป็น HTTPS ทำให้ไม่มีปัญหา Mixed Content ครับ
        return base_url("image-proxy?file=" . urlencode($subPath));
    }
}

if (!function_exists('recruit_image_url')) {
    /**
     * Generate URL for recruit student image
     * Uses image-proxy to handle both local and remote images transparently
     * 
     * @param string $filename The image filename
     * @param int|string $level The student level (1 or 4)
     * @param string $type The image type: 'img', 'certificate', 'certificateB', 'copyidCard'
     * @return string The full URL to the image
     */
    function recruit_image_url(?string $filename, $level = 1, string $type = 'img', bool $forceDirect = false): string
    {
        if (empty($filename)) {
            return base_url('public/assets/img/default-user.png');
        }
        
        $folderMap = [
            'img' => 'img',
            'certificate' => 'certificate',
            'certificateB' => 'certificateB',
            'copyidCard' => 'copyidCard'
        ];
        
        $folder = $folderMap[$type] ?? 'img';
        
        return get_recruit_file_url($filename, $level, $folder, $forceDirect);
    }
}

if (!function_exists('recruit_document_url')) {
    /**
     * Generate URL for recruit student documents (ปพ.1, สำเนาบัตร)
     * 
     * @param string $filename The document filename
     * @param int|string $level The student level (1 or 4)
     * @param string $type The document type: 'certificateEdu', 'certificateEduB', 'copyidCard'
     * @return string The full URL to the document
     */
    function recruit_document_url(?string $filename, $level = 1, string $type = 'certificateEdu', bool $forceDirect = false): string
    {
        if (empty($filename)) {
            return '';
        }
        
        $folderMap = [
            'certificateEdu' => 'certificate',
            'certificateEduB' => 'certificateB',
            'copyidCard' => 'copyidCard'
        ];
        
        $folder = $folderMap[$type] ?? 'certificate';
        
        return get_recruit_file_url($filename, $level, $folder, $forceDirect);
    }
}

if (!function_exists('check_local_file_exists')) {
    /**
     * Check if a file exists in local storage
     * 
     * @param string $path Relative path from public/uploads/admission/
     * @return bool True if file exists locally
     */
    function check_local_file_exists(string $path): bool
    {
        $localPath = FCPATH . 'uploads/admission/' . ltrim($path, '/');
        return file_exists($localPath) && is_file($localPath);
    }
}

if (!function_exists('get_file_storage_location')) {
    /**
     * Determine where a file is stored (local or remote)
     * 
     * @param string $filename The filename
     * @param int|string $level The student level
     * @param string $folder The folder name
     * @return string 'local', 'remote', or 'not_found'
     */
    function get_file_storage_location(string $filename, $level = 1, string $folder = 'img'): string
    {
        if (empty($filename)) {
            return 'not_found';
        }
        
        $path = "recruitstudent/m{$level}/{$folder}/{$filename}";
        
        // Check local first
        if (check_local_file_exists($path)) {
            return 'local';
        }
        
        // Assume remote (image-proxy will handle the actual check)
        return 'remote';
    }
}

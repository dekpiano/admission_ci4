<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\RemoteUpload;

/**
 * AdminControlLocalSync
 * 
 * Controller สำหรับ Sync ไฟล์ที่บันทึกใน Local กลับไป Remote Server
 * เมื่อ Server พร้อมใช้งานแล้ว
 */
class AdminControlLocalSync extends BaseController
{
    protected $db;
    protected $remoteUpload;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->remoteUpload = new RemoteUpload();
    }

    /**
     * แสดงหน้า Dashboard สำหรับ Sync ไฟล์
     */
    public function index()
    {
        $data['title'] = 'จัดการไฟล์ Local';
        $data['pendingFiles'] = $this->getPendingLocalFiles();
        $data['serverStatus'] = $this->checkServerStatus();
        
        return view('Admin/PageAdminLocalSync/AdminLocalSync', $data);
    }

    /**
     * ตรวจสอบสถานะ Remote Server
     */
    public function checkServerStatus()
    {
        $servers = [
            'https://skj.nsnpao.go.th' => 'Main Server (skj.nsnpao.go.th)'
        ];

        $status = [];
        foreach ($servers as $url => $name) {
            $status[$name] = [
                'url' => $url,
                'available' => $this->isServerAvailable($url),
                'checked_at' => date('Y-m-d H:i:s')
            ];
        }

        return $status;
    }

    /**
     * ตรวจสอบว่าเซิร์ฟเวอร์พร้อมใช้งานหรือไม่
     */
    protected function isServerAvailable($serverUrl)
    {
        try {
            $ch = curl_init($serverUrl . "/public/index.php");
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_errno($ch);
            curl_close($ch);
            return ($error === 0 && $httpCode > 0 && $httpCode < 500);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ดึงรายการไฟล์ที่รอ Sync ใน Local
     */
    public function getPendingLocalFiles()
    {
        $localUploadPath = FCPATH . 'uploads/admission/recruitstudent/';
        $pendingFiles = [];

        if (!is_dir($localUploadPath)) {
            return $pendingFiles;
        }

        // Scan directory recursively
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($localUploadPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace(FCPATH . 'uploads/', '', $file->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath);
                
                $pendingFiles[] = [
                    'filename' => $file->getFilename(),
                    'path' => $relativePath,
                    'full_path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'size_formatted' => $this->formatFileSize($file->getSize()),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    'type' => $this->getFileType($file->getFilename())
                ];
            }
        }

        return $pendingFiles;
    }

    /**
     * Sync ไฟล์ทั้งหมดจาก Local ไป Remote
     */
    public function syncAll()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('skjadmin/local-sync');
        }

        // ตรวจสอบว่ามี server พร้อมใช้งานหรือไม่
        $serverStatus = $this->checkServerStatus();
        $availableServer = null;

        foreach ($serverStatus as $name => $info) {
            if ($info['available']) {
                $availableServer = $info['url'];
                break;
            }
        }

        if (!$availableServer) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่มีเซิร์ฟเวอร์ใดพร้อมใช้งาน กรุณาลองใหม่ภายหลัง'
            ]);
        }

        $pendingFiles = $this->getPendingLocalFiles();
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        foreach ($pendingFiles as $fileInfo) {
            $result = $this->syncSingleFile($fileInfo, $availableServer);
            if ($result['status'] === 'success') {
                $results['success']++;
            } else {
                $results['failed']++;
            }
            $results['details'][] = $result;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Sync เสร็จสิ้น: สำเร็จ {$results['success']} ไฟล์, ล้มเหลว {$results['failed']} ไฟล์",
            'results' => $results
        ]);
    }

    /**
     * Sync ไฟล์เดี่ยว
     */
    public function syncSingle()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('skjadmin/local-sync');
        }

        $filePath = $this->request->getPost('file_path');
        
        if (empty($filePath)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบ path ของไฟล์'
            ]);
        }

        $fullPath = FCPATH . 'uploads/' . $filePath;
        
        if (!file_exists($fullPath)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบไฟล์ในระบบ'
            ]);
        }

        // ตรวจสอบ server
        $serverStatus = $this->checkServerStatus();
        $availableServer = null;

        foreach ($serverStatus as $name => $info) {
            if ($info['available']) {
                $availableServer = $info['url'];
                break;
            }
        }

        if (!$availableServer) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่มีเซิร์ฟเวอร์ใดพร้อมใช้งาน'
            ]);
        }

        $fileInfo = [
            'filename' => basename($fullPath),
            'path' => $filePath,
            'full_path' => $fullPath
        ];

        $result = $this->syncSingleFile($fileInfo, $availableServer);

        return $this->response->setJSON($result);
    }

    /**
     * ดำเนินการ Sync ไฟล์เดี่ยว
     */
    protected function syncSingleFile($fileInfo, $serverUrl)
    {
        $fullPath = $fileInfo['full_path'];
        $relativePath = $fileInfo['path'];
        $filename = $fileInfo['filename'];

        // แยก subPath จาก relativePath
        // เช่น admission/recruitstudent/m1/img/filename.jpg
        $pathParts = explode('/', $relativePath);
        array_pop($pathParts); // ลบ filename ออก
        $subPath = implode('/', $pathParts);

        try {
            log_message('info', "LocalSync: เริ่ม sync ไฟล์ {$filename} ไปยัง {$serverUrl}");
            log_message('debug', "LocalSync: Full path: {$fullPath}, SubPath: {$subPath}");
            
            // ตรวจสอบและ compress รูปภาพที่ใหญ่เกินไป (> 0.9MB เพื่อมุดผ่าน Nginx 1MB)
            $maxSizeMB = 0.9;
            $currentSize = filesize($fullPath);
            $mimeType = mime_content_type($fullPath);
            $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            
            if ($currentSize > ($maxSizeMB * 1024 * 1024) && in_array($mimeType, $imageTypes)) {
                log_message('info', "LocalSync: ไฟล์ใหญ่เกิน {$maxSizeMB}MB (" . $this->formatFileSize($currentSize) . "), กำลัง compress...");
                
                try {
                    $compressor = new \App\Libraries\ImageCompressor();
                    $compressor->setMaxFileSize($maxSizeMB)
                               ->setMaxDimensions(1600, 2000)
                               ->setJpegQuality(85);
                    
                    $compressResult = $compressor->compress($fullPath);
                    
                    if ($compressResult['success'] && isset($compressResult['compressed']) && $compressResult['compressed']) {
                        log_message('info', "LocalSync: Compress สำเร็จ - " . $compressResult['message']);
                        
                        // ถ้าแปลงเป็น JPG ให้เปลี่ยนชื่อไฟล์
                        if (isset($compressResult['output_path']) && $compressResult['output_path'] !== $fullPath) {
                            // ลบไฟล์เดิม
                            @unlink($fullPath);
                            $fullPath = $compressResult['output_path'];
                            $filename = basename($fullPath);
                            log_message('info', "LocalSync: เปลี่ยนชื่อไฟล์เป็น {$filename}");
                        }
                    }
                } catch (\Exception $e) {
                    log_message('warning', "LocalSync: ไม่สามารถ compress ได้: " . $e->getMessage());
                }
            }
            
            // อัปโหลดไป Remote Server
            $result = $this->uploadToRemote($fullPath, $subPath, $filename, $serverUrl);

            if ($result && isset($result['status']) && $result['status'] === 'success') {
                log_message('info', "LocalSync: อัปโหลดสำเร็จ {$filename}");
                
                // อัปเดตฐานข้อมูล
                $dbUpdated = $this->updateDatabaseReferences($filename, $result['filename'] ?? $filename);
                log_message('debug', "LocalSync: อัปเดต DB {$dbUpdated} records สำหรับ {$filename}");

                // ลบไฟล์ Local
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    log_message('info', "LocalSync: ลบไฟล์ local สำเร็จ {$fullPath}");
                    
                    // ลบ folder ถ้าว่างเปล่า
                    $this->removeEmptyDirectories(dirname($fullPath));
                }

                return [
                    'status' => 'success',
                    'message' => "Sync สำเร็จ: {$filename}",
                    'filename' => $filename,
                    'remote_filename' => $result['filename'] ?? $filename,
                    'db_updated' => $dbUpdated
                ];
            }

            // Log error details
            $errorMessage = $result['message'] ?? 'ไม่ทราบสาเหตุ';
            log_message('error', "LocalSync: อัปโหลดล้มเหลว {$filename}");
            log_message('error', "LocalSync: Error message: {$errorMessage}");
            log_message('error', "LocalSync: Full result: " . json_encode($result, JSON_UNESCAPED_UNICODE));
            
            return [
                'status' => 'error',
                'message' => "อัปโหลดล้มเหลว: " . $errorMessage,
                'filename' => $filename,
                'debug' => $result // เพิ่ม debug info
            ];

        } catch (\Exception $e) {
            log_message('error', "LocalSync: Exception สำหรับ {$filename}: " . $e->getMessage());
            log_message('error', "LocalSync: Stack trace: " . $e->getTraceAsString());
            
            return [
                'status' => 'error',
                'message' => "Error: " . $e->getMessage(),
                'filename' => $filename
            ];
        }
    }

    /**
     * อัปโหลดไฟล์ไป Remote Server โดยตรง
     */
    protected function uploadToRemote($filePath, $subPath, $filename, $serverUrl)
    {
        $token = trim(getenv('upload.secret.token') ?: "Dekpiano2025!!");
        $url = $serverUrl . "/token/upload.php"; // ยังคงใช้ endpoint เดิมใน server ปลายทาง

        try {
            $mimeType = mime_content_type($filePath);
            $fileSize = filesize($filePath);
            
            log_message('debug', "LocalSync Upload: URL={$url}, File={$filename}, Size=" . $this->formatFileSize($fileSize) . ", MimeType={$mimeType}");
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120); // เพิ่ม timeout สำหรับไฟล์ใหญ่
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-Auth-Token: ' . $token,
                'Authorization: Bearer ' . $token
            ]);
            
            $postData = [
                'path' => $subPath,
                'file' => new \CURLFile($filePath, $mimeType, $filename),
                'desired_filename' => $filename
            ];
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            
            $startTime = microtime(true);
            $response = curl_exec($ch);
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlInfo = curl_getinfo($ch);
            $error = curl_error($ch);
            $errorCode = curl_errno($ch);
            curl_close($ch);
            
            log_message('debug', "LocalSync Upload: HTTP={$httpCode}, Duration={$duration}s, Response length=" . strlen($response));

            if ($error) {
                log_message('error', "LocalSync cURL Error [{$errorCode}]: {$error}");
                log_message('error', "LocalSync cURL Info: " . json_encode([
                    'url' => $curlInfo['url'],
                    'http_code' => $httpCode,
                    'connect_time' => $curlInfo['connect_time'],
                    'total_time' => $curlInfo['total_time'],
                    'namelookup_time' => $curlInfo['namelookup_time'],
                    'primary_ip' => $curlInfo['primary_ip'] ?? 'N/A'
                ]));
                
                // แปลง error code เป็นข้อความที่เข้าใจง่าย
                $friendlyError = $this->getCurlErrorMessage($errorCode, $error);
                return ['status' => 'error', 'message' => $friendlyError, 'curl_error' => $error, 'curl_code' => $errorCode];
            }

            $result = json_decode($response, true);
            
            if ($httpCode >= 200 && $httpCode < 300 && isset($result['status']) && $result['status'] === 'success') {
                log_message('info', "LocalSync Upload Success: {$filename} -> Remote in {$duration}s");
                return $result;
            }

            // Log error response
            log_message('error', "LocalSync Upload Failed: HTTP {$httpCode}");
            log_message('error', "LocalSync Response: " . substr($response, 0, 500)); // จำกัด log
            
            // แปลง HTTP code เป็นข้อความที่เข้าใจง่าย
            $friendlyMessage = $this->getHttpErrorMessage($httpCode, $result['message'] ?? null);
            return ['status' => 'error', 'message' => $friendlyMessage, 'http_code' => $httpCode, 'response' => $result];

        } catch (\Exception $e) {
            log_message('error', "LocalSync Upload Exception: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    /**
     * อัปเดตการอ้างอิงในฐานข้อมูล
     */
    protected function updateDatabaseReferences($localFilename, $remoteFilename)
    {
        $updated = 0;

        // อัปเดต tb_recruitstudent สำหรับทุก column ที่เกี่ยวข้อง
        $columns = ['recruit_img', 'recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard'];

        foreach ($columns as $column) {
            // หา record ที่มี filename นี้
            $result = $this->db->table('tb_recruitstudent')
                ->where($column, $localFilename)
                ->update([$column => $remoteFilename]);
            
            $updated += $this->db->affectedRows();
        }

        log_message('info', "LocalSync: Updated {$updated} database references for {$localFilename}");

        return $updated;
    }

    /**
     * ลบ Folder ว่างเปล่า
     */
    protected function removeEmptyDirectories($path)
    {
        $basePath = FCPATH . 'uploads/admission';
        
        // ไม่ลบถ้าเป็น base path หรือสูงกว่า
        if (strlen($path) <= strlen($basePath)) {
            return;
        }

        // ลบถ้า folder ว่างเปล่า
        if (is_dir($path) && count(scandir($path)) === 2) { // . และ ..
            rmdir($path);
            // ลบ parent recursively
            $this->removeEmptyDirectories(dirname($path));
        }
    }

    /**
     * API: ตรวจสอบสถานะ Server (สำหรับ Auto-sync)
     */
    public function apiCheckStatus()
    {
        $serverStatus = $this->checkServerStatus();
        $pendingCount = count($this->getPendingLocalFiles());
        $hasAvailableServer = false;

        foreach ($serverStatus as $info) {
            if ($info['available']) {
                $hasAvailableServer = true;
                break;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'servers' => $serverStatus,
            'pending_files' => $pendingCount,
            'can_sync' => $hasAvailableServer && $pendingCount > 0,
            'checked_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Format file size
     */
    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Get file type from filename
     */
    protected function getFileType($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $docExts = ['pdf', 'doc', 'docx'];
        
        if (in_array($ext, $imageExts)) return 'image';
        if (in_array($ext, $docExts)) return 'document';
        return 'file';
    }

    /**
     * แปลง cURL error code เป็นข้อความที่เข้าใจง่าย
     */
    protected function getCurlErrorMessage($errorCode, $errorMessage)
    {
        $errorMessages = [
            6  => 'ไม่สามารถเชื่อมต่อ Server ได้ (DNS Resolution Failed)',
            7  => 'Server ปฏิเสธการเชื่อมต่อ (Connection Refused)',
            28 => 'หมดเวลาเชื่อมต่อ (Connection Timeout) - Server ตอบสนองช้าหรือไม่พร้อมใช้งาน',
            35 => 'ปัญหา SSL/TLS Certificate',
            52 => 'Server ไม่ตอบกลับข้อมูล (Empty Reply)',
            55 => 'การส่งข้อมูลล้มเหลว (Send Failure) - อาจเป็นปัญหา Network',
            56 => 'การรับข้อมูลล้มเหลว (Receive Failure)',
            60 => 'ปัญหา SSL Certificate - ใบรับรองไม่ถูกต้อง',
        ];

        if (isset($errorMessages[$errorCode])) {
            return $errorMessages[$errorCode];
        }

        return "cURL Error [{$errorCode}]: {$errorMessage}";
    }

    /**
     * แปลง HTTP status code เป็นข้อความที่เข้าใจง่าย
     */
    protected function getHttpErrorMessage($httpCode, $serverMessage = null)
    {
        $httpMessages = [
            400 => 'คำขอไม่ถูกต้อง (Bad Request)',
            401 => 'ไม่ได้รับอนุญาต - Token ไม่ถูกต้อง',
            403 => 'ไม่มีสิทธิ์เข้าถึง (Forbidden)',
            404 => 'ไม่พบ endpoint บน Remote Server (upload.php Not Found)',
            408 => 'หมดเวลาคำขอ (Request Timeout)',
            413 => 'ไฟล์ใหญ่เกินไป (Payload Too Large)',
            429 => 'คำขอมากเกินไป (Too Many Requests)',
            500 => 'Server ปลายทางมีปัญหา (Internal Server Error)',
            502 => 'Bad Gateway - Server กลางมีปัญหา',
            503 => 'Server ไม่พร้อมให้บริการ (Service Unavailable)',
            504 => 'Gateway Timeout - Server ตอบสนองช้าเกินไป',
        ];

        // ถ้ามี message จาก server ให้ใช้
        if (!empty($serverMessage)) {
            $base = $httpMessages[$httpCode] ?? "HTTP {$httpCode}";
            return "{$base}: {$serverMessage}";
        }

        if (isset($httpMessages[$httpCode])) {
            return $httpMessages[$httpCode];
        }

        return "HTTP Error {$httpCode}";
    }
}

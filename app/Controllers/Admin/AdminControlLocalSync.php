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
            'http://118.172.140.151:8000' => 'HTTP Server',
            'https://skj.nsnpao.go.th' => 'HTTPS Server'
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
            $ch = curl_init($serverUrl . "/token/upload.php");
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
        $localUploadPath = FCPATH . 'uploads/admission/';
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
            return redirect()->to('admin/local-sync');
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
            return redirect()->to('admin/local-sync');
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
            // อัปโหลดไป Remote Server
            $result = $this->uploadToRemote($fullPath, $subPath, $filename, $serverUrl);

            if ($result && isset($result['status']) && $result['status'] === 'success') {
                // อัปเดตฐานข้อมูล
                $dbUpdated = $this->updateDatabaseReferences($filename, $result['filename'] ?? $filename);

                // ลบไฟล์ Local
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    
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

            return [
                'status' => 'error',
                'message' => "อัปโหลดล้มเหลว: " . ($result['message'] ?? 'ไม่ทราบสาเหตุ'),
                'filename' => $filename
            ];

        } catch (\Exception $e) {
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
        $url = $serverUrl . "/token/upload.php";

        try {
            $mimeType = mime_content_type($filePath);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
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
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                return ['status' => 'error', 'message' => $error];
            }

            $result = json_decode($response, true);
            
            if ($httpCode >= 200 && $httpCode < 300 && isset($result['status']) && $result['status'] === 'success') {
                return $result;
            }

            return ['status' => 'error', 'message' => $result['message'] ?? "HTTP {$httpCode}"];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
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
}

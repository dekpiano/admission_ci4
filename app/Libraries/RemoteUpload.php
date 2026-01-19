<?php

namespace App\Libraries;

class RemoteUpload
{
    // อ่านค่า server จาก .env
    protected $primaryServer;
    protected $fallbackServer;
    protected $activeServer = null;
    protected $token;
    protected $useLocalFallback = true; // เปิดใช้ local fallback

    public function __construct()
    {
        // อ่านค่า server จาก .env
        $this->primaryServer = getenv('upload.server.host') ?: "https://skj.nsnpao.go.th";
        $this->fallbackServer = $this->primaryServer; // ใช้ server เดียวกัน
        
        // Determine which server to use
        $this->activeServer = $this->getActiveServer();
        $this->token = trim(getenv('upload.secret.token') ?: "Dekpiano2025!!");
    }

    /**
     * Check which server is available and return the active one
     */
    protected function getActiveServer()
    {
        $cacheFile = WRITEPATH . 'cache/active_upload_server.txt';
        
        // Clear cache if older than 2 minutes (faster failover)
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 120) {
            $cached = trim(file_get_contents($cacheFile));
            // Verify cached server is still working
            if ($this->isServerAvailable($cached)) {
                return $cached;
            }
        }

        // Try HTTP server first (primary)
        if ($this->isServerAvailable($this->primaryServer)) {
            $this->cacheActiveServer($cacheFile, $this->primaryServer);
            return $this->primaryServer;
        }

        // Fall back to HTTPS
        if ($this->isServerAvailable($this->fallbackServer)) {
            $this->cacheActiveServer($cacheFile, $this->fallbackServer);
            return $this->fallbackServer;
        }

        // Return primary as default, will try local fallback in upload()
        return $this->primaryServer;
    }

    protected function isServerAvailable($serverUrl)
    {
        try {
            $ch = curl_init($serverUrl . "/public/index.php"); // ตรวจสอบผ่านหน้าเว็บปกติ
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
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

    protected function cacheActiveServer($cacheFile, $server)
    {
        if (!is_dir(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0755, true);
        }
        file_put_contents($cacheFile, $server);
    }

    /**
     * Unified request handler with fallback support
     */
    protected function sendRequest($endpoint, $payload, $isMultipart = false)
    {
        $servers = ($this->activeServer === $this->primaryServer) 
                   ? [$this->primaryServer, $this->fallbackServer] 
                   : [$this->fallbackServer, $this->primaryServer];

        foreach ($servers as $server) {
            $url = $server . "/token/" . $endpoint;
            $result = $this->executeRequest($url, $payload, $isMultipart);

            if ($result['status'] === 'success') {
                // If we successfully used a different server than currently active, update cache
                if ($server !== $this->activeServer) {
                    $this->activeServer = $server;
                    $this->cacheActiveServer(WRITEPATH . 'cache/active_upload_server.txt', $server);
                    log_message('info', "RemoteUpload: Switched active server to {$server} due to success after fallback");
                }
                return $result['body'];
            }

            log_message('debug', "RemoteUpload: Request to {$server} failed: " . ($result['message'] ?? 'Unknown error'));
        }

        return ['status' => 'error', 'message' => 'Both servers failed to process request'];
    }

    protected function executeRequest($url, $payload, $isMultipart)
    {
        try {
            $client = \Config\Services::curlrequest();
            $options = [
                'headers' => [
                    'X-Auth-Token' => $this->token,
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'http_errors' => false,
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 5,
            ];

            if ($isMultipart) {
                $options['multipart'] = $payload;
            } else {
                $options['headers']['Content-Type'] = 'application/json';
                $options['body'] = json_encode($payload);
            }

            $response = $client->post($url, $options);
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($body['status']) && ($body['status'] === 'success' || $body['status'] === 'partial_success')) {
                return ['status' => 'success', 'body' => $body];
            }

            return ['status' => 'error', 'message' => $body['message'] ?? 'Server error'];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Upload File - Intelligent Path Selection
     * รองรับการ compress รูปภาพอัตโนมัติเมื่อไฟล์ใหญ่เกินไป
     */
    public function upload($file, $subPath, $customName = null, $autoCompress = true)
    {
        $filePath = ''; $mimeType = ''; $originalName = '';

        if ($file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
            if (!$file->isValid()) return false;
            $filePath = $file->getTempName();
            $mimeType = $file->getClientMimeType();
            $originalName = $customName ?: $file->getName();
        } else {
            if (!file_exists($file)) return false;
            $filePath = $file;
            $mimeType = mime_content_type($file);
            $originalName = $customName ?: basename($file);
        }

        // Auto compress รูปภาพที่ใหญ่เกินไป (> 0.9MB เพื่อให้ผ่านด่าน Nginx 1MB)
        $maxSizeMB = 0.9;
        $currentSize = filesize($filePath);
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        if ($autoCompress && $currentSize > ($maxSizeMB * 1024 * 1024) && in_array($mimeType, $imageTypes)) {
            log_message('info', "RemoteUpload: ไฟล์ใหญ่เกิน {$maxSizeMB}MB (" . $this->formatFileSize($currentSize) . "), กำลัง compress...");
            
            try {
                $compressor = new ImageCompressor();
                $compressor->setMaxFileSize($maxSizeMB)
                           ->setMaxDimensions(1600, 2000)  // ขนาดพอดี A4 สำหรับเอกสาร
                           ->setJpegQuality(85);
                
                $result = $compressor->compress($filePath);
                
                if ($result['success'] && isset($result['compressed']) && $result['compressed']) {
                    log_message('info', "RemoteUpload: Compress สำเร็จ - " . $result['message']);
                    
                    // ถ้าแปลงเป็น JPG ให้เปลี่ยนชื่อไฟล์ด้วย
                    if (isset($result['output_path']) && $result['output_path'] !== $filePath) {
                        $filePath = $result['output_path'];
                        $mimeType = 'image/jpeg';
                        // เปลี่ยนนามสกุลไฟล์ใน originalName ด้วย
                        $originalName = preg_replace('/\.(png|gif|webp)$/i', '.jpg', $originalName);
                    }
                }
            } catch (\Exception $e) {
                log_message('warning', "RemoteUpload: ไม่สามารถ compress ได้: " . $e->getMessage());
                // ดำเนินการต่อโดยไม่ compress
            }
        }

        // ตรวจสอบว่า Remote Server (skj.nsnpao.go.th) ใช้งานได้หรือไม่
        // ถ้าใช้ได้ ให้ส่งไป Remote เป็นหลัก
        // ถ้าไม่ได้ ให้เก็บไฟล์ที่ server ตัวเอง (local fallback)
        
        $remoteServerAvailable = $this->isServerAvailable($this->primaryServer);
        
        if ($remoteServerAvailable) {
            // Remote server ใช้งานได้ - ส่งไป Remote
            $payload = [
                'path' => $subPath,
                'file' => new \CURLFile($filePath, $mimeType, $originalName)
            ];
            if ($customName) $payload['desired_filename'] = $customName;

            $result = $this->sendRequest('upload.php', $payload, true);
            
            // หาก Remote ส่งสำเร็จ ให้ return ผลลัพธ์
            if (isset($result['status']) && $result['status'] === 'success') {
                log_message('info', 'RemoteUpload: File uploaded to remote server: ' . $this->primaryServer);
                return $result;
            }
            
            // หาก Remote ล้มเหลว และเปิดใช้ local fallback
            if ($this->useLocalFallback) {
                log_message('warning', 'RemoteUpload: Remote upload failed, falling back to local storage');
                return $this->uploadLocal($filePath, $subPath, $originalName);
            }
            
            return $result;
        }
        
        // Remote server ใช้งานไม่ได้ - เก็บไฟล์ที่ server ตัวเอง
        log_message('warning', 'RemoteUpload: Remote server unavailable (' . $this->primaryServer . '), saving locally');
        return $this->uploadLocal($filePath, $subPath, $originalName);
    }
    
    /**
     * Local Upload Fallback
     * Saves file to public/uploads directory when remote servers are unavailable
     */
    protected function uploadLocal($filePath, $subPath, $fileName)
    {
        try {
            // Generate unique filename to prevent conflicts
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $uniqueName = $baseName . '_' . uniqid() . '.' . $ext;
            
            // Create directory if not exists
            $uploadDir = FCPATH . 'uploads/' . $subPath;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $destination = $uploadDir . '/' . $uniqueName;
            
            // Copy file to destination
            if (copy($filePath, $destination)) {
                log_message('info', 'RemoteUpload: File saved locally: ' . $destination);
                return [
                    'status' => 'success',
                    'filename' => $uniqueName,
                    'path' => $subPath . '/' . $uniqueName,
                    'url' => base_url('uploads/' . $subPath . '/' . $uniqueName),
                    'storage' => 'local' // Flag to indicate local storage
                ];
            }
            
            return ['status' => 'error', 'message' => 'Failed to save file locally'];
            
        } catch (\Exception $e) {
            log_message('error', 'RemoteUpload Local Fallback Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Local fallback failed: ' . $e->getMessage()];
        }
    }

    /**
     * Delete Files - Handlers both Local and Remote
     */
    public function delete($files, $subPath)
    {
        $fileList = is_array($files) ? $files : [$files];
        
        // 1. ลบจาก Local ก่อน (ถ้ามี)
        foreach ($fileList as $file) {
            $localPath = FCPATH . 'uploads/' . $subPath . '/' . $file;
            if (file_exists($localPath)) {
                @unlink($localPath);
                log_message('info', "RemoteUpload: Local file deleted: {$localPath}");
            }
        }

        // 2. ส่งคำสั่งไปลบที่ Remote Server ตามปกติ
        $payload = [
            'files' => $fileList,
            'path' => $subPath
        ];
        $result = $this->sendRequest('delete.php', $payload);
        return isset($result['status']) && ($result['status'] === 'success' || $result['status'] === 'partial_success');
    }

    /**
     * Move to Trash
     */
    public function moveToTrash($files, $subPath)
    {
        $payload = [
            'files' => is_array($files) ? $files : [$files],
            'path' => $subPath
        ];
        return $this->sendRequest('move_to_trash.php', $payload);
    }

    /**
     * Restore from Trash
     */
    public function restoreFromTrash($files, $subPath)
    {
        $payload = [
            'files' => is_array($files) ? $files : [$files],
            'path' => $subPath
        ];
        return $this->sendRequest('restore_from_trash.php', $payload);
    }

    /**
     * List Trash
     */
    public function listTrash($subPath = '')
    {
        return $this->sendRequest('list_trash.php', ['path' => $subPath]);
    }

    /**
     * Format file size for logging
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
}

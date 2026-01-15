<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class ImageProxy extends BaseController
{
    // สลับลำดับ: HTTP ก่อน (เพราะ HTTPS มีปัญหา SSL)
    protected $primaryServer = "http://118.172.140.151:8000";
    protected $fallbackServer = "http://118.172.140.151:8000";
    
    public function index()
    {
        $fileName = $this->request->getVar('file'); // e.g., recruitstudent/m4/img/image.png
        $debug = $this->request->getVar('debug') === '1'; // Add ?debug=1 for debugging
        
        if (empty($fileName)) {
            return $this->response->setStatusCode(400)->setBody('Missing file parameter');
        }

        $debugInfo = [];

        // 1. ตรวจสอบ Local ก่อน (กรณีเซิร์ฟเวอร์ล่มและใช้ local fallback)
        $localPath = FCPATH . 'uploads/admission/' . ltrim($fileName, '/');
        $debugInfo['local_path'] = $localPath;
        $debugInfo['local_exists'] = file_exists($localPath);
        
        $localResult = $this->fetchFromLocal($fileName);
        if ($localResult !== null) {
            return $localResult;
        }

        // 2. ลอง Remote servers (HTTP ก่อน, แล้ว HTTPS)
        $servers = [$this->primaryServer, $this->fallbackServer];
        
        foreach ($servers as $server) {
            $result = $this->fetchFromServer($server, $fileName, $debugInfo);
            if ($result !== null) {
                return $result;
            }
        }
        
        // ทั้ง Local และ Remote ไม่พบ
        log_message('error', "ImageProxy: File not found anywhere: {$fileName} | Debug: " . json_encode($debugInfo));
        
        // ถ้าเปิด debug mode ให้แสดงข้อมูล
        if ($debug) {
            return $this->response
                ->setStatusCode(404)
                ->setContentType('application/json')
                ->setBody(json_encode([
                    'error' => 'File not found',
                    'file' => $fileName,
                    'debug' => $debugInfo
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        
        return $this->response->setStatusCode(404)->setBody('File not found.');
    }
    
    /**
     * ตรวจสอบและส่งไฟล์จาก Local Storage
     * 
     * @param string $fileName The file path
     * @return \CodeIgniter\HTTP\Response|null Returns response if found, null if not
     */
    protected function fetchFromLocal(string $fileName)
    {
        // ไฟล์ local อยู่ที่ public/uploads/admission/
        $localPath = FCPATH . 'uploads/admission/' . ltrim($fileName, '/');
        
        if (file_exists($localPath) && is_file($localPath)) {
            try {
                $body = file_get_contents($localPath);
                $mimeType = mime_content_type($localPath);
                $contentLength = filesize($localPath);
                
                log_message('debug', "ImageProxy: Serving from local: {$localPath}");
                
                return $this->response
                    ->setStatusCode(200)
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Length', $contentLength)
                    ->setHeader('Cache-Control', 'public, max-age=86400')
                    ->setHeader('X-Source', 'local') // บอกว่ามาจาก local
                    ->setBody($body);
            } catch (\Exception $e) {
                log_message('error', "ImageProxy: Error reading local file: " . $e->getMessage());
                return null;
            }
        }
        
        return null;
    }
    
    /**
     * Try to fetch file from a specific server
     * 
     * @param string $serverUrl The server URL
     * @param string $fileName The file path
     * @param array &$debugInfo Reference to debug info array
     * @return \CodeIgniter\HTTP\Response|null Returns response if successful, null if failed
     */
    protected function fetchFromServer(string $serverUrl, string $fileName, array &$debugInfo = [])
    {
        $fullRemoteUrl = rtrim($serverUrl, '/') . '/uploads/admission/' . ltrim($fileName, '/');
        $debugInfo['tried_urls'][] = $fullRemoteUrl;
        
        $client = \Config\Services::curlrequest([
            'verify' => false,
            'timeout' => 10,  // เพิ่ม timeout เป็น 10 วินาที
            'connect_timeout' => 5,  // เพิ่ม connect timeout เป็น 5 วินาที
        ]);

        try {
            $response = $client->get($fullRemoteUrl, [
                'headers' => [
                    'Referer' => base_url(),
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ],
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $debugInfo['server_responses'][$serverUrl] = $statusCode;
            
            if ($statusCode === 200) {
                $body = $response->getBody();
                $contentType = $response->getHeaderLine('Content-Type');
                $contentLength = $response->getHeaderLine('Content-Length');
                
                // Determine the correct Content-Type
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $mimeMap = [
                    'heic' => 'image/heic',
                    'heif' => 'image/heif',
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png'  => 'image/png',
                    'gif'  => 'image/gif',
                    'webp' => 'image/webp',
                    'pdf'  => 'application/pdf',
                ];

                if (isset($mimeMap[$extension])) {
                    $finalContentType = $mimeMap[$extension];
                } else {
                    $finalContentType = $contentType ?: 'application/octet-stream';
                    
                    if ($finalContentType === 'application/octet-stream' || strpos($finalContentType, 'text/plain') !== false) {
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $detectedMime = $finfo->buffer($body);
                        if ($detectedMime && $detectedMime !== 'text/plain') {
                            $finalContentType = $detectedMime;
                        }
                    }
                }

                return $this->response
                            ->setStatusCode($statusCode)
                            ->setHeader('Content-Type', $finalContentType)
                            ->setHeader('Content-Length', $contentLength ?: strlen($body))
                            ->setHeader('Cache-Control', 'public, max-age=86400')
                            ->setHeader('X-Source', 'remote') // บอกว่ามาจาก remote
                            ->setHeader('X-Remote-Server', $serverUrl)
                            ->setBody($body);
            }
            
            // Not 200, try next server
            log_message('error', "ImageProxy: Server {$serverUrl} returned status {$statusCode} for url: {$fullRemoteUrl}");
            return null;

        } catch (\Exception $e) {
            $debugInfo['errors'][$serverUrl] = $e->getMessage();
            log_message('debug', "ImageProxy: Server {$serverUrl} failed with exception: " . $e->getMessage());
            return null;
        }
    }
}

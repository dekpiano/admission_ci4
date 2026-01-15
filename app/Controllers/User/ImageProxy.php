<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class ImageProxy extends BaseController
{
    // ใช้ IP เป็นตัวหลัก (สำหรับช่วงที่โดเมนหลักล่ม) และใช้โดเมนหลักเป็นตัวสำรอง
    protected $primaryServer = "http://118.172.140.151:8000";
    protected $fallbackServer = "https://skj.nsnpao.go.th";
    
    public function index()
    {
        $fileName = $this->request->getVar('file'); // e.g., recruitstudent/m4/img/image.png
        
        if (empty($fileName)) {
            return $this->response->setStatusCode(400)->setBody('Missing file parameter');
        }

        $debugInfo = [];

        // 1. ตรวจสอบ Local ก่อน
        $localResult = $this->fetchFromLocal($fileName, $debugInfo);
        if ($localResult !== null) {
            return $localResult;
        }

        // 2. ลอง Remote servers
        $servers = [$this->primaryServer, $this->fallbackServer];
        
        foreach ($servers as $server) {
            $result = $this->fetchFromServer($server, $fileName, $debugInfo);
            if ($result !== null) {
                return $result;
            }
        }
        
        // ทั้ง Local และ Remote ไม่พบ
        log_message('error', "ImageProxy: File not found anywhere: {$fileName} | Debug: " . json_encode($debugInfo));
        
        // หากเปิด debug mode โดยส่ง ?debug=1 มา ค่อยแสดง JSON
        if ($this->request->getVar('debug') === '1') {
            return $this->response->setStatusCode(404)
                ->setContentType('application/json')
                ->setBody(json_encode([
                    'error' => 'File not found everywhere',
                    'file' => $fileName,
                    'debug' => $debugInfo
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $this->response->setStatusCode(404)->setBody('File not found.');
    }
    
    protected function fetchFromLocal(string $fileName, array &$debugInfo)
    {
        $fileName = ltrim($fileName, '/');
        // ลองค้นหาทั้งแบบมี และไม่มี admission/
        $possiblePaths = [
            FCPATH . 'uploads/admission/' . $fileName,
            FCPATH . 'uploads/' . $fileName
        ];

        foreach ($possiblePaths as $localPath) {
            $debugInfo['local_checks'][] = [
                'path' => $localPath,
                'exists' => file_exists($localPath)
            ];

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
                        ->setHeader('X-Source', 'local')
                        ->setBody($body);
                } catch (\Exception $e) {
                    log_message('error', "ImageProxy: Error reading local file: " . $e->getMessage());
                }
            }
        }
        
        return null;
    }
    
    protected function fetchFromServer(string $serverUrl, string $fileName, array &$debugInfo = [])
    {
        $fileName = ltrim($fileName, '/');
        $possiblePaths = [
            '/uploads/admission/' . $fileName,
            '/uploads/' . $fileName,
        ];
        
        $client = \Config\Services::curlrequest([
            'verify' => false,
            'timeout' => 5,
            'connect_timeout' => 3,
        ]);

        foreach ($possiblePaths as $relativeUrl) {
            $fullRemoteUrl = rtrim($serverUrl, '/') . $relativeUrl;
            $debugInfo['tried_urls'][] = $fullRemoteUrl;

            try {
                $response = $client->get($fullRemoteUrl, [
                    'headers' => [
                        'Referer' => base_url(),
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    ],
                    'http_errors' => false,
                ]);

                $statusCode = $response->getStatusCode();
                $debugInfo['server_responses'][$fullRemoteUrl] = $statusCode;
                
                if ($statusCode === 200) {
                    $body = $response->getBody();
                    
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

                    $finalContentType = $mimeMap[$extension] ?? $response->getHeaderLine('Content-Type') ?: 'application/octet-stream';

                    return $this->response
                                ->setStatusCode($statusCode)
                                ->setHeader('Content-Type', $finalContentType)
                                ->setHeader('X-Source', 'remote')
                                ->setHeader('X-Remote-Server', $serverUrl)
                                ->setBody($body);
                }
            } catch (\Exception $e) {
                $debugInfo['errors'][$fullRemoteUrl] = $e->getMessage();
            }
        }
        return null;
    }
}

<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class ImageProxy extends BaseController
{
    protected $primaryServer = "https://skj.nsnpao.go.th";
    protected $fallbackServer = "http://118.172.140.151:8000";
    
    public function index()
    {
        $fileName = $this->request->getVar('file'); // e.g., recruitstudent/m4/img/image.png
        
        if (empty($fileName)) {
            return $this->response->setStatusCode(400)->setBody('Missing file parameter');
        }

        // Try primary server first, then fallback
        $servers = [$this->primaryServer, $this->fallbackServer];
        
        foreach ($servers as $server) {
            $result = $this->fetchFromServer($server, $fileName);
            if ($result !== null) {
                return $result;
            }
        }
        
        // Both servers failed
        log_message('error', "ImageProxy: Both servers failed for file: {$fileName}");
        return $this->response->setStatusCode(404)->setBody('File not found on any server.');
    }
    
    /**
     * Try to fetch file from a specific server
     * 
     * @param string $serverUrl The server URL
     * @param string $fileName The file path
     * @return \CodeIgniter\HTTP\Response|null Returns response if successful, null if failed
     */
    protected function fetchFromServer(string $serverUrl, string $fileName)
    {
        $fullRemoteUrl = rtrim($serverUrl, '/') . '/uploads/admission/' . ltrim($fileName, '/');
        
        $client = \Config\Services::curlrequest([
            'verify' => false,
            'timeout' => 5,
            'connect_timeout' => 3,
        ]);

        try {
            $response = $client->get($fullRemoteUrl, [
                'headers' => [
                    'Referer' => '',
                ],
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            
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
                            ->setBody($body);
            }
            
            // Not 200, try next server
            log_message('debug', "ImageProxy: Server {$serverUrl} returned status {$statusCode} for {$fileName}");
            return null;

        } catch (\Exception $e) {
            log_message('debug', "ImageProxy: Server {$serverUrl} failed with exception: " . $e->getMessage());
            return null;
        }
    }
}


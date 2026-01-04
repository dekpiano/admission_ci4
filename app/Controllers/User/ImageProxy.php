<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class ImageProxy extends BaseController
{
    public function index()
    {
        $fileName = $this->request->getVar('file'); // e.g., recruitstudent/m4/img/image.png
        
        if (empty($fileName)) {
            return $this->response->setStatusCode(400)->setBody('Missing file parameter');
        }

        // Base URL for remote files (from RemoteUpload library's config)
        $remoteBaseUrl = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";

        // Construct the full URL to the remote file
        $fullRemoteUrl = rtrim($remoteBaseUrl, '/') . '/' . ltrim($fileName, '/');
        
        // Use CodeIgniter's CURLRequest service to fetch the file
        $client = \Config\Services::curlrequest([
            'verify' => false, // Adjust as needed for production, false to bypass SSL verification
        ]);

        try {
            $response = $client->get($fullRemoteUrl, [
                // Set referer policy to not send referer header, or set a dummy one
                'headers' => [
                    'Referer' => '', // Explicitly set empty referer to bypass hotlink protection
                ],
                'http_errors' => false, // Don't throw exceptions for 4xx/5xx responses
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $contentType = $response->getHeaderLine('Content-Type');
            $contentLength = $response->getHeaderLine('Content-Length');

            if ($statusCode === 200) {
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

                // Prioritize extension-based MIME type if it's a known image/PDF type
                // otherwise use the response header or fallback
                if (isset($mimeMap[$extension])) {
                    $finalContentType = $mimeMap[$extension];
                } else {
                    $finalContentType = $contentType ?: 'application/octet-stream';
                    
                    // If content type is generic, try to use fileinfo for better detection
                    if ($finalContentType === 'application/octet-stream' || strpos($finalContentType, 'text/plain') !== false) {
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $detectedMime = $finfo->buffer($body);
                        if ($detectedMime && $detectedMime !== 'text/plain') {
                            $finalContentType = $detectedMime;
                        }
                    }
                }

                // Set appropriate headers and output the image
                return $this->response
                            ->setStatusCode($statusCode)
                            ->setHeader('Content-Type', $finalContentType)
                            ->setHeader('Content-Length', $contentLength ?: strlen($body))
                            ->setHeader('Cache-Control', 'public, max-age=86400') // Add caching for better performance
                            ->setBody($body);
            } else {
                // Return a generic placeholder or an error image/message
                log_message('error', "ImageProxy: Failed to fetch {$fullRemoteUrl}. Status: {$statusCode}");
                // Option 1: Return a 404
                return $this->response->setStatusCode(404)->setBody("File not found or inaccessible on remote server. (Status: {$statusCode})");
                // Option 2: Redirect to a local placeholder image
                // return redirect()->to(base_url('sneat-assets/img/avatars/1.png'));
            }

        } catch (\Exception $e) {
            log_message('error', "ImageProxy Exception: " . $e->getMessage());
            return $this->response->setStatusCode(500)->setBody('ImageProxy: Internal server error.');
        }
    }
}

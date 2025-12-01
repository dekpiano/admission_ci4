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
                // Set appropriate headers and output the image
                return $this->response
                            ->setStatusCode($statusCode)
                            ->setHeader('Content-Type', $contentType ?: 'application/octet-stream')
                            ->setHeader('Content-Length', $contentLength ?: strlen($body))
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

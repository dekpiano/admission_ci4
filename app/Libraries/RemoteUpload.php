<?php

namespace App\Libraries;

class RemoteUpload
{
    protected $primaryServer = "https://skj.nsnpao.go.th";
    protected $fallbackServer = "http://118.172.140.151:8000";
    protected $activeServer = null;
    protected $token;

    public function __construct()
    {
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
        
        // Try cache first (valid for 5 minutes)
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 300) {
            return trim(file_get_contents($cacheFile));
        }

        // Try primary first
        if ($this->isServerAvailable($this->primaryServer)) {
            $this->cacheActiveServer($cacheFile, $this->primaryServer);
            return $this->primaryServer;
        }

        // Fall back to secondary
        if ($this->isServerAvailable($this->fallbackServer)) {
            $this->cacheActiveServer($cacheFile, $this->fallbackServer);
            return $this->fallbackServer;
        }

        return $this->primaryServer; // Default back to primary
    }

    protected function isServerAvailable($serverUrl)
    {
        try {
            $ch = curl_init($serverUrl . "/token/upload.php");
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
     * Upload File
     */
    public function upload($file, $subPath, $customName = null)
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

        $payload = [
            'path' => $subPath,
            'file' => new \CURLFile($filePath, $mimeType, $originalName)
        ];
        if ($customName) $payload['desired_filename'] = $customName;

        return $this->sendRequest('upload.php', $payload, true);
    }

    /**
     * Delete Files
     */
    public function delete($files, $subPath)
    {
        $payload = [
            'files' => is_array($files) ? $files : [$files],
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
}

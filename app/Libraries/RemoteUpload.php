<?php

namespace App\Libraries;

class RemoteUpload
{
    protected $uploadUrl;
    protected $deleteUrl;
    protected $moveToTrashUrl;
    protected $restoreFromTrashUrl;
    protected $listTrashUrl;
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $baseTokenUrl = "https://skj.nsnpao.go.th/token/";
        
        $this->uploadUrl = getenv('upload.server.url') ?: $baseTokenUrl . "upload.php";
        $this->deleteUrl = getenv('upload.server.delete.url') ?: $baseTokenUrl . "delete.php";
        $this->moveToTrashUrl = $baseTokenUrl . "move_to_trash.php";
        $this->restoreFromTrashUrl = $baseTokenUrl . "restore_from_trash.php";
        $this->listTrashUrl = $baseTokenUrl . "list_trash.php";
        $this->baseUrl = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
        $this->token = trim(getenv('upload.secret.token') ?: "Dekpiano2025!!");
    }

    /**
     * Upload a file to the remote server.
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile|string $file The file object or path to file.
     * @param string $subPath The subdirectory path.
     * @param string|null $customName Optional custom filename.
     * @return array|false Returns array with 'status' and 'filename' on success, or false on failure.
     */
    /**
     * Upload a file to the remote server.
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile|string $file The file object or path to file.
     * @param string $subPath The subdirectory path.
     * @param string|null $customName Optional custom filename.
     * @return array|false Returns array with 'status' and 'filename' on success, or false on failure.
     */
    public function upload($file, $subPath, $customName = null)
    {
        try {
            $client = \Config\Services::curlrequest();

            $filePath = '';
            $mimeType = '';
            $originalName = '';

            if ($file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                if (!$file->isValid()) {
                    return false;
                }
                $filePath = $file->getTempName();
                $mimeType = $file->getClientMimeType();
                $originalName = $customName ?: $file->getName();
            } else {
                if (!file_exists($file)) {
                    return false;
                }
                $filePath = $file;
                $mimeType = mime_content_type($file);
                $originalName = $customName ?: basename($file);
            }

            $postData = [
                'path' => $subPath,
                'file' => new \CURLFile($filePath, $mimeType, $originalName)
            ];
            
            if ($customName) {
                $postData['desired_filename'] = $customName;
            }

            $response = $client->post($this->uploadUrl, [
                'multipart' => $postData,
                'headers' => [
                    'X-Auth-Token' => $this->token,
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'http_errors' => false,
                'verify' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($body['status']) && $body['status'] === 'success') {
                return $body;
            } else {
                log_message('error', 'RemoteUpload Error: ' . ($body['message'] ?? 'Unknown error') . ' Status: ' . $statusCode);
                return ['status' => 'error', 'message' => $body['message'] ?? 'Upload failed'];
            }

        } catch (\Exception $e) {
            log_message('error', 'RemoteUpload Exception: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Connection error'];
        }
    }

    /**
     * Delete files from the remote server.
     *
     * @param array|string $files Single filename or array of filenames.
     * @param string $subPath The subdirectory path.
     * @return bool True on success, false on failure.
     */
    public function delete($files, $subPath)
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        try {
            $client = \Config\Services::curlrequest();

            $jsonData = json_encode([
                'files' => $files,
                'path' => $subPath
            ]);

            $response = $client->post($this->deleteUrl, [
                'body' => $jsonData,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Auth-Token' => $this->token,
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'http_errors' => false,
                'verify' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($body['status']) && ($body['status'] === 'success' || $body['status'] === 'partial_success')) {
                return true;
            }

            log_message('error', 'RemoteUpload Delete Error: ' . ($body['message'] ?? 'Unknown error'));
            return false;

        } catch (\Exception $e) {
            log_message('error', 'RemoteUpload Delete Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Move files to trash instead of deleting permanently.
     * Files will be automatically deleted after 30 days.
     *
     * @param array|string $files Single filename or array of filenames.
     * @param string $subPath The subdirectory path.
     * @return array|false Returns array with status and moved files on success, or false on failure.
     */
    public function moveToTrash($files, $subPath)
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        try {
            $client = \Config\Services::curlrequest();

            $jsonData = json_encode([
                'files' => $files,
                'path' => $subPath
            ]);

            $response = $client->post($this->moveToTrashUrl, [
                'body' => $jsonData,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Auth-Token' => $this->token,
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'http_errors' => false,
                'verify' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($body['status']) && ($body['status'] === 'success' || $body['status'] === 'partial_success')) {
                return $body;
            }

            log_message('error', 'RemoteUpload MoveToTrash Error: ' . ($body['message'] ?? 'Unknown error'));
            return false;

        } catch (\Exception $e) {
            log_message('error', 'RemoteUpload MoveToTrash Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Restore a file from trash back to its original location.
     *
     * @param string $trashFile The filename in trash.
     * @param string $trashPath The path within trash directory.
     * @return array|false Returns array with status on success, or false on failure.
     */
    public function restoreFromTrash($trashFile, $trashPath)
    {
        try {
            $client = \Config\Services::curlrequest();

            $jsonData = json_encode([
                'trash_file' => $trashFile,
                'trash_path' => $trashPath
            ]);

            $response = $client->post($this->restoreFromTrashUrl, [
                'body' => $jsonData,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Auth-Token' => $this->token,
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'http_errors' => false,
                'verify' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($body['status']) && $body['status'] === 'success') {
                return $body;
            }

            log_message('error', 'RemoteUpload RestoreFromTrash Error: ' . ($body['message'] ?? 'Unknown error'));
            return false;

        } catch (\Exception $e) {
            log_message('error', 'RemoteUpload RestoreFromTrash Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * List all files in trash.
     *
     * @return array|false Returns array with files on success, or false on failure.
     */
    public function listTrash()
    {
        try {
            $client = \Config\Services::curlrequest();

            $response = $client->get($this->listTrashUrl, [
                'headers' => [
                    'X-Auth-Token' => $this->token,
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'http_errors' => false,
                'verify' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($body['status']) && $body['status'] === 'success') {
                return $body;
            }

            log_message('error', 'RemoteUpload ListTrash Error: ' . ($body['message'] ?? 'Unknown error'));
            return false;

        } catch (\Exception $e) {
            log_message('error', 'RemoteUpload ListTrash Exception: ' . $e->getMessage());
            return false;
        }
    }
}

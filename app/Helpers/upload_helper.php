<?php

/**
 * Upload Helper Functions
 * 
 * Helper functions for getting the active upload server URL
 * with automatic fallback support.
 */

if (!function_exists('get_active_upload_server')) {
    /**
     * Get the active upload server URL with fallback support
     * Reads from cache file created by RemoteUpload library
     * 
     * @return string The active server URL
     */
    function get_active_upload_server(): string
    {
        $primaryServer = "https://skj.nsnpao.go.th";
        $fallbackServer = "http://118.172.140.151:8000";
        
        // Check cache first (same cache as RemoteUpload library)
        $cacheFile = WRITEPATH . 'cache/active_upload_server.txt';
        
        // Use cache if valid (less than 5 minutes old)
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 300) {
            $cached = trim(file_get_contents($cacheFile));
            if (!empty($cached)) {
                return $cached;
            }
        }
        
        // Quick check primary server (reduced timeout for faster fallback)
        if (is_server_available($primaryServer)) {
            cache_active_server($cacheFile, $primaryServer);
            return $primaryServer;
        }
        
        // Primary failed, use fallback
        log_message('info', 'UploadHelper: Primary server unavailable, using fallback');
        cache_active_server($cacheFile, $fallbackServer);
        return $fallbackServer;
    }
}

if (!function_exists('get_upload_base_url')) {
    /**
     * Get the base URL for uploaded files (admission folder)
     * 
     * @return string The base URL for admission uploads
     */
    function get_upload_base_url(): string
    {
        $envUrl = getenv('upload.server.baseurl');
        if ($envUrl) {
            return $envUrl;
        }
        
        return get_active_upload_server() . "/uploads/admission/";
    }
}

if (!function_exists('get_token_url')) {
    /**
     * Get the token URL for API endpoints
     * 
     * @param string $endpoint The endpoint filename (e.g., 'upload.php', 'list_files.php')
     * @return string The full token URL
     */
    function get_token_url(string $endpoint = ''): string
    {
        return get_active_upload_server() . "/token/" . $endpoint;
    }
}

if (!function_exists('is_server_available')) {
    /**
     * Check if a server is available
     * 
     * @param string $serverUrl The server URL to check
     * @return bool True if server is available
     */
    function is_server_available(string $serverUrl): bool
    {
        try {
            $ch = curl_init($serverUrl . "/token/upload.php");
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);  // Reduced from 5 to 2 seconds
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);  // Reduced from 3 to 2 seconds
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
}

if (!function_exists('cache_active_server')) {
    /**
     * Cache the active server to file
     * 
     * @param string $cacheFile Path to cache file
     * @param string $server Server URL to cache
     */
    function cache_active_server(string $cacheFile, string $server): void
    {
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, $server);
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

    }

    /**
     * Send LINE OA Broadcast message (sends to all friends)
     * Uses LINE Messaging API instead of deprecated LINE Notify
     * @param string $message
     * @param string|null $channelAccessToken (optional, uses env if not provided)
     * @return mixed
     */
    protected function sendLineBroadcast($message, $channelAccessToken = null)
    {
        // Don't send if running on localhost
        if (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) {
            log_message('debug', 'LINE Broadcast skipped (localhost)');
            return false;
        }

        // Get token from parameter or environment
        $token = $channelAccessToken ?: getenv('LINE_CHANNEL_ACCESS_TOKEN') ?: 'J5jOTH9S0h8Kye0s6SIoPWv+fLT0J4+K7ozXYM7xV7yZRjGt/ggNhJPck/fddEIj0S5D/P5FJvWYD8cFP4wPPoi3DpvGwbxVeecGrRQpx4dbE/VBaIqXAPnR7Ix7/qDYhO006+qD9qpWu64Y31FN+gdB04t89/1O/w1cDnyilFU=';

        if (empty($token)) {
            log_message('error', 'LINE Broadcast: No channel access token');
            return false;
        }

        // Prepare broadcast message
        $data = [
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message
                ]
            ]
        ];

        // Send via LINE Messaging API Broadcast endpoint
        $ch = curl_init('https://api.line.me/v2/bot/message/broadcast');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', 'LINE Broadcast CURL Error: ' . $error);
            return false;
        }

        if ($httpCode !== 200) {
            log_message('error', 'LINE Broadcast Error: HTTP ' . $httpCode . ' - ' . $result);
            return false;
        }

        log_message('info', 'LINE Broadcast sent successfully');
        return json_decode($result);
    }

    /**
     * Legacy method for compatibility - redirects to sendLineBroadcast
     * @deprecated Use sendLineBroadcast instead
     */
    protected function notify_message($message, $token = null)
    {
        return $this->sendLineBroadcast($message, $token);
    }
}

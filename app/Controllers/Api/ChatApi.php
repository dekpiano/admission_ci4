<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class ChatApi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->setCorsHeaders();
        $this->ensureChatTablesExist();
    }

    /**
     * Set Cross-Origin Resource Sharing (CORS) headers so the widget can be embedded anywhere
     */
    private function setCorsHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization, X-CSRF-TOKEN');
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit(0);
        }
    }

    /**
     * Handle OPTIONS preflight requests
     */
    public function optionsHandler()
    {
        $this->setCorsHeaders();
        return $this->response->setStatusCode(200)->setBody('OK');
    }

    /**
     * Auto-create chat tables if not exist
     */
    private function ensureChatTablesExist()
    {
        try {
            $sqlSessions = "CREATE TABLE IF NOT EXISTS tb_chat_sessions (
                session_id INT(11) NOT NULL AUTO_INCREMENT,
                session_token VARCHAR(64) NOT NULL UNIQUE,
                user_name VARCHAR(150) NOT NULL,
                user_tel VARCHAR(50) NULL,
                user_ip VARCHAR(45) NULL,
                user_agent TEXT NULL,
                telegram_last_msg_id VARCHAR(50) NULL,
                status ENUM('active', 'closed') DEFAULT 'active',
                unread_user_count INT(11) DEFAULT 0,
                unread_admin_count INT(11) DEFAULT 0,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                PRIMARY KEY (session_id),
                INDEX idx_token (session_token),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

            $this->db->query($sqlSessions);

            $sqlMessages = "CREATE TABLE IF NOT EXISTS tb_chat_messages (
                message_id INT(11) NOT NULL AUTO_INCREMENT,
                session_id INT(11) NOT NULL,
                sender_type ENUM('user', 'admin', 'system') NOT NULL,
                sender_name VARCHAR(150) NULL,
                message TEXT NOT NULL,
                telegram_msg_id VARCHAR(50) NULL,
                is_read TINYINT(1) DEFAULT 0,
                created_at DATETIME NOT NULL,
                PRIMARY KEY (message_id),
                INDEX idx_session (session_id),
                INDEX idx_sender (sender_type),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

            $this->db->query($sqlMessages);
        } catch (\Throwable $e) {
            log_message('error', '[ensureChatTablesExist] ' . $e->getMessage());
        }
    }



    /**
     * Initialize or resume chat session
     * POST: session_token (optional), user_name, user_tel
     */
    public function initSession()
    {
        $this->setCorsHeaders();

        $token = trim($this->request->getPost('session_token') ?? '');
        $name = trim($this->request->getPost('user_name') ?? '');
        $tel = trim($this->request->getPost('user_tel') ?? '');

        if (!empty($token)) {
            $session = $this->db->table('tb_chat_sessions')->where('session_token', $token)->get()->getRow();
            if ($session) {
                // If user updated their name or phone
                if (!empty($name) && $name !== $session->user_name) {
                    $this->db->table('tb_chat_sessions')->where('session_id', $session->session_id)->update([
                        'user_name'  => $name,
                        'user_tel'   => !empty($tel) ? $tel : $session->user_tel,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $session->user_name = $name;
                }

                $messages = $this->db->table('tb_chat_messages')
                    ->where('session_id', $session->session_id)
                    ->orderBy('created_at', 'ASC')
                    ->get()
                    ->getResult();

                return $this->response->setJSON([
                    'status'   => 'success',
                    'session'  => $session,
                    'messages' => $messages
                ]);
            }
        }

        // New session creation
        if (empty($name)) {
            $name = 'ผู้ติดต่อทั่วไป';
        }

        $newToken = bin2hex(random_bytes(16));
        $insertData = [
            'session_token'       => $newToken,
            'user_name'           => $name,
            'user_tel'            => $tel,
            'user_ip'             => $this->request->getIPAddress(),
            'user_agent'          => substr($this->request->getUserAgent()->getAgentString(), 0, 500),
            'status'              => 'active',
            'unread_user_count'   => 0,
            'unread_admin_count'  => 0,
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s')
        ];

        $this->db->table('tb_chat_sessions')->insert($insertData);
        $sessionId = $this->db->insertID();

        // System greeting message
        $greeting = [
            'session_id'  => $sessionId,
            'sender_type' => 'system',
            'sender_name' => 'ระบบตอบรับอัตโนมัติ',
            'message'     => "สวัสดีครับ ยินดีต้อนรับสู่ศูนย์บริการข้อมูล SKJ มีข้อสงสัยหรือต้องการสอบถามข้อมูลด้านใด พิมพ์ข้อความไว้ได้เลยครับ เจ้าหน้าที่จะรีบตอบกลับให้เร็วที่สุดครับ ✨",
            'is_read'     => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->table('tb_chat_messages')->insert($greeting);

        $session = $this->db->table('tb_chat_sessions')->where('session_id', $sessionId)->get()->getRow();
        $messages = $this->db->table('tb_chat_messages')->where('session_id', $sessionId)->orderBy('created_at', 'ASC')->get()->getResult();

        return $this->response->setJSON([
            'status'   => 'success',
            'session'  => $session,
            'messages' => $messages
        ]);
    }

    /**
     * Send message from user
     * POST: session_token, message, user_name, user_tel
     */
    public function sendMessage()
    {
        $this->setCorsHeaders();

        $token = trim($this->request->getPost('session_token') ?? '');
        $messageText = trim($this->request->getPost('message') ?? '');
        $name = trim($this->request->getPost('user_name') ?? '');
        $tel = trim($this->request->getPost('user_tel') ?? '');

        if (empty($token) || empty($messageText)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
        }

        $session = $this->db->table('tb_chat_sessions')->where('session_token', $token)->get()->getRow();
        if (!$session) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบประวัติการสนทนา กรุณารีเฟรชหน้าจอ']);
        }

        // Update user profile if provided
        $updateSession = [
            'updated_at'         => date('Y-m-d H:i:s'),
            'unread_admin_count' => ($session->unread_admin_count ?? 0) + 1,
            'status'             => 'active'
        ];
        if (!empty($name)) $updateSession['user_name'] = $name;
        if (!empty($tel))  $updateSession['user_tel'] = $tel;
        $this->db->table('tb_chat_sessions')->where('session_id', $session->session_id)->update($updateSession);

        // Insert message
        $insertMsg = [
            'session_id'  => $session->session_id,
            'sender_type' => 'user',
            'sender_name' => !empty($name) ? $name : $session->user_name,
            'message'     => htmlspecialchars($messageText, ENT_QUOTES, 'UTF-8'),
            'is_read'     => 0,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->table('tb_chat_messages')->insert($insertMsg);
        $messageId = $this->db->insertID();

        // Send Notification to Telegram
        $telegramResult = $this->forwardToTelegram($session, $insertMsg['sender_name'], $messageText);
        if ($telegramResult['success'] && !empty($telegramResult['telegram_msg_id'])) {
            $this->db->table('tb_chat_messages')->where('message_id', $messageId)->update([
                'telegram_msg_id' => $telegramResult['telegram_msg_id']
            ]);
            $this->db->table('tb_chat_sessions')->where('session_id', $session->session_id)->update([
                'telegram_last_msg_id' => $telegramResult['telegram_msg_id']
            ]);
        }

        $newMsg = $this->db->table('tb_chat_messages')->where('message_id', $messageId)->get()->getRow();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => $newMsg
        ]);
    }

    /**
     * Poll for new messages
     * GET: session_token, last_message_id
     */
    public function getMessages()
    {
        $this->setCorsHeaders();

        $token = trim($this->request->getGet('session_token') ?? '');
        $lastId = (int)($this->request->getGet('last_message_id') ?? 0);

        if (empty($token)) {
            return $this->response->setJSON(['status' => 'error', 'messages' => []]);
        }

        // Auto-sync replies from Telegram Bot (Works on Localhost & Server)
        $this->syncTelegramUpdates();

        $session = $this->db->table('tb_chat_sessions')->where('session_token', $token)->get()->getRow();
        if (!$session) {
            return $this->response->setJSON(['status' => 'error', 'messages' => []]);
        }

        // Query new messages after lastId
        $builder = $this->db->table('tb_chat_messages')
            ->where('session_id', $session->session_id);

        if ($lastId > 0) {
            $builder->where('message_id >', $lastId);
        }

        $messages = $builder->orderBy('created_at', 'ASC')->get()->getResult();

        // Mark admin/system messages as read by user
        if (!empty($messages)) {
            $this->db->table('tb_chat_messages')
                ->where('session_id', $session->session_id)
                ->whereIn('sender_type', ['admin', 'system'])
                ->where('is_read', 0)
                ->update(['is_read' => 1]);

            $this->db->table('tb_chat_sessions')
                ->where('session_id', $session->session_id)
                ->update(['unread_user_count' => 0]);
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'messages' => $messages
        ]);
    }

    /**
     * Auto Sync Telegram Updates (2-Way Replies)
     */
    private function syncTelegramUpdates()
    {
        try {
            $config = $this->db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();
            if (!$config || $config->telegram_status !== 'on' || empty($config->telegram_bot_token)) {
                return;
            }

            $cache = \Config\Services::cache();
            $lastUpdateId = (int)($cache->get('tg_last_update_id') ?? 0);

            $url = "https://api.telegram.org/bot{$config->telegram_bot_token}/getUpdates?offset=" . ($lastUpdateId + 1) . "&limit=10&timeout=0";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || empty($response)) {
                return;
            }

            $data = json_decode($response, true);
            if (!isset($data['ok']) || $data['ok'] !== true || empty($data['result'])) {
                return;
            }

            $maxUpdateId = $lastUpdateId;
            foreach ($data['result'] as $update) {
                $updateId = $update['update_id'] ?? 0;
                if ($updateId > $maxUpdateId) {
                    $maxUpdateId = $updateId;
                }

                $msg = $update['message'] ?? null;
                if (!$msg) continue;

                $replyTo = $msg['reply_to_message'] ?? null;
                $replyText = trim($msg['text'] ?? '');
                $fromAdmin = $msg['from']['first_name'] ?? 'เจ้าหน้าที่ (Telegram)';

                if (!$replyTo || empty($replyText)) continue;

                $targetTelegramMsgId = $replyTo['message_id'] ?? null;
                if (!$targetTelegramMsgId) continue;

                // Find matching session
                $session = $this->db->table('tb_chat_sessions')
                    ->where('telegram_last_msg_id', $targetTelegramMsgId)
                    ->get()->getRow();

                if (!$session) {
                    $matchedMsg = $this->db->table('tb_chat_messages')
                        ->where('telegram_msg_id', $targetTelegramMsgId)
                        ->get()->getRow();
                    if ($matchedMsg) {
                        $session = $this->db->table('tb_chat_sessions')
                            ->where('session_id', $matchedMsg->session_id)
                            ->get()->getRow();
                    }
                }

                if ($session) {
                    // Check duplicate message
                    $existing = $this->db->table('tb_chat_messages')
                        ->where('telegram_msg_id', $msg['message_id'])
                        ->countAllResults();

                    if ($existing == 0) {
                        $this->db->table('tb_chat_messages')->insert([
                            'session_id'      => $session->session_id,
                            'sender_type'     => 'admin',
                            'sender_name'     => $fromAdmin,
                            'message'         => htmlspecialchars($replyText, ENT_QUOTES, 'UTF-8'),
                            'telegram_msg_id' => $msg['message_id'],
                            'is_read'         => 0,
                            'created_at'      => date('Y-m-d H:i:s')
                        ]);

                        $this->db->table('tb_chat_sessions')
                            ->where('session_id', $session->session_id)
                            ->update([
                                'updated_at'        => date('Y-m-d H:i:s'),
                                'unread_user_count' => ($session->unread_user_count ?? 0) + 1,
                                'status'            => 'active'
                            ]);
                    }
                }
            }

            if ($maxUpdateId > $lastUpdateId) {
                $cache->save('tg_last_update_id', $maxUpdateId, 86400);
            }
        } catch (\Throwable $e) {
            log_message('error', '[syncTelegramUpdates] ' . $e->getMessage());
        }
    }

    /**
     * Helper to forward message to Telegram
     */
    private function forwardToTelegram($session, $senderName, $text)
    {
        try {
            $config = $this->db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();
            if (!$config || $config->telegram_status !== 'on' || empty($config->telegram_bot_token) || empty($config->telegram_chat_id)) {
                return ['success' => false, 'message' => 'Telegram not configured'];
            }

            $shortToken = substr($session->session_token, 0, 8);
            $tel = !empty($session->user_tel) ? $session->user_tel : '-';

            $adminChatUrl = site_url('skjadmin/live-chat?session=' . $session->session_token);
            $tgChatUrl = str_replace('://localhost', '://127.0.0.1', $adminChatUrl);

            $msg = "💬 <b>มีข้อความแชทใหม่จากหน้าเว็บ!</b>\n";
            $msg .= "━━━━━━━━━━━━━━━━━━━\n";
            $msg .= "👤 <b>ผู้ติดต่อ:</b> " . htmlspecialchars($senderName, ENT_QUOTES, 'UTF-8') . "\n";
            $msg .= "📱 <b>เบอร์โทร:</b> <code>{$tel}</code>\n";
            $msg .= "🆔 <b>รหัสห้อง:</b> <code>#CHAT_{$shortToken}</code>\n";
            $msg .= "🕒 <b>เวลา:</b> " . date('d/m/Y H:i:s') . " น.\n";
            $msg .= "━━━━━━━━━━━━━━━━━━━\n";
            $msg .= "💬 <b>ข้อความ:</b>\n" . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . "\n";
            $msg .= "━━━━━━━━━━━━━━━━━━━\n";
            $msg .= "👉 <i>กด <b>Reply</b> เพื่อตอบกลับใน Telegram หรือ</i>\n";
            $msg .= "🖥️ <b>เปิดหน้าเว็บตอบแชท:</b>\n<a href=\"{$tgChatUrl}\">{$tgChatUrl}</a>";

            $postData = [
                'chat_id'                  => $config->telegram_chat_id,
                'text'                     => $msg,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true
            ];

            $isPublicUrl = filter_var($adminChatUrl, FILTER_VALIDATE_URL) && !preg_match('/localhost|127\.0\.0\.1/i', $adminChatUrl);
            if ($isPublicUrl) {
                $postData['reply_markup'] = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => '💬 เปิดหน้า Live Chat Dashboard', 'url' => $adminChatUrl]
                        ]
                    ]
                ]);
            }

            $url = "https://api.telegram.org/bot{$config->telegram_bot_token}/sendMessage";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            $resData = json_decode($response, true);
            if ($httpCode === 200 && isset($resData['ok']) && $resData['ok'] === true) {
                $tgMsgId = $resData['result']['message_id'] ?? null;
                return ['success' => true, 'telegram_msg_id' => $tgMsgId];
            }

            log_message('error', '[forwardToTelegram] Failed: HTTP ' . $httpCode . ' - ' . ($response ?: $error));
            return ['success' => false, 'error' => $resData['description'] ?? "HTTP $httpCode"];
        } catch (\Throwable $e) {
            log_message('error', '[forwardToTelegram] ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

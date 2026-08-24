<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlTelegram extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    private function checkAuth()
    {
        if (!$this->session->has('login_id') && !$this->session->has('pers_id')) {
            $targetUrl = current_url(true)->__toString();
            $this->session->set('redirect_url', $targetUrl);
            return redirect()->to(base_url('auth/login?redirect=' . urlencode($targetUrl)));
        }
        return null;
    }

    /**
     * Display Telegram Notify settings page
     */
    public function index()
    {
        if ($redir = $this->checkAuth()) {
            return $redir;
        }

        $this->ensureTableExists();

        $config = $this->db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();

        $data = [
            'title'  => 'จัดการการแจ้งเตือน Telegram',
            'menu'   => 'telegram_notify',
            'config' => $config
        ];

        return view('Admin/PageAdminTelegram/AdminTelegram', $data);
    }

    /**
     * Update Telegram Bot configuration (AJAX)
     */
    public function updateConfig()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        try {
            $this->ensureTableExists();

            $botToken = trim($this->request->getPost('telegram_bot_token') ?? '');
            $chatId = trim($this->request->getPost('telegram_chat_id') ?? '');
            $status = $this->request->getPost('telegram_status') === 'on' ? 'on' : 'off';
            $notifyNewApplicant = $this->request->getPost('telegram_notify_new_applicant') === 'on' ? 'on' : 'off';

            $updateData = [
                'telegram_bot_token'             => $botToken,
                'telegram_chat_id'               => $chatId,
                'telegram_status'                => $status,
                'telegram_notify_new_applicant'  => $notifyNewApplicant,
                'telegram_updated_at'            => date('Y-m-d H:i:s')
            ];

            $this->db->table('tb_telegram_config')->where('telegram_id', 1)->update($updateData);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'บันทึกการตั้งค่า Telegram เรียบร้อยแล้ว'
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Auto-detect Chat IDs from Telegram getUpdates (AJAX)
     */
    public function detectChatId()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $botToken = trim($this->request->getPost('telegram_bot_token') ?? '');
        if (empty($botToken)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'กรุณากรอก Bot Token ก่อนค้นหา Chat ID'
            ]);
        }

        $url = "https://api.telegram.org/bot{$botToken}/getUpdates";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ไม่สามารถเชื่อมต่อ Telegram ได้: ' . $error
            ]);
        }

        $resData = json_decode($response, true);
        if ($httpCode !== 200 || !isset($resData['ok']) || !$resData['ok']) {
            $desc = $resData['description'] ?? "HTTP $httpCode";
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Telegram Error: ' . $desc
            ]);
        }

        $updates = $resData['result'] ?? [];
        $foundChats = [];

        foreach ($updates as $up) {
            $chat = null;
            if (isset($up['message']['chat'])) {
                $chat = $up['message']['chat'];
            } elseif (isset($up['my_chat_member']['chat'])) {
                $chat = $up['my_chat_member']['chat'];
            } elseif (isset($up['channel_post']['chat'])) {
                $chat = $up['channel_post']['chat'];
            }

            if ($chat && isset($chat['id'])) {
                $cId = (string)$chat['id'];
                $cTitle = $chat['title'] ?? ($chat['first_name'] ?? 'Private Chat');
                $cType = $chat['type'] ?? 'unknown';
                $foundChats[$cId] = [
                    'id'    => $cId,
                    'title' => $cTitle,
                    'type'  => $cType
                ];
            }
        }

        $chatList = array_values($foundChats);

        if (empty($chatList)) {
            return $this->response->setJSON([
                'status'  => 'empty',
                'message' => 'ยังไม่พบข้อมูลห้องแชท<br><br><b>คำแนะนำ:</b><br>1. ดึง Bot เข้ากลุ่มแล้วหรือยัง?<br>2. โปรดพิมพ์ข้อความอะไรก็ได้ในกลุ่ม 1 ข้อความ (เช่น <code>test</code>)<br>3. แล้วกดปุ่มค้นหาอีกครั้ง'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'chats'   => $chatList,
            'message' => 'พบห้องแชท ' . count($chatList) . ' รายการ'
        ]);
    }

    public function sendTestMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $botToken = trim($this->request->getPost('telegram_bot_token') ?? '');
        $chatId = trim($this->request->getPost('telegram_chat_id') ?? '');
        $customMessage = trim($this->request->getPost('test_message') ?? '');

        if (empty($botToken)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'กรุณาระบุ Bot Token ก่อนทดสอบส่งข้อความ'
            ]);
        }

        if (empty($chatId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'กรุณาระบุ Chat ID / Group ID ก่อนทดสอบส่งข้อความ'
            ]);
        }

        $text = !empty($customMessage) ? $customMessage : "🔔 <b>ทดสอบการเชื่อมต่อ Telegram Bot</b>\n\nระบบรับสมัครนักเรียน SKJ Admission เชื่อมต่อกับ Telegram สำเร็จแล้ว!\n🕒 เวลา: " . date('d/m/Y H:i:s') . " น.";

        $result = $this->sendDirectTelegram($botToken, $chatId, $text);

        if ($result['success']) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'ส่งข้อความทดสอบไปยัง Telegram สำเร็จเรียบร้อยแล้ว!'
            ]);
        } else {
            $rawError = $result['error'] ?? '';
            $friendlyMsg = $rawError;

            if (strpos($rawError, 'chat not found') !== false) {
                $friendlyMsg = "ไม่พบห้องแชท (Chat not found)<br><br><b>วิธีแก้ไข:</b><br>1. ดึง Bot ที่สร้างเข้ากลุ่ม Telegram ก่อน<br>2. ตั้งค่าให้ Bot เป็น Admin ในกลุ่ม<br>3. ตรวจสอบว่า Chat ID มีเครื่องหมายลบนำหน้า (เช่น <code>-100...</code> หรือ <code>-...</code>)";
            } elseif (strpos($rawError, 'Unauthorized') !== false) {
                $friendlyMsg = "Bot Token ไม่ถูกต้อง (Unauthorized)<br><br>โปรดตรวจสอบ Bot Token ที่ได้รับจาก @BotFather อีกครั้ง";
            } elseif (strpos($rawError, 'bot was blocked') !== false) {
                $friendlyMsg = "บอทถูกบล็อกโดยผู้ใช้หรือกลุ่มนี้";
            } elseif (strpos($rawError, 'bot is not a member') !== false) {
                $friendlyMsg = "บอทยังไม่ได้เป็นสมาชิกในกลุ่ม โปรดดึงบอทเข้ากลุ่มก่อน";
            }

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $friendlyMsg
            ]);
        }
    }

    /**
     * Helper to send direct Telegram curl request
     */
    private function sendDirectTelegram($botToken, $chatId, $message)
    {
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $postData = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'error' => 'CURL Error: ' . $error];
        }

        $resData = json_decode($response, true);
        if ($httpCode === 200 && isset($resData['ok']) && $resData['ok'] === true) {
            return ['success' => true, 'response' => $resData];
        }

        $errDesc = $resData['description'] ?? "HTTP Status $httpCode";
        return ['success' => false, 'error' => $errDesc];
    }

    /**
     * Set Telegram Webhook (AJAX)
     */
    public function setWebhook()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $config = $this->db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();
        if (!$config || empty($config->telegram_bot_token)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'กรุณาระบุและบันทึก Bot Token ก่อนตั้งค่า Webhook'
            ]);
        }

        $webhookUrl = trim($this->request->getPost('webhook_url') ?? site_url('api/telegram/webhook'));

        $url = "https://api.telegram.org/bot{$config->telegram_bot_token}/setWebhook?url=" . urlencode($webhookUrl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'CURL Error: ' . $error]);
        }

        $resData = json_decode($response, true);
        if ($httpCode === 200 && isset($resData['ok']) && $resData['ok'] === true) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'ตั้งค่า Webhook สำเร็จ! ตอนนี้เมื่อครู Reply ใน Telegram ข้อความจะส่งกลับหานักเรียนบนหน้าเว็บทันที',
                'result'  => $resData
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Telegram Error: ' . ($resData['description'] ?? "HTTP $httpCode")
        ]);
    }

    /**
     * Get Telegram Webhook info (AJAX)
     */
    public function getWebhookInfo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $config = $this->db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();
        if (!$config || empty($config->telegram_bot_token)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'กรุณาระบุและบันทึก Bot Token ก่อนตรวจสอบ Webhook'
            ]);
        }

        $url = "https://api.telegram.org/bot{$config->telegram_bot_token}/getWebhookInfo";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'CURL Error: ' . $error]);
        }

        $resData = json_decode($response, true);
        if ($httpCode === 200 && isset($resData['ok']) && $resData['ok'] === true) {
            return $this->response->setJSON([
                'status' => 'success',
                'info'   => $resData['result'] ?? []
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Telegram Error: ' . ($resData['description'] ?? "HTTP $httpCode")
        ]);
    }

    /**
     * Delete Webhook (AJAX)
     */
    public function deleteWebhook()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $config = $this->db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();
        if (!$config || empty($config->telegram_bot_token)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'กรุณาระบุ Bot Token ก่อนลบ Webhook'
            ]);
        }

        $url = "https://api.telegram.org/bot{$config->telegram_bot_token}/deleteWebhook";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $resData = json_decode($response, true);
        if ($httpCode === 200 && isset($resData['ok']) && $resData['ok'] === true) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'ยกเลิกการตั้งค่า Webhook เรียบร้อยแล้ว'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Telegram Error: ' . ($resData['description'] ?? "HTTP $httpCode")
        ]);
    }

    /**
     * Ensure tb_telegram_config table exists
     */
    private function ensureTableExists()
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS tb_telegram_config (
                telegram_id INT(11) NOT NULL AUTO_INCREMENT,
                telegram_bot_token VARCHAR(255) NULL,
                telegram_chat_id VARCHAR(100) NULL,
                telegram_status ENUM('on', 'off') DEFAULT 'on',
                telegram_notify_new_applicant ENUM('on', 'off') DEFAULT 'on',
                telegram_updated_at DATETIME NULL,
                PRIMARY KEY (telegram_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

            $this->db->query($sql);

            // Seed default row if empty
            $count = $this->db->table('tb_telegram_config')->countAllResults();
            if ($count === 0) {
                $this->db->table('tb_telegram_config')->insert([
                    'telegram_id'                   => 1,
                    'telegram_bot_token'            => '',
                    'telegram_chat_id'              => '',
                    'telegram_status'               => 'on',
                    'telegram_notify_new_applicant' => 'on',
                    'telegram_updated_at'           => date('Y-m-d H:i:s')
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', '[ensureTableExists] ' . $e->getMessage());
        }
    }
}

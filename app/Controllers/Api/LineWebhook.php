<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

/**
 * LINE Webhook Controller
 * รับข้อมูลจาก LINE Messaging API เมื่อมีเหตุการณ์เกิดขึ้น
 */
class LineWebhook extends BaseController
{
    protected $db;
    protected $channelAccessToken;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->channelAccessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN') ?: 'J5jOTH9S0h8Kye0s6SIoPWv+fLT0J4+K7ozXYM7xV7yZRjGt/ggNhJPck/fddEIj0S5D/P5FJvWYD8cFP4wPPoi3DpvGwbxVeecGrRQpx4dbE/VBaIqXAPnR7Ix7/qDYhO006+qD9qpWu64Y31FN+gdB04t89/1O/w1cDnyilFU=';
    }

    /**
     * รับ Webhook จาก LINE
     */
    public function webhook()
    {
        // รับข้อมูล JSON
        $body = file_get_contents('php://input');
        $events = json_decode($body, true);

        if (!$events || !isset($events['events'])) {
            return $this->response->setStatusCode(200)->setBody('OK');
        }

        foreach ($events['events'] as $event) {
            $this->handleEvent($event);
        }

        return $this->response->setStatusCode(200)->setBody('OK');
    }

    /**
     * จัดการ Event
     */
    private function handleEvent($event)
    {
        $type = $event['type'] ?? '';
        $userId = $event['source']['userId'] ?? null;

        if (!$userId) {
            return;
        }

        switch ($type) {
            case 'follow':
                // เมื่อคนเพิ่มเพื่อน OA
                $this->handleFollow($userId, $event['replyToken'] ?? null);
                break;

            case 'unfollow':
                // เมื่อคนบล็อก OA
                $this->handleUnfollow($userId);
                break;

            case 'message':
                // เมื่อมีข้อความเข้ามา
                $this->handleMessage($userId, $event['message'] ?? [], $event['replyToken'] ?? null);
                break;
        }
    }

    /**
     * เมื่อคนเพิ่มเพื่อน OA
     */
    private function handleFollow($userId, $replyToken)
    {
        // ดึงข้อมูลผู้ใช้
        $profile = $this->getProfile($userId);

        // ส่งข้อความต้อนรับ
        if ($replyToken) {
            $welcomeMsg = "สวัสดีครับ! 🎉\n\n";
            $welcomeMsg .= "ยินดีต้อนรับสู่ระบบแจ้งเตือน SKJ Admission\n\n";
            $welcomeMsg .= "📌 พิมพ์ \"ลงทะเบียน\" เพื่อลงทะเบียนรับแจ้งเตือน\n";
            $welcomeMsg .= "📌 พิมพ์ \"ยกเลิก\" เพื่อยกเลิกการรับแจ้งเตือน\n";
            $welcomeMsg .= "📌 พิมพ์ \"สถานะ\" เพื่อตรวจสอบสถานะ";

            $this->replyMessage($replyToken, $welcomeMsg);
        }

        log_message('info', 'LINE Follow: ' . $userId . ' - ' . ($profile['displayName'] ?? 'Unknown'));
    }

    /**
     * เมื่อคนบล็อก OA
     */
    private function handleUnfollow($userId)
    {
        // ลบออกจากรายการ Admin
        if ($this->db->tableExists('tb_line_admins')) {
            $this->db->table('tb_line_admins')
                ->where('line_user_id', $userId)
                ->delete();
        }

        log_message('info', 'LINE Unfollow: ' . $userId);
    }

    /**
     * เมื่อมีข้อความเข้ามา
     */
    private function handleMessage($userId, $message, $replyToken)
    {
        if ($message['type'] !== 'text') {
            return;
        }

        $text = trim(mb_strtolower($message['text']));

        // ตรวจสอบคำสั่ง
        switch (true) {
            case in_array($text, ['ลงทะเบียน', 'register', 'สมัคร']):
                $this->registerAdmin($userId, $replyToken);
                break;

            case in_array($text, ['ยกเลิก', 'unregister', 'cancel']):
                $this->unregisterAdmin($userId, $replyToken);
                break;

            case in_array($text, ['สถานะ', 'status']):
                $this->checkStatus($userId, $replyToken);
                break;

            case in_array($text, ['ช่วยเหลือ', 'help', '?']):
                $this->showHelp($replyToken);
                break;

            case in_array($text, ['userid', 'id', 'myid']):
                $this->showUserId($userId, $replyToken);
                break;
        }
    }

    /**
     * แสดง User ID
     */
    private function showUserId($userId, $replyToken)
    {
        $msg = "🆔 LINE User ID ของคุณ\n\n";
        $msg .= $userId . "\n\n";
        $msg .= "📋 คัดลอกไปใส่ในหน้า Admin ได้เลย";

        $this->replyMessage($replyToken, $msg);
    }

    /**
     * ลงทะเบียน Admin
     */
    private function registerAdmin($userId, $replyToken)
    {
        // ตรวจสอบว่ามีตารางหรือไม่
        if (!$this->db->tableExists('tb_line_admins')) {
            $this->replyMessage($replyToken, "❌ ระบบยังไม่พร้อม กรุณาติดต่อผู้ดูแลระบบ");
            return;
        }

        // ตรวจสอบว่าลงทะเบียนแล้วหรือยัง
        $existing = $this->db->table('tb_line_admins')
            ->where('line_user_id', $userId)
            ->get()
            ->getRow();

        if ($existing) {
            if ($existing->line_status == 1) {
                $this->replyMessage($replyToken, "✅ คุณลงทะเบียนรับแจ้งเตือนอยู่แล้ว");
            } else {
                // เปิดใหม่
                $this->db->table('tb_line_admins')
                    ->where('line_user_id', $userId)
                    ->update([
                        'line_status' => 1,
                        'line_updated' => date('Y-m-d H:i:s')
                    ]);
                $this->replyMessage($replyToken, "✅ เปิดรับแจ้งเตือนอีกครั้งแล้ว!");
            }
            return;
        }

        // ดึงข้อมูลโปรไฟล์
        $profile = $this->getProfile($userId);

        // เพิ่มลงฐานข้อมูล
        $this->db->table('tb_line_admins')->insert([
            'line_user_id' => $userId,
            'line_display_name' => $profile['displayName'] ?? 'Unknown',
            'line_picture_url' => $profile['pictureUrl'] ?? null,
            'line_status' => 1,
            'line_events' => json_encode(['new_applicant']),
            'line_created' => date('Y-m-d H:i:s'),
            'line_updated' => date('Y-m-d H:i:s')
        ]);

        $msg = "✅ ลงทะเบียนสำเร็จ!\n\n";
        $msg .= "👤 ชื่อ: " . ($profile['displayName'] ?? 'Unknown') . "\n";
        $msg .= "📅 เวลา: " . date('d/m/Y H:i') . " น.\n\n";
        $msg .= "คุณจะได้รับแจ้งเตือนเมื่อมีนักเรียนสมัครใหม่";

        $this->replyMessage($replyToken, $msg);

        log_message('info', 'LINE Admin Registered: ' . $userId);
    }

    /**
     * ยกเลิกการลงทะเบียน
     */
    private function unregisterAdmin($userId, $replyToken)
    {
        if (!$this->db->tableExists('tb_line_admins')) {
            $this->replyMessage($replyToken, "❌ ระบบยังไม่พร้อม");
            return;
        }

        $result = $this->db->table('tb_line_admins')
            ->where('line_user_id', $userId)
            ->update([
                'line_status' => 0,
                'line_updated' => date('Y-m-d H:i:s')
            ]);

        if ($this->db->affectedRows() > 0) {
            $this->replyMessage($replyToken, "🔕 ปิดรับแจ้งเตือนแล้ว\n\nพิมพ์ \"ลงทะเบียน\" เพื่อเปิดรับอีกครั้ง");
        } else {
            $this->replyMessage($replyToken, "❓ คุณยังไม่ได้ลงทะเบียนในระบบ");
        }
    }

    /**
     * ตรวจสอบสถานะ
     */
    private function checkStatus($userId, $replyToken)
    {
        if (!$this->db->tableExists('tb_line_admins')) {
            $this->replyMessage($replyToken, "❌ ระบบยังไม่พร้อม");
            return;
        }

        $admin = $this->db->table('tb_line_admins')
            ->where('line_user_id', $userId)
            ->get()
            ->getRow();

        if (!$admin) {
            $msg = "❓ คุณยังไม่ได้ลงทะเบียน\n\n";
            $msg .= "พิมพ์ \"ลงทะเบียน\" เพื่อเริ่มรับแจ้งเตือน";
        } else {
            $status = $admin->line_status == 1 ? '✅ เปิดใช้งาน' : '🔕 ปิดใช้งาน';
            $msg = "📊 สถานะของคุณ\n\n";
            $msg .= "👤 ชื่อ: " . $admin->line_display_name . "\n";
            $msg .= "📌 สถานะ: " . $status . "\n";
            $msg .= "📅 ลงทะเบียน: " . date('d/m/Y H:i', strtotime($admin->line_created)) . "\n";
        }

        $this->replyMessage($replyToken, $msg);
    }

    /**
     * แสดงคำสั่งช่วยเหลือ
     */
    private function showHelp($replyToken)
    {
        $msg = "📚 คำสั่งที่ใช้ได้\n\n";
        $msg .= "📌 ลงทะเบียน - เริ่มรับแจ้งเตือน\n";
        $msg .= "📌 ยกเลิก - หยุดรับแจ้งเตือน\n";
        $msg .= "📌 สถานะ - ตรวจสอบสถานะ\n";
        $msg .= "📌 userid - ดู LINE User ID\n";
        $msg .= "📌 ช่วยเหลือ - แสดงคำสั่ง\n\n";
        $msg .= "🏫 SKJ Admission System";

        $this->replyMessage($replyToken, $msg);
    }

    /**
     * ดึงข้อมูลโปรไฟล์ผู้ใช้
     */
    private function getProfile($userId)
    {
        $ch = curl_init('https://api.line.me/v2/bot/profile/' . $userId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->channelAccessToken
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true) ?: [];
    }

    /**
     * ตอบกลับข้อความ
     */
    private function replyMessage($replyToken, $message)
    {
        if (!$replyToken) {
            return false;
        }

        $data = [
            'replyToken' => $replyToken,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message
                ]
            ]
        ];

        $ch = curl_init('https://api.line.me/v2/bot/message/reply');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }
}

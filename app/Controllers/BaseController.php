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
     * Send Telegram Bot Message
     * @param string $message Text to send (HTML formatted)
     * @param string|null $chatId Custom Chat ID (optional, defaults to DB config)
     * @param string|null $botToken Custom Bot Token (optional, defaults to DB config)
     * @return array
     */
    protected function sendTelegramMessage($message, $chatId = null, $botToken = null)
    {
        try {
            $db = \Config\Database::connect();
            $config = $db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();

            if (!$config || $config->telegram_status !== 'on') {
                return ['success' => false, 'message' => 'Telegram notify is disabled'];
            }

            $token = $botToken ?: $config->telegram_bot_token;
            $targetChatId = $chatId ?: $config->telegram_chat_id;

            if (empty($token) || empty($targetChatId)) {
                log_message('warning', 'Telegram Notify: Missing Bot Token or Chat ID');
                return ['success' => false, 'message' => 'Missing Bot Token or Chat ID'];
            }

            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            $postData = [
                'chat_id'                  => $targetChatId,
                'text'                     => $message,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                log_message('error', 'Telegram CURL Error: ' . $error);
                return ['success' => false, 'error' => $error];
            }

            $resData = json_decode($response, true);
            if ($httpCode === 200 && isset($resData['ok']) && $resData['ok'] === true) {
                log_message('info', 'Telegram notification sent successfully');
                return ['success' => true, 'response' => $resData];
            }

            log_message('error', 'Telegram API Error: ' . ($resData['description'] ?? 'HTTP ' . $httpCode));
            return ['success' => false, 'error' => $resData['description'] ?? "HTTP $httpCode"];
        } catch (\Throwable $e) {
            log_message('error', 'Telegram Send Exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send rich notification when new applicant registers
     * @param array $applicantData
     * @param string|int $year
     */
    protected function sendTelegramApplicantNotification($applicantData, $year)
    {
        try {
            $db = \Config\Database::connect();
            $config = $db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();
            if ($config && $config->telegram_notify_new_applicant === 'off') {
                return;
            }

            $prefix = $applicantData['recruit_prefix'] ?? '';
            $fname = $applicantData['recruit_firstName'] ?? '';
            $lname = $applicantData['recruit_lastName'] ?? '';
            $level = $applicantData['recruit_regLevel'] ?? '';
            $course = $applicantData['recruit_tpyeRoom'] ?? '-';
            $category = $applicantData['recruit_category'] ?? '';
            $recruitId = $applicantData['recruit_id'] ?? '-';

            // Get readable quota name
            $quotaInfo = $db->table('tb_quota')->where('quota_id', $category)->orWhere('quota_key', $category)->get()->getRow();
            $quotaName = $quotaInfo ? $quotaInfo->quota_explain : 'ทั่วไป';

            $msg = "📢 <b>มีนักเรียนสมัครเรียนออนไลน์ใหม่!</b>\n";
            $msg .= "━━━━━━━━━━━━━━━━━━━\n";
            $msg .= "🆔 <b>เลขที่สมัคร:</b> <code>{$recruitId}</code>\n";
            $msg .= "👤 <b>ชื่อ-สกุล:</b> {$prefix}{$fname} {$lname}\n";
            $msg .= "🏫 <b>ระดับชั้น:</b> ม.{$level}\n";
            $msg .= "🎯 <b>ประเภทโควต้า:</b> {$quotaName}\n";
            $msg .= "📚 <b>แผนการเรียน:</b> {$course}\n";
            $msg .= "📅 <b>ปีการศึกษา:</b> {$year}\n";
            $msg .= "🕒 <b>เวลาที่สมัคร:</b> " . date('d/m/Y H:i') . " น.\n";
            $msg .= "━━━━━━━━━━━━━━━━━━━\n";
            $msg .= "🔗 <a href=\"" . site_url('skjadmin/recruits') . "\">เข้าสู่ระบบตรวจสอบข้อมูล</a>";

            $this->sendTelegramMessage($msg);
        } catch (\Throwable $e) {
            log_message('error', 'Telegram Applicant Alert Exception: ' . $e->getMessage());
        }
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



    /**
     * ส่งทั้ง LINE Broadcast และ LINE Push ไปยัง Admin ที่ลงทะเบียน
     * @param string $message ข้อความที่จะส่ง
     * @param string $eventType ประเภท event เช่น 'new_applicant'
     * @return array ผลลัพธ์การส่ง
     */
    protected function sendLineAll($message, $eventType = 'new_applicant')
    {
        $results = [
            'broadcast' => false,
            'push' => []
        ];

        // Don't send if running on localhost
        if (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) {
            log_message('debug', 'LINE All skipped (localhost)');
            return $results;
        }

        // ส่ง LINE Push ไปยัง Admin ที่ลงทะเบียน
        try {
            $results['push'] = $this->sendLinePushToAdmins($message, $eventType);
        } catch (\Exception $e) {
            log_message('error', 'LINE Push Error: ' . $e->getMessage());
        }

        // ถ้าไม่มี Admin ลงทะเบียน ให้ fallback ไป Broadcast
        if (empty($results['push']) || (isset($results['push']['status']) && $results['push']['status'] === 'no_admins')) {
            try {
                $results['broadcast'] = $this->sendLineBroadcast($message);
            } catch (\Exception $e) {
                log_message('error', 'LINE Broadcast Error: ' . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * ส่ง LINE Push Message ไปยัง Admin ที่ลงทะเบียนไว้
     * @param string $message ข้อความที่จะส่ง
     * @param string $eventType ประเภท event
     * @return array ผลลัพธ์การส่ง
     */
    protected function sendLinePushToAdmins($message, $eventType = 'new_applicant')
    {
        // ดึงรายการ Admin ที่ลงทะเบียนและเปิดรับแจ้งเตือน
        $adminUserIds = $this->getLineAdminUserIds($eventType);

        if (empty($adminUserIds)) {
            log_message('info', 'LINE Push: No admins registered for event: ' . $eventType);
            return ['status' => 'no_admins', 'message' => 'ไม่มี Admin ลงทะเบียนรับแจ้งเตือน'];
        }

        // ส่ง Multicast (ส่งครั้งเดียวไปหลายคน)
        return $this->sendLineMulticast($adminUserIds, $message);
    }

    /**
     * ส่ง LINE Multicast Message (ส่งถึงหลายคนพร้อมกัน)
     * @param array $userIds รายการ LINE User ID
     * @param string $message ข้อความ
     * @return array ผลลัพธ์
     */
    protected function sendLineMulticast($userIds, $message)
    {
        $token = getenv('LINE_CHANNEL_ACCESS_TOKEN') ?: 'J5jOTH9S0h8Kye0s6SIoPWv+fLT0J4+K7ozXYM7xV7yZRjGt/ggNhJPck/fddEIj0S5D/P5FJvWYD8cFP4wPPoi3DpvGwbxVeecGrRQpx4dbE/VBaIqXAPnR7Ix7/qDYhO006+qD9qpWu64Y31FN+gdB04t89/1O/w1cDnyilFU=';

        if (empty($token)) {
            return ['status' => 'error', 'message' => 'No channel access token'];
        }

        // Multicast รองรับสูงสุด 500 คนต่อครั้ง
        $chunks = array_chunk($userIds, 500);
        $results = [];

        foreach ($chunks as $chunk) {
            $data = [
                'to' => $chunk,
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $message
                    ]
                ]
            ];

            $ch = curl_init('https://api.line.me/v2/bot/message/multicast');
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
                $results[] = ['status' => 'error', 'message' => $error];
            } elseif ($httpCode !== 200) {
                $results[] = ['status' => 'error', 'http_code' => $httpCode, 'response' => $result];
            } else {
                $results[] = ['status' => 'success', 'count' => count($chunk)];
            }
        }

        $successCount = array_sum(array_map(function ($r) {
            return isset($r['count']) ? $r['count'] : 0;
        }, $results));

        log_message('info', 'LINE Multicast sent to ' . $successCount . ' admins');

        return [
            'status' => 'success',
            'total_sent' => $successCount,
            'details' => $results
        ];
    }

    /**
     * ดึงรายการ LINE User ID ของ Admin ที่ลงทะเบียน
     * @param string $eventType ประเภท event
     * @return array รายการ User ID
     */
    protected function getLineAdminUserIds($eventType = null)
    {
        try {
            $db = \Config\Database::connect();

            // ตรวจสอบว่ามีตารางหรือไม่
            if (!$db->tableExists('tb_line_admins')) {
                return [];
            }

            $builder = $db->table('tb_line_admins')
                ->where('line_status', 1)
                ->select('line_user_id, line_events');

            $admins = $builder->get()->getResult();

            $userIds = [];
            foreach ($admins as $admin) {
                // ถ้าระบุ eventType ให้ตรวจสอบว่า Admin นี้รับ event นี้หรือไม่
                if ($eventType && !empty($admin->line_events)) {
                    $events = json_decode($admin->line_events, true);
                    if (is_array($events) && !in_array($eventType, $events)) {
                        continue;
                    }
                }
                $userIds[] = $admin->line_user_id;
            }

            return $userIds;
        } catch (\Exception $e) {
            log_message('error', 'Error getting LINE admin user IDs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * ส่ง LINE Push Message ถึงคนเดียว
     * @param string $userId LINE User ID
     * @param string $message ข้อความ
     * @return array ผลลัพธ์
     */
    protected function sendLinePush($userId, $message)
    {
        $token = getenv('LINE_CHANNEL_ACCESS_TOKEN') ?: 'J5jOTH9S0h8Kye0s6SIoPWv+fLT0J4+K7ozXYM7xV7yZRjGt/ggNhJPck/fddEIj0S5D/P5FJvWYD8cFP4wPPoi3DpvGwbxVeecGrRQpx4dbE/VBaIqXAPnR7Ix7/qDYhO006+qD9qpWu64Y31FN+gdB04t89/1O/w1cDnyilFU=';

        if (empty($token)) {
            return ['status' => 'error', 'message' => 'No channel access token'];
        }

        $data = [
            'to' => $userId,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message
                ]
            ]
        ];

        $ch = curl_init('https://api.line.me/v2/bot/message/push');
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
            log_message('error', 'LINE Push CURL Error: ' . $error);
            return ['status' => 'error', 'message' => 'CURL Error: ' . $error];
        }

        if ($httpCode !== 200) {
            $responseData = json_decode($result, true);
            $errorMsg = $responseData['message'] ?? $result;
            log_message('error', 'LINE Push Error: HTTP ' . $httpCode . ' - ' . $result);
            return ['status' => 'error', 'message' => 'HTTP ' . $httpCode . ': ' . $errorMsg];
        }

        log_message('info', 'LINE Push sent successfully to: ' . $userId);
        return ['status' => 'success', 'message' => 'ส่งสำเร็จ'];
    }
}


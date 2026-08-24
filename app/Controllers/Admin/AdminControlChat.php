<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlChat extends BaseController
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
     * Live Chat Dashboard Page
     */
    public function index()
    {
        if ($redir = $this->checkAuth()) {
            return $redir;
        }

        $activeToken = $this->request->getGet('session') ?? '';

        $data = [
            'title'       => 'ระบบสนทนาสด (Live Chat)',
            'menu'        => 'live_chat',
            'activeToken' => $activeToken
        ];

        return view('Admin/PageAdminChat/AdminChatIndex', $data);
    }

    /**
     * Get list of chat sessions (AJAX)
     */
    public function getSessions()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $this->syncTelegramUpdates();

        $sessions = $this->db->table('tb_chat_sessions')
            ->orderBy('updated_at', 'DESC')
            ->limit(50)
            ->get()
            ->getResult();

        // Get last message snippet for each session
        foreach ($sessions as &$s) {
            $lastMsg = $this->db->table('tb_chat_messages')
                ->where('session_id', $s->session_id)
                ->orderBy('created_at', 'DESC')
                ->limit(1)
                ->get()
                ->getRow();
            $s->last_message = $lastMsg ? $lastMsg->message : '';
            $s->last_message_time = $lastMsg ? $lastMsg->created_at : $s->created_at;
            $s->last_sender = $lastMsg ? $lastMsg->sender_type : '';
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'sessions' => $sessions
        ]);
    }

    /**
     * Get messages for a specific session (AJAX)
     */
    public function getSessionMessages($sessionId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $session = $this->db->table('tb_chat_sessions')->where('session_id', $sessionId)->get()->getRow();
        if (!$session) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Session not found']);
        }

        // Mark admin unread as 0
        $this->db->table('tb_chat_sessions')->where('session_id', $sessionId)->update([
            'unread_admin_count' => 0
        ]);

        $messages = $this->db->table('tb_chat_messages')
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResult();

        return $this->response->setJSON([
            'status'   => 'success',
            'session'  => $session,
            'messages' => $messages
        ]);
    }

    /**
     * Admin send reply message (AJAX)
     */
    public function sendReply()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $sessionId = (int)$this->request->getPost('session_id');
        $replyText = trim($this->request->getPost('message') ?? '');

        if ($sessionId <= 0 || empty($replyText)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกข้อความตอบกลับ']);
        }

        $session = $this->db->table('tb_chat_sessions')->where('session_id', $sessionId)->get()->getRow();
        if (!$session) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลห้องสนทนา']);
        }

        $adminName = $this->session->get('pers_fullname') ?? ($this->session->get('username') ?? 'เจ้าหน้าที่ / Admin');

        $insertMsg = [
            'session_id'  => $session->session_id,
            'sender_type' => 'admin',
            'sender_name' => $adminName . ' (ครู/แอดมิน)',
            'message'     => htmlspecialchars($replyText, ENT_QUOTES, 'UTF-8'),
            'is_read'     => 0,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->table('tb_chat_messages')->insert($insertMsg);
        $msgId = $this->db->insertID();

        // Update session
        $this->db->table('tb_chat_sessions')->where('session_id', $session->session_id)->update([
            'updated_at'         => date('Y-m-d H:i:s'),
            'unread_user_count'  => ($session->unread_user_count ?? 0) + 1,
            'unread_admin_count' => 0
        ]);

        $newMsg = $this->db->table('tb_chat_messages')->where('message_id', $msgId)->get()->getRow();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => $newMsg
        ]);
    }

    /**
     * Toggle session status (active/closed)
     */
    public function toggleStatus($sessionId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $session = $this->db->table('tb_chat_sessions')->where('session_id', $sessionId)->get()->getRow();
        if (!$session) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Session not found']);
        }

        $newStatus = $session->status === 'active' ? 'closed' : 'active';
        $this->db->table('tb_chat_sessions')->where('session_id', $sessionId)->update([
            'status'     => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status'     => 'success',
            'new_status' => $newStatus,
            'message'    => $newStatus === 'closed' ? 'ปิดการสนทนาเรียบร้อยแล้ว' : 'เปิดการสนทนาอีกครั้งแล้ว'
        ]);
    }

    /**
     * Sync replies directly from Telegram getUpdates
     */
    private function syncTelegramUpdates()
    {
        try {
            $config = $this->db->table('tb_telegram_config')->where('telegram_id', 1)->get()->getRow();
            if (!$config || $config->telegram_status !== 'on' || empty($config->telegram_bot_token)) {
                return;
            }

            $cache = \Config\Services::cache();
            $lastCheck = $cache->get('tg_last_poll_time');
            if ($lastCheck && (time() - $lastCheck) < 2) {
                return;
            }
            $cache->save('tg_last_poll_time', time(), 10);

            $lastUpdateId = (int)$cache->get('tg_last_update_id') ?: 0;
            $url = "https://api.telegram.org/bot{$config->telegram_bot_token}/getUpdates?offset={$lastUpdateId}&limit=20&timeout=0";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || empty($response)) {
                return;
            }

            $data = json_decode($response, true);
            if (!isset($data['ok']) || !$data['ok'] || empty($data['result'])) {
                return;
            }

            $maxUpdateId = $lastUpdateId;
            foreach ($data['result'] as $up) {
                $uId = (int)($up['update_id'] ?? 0);
                if ($uId >= $maxUpdateId) {
                    $maxUpdateId = $uId + 1;
                }

                $msg = $up['message'] ?? ($up['edited_message'] ?? null);
                if (!$msg) continue;

                $text = trim($msg['text'] ?? ($msg['caption'] ?? ''));
                if (empty($text)) continue;

                $targetSession = null;

                // 1. Match by reply_to_message
                if (isset($msg['reply_to_message']['message_id'])) {
                    $replyToId = (string)$msg['reply_to_message']['message_id'];

                    $matchedMsg = $this->db->table('tb_chat_messages')
                        ->where('telegram_msg_id', $replyToId)
                        ->get()
                        ->getRow();

                    if ($matchedMsg) {
                        $targetSession = $this->db->table('tb_chat_sessions')
                            ->where('session_id', $matchedMsg->session_id)
                            ->get()
                            ->getRow();
                    } else {
                        $targetSession = $this->db->table('tb_chat_sessions')
                            ->where('telegram_last_msg_id', $replyToId)
                            ->get()
                            ->getRow();
                    }
                }

                // 2. Match by hashtag #CHAT_xxxx
                if (!$targetSession && preg_match('/#CHAT_([a-zA-Z0-9]+)/i', $text, $matches)) {
                    $shortToken = $matches[1];
                    $targetSession = $this->db->table('tb_chat_sessions')
                        ->like('session_token', $shortToken, 'after')
                        ->get()
                        ->getRow();
                    $text = trim(str_replace($matches[0], '', $text));
                }

                if ($targetSession) {
                    $tgMsgId = (string)($msg['message_id'] ?? '');

                    $existing = $this->db->table('tb_chat_messages')
                        ->where('session_id', $targetSession->session_id)
                        ->where('telegram_msg_id', $tgMsgId)
                        ->get()
                        ->getRow();

                    if (!$existing) {
                        $from = $msg['from'] ?? [];
                        $adminName = trim(($from['first_name'] ?? '') . ' ' . ($from['last_name'] ?? ''));
                        if (empty($adminName)) {
                            $adminName = $from['username'] ?? 'เจ้าหน้าที่ / Admin';
                        }
                        $adminName .= ' (ครู/แอดมิน)';

                        $this->db->table('tb_chat_messages')->insert([
                            'session_id'      => $targetSession->session_id,
                            'sender_type'     => 'admin',
                            'sender_name'     => $adminName,
                            'message'         => htmlspecialchars($text, ENT_QUOTES, 'UTF-8'),
                            'telegram_msg_id' => $tgMsgId,
                            'is_read'         => 0,
                            'created_at'      => date('Y-m-d H:i:s')
                        ]);

                        $this->db->table('tb_chat_sessions')->where('session_id', $targetSession->session_id)->update([
                            'updated_at'         => date('Y-m-d H:i:s'),
                            'unread_user_count'  => ($targetSession->unread_user_count ?? 0) + 1,
                            'unread_admin_count' => 0
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
}

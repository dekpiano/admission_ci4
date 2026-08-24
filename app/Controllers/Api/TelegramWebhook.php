<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class TelegramWebhook extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Webhook endpoint called by Telegram Bot
     */
    public function handle()
    {
        $input = file_get_contents('php://input');
        if (empty($input)) {
            return $this->response->setStatusCode(200)->setBody('OK - No payload');
        }

        $update = json_decode($input, true);
        if (!$update) {
            return $this->response->setStatusCode(200)->setBody('OK - Invalid JSON');
        }

        // Process message or edited message
        $message = $update['message'] ?? ($update['edited_message'] ?? null);
        if (!$message) {
            return $this->response->setStatusCode(200)->setBody('OK - Not a message');
        }

        $text = trim($message['text'] ?? ($message['caption'] ?? ''));
        if (empty($text)) {
            return $this->response->setStatusCode(200)->setBody('OK - No text');
        }

        // 1. Check if this is a reply to an existing forwarded message
        $targetSession = null;
        if (isset($message['reply_to_message']['message_id'])) {
            $replyToId = (string)$message['reply_to_message']['message_id'];

            // Match from message history
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
                // Match from session last telegram msg id
                $targetSession = $this->db->table('tb_chat_sessions')
                    ->where('telegram_last_msg_id', $replyToId)
                    ->get()
                    ->getRow();
            }
        }

        // 2. If not a direct reply, check if message contains hashtag e.g. #CHAT_1a2b3c4d
        if (!$targetSession && preg_match('/#CHAT_([a-zA-Z0-9]+)/i', $text, $matches)) {
            $shortToken = $matches[1];
            $targetSession = $this->db->table('tb_chat_sessions')
                ->like('session_token', $shortToken, 'after')
                ->get()
                ->getRow();
            // Remove the hashtag from the outgoing message text
            $text = trim(str_replace($matches[0], '', $text));
        }

        // If matched to a valid web chat session
        if ($targetSession) {
            $from = $message['from'] ?? [];
            $adminName = trim(($from['first_name'] ?? '') . ' ' . ($from['last_name'] ?? ''));
            if (empty($adminName)) {
                $adminName = $from['username'] ?? 'เจ้าหน้าที่ / Admin';
            }
            $adminName .= ' (ครู/แอดมิน)';

            // Insert admin reply into chat messages
            $insertMsg = [
                'session_id'      => $targetSession->session_id,
                'sender_type'     => 'admin',
                'sender_name'     => $adminName,
                'message'         => htmlspecialchars($text, ENT_QUOTES, 'UTF-8'),
                'telegram_msg_id' => (string)($message['message_id'] ?? ''),
                'is_read'         => 0,
                'created_at'      => date('Y-m-d H:i:s')
            ];
            $this->db->table('tb_chat_messages')->insert($insertMsg);

            // Update session status & unread count
            $this->db->table('tb_chat_sessions')->where('session_id', $targetSession->session_id)->update([
                'updated_at'         => date('Y-m-d H:i:s'),
                'unread_user_count'  => ($targetSession->unread_user_count ?? 0) + 1,
                'unread_admin_count' => 0
            ]);

            log_message('info', "[TelegramWebhook] Admin reply added to session #{$targetSession->session_id} from {$adminName}");
        }

        return $this->response->setStatusCode(200)->setBody('OK');
    }
}

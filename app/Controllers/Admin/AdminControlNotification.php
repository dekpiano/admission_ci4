<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\NotificationModel;

class AdminControlNotification extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Get notifications for navbar (AJAX)
     */
    public function getNotifications()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $limit = $this->request->getGet('limit') ?? 10;
        $notifications = $this->notificationModel->getRecent((int)$limit);
        $unreadCount = $this->notificationModel->getUnreadCount();

        // Format notifications for display
        $formattedNotifications = [];
        foreach ($notifications as $notif) {
            $formattedNotifications[] = [
                'id' => $notif->notification_id,
                'type' => $notif->notification_type,
                'title' => $notif->notification_title,
                'message' => $notif->notification_message,
                'url' => site_url($notif->notification_url),
                'is_read' => (bool)$notif->notification_read,
                'time_ago' => $this->timeAgo($notif->notification_created_at),
                'created_at' => $notif->notification_created_at
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'notifications' => $formattedNotifications
        ]);
    }

    /**
     * Get unread count only (AJAX)
     */
    public function getUnreadCount()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $unreadCount = $this->notificationModel->getUnreadCount();

        return $this->response->setJSON([
            'status' => 'success',
            'count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markAsRead($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        if (!$id) {
            $id = $this->request->getPost('id');
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing notification ID']);
        }

        $result = $this->notificationModel->markAsRead((int)$id);

        return $this->response->setJSON([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Marked as read' : 'Failed to update'
        ]);
    }

    /**
     * Mark all notifications as read (AJAX)
     */
    public function markAllAsRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $result = $this->notificationModel->markAllAsRead();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete old notifications (can be called by cron)
     */
    public function cleanup()
    {
        $days = $this->request->getGet('days') ?? 30;
        $deleted = $this->notificationModel->deleteOld((int)$days);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Deleted {$deleted} old notifications"
        ]);
    }

    /**
     * Helper: Format time ago in Thai
     */
    private function timeAgo($datetime): string
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'เมื่อสักครู่';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' นาทีที่แล้ว';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' ชั่วโมงที่แล้ว';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' วันที่แล้ว';
        } else {
            return date('d/m/Y H:i', $timestamp);
        }
    }
}

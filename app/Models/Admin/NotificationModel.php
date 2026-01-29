<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'tb_notifications';
    protected $primaryKey = 'notification_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'notification_type',
        'notification_title',
        'notification_message',
        'notification_data',
        'notification_url',
        'notification_read',
        'notification_created_at',
        'notification_read_at'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'notification_created_at';

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(): int
    {
        return $this->where('notification_read', 0)->countAllResults();
    }

    /**
     * Get recent notifications
     */
    public function getRecent(int $limit = 10): array
    {
        return $this->orderBy('notification_created_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Get unread notifications
     */
    public function getUnread(int $limit = 10): array
    {
        return $this->where('notification_read', 0)
            ->orderBy('notification_created_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $id): bool
    {
        return $this->update($id, [
            'notification_read' => 1,
            'notification_read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): bool
    {
        return $this->set([
            'notification_read' => 1,
            'notification_read_at' => date('Y-m-d H:i:s')
        ])
            ->where('notification_read', 0)
            ->update();
    }

    /**
     * Create new applicant notification
     */
    public function createApplicantNotification(array $applicantData): bool
    {
        $prefix = $applicantData['recruit_prefix'] ?? '';
        $firstName = $applicantData['recruit_firstName'] ?? '';
        $lastName = $applicantData['recruit_lastName'] ?? '';
        $level = ($applicantData['recruit_regLevel'] ?? 1) == 1 ? 'ม.1' : 'ม.4';
        $course = $applicantData['recruit_tpyeRoom'] ?? '';
        $recruitId = $applicantData['recruit_id'] ?? '';

        $data = [
            'notification_type' => 'new_applicant',
            'notification_title' => 'ผู้สมัครใหม่ ' . $level,
            'notification_message' => $prefix . $firstName . ' ' . $lastName . ' สมัครเรียน ' . $course,
            'notification_data' => json_encode([
                'recruit_id' => $recruitId,
                'recruit_name' => $prefix . $firstName . ' ' . $lastName,
                'recruit_level' => $level,
                'recruit_course' => $course
            ]),
            'notification_url' => 'skjadmin/recruits/view/' . $recruitId,
            'notification_read' => 0,
            'notification_created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    /**
     * Delete old notifications (older than 30 days)
     */
    public function deleteOld(int $days = 30): int
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('notification_created_at <', $date)->delete();
    }
}

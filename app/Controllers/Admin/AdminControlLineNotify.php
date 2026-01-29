<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlLineNotify extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * หน้าหลักจัดการ LINE Admins
     */
    public function index()
    {
        // ตรวจสอบว่ามีตารางหรือยัง
        if (!$this->db->tableExists('tb_line_admins')) {
            $data['title'] = 'จัดการ LINE แจ้งเตือน Admin';
            $data['tableExists'] = false;
            return view('Admin/PageAdminLineNotify/AdminLineNotify', $data);
        }

        $data['title'] = 'จัดการ LINE แจ้งเตือน Admin';
        $data['tableExists'] = true;
        $data['admins'] = $this->db->table('tb_line_admins')
            ->orderBy('line_admin_id', 'DESC')
            ->get()
            ->getResult();

        // สร้าง Webhook URL
        $data['webhookUrl'] = site_url('api/line/webhook');

        return view('Admin/PageAdminLineNotify/AdminLineNotify', $data);
    }

    /**
     * ลบ Admin
     */
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        if (!$id) {
            $id = $this->request->getPost('id');
        }

        $this->db->table('tb_line_admins')->where('line_admin_id', $id)->delete();

        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบสำเร็จ']);
    }

    /**
     * เปิด/ปิด สถานะ
     */
    public function toggleStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $this->db->table('tb_line_admins')
            ->where('line_admin_id', $id)
            ->update([
                'line_status' => $status,
                'line_updated' => date('Y-m-d H:i:s')
            ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'อัพเดทสถานะสำเร็จ']);
    }

    /**
     * ทดสอบส่งแจ้งเตือน
     */
    public function test($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        if (!$id) {
            $id = $this->request->getPost('id');
        }

        // ดึง User ID
        $admin = $this->db->table('tb_line_admins')
            ->where('line_admin_id', $id)
            ->get()
            ->getRow();

        if (!$admin) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
        }

        // ส่งข้อความทดสอบ
        $testMsg = "\n🔔 ทดสอบการแจ้งเตือน\n\n";
        $testMsg .= "📍 จาก: SKJ Admission System\n";
        $testMsg .= "⏰ เวลา: " . date('d/m/Y H:i:s') . " น.\n";
        $testMsg .= "✅ การเชื่อมต่อสำเร็จ!";

        $result = $this->sendLinePush($admin->line_user_id, $testMsg);

        if ($result['status'] === 'success') {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ส่งข้อความทดสอบสำเร็จ!']);
        } else {
            $error = $result['message'] ?? 'ไม่ทราบสาเหตุ';
            return $this->response->setJSON(['status' => 'error', 'message' => 'ส่งไม่สำเร็จ: ' . $error]);
        }
    }

    /**
     * สร้างตาราง tb_line_admins
     */
    public function createTable()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        try {
            $sql = "CREATE TABLE IF NOT EXISTS `tb_line_admins` (
                `line_admin_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'รหัส (Primary Key)',
                `line_user_id` VARCHAR(50) NOT NULL COMMENT 'LINE User ID',
                `line_display_name` VARCHAR(100) NULL COMMENT 'ชื่อที่แสดงใน LINE',
                `line_picture_url` VARCHAR(500) NULL COMMENT 'URL รูปโปรไฟล์ LINE',
                `admin_rloes_id` INT(11) NULL COMMENT 'รหัส Admin ในระบบ',
                `line_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ: 0=ปิด, 1=เปิด',
                `line_events` TEXT NULL COMMENT 'Events ที่จะแจ้งเตือน (JSON)',
                `line_created` DATETIME NULL COMMENT 'วันเวลาที่ลงทะเบียน',
                `line_updated` DATETIME NULL COMMENT 'วันเวลาที่อัพเดท',
                PRIMARY KEY (`line_admin_id`),
                UNIQUE KEY `uk_line_user_id` (`line_user_id`),
                INDEX `idx_line_status` (`line_status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            $this->db->query($sql);

            return $this->response->setJSON(['status' => 'success', 'message' => 'สร้างตารางสำเร็จ!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * เพิ่ม Admin ด้วยตนเอง (กรณีรู้ User ID แล้ว)
     */
    public function addManual()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $userId = $this->request->getPost('line_user_id');
        $name = $this->request->getPost('line_display_name');

        if (empty($userId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอก LINE User ID']);
        }

        // ตรวจสอบซ้ำ
        $exists = $this->db->table('tb_line_admins')
            ->where('line_user_id', $userId)
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User ID นี้มีอยู่ในระบบแล้ว']);
        }

        // เพิ่มข้อมูล
        $this->db->table('tb_line_admins')->insert([
            'line_user_id' => $userId,
            'line_display_name' => $name ?: 'ไม่ระบุชื่อ',
            'line_status' => 1,
            'line_events' => json_encode(['new_applicant']),
            'line_created' => date('Y-m-d H:i:s'),
            'line_updated' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'เพิ่มสำเร็จ']);
    }
}

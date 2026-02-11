<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class AdminControlAnnouncement extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['upload', 'url']);
    }

    /**
     * Create announcements table if not exists
     */
    private function ensureTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `tb_announcements` (
            `announce_id` INT(11) NOT NULL AUTO_INCREMENT,
            `announce_title` VARCHAR(255) NOT NULL,
            `announce_type` ENUM('exam', 'study', 'exam_normal', 'exam_sports', 'passed_normal', 'passed_sports') NOT NULL DEFAULT 'exam_normal',
            `announce_file` VARCHAR(500) NOT NULL,
            `announce_file_type` ENUM('pdf','image') NOT NULL DEFAULT 'pdf',
            `announce_year` VARCHAR(10) NOT NULL,
            `announce_round` VARCHAR(5) DEFAULT '1',
            `announce_reg_level` VARCHAR(5) DEFAULT NULL COMMENT 'ระดับชั้น เช่น 1, 4',
            `announce_status` ENUM('on','off') NOT NULL DEFAULT 'on',
            `announce_description` TEXT DEFAULT NULL,
            `announce_created_by` INT(11) DEFAULT NULL,
            `announce_created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `announce_updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`announce_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

        // Migration: Expand enum and move old values
        $this->db->query("ALTER TABLE tb_announcements MODIFY COLUMN announce_type ENUM('exam', 'study', 'exam_normal', 'exam_sports', 'passed_normal', 'passed_sports') NOT NULL DEFAULT 'exam_normal'");
        
        // Add link to file_type
        $this->db->query("ALTER TABLE tb_announcements MODIFY COLUMN announce_file_type ENUM('pdf','image','link') NOT NULL DEFAULT 'pdf'");

        // Support alphanumeric pers_id
        $this->db->query("ALTER TABLE tb_announcements MODIFY COLUMN announce_created_by VARCHAR(50) DEFAULT NULL");
        
        $this->db->query("UPDATE tb_announcements SET announce_type = 'exam_normal' WHERE announce_type = 'exam'");
        $this->db->query("UPDATE tb_announcements SET announce_type = 'passed_normal' WHERE announce_type = 'study'");
    }

    /**
     * Main page - List all announcements
     */
    public function index()
    {
        $this->ensureTable();

        // Get distinct years
        $years = $this->db->query("SELECT DISTINCT announce_year FROM tb_announcements ORDER BY announce_year DESC")->getResultArray();
        $yearList = array_column($years, 'announce_year');

        if (empty($yearList)) {
            $yearList = [date('Y') + 543]; // Buddhist year
        }

        $selectedYear = $this->request->getVar('year') ?? $yearList[0];

        $announcements = $this->db->table('tb_announcements')
            ->where('announce_year', $selectedYear)
            ->orderBy('announce_created_at', 'DESC')
            ->get()->getResultArray();

        $data['announcements'] = $announcements;
        $data['years'] = $yearList;
        $data['selected_year'] = $selectedYear;
        $data['title'] = 'จัดการประกาศผล';

        // Fetch System Status for linking conditions
        $data['systemSettings'] = $this->db->table('tb_onoffsys')->where('onoff_id', 1)->get()->getRow();

        // Fetch Schedule for the year to show "Conditions"
        $data['schedules'] = $this->db->table('tb_admission_schedule')
            ->where('schedule_year', $selectedYear)
            ->get()->getResultArray();

        return view('Admin/PageAdminAnnouncement/PageAdminAnnouncementIndex', $data);
    }

    /**
     * Store new announcement (AJAX)
     */
    public function store()
    {
        try {
            $this->ensureTable();

            $isLink = $this->request->getPost('is_link') === 'true';
            $fileName = '';
            $fileType = 'pdf';

            log_message('debug', 'Announce Store: Start');

            $link = $this->request->getPost('announce_link');
            $file = $this->request->getFile('announce_file');
            $hasFile = ($file && $file->isValid());

            log_message('debug', "Announce Store: isLink=" . ($isLink ? 'true' : 'false') . ", hasFile=" . ($hasFile ? 'true' : 'false'));

            if ($isLink || (!$hasFile && !empty($link))) {
                // Link mode (explicit or fallback)
                if (empty($link)) {
                    return $this->response->setJSON(['success' => false, 'message' => '[DEBUG:STORE] กรุณาระบุลิงก์ประกาศ']);
                }
                $fileName = $link;
                $fileType = 'link';
            } else {
                // File mode
                if (!$hasFile) {
                    return $this->response->setJSON(['success' => false, 'message' => '[DEBUG:STORE] กรุณาเลือกไฟล์หรือระบุลิงก์ประกาศ']);
                }

                // Determine file type
                $mime = $file->getMimeType();
                $fileType = (strpos($mime, 'image') !== false) ? 'image' : 'pdf';

                // Upload file
                $remoteUpload = new \App\Libraries\RemoteUpload();
                $subPath = 'admission/announcements';
                $result = $remoteUpload->upload($file, $subPath);

                if (!$result || $result['status'] !== 'success') {
                    return $this->response->setJSON(['success' => false, 'message' => 'อัปโหลดไฟล์ไม่สำเร็จ: ' . ($result['message'] ?? 'ไม่ทราบสาเหตุ')]);
                }
                $fileName = $result['filename'];
            }

            $data = [
                'announce_title' => $this->request->getPost('announce_title'),
                'announce_type' => $this->request->getPost('announce_type'),
                'announce_file' => $fileName,
                'announce_file_type' => $fileType,
                'announce_year' => $this->request->getPost('announce_year'),
                'announce_round' => $this->request->getPost('announce_round') ?: '1',
                'announce_reg_level' => $this->request->getPost('announce_reg_level') ?: null,
                'announce_status' => 'on',
                'announce_description' => $this->request->getPost('announce_description'),
                'announce_created_by' => \Config\Services::session()->get('pers_id') ?: null,
            ];

            $this->db->table('tb_announcements')->insert($data);
            log_message('debug', 'Announce Store: Inserted ID=' . $this->db->insertID());

            return $this->response->setJSON(['success' => true, 'message' => 'เพิ่มประกาศสำเร็จ']);
        } catch (\Throwable $e) {
            log_message('error', 'Announce Store Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดภายในระบบ: ' . $e->getMessage()]);
        }
    }

    /**
     * Update announcement (AJAX)
     */
    public function update($id = null)
    {
        try {
            $this->ensureTable();

        $existing = $this->db->table('tb_announcements')->where('announce_id', $id)->get()->getRow();
        if (!$existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูลประกาศ']);
        }

        $data = [
            'announce_title' => $this->request->getPost('announce_title'),
            'announce_type' => $this->request->getPost('announce_type'),
            'announce_year' => $this->request->getPost('announce_year'),
            'announce_round' => $this->request->getPost('announce_round') ?: '1',
            'announce_reg_level' => $this->request->getPost('announce_reg_level') ?: null,
            'announce_description' => $this->request->getPost('announce_description'),
        ];

        $isLink = $this->request->getPost('is_link') === 'true';

        if ($isLink) {
            $link = $this->request->getPost('announce_link');
            if (empty($link)) {
                return $this->response->setJSON(['success' => false, 'message' => '[DEBUG:UPDATE] กรุณาระบุลิงก์ประกาศ']);
            }
            $data['announce_file'] = $link;
            $data['announce_file_type'] = 'link';
        } else {
            // Check if new file uploaded
            $file = $this->request->getFile('announce_file');
            if ($file && $file->isValid()) {
                $mime = $file->getMimeType();
                $fileType = 'pdf';
                if (strpos($mime, 'image') !== false) {
                    $fileType = 'image';
                }

                $remoteUpload = new \App\Libraries\RemoteUpload();
                $subPath = 'admission/announcements';
                $result = $remoteUpload->upload($file, $subPath);

                if ($result && $result['status'] === 'success') {
                    // Delete old file if it wasn't a link
                    if (!empty($existing->announce_file) && $existing->announce_file_type !== 'link') {
                        $remoteUpload->delete($existing->announce_file, $subPath);
                    }
                    $data['announce_file'] = $result['filename'];
                    $data['announce_file_type'] = $fileType;
                }
            } else {
                // Fallback: If no file uploaded, check if they provided a link instead
                $link = $this->request->getPost('announce_link');
                if (!empty($link)) {
                    $data['announce_file'] = $link;
                    $data['announce_file_type'] = 'link';
                }
            }
        }

        $this->db->table('tb_announcements')->where('announce_id', $id)->update($data);

        return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตประกาศสำเร็จ']);
        } catch (\Throwable $e) {
            log_message('error', 'Announce Update Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดภายในระบบ: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle announcement status (AJAX)
     */
    public function toggleStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if (!$id || !in_array($status, ['on', 'off'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
        }

        $this->db->table('tb_announcements')->where('announce_id', $id)->update(['announce_status' => $status]);

        return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
    }

    /**
     * Delete announcement (AJAX)
     */
    public function delete($id = null)
    {
        $this->ensureTable();

        $existing = $this->db->table('tb_announcements')->where('announce_id', $id)->get()->getRow();
        if (!$existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูลประกาศ']);
        }

        // Delete file if it's not a link
        if (!empty($existing->announce_file) && $existing->announce_file_type !== 'link') {
            $remoteUpload = new \App\Libraries\RemoteUpload();
            $subPath = 'admission/announcements';
            $remoteUpload->delete($existing->announce_file, $subPath);
        }

        $this->db->table('tb_announcements')->where('announce_id', $id)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'ลบประกาศสำเร็จ']);
    }

    /**
     * Get file URL for announcement
     */
    public static function getFileUrl($filename)
    {
        if (empty($filename)) return '';

        // If it's already a full URL (http or https)
        if (strpos($filename, 'http://') === 0 || strpos($filename, 'https://') === 0) {
            return $filename;
        }

        $subPath = "announcements/{$filename}";
        $fullPath = "admission/" . $subPath;

        // Check local first
        $localPath = FCPATH . 'uploads/' . $fullPath;
        if (file_exists($localPath)) {
            return base_url('uploads/' . $fullPath);
        }

        // Use remote server
        $baseUrl = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
        return rtrim($baseUrl, '/') . '/' . $subPath;
    }
}

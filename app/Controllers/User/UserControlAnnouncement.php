<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

helper('upload');


use App\Models\AdmissionModel;

class UserControlAnnouncement extends BaseController
{
    protected $db;
    protected $admissionModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * Display announcements for public viewing
     */
    public function index()
    {
        // Ensure table exists
        $this->db->query("CREATE TABLE IF NOT EXISTS `tb_announcements` (
            `announce_id` INT(11) NOT NULL AUTO_INCREMENT,
            `announce_title` VARCHAR(255) NOT NULL,
            `announce_type` ENUM('exam','study') NOT NULL DEFAULT 'exam',
            `announce_file` VARCHAR(500) NOT NULL,
            `announce_file_type` ENUM('pdf','image') NOT NULL DEFAULT 'pdf',
            `announce_year` VARCHAR(10) NOT NULL,
            `announce_round` VARCHAR(5) DEFAULT '1',
            `announce_reg_level` VARCHAR(5) DEFAULT NULL,
            `announce_status` ENUM('on','off') NOT NULL DEFAULT 'on',
            `announce_description` TEXT DEFAULT NULL,
            `announce_created_by` INT(11) DEFAULT NULL,
            `announce_created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `announce_updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`announce_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

        // Get distinct years that have active announcements
        $years = $this->db->query("SELECT DISTINCT announce_year FROM tb_announcements WHERE announce_status = 'on' ORDER BY announce_year DESC")->getResultArray();
        $yearList = array_column($years, 'announce_year');

        if (empty($yearList)) {
            $yearList = [date('Y') + 543];
        }

        $selectedYear = $this->request->getVar('year') ?? $yearList[0];
        $selectedType = $this->request->getVar('type') ?? '';

        // Query builder
        $builder = $this->db->table('tb_announcements')
            ->where('announce_year', $selectedYear)
            ->where('announce_status', 'on')
            ->orderBy('announce_created_at', 'DESC');

        if (!empty($selectedType)) {
            $builder->where('announce_type', $selectedType);
        }

        $announcements = $builder->get()->getResultArray();

        // Count by type
        $examCount = count(array_filter($announcements, fn($a) => $a['announce_type'] === 'exam'));
        $studyCount = count(array_filter($announcements, fn($a) => $a['announce_type'] === 'study'));

        // Get system data for layout consistency
        $data['systemStatus'] = $this->admissionModel->getSystemStatus();
        $data['quotas'] = $this->admissionModel->getAllQuotas();
        $data['checkYear'] = $this->admissionModel->getOpenYear();

        $data['announcements'] = $announcements;
        $data['years'] = $yearList;
        $data['selected_year'] = $selectedYear;
        $data['selected_type'] = $selectedType;
        $data['examCount'] = $examCount;
        $data['studyCount'] = $studyCount;
        $data['title'] = 'ประกาศผลการคัดเลือก';

        return view('User/PageUserAnnouncement/PageUserAnnouncementIndex', $data);
    }
}

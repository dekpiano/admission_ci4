<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlDashboard extends BaseController
{
    protected $db;
    protected $session;
    protected $admissionModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->admissionModel = new \App\Models\AdmissionModel();
    }

    public function index($year = null)
    {
        $data['title'] = 'Dashboard';
        
        // Get all available years for the dropdown
        $data['years'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_year')
            ->distinct()
            ->orderBy('recruit_year', 'DESC')
            ->get()
            ->getResult();

        // Determine which year to show
        if ($year === null) {
            $openYear = $this->admissionModel->getOpenYear();
            $year = $openYear->openyear_year;
        }
        
        $data['selected_year'] = $year;

        // Fetch Statistics
        $data['stats'] = $this->db->table('tb_recruitstudent')
            ->select('COUNT(recruit_year) AS StuALL,
                COUNT(CASE WHEN recruit_status = "ผ่านการตรวจสอบ" THEN 1 END) AS Pass,
                COUNT(CASE WHEN recruit_status != "ผ่านการตรวจสอบ" THEN 1 END) AS NoPass,
                COUNT(CASE WHEN recruit_regLevel = "1" THEN 1 END) AS NumAllM1,
                COUNT(CASE WHEN recruit_regLevel = "4" THEN 1 END) AS NumAllM4')
            ->where('recruit_year', $year)
            ->get()->getRow();

        return view('Admin/PageAdminDashboard/PageAdminDashboardIndex', $data);
    }
}

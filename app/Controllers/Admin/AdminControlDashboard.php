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
        helper(['url', 'upload']);
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

        // Fetch Statistics (Summary)
        $data['stats'] = $this->db->table('tb_recruitstudent')
            ->select('COUNT(recruit_year) AS StuALL,
                COUNT(CASE WHEN recruit_status = "ผ่านการตรวจสอบ" THEN 1 END) AS Pass,
                COUNT(CASE WHEN recruit_status = "รอการตรวจสอบ" OR recruit_status = "" OR recruit_status IS NULL THEN 1 END) AS Pending,
                COUNT(CASE WHEN recruit_status = "ไม่ผ่านการตรวจสอบ (รอแก้ไข)" THEN 1 END) AS Edit,
                COUNT(CASE WHEN recruit_regLevel = "1" THEN 1 END) AS NumAllM1,
                COUNT(CASE WHEN recruit_regLevel = "4" THEN 1 END) AS NumAllM4')
            ->where('recruit_year', $year)
            ->get()->getRow();

        // Quota Statistics
        $data['quota_stats'] = $this->db->table('tb_recruitstudent r')
            ->select('q.quota_explain, q.quota_level, COUNT(r.recruit_id) as count')
            ->join('tb_quota q', 'r.recruit_category = q.quota_id')
            ->where('r.recruit_year', $year)
            ->groupBy('q.quota_id')
            ->orderBy('count', 'DESC')
            ->get()->getResult();

        // Recent 5 Registrations
        $data['recent_registrations'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_id, recruit_prefix, recruit_firstName, recruit_lastName, recruit_regLevel, recruit_status, recruit_date')
            ->where('recruit_year', $year)
            ->orderBy('recruit_date', 'DESC')
            ->limit(5)
            ->get()->getResult();

        // Daily Trend (Last 14 days)
        $data['daily_trend'] = $this->db->table('tb_recruitstudent')
            ->select('DATE(recruit_date) as date, COUNT(*) as count')
            ->where('recruit_year', $year)
            ->where('recruit_date >=', date('Y-m-d', strtotime('-14 days')))
            ->groupBy('DATE(recruit_date)')
            ->orderBy('date', 'ASC')
            ->get()->getResult();

        return view('Admin/PageAdminDashboard/PageAdminDashboardIndex', $data);
    }
}

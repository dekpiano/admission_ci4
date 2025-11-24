<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdmissionController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['url', 'form']);
    }

    public function dashboard()
    {
        $data['title'] = "แดชบอร์ดระบบรับสมัครนักเรียน";
        $openyear_q = $this->db->table('tb_openyear')->get()->getRow();
        $current_year = $openyear_q ? $openyear_q->openyear_year : date('Y') + 543;

        // Fetching statistics
        $builder = $this->db->table('tb_recruitstudent')->where('recruit_year', $current_year);

        $data['m1_total'] = (clone $builder)->where('recruit_regLevel', '1')->countAllResults();
        $data['m4_total'] = (clone $builder)->where('recruit_regLevel', '4')->countAllResults();
        $data['verified_total'] = (clone $builder)->where('recruit_status', 'ผ่านการตรวจสอบ')->countAllResults();
        $data['pending_total'] = (clone $builder)->where('recruit_status', 'รอการตรวจสอบ')->countAllResults();
        
        return view('admin/AdmissionDashboard', $data);
    }
}

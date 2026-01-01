<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;
use App\Libraries\Datethai;

class AdminControlStatistic extends BaseController
{
    protected $db;
    protected $session;
    protected $admissionModel;
    protected $datethai;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->admissionModel = new AdmissionModel();
        $this->datethai = new Datethai();
    }

    private function checkAuth()
    {
        if (!$this->session->has('login_id') && !$this->session->has('pers_id')) {
            return redirect()->to('loginAdmin');
        }
        return null;
    }

    public function index($year = null)
    {
        if ($redir = $this->checkAuth())
            return $redir;

        $checkYear = $this->admissionModel->getOpenYear();
        if ($year === null) {
            $year = $checkYear->openyear_year;
        }

        $data['title'] = "สรุปสถิติการรับสมัคร ปีการศึกษา " . $year;
        $data['checkYear'] = $checkYear;
        $data['selectedYear'] = $year;
        $data['years'] = $this->admissionModel->getRecruitmentYears();
        $data['systemStatus'] = $this->admissionModel->getSystemStatus();
        $data['stats'] = $this->admissionModel->getAdmissionStats($year);
        $data['dailyStats'] = $this->admissionModel->getDailyStats($year);
        $data['statusByLevel'] = $this->admissionModel->getStatsByLevelAndStatus($year);
        $data['datethai'] = $this->datethai;

        return view('Admin/PageAdminStatistic/PageAdminStatisticIndex', $data);
    }
}

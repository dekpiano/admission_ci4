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
        
        $yearParam = $this->request->getGet('year');
        if ($yearParam) {
            $year = $yearParam;
        } elseif ($year === null) {
            $year = $checkYear->openyear_year;
        }

        $round = $this->request->getGet('round');
        $date = $this->request->getGet('date');

        $filters = [
            'recruit_round' => $round,
            'recruit_date' => $date
        ];

        $data['title'] = "สรุปสถิติการรับสมัคร ปีการศึกษา " . $year;
        $data['checkYear'] = $checkYear;
        $data['selectedYear'] = $year;
        $data['selectedRound'] = $round;
        $data['selectedDate'] = $date;
        $data['years'] = $this->admissionModel->getRecruitmentYears();
        
        // Fetch available rounds for filter
        $data['rounds'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_round')
            ->where('recruit_year', $year)
            ->where('recruit_round !=', null)
            ->where('recruit_round !=', '')
            ->groupBy('recruit_round')
            ->orderBy('recruit_round', 'ASC')
            ->get()->getResult();

        // Fetch available dates for filter
        $data['dates'] = $this->db->table('tb_recruitstudent')
            ->select('DATE(recruit_date) as recruit_date')
            ->where('recruit_year', $year)
            ->groupBy('DATE(recruit_date)')
            ->orderBy('DATE(recruit_date)', 'DESC')
            ->get()->getResult();

        $data['systemStatus'] = $this->admissionModel->getSystemStatus();
        $data['stats'] = $this->admissionModel->getAdmissionStats($year, $filters);
        $data['dailyStats'] = $this->admissionModel->getDailyStats($year, $filters);
        $data['dailyExcellenceStats'] = $this->admissionModel->getDailyExcellenceStats($year, $filters);
        $data['allCourses'] = $this->admissionModel->getAllCourses();
        $data['statusByLevel'] = $this->admissionModel->getStatsByLevelAndStatus($year, $filters);
        $data['datethai'] = $this->datethai;

        return view('Admin/PageAdminStatistic/PageAdminStatisticIndex', $data);
    }
}

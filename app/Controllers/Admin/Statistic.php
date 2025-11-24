<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;

class Statistic extends BaseController
{
    protected $db;
    protected $session;
    protected $title = "การรับสมัคร";
    protected $admissionModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); // Keep for now for complex queries not yet in model
        $this->session = \Config\Services::session();
        $this->admissionModel = new AdmissionModel();
        helper(['url']);
    }

    private function checkAuth()
    {
        if (!$this->session->has('login_id')) {
            return redirect()->to('loginAdmin');
        }
        return null;
    }

    public function statistic_student($year)
    {
        if ($redir = $this->checkAuth()) return $redir;

        $data['switch'] = $this->admissionModel->getSystemStatus();
        $data['title'] = $this->title;

        $data['recruit'] = $this->db->table('tb_recruitstudent')
            ->select('COUNT(recruit_year) AS StuALL,
		COUNT(CASE WHEN recruit_status = "ผ่านการตรวจสอบ" THEN 1 END) AS Pass,
		COUNT(CASE WHEN recruit_status != "ผ่านการตรวจสอบ" THEN 1 END) AS NoPass,
		COUNT(CASE WHEN recruit_regLevel = "1" THEN 1 END) AS NumAllM1,
		COUNT(CASE WHEN recruit_regLevel = "4" THEN 1 END) AS NumAllM4,
		COUNT(
			CASE 
			WHEN recruit_regLevel = "2" ||  
			recruit_regLevel = "3" ||
			recruit_regLevel = "5" ||
			recruit_regLevel = "6" 
			THEN 1 END
			) AS NumAllMother')
            ->where('recruit_year', $year)
            ->orderBy('recruit_id', 'DESC')
            ->get()->getResult();

        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['year'] = $this->admissionModel->getRecruitmentYears();

        $data['ChartNormal'] = $this->ChartNormal($year);

        return view('admin/admin_admission_Statistic.php', $data);
    }

    public function ChartNormal($year)
    {
        $ChartNormal = $this->db->table('tb_recruitstudent')
            ->select("recruit_date,
			SUM(CASE WHEN recruit_major = 'วิทย์ - คณิต' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Sci,
			SUM(CASE WHEN recruit_major = 'วิทย์ - เทคโน' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Teac,
			SUM(CASE WHEN recruit_major = 'ภาษา' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Lang,
			SUM(CASE WHEN recruit_major = 'ดนตรี' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Music,
			SUM(CASE WHEN recruit_major = 'ศิลปะ' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Art,
			SUM(CASE WHEN recruit_major = 'นาฏศิลป์' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Dance,
			SUM(CASE WHEN recruit_major = 'การงาน' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Career,
			 SUM(CASE 
				WHEN recruit_major IN ('วิทย์ - คณิต', 'วิทย์ - เทคโน', 'ภาษา', 'ดนตรี', 'ศิลปะ', 'นาฏศิลป์', 'การงาน') 
				AND recruit_regLevel IN (1) 
				THEN 1 ELSE 0 
			END) AS Total_M1,
			SUM(CASE WHEN recruit_major = 'วิทย์ - คณิต' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Sci,
			SUM(CASE WHEN recruit_major = 'วิทย์ - เทคโน' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Teac,
			SUM(CASE WHEN recruit_major = 'ภาษา' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Lang,
			SUM(CASE WHEN recruit_major = 'ดนตรี' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Music,
			SUM(CASE WHEN recruit_major = 'ศิลปะ' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Art,
			SUM(CASE WHEN recruit_major = 'นาฏศิลป์' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Dance,
			SUM(CASE WHEN recruit_major = 'การงาน' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Career,
			SUM(CASE 
				WHEN recruit_major IN ('วิทย์ - คณิต', 'วิทย์ - เทคโน', 'ภาษา', 'ดนตรี', 'ศิลปะ', 'นาฏศิลป์', 'การงาน') 
				AND recruit_regLevel IN (4) 
				THEN 1 ELSE 0 
			END) AS Total_M4")
            ->where('recruit_year', $year)
            ->where('recruit_category', 'normal')
            ->where("recruit_date BETWEEN '2025-03-25' AND '2025-03-31'", NULL, FALSE)
            ->groupBy('recruit_date')
            ->get()->getResult();

        return $ChartNormal;
    }

    public function AllStatistic($year)
    {
        $data = $this->report_student($year);

        $data['year'] = $this->admissionModel->getRecruitmentYears();
        $data['checkYear'] = $this->admissionModel->getOpenYear();

        $data['switch'] = $this->admissionModel->getSystemStatus();
        $data['quota'] = $this->admissionModel->getAllQuotas();
        $data['datethai'] = $this->datethai;

        $data['title'] = "สถิติการรับสมัครนักเรียน" . $data['checkYear']->openyear_year;
        $data['description'] = "ดูสถิติแบบเรียลไทม์";
        $data['banner'] = base_url() . "asset/img/Statistics.png";
        $data['url'] = "Statistic";

        return view('AdminssionStatistic', $data);
    }
Controllers\Admin;

use App\Controllers\BaseController;

class Statistic extends BaseController
{
    protected $db;
    protected $session;
    protected $title = "การรับสมัคร";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['url']);
    }

    private function checkAuth()
    {
        if (!$this->session->has('login_id')) {
            return redirect()->to('loginAdmin');
        }
        return null;
    }

    public function statistic_student($year)
    {
        if ($redir = $this->checkAuth()) return $redir;

        $data['switch'] = $this->db->table("tb_onoffsys")->get()->getResult();
        $data['title'] = $this->title;

        $data['recruit'] = $this->db->table('tb_recruitstudent')
            ->select('COUNT(recruit_year) AS StuALL,
		COUNT(CASE WHEN recruit_status = "ผ่านการตรวจสอบ" THEN 1 END) AS Pass,
		COUNT(CASE WHEN recruit_status != "ผ่านการตรวจสอบ" THEN 1 END) AS NoPass,
		COUNT(CASE WHEN recruit_regLevel = "1" THEN 1 END) AS NumAllM1,
		COUNT(CASE WHEN recruit_regLevel = "4" THEN 1 END) AS NumAllM4,
		COUNT(
			CASE 
			WHEN recruit_regLevel = "2" ||  
			recruit_regLevel = "3" ||
			recruit_regLevel = "5" ||
			recruit_regLevel = "6" 
			THEN 1 END
			) AS NumAllMother')
            ->where('recruit_year', $year)
            ->orderBy('recruit_id', 'DESC')
            ->get()->getResult();

        $data['checkYear'] = $this->db->table('tb_openyear')->get()->getResult();
        $data['year'] = $this->db->table('tb_recruitstudent')->select('recruit_year')->groupBy('recruit_year')->orderBy('recruit_year', 'DESC')->get()->getResult();

        $data['ChartNormal'] = $this->ChartNormal($year);

        return view('admin/admin_admission_Statistic.php', $data);
    }

    public function ChartNormal($year)
    {
        $ChartNormal = $this->db->table('tb_recruitstudent')
            ->select("recruit_date,
			SUM(CASE WHEN recruit_major = 'วิทย์ - คณิต' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Sci,
			SUM(CASE WHEN recruit_major = 'วิทย์ - เทคโน' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Teac,
			SUM(CASE WHEN recruit_major = 'ภาษา' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Lang,
			SUM(CASE WHEN recruit_major = 'ดนตรี' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Music,
			SUM(CASE WHEN recruit_major = 'ศิลปะ' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Art,
			SUM(CASE WHEN recruit_major = 'นาฏศิลป์' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Dance,
			SUM(CASE WHEN recruit_major = 'การงาน' AND recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1_Career,
			 SUM(CASE 
				WHEN recruit_major IN ('วิทย์ - คณิต', 'วิทย์ - เทคโน', 'ภาษา', 'ดนตรี', 'ศิลปะ', 'นาฏศิลป์', 'การงาน') 
				AND recruit_regLevel IN (1) 
				THEN 1 ELSE 0 
			END) AS Total_M1,
			SUM(CASE WHEN recruit_major = 'วิทย์ - คณิต' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Sci,
			SUM(CASE WHEN recruit_major = 'วิทย์ - เทคโน' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Teac,
			SUM(CASE WHEN recruit_major = 'ภาษา' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Lang,
			SUM(CASE WHEN recruit_major = 'ดนตรี' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Music,
			SUM(CASE WHEN recruit_major = 'ศิลปะ' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Art,
			SUM(CASE WHEN recruit_major = 'นาฏศิลป์' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Dance,
			SUM(CASE WHEN recruit_major = 'การงาน' AND recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4_Career,
			SUM(CASE 
				WHEN recruit_major IN ('วิทย์ - คณิต', 'วิทย์ - เทคโน', 'ภาษา', 'ดนตรี', 'ศิลปะ', 'นาฏศิลป์', 'การงาน') 
				AND recruit_regLevel IN (4) 
				THEN 1 ELSE 0 
			END) AS Total_M4")
            ->where('recruit_year', $year)
            ->where('recruit_category', 'normal')
            ->where("recruit_date BETWEEN '2025-03-25' AND '2025-03-31'", NULL, FALSE)
            ->groupBy('recruit_date')
            ->get()->getResult();

        return $ChartNormal;
    }

    public function ChartStudentsRecruitM1()
    {
        $ChartStuAll = [];
        $CheckStuAll = $this->db->table('tb_recruitstudent')
            ->select('SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศด้านกีฬา (Sport Program)" THEN 1 ELSE 0 END) AS SP  ,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านวิชาการ (Science Match and Technology Program)" THEN 1 ELSE 0 END) AS SMT,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านภาษา (Chinese English Program)" THEN 1 ELSE 0 END) AS CEP,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านดนตรี ศิลปะ การแสดง (Performing Arts Program)" THEN 1 ELSE 0 END) AS PAP,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศด้านการงานอาชีพ (Career Program)" THEN 1 ELSE 0 END) AS CP')
            ->where('recruit_regLevel', 1)
            ->where('recruit_year', $this->request->getPost('Year'))
            ->get()->getResult();

        foreach ($CheckStuAll as $value) {
            $ChartStuAll[] = $value->SMT;
            $ChartStuAll[] = $value->CEP;
            $ChartStuAll[] = $value->CP;
            $ChartStuAll[] = $value->PAP;
            $ChartStuAll[] = $value->SP;
        }

        echo json_encode($ChartStuAll);
    }

    public function ChartStudentsRecruitM4()
    {
        $ChartStuAll = [];
        $CheckStuAll = $this->db->table('tb_recruitstudent')
            ->select('SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศด้านกีฬา (Sport Program)" THEN 1 ELSE 0 END) AS SP ,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านวิชาการ (Science Match and Technology Program)" THEN 1 ELSE 0 END) AS SMT,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านภาษา (Chinese English Program)" THEN 1 ELSE 0 END) AS CEP,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านดนตรี ศิลปะ การแสดง (Performing Arts Program)" THEN 1 ELSE 0 END) AS PAP,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศด้านการงานอาชีพ (Career Program)" THEN 1 ELSE 0 END) AS CP')
            ->where('recruit_regLevel', 4)
            ->where('recruit_year', $this->request->getPost('Year'))
            ->get()->getResult();

        foreach ($CheckStuAll as $value) {
            $ChartStuAll[] = $value->SMT;
            $ChartStuAll[] = $value->CEP;
            $ChartStuAll[] = $value->CP;
            $ChartStuAll[] = $value->PAP;
            $ChartStuAll[] = $value->SP;
        }

        echo json_encode($ChartStuAll);
    }

    public function ChartStudentsRecruitMOther()
    {
        $ChartStuAll = [];
        $year = $this->request->getPost('Year');
        $where = "(`recruit_regLevel` = 2 AND `recruit_year` = $year)
		or (`recruit_regLevel` = 3 AND `recruit_year` = $year)
		or (`recruit_regLevel` = 5 AND `recruit_year` = $year)
		or (`recruit_regLevel` = 6 AND `recruit_year` = $year)";
        
        $CheckStuAll = $this->db->table('tb_recruitstudent')
            ->select('SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศด้านกีฬา (Sport Program)" THEN 1 ELSE 0 END) AS SP  ,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านวิชาการ (Science Match and Technology Program)" THEN 1 ELSE 0 END) AS SMT,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านภาษา (Chinese English Program)" THEN 1 ELSE 0 END) AS CEP,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศทางด้านดนตรี ศิลปะ การแสดง (Performing Arts Program)" THEN 1 ELSE 0 END) AS PAP,
		   SUM(CASE WHEN recruit_tpyeRoom = "ห้องเรียนความเป็นเลิศด้านการงานอาชีพ (Career Program)" THEN 1 ELSE 0 END) AS CP')
            ->where($where, NULL, FALSE)
            ->get()->getResult();

        foreach ($CheckStuAll as $value) {
            $ChartStuAll[] = $value->SMT;
            $ChartStuAll[] = $value->CEP;
            $ChartStuAll[] = $value->CP;
            $ChartStuAll[] = $value->PAP;
            $ChartStuAll[] = $value->SP;
        }

        echo json_encode($ChartStuAll);
    }
}

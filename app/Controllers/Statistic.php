<?php

namespace App\Controllers;

use App\Libraries\Datethai;

class Statistic extends BaseController
{
    protected $db;
    protected $datethai;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->datethai = new Datethai();
        helper(['url']);
    }

    public function report_student($year)
    {
        $type_quota = $this->db->table('tb_quota')->get()->getResult();

        $data['StatisticAll'] = $this->db->table('tb_recruitstudent')
            ->select('COUNT(tb_recruitstudent.recruit_category) AS num,
					SUM(CASE WHEN recruit_prefix = "เด็กหญิง" or recruit_prefix = "นางสาว" THEN 1 END) AS Girl,
					SUM(CASE WHEN recruit_prefix = "เด็กชาย" or recruit_prefix = "นาย" THEN 1 END) AS Man,
					tb_quota.quota_explain')
            ->join('tb_quota', 'tb_quota.quota_key = tb_recruitstudent.recruit_category')
            ->where('recruit_year', $year)
            ->groupBy('tb_recruitstudent.recruit_category')
            ->orderBy('recruit_date', 'ASC')
            ->get()->getResult();

        $data['StatisticNormal'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_date,
					SUM(CASE WHEN recruit_prefix = "เด็กหญิง"  AND recruit_regLevel = 1 THEN 1 END) AS Girl1,
					SUM(CASE WHEN recruit_prefix = "เด็กชาย"  AND recruit_regLevel = 1 THEN 1 END) AS Man1,					
					SUM(CASE WHEN recruit_prefix = "นางสาว" or recruit_prefix = "เด็กหญิง" AND recruit_regLevel = 4 THEN 1 END) AS Girl4,
					SUM(CASE WHEN recruit_prefix = "นาย" or recruit_prefix = "เด็กชาย" AND recruit_regLevel = 4 THEN 1 END) AS Man4,
					tb_quota.quota_explain')
            ->join('tb_quota', 'tb_quota.quota_key = tb_recruitstudent.recruit_category')
            ->where('recruit_year', $year)
            ->where('recruit_category', "normal")
            ->where('recruit_date BETWEEN "2024-03-09" AND "2024-03-15"')
            ->groupBy('tb_recruitstudent.recruit_date')
            ->orderBy('recruit_date', 'ASC')
            ->get()->getResult();

        $data['RegisterAll'] = $this->db->table('tb_recruitstudent')
            ->select("COUNT(recruit_year) AS RegAll,
			SUM(CASE WHEN recruit_status = 'ผ่านการตรวจสอบ' THEN 1 END) AS Pass,
			SUM(CASE WHEN recruit_status != 'ผ่านการตรวจสอบ' THEN 1 END) AS NoPass")
            ->where('recruit_year', $year)
            ->get()->getResult();

        $data['StatisticTableNormal'] = $this->db->table('tb_recruitstudent')
            ->select('tb_recruitstudent.recruit_date,
			SUM(CASE WHEN recruit_prefix = "เด็กหญิง"  and recruit_regLevel = 1 THEN 1 ELSE 0 END) AS F1,
			SUM(CASE WHEN (recruit_prefix = "เด็กหญิง" and recruit_regLevel = 4) or (recruit_prefix = "นางสาว"  and recruit_regLevel = 4) THEN 1 ELSE 0 END) AS F4,
			SUM(CASE WHEN recruit_prefix = "เด็กชาย"  and recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1,
			SUM(CASE WHEN (recruit_prefix = "เด็กชาย" and recruit_regLevel = 4) or (recruit_prefix = "นาย" and recruit_regLevel = 4) THEN 1 ELSE 0 END) AS M4')
            ->where('recruit_year', $year)
            ->where('recruit_category', 'normal')
            ->groupBy('recruit_date')
            ->get()->getResult();

        $data['StatisticTableQuotaM14'] = $this->db->table('tb_recruitstudent')
            ->select('tb_recruitstudent.recruit_date,
			SUM(CASE WHEN recruit_prefix = "เด็กหญิง"  and recruit_regLevel = 1 THEN 1 ELSE 0 END) AS F1,
			SUM(CASE WHEN recruit_prefix = "เด็กชาย"  and recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1,
			SUM(CASE WHEN (recruit_prefix = "เด็กหญิง" and recruit_regLevel = 4) or (recruit_prefix = "นางสาว"  and recruit_regLevel = 4) THEN 1 ELSE 0 END) AS F4,			
			SUM(CASE WHEN (recruit_prefix = "เด็กชาย" and recruit_regLevel = 4) or (recruit_prefix = "นาย" and recruit_regLevel = 4) THEN 1 ELSE 0 END) AS M4')
            ->where('recruit_year', $year)
            ->where("recruit_date BETWEEN '2025-01-01' AND '2025-01-31'")
            ->groupBy('recruit_date')
            ->get()->getResultArray();

        $data['StatisticTableQuotaSport'] = $this->db->table('tb_recruitstudent')
            ->select('tb_recruitstudent.recruit_major,
			SUM(CASE WHEN recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1,
			SUM(CASE WHEN recruit_regLevel = 2 THEN 1 ELSE 0 END) AS M2,
			SUM(CASE WHEN recruit_regLevel = 3 THEN 1 ELSE 0 END) AS M3,
			SUM(CASE WHEN recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4,
			SUM(CASE WHEN recruit_regLevel = 5 THEN 1 ELSE 0 END) AS M5,
			SUM(CASE WHEN recruit_regLevel = 6 THEN 1 ELSE 0 END) AS M6,
			COUNT(recruit_major) AS Tatal')
            ->where('recruit_year', $year)
            ->where("recruit_category", "quotasport")
            ->groupBy('recruit_major')
            ->get()->getResult();

        return $data;
    }

    public function AllStatistic($year)
    {
        $data = $this->report_student($year);

        $data['year'] = $this->db->table('tb_recruitstudent')->select('recruit_year')->groupBy('recruit_year')->orderBy('recruit_year', 'DESC')->get()->getResult();
        $data['checkYear'] = $this->db->table('tb_openyear')->select('*')->get()->getResult();

        $data['switch'] = $this->db->table("tb_onoffsys")->get()->getResult();
        $data['quota'] = $this->db->table("tb_quota")->get()->getResult();
        $data['datethai'] = $this->datethai;

        // $db2 = \Config\Database::connect('skjmain'); // Not used in original code logic shown
        $data['title'] = "สถิติการรับสมัครนักเรียน" . $data['checkYear'][0]->openyear_year;
        $data['description'] = "ดูสถิติแบบเรียลไทม์";
        $data['banner'] = base_url() . "asset/img/Statistics.png";
        $data['url'] = "Statistic";

        return view('AdminssionStatistic', $data);
    }

    public function StatisticViewQuotaSport($year)
    {
        $data = $this->db->table('tb_recruitstudent')
            ->select('tb_recruitstudent.recruit_major,
			SUM(CASE WHEN recruit_regLevel = 1 THEN 1 ELSE 0 END) AS M1,
			SUM(CASE WHEN recruit_regLevel = 2 THEN 1 ELSE 0 END) AS M2,
			SUM(CASE WHEN recruit_regLevel = 3 THEN 1 ELSE 0 END) AS M3,
			SUM(CASE WHEN recruit_regLevel = 4 THEN 1 ELSE 0 END) AS M4,
			SUM(CASE WHEN recruit_regLevel = 5 THEN 1 ELSE 0 END) AS M5,
			SUM(CASE WHEN recruit_regLevel = 6 THEN 1 ELSE 0 END) AS M6,
			COUNT(recruit_major) AS Tatal')
            ->where('recruit_year', $year)
            ->where("recruit_category", "quotasport")
            ->groupBy('recruit_major')
            ->get()->getResultArray();
        echo json_encode($data);
    }

    public function StatisticViewQuotaSportFM($Year)
    {
        $data['StatisticViewQuotaSportFM'] = $this->db->table('tb_recruitstudent')
            ->select('SUM(CASE WHEN recruit_prefix = "เด็กหญิง" or recruit_prefix = "นางสาว" THEN 1 ELSE 0 END) AS female,
					SUM(CASE WHEN recruit_prefix = "เด็กชาย" or recruit_prefix = "นาย" THEN 1 ELSE 0 END) AS male,
					tb_recruitstudent.recruit_regLevel
					,tb_recruitstudent.recruit_year
					,tb_recruitstudent.recruit_date')
            ->where('recruit_year', $Year)
            ->where('recruit_category', 'quotasport')
            ->get()->getRow();

        echo json_encode($data['StatisticViewQuotaSportFM']);
    }

    public function StatisticViewQuota($Year)
    {
        $data['StatisticCroTar'] = $this->db->table('tb_recruitstudent')
            ->select('SUM(CASE WHEN recruit_prefix = "เด็กหญิง" or recruit_prefix = "นางสาว" THEN 1 END) AS female,
					SUM(CASE WHEN recruit_prefix = "เด็กชาย" or recruit_prefix = "นาย" THEN 1 END) AS male,
					tb_recruitstudent.recruit_regLevel
					,tb_recruitstudent.recruit_year
					,tb_recruitstudent.recruit_date')
            ->where('recruit_year', $Year)
            ->where("recruit_date BETWEEN '2025-01-01' AND '2025-01-31'", NULL, FALSE)
            ->groupBy('recruit_date')
            ->orderBy('recruit_date', 'ASC')
            ->get()->getResultArray();

        echo json_encode($data['StatisticCroTar']);
    }

    public function StatisticViewGeneral($Year)
    {
        $data['StatisticGeneral'] = $this->db->table('tb_recruitstudent')
            ->select('SUM(CASE WHEN recruit_prefix = "เด็กหญิง" or recruit_prefix = "นางสาว" THEN 1 END) AS female,
					SUM(CASE WHEN recruit_prefix = "เด็กชาย" or recruit_prefix = "นาย" THEN 1 END) AS male,
					tb_recruitstudent.recruit_regLevel
					,tb_recruitstudent.recruit_year
					,tb_recruitstudent.recruit_date')
            ->where('recruit_year', $Year)
            ->where("recruit_date BETWEEN '2025-03-25' AND '2025-03-31'", NULL, FALSE)
            ->groupBy('recruit_date')
            ->orderBy('recruit_date', 'ASC')
            ->get()->getResultArray();

        echo json_encode($data['StatisticGeneral']);
    }

    public function StatisticViewGeneralTotal($Year)
    {
        $data['StatisticGeneralTotal'] = $this->db->table('tb_recruitstudent')
            ->select('SUM(CASE WHEN recruit_prefix = "เด็กหญิง" or recruit_prefix = "นางสาว" THEN 1 ELSE 0 END) AS female,
					SUM(CASE WHEN recruit_prefix = "เด็กชาย" or recruit_prefix = "นาย" THEN 1 ELSE 0 END) AS male,
					tb_recruitstudent.recruit_regLevel
					,tb_recruitstudent.recruit_year
					,tb_recruitstudent.recruit_date')
            ->where('recruit_year', $Year)
            ->where('recruit_category', 'normal')
            ->get()->getRow();

        echo json_encode($data['StatisticGeneralTotal']);
    }
}
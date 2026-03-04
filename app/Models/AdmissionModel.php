<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmissionModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        helper('upload');
    }

    protected $table = 'tb_recruitstudent';
    protected $primaryKey = 'recruit_id'; // ใช้ recruit_id เป็น PK เพราะ Controller ค้นหาด้วย recruit_id
    protected $allowedFields = [
        'id',
        'recruit_id',
        'recruit_year',
        'recruit_regLevel',
        'recruit_prefix',
        'recruit_firstName',
        'recruit_lastName',
        'recruit_idCard',
        'recruit_birthday',
        'recruit_race',
        'recruit_nationality',
        'recruit_religion',
        'recruit_phone',
        'recruit_homeNumber',
        'recruit_homeGroup',
        'recruit_homeRoad',
        'recruit_homeSubdistrict',
        'recruit_homedistrict',
        'recruit_homeProvince',
        'recruit_homePostcode',
        'recruit_oldSchool',
        'recruit_district',
        'recruit_province',
        'recruit_grade',
        'recruit_category',
        'recruit_tpyeRoom',
        'recruit_tpyeRoom_id',
        'recruit_major',
        'recruit_majorOrder',
        'recruit_nickname',
        'recruit_weight',
        'recruit_height',
        'recruit_fatherName',
        'recruit_fatherJob',
        'recruit_motherName',
        'recruit_motherJob',
        'recruit_sportPosition',
        'recruit_sportSelectionResult',
        'recruit_agegroup',
        'recruit_img',
        'recruit_status',
        'recruit_date',
        'recruit_dateUpdate',
        'recruit_address',
        'recruit_copyAddress',
        'recruit_statusSurrender',
        'recruit_statusFinal',
        'recruit_StatusQuiz',
        'recruit_certificateAbility',
        'recruit_certificateEdu',
        'recruit_certificateEduB',
        'recruit_copyidCard',
        'recruit_userUpdate',
        'recruit_round'
    ];

    public function student_insert($data)
    {
        if ($this->insert($data)) {
            return $this->insertID();
        }
        return false;
    }

    public function student_update($id, $data)
    {
        return $this->update($id, $data);
    }

    public function student_delete($id)
    {
        return $this->delete($id);
    }

    public function getSystemStatus()
    {
        return $this->db->table('tb_onoffsys')->where('onoff_id', 1)->get()->getRow();
    }

    public function getOpenYear()
    {
        return $this->db->table('tb_openyear')->get()->getRow();
    }

    public function getRecruitmentYears()
    {
        return $this->db->table('tb_recruitstudent')
            ->select('recruit_year')
            ->groupBy('recruit_year')
            ->orderBy('recruit_year', 'DESC')
            ->get()
            ->getResult();
    }

    public function isIdCardRegistered($idcard, $year)
    {
        $plainId = str_replace('-', '', $idcard ?? '');
        $formattedId = \format_id_card($idcard);

        return $this->groupStart()
                ->where('recruit_idCard', $plainId)
                ->orWhere('recruit_idCard', $formattedId)
            ->groupEnd()
            ->where('recruit_year', $year)
            ->countAllResults() > 0;
    }

    public function getLatestRecruitId()
    {
        return $this->select('recruit_id')->orderBy('recruit_id', 'DESC')->get()->getRow();
    }

    public function checkIdCard($idCard)
    {
        $year = $this->getOpenYear()->openyear_year;
        return $this->isIdCardRegistered($idCard, $year);
    }

    public function findStudentForStatusCheck($idcard, $birthday, $year)
    {
        $plainId = str_replace('-', '', $idcard ?? '');
        $formattedId = \format_id_card($idcard);

        return $this->groupStart()
                ->where('recruit_idCard', $plainId)
                ->orWhere('recruit_idCard', $formattedId)
            ->groupEnd()
            ->where('recruit_birthday', $birthday)
            ->where('recruit_year', $year)
            ->get()
            ->getRow();
    }

    public function getCourseById($id, $gradeLevel)
    {
        $grade = ($gradeLevel <= 3) ? 'ม.ต้น' : 'ม.ปลาย';
        return $this->db->table('tb_course')
            ->where('course_id', $id)
            ->where('course_gradelevel', $grade)
            ->get()
            ->getRow();
    }

    public function getQuotaByKey($key)
    {
        return $this->db->table('tb_quota')->where('quota_key', $key)->get()->getRow();
    }

    public function getCourseIdsFromQuota($key)
    {
        $quota = $this->db->table('tb_quota')->select('quota_course')->where('quota_key', $key)->get()->getRow();
        if ($quota && !empty($quota->quota_course)) {
            return explode('|', $quota->quota_course);
        }
        return [];
    }

    public function getCourseDetails($courseId)
    {
        return $this->db->table('tb_course')
            ->select('course_fullname, course_branch')
            ->where('course_id', $courseId)
            ->get()
            ->getRow();
    }

    public function getApplicantsByLevel($level)
    {
        return $this->select('recruit_id, recruit_regLevel, recruit_status, recruit_tpyeRoom, recruit_prefix, recruit_firstName, recruit_lastName')
            ->where('recruit_regLevel', $level)
            ->get()
            ->getResult();
    }

    public function checkPrintLogin($id, $date)
    {
        return $this->where('recruit_id', $id)
            ->where('recruit_birthday', $date)
            ->get()
            ->getRow();
    }

    public function getSystemOnOffStatus()
    {
        return $this->db->table('tb_onoffsys')
            ->select('onoff_regis, onoff_datetime_regis_close, onoff_datetime_regis_open')
            ->where('onoff_id', 1)
            ->get()
            ->getRow();
    }

    public function getAllQuotas()
    {
        return $this->db->table('tb_quota')->get()->getResult();
    }

    public function getAllCourses()
    {
        return $this->db->table('tb_course')->get()->getResult();
    }

    public function getQuotasWithApplicants($year)
    {
        return $this->db->table('tb_quota')
            ->select('tb_quota.*')
            ->join('tb_recruitstudent', 'tb_recruitstudent.recruit_category = tb_quota.quota_key')
            ->where('tb_recruitstudent.recruit_year', $year)
            ->groupBy('tb_quota.quota_key')
            ->get()
            ->getResult();
    }

    public function getCoursesByGradeLevel($level)
    {
        return $this->db->table("tb_course")
            ->where('course_gradelevel', $level)
            ->get()
            ->getResult();
    }

    public function getSchool($postData)
    {
        $db_schollall = \Config\Database::connect('schoolall');

        $response = array();

        if (isset($postData['search'])) {
            // Select record
            $builder = $db_schollall->table('schoolall');
            $builder->select('schoola_province,schoola_amphur,schoola_district,schoola_name,schoola_id');
            $builder->like('schoola_name', $postData['search']);

            $records = $builder->get()->getResult();

            foreach ($records as $row) {
                $response[] = array(
                    "value" => $row->schoola_id,
                    "label" => $row->schoola_name,
                    "amphur" => $row->schoola_amphur,
                    "district" => $row->schoola_district,
                    "province" => $row->schoola_province,
                );
            }
        }

        return $response;
    }

    public function getServiceAreaSchools($search)
    {
        $builder = $this->db->table('tb_service_area_schools');
        $builder->select('id, school_name, school_amphur, school_province');
        if (!empty($search)) {
            $builder->like('school_name', $search);
        }
        $records = $builder->get()->getResult();

        $response = [];
        foreach ($records as $row) {
            $response[] = [
                "value" => $row->school_name, // Use name as value for consistency if ID is not used in main table
                "label" => $row->school_name,
                "province" => $row->school_province,
                "amphur" => $row->school_amphur
            ];
        }
        return $response;
    }
    public function getAdmissionSchedule($year)
    {
        return $this->db->table('tb_admission_schedule')
            ->where('schedule_year', $year)
            ->orderBy('schedule_id', 'ASC')
            ->orderBy('schedule_level', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Get admission statistics for a specific year with optional filters
     */
    public function getAdmissionStats($year, $filters = [])
    {
        $builder = $this->db->table($this->table);
        $builder->where('recruit_year', $year);
        
        if (!empty($filters['recruit_round'])) {
            $builder->where('recruit_round', $filters['recruit_round']);
        }
        if (!empty($filters['recruit_category'])) {
            $builder->where('recruit_category', $filters['recruit_category']);
        }

        // Total by level and gender
        $totalByLevelBuilder = clone $builder;
        $totalByLevel = $totalByLevelBuilder
            ->select('recruit_regLevel, 
                     COUNT(*) as total,
                     SUM(CASE WHEN recruit_prefix IN ("เด็กชาย", "นาย") THEN 1 ELSE 0 END) as male,
                     SUM(CASE WHEN recruit_prefix IN ("เด็กหญิง", "นางสาว") THEN 1 ELSE 0 END) as female')
            ->groupBy('recruit_regLevel')
            ->get()
            ->getResult();

        // Total by status
        $totalByStatusBuilder = clone $builder;
        $totalByStatus = $totalByStatusBuilder
            ->select('recruit_status, COUNT(*) as total')
            ->groupBy('recruit_status')
            ->get()
            ->getResult();

        // Total by Program/Room and gender
        $totalByRoomBuilder = clone $builder;
        $totalByRoom = $totalByRoomBuilder
            ->select('recruit_regLevel, recruit_tpyeRoom, 
                     COUNT(*) as total,
                     SUM(CASE WHEN recruit_prefix IN ("เด็กชาย", "นาย") THEN 1 ELSE 0 END) as male,
                     SUM(CASE WHEN recruit_prefix IN ("เด็กหญิง", "นางสาว") THEN 1 ELSE 0 END) as female')
            ->groupBy('recruit_regLevel, recruit_tpyeRoom')
            ->orderBy('recruit_regLevel', 'ASC')
            ->get()
            ->getResult();

        $grandTotalBuilder = clone $builder;
        return [
            'total_by_level' => $totalByLevel,
            'total_by_status' => $totalByStatus,
            'total_by_room' => $totalByRoom,
            'grand_total' => $grandTotalBuilder->countAllResults()
        ];
    }

    /**
     * Get daily registration statistics with optional filters
     */
    public function getDailyStats($year, $filters = [])
    {
        $builder = $this->db->table($this->table);
        $builder->where('recruit_year', $year);

        if (!empty($filters['recruit_round'])) {
            $builder->where('recruit_round', $filters['recruit_round']);
        }
        if (!empty($filters['recruit_category'])) {
            $builder->where('recruit_category', $filters['recruit_category']);
        }

        return $builder->select('DATE(recruit_date) as date, 
                     COUNT(*) as total,
                     SUM(CASE WHEN recruit_regLevel = 1 THEN 1 ELSE 0 END) as m1,
                     SUM(CASE WHEN recruit_regLevel = 4 THEN 1 ELSE 0 END) as m4,
                     SUM(CASE WHEN recruit_prefix IN ("เด็กชาย", "นาย") THEN 1 ELSE 0 END) as male,
                     SUM(CASE WHEN recruit_prefix IN ("เด็กหญิง", "นางสาว") THEN 1 ELSE 0 END) as female')
            ->groupBy('DATE(recruit_date)')
            ->orderBy('DATE(recruit_date)', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Get statistics grouped by level and status with optional filters
     */
    public function getStatsByLevelAndStatus($year, $filters = [])
    {
        $builder = $this->db->table($this->table);
        $builder->where('recruit_year', $year);

        if (!empty($filters['recruit_round'])) {
            $builder->where('recruit_round', $filters['recruit_round']);
        }
        if (!empty($filters['recruit_category'])) {
            $builder->where('recruit_category', $filters['recruit_category']);
        }

        return $builder->select('recruit_regLevel, recruit_status, COUNT(*) as total')
            ->groupBy('recruit_regLevel, recruit_status')
            ->get()
            ->getResult();
    }
}

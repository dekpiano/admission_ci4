<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlSurrender extends BaseController
{
    protected $db;
    protected $session;
    protected $title = "การรับสมัคร";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['url', 'upload']);
    }

    private function checkAuth()
    {
        if (!$this->session->has('login_id') && !$this->session->has('pers_id')) {
            return redirect()->to('loginAdmin');
        }
        return null;
    }

    public function index()
    {
        // Check and add columns if not exist
        if (!$this->db->fieldExists('recruit_statusSurrender', 'tb_recruitstudent')) {
            $this->db->query("ALTER TABLE tb_recruitstudent ADD COLUMN recruit_statusSurrender VARCHAR(20) NULL");
        }
        if (!$this->db->fieldExists('recruit_statusFinal', 'tb_recruitstudent')) {
            $this->db->query("ALTER TABLE tb_recruitstudent ADD COLUMN recruit_statusFinal VARCHAR(50) NULL");
        }

        $request = service('request');
        
        // Fetch Available Years from data
        $yearList = $this->db->table('tb_recruitstudent')
            ->select('recruit_year')
            ->groupBy('recruit_year')
            ->orderBy('recruit_year', 'DESC')
            ->get()->getResult();
        $data['years'] = $yearList;

        // Get selected year from request or default to latest year in DB
        $year = $request->getVar('year');
        if (empty($year)) {
            if (!empty($yearList)) {
                $year = $yearList[0]->recruit_year;
            } else {
                $year = date('Y') + 543;
            }
        }
        
        $data['selected_year'] = $year;
        $data['title'] = 'ข้อมูลการรายงานตัว';

        $builder = $this->db->table('tb_recruitstudent');
        $builder->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_course.course_initials, tb_course.course_fullname, tb_course.course_branch, skjacth_personnel.tb_students.stu_UpdateConfirm');
        $builder->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left');
        $builder->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left');
        $builder->join('skjacth_personnel.tb_students', 'tb_recruitstudent.recruit_idCard = skjacth_personnel.tb_students.stu_iden', 'left');
        $builder->where('recruit_year', $year);
        $builder->groupBy('tb_recruitstudent.recruit_id');
        $builder->orderBy('recruit_id', 'DESC');

        $data['students'] = $builder->get()->getResult();

        return view('Admin/PageAdminSurrender/PageAdminSurrenderIndex', $data);
    }

    public function UpdateSurrender()
    {
        try {
            $recruit_id = $this->request->getVar('recruit_id');
            $status = $this->request->getVar('status');
            
            if (empty($recruit_id)) {
                return $this->response->setJSON(['success' => false, 'message' => 'recruit_id is empty']);
            }

            $data = ['recruit_statusSurrender' => ($status == 1 ? date('Y-m-d') : '')];
            $this->db->table('tb_recruitstudent')->where('recruit_id', $recruit_id)->update($data);
            $affected = $this->db->affectedRows();

            return $this->response->setJSON([
                'success' => $affected > 0,
                'affected' => $affected,
                'recruit_id' => $recruit_id,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function UpdateFinalStatus()
    {
        try {
            $recruit_id = $this->request->getVar('recruit_id');
            $status = $this->request->getVar('status');
            
            if (empty($recruit_id)) {
                return $this->response->setJSON(['success' => false, 'message' => 'recruit_id is empty']);
            }

            // Ensure column exists
            if (!$this->db->fieldExists('recruit_statusFinal', 'tb_recruitstudent')) {
                $this->db->query("ALTER TABLE tb_recruitstudent ADD COLUMN recruit_statusFinal VARCHAR(50) NULL");
            }

            $data = ['recruit_statusFinal' => ($status == 1 ? 'เสร็จสิ้น' : '')];
            $this->db->table('tb_recruitstudent')->where('recruit_id', $recruit_id)->update($data);
            $affected = $this->db->affectedRows();

            return $this->response->setJSON([
                'success' => $affected > 0,
                'affected' => $affected,
                'recruit_id' => $recruit_id,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function print($id)
    {
        // Load mPDF using SHARED_LIB_PATH
        if (file_exists(SHARED_LIB_PATH . '/mpdf/vendor/autoload.php')) {
            require_once SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        } else {
            return "mPDF library not found at: " . SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        }

        // Fetch recruit data first to get studentId
        $recruit = $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->get()->getResult();
        if (empty($recruit)) {
            return "ไม่พบข้อมูลผู้สมัคร";
        }

        $studentId = $recruit[0]->recruit_idCard;
        $Year = $recruit[0]->recruit_year;

        // Fetch Confirmation Data (tb_students)
        $confrim = $this->db->table('skjacth_personnel.tb_students')
            ->where('stu_iden', $studentId)
            ->get()->getResult();

        if (empty($confrim)) {
            return "ไม่พบข้อมูลการรายงานตัว กรุณาให้นักเรียนกรอกข้อมูลรายงานตัวก่อน";
        }

        $idstu = str_replace('-', '', $confrim[0]->stu_iden);

        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        $date_Y = date('Y') + 543;
        $date_D = (int) date('d');
        $date_M = date('n');

        $date_Y_birt = date('Y', strtotime($confrim[0]->stu_birthDay)) + 543;
        $date_D_birt = (int) date('d', strtotime($confrim[0]->stu_birthDay));
        $date_M_birt = date('n', strtotime($confrim[0]->stu_birthDay));

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'debug' => false
        ]);
        $mpdf->SetTitle($confrim[0]->stu_prefix . $confrim[0]->stu_fristName . ' ' . $confrim[0]->stu_lastName);

        $html = '<div style="position:absolute;top:577px;left:263px; width:100%; font-size:1.5rem">' . $idstu[0] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:305px; width:100%; font-size:1.5rem">' . $idstu[1] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:343px; width:100%; font-size:1.5rem">' . $idstu[2] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:380px; width:100%; font-size:1.5rem">' . $idstu[3] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:415px; width:100%; font-size:1.5rem">' . $idstu[4] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:455px; width:100%; font-size:1.5rem">' . $idstu[5] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:490px; width:100%; font-size:1.5rem">' . $idstu[6] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:530px; width:100%; font-size:1.5rem">' . $idstu[7] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:565px; width:100%; font-size:1.5rem">' . $idstu[8] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:600px; width:100%; font-size:1.5rem">' . $idstu[9] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:645px; width:100%; font-size:1.5rem">' . $idstu[10] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:680px; width:100%; font-size:1.5rem">' . $idstu[11] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:722px; width:100%; font-size:1.5rem">' . $idstu[12] . '</div>';

        $html .= '<div style="position:absolute;top:30px;left:50px; width:100%">เลขที่สมัคร ' . $recruit[0]->recruit_id . '</div>';

        $html .= '<div style="position:absolute;top:463px;left:420px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:475px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:550px; width:100%">' . $date_Y . '</div>';

        $html .= '<div style="position:absolute;top:75px;left:663px; width:100%"><img style="width: 100px;height:130px;" src="' . get_recruit_file_url($recruit[0]->recruit_img, $recruit[0]->recruit_regLevel, 'img') . '"></div>';

        $regLevel = $confrim[0]->stu_regLevel ?? $recruit[0]->recruit_regLevel;

        $html .= '<div style="position:absolute;top:105px;left:230px; width:100%">' . $regLevel . '</div>';
        $html .= '<div style="position:absolute;top:105px;left:470px; width:100%">' . $Year . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:140px; width:100%">' . $confrim[0]->stu_prefix . $confrim[0]->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:400px; width:100%">' . $confrim[0]->stu_lastName . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:120px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:250px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:470px; width:100%">' . $date_Y . '</div>';

        $html .= '<div style="position:absolute;top:243px;left:340px; width:100%">' . $confrim[0]->stu_prefix . $confrim[0]->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:243px;left:530px; width:100%">' . $confrim[0]->stu_lastName . '</div>';

        $html .= '<div style="position:absolute;top:510px;left:250px; width:100%">' . $date_D_birt . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:420px; width:100%">' . $TH_Month[$date_M_birt - 1] . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:650px; width:100%">' . $date_Y_birt . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:235px; width:100%">' . $confrim[0]->stu_birthTambon . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:420px; width:100%">' . $confrim[0]->stu_birthDistrict . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:620px; width:100%">' . $confrim[0]->stu_birthProvirce . '</div>';
        $html .= '<div style="position:absolute;top:553px;left:240px; width:100%">' . $confrim[0]->stu_birthHospital . '</div>';

        $html .= '<div style="position:absolute;top:618px;left:160px; width:100%">' . $confrim[0]->stu_nationality . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:330px; width:100%">' . $confrim[0]->stu_race . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:520px; width:100%">' . $confrim[0]->stu_religion . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:680px; width:100%">' . $confrim[0]->stu_bloodType . '</div>';
        $html .= '<div style="position:absolute;top:640px;left:180px; width:100%">' . $confrim[0]->stu_diseaes . '</div>';

        $html .= '<div style="position:absolute;top:668px;left:370px; width:100%">' . $confrim[0]->stu_numberSibling . '</div>';
        $html .= '<div style="position:absolute;top:668px;left:620px; width:100%">' . $confrim[0]->stu_firstChild . '</div>';
        $html .= '<div style="position:absolute;top:690px;left:530px; width:100%">' . $confrim[0]->stu_numberSiblingSkj . '</div>';
        $html .= '<div style="position:absolute;top:690px;left:700px; width:100%">' . $confrim[0]->stu_nickName . '</div>';

        $html .= '<div style="position:absolute;top:710px;left:120px; width:100%">' . $confrim[0]->stu_disablde . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:290px; width:100%">' . $confrim[0]->stu_wieght . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:410px; width:100%">' . $confrim[0]->stu_hieght . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:640px; width:100%">' . $confrim[0]->stu_talent . '</div>';

        $checkMark = '<div style="position:absolute;top:%dpx;left:%dpx; width:100%%"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';

        if ($confrim[0]->stu_parenalStatus == 'อยู่ด้วยกัน')
            $html .= sprintf($checkMark, 740, 178);
        else if ($confrim[0]->stu_parenalStatus == 'แยกกันอยู่')
            $html .= sprintf($checkMark, 740, 270);
        else if ($confrim[0]->stu_parenalStatus == 'หย่าร้าง')
            $html .= sprintf($checkMark, 740, 365);
        else if ($confrim[0]->stu_parenalStatus == 'บิดาถึงแก่กรรม')
            $html .= sprintf($checkMark, 740, 448);
        else if ($confrim[0]->stu_parenalStatus == 'มารดาถึงแก่กรรม')
            $html .= sprintf($checkMark, 740, 565);
        else if ($confrim[0]->stu_parenalStatus == 'บิดาหรือมารดาแต่งงานใหม่')
            $html .= sprintf($checkMark, 765, 178);

        if ($confrim[0]->stu_presentLife == 'อยู่กับบิดาและมารดา')
            $html .= sprintf($checkMark, 790, 225);
        else if ($confrim[0]->stu_presentLife == 'อยู่กับบิดาหรือมารดา')
            $html .= sprintf($checkMark, 790, 360);
        else if ($confrim[0]->stu_presentLife == 'บุคคลอื่น')
            $html .= sprintf($checkMark, 790, 510);

        $html .= '<div style="position:absolute;top:787px;left:620px; width:100%">' . $confrim[0]->stu_personOther . '</div>';

        $html .= '<div style="position:absolute;top:814px;left:310px; width:100%">' . $confrim[0]->stu_hCode . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:495px; width:100%">' . $confrim[0]->stu_hNumber . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:585px; width:100%">' . $confrim[0]->stu_hMoo . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:665px; width:100%">' . $confrim[0]->stu_hRoad . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:120px; width:100%">' . $confrim[0]->stu_hTambon . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:300px; width:100%">' . $confrim[0]->stu_hDistrict . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:490px; width:100%">' . $confrim[0]->stu_hProvince . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:675px; width:100%">' . $confrim[0]->stu_hPostCode . '</div>';
        $html .= '<div style="position:absolute;top:857px;left:190px; width:100%">' . $confrim[0]->stu_phone . '</div>';
        $html .= '<div style="position:absolute;top:857px;left:450px; width:100%">' . $confrim[0]->stu_email . '</div>';

        $html .= '<div style="position:absolute;top:883px;left:340px; width:100%">' . $confrim[0]->stu_cNumber . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:450px; width:100%">' . $confrim[0]->stu_cMoo . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:520px; width:100%">' . $confrim[0]->stu_cRoad . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:660px; width:100%">' . $confrim[0]->stu_cTumbao . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:110px; width:100%">' . $confrim[0]->stu_cDistrict . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:300px; width:100%">' . $confrim[0]->stu_cProvince . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:490px; width:100%">' . $confrim[0]->stu_cPostcode . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:640px; width:100%">' . $confrim[0]->stu_phone . '</div>';

        if ($confrim[0]->stu_natureRoom == 'บ้านตนเอง')
            $html .= sprintf($checkMark, 935, 130);
        else if ($confrim[0]->stu_natureRoom == 'เช่าอยู่')
            $html .= sprintf($checkMark, 935, 227);
        else if ($confrim[0]->stu_natureRoom == 'อาศัยผู้อื่นอยู่')
            $html .= sprintf($checkMark, 935, 300);
        else if ($confrim[0]->stu_natureRoom == 'บ้านพักราชการ')
            $html .= sprintf($checkMark, 935, 405);
        else if ($confrim[0]->stu_natureRoom == 'วัด')
            $html .= sprintf($checkMark, 935, 525);
        else if ($confrim[0]->stu_natureRoom == 'หอพัก')
            $html .= sprintf($checkMark, 935, 570);

        $html .= '<div style="position:absolute;top:950px;left:250px; width:100%">' . $confrim[0]->stu_farSchool . '</div>';
        $html .= '<div style="position:absolute;top:950px;left:470px; width:100%">' . $confrim[0]->stu_travel . '</div>';

        $html .= '<div style="position:absolute;top:977px;left:153px; width:100%">' . $confrim[0]->stu_gradLevel . '</div>';
        $html .= '<div style="position:absolute;top:977px;left:260px; width:100%">' . $confrim[0]->stu_schoolfrom . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:230px; width:100%">' . $confrim[0]->stu_schoolTambao . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:450px; width:100%">' . $confrim[0]->stu_schoolDistrict . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:630px; width:100%">' . $confrim[0]->stu_schoolProvince . '</div>';

        if ($confrim[0]->stu_usedStudent == 'ไม่เคย')
            $html .= sprintf($checkMark, 1030, 487);
        else if ($confrim[0]->stu_usedStudent == 'เคย')
            $html .= sprintf($checkMark, 1030, 560);

        $html .= '<div style="position:absolute;top:1026px;left:690px; width:100%">' . $confrim[0]->stu_inputLevel . '</div>';

        $html .= '<div style="position:absolute;top:1055px;left:200px; width:100%">' . $confrim[0]->stu_phoneUrgent . '</div>';
        $html .= '<div style="position:absolute;top:1055px;left:550px; width:100%">' . $confrim[0]->stu_phoneFriend . '</div>';

        if ($recruit[0]->recruit_regLevel >= 4) {
            $mpdf->SetDocTemplate('uploads/confirm/confirmM4All.pdf', true);
        } else if ($recruit[0]->recruit_regLevel <= 3) {
            $mpdf->SetDocTemplate('uploads/confirm/confirmM1All.pdf', true);
        }

        $filename = sprintf("%04d", $confrim[0]->stu_iden) . '-' . $confrim[0]->stu_prefix . $confrim[0]->stu_fristName . ' ' . $confrim[0]->stu_lastName;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage();

        // Father Data
        $confrimFa = $this->db->table('skjacth_personnel.tb_parent')->where('par_stuID', $studentId)->where('par_relationKey', "พ่อ")->get()->getResult();
        $father = $confrimFa[0] ?? null;

        $par_decease = ($father->par_decease ?? '0000-00-00') == '0000-00-00' ? "" : $father->par_decease;

        $html2 = '<div style="position:absolute;top:129px;left:195px; width:100%">' . ($father->par_prefix ?? '') . ($father->par_firstName ?? '') . ' ' . ($father->par_lastName ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:150px;left:275px; width:100%">' . ($father->par_ago ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:180px;left:197px; width:100%">' . ($father->par_IdNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:205px;left:197px; width:100%">' . ($father->par_national ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:230px;left:197px; width:100%">' . ($father->par_race ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:255px;left:197px; width:100%">' . ($father->par_religion ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:280px;left:197px; width:100%">' . ($father->par_career ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:305px;left:197px; width:100%">' . ($father->par_education ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:330px;left:197px; width:100%">' . ($father->par_salary ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:355px;left:197px; width:100%">' . ($father->par_positionJob ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:380px;left:197px; width:100%">' . ($father->par_phone ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:405px;left:197px; width:100%">' . $par_decease . '</div>';

        $html2 .= '<div style="position:absolute;top:430px;left:255px; width:100%">' . ($father->par_hNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:430px;left:335px; width:100%">' . ($father->par_hMoo ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:452px;left:245px; width:100%">' . ($father->par_hTambon ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:474px;left:245px; width:100%">' . ($father->par_hDistrict ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:496px;left:245px; width:100%">' . ($father->par_hProvince ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:518px;left:280px; width:100%">' . ($father->par_hPostcode ?? '') . '</div>';

        $html2 .= '<div style="position:absolute;top:540px;left:255px; width:100%">' . ($father->par_cNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:540px;left:335px; width:100%">' . ($father->par_cMoo ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:562px;left:245px; width:100%">' . ($father->par_cTambon ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:584px;left:245px; width:100%">' . ($father->par_cDistrict ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:605px;left:245px; width:100%">' . ($father->par_cProvince ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:628px;left:280px; width:100%">' . ($father->par_cPostcode ?? '') . '</div>';

        if (($father->par_rest ?? '') == 'บ้านตนเอง')
            $html2 .= sprintf($checkMark, 655, 200);
        else if (($father->par_rest ?? '') == 'เช่าบ้าน')
            $html2 .= sprintf($checkMark, 680, 200);
        else if (($father->par_rest ?? '') == 'อาศัยผู้อื่น')
            $html2 .= sprintf($checkMark, 705, 200);
        else if (($father->par_rest ?? '') == 'บ้านพักสวัสดิการ')
            $html2 .= sprintf($checkMark, 730, 200);
        else if (($father->par_rest ?? '') == 'อื่นๆ')
            $html2 .= sprintf($checkMark, 755, 200);

        $html2 .= '<div style="position:absolute;top:750px;left:280px; width:100%">' . ($father->par_restOrthor ?? '') . '</div>';

        if (($father->par_service ?? '') == 'กระทรวง') {
            $html2 .= sprintf($checkMark, 780, 200);
            $html2 .= '<div style="position:absolute;top:775px;left:280px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
        } else if (($father->par_service ?? '') == 'กรม') {
            $html2 .= sprintf($checkMark, 803, 200);
            $html2 .= '<div style="position:absolute;top:797px;left:250px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
        } else if (($father->par_service ?? '') == 'กอง') {
            $html2 .= sprintf($checkMark, 828, 200);
            $html2 .= '<div style="position:absolute;top:820px;left:250px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
        } else if (($father->par_service ?? '') == 'ฝ่าย/แผนก') {
            $html2 .= sprintf($checkMark, 850, 200);
            $html2 .= '<div style="position:absolute;top:845px;left:285px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
        }

        if (($father->par_claim ?? '') == 'เบิกได้')
            $html2 .= sprintf($checkMark, 875, 200);
        else if (($father->par_claim ?? '') == 'เบิกไม่ได้')
            $html2 .= sprintf($checkMark, 875, 270);

        // Mother Data
        $confrimMa = $this->db->table('skjacth_personnel.tb_parent')->where('par_stuID', $studentId)->where('par_relationKey', "แม่")->get()->getResult();
        $mother = $confrimMa[0] ?? null;
        $par_decease_Ma = ($mother->par_decease ?? '0000-00-00') == '0000-00-00' ? "" : $mother->par_decease;

        $html2 .= '<div style="position:absolute;top:129px;left:400px; width:100%">' . ($mother->par_prefix ?? '') . ($mother->par_firstName ?? '') . ' ' . ($mother->par_lastName ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:150px;left:475px; width:100%">' . ($mother->par_ago ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:180px;left:400px; width:100%">' . ($mother->par_IdNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:205px;left:400px; width:100%">' . ($mother->par_national ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:230px;left:400px; width:100%">' . ($mother->par_race ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:255px;left:400px; width:100%">' . ($mother->par_religion ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:280px;left:400px; width:100%">' . ($mother->par_career ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:305px;left:400px; width:100%">' . ($mother->par_education ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:330px;left:400px; width:100%">' . ($mother->par_salary ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:355px;left:400px; width:100%">' . ($mother->par_positionJob ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:380px;left:400px; width:100%">' . ($mother->par_phone ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:405px;left:400px; width:100%">' . $par_decease_Ma . '</div>';

        $html2 .= '<div style="position:absolute;top:430px;left:455px; width:100%">' . ($mother->par_hNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:430px;left:530px; width:100%">' . ($mother->par_hMoo ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:452px;left:450px; width:100%">' . ($mother->par_hTambon ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:474px;left:450px; width:100%">' . ($mother->par_hDistrict ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:496px;left:450px; width:100%">' . ($mother->par_hProvince ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:518px;left:480px; width:100%">' . ($mother->par_hPostcode ?? '') . '</div>';

        $html2 .= '<div style="position:absolute;top:540px;left:455px; width:100%">' . ($mother->par_cNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:540px;left:530px; width:100%">' . ($mother->par_cMoo ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:562px;left:450px; width:100%">' . ($mother->par_cTambon ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:584px;left:450px; width:100%">' . ($mother->par_cDistrict ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:605px;left:450px; width:100%">' . ($mother->par_cProvince ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:628px;left:480px; width:100%">' . ($mother->par_cPostcode ?? '') . '</div>';

        if (($mother->par_rest ?? '') == 'บ้านตนเอง')
            $html2 .= sprintf($checkMark, 655, 400);
        else if (($mother->par_rest ?? '') == 'เช่าบ้าน')
            $html2 .= sprintf($checkMark, 680, 400);
        else if (($mother->par_rest ?? '') == 'อาศัยผู้อื่น')
            $html2 .= sprintf($checkMark, 705, 400);
        else if (($mother->par_rest ?? '') == 'บ้านพักสวัสดิการ')
            $html2 .= sprintf($checkMark, 730, 400);
        else if (($mother->par_rest ?? '') == 'อื่นๆ')
            $html2 .= sprintf($checkMark, 755, 400);

        $html2 .= '<div style="position:absolute;top:750px;left:480px; width:100%">' . ($mother->par_restOrthor ?? '') . '</div>';

        if (($mother->par_service ?? '') == 'กระทรวง') {
            $html2 .= sprintf($checkMark, 780, 400);
            $html2 .= '<div style="position:absolute;top:775px;left:480px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
        } else if (($mother->par_service ?? '') == 'กรม') {
            $html2 .= sprintf($checkMark, 803, 400);
            $html2 .= '<div style="position:absolute;top:797px;left:450px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
        } else if (($mother->par_service ?? '') == 'กอง') {
            $html2 .= sprintf($checkMark, 828, 400);
            $html2 .= '<div style="position:absolute;top:820px;left:450px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
        } else if (($mother->par_service ?? '') == 'ฝ่าย/แผนก') {
            $html2 .= sprintf($checkMark, 850, 400);
            $html2 .= '<div style="position:absolute;top:845px;left:485px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
        }

        if (($mother->par_claim ?? '') == 'เบิกได้')
            $html2 .= sprintf($checkMark, 875, 400);
        else if (($mother->par_claim ?? '') == 'เบิกไม่ได้')
            $html2 .= sprintf($checkMark, 875, 470);

        // Guardian Data
        $confrimPu = $this->db->table('skjacth_personnel.tb_parent')->where('par_stuID', $studentId)->where('par_relationKey', "ผู้ปกครอง")->get()->getResult();
        $guardian = $confrimPu[0] ?? null;
        $par_decease_Pu = ($guardian->par_decease ?? '0000-00-00') == '0000-00-00' ? "" : $guardian->par_decease;

        $html2 .= '<div style="position:absolute;top:115px;left:600px; width:100%">' . ($guardian->par_prefix ?? '') . ($guardian->par_firstName ?? '') . ' ' . ($guardian->par_lastName ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:133px;left:710px; width:100%">' . ($guardian->par_relation ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:155px;left:675px; width:100%">' . ($guardian->par_ago ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:177px;left:600px; width:100%">' . ($guardian->par_IdNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:205px;left:600px; width:100%">' . ($guardian->par_national ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:230px;left:600px; width:100%">' . ($guardian->par_race ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:255px;left:600px; width:100%">' . ($guardian->par_religion ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:280px;left:600px; width:100%">' . ($guardian->par_career ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:305px;left:600px; width:100%">' . ($guardian->par_education ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:330px;left:600px; width:100%">' . ($guardian->par_salary ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:355px;left:600px; width:100%">' . ($guardian->par_positionJob ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:380px;left:600px; width:100%">' . ($guardian->par_phone ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:405px;left:600px; width:100%">' . $par_decease_Pu . '</div>';

        $html2 .= '<div style="position:absolute;top:428px;left:655px; width:100%">' . ($guardian->par_hNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:428px;left:730px; width:100%">' . ($guardian->par_hMoo ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:450px;left:650px; width:100%">' . ($guardian->par_hTambon ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:472px;left:650px; width:100%">' . ($guardian->par_hDistrict ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:494px;left:650px; width:100%">' . ($guardian->par_hProvince ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:516px;left:680px; width:100%">' . ($guardian->par_hPostcode ?? '') . '</div>';

        $html2 .= '<div style="position:absolute;top:537px;left:655px; width:100%">' . ($guardian->par_cNumber ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:537px;left:730px; width:100%">' . ($guardian->par_cMoo ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:559px;left:650px; width:100%">' . ($guardian->par_cTambon ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:581px;left:650px; width:100%">' . ($guardian->par_cDistrict ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:602px;left:650px; width:100%">' . ($guardian->par_cProvince ?? '') . '</div>';
        $html2 .= '<div style="position:absolute;top:625px;left:680px; width:100%">' . ($guardian->par_cPostcode ?? '') . '</div>';

        if (($guardian->par_rest ?? '') == 'บ้านตนเอง')
            $html2 .= sprintf($checkMark, 655, 600);
        else if (($guardian->par_rest ?? '') == 'เช่าบ้าน')
            $html2 .= sprintf($checkMark, 680, 600);
        else if (($guardian->par_rest ?? '') == 'อาศัยผู้อื่น')
            $html2 .= sprintf($checkMark, 705, 600);
        else if (($guardian->par_rest ?? '') == 'บ้านพักสวัสดิการ')
            $html2 .= sprintf($checkMark, 730, 600);
        else if (($guardian->par_rest ?? '') == 'อื่นๆ')
            $html2 .= sprintf($checkMark, 755, 600);

        $html2 .= '<div style="position:absolute;top:747px;left:680px; width:100%">' . ($guardian->par_restOrthor ?? '') . '</div>';

        if (($guardian->par_service ?? '') == 'กระทรวง') {
            $html2 .= sprintf($checkMark, 777, 600);
            $html2 .= '<div style="position:absolute;top:773px;left:680px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
        } else if (($guardian->par_service ?? '') == 'กรม') {
            $html2 .= sprintf($checkMark, 803, 600);
            $html2 .= '<div style="position:absolute;top:797px;left:650px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
        } else if (($guardian->par_service ?? '') == 'กอง') {
            $html2 .= sprintf($checkMark, 828, 600);
            $html2 .= '<div style="position:absolute;top:820px;left:650px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
        } else if (($guardian->par_service ?? '') == 'ฝ่าย/แผนก') {
            $html2 .= sprintf($checkMark, 850, 600);
            $html2 .= '<div style="position:absolute;top:845px;left:685px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
        }

        $html2 .= '<div style="position:absolute;top:993px;left:230px; width:100%">' . $date_D . '</div>';
        $html2 .= '<div style="position:absolute;top:993px;left:350px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html2 .= '<div style="position:absolute;top:993px;left:540px; width:100%">' . $date_Y . '</div>';

        $mpdf->WriteHTML($html2);
        $mpdf->Output('Reg_' . $filename . '.pdf', 'I');
        exit;
    }

    private function generateSurrenderPDFHtml($student)
    {
        // Deprecated, using direct generation in print()
        return "";
    }
}

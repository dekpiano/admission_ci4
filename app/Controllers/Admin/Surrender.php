<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Surrender extends BaseController
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

    public function index()
    {
        $request = service('request');
        $year = $request->getVar('year') ?? date('Y');
        
        $data['title'] = 'ข้อมูลการรายงานตัว';
        $data['selected_year'] = $year;

        // Fetch Years
        $data['years'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_year')
            ->groupBy('recruit_year')
            ->orderBy('recruit_year', 'DESC')
            ->get()->getResult();

        // Determine selected year
        if (empty($year) || $year == date('Y')) {
            if (!empty($data['years'])) {
                // Default to the latest year in DB if no specific year requested or just default date('Y')
                // Check if date('Y') exists in list?
                // Better: if no GET param, use latest year.
                if (!$request->getVar('year')) {
                    $year = $data['years'][0]->recruit_year;
                }
            } else {
                 // Fallback if no data
                 $year = date('Y') + 543; // Assume Thai year by default for school systems
            }
        }
        $data['selected_year'] = $year;

        // Fetch Students who passed and are ready for surrender (or have surrendered)
        // Note: 'recruit_status' = 'ผ่านการตรวจสอบ' means they passed the initial check.
        // We might want to filter by those who passed the exam too? 
        // For now, following the original logic: recruit_status = "ผ่านการตรวจสอบ"
        
        $builder = $this->db->table('tb_recruitstudent');
        $builder->select('tb_recruitstudent.*, tb_quota.quota_explain, skjacth_personnel.tb_students.stu_UpdateConfirm');
        $builder->join('tb_quota', 'tb_quota.quota_key = tb_recruitstudent.recruit_category', 'left');
        $builder->join('skjacth_personnel.tb_students', 'tb_recruitstudent.recruit_idCard = skjacth_personnel.tb_students.stu_iden', 'left');
        $builder->where('recruit_year', $year);
        // $builder->where('recruit_status', 'ผ่านการตรวจสอบ'); // Show all students as requested
        $builder->orderBy('recruit_id', 'DESC');
        
        $data['students'] = $builder->get()->getResult();

        return view('Admin/PageAdminSurrender/PageAdminSurrenderIndex', $data);
    }

    public function UpdateSurrender()
    {
        $recruit_id = $this->request->getPost('recruit_id');
        $data = ['recruit_statusSurrender' => date('Y-m-d H:i:s')];
        $result = $this->db->table('tb_recruitstudent')->where('recruit_id', $recruit_id)->update($data);
        
        return $this->response->setJSON(['success' => $result]);
    }

    public function print($id)
    {
        // Load mPDF
        $path = dirname(dirname(dirname(dirname(dirname((dirname(__FILE__)))))));
        if (file_exists($path . '/librarie_skj/mpdf/vendor/autoload.php')) {
            require_once $path . '/librarie_skj/mpdf/vendor/autoload.php';
        } else {
            return "mPDF library not found.";
        }

        $customFontDir = $path . '/librarie_skj/vendor/mpdf/mpdf/ttfonts';
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [$customFontDir]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'THSarabun.ttf',
                    'I' => 'THSarabun-Italic.ttf',
                    'B' => 'THSarabun-Bold.ttf',
                    'BI' => 'THSarabun-BoldItalic.ttf',
                ]
            ],
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp'
        ]);

        // Connect to Databases
        $db = \Config\Database::connect();
        $db_pers = \Config\Database::connect('skjpers');

        // Fetch Data
        $builder = $db->table('tb_recruitstudent');
        $builder->select('tb_recruitstudent.*, tb_quota.quota_explain, province.PROVINCE_NAME, district.DISTRICT_NAME, amphur.AMPHUR_NAME');
        $thpa = 'skjacth_thailandpa'; 
        $builder->join($thpa.'.province', 'tb_recruitstudent.recruit_homeProvince = '.$thpa.'.province.PROVINCE_ID', 'left');
        $builder->join($thpa.'.district', 'tb_recruitstudent.recruit_homeSubdistrict = '.$thpa.'.district.DISTRICT_ID', 'left');
        $builder->join($thpa.'.amphur', 'tb_recruitstudent.recruit_homedistrict = '.$thpa.'.amphur.AMPHUR_ID', 'left');
        $builder->join('tb_quota', 'tb_quota.quota_key = tb_recruitstudent.recruit_category', 'left');
        $builder->where('recruit_id', $id);
        $recruit = $builder->get()->getRow();

        if (!$recruit) return "ไม่พบข้อมูลผู้สมัคร";

        // Fetch Confirmation Data (tb_students) using ID Card
        $confirm = $db_pers->table('tb_students')->where('stu_iden', $recruit->recruit_idCard)->get()->getRow();
        
        // Fallback if not found in tb_students, use tb_recruitstudent data
        if (!$confirm) {
            // Create a mock object with necessary fields from recruit data
            $confirm = (object)[
                'stu_iden' => $recruit->recruit_idCard,
                'stu_prefix' => $recruit->recruit_prefix,
                'stu_fristName' => $recruit->recruit_firstName,
                'stu_lastName' => $recruit->recruit_lastName,
                'stu_birthDay' => $recruit->recruit_birthday,
                'stu_regLevel' => 'ม.' . $recruit->recruit_regLevel,
                'stu_phone' => $recruit->recruit_phone,
                'stu_email' => '',
                'stu_birthTambon' => '',
                'stu_birthDistrict' => '',
                'stu_birthProvirce' => '',
                'stu_birthHospital' => '',
                'stu_nationality' => $recruit->recruit_nationality ?? '',
                'stu_race' => $recruit->recruit_race ?? '',
                'stu_religion' => $recruit->recruit_religion ?? '',
                'stu_bloodType' => '',
                'stu_diseaes' => '',
                'stu_numberSibling' => '',
                'stu_firstChild' => '',
                'stu_numberSiblingSkj' => '',
                'stu_nickName' => '',
                'stu_disablde' => '',
                'stu_wieght' => '',
                'stu_hieght' => '',
                'stu_talent' => '',
                'stu_parenalStatus' => '',
                'stu_presentLife' => '',
                'stu_personOther' => '',
                'stu_hCode' => '',
                'stu_hNumber' => $recruit->recruit_homeNumber ?? '',
                'stu_hMoo' => $recruit->recruit_homeMoo ?? '',
                'stu_hRoad' => $recruit->recruit_homeRoad ?? '',
                'stu_hTambon' => $recruit->DISTRICT_NAME ?? '', // Note: DISTRICT_NAME is Tambon in this DB schema usually
                'stu_hDistrict' => $recruit->AMPHUR_NAME ?? '',
                'stu_hProvince' => $recruit->PROVINCE_NAME ?? '',
                'stu_hPostCode' => $recruit->recruit_homePostcode ?? '',
                'stu_natureRoom' => '',
                'stu_farSchool' => '',
                'stu_travel' => '',
                'stu_gradLevel' => '',
                'stu_schoolfrom' => $recruit->recruit_oldSchool ?? '',
                'stu_schoolTambao' => '',
                'stu_schoolDistrict' => '',
                'stu_schoolProvince' => '',
                'stu_usedStudent' => '',
                'stu_inputLevel' => '',
                'stu_phoneUrgent' => '',
                'stu_phoneFriend' => ''
            ];
        }

        // Fetch Parents
        $father = $db_pers->table('tb_parent')->where('par_stuID', $confirm->stu_iden)->where('par_relation', 'บิดา')->get()->getRow();
        $mother = $db_pers->table('tb_parent')->where('par_stuID', $confirm->stu_iden)->where('par_relation', 'มารดา')->get()->getRow();
        $guardian = $db_pers->table('tb_parent')->where('par_stuID', $confirm->stu_iden)->where('par_relationKey', 'ผู้ปกครอง')->get()->getRow();

        // Prepare Variables
        $idstu = str_replace('-', '', $confirm->stu_iden);
        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        
        $date_Y = date('Y') + 543;
        $date_D = (int)date('d');
        $date_M = date('n');

        $date_Y_birt = date('Y', strtotime($confirm->stu_birthDay)) + 543; // Buddhist Year for birth
        $date_D_birt = (int)date('d', strtotime($confirm->stu_birthDay));
        $date_M_birt = date('n', strtotime($confirm->stu_birthDay));

        // Set Template
        if ($recruit->recruit_regLevel >= 4) {
            $mpdf->SetDocTemplate('uploads/confirm/confirmM4All.pdf', true);
        } else {
            $mpdf->SetDocTemplate('uploads/confirm/confirmM1All.pdf', true);
        }

        $mpdf->SetTitle($confirm->stu_prefix . $confirm->stu_fristName . ' ' . $confirm->stu_lastName);

        // --- Page 1: Student Info ---
        $html = '';
        // ID Card
        $positions = [263, 305, 343, 380, 415, 455, 490, 530, 565, 600, 645, 680, 722];
        for ($i = 0; $i < 13; $i++) {
            if (isset($idstu[$i])) {
                $html .= '<div style="position:absolute;top:577px;left:' . $positions[$i] . 'px; width:100%; font-size:1.5rem">' . $idstu[$i] . '</div>';
            }
        }

        $html .= '<div style="position:absolute;top:30px;left:50px; width:100%">เลขที่สมัคร ' . $recruit->recruit_id . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:420px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:475px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:550px; width:100%">' . $date_Y . '</div>';

        $imgUrl = FCPATH . 'uploads/recruitstudent/m' . $recruit->recruit_regLevel . '/img/' . $recruit->recruit_img;
        if (file_exists($imgUrl)) {
             $html .= '<div style="position:absolute;top:75px;left:663px; width:100%"><img style="width: 100px;height:120px;" src="' . $imgUrl . '"></div>';
        }

        $html .= '<div style="position:absolute;top:105px;left:230px; width:100%">' . $confirm->stu_regLevel . '</div>';
        $html .= '<div style="position:absolute;top:105px;left:470px; width:100%">' . $recruit->recruit_year . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:140px; width:100%">' . $confirm->stu_prefix . $confirm->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:400px; width:100%">' . $confirm->stu_lastName . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:120px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:250px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:470px; width:100%">' . $date_Y . '</div>';

        $html .= '<div style="position:absolute;top:243px;left:340px; width:100%">' . $confirm->stu_prefix . $confirm->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:243px;left:530px; width:100%">' . $confirm->stu_lastName . '</div>';

        $html .= '<div style="position:absolute;top:510px;left:250px; width:100%">' . $date_D_birt . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:420px; width:100%">' . $TH_Month[$date_M_birt - 1] . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:650px; width:100%">' . $date_Y_birt . '</div>';
        
        $html .= '<div style="position:absolute;top:531px;left:235px; width:100%">' . $confirm->stu_birthTambon . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:420px; width:100%">' . $confirm->stu_birthDistrict . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:620px; width:100%">' . $confirm->stu_birthProvirce . '</div>';
        $html .= '<div style="position:absolute;top:553px;left:240px; width:100%">' . $confirm->stu_birthHospital . '</div>';

        $html .= '<div style="position:absolute;top:618px;left:160px; width:100%">' . $confirm->stu_nationality . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:330px; width:100%">' . $confirm->stu_race . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:520px; width:100%">' . $confirm->stu_religion . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:680px; width:100%">' . $confirm->stu_bloodType . '</div>';
        $html .= '<div style="position:absolute;top:640px;left:180px; width:100%">' . $confirm->stu_diseaes . '</div>';

        $html .= '<div style="position:absolute;top:668px;left:370px; width:100%">' . $confirm->stu_numberSibling . '</div>';
        $html .= '<div style="position:absolute;top:668px;left:620px; width:100%">' . $confirm->stu_firstChild . '</div>';
        $html .= '<div style="position:absolute;top:690px;left:530px; width:100%">' . $confirm->stu_numberSiblingSkj . '</div>';
        $html .= '<div style="position:absolute;top:690px;left:700px; width:100%">' . $confirm->stu_nickName . '</div>';

        $html .= '<div style="position:absolute;top:710px;left:120px; width:100%">' . $confirm->stu_disablde . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:290px; width:100%">' . $confirm->stu_wieght . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:410px; width:100%">' . $confirm->stu_hieght . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:640px; width:100%">' . $confirm->stu_talent . '</div>';

        // Parental Status Checkboxes
        $checkMark = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>';
        
        $pStatusMap = [
            'อยู่ด้วยกัน' => 178, 'แยกกันอยู่' => 270, 'หย่าร้าง' => 365,
            'บิดาถึงแก่กรรม' => 448, 'มารดาถึงแก่กรรม' => 565, 'บิดาหรือมารดาแต่งงานใหม่' => 178 // Y=765 for this one
        ];
        if (isset($pStatusMap[$confirm->stu_parenalStatus])) {
            $top = ($confirm->stu_parenalStatus == 'บิดาหรือมารดาแต่งงานใหม่') ? 765 : 740;
            $left = $pStatusMap[$confirm->stu_parenalStatus];
            $html .= '<div style="position:absolute;top:' . $top . 'px;left:' . $left . 'px; width:100%">' . $checkMark . '</div>';
        }

        $pLifeMap = ['อยู่กับบิดาและมารดา' => 225, 'อยู่กับบิดาหรือมารดา' => 360, 'บุคคลอื่น' => 510];
        if (isset($pLifeMap[$confirm->stu_presentLife])) {
            $html .= '<div style="position:absolute;top:790px;left:' . $pLifeMap[$confirm->stu_presentLife] . 'px; width:100%">' . $checkMark . '</div>';
        }
        $html .= '<div style="position:absolute;top:787px;left:620px; width:100%">' . $confirm->stu_personOther . '</div>';

        // Address
        $html .= '<div style="position:absolute;top:814px;left:310px; width:100%">' . $confirm->stu_hCode . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:495px; width:100%">' . $confirm->stu_hNumber . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:585px; width:100%">' . $confirm->stu_hMoo . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:665px; width:100%">' . $confirm->stu_hRoad . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:120px; width:100%">' . $confirm->stu_hTambon . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:300px; width:100%">' . $confirm->stu_hDistrict . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:490px; width:100%">' . $confirm->stu_hProvince . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:675px; width:100%">' . $confirm->stu_hPostCode . '</div>';
        $html .= '<div style="position:absolute;top:857px;left:190px; width:100%">' . $confirm->stu_phone . '</div>';
        $html .= '<div style="position:absolute;top:857px;left:450px; width:100%">' . $confirm->stu_email . '</div>';

        // Current Address (if different) - Assuming same logic as original code which repeats some fields
        $html .= '<div style="position:absolute;top:883px;left:340px; width:100%">' . $confirm->stu_hNumber . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:450px; width:100%">' . $confirm->stu_hMoo . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:520px; width:100%">' . $confirm->stu_hRoad . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:660px; width:100%">' . $confirm->stu_hTambon . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:110px; width:100%">' . $confirm->stu_hDistrict . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:300px; width:100%">' . $confirm->stu_hProvince . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:490px; width:100%">' . $confirm->stu_hPostCode . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:640px; width:100%">' . $confirm->stu_phone . '</div>';

        $roomMap = ['บ้านตนเอง' => 130, 'เช่าอยู่' => 227, 'อาศัยผู้อื่นอยู่' => 300, 'บ้านพักราชการ' => 405, 'วัด' => 525, 'หอพัก' => 570];
        if (isset($roomMap[$confirm->stu_natureRoom])) {
            $html .= '<div style="position:absolute;top:935px;left:' . $roomMap[$confirm->stu_natureRoom] . 'px; width:100%">' . $checkMark . '</div>';
        }

        $html .= '<div style="position:absolute;top:950px;left:250px; width:100%">' . $confirm->stu_farSchool . '</div>';
        $html .= '<div style="position:absolute;top:950px;left:470px; width:100%">' . $confirm->stu_travel . '</div>';

        $html .= '<div style="position:absolute;top:977px;left:153px; width:100%">' . $confirm->stu_gradLevel . '</div>';
        $html .= '<div style="position:absolute;top:977px;left:260px; width:100%">' . $confirm->stu_schoolfrom . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:230px; width:100%">' . $confirm->stu_schoolTambao . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:450px; width:100%">' . $confirm->stu_schoolDistrict . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:630px; width:100%">' . $confirm->stu_schoolProvince . '</div>';

        if ($confirm->stu_usedStudent == 'ไม่เคย') {
            $html .= '<div style="position:absolute;top:1030px;left:487px; width:100%">' . $checkMark . '</div>';
        } elseif ($confirm->stu_usedStudent == 'เคย') {
            $html .= '<div style="position:absolute;top:1030px;left:560px; width:100%">' . $checkMark . '</div>';
        }
        $html .= '<div style="position:absolute;top:1026px;left:690px; width:100%">' . $confirm->stu_inputLevel . '</div>';

        $html .= '<div style="position:absolute;top:1055px;left:200px; width:100%">' . $confirm->stu_phoneUrgent . '</div>';
        $html .= '<div style="position:absolute;top:1055px;left:550px; width:100%">' . $confirm->stu_phoneFriend . '</div>';

        $mpdf->WriteHTML($html);

        // --- Page 2: Parent Info ---
        $mpdf->AddPage();
        $html2 = '';

        // Father
        if ($father) {
            $par_decease = ($father->par_decease == '0000-00-00') ? "" : $father->par_decease;
            $html2 .= '<div style="position:absolute;top:129px;left:195px; width:100%">' . $father->par_prefix . $father->par_firstName . ' ' . $father->par_lastName . '</div>';
            $html2 .= '<div style="position:absolute;top:150px;left:275px; width:100%">' . $father->par_ago . '</div>';
            $html2 .= '<div style="position:absolute;top:180px;left:197px; width:100%">' . $father->par_IdNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:205px;left:197px; width:100%">' . $father->par_national . '</div>';
            $html2 .= '<div style="position:absolute;top:230px;left:197px; width:100%">' . $father->par_race . '</div>';
            $html2 .= '<div style="position:absolute;top:255px;left:197px; width:100%">' . $father->par_religion . '</div>';
            $html2 .= '<div style="position:absolute;top:280px;left:197px; width:100%">' . $father->par_career . '</div>';
            $html2 .= '<div style="position:absolute;top:305px;left:197px; width:100%">' . $father->par_education . '</div>';
            $html2 .= '<div style="position:absolute;top:330px;left:197px; width:100%">' . $father->par_salary . '</div>';
            $html2 .= '<div style="position:absolute;top:355px;left:197px; width:100%">' . $father->par_positionJob . '</div>';
            $html2 .= '<div style="position:absolute;top:380px;left:197px; width:100%">' . $father->par_phone . '</div>';
            $html2 .= '<div style="position:absolute;top:405px;left:197px; width:100%">' . $par_decease . '</div>';

            $html2 .= '<div style="position:absolute;top:430px;left:255px; width:100%">' . $father->par_hNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:430px;left:335px; width:100%">' . $father->par_hMoo . '</div>';
            $html2 .= '<div style="position:absolute;top:452px;left:245px; width:100%">' . $father->par_hTambon . '</div>';
            $html2 .= '<div style="position:absolute;top:474px;left:245px; width:100%">' . $father->par_hDistrict . '</div>';
            $html2 .= '<div style="position:absolute;top:496px;left:245px; width:100%">' . $father->par_hProvince . '</div>';
            $html2 .= '<div style="position:absolute;top:518px;left:280px; width:100%">' . $father->par_hPostcode . '</div>';

            $html2 .= '<div style="position:absolute;top:540px;left:255px; width:100%">' . $father->par_cNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:540px;left:335px; width:100%">' . $father->par_cMoo . '</div>';
            $html2 .= '<div style="position:absolute;top:562px;left:245px; width:100%">' . $father->par_cTambon . '</div>';
            $html2 .= '<div style="position:absolute;top:584px;left:245px; width:100%">' . $father->par_cDistrict . '</div>';
            $html2 .= '<div style="position:absolute;top:605px;left:245px; width:100%">' . $father->par_cProvince . '</div>';
            $html2 .= '<div style="position:absolute;top:628px;left:280px; width:100%">' . $father->par_cPostcode . '</div>';
            
            // Father Rest Checkbox
            $fRestMap = ['บ้านตนเอง' => 200, 'เช่าบ้าน' => 200, 'อาศัยผู้อื่น' => 200, 'บ้านพักสวัสดิการ' => 200, 'อื่นๆ' => 200];
            $fRestTop = ['บ้านตนเอง' => 655, 'เช่าบ้าน' => 680, 'อาศัยผู้อื่น' => 705, 'บ้านพักสวัสดิการ' => 730, 'อื่นๆ' => 755];
            if (isset($fRestMap[$father->par_rest])) {
                $html2 .= '<div style="position:absolute;top:' . $fRestTop[$father->par_rest] . 'px;left:200px; width:100%">' . $checkMark . '</div>';
            }
            $html2 .= '<div style="position:absolute;top:750px;left:280px; width:100%">' . $father->par_restOrthor . '</div>';
            
             // Father Service Checkbox
            $fServMap = ['กระทรวง' => 780, 'กรม' => 803, 'กอง' => 828, 'ฝ่าย/แผนก' => 850];
            if (isset($fServMap[$father->par_service])) {
                 $html2 .= '<div style="position:absolute;top:' . $fServMap[$father->par_service] . 'px;left:200px; width:100%">' . $checkMark . '</div>';
                 $topName = $fServMap[$father->par_service] - 5; // Adjust slightly for text
                 $html2 .= '<div style="position:absolute;top:' . $topName . 'px;left:280px; width:100%">' . $father->par_serviceName . '</div>';
            }
            
            if ($father->par_claim == 'เบิกได้') {
                $html2 .= '<div style="position:absolute;top:875px;left:200px; width:100%">' . $checkMark . '</div>';
            } elseif ($father->par_claim == 'เบิกไม่ได้') {
                $html2 .= '<div style="position:absolute;top:875px;left:270px; width:100%">' . $checkMark . '</div>';
            }
        }

        // Mother
        if ($mother) {
            $par_decease_Ma = ($mother->par_decease == '0000-00-00') ? "" : $mother->par_decease;
            $html2 .= '<div style="position:absolute;top:129px;left:400px; width:100%">' . $mother->par_prefix . $mother->par_firstName . ' ' . $mother->par_lastName . '</div>';
            $html2 .= '<div style="position:absolute;top:150px;left:475px; width:100%">' . $mother->par_ago . '</div>';
            $html2 .= '<div style="position:absolute;top:180px;left:400px; width:100%">' . $mother->par_IdNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:205px;left:400px; width:100%">' . $mother->par_national . '</div>';
            $html2 .= '<div style="position:absolute;top:230px;left:400px; width:100%">' . $mother->par_race . '</div>';
            $html2 .= '<div style="position:absolute;top:255px;left:400px; width:100%">' . $mother->par_religion . '</div>';
            $html2 .= '<div style="position:absolute;top:280px;left:400px; width:100%">' . $mother->par_career . '</div>';
            $html2 .= '<div style="position:absolute;top:305px;left:400px; width:100%">' . $mother->par_education . '</div>';
            $html2 .= '<div style="position:absolute;top:330px;left:400px; width:100%">' . $mother->par_salary . '</div>';
            $html2 .= '<div style="position:absolute;top:355px;left:400px; width:100%">' . $mother->par_positionJob . '</div>';
            $html2 .= '<div style="position:absolute;top:380px;left:400px; width:100%">' . $mother->par_phone . '</div>';
            $html2 .= '<div style="position:absolute;top:405px;left:400px; width:100%">' . $par_decease_Ma . '</div>';

            $html2 .= '<div style="position:absolute;top:430px;left:455px; width:100%">' . $mother->par_hNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:430px;left:530px; width:100%">' . $mother->par_hMoo . '</div>';
            $html2 .= '<div style="position:absolute;top:452px;left:450px; width:100%">' . $mother->par_hTambon . '</div>';
            $html2 .= '<div style="position:absolute;top:474px;left:450px; width:100%">' . $mother->par_hDistrict . '</div>';
            $html2 .= '<div style="position:absolute;top:496px;left:450px; width:100%">' . $mother->par_hProvince . '</div>';
            $html2 .= '<div style="position:absolute;top:518px;left:480px; width:100%">' . $mother->par_hPostcode . '</div>';

            $html2 .= '<div style="position:absolute;top:540px;left:455px; width:100%">' . $mother->par_cNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:540px;left:530px; width:100%">' . $mother->par_cMoo . '</div>';
            $html2 .= '<div style="position:absolute;top:562px;left:450px; width:100%">' . $mother->par_cTambon . '</div>';
            $html2 .= '<div style="position:absolute;top:584px;left:450px; width:100%">' . $mother->par_cDistrict . '</div>';
            $html2 .= '<div style="position:absolute;top:605px;left:450px; width:100%">' . $mother->par_cProvince . '</div>';
            $html2 .= '<div style="position:absolute;top:628px;left:480px; width:100%">' . $mother->par_cPostcode . '</div>';
            
             // Mother Rest Checkbox
            $mRestTop = ['บ้านตนเอง' => 655, 'เช่าบ้าน' => 680, 'อาศัยผู้อื่น' => 705, 'บ้านพักสวัสดิการ' => 730, 'อื่นๆ' => 755];
            if (isset($mRestTop[$mother->par_rest])) {
                $html2 .= '<div style="position:absolute;top:' . $mRestTop[$mother->par_rest] . 'px;left:400px; width:100%">' . $checkMark . '</div>';
            }
            $html2 .= '<div style="position:absolute;top:750px;left:480px; width:100%">' . $mother->par_restOrthor . '</div>';

             // Mother Service Checkbox
            $mServMap = ['กระทรวง' => 780, 'กรม' => 803, 'กอง' => 828, 'ฝ่าย/แผนก' => 850];
            if (isset($mServMap[$mother->par_service])) {
                 $html2 .= '<div style="position:absolute;top:' . $mServMap[$mother->par_service] . 'px;left:400px; width:100%">' . $checkMark . '</div>';
                 $topName = $mServMap[$mother->par_service] - 5;
                 $html2 .= '<div style="position:absolute;top:' . $topName . 'px;left:480px; width:100%">' . $mother->par_serviceName . '</div>';
            }
            
            if ($mother->par_claim == 'เบิกได้') {
                $html2 .= '<div style="position:absolute;top:875px;left:400px; width:100%">' . $checkMark . '</div>';
            } elseif ($mother->par_claim == 'เบิกไม่ได้') {
                // $html2 .= '<div style="position:absolute;top:875px;left:470px; width:100%">' . $checkMark . '</div>';
            }
        }

        // Guardian
        if ($guardian) {
            $par_decease_Pu = ($guardian->par_decease == '0000-00-00') ? "" : $guardian->par_decease;
            $html2 .= '<div style="position:absolute;top:115px;left:600px; width:100%">' . $guardian->par_prefix . $guardian->par_firstName . ' ' . $guardian->par_lastName . '</div>';
            $html2 .= '<div style="position:absolute;top:133px;left:710px; width:100%">' . $guardian->par_relation . '</div>';
            $html2 .= '<div style="position:absolute;top:155px;left:675px; width:100%">' . $guardian->par_ago . '</div>';
            $html2 .= '<div style="position:absolute;top:177px;left:600px; width:100%">' . $guardian->par_IdNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:205px;left:600px; width:100%">' . $guardian->par_national . '</div>';
            $html2 .= '<div style="position:absolute;top:230px;left:600px; width:100%">' . $guardian->par_race . '</div>';
            $html2 .= '<div style="position:absolute;top:255px;left:600px; width:100%">' . $guardian->par_religion . '</div>';
            $html2 .= '<div style="position:absolute;top:280px;left:600px; width:100%">' . $guardian->par_career . '</div>';
            $html2 .= '<div style="position:absolute;top:305px;left:600px; width:100%">' . $guardian->par_education . '</div>';
            $html2 .= '<div style="position:absolute;top:330px;left:600px; width:100%">' . $guardian->par_salary . '</div>';
            $html2 .= '<div style="position:absolute;top:355px;left:600px; width:100%">' . $guardian->par_positionJob . '</div>';
            $html2 .= '<div style="position:absolute;top:380px;left:600px; width:100%">' . $guardian->par_phone . '</div>';
            $html2 .= '<div style="position:absolute;top:405px;left:600px; width:100%">' . $par_decease_Pu . '</div>';

            $html2 .= '<div style="position:absolute;top:428px;left:655px; width:100%">' . $guardian->par_hNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:428px;left:730px; width:100%">' . $guardian->par_hMoo . '</div>';
            $html2 .= '<div style="position:absolute;top:450px;left:650px; width:100%">' . $guardian->par_hTambon . '</div>';
            $html2 .= '<div style="position:absolute;top:472px;left:650px; width:100%">' . $guardian->par_hDistrict . '</div>';
            $html2 .= '<div style="position:absolute;top:494px;left:650px; width:100%">' . $guardian->par_hProvince . '</div>';
            $html2 .= '<div style="position:absolute;top:516px;left:680px; width:100%">' . $guardian->par_hPostcode . '</div>';

            $html2 .= '<div style="position:absolute;top:537px;left:655px; width:100%">' . $guardian->par_cNumber . '</div>';
            $html2 .= '<div style="position:absolute;top:537px;left:730px; width:100%">' . $guardian->par_cMoo . '</div>';
            $html2 .= '<div style="position:absolute;top:559px;left:650px; width:100%">' . $guardian->par_cTambon . '</div>';
            $html2 .= '<div style="position:absolute;top:581px;left:650px; width:100%">' . $guardian->par_cDistrict . '</div>';
            $html2 .= '<div style="position:absolute;top:602px;left:650px; width:100%">' . $guardian->par_cProvince . '</div>';
            $html2 .= '<div style="position:absolute;top:625px;left:680px; width:100%">' . $guardian->par_cPostcode . '</div>';

             // Guardian Rest Checkbox
            $gRestTop = ['บ้านตนเอง' => 655, 'เช่าบ้าน' => 680, 'อาศัยผู้อื่น' => 705, 'บ้านพักสวัสดิการ' => 730, 'อื่นๆ' => 755];
            if (isset($gRestTop[$guardian->par_rest])) {
                $html2 .= '<div style="position:absolute;top:' . $gRestTop[$guardian->par_rest] . 'px;left:600px; width:100%">' . $checkMark . '</div>';
            }
            $html2 .= '<div style="position:absolute;top:747px;left:680px; width:100%">' . $guardian->par_restOrthor . '</div>';

             // Guardian Service Checkbox
            $gServMap = ['กระทรวง' => 777, 'กรม' => 803, 'กอง' => 828, 'ฝ่าย/แผนก' => 850];
            if (isset($gServMap[$guardian->par_service])) {
                 $html2 .= '<div style="position:absolute;top:' . $gServMap[$guardian->par_service] . 'px;left:600px; width:100%">' . $checkMark . '</div>';
                 $topName = $gServMap[$guardian->par_service] - 4;
                 $html2 .= '<div style="position:absolute;top:' . $topName . 'px;left:680px; width:100%">' . $guardian->par_serviceName . '</div>';
            }
        }

        $html2 .= '<div style="position:absolute;top:993px;left:230px; width:100%">' . $date_D . '</div>';
        $html2 .= '<div style="position:absolute;top:993px;left:350px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html2 .= '<div style="position:absolute;top:993px;left:540px; width:100%">' . $date_Y . '</div>';

        $mpdf->WriteHTML($html2);
        
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Surrender_Form_' . $id . '.pdf', 'I');
    }

    private function generateSurrenderPDFHtml($student)
    {
        // Deprecated, using direct generation in print()
        return "";
    }
}

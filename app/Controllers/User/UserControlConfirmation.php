<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;
use App\Libraries\Datethai;

class UserControlConfirmation extends BaseController
{
    protected $admissionModel;
    protected $db;
    protected $session;
    protected $datethai;

    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->datethai = new Datethai();
        helper(['url', 'form']);
    }

    /**
     * Displays the login page for confirmation.
     */
    public function login()
    {
        $data['title'] = 'รายงานตัวออนไลน์';
        $data['quotas'] = $this->admissionModel->getAllQuotas();
        $data['systemStatus'] = $this->admissionModel->getSystemStatus();
        return view('User/PageUserConfirmation/Login', $data);
    }

    /**
     * Checks the student's credentials for confirmation.
     */
    public function checkStudent()
    {
        $idCardInput = $this->request->getPost('idenStu');
        $year = $this->admissionModel->getOpenYear()->openyear_year;

        if ($idCardInput) {
            // Handle array if multiple inputs with same name (due to JS appending hidden input)
            if (is_array($idCardInput)) {
                // Usually the last one is the hidden one (plain)
                // But let's just take the first one and clean it ourselves to be safe
                $idCardInput = $idCardInput[0]; 
            }

            $plainId = str_replace('-', '', $idCardInput);
            // Format with dashes: 1-2345-67890-12-3
            $formattedId = '';
            if (strlen($plainId) == 13) {
                $formattedId = substr($plainId, 0, 1) . '-' . substr($plainId, 1, 4) . '-' . substr($plainId, 5, 5) . '-' . substr($plainId, 10, 2) . '-' . substr($plainId, 12, 1);
            } else {
                $formattedId = $idCardInput; // Fallback
            }

            // Check if student exists in recruit table for the current year
            // Check both plain and formatted ID to be safe
            $recruit = $this->db->table('tb_recruitstudent')
                                ->groupStart()
                                    ->where('recruit_idCard', $plainId)
                                    ->orWhere('recruit_idCard', $formattedId)
                                ->groupEnd()
                                ->where('recruit_year', $year)
                                ->get()->getRow();

            if ($recruit) {
                // You might want to check recruit_status here as well
                // if ($recruit->recruit_status != 'ผ่านการตรวจสอบ') { ... }

                // Store the ID that was found (or the plain one, consistent with session usage)
                // Let's store the one from DB to be sure, or just the plain one if we use that for queries
                $this->session->set('confirmation_student_id', $recruit->recruit_idCard);
                return redirect()->to('confirmation/form');
            } else {
                return redirect()->to('confirmation/login')->with('error', 'ไม่พบข้อมูลการสมัคร หรือยังไม่ผ่านการตรวจสอบ');
            }
        } else {
            return redirect()->to('confirmation/login')->with('error', 'กรุณากรอกเลขประจำตัวประชาชน');
        }
    }

    /**
     * Displays the main confirmation form/dashboard.
     */
    public function form()
    {
        if (!$this->session->has('confirmation_student_id')) {
            return redirect()->to('confirmation/login');
        }

        $studentId = $this->session->get('confirmation_student_id');
        $year = $this->admissionModel->getOpenYear()->openyear_year;

        // Fetch Recruit Data
        $recruit = $this->db->table('tb_recruitstudent')
                            ->where('recruit_idCard', $studentId)
                            ->where('recruit_year', $year)
                            ->get()->getResult(); // View expects array of objects for $stu[0]

        if (empty($recruit)) {
            return redirect()->to('confirmation/login')->with('error', 'ไม่พบข้อมูล');
        }

        // Fetch Personnel Data (tb_students)
        $studentPers = $this->db->table('skjacth_personnel.tb_students')
                                ->where('stu_iden', $studentId)
                                ->get()->getResult();
        
        $isStudentSaved = !empty($studentPers);

        // Pre-fill from Recruit Data if Personnel Data is empty
        if (empty($studentPers) && !empty($recruit)) {
            $r = $recruit[0];
            $newStu = new \stdClass();
            $newStu->stu_iden = $r->recruit_idCard;
            $newStu->stu_prefix = $r->recruit_prefix;
            $newStu->stu_fristName = $r->recruit_firstName;
            $newStu->stu_lastName = $r->recruit_lastName;
            $newStu->stu_birthDay = $r->recruit_birthday;
            $newStu->stu_phone = $r->recruit_phone;
            $newStu->stu_race = $r->recruit_race;
            $newStu->stu_nationality = $r->recruit_nationality;
            $newStu->stu_religion = $r->recruit_religion;
            $newStu->stu_hNumber = $r->recruit_homeNumber;
            $newStu->stu_hMoo = $r->recruit_homeGroup;
            $newStu->stu_hRoad = $r->recruit_homeRoad;
            $newStu->stu_hTambon = $r->recruit_homeSubdistrict;
            $newStu->stu_hDistrict = $r->recruit_homedistrict;
            $newStu->stu_hProvince = $r->recruit_homeProvince;
            $newStu->stu_hPostCode = $r->recruit_homePostcode; // Check casing
            $newStu->stu_schoolfrom = $r->recruit_oldSchool;
            $newStu->stu_schoolDistrict = $r->recruit_district;
            $newStu->stu_schoolProvince = $r->recruit_province;
            // Initialize other fields as empty or default to avoid undefined property notices in view
            $newStu->stu_email = '';
            $newStu->stu_birthHospital = '';
            $newStu->stu_birthTambon = '';
            $newStu->stu_birthDistrict = '';
            $newStu->stu_birthProvirce = '';
            $newStu->stu_bloodType = '';
            $newStu->stu_diseaes = '';
            $newStu->stu_disablde = '';
            $newStu->stu_wieght = '';
            $newStu->stu_hieght = '';
            $newStu->stu_talent = '';
            $newStu->stu_numberSibling = '';
            $newStu->stu_firstChild = '';
            $newStu->stu_numberSiblingSkj = '';
            $newStu->stu_parenalStatus = '';
            $newStu->stu_presentLife = '';
            $newStu->stu_personOther = '';
            $newStu->stu_hCode = '';
            $newStu->stu_cNumber = '';
            $newStu->stu_cMoo = '';
            $newStu->stu_cRoad = '';
            $newStu->stu_cTumbao = '';
            $newStu->stu_cDistrict = '';
            $newStu->stu_cProvince = '';
            $newStu->stu_cPostcode = '';
            $newStu->stu_natureRoom = '';
            $newStu->stu_farSchool = '';
            $newStu->stu_travel = '';
            $newStu->stu_gradLevel = '';
            $newStu->stu_schoolTambao = '';
            $newStu->stu_usedStudent = '';
            $newStu->stu_inputLevel = '';
            $newStu->stu_phoneUrgent = '';
            $newStu->stu_phoneFriend = '';
            $newStu->stu_nickName = '';

            $studentPers = [$newStu];
        }

        // Fetch Parent Data
        $parents = $this->db->table('skjacth_personnel.tb_parent')
                            ->where('par_stuID', $studentId)
                            ->get()->getResult();

        $data['title'] = 'กรอกข้อมูลรายงานตัว';
        $data['stu'] = $recruit; // Maps to $stu in view
        
        // Prepare data for forms (using array_values to reindex)
        $data['stuConf'] = $studentPers;
        $data['FatherConf'] = array_values(array_filter($parents, function($p) { return $p->par_relationKey == 'พ่อ'; }));
        $data['MotherConf'] = array_values(array_filter($parents, function($p) { return $p->par_relationKey == 'แม่'; }));
        $data['OtherConf'] = array_values(array_filter($parents, function($p) { return $p->par_relationKey == 'ผู้ปกครอง'; }));

        $data['checkYear'] = [$this->admissionModel->getOpenYear()];
        $data['datethai'] = $this->datethai;
        
        // Flags for print button
        $data['Ckeckstu'] = !empty($studentPers) ? 1 : 0;
        $data['OtherCkeck'] = !empty($parents) ? 1 : 0; // Simplified check

        // Pass flags for "Edit" views
        $data['FatherCkeck'] = !empty($data['FatherConf']) ? 1 : 0;
        $data['MatherCkeck'] = !empty($data['MotherConf']) ? 1 : 0;
        $data['OtherCkeck'] = !empty($data['OtherConf']) ? 1 : 0;
        $data['isStudentSaved'] = $isStudentSaved ? 1 : 0;

        return view('User/PageUserConfirmation/Dashboard', $data);
    }

    /**
     * Saves the confirmation data.
     */
    public function save()
    {
        if (!$this->session->has('confirmation_student_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Session expired. Please login again.']);
        }

        $studentId = $this->session->get('confirmation_student_id');
        $post = $this->request->getPost();

        // Check which form is submitted
        if (isset($post['stu_iden'])) {
            // Student Form
            return $this->saveStudent($post, $studentId);
        } elseif (isset($post['par_relationKey']) && $post['par_relationKey'] == 'พ่อ') {
            // Father Form
            return $this->saveParent($post, $studentId, 'พ่อ');
        } elseif (isset($post['par_relationKeyM']) && $post['par_relationKeyM'] == 'แม่') {
            // Mother Form
            return $this->saveParent($post, $studentId, 'แม่');
        } elseif (isset($post['par_relationKeyO']) && $post['par_relationKeyO'] == 'ผู้ปกครอง') {
            // Guardian Form
            return $this->saveParent($post, $studentId, 'ผู้ปกครอง');
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid form data.']);
    }

    private function saveStudent($data, $studentId)
    {
        // Keep original data for saving
        $saveData = $data;

        // Clean data for validation
        $data['stu_iden'] = str_replace('-', '', $data['stu_iden']);
        $data['stu_phone'] = str_replace('-', '', $data['stu_phone']);
        $data['stu_phoneUrgent'] = str_replace('-', '', $data['stu_phoneUrgent']);
        $data['stu_phoneFriend'] = str_replace('-', '', $data['stu_phoneFriend']);
        
        // Construct birthdate for validation
        $birthDate = ($data['stu_year'] - 543) . '-' . sprintf('%02d', $data['stu_month']) . '-' . sprintf('%02d', $data['stu_day']);
        $data['stu_birthDay'] = $birthDate;

        // Validation Rules
        $rules = [
            'stu_iden' => 'required|numeric|exact_length[13]',
            'stu_prefix' => 'required',
            'stu_fristName' => 'required',
            'stu_lastName' => 'required',
            'stu_phone' => 'required',
            'stu_birthDay' => 'valid_date',
            'stu_year' => 'required|numeric',
            'stu_month' => 'required|numeric',
            'stu_day' => 'required|numeric',
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode(', ', $validation->getErrors())]);
        }

        // Prepare data for tb_students using ORIGINAL formatted data ($saveData) where appropriate
        // But use constructed birthDate
        $studentData = [
            'stu_UpdateConfirm' => $this->admissionModel->getOpenYear()->openyear_year,
            'stu_iden' => $saveData['stu_iden'], // Save with dashes
            'stu_prefix' => $saveData['stu_prefix'],
            'stu_fristName' => $saveData['stu_fristName'],
            'stu_lastName' => $saveData['stu_lastName'],
            'stu_nickName' => $saveData['stu_nickName'],
            'stu_birthDay' => $birthDate,
            'stu_phone' => $saveData['stu_phone'], // Save with dashes
            'stu_email' => $saveData['stu_email'],
            'stu_birthHospital' => $saveData['stu_birthHospital'],
            'stu_birthTambon' => $saveData['stu_birthTambon'],
            'stu_birthDistrict' => $saveData['stu_birthDistrict'],
            'stu_birthProvirce' => $saveData['stu_birthProvirce'],
            'stu_nationality' => $saveData['stu_nationality'],
            'stu_race' => $saveData['stu_race'],
            'stu_religion' => $saveData['stu_religion'],
            'stu_bloodType' => $saveData['stu_bloodType'],
            'stu_diseaes' => $saveData['stu_diseaes'],
            'stu_disablde' => $saveData['stu_disablde'],
            'stu_wieght' => $saveData['stu_wieght'],
            'stu_hieght' => $saveData['stu_hieght'],
            'stu_talent' => $saveData['stu_talent'],
            'stu_numberSibling' => $saveData['stu_numberSibling'],
            'stu_firstChild' => $saveData['stu_firstChild'],
            'stu_numberSiblingSkj' => $saveData['stu_numberSiblingSkj'],
            'stu_parenalStatus' => $saveData['stu_parenalStatus'],
            'stu_presentLife' => $saveData['stu_presentLife'],
            'stu_personOther' => $saveData['stu_personOther'] ?? '',
            'stu_hCode' => $saveData['stu_hCode'],
            'stu_hNumber' => $saveData['stu_hNumber'],
            'stu_hMoo' => $saveData['stu_hMoo'],
            'stu_hRoad' => $saveData['stu_hRoad'],
            'stu_hTambon' => $saveData['stu_hTambon'],
            'stu_hDistrict' => $saveData['stu_hDistrict'],
            'stu_hProvince' => $saveData['stu_hProvince'],
            'stu_hPostCode' => $saveData['stu_hPostCode'], 
            'stu_cNumber' => $saveData['stu_cNumber'],
            'stu_cMoo' => $saveData['stu_cMoo'],
            'stu_cRoad' => $saveData['stu_cRoad'],
            'stu_cTumbao' => $saveData['stu_cTumbao'],
            'stu_cDistrict' => $saveData['stu_cDistrict'],
            'stu_cProvince' => $saveData['stu_cProvince'],
            'stu_cPostcode' => $saveData['stu_cPostcode'],
            'stu_natureRoom' => $saveData['stu_natureRoom'],
            'stu_farSchool' => $saveData['stu_farSchool'],
            'stu_travel' => $saveData['stu_travel'],
            'stu_gradLevel' => $saveData['stu_gradLevel'],
            'stu_schoolfrom' => $saveData['stu_schoolfrom'],
            'stu_schoolTambao' => $saveData['stu_schoolTambao'],
            'stu_schoolDistrict' => $saveData['stu_schoolDistrict'],
            'stu_schoolProvince' => $saveData['stu_schoolProvince'],
            'stu_usedStudent' => $saveData['stu_usedStudent'],
            'stu_inputLevel' => $saveData['stu_inputLevel'] ?? '',
            'stu_phoneUrgent' => $saveData['stu_phoneUrgent'], // Save with dashes
            'stu_phoneFriend' => $saveData['stu_phoneFriend'], // Save with dashes
        ];

        // Check if student exists (using ID with dashes as per session/DB convention)
        $existing = $this->db->table('skjacth_personnel.tb_students')->where('stu_iden', $studentId)->get()->getRow();

        if ($existing) {
            $this->db->table('skjacth_personnel.tb_students')->where('stu_iden', $studentId)->update($studentData);
        } else {
            $this->db->table('skjacth_personnel.tb_students')->insert($studentData);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกข้อมูลนักเรียนเรียบร้อยแล้ว']);
    }

    private function saveParent($data, $studentId, $relationKey)
    {
        // Keep original data
        $saveData = $data;

        // Map fields based on relation key
        $suffix = '';
        if ($relationKey == 'แม่') $suffix = 'M';
        if ($relationKey == 'ผู้ปกครอง') $suffix = 'O';

        // Clean data for validation
        $data['par_IdNumber' . $suffix] = str_replace('-', '', $data['par_IdNumber' . $suffix] ?? '');
        $data['par_phone' . $suffix] = str_replace('-', '', $data['par_phone' . $suffix] ?? '');

        // Validation Rules
        $rules = [
            'par_prefix' . $suffix => 'required',
            'par_firstName' . $suffix => 'required',
            'par_lastName' . $suffix => 'required',
            'par_IdNumber' . $suffix => 'required|numeric|exact_length[13]',
            'par_phone' . $suffix => 'required',
            'par_hNumber' . $suffix => 'required',
            'par_hTambon' . $suffix => 'required',
            'par_hDistrict' . $suffix => 'required',
            'par_hProvince' . $suffix => 'required',
            'par_hPostcode' . $suffix => 'required',
        ];

        // Adjust rule keys to match input names for proper error reporting
        // Actually, validation runs on $data keys.
        // We need to set rules using the keys present in $data.
        
        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode(', ', $validation->getErrors())]);
        }

        $parentData = [
            'par_stuID' => $studentId,
            'par_relationKey' => $relationKey,
            'par_relation' => $saveData['par_relation' . $suffix] ?? $relationKey,
            'par_prefix' => $saveData['par_prefix' . $suffix],
            'par_firstName' => $saveData['par_firstName' . $suffix],
            'par_lastName' => $saveData['par_lastName' . $suffix],
            'par_ago' => $saveData['par_ago' . $suffix],
            'par_IdNumber' => $saveData['par_IdNumber' . $suffix], // Save with dashes
            'par_phone' => $saveData['par_phone' . $suffix],       // Save with dashes
            'par_race' => $saveData['par_race' . $suffix],
            'par_national' => $saveData['par_national' . $suffix],
            'par_religion' => $saveData['par_religion' . $suffix],
            'par_career' => $saveData['par_career' . $suffix],
            'par_education' => $saveData['par_education' . $suffix],
            'par_salary' => $saveData['par_salary' . $suffix],
            'par_positionJob' => $saveData['par_positionJob' . $suffix],
            'par_decease' => $saveData['par_decease' . $suffix] ?? null,
            'par_hNumber' => $saveData['par_hNumber' . $suffix],
            'par_hMoo' => $saveData['par_hMoo' . $suffix],
            'par_hTambon' => $saveData['par_hTambon' . $suffix],
            'par_hDistrict' => $saveData['par_hDistrict' . $suffix],
            'par_hProvince' => $saveData['par_hProvince' . $suffix],
            'par_hPostcode' => $saveData['par_hPostcode' . $suffix],
            'par_cNumber' => $saveData['par_cNumber' . $suffix],
            'par_cMoo' => $saveData['par_cMoo' . $suffix],
            'par_cTambon' => $saveData['par_cTambon' . $suffix],
            'par_cDistrict' => $saveData['par_cDistrict' . $suffix],
            'par_cProvince' => $saveData['par_cProvince' . $suffix],
            'par_cPostcode' => $saveData['par_cPostcode' . $suffix],
            'par_rest' => $saveData['par_rest' . $suffix] ?? '',
            'par_restOrthor' => $saveData['par_restOrthor' . $suffix] ?? '',
            'par_service' => $saveData['par_service' . $suffix] ?? '',
            // Handle serviceName array (take first non-empty element if array)
            'par_serviceName' => is_array($saveData['par_serviceName' . $suffix] ?? '') ? (reset(array_filter($saveData['par_serviceName' . $suffix] ?? [], function($v) { return trim($v) !== ''; })) ?: '') : ($saveData['par_serviceName' . $suffix] ?? ''),
            'par_claim' => $saveData['par_claim' . $suffix] ?? '',
        ];

        // Check if parent record exists for this student and relation
        $existing = $this->db->table('skjacth_personnel.tb_parent')
                             ->where('par_stuID', $studentId)
                             ->where('par_relationKey', $relationKey)
                             ->get()->getRow();

        if ($existing) {
            $this->db->table('skjacth_personnel.tb_parent')
                     ->where('par_id', $existing->par_id)
                     ->update($parentData);
        } else {
            $this->db->table('skjacth_personnel.tb_parent')->insert($parentData);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกข้อมูล' . $relationKey . 'เรียบร้อยแล้ว']);
    }

    /**
     * Logs out the user.
     */
    public function logout()
    {
        $this->session->remove('confirmation_student_id');
        return redirect()->to('confirmation/login');
    }

    public function pdf()
    {
        if (!$this->session->has('confirmation_student_id')) {
            return redirect()->to('confirmation/login');
        }

        $studentId = $this->session->get('confirmation_student_id');
        
        // Load mPDF
        require_once ROOTPATH . 'librarie_skj/mpdf/vendor/autoload.php';

        // Fetch Data
        $checkYear = $this->db->table('tb_openyear')->get()->getRow();
        $Year = $checkYear->openyear_year;

        $recruit = $this->db->table('tb_recruitstudent')
                            ->where('recruit_idCard', $studentId)
                            ->where('recruit_year', $Year)
                            ->get()->getResult();

        $confrim = $this->db->table('skjacth_personnel.tb_students')
                            ->where('stu_iden', $studentId)
                            ->get()->getResult();

        if (empty($recruit) || empty($confrim)) {
            return "Data not found";
        }

        $idstu = str_replace('-', '', $confrim[0]->stu_iden); // Split 13 digits

        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        
        $date_Y = date('Y') + 543;
        $date_D = (int)date('d');
        $date_M = date('n');

        $date_Y_birt = date('Y', strtotime($confrim[0]->stu_birthDay)) + 543;
        $date_D_birt = (int)date('d', strtotime($confrim[0]->stu_birthDay));
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

        $html .= '<div style="position:absolute;top:75px;left:663px; width:100%"><img style="width: 100px;height:130px;" src="' . FCPATH . 'uploads/recruitstudent/m' . $recruit[0]->recruit_regLevel . '/img/' . $recruit[0]->recruit_img . '"></div>';
        
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

        $checkMark = '<div style="position:absolute;top:%dpx;left:%dpx; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
        
        if ($confrim[0]->stu_parenalStatus == 'อยู่ด้วยกัน') $html .= sprintf($checkMark, 740, 178);
        else if ($confrim[0]->stu_parenalStatus == 'แยกกันอยู่') $html .= sprintf($checkMark, 740, 270);
        else if ($confrim[0]->stu_parenalStatus == 'หย่าร้าง') $html .= sprintf($checkMark, 740, 365);
        else if ($confrim[0]->stu_parenalStatus == 'บิดาถึงแก่กรรม') $html .= sprintf($checkMark, 740, 448);
        else if ($confrim[0]->stu_parenalStatus == 'มารดาถึงแก่กรรม') $html .= sprintf($checkMark, 740, 565);
        else if ($confrim[0]->stu_parenalStatus == 'บิดาหรือมารดาแต่งงานใหม่') $html .= sprintf($checkMark, 765, 178);

        if ($confrim[0]->stu_presentLife == 'อยู่กับบิดาและมารดา') $html .= sprintf($checkMark, 790, 225);
        else if ($confrim[0]->stu_presentLife == 'อยู่กับบิดาหรือมารดา') $html .= sprintf($checkMark, 790, 360);
        else if ($confrim[0]->stu_presentLife == 'บุคคลอื่น') $html .= sprintf($checkMark, 790, 510);
        
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
        $html .= '<div style="position:absolute;top:883px;left:660px; width:100%">' . $confrim[0]->stu_cTambon . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:110px; width:100%">' . $confrim[0]->stu_cDistrict . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:300px; width:100%">' . $confrim[0]->stu_cProvince . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:490px; width:100%">' . $confrim[0]->stu_cPostcode . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:640px; width:100%">' . $confrim[0]->stu_phone . '</div>';

        if ($confrim[0]->stu_natureRoom == 'บ้านตนเอง') $html .= sprintf($checkMark, 935, 130);
        else if ($confrim[0]->stu_natureRoom == 'เช่าอยู่') $html .= sprintf($checkMark, 935, 227);
        else if ($confrim[0]->stu_natureRoom == 'อาศัยผู้อื่นอยู่') $html .= sprintf($checkMark, 935, 300);
        else if ($confrim[0]->stu_natureRoom == 'บ้านพักราชการ') $html .= sprintf($checkMark, 935, 405);
        else if ($confrim[0]->stu_natureRoom == 'วัด') $html .= sprintf($checkMark, 935, 525);
        else if ($confrim[0]->stu_natureRoom == 'หอพัก') $html .= sprintf($checkMark, 935, 570);

        $html .= '<div style="position:absolute;top:950px;left:250px; width:100%">' . $confrim[0]->stu_farSchool . '</div>';
        $html .= '<div style="position:absolute;top:950px;left:470px; width:100%">' . $confrim[0]->stu_travel . '</div>';

        $html .= '<div style="position:absolute;top:977px;left:153px; width:100%">' . $confrim[0]->stu_gradLevel . '</div>';
        $html .= '<div style="position:absolute;top:977px;left:260px; width:100%">' . $confrim[0]->stu_schoolfrom . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:230px; width:100%">' . $confrim[0]->stu_schoolTambao . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:450px; width:100%">' . $confrim[0]->stu_schoolDistrict . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:630px; width:100%">' . $confrim[0]->stu_schoolProvince . '</div>';

        if ($confrim[0]->stu_usedStudent == 'ไม่เคย') $html .= sprintf($checkMark, 1030, 487);
        else if ($confrim[0]->stu_usedStudent == 'เคย') $html .= sprintf($checkMark, 1030, 560);
        
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

        if (($father->par_rest ?? '') == 'บ้านตนเอง') $html2 .= sprintf($checkMark, 655, 200);
        else if (($father->par_rest ?? '') == 'เช่าบ้าน') $html2 .= sprintf($checkMark, 680, 200);
        else if (($father->par_rest ?? '') == 'อาศัยผู้อื่น') $html2 .= sprintf($checkMark, 705, 200);
        else if (($father->par_rest ?? '') == 'บ้านพักสวัสดิการ') $html2 .= sprintf($checkMark, 730, 200);
        else if (($father->par_rest ?? '') == 'อื่นๆ') $html2 .= sprintf($checkMark, 755, 200);

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

        if (($father->par_claim ?? '') == 'เบิกได้') $html2 .= sprintf($checkMark, 875, 200);
        else if (($father->par_claim ?? '') == 'เบิกไม่ได้') $html2 .= sprintf($checkMark, 875, 270);

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

        if (($mother->par_rest ?? '') == 'บ้านตนเอง') $html2 .= sprintf($checkMark, 655, 400);
        else if (($mother->par_rest ?? '') == 'เช่าบ้าน') $html2 .= sprintf($checkMark, 680, 400);
        else if (($mother->par_rest ?? '') == 'อาศัยผู้อื่น') $html2 .= sprintf($checkMark, 705, 400);
        else if (($mother->par_rest ?? '') == 'บ้านพักสวัสดิการ') $html2 .= sprintf($checkMark, 730, 400);
        else if (($mother->par_rest ?? '') == 'อื่นๆ') $html2 .= sprintf($checkMark, 755, 400);

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

        if (($mother->par_claim ?? '') == 'เบิกได้') $html2 .= sprintf($checkMark, 875, 400);
        // else if (($mother->par_claim ?? '') == 'เบิกไม่ได้') $html2 .= sprintf($checkMark, 875, 470);

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

        if (($guardian->par_rest ?? '') == 'บ้านตนเอง') $html2 .= sprintf($checkMark, 655, 600);
        else if (($guardian->par_rest ?? '') == 'เช่าบ้าน') $html2 .= sprintf($checkMark, 680, 600);
        else if (($guardian->par_rest ?? '') == 'อาศัยผู้อื่น') $html2 .= sprintf($checkMark, 705, 600);
        else if (($guardian->par_rest ?? '') == 'บ้านพักสวัสดิการ') $html2 .= sprintf($checkMark, 730, 600);
        else if (($guardian->par_rest ?? '') == 'อื่นๆ') $html2 .= sprintf($checkMark, 755, 600);

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
}


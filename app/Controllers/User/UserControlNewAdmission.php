<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;
use App\Libraries\Timeago;
use App\Libraries\Datethai;
use App\Libraries\RemoteUpload;

class UserControlNewAdmission extends BaseController
{
    protected $admissionModel;
    protected $db;
    protected $session;
    protected $timeago;
    protected $datethai;

    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->timeago = new Timeago();
        $this->datethai = new Datethai();
        helper(['url', 'form']);
    }

    public function index()
    {
        $data['title'] = "ระบบรับสมัครนักเรียนออนไลน์";
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['systemStatus'] = $this->admissionModel->getSystemStatus();
        $data['quotas'] = $this->admissionModel->getAllQuotas();
        $data['courses'] = $this->admissionModel->getAllCourses();
        $data['datethai'] = $this->datethai; // Pass Datethai library to view

        $data['schedules'] = $this->admissionModel->getAdmissionSchedule($data['checkYear']->openyear_year);
        $data['stats'] = $this->admissionModel->getAdmissionStats($data['checkYear']->openyear_year);
        $data['contact_info'] = $this->getContactInfo();

        return view('User/UserHome', $data);
    }

    private function getContactInfo()
    {
        return [
            'school_name' => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์',
            'address' => '160 หมู่ 1 ตำบลนครสวรรค์ออก อำเภอเมืองนครสวรรค์ จังหวัดนครสวรรค์ 60000',
            'phone' => '056-009-667',
            'phone2' => 'หัวหน้างานรับนักเรียน ครูณัฏฐิกานต์ แสงอุทัย 09-2189-9145',
            'email' => 'skjschool@gmail.com',
            'line_id' => '@514kixba',
            'facebook' => 'https://www.facebook.com/SKJNS160',
            'website' => 'https://skj.ac.th',
            'office_hours' => 'จันทร์ - ศุกร์ 08:30 - 16:30 น.',
        ];
    }

    public function pre_check($level = null)
    {
        if (!$level) {
            return redirect()->to('new-admission');
        }

        // Security Check: System Status & Time
        $systemStatus = $this->admissionModel->getSystemStatus();
        $currentTime = time();
        $openTime = isset($systemStatus->onoff_datetime_regis_open) ? strtotime($systemStatus->onoff_datetime_regis_open) : 0;
        $closeTime = isset($systemStatus->onoff_datetime_regis_close) ? strtotime($systemStatus->onoff_datetime_regis_close) : 0;

        if (
            ($systemStatus->onoff_regis != 'on') ||
            ($openTime > 0 && $currentTime < $openTime) ||
            ($closeTime > 0 && $currentTime > $closeTime)
        ) {
            return redirect()->to('new-admission')->with('error', 'ระบบปิดรับสมัคร หรือไม่ได้อยู่ในช่วงเวลาการรับสมัคร');
        }

        $data['title'] = "ตรวจสอบสิทธิ์การสมัคร " . ($level == 1 ? "ม.1" : "ม.4");
        $data['level'] = $level;
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['quotas'] = $this->admissionModel->getAllQuotas(); // Add quotas for menu generation
        $data['systemStatus'] = $systemStatus; // Pass system status

        return view('User/UserPreCheck', $data);
    }

    public function check_id_card()
    {
        $idCard = $this->request->getPost('recruit_idCard');
        $level = $this->request->getPost('level');

        // Basic Validation
        if (empty($idCard)) {
            return redirect()->back()->with('error', 'กรุณากรอกเลขบัตรประชาชน');
        }

        // Check if ID card already exists in tb_recruitstudent
        $existingStudent = $this->admissionModel->checkIdCard($idCard);

        if ($existingStudent) {
            return redirect()->back()->with('error', 'เลขบัตรประชาชนนี้ได้ทำการสมัครไปแล้ว');
        }

        // Pass ID card to the registration form (via session or view data)
        // For security, using session flashdata is better than URL parameters
        $this->session->setFlashdata('pre_check_idCard', $idCard);

        return redirect()->to(base_url('new-admission/register/' . $level));
    }

    public function register($level = null)
    {
        if (!$level) {
            return redirect()->to('new-admission');
        }

        // Check if ID Card is passed from pre-check
        $preCheckIdCard = $this->session->getFlashdata('pre_check_idCard');

        // If no ID Card in session (direct access), redirect to pre-check
        if (!$preCheckIdCard) {
            return redirect()->to('new-admission/pre-check/' . $level);
        }

        $data['title'] = "สมัครเรียน " . ($level == 1 ? "ม.1" : "ม.4");
        $data['level'] = $level;
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['quotas'] = $this->admissionModel->getAllQuotas(); // Filter in view or here
        $data['preCheckIdCard'] = $preCheckIdCard; // Pass to view
        $data['preCheckQuota'] = $this->session->getFlashdata('pre_check_quota');
        $data['preCheckOldSchool'] = $this->session->getFlashdata('pre_check_oldSchool');
        $data['preCheckDistrict'] = $this->session->getFlashdata('pre_check_district');
        $data['preCheckProvince'] = $this->session->getFlashdata('pre_check_province');
        $data['systemStatus'] = $this->admissionModel->getSystemStatus(); // Pass system status

        // Get courses based on level
        $gradeLevel = ($level == 1) ? 'ม.ต้น' : 'ม.ปลาย';
        $data['courses'] = $this->admissionModel->getCoursesByGradeLevel($gradeLevel);

        // Generate CAPTCHA
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $captchaAnswer = $num1 + $num2;
        $this->session->set('captcha_answer', $captchaAnswer);
        $data['captcha_num1'] = $num1;
        $data['captcha_num2'] = $num2;

        // Debug logging
        log_message('debug', 'CAPTCHA Generated - Num1: ' . $num1 . ', Num2: ' . $num2 . ', Answer: ' . $captchaAnswer . ', Session ID: ' . session_id());

        return view('User/UserRegister', $data);
    }

    public function ajax_school_search()
    {
        $searchTerm = $this->request->getVar('q'); // select2 sends 'q' for search term
        $isServiceArea = $this->request->getVar('is_service_area');

        if ($isServiceArea === 'true') {
            $response = $this->admissionModel->getServiceAreaSchools($searchTerm);
        } else {
            // The getSchool method already exists in AdmissionModel
            $response = $this->admissionModel->getSchool(['search' => $searchTerm]);
        }

        // Re-format for Select2.js, which expects 'id' and 'text' keys
        $select2_data = [];
        foreach ($response as $item) {
            $select2_data[] = [
                'id' => $item['value'],      // schoola_id
                'text' => $item['label'],    // schoola_name
                'amphur' => $item['amphur'],  // schoola_amphur
                'province' => $item['province'] // schoola_province
            ];
        }

        return $this->response->setJSON(['results' => $select2_data]);
    }

    public function status()
    {
        $data['title'] = "ตรวจสอบสถานะการสมัคร";
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['quotas'] = $this->admissionModel->getAllQuotas(); // Add quotas for menu generation
        $data['systemStatus'] = $this->admissionModel->getSystemStatus(); // Pass system status
        return view('User/UserStatus', $data);
    }

    public function statistics()
    {
        $checkYear = $this->admissionModel->getOpenYear();
        $year = $checkYear->openyear_year;

        $data['title'] = "สถิติการรับสมัครปีการศึกษา " . $year;
        $data['checkYear'] = $checkYear;
        $data['systemStatus'] = $this->admissionModel->getSystemStatus();
        $data['quotas'] = $this->admissionModel->getAllQuotas();
        $data['stats'] = $this->admissionModel->getAdmissionStats($year);
        $data['dailyStats'] = $this->admissionModel->getDailyStats($year);
        $data['statusByLevel'] = $this->admissionModel->getStatsByLevelAndStatus($year);
        $data['datethai'] = $this->datethai;

        return view('User/UserStatistics', $data);
    }

    /**
     * Refresh CAPTCHA via AJAX
     * Updates the session with new CAPTCHA answer
     */
    public function refresh_captcha()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $num1 = $this->request->getPost('num1');
        $num2 = $this->request->getPost('num2');

        if ($num1 !== null && $num2 !== null) {
            $captchaAnswer = intval($num1) + intval($num2);
            $this->session->set('captcha_answer', $captchaAnswer);

            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid parameters']);
    }

    public function save_register()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid Request');
        }

        $post = $this->request->getPost();

        // CAPTCHA Validation
        $captchaAnswer = $this->session->get('captcha_answer');
        $userCaptcha = isset($post['captcha_answer']) ? trim($post['captcha_answer']) : '';

        // Debug logging (can be removed in production)
        log_message('debug', 'CAPTCHA Check - Session Answer: ' . var_export($captchaAnswer, true) . ', User Answer: ' . var_export($userCaptcha, true) . ', Session ID: ' . session_id());

        // Check if CAPTCHA answer exists in session
        if ($captchaAnswer === null || $captchaAnswer === '') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เซสชันหมดอายุ กรุณารีเฟรชหน้าและลองใหม่อีกครั้ง'
            ]);
        }

        // Check if user provided an answer
        if ($userCaptcha === '' || !is_numeric($userCaptcha)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกคำตอบรหัสยืนยัน (CAPTCHA)'
            ]);
        }

        // Compare answers (cast both to integer)
        if ((int) $userCaptcha !== (int) $captchaAnswer) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'คำตอบรหัสยืนยันไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง (คำตอบที่กรอก: ' . $userCaptcha . ')'
            ]);
        }

        // Clear CAPTCHA session after successful validation
        $this->session->remove('captcha_answer');

        // Basic Validation
        if (
            !$this->validate([
                'recruit_idCard' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'กรุณากรอกเลขบัตรประชาชน',
                    ]
                ],
                'recruit_firstName' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'กรุณากรอกชื่อ',
                    ]
                ],
                'recruit_lastName' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'กรุณากรอกนามสกุล',
                    ]
                ],
                'recruit_category' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'กรุณาเลือกประเภทโควตา',
                    ]
                ]
            ])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $systemStatus = $this->admissionModel->getSystemStatus();
        $year = $this->admissionModel->getOpenYear()->openyear_year;

        // Check duplicate
        if ($this->admissionModel->isIdCardRegistered($post['recruit_idCard'], $year)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เลขบัตรประชาชนนี้ได้ทำการสมัครไปแล้ว กรุณาตรวจสอบสถานะ'
            ]);
        }

        // Global Encoding Fix & Input Sanitization
        header('Content-Type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');
        
        // Sanitize every input and detect encoding more carefully
        foreach ($post as $key => $value) {
            if (is_string($value)) {
                // Simplified sanitization that won't throw ValueErrors
                // We convert from 'auto' which handles common encodings, or fallback to UTF-8
                $post[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
            }
        }
        
        // Robust MAX ID fetch directly from DB to prevent collisions
        $db = \Config\Database::connect();
        $builder = $db->table('tb_recruitstudent');
        $maxIdRow = $builder->selectMax('recruit_id')->get()->getRow();
        $lastId = $maxIdRow ? (int)$maxIdRow->recruit_id : 0;
        
        $openyear = $this->admissionModel->getOpenYear();
        $prefix = (int)$openyear->openyear_year;
        
        if (strpos((string)$lastId, (string)$prefix) === 0) {
            $recruit_id = (string)($lastId + 1);
        } else {
            $recruit_id = $prefix . "0001";
        }
        
        // Double check if this ID exists (just in case)
        while ($db->table('tb_recruitstudent')->where('recruit_id', $recruit_id)->countAllResults() > 0) {
            $recruit_id = (string)((int)$recruit_id + 1);
        }

        log_message('debug', 'Admission: Using final recruit_id: ' . $recruit_id . ' | Name: ' . ($post['recruit_firstName'] ?? 'N/A'));

        // Handle Birthday
        $recruit_birthday = ($post['recruit_birthdayY'] - 543) . '-' . $post['recruit_birthdayM'] . '-' . $post['recruit_birthdayD'];

        // Reprepare values from sanitized inputs
        $courseDetails1 = $this->admissionModel->getCourseDetails($post['recruit_tpyeRoom1']);
        $course_fullname = $courseDetails1 ? $courseDetails1->course_fullname : '';
        $course_branch = $courseDetails1 ? $courseDetails1->course_branch : '';
        
        $ranks = [];
        if (!empty($post['recruit_tpyeRoom1'])) $ranks[] = $post['recruit_tpyeRoom1'];
        if (!empty($post['recruit_tpyeRoom2'])) $ranks[] = $post['recruit_tpyeRoom2'];
        if (!empty($post['recruit_tpyeRoom3'])) $ranks[] = $post['recruit_tpyeRoom3'];
        $majorOrder = implode('|', $ranks);


        // Prepare Data
        $data_insert = [
            'recruit_id' => $recruit_id,
            'recruit_year' => $year,
            'recruit_regLevel' => $post['recruit_regLevel'],
            'recruit_prefix' => $post['recruit_prefix'],
            'recruit_firstName' => $post['recruit_firstName'],
            'recruit_lastName' => $post['recruit_lastName'],
            'recruit_idCard' => str_replace('-', '', $post['recruit_idCard']),
            'recruit_birthday' => $recruit_birthday,
            'recruit_race' => $post['recruit_race'],
            'recruit_nationality' => $post['recruit_nationality'],
            'recruit_religion' => $post['recruit_religion'],
            'recruit_phone' => str_replace('-', '', $post['recruit_phone']),
            'recruit_homeNumber' => $post['recruit_homeNumber'],
            'recruit_homeGroup' => $post['recruit_homeGroup'],
            'recruit_homeRoad' => $post['recruit_homeRoad'],
            'recruit_homeSubdistrict' => $post['recruit_homeSubdistrict'],
            'recruit_homedistrict' => $post['recruit_homedistrict'],
            'recruit_homeProvince' => $post['recruit_homeProvince'],
            'recruit_homePostcode' => $post['recruit_homePostcode'],
            'recruit_oldSchool' => $post['recruit_oldSchool'],
            'recruit_district' => $post['recruit_district'],
            'recruit_province' => $post['recruit_province'],
            'recruit_grade' => $post['recruit_grade'],
            'recruit_category' => $post['recruit_category'],
            'recruit_tpyeRoom' => $course_fullname,
            'recruit_tpyeRoom_id' => $post['recruit_tpyeRoom1'],
            'recruit_major' => $course_branch,
            'recruit_majorOrder' => $majorOrder,
            'recruit_nickname' => $post['recruit_nickname'] ?? '',
            'recruit_agegroup' => !empty($post['recruit_agegroup']) ? (int)$post['recruit_agegroup'] : 0,
            'recruit_weight' => !empty($post['recruit_weight']) ? (float)$post['recruit_weight'] : 0,
            'recruit_height' => !empty($post['recruit_height']) ? (float)$post['recruit_height'] : 0,
            'recruit_fatherName' => $post['recruit_fatherName'] ?? '',
            'recruit_fatherJob' => $post['recruit_fatherJob'] ?? '',
            'recruit_motherName' => $post['recruit_motherName'] ?? '',
            'recruit_motherJob' => $post['recruit_motherJob'] ?? '',
            'recruit_sportPosition' => $post['recruit_sportPosition'] ?? '',
            'recruit_sportSelectionResult' => '',
            'recruit_address' => "เลขที่ " . $post['recruit_homeNumber'] . " หมู่ที่ " . (!empty($post['recruit_homeGroup']) ? $post['recruit_homeGroup'] : '-') . " ถนน " . (!empty($post['recruit_homeRoad']) ? $post['recruit_homeRoad'] : '-') . " ตำบล" . $post['recruit_homeSubdistrict'] . " อำเภอ" . $post['recruit_homedistrict'] . " จังหวัด" . $post['recruit_homeProvince'] . " " . $post['recruit_homePostcode'],
            'recruit_copyAddress' => '',
            'recruit_status' => "รอการตรวจสอบ",
            'recruit_date' => date('Y-m-d H:i:s'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s'),
            'recruit_statusSurrender' => '',
            'recruit_StatusQuiz' => 'รอเข้าสอบ',
            'recruit_certificateAbility' => '',
            'recruit_userUpdate' => ''
        ];

        if ($year >= 2569) {
            $data_insert['recruit_round'] = (int)($systemStatus->onoff_round ?? 1);
        }

        // Handle Files
        $file_fields = ['recruit_img', 'recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard'];
        $folder_map = [
            'recruit_img' => 'img',
            'recruit_certificateEdu' => 'certificate',
            'recruit_certificateEduB' => 'certificateB', // Fixed to match admin delete logic
            'recruit_copyidCard' => 'copyidCard'
        ];

        // Server-side validation for mandatory files
        $core_files = [
            'recruit_certificateEdu' => 'ปพ.1 (หน้า)',
            'recruit_certificateEduB' => 'ปพ.1 (หลัง)',
            'recruit_copyidCard' => 'สำเนาบัตรประชาชน'
        ];
        foreach ($core_files as $cf => $label) {
            $f = $this->request->getFile($cf);
            if (!$f || !$f->isValid()) {
                $reason = 'ไม่พบไฟล์หรือไฟล์ไม่ถูกต้อง';
                if ($f) {
                    $error = $f->getError();
                    if ($error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE) {
                        $reason = 'ไฟล์มีขนาดใหญ่เกินไป';
                    } elseif ($error == UPLOAD_ERR_NO_FILE) {
                        $reason = 'ยังไม่ได้เลือกไฟล์';
                    } else {
                        $reason = 'ข้อผิดพลาด: ' . $f->getErrorString();
                    }
                }
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => "❌ ข้อมูลไม่ครบถ้วน: กรุณาอัปโหลดไฟล์ **{$label}** ({$reason})"
                ]);
            }
        }

        if (empty($post['recruit_img_cropped'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '❌ ข้อมูลไม่ครบถ้วน: กรุณาอัปโหลดรูปถ่ายนักเรียน'
            ]);
        }

        $uploadedFiles = []; // Keep track to rollback if needed

        foreach ($file_fields as $field) {
            $file = $this->request->getFile($field);
            // Check if file is uploaded or if there's a base64 string for image
            if ($field === 'recruit_img' && !empty($post['recruit_img_cropped'])) {
                // Handle Base64 Image
                $base64Image = $post['recruit_img_cropped'];
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $fileName = $year . '-' . $post['recruit_idCard'] . '-' . uniqid() . '.png';

                $tempFile = tempnam(sys_get_temp_dir(), 'img');
                file_put_contents($tempFile, $imageData);

                $remoteUpload = new RemoteUpload();
                $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/img';

                $result = $remoteUpload->upload($tempFile, $subPath, $fileName);
                @unlink($tempFile);

                if ($result && $result['status'] === 'success') {
                    $data_insert[$field] = $result['filename'];
                    $uploadedFiles[] = ['path' => $subPath, 'file' => $result['filename']];
                } else {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'เกิดข้อผิดพลาดในการอัปโหลดรูปถ่าย: ' . ($result['message'] ?? 'ไม่ทราบสาเหตุ')
                    ]);
                }

            } elseif ($file && $file->isValid() && !$file->hasMoved()) {
                $folder = $folder_map[$field];
                $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/' . $folder;

                $remoteUpload = new RemoteUpload();
                $result = $remoteUpload->upload($file, $subPath);

                if ($result && $result['status'] === 'success') {
                    $data_insert[$field] = $result['filename'];
                    $uploadedFiles[] = ['path' => $subPath, 'file' => $result['filename']];
                } else {
                    // Rollback previous uploads? For now just return error
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์ ' . $field . ': ' . ($result['message'] ?? 'ไม่ทราบสาเหตุ')
                    ]);
                }
            }
        }



        // Insert
        $this->db->transBegin();
        try {
            // Force connection charset to UTF8MB4
            $this->db->query("SET NAMES 'utf8mb4'");
            $this->db->query("SET CHARACTER SET utf8mb4");
            $this->db->query("SET character_set_connection=utf8mb4");
            $this->db->query("SET character_set_results=utf8mb4");
            $this->db->query("SET character_set_client=utf8mb4");

            // Ensure data types are strictly correct to avoid MySQL strict mode errors
            foreach ($data_insert as $key => $value) {
                if ($value === '') {
                    // List fields that can't be empty string in some DB configs
                    if (in_array($key, ['recruit_weight', 'recruit_height', 'recruit_agegroup', 'recruit_tpyeRoom_id'])) {
                        $data_insert[$key] = 0;
                    }
                }
            }

            // Disable validation to see if it's the cause
            $inserted = $this->admissionModel->skipValidation(true)->insert($data_insert);

            if (!$inserted) {
                $error = $this->db->error();
                $validationErrors = $this->admissionModel->errors();
                $lastQuery = (string)$this->db->getLastQuery();
                
                $msg = !empty($error['message']) ? $error['message'] : 'DB rejection';
                if (!empty($validationErrors)) {
                    $msg .= ' | Validation: ' . json_encode($validationErrors);
                }
                
                log_message('error', 'Insert Failed. Query: ' . $lastQuery);
                log_message('error', 'DB Error: ' . json_encode($error));
                
                throw new \Exception($msg . ' (Check logs for Query info)');
            }

            $this->db->transCommit();

            // --- LINE OA Broadcast Notification ---
            try {
                // Get quota name
                $quotaInfo = $this->admissionModel->getQuotaByKey($data_insert['recruit_category']);
                $quotaName = $quotaInfo ? $quotaInfo->quota_explain : 'ทั่วไป';

                $lineMsg = "📢 มีนักเรียนสมัครใหม่\n\n";
                $lineMsg .= "👤 ชื่อ: {$data_insert['recruit_prefix']}{$data_insert['recruit_firstName']} {$data_insert['recruit_lastName']}\n";
                $lineMsg .= "🕒 เวลา: " . date('d/m/Y H:i') . " น.\n";
                $lineMsg .= "📋 ปีการศึกษา: {$year}\n";
                $lineMsg .= "🏫 ระดับชั้น: ม." . ($data_insert['recruit_regLevel'] == 1 ? "1" : "4") . "\n";
                $lineMsg .= "🎯 รอบ: {$quotaName}\n";
                $lineMsg .= "📚 แผนการเรียน: {$data_insert['recruit_tpyeRoom']}";

                $this->sendLineBroadcast($lineMsg);
            } catch (\Exception $e) {
                log_message('error', 'LINE Broadcast Error: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'สมัครเรียนสำเร็จ! กรุณาตรวจสอบสถานะ',
                'redirect_url' => base_url('new-admission/status')
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();

            // Cleanup uploaded files on failure
            $remoteUpload = new RemoteUpload();
            foreach ($uploadedFiles as $uf) {
                // RemoteUpload->delete will already try both servers if configured
                $remoteUpload->delete($uf['file'], $uf['path']);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage() . ' (ข้อมูลยังไม่ถูกส่ง กรุณาลองใหม่อีกครั้ง)'
            ]);
        }
    }

    private function NumberID()
    {
        $openyear = $this->admissionModel->getOpenYear();
        $chk_id = $this->admissionModel->getLatestRecruitId();

        if (empty($chk_id)) {
            $year = $openyear->openyear_year;
            return $year . "0001";
        } else {
            if (strpos($chk_id->recruit_id, $openyear->openyear_year) === 0) {
                $number = substr($chk_id->recruit_id, strlen($openyear->openyear_year));
                $s = sprintf("%04d", $number + 1);
                return $openyear->openyear_year . $s;
            } else {
                return $openyear->openyear_year . "0001";
            }
        }
    }

    public function showTableSchema()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SHOW CREATE TABLE `tb_recruitstudent` ");
        $row = $query->getRowArray();
        return $this->response->setJSON($row);
    }

    public function addAutoIdColumn()
    {
        try {
            $db = \Config\Database::connect();
            
            // 0. Disable strict mode
            $db->query("SET SESSION sql_mode = ''");
            
            // 1. Check if column exists
            $fields = $db->getFieldNames('tb_recruitstudent');
            if (in_array('id', $fields)) {
                return $this->response->setJSON(['status' => 'info', 'message' => 'Column "id" already exists.']);
            }
            
            // 2. Add id column as primary key and move recruit_id to be just a unique column
            // We need to drop existing primary key first
            $db->query("ALTER TABLE `tb_recruitstudent` DROP PRIMARY KEY");
            $db->query("ALTER TABLE `tb_recruitstudent` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
            $db->query("ALTER TABLE `tb_recruitstudent` ADD UNIQUE (`recruit_id`)");
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Auto-increment ID column added and Primary Key updated successfully!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateDatabaseCharset()
    {
        try {
            $db = \Config\Database::connect();
            $dbname = $db->getDatabase();
            
            // 0. Disable strict mode for this session
            $db->query("SET SESSION sql_mode = ''");
            
            // 1. Update Database charset
            $db->query("ALTER DATABASE `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // 2. Get all tables
            $tables = $db->listTables();
            $results = [];
            
            foreach ($tables as $table) {
                // 3. Update Table. Use CONVERT TO to change data as well
                $db->query("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $results[] = "Updated table: {$table}";
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Database and all tables updated to utf8mb4_unicode_ci successfully!',
                'details' => $results
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;
use App\Libraries\Timeago;
use App\Libraries\Datethai;
use App\Libraries\RemoteUpload;
use CodeIgniter\I18n\Time;

class UserControlAdmission extends BaseController
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

    public function dataAll()
    {
        $data['full_url'] = current_url();
        $data['switch'] = $this->admissionModel->getSystemStatus();
        $data['year'] = $this->admissionModel->getRecruitmentYears();
        $data['checkYear'] = $this->admissionModel->getOpenYear();

        return $data;
    }

    public function index()
    {
        $data = $this->dataAll();
        $data['datethai'] = $this->datethai;
        $current_year = !empty($data['checkYear']->openyear_year) ? $data['checkYear']->openyear_year : date('Y') + 543;
        $data['title'] = "ระบบรับสมัครนักเรียนออนไลน์ ปีการศึกษา " . $current_year;
        $data['description'] = "โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";
        $data['banner'] = base_url('public/asset/img/logo.png');
        $data['url'] = "admission";

        return view('AdminssionHome', $data);
    }

    public function get_student_status()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $idcard = $this->request->getPost('search_idcard');
        $day = $this->request->getPost('search_day');
        $month = $this->request->getPost('search_month');
        $year = $this->request->getPost('search_year');

        if (empty($idcard) || empty($day) || empty($month) || empty($year)) {
            return $this->response->setJSON(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        }
        
        $current_year_obj = $this->admissionModel->getOpenYear();
        $current_year = $current_year_obj ? $current_year_obj->openyear_year : date('Y') + 543;

        // Frontend sends Gregorian year, so no need to subtract 543
        $birthday = $year . '-' . $month . '-' . $day;

        $student = $this->admissionModel->findStudentForStatusCheck($idcard, $birthday, $current_year);
        
        if ($student) {
            return $this->response->setJSON(['success' => true, 'student' => $student]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูลนักเรียน หรือข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง','Do' => $student]);
        }
    }

    public function edit_student($id)
    {
        $data = $this->dataAll();
        $data['title'] = "แก้ไขข้อมูลการสมัคร";
        $data['description'] = "แบบฟอร์มแก้ไขข้อมูลสำหรับนักเรียน";
        
        // Fetch the existing student data
        $student = $this->admissionModel->find($id);

        if (!$student) {
            // Handle student not found
            return redirect()->to('/')->with('error', 'ไม่พบข้อมูลนักเรียน');
        }

        // --- NEW CHECK ---
        // --- NEW CHECK ---
        // ID 3 is 'normal'
        if ($student['recruit_category'] != 3) {
            return redirect()->to('new-admission/status')->with('error', 'คุณไม่สามารถแก้ไขข้อมูลการสมัครประเภทนี้ได้ กรุณาติดต่อผู้ดูแลระบบ');
        }
        // --- END NEW CHECK ---
        // --- END NEW CHECK ---

        $level = $student['recruit_regLevel'];
        $gradeLevel = ($level == 1) ? 'ม.ต้น' : 'ม.ปลาย';

        $data['student'] = $student;
        $data['level'] = $level;
        $data['quotas'] = $this->admissionModel->getAllQuotas();
        $data['courses'] = $this->admissionModel->getCoursesByGradeLevel($gradeLevel);
        $data['remote_base_url'] = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";

        return view('User/UserEdit', $data);
    }

    public function update_student()
    {
        if ($this->request->getMethod() === 'post') {
            $recruit_id = $this->request->getPost('recruit_id');
            $original_student = $this->admissionModel->find($recruit_id);

            if (!$original_student) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลนักเรียนที่ต้องการแก้ไข']);
            }

            // --- NEW CHECK ---
            // --- NEW CHECK ---
            // ID 3 is 'normal'
            if ($original_student['recruit_category'] != 3) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'คุณไม่สามารถแก้ไขข้อมูลการสมัครประเภทนี้ได้ กรุณาติดต่อผู้ดูแลระบบ']);
            }
            // --- END NEW CHECK ---
            // --- END NEW CHECK ---

            // Expanded validation rules
            $rules = [
                'recruit_category' => 'required',
                // recruit_tpyeRoom1 might not be required for all quotas
                'recruit_prefix' => 'required',
                'recruit_firstName' => 'required',
                'recruit_lastName' => 'required',
                'recruit_birthdayD' => 'required',
                'recruit_birthdayM' => 'required',
                'recruit_birthdayY' => 'required',
                'recruit_race' => 'required',
                'recruit_nationality' => 'required',
                'recruit_religion' => 'required',
                'recruit_phone' => 'required',
                'recruit_homeNumber' => 'required',
                'recruit_homeGroup' => 'required',
                'recruit_homeSubdistrict' => 'required',
                'recruit_homedistrict' => 'required',
                'recruit_homeProvince' => 'required',
                'recruit_homePostcode' => 'required',
                'recruit_oldSchool' => 'required',
                'recruit_district' => 'required',
                'recruit_province' => 'required',
                'recruit_grade' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[4.00]',
                // File fields are optional for update if a new one isn't uploaded
                'recruit_img_cropped' => 'permit_empty',
                'recruit_certificateEdu' => 'if_exist|max_size[recruit_certificateEdu,4096]|ext_in[recruit_certificateEdu,jpg,jpeg,png,pdf]',
                'recruit_copyidCard' => 'if_exist|max_size[recruit_copyidCard,4096]|ext_in[recruit_copyidCard,jpg,jpeg,png,pdf]',
                'recruit_certificateEduB' => 'if_exist|max_size[recruit_certificateEduB,4096]|ext_in[recruit_certificateEduB,jpg,jpeg,png,pdf]',
            ];

            if (!$this->validate($rules)) {
                $errors = implode('<br>', $this->validator->getErrors());
                return $this->response->setJSON(['status' => 'error', 'message' => $errors]);
            }

            $post = $this->request->getPost();
            $openyear = $this->dataAll()['checkYear']->openyear_year;

            // Handle Course Ranks
            $ranks = [];
            if (!empty($post['recruit_tpyeRoom1'])) $ranks[] = $post['recruit_tpyeRoom1'];
            if (!empty($post['recruit_tpyeRoom2'])) $ranks[] = $post['recruit_tpyeRoom2'];
            if (!empty($post['recruit_tpyeRoom3'])) $ranks[] = $post['recruit_tpyeRoom3'];
            $majorOrder = implode('|', $ranks);

            $courseDetails1 = $this->admissionModel->getCourseDetails($post['recruit_tpyeRoom1'] ?? '');
            
            $data_update = [
                'recruit_prefix' => $post['recruit_prefix'],
                'recruit_firstName' => $post['recruit_firstName'],
                'recruit_lastName' => $post['recruit_lastName'],
                'recruit_birthday' => ($post['recruit_birthdayY'] - 543) . '-' . $post['recruit_birthdayM'] . '-' . $post['recruit_birthdayD'],
                'recruit_race' => $post['recruit_race'],
                'recruit_nationality' => $post['recruit_nationality'],
                'recruit_religion' => $post['recruit_religion'],
                'recruit_phone' => $post['recruit_phone'],
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
                'recruit_tpyeRoom' => $courseDetails1 ? $courseDetails1->course_fullname : '',
                'recruit_major' => $courseDetails1 ? $courseDetails1->course_branch : '',
                'recruit_majorOrder' => $majorOrder,
                'recruit_status' => 'รอการตรวจสอบ', // Set status back to pending
                'recruit_dateUpdate' => date('Y-m-d H:i:s'),
            ];

            // --- Handle File Uploads ---
            
            // Profile Image (from cropper)
            if (!empty($post['recruit_img_cropped'])) {
                $base64Image = $post['recruit_img_cropped'];
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $fileName = $openyear . '-' . $post['recruit_idCard'] . '-' . uniqid() . '.png';
                
                $tempFile = tempnam(sys_get_temp_dir(), 'img');
                file_put_contents($tempFile, $imageData);

                $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/img';
                
                $remoteUpload = new RemoteUpload();
                $result = $remoteUpload->upload($tempFile, $subPath, $fileName);
                
                if ($result && $result['status'] === 'success') {
                    $data_update['recruit_img'] = $result['filename'];
                    // Delete old image
                    if (!empty($original_student['recruit_img'])) {
                        $remoteUpload->delete($original_student['recruit_img'], $subPath);
                    }
                }
                @unlink($tempFile);
            }

            // Other document files
            $file_fields_upload = ['recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard'];
            $folder_map = [
                'recruit_certificateEdu' => 'certificate',
                'recruit_certificateEduB' => 'certificate',
                'recruit_copyidCard' => 'copyidCard',
            ];

            foreach ($file_fields_upload as $field) {
                $file = $this->request->getFile($field);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $folder = $folder_map[$field];
                    $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/' . $folder;
                    
                    $remoteUpload = new RemoteUpload();
                    $result = $remoteUpload->upload($file, $subPath);

                    if ($result && $result['status'] === 'success') {
                        $data_update[$field] = $result['filename'];
                        // Delete old file
                        if (isset($original_student[$field]) && !empty($original_student[$field])) {
                            $remoteUpload->delete($original_student[$field], $subPath);
                        }
                    }
                }
            }

            $this->db->transBegin();
            $this->admissionModel->update($recruit_id, $data_update);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้! กรุณาตรวจสอบข้อมูลและลองใหม่อีกครั้ง']);
            } else {
                $this->db->transCommit();
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'แก้ไขข้อมูลสำเร็จแล้ว กรุณารอการตรวจสอบอีกครั้ง',
                    'redirect_url' => base_url('new-admission/status')
                ]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
    }



    public function reg_student($id, $quota = null)
    {
        //return redirect()->to('CloseStudent'); 
        $data = $this->dataAll();
        $data['title'] = "หน้าสมัครเรียนออนไลน์";
        $data['description'] = "แบบฟอร์กรอกข้อมูลสำหรับนักเรียน";
        $data['banner'] = base_url() . "asset/img/banner-admission64.png";
        $data['url'] = "welcome";
        
        $quotaData = $this->admissionModel->getQuotaByKey($quota);
        $data['TypeQuota'] = $quotaData ? [$quotaData] : []; // Keep as array for view compatibility

        $subQuota = $this->admissionModel->getCourseIdsFromQuota($quota);

        $data['Course'] = [];
        foreach ($subQuota as $courseId) {
            $selCourse = $this->admissionModel->getCourseById($courseId, $id);
            if ($selCourse) {
                $data['Course'][] = $selCourse;
            }
        }

        if ($id > 0) {
            return view('AdminssionRegister', $data);
        }

        return redirect()->to('/');
    }

    function NumberID()
    {
        $openyear = $this->admissionModel->getOpenYear();
        $chk_id = $this->admissionModel->getLatestRecruitId();

        if (empty($chk_id)) {
            $year =  $openyear->openyear_year;
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

    public function CheckStudentRegister()
    {
        $data = $this->dataAll();
        $idCard = $this->request->getPost('Idcard');
        $isRegistered = $this->admissionModel->isIdCardRegistered($idCard, $data['checkYear']->openyear_year);
        echo $isRegistered ? 1 : 0;
    }


    public function reg_insert()
    {
        $data = $this->dataAll();
        $post = $this->request->getPost();

        //รับรอบปกติ (ID 3 = normal)
        if ($post['recruit_category'] == 3) {
            $SelImpo = implode('|', $post['recruit_majorOrder']);
            $checkCourse = $this->admissionModel->getCourseDetails($post['recruit_majorOrder'][0]);
            $course_fullname = $checkCourse ? $checkCourse->course_fullname : '';
            $course_branch = $checkCourse ? $checkCourse->course_branch : '';
        } else {
            $SelImpo = "";
            $course_fullname = $post['recruit_tpyeRoom'];
            $course_branch = $post['recruit_major'];
        }
        
        // hCaptcha verification (Secret key should be in .env)
        $hcaptchaResponse = $post['h-captcha-response'];
        $secretKey = getenv('HCAPTCHA_SECRET_KEY') ?: 'ES_47c9a8452c844bf6b5bf834237aacb8d'; // Fallback for local dev
        $url = 'https://hcaptcha.com/siteverify';
        $verify_data = ['secret' => $secretKey, 'response' => $hcaptchaResponse];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($verify_data),
            ],
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseData = json_decode($response);

        if ($responseData && $responseData->success) {
            
            $isRegistered = $this->admissionModel->isIdCardRegistered($post['recruit_idCard'], $data['checkYear']->openyear_year);

            if ($isRegistered) {
                $this->session->setFlashdata(['msg' => 'NO', 'messge' => 'คุณได้ลงทะเบียนแล้ว กรุณาตรวจสอบการสมัครปีการศึกษานี้แล้ว', 'status' => 'error']);
                return redirect()->to('welcome');
            } else {
                
                $fileName = '';
                if (!empty($post['recruit_img'])) {
                    $fileName = $data['checkYear']->openyear_year . '-' . $post['recruit_idCard'] . '-' . uniqid() . '.png';
                }

                $recruit_birthday = ($post['recruit_birthdayY'] - 543) . '-' . $post['recruit_birthdayM'] . '-' . $post['recruit_birthdayD'];

                $data_insert = [
                    'recruit_id'  => $this->NumberID(),
                    'recruit_year' => $data['checkYear']->openyear_year,
                    'recruit_regLevel' => $post['recruit_regLevel'],
                    'recruit_prefix' => $post['recruit_prefix'],
                    'recruit_firstName' => $post['recruit_firstName'],
                    'recruit_lastName' => $post['recruit_lastName'],
                    'recruit_idCard' => $post['recruit_idCard'],
                    'recruit_birthday' => $recruit_birthday,
                    'recruit_race' => $post['recruit_race'],
                    'recruit_nationality' => $post['recruit_nationality'],
                    'recruit_religion' => $post['recruit_religion'],
                    'recruit_phone' => $post['recruit_phone'],
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
                    'recruit_major' => $course_branch,
                    'recruit_majorOrder' => $SelImpo,
                    'recruit_agegroup' => isset($post['recruit_agegroup']) ? $post['recruit_agegroup'] : 0,
                    'recruit_img' => $fileName,
                    'recruit_status' => "รอการตรวจสอบ",
                    'recruit_date'    => date('Y-m-d H:i:s'),
                    'recruit_dateUpdate' => date('Y-m-d H:i:s'),
                    'recruit_address' => '',
                    'recruit_copyAddress' => '',
                    'recruit_statusSurrender' => 'Normal',
                    'recruit_StatusQuiz' => 'รอเข้าสอบ'
                ];

                $this->db->transBegin();
                $student_id = $this->admissionModel->student_insert($data_insert);

                if ($this->db->transStatus() === FALSE || $student_id === false) {
                    $this->db->transRollback();
                    $this->session->setFlashdata(['msg' => 'NO', 'messge' => 'ไม่สามารถบันทึกข้อมูลได้! กรุณาตรวจสอบข้อมูลและลองใหม่อีกครั้ง', 'status' => 'error']);
                    return redirect()->to('welcome');
                } else {
                    $this->db->transCommit();

                    // --- Save Profile Image ---
                    // --- Save Profile Image ---
                    if ($fileName !== '') {
                        $image = $post['recruit_img'];
                        $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $image));
                        
                        $tempFile = tempnam(sys_get_temp_dir(), 'img');
                        file_put_contents($tempFile, $imageData);

                        $remoteUpload = new RemoteUpload();
                        $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/img';
                        $result = $remoteUpload->upload($tempFile, $subPath, $fileName);
                        
                        // If upload failed, we might want to log it or handle it, but the record is already inserted.
                        // Ideally we should update the record if filename changed, but here we used $fileName for insert.
                        // If remote upload changes name, we need to update DB.
                        if ($result && $result['status'] === 'success') {
                             if ($result['filename'] !== $fileName) {
                                 $this->admissionModel->student_update($student_id, ['recruit_img' => $result['filename']]);
                             }
                        } else {
                             // Upload failed
                        }
                        @unlink($tempFile);
                    }

                    // --- Save Other Uploaded Files ---
                    $update_data = [];
                    $filesAbility = $this->request->getFileMultiple('recruit_certificateAbility');
                    if ($filesAbility) {
                         $abilityFiles = $this->UploadCertificateAbility($filesAbility, $post['recruit_regLevel']);
                         if ($abilityFiles) {
                             $update_data['recruit_certificateAbility'] = $abilityFiles;
                         }
                    }
                    $file_fields = ['recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard', 'recruit_copyAddress'];
                    $folder_map = [
                        'recruit_certificateEdu' => 'certificate',
                        'recruit_certificateEduB' => 'certificateB',
                        'recruit_copyidCard' => 'copyidCard',
                        'recruit_copyAddress' => 'copyAddress'
                    ];
                    foreach ($file_fields as $field) {
                        $file = $this->request->getFile($field);
                        if ($file && $file->isValid() && !$file->hasMoved()) {
                            $folder = $folder_map[$field];
                            $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/' . $folder;
                            
                            $remoteUpload = new RemoteUpload();
                            $result = $remoteUpload->upload($file, $subPath);
                            
                            if ($result && $result['status'] === 'success') {
                                $update_data[$field] = $result['filename'];
                            }
                        }
                    }

                    if (!empty($update_data)) {
                        $this->admissionModel->student_update($student_id, $update_data);
                    }

                    $this->session->setFlashdata(['msg' => 'Yes', 'messge' => 'สมัครเรียนสำเร็จ สามารถตรวจสอบสถานะการสมัครเพื่อพิมพ์ใบสมัครได้', 'status' => 'success']);
                    return redirect()->to('welcome');
                }
            }
        } else {
            // hCaptcha failed
            $this->session->setFlashdata(['msg' => 'NO', 'messge' => 'Captcha ไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง', 'status' => 'error']);
            return redirect()->to('welcome');
        }
    }

    public function UploadCertificateAbility($files, $regLevel)
    {
        $SetName = array();
        $remoteUpload = new RemoteUpload();
        $subPath = 'admission/recruitstudent/m' . $regLevel . '/certificateAbility';

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                
                // Resize locally first
                $tempName = $file->getTempName();
                try {
                    \Config\Services::image()
                        ->withFile($tempName)
                        ->resize(1024, 1024, true, 'width')
                        ->save($tempName);
                } catch (\Exception $e) {
                    // Log error or ignore
                }

                $result = $remoteUpload->upload($file, $subPath);
                if ($result && $result['status'] === 'success') {
                    $SetName[] = $result['filename'];
                }
            }
        }
        $result = !empty($SetName) ? implode('|', $SetName) : 0;
        return $result;
    }


    public function checkdata_student()
    {
        $data = $this->dataAll();
        $data['title'] = 'ตรวจสอบและแก้ไขการสมัคร';
        $data['description'] = 'ตรวจสอบและแก้ไขการสมัคร';
        return view('stu_checkdata', $data);
    }

    public function data_user()
    {

        $data = $this->dataAll();
        $data['title'] = 'แก้ไขชื่อผู้สมัครสอบ';
        $data['description'] = 'แก้ไขชื่อผู้สมัครสอบ';

        $status = ['success' => true]; // Bypassing captcha for now

        if ($status['success']) {
            $search_stu = $this->request->getPost('search_stu');
            $student = $this->admissionModel->where('recruit_idCard', $search_stu)->first();
            
            if (!$student) {
                $this->session->setFlashdata(['alert1' => 'success', 'msg' => 'NO', 'messge' => 'ไม่มีข้อมูลในระบบ หรือ ยังไม่ได้ลงทะเบียนเรียน']);
                return redirect()->to('StudentLogin');
            } else {
                $this->session->set('IDstudent', $student['recruit_id']);
                return redirect()->to('StudentData');
            }
        } else {
            return redirect()->to('StudentLogin');
        }
    }

    function notify_message($message, $token)
    {
        // LINE_API constant and token should be in .env
        if (!defined('LINE_API')) define('LINE_API', 'https://notify-api.line.me/api/notify');
        
        $queryData = array('message' => $message);
        $queryData = http_build_query($queryData, '', '&');
        $headerOptions = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                    . "Authorization: Bearer " . $token . "\r\n"
                    . "Content-Length: " . strlen($queryData) . "\r\n",
                'content' => $queryData
            ),
        );
        $context = stream_context_create($headerOptions);
        $result = file_get_contents(LINE_API, FALSE, $context);
        $res = json_decode($result);
        return $res;
    }

    public function print_student()
    {

        $data = $this->dataAll();
        $data['title'] = 'ประกาศรายชื่อผู้สมัครสอบ';
        $data['description'] = 'ประกาศรายชื่อผู้สมัครสอบ';

        $data['m1'] = $this->admissionModel->getApplicantsByLevel('1');
        $data['m4'] = $this->admissionModel->getApplicantsByLevel('4');

        return view('stu_announce', $data);
    }

    public function check_print()
    {
        $id = $this->request->getPost('id');
        $d = $this->request->getPost('recruit_birthdayD');
        $m = $this->request->getPost('recruit_birthdayM');
        $y = $this->request->getPost('recruit_birthdayY') - 543;
        $date = $y . '-' . $m . '-' . $d;

        $student = $this->admissionModel->checkPrintLogin($id, $date);

        if ($student) {
            echo base_url('control_admission/pdf/' . $student->recruit_id);
        } else {
            echo 0;
        }
    }

    public function pdf($id)
    {
        // 1. Load mPDF (Logic from AdminControlReport)
        $path = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
        if (file_exists($path . '/librarie_skj/mpdf/vendor/autoload.php')) {
            require_once $path . '/librarie_skj/mpdf/vendor/autoload.php';
        } else {
            // Fallback to vendor if custom path fails
            if (file_exists(FCPATH . '../vendor/autoload.php')) {
                require_once FCPATH . '../vendor/autoload.php';
            }
        }

        if (!class_exists('\Mpdf\Mpdf')) {
            return "ไม่พบไลบรารี mPDF กรุณาติดตั้ง";
        }

        // 2. Database Connections
        $db = \Config\Database::connect();
        
        // 3. Fetch Student Data
        // We use $id passed to the function, NOT session, to allow status check printing
        $builder = $db->table('tb_recruitstudent');
        $builder->where('recruit_id', $id);
        $student = $builder->get()->getRow();

        if (!$student) {
            return "ไม่พบข้อมูลนักเรียน";
        }

        // 4. Prepare Data
        $date_Y = date('Y', strtotime($student->recruit_birthday)) + 543;
        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $date_D = date('d', strtotime($student->recruit_birthday));
        $date_M = date('n', strtotime($student->recruit_birthday));

        $date_Y_regis = date('Y', strtotime($student->recruit_date)) + 543;
        $date_D_regis = date('d', strtotime($student->recruit_date));
        $date_M_regis = date('n', strtotime($student->recruit_date));

        // 5. Initialize mPDF (Card Format [210, 90])
        // Clean output buffer
        if (ob_get_length()) ob_clean();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'format' => [210, 90],
            'tempDir' => WRITEPATH . 'temp' // Added tempDir as per AdminControlReport
        ]);

        $mpdf->SetTitle($student->recruit_prefix . $student->recruit_firstName . ' ' . $student->recruit_lastName);
        $mpdf->showImageErrors = true;

        // 6. Generate HTML Content
        // 6. Generate HTML Content
        // Image Path
        $img_tag = '';
        if (!empty($student->recruit_img)) {
            // Construct Remote URL directly to avoid localhost loopback issues with mPDF
            $remoteBaseUrl = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
            $fileName = 'recruitstudent/m' . $student->recruit_regLevel . '/img/' . $student->recruit_img;
            $fullRemoteUrl = rtrim($remoteBaseUrl, '/') . '/' . ltrim($fileName, '/');

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $fullRemoteUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
                $imageData = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode == 200 && !empty($imageData)) {
                    // Detect MIME type from content, not extension
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->buffer($imageData);
                    
                    // Allow only valid image types
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
                    
                    if (in_array($mime, $allowedMimes)) {
                        $base64 = base64_encode($imageData);
                        $img_src = 'data:' . $mime . ';base64,' . $base64;
                        $img_tag = '<img style="width:120px;height:100px;" src="' . $img_src . '">';
                    }
                }
            } catch (\Exception $e) {
                // Fallback or ignore
            }
        }

        $html = '<div style="position:absolute;top:100px;left:75px; width:100%">' . $img_tag . '</div>';
        $html .= '<div style="position:absolute;top:57px;left:150px; width:100%">' . sprintf("%04d", $student->recruit_id) . '</div>'; // Application ID
        $html .= '<div style="position:absolute;top:100px;left:250px; width:100%">' . $student->recruit_prefix . $student->recruit_firstName . '</div>'; // Name
        $html .= '<div style="position:absolute;top:100px;left:480px; width:100%">' . $student->recruit_lastName . '</div>'; // Surname
        $html .= '<div style="position:absolute;top:127px;left:400px; width:100%">' . $student->recruit_idCard . '</div>'; // ID Card
        $html .= '<div style="position:absolute;top:155px;left:270px; width:100%"> ' . $student->recruit_tpyeRoom . '</div>'; // Program
        
        // License Image (Signature/Stamp)
        $license_path = FCPATH . 'public/asset/img/license.png'; // Adjusted path assuming public/asset
        if (!file_exists($license_path)) {
             $license_path = FCPATH . 'asset/img/license.png'; // Try alternate path
        }
        if (file_exists($license_path)) {
             $html .= '<div style="position:absolute;top:200px;left:340px; width:100%"><img style="width:120px;height:100px;" src="' . $license_path . '"></div>';
        }

        $html .= '<div style="position:absolute;top:255px;left:360px; width:100%">' . $date_D_regis . ' ' . $TH_Month[$date_M_regis - 1] . ' ' . $date_Y_regis . '</div>'; // Date

        // 7. Set Template
        $templatePath = FCPATH . 'uploads/recruitstudent/pdf_registudentForStudent.pdf';
        
        if (file_exists($templatePath)) {
            $mpdf->SetDocTemplate($templatePath, true);
        } else {
            $html .= '<div style="color:red; position:absolute; top:0; left:0;">Template not found: ' . $templatePath . '</div>';
        }

        $mpdf->WriteHTML($html);
        
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Reg_' . $student->recruit_idCard . '.pdf', 'I');
        exit(); // Prevent CI4 from interfering with the output
    }

    public function SchoolList()
    {
        $postData = $this->request->getPost();
        $data = $this->admissionModel->getSchool($postData);
        echo json_encode($data);
    }

    public function CheckOnOffRegis()
    {
        $data = $this->admissionModel->getSystemOnOffStatus();
        return $this->response->setJSON($data);
    }
}

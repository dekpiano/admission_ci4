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
        
        return view('User/UserHome', $data);
    }

    public function pre_check($level = null)
    {
        if (!$level) {
            return redirect()->to('new-admission');
        }

        $data['title'] = "ตรวจสอบสิทธิ์การสมัคร " . ($level == 1 ? "ม.1" : "ม.4");
        $data['level'] = $level;
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['quotas'] = $this->admissionModel->getAllQuotas(); // Add quotas for menu generation
        $data['systemStatus'] = $this->admissionModel->getSystemStatus(); // Pass system status
        
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
        foreach($response as $item) {
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
        $data['quotas'] = $this->admissionModel->getAllQuotas(); // Add quotas for menu generation
        $data['systemStatus'] = $this->admissionModel->getSystemStatus(); // Pass system status
        return view('User/UserStatus', $data);
    }

    public function save_register()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid Request');
        }

        $post = $this->request->getPost();
        
        // Basic Validation
        if (!$this->validate([
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
        ])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $year = $this->admissionModel->getOpenYear()->openyear_year;

        // Check duplicate
        if ($this->admissionModel->isIdCardRegistered($post['recruit_idCard'], $year)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เลขบัตรประชาชนนี้ได้ทำการสมัครไปแล้ว กรุณาตรวจสอบสถานะ'
            ]);
        }

        // Generate ID
        $recruit_id = $this->NumberID();

        // Handle Birthday
        $recruit_birthday = ($post['recruit_birthdayY'] - 543) . '-' . $post['recruit_birthdayM'] . '-' . $post['recruit_birthdayD'];

        // Lookup Course Details for Rank 1 (Primary)
        $courseDetails1 = $this->admissionModel->getCourseDetails($post['recruit_tpyeRoom1']);
        $course_fullname = $courseDetails1 ? $courseDetails1->course_fullname : '';
        $course_branch = $courseDetails1 ? $courseDetails1->course_branch : '';

        // Handle Ranks for recruit_majorOrder (Format: ID|ID|ID)
        $ranks = [];
        if (!empty($post['recruit_tpyeRoom1'])) {
            $ranks[] = $post['recruit_tpyeRoom1'];
        }
        if (!empty($post['recruit_tpyeRoom2'])) {
            $ranks[] = $post['recruit_tpyeRoom2'];
        }
        if (!empty($post['recruit_tpyeRoom3'])) {
            $ranks[] = $post['recruit_tpyeRoom3'];
        }
        $majorOrder = implode('|', $ranks);

        // Prepare Data
        $data_insert = [
            'recruit_id'  => $recruit_id,
            'recruit_year' => $year,
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
            'recruit_tpyeRoom_id' => $post['recruit_tpyeRoom1'],
            'recruit_major' => $course_branch, 
            'recruit_majorOrder' => $majorOrder,
            'recruit_agegroup' => isset($post['recruit_agegroup']) ? $post['recruit_agegroup'] : 0,
            'recruit_status' => "รอการตรวจสอบ",
            'recruit_date'    => date('Y-m-d H:i:s'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s'),
            'recruit_statusSurrender' => '',
            'recruit_StatusQuiz' => 'รอเข้าสอบ'
        ];

        // Handle Files
        $file_fields = ['recruit_img', 'recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard'];
        $folder_map = [
            'recruit_img' => 'img',
            'recruit_certificateEdu' => 'certificate',
            'recruit_certificateEduB' => 'certificate',
            'recruit_copyidCard' => 'copyidCard'
        ];

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
            $this->admissionModel->insert($data_insert);
            
            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Database Insert Failed');
            }
            
            $this->db->transCommit();
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'สมัครเรียนสำเร็จ! กรุณาตรวจสอบสถานะ',
                'redirect_url' => base_url('new-admission/status')
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            // Ideally delete uploaded files here if transaction failed
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง'
            ]);
        }
    }

    private function NumberID()
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
}

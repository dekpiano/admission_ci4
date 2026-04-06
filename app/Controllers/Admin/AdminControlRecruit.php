<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;
use Mpdf\Mpdf;

helper('upload');

class AdminControlRecruit extends BaseController
{
    public function index()
    {
        $model = new AdmissionModel();

        // Get distinct years
        $years = $model->select('recruit_year')->distinct()->orderBy('recruit_year', 'DESC')->findColumn('recruit_year');

        if (empty($years)) {
            $years = [date('Y')];
        }

        // Get selected year
        $selectedYear = $this->request->getVar('year') ?? $years[0];

        // Join with Quota table to get readable category names
        $data['recruits'] = $model->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_course.course_initials')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->where('recruit_year', $selectedYear)
            ->orderBy('recruit_id', 'DESC')
            ->findAll();

        $data['rounds'] = $model->select('recruit_round')->distinct()->orderBy('recruit_round', 'ASC')->findColumn('recruit_round') ?? ['1'];
        if(empty($data['rounds'])) $data['rounds'] = ['1'];

        // Get quotas for filter dropdown
        $data['quotas'] = $model->getAllQuotas();
        
        // Get courses for filter dropdown
        $data['courses'] = $model->getAllCourses();
        
        // Get distinct regLevels for filter dropdown
        $data['regLevels'] = $model->select('recruit_regLevel')->distinct()->orderBy('recruit_regLevel', 'ASC')->findColumn('recruit_regLevel') ?? ['1', '4'];

        $data['years'] = $years;
        $data['selected_year'] = $selectedYear;
        $data['title'] = 'ข้อมูลผู้สมัคร';

        return view('Admin/PageAdminRecruit/PageAdminRecruitIndex', $data);
    }

    	public function register()
	{
		$model = new AdmissionModel();

		$level = $this->request->getVar('level') ?? '1';

		$data['checkYear'] = $model->getOpenYear();
		$data['systemStatus'] = $model->getSystemStatus();
		$data['quotas'] = $model->getAllQuotas();
		
		// กรองแผนการเรียนตามระดับชั้น (ม.1 = ม.ต้น, ม.4 = ม.ปลาย)
		$gradeName = ($level == '1') ? 'ม.ต้น' : 'ม.ปลาย';
		$data['courses'] = $model->db->table('tb_course')->where('course_gradelevel', $gradeName)->get()->getResult();
		
		$data['level'] = $level;
		$data['title'] = 'ลงทะเบียน Walk-in';

		// Generate CAPTCHA numbers just in case
		$data['captcha_num1'] = rand(1, 10);
		$data['captcha_num2'] = rand(1, 10);

		return view('Admin/PageAdminRecruit/PageAdminRecruitRegister', $data);
	}


	public function check_id_ajax()
	{
		if (!$this->request->isAJAX()) {
			return $this->response->setJSON(['error' => 'Invalid Request']);
		}

		$idCard = $this->request->getPost('idCard');
		$model = new AdmissionModel();
		$year = $model->getOpenYear()->openyear_year;
		$exists = $model->isIdCardRegistered($idCard, $year);

		return $this->response->setJSON(['exists' => $exists]);
	}


    public function save_register()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to(site_url('skjadmin/recruits/register'));
        }

        try {
            $post = $this->request->getPost();
            $model = new AdmissionModel();
            $systemStatus = $model->getSystemStatus();
            $year = $model->getOpenYear()->openyear_year;

            // Basic Validation
            if (
                !$this->validate([
                    'recruit_idCard' => [
                        'rules' => 'required',
                        'errors' => ['required' => 'กรุณากรอกเลขบัตรประชาชน']
                    ],
                    'recruit_firstName' => [
                        'rules' => 'required',
                        'errors' => ['required' => 'กรุณากรอกชื่อ']
                    ],
                    'recruit_lastName' => [
                        'rules' => 'required',
                        'errors' => ['required' => 'กรุณากรอกนามสกุล']
                    ],
                    'recruit_category' => [
                        'rules' => 'required',
                        'errors' => ['required' => 'กรุณาเลือกประเภทโควตา']
                    ]
                ])
            ) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    'errors' => $this->validator->getErrors()
                ]);
            }

        // Check duplicate
        if ($model->isIdCardRegistered($post['recruit_idCard'], $year)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เลขบัตรประชาชนนี้ได้ทำการสมัครไปแล้ว'
            ]);
        }

        // Robust MAX ID fetch directly from DB to prevent collisions
        $db = \Config\Database::connect();
        $builder = $db->table('tb_recruitstudent');
        $maxIdRow = $builder->selectMax('recruit_id')->get()->getRow();
        $lastId = $maxIdRow ? (int)$maxIdRow->recruit_id : 0;
        
        $openyear = $model->getOpenYear();
        $prefix = (int)$openyear->openyear_year;
        
        if (strpos((string)$lastId, (string)$prefix) === 0) {
            $recruit_id = (string)($lastId + 1);
        } else {
            $recruit_id = $prefix . "0001";
        }
        
        // Double check if this ID exists
        while ($db->table('tb_recruitstudent')->where('recruit_id', $recruit_id)->countAllResults() > 0) {
            $recruit_id = (string)((int)$recruit_id + 1);
        }

        // Handle Birthday
        $recruit_birthday = ($post['recruit_birthdayY'] - 543) . '-' . $post['recruit_birthdayM'] . '-' . $post['recruit_birthdayD'];

        // Reprepare values
        $courseModel = new \App\Models\CourseModel();
        $courseDetails1 = $courseModel->find($post['recruit_tpyeRoom1']);
        $course_fullname = $courseDetails1 ? $courseDetails1['course_fullname'] : '';
        $course_branch = $courseDetails1 ? $courseDetails1['course_branch'] : '';
        
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
            'recruit_idCard' => \format_id_card($post['recruit_idCard']),
            'recruit_birthday' => $recruit_birthday,
            'recruit_race' => $post['recruit_race'],
            'recruit_nationality' => $post['recruit_nationality'],
            'recruit_religion' => $post['recruit_religion'],
            'recruit_phone' => $post['recruit_phone'],
            'recruit_homeNumber' => $post['recruit_homeNumber'],
            'recruit_homeGroup' => $post['recruit_homeGroup'],
            'recruit_homeRoad' => $post['recruit_homeRoad'] ?? '',
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
            'recruit_address' => "เลขที่ " . $post['recruit_homeNumber'] . " หมู่ที่ " . (!empty($post['recruit_homeGroup']) ? $post['recruit_homeGroup'] : '-') . " ถนน " . (!empty($post['recruit_homeRoad']) ? $post['recruit_homeRoad'] : '-') . " ตำบล" . $post['recruit_homeSubdistrict'] . " อำเภอ" . $post['recruit_homedistrict'] . " จังหวัด" . $post['recruit_homeProvince'] . " " . $post['recruit_homePostcode'],
            'recruit_copyAddress' => '',
            'recruit_status' => "ผ่านการตรวจสอบ", // Admin registered = automatic approved
            'recruit_date' => date('Y-m-d H:i:s'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s'),
            'recruit_StatusQuiz' => 'รอเข้าสอบ',
            'recruit_statusSurrender' => '',
            'recruit_certificateAbility' => '',
            'recruit_sportSelectionResult' => '',
            'recruit_userUpdate' => session()->get('pers_id')
        ];

        if ($year >= 2569) {
            $data_insert['recruit_round'] = (int)($systemStatus->onoff_round ?? 1);
        }

        // Handle Files
        $remoteUpload = new \App\Libraries\RemoteUpload();
        $uploadedFiles = [];
        $file_fields = ['recruit_img', 'recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard'];
        $folder_map = [
            'recruit_img' => 'img',
            'recruit_certificateEdu' => 'certificate',
            'recruit_certificateEduB' => 'certificateB',
            'recruit_copyidCard' => 'copyidCard'
        ];

        foreach ($file_fields as $field) {
            if ($field === 'recruit_img' && !empty($post['recruit_img_cropped'])) {
                $base64Image = $post['recruit_img_cropped'];
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $fileName = $year . '-' . $post['recruit_idCard'] . '-' . uniqid() . '.png';
                $tempFile = tempnam(sys_get_temp_dir(), 'img');
                file_put_contents($tempFile, $imageData);
                $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/img';
                $result = $remoteUpload->upload($tempFile, $subPath, $fileName);
                @unlink($tempFile);
                if ($result && $result['status'] === 'success') {
                    $data_insert[$field] = $result['filename'];
                    $uploadedFiles[] = ['path' => $subPath, 'file' => $result['filename']];
                }
            } else {
                $file = $this->request->getFile($field);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $folder = $folder_map[$field];
                    $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/' . $folder;
                    $result = $remoteUpload->upload($file, $subPath);
                    if ($result && $result['status'] === 'success') {
                        $data_insert[$field] = $result['filename'];
                        $uploadedFiles[] = ['path' => $subPath, 'file' => $result['filename']];
                    }
                }
            }
        }

            if ($model->insert($data_insert)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'บันทึกข้อมูลนักเรียน (Walk-in) สำเร็จ',
                    'redirect_url' => site_url('skjadmin/recruits')
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        } catch (\Throwable $e) {
            log_message('error', '[AdminControlRecruit::save_register] Throwable: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดภายในระบบ: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    public function view($id = null)

    {
        $model = new AdmissionModel();
        $courseModel = new \App\Models\CourseModel();

        $data['recruit'] = $model->select('tb_recruitstudent.*, tb_course.course_fullname as course_name_joined, tb_personnel.pers_prefix as verifier_prefix, tb_personnel.pers_firstname as verifier_fname, tb_personnel.pers_lastname as verifier_lname')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->join('skjacth_personnel.tb_personnel', 'skjacth_personnel.tb_personnel.pers_id = tb_recruitstudent.recruit_userUpdate', 'left')
            ->find($id);

        if (empty($data['recruit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้สมัคร ID: ' . $id);
        }

        // Process recruit_majorOrder if it exists and is in the new format (pipe-separated IDs)
        if (!empty($data['recruit']['recruit_majorOrder'])) {
            $courseIds = explode('|', $data['recruit']['recruit_majorOrder']);
            $majorOrderList = [];

            // Fetch course full names for each ID
            if (!empty($courseIds)) {
                // Using whereIn for a single query to get all courses
                $courses = $courseModel->select('course_id, course_fullname')
                    ->whereIn('course_id', $courseIds)
                    ->findAll();

                // Map course_id to course_fullname for easy lookup
                $courseMap = [];
                foreach ($courses as $course) {
                    $courseMap[$course['course_id']] = $course['course_fullname'];
                }

                // Reconstruct the list in the original order specified by recruit_majorOrder
                foreach ($courseIds as $id) {
                    if (isset($courseMap[$id])) {
                        $majorOrderList[] = $courseMap[$id];
                    } else {
                        // If a course ID from recruit_majorOrder isn't found, add a placeholder or skip
                        $majorOrderList[] = 'ไม่พบหลักสูตร (' . $id . ')';
                    }
                }
            }
            $data['recruit']['major_order_list'] = $majorOrderList;
        } else {
            $data['recruit']['major_order_list'] = []; // Ensure it's always an array even if recruit_majorOrder is empty
        }

        $data['remote_base_url'] = get_upload_base_url();
        $data['title'] = 'รายละเอียดผู้สมัคร';
        return view('Admin/PageAdminRecruit/PageAdminRecruitView', $data);
    }

    public function edit($id = null)
    {
        $model = new AdmissionModel();
        $courseModel = new \App\Models\CourseModel();
        $data['recruit'] = $model->find($id);

        if (empty($data['recruit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้สมัคร ID: ' . $id);
        }

        // Process recruit_majorOrder to get IDs array
        $data['major_order_ids'] = [];
        if (!empty($data['recruit']['recruit_majorOrder'])) {
            $data['major_order_ids'] = explode('|', $data['recruit']['recruit_majorOrder']);
        }

        $data['courses'] = $model->getAllCourses();
        $data['quotas'] = $model->getAllQuotas();
        $data['courses_json'] = json_encode($data['courses']);
        $data['remote_base_url'] = get_upload_base_url();
        $data['title'] = 'แก้ไขข้อมูลผู้สมัคร';
        return view('Admin/PageAdminRecruit/PageAdminRecruitEdit', $data);
    }

    public function update($id = null)
    {
        $model = new AdmissionModel();
        $courseModel = new \App\Models\CourseModel();

        // Build major order from 3 dropdowns
        $majorOrder = [];
        $course1 = $this->request->getPost('recruit_tpyeRoom1');
        $course2 = $this->request->getPost('recruit_tpyeRoom2');
        $course3 = $this->request->getPost('recruit_tpyeRoom3');

        if (!empty($course1))
            $majorOrder[] = $course1;
        if (!empty($course2))
            $majorOrder[] = $course2;
        if (!empty($course3))
            $majorOrder[] = $course3;

        $majorOrderStr = implode('|', $majorOrder);

        // Combine birthday split fields (Match User side UI split)
        // Handle birthday: can be from single field (Admin Flatpickr) or split fields (User select)
        $birthday = $this->request->getPost('recruit_birthday');
        
        if (empty($birthday)) {
            $birthD = $this->request->getPost('recruit_birthdayD');
            $birthM = $this->request->getPost('recruit_birthdayM');
            $birthY = (int)$this->request->getPost('recruit_birthdayY');
            if ($birthD && $birthM && $birthY) {
                $birthday = ($birthY - 543) . '-' . $birthM . '-' . $birthD;
            }
        }

        // Get first course as primary for backward compatibility
        $courseId = $course1;
        $courseName = '';
        if ($courseId) {
            $course = $courseModel->find($courseId);
            if ($course) {
                $courseName = $course['course_fullname'];
            }
        }

        $data = [
            'recruit_idCard' => \format_id_card($this->request->getPost('recruit_idCard')),
            'recruit_prefix' => $this->request->getPost('recruit_prefix'),
            'recruit_firstName' => $this->request->getPost('recruit_firstName'),
            'recruit_lastName' => $this->request->getPost('recruit_lastName'),
            'recruit_birthday' => $birthday,
            'recruit_phone' => $this->request->getPost('recruit_phone'),
            'recruit_oldSchool' => $this->request->getPost('recruit_oldSchool'),
            'recruit_grade' => $this->request->getPost('recruit_grade'),
            'recruit_regLevel' => $this->request->getPost('recruit_regLevel'),
            'recruit_category' => $this->request->getPost('recruit_category'),
            'recruit_tpyeRoom' => $courseName,
            'recruit_tpyeRoom_id' => $courseId,
            'recruit_majorOrder' => $majorOrderStr,
            'recruit_status' => $this->request->getPost('recruit_status'),
            'recruit_homeNumber' => $this->request->getPost('recruit_homeNumber'),
            'recruit_homeGroup' => $this->request->getPost('recruit_homeGroup'),
            'recruit_homeRoad' => $this->request->getPost('recruit_homeRoad'),
            'recruit_homeSubdistrict' => $this->request->getPost('recruit_homeSubdistrict'),
            'recruit_homedistrict' => $this->request->getPost('recruit_homedistrict'),
            'recruit_homeProvince' => $this->request->getPost('recruit_homeProvince'),
            'recruit_homePostcode' => $this->request->getPost('recruit_homePostcode'),
            'recruit_race' => $this->request->getPost('recruit_race'),
            'recruit_nationality' => $this->request->getPost('recruit_nationality'),
            'recruit_religion' => $this->request->getPost('recruit_religion'),
            'recruit_district' => $this->request->getPost('recruit_district'),
            'recruit_province' => $this->request->getPost('recruit_province'),
            'recruit_major' => $this->request->getPost('recruit_major') ?: $course['course_branch'] ?? '',
            'recruit_address' => "เลขที่ " . $this->request->getPost('recruit_homeNumber') . " หมู่ที่ " . (!empty($this->request->getPost('recruit_homeGroup')) ? $this->request->getPost('recruit_homeGroup') : '-') . " ถนน " . (!empty($this->request->getPost('recruit_homeRoad')) ? $this->request->getPost('recruit_homeRoad') : '-') . " ตำบล" . $this->request->getPost('recruit_homeSubdistrict') . " อำเภอ" . $this->request->getPost('recruit_homedistrict') . " จังหวัด" . $this->request->getPost('recruit_homeProvince') . " " . $this->request->getPost('recruit_homePostcode'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s'),
            'recruit_userUpdate' => session()->get('pers_id'),
            'recruit_sportSelectionResult' => $this->request->getPost('recruit_sportSelectionResult'),
            // Sport fields (for athlete applicants)
            'recruit_agegroup' => $this->request->getPost('recruit_agegroup') ?: null,
            'recruit_sportPosition' => $this->request->getPost('recruit_sportPosition') ?: null,
            'recruit_nickname' => $this->request->getPost('recruit_nickname') ?: null,
            'recruit_weight' => $this->request->getPost('recruit_weight') ?: null,
            'recruit_height' => $this->request->getPost('recruit_height') ?: null,
            'recruit_fatherName' => $this->request->getPost('recruit_fatherName') ?: null,
            'recruit_motherName' => $this->request->getPost('recruit_motherName') ?: null,
            'recruit_fatherJob' => $this->request->getPost('recruit_fatherJob') ?: null,
            'recruit_motherJob' => $this->request->getPost('recruit_motherJob') ?: null,
        ];

        $currentRecruit = $model->find($id);
        $regLevel = $currentRecruit['recruit_regLevel'];
        $remoteUpload = new \App\Libraries\RemoteUpload();

        // Handle file uploads
        $fileFields = [
            'recruit_img' => 'img',
            'recruit_certificateEdu' => 'certificate',
            'recruit_certificateEduB' => 'certificateB',
            'recruit_copyidCard' => 'copyidCard',
            'recruit_copyAddress' => 'copyAddress',
        ];

        // Handle direct cropped image (Base64 from Cropper.js)
        $croppedImg = $this->request->getPost('recruit_img_cropped');
        if (!empty($croppedImg)) {
            $subPath = 'admission/recruitstudent/m' . $regLevel . '/img';
            $remoteUpload = new \App\Libraries\RemoteUpload();
            $result = $remoteUpload->uploadBase64($croppedImg, $subPath, 'student_photo_' . $id);
            if ($result && $result['status'] === 'success') {
                $data['recruit_img'] = $result['filename'];
                // Delete old file if exists
                if (!empty($currentRecruit['recruit_img'])) {
                    $remoteUpload->delete($currentRecruit['recruit_img'], $subPath);
                }
            }
        }

        foreach ($fileFields as $field => $folder) {
            // Skip recruit_img if already handled by cropper
            if ($field === 'recruit_img' && !empty($croppedImg)) continue;
            
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $subPath = 'admission/recruitstudent/m' . $regLevel . '/' . $folder;
                $result = $remoteUpload->upload($file, $subPath);

                if ($result && $result['status'] === 'success') {
                    $data[$field] = $result['filename'];
                    // Delete old file if exists
                    if (!empty($currentRecruit[$field])) {
                        $remoteUpload->delete($currentRecruit[$field], $subPath);
                    }
                }
            }
        }

        // Handle ability certificate (multiple files)
        $abilityFiles = $this->request->getFileMultiple('recruit_certificateAbility');
        if (!empty($abilityFiles) && $abilityFiles[0]->isValid()) {
            $subPath = 'admission/recruitstudent/m' . $regLevel . '/certificateAbility';
            $newAbilityFiles = [];
            foreach ($abilityFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $result = $remoteUpload->upload($file, $subPath);
                    if ($result && $result['status'] === 'success') {
                        $newAbilityFiles[] = $result['filename'];
                    }
                }
            }
            if (!empty($newAbilityFiles)) {
                $data['recruit_certificateAbility'] = implode('|', $newAbilityFiles);
            }
        }

        $model->update($id, $data);

        return redirect()->to(site_url('skjadmin/recruits/view/' . $id))->with('success', 'อัปเดตข้อมูลผู้สมัครสำเร็จ');
    }

    public function delete($id = null)
    {
        $model = new AdmissionModel();
        $recruit = $model->find($id); // Find the record first

        if ($recruit) {
            $remoteUpload = new \App\Libraries\RemoteUpload();
            $regLevel = $recruit['recruit_regLevel'];

            // Array of fields and their corresponding subdirectories
            $fileFields = [
                'recruit_img' => 'img',
                'recruit_certificateEdu' => 'certificate',
                'recruit_certificateEduB' => 'certificateB',
                'recruit_copyidCard' => 'copyidCard',
                'recruit_copyAddress' => 'copyAddress',
            ];

            // Delete individual files
            foreach ($fileFields as $field => $folder) {
                if (!empty($recruit[$field])) {
                    $subPath = 'admission/recruitstudent/m' . $regLevel . '/' . $folder;
                    $remoteUpload->delete($recruit[$field], $subPath);
                }
            }

            // Delete ability certificate files (which can be multiple)
            if (!empty($recruit['recruit_certificateAbility'])) {
                $ability_files = explode('|', $recruit['recruit_certificateAbility']);
                if (count($ability_files) > 0) {
                    $subPath = 'admission/recruitstudent/m' . $regLevel . '/certificateAbility';
                    $remoteUpload->delete($ability_files, $subPath);
                }
            }
        }

        $model->delete($id); // Now delete the DB record

        return redirect()->to(site_url('skjadmin/recruits'))->with('success', 'ลบข้อมูลผู้สมัครและไฟล์ที่เกี่ยวข้องสำเร็จ');
    }

    public function updateStatus()
    {
        try {
            $id = $this->request->getVar('id');
            $status = $this->request->getVar('status');

            if ($id && $status) {
                $model = new AdmissionModel();
                $updated = $model->update($id, [
                    'recruit_status' => $status,
                    'recruit_userUpdate' => session()->get('pers_id'),
                    'recruit_dateUpdate' => date('Y-m-d H:i:s')
                ]);

                if ($updated) {
                    return $this->response->setJSON(['success' => true]);
                }
            }

            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Missing ID or Status',
                'debug' => [
                    'id' => $id,
                    'status' => $status,
                    'method' => $this->request->getMethod(),
                    'post' => $this->request->getPost(),
                    'get' => $this->request->getGet(),
                    'all_vars' => $this->request->getVar()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update sport selection result via AJAX
     */
    public function updateSportResult()
    {
        $request = service('request');
        $id = $request->getPost('id');
        $result = $request->getPost('result');

        $validResults = ['รอคัดเลือก', 'ผ่านการคัดเลือก', 'ไม่ผ่านการคัดเลือก', 'ไม่มาคัดเลือก'];

        if ($id && in_array($result, $validResults)) {
            $model = new AdmissionModel();
            $updated = $model->update($id, [
            'recruit_sportSelectionResult' => $result,
            'recruit_userUpdate' => session()->get('pers_id'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s')
        ]);

            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }

    /**
     * Update quiz/written exam result via AJAX
     */
    public function updateQuizResult()
    {
        $request = service('request');
        $id = $request->getPost('id');
        $result = $request->getPost('result');

        $validResults = ['รอสอบ', 'สอบผ่าน', 'สอบไม่ผ่าน', 'ไม่มาสอบ'];

        if ($id && in_array($result, $validResults)) {
            $model = new AdmissionModel();
            $updated = $model->update($id, [
            'recruit_StatusQuiz' => $result,
            'recruit_userUpdate' => session()->get('pers_id'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s')
        ]);

            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }

    public function getRecruitsAjax()
    {
        $request = service('request');
        $model = new AdmissionModel();

        $draw = intval($request->getVar('draw'));
        $start = intval($request->getVar('start'));
        $length = intval($request->getVar('length'));

        $search = $request->getVar('search');
        $searchValue = $search['value'] ?? '';

        $year = $request->getVar('year') ?? date('Y');
        $statusFilter = $request->getVar('status_filter') ?? '';
        $roundFilter = $request->getVar('round_filter') ?? '';
        $quotaFilter = $request->getVar('quota_filter') ?? '';
        $courseFilter = $request->getVar('course_filter') ?? '';
        $regLevelFilter = $request->getVar('reg_level_filter') ?? '';
        $excellenceFilter = $request->getVar('excellence_filter') ?? '';
        $advancedSearch = $request->getVar('advanced_search') ?? '';

        // 1. Get Total Records (for this year)
        $totalRecords = $model->where('recruit_year', $year)->countAllResults();

        // 2. Count Filtered Records
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->where('tb_recruitstudent.recruit_year', $year);

        // Apply status filter
        if (!empty($statusFilter)) {
            if ($statusFilter === 'ไม่ผ่าน') {
                $builder->like('tb_recruitstudent.recruit_status', 'ไม่ผ่าน');
            } else {
                $builder->where('tb_recruitstudent.recruit_status', $statusFilter);
            }
        }
        if (!empty($roundFilter)) {
            $builder->where('tb_recruitstudent.recruit_round', $roundFilter);
        }
        // Apply quota filter
        if (!empty($quotaFilter)) {
            $builder->where('tb_recruitstudent.recruit_category', $quotaFilter);
        }
        // Apply course filter
        if (!empty($courseFilter)) {
            $builder->where('tb_recruitstudent.recruit_tpyeRoom_id', $courseFilter);
        }
        // Apply regLevel filter
        if (!empty($regLevelFilter)) {
            $builder->where('tb_recruitstudent.recruit_regLevel', $regLevelFilter);
        }
        // Apply excellence filter (sport/normal) based on course_initials
        if (!empty($excellenceFilter)) {
            if ($excellenceFilter === 'sport') {
                // Excellence/Sport courses have specific initials (not empty)
                $builder->where('tb_course.course_initials IS NOT NULL');
                $builder->where('tb_course.course_initials !=', '');
            } elseif ($excellenceFilter === 'normal') {
                // Normal courses have NULL or empty initials
                $builder->groupStart()
                    ->where('tb_course.course_initials IS NULL')
                    ->orWhere('tb_course.course_initials', '')
                    ->groupEnd();
            }
        }

        // Apply DataTables search (from search box in DataTable)
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('tb_course.course_fullname', $searchValue)
                ->orLike('tb_course.course_branch', $searchValue)
                ->orLike('tb_recruitstudent.recruit_regLevel', $searchValue)
                ->orLike('tb_recruitstudent.recruit_firstName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_lastName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_id', $searchValue)
                ->orLike('tb_recruitstudent.recruit_idCard', $searchValue)
                ->orLike('tb_recruitstudent.recruit_phone', $searchValue)
                ->orLike('tb_quota.quota_explain', $searchValue)
                ->groupEnd();
        }

        // Apply advanced search (searches more fields including ID card, phone, old school)
        if (!empty($advancedSearch)) {
            $builder->groupStart()
                ->like('tb_recruitstudent.recruit_id', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_firstName', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_lastName', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_idCard', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_phone', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_oldSchool', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_address', $advancedSearch)
                ->orLike('tb_quota.quota_explain', $advancedSearch)
                ->orLike('tb_course.course_branch', $advancedSearch)
                ->orLike('tb_course.course_fullname', $advancedSearch)
                ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults(); // Consumes builder

        // 3. Fetch Data
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id, tb_recruitstudent.recruit_prefix, tb_recruitstudent.recruit_firstName, tb_recruitstudent.recruit_lastName, tb_recruitstudent.recruit_regLevel, tb_recruitstudent.recruit_img, tb_recruitstudent.recruit_round, tb_quota.quota_explain, tb_recruitstudent.recruit_category, tb_course.course_branch, tb_course.course_fullname, tb_course.course_initials, tb_recruitstudent.recruit_tpyeRoom, tb_recruitstudent.recruit_status, tb_recruitstudent.recruit_majorOrder, tb_quota.quota_key, tb_recruitstudent.recruit_sportPosition, tb_recruitstudent.recruit_sportSelectionResult, tb_recruitstudent.recruit_StatusQuiz, tb_recruitstudent.recruit_dateUpdate, tb_recruitstudent.recruit_certificateEdu, tb_recruitstudent.recruit_certificateEduB, tb_recruitstudent.recruit_copyidCard, skjacth_personnel.tb_personnel.pers_prefix as verifier_prefix, skjacth_personnel.tb_personnel.pers_firstname as verifier_fname, skjacth_personnel.tb_personnel.pers_lastname as verifier_lname')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->join('skjacth_personnel.tb_personnel', 'skjacth_personnel.tb_personnel.pers_id = tb_recruitstudent.recruit_userUpdate', 'left')
            ->where('tb_recruitstudent.recruit_year', $year);

        // Apply status filter
        if (!empty($statusFilter)) {
            if ($statusFilter === 'ไม่ผ่าน') {
                $builder->like('tb_recruitstudent.recruit_status', 'ไม่ผ่าน');
            } else {
                $builder->where('tb_recruitstudent.recruit_status', $statusFilter);
            }
        }
        if (!empty($roundFilter)) {
            $builder->where('tb_recruitstudent.recruit_round', $roundFilter);
        }
        // Apply quota filter
        if (!empty($quotaFilter)) {
            $builder->where('tb_recruitstudent.recruit_category', $quotaFilter);
        }
        // Apply course filter
        if (!empty($courseFilter)) {
            $builder->where('tb_recruitstudent.recruit_tpyeRoom_id', $courseFilter);
        }
        // Apply regLevel filter
        if (!empty($regLevelFilter)) {
            $builder->where('tb_recruitstudent.recruit_regLevel', $regLevelFilter);
        }
        // Apply excellence filter (sport/normal) based on course_initials
        if (!empty($excellenceFilter)) {
            if ($excellenceFilter === 'sport') {
                $builder->where('tb_course.course_initials IS NOT NULL');
                $builder->where('tb_course.course_initials !=', '');
            } elseif ($excellenceFilter === 'normal') {
                $builder->groupStart()
                    ->where('tb_course.course_initials IS NULL')
                    ->orWhere('tb_course.course_initials', '')
                    ->groupEnd();
            }
        }
        // Apply DataTables search
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('tb_course.course_fullname', $searchValue)
                ->orLike('tb_course.course_branch', $searchValue)
                ->orLike('tb_recruitstudent.recruit_regLevel', $searchValue)
                ->orLike('tb_recruitstudent.recruit_firstName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_lastName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_id', $searchValue)
                ->orLike('tb_recruitstudent.recruit_idCard', $searchValue)
                ->orLike('tb_recruitstudent.recruit_phone', $searchValue)
                ->orLike('tb_quota.quota_explain', $searchValue)
                ->groupEnd();
        }

        // Apply advanced search
        if (!empty($advancedSearch)) {
            $builder->groupStart()
                ->like('tb_recruitstudent.recruit_id', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_firstName', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_lastName', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_idCard', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_phone', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_oldSchool', $advancedSearch)
                ->orLike('tb_recruitstudent.recruit_address', $advancedSearch)
                ->orLike('tb_quota.quota_explain', $advancedSearch)
                ->orLike('tb_course.course_branch', $advancedSearch)
                ->orLike('tb_course.course_fullname', $advancedSearch)
                ->groupEnd();
        }

        // Apply Pagination
        if ($length > 0) {
            $builder->limit($length, $start);
        }


        // ลบ groupBy ออกเพราะ recruit_id เป็น Primary Key ไม่ต้อง GROUP BY
        $builder->orderBy('tb_recruitstudent.recruit_id', 'DESC');

        $recruits = $builder->get()->getResultArray();

        $data = [];
        foreach ($recruits as $recruit) {
            $status = $recruit['recruit_status'] ?? 'รอการตรวจสอบ';
            $statusClass = 'status-pending'; // Default: orange/warning
            if ($status === 'ผ่านการตรวจสอบ') {
                $statusClass = 'status-approved'; // Green
            } elseif ($status === 'รอการตรวจสอบ') {
                $statusClass = 'status-pending'; // Orange
            } elseif (strpos($status, 'ไม่ผ่าน') !== false) {
                $statusClass = 'status-rejected'; // Red
            }

            // Generate avatar with lazy loading - use direct URL for better browser accessibility
            $imgSrc = get_recruit_file_url(($recruit['recruit_img'] ?? 'default.png'), ($recruit['recruit_regLevel'] ?? '1'), 'img', true);
            $defaultImg = base_url('sneat-assets/img/avatars/1.png');
            $avatar = '<img src="' . $imgSrc . '" class="recruit-avatar" alt="Avatar" loading="lazy" onerror="this.onerror=null;this.src=\'' . $defaultImg . '\';">';

            // Check if this is a sports applicant
            $isSport = (
                (!empty($recruit['recruit_sportPosition']) && $recruit['recruit_sportPosition'] !== '-') ||
                (isset($recruit['course_fullname']) && mb_strpos($recruit['course_fullname'], 'กีฬา') !== false) ||
                (isset($recruit['course_branch']) && mb_strpos($recruit['course_branch'], 'กีฬา') !== false) ||
                (isset($recruit['quota_key']) && $recruit['quota_key'] === 'sport')
            );

            // Generate action dropdown menu
            $printMenuItems = '';
            if ($isSport) {
                // Sport applicant: show both print options
                $printMenuItems = '
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-run me-2 text-purple"></i>พิมพ์ใบสมัครกีฬา</a></li>
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print-normal/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-printer me-2 text-info"></i>พิมพ์ใบสมัครธรรมดา</a></li>';
            } else {
                // Regular applicant: show only normal print
                $printMenuItems = '
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-printer me-2 text-info"></i>พิมพ์ใบสมัคร</a></li>';
            }

            // Build download menu items based on available files
            $regLevel = $recruit['recruit_regLevel'] ?? '1';
            $downloadMenuItems = '';
            
            // Check and add download links for each file type
            if (!empty($recruit['recruit_certificateEdu'])) {
                $certEduUrl = get_recruit_file_url($recruit['recruit_certificateEdu'], $regLevel, 'certificate');
                $downloadMenuItems .= '<li><a class="dropdown-item" href="' . $certEduUrl . '" target="_blank" download><i class="bx bx-download me-2 text-success"></i>ปพ.1 ด้านหน้า</a></li>';
            }
            if (!empty($recruit['recruit_certificateEduB'])) {
                $certEduBUrl = get_recruit_file_url($recruit['recruit_certificateEduB'], $regLevel, 'certificateB');
                $downloadMenuItems .= '<li><a class="dropdown-item" href="' . $certEduBUrl . '" target="_blank" download><i class="bx bx-download me-2 text-success"></i>ปพ.1 ด้านหลัง</a></li>';
            }
            if (!empty($recruit['recruit_copyidCard'])) {
                $copyIdUrl = get_recruit_file_url($recruit['recruit_copyidCard'], $regLevel, 'copyidCard');
                $downloadMenuItems .= '<li><a class="dropdown-item" href="' . $copyIdUrl . '" target="_blank" download><i class="bx bx-download me-2 text-success"></i>สำเนาบัตรประชาชน</a></li>';
            }
            
            // Add divider and download section only if there are files
            $downloadSection = '';
            if (!empty($downloadMenuItems)) {
                $downloadSection = '
                    <li><hr class="dropdown-divider"></li>
                    <li class="dropdown-header px-3 py-1 text-muted small"><i class="bx bx-folder-open me-1"></i>ดาวน์โหลดเอกสาร</li>
                    ' . $downloadMenuItems;
            }

            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-cog"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) . '"><i class="bx bx-show me-2 text-primary"></i>ดูข้อมูลทั้งหมด</a></li>
                        <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/edit/' . $recruit['recruit_id']) . '"><i class="bx bx-edit me-2 text-warning"></i>แก้ไขข้อมูล</a></li>
                        <li><hr class="dropdown-divider"></li>
                        ' . $printMenuItems . $downloadSection . '
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDelete(' . $recruit['recruit_id'] . ')"><i class="bx bx-trash me-2"></i>ลบข้อมูล</a></li>
                    </ul>
                </div>';


            // Build course display with all ranks from recruit_majorOrder
            $courseHtml = '';
            if (!empty($recruit['recruit_majorOrder'])) {
                $courseIds = explode('|', $recruit['recruit_majorOrder']);
                if (count($courseIds) > 0) {
                    // Get course model and fetch all courses at once
                    $courseModel = new \App\Models\CourseModel();
                    $courses = $courseModel->select('course_id, course_branch')
                        ->whereIn('course_id', $courseIds)
                        ->findAll();

                    // Create a map for quick lookup
                    $courseMap = [];
                    foreach ($courses as $course) {
                        $courseMap[$course['course_id']] = $course['course_branch'];
                    }

                    // Build HTML with order numbers
                    $courseItems = [];
                    foreach ($courseIds as $index => $id) {
                        $orderNum = $index + 1;
                        $courseName = $courseMap[$id] ?? 'ไม่พบ';
                        $badgeColor = 'bg-label-info';
                        if ($orderNum == 1) {
                            $badgeColor = 'bg-label-primary';
                        } elseif ($orderNum == 2) {
                            $badgeColor = 'bg-label-success';
                        } elseif ($orderNum == 3) {
                            $badgeColor = 'bg-label-warning';
                        }
                        $courseItems[] = '<span class="badge ' . $badgeColor . ' me-1 mb-1" title="อันดับที่ ' . $orderNum . '">' . $orderNum . '. ' . esc($courseName) . '</span>';
                    }
                    $courseHtml = '<div class="d-flex flex-wrap gap-1">' . implode('', $courseItems) . '</div>';
                }
            }

            // Fallback if no majorOrder
            if (empty($courseHtml)) {
                $courseHtml = '<span class="badge bg-label-info">' . esc($recruit['course_branch'] ?? $recruit['course_fullname'] ?? $recruit['recruit_tpyeRoom']) . '</span>';
            }

            // Build selection result display - single column for both sport and non-sport
            // Shows sport selection for sport applicants, quiz result for non-sport
            $selectionResultHtml = '';
            if ($isSport) {
                // Sport applicant - show sport selection dropdown
                $sportResult = $recruit['recruit_sportSelectionResult'] ?? 'รอคัดเลือก';
                $selectionResultHtml = '
                    <div class="d-flex align-items-center gap-1 justify-content-center">
                        <span class="badge bg-label-purple rounded-pill" title="นักกีฬา"><i class="bx bx-run"></i></span>
                        <select class="form-select form-select-sm sport-result-select" data-id="' . $recruit['recruit_id'] . '" style="width: auto; font-size: 0.75rem; padding: 0.25rem 1.5rem 0.25rem 0.5rem;">
                            <option value="รอคัดเลือก"' . ($sportResult === 'รอคัดเลือก' || empty($sportResult) ? ' selected' : '') . '>⏳ รอคัดเลือก</option>
                            <option value="ผ่านการคัดเลือก"' . ($sportResult === 'ผ่านการคัดเลือก' ? ' selected' : '') . '>✅ ผ่านการคัดเลือก</option>
                            <option value="ไม่ผ่านการคัดเลือก"' . ($sportResult === 'ไม่ผ่านการคัดเลือก' ? ' selected' : '') . '>❌ ไม่ผ่านการคัดเลือก</option>
                            <option value="ไม่มาคัดเลือก"' . ($sportResult === 'ไม่มาคัดเลือก' ? ' selected' : '') . '>🚫 ไม่มาคัดเลือก</option>
                        </select>
                    </div>';
            } else {
                // Non-sport applicant - show quiz result dropdown
                $quizResult = $recruit['recruit_StatusQuiz'] ?? 'รอสอบ';
                $selectionResultHtml = '
                    <div class="d-flex align-items-center gap-1 justify-content-center">
                        <span class="badge bg-label-info rounded-pill" title="สอบข้อเขียน"><i class="bx bx-edit"></i></span>
                        <select class="form-select form-select-sm quiz-result-select" data-id="' . $recruit['recruit_id'] . '" style="width: auto; font-size: 0.75rem; padding: 0.25rem 1.5rem 0.25rem 0.5rem;">
                            <option value="รอสอบ"' . ($quizResult === 'รอสอบ' || empty($quizResult) ? ' selected' : '') . '>⏳ รอสอบ</option>
                            <option value="สอบผ่าน"' . ($quizResult === 'สอบผ่าน' ? ' selected' : '') . '>✅ สอบผ่าน</option>
                            <option value="สอบไม่ผ่าน"' . ($quizResult === 'สอบไม่ผ่าน' ? ' selected' : '') . '>❌ สอบไม่ผ่าน</option>
                            <option value="ไม่มาสอบ"' . ($quizResult === 'ไม่มาสอบ' ? ' selected' : '') . '>🚫 ไม่มาสอบ</option>
                        </select>
                    </div>';
            }

            $verifierName = '-';
            if (!empty($recruit['verifier_fname'])) {
                $verifierName = esc($recruit['verifier_prefix'] . $recruit['verifier_fname'] . ' ' . $recruit['verifier_lname']);
                if (!empty($recruit['recruit_dateUpdate'])) {
                    $verifierName .= '<div class="text-muted" style="font-size: 0.65rem; line-height: 1.2;">(' . date('d/m/Y H:i', strtotime($recruit['recruit_dateUpdate'])) . ')</div>';
                }
            }

            // Build excellence type display based on course_initials
            $excellenceTypeHtml = '';
            $courseInitials = $recruit['course_initials'] ?? '';
            if (!empty($courseInitials)) {
                // Has course_initials = Excellence/Special program
                $excellenceTypeHtml = '<span class="text-purple fw-semibold">' . esc($courseInitials) . '</span>';
            } else {
                // No course_initials = Normal program
                $excellenceTypeHtml = '<span class="text-muted">ทั่วไป</span>';
            }

            $data[] = [
                'avatar' => $avatar,
                'recruit_id' => '<span class="badge bg-label-secondary">' . esc(sprintf('%04d', $recruit['recruit_id'] ?? 0)) . '</span>',
                'name' => '<div class="fw-semibold">' . esc(($recruit['recruit_prefix'] ?? '') . ($recruit['recruit_firstName'] ?? '')) . '</div><small class="text-muted">' . esc($recruit['recruit_lastName'] ?? '') . '</small>',
                'round' => '<span class="badge bg-label-dark">รอบที่ ' . esc($recruit['recruit_round'] ?? '1') . '</span>',
                'reg_level' => '<span class="badge bg-label-primary">ม.' . esc($recruit['recruit_regLevel'] ?? '') . '</span>',
                'category' => '<small>' . esc($recruit['quota_explain'] ?? $recruit['recruit_category']) . '</small>',
                'excellence_type' => $excellenceTypeHtml,
                'course' => $courseHtml,
                'selection_result' => $selectionResultHtml,
                'status' => '<span class="status-badge ' . $statusClass . '">' . esc($status) . '</span>',
                'verifier' => $verifierName,
                'actions' => $actions
            ];
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
        ];

        return $this->response->setJSON($output);
    }

    /**
     * Get all recruits for client-side DataTable (no pagination)
     */
    public function getRecruitsAll()
    {
        $request = service('request');
        $model = new AdmissionModel();

        $year = $request->getVar('year') ?? date('Y');
        $statusFilter = $request->getVar('status_filter') ?? '';
        $roundFilter = $request->getVar('round_filter') ?? '';

        // Build query
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id, tb_recruitstudent.recruit_prefix, tb_recruitstudent.recruit_firstName, tb_recruitstudent.recruit_lastName, tb_recruitstudent.recruit_regLevel, tb_recruitstudent.recruit_img, tb_recruitstudent.recruit_round, tb_quota.quota_explain, tb_recruitstudent.recruit_category, tb_course.course_branch, tb_course.course_fullname, tb_course.course_initials, tb_recruitstudent.recruit_tpyeRoom, tb_recruitstudent.recruit_status, tb_recruitstudent.recruit_majorOrder, tb_quota.quota_key, tb_recruitstudent.recruit_sportPosition, tb_recruitstudent.recruit_sportSelectionResult, tb_recruitstudent.recruit_StatusQuiz, tb_recruitstudent.recruit_dateUpdate, tb_recruitstudent.recruit_certificateEdu, tb_recruitstudent.recruit_certificateEduB, tb_recruitstudent.recruit_copyidCard, skjacth_personnel.tb_personnel.pers_prefix as verifier_prefix, skjacth_personnel.tb_personnel.pers_firstname as verifier_fname, skjacth_personnel.tb_personnel.pers_lastname as verifier_lname')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->join('skjacth_personnel.tb_personnel', 'skjacth_personnel.tb_personnel.pers_id = tb_recruitstudent.recruit_userUpdate', 'left')
            ->where('tb_recruitstudent.recruit_year', $year);

        // Apply status filter
        if (!empty($statusFilter)) {
            if ($statusFilter === 'ไม่ผ่าน') {
                $builder->like('tb_recruitstudent.recruit_status', 'ไม่ผ่าน');
            } else {
                $builder->where('tb_recruitstudent.recruit_status', $statusFilter);
            }
        }

        // Apply round filter
        if (!empty($roundFilter)) {
            $builder->where('tb_recruitstudent.recruit_round', $roundFilter);
        }

        $builder->orderBy('tb_recruitstudent.recruit_id', 'DESC');
        $recruits = $builder->get()->getResultArray();

        // Build data array
        $data = [];
        foreach ($recruits as $recruit) {
            // Avatar - use same method as getRecruitsAjax
            $imgSrc = get_recruit_file_url(($recruit['recruit_img'] ?? 'default.png'), ($recruit['recruit_regLevel'] ?? '1'), 'img', true);
            $defaultImg = base_url('sneat-assets/img/avatars/1.png');
            $avatar = '<img src="' . $imgSrc . '" class="recruit-avatar" alt="Avatar" loading="lazy" onerror="this.onerror=null;this.src=\'' . $defaultImg . '\';">';

            // Status
            $status = $recruit['recruit_status'] ?? 'รอการตรวจสอบ';
            $statusClass = 'status-pending';
            if ($status === 'ผ่านการตรวจสอบ') {
                $statusClass = 'status-approved';
            } elseif (strpos($status, 'ไม่ผ่าน') !== false) {
                $statusClass = 'status-rejected';
            }

            // Check if sport candidate
            $quotaKey = $recruit['quota_key'] ?? '';
            $isSport = !empty($quotaKey) && strpos($quotaKey, 'A') === 0;

            // Print menu items
            if ($isSport) {
                $printMenuItems = '
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-run me-2 text-purple"></i>พิมพ์ใบสมัครกีฬา</a></li>
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print-normal/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-printer me-2 text-info"></i>พิมพ์ใบสมัครธรรมดา</a></li>';
            } else {
                $printMenuItems = '
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-printer me-2 text-info"></i>พิมพ์ใบสมัคร</a></li>';
            }

            // Actions dropdown
            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-cog"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) . '"><i class="bx bx-show me-2 text-primary"></i>ดูข้อมูล</a></li>
                        <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/edit/' . $recruit['recruit_id']) . '"><i class="bx bx-edit me-2 text-warning"></i>แก้ไข</a></li>
                        <li><hr class="dropdown-divider"></li>
                        ' . $printMenuItems . '
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDelete(' . $recruit['recruit_id'] . ')"><i class="bx bx-trash me-2"></i>ลบ</a></li>
                    </ul>
                </div>';

            // Course display
            $courseHtml = '<span class="badge bg-label-info">' . esc($recruit['course_branch'] ?? $recruit['course_fullname'] ?? $recruit['recruit_tpyeRoom']) . '</span>';

            // Selection result
            if ($isSport) {
                $sportResult = $recruit['recruit_sportSelectionResult'] ?? 'รอคัดเลือก';
                $selectionResultHtml = '
                    <select class="form-select form-select-sm sport-result-select" data-id="' . $recruit['recruit_id'] . '" style="width: auto; font-size: 0.75rem;">
                        <option value="รอคัดเลือก"' . ($sportResult === 'รอคัดเลือก' || empty($sportResult) ? ' selected' : '') . '>⏳ รอ</option>
                        <option value="ผ่านการคัดเลือก"' . ($sportResult === 'ผ่านการคัดเลือก' ? ' selected' : '') . '>✅ ผ่าน</option>
                        <option value="ไม่ผ่านการคัดเลือก"' . ($sportResult === 'ไม่ผ่านการคัดเลือก' ? ' selected' : '') . '>❌ ไม่ผ่าน</option>
                    </select>';
            } else {
                $quizResult = $recruit['recruit_StatusQuiz'] ?? 'รอสอบ';
                $selectionResultHtml = '
                    <select class="form-select form-select-sm quiz-result-select" data-id="' . $recruit['recruit_id'] . '" style="width: auto; font-size: 0.75rem;">
                        <option value="รอสอบ"' . ($quizResult === 'รอสอบ' || empty($quizResult) ? ' selected' : '') . '>⏳ รอ</option>
                        <option value="สอบผ่าน"' . ($quizResult === 'สอบผ่าน' ? ' selected' : '') . '>✅ ผ่าน</option>
                        <option value="สอบไม่ผ่าน"' . ($quizResult === 'สอบไม่ผ่าน' ? ' selected' : '') . '>❌ ไม่ผ่าน</option>
                    </select>';
            }

            // Excellence type
            $courseInitials = $recruit['course_initials'] ?? '';
            if (!empty($courseInitials)) {
                $excellenceTypeHtml = '<span class="text-purple fw-semibold">' . esc($courseInitials) . '</span>';
            } else {
                $excellenceTypeHtml = '<span class="text-muted">ทั่วไป</span>';
            }

            $data[] = [
                'avatar' => $avatar,
                'recruit_id' => '<span class="badge bg-label-secondary">' . esc(sprintf('%04d', $recruit['recruit_id'] ?? 0)) . '</span>',
                'name' => '<div class="fw-semibold">' . esc(($recruit['recruit_prefix'] ?? '') . ($recruit['recruit_firstName'] ?? '')) . '</div><small class="text-muted">' . esc($recruit['recruit_lastName'] ?? '') . '</small>',
                'round' => '<span class="badge bg-label-dark">รอบที่ ' . esc($recruit['recruit_round'] ?? '1') . '</span>',
                'reg_level' => '<span class="badge bg-label-primary">ม.' . esc($recruit['recruit_regLevel'] ?? '') . '</span>',
                'excellence_type' => $excellenceTypeHtml,
                'course' => $courseHtml,
                'selection_result' => $selectionResultHtml,
                'status' => '<span class="status-badge ' . $statusClass . '">' . esc($status) . '</span>',
                'actions' => $actions
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * Get statistics for the recruits
     */
    public function getStats()
    {
        $request = service('request');
        $year = $request->getVar('year') ?? date('Y');

        // Create fresh model instances for each count to avoid query builder condition accumulation
        $total = (new AdmissionModel())->where('recruit_year', $year)->countAllResults();

        $approved = (new AdmissionModel())
            ->where('recruit_year', $year)
            ->where('recruit_status', 'ผ่านการตรวจสอบ')
            ->countAllResults();

        $pending = (new AdmissionModel())
            ->where('recruit_year', $year)
            ->where('recruit_status', 'รอการตรวจสอบ')
            ->countAllResults();

        $rejected = (new AdmissionModel())
            ->where('recruit_year', $year)
            ->like('recruit_status', 'ไม่ผ่าน')
            ->countAllResults();

        // Count students who passed selection (quiz or sport)
        $passedSelection = (new AdmissionModel())
            ->where('recruit_year', $year)
            ->groupStart()
                ->where('recruit_StatusQuiz', 'สอบผ่าน')
                ->orWhere('recruit_sportSelectionResult', 'ผ่านการคัดเลือก')
            ->groupEnd()
            ->countAllResults();

        return $this->response->setJSON([
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected,
            'passedSelection' => $passedSelection
        ]);
    }

    /**
     * Export recruits data to CSV/Excel
     */
    public function export()
    {
        $request = service('request');
        $year = $request->getVar('year') ?? date('Y');
        $statusFilter = $request->getVar('status_filter') ?? '';
        $roundFilter = $request->getVar('round_filter') ?? '';
        $quotaFilter = $request->getVar('quota_filter') ?? '';
        $courseFilter = $request->getVar('course_filter') ?? '';
        $regLevelFilter = $request->getVar('reg_level_filter') ?? '';
        $excellenceFilter = $request->getVar('excellence_filter') ?? '';
        $searchValue = $request->getVar('search') ?? '';
        $format = $request->getVar('format') ?? 'csv';

        $model = new AdmissionModel();
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id, tb_recruitstudent.recruit_prefix, tb_recruitstudent.recruit_firstName, tb_recruitstudent.recruit_lastName, tb_recruitstudent.recruit_idCard, tb_recruitstudent.recruit_phone, tb_recruitstudent.recruit_birthday, tb_recruitstudent.recruit_oldSchool, tb_recruitstudent.recruit_grade, tb_recruitstudent.recruit_regLevel, tb_recruitstudent.recruit_round, tb_recruitstudent.recruit_status, tb_recruitstudent.recruit_address, tb_recruitstudent.recruit_province, tb_recruitstudent.recruit_district, tb_recruitstudent.recruit_date, tb_recruitstudent.recruit_sportSelectionResult, tb_recruitstudent.recruit_StatusQuiz, tb_quota.quota_explain, tb_course.course_fullname, tb_course.course_branch, tb_course.course_initials')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->where('tb_recruitstudent.recruit_year', $year);

        // Apply status filter
        if (!empty($statusFilter)) {
            if ($statusFilter === 'ไม่ผ่าน') {
                $builder->like('tb_recruitstudent.recruit_status', 'ไม่ผ่าน');
            } else {
                $builder->where('tb_recruitstudent.recruit_status', $statusFilter);
            }
        }

        // Apply round filter
        if (!empty($roundFilter)) {
            $builder->where('tb_recruitstudent.recruit_round', $roundFilter);
        }

        // Apply quota filter
        if (!empty($quotaFilter)) {
            $builder->where('tb_recruitstudent.recruit_category', $quotaFilter);
        }

        // Apply course filter
        if (!empty($courseFilter)) {
            $builder->where('tb_recruitstudent.recruit_tpyeRoom_id', $courseFilter);
        }

        // Apply regLevel filter
        if (!empty($regLevelFilter)) {
            $builder->where('tb_recruitstudent.recruit_regLevel', $regLevelFilter);
        }

        // Apply excellence filter based on course_initials
        if (!empty($excellenceFilter)) {
            if ($excellenceFilter === 'sport') {
                $builder->where('tb_course.course_initials IS NOT NULL');
                $builder->where('tb_course.course_initials !=', '');
            } elseif ($excellenceFilter === 'normal') {
                $builder->groupStart()
                    ->where('tb_course.course_initials IS NULL')
                    ->orWhere('tb_course.course_initials', '')
                    ->groupEnd();
            }
        }

        // Apply search filter
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('tb_recruitstudent.recruit_id', $searchValue)
                ->orLike('tb_recruitstudent.recruit_firstName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_lastName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_idCard', $searchValue)
                ->orLike('tb_recruitstudent.recruit_phone', $searchValue)
                ->orLike('tb_recruitstudent.recruit_oldSchool', $searchValue)
                ->orLike('tb_quota.quota_explain', $searchValue)
                ->orLike('tb_course.course_branch', $searchValue)
                ->orLike('tb_course.course_fullname', $searchValue)
                ->groupEnd();
        }

        $builder->orderBy('tb_recruitstudent.recruit_id', 'ASC');
        $recruits = $builder->get()->getResultArray();

        // Prepare CSV headers
        $headers = [
            'ลำดับ',
            'รหัสผู้สมัคร',
            'คำนำหน้า',
            'ชื่อ',
            'นามสกุล',
            'เลขบัตรประชาชน',
            'เบอร์โทร',
            'วันเกิด',
            'โรงเรียนเดิม',
            'เกรดเฉลี่ย',
            'ระดับชั้นที่สมัคร',
            'รอบที่',
            'ประเภทโควตา',
            'หลักสูตร',
            'สาขา',
            'ประเภท',
            'สถานะเอกสาร',
            'ผลคัดเลือกกีฬา',
            'ผลสอบข้อเขียน',
            'ที่อยู่',
            'อำเภอ',
            'จังหวัด',
            'วันที่สมัคร'
        ];

        // Create filename
        $ext = ($format === 'excel') ? 'csv' : 'csv';
        $filename = 'recruits_' . $year . '_' . date('Y-m-d_His') . '.' . $ext;

        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8 Excel compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Write headers
        fputcsv($output, $headers);

        // Write data
        $i = 1;
        foreach ($recruits as $recruit) {
            $excellenceType = (!empty($recruit['course_initials'])) ? $recruit['course_initials'] : 'ทั่วไป';
            
            $row = [
                $i++,
                sprintf('%04d', $recruit['recruit_id']),
                $recruit['recruit_prefix'] ?? '',
                $recruit['recruit_firstName'] ?? '',
                $recruit['recruit_lastName'] ?? '',
                $recruit['recruit_idCard'] ?? '',
                $recruit['recruit_phone'] ?? '',
                $recruit['recruit_birthday'] ?? '',
                $recruit['recruit_oldSchool'] ?? '',
                $recruit['recruit_grade'] ?? '',
                'ม.' . ($recruit['recruit_regLevel'] ?? ''),
                $recruit['recruit_round'] ?? '1',
                $recruit['quota_explain'] ?? '',
                $recruit['course_fullname'] ?? '',
                $recruit['course_branch'] ?? '',
                $excellenceType,
                $recruit['recruit_status'] ?? 'รอการตรวจสอบ',
                $recruit['recruit_sportSelectionResult'] ?? '-',
                $recruit['recruit_StatusQuiz'] ?? '-',
                $recruit['recruit_address'] ?? '',
                $recruit['recruit_district'] ?? '',
                $recruit['recruit_province'] ?? '',
                $recruit['recruit_date'] ?? ''
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    public function print($id)
    {
        $db = \Config\Database::connect();
        $model = new AdmissionModel();
        $recruit = $model->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_quota.quota_key, tb_course.course_fullname as course_name_joined, tb_course.course_branch')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->find($id);

        if (!$recruit) {
            return "ไม่พบข้อมูลผู้สมัคร";
        }

        // Standard Composer autoloading


        $customFontDir = FCPATH . 'public/fonts/sarabun';
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $tempDir = WRITEPATH . 'temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [$customFontDir]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'thsarabun.ttf',
                    'I' => 'thsarabun-italic.ttf',
                    'B' => 'thsarabun-bold.ttf',
                    'BI' => 'thsarabun-bolditalic.ttf',
                ]
            ],
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp'
        ]);

        // Prepare Data
        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        $date_Y = date('Y', strtotime($recruit['recruit_birthday'])) + 543;
        $date_D = date('j', strtotime($recruit['recruit_birthday']));
        $date_M = date('n', strtotime($recruit['recruit_birthday']));

        $date_Y_regis = date('Y', strtotime($recruit['recruit_date'])) + 543;
        $date_D_regis = date('j', strtotime($recruit['recruit_date']));
        $date_M_regis = date('n', strtotime($recruit['recruit_date']));

        // Calculate Age
        $birthDate = new \DateTime($recruit['recruit_birthday']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $sch = explode("โรงเรียน", $recruit['recruit_oldSchool']);
        $oldSchool = ($sch[0] == '' && isset($sch[1])) ? $sch[1] : $sch[0];

        $mpdf->SetTitle($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);

        // Generate HTML
        $html = '';
        $baseUrl = get_upload_base_url();
        $imgUrl = get_recruit_file_url($recruit['recruit_img'], $recruit['recruit_regLevel'], 'img');

        // Check if this is a sports excellence applicant
        $isSport = (
            (!empty($recruit['recruit_sportPosition']) && $recruit['recruit_sportPosition'] !== '-') ||
            (isset($recruit['course_name_joined']) && mb_strpos($recruit['course_name_joined'], 'กีฬา') !== false) ||
            (isset($recruit['course_branch']) && mb_strpos($recruit['course_branch'], 'กีฬา') !== false) ||
            (isset($recruit['quota_key']) && $recruit['quota_key'] === 'sport')
        );

        if ($isSport) {
            // Layout for Sport Excellence (A4) - Synced with UserControlAdmission coordinates
            $sportPdfTemplate = FCPATH . 'uploads/recruitstudent/registerSKJ_sport.pdf';
            if (!file_exists($sportPdfTemplate)) {
                return "ไม่พบไฟล์ Template PDF กีฬา: " . $sportPdfTemplate;
            }
            $mpdf->SetDocTemplate($sportPdfTemplate, true);
            $mpdf->AddPage();

            // Image (168, 10, 30, 40) - Adjusted to be further right but still safe
            if (!empty($recruit['recruit_img'])) {
                $mpdf->Image($imgUrl, 173, 10, 30, 40);
            }

            // Top Content - Year
            $mpdf->SetXY(147, 42);
            $yearDisplay = $recruit['recruit_year'];
            if ($recruit['recruit_year'] >= 2569 && !empty($recruit['recruit_round'])) {
                $yearDisplay .= ' (รอบที่ ' . $recruit['recruit_round'] . ')';
            }
            $mpdf->WriteHTML($yearDisplay);

            // Name and Gender
            $mpdf->SetXY(40, 58);
            $mpdf->WriteHTML($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);

            $checkMarkPath = FCPATH . 'uploads/recruitstudent/Check-Mark1.png';
            if ($recruit['recruit_prefix'] === 'เด็กหญิง' || $recruit['recruit_prefix'] === 'นางสาว' || (isset($recruit['recruit_gender']) && $recruit['recruit_gender'] === 'หญิง')) {
                $mpdf->Image($checkMarkPath, 120, 58, 7, 7);
            } else {
                $mpdf->Image($checkMarkPath, 108, 58, 7, 7);
            }

            // Age and Nickname
            $mpdf->SetXY(146, 58);
            $mpdf->WriteHTML($age);
            $mpdf->SetXY(175, 58);
            $mpdf->WriteHTML($recruit['recruit_nickname'] ?? '');

            // Birth Date and Physicals
            $mpdf->SetXY(30, 66);
            $mpdf->WriteHTML($date_D);
            $mpdf->SetXY(60, 66);
            $mpdf->WriteHTML($TH_Month[$date_M - 1]);
            $mpdf->SetXY(98, 66);
            $mpdf->WriteHTML($date_Y);
            $mpdf->SetXY(127, 66);
            $mpdf->WriteHTML($recruit['recruit_height'] ?? '');
            $mpdf->SetXY(173, 66);
            $mpdf->WriteHTML($recruit['recruit_weight'] ?? '');

            // Parents
            $mpdf->SetXY(40, 74);
            $mpdf->WriteHTML($recruit['recruit_fatherName'] ?? '');
            $mpdf->SetXY(130, 74);
            $mpdf->WriteHTML($recruit['recruit_fatherJob'] ?? '');
            $mpdf->SetXY(40, 82);
            $mpdf->WriteHTML($recruit['recruit_motherName'] ?? '');
            $mpdf->SetXY(130, 82);
            $mpdf->WriteHTML($recruit['recruit_motherJob'] ?? '');

            // Address
            $mpdf->SetXY(65, 90);
            $mpdf->WriteHTML($recruit['recruit_homeNumber']);
            $mpdf->SetXY(91, 90);
            $mpdf->WriteHTML($recruit['recruit_homeGroup'] ?? '-');
            $mpdf->SetXY(112, 90);
            $mpdf->WriteHTML($recruit['recruit_homeRoad'] ?? '-');
            $mpdf->SetXY(142, 90);
            $mpdf->WriteHTML($recruit['recruit_homeSubdistrict']);
            $mpdf->SetXY(30, 98);
            $mpdf->WriteHTML($recruit['recruit_homedistrict']);
            $mpdf->SetXY(75, 98);
            $mpdf->WriteHTML($recruit['recruit_homeProvince']);
            $mpdf->SetXY(123, 98);
            $mpdf->WriteHTML($recruit['recruit_homePostcode']);
            $mpdf->SetXY(165, 98);
            $mpdf->WriteHTML($recruit['recruit_phone']);

            // Education
            $mpdf->SetXY(123, 107);
            $mpdf->WriteHTML($date_Y_regis);
            $mpdf->SetXY(45, 115);
            $mpdf->WriteHTML($recruit['recruit_regLevel'] == 1 ? 'ป.6' : 'ม.3');
            $mpdf->SetXY(107, 115);
            $mpdf->WriteHTML($oldSchool);

            // Choice
            $mpdf->SetXY(50, 123);
            $mpdf->WriteHTML('มัธยมศึกษาปีที่ ' . $recruit['recruit_regLevel']);
            $mpdf->SetXY(109, 123);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');
            $mpdf->SetXY(162, 123);
            $mpdf->WriteHTML($recruit['recruit_sportPosition'] ?? '');

            // Signature
            $mpdf->SetXY(140, 141);
            $mpdf->WriteHTML($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);

            // Confirmation Part (Bottom)
            $mpdf->SetXY(140, 188);
            $yearDisplayBottom = $recruit['recruit_year'];
            if ($recruit['recruit_year'] >= 2569 && !empty($recruit['recruit_round'])) {
                $yearDisplayBottom .= ' (รอบที่ ' . $recruit['recruit_round'] . ')';
            }
            $mpdf->WriteHTML($yearDisplayBottom);
            $mpdf->SetXY(35, 196);
            $mpdf->WriteHTML($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);
            $mpdf->SetXY(118, 196);
            $mpdf->WriteHTML('มัธยมศึกษาปีที่ ' . $recruit['recruit_regLevel']);
            $mpdf->SetXY(167, 196);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');

            // Top Left & Bottom Right Tags
            $mpdf->SetXY(15, 18);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');
            $mpdf->SetXY(15, 26);
            $mpdf->WriteHTML($recruit['recruit_sportPosition'] ?? '');
            $mpdf->SetXY(164, 173);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');
            $mpdf->SetXY(164, 183);
            $mpdf->WriteHTML($recruit['recruit_sportPosition'] ?? '');

        } else {
            // Layout for Regular Application (registerSKJ.pdf)
            if (!empty($recruit['recruit_img'])) {
                $html .= '<div style="position:absolute;top:110px;left:620px; width:100%"><img style="width: 113.38px;height:151.18px;" src="' . $imgUrl . '"></div>';
            }

            $quotaDisplay = $recruit['quota_explain'];
            if ($recruit['recruit_year'] >= 2569 && !empty($recruit['recruit_round'])) {
                $quotaDisplay .= ' (รอบที่ ' . $recruit['recruit_round'] . ')';
            }
            $html .= '<div style="position:absolute;top:18px;left:100px; width:100%;font-size:16px;">' . $quotaDisplay . '</div>';
            $html .= '<div style="position:absolute;top:180px;left:555px; width:100%;font-size:24px;">' . $recruit['recruit_regLevel'] . '</div>';
            $html .= '<div style="position:absolute;top:63px;left:700px; width:100%">' . sprintf("%04d", $recruit['recruit_id']) . '</div>';
            $html .= '<div style="position:absolute;top:280px;left:180px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . '</div>';
            $html .= '<div style="position:absolute;top:280px;left:470px; width:100%">' . $recruit['recruit_lastName'] . '</div>';
            $html .= '<div style="position:absolute;top:307px;left:270px; width:100%">' . $oldSchool . '</div>';
            $html .= '<div style="position:absolute;top:335px;left:170px; width:100%">' . $recruit['recruit_district'] . '</div>';
            $html .= '<div style="position:absolute;top:335px;left:510px; width:100%">' . $recruit['recruit_province'] . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:160px; width:100%">' . $date_D . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:240px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:370px; width:100%">' . $date_Y . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:470px; width:100%">' . $age . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:600px; width:100%">' . $recruit['recruit_race'] . '</div>';
            $html .= '<div style="position:absolute;top:390px;left:162px; width:100%">' . $recruit['recruit_nationality'] . '</div>';
            $html .= '<div style="position:absolute;top:390px;left:300px; width:100%">' . $recruit['recruit_religion'] . '</div>';
            $html .= '<div style="position:absolute;top:390px;left:540px; width:100%">' . $recruit['recruit_idCard'] . '</div>';
            $html .= '<div style="position:absolute;top:418px;left:350px; width:100%">' . $recruit['recruit_phone'] . '</div>';
            $html .= '<div style="position:absolute;top:418px;left:600px; width:100%">' . $recruit['recruit_grade'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:270px; width:100%">' . $recruit['recruit_homeNumber'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:390px; width:100%">' . $recruit['recruit_homeGroup'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:475px; width:100%">' . $recruit['recruit_homeRoad'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:615px; width:100%">' . $recruit['recruit_homeSubdistrict'] . '</div>';
            $html .= '<div style="position:absolute;top:475px;left:180px; width:100%">' . $recruit['recruit_homedistrict'] . '</div>';
            $html .= '<div style="position:absolute;top:475px;left:400px; width:100%">' . $recruit['recruit_homeProvince'] . '</div>';
            $html .= '<div style="position:absolute;top:475px;left:620px; width:100%">' . $recruit['recruit_homePostcode'] . '</div>';
            $html .= '<div style="position:absolute;top:503px;left:695px; width:100%;font-size:22px;">' . $recruit['recruit_regLevel'] . '</div>';

            $html .= '<div style="position:absolute;top:880px;left:340px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName'] . '</div>';
            $html .= '<div style="position:absolute;top:905px;left:350px; width:100%">' . $date_D_regis . ' ' . $TH_Month[$date_M_regis - 1] . ' ' . $date_Y_regis . '</div>';

            // Major Order / Course Selection
            if ($recruit['quota_key'] == "normal") {
                $SubCourse = explode('|', $recruit['recruit_majorOrder']);
                $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
                foreach ($SubCourse as $key => $v_SubCourse) {
                    $CheckCourse = $db->table('tb_course')->select('course_initials')->where('course_id', $v_SubCourse)->get()->getRow();
                    if ($CheckCourse) {
                        $html .= "ลำดับที่ " . ($key + 1) . ' ' . $CheckCourse->course_initials . "<br>";
                    }
                }
                $html .= '</div>';
            } else {
                $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
                // Use course_name_joined if available, otherwise fallback to recruit_tpyeRoom
                $courseDisplay = !empty($recruit['course_name_joined']) ? $recruit['course_name_joined'] : $recruit['recruit_tpyeRoom'];
                $html .= "ลำดับที่ 1 " . $courseDisplay . ' สาขา ' . $recruit['recruit_major'];
                $html .= '</div>';
            }

            // Documents Checkmarks (using dejavusans font which is built-in to mPDF)
            $checkEmoji = '<span style="font-family: dejavusans; font-size: 30px; line-height: 1;">✔</span>';
            if (!empty($recruit['recruit_certificateEdu'])) {
                $html .= '<div style="position:absolute;top:785px;left:110px; width:100%;">' . $checkEmoji . '</div>';
            }
            if (!empty($recruit['recruit_copyidCard'])) {
                $html .= '<div style="position:absolute;top:785px;left:328px; width:100%;">' . $checkEmoji . '</div>';
            }
            if (!empty($recruit['recruit_img'])) {
                $html .= '<div style="position:absolute;top:788px;left:560px; width:100%;">' . $checkEmoji . '</div>';
            }

            $regularPdfTemplate = FCPATH . 'uploads/recruitstudent/registerSKJ.pdf';
            if (!file_exists($regularPdfTemplate)) {
                return "ไม่พบไฟล์ Template PDF ปกติ: " . $regularPdfTemplate;
            }
            $mpdf->SetDocTemplate($regularPdfTemplate, true);
        }

        $mpdf->WriteHTML($html);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Reg_' . sprintf("%04d", $recruit['recruit_id']) . '.pdf', 'I');
    }

    /**
     * Print Normal Application Form (always use regular template, not sport)
     * Used for sport applicants who passed selection and need regular enrollment form
     */
    public function printNormal($id)
    {
        $db = \Config\Database::connect();
        $model = new AdmissionModel();
        $recruit = $model->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_quota.quota_key, tb_course.course_fullname as course_name_joined, tb_course.course_branch')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->find($id);

        if (!$recruit) {
            return "ไม่พบข้อมูลผู้สมัคร";
        }

        // Standard Composer autoloading


        $customFontDir = FCPATH . 'public/fonts/sarabun';
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $tempDir = WRITEPATH . 'temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [$customFontDir]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'thsarabun.ttf',
                    'I' => 'thsarabun-italic.ttf',
                    'B' => 'thsarabun-bold.ttf',
                    'BI' => 'thsarabun-bolditalic.ttf',
                ]
            ],
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp'
        ]);

        // Prepare Data
        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        $date_Y = date('Y', strtotime($recruit['recruit_birthday'])) + 543;
        $date_D = date('j', strtotime($recruit['recruit_birthday']));
        $date_M = date('n', strtotime($recruit['recruit_birthday']));

        $date_Y_regis = date('Y', strtotime($recruit['recruit_date'])) + 543;
        $date_D_regis = date('j', strtotime($recruit['recruit_date']));
        $date_M_regis = date('n', strtotime($recruit['recruit_date']));

        // Calculate Age
        $birthDate = new \DateTime($recruit['recruit_birthday']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $sch = explode("โรงเรียน", $recruit['recruit_oldSchool']);
        $oldSchool = ($sch[0] == '' && isset($sch[1])) ? $sch[1] : $sch[0];

        $mpdf->SetTitle($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName'] . ' (ใบสมัครธรรมดา)');

        // Generate HTML - Always use Regular Application Layout
        $html = '';
        $imgUrl = get_recruit_file_url($recruit['recruit_img'], $recruit['recruit_regLevel'], 'img');

        if (!empty($recruit['recruit_img'])) {
            $html .= '<div style="position:absolute;top:110px;left:620px; width:100%"><img style="width: 113.38px;height:151.18px;" src="' . $imgUrl . '"></div>';
        }

        $quotaDisplay = $recruit['quota_explain'];
        if ($recruit['recruit_year'] >= 2569 && !empty($recruit['recruit_round'])) {
            $quotaDisplay .= ' (รอบที่ ' . $recruit['recruit_round'] . ')';
        }
        $html .= '<div style="position:absolute;top:18px;left:100px; width:100%;font-size:16px;">' . $quotaDisplay . '</div>';
        $html .= '<div style="position:absolute;top:180px;left:555px; width:100%;font-size:24px;">' . $recruit['recruit_regLevel'] . '</div>';
        $html .= '<div style="position:absolute;top:63px;left:700px; width:100%">' . sprintf("%04d", $recruit['recruit_id']) . '</div>';
        $html .= '<div style="position:absolute;top:280px;left:180px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . '</div>';
        $html .= '<div style="position:absolute;top:280px;left:470px; width:100%">' . $recruit['recruit_lastName'] . '</div>';
        $html .= '<div style="position:absolute;top:307px;left:270px; width:100%">' . $oldSchool . '</div>';
        $html .= '<div style="position:absolute;top:335px;left:170px; width:100%">' . $recruit['recruit_district'] . '</div>';
        $html .= '<div style="position:absolute;top:335px;left:510px; width:100%">' . $recruit['recruit_province'] . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:160px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:240px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:370px; width:100%">' . $date_Y . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:470px; width:100%">' . $age . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:600px; width:100%">' . $recruit['recruit_race'] . '</div>';
        $html .= '<div style="position:absolute;top:390px;left:162px; width:100%">' . $recruit['recruit_nationality'] . '</div>';
        $html .= '<div style="position:absolute;top:390px;left:300px; width:100%">' . $recruit['recruit_religion'] . '</div>';
        $html .= '<div style="position:absolute;top:390px;left:540px; width:100%">' . $recruit['recruit_idCard'] . '</div>';
        $html .= '<div style="position:absolute;top:418px;left:350px; width:100%">' . $recruit['recruit_phone'] . '</div>';
        $html .= '<div style="position:absolute;top:418px;left:600px; width:100%">' . $recruit['recruit_grade'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:270px; width:100%">' . $recruit['recruit_homeNumber'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:390px; width:100%">' . $recruit['recruit_homeGroup'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:475px; width:100%">' . $recruit['recruit_homeRoad'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:615px; width:100%">' . $recruit['recruit_homeSubdistrict'] . '</div>';
        $html .= '<div style="position:absolute;top:475px;left:180px; width:100%">' . $recruit['recruit_homedistrict'] . '</div>';
        $html .= '<div style="position:absolute;top:475px;left:400px; width:100%">' . $recruit['recruit_homeProvince'] . '</div>';
        $html .= '<div style="position:absolute;top:475px;left:620px; width:100%">' . $recruit['recruit_homePostcode'] . '</div>';
        $html .= '<div style="position:absolute;top:503px;left:695px; width:100%;font-size:22px;">' . $recruit['recruit_regLevel'] . '</div>';

        $html .= '<div style="position:absolute;top:880px;left:340px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName'] . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:350px; width:100%">' . $date_D_regis . ' ' . $TH_Month[$date_M_regis - 1] . ' ' . $date_Y_regis . '</div>';

        // Major Order / Course Selection - For sport applicants, show their selected course
        if ($recruit['quota_key'] == "normal") {
            $SubCourse = explode('|', $recruit['recruit_majorOrder']);
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            foreach ($SubCourse as $key => $v_SubCourse) {
                $CheckCourse = $db->table('tb_course')->select('course_initials')->where('course_id', $v_SubCourse)->get()->getRow();
                if ($CheckCourse) {
                    $html .= "ลำดับที่ " . ($key + 1) . ' ' . $CheckCourse->course_initials . "<br>";
                }
            }
            $html .= '</div>';
        } else {
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            // Use course_name_joined if available, otherwise fallback to recruit_tpyeRoom
            $courseDisplay = !empty($recruit['course_name_joined']) ? $recruit['course_name_joined'] : $recruit['recruit_tpyeRoom'];
            $html .= "ลำดับที่ 1 " . $courseDisplay . ' สาขา ' . $recruit['recruit_major'];
            $html .= '</div>';
        }


        // Documents Checkmarks
        $checkEmoji = '<span style="font-family: dejavusans; font-size: 30px; line-height: 1;">✔</span>';
        if (!empty($recruit['recruit_certificateEdu'])) {
            $html .= '<div style="position:absolute;top:785px;left:110px; width:100%;">' . $checkEmoji . '</div>';
        }
        if (!empty($recruit['recruit_copyidCard'])) {
            $html .= '<div style="position:absolute;top:785px;left:328px; width:100%;">' . $checkEmoji . '</div>';
        }
        if (!empty($recruit['recruit_img'])) {
            $html .= '<div style="position:absolute;top:788px;left:560px; width:100%;">' . $checkEmoji . '</div>';
        }

        $regularPdfTemplate = FCPATH . 'uploads/recruitstudent/registerSKJ.pdf';
        if (!file_exists($regularPdfTemplate)) {
            return "ไม่พบไฟล์ Template PDF ปกติ: " . $regularPdfTemplate;
        }
        $mpdf->SetDocTemplate($regularPdfTemplate, true);
        $mpdf->WriteHTML($html);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Reg_Normal_' . sprintf("%04d", $recruit['recruit_id']) . '.pdf', 'I');
    }
}

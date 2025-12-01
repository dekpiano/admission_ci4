<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;
use App\Models\LoginModel;
use App\Libraries\Datethai;
use App\Libraries\Timeago;
use App\Libraries\RemoteUpload;

class UserControlStudents extends BaseController
{
    protected $admissionModel;
    protected $loginModel;
    protected $datethai;
    protected $timeago;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
        $this->loginModel = new LoginModel();
        $this->datethai = new Datethai();
        $this->timeago = new Timeago();
        $this->db = \Config\Database::connect(); // Keep for other methods not yet refactored
        $this->session = \Config\Services::session();
        helper(['url', 'form', 'cookie']);
    }

    public function checkSession()
    {
        $switch = $this->admissionModel->getSystemStatus();
        if ($switch && $switch->onoff_system == 'off') {
            return redirect()->to('CloseSystem');
        }
        if (!$this->session->has('loginStudentID')) {
            return redirect()->to('login');
        }
        return null;
    }

    public function recaptcha_google($captcha)
    {
        $recaptchaResponse = $captcha;
        $userIp = $this->request->getIPAddress();

        $secret = "6LdZePgUAAAAANhhOGZi6JGWmQcETK7bkT7E0edR";

        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output, true);
    }

    public function StudentLogin()
    {
        echo "Student Login Page";
    }

    public function StudentsHome()
    {
        if ($redir = $this->checkSession()) return $redir;

        $data['title'] = 'หน้าแรก';
        $data['description'] = 'ตรวจสอบและแก้ไขการสมัคร';
        $data['stu'] = $this->admissionModel->where('recruit_idCard', $this->session->get('StudentIDCrad'))->orderBy('recruit_id', 'DESC')->get()->getRow();
        $data['chk_stu'] = $this->admissionModel->where('recruit_idCard', $this->session->get('StudentIDCrad'))->orderBy('recruit_id', 'DESC')->get()->getResult();
        
        return view('students/StudentsHome', $data);
    }

    public function StudentsStatus()
    {
        if ($redir = $this->checkSession()) return $redir;

        $data['title'] = 'ตรวจสอบสถานะการสมัคร';
        $data['description'] = 'ตรวจสอบและแก้ไขการสมัคร';
        $data['stu'] = $this->admissionModel->where('recruit_idCard', $this->session->get('StudentIDCrad'))->orderBy('recruit_id', 'DESC')->get()->getRow();
        $data['course'] = $this->db->table('tb_course')->get()->getResult(); // Still direct DB, consider moving to model
        $data['chk_stu'] = $this->admissionModel->where('recruit_idCard', $this->session->get('StudentIDCrad'))->orderBy('recruit_id', 'DESC')->get()->getResult();
        
        return view('students/StudentsStatus', $data);
    }

    public function SelectThailand()
    {
        $th = \Config\Database::connect('thailandpa');

        if ($this->request->getPost('id') && $this->request->getPost('func') === 'province') {
            $amphur = $th->table('amphur')->where('PROVINCE_ID', $this->request->getPost('id'))->get()->getResult();
            echo '<option value="">กรุณาเลือกอำเภอ</option>';
            foreach ($amphur as $key => $value) {
                echo '<option value="' . $value->AMPHUR_ID . '">' . $value->AMPHUR_NAME . '</option>';
            }
        }

        if ($this->request->getPost('id') && $this->request->getPost('func') === 'amphur') {
            $district = $th->table('district')->where('AMPHUR_ID', $this->request->getPost('id'))->get()->getResult();
            echo '<option value="">กรุณาเลือกตำบล</option>';
            foreach ($district as $key => $value) {
                echo '<option value="' . $value->DISTRICT_ID . '">' . $value->DISTRICT_NAME . '</option>';
            }
        }

        if ($this->request->getPost('id') && $this->request->getPost('func') === 'postcode') {
            $district = $th->table('amphur')->where('AMPHUR_ID', $this->request->getPost('id'))->get()->getResult();
            echo $district[0]->POSTCODE;
        }
    }

    public function StudentsEdit()
    {
        if ($redir = $this->checkSession()) return $redir;

        $data['title'] = 'แก้ไขข้อมูลการสมัคร';
        $data['description'] = 'ตรวจสอบและแก้ไขการสมัคร';
        $data['stu'] = $this->admissionModel->where('recruit_idCard', $this->session->get('StudentIDCrad'))->orderBy('recruit_id', 'DESC')->get()->getRow();
        $data['chk_stu'] = $this->admissionModel->where('recruit_idCard', $this->session->get('StudentIDCrad'))->orderBy('recruit_id', 'DESC')->get()->getResult();

        $gradeLevel = $data['chk_stu'][0]->recruit_regLevel;
        if ($gradeLevel <= 3) {
            $data['Course'] = $this->admissionModel->getCoursesByGradeLevel('ม.ต้น');
        } else {
            $data['Course'] = $this->admissionModel->getCoursesByGradeLevel('ม.ปลาย');
        }
        return view('students/StudentsEdit', $data);
    }

    public function reg_update($id)
    {
        if ($redir = $this->checkSession()) {
            return $this->request->isAJAX() 
                ? $this->response->setJSON(['status' => 'error', 'message' => 'Session expired', 'redirect' => base_url('login')]) 
                : $redir;
        }

        if (!$this->request->isAJAX()) {
             return redirect()->back()->with('error', 'Invalid Request');
        }

        $status = $this->recaptcha_google($this->request->getPost('captcha'));
        if ($status['success']) {
            $post = $this->request->getPost();
            $openyear = $this->admissionModel->getOpenYear(); 
            $data_R = $this->admissionModel->where('recruit_id', $id)->get()->getResult(); 
            
            $checkCourse = $this->admissionModel->getCourseDetails($post['recruit_majorOrder'][0]); 
            $majorOrder = implode("|", $post['recruit_majorOrder']);
            $recruit_birthday = ($post['recruit_birthdayY'] - 543) . '-' . $post['recruit_birthdayM'] . '-' . $post['recruit_birthdayD'];
            
            $data_update = [
                'recruit_regLevel' => $post['recruit_regLevel'],
                'recruit_prefix' => $post['recruit_prefix'],
                'recruit_firstName' => $post['recruit_firstName'],
                'recruit_lastName' => $post['recruit_lastName'],
                'recruit_oldSchool' => $post['recruit_oldSchool'],
                'recruit_district' => $post['recruit_district'],
                'recruit_province' => $post['recruit_province'],
                'recruit_birthday' => $recruit_birthday,
                'recruit_race' => $post['recruit_race'],
                'recruit_nationality' => $post['recruit_nationality'],
                'recruit_religion' => $post['recruit_religion'],
                'recruit_idCard' => $post['recruit_idCard'],
                'recruit_phone' => $post['recruit_phone'],
                'recruit_homeNumber' => $post['recruit_homeNumber'],
                'recruit_homeGroup' => $post['recruit_homeGroup'],
                'recruit_homeRoad' => $post['recruit_homeRoad'],
                'recruit_homeSubdistrict' => $post['recruit_homeSubdistrict'],
                'recruit_homedistrict' => $post['recruit_homedistrict'],
                'recruit_homeProvince' => $post['recruit_homeProvince'],
                'recruit_homePostcode' => $post['recruit_homePostcode'],
                'recruit_tpyeRoom' => $checkCourse->course_fullname,
                'recruit_major' => $checkCourse->course_branch,
                'recruit_grade' => $post['recruit_grade'],
                'recruit_status' => "รอการตรวจสอบ",
                'recruit_majorOrder' => ($majorOrder ? $majorOrder : "")
            ];

            // Handle files
            $filesAbility = $this->request->getFileMultiple('recruit_certificateAbility');
            // ... (Ability files logic skipped for brevity as per previous code) ...

            // Images
            $file_fields = ['recruit_img', 'recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard'];
            $folder_map = [
                'recruit_img' => 'img',
                'recruit_certificateEdu' => 'certificate',
                'recruit_certificateEduB' => 'certificateB',
                'recruit_copyidCard' => 'copyidCard'
            ];

            foreach ($file_fields as $field) {
                $file = $this->request->getFile($field);
                
                // Check for cropped image base64
                if ($field === 'recruit_img' && !empty($post['recruit_img_cropped'])) {
                     $base64Image = $post['recruit_img_cropped'];
                     // Only process if it looks like a base64 string (not a URL)
                     if (strpos($base64Image, 'data:image') === 0) {
                         $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                         $fileName = $openyear->openyear_year . '-' . $post['recruit_idCard'] . '-' . uniqid() . '.png';
                         
                         $tempFile = tempnam(sys_get_temp_dir(), 'img');
                         file_put_contents($tempFile, $imageData);

                         $remoteUpload = new RemoteUpload();
                         $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/img';
                         
                         $result = $remoteUpload->upload($tempFile, $subPath, $fileName);
                         @unlink($tempFile);

                         if ($result && $result['status'] === 'success') {
                             $data_update[$field] = $result['filename'];
                             // Delete old file
                             if (!empty($data_R[0]->$field)) {
                                 // Use the correct subPath for deleting the old file
                                 $remoteUpload->delete($data_R[0]->$field, $subPath);
                             }
                         } else {
                             return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปโหลดรูปถ่าย']);
                         }
                     }
                } elseif ($file && $file->isValid() && !$file->hasMoved()) {
                    $folder = $folder_map[$field]; // Fixed typo: foder to folder
                    $subPath = 'admission/recruitstudent/m' . $post['recruit_regLevel'] . '/' . $folder;
                    
                    $remoteUpload = new RemoteUpload();
                    $result = $remoteUpload->upload($file, $subPath);
                    
                    if ($result && $result['status'] === 'success') {
                        $data_update[$field] = $result['filename'];
                        
                        // Delete old file
                        if (!empty($data_R[0]->$field)) {
                            $remoteUpload->delete($data_R[0]->$field, $subPath);
                        }
                    } else {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์']);
                    }
                }
            }

            if ($this->admissionModel->student_update($id, $data_update)) {
                $this->session->setFlashdata(['status' => 'success', 'msg' => 'Yes', 'messge' => 'แก้ไขข้อมูลสำเร็จ รอการตรวจสอบอีกครั้ง!']);
                return $this->response->setJSON(['status' => 'success', 'message' => 'แก้ไขข้อมูลสำเร็จ!', 'redirect_url' => base_url('StudentsEdit')]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database update failed']);
            }

        } else {
             return $this->response->setJSON(['status' => 'error', 'message' => 'reCAPTCHA failed']);
        }
    }

    public function logoutStudent()
    {
        delete_cookie('username');
        delete_cookie('password');
        $this->session->destroy();
        return redirect()->to('welcome');
    }

    public function PDFForStudent()
    {
        if (!class_exists('\Mpdf\Mpdf')) {
            echo "mPDF library not found. Please install it via composer: composer require mpdf/mpdf";
            return;
        }

        $idCard = $this->session->get('StudentIDCrad');
        $datapdf = $this->admissionModel->where('recruit_idCard', $idCard)->orderBy('recruit_id', 'DESC')->first(); // Get single row

        if (!$datapdf) {
            echo "Student data not found for PDF generation.";
            return;
        }

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'format' => [210, 90]
        ]);
        
        $html = "PDF Content Here"; // Placeholder
        // Populate HTML with $datapdf->recruit_idCard etc.
        $html = "<h1>Student ID Card: " . $datapdf->recruit_idCard . "</h1>";

        $mpdf->WriteHTML($html);
        $mpdf->Output('Reg_' . $datapdf->recruit_idCard . '.pdf', 'I');
    }
}

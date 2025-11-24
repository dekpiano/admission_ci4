<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\AdmissionModel;
use Google_Client;
use Google_Service_Oauth2;

class Login extends BaseController
{
    protected $loginModel;
    protected $admissionModel;
    protected $googleClient;
    protected $GoogleButton = "";
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
        $this->admissionModel = new AdmissionModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['url', 'cookie', 'form']);

        // Google Client Setup
        // ... (rest of constructor is the same)
        if (class_exists('Google_Client')) {
            $redirect_uri = base_url('loginGoogle');

            $this->googleClient = new Google_Client();
            $this->googleClient->setClientId('112583025699-4qiv5d413kebk4s53cc1450fopts7n3m.apps.googleusercontent.com');
            $this->googleClient->setClientSecret('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');
            $this->googleClient->setRedirectUri($redirect_uri);
            $this->googleClient->addScope('email');
            $this->googleClient->addScope('profile');

            $this->GoogleButton = '<a href="' . $this->googleClient->createAuthUrl() . '" class="btn btn-primary me-3 w-auto"><i class="fa fa-google-plus-official" aria-hidden="true"></i> Login by Google </a>';
        } else {
            $this->GoogleButton = '<a href="#" class="btn btn-danger me-3 w-auto">Google Login Not Available (Library Missing)</a>';
        }
    }

    public function login_student()
    {
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['GoogleButton'] = $this->GoogleButton;
        return view('login/login_main.php', $data);
    }

    public function login_main()
    {
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $data['GoogleButton'] = $this->GoogleButton;
        return view('login/login_admin.php', $data);
    }

    public function login_admin()
    {
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        $year = $this->admissionModel->getOpenYear();

        if ($this->request->getGet('code')) {
            if (isset($this->googleClient)) {
                $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getGet('code'));
                $this->googleClient->setAccessToken($token);

                $google_oauth = new Google_Service_Oauth2($this->googleClient);
                $google_account_info = $google_oauth->userinfo->get();

                $DBpers = \Config\Database::connect('skjpers');
                $result = $DBpers->table('tb_personnel')->where('pers_username', $google_account_info->email)->get()->getRow();
                
                if ($result) {
                    $this->session->set([
                        'login_id' => $result->pers_id,
                        'fullname' => $result->pers_prefix . $result->pers_firstname . ' ' . $result->pers_lastname,
                        'status' => 'user',
                        'permission_menu' => $result->pers_workother_id,
                        'user_img' => $result->pers_img,
                        'year' => $year->openyear_year
                    ]);
                    return redirect()->to('admin/Recruitment/' . $data['checkYear']->openyear_year);
                } else {
                     // Handle user not found
                     return redirect()->to('loginAdmin')->with('error', 'User not found');
                }
            }
        } else {
             if (isset($this->googleClient)) {
                $auth_url = $this->googleClient->createAuthUrl();
                return redirect()->to($auth_url);
             }
        }
    }

    public function validlogin()
    {
        $username = $this->request->getPost('username');
        $password = md5(md5($this->request->getPost('password')));

        if ($this->loginModel->record_count($username, $password) == 1) {
            $result = $this->loginModel->fetch_user_login($username, $password);
            $year = $this->admissionModel->getOpenYear();
            
            set_cookie('username', $username, '3600');
            set_cookie('password', $password, '3600');

            $this->session->set([
                'login_id' => $result->pers_id,
                'fullname' => $result->pers_prefix . $result->pers_firstname . ' ' . $result->pers_lastname,
                'status' => 'user',
                'permission_menu' => $result->pers_workother_id,
                'user_img' => $result->pers_img,
                'year' => $year->openyear_year
            ]);

            return redirect()->to('admin/Recruitment/' . $this->request->getPost('openyear_year'));
        } else {
            return redirect()->to('welcome');
        }
    }

    public function logout()
    {
        delete_cookie('username');
        delete_cookie('password');
        $this->session->destroy();
        return redirect()->to(base_url());
    }

    public function CheckLogin()
    {
        $dm = date('m-d', strtotime($this->request->getPost('recruit_birthday')));
        $Y = date('Y', strtotime($this->request->getPost('recruit_birthday'))) - 543;
        $brith = $Y . '-' . $dm;

        $result = $this->loginModel->Student_Login($this->request->getPost('recruit_idCard'), $brith);

        if (count($result) <= 0) {
            $this->session->setFlashdata(['status' => 'error', 'msg' => 'NO', 'messge' => 'เลขบัตรประชาชนหรือวันเกิดไม่ถูกต้อง หรือ ยังไม่ได้ลงทะเบียนเรียน']);
            return redirect()->to('login');
        } else {
            $this->session->set([
                'loginStudentID' => $result[0]->recruit_id,
                'fullname' => $result[0]->recruit_prefix . $result[0]->recruit_firstName . ' ' . $result[0]->recruit_lastName,
                'StudentIDCrad' => $result[0]->recruit_idCard
            ]);
            return redirect()->to('StudentHome');
        }
    }

    public function CheckLoginConfirmStudentNew()
    {
        if ($this->request->getPost('idenStu')) {
            $builder = $this->db->table('tb_recruitstudent');
            $builder->where('recruit_idCard', $this->request->getPost('idenStu'));
            $builder->where('recruit_phone', $this->request->getPost('recruit_phone'));
            $builder->where('recruit_status', "ผ่านการตรวจสอบ");
            $count = $builder->countAllResults();
            
            if ($count > 0) {
                $this->session->set('idenStu', $this->request->getPost('idenStu'));
                echo 1;
            } else {
                echo 0;
                $this->session->destroy();
            }
        } else {
            echo 0;
            $this->session->destroy();
        }
    }

    public function Confirmlogout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('Confirm'));
    }

    public function CloseSystem()
    {
        $data['checkYear'] = $this->admissionModel->getOpenYear();
        return view('AdminssionCloseSystem', $data);
    }
}

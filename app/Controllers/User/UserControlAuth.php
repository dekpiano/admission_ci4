<?php

namespace App\Controllers\User;

//require D:\xampp\librarie_skj\google_sheet\vendor/autoload.php; // User-provided path to Google Autoload

use App\Models\PersonnelModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UserControlAuth extends \App\Controllers\BaseController
{
    private $clientId = '29638025169-aeobhq04v0lvimcjd27osmhlpua380gl.apps.googleusercontent.com';

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form', 'url'];

    /**
     * Becomes true once Controller is initialized.
     *
     * @var bool
     */
    protected bool $isInitialized = false;

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Instance of the Response object.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Instance of the LoggerInterface.
     *
     * @var LoggerInterface
     */
    protected $logger;



    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
    }

    public function login()
    {
        helper(['form', 'url']); 
        
        $data = [];
        return view('Admin/PageAdminAuth/PageAdminAuthLogin', $data);
    }

    public function googleLogin()
    {
        $credential = $this->request->getPost('credential');
        if (!$credential) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Token ไม่ถูกต้อง');
        }

        // Verify with Google via API call (The 'New Way')
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get("https://oauth2.googleapis.com/tokeninfo?id_token=" . $credential);
            $payload = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return redirect()->to(base_url('auth/login'))->with('error', 'การเชื่อมต่อกับ Google ล้มเหลว');
        }

        if (!$payload || isset($payload['error'])) {
            return redirect()->to(base_url('auth/login'))->with('error', 'การยืนยันตัวตนกับ Google ล้มเหลว');
        }

        // Validate Client ID (Audience)
        if ($payload['aud'] !== $this->clientId) {
             return redirect()->to(base_url('auth/login'))->with('error', 'Client ID ไม่ถูกต้อง');
        }

        // Check if email is from the organization domain
        if (!preg_match('/@skj\.ac\.th$/i', $payload['email'])) {
            return redirect()->to(base_url('auth/login'))->with('error', 'คุณไม่มีสิทธิ์ระบบนี้ (เฉพาะอีเมล @skj.ac.th)');
        }

        $email = $payload['email'];
        $google_sub = $payload['sub'];

        $DB_Admission = \Config\Database::connect();
        $DBrloes = $DB_Admission->table('tb_admin_rloes');
        $DB_Personnel = \Config\Database::connect('skjpers');
        $DBPers = $DB_Personnel->table('tb_personnel');     

        // 1. Find by Google Sub or Email
        $User = $DBPers->groupStart()
                       ->where('login_oauth_uid', $google_sub)
                       ->orWhere('pers_username', $email)
                       ->groupEnd()
                       ->get()->getRowArray();

        if ($User) {
            // Update user's login_oauth_uid if not set
            if (empty($User['login_oauth_uid'])) {
                $DBPers->where('pers_id', $User['pers_id'])->update(['login_oauth_uid' => $google_sub, 'updated_at' => date('Y-m-d H:i:s')]);
            }

            // Fetch roles
            $User2 = $DBrloes->select('admin_rloes_status,GROUP_CONCAT(admin_rloes_nanetype) AS rloesAll')
                             ->where('admin_rloes_userid', $User['pers_id'])
                             ->groupBy('admin_rloes_status')
                             ->get()->getRowArray();
            
            if (empty($User2['admin_rloes_status'])) {
                return redirect()->to(base_url('auth/login'))->with('error', 'คุณไม่มีสิทธิ์เข้าใช้งานระบบนี้');
            }

            $newdata = [
                'pers_id'       => $User['pers_id'],
                'pers_firstname' => $User['pers_firstname'],
                'pers_lastname'  => $User['pers_lastname'],
                'pers_username'  => $User['pers_username'],
                'pers_img'       => $User['pers_img'],
                'isLoggedIn'     => true,
                'google_id'      => $google_sub,
                'email'          => $email,
                'rloes'          => $User2['rloesAll'] ?? null,
                'status'         => $User2['admin_rloes_status'] ?? "Member"
            ];                
            session()->set($newdata);  
            
            return redirect()->to(base_url('skjadmin')); 
        } else {
            return redirect()->to(base_url('auth/login'))->with('error', "ไม่พบชื่อพนักงานที่เตรียมไว้กับอีเมล $email ในระบบ");
        }
    }

    private function setUserSession($user)
    {
        $DB_Admission = \Config\Database::connect();
        $DBrloes = $DB_Admission->table('tb_admin_rloes');
        
        // Fetch roles if any
        $User2 = $DBrloes->select('admin_rloes_status,GROUP_CONCAT(admin_rloes_nanetype) AS rloesAll')
                         ->where('admin_rloes_userid', $user['pers_id'])
                         ->groupBy('admin_rloes_status')
                         ->get()->getRowArray();

        $data = [
            'pers_id'       => $user['pers_id'],
            'pers_firstname' => $user['pers_firstname'],
            'pers_lastname'  => $user['pers_lastname'],
            'pers_username'  => $user['pers_username'],
            'pers_img'       => $user['pers_img'],
            'isLoggedIn'     => true,
            'rloes'          => $User2['rloesAll'] ?? null,
            'status'         => $User2['admin_rloes_status'] ?? "Member"
        ];

        session()->set($data);
        return redirect()->to(base_url('skjadmin')); // Always redirect to the new admin dashboard
    }

    public function localLogout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }

    public function logout()
    {
        // Google specific logout logic if needed, otherwise just destroy session
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}
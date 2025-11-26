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
    private $googleClient = null;
    private $GoogleButton = "";

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

    function __construct(){
        $path = dirname(dirname(dirname(dirname(dirname((dirname(__FILE__)))))));
		require $path . '/librarie_skj/google_sheet/vendor/autoload.php';
        
        $googleConfig = config('Google');
        $this->googleClient = new \Google_Client();
        $this->googleClient->setClientId($googleConfig->clientId);
		$this->googleClient->setClientSecret($googleConfig->clientSecret);
        $this->googleClient->setRedirectUri($googleConfig->redirectUri);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');

        $this->GoogleButton = '<a href="'.$this->googleClient->createAuthUrl().'" id="googleLoginBtn" class="btn btn-primary me-3 w-auto"><i class="tf-icons bx bxl-google-plus"></i> Login by Google </a>';
    }

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
        $data['GoogleButton'] = $this->GoogleButton;
        return view('Admin/PageAdminAuth/PageAdminAuthLogin', $data);
    }

    public function googleAuth()
    {
        return redirect()->to($this->googleClient->createAuthUrl());
    }

    public function googleCallback()
    {
        $session = session();
        $DB_Admission = \Config\Database::connect(); // Admission database (Default)
        $DBrloes = $DB_Admission->table('tb_admin_rloes');
        $DB_Personnel = \Config\Database::connect('skjpers'); // Personnel database
        $DBPers = $DB_Personnel->table('tb_personnel');     
        
        // For now, I'll set a default return to the new admin dashboard
        if (!session()->has('Return')) {
            session()->set('Return', base_url('skjadmin')); // Default admin dashboard
        }
        
        if($this->request->getVar("code")){
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar("code"));
            
            if(!isset($token['error'])){
                $this->googleClient->setAccessToken($token['access_token']);           
                session()->set('AccessToken', $token['access_token']);
            
                $googleService = new \Google_Service_Oauth2($this->googleClient);  
                $googleUser = $googleService->userinfo->get();            
                
                // Check if email is from the organization domain
                if (!preg_match('/@skj\.ac\.th$/i', $googleUser['email'])) {
                    session()->setFlashdata('error', 'คุณไม่มีสิทธิ์ระบบนี้ (เฉพาะอีเมล @skj.ac.th)');
                    return redirect()->to(base_url('auth/login'));
                }

                $CheckEmail = $DBPers->where('pers_username', $googleUser['email'])->get()->getRowArray();

                if($CheckEmail){
                    // Update user's login_oauth_uid and updated_at
                    $UserData = [
                        'login_oauth_uid' => $googleUser['id'],
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $DBPers->where('pers_username', $googleUser['email'])->update($UserData);

                    $User = $DBPers->where('pers_username', $googleUser['email'])->get()->getRowArray();
                    // Fetch roles if any
                    $User2 = $DBrloes->select('admin_rloes_status,GROUP_CONCAT(admin_rloes_nanetype) AS rloesAll')
                                     ->where('admin_rloes_userid', $User['pers_id'])
                                     ->get()->getRowArray();
                    
                    if (empty($User2['admin_rloes_status'])) {
                        session()->setFlashdata('error', 'คุณไม่มีสิทธิ์เข้าใช้งานระบบนี้');
                        return redirect()->to(base_url('auth/login'));
                    }

                    $newdata = [
                        'pers_id'       => $User['pers_id'], // Using pers_id for consistency
                        'pers_firstname' => $User['pers_firstname'],
                        'pers_lastname'  => $User['pers_lastname'],
                        'pers_username'  => $User['pers_username'],
                        'pers_img'       => $User['pers_img'],
                        'isLoggedIn'     => true,
                        'google_id'      => $googleUser['id'], // Store Google ID
                        'email'          => $googleUser['email'],
                        'rloes'          => $User2['rloesAll'] ?? null,
                        'status'         => $User2['admin_rloes_status'] ?? "Member"
                    ];                
                    session()->set($newdata);  
                    
                    return redirect()->to(base_url('skjadmin')); // Always redirect to the new admin dashboard
                   
                } else {
                    session()->setFlashdata('error', 'ไม่พบชื่อผู้ใช้ Google ในระบบ');
                    return redirect()->to(base_url('auth/login'));
                }
            } else {
                session()->setFlashdata('error', "เกิดข้อผิดพลาดในการเข้าสู่ระบบด้วย Google: " . $token['error_description']);     
                return redirect()->to(base_url('auth/login'));
            }
        } else {
            session()->setFlashdata('error', 'ไม่สามารถเข้าสู่ระบบด้วย Google ได้: ไม่มีรหัสรับรองความถูกต้อง');
            return redirect()->to(base_url('auth/login'));
        }
    }

    private function setUserSession($user)
    {
        $DB_Admission = \Config\Database::connect();
        $DBrloes = $DB_Admission->table('tb_admin_rloes');
        
        // Fetch roles if any
        $User2 = $DBrloes->select('admin_rloes_status,GROUP_CONCAT(admin_rloes_nanetype) AS rloesAll')
                         ->where('admin_rloes_userid', $user['pers_id'])
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
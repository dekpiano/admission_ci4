<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Setting extends BaseController
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

    public function AdminSystem()
    {
        if ($redir = $this->checkAuth()) return $redir;

        $data['switch'] = $this->db->table("tb_onoffsys")->get()->getResult();
        $data['switch_quota'] = $this->db->table("tb_quota")->get()->getResult();
        $data['switch_course'] = $this->db->table("tb_course")->select('course_id,course_branch,course_gradelevel')->get()->getResult();

        $data['title'] = "ตั้งค่าระบบ";
        $data['checkYear'] = $this->db->table('tb_openyear')->get()->getResult();
        $data['year'] = $this->db->table('tb_recruitstudent')->select('recruit_year')->groupBy('recruit_year')->orderBy('recruit_year', 'DESC')->get()->getResult();

        echo view('admin/layout/navber_admin.php', $data);
        echo view('admin/layout/menu_top_admin.php');
        echo view('admin/admin_admission_status.php');
        echo view('admin/layout/footer_admin.php');
    }

    public function UpdateQuotaInCourse()
    {
        $course_id = $this->request->getPost('course_id');
        $course_data = $this->request->getPost('course_data');
        
        $data = ['quota_course' => $course_data];
        echo $this->db->table('tb_quota')->where('quota_id', $course_id)->update($data);
    }

    public function UpdateDatatimeOnoffRegis()
    {
        $id = $this->request->getPost('id');
        $date = $this->request->getPost('date');
        $SetDatetime = date('Y-m-d H:i:s', strtotime($date));

        $data = ['onoff_datetime_regis_close' => $SetDatetime];
        $result = $this->db->table('tb_onoffsys')->where('onoff_id', $id)->update($data);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'อัปเดตสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตได้']);
        }
    }
}

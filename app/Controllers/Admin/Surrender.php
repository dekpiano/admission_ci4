<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Surrender extends BaseController
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

    public function PageSurrenderMain($year)
    {
        if ($redir = $this->checkAuth()) return $redir;

        $data['switch'] = $this->db->table("tb_onoffsys")->get()->getResult();
        $data['title'] = $this->title;

        $data['recruit'] = $this->db->table('skjacth_admission.tb_recruitstudent')
            ->select('skjacth_admission.tb_recruitstudent.recruit_id,
		skjacth_admission.tb_recruitstudent.recruit_regLevel,
		skjacth_admission.tb_recruitstudent.recruit_prefix,
		skjacth_admission.tb_recruitstudent.recruit_firstName,
		skjacth_admission.tb_recruitstudent.recruit_lastName,
		skjacth_admission.tb_recruitstudent.recruit_year,
		skjacth_admission.tb_recruitstudent.recruit_status,
		skjacth_admission.tb_recruitstudent.recruit_tpyeRoom,
		skjacth_admission.tb_recruitstudent.recruit_idCard,
		skjacth_admission.tb_recruitstudent.recruit_category,
		skjacth_admission.tb_recruitstudent.recruit_img,
		skjacth_admission.tb_recruitstudent.recruit_phone,
		skjacth_admission.tb_recruitstudent.recruit_statusSurrender,
		skjacth_personnel.tb_students.stu_id,
		skjacth_personnel.tb_students.stu_UpdateConfirm')
            ->join('skjacth_personnel.tb_students', 'skjacth_admission.tb_recruitstudent.recruit_idCard = skjacth_personnel.tb_students.stu_iden', 'LEFT')
            ->where('recruit_year', $year)
            ->where('recruit_status', "ผ่านการตรวจสอบ")
            ->orderBy('recruit_id', 'DESC')
            ->get()->getResult();

        $data['checkYear'] = $this->db->table('tb_openyear')->get()->getResult();
        $data['year'] = $this->db->table('tb_recruitstudent')->select('recruit_year')->groupBy('recruit_year')->orderBy('recruit_year', 'DESC')->get()->getResult();

        return view('admin/admin_admission_surrender.php', $data);
    }

    public function UpdateSurrender()
    {
        $recruit_id = $this->request->getPost('recruit_id');
        $data = ['recruit_statusSurrender' => date('Y-m-d H:i:s')];
        echo $this->db->table('tb_recruitstudent')->where('recruit_id', $recruit_id)->update($data);
    }
}

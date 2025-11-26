<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlQuiz extends BaseController
{
    protected $db;
    protected $session;
    protected $title = "การสอบ";

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

    public function PageQuizMain($year)
    {
        if ($redir = $this->checkAuth()) return $redir;

        // $ConnPers = \Config\Database::connect('skjpers'); // Used in join, CI4 handles this in query builder if configured correctly or using multiple DB connections manually.
        // In CI3 code: $this->db->join('skjacth_personnel.tb_students', ...)
        // This implies cross-database join. In MySQL, if the user has access to both DBs, this works with standard query builder using 'database.table'.
        
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
		skjacth_admission.tb_recruitstudent.recruit_StatusQuiz,
		skjacth_personnel.tb_students.stu_id,
		skjacth_personnel.tb_students.stu_UpdateConfirm')
            ->join('skjacth_personnel.tb_students', 'skjacth_admission.tb_recruitstudent.recruit_idCard = skjacth_personnel.tb_students.stu_iden', 'LEFT')
            ->where('recruit_year', $year)
            ->where('recruit_status', "ผ่านการตรวจสอบ")
            ->where('recruit_category', "normal")
            ->orderBy('recruit_id', 'DESC')
            ->get()->getResult();

        $data['checkYear'] = $this->db->table('tb_openyear')->get()->getResult();
        $data['year'] = $this->db->table('tb_recruitstudent')->select('recruit_year')->groupBy('recruit_year')->orderBy('recruit_year', 'DESC')->get()->getResult();

        return view('admin/admin_admission_quiz.php', $data);
    }

    public function UpdateStatusQuiz()
    {
        $recruit_id = $this->request->getPost('recruit_id');
        $status = $this->request->getPost('recruit_StatusQuiz');
        
        $data = ['recruit_StatusQuiz' => $status];
        $this->db->table('tb_recruitstudent')->where('recruit_id', $recruit_id)->update($data);
        
        if ($status == "ผ่าน") {
            echo 1;
        } else {
            echo 0;
        }
    }
}

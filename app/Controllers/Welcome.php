<?php

namespace App\Controllers;

use App\Models\AdmissionModel;



class Welcome extends BaseController

{

    protected $db;

    protected $skjmain;

    protected $skjpers;

    protected $datethai;

    protected $timeago;

    protected $admissionModel;



    public static $title = "โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";

    public static $description = "เป็นผู้นำ รักเพื่อน นับถือพี่ เคารพครู กตัญญูพ่อแม่ ดูแลน้อง สนองคุณแผ่นดิน โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";



    public function __construct()

    {

        $this->db = \Config\Database::connect();

        $this->skjmain = \Config\Database::connect('skjmain');

        $this->skjpers = \Config\Database::connect('skjpers');

        $this->datethai = new Datethai();

        $this->timeago = new Timeago();

        $this->admissionModel = new AdmissionModel();

        helper(['url']);

    }



    public function index()

    {

        // This now redirects to Admission::index via Routes.php

        // Keeping it here in case routes are changed back.

        return redirect()->to('admission');

    }



    public function not_404()

    {

        return view('errors/404.php');

    }



    public function CheckRegister()

    {

        $data['checkYear'] = $this->admissionModel->getOpenYear();



        $builder = $this->db->table('tb_recruitstudent');

        $builder->select('skjacth_admission.tb_recruitstudent.recruit_id,

		skjacth_admission.tb_recruitstudent.recruit_regLevel,

		skjacth_admission.tb_recruitstudent.recruit_prefix,

		skjacth_admission.tb_recruitstudent.recruit_firstName,

		skjacth_admission.tb_recruitstudent.recruit_lastName,

		skjacth_admission.tb_recruitstudent.recruit_status,

		skjacth_admission.tb_recruitstudent.recruit_tpyeRoom,

		skjacth_admission.tb_recruitstudent.recruit_idCard,

		skjacth_admission.tb_recruitstudent.recruit_category,

		skjacth_admission.tb_recruitstudent.recruit_statusSurrender,

		skjacth_personnel.tb_students.stu_id,

		skjacth_personnel.tb_students.stu_UpdateConfirm');

        

        // Explicitly using database names as in the original code

        $builder->join('skjacth_personnel.tb_students', 'skjacth_admission.tb_recruitstudent.recruit_idCard = skjacth_personnel.tb_students.stu_iden', 'LEFT');

        $builder->where('recruit_year', $data['checkYear']->openyear_year);

        $builder->orderBy('recruit_id', 'DESC');

        

        $data['DataStudents'] = $builder->get()->getResult();



        $data['switch'] = $this->db->table("tb_onoffsys")->get()->getResult();

        $data['quota'] = $this->db->table("tb_quota")->get()->getResult();



        $data['title'] = "เช็คข้อมูลการสมัครเรียน " . $data['checkYear']->openyear_year;

        $data['description'] = "เช็คข้อมูลการสมัครเรียนแบบเรียลไทม์";

        $data['banner'] = base_url() . "uploads/confirm/logoCheckData.PNG";

        $data['url'] = "CheckRegister";



        return view('AdminssionCheckData', $data);

    }
}

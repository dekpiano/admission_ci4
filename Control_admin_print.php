<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control_admin_print extends CI_Controller {
	
	var  $title = "การรับสมัคร";
	
	public function __construct() {
		parent::__construct();
		$this->load->model('model_admission');
		$this->load->model('admin/admin_model_admission');
		if ($this->session->userdata('fullname') == '') {
			redirect('login','refresh');
		}
	}



	  public function PagePrintMain(){
		$data['title'] = "พิมพ์ใบสมัคร";
		$data['switch'] = $this->db->get("tb_onoffsys")->result();
		$data['quota'] = $this->db->select('quota_explain,quota_id,quota_key')->get("tb_quota")->result();
		$data['course'] = $this->db->select('course_id,course_initials')->group_by('course_initials')->get("tb_course")->result();
		$data['CountYear'] = $this->db->select('recruit_year')->group_by('recruit_year')->order_by('recruit_year','DESC')->get("tb_recruitstudent")->result();
		$data['checkYear'] = $this->db->select('*')->from('tb_openyear')->get()->result();
		
		//echo '<pre>'; print_r($data['CountYear']); exit();
		$this->load->view('admin/layout/navber_admin.php',$data);
		$this->load->view('admin/layout/menu_top_admin.php');
		$this->load->view('admin/admin_admission_print.php');
		$this->load->view('admin/layout/footer_admin.php');
	  }

	  public function ChangeCouse(){
		$data['course'] = $this->db->select('course_id,course_initials')->where('course_gradelevel',$this->input->post('mainOption'))->get("tb_course")->result();

			echo json_encode($data['course']);
	  }

	 
	  public function DownloadPDF($SelYear,$SelQuota,$SelFloor,$SelCourse)
    {
		// echo "<pre>"; print_r($SelYear);
		// exit();
		$path = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
		require $path . '/librarie_skj/mpdf/vendor/autoload.php';

		$thai = $this->load->database('thailandpa', TRUE);
		$thpa = $thai->database;
		
		$datapdf = $this->db->select('skjacth_admission.tb_recruitstudent.*,
									  skjacth_admission.tb_quota.quota_explain,skjacth_admission.tb_quota.quota_key')
		->from('skjacth_admission.tb_recruitstudent')										
		->join('skjacth_admission.tb_quota','skjacth_admission.tb_quota.quota_key = skjacth_admission.tb_recruitstudent.recruit_category','left')
		->where('recruit_year',$SelYear)
		->where('recruit_category',$SelQuota)
		->where('recruit_regLevel',$SelFloor)		
		->get()->result();

		
		// foreach ($datapdf as $key => $v_datapdf) {
		// 	$id = $v_datapdf->recruit_id;
		// 	$sub = explode("|",$v_datapdf->recruit_majorOrder);
		// 	if($sub[0] == $SelCourse){
		// 		echo '<pre>'; print_r($sub[0]); 
		// 	}
			
		// }

		// exit();

		// echo FCPATH.'uploads/recruitstudent/m'.$v_datapdf->recruit_regLevel.'/img/'.$v_datapdf->recruit_img; exit();
		$mpdf = new \Mpdf\Mpdf([
			'default_font_size' => 16,
			'default_font' => 'sarabun'
		]);

		foreach ($datapdf as $key => $v_datapdf) {
			$id = $v_datapdf->recruit_id;
			$sub = explode("|",$v_datapdf->recruit_majorOrder);
			if($sub[0] == $SelCourse){


    	$date_Y = date('Y',strtotime($v_datapdf->recruit_birthday))+543;
    	$TH_Month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    	$date_D = date('d',strtotime($v_datapdf->recruit_birthday));
    	$date_M = date('n',strtotime($v_datapdf->recruit_birthday));

		$date_Y_regis = date('Y',strtotime($v_datapdf->recruit_date))+543;
    	$date_D_regis = date('d',strtotime($v_datapdf->recruit_date));
    	$date_M_regis = date('n',strtotime($v_datapdf->recruit_date));

    	$sch = explode("โรงเรียน", $v_datapdf->recruit_oldSchool); //แยกคำว่าโรงเรียน
	
        $mpdf->SetTitle($v_datapdf->recruit_prefix.$v_datapdf->recruit_firstName.' '.$v_datapdf->recruit_lastName);
        $html = '<div style="position:absolute;top:90px;left:635px; width:100%"><img style="width: 120px;hight:100px;" src='.FCPATH.'uploads/recruitstudent/m'.$v_datapdf->recruit_regLevel.'/img/'.$v_datapdf->recruit_img.'></div>'; 
		$html .= '<div style="position:absolute;top:18px;left:100px; width:100%;font-size:16px;">'.$v_datapdf->quota_explain.'</div>'; //รอบโควตาการสมัคร
		$html .= '<div style="position:absolute;top:180px;left:555px; width:100%;font-size:24px;">'.$v_datapdf->recruit_regLevel.'</div>';// ชั้นที่ สมัคร
        $html .= '<div style="position:absolute;top:63px;left:700px; width:100%">'.sprintf("%04d",$v_datapdf->recruit_id).'</div>'; //เลขที่สมัคร
		$html .= '<div style="position:absolute;top:280px;left:180px; width:100%">'.$v_datapdf->recruit_prefix.$v_datapdf->recruit_firstName.'</div>'; //ชื่อผู้สมัคร
		$html .= '<div style="position:absolute;top:280px;left:470px; width:100%">'.$v_datapdf->recruit_lastName.'</div>'; //นามสกุลผู้สมัคร
		$html .= '<div style="position:absolute;top:307px;left:270px; width:100%">'.($sch[0] == '' ? $sch[1] : $sch[0]).'</div>'; //โรงเรียนเดิม
		$html .= '<div style="position:absolute;top:335px;left:170px; width:100%">'.$v_datapdf->recruit_district.'</div>'; //อำเภอโรงเรียน
		$html .= '<div style="position:absolute;top:335px;left:510px; width:100%">'.$v_datapdf->recruit_province.'</div>'; //จังหวัดโรงเรียน
		$html .= '<div style="position:absolute;top:363px;left:160px; width:100%">'.$date_D.'</div>'; //วันเกิด
		$html .= '<div style="position:absolute;top:363px;left:240px; width:100%">'.$TH_Month[$date_M-1].'</div>'; //เดือนเกิด
		$html .= '<div style="position:absolute;top:363px;left:370px; width:100%">'.$date_Y.'</div>'; //ปีเกิด
		$html .= '<div style="position:absolute;top:363px;left:470px; width:100%">'.$this->timeago->getAge($v_datapdf->recruit_birthday).'</div>'; //อายุ
		$html .= '<div style="position:absolute;top:363px;left:600px; width:100%">'.$v_datapdf->recruit_race.'</div>'; //เชื้อชาติ
		$html .= '<div style="position:absolute;top:390px;left:162px; width:100%">'.$v_datapdf->recruit_nationality.'</div>'; //สัญชาติ
		$html .= '<div style="position:absolute;top:390px;left:300px; width:100%">'.$v_datapdf->recruit_religion.'</div>'; //ศาสนา
		$html .= '<div style="position:absolute;top:390px;left:540px; width:100%">'.$v_datapdf->recruit_idCard.'</div>'; //เลขบัตรประจำตัวประชาชน
		$html .= '<div style="position:absolute;top:418px;left:350px; width:100%">'.$v_datapdf->recruit_phone.'</div>'; //เบอร์โทรติดต่อ
		$html .= '<div style="position:absolute;top:418px;left:600px; width:100%">'.$v_datapdf->recruit_grade.'</div>'; //เกรดเฉี่ย
		$html .= '<div style="position:absolute;top:445px;left:270px; width:100%">'.$v_datapdf->recruit_homeNumber.'</div>'; //บ้านเลขที่ //แก้*****
		$html .= '<div style="position:absolute;top:445px;left:390px; width:100%">'.$v_datapdf->recruit_homeGroup.'</div>'; //หมู่
		$html .= '<div style="position:absolute;top:445px;left:475px; width:100%">'.$v_datapdf->recruit_homeRoad.'</div>'; //ถนน
		$html .= '<div style="position:absolute;top:445px;left:615px; width:100%">'.$v_datapdf->recruit_homeSubdistrict.'</div>'; //ตำบล
		$html .= '<div style="position:absolute;top:475px;left:180px; width:100%">'.$v_datapdf->recruit_homedistrict.'</div>'; //อำเภอ
		$html .= '<div style="position:absolute;top:475px;left:400px; width:100%">'.$v_datapdf->recruit_homeProvince.'</div>'; //จังหวัด
		$html .= '<div style="position:absolute;top:475px;left:620px; width:100%">'.$v_datapdf->recruit_homePostcode.'</div>'; //รหัสไปรษณีย์
		$html .= '<div style="position:absolute;top:503px;left:695px; width:100%;font-size:22px;">'.$v_datapdf->recruit_regLevel.'</div>';// ชั้นที่ สมัคร
		// ส่วนที่ 2recruit_date		
		$html .= '<div style="position:absolute;top:880px;left:340px; width:100%">'.$v_datapdf->recruit_prefix.$v_datapdf->recruit_firstName.' '.$v_datapdf->recruit_lastName.'</div>'; //ชื่อผู้สมัคร
		
		$html .= '<div style="position:absolute;top:905px;left:350px; width:100%">'.$date_D_regis.' '.$TH_Month[$date_M_regis-1].' '.$date_Y_regis.'</div>'; // วันที่สมัครตอนที่ 1

		//แสดง ลำดับการเลือกหลักสูตร
		if($v_datapdf->quota_key == "normal"){
			$CheckStu = $this->db->select('recruit_majorOrder')->where('recruit_id',$id)->get('tb_recruitstudent')->row();
			$SubCourse = explode('|',$CheckStu->recruit_majorOrder);	
			$html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
			foreach ($SubCourse as $key => $v_SubCourse) {
				$CheckCourse = $this->db->select('course_initials')->where('course_id', $v_SubCourse)->get('tb_course')->row();
				$html .= "ลำดับที่ ".($key+1).' '.$CheckCourse->course_initials."<br>";
			}		
			$html .= '</div>';
		}else{
			$CheckStu = $this->db->select('recruit_tpyeRoom,recruit_major')->where('recruit_id',$id)->get('tb_recruitstudent')->row();
			
			$html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
			
				$html .= "ลำดับที่ 1 ".$CheckStu->recruit_tpyeRoom. ' สาขา '.$CheckStu->recruit_major;
				
			$html .= '</div>';
		}
		

		//  $AtpyeRoom = array('ห้องเรียนความเป็นเลิศทางด้านวิชาการ (Science Match and Technology Program)','ห้องเรียนความเป็นเลิศทางด้านภาษา (Chinese English Program)','ห้องเรียนความเป็นเลิศทางด้านดนตรี ศิลปะ การแสดง (Preforming Art Program)','ห้องเรียนความเป็นเลิศด้านการงานอาชีพ (Career Program)','ห้องเรียนความเป็นเลิศด้านกีฬา (Sport Program)' ); 
    	// if($v_datapdf->recruit_tpyeRoom == $AtpyeRoom[0]){
    	// 	$html .= '<div style="position:absolute;top:540px;left:110px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
    	// }elseif($v_datapdf->recruit_tpyeRoom == $AtpyeRoom[1]){
    	// 	$html .= '<div style="position:absolute;top:570px;left:110px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
    	// }elseif($v_datapdf->recruit_tpyeRoom == $AtpyeRoom[2]){
    	// 	$html .= '<div style="position:absolute;top:595px;left:110px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
    	// }elseif($v_datapdf->recruit_tpyeRoom == $AtpyeRoom[3]){
    	// 	$html .= '<div style="position:absolute;top:625px;left:110px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
		// }elseif($v_datapdf->recruit_tpyeRoom == $AtpyeRoom[4]){
    	// 	$html .= '<div style="position:absolute;top:510px;left:110px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
		// }

		
    	if($v_datapdf->recruit_certificateEdu != ''){
    		$html .= '<div style="position:absolute;top:795px;left:110px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
    	}
    	if($v_datapdf->recruit_copyidCard != ''){
    		$html .= '<div style="position:absolute;top:795px;left:330x; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
    	}
    	if($v_datapdf->recruit_img != ''){
    		$html .= '<div style="position:absolute;top:795px;left:560px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
    	}

		$mpdf->SetDocTemplate('uploads/recruitstudent/registerSKJ.pdf',true);
		$filename = sprintf("%04d",$v_datapdf->recruit_id).'-'.$v_datapdf->recruit_prefix.$v_datapdf->recruit_firstName.' '.$v_datapdf->recruit_lastName;
        $mpdf->WriteHTML($html);
		$mpdf->AddPage();

			}
		}


        $mpdf->Output('Reg_'.$filename.'.pdf','I'); // opens in browser
		//echo basename('Reg_'.$filename.'.pdf');
        //$mpdf->Output('arjun.pdf','D'); // it downloads the file into the user system, with give name
    }
	


}

?>
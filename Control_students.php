<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control_students extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('datethai');
		$this->load->model('model_admission');
		$this->load->model('Model_login');
		$switch = $this->db->get("tb_onoffsys")->result();
		if($switch[0]->onoff_system == 'off'){
			redirect('CloseSystem');
		}
		if ($this->session->userdata('loginStudentID') == '') {
			redirect('login','refresh');
		}
		
	}
	

	public function recaptcha_google($captcha)
	{
		$recaptchaResponse = $captcha;
		$userIp=$this->input->ip_address();
     
        $secret = "6LdZePgUAAAAANhhOGZi6JGWmQcETK7bkT7E0edR";
   
        $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;
 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);      
         
        return $status= json_decode($output, true);
	}

	public function StudentsHome(){
		$data['title'] = 'หน้าแรก';
		$data['description'] = 'ตรวจสอบและแก้ไขการสมัคร';
		$data['stu'] = $this->db->select('recruit_id,recruit_prefix,recruit_firstName,recruit_lastName,recruit_status,recruit_tpyeRoom,recruit_status')->where('recruit_idCard',$this->session->userdata('StudentIDCrad'))->order_by('recruit_id','DESC')->get('tb_recruitstudent')->row();
		$data['chk_stu'] = $this->db->where('recruit_idCard',$this->session->userdata('StudentIDCrad'))->order_by('recruit_id','DESC')->get('tb_recruitstudent')->result();
		$this->load->view('students/layout/navber_students.php',$data);
		$this->load->view('students/layout/menu_top_students.php');
		$this->load->view('students/StudentsHome.php');
		$this->load->view('students/layout/footer_students.php');
	}

	public function StudentsStatus(){
		$data['title'] = 'ตรวจสอบสถานะการสมัคร';
		$data['description'] = 'ตรวจสอบและแก้ไขการสมัคร';
		$data['stu'] = $this->db->select('recruit_id,recruit_prefix,recruit_firstName,recruit_lastName,recruit_status,recruit_tpyeRoom,recruit_status,recruit_category,recruit_majorOrder')->where('recruit_idCard',$this->session->userdata('StudentIDCrad'))->order_by('recruit_id','DESC')->get('tb_recruitstudent')->row();
		$data['course'] = $this->db->get('tb_course')->result();
		$data['chk_stu'] = $this->db->where('recruit_idCard',$this->session->userdata('StudentIDCrad'))->order_by('recruit_id','DESC')->get('tb_recruitstudent')->result();
		$this->load->view('students/layout/navber_students.php',$data);
		$this->load->view('students/layout/menu_top_students.php');
		$this->load->view('students/StudentsStatus.php');
		$this->load->view('students/layout/footer_students.php');
	}

	public function SelectThailand(){
		$th = $this->load->database('thailandpa', TRUE);

		if($this->input->post('id',TRUE) && $this->input->post('func') === 'province'){
			$amphur = $th->where('PROVINCE_ID',$this->input->post('id'))->get('amphur')->result(); 
			echo '<option value="">กรุณาเลือกอำเภอ</option>';
			foreach ($amphur as $key => $value) {
				echo '<option value="'.$value->AMPHUR_ID.'">'.$value->AMPHUR_NAME.'</option>';
			}
		}

		if($this->input->post('id',TRUE) && $this->input->post('func') === 'amphur'){
			$district = $th->where('AMPHUR_ID',$this->input->post('id'))->get('district')->result(); 
			echo '<option value="">กรุณาเลือกตำบล</option>';
			foreach ($district as $key => $value) {
				echo '<option value="'.$value->DISTRICT_ID.'">'.$value->DISTRICT_NAME.'</option>';
			}
		}

		if($this->input->post('id',TRUE) && $this->input->post('func') === 'postcode'){
			$district = $th->where('AMPHUR_ID',$this->input->post('id'))->get('amphur')->result(); 		
			echo $district[0]->POSTCODE;
		}
		
	}

	public function StudentsEdit(){
		$data['title'] = 'แก้ไขข้อมูลการสมัคร';
		$data['description'] = 'ตรวจสอบและแก้ไขการสมัคร';
		$data['stu'] = $this->db->select('recruit_id,recruit_prefix,recruit_firstName,recruit_lastName,recruit_status,recruit_tpyeRoom,recruit_status')->where('recruit_idCard',$this->session->userdata('StudentIDCrad'))->order_by('recruit_id','DESC')->get('tb_recruitstudent')->row();
		$data['chk_stu'] = $this->db->where('recruit_idCard',$this->session->userdata('StudentIDCrad'))->order_by('recruit_id','DESC')->get('tb_recruitstudent')->result();

		//echo '<pre>';print_r($data['chk_stu']);exit();
		if($data['chk_stu'][0]->recruit_regLevel <= 3){
			$data['Course'] = $this->db->where('course_gradelevel','ม.ต้น')->get("tb_course")->result();
		}else{
			$data['Course'] = $this->db->where('course_gradelevel','ม.ปลาย')->get("tb_course")->result();
		}
		$this->load->view('students/layout/navber_students.php',$data);
		$this->load->view('students/layout/menu_top_students.php');
		$this->load->view('students/StudentsEdit.php');
		$this->load->view('students/layout/footer_students.php');
	}


	public function reg_update($id)
	{	
	
		$status = $this->recaptcha_google($this->input->post('captcha')); 
        if ($status['success']) {
			$data_update = array();
			$openyear = $this->db->get('tb_openyear')->result();
			$data_R = $this->db->where('recruit_id',$id)->get('tb_recruitstudent')->result();
		
		$file = array($_FILES['recruit_img']['error'],
							$_FILES['recruit_certificateEdu']['error'],
							$_FILES['recruit_certificateEduB']['error'],
							$_FILES['recruit_copyidCard']['error']);
		$CheckCourse = $this->db->where('course_id',$this->input->post('recruit_majorOrder')[0])->get('tb_course')->row();
		$majorOrder = implode("|",$this->input->post('recruit_majorOrder'));
		$recruit_birthday = ($this->input->post('recruit_birthdayY')-543).'-'.$this->input->post('recruit_birthdayM').'-'.$this->input->post('recruit_birthdayD');
		$data_update += array(
			'recruit_regLevel' => $this->input->post('recruit_regLevel'),
			'recruit_prefix' => $this->input->post('recruit_prefix'),
			'recruit_firstName' => $this->input->post('recruit_firstName'),
			'recruit_lastName' => $this->input->post('recruit_lastName'),
			'recruit_oldSchool' => $this->input->post('recruit_oldSchool'),
			'recruit_district' => $this->input->post('recruit_district'),
			'recruit_province' => $this->input->post('recruit_province'),
			'recruit_birthday' => $recruit_birthday,
			'recruit_race' => $this->input->post('recruit_race'),
			'recruit_nationality' => $this->input->post('recruit_nationality'), 
			'recruit_religion' => $this->input->post('recruit_religion'),
			'recruit_idCard' => $this->input->post('recruit_idCard'),
			'recruit_phone' => $this->input->post('recruit_phone'),
			'recruit_homeNumber' => $this->input->post('recruit_homeNumber'),
			'recruit_homeGroup' => $this->input->post('recruit_homeGroup'),
			'recruit_homeRoad' => $this->input->post('recruit_homeRoad'),
			'recruit_homeSubdistrict' => $this->input->post('recruit_homeSubdistrict'),
			'recruit_homedistrict' => $this->input->post('recruit_homedistrict'),
			'recruit_homeProvince' => $this->input->post('recruit_homeProvince'),
			'recruit_homePostcode' => $this->input->post('recruit_homePostcode'),
			'recruit_tpyeRoom' => $CheckCourse->course_fullname,
			'recruit_major' => $CheckCourse->course_branch,
			'recruit_grade' => $this->input->post('recruit_grade'),
			'recruit_status' => "รอการตรวจสอบ",
			'recruit_majorOrder' => ($majorOrder ? $majorOrder : "")
		);
		
	
			$data_update += array('recruit_certificateAbility' => $this->UploadCertificateAbility($data_R[0]->recruit_certificateAbility));
		

		//echo '<pre>';print_r($this->UploadCertificateAbility($data_R[0]->recruit_certificateAbility)); exit();

			if($file[0] == 0){
				$imageFileType = strtolower(pathinfo($_FILES['recruit_img']['name'],PATHINFO_EXTENSION));						
				$file_check = $_FILES['recruit_img']['error'];
				$foder = 'img';
				$do_upload = 'recruit_img';
				$rand_name = $openyear[0]->openyear_year.'-'.$this->input->post('recruit_idCard').rand();
				if($file_check == 0){
					$data_update += array('recruit_img' => $rand_name.'.'.$imageFileType);	
					$this->update_img($id,$data_R[0]->recruit_img,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}else{
					$imageFileType = 0;
					$this->update_img($id,$data_R[0]->recruit_img,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}			

			}if($file[1] == 0){
				$imageFileType = strtolower(pathinfo($_FILES['recruit_certificateEdu']['name'],PATHINFO_EXTENSION));						
				$file_check = $_FILES['recruit_certificateEdu']['error'];
				$foder = 'certificate';
				$do_upload = 'recruit_certificateEdu';
				$rand_name = $openyear[0]->openyear_year.'-'.$this->input->post('recruit_idCard').rand();
				if($file_check == 0){
					$data_update += array('recruit_certificateEdu' => $rand_name.'.'.$imageFileType);	
					$this->update_img($id,$data_R[0]->recruit_certificateEdu,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}else{
					$imageFileType = 0;
					$this->update_img($id,$data_R[0]->recruit_certificateEdu,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}
			}if($file[2] == 0){
				$imageFileType = strtolower(pathinfo($_FILES['recruit_certificateEduB']['name'],PATHINFO_EXTENSION));						
				$file_check = $_FILES['recruit_certificateEduB']['error'];
				$foder = 'certificateB';
				$do_upload = 'recruit_certificateEduB';
				$rand_name = $openyear[0]->openyear_year.'-'.$this->input->post('recruit_idCard').rand();
				if($file_check == 0){
					$data_update += array('recruit_certificateEduB' => $rand_name.'.'.$imageFileType);	
					$this->update_img($id,$data_R[0]->recruit_certificateEduB,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}else{
					$imageFileType = 0;
					$this->update_img($id,$data_R[0]->recruit_certificateEduB,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}
			}if($file[3] == 0){
				$imageFileType = strtolower(pathinfo($_FILES['recruit_copyidCard']['name'],PATHINFO_EXTENSION));						
				$file_check = $_FILES['recruit_copyidCard']['error'];
				$foder = 'copyidCard';
				$do_upload = 'recruit_copyidCard';
				$rand_name = $openyear[0]->openyear_year.'-'.$this->input->post('recruit_idCard').rand();
				if($file_check == 0){
					$data_update += array('recruit_copyidCard' => $rand_name.'.'.$imageFileType);	
					$this->update_img($id,$data_R[0]->recruit_copyidCard,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}else{
					$imageFileType = 0;
					$this->update_img($id,$data_R[0]->recruit_copyidCard,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
				}
			}
			// if(in_array($_FILES['recruit_copyAddress']['error'],$file)){
			// 	$imageFileType = strtolower(pathinfo($_FILES['recruit_copyAddress']['name'],PATHINFO_EXTENSION));						
			// 	$file_check = $_FILES['recruit_copyAddress']['error'];
			// 	$foder = 'copyAddress';
			// 	$do_upload = 'recruit_copyAddress';
			// 	$rand_name = $openyear[0]->openyear_year.'-'.$this->input->post('recruit_idCard').rand();
			// 	if($file_check == 0 ){
			// 		$data_update = array('recruit_copyAddress' => $rand_name.'.'.$imageFileType);	
			// 		$this->update_img($id,$data_R[0]->recruit_copyAddress,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
			// 	}else{
			// 		$imageFileType = 0;
			// 		$this->update_img($id,$data_R[0]->recruit_copyAddress,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name);
			// 	}
				
			// }

			  
			if($this->model_admission->student_update($data_update,$id) == 1){
				$this->session->set_flashdata(array('status'=>'success','msg'=> 'Yes','messge' => 'แก้ไขข้อมูลสำเร็จ รอการตรวจสอบอีกครั้ง!'));
		}
				
			redirect('StudentsEdit');			 	
			
				// define('LINE_API',"https://notify-api.line.me/api/notify"); 
				// $token = "E9GFruPeXW6Mogn156Pllr1D8wWiY69BHfpKzLHBxcj"; 
				// $str = "มีนักเรียนแก้ไขข้อมูล\n".'ตรวจสอบ : '.base_url('admin/recruitstudent');		
				// $res = $this->notify_message($str,$token);
			
		}else{
			$data = $this->dataAll();
			$data['title'] = 'ตรวจสอบและแก้ไขข้อมูล';
			$data['description'] = 'ตรวจสอบและแก้ไขข้อมูล';
			$data['chk_stu'] = $this->db->where('recruit_idCard',$this->input->post('recruit_idCard'))->get('tb_recruitstudent')->result();
			//$data['alert_verify'] = array('1','ยืนยันฉันไม่ใช่โปรแกรมอัตโนมัติ','warning');
			$this->load->view('layout/header.php',$data);
			$this->load->view('layout/navber.php');
			$this->load->view('stu_dataStudent.php');
			$this->load->view('layout/footer.php');
		}
			
		// redirect('checkRegister/dataStudent?a=3&search_stu='.$this->input->post('recruit_idCard').'&Succeed=1');
	}


	public function update_img($id,$img,$file_check,$foder,$do_upload,$data_update,$imageFileType,$rand_name)
	{
		if($file_check == 0 ){
			$config['upload_path']   = 'uploads/recruitstudent/m'.$this->input->post('recruit_regLevel').'/'.$foder.'/'; //Folder สำหรับ เก็บ ไฟล์ที่  Upload
			$config['allowed_types'] = 'gif|jpg|jpeg|png'; //รูปแบบไฟล์ที่ อนุญาตให้ Upload ได้
			$config['max_size']      = 0; //ขนาดไฟล์สูงสุดที่ Upload ได้ (กรณีไม่จำกัดขนาด กำหนดเป็น 0)
			$config['max_width']     = 0; //ขนาดความกว้างสูงสุด (กรณีไม่จำกัดขนาด กำหนดเป็น 0)
			$config['max_height']    = 0;  //ขนาดความสูงสูงสดุ (กรณีไม่จำกัดขนาด กำหนดเป็น 0)
			$config['file_name']  = $rand_name.'.'.$imageFileType; //กำหนดเป็น true ให้ระบบ เปลียนชื่อ ไฟล์  อัตโนมัติ  ป้องกันกรณีชื่อไฟล์ซ้ำกัน
			   $this->load->library('upload', $config);
			   $this->upload->initialize($config);
			   if($this->upload->do_upload($do_upload))
			   {
				   
				$uploadedImage = $this->upload->data();
				$imgdata=exif_read_data($this->upload->upload_path.$this->upload->file_name, 'IFD0');
				$source_path = './uploads/recruitstudent/m'.$this->input->post('recruit_regLevel').'/'.$foder.'/'.$uploadedImage['file_name'];
				$img_target = './uploads/recruitstudent1/'.$uploadedImage['file_name'];
				$config1['image_library'] = 'GD2';
				$config1['source_image'] = $uploadedImage['full_path'];
				$config1['quality'] = '100%';
				$config1['maintain_ratio'] = TRUE;
				$config1['width']         = 600;
				$config1['height']       = 800;				
				

				$this->load->library('image_lib');
				$this->image_lib->initialize($config1);
				 if ( ! $this->image_lib->resize())
				{
						echo $this->image_lib->display_errors();
				}else{
					$this->image_lib->clear();
					$config=array();

                $config['image_library'] = 'gd2';
                $config['source_image'] = $uploadedImage['full_path'];


                switch($imgdata['Orientation']) {
                    case 3:
                        $config['rotation_angle']='180';
                        break;
                    case 6:
                        $config['rotation_angle']='270';
                        break;
                    case 8:
                        $config['rotation_angle']='90';
                        break;
                }

                $this->image_lib->initialize($config); 
                $this->image_lib->rotate();
				}
				
				   @unlink("./uploads/recruitstudent/m".$this->input->post('recruit_regLevel').'/'.$foder.'/'.$img);
				   
				 
			   }
			   else
			   {
				   $error = array('error' => $this->upload->display_errors());
				   //print_r($error['error']);
				   $this->session->set_flashdata(array('status'=>'error','msg'=> 'NO','messge' => $error['error']));
			   }
		   }
		   else{
   
			   if($this->model_admission->student_update($data_update,$id) == 1){
					   $this->session->set_flashdata(array('status'=>'success','msg'=> 'Yes','messge' => 'แก้ไขข้อมูลสำเร็จ'));
			   
			   }
		   }
	}

	public function UploadCertificateAbility($NameOld){
		
		$files = $_FILES['recruit_certificateAbility'];	
			
		$existing_files = explode('|',$NameOld);
        
		$file_names = $existing_files; // เริ่มต้นด้วยชื่อไฟล์เดิม
		$config['upload_path'] = 'uploads/recruitstudent/m'.$this->input->post('recruit_regLevel').'/certificateAbility/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['encrypt_name'] = TRUE; // ใช้เพื่อเปลี่ยนชื่อไฟล์โดยอัตโนมัติเพื่อความปลอดภัย

		
		if (is_array($files['name'])) {
			foreach ($files['name'] as $index => $file_name){
				if (!empty($file_name)){
					//echo '<pre>'; print_r($file_name); exit();
					$_FILES['recruit_certificateAbility']['name'] = $files['name'][$index];
					$_FILES['recruit_certificateAbility']['type'] = $files['type'][$index];
					$_FILES['recruit_certificateAbility']['tmp_name'] = $files['tmp_name'][$index];
					$_FILES['recruit_certificateAbility']['error'] = $files['error'][$index];
					$_FILES['recruit_certificateAbility']['size'] = $files['size'][$index];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('recruit_certificateAbility')) {
						$data = $this->upload->data();
						$file_names[$index] = $data['file_name'];
						
						// Resize image
						$resize_config['image_library'] = 'gd2';
						$resize_config['source_image'] = $data['full_path'];
						$resize_config['maintain_ratio'] = TRUE;
						$resize_config['width'] = 1024;
			
						$this->image_lib->clear();
						$this->image_lib->initialize($resize_config);
						$this->image_lib->resize();
			
						$upload_path = "./uploads/recruitstudent/m".$this->input->post('recruit_regLevel')."/certificateAbility/";
						if (!empty($existing_files[$index]) && file_exists($upload_path.$existing_files[$index])) {
							unlink($upload_path.$existing_files[$index]);
						}

					} else {
						echo $this->upload->display_errors();
					}
				}
			}
		}
			// รวมชื่อไฟล์ใหม่
			$file_names_string = implode('|', $file_names);

		return $file_names_string;
	}

	function notify_message($message,$token){
		$queryData = array('message' => $message);
		$queryData = http_build_query($queryData,'','&');
		$headerOptions = array( 
				'http'=>array(
					'method'=>'POST',
					'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
							."Authorization: Bearer ".$token."\r\n"
							."Content-Length: ".strlen($queryData)."\r\n",
					'content' => $queryData
				),
		);
		$context = stream_context_create($headerOptions);
		$result = file_get_contents(LINE_API,FALSE,$context);
		$res = json_decode($result);
		return $res;
		}


	public function logoutStudent()
	{
		delete_cookie('username'); 
		delete_cookie('password'); 
		$this->session->sess_destroy();
		redirect('welcome');
	}

	

	public function PDFForStudent()
    {

		$path = (dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
		require $path . '/librarie_skj/mpdf/vendor/autoload.php';

		$thai = $this->load->database('thailandpa', TRUE);
		$thpa = $thai->database;
		
		$datapdf = $this->db->select('skjacth_admission.tb_recruitstudent.*')
										->from('skjacth_admission.tb_recruitstudent')
										
		->where('skjacth_admission.tb_recruitstudent.recruit_idCard',$this->session->userdata('StudentIDCrad'))
		->order_by('recruit_id','DESC')
		->get()->result();		
	 	$majorOrder = explode("|",$datapdf[0]->recruit_majorOrder);	
		$majorOrderFull = $this->db->select("course_initials")->where('course_id',$majorOrder[0])->get('tb_course')->row();
		//echo '<pre>'; print_r($datapdf); exit();
		

    	$date_Y = date('Y',strtotime($datapdf[0]->recruit_birthday))+543;
    	$TH_Month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    	$date_D = date('d',strtotime($datapdf[0]->recruit_birthday));
    	$date_M = date('n',strtotime($datapdf[0]->recruit_birthday));

		$date_Y_regis = date('Y',strtotime($datapdf[0]->recruit_date))+543;
    	$date_D_regis = date('d',strtotime($datapdf[0]->recruit_date));
    	$date_M_regis = date('n',strtotime($datapdf[0]->recruit_date));

    	$sch = explode("โรงเรียน", $datapdf[0]->recruit_oldSchool); //แยกคำว่าโรงเรียน
    	
        $mpdf = new \Mpdf\Mpdf([
					'default_font_size' => 16,
					'default_font' => 'sarabun',
					'format' => [210, 90]
				]);
        $mpdf->SetTitle($datapdf[0]->recruit_prefix.$datapdf[0]->recruit_firstName.' '.$datapdf[0]->recruit_lastName);
		$mpdf->showImageErrors = true;
		// ส่วนที่ 2recruit_date
		$html = '<div style="position:absolute;top:100px;left:75px; width:100%"><img style="width:120px;hight:100px;" src='.FCPATH.'uploads/recruitstudent/m'.$datapdf[0]->recruit_regLevel.'/img/'.$datapdf[0]->recruit_img.'></div>'; 
		$html .= '<div style="position:absolute;top:57px;left:150px; width:100%">'.sprintf("%04d",$datapdf[0]->recruit_id).'</div>'; //เลขที่สมัคร
		$html .= '<div style="position:absolute;top:100px;left:250px; width:100%">'.$datapdf[0]->recruit_prefix.$datapdf[0]->recruit_firstName.'</div>'; //ชื่อผู้สมัคร
		$html .= '<div style="position:absolute;top:100px;left:480px; width:100%">'.$datapdf[0]->recruit_lastName.'</div>'; //นามสกุลผู้สมัคร
		$html .= '<div style="position:absolute;top:127;left:400px; width:100%">'.$datapdf[0]->recruit_idCard.'</div>';	
		$html .= '<div style="position:absolute;top:155;left:270px; width:100%"> '.$datapdf[0]->recruit_tpyeRoom.'</div>';	
		$html .= '<div style="position:absolute;top:200px;left:340px; width:100%"><img style="width:120px;hight:100px;" src='.FCPATH.'asset/img/license.png'.'></div>';
		$html .= '<div style="position:absolute;top:255x;left:360px; width:100%">'.$date_D_regis.' '.$TH_Month[$date_M_regis-1].' '.$date_Y_regis.'</div>'; // วันที่สมัครตอนที่ 2

		$mpdf->SetDocTemplate('uploads/recruitstudent/pdf_registudentForStudent'.'.pdf',true);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Reg_'.$datapdf[0]->recruit_idCard.'.pdf','I'); // opens in browser
        //$mpdf->Output('arjun.pdf','D'); // it downloads the file into the user system, with give name
    }

	public function pdf()
    {
		$path = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
		require $path . '/librarie_skj/mpdf/vendor/autoload.php';

		$thai = $this->load->database('thailandpa', TRUE);
		$thpa = $thai->database;
		
		$datapdf = $this->db->select('skjacth_admission.tb_recruitstudent.*,
										'.$thpa.'.province.PROVINCE_NAME,
										'.$thpa.'.district.DISTRICT_NAME,
										'.$thpa.'.amphur.AMPHUR_NAME')
										->from('skjacth_admission.tb_recruitstudent')
										->join($thpa.'.province','skjacth_admission.tb_recruitstudent.recruit_homeProvince = '.$thpa.'.province.PROVINCE_ID', 'INNER')
										->join($thpa.'.district','skjacth_admission.tb_recruitstudent.recruit_homeSubdistrict = '.$thpa.'.district.DISTRICT_ID', 'INNER')
										->join($thpa.'.amphur','skjacth_admission.tb_recruitstudent.recruit_homedistrict = '.$thpa.'.amphur.AMPHUR_ID', 'INNER')
		->where('skjacth_admission.tb_recruitstudent.recruit_id',$this->session->userdata('loginStudentID'))
		->get()->result();
		//echo '<pre>'; print_r($datapdf); exit();
		

    	$date_Y = date('Y',strtotime($datapdf[0]->recruit_birthday))+543;
    	$TH_Month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    	$date_D = date('d',strtotime($datapdf[0]->recruit_birthday));
    	$date_M = date('n',strtotime($datapdf[0]->recruit_birthday));

		$date_Y_regis = date('Y',strtotime($datapdf[0]->recruit_date))+543;
    	$date_D_regis = date('d',strtotime($datapdf[0]->recruit_date));
    	$date_M_regis = date('n',strtotime($datapdf[0]->recruit_date));

		//print_r($date_M_regis); exit();
    	$sch = explode("โรงเรียน", $datapdf[0]->recruit_oldSchool); //แยกคำว่าโรงเรียน
    	
        $mpdf = new \Mpdf\Mpdf([
					'default_font_size' => 16,
					'default_font' => 'sarabun'
				]);
        $mpdf->SetTitle($datapdf[0]->recruit_prefix.$datapdf[0]->recruit_firstName.' '.$datapdf[0]->recruit_lastName);
        $html = '<div style="position:absolute;top:60px;left:630px; width:100%"><img style="width: 120px;hight:auto;" src='.base_url('uploads/recruitstudent/m'.$datapdf[0]->recruit_regLevel.'/img/'.$datapdf[0]->recruit_img).'></div>'; 
        $html .= '<div style="position:absolute;top:18px;left:690px; width:100%">'.sprintf("%04d",$datapdf[0]->recruit_id).'</div>'; //เลขที่สมัคร
		$html .= '<div style="position:absolute;top:257px;left:180px; width:100%">'.$datapdf[0]->recruit_prefix.$datapdf[0]->recruit_firstName.'</div>'; //ชื่อผู้สมัคร
		$html .= '<div style="position:absolute;top:257px;left:470px; width:100%">'.$datapdf[0]->recruit_lastName.'</div>'; //นามสกุลผู้สมัคร
		$html .= '<div style="position:absolute;top:287px;left:320px; width:100%">'.($sch[0] == '' ? $sch[1] : $sch[0]).'</div>'; //โรงเรียนเดิม
		$html .= '<div style="position:absolute;top:315px;left:170px; width:100%">'.$datapdf[0]->recruit_district.'</div>'; //อำเภอโรงเรียน
		$html .= '<div style="position:absolute;top:315px;left:510px; width:100%">'.$datapdf[0]->recruit_province.'</div>'; //จังหวัดโรงเรียน
		$html .= '<div style="position:absolute;top:343px;left:140px; width:100%">'.$date_D.'</div>'; //วันเกิด
		$html .= '<div style="position:absolute;top:343px;left:235px; width:100%">'.$TH_Month[$date_M-1].'</div>'; //เดือนเกิด
		$html .= '<div style="position:absolute;top:343px;left:360px; width:100%">'.$date_Y.'</div>'; //ปีเกิด
		$html .= '<div style="position:absolute;top:343px;left:455px; width:100%">'.$this->timeago->getAge($datapdf[0]->recruit_birthday).'</div>'; //อายุ
		$html .= '<div style="position:absolute;top:343px;left:600px; width:100%">'.$datapdf[0]->recruit_race.'</div>'; //เชื้อชาติ
		$html .= '<div style="position:absolute;top:372px;left:162px; width:100%">'.$datapdf[0]->recruit_nationality.'</div>'; //สัญชาติ
		$html .= '<div style="position:absolute;top:372px;left:300px; width:100%">'.$datapdf[0]->recruit_religion.'</div>'; //ศาสนา
		$html .= '<div style="position:absolute;top:372px;left:540px; width:100%">'.$datapdf[0]->recruit_idCard.'</div>'; //เลขบัตรประจำตัวประชาชน
		$html .= '<div style="position:absolute;top:400px;left:350px; width:100%">'.$datapdf[0]->recruit_phone.'</div>'; //เบอร์โทรติดต่อ
		$html .= '<div style="position:absolute;top:428px;left:240px; width:100%">'.$datapdf[0]->recruit_homeNumber.'</div>'; //บ้านเลขที่ //แก้*****
		$html .= '<div style="position:absolute;top:428px;left:340px; width:100%">'.$datapdf[0]->recruit_homeGroup.'</div>'; //หมู่
		$html .= '<div style="position:absolute;top:428px;left:420px; width:100%">'.$datapdf[0]->recruit_homeRoad.'</div>'; //ถนน
		$html .= '<div style="position:absolute;top:428px;left:600px; width:100%">'.$datapdf[0]->DISTRICT_NAME.'</div>'; //ตำบล
		$html .= '<div style="position:absolute;top:455px;left:175px; width:100%">'.$datapdf[0]->AMPHUR_NAME.'</div>'; //อำเภอ
		$html .= '<div style="position:absolute;top:455px;left:390px; width:100%">'.$datapdf[0]->PROVINCE_NAME.'</div>'; //จังหวัด
		$html .= '<div style="position:absolute;top:455px;left:620px; width:100%">'.$datapdf[0]->recruit_homePostcode.'</div>'; //รหัสไปรษณีย์
		// ส่วนที่ 2recruit_date
		$html .= '<div style="position:absolute;top:900px;left:75px; width:100%"><img style="width:120px;hight:100px;" src='.base_url('uploads/recruitstudent/m'.$datapdf[0]->recruit_regLevel.'/img/'.$datapdf[0]->recruit_img).'></div>'; 
		$html .= '<div style="position:absolute;top:870px;left:150px; width:100%">'.sprintf("%04d",$datapdf[0]->recruit_id).'</div>'; //เลขที่สมัคร
		$html .= '<div style="position:absolute;top:910px;left:250px; width:100%">'.$datapdf[0]->recruit_prefix.$datapdf[0]->recruit_firstName.'</div>'; //ชื่อผู้สมัคร
		$html .= '<div style="position:absolute;top:910px;left:480px; width:100%">'.$datapdf[0]->recruit_lastName.'</div>'; //นามสกุลผู้สมัคร
		$html .= '<div style="position:absolute;top:940;left:400px; width:100%">'.$datapdf[0]->recruit_idCard.'</div>';	
		$html .= '<div style="position:absolute;top:967;left:270px; width:100%">'.$datapdf[0]->recruit_tpyeRoom.'</div>';	
		$html .= '<div style="position:absolute;top:808px;left:360px; width:100%">'.$date_D_regis.' '.$TH_Month[$date_M_regis-1].' '.$date_Y_regis.'</div>'; // วันที่สมัครตอนที่ 1

		$html .= '<div style="position:absolute;top:1067px;left:360px; width:100%">'.$date_D_regis.' '.$TH_Month[$date_M_regis-1].' '.$date_Y_regis.'</div>'; // วันที่สมัครตอนที่ 2

		
		$AtpyeRoom = array('ห้องเรียนความเป็นเลิศทางด้านวิชาการ (Science Match and Technology Program)','ห้องเรียนความเป็นเลิศทางด้านภาษา (Chinese English Program)','ห้องเรียนความเป็นเลิศทางด้านดนตรี ศิลปะ การแสดง (Preforming Art Program)','ห้องเรียนความเป็นเลิศด้านการงานอาชีพ (Career Program)','ห้องเรียนความเป็นเลิศด้านกีฬา (Sport Program)' ); 
    	if($datapdf[0]->recruit_tpyeRoom == $AtpyeRoom[0]){
    		$html .= '<div style="position:absolute;top:570px;left:100px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
    	}elseif($datapdf[0]->recruit_tpyeRoom == $AtpyeRoom[1]){
    		$html .= '<div style="position:absolute;top:600px;left:100px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
    	}elseif($datapdf[0]->recruit_tpyeRoom == $AtpyeRoom[2]){
    		$html .= '<div style="position:absolute;top:623px;left:100px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
    	}elseif($datapdf[0]->recruit_tpyeRoom == $AtpyeRoom[3]){
    		$html .= '<div style="position:absolute;top:645px;left:100px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
		}elseif($datapdf[0]->recruit_tpyeRoom == $AtpyeRoom[4]){
    		$html .= '<div style="position:absolute;top:545px;left:100px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
		}

    	if($datapdf[0]->recruit_certificateEdu != ''){
    		$html .= '<div style="position:absolute;top:720px;left:100px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
    	}
    	if($datapdf[0]->recruit_copyidCard != ''){
    		$html .= '<div style="position:absolute;top:720px;left:245px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
    	}
    	if($datapdf[0]->recruit_copyAddress != ''){
    		$html .= '<div style="position:absolute;top:720px;left:435px; width:100%"><img src="https://img.icons8.com/metro/26/000000/checkmark.png"/></div>';
    	}
		$mpdf->SetDocTemplate('uploads/recruitstudent/pdf_registudent'.$datapdf[0]->recruit_regLevel.'.pdf',true);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Reg_'.$datapdf[0]->recruit_idCard.'.pdf','I'); // opens in browser
        //$mpdf->Output('arjun.pdf','D'); // it downloads the file into the user system, with give name
    }

	public function SchoolList(){
		// POST data
		$postData = $this->input->post();
	
		// Get data
		$data = $this->model_admission->getSchool($postData);
	
		echo json_encode($data);
	  }



}
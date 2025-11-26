<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ReportController extends BaseController
{
    protected $db;
    protected $db_thailand;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->db_thailand = \Config\Database::connect('thailandpa');

        // Load External Libraries (mPDF, Google, etc.)
        $path = dirname(dirname(dirname(dirname(dirname((dirname(__FILE__)))))));
        if (file_exists($path . '/librarie_skj/mpdf/vendor/autoload.php')) {
            require_once $path . '/librarie_skj/mpdf/vendor/autoload.php';
        }
    }

    public function index()
    {
        $data['title'] = 'รายงานและการพิมพ์';
        
        // Fetch Years
        $data['years'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_year')
            ->groupBy('recruit_year')
            ->orderBy('recruit_year', 'DESC')
            ->get()->getResult();

        // Fetch Courses (Types)
        $data['courses'] = $this->db->table('tb_course')
            ->select('course_fullname, course_gradelevel')
            ->groupBy('course_fullname')
            ->get()->getResult();

        return view('Admin/PageAdminReport/PageAdminReportIndex', $data);
    }

    public function print_all()
    {
        $year = $this->request->getGet('year');
        $level = $this->request->getGet('level'); // 1 or 4
        $type = $this->request->getGet('type'); // e.g., 'ห้องเรียนพิเศษ...'

        if (empty($year) || empty($level)) {
            return "กรุณาเลือกปีการศึกษาและระดับชั้น";
        }

        // Check mPDF
        if (!class_exists('\Mpdf\Mpdf')) {
            // Try to load vendor autoload if not loaded (though CI4 usually handles this)
             $path = dirname(dirname(dirname(dirname(dirname((dirname(__FILE__)))))));
             if (file_exists($path . '/librarie_skj/google_sheet/vendor/autoload.php')) {
                 require_once $path . '/librarie_skj/google_sheet/vendor/autoload.php';
             } else {
                 return "mPDF library not found. Please install via Composer.";
             }
        }

        $builder = $this->db->table('tb_recruitstudent');
        $builder->select('tb_recruitstudent.*, tb_quota.quota_explain');
        // Joins for address (using raw SQL for cross-database joins if needed, or assuming views/synonyms)
        // Since we have separate DB connections, standard join might fail if user doesn't have cross-db permissions or config isn't set for it.
        // However, in the previous code (Admission.php), it used full table names 'skjacth_thailandpa.province'.
        // We will try that approach.
        
        $thpa = 'skjacth_thailandpa'; 
        
        $builder->join($thpa.'.province', 'tb_recruitstudent.recruit_homeProvince = '.$thpa.'.province.PROVINCE_ID', 'left');
        $builder->join($thpa.'.district', 'tb_recruitstudent.recruit_homeSubdistrict = '.$thpa.'.district.DISTRICT_ID', 'left');
        $builder->join($thpa.'.amphur', 'tb_recruitstudent.recruit_homedistrict = '.$thpa.'.amphur.AMPHUR_ID', 'left');
        $builder->join('tb_quota', 'tb_quota.quota_key = tb_recruitstudent.recruit_category', 'left');
        
        $builder->where('recruit_year', $year);
        $builder->where('recruit_regLevel', $level);
        
        if (!empty($type) && $type != 'all') {
            $builder->where('recruit_tpyeRoom', urldecode($type));
        }

        $builder->orderBy('recruit_id', 'ASC');
        $students = $builder->get()->getResult();

        if (empty($students)) {
            return "ไม่พบข้อมูลนักเรียน";
        }

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp' // Ensure temp dir exists
        ]);

        foreach ($students as $student) {
            $html = $this->generateStudentPDFHtml($student);
            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
        }
        
        // Remove last blank page if any (AddPage adds a new one)
        // mPDF handles this, usually AddPage is called *between* pages. 
        // Better loop logic:
        // $mpdf->WriteHTML($htmls[0]);
        // for($i=1; $i<count($htmls); $i++) { $mpdf->AddPage(); $mpdf->WriteHTML($htmls[$i]); }
        // But the current loop adds a page *after* each student, leaving a blank one at end.
        // Let's fix:
        
        // Actually, the previous code did AddPage at the end. Let's stick to a cleaner way.
        // We will reset mPDF and loop correctly.
        
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp'
        ]);
        
        $count = count($students);
        foreach ($students as $index => $student) {
            $html = $this->generateStudentPDFHtml($student);
            $mpdf->WriteHTML($html);
            if ($index < $count - 1) {
                $mpdf->AddPage();
            }
        }

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Recruit_Report.pdf', 'I');
    }

    private function generateStudentPDFHtml($student)
    {
        // Helper for Thai Date
        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $date_Y = date('Y', strtotime($student->recruit_birthday)) + 543;
        $date_D = date('d', strtotime($student->recruit_birthday));
        $date_M = date('n', strtotime($student->recruit_birthday));
        
        // Calculate Age
        $birthDate = new \DateTime($student->recruit_birthday);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $sch = explode("โรงเรียน", $student->recruit_oldSchool);
        $oldSchool = ($sch[0] == '' && isset($sch[1])) ? $sch[1] : $sch[0];

        // Use absolute positioning as per original design
        // Note: This requires the background template image to be set in mPDF or CSS.
        // The original code didn't show setting a background, but usually these forms have one.
        // Assuming no background image for now, or we need to add one.
        // If there's a background image (e.g. application form), we should add it.
        // I'll stick to the structure provided in the previous Admission.php
        
        $html = '<div style="position:relative; width:100%; height:100%;">';
        
        // Image
        $imgUrl = base_url('uploads/recruitstudent/m'.$student->recruit_regLevel.'/img/'.$student->recruit_img);
        $html .= '<div style="position:absolute;top:60px;left:635px;"><img style="width: 120px;height:100px;" src="'.$imgUrl.'"></div>';
        
        // Data Fields
        $html .= '<div style="position:absolute;top:18px;left:100px;">รอบ '.$student->quota_explain.'</div>';
        $html .= '<div style="position:absolute;top:18px;left:690px;">'.sprintf("%04d",$student->recruit_id).'</div>';
        $html .= '<div style="position:absolute;top:232px;left:180px;">'.$student->recruit_prefix.$student->recruit_firstName.'</div>';
        $html .= '<div style="position:absolute;top:232px;left:470px;">'.$student->recruit_lastName.'</div>';
        $html .= '<div style="position:absolute;top:262px;left:340px;">'.$oldSchool.'</div>';
        $html .= '<div style="position:absolute;top:290px;left:170px;">'.$student->DISTRICT_NAME.'</div>'; // Using joined column
        $html .= '<div style="position:absolute;top:290px;left:510px;">'.$student->PROVINCE_NAME.'</div>';
        
        $html .= '<div style="position:absolute;top:318px;left:160px;">'.$date_D.'</div>';
        $html .= '<div style="position:absolute;top:318px;left:240px;">'.$TH_Month[$date_M-1].'</div>';
        $html .= '<div style="position:absolute;top:318px;left:370px;">'.$date_Y.'</div>';
        $html .= '<div style="position:absolute;top:318px;left:470px;">'.$age.'</div>';
        $html .= '<div style="position:absolute;top:318px;left:600px;">'.$student->recruit_race.'</div>';
        
        $html .= '<div style="position:absolute;top:345px;left:162px;">'.$student->recruit_nationality.'</div>';
        $html .= '<div style="position:absolute;top:345px;left:300px;">'.$student->recruit_religion.'</div>';
        $html .= '<div style="position:absolute;top:345px;left:540px;">'.$student->recruit_idCard.'</div>';
        
        $html .= '<div style="position:absolute;top:373px;left:350px;">'.$student->recruit_phone.'</div>';
        $html .= '<div style="position:absolute;top:373px;left:600px;">'.$student->recruit_grade.'</div>';
        
        $html .= '</div>';

        return $html;
    }
}

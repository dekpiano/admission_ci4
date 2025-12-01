<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;

class AdminControlRecruit extends BaseController
{
    public function index()
    {
        $model = new AdmissionModel();
        
        // Get distinct years
        $years = $model->select('recruit_year')->distinct()->orderBy('recruit_year', 'DESC')->findColumn('recruit_year');
        
        if (empty($years)) {
            $years = [date('Y')];
        }

        // Get selected year
        $selectedYear = $this->request->getVar('year') ?? $years[0];

        // Join with Quota table to get readable category names
        $data['recruits'] = $model->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_course.course_initials')
                                  ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
                                  ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
                                  ->where('recruit_year', $selectedYear)
                                  ->groupBy('tb_recruitstudent.recruit_id')
                                  ->orderBy('recruit_id', 'DESC')
                                  ->findAll();

        $data['years'] = $years;
        $data['selected_year'] = $selectedYear;
        $data['title'] = 'ข้อมูลผู้สมัคร';

        return view('Admin/PageAdminRecruit/PageAdminRecruitIndex', $data);
    }

    public function view($id = null)
    {
        $model = new AdmissionModel();
        $courseModel = new \App\Models\CourseModel(); 
        
        $data['recruit'] = $model->select('tb_recruitstudent.*, tb_course.course_fullname as course_name_joined')
                                 ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
                                 ->find($id);

        if (empty($data['recruit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้สมัคร ID: ' . $id);
        }

        // Process recruit_majorOrder if it exists and is in the new format (pipe-separated IDs)
        if (!empty($data['recruit']['recruit_majorOrder'])) {
            $courseIds = explode('|', $data['recruit']['recruit_majorOrder']);
            $majorOrderList = [];

            // Fetch course initials for each ID
            if (!empty($courseIds)) {
                // Using whereIn for a single query to get all courses
                $courses = $courseModel->select('course_id, course_initials')
                                       ->whereIn('course_id', $courseIds)
                                       ->findAll();
                
                // Map course_id to course_initials for easy lookup
                $courseMap = [];
                foreach ($courses as $course) {
                    $courseMap[$course['course_id']] = $course['course_initials'];
                }

                // Reconstruct the list in the original order specified by recruit_majorOrder
                foreach ($courseIds as $id) {
                    if (isset($courseMap[$id])) {
                        $majorOrderList[] = $courseMap[$id];
                    } else {
                        // If a course ID from recruit_majorOrder isn't found, add a placeholder or skip
                        $majorOrderList[] = 'ไม่พบหลักสูตร (' . $id . ')';
                    }
                }
            }
            $data['recruit']['major_order_list'] = $majorOrderList;
        } else {
            $data['recruit']['major_order_list'] = []; // Ensure it's always an array even if recruit_majorOrder is empty
        }

        $data['remote_base_url'] = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
        $data['title'] = 'รายละเอียดผู้สมัคร';
        return view('Admin/PageAdminRecruit/PageAdminRecruitView', $data);
    }

    public function edit($id = null)
    {
        $model = new AdmissionModel();
        $data['recruit'] = $model->find($id);

        if (empty($data['recruit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้สมัคร ID: ' . $id);
        }

        $data['courses'] = $model->getAllCourses();
        $data['quotas'] = $model->getAllQuotas();
        $data['remote_base_url'] = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
        $data['title'] = 'แก้ไขข้อมูลผู้สมัคร';
        return view('Admin/PageAdminRecruit/PageAdminRecruitEdit', $data);
    }

    public function update($id = null)
    {
        $model = new AdmissionModel();
        $courseModel = new \App\Models\CourseModel();

        // Get Course Name from ID for backward compatibility (optional but recommended)
        $courseId = $this->request->getPost('recruit_tpyeRoom_id');
        $courseName = '';
        if ($courseId) {
            $course = $courseModel->find($courseId);
            if ($course) {
                $courseName = $course['course_fullname'];
            }
        }

        $data = [
            'recruit_idCard' => $this->request->getPost('recruit_idCard'),
            'recruit_prefix' => $this->request->getPost('recruit_prefix'),
            'recruit_firstName' => $this->request->getPost('recruit_firstName'),
            'recruit_lastName' => $this->request->getPost('recruit_lastName'),
            'recruit_birthday' => $this->request->getPost('recruit_birthday'),
            'recruit_phone' => $this->request->getPost('recruit_phone'),
            'recruit_oldSchool' => $this->request->getPost('recruit_oldSchool'),
            'recruit_grade' => $this->request->getPost('recruit_grade'),
            'recruit_regLevel' => $this->request->getPost('recruit_regLevel'),
            'recruit_category' => $this->request->getPost('recruit_category'),
            'recruit_tpyeRoom' => $courseName, // Keep text for legacy
            'recruit_tpyeRoom_id' => $courseId, // New ID
            'recruit_status' => $this->request->getPost('recruit_status'),
            'recruit_homeNumber' => $this->request->getPost('recruit_homeNumber'),
            'recruit_homeGroup' => $this->request->getPost('recruit_homeGroup'),
            'recruit_homeRoad' => $this->request->getPost('recruit_homeRoad'),
            'recruit_homeSubdistrict' => $this->request->getPost('recruit_homeSubdistrict'),
            'recruit_homedistrict' => $this->request->getPost('recruit_homedistrict'),
            'recruit_homeProvince' => $this->request->getPost('recruit_homeProvince'),
            'recruit_homePostcode' => $this->request->getPost('recruit_homePostcode'),
        ];
        
        // Handle file upload
        $img = $this->request->getFile('recruit_img');
        if ($img->isValid() && !$img->hasMoved()) {
            $currentRecruit = $model->find($id);
            $regLevel = $currentRecruit['recruit_regLevel'];
            
            $remoteUpload = new \App\Libraries\RemoteUpload();
            $subPath = 'admission/recruitstudent/m' . $regLevel . '/img';
            
            $result = $remoteUpload->upload($img, $subPath);
            
            if ($result && $result['status'] === 'success') {
                $data['recruit_img'] = $result['filename'];
                // Delete old image if exists
                if (!empty($currentRecruit['recruit_img'])) {
                    $remoteUpload->delete($currentRecruit['recruit_img'], $subPath);
                }
            }
        }

        $model->update($id, $data);

        return redirect()->to(site_url('skjadmin/recruits'))->with('success', 'อัปเดตข้อมูลผู้สมัครสำเร็จ');
    }

    public function delete($id = null)
    {
        $model = new AdmissionModel();
        $recruit = $model->find($id); // Find the record first

        if ($recruit) {
            $remoteUpload = new \App\Libraries\RemoteUpload();
            $regLevel = $recruit['recruit_regLevel'];

            // Array of fields and their corresponding subdirectories
            $fileFields = [
                'recruit_img' => 'img',
                'recruit_certificateEdu' => 'certificate',
                'recruit_certificateEduB' => 'certificateB',
                'recruit_copyidCard' => 'copyidCard',
                'recruit_copyAddress' => 'copyAddress',
            ];

            // Delete individual files
            foreach ($fileFields as $field => $folder) {
                if (!empty($recruit[$field])) {
                    $subPath = 'admission/recruitstudent/m' . $regLevel . '/' . $folder;
                    $remoteUpload->delete($recruit[$field], $subPath);
                }
            }

            // Delete ability certificate files (which can be multiple)
            if (!empty($recruit['recruit_certificateAbility'])) {
                $ability_files = explode('|', $recruit['recruit_certificateAbility']);
                if (count($ability_files) > 0) {
                    $subPath = 'admission/recruitstudent/m' . $regLevel . '/certificateAbility';
                    $remoteUpload->delete($ability_files, $subPath);
                }
            }
        }

        $model->delete($id); // Now delete the DB record

        return redirect()->to(site_url('skjadmin/recruits'))->with('success', 'ลบข้อมูลผู้สมัครและไฟล์ที่เกี่ยวข้องสำเร็จ');
    }

    public function updateStatus()
    {
        $request = service('request');
        $id = $request->getPost('id');
        $status = $request->getPost('status');

        if ($id && $status) {
            $model = new AdmissionModel();
            $updated = $model->update($id, ['recruit_status' => $status]);

            if ($updated) {
                return $this->response->setJSON(['success' => true]);
            }
        }

        return $this->response->setJSON(['success' => false]);
    }
    public function getRecruitsAjax()
    {
        $request = service('request');
        $model = new AdmissionModel();

        $draw = intval($request->getVar('draw'));
        $start = intval($request->getVar('start'));
        $length = intval($request->getVar('length'));
        
        $search = $request->getVar('search');
        $searchValue = $search['value'] ?? '';
        
        $year = $request->getVar('year') ?? date('Y');

        // 1. Get Total Records (for this year)
        $totalRecords = $model->where('recruit_year', $year)->countAllResults();

        // 2. Count Filtered Records
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id')
                ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
                ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
                ->where('tb_recruitstudent.recruit_year', $year);

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('tb_recruitstudent.recruit_id', $searchValue)
                ->orLike('tb_recruitstudent.recruit_firstName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_lastName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_category', $searchValue)
                ->orLike('tb_quota.quota_key', $searchValue)
                ->orLike('tb_quota.quota_explain', $searchValue)
                ->orLike('tb_course.course_initials', $searchValue)
                ->orLike('tb_course.course_fullname', $searchValue)
                ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults(); // Consumes builder

        // 3. Fetch Data
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id, tb_recruitstudent.recruit_prefix, tb_recruitstudent.recruit_firstName, tb_recruitstudent.recruit_lastName, tb_quota.quota_explain, tb_recruitstudent.recruit_category, tb_course.course_initials, tb_course.course_fullname, tb_recruitstudent.recruit_tpyeRoom, tb_recruitstudent.recruit_status')
                ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
                ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
                ->where('tb_recruitstudent.recruit_year', $year);

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('tb_recruitstudent.recruit_id', $searchValue)
                ->orLike('tb_recruitstudent.recruit_firstName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_lastName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_category', $searchValue)
                ->orLike('tb_quota.quota_key', $searchValue)
                ->orLike('tb_quota.quota_explain', $searchValue)
                ->orLike('tb_course.course_initials', $searchValue)
                ->orLike('tb_course.course_fullname', $searchValue)
                ->groupEnd();
        }

        // Apply Pagination
        if ($length > 0) {
            $builder->limit($length, $start);
        }
        
        $builder->groupBy('tb_recruitstudent.recruit_id');
        $builder->orderBy('tb_recruitstudent.recruit_id', 'DESC');
        
        $recruits = $builder->get()->getResultArray();

        $data = [];
        foreach ($recruits as $recruit) {
            $status = $recruit['recruit_status'] ?? 'รอตรวจสอบ';
            $statusClass = 'bg-label-primary';
            if ($status === 'ผ่านการตรวจสอบ') {
                $statusClass = 'bg-label-success';
            } elseif ($status !== 'รอตรวจสอบ') {
                $statusClass = 'bg-label-danger';
            }

            $actions = '<div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) . '"><i class="bx bx-show-alt me-1"></i> ดูรายละเอียด</a>
                            <a class="dropdown-item" href="' . site_url('skjadmin/recruits/edit/' . $recruit['recruit_id']) . '"><i class="bx bx-edit-alt me-1"></i> แก้ไข</a>
                            <a class="dropdown-item" href="' . site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-printer me-1"></i> พิมพ์ใบสมัคร</a>
                            <a class="dropdown-item" href="' . site_url('skjadmin/recruits/delete/' . $recruit['recruit_id']) . '" onclick="return confirm(\'คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลผู้สมัครนี้?\')"><i class="bx bx-trash me-1"></i> ลบ</a>
                          </div>
                        </div>';

            $data[] = [
                'recruit_id' => esc($recruit['recruit_id'] ?? ''),
                'name' => '<strong>' . esc(($recruit['recruit_prefix'] ?? '') . ($recruit['recruit_firstName'] ?? '') . ' ' . ($recruit['recruit_lastName'] ?? '')) . '</strong>',
                'category' => esc($recruit['quota_explain'] ?? $recruit['recruit_category']),
                'course' => esc($recruit['course_initials'] ?? $recruit['course_fullname'] ?? $recruit['recruit_tpyeRoom']),
                'status' => '<span class="badge ' . $statusClass . ' me-1">' . esc($status) . '</span>',
                'actions' => $actions
            ];
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
        ];

        return $this->response->setJSON($output);
    }

    public function print($id)
    {
        $db = \Config\Database::connect();
        $model = new AdmissionModel();
        $recruit = $model->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_quota.quota_key, tb_course.course_fullname as course_name_joined')
                         ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
                         ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
                         ->find($id);

        if (!$recruit) {
            return "ไม่พบข้อมูลผู้สมัคร";
        }

        // Load mPDF
        $path = dirname(dirname(dirname(dirname(dirname((dirname(__FILE__)))))));
        if (file_exists($path . '/librarie_skj/mpdf/vendor/autoload.php')) {
            require_once $path . '/librarie_skj/mpdf/vendor/autoload.php';
        } else {
            return "mPDF library not found.";
        }

        $customFontDir = $path . '/librarie_skj/vendor/mpdf/mpdf/ttfonts';
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [$customFontDir]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'THSarabun.ttf',
                    'I' => 'THSarabun-Italic.ttf',
                    'B' => 'THSarabun-Bold.ttf',
                    'BI' => 'THSarabun-BoldItalic.ttf',
                ]
            ],
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp'
        ]);

        // Prepare Data
        $TH_Month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        
        $date_Y = date('Y',strtotime($recruit['recruit_birthday']))+543;
        $date_D = date('d',strtotime($recruit['recruit_birthday']));
        $date_M = date('n',strtotime($recruit['recruit_birthday']));

        $date_Y_regis = date('Y',strtotime($recruit['recruit_date']))+543;
        $date_D_regis = date('d',strtotime($recruit['recruit_date']));
        $date_M_regis = date('n',strtotime($recruit['recruit_date']));

        // Calculate Age
        $birthDate = new \DateTime($recruit['recruit_birthday']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $sch = explode("โรงเรียน", $recruit['recruit_oldSchool']);
        $oldSchool = ($sch[0] == '' && isset($sch[1])) ? $sch[1] : $sch[0];

        $mpdf->SetTitle($recruit['recruit_prefix'].$recruit['recruit_firstName'].' '.$recruit['recruit_lastName']);
        
        // Generate HTML
        $html = '';
        $baseUrl = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
        $imgUrl = base_url('image-proxy?file=recruitstudent/m' . $recruit['recruit_regLevel'] . '/img/' . $recruit['recruit_img']);
        
        if (!empty($recruit['recruit_img'])) {
             $html .= '<div style="position:absolute;top:90px;left:635px; width:100%"><img style="width: 120px;height:100px;" src="'.$imgUrl.'"></div>'; 
        }
        
        $html .= '<div style="position:absolute;top:18px;left:100px; width:100%;font-size:16px;">'.$recruit['quota_explain'].'</div>';
        $html .= '<div style="position:absolute;top:180px;left:555px; width:100%;font-size:24px;">'.$recruit['recruit_regLevel'].'</div>';
        $html .= '<div style="position:absolute;top:63px;left:700px; width:100%">'.sprintf("%04d",$recruit['recruit_id']).'</div>';
        $html .= '<div style="position:absolute;top:280px;left:180px; width:100%">'.$recruit['recruit_prefix'].$recruit['recruit_firstName'].'</div>';
        $html .= '<div style="position:absolute;top:280px;left:470px; width:100%">'.$recruit['recruit_lastName'].'</div>';
        $html .= '<div style="position:absolute;top:307px;left:270px; width:100%">'.$oldSchool.'</div>';
        $html .= '<div style="position:absolute;top:335px;left:170px; width:100%">'.$recruit['recruit_district'].'</div>';
        $html .= '<div style="position:absolute;top:335px;left:510px; width:100%">'.$recruit['recruit_province'].'</div>';
        $html .= '<div style="position:absolute;top:363px;left:160px; width:100%">'.$date_D.'</div>';
        $html .= '<div style="position:absolute;top:363px;left:240px; width:100%">'.$TH_Month[$date_M-1].'</div>';
        $html .= '<div style="position:absolute;top:363px;left:370px; width:100%">'.$date_Y.'</div>';
        $html .= '<div style="position:absolute;top:363px;left:470px; width:100%">'.$age.'</div>';
        $html .= '<div style="position:absolute;top:363px;left:600px; width:100%">'.$recruit['recruit_race'].'</div>';
        $html .= '<div style="position:absolute;top:390px;left:162px; width:100%">'.$recruit['recruit_nationality'].'</div>';
        $html .= '<div style="position:absolute;top:390px;left:300px; width:100%">'.$recruit['recruit_religion'].'</div>';
        $html .= '<div style="position:absolute;top:390px;left:540px; width:100%">'.$recruit['recruit_idCard'].'</div>';
        $html .= '<div style="position:absolute;top:418px;left:350px; width:100%">'.$recruit['recruit_phone'].'</div>';
        $html .= '<div style="position:absolute;top:418px;left:600px; width:100%">'.$recruit['recruit_grade'].'</div>';
        $html .= '<div style="position:absolute;top:445px;left:270px; width:100%">'.$recruit['recruit_homeNumber'].'</div>';
        $html .= '<div style="position:absolute;top:445px;left:390px; width:100%">'.$recruit['recruit_homeGroup'].'</div>';
        $html .= '<div style="position:absolute;top:445px;left:475px; width:100%">'.$recruit['recruit_homeRoad'].'</div>';
        $html .= '<div style="position:absolute;top:445px;left:615px; width:100%">'.$recruit['recruit_homeSubdistrict'].'</div>';
        $html .= '<div style="position:absolute;top:475px;left:180px; width:100%">'.$recruit['recruit_homedistrict'].'</div>';
        $html .= '<div style="position:absolute;top:475px;left:400px; width:100%">'.$recruit['recruit_homeProvince'].'</div>';
        $html .= '<div style="position:absolute;top:475px;left:620px; width:100%">'.$recruit['recruit_homePostcode'].'</div>';
        $html .= '<div style="position:absolute;top:503px;left:695px; width:100%;font-size:22px;">'.$recruit['recruit_regLevel'].'</div>';
        
        $html .= '<div style="position:absolute;top:880px;left:340px; width:100%">'.$recruit['recruit_prefix'].$recruit['recruit_firstName'].' '.$recruit['recruit_lastName'].'</div>';
        $html .= '<div style="position:absolute;top:905px;left:350px; width:100%">'.$date_D_regis.' '.$TH_Month[$date_M_regis-1].' '.$date_Y_regis.'</div>';

        // Major Order / Course Selection
        if ($recruit['quota_key'] == "normal") {
            $SubCourse = explode('|', $recruit['recruit_majorOrder']);
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            foreach ($SubCourse as $key => $v_SubCourse) {
                $CheckCourse = $db->table('tb_course')->select('course_initials')->where('course_id', $v_SubCourse)->get()->getRow();
                if ($CheckCourse) {
                    $html .= "ลำดับที่ ".($key+1).' '.$CheckCourse->course_initials."<br>";
                }
            }
            $html .= '</div>';
        } else {
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            // Use course_name_joined if available, otherwise fallback to recruit_tpyeRoom
            $courseDisplay = !empty($recruit['course_name_joined']) ? $recruit['course_name_joined'] : $recruit['recruit_tpyeRoom'];
            $html .= "ลำดับที่ 1 ".$courseDisplay. ' สาขา '.$recruit['recruit_major'];
            $html .= '</div>';
        }

        // Documents Checkmarks
        if (!empty($recruit['recruit_certificateEdu'])) {
            $html .= '<div style="position:absolute;top:795px;left:110px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
        }
        if (!empty($recruit['recruit_copyidCard'])) {
            $html .= '<div style="position:absolute;top:795px;left:330x; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
        }
        if (!empty($recruit['recruit_img'])) {
            $html .= '<div style="position:absolute;top:795px;left:560px; width:100%"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>';
        }

        $mpdf->SetDocTemplate('uploads/recruitstudent/registerSKJ.pdf', true);
        $mpdf->WriteHTML($html);
        
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Reg_'.sprintf("%04d",$recruit['recruit_id']).'.pdf', 'I');
    }
}

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

        $data['rounds'] = $model->select('recruit_round')->distinct()->orderBy('recruit_round', 'ASC')->findColumn('recruit_round') ?? ['1'];
        if(empty($data['rounds'])) $data['rounds'] = ['1'];

        $data['years'] = $years;
        $data['selected_year'] = $selectedYear;
        $data['title'] = 'ข้อมูลผู้สมัคร';

        return view('Admin/PageAdminRecruit/PageAdminRecruitIndex', $data);
    }

    public function view($id = null)
    {
        $model = new AdmissionModel();
        $courseModel = new \App\Models\CourseModel();

        $data['recruit'] = $model->select('tb_recruitstudent.*, tb_course.course_fullname as course_name_joined, tb_personnel.pers_prefix as verifier_prefix, tb_personnel.pers_firstname as verifier_fname, tb_personnel.pers_lastname as verifier_lname')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->join('skjacth_personnel.tb_personnel', 'skjacth_personnel.tb_personnel.pers_id = tb_recruitstudent.recruit_userUpdate', 'left')
            ->find($id);

        if (empty($data['recruit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้สมัคร ID: ' . $id);
        }

        // Process recruit_majorOrder if it exists and is in the new format (pipe-separated IDs)
        if (!empty($data['recruit']['recruit_majorOrder'])) {
            $courseIds = explode('|', $data['recruit']['recruit_majorOrder']);
            $majorOrderList = [];

            // Fetch course full names for each ID
            if (!empty($courseIds)) {
                // Using whereIn for a single query to get all courses
                $courses = $courseModel->select('course_id, course_fullname')
                    ->whereIn('course_id', $courseIds)
                    ->findAll();

                // Map course_id to course_fullname for easy lookup
                $courseMap = [];
                foreach ($courses as $course) {
                    $courseMap[$course['course_id']] = $course['course_fullname'];
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
        $courseModel = new \App\Models\CourseModel();
        $data['recruit'] = $model->find($id);

        if (empty($data['recruit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้สมัคร ID: ' . $id);
        }

        // Process recruit_majorOrder to get IDs array
        $data['major_order_ids'] = [];
        if (!empty($data['recruit']['recruit_majorOrder'])) {
            $data['major_order_ids'] = explode('|', $data['recruit']['recruit_majorOrder']);
        }

        $data['courses'] = $model->getAllCourses();
        $data['quotas'] = $model->getAllQuotas();
        $data['courses_json'] = json_encode($data['courses']);
        $data['remote_base_url'] = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
        $data['title'] = 'แก้ไขข้อมูลผู้สมัคร';
        return view('Admin/PageAdminRecruit/PageAdminRecruitEdit', $data);
    }

    public function update($id = null)
    {
        $model = new AdmissionModel();
        $courseModel = new \App\Models\CourseModel();

        // Build major order from 3 dropdowns
        $majorOrder = [];
        $course1 = $this->request->getPost('recruit_tpyeRoom1');
        $course2 = $this->request->getPost('recruit_tpyeRoom2');
        $course3 = $this->request->getPost('recruit_tpyeRoom3');

        if (!empty($course1))
            $majorOrder[] = $course1;
        if (!empty($course2))
            $majorOrder[] = $course2;
        if (!empty($course3))
            $majorOrder[] = $course3;

        $majorOrderStr = implode('|', $majorOrder);

        // Get first course as primary for backward compatibility
        $courseId = $course1;
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
            'recruit_tpyeRoom' => $courseName,
            'recruit_tpyeRoom_id' => $courseId,
            'recruit_majorOrder' => $majorOrderStr,
            'recruit_status' => $this->request->getPost('recruit_status'),
            'recruit_homeNumber' => $this->request->getPost('recruit_homeNumber'),
            'recruit_homeGroup' => $this->request->getPost('recruit_homeGroup'),
            'recruit_homeRoad' => $this->request->getPost('recruit_homeRoad'),
            'recruit_homeSubdistrict' => $this->request->getPost('recruit_homeSubdistrict'),
            'recruit_homedistrict' => $this->request->getPost('recruit_homedistrict'),
            'recruit_homeProvince' => $this->request->getPost('recruit_homeProvince'),
            'recruit_homePostcode' => $this->request->getPost('recruit_homePostcode'),
            'recruit_race' => $this->request->getPost('recruit_race'),
            'recruit_nationality' => $this->request->getPost('recruit_nationality'),
            'recruit_religion' => $this->request->getPost('recruit_religion'),
            'recruit_district' => $this->request->getPost('recruit_district'),
            'recruit_province' => $this->request->getPost('recruit_province'),
            'recruit_major' => $this->request->getPost('recruit_major') ?: $course['course_branch'] ?? '',
            'recruit_address' => "เลขที่ " . $this->request->getPost('recruit_homeNumber') . " หมู่ที่ " . (!empty($this->request->getPost('recruit_homeGroup')) ? $this->request->getPost('recruit_homeGroup') : '-') . " ถนน " . (!empty($this->request->getPost('recruit_homeRoad')) ? $this->request->getPost('recruit_homeRoad') : '-') . " ตำบล" . $this->request->getPost('recruit_homeSubdistrict') . " อำเภอ" . $this->request->getPost('recruit_homedistrict') . " จังหวัด" . $this->request->getPost('recruit_homeProvince') . " " . $this->request->getPost('recruit_homePostcode'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s'),
            'recruit_userUpdate' => session()->get('pers_id'),
            'recruit_sportSelectionResult' => $this->request->getPost('recruit_sportSelectionResult'),
        ];

        $currentRecruit = $model->find($id);
        $regLevel = $currentRecruit['recruit_regLevel'];
        $remoteUpload = new \App\Libraries\RemoteUpload();

        // Handle file uploads
        $fileFields = [
            'recruit_img' => 'img',
            'recruit_certificateEdu' => 'certificate',
            'recruit_certificateEduB' => 'certificateB',
            'recruit_copyidCard' => 'copyidCard',
            'recruit_copyAddress' => 'copyAddress',
        ];

        foreach ($fileFields as $field => $folder) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $subPath = 'admission/recruitstudent/m' . $regLevel . '/' . $folder;
                $result = $remoteUpload->upload($file, $subPath);

                if ($result && $result['status'] === 'success') {
                    $data[$field] = $result['filename'];
                    // Delete old file if exists
                    if (!empty($currentRecruit[$field])) {
                        $remoteUpload->delete($currentRecruit[$field], $subPath);
                    }
                }
            }
        }

        // Handle ability certificate (multiple files)
        $abilityFiles = $this->request->getFileMultiple('recruit_certificateAbility');
        if (!empty($abilityFiles) && $abilityFiles[0]->isValid()) {
            $subPath = 'admission/recruitstudent/m' . $regLevel . '/certificateAbility';
            $newAbilityFiles = [];
            foreach ($abilityFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $result = $remoteUpload->upload($file, $subPath);
                    if ($result && $result['status'] === 'success') {
                        $newAbilityFiles[] = $result['filename'];
                    }
                }
            }
            if (!empty($newAbilityFiles)) {
                $data['recruit_certificateAbility'] = implode('|', $newAbilityFiles);
            }
        }

        $model->update($id, $data);

        return redirect()->to(site_url('skjadmin/recruits/view/' . $id))->with('success', 'อัปเดตข้อมูลผู้สมัครสำเร็จ');
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
        try {
            $id = $this->request->getVar('id');
            $status = $this->request->getVar('status');

            if ($id && $status) {
                $model = new AdmissionModel();
                $updated = $model->update($id, [
                    'recruit_status' => $status,
                    'recruit_userUpdate' => session()->get('pers_id'),
                    'recruit_dateUpdate' => date('Y-m-d H:i:s')
                ]);

                if ($updated) {
                    return $this->response->setJSON(['success' => true]);
                }
            }

            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Missing ID or Status',
                'debug' => [
                    'id' => $id,
                    'status' => $status,
                    'method' => $this->request->getMethod(),
                    'post' => $this->request->getPost(),
                    'get' => $this->request->getGet(),
                    'all_vars' => $this->request->getVar()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update sport selection result via AJAX
     */
    public function updateSportResult()
    {
        $request = service('request');
        $id = $request->getPost('id');
        $result = $request->getPost('result');

        $validResults = ['รอคัดเลือก', 'ผ่านการคัดเลือก', 'ไม่ผ่านการคัดเลือก', 'ไม่มาคัดเลือก'];

        if ($id && in_array($result, $validResults)) {
            $model = new AdmissionModel();
            $updated = $model->update($id, [
            'recruit_sportSelectionResult' => $result,
            'recruit_userUpdate' => session()->get('pers_id'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s')
        ]);

            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }

    /**
     * Update quiz/written exam result via AJAX
     */
    public function updateQuizResult()
    {
        $request = service('request');
        $id = $request->getPost('id');
        $result = $request->getPost('result');

        $validResults = ['รอสอบ', 'สอบผ่าน', 'สอบไม่ผ่าน', 'ไม่มาสอบ'];

        if ($id && in_array($result, $validResults)) {
            $model = new AdmissionModel();
            $updated = $model->update($id, [
            'recruit_StatusQuiz' => $result,
            'recruit_userUpdate' => session()->get('pers_id'),
            'recruit_dateUpdate' => date('Y-m-d H:i:s')
        ]);

            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
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
        $statusFilter = $request->getVar('status_filter') ?? '';
        $roundFilter = $request->getVar('round_filter') ?? '';

        // 1. Get Total Records (for this year)
        $totalRecords = $model->where('recruit_year', $year)->countAllResults();

        // 2. Count Filtered Records
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->where('tb_recruitstudent.recruit_year', $year);

        // Apply status filter
        if (!empty($statusFilter)) {
            if ($statusFilter === 'ไม่ผ่าน') {
                $builder->like('tb_recruitstudent.recruit_status', 'ไม่ผ่าน');
            } else {
                $builder->where('tb_recruitstudent.recruit_status', $statusFilter);
            }
        }
        if (!empty($roundFilter)) {
            $builder->where('tb_recruitstudent.recruit_round', $roundFilter);
        }

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('tb_recruitstudent.recruit_id', $searchValue)
                ->orLike('tb_recruitstudent.recruit_firstName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_lastName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_category', $searchValue)
                ->orLike('tb_quota.quota_key', $searchValue)
                ->orLike('tb_quota.quota_explain', $searchValue)
                ->orLike('tb_course.course_branch', $searchValue)
                ->orLike('tb_course.course_fullname', $searchValue)
                ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults(); // Consumes builder

        // 3. Fetch Data
        $builder = $model->builder();
        $builder->select('tb_recruitstudent.recruit_id, tb_recruitstudent.recruit_prefix, tb_recruitstudent.recruit_firstName, tb_recruitstudent.recruit_lastName, tb_recruitstudent.recruit_regLevel, tb_recruitstudent.recruit_img, tb_recruitstudent.recruit_round, tb_quota.quota_explain, tb_recruitstudent.recruit_category, tb_course.course_branch, tb_course.course_fullname, tb_recruitstudent.recruit_tpyeRoom, tb_recruitstudent.recruit_status, tb_recruitstudent.recruit_majorOrder, tb_quota.quota_key, tb_recruitstudent.recruit_sportPosition, tb_recruitstudent.recruit_sportSelectionResult, tb_recruitstudent.recruit_StatusQuiz, tb_recruitstudent.recruit_dateUpdate, skjacth_personnel.tb_personnel.pers_prefix as verifier_prefix, skjacth_personnel.tb_personnel.pers_firstname as verifier_fname, skjacth_personnel.tb_personnel.pers_lastname as verifier_lname')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->join('skjacth_personnel.tb_personnel', 'skjacth_personnel.tb_personnel.pers_id = tb_recruitstudent.recruit_userUpdate', 'left')
            ->where('tb_recruitstudent.recruit_year', $year);

        // Apply status filter
        if (!empty($statusFilter)) {
            if ($statusFilter === 'ไม่ผ่าน') {
                $builder->like('tb_recruitstudent.recruit_status', 'ไม่ผ่าน');
            } else {
                $builder->where('tb_recruitstudent.recruit_status', $statusFilter);
            }
        }
        if (!empty($roundFilter)) {
            $builder->where('tb_recruitstudent.recruit_round', $roundFilter);
        }

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('tb_recruitstudent.recruit_id', $searchValue)
                ->orLike('tb_recruitstudent.recruit_firstName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_lastName', $searchValue)
                ->orLike('tb_recruitstudent.recruit_category', $searchValue)
                ->orLike('tb_quota.quota_key', $searchValue)
                ->orLike('tb_quota.quota_explain', $searchValue)
                ->orLike('tb_course.course_branch', $searchValue)
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
            $status = $recruit['recruit_status'] ?? 'รอการตรวจสอบ';
            $statusClass = 'status-pending'; // Default: orange/warning
            if ($status === 'ผ่านการตรวจสอบ') {
                $statusClass = 'status-approved'; // Green
            } elseif ($status === 'รอการตรวจสอบ') {
                $statusClass = 'status-pending'; // Orange
            } elseif (strpos($status, 'ไม่ผ่าน') !== false) {
                $statusClass = 'status-rejected'; // Red
            }

            // Generate avatar with lazy loading
            $imgSrc = base_url('image-proxy?file=recruitstudent/m' . ($recruit['recruit_regLevel'] ?? '1') . '/img/' . ($recruit['recruit_img'] ?? 'default.png'));
            $defaultImg = base_url('sneat-assets/img/avatars/1.png');
            $avatar = '<img src="' . $imgSrc . '" class="recruit-avatar" alt="Avatar" loading="lazy" onerror="this.onerror=null;this.src=\'' . $defaultImg . '\';">';

            // Check if this is a sports applicant
            $isSport = (
                (!empty($recruit['recruit_sportPosition']) && $recruit['recruit_sportPosition'] !== '-') ||
                (isset($recruit['course_fullname']) && mb_strpos($recruit['course_fullname'], 'กีฬา') !== false) ||
                (isset($recruit['course_branch']) && mb_strpos($recruit['course_branch'], 'กีฬา') !== false) ||
                (isset($recruit['quota_key']) && $recruit['quota_key'] === 'sport')
            );

            // Generate action dropdown menu
            $printMenuItems = '';
            if ($isSport) {
                // Sport applicant: show both print options
                $printMenuItems = '
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-run me-2 text-purple"></i>พิมพ์ใบสมัครกีฬา</a></li>
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print-normal/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-printer me-2 text-info"></i>พิมพ์ใบสมัครธรรมดา</a></li>';
            } else {
                // Regular applicant: show only normal print
                $printMenuItems = '
                    <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/print/' . $recruit['recruit_id']) . '" target="_blank"><i class="bx bx-printer me-2 text-info"></i>พิมพ์ใบสมัคร</a></li>';
            }

            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-show me-1"></i>ดูรายละเอียด
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/view/' . $recruit['recruit_id']) . '"><i class="bx bx-show me-2 text-primary"></i>ดูข้อมูลทั้งหมด</a></li>
                        <li><a class="dropdown-item" href="' . site_url('skjadmin/recruits/edit/' . $recruit['recruit_id']) . '"><i class="bx bx-edit me-2 text-warning"></i>แก้ไขข้อมูล</a></li>
                        <li><hr class="dropdown-divider"></li>
                        ' . $printMenuItems . '
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDelete(' . $recruit['recruit_id'] . ')"><i class="bx bx-trash me-2"></i>ลบข้อมูล</a></li>
                    </ul>
                </div>';

            // Build course display with all ranks from recruit_majorOrder
            $courseHtml = '';
            if (!empty($recruit['recruit_majorOrder'])) {
                $courseIds = explode('|', $recruit['recruit_majorOrder']);
                if (count($courseIds) > 0) {
                    // Get course model and fetch all courses at once
                    $courseModel = new \App\Models\CourseModel();
                    $courses = $courseModel->select('course_id, course_branch')
                        ->whereIn('course_id', $courseIds)
                        ->findAll();

                    // Create a map for quick lookup
                    $courseMap = [];
                    foreach ($courses as $course) {
                        $courseMap[$course['course_id']] = $course['course_branch'];
                    }

                    // Build HTML with order numbers
                    $courseItems = [];
                    foreach ($courseIds as $index => $id) {
                        $orderNum = $index + 1;
                        $courseName = $courseMap[$id] ?? 'ไม่พบ';
                        $badgeColor = 'bg-label-info';
                        if ($orderNum == 1) {
                            $badgeColor = 'bg-label-primary';
                        } elseif ($orderNum == 2) {
                            $badgeColor = 'bg-label-success';
                        } elseif ($orderNum == 3) {
                            $badgeColor = 'bg-label-warning';
                        }
                        $courseItems[] = '<span class="badge ' . $badgeColor . ' me-1 mb-1" title="อันดับที่ ' . $orderNum . '">' . $orderNum . '. ' . esc($courseName) . '</span>';
                    }
                    $courseHtml = '<div class="d-flex flex-wrap gap-1">' . implode('', $courseItems) . '</div>';
                }
            }

            // Fallback if no majorOrder
            if (empty($courseHtml)) {
                $courseHtml = '<span class="badge bg-label-info">' . esc($recruit['course_branch'] ?? $recruit['course_fullname'] ?? $recruit['recruit_tpyeRoom']) . '</span>';
            }

            // Build selection result display - single column for both sport and non-sport
            // Shows sport selection for sport applicants, quiz result for non-sport
            $selectionResultHtml = '';
            if ($isSport) {
                // Sport applicant - show sport selection dropdown
                $sportResult = $recruit['recruit_sportSelectionResult'] ?? 'รอคัดเลือก';
                $selectionResultHtml = '
                    <div class="d-flex align-items-center gap-1 justify-content-center">
                        <span class="badge bg-label-purple rounded-pill" title="นักกีฬา"><i class="bx bx-run"></i></span>
                        <select class="form-select form-select-sm sport-result-select" data-id="' . $recruit['recruit_id'] . '" style="width: auto; font-size: 0.75rem; padding: 0.25rem 1.5rem 0.25rem 0.5rem;">
                            <option value="รอคัดเลือก"' . ($sportResult === 'รอคัดเลือก' || empty($sportResult) ? ' selected' : '') . '>⏳ รอคัดเลือก</option>
                            <option value="ผ่านการคัดเลือก"' . ($sportResult === 'ผ่านการคัดเลือก' ? ' selected' : '') . '>✅ ผ่านการคัดเลือก</option>
                            <option value="ไม่ผ่านการคัดเลือก"' . ($sportResult === 'ไม่ผ่านการคัดเลือก' ? ' selected' : '') . '>❌ ไม่ผ่านการคัดเลือก</option>
                            <option value="ไม่มาคัดเลือก"' . ($sportResult === 'ไม่มาคัดเลือก' ? ' selected' : '') . '>🚫 ไม่มาคัดเลือก</option>
                        </select>
                    </div>';
            } else {
                // Non-sport applicant - show quiz result dropdown
                $quizResult = $recruit['recruit_StatusQuiz'] ?? 'รอสอบ';
                $selectionResultHtml = '
                    <div class="d-flex align-items-center gap-1 justify-content-center">
                        <span class="badge bg-label-info rounded-pill" title="สอบข้อเขียน"><i class="bx bx-edit"></i></span>
                        <select class="form-select form-select-sm quiz-result-select" data-id="' . $recruit['recruit_id'] . '" style="width: auto; font-size: 0.75rem; padding: 0.25rem 1.5rem 0.25rem 0.5rem;">
                            <option value="รอสอบ"' . ($quizResult === 'รอสอบ' || empty($quizResult) ? ' selected' : '') . '>⏳ รอสอบ</option>
                            <option value="สอบผ่าน"' . ($quizResult === 'สอบผ่าน' ? ' selected' : '') . '>✅ สอบผ่าน</option>
                            <option value="สอบไม่ผ่าน"' . ($quizResult === 'สอบไม่ผ่าน' ? ' selected' : '') . '>❌ สอบไม่ผ่าน</option>
                            <option value="ไม่มาสอบ"' . ($quizResult === 'ไม่มาสอบ' ? ' selected' : '') . '>🚫 ไม่มาสอบ</option>
                        </select>
                    </div>';
            }

            $verifierName = '-';
            if (!empty($recruit['verifier_fname'])) {
                $verifierName = esc($recruit['verifier_prefix'] . $recruit['verifier_fname'] . ' ' . $recruit['verifier_lname']);
                if (!empty($recruit['recruit_dateUpdate'])) {
                    $verifierName .= '<div class="text-muted" style="font-size: 0.65rem; line-height: 1.2;">(' . date('d/m/Y H:i', strtotime($recruit['recruit_dateUpdate'])) . ')</div>';
                }
            }

            $data[] = [
                'avatar' => $avatar,
                'recruit_id' => '<span class="badge bg-label-secondary">' . esc(sprintf('%04d', $recruit['recruit_id'] ?? 0)) . '</span>',
                'name' => '<div class="fw-semibold">' . esc(($recruit['recruit_prefix'] ?? '') . ($recruit['recruit_firstName'] ?? '')) . '</div><small class="text-muted">' . esc($recruit['recruit_lastName'] ?? '') . '</small>',
                'round' => '<span class="badge bg-label-dark">รอบที่ ' . esc($recruit['recruit_round'] ?? '1') . '</span>',
                'category' => '<small>' . esc($recruit['quota_explain'] ?? $recruit['recruit_category']) . '</small>',
                'course' => $courseHtml,
                'selection_result' => $selectionResultHtml,
                'status' => '<span class="status-badge ' . $statusClass . '">' . esc($status) . '</span>',
                'verifier' => $verifierName,
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

    /**
     * Get statistics for the recruits
     */
    public function getStats()
    {
        $request = service('request');
        $year = $request->getVar('year') ?? date('Y');

        // Create fresh model instances for each count to avoid query builder condition accumulation
        $total = (new AdmissionModel())->where('recruit_year', $year)->countAllResults();

        $approved = (new AdmissionModel())
            ->where('recruit_year', $year)
            ->where('recruit_status', 'ผ่านการตรวจสอบ')
            ->countAllResults();

        $pending = (new AdmissionModel())
            ->where('recruit_year', $year)
            ->where('recruit_status', 'รอการตรวจสอบ')
            ->countAllResults();

        $rejected = (new AdmissionModel())
            ->where('recruit_year', $year)
            ->like('recruit_status', 'ไม่ผ่าน')
            ->countAllResults();

        return $this->response->setJSON([
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected
        ]);
    }

    public function print($id)
    {
        $db = \Config\Database::connect();
        $model = new AdmissionModel();
        $recruit = $model->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_quota.quota_key, tb_course.course_fullname as course_name_joined, tb_course.course_branch')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->find($id);

        if (!$recruit) {
            return "ไม่พบข้อมูลผู้สมัคร";
        }

        // Load mPDF using SHARED_LIB_PATH
        if (file_exists(SHARED_LIB_PATH . '/mpdf/vendor/autoload.php')) {
            require_once SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        } else {
            return "mPDF library not found at: " . SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        }

        $customFontDir = SHARED_LIB_PATH . '/vendor/mpdf/mpdf/ttfonts';
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
        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        $date_Y = date('Y', strtotime($recruit['recruit_birthday'])) + 543;
        $date_D = date('d', strtotime($recruit['recruit_birthday']));
        $date_M = date('n', strtotime($recruit['recruit_birthday']));

        $date_Y_regis = date('Y', strtotime($recruit['recruit_date'])) + 543;
        $date_D_regis = date('d', strtotime($recruit['recruit_date']));
        $date_M_regis = date('n', strtotime($recruit['recruit_date']));

        // Calculate Age
        $birthDate = new \DateTime($recruit['recruit_birthday']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $sch = explode("โรงเรียน", $recruit['recruit_oldSchool']);
        $oldSchool = ($sch[0] == '' && isset($sch[1])) ? $sch[1] : $sch[0];

        $mpdf->SetTitle($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);

        // Generate HTML
        $html = '';
        $baseUrl = getenv('upload.server.baseurl') ?: "https://skj.nsnpao.go.th/uploads/admission/";
        $imgUrl = base_url('image-proxy?file=recruitstudent/m' . $recruit['recruit_regLevel'] . '/img/' . $recruit['recruit_img']);

        // Check if this is a sports excellence applicant
        $isSport = (
            (!empty($recruit['recruit_sportPosition']) && $recruit['recruit_sportPosition'] !== '-') ||
            (isset($recruit['course_name_joined']) && mb_strpos($recruit['course_name_joined'], 'กีฬา') !== false) ||
            (isset($recruit['course_branch']) && mb_strpos($recruit['course_branch'], 'กีฬา') !== false) ||
            (isset($recruit['quota_key']) && $recruit['quota_key'] === 'sport')
        );

        if ($isSport) {
            // Layout for Sport Excellence (A4) - Synced with UserControlAdmission coordinates
            $mpdf->SetDocTemplate('uploads/recruitstudent/registerSKJ_sport.pdf', true);
            $mpdf->AddPage();

            // Image (173, 10, 30, 40)
            if (!empty($recruit['recruit_img'])) {
                $mpdf->Image($imgUrl, 173, 10, 30, 40);
            }

            // Top Content - Year
            $mpdf->SetXY(147, 42);
            $mpdf->WriteHTML($recruit['recruit_year']);

            // Name and Gender
            $mpdf->SetXY(40, 58);
            $mpdf->WriteHTML($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);

            $checkMarkPath = FCPATH . 'uploads/recruitstudent/Check-Mark1.png';
            if ($recruit['recruit_prefix'] === 'เด็กหญิง' || $recruit['recruit_prefix'] === 'นางสาว' || (isset($recruit['recruit_gender']) && $recruit['recruit_gender'] === 'หญิง')) {
                $mpdf->Image($checkMarkPath, 120, 58, 7, 7);
            } else {
                $mpdf->Image($checkMarkPath, 108, 58, 7, 7);
            }

            // Age and Nickname
            $mpdf->SetXY(146, 58);
            $mpdf->WriteHTML($age);
            $mpdf->SetXY(175, 58);
            $mpdf->WriteHTML($recruit['recruit_nickname'] ?? '');

            // Birth Date and Physicals
            $mpdf->SetXY(30, 66);
            $mpdf->WriteHTML($date_D);
            $mpdf->SetXY(60, 66);
            $mpdf->WriteHTML($TH_Month[$date_M - 1]);
            $mpdf->SetXY(98, 66);
            $mpdf->WriteHTML($date_Y);
            $mpdf->SetXY(127, 66);
            $mpdf->WriteHTML($recruit['recruit_height'] ?? '');
            $mpdf->SetXY(173, 66);
            $mpdf->WriteHTML($recruit['recruit_weight'] ?? '');

            // Parents
            $mpdf->SetXY(40, 74);
            $mpdf->WriteHTML($recruit['recruit_fatherName'] ?? '');
            $mpdf->SetXY(130, 74);
            $mpdf->WriteHTML($recruit['recruit_fatherJob'] ?? '');
            $mpdf->SetXY(40, 82);
            $mpdf->WriteHTML($recruit['recruit_motherName'] ?? '');
            $mpdf->SetXY(130, 82);
            $mpdf->WriteHTML($recruit['recruit_motherJob'] ?? '');

            // Address
            $mpdf->SetXY(65, 90);
            $mpdf->WriteHTML($recruit['recruit_homeNumber']);
            $mpdf->SetXY(91, 90);
            $mpdf->WriteHTML($recruit['recruit_homeGroup'] ?? '-');
            $mpdf->SetXY(112, 90);
            $mpdf->WriteHTML($recruit['recruit_homeRoad'] ?? '-');
            $mpdf->SetXY(142, 90);
            $mpdf->WriteHTML($recruit['recruit_homeSubdistrict']);
            $mpdf->SetXY(30, 98);
            $mpdf->WriteHTML($recruit['recruit_homedistrict']);
            $mpdf->SetXY(75, 98);
            $mpdf->WriteHTML($recruit['recruit_homeProvince']);
            $mpdf->SetXY(123, 98);
            $mpdf->WriteHTML($recruit['recruit_homePostcode']);
            $mpdf->SetXY(165, 98);
            $mpdf->WriteHTML($recruit['recruit_phone']);

            // Education
            $mpdf->SetXY(123, 107);
            $mpdf->WriteHTML($date_Y_regis);
            $mpdf->SetXY(45, 115);
            $mpdf->WriteHTML($recruit['recruit_regLevel'] == 1 ? 'ป.6' : 'ม.3');
            $mpdf->SetXY(107, 115);
            $mpdf->WriteHTML($oldSchool);

            // Choice
            $mpdf->SetXY(50, 123);
            $mpdf->WriteHTML('มัธยมศึกษาปีที่ ' . $recruit['recruit_regLevel']);
            $mpdf->SetXY(109, 123);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');
            $mpdf->SetXY(162, 123);
            $mpdf->WriteHTML($recruit['recruit_sportPosition'] ?? '');

            // Signature
            $mpdf->SetXY(140, 141);
            $mpdf->WriteHTML($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);

            // Confirmation Part (Bottom)
            $mpdf->SetXY(140, 188);
            $mpdf->WriteHTML($recruit['recruit_year']);
            $mpdf->SetXY(35, 196);
            $mpdf->WriteHTML($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName']);
            $mpdf->SetXY(118, 196);
            $mpdf->WriteHTML('มัธยมศึกษาปีที่ ' . $recruit['recruit_regLevel']);
            $mpdf->SetXY(167, 196);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');

            // Top Left & Bottom Right Tags
            $mpdf->SetXY(15, 18);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');
            $mpdf->SetXY(15, 26);
            $mpdf->WriteHTML($recruit['recruit_sportPosition'] ?? '');
            $mpdf->SetXY(164, 173);
            $mpdf->WriteHTML($recruit['course_branch'] ?? '');
            $mpdf->SetXY(164, 183);
            $mpdf->WriteHTML($recruit['recruit_sportPosition'] ?? '');

        } else {
            // Layout for Regular Application (registerSKJ.pdf)
            if (!empty($recruit['recruit_img'])) {
                $html .= '<div style="position:absolute;top:110px;left:680px; width:100%"><img style="width: 113.38px;height:151.18px;" src="' . $imgUrl . '"></div>';
            }

            $html .= '<div style="position:absolute;top:18px;left:100px; width:100%;font-size:16px;">' . $recruit['quota_explain'] . '</div>';
            $html .= '<div style="position:absolute;top:180px;left:555px; width:100%;font-size:24px;">' . $recruit['recruit_regLevel'] . '</div>';
            $html .= '<div style="position:absolute;top:63px;left:700px; width:100%">' . sprintf("%04d", $recruit['recruit_id']) . '</div>';
            $html .= '<div style="position:absolute;top:280px;left:180px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . '</div>';
            $html .= '<div style="position:absolute;top:280px;left:470px; width:100%">' . $recruit['recruit_lastName'] . '</div>';
            $html .= '<div style="position:absolute;top:307px;left:270px; width:100%">' . $oldSchool . '</div>';
            $html .= '<div style="position:absolute;top:335px;left:170px; width:100%">' . $recruit['recruit_district'] . '</div>';
            $html .= '<div style="position:absolute;top:335px;left:510px; width:100%">' . $recruit['recruit_province'] . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:160px; width:100%">' . $date_D . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:240px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:370px; width:100%">' . $date_Y . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:470px; width:100%">' . $age . '</div>';
            $html .= '<div style="position:absolute;top:363px;left:600px; width:100%">' . $recruit['recruit_race'] . '</div>';
            $html .= '<div style="position:absolute;top:390px;left:162px; width:100%">' . $recruit['recruit_nationality'] . '</div>';
            $html .= '<div style="position:absolute;top:390px;left:300px; width:100%">' . $recruit['recruit_religion'] . '</div>';
            $html .= '<div style="position:absolute;top:390px;left:540px; width:100%">' . $recruit['recruit_idCard'] . '</div>';
            $html .= '<div style="position:absolute;top:418px;left:350px; width:100%">' . $recruit['recruit_phone'] . '</div>';
            $html .= '<div style="position:absolute;top:418px;left:600px; width:100%">' . $recruit['recruit_grade'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:270px; width:100%">' . $recruit['recruit_homeNumber'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:390px; width:100%">' . $recruit['recruit_homeGroup'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:475px; width:100%">' . $recruit['recruit_homeRoad'] . '</div>';
            $html .= '<div style="position:absolute;top:445px;left:615px; width:100%">' . $recruit['recruit_homeSubdistrict'] . '</div>';
            $html .= '<div style="position:absolute;top:475px;left:180px; width:100%">' . $recruit['recruit_homedistrict'] . '</div>';
            $html .= '<div style="position:absolute;top:475px;left:400px; width:100%">' . $recruit['recruit_homeProvince'] . '</div>';
            $html .= '<div style="position:absolute;top:475px;left:620px; width:100%">' . $recruit['recruit_homePostcode'] . '</div>';
            $html .= '<div style="position:absolute;top:503px;left:695px; width:100%;font-size:22px;">' . $recruit['recruit_regLevel'] . '</div>';

            $html .= '<div style="position:absolute;top:880px;left:340px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName'] . '</div>';
            $html .= '<div style="position:absolute;top:905px;left:350px; width:100%">' . $date_D_regis . ' ' . $TH_Month[$date_M_regis - 1] . ' ' . $date_Y_regis . '</div>';

            // Major Order / Course Selection
            if ($recruit['quota_key'] == "normal") {
                $SubCourse = explode('|', $recruit['recruit_majorOrder']);
                $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
                foreach ($SubCourse as $key => $v_SubCourse) {
                    $CheckCourse = $db->table('tb_course')->select('course_initials')->where('course_id', $v_SubCourse)->get()->getRow();
                    if ($CheckCourse) {
                        $html .= "ลำดับที่ " . ($key + 1) . ' ' . $CheckCourse->course_initials . "<br>";
                    }
                }
                $html .= '</div>';
            } else {
                $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
                // Use course_name_joined if available, otherwise fallback to recruit_tpyeRoom
                $courseDisplay = !empty($recruit['course_name_joined']) ? $recruit['course_name_joined'] : $recruit['recruit_tpyeRoom'];
                $html .= "ลำดับที่ 1 " . $courseDisplay . ' สาขา ' . $recruit['recruit_major'];
                $html .= '</div>';
            }

            // Documents Checkmarks (using dejavusans font which is built-in to mPDF)
            $checkEmoji = '<span style="font-family: dejavusans; font-size: 30px; line-height: 1;">✔</span>';
            if (!empty($recruit['recruit_certificateEdu'])) {
                $html .= '<div style="position:absolute;top:785px;left:110px; width:100%;">' . $checkEmoji . '</div>';
            }
            if (!empty($recruit['recruit_copyidCard'])) {
                $html .= '<div style="position:absolute;top:785px;left:328px; width:100%;">' . $checkEmoji . '</div>';
            }
            if (!empty($recruit['recruit_img'])) {
                $html .= '<div style="position:absolute;top:788px;left:560px; width:100%;">' . $checkEmoji . '</div>';
            }

            $mpdf->SetDocTemplate('uploads/recruitstudent/registerSKJ.pdf', true);
        }

        $mpdf->WriteHTML($html);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Reg_' . sprintf("%04d", $recruit['recruit_id']) . '.pdf', 'I');
    }

    /**
     * Print Normal Application Form (always use regular template, not sport)
     * Used for sport applicants who passed selection and need regular enrollment form
     */
    public function printNormal($id)
    {
        $db = \Config\Database::connect();
        $model = new AdmissionModel();
        $recruit = $model->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_quota.quota_key, tb_course.course_fullname as course_name_joined, tb_course.course_branch')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->find($id);

        if (!$recruit) {
            return "ไม่พบข้อมูลผู้สมัคร";
        }

        // Load mPDF using SHARED_LIB_PATH
        if (file_exists(SHARED_LIB_PATH . '/mpdf/vendor/autoload.php')) {
            require_once SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        } else {
            return "mPDF library not found at: " . SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        }

        $customFontDir = SHARED_LIB_PATH . '/vendor/mpdf/mpdf/ttfonts';
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
        $TH_Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        $date_Y = date('Y', strtotime($recruit['recruit_birthday'])) + 543;
        $date_D = date('d', strtotime($recruit['recruit_birthday']));
        $date_M = date('n', strtotime($recruit['recruit_birthday']));

        $date_Y_regis = date('Y', strtotime($recruit['recruit_date'])) + 543;
        $date_D_regis = date('d', strtotime($recruit['recruit_date']));
        $date_M_regis = date('n', strtotime($recruit['recruit_date']));

        // Calculate Age
        $birthDate = new \DateTime($recruit['recruit_birthday']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $sch = explode("โรงเรียน", $recruit['recruit_oldSchool']);
        $oldSchool = ($sch[0] == '' && isset($sch[1])) ? $sch[1] : $sch[0];

        $mpdf->SetTitle($recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName'] . ' (ใบสมัครธรรมดา)');

        // Generate HTML - Always use Regular Application Layout
        $html = '';
        $imgUrl = base_url('image-proxy?file=recruitstudent/m' . $recruit['recruit_regLevel'] . '/img/' . $recruit['recruit_img']);

        if (!empty($recruit['recruit_img'])) {
            $html .= '<div style="position:absolute;top:110px;left:680px; width:100%"><img style="width: 113.38px;height:151.18px;" src="' . $imgUrl . '"></div>';
        }

        $html .= '<div style="position:absolute;top:18px;left:100px; width:100%;font-size:16px;">' . $recruit['quota_explain'] . '</div>';
        $html .= '<div style="position:absolute;top:180px;left:555px; width:100%;font-size:24px;">' . $recruit['recruit_regLevel'] . '</div>';
        $html .= '<div style="position:absolute;top:63px;left:700px; width:100%">' . sprintf("%04d", $recruit['recruit_id']) . '</div>';
        $html .= '<div style="position:absolute;top:280px;left:180px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . '</div>';
        $html .= '<div style="position:absolute;top:280px;left:470px; width:100%">' . $recruit['recruit_lastName'] . '</div>';
        $html .= '<div style="position:absolute;top:307px;left:270px; width:100%">' . $oldSchool . '</div>';
        $html .= '<div style="position:absolute;top:335px;left:170px; width:100%">' . $recruit['recruit_district'] . '</div>';
        $html .= '<div style="position:absolute;top:335px;left:510px; width:100%">' . $recruit['recruit_province'] . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:160px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:240px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:370px; width:100%">' . $date_Y . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:470px; width:100%">' . $age . '</div>';
        $html .= '<div style="position:absolute;top:363px;left:600px; width:100%">' . $recruit['recruit_race'] . '</div>';
        $html .= '<div style="position:absolute;top:390px;left:162px; width:100%">' . $recruit['recruit_nationality'] . '</div>';
        $html .= '<div style="position:absolute;top:390px;left:300px; width:100%">' . $recruit['recruit_religion'] . '</div>';
        $html .= '<div style="position:absolute;top:390px;left:540px; width:100%">' . $recruit['recruit_idCard'] . '</div>';
        $html .= '<div style="position:absolute;top:418px;left:350px; width:100%">' . $recruit['recruit_phone'] . '</div>';
        $html .= '<div style="position:absolute;top:418px;left:600px; width:100%">' . $recruit['recruit_grade'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:270px; width:100%">' . $recruit['recruit_homeNumber'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:390px; width:100%">' . $recruit['recruit_homeGroup'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:475px; width:100%">' . $recruit['recruit_homeRoad'] . '</div>';
        $html .= '<div style="position:absolute;top:445px;left:615px; width:100%">' . $recruit['recruit_homeSubdistrict'] . '</div>';
        $html .= '<div style="position:absolute;top:475px;left:180px; width:100%">' . $recruit['recruit_homedistrict'] . '</div>';
        $html .= '<div style="position:absolute;top:475px;left:400px; width:100%">' . $recruit['recruit_homeProvince'] . '</div>';
        $html .= '<div style="position:absolute;top:475px;left:620px; width:100%">' . $recruit['recruit_homePostcode'] . '</div>';
        $html .= '<div style="position:absolute;top:503px;left:695px; width:100%;font-size:22px;">' . $recruit['recruit_regLevel'] . '</div>';

        $html .= '<div style="position:absolute;top:880px;left:340px; width:100%">' . $recruit['recruit_prefix'] . $recruit['recruit_firstName'] . ' ' . $recruit['recruit_lastName'] . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:350px; width:100%">' . $date_D_regis . ' ' . $TH_Month[$date_M_regis - 1] . ' ' . $date_Y_regis . '</div>';

        // Major Order / Course Selection - For sport applicants, show their selected course
        if ($recruit['quota_key'] == "normal") {
            $SubCourse = explode('|', $recruit['recruit_majorOrder']);
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            foreach ($SubCourse as $key => $v_SubCourse) {
                $CheckCourse = $db->table('tb_course')->select('course_initials')->where('course_id', $v_SubCourse)->get()->getRow();
                if ($CheckCourse) {
                    $html .= "ลำดับที่ " . ($key + 1) . ' ' . $CheckCourse->course_initials . "<br>";
                }
            }
            $html .= '</div>';
        } else {
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            // Use course_name_joined if available, otherwise fallback to recruit_tpyeRoom
            $courseDisplay = !empty($recruit['course_name_joined']) ? $recruit['course_name_joined'] : $recruit['recruit_tpyeRoom'];
            $html .= "ลำดับที่ 1 " . $courseDisplay . ' สาขา ' . $recruit['recruit_major'];
            $html .= '</div>';
        }


        // Documents Checkmarks
        $checkEmoji = '<span style="font-family: dejavusans; font-size: 30px; line-height: 1;">✔</span>';
        if (!empty($recruit['recruit_certificateEdu'])) {
            $html .= '<div style="position:absolute;top:785px;left:110px; width:100%;">' . $checkEmoji . '</div>';
        }
        if (!empty($recruit['recruit_copyidCard'])) {
            $html .= '<div style="position:absolute;top:785px;left:328px; width:100%;">' . $checkEmoji . '</div>';
        }
        if (!empty($recruit['recruit_img'])) {
            $html .= '<div style="position:absolute;top:788px;left:560px; width:100%;">' . $checkEmoji . '</div>';
        }

        $mpdf->SetDocTemplate('uploads/recruitstudent/registerSKJ.pdf', true);
        $mpdf->WriteHTML($html);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Reg_Normal_' . sprintf("%04d", $recruit['recruit_id']) . '.pdf', 'I');
    }
}

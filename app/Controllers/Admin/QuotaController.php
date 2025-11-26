<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\QuotaModel;

class QuotaController extends BaseController
{
    public function index()
    {
        $model = new QuotaModel();
        $courseModel = new \App\Models\CourseModel();

        $quotas = $model->findAll();
        $courses = $courseModel->findAll();
        
        $courseMap = [];
        foreach ($courses as $c) {
            $courseMap[$c['course_id']] = [
                'name' => $c['course_fullname'],
                'level' => $c['course_gradelevel']
            ];
        }

        foreach ($quotas as &$q) {
            if (!empty($q['quota_course'])) {
                $ids = explode('|', $q['quota_course']);
                $m1Courses = [];
                $m4Courses = [];

                foreach ($ids as $id) {
                    if (isset($courseMap[$id])) {
                        $course = $courseMap[$id];
                        // Check level to categorize
                        // Assuming '1' or 'ต้น' indicates M.1/Junior, others M.4/Senior
                        if (strpos($course['level'], '1') !== false || strpos($course['level'], 'ต้น') !== false) {
                            $m1Courses[] = $course;
                        } else {
                            $m4Courses[] = $course;
                        }
                    }
                }
                
                $html = '';
                
                if (!empty($m1Courses)) {
                    $html .= '<h6 class="fw-bold text-primary mt-2"><i class="bx bx-user me-1"></i> ระดับชั้น ม.ต้น</h6>';
                    $html .= '<div class="table-responsive mb-3"><table class="table table-bordered table-sm table-striped"><thead><tr class="table-primary"><th>หลักสูตร</th></tr></thead><tbody>';
                    foreach ($m1Courses as $c) {
                        $html .= '<tr><td>' . $c['name'] . '</td></tr>';
                    }
                    $html .= '</tbody></table></div>';
                }

                if (!empty($m4Courses)) {
                    $html .= '<h6 class="fw-bold text-warning mt-2"><i class="bx bx-user-plus me-1"></i> ระดับชั้น ม.ปลาย</h6>';
                    $html .= '<div class="table-responsive"><table class="table table-bordered table-sm table-striped"><thead><tr class="table-warning"><th>หลักสูตร</th></tr></thead><tbody>';
                    foreach ($m4Courses as $c) {
                        $html .= '<tr><td>' . $c['name'] . '</td></tr>';
                    }
                    $html .= '</tbody></table></div>';
                }

                if (empty($html)) {
                    $html = '<div class="text-center text-muted my-3">ไม่พบข้อมูลหลักสูตร</div>';
                }

                $q['course_list_html'] = $html;
            } else {
                $q['course_list_html'] = '<div class="text-center text-muted my-3">ไม่มีหลักสูตรที่ระบุ</div>';
            }
        }

        $data['quotas'] = $quotas;
        return view('Admin/PageAdminQuota/PageAdminQuotaIndex', $data);
    }

    public function add()
    {
        return view('Admin/PageAdminQuota/PageAdminQuotaAdd');
    }

    public function create()
    {
        $model = new QuotaModel();
        $data = [
            'quota_key' => $this->request->getPost('quota_key'),
            'quota_level' => $this->request->getPost('quota_level'),
            'quota_explain' => $this->request->getPost('quota_explain'),
            'quota_status' => $this->request->getPost('quota_status'),
            'quota_course' => $this->request->getPost('quota_course'),
        ];
        $model->insert($data);
        return redirect()->to(site_url('skjadmin/quotas'))->with('success', 'เพิ่มโควต้าใหม่สำเร็จ');
    }

    public function edit($id = null)
    {
        $model = new QuotaModel();
        $courseModel = new \App\Models\CourseModel();
        
        $data['quota'] = $model->find($id);
        $data['courses'] = $courseModel->findAll();

        if (empty($data['quota'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลโควต้าสำหรับ ID: ' . $id);
        }
        return view('Admin/PageAdminQuota/PageAdminQuotaEdit', $data);
    }

    public function update($id = null)
    {
        $model = new QuotaModel();
        
        $quotaCourse = $this->request->getPost('quota_course');
        // Handle array from checkboxes
        if (is_array($quotaCourse)) {
            $quotaCourse = implode('|', $quotaCourse);
        }

        $quotaLevel = $this->request->getPost('quota_level');
        // Handle array from checkboxes for level
        if (is_array($quotaLevel)) {
            $quotaLevel = implode('|', $quotaLevel);
        }

        $data = [
            'quota_key' => $this->request->getPost('quota_key'),
            'quota_level' => $quotaLevel,
            'quota_explain' => $this->request->getPost('quota_explain'),
            'quota_status' => $this->request->getPost('quota_status'),
            'quota_course' => $quotaCourse,
        ];
        $model->update($id, $data);
        return redirect()->to(site_url('skjadmin/quotas'))->with('success', 'อัปเดตโควต้าสำเร็จ');
    }

    public function delete($id = null)
    {
        $model = new QuotaModel();
        $model->delete($id);
        return redirect()->to(site_url('skjadmin/quotas'))->with('success', 'ลบโควต้าสำเร็จ');
    }

    public function updateStatus()
    {
        $request = \Config\Services::request();
        if ($request->isAJAX()) {
            $id = $request->getPost('id');
            $status = $request->getPost('status');

            $model = new QuotaModel();
            $updated = $model->update($id, ['quota_status' => $status]);

            if ($updated) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Update failed']);
            }
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
}

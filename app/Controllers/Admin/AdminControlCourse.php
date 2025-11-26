<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class AdminControlCourse extends BaseController
{
    public function index()
    {
        $model = new CourseModel();
        $data['courses'] = $model->findAll();
        $data['title'] = 'การจัดการหลักสูตร';
        return view('Admin/PageAdminCourse/PageAdminCourseIndex', $data);
    }

    public function add()
    {
        $data['title'] = 'เพิ่มหลักสูตร';
        return view('Admin/PageAdminCourse/PageAdminCourseAdd', $data);
    }

    public function create()
    {
        $model = new CourseModel();
        $data = [
            'course_fullname' => $this->request->getPost('course_fullname'),
            'course_initials' => $this->request->getPost('course_initials'),
            'course_branch' => $this->request->getPost('course_branch'),
            'course_gradelevel' => $this->request->getPost('course_gradelevel'),
        ];
        $model->insert($data);
        return redirect()->to(site_url('skjadmin/courses'))->with('success', 'เพิ่มหลักสูตรใหม่สำเร็จ');
    }

    public function edit($id = null)
    {
        $model = new CourseModel();
        $data['course'] = $model->find($id);

        if (empty($data['course'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลหลักสูตรสำหรับ ID: ' . $id);
        }
        $data['title'] = 'แก้ไขหลักสูตร';
        return view('Admin/PageAdminCourse/PageAdminCourseEdit', $data);
    }

    public function update($id = null)
    {
        $model = new CourseModel();
        $data = [
            'course_fullname' => $this->request->getPost('course_fullname'),
            'course_initials' => $this->request->getPost('course_initials'),
            'course_branch' => $this->request->getPost('course_branch'),
            'course_gradelevel' => $this->request->getPost('course_gradelevel'),
        ];
        $model->update($id, $data);
        return redirect()->to(site_url('skjadmin/courses'))->with('success', 'อัปเดตหลักสูตรสำเร็จ');
    }

    public function delete($id = null)
    {
        $model = new CourseModel();
        $model->delete($id);
        return redirect()->to(site_url('skjadmin/courses'))->with('success', 'ลบหลักสูตรสำเร็จ');
    }
}

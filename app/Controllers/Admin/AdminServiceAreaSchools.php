<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdmissionModel;

class AdminServiceAreaSchools extends BaseController
{
    protected $admissionModel;
    protected $db;

    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
        $this->db = \Config\Database::connect();
        helper(['url', 'form']);
    }

    public function index()
    {
        $data['title'] = "จัดการโรงเรียนในเขตพื้นที่บริการ";
        $data['schools'] = $this->db->table('tb_service_area_schools')->get()->getResult();
        return view('Admin/PageAdminServiceAreaSchools/index', $data);
    }

    public function add()
    {
        $schoolName = $this->request->getPost('school_name');
        $schoolAmphur = $this->request->getPost('school_amphur');
        $schoolProvince = $this->request->getPost('school_province');

        if ($schoolName) {
            // Check duplicate
            $exists = $this->db->table('tb_service_area_schools')
                ->where('school_name', $schoolName)
                ->countAllResults();

            if ($exists == 0) {
                $this->db->table('tb_service_area_schools')->insert([
                    'school_name' => $schoolName,
                    'school_amphur' => $schoolAmphur,
                    'school_province' => $schoolProvince,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return $this->response->setJSON(['status' => 'success', 'message' => 'เพิ่มข้อมูลสำเร็จ']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'มีรายชื่อโรงเรียนนี้อยู่แล้ว']);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
    }

    public function delete($id)
    {
        $this->db->table('tb_service_area_schools')->where('id', $id)->delete();
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
    }

    public function search_all_schools()
    {
        $term = $this->request->getVar('q');
        return $this->response->setJSON($this->admissionModel->getSchool(['search' => $term]));
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UserController extends BaseController
{
    protected $db;
    protected $db_academic;
    protected $db_personnel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->db_academic = \Config\Database::connect('academic');
        $this->db_personnel = \Config\Database::connect('skjpers');
    }

    public function index()
    {
        $data['title'] = 'จัดการผู้ใช้งาน';
        
        // Fetch Admins with Personnel Info
        $builder = $this->db_academic->table('tb_admin_rloes');
        $builder->select('tb_admin_rloes.*, tb_personnel.pers_prefix, tb_personnel.pers_firstname, tb_personnel.pers_lastname, tb_personnel.pers_img, tb_personnel.pers_username');
        // Join across databases requires specifying database name if on same server, 
        // but CI4 Query Builder with multiple connections is tricky for joins.
        // Best to fetch admins first, then fetch personnel info, or use raw SQL if DBs are on same server.
        // Assuming same server:
        $builder->join('skjacth_personnel.tb_personnel', 'skjacth_personnel.tb_personnel.pers_id = skjacth_academic.tb_admin_rloes.admin_rloes_userid');
        $data['users'] = $builder->get()->getResult();

        return view('Admin/PageAdminUser/PageAdminUserIndex', $data);
    }

    public function search_personnel()
    {
        $term = $this->request->getVar('term');
        
        $builder = $this->db_personnel->table('tb_personnel');
        $builder->like('pers_firstname', $term);
        $builder->orLike('pers_lastname', $term);
        $builder->limit(10);
        $results = $builder->get()->getResult();
        
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'id' => $row->pers_id,
                'text' => $row->pers_prefix . $row->pers_firstname . ' ' . $row->pers_lastname . ' (' . $row->pers_username . ')'
            ];
        }
        
        return $this->response->setJSON($data);
    }

    public function create()
    {
        $userId = $this->request->getPost('user_id');
        $role = $this->request->getPost('role'); // e.g., 'Super Admin', 'Staff'
        
        // Check if already exists
        $exists = $this->db_academic->table('tb_admin_rloes')->where('admin_rloes_userid', $userId)->countAllResults();
        
        if ($exists > 0) {
            return $this->response->setJSON(['success' => false, 'msg' => 'ผู้ใช้งานนี้มีสิทธิ์อยู่แล้ว']);
        }

        $data = [
            'admin_rloes_userid' => $userId,
            'admin_rloes_status' => $role,
            'admin_rloes_nanetype' => 'Admin', // Default type
            'admin_rloes_academic_position' => ''
        ];
        
        $this->db_academic->table('tb_admin_rloes')->insert($data);
        
        return $this->response->setJSON(['success' => true, 'msg' => 'เพิ่มผู้ใช้งานเรียบร้อยแล้ว']);
    }

    public function delete($id)
    {
        $this->db_academic->table('tb_admin_rloes')->where('admin_rloes_id', $id)->delete();
        return redirect()->to(base_url('skjadmin/users'))->with('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
    }
}

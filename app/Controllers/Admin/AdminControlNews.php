<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlNews extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['url']);
    }

    private function checkAuth()
    {
        if (!$this->session->has('login_id')) {
            return redirect()->to('loginAdmin');
        }
        return null;
    }

    public function all()
    {
        $data['switch'] = $this->db->table("tb_onoffsys")->get()->getResult();
        $data['year'] = $this->db->table('tb_recruitstudent')->select('recruit_year')->groupBy('recruit_year')->orderBy('recruit_year', 'DESC')->get()->getResult();
        $data['checkYear'] = $this->db->table('tb_openyear')->get()->getResult();
        return $data;
    }

    public function index()
    {
        if ($redir = $this->checkAuth()) return $redir;

        $data = $this->all();
        $data['title'] = "ประชาสัมพันธ์";

        return view('admin/admin_news/admin_admission_news', $data);
    }

    public function add()
    {
        if ($redir = $this->checkAuth()) return $redir;

        $data = $this->all();
        $data['title'] = "เพิ่มประชาสัมพันธ์";

        return view('admin/admin_news/admin_admission_add', $data);
    }
}

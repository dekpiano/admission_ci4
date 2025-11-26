<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlSetting extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data['title'] = 'ตั้งค่าระบบ';
        $data['menu'] = 'settings';
        
        // Fetch System Status
        $data['settings'] = $this->db->table('tb_onoffsys')->where('onoff_id', 1)->get()->getRow();
        
        // Fetch Current Year
        $data['yearConfig'] = $this->db->table('tb_openyear')->where('openyear_id', 1)->get()->getRow();
        
        // Fetch Available Years (from recruit student data)
        $data['years'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_year')
            ->groupBy('recruit_year')
            ->orderBy('recruit_year', 'DESC')
            ->get()->getResult();

        return view('Admin/PageAdminSetting/PageAdminSettingIndex', $data);
    }

    public function update_status()
    {
        $field = $this->request->getPost('field'); // e.g., 'onoff_regis', 'onoff_report'
        $mode = $this->request->getPost('mode'); // 'true' or 'false'
        
        $status = ($mode === 'true') ? 'on' : 'off';
        
        $updateData = [$field => $status];
        
        // Add timestamp/user log if needed based on field
        if ($field == 'onoff_regis') {
            $updateData['onoff_datetime_regis_close'] = date('Y-m-d H:i:s');
            $updateData['onoff_user_regis'] = session()->get('pers_id');
        } elseif ($field == 'onoff_system') {
            $updateData['onoff_datetime_system'] = date('Y-m-d H:i:s');
            $updateData['onoff_user_system'] = session()->get('pers_id');
        } elseif ($field == 'onoff_report') {
            $updateData['onoff_user_report'] = session()->get('pers_id');
        }

        $this->db->table('tb_onoffsys')->where('onoff_id', 1)->update($updateData);
        
        return $this->response->setJSON(['success' => true, 'status' => $status]);
    }

    public function update_year()
    {
        $year = $this->request->getPost('year');
        
        $this->db->table('tb_openyear')->where('openyear_id', 1)->update([
            'openyear_year' => $year,
            'openyear_userid' => session()->get('pers_id')
        ]);
        
        return $this->response->setJSON(['success' => true, 'msg' => 'เปลี่ยนปีการศึกษาเรียบร้อยแล้ว']);
    }

    public function update_comment()
    {
        $comment = $this->request->getPost('comment');
        
        $this->db->table('tb_onoffsys')->where('onoff_id', 1)->update([
            'onoff_comment' => $comment
        ]);
        
        return $this->response->setJSON(['success' => true, 'msg' => 'บันทึกข้อความเรียบร้อยแล้ว']);
    }
}

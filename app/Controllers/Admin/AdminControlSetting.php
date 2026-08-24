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

        $this->ensureColumnExists();

        // Fetch System Status
        $data['settings'] = $this->db->table('tb_onoffsys')->where('onoff_id', 1)->get()->getRow();

        // Fetch Current Year
        $data['yearConfig'] = $this->db->table('tb_openyear')->where('openyear_id', 1)->get()->getRow();

        // Fetch Available Years (from recruit student data)
        try {
            $data['years'] = $this->db->table('tb_recruitstudent')
                ->select('recruit_year')
                ->groupBy('recruit_year')
                ->orderBy('recruit_year', 'DESC')
                ->get()->getResult();
        } catch (\Throwable $e) {
            $data['years'] = [];
        }

        return view('Admin/PageAdminSetting/PageAdminSettingIndex', $data);
    }

    public function update_status()
    {
        try {
            $field = $this->request->getPost('field');
            $mode = $this->request->getPost('mode');

            $status = ($mode === 'true') ? 'on' : 'off';
            $updateData = [$field => $status];

            if ($field == 'onoff_regis') {
                $updateData['onoff_user_regis'] = session()->get('pers_id');
            } elseif ($field == 'onoff_system') {
                $updateData['onoff_datetime_system'] = date('Y-m-d H:i:s');
                $updateData['onoff_user_system'] = session()->get('pers_id');
            } elseif ($field == 'onoff_report') {
                $updateData['onoff_user_report'] = session()->get('pers_id');
            }

            $this->db->table('tb_onoffsys')->where('onoff_id', 1)->update($updateData);

            return $this->response->setJSON(['success' => true, 'status' => $status]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function update_year()
    {
        try {
            $year = $this->request->getPost('year');

            $this->db->table('tb_openyear')->where('openyear_id', 1)->update([
                'openyear_year' => $year,
                'openyear_userid' => session()->get('pers_id')
            ]);

            return $this->response->setJSON(['success' => true, 'msg' => 'เปลี่ยนปีการศึกษาเรียบร้อยแล้ว']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function update_comment()
    {
        try {
            $comment = $this->request->getPost('comment');

            $this->db->table('tb_onoffsys')->where('onoff_id', 1)->update([
                'onoff_comment' => $comment
            ]);

            return $this->response->setJSON(['success' => true, 'msg' => 'บันทึกข้อความเรียบร้อยแล้ว']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    private function toGregorianDateTime($dateStr)
    {
        if (empty($dateStr)) {
            return null;
        }

        $dateStr = trim($dateStr);

        $thaiMonths = [
            'มกราคม' => '01', 'ม.ค.' => '01',
            'กุมภาพันธ์' => '02', 'ก.พ.' => '02',
            'มีนาคม' => '03', 'มี.ค.' => '03',
            'เมษายน' => '04', 'เม.ย.' => '04',
            'พฤษภาคม' => '05', 'พ.ค.' => '05',
            'มิถุนายน' => '06', 'มิ.ย.' => '06',
            'กรกฎาคม' => '07', 'ก.ค.' => '07',
            'สิงหาคม' => '08', 'ส.ค.' => '08',
            'กันยายน' => '09', 'ก.ย.' => '09',
            'ตุลาคม' => '10', 'ต.ค.' => '10',
            'พฤศจิกายน' => '11', 'พ.ย.' => '11',
            'ธันวาคม' => '12', 'ธ.ค.' => '12',
        ];

        // Match Thai full format: e.g. "1 มกราคม 2569 เวลา 08:30 น." or "1 มกราคม 2569 08:30:00"
        foreach ($thaiMonths as $thaiM => $monthNum) {
            if (strpos($dateStr, $thaiM) !== false) {
                if (preg_match('/(\d{1,2})\s+' . preg_quote($thaiM, '/') . '\s+(\d{4})(?:\s+(?:เวลา\s+)?(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?(?:\s*น\.?)?)?/', $dateStr, $m)) {
                    $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                    $year = intval($m[2]);
                    if ($year > 2400) {
                        $year -= 543;
                    }
                    $hour = isset($m[3]) ? str_pad($m[3], 2, '0', STR_PAD_LEFT) : '00';
                    $min = isset($m[4]) ? str_pad($m[4], 2, '0', STR_PAD_LEFT) : '00';
                    $sec = isset($m[5]) ? str_pad($m[5], 2, '0', STR_PAD_LEFT) : '00';
                    return "$year-$monthNum-$day $hour:$min:$sec";
                }
            }
        }

        // Convert Buddhist Year (e.g. 2569-01-01 or 2569/01/01)
        if (preg_match('/^(\d{4})([-\/])(.*)$/', $dateStr, $matches)) {
            $year = intval($matches[1]);
            if ($year > 2400) {
                $year -= 543;
                $dateStr = $year . $matches[2] . $matches[3];
            }
        }

        $timestamp = strtotime($dateStr);
        if ($timestamp !== false) {
            return date('Y-m-d H:i:s', $timestamp);
        }

        return null;
    }

    public function update_dates()
    {
        try {
            $this->ensureColumnExists();
            $dateOpen = $this->request->getPost('dateOpen');
            $dateClose = $this->request->getPost('dateClose');

            $parsedOpen = $this->toGregorianDateTime($dateOpen);
            $parsedClose = $this->toGregorianDateTime($dateClose);

            $updateData = [
                'onoff_datetime_regis_open' => $parsedOpen,
                'onoff_datetime_regis_close' => $parsedClose,
            ];

            $this->db->table('tb_onoffsys')->where('onoff_id', 1)->update($updateData);

            return $this->response->setJSON([
                'success' => true, 
                'msg' => 'บันทึกช่วงเวลาเรียบร้อยแล้ว'
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'success' => false, 
                'msg' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function update_round()
    {
        try {
            $this->ensureColumnExists();
            $round = $this->request->getPost('round');

            $this->db->table('tb_onoffsys')->where('onoff_id', 1)->update([
                'onoff_round' => $round
            ]);

            return $this->response->setJSON(['success' => true, 'msg' => 'เปลี่ยนรอบการรับสมัครเรียบร้อยแล้ว']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function update_system_text()
    {
        try {
            $this->ensureColumnExists();
            $text = $this->request->getPost('text');

            $this->db->table('tb_onoffsys')->where('onoff_id', 1)->update([
                'onoff_system_text' => $text
            ]);

            return $this->response->setJSON(['success' => true, 'msg' => 'อัปเดตหัวข้อประกาศเรียบร้อยแล้ว']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    private function ensureColumnExists()
    {
        try {
            $fields = $this->db->getFieldNames('tb_onoffsys');
            if (!in_array('onoff_system_text', $fields)) {
                $this->db->query("ALTER TABLE tb_onoffsys ADD onoff_system_text VARCHAR(255) DEFAULT 'ประกาศผลการคัดเลือก' AFTER onoff_system");
            }
            if (!in_array('onoff_datetime_regis_open', $fields)) {
                $this->db->query("ALTER TABLE tb_onoffsys ADD onoff_datetime_regis_open DATETIME NULL AFTER onoff_regis");
            }
            if (!in_array('onoff_datetime_regis_close', $fields)) {
                $this->db->query("ALTER TABLE tb_onoffsys ADD onoff_datetime_regis_close DATETIME NULL AFTER onoff_datetime_regis_open");
            }
            if (!in_array('onoff_round', $fields)) {
                $this->db->query("ALTER TABLE tb_onoffsys ADD onoff_round INT(11) DEFAULT 1 AFTER onoff_system");
            }
        } catch (\Throwable $e) {
            // Ignore if column already exists
        }
    }
}

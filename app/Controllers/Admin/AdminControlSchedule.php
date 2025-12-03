<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlSchedule extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Display schedule management page
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('admin/login'));
        }

        // Get current academic year from tb_openyear
        $yearBuilder = $this->db->table('tb_openyear');
        $yearConfig = $yearBuilder->where('openyear_id', 1)->get()->getRow();
        $currentYear = $yearConfig ? $yearConfig->openyear_year : date('Y') + 543;

        // Get all schedules ordered by round and level
        $scheduleBuilder = $this->db->table('tb_admission_schedule');
        $scheduleBuilder->orderBy('schedule_year', 'DESC');
        $scheduleBuilder->orderBy('schedule_level', 'ASC');
        $scheduleBuilder->orderBy('schedule_round', 'ASC');
        $schedules = $scheduleBuilder->get()->getResultArray();

        $data = [
            'title' => 'จัดการกำหนดการ',
            'currentYear' => $currentYear,
            'schedules' => $schedules
        ];

        return view('Admin/PageAdminSchedule/AdminSchedule', $data);
    }

    /**
     * Get all schedules for a specific year (AJAX)
     */
    public function getSchedules()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $year = $this->request->getPost('year');
        $level = $this->request->getPost('level');

        $scheduleBuilder = $this->db->table('tb_admission_schedule');
        
        if ($year) {
            $scheduleBuilder->where('schedule_year', $year);
        }
        
        if ($level) {
            $scheduleBuilder->where('schedule_level', $level);
        }
        
        $scheduleBuilder->orderBy('schedule_round', 'ASC');
        $scheduleBuilder->orderBy('schedule_level', 'ASC');
        $schedules = $scheduleBuilder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $schedules
        ]);
    }

    /**
     * Add new schedule (AJAX)
     */
    public function addSchedule()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'schedule_round' => 'required',
            'schedule_level' => 'required',
            'schedule_year' => 'required|numeric',
            'schedule_recruit_start' => 'required|valid_date',
            'schedule_recruit_end' => 'required|valid_date',
            'schedule_exam' => 'permit_empty|valid_date',
            'schedule_announce' => 'permit_empty|valid_date',
            'schedule_report' => 'permit_empty|valid_date'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน',
                'errors' => $validation->getErrors()
            ]);
        }

        $data = [
            'schedule_round' => $this->request->getPost('schedule_round'),
            'schedule_level' => $this->request->getPost('schedule_level'),
            'schedule_year' => $this->request->getPost('schedule_year'),
            'schedule_recruit_start' => $this->request->getPost('schedule_recruit_start'),
            'schedule_recruit_end' => $this->request->getPost('schedule_recruit_end'),
            'schedule_exam' => $this->request->getPost('schedule_exam') ?: null,
            'schedule_announce' => $this->request->getPost('schedule_announce') ?: null,
            'schedule_report' => $this->request->getPost('schedule_report') ?: null
        ];

        try {
            $scheduleBuilder = $this->db->table('tb_admission_schedule');
            $scheduleBuilder->insert($data);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'เพิ่มกำหนดการสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update schedule (AJAX)
     */
    public function updateSchedule()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $scheduleId = $this->request->getPost('schedule_id');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'schedule_id' => 'required|numeric',
            'schedule_round' => 'required',
            'schedule_level' => 'required',
            'schedule_year' => 'required|numeric',
            'schedule_recruit_start' => 'required|valid_date',
            'schedule_recruit_end' => 'required|valid_date',
            'schedule_exam' => 'permit_empty|valid_date',
            'schedule_announce' => 'permit_empty|valid_date',
            'schedule_report' => 'permit_empty|valid_date'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน',
                'errors' => $validation->getErrors()
            ]);
        }

        $data = [
            'schedule_round' => $this->request->getPost('schedule_round'),
            'schedule_level' => $this->request->getPost('schedule_level'),
            'schedule_year' => $this->request->getPost('schedule_year'),
            'schedule_recruit_start' => $this->request->getPost('schedule_recruit_start'),
            'schedule_recruit_end' => $this->request->getPost('schedule_recruit_end'),
            'schedule_exam' => $this->request->getPost('schedule_exam') ?: null,
            'schedule_announce' => $this->request->getPost('schedule_announce') ?: null,
            'schedule_report' => $this->request->getPost('schedule_report') ?: null
        ];

        try {
            $scheduleBuilder = $this->db->table('tb_admission_schedule');
            $scheduleBuilder->where('schedule_id', $scheduleId);
            $scheduleBuilder->update($data);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'แก้ไขกำหนดการสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete schedule (AJAX)
     */
    public function deleteSchedule()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $scheduleId = $this->request->getPost('schedule_id');

        if (!$scheduleId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลกำหนดการ'
            ]);
        }

        try {
            $scheduleBuilder = $this->db->table('tb_admission_schedule');
            $scheduleBuilder->where('schedule_id', $scheduleId);
            $scheduleBuilder->delete();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'ลบกำหนดการสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }
}

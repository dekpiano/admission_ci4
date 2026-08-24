<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\AdmissionModel;
use App\Libraries\RemoteUpload;

class AdminControlCleanup extends BaseController
{
    protected $adminAdmissionModel;
    protected $db;
    protected $session;
    protected $remoteUpload;

    public function __construct()
    {
        $this->adminAdmissionModel = new AdmissionModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->remoteUpload = new RemoteUpload();
        helper(['url', 'form', 'upload']);
    }

    private function checkAuth()
    {
        if (!$this->session->has('login_id') && !$this->session->has('pers_id')) {
            return false;
        }
        return true;
    }

    public function index()
    {
        if (!$this->checkAuth()) {
            return redirect()->to('loginAdmin');
        }

        $data['title'] = "จัดการไฟล์ขยะ";

        // Count junk candidates
        $data['counts'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_status, COUNT(*) as total')
            ->whereIn('recruit_status', ['กรอกข้อมูลไม่ครบถ้วน', 'พฤติกรรมไม่เหมาะสม', 'มีพฤติกรรมไม่เหมาะสม', 'ไม่มีรูปภาพ หรือรูปภาพไม่ผ่านการตรวจสอบ'])
            ->groupBy('recruit_status')
            ->get()->getResult();

        // Total junk
        $data['total_junk'] = 0;
        foreach ($data['counts'] as $row) {
            $data['total_junk'] += $row->total;
        }

        // Check local temp files
        $tempFiles = glob(WRITEPATH . 'temp/*') ?: [];
        $cacheFiles = glob(WRITEPATH . 'cache/*') ?: [];
        $data['local_temp_count'] = max(0, count($tempFiles) - 1);
        $data['local_cache_count'] = max(0, count($cacheFiles) - 1);

        return view('Admin/PageAdminCleanup/PageAdminCleanupIndex', $data);
    }

    public function clean_local()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $temp_files = glob(WRITEPATH . 'temp/*') ?: [];
        $cache_files = glob(WRITEPATH . 'cache/*') ?: [];
        $deleted = 0;

        foreach (array_merge($temp_files, $cache_files) as $file) {
            if (is_file($file) && !in_array(basename($file), ['.htaccess', 'index.html', '.gitkeep'])) {
                @unlink($file);
                $deleted++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "ล้างไฟล์ชั่วคราวในเครื่องสำเร็จ $deleted รายการ"
        ]);
    }

    public function scan()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $status = $this->request->getPost('status');
        $builder = $this->db->table('tb_recruitstudent');

        if ($status && $status != 'all') {
            $builder->where('recruit_status', $status);
        } else {
            $builder->whereIn('recruit_status', ['กรอกข้อมูลไม่ครบถ้วน', 'พฤติกรรมไม่เหมาะสม', 'มีพฤติกรรมไม่เหมาะสม', 'ไม่มีรูปภาพ หรือรูปภาพไม่ผ่านการตรวจสอบ']);
        }

        $candidates = $builder->get()->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $candidates
        ]);
    }

    public function delete()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $ids = $this->request->getPost('ids');
        if (empty($ids)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเลือกข้อมูลที่ต้องการลบ']);
        }

        $success_count = 0;
        $error_count = 0;

        foreach ($ids as $id) {
            $student = $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->get()->getRow();
            if ($student) {
                // Delete files from Remote Server
                $regLevel = $student->recruit_regLevel;
                $file_fields = [
                    'recruit_img' => 'img',
                    'recruit_certificateEdu' => 'certificate',
                    'recruit_certificateEduB' => 'certificateB',
                    'recruit_copyidCard' => 'copyidCard',
                    'recruit_copyAddress' => 'copyAddress'
                ];

                foreach ($file_fields as $field => $folder) {
                    if (!empty($student->$field)) {
                        $subPath = 'admission/recruitstudent/m' . $regLevel . '/' . $folder;
                        $this->remoteUpload->delete($student->$field, $subPath);
                    }
                }

                // Delete ability certificates
                if (!empty($student->recruit_certificateAbility)) {
                    $abilityFiles = explode('|', $student->recruit_certificateAbility);
                    $this->remoteUpload->delete($abilityFiles, 'admission/recruitstudent/m' . $regLevel . '/certificateAbility');
                }

                // Delete from DB
                if ($this->db->table('tb_recruitstudent')->where('recruit_id', $id)->delete()) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "ลบข้อมูลสำเร็จ $success_count รายการ" . ($error_count > 0 ? " (ผิดพลาด $error_count รายการ)" : "")
        ]);
    }

    /**
     * Delete a single recruit record (for progress tracking)
     */
    public function delete_single()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $id = $this->request->getPost('id');
        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบ ID']);
        }

        $student = $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->get()->getRow();
        if (!$student) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
        }

        // Delete files from Remote Server
        $regLevel = $student->recruit_regLevel;
        $file_fields = [
            'recruit_img' => 'img',
            'recruit_certificateEdu' => 'certificate',
            'recruit_certificateEduB' => 'certificateB',
            'recruit_copyidCard' => 'copyidCard',
            'recruit_copyAddress' => 'copyAddress'
        ];

        foreach ($file_fields as $field => $folder) {
            if (!empty($student->$field)) {
                $subPath = 'admission/recruitstudent/m' . $regLevel . '/' . $folder;
                $this->remoteUpload->delete($student->$field, $subPath);
            }
        }

        // Delete ability certificates
        if (!empty($student->recruit_certificateAbility)) {
            $abilityFiles = explode('|', $student->recruit_certificateAbility);
            $this->remoteUpload->delete($abilityFiles, 'admission/recruitstudent/m' . $regLevel . '/certificateAbility');
        }

        // Delete from DB
        if ($this->db->table('tb_recruitstudent')->where('recruit_id', $id)->delete()) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ลบไม่สำเร็จ']);
    }

    public function scan_orphans()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $db_files = $this->get_db_filenames();
        $remote_url = get_token_url('list_files.php');
        $token = trim(getenv('upload.secret.token') ?: "Dekpiano2025!!");

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($remote_url, [
                'headers' => ['X-Auth-Token' => $token],
                'verify' => false,
                'timeout' => 30
            ]);
            $body = json_decode($response->getBody(), true);

            if ($body['status'] !== 'success') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ยังไม่ได้ตั้งค่าไฟล์ list_files.php บน Server ปลายทาง']);
            }

            $remote_files = $body['files'];
            $orphans = [];

            foreach ($remote_files as $file) {
                if (in_array($file['name'], ['index.html', '.htaccess', 'default.png']))
                    continue;

                if (!in_array($file['name'], $db_files)) {
                    $orphans[] = [
                        'name' => $file['name'],
                        'path' => $file['path'],
                        'size' => $file['size'] ?? 0
                    ];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'count' => count($orphans),
                'orphans' => $orphans
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ติดต่อ Server ปลายทางไม่ได้: ' . $e->getMessage()]);
        }
    }

    private function get_db_filenames()
    {
        $fields = ['recruit_img', 'recruit_certificateEdu', 'recruit_certificateEduB', 'recruit_copyidCard', 'recruit_copyAddress', 'recruit_certificateAbility'];
        $all_filenames = [];

        foreach ($fields as $field) {
            $results = $this->db->table('tb_recruitstudent')->select($field)->where($field . ' !=', '')->get()->getResultArray();
            foreach ($results as $row) {
                if ($field == 'recruit_certificateAbility') {
                    $parts = explode('|', $row[$field]);
                    foreach ($parts as $p) {
                        if (!empty($p))
                            $all_filenames[] = $p;
                    }
                } else {
                    $all_filenames[] = $row[$field];
                }
            }
        }
        return array_unique($all_filenames);
    }

    public function delete_orphans()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $files = $this->request->getPost('files');
        if (empty($files)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเลือกไฟล์']);
        }

        $success = 0;
        foreach ($files as $file) {
            $subPath = dirname($file['path']);
            if ($this->remoteUpload->delete($file['name'], $subPath)) {
                $success++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "กำจัดไฟล์ไม่ตรง DB สำเร็จ $success รายการ"
        ]);
    }

    /**
     * Delete a single orphan file (for progress tracking)
     */
    public function delete_orphan_single()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $name = $this->request->getPost('name');
        $path = $this->request->getPost('path');

        if (empty($name) || empty($path)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ครบ']);
        }

        $subPath = dirname($path);
        if ($this->remoteUpload->delete($name, $subPath)) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ลบไม่สำเร็จ']);
    }

    /**
     * List files in trash
     */
    public function list_trash()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $result = $this->remoteUpload->listTrash();
        
        if ($result) {
            // Calculate total size in MB
            $totalSizeMB = round(($result['total_size'] ?? 0) / 1024 / 1024, 2);
            
            return $this->response->setJSON([
                'status' => 'success',
                'count' => $result['count'] ?? 0,
                'total_size' => $result['total_size'] ?? 0,
                'total_size_mb' => $totalSizeMB,
                'expired_count' => $result['expired_count'] ?? 0,
                'files' => $result['files'] ?? []
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'ไม่สามารถเชื่อมต่อ Server ปลายทางได้ (กรุณาอัพโหลด list_trash.php)'
        ]);
    }

    /**
     * Restore a file from trash
     */
    public function restore_file()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $name = $this->request->getPost('name');
        $path = $this->request->getPost('path');

        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ครบ']);
        }

        $result = $this->remoteUpload->restoreFromTrash($name, $path);
        
        if ($result && $result['status'] === 'success') {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'กู้คืนไฟล์สำเร็จ',
                'restored_to' => $result['restored_to'] ?? ''
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => $result['message'] ?? 'กู้คืนไม่สำเร็จ'
        ]);
    }

    /**
     * Empty expired files from trash
     */
    public function empty_expired()
    {
        if (!$this->checkAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $remote_url = get_token_url('empty_expired_trash.php');
        $token = trim(getenv('upload.secret.token') ?: "Dekpiano2025!!");

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->post($remote_url, [
                'headers' => [
                    'X-Auth-Token' => $token,
                    'Authorization' => 'Bearer ' . $token
                ],
                'verify' => false,
                'timeout' => 60
            ]);
            $body = json_decode($response->getBody(), true);

            if ($body && $body['status'] === 'success') {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => $body['message'] ?? 'สำเร็จ',
                    'deleted_count' => $body['deleted_count'] ?? 0,
                    'deleted_files' => $body['deleted_files'] ?? []
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $body['message'] ?? 'เกิดข้อผิดพลาด'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ติดต่อ Server ปลายทางไม่ได้: ' . $e->getMessage()
            ]);
        }
    }
}

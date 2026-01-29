<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class AdminControlSchool extends BaseController
{
    use ResponseTrait;

    protected $db_schoolall;

    public function __construct()
    {
        $this->db_schoolall = \Config\Database::connect('schoolall');
        helper(['url', 'form']);
    }

    /**
     * Main school management page
     */
    public function index()
    {
        $data['title'] = "จัดการข้อมูลโรงเรียน";
        $data['stats'] = $this->getSchoolStats();
        return view('Admin/PageAdminSchools/index', $data);
    }

    /**
     * Get school statistics
     */
    protected function getSchoolStats()
    {
        $stats = [];
        $builder = $this->db_schoolall->table('schoolall');
        
        // Total schools
        $stats['total'] = $builder->countAllResults(false);
        
        // Schools by province (top 10)
        $provinceStats = $this->db_schoolall->table('schoolall')
            ->select('schoola_province, COUNT(*) as count')
            ->groupBy('schoola_province')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get()
            ->getResult();
        $stats['by_province'] = $provinceStats;
        
        return $stats;
    }

    /**
     * Get schools via AJAX with DataTables server-side processing
     */
    public function getSchoolsAjax()
    {
        $request = $this->request;
        
        $draw = $request->getVar('draw');
        $start = $request->getVar('start') ?? 0;
        $length = $request->getVar('length') ?? 10;
        $search = $request->getVar('search')['value'] ?? '';
        $orderColumn = $request->getVar('order')[0]['column'] ?? 0;
        $orderDir = $request->getVar('order')[0]['dir'] ?? 'asc';
        
        $columns = ['schoola_id', 'schoola_name', 'schoola_amphur', 'schoola_district', 'schoola_province'];
        $orderBy = $columns[$orderColumn] ?? 'schoola_id';
        
        $builder = $this->db_schoolall->table('schoolall');
        
        // Total records
        $totalRecords = $this->db_schoolall->table('schoolall')->countAllResults();
        
        // Filtered records
        $builder->select('schoola_id, schoola_name, schoola_amphur, schoola_district, schoola_province');
        
        if (!empty($search)) {
            $builder->groupStart()
                ->like('schoola_name', $search)
                ->orLike('schoola_amphur', $search)
                ->orLike('schoola_district', $search)
                ->orLike('schoola_province', $search)
                ->groupEnd();
        }
        
        $filteredRecords = $builder->countAllResults(false);
        
        // Get data
        $builder->orderBy($orderBy, $orderDir);
        $builder->limit($length, $start);
        $schools = $builder->get()->getResult();
        
        // Prepare data for DataTables
        $data = [];
        foreach ($schools as $school) {
            $data[] = [
                'schoola_id' => $school->schoola_id,
                'schoola_name' => $school->schoola_name,
                'schoola_amphur' => $school->schoola_amphur,
                'schoola_district' => $school->schoola_district,
                'schoola_province' => $school->schoola_province
            ];
        }
        
        return $this->respond([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get single school for editing
     */
    public function getSchool($id)
    {
        $builder = $this->db_schoolall->table('schoolall');
        $school = $builder->where('schoola_id', $id)->get()->getRow();
        
        if ($school) {
            return $this->respond(['status' => 'success', 'data' => $school]);
        }
        return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลโรงเรียน'], 404);
    }

    /**
     * Add new school
     */
    public function add()
    {
        $schoolName = $this->request->getPost('school_name');
        $schoolAmphur = $this->request->getPost('school_amphur');
        $schoolDistrict = $this->request->getPost('school_district');
        $schoolProvince = $this->request->getPost('school_province');

        if (empty($schoolName)) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณากรอกชื่อโรงเรียน'], 400);
        }

        // Check duplicate
        $exists = $this->db_schoolall->table('schoolall')
            ->where('schoola_name', $schoolName)
            ->where('schoola_amphur', $schoolAmphur)
            ->where('schoola_province', $schoolProvince)
            ->countAllResults();

        if ($exists > 0) {
            return $this->respond(['status' => 'error', 'message' => 'มีรายชื่อโรงเรียนนี้อยู่แล้วในจังหวัดและอำเภอเดียวกัน'], 400);
        }

        // Get max ID
        $maxId = $this->db_schoolall->table('schoolall')->selectMax('schoola_id')->get()->getRow()->schoola_id;
        $newId = $maxId + 1;

        $this->db_schoolall->table('schoolall')->insert([
            'schoola_id' => $newId,
            'schoola_name' => $schoolName,
            'schoola_amphur' => $schoolAmphur ?? '',
            'schoola_district' => $schoolDistrict ?? '',
            'schoola_province' => $schoolProvince ?? '',
            'schoola_postcode' => ''
        ]);

        return $this->respond(['status' => 'success', 'message' => 'เพิ่มข้อมูลโรงเรียนสำเร็จ', 'id' => $newId]);
    }

    /**
     * Update school
     */
    public function update($id)
    {
        $schoolName = $this->request->getPost('school_name');
        $schoolAmphur = $this->request->getPost('school_amphur');
        $schoolDistrict = $this->request->getPost('school_district');
        $schoolProvince = $this->request->getPost('school_province');

        if (empty($schoolName)) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณากรอกชื่อโรงเรียน'], 400);
        }

        // Check if school exists
        $exists = $this->db_schoolall->table('schoolall')
            ->where('schoola_id', $id)
            ->countAllResults();

        if ($exists == 0) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลโรงเรียน'], 404);
        }

        // Check duplicate (exclude current record)
        $duplicate = $this->db_schoolall->table('schoolall')
            ->where('schoola_name', $schoolName)
            ->where('schoola_amphur', $schoolAmphur)
            ->where('schoola_province', $schoolProvince)
            ->where('schoola_id !=', $id)
            ->countAllResults();

        if ($duplicate > 0) {
            return $this->respond(['status' => 'error', 'message' => 'มีรายชื่อโรงเรียนนี้อยู่แล้วในจังหวัดและอำเภอเดียวกัน'], 400);
        }

        $this->db_schoolall->table('schoolall')
            ->where('schoola_id', $id)
            ->update([
                'schoola_name' => $schoolName,
                'schoola_amphur' => $schoolAmphur ?? '',
                'schoola_district' => $schoolDistrict ?? '',
                'schoola_province' => $schoolProvince ?? ''
            ]);

        return $this->respond(['status' => 'success', 'message' => 'แก้ไขข้อมูลโรงเรียนสำเร็จ']);
    }

    /**
     * Delete school
     */
    public function delete($id)
    {
        // Check if school exists
        $exists = $this->db_schoolall->table('schoolall')
            ->where('schoola_id', $id)
            ->countAllResults();

        if ($exists == 0) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลโรงเรียน'], 404);
        }

        $this->db_schoolall->table('schoolall')
            ->where('schoola_id', $id)
            ->delete();

        return $this->respond(['status' => 'success', 'message' => 'ลบข้อมูลโรงเรียนสำเร็จ']);
    }

    /**
     * Get provinces list for dropdown
     */
    public function getProvinces()
    {
        $provinces = $this->db_schoolall->table('schoolall')
            ->select('schoola_province')
            ->groupBy('schoola_province')
            ->orderBy('schoola_province', 'ASC')
            ->get()
            ->getResult();

        return $this->respond($provinces);
    }

    /**
     * Get amphurs by province
     */
    public function getAmphurs()
    {
        $province = $this->request->getVar('province');
        
        $amphurs = $this->db_schoolall->table('schoolall')
            ->select('schoola_amphur')
            ->where('schoola_province', $province)
            ->groupBy('schoola_amphur')
            ->orderBy('schoola_amphur', 'ASC')
            ->get()
            ->getResult();

        return $this->respond($amphurs);
    }

    /**
     * Get districts by amphur
     */
    public function getDistricts()
    {
        $province = $this->request->getVar('province');
        $amphur = $this->request->getVar('amphur');
        
        $districts = $this->db_schoolall->table('schoolall')
            ->select('schoola_district')
            ->where('schoola_province', $province)
            ->where('schoola_amphur', $amphur)
            ->groupBy('schoola_district')
            ->orderBy('schoola_district', 'ASC')
            ->get()
            ->getResult();

        return $this->respond($districts);
    }
}

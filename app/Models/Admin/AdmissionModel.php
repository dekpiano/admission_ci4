<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class AdmissionModel extends Model
{
    protected $table = 'tb_recruitstudent';
    protected $primaryKey = 'recruit_id';
    protected $allowedFields = [
        'recruit_regLevel',
        'recruit_prefix',
        'recruit_firstName',
        'recruit_lastName',
        'recruit_oldSchool',
        'recruit_district',
        'recruit_province',
        'recruit_birthday',
        'recruit_race',
        'recruit_nationality',
        'recruit_religion',
        'recruit_idCard',
        'recruit_phone',
        'recruit_homeNumber',
        'recruit_homeGroup',
        'recruit_homeRoad',
        'recruit_homeSubdistrict',
        'recruit_homedistrict',
        'recruit_homeProvince',
        'recruit_homePostcode',
        'recruit_tpyeRoom',
        'recruit_major',
        'recruit_grade',
        'recruit_year',
        'recruit_majorOrder',
        'recruit_agegroup',
        'recruit_img',
        'recruit_certificateEdu',
        'recruit_certificateEduB',
        'recruit_copyidCard',
        'recruit_copyAddress',
        'recruit_certificateAbility',
        'recruit_status',
        'recruit_category'
    ];

    public function recruitstudent_insert($data)
    {
        return $this->db->table('tb_recruitstudent')->insert($data);
    }

    public function recruitstudent_update($data, $id)
    {
        return $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->update($data);
    }

    public function recruitstudent_delete($id)
    {
        return $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->delete();
    }
}

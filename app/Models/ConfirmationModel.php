<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfirmationModel extends Model
{
    protected $table = 'tb_students'; // This will likely be the skjpers.tb_students table
    protected $primaryKey = 'stu_id'; // Assuming a primary key
    protected $allowedFields = [
        // Add all the fields from the old UserControlConfirm controller's insert/update methods
        'stu_iden', 'stu_UpdateConfirm', 'stu_prefix', 'stu_fristName', 'stu_lastName', 
        'stu_nickName', 'stu_img', 'stu_birthDay', 'stu_birthTambon', 'stu_birthDistrict', 
        'stu_birthProvirce', 'stu_birthHospital', 'stu_nationality', 'stu_race', 'stu_religion', 
        'stu_bloodType', 'stu_diseaes', 'stu_numberSibling', 'stu_firstChild', 'stu_numberSiblingSkj', 
        'stu_disablde', 'stu_wieght', 'stu_hieght', 'stu_talent', 'stu_parenalStatus', 
        'stu_presentLife', 'stu_personOther', 'stu_hCode', 'stu_hNumber', 'stu_hMoo', 
        'stu_hRoad', 'stu_hTambon', 'stu_hDistrict', 'stu_hProvince', 'stu_hPostCode', 
        'stu_phone', 'stu_email', 'stu_cNumber', 'stu_cMoo', 'stu_cRoad', 'stu_cTumbao', 
        'stu_cDistrict', 'stu_cProvince', 'stu_cPostcode', 'stu_natureRoom', 'stu_farSchool', 
        'stu_travel', 'stu_gradLevel', 'stu_schoolfrom', 'stu_schoolTambao', 'stu_schoolDistrict', 
        'stu_schoolProvince', 'stu_usedStudent', 'stu_inputLevel', 'stu_phoneUrgent', 
        'stu_phoneFriend', 'stu_active', 'stu_createDate'
    ];

    // You might need to set the database group if it's not the default one
    // protected $DBGroup = 'skjpers';

    /**
     * Check if a student has already confirmed.
     * @param string $idCard The student's ID card number.
     * @return bool
     */
    public function hasConfirmed($idCard)
    {
        // This assumes you have configured the 'skjpers' database group in app/Config/Database.php
        $db = \Config\Database::connect('skjpers');
        $builder = $db->table($this->table);
        
        return $builder->where('stu_iden', $idCard)->countAllResults() > 0;
    }

    // Add other methods as needed, e.g., for inserting/updating student data, parent data, etc.
}

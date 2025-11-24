<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfirmModel extends Model
{
    public function ConfirmStudentCheckID($ID)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_students')->where('stu_iden', $ID)->countAllResults();
    }

    public function ConfirmParentCheckID($idStu, $relationKey)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_parent')->where('par_stuID', $idStu)->where('par_relationKey', $relationKey)->countAllResults();
    }

    public function ConfirmStudentInsert($data)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_students')->insert($data);
    }

    public function ConfirmStudentUpdate($data, $id)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_students')->where('stu_iden', $id)->update($data);
    }

    // Father
    public function ConfirmFatherInsert($data)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_parent')->insert($data);
    }

    public function ConfirmFatherUpdate($data, $id)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_parent')->where('par_id', $id)->update($data);
    }

    // Mother
    public function ConfirmMatherInsert($data)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_parent')->insert($data);
    }

    public function ConfirmMatherUpdate($data, $id)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_parent')->where('par_id', $id)->update($data);
    }

    // Other
    public function ConfirmOtherInsert($data)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_parent')->insert($data);
    }

    public function ConfirmOtherUpdate($data, $id)
    {
        $Conf = \Config\Database::connect('skjpers');
        return $Conf->table('tb_parent')->where('par_id', $id)->update($data);
    }
}

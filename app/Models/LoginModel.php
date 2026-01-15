<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        helper('upload');
    }

    public function record_count($username, $password)
    {
        $db2 = \Config\Database::connect('skjpers');
        $db2->table('tb_personnel')->where('pers_username', $username);
        $db2->table('tb_personnel')->where('pers_password', $password);
        // CI4 countAllResults needs the table builder or table name if using Model
        return $db2->table('tb_personnel')
            ->where('pers_username', $username)
            ->where('pers_password', $password)
            ->countAllResults();
    }

    public function fetch_user_login($username, $password)
    {
        $db2 = \Config\Database::connect('skjpers');
        return $db2->table('tb_personnel')
            ->where('pers_username', $username)
            ->where('pers_password', $password)
            ->get()->getRow();
    }

    public function Student_Login($username, $password)
    {
        $plainId = str_replace('-', '', $username ?? '');
        $formattedId = \format_id_card($username);

        return $this->db->table('tb_recruitstudent')
            ->groupStart()
                ->where('recruit_idCard', $plainId)
                ->orWhere('recruit_idCard', $formattedId)
            ->groupEnd()
            ->where('recruit_birthday', $password)
            ->get()->getResult();
    }
}

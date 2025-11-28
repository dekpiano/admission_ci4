<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class UserControlManual extends BaseController
{
    public function index()
    {
        $data['title'] = 'คู่มือการใช้งาน';
        return view('User/PageUserManual/UserManual', $data);
    }
}

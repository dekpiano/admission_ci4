<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table      = 'tb_course';
    protected $primaryKey = 'course_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Assuming no soft deletes for courses

    protected $allowedFields = ['course_fullname', 'course_initials', 'course_branch', 'course_gradelevel', 'course_age'];

    protected $useTimestamps = false; // Assuming no timestamp fields for courses
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

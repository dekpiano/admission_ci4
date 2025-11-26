<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotaModel extends Model
{
    protected $table      = 'tb_quota';
    protected $primaryKey = 'quota_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Assuming no soft deletes for quotas

    protected $allowedFields = ['quota_key', 'quota_level', 'quota_explain', 'quota_status', 'quota_course'];

    protected $useTimestamps = false; // Assuming no timestamp fields for quotas
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

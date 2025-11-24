<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'tb_news';
    protected $primaryKey = 'news_id';
    protected $allowedFields = ['news_view']; // Add other fields if needed

    public function update_count_view($id)
    {
        $check = $this->db->table('tb_news')->select('news_id,news_view')->where('news_id', $id)->get()->getRow();

        if ($check) {
            $data = ['news_view' => ($check->news_view + 1)];
            return $this->db->table('tb_news')->where('news_id', $id)->update($data);
        }
        return false;
    }
}

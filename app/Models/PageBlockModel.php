<?php

namespace App\Models;

use CodeIgniter\Model;

class PageBlockModel extends Model
{
    protected $table = 'page_blocks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['page_id', 'block_type', 'block_data', 'block_data_en', 'sort_order', 'published'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

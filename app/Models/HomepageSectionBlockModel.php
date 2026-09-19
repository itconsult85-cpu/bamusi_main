<?php

namespace App\Models;

use CodeIgniter\Model;

class HomepageSectionBlockModel extends Model
{
    protected $table = 'homepage_section_blocks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'section_id', 'section_key', 'block_type', 'block_data', 'block_data_en',
        'sort_order', 'published',
    ];
    protected $useTimestamps = true;
}

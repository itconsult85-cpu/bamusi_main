<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionLinkModel extends Model
{
    protected $table = 'section_links';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'section_key',
        'label',
        'label_en',
        'sublabel',
        'sublabel_en',
        'url',
        'sort_order',
        'published',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

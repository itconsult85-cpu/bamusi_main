<?php

namespace App\Models;

use CodeIgniter\Model;

class HomepageSectionItemModel extends Model
{
    protected $table = 'homepage_section_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'section_key', 'item_key', 'label', 'label_en', 'title', 'title_en',
        'body', 'body_en', 'url', 'media_url', 'options_json', 'sort_order', 'published'
    ];
    protected $useTimestamps = true;
}

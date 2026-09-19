<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuItemModel extends Model
{
    protected $table = 'cms_menu_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'parent_id',
        'label',
        'label_en',
        'target_type',
        'target',
        'description',
        'description_en',
        'active',
        'is_mega',
        'sort_order',
    ];
    protected $useTimestamps = true;
}

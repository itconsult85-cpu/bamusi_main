<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table = 'site_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'label',
        'label_en',
        'location',
        'type',
        'sort_order',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
    protected $updatedField  = 'updated_at';
}

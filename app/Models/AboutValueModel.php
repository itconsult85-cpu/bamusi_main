<?php

namespace App\Models;

use CodeIgniter\Model;

class AboutValueModel extends Model
{
    protected $table = 'about_values';
    protected $allowedFields = ['label', 'label_en', 'description', 'description_en', 'sort_order', 'published'];
}

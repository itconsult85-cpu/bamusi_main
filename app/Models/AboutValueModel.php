<?php

namespace App\Models;

use CodeIgniter\Model;

class AboutValueModel extends Model
{
    protected $table            = 'about_values';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['label', 'description', 'sort_order', 'published'];
    protected $useTimestamps    = true;
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PartnerModel extends Model
{
    protected $table = 'partners';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'name_en', 'logo_url', 'website_url', 'sort_order', 'published'];
    protected $useTimestamps = true;
}

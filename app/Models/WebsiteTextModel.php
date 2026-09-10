<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteTextModel extends Model
{
    protected $table = 'website_texts';
    protected $allowedFields = ['text_key', 'label', 'location', 'value', 'value_en', 'sort_order', 'published'];
}

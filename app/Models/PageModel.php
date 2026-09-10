<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'slug',
        'parent_id',
        'is_mega',
        'menu_desc',
        'menu_desc_en',
        'title',
        'title_en',
        'excerpt',
        'excerpt_en',
        'body',
        'body_en',
        'image_url',
        'published',
        'show_in_menu',
        'menu_label',
        'menu_label_en',
        'sort_order',
        'meta_title',
        'meta_description',
        'header_kicker',
        'header_title',
        'header_intro',
        'header_logo_url',
        'header_show_logo',
        'header_show_intro',
        'header_show_back',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

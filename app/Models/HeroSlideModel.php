<?php

namespace App\Models;

use CodeIgniter\Model;

class HeroSlideModel extends Model
{
    protected $table = 'hero_slides';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kicker',
        'kicker_en',
        'title',
        'title_en',
        'lead',
        'lead_en',
        'quote',
        'quote_en',
        'image_url',
        'kicker_color',
        'title_color',
        'lead_color',
        'quote_color',
        'button_label',
        'button_label_en',
        'button_url',
        'sort_order',
        'published',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PageSectionModel extends Model
{
    protected $table = 'page_sections';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'section_key',
        'section_name',
        'kicker',
        'kicker_en',
        'title',
        'title_en',
        'subtitle',
        'subtitle_en',
        'quote',
        'quote_en',
        'content',
        'content_en',
        'vision',
        'vision_en',
        'mission',
        'mission_en',
        'button_label',
        'button_label_en',
        'button_url',
        'button_position',
        'button_location',
        'cards_visible',
        'cards_limit',
        'cards_columns',
        'media_url',
        'published',
        'sort_order'
    ];
    protected $useTimestamps = true;
}

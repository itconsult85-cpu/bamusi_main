<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table            = 'programs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Pastikan _en masuk di sini!
    protected $allowedFields    = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'image_url',
        'sort_order',
        'division',
        'published'
    ];

    protected $useTimestamps = true;
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class CmsItemModel extends Model
{
    protected $table            = 'cms_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'kind',
        'title',
        'summary',
        'body',
        'url',
        'image_url',
        'category',
        'event_date',
        'location',
        'published'
    ];

    // Tabel cms_items memiliki created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi khusus untuk mengambil berdasarkan jenis (news, agenda, dll)
    public function getItemsByKind($kind, $limit = null)
    {
        $builder = $this->where('kind', $kind)->where('published', 1)->orderBy('created_at', 'DESC');
        if ($limit) {
            return $builder->findAll($limit);
        }
        return $builder->findAll();
    }
}

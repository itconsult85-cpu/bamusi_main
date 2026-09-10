<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table            = 'site_settings';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['setting_key', 'setting_value'];

    // Tabel ini hanya punya updated_at, jadi kita matikan auto timestamps standar CI4
    protected $useTimestamps    = false;

    // Helper untuk mengambil semua setting dalam bentuk array asosiatif (key => value)
    public function getSettingsArray()
    {
        $settings = $this->findAll();
        $result = [];
        foreach ($settings as $s) {
            $result[$s['setting_key']] = $s['setting_value'];
        }
        return $result;
    }
}

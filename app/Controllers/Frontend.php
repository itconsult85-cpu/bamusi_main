<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Frontend extends BaseController
{
    public function index()
    {
        // Tangkap bahasa dari URL (/id atau /en)
        $locale = $this->request->getLocale();
        $db = \Config\Database::connect();

        // Ambil data dari database (misal: hero slides & berita)
        $slider = $db->table('hero_slides')->get()->getResultArray();
        $berita = $db->table('cms_items')->where('kind', 'news')->get()->getResultArray();

        $data = [
            'locale' => $locale,
            'slider' => $slider,
            'berita' => $berita
        ];

        return view('homepage', $data);
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\Controller;
// Panggil library translator
use Stichoza\GoogleTranslate\GoogleTranslate;

class AdminCms extends BaseController
{
    public function simpanBerita()
    {
        $db = \Config\Database::connect();

        // 1. Tangkap input dari form CMS (Hanya Bahasa Indonesia)
        $judul_id = $this->request->getPost('title');
        $isi_id   = $this->request->getPost('body');

        // 2. Inisialisasi Translator (Dari 'id' ke 'en')
        $tr = new GoogleTranslate('en', 'id');

        // 3. Sistem Otomatis Menerjemahkan!
        $judul_en = $tr->translate($judul_id);
        $isi_en   = $tr->translate($isi_id);

        // 4. Siapkan data untuk dimasukkan ke database
        $data = [
            'kind'       => 'news',
            'title'      => $judul_id,
            'title_en'   => $judul_en,     // Hasil auto-translate
            'body'       => $isi_id,
            'body_en'    => $isi_en,       // Hasil auto-translate
            'published'  => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // 5. Simpan ke database
        $db->table('cms_items')->insert($data);

        // Kembalikan ke halaman CMS dengan pesan sukses
        return redirect()->to('/admin/berita')->with('sukses', 'Berita berhasil disimpan dan diterjemahkan otomatis!');
    }
}

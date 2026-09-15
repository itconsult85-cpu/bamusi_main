<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNewsStandalonePage extends Migration
{
    public function up()
    {
        if ($this->db->table('pages')->where('slug', 'berita')->countAllResults() > 0) {
            return;
        }

        $this->db->table('pages')->insert([
            'parent_id'         => null,
            'slug'              => 'berita',
            'title'             => 'Berita',
            'title_en'          => 'News',
            'excerpt'           => 'Kabar BAMUSI untuk Indonesia.',
            'excerpt_en'        => 'BAMUSI news for Indonesia.',
            'body'              => null,
            'body_en'           => null,
            'image_url'         => null,
            'published'         => 1,
            'show_in_menu'      => 1,
            'is_mega'           => 0,
            'menu_label'        => 'Berita',
            'menu_label_en'     => 'News',
            'sort_order'        => 50,
            'meta_title'        => 'Berita BAMUSI',
            'meta_description'  => 'Pemberitaan terbaru tentang Baitul Muslimin Indonesia.',
            'header_kicker'     => '05 / 05 · Ruang Berita',
            'header_title'      => 'Kabar BAMUSI untuk Indonesia.',
            'header_intro'      => 'Kurasi pemberitaan publik tentang Baitul Muslimin Indonesia dari sumber nasional.',
            'header_show_logo'  => 0,
            'header_show_intro' => 1,
            'header_show_back'  => 1,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->db->table('pages')->where('slug', 'berita')->delete();
    }
}

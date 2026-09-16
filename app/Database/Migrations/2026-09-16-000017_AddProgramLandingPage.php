<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProgramLandingPage extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('pages')) return;
        $pages = $this->db->table('pages');
        if ($pages->where('slug', 'program')->countAllResults() > 0) return;

        $pages->insert([
            'slug' => 'program',
            'title' => 'Program BAMUSI',
            'title_en' => 'BAMUSI Programs',
            'excerpt' => 'Ruang khidmah dan program BAMUSI untuk Indonesia.',
            'excerpt_en' => 'BAMUSI programs and service spaces for Indonesia.',
            'body' => null,
            'body_en' => null,
            'published' => 1,
            'show_in_menu' => 1,
            'is_mega' => 0,
            'menu_label' => 'Program',
            'menu_label_en' => 'Programs',
            'sort_order' => 30,
            'meta_title' => 'Program BAMUSI',
            'meta_description' => 'Daftar program dan ruang khidmah Baitul Muslimin Indonesia.',
            'header_kicker' => 'BAMUSI / PROGRAM',
            'header_title' => 'Ruang khidmah yang nyata.',
            'header_intro' => 'Jelajahi seluruh program BAMUSI berdasarkan bidang dan kebutuhan.',
            'header_show_logo' => 0,
            'header_show_intro' => 1,
            'header_show_back' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        if ($this->db->tableExists('pages')) $this->db->table('pages')->where('slug', 'program')->delete();
    }
}

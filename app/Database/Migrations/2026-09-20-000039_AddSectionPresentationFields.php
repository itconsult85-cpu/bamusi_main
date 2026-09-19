<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectionPresentationFields extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) {
            return;
        }

        $fields = [
            'kicker_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'null' => false, 'default' => '#e7aa6b', 'after' => 'quote_en'],
            'title_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'null' => false, 'default' => '#ffffff', 'after' => 'kicker_color'],
            'lead_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'null' => false, 'default' => '#d7e8dd', 'after' => 'title_color'],
            'quote_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'null' => false, 'default' => '#e7aa6b', 'after' => 'lead_color'],
        ];
        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'page_sections')) {
                $this->forge->addColumn('page_sections', [$name => $definition]);
            }
        }

        // Urutan aktual homepage: Hero, About, Nilai, Visi, Sejarah, Pengurus,
        // Program, Agenda, Berita, Tulisan, Sosial, Mitra, Magang, Bergabung, Kontak.
        $order = [
            'hero' => 10, 'about' => 20, 'nilai' => 30, 'visi' => 40,
            'history' => 50, 'board' => 60, 'program' => 70, 'agenda' => 80,
            'news' => 90, 'writing' => 100, 'social' => 110, 'partners' => 120,
            'internship' => 130, 'join' => 140, 'contact' => 150, 'feature' => 160,
            'gallery' => 170,
        ];
        foreach ($order as $key => $sort) {
            $this->db->table('page_sections')->where('section_key', $key)->update(['sort_order' => $sort]);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('page_sections')) {
            return;
        }
        foreach (['quote_color', 'lead_color', 'title_color', 'kicker_color'] as $field) {
            if ($this->db->fieldExists($field, 'page_sections')) {
                $this->forge->dropColumn('page_sections', $field);
            }
        }
    }
}

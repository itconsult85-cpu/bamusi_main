<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateHeroSlideToPageSection extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections') || !$this->db->tableExists('hero_slides')) {
            return;
        }

        $section = $this->db->table('page_sections')
            ->where('section_key', 'hero')
            ->get()
            ->getRowArray();
        $slide = $this->db->table('hero_slides')
            ->where('published', 1)
            ->orderBy('sort_order', 'ASC')
            ->get(1)
            ->getRowArray();

        if (!$section || !$slide) {
            return;
        }

        $copy = [];
        foreach ([
            'kicker', 'kicker_en', 'title', 'title_en', 'quote', 'quote_en',
            'button_label', 'button_label_en', 'button_url',
        ] as $field) {
            if (trim((string) ($section[$field] ?? '')) === '' && trim((string) ($slide[$field] ?? '')) !== '') {
                $copy[$field] = $slide[$field];
            }
        }
        if (trim((string) ($section['subtitle'] ?? '')) === '' && trim((string) ($slide['lead'] ?? '')) !== '') {
            $copy['subtitle'] = $slide['lead'];
        }
        if (trim((string) ($section['subtitle_en'] ?? '')) === '' && trim((string) ($slide['lead_en'] ?? '')) !== '') {
            $copy['subtitle_en'] = $slide['lead_en'];
        }
        if (trim((string) ($section['media_url'] ?? '')) === '' && trim((string) ($slide['image_url'] ?? '')) !== '') {
            $copy['media_url'] = $slide['image_url'];
        }

        if ($copy) {
            $this->db->table('page_sections')->where('id', $section['id'])->update($copy);
        }
    }

    public function down()
    {
        // Data hasil migrasi tidak dihapus agar rollback tidak merusak input CMS.
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateHomepageToBlocks extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections') || !$this->db->tableExists('homepage_section_blocks')) {
            return;
        }

        $sections = $this->db->table('page_sections')->orderBy('sort_order', 'ASC')->get()->getResultArray();
        $blocks = $this->db->table('homepage_section_blocks');
        $now = date('Y-m-d H:i:s');

        foreach ($sections as $section) {
            if ($blocks->where('section_id', $section['id'])->countAllResults() > 0) {
                continue;
            }

            $key = strtolower(trim((string) ($section['section_key'] ?? '')));
            $name = strtolower(trim((string) ($section['section_name'] ?? '')));
            $template = $key;
            $known = [
                'hero banner' => 'hero', 'tentang bamusi' => 'about',
                'lima nilai utama' => 'nilai', 'visi dan misi' => 'visi',
                'sejarah bamusi' => 'history', 'pengurus bamusi' => 'board',
                'mitra homepage' => 'partners',
            ];
            foreach ($known as $label => $knownKey) {
                if ($name === $label || str_contains($name, $label)) {
                    $template = $knownKey;
                    break;
                }
            }

            $type = 'rich_text';
            $data = ['source' => 'section'];
            switch ($template) {
                case 'hero': $type = 'hero_slider'; break;
                case 'about': $type = 'image_text'; break;
                case 'nilai': $type = 'cards'; $data['source'] = 'about_values'; $data['variant'] = 'dark'; $data['columns'] = 5; $data['limit'] = 5; break;
                case 'board': $type = 'cards'; $data['source'] = 'board'; $data['columns'] = 4; $data['limit'] = 12; break;
                case 'program': case 'feature': $type = 'cards'; $data['source'] = 'program'; $data['columns'] = 3; $data['limit'] = 12; break;
                case 'agenda': $type = 'cards'; $data['source'] = 'agenda'; $data['columns'] = 4; $data['limit'] = 4; break;
                case 'news': case 'writing': $type = 'collection'; $data['source'] = 'news'; $data['columns'] = 4; $data['limit'] = 4; break;
                case 'social': $type = 'cards'; $data['source'] = 'social'; $data['columns'] = 3; $data['limit'] = 12; break;
                case 'partners': $type = 'logo_grid'; $data['source'] = 'partners'; $data['columns'] = 5; $data['limit'] = 24; break;
                case 'join': case 'internship': $type = 'join_form'; break;
            }

            $blocks->insert([
                'section_id' => $section['id'],
                'section_key' => $section['section_key'],
                'block_type' => $type,
                'block_data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'block_data_en' => json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'sort_order' => 0,
                // Publisher section dikontrol oleh page_sections; block layout
                // tetap aktif agar tidak hilang saat section dinyalakan lagi.
                'published' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $this->db->table('page_sections')->where('id', $section['id'])->update(['layout_mode' => 'builder']);
        }
    }

    public function down()
    {
        // Block data is canonical after migration and must not be deleted automatically.
    }
}

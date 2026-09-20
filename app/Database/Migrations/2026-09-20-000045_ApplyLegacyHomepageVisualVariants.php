<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ApplyLegacyHomepageVisualVariants extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections') || !$this->db->tableExists('homepage_section_blocks')) {
            return;
        }

        $variants = [
            'hero' => ['hero_slider', 'hero_split'],
            'about' => ['image_text', 'about_sidebar'],
            'nilai' => ['cards', 'nilai_cards'],
            'visi' => ['image_text', 'visi_split'],
            'history' => ['rich_text', 'history_editorial'],
            'board' => ['cards', 'board_cards'],
            'program' => ['cards', 'program_cards'],
            'feature' => ['cards', 'about_feature'],
            'agenda' => ['cards', 'agenda_cards'],
            'news' => ['collection', 'news_cards'],
            'writing' => ['collection', 'writing_cards'],
            'social' => ['cards', 'social_cards'],
            'partners' => ['logo_grid', 'partners_grid'],
            'join' => ['join_form', 'join_form'],
            'internship' => ['join_form', 'join_form'],
        ];

        foreach ($this->db->table('page_sections')->get()->getResultArray() as $section) {
            $key = strtolower(trim((string) ($section['section_key'] ?? '')));
            $name = strtolower(trim((string) ($section['section_name'] ?? '')));
            $templateKey = $key;
            foreach (['hero banner' => 'hero', 'lima nilai utama' => 'nilai', 'visi dan misi' => 'visi', 'sejarah bamusi' => 'history', 'pengurus bamusi' => 'board', 'mitra homepage' => 'partners'] as $label => $knownKey) {
                if ($name === $label || str_contains($name, $label)) {
                    $templateKey = $knownKey;
                    break;
                }
            }
            if (!isset($variants[$templateKey])) {
                continue;
            }

            [$blockType, $variant] = $variants[$templateKey];
            $block = $this->db->table('homepage_section_blocks')->where('section_id', $section['id'])->orderBy('sort_order', 'ASC')->get()->getRowArray();
            if (!$block) {
                continue;
            }
            $data = json_decode((string) ($block['block_data'] ?? ''), true);
            $data = is_array($data) ? $data : [];
            $data['template_variant'] = $variant;
            if ($templateKey === 'about') {
                $data['source'] = 'about_links';
            } elseif ($templateKey === 'hero') {
                $data['source'] = 'section';
            } elseif (in_array($templateKey, ['join', 'internship'], true)) {
                $data['source'] = 'join_interest';
            }
            $this->db->table('homepage_section_blocks')->where('id', $block['id'])->update([
                'section_key' => $section['section_key'],
                'block_type' => $blockType,
                'block_data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'published' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function down()
    {
        // Variant data tidak dihapus karena block tetap valid sebagai konfigurasi homepage.
    }
}

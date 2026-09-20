<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RepairWritingHomepageBlockSource extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections') || !$this->db->tableExists('homepage_section_blocks')) {
            return;
        }

        $sections = [];
        foreach ($this->db->table('page_sections')->get()->getResultArray() as $section) {
            $key = strtolower(trim((string) ($section['section_key'] ?? '')));
            $renderKey = strtolower(trim((string) ($section['render_key'] ?? '')));
            $name = strtolower(trim((string) ($section['section_name'] ?? '')));
            if ($key === 'writing' || $renderKey === 'writing' || $name === 'writing') {
                $sections[] = $section;
            }
        }

        foreach ($sections as $section) {
            $blocks = $this->db->table('homepage_section_blocks')
                ->where('section_id', $section['id'])
                ->get()->getResultArray();

            foreach ($blocks as $block) {
                $data = json_decode((string) ($block['block_data'] ?? ''), true);
                if (!is_array($data) || ($data['source'] ?? null) !== 'news') {
                    continue;
                }

                $data['source'] = 'article';
                $this->db->table('homepage_section_blocks')
                    ->where('id', $block['id'])
                    ->update(['block_data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
        }
    }

    public function down()
    {
        // Do not restore the incorrect news source automatically.
    }
}

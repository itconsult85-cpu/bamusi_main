<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveLegacyHomepageTexts extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('website_texts') || !$this->db->tableExists('page_sections')) {
            return;
        }

        $this->db->query("UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_title' SET ps.title = wt.value, ps.title_en = NULL WHERE ps.section_key = 'history' AND (ps.title IS NULL OR ps.title = '')");
        $this->db->query("UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_body' SET ps.subtitle = wt.value, ps.subtitle_en = NULL WHERE ps.section_key = 'history' AND (ps.subtitle IS NULL OR ps.subtitle = '')");
        $this->db->query("UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_link' SET ps.button_label = wt.value, ps.button_label_en = NULL WHERE ps.section_key = 'history' AND (ps.button_label IS NULL OR ps.button_label = '')");
        $this->db->query("UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_kicker' SET ps.kicker = wt.value, ps.kicker_en = NULL WHERE ps.section_key = 'history' AND (ps.kicker IS NULL OR ps.kicker = '')");

        $this->db->table('website_texts')
            ->groupStart()
                ->whereIn('text_key', [
                    'home.history_title', 'home.history_body', 'home.history_link',
                    'home.history_kicker',
                ])
                ->orLike('text_key', 'reference.hero_', 'after')
            ->groupEnd()
            ->delete();
    }

    public function down()
    {
        // Section fields are now the canonical source; legacy rows are not recreated.
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LinkWebsiteTextsToSections extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('website_texts')) {
            return;
        }

        if (!$this->db->fieldExists('section_key', 'website_texts')) {
            $this->forge->addColumn('website_texts', [
                'section_key' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 80,
                    'null'       => true,
                    'after'      => 'location',
                ],
            ]);
        }

        $indexes = $this->db->query('SHOW INDEX FROM `website_texts`')->getResultArray();
        $hasSectionIndex = false;
        foreach ($indexes as $index) {
            if (($index['Key_name'] ?? '') === 'section_published_sort_order') {
                $hasSectionIndex = true;
                break;
            }
        }
        if (!$hasSectionIndex) {
            $this->db->query('ALTER TABLE `website_texts` ADD INDEX `section_published_sort_order` (`section_key`, `published`, `sort_order`)');
        }

        $builder = $this->db->table('website_texts');
        $mapping = [
            'home.hero_'       => 'hero',
            'reference.hero_'  => 'hero',
            'reference.about_' => 'about',
            'home.about_'      => 'about',
            'home.board_'      => 'board',
            'home.member_'     => 'board',
            'home.agenda_'     => 'agenda',
            'home.gallery_'    => 'gallery',
            'home.social_'     => 'social',
            'home.instagram_'  => 'social',
            'home.tiktok_'     => 'social',
            'home.youtube_'    => 'social',
            'home.contact_'    => 'contact',
            'home.program_'    => 'program',
            'home.feature_'    => 'feature',
            'home.writing_'    => 'writing',
            'home.join_'       => 'join',
            'reference.join_'  => 'join',
            'home.internship_' => 'internship',
            'reference.khidmah_' => 'program',
            'home.history_'    => 'history',
            'home.rail_'       => 'navigation',
            'home.bar_'        => 'navigation',
            'home.vision_'     => 'visi',
            'home.mission_'    => 'visi',
        ];

        foreach ($mapping as $prefix => $sectionKey) {
            $builder->set('section_key', $sectionKey)
                ->like('text_key', $prefix, 'after')
                ->where('section_key IS NULL', null, false)
                ->update();
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('website_texts') || !$this->db->fieldExists('section_key', 'website_texts')) {
            return;
        }

        $this->db->query('ALTER TABLE `website_texts` DROP INDEX `section_published_sort_order`');
        $this->forge->dropColumn('website_texts', 'section_key');
    }
}

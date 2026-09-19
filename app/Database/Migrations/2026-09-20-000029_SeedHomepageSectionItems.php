<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedHomepageSectionItems extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('homepage_section_items')) return;
        $table = $this->db->table('homepage_section_items');
        $now = date('Y-m-d H:i:s');
        $seed = [
            ['feature', 'chairman-language', 'Bahasa Ketum', "Chairman's Language", 'Bahasa Ketum', "Chairman's Language", 'Gagasan kebangsaan dalam tutur yang dekat, reflektif, dan mudah dipahami.', 'National ideas in speech that are close, reflective, and easy to understand.', 10],
            ['feature', 'mega-dzikir', 'Mega Dzikir', 'Mega Dhikr', 'Mega Dzikir', 'Mega Dhikr', 'Majelis doa dan kebersamaan yang meneguhkan spiritualitas, persatuan, serta kepedulian sosial.', 'An assembly of prayer and togetherness that confirms spirituality and social care.', 20],
            ['feature', 'komunikasi-terbuka', 'Komunikasi Terbuka', 'Open Communication', 'Komunikasi Terbuka', 'Open Communication', 'Penyampaian sikap dan agenda publik secara langsung, bertanggung jawab, dan berbasis fakta.', 'Delivery of public attitudes and agendas directly, responsibly, and based on facts.', 30],
            ['social', 'instagram', 'Instagram', 'Instagram', '@baitul.muslimin.indonesia', '@baitul.muslimin.indonesia', null, null, 10],
            ['social', 'tiktok', 'TikTok', 'TikTok', '@baitulmusliminindonesia', '@baitulmusliminindonesia', null, null, 20],
            ['social', 'youtube', 'YouTube', 'YouTube', '@bamusitv', '@bamusitv', null, null, 30],
            ['join_interest', 'membership', 'Keanggotaan Umum', 'General Membership', 'Keanggotaan Umum', 'General Membership', null, null, 10],
            ['join_interest', 'volunteer', 'Relawan Program', 'Program Volunteer', 'Relawan Program', 'Program Volunteer', null, null, 20],
        ];
        foreach ($seed as $row) {
            if ($table->where(['section_key' => $row[0], 'item_key' => $row[1]])->countAllResults() > 0) continue;
            $table->insert([
                'section_key' => $row[0], 'item_key' => $row[1],
                'label' => $row[2], 'label_en' => $row[3],
                'title' => $row[4], 'title_en' => $row[5],
                'body' => $row[6], 'body_en' => $row[7],
                'sort_order' => $row[8], 'published' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down() {}
}

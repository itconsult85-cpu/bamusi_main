<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVisionMissionSectionFields extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) return;

        $definitions = [
            'vision' => ['type' => 'TEXT', 'null' => true, 'after' => 'content'],
            'vision_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'vision'],
            'mission' => ['type' => 'TEXT', 'null' => true, 'after' => 'vision_en'],
            'mission_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'mission'],
        ];
        $fields = [];
        foreach ($definitions as $name => $definition) {
            if (!$this->db->fieldExists($name, 'page_sections')) $fields[$name] = $definition;
        }
        if ($fields) $this->forge->addColumn('page_sections', $fields);

        $settings = $this->db->table('site_settings')
            ->whereIn('setting_key', ['about_vision', 'about_mission'])
            ->get()->getResultArray();
        $values = [];
        foreach ($settings as $setting) $values[$setting['setting_key']] = $setting['setting_value'];
        $section = $this->db->table('page_sections')->where('section_key', 'visi')->get()->getRowArray();
        if ($section) {
            $updates = [];
            if (empty($section['vision']) && !empty($values['about_vision'])) $updates['vision'] = $values['about_vision'];
            if (empty($section['mission']) && !empty($values['about_mission'])) $updates['mission'] = $values['about_mission'];
            if ($updates) $this->db->table('page_sections')->where('id', $section['id'])->update($updates);
        } else {
            $this->db->table('page_sections')->insert([
                'section_key' => 'visi',
                'section_name' => 'Visi dan Misi',
                'kicker' => 'VISI DAN MISI',
                'kicker_en' => 'VISION AND MISSION',
                'title' => 'Menjadi rumah kebangsaan Muslim Indonesia yang progresif.',
                'title_en' => 'Becoming the national home of progressive Indonesian Muslims.',
                'vision' => $values['about_vision'] ?? null,
                'mission' => $values['about_mission'] ?? null,
                'published' => 1,
                'sort_order' => 30,
            ]);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('page_sections')) return;
        foreach (['vision', 'vision_en', 'mission', 'mission_en'] as $field) {
            if ($this->db->fieldExists($field, 'page_sections')) $this->forge->dropColumn('page_sections', $field);
        }
    }
}

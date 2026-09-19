<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixNilaiHomepageCta extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) return;

        $builder = $this->db->table('page_sections');
        $section = $builder->where('section_key', 'nilai')->get()->getRowArray();
        if (!$section) return;

        $data = [];
        if (trim((string) ($section['button_label'] ?? '')) === '') {
            $data['button_label'] = 'Baca Selengkapnya ↗';
        }
        if (trim((string) ($section['button_label_en'] ?? '')) === '') {
            $data['button_label_en'] = 'Read More ↗';
        }
        if (trim((string) ($section['button_url'] ?? '')) === '') {
            $data['button_url'] = '/lima-nilai-utama';
        }

        if ($data) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $builder->where('id', $section['id'])->update($data);
        }
    }

    public function down()
    {
        // Tidak mengosongkan nilai yang mungkin telah disesuaikan melalui CMS.
    }
}

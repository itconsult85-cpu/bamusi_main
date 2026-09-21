<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFooterSettings extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('site_settings')) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $fields = [
            ['footer.heading', 'Kantor Pengurus<br>Pusat BAMUSI', 'Judul Footer', 'textarea'],
            ['footer.tagline', 'Islam Nusantara yang berkemajuan untuk Indonesia Raya.', 'Tagline Footer', 'textarea'],
            ['partner_name', 'PDI Perjuangan', 'Nama Mitra', 'text'],
            ['partner_url', 'https://pdiperjuangan.id/', 'URL Mitra', 'url'],
            ['contact_address_short', 'Jl. Kalibata Tengah, Kalibata, Kec. Pancoran,<br>Kota Jakarta Selatan, DKI Jakarta 12740', 'Alamat Singkat', 'textarea'],
            ['contact_maps_url', '', 'URL Google Maps', 'url'],
            ['contact_maps_label', 'Buka di Google Maps', 'Label Tautan Google Maps', 'text'],
            ['whatsapp_label', '+62 878 9262 7144', 'Nomor WhatsApp yang Ditampilkan', 'text'],
            ['whatsapp_url', '', 'URL WhatsApp', 'url'],
            ['instagram_url', '', 'URL Instagram', 'url'],
            ['tiktok_url', '', 'URL TikTok', 'url'],
            ['youtube_url', '', 'URL YouTube', 'url'],
            ['footer.copyright', 'Baitul Muslimin Indonesia. Hak cipta dilindungi.', 'Teks Copyright', 'text'],
        ];

        foreach ($fields as [$key, $value, $label, $type]) {
            $exists = $this->db->table('site_settings')->where('setting_key', $key)->countAllResults();
            if ($exists > 0) {
                continue;
            }
            $this->db->table('site_settings')->insert([
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_value_en' => $value,
                'label' => $label,
                'label_en' => $label,
                'location' => 'footer',
                'type' => $type,
                'sort_order' => 900,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        // Data ini dihapus hanya jika rollback migrasi diminta secara eksplisit.
        if ($this->db->tableExists('site_settings')) {
            $this->db->table('site_settings')->whereIn('setting_key', [
                'footer.heading', 'footer.tagline', 'partner_name', 'partner_url',
                'contact_address_short', 'contact_maps_url', 'contact_maps_label',
                'whatsapp_label', 'whatsapp_url', 'instagram_url', 'tiktok_url',
                'youtube_url', 'footer.copyright',
            ])->delete();
        }
    }
}

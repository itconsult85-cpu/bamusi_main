<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class FooterCMS extends BaseController
{
    private SiteSettingModel $model;

    /** @var array<string, array<string, string>> */
    private array $fields = [
        'footer.heading' => [
            'label' => 'Judul Footer',
            'type' => 'textarea',
            'group' => 'Identitas Footer',
            'help' => 'Mendukung HTML sederhana seperti <code>&lt;br&gt;</code> untuk pindah baris.',
        ],
        'footer.tagline' => [
            'label' => 'Tagline Footer',
            'type' => 'textarea',
            'group' => 'Identitas Footer',
        ],
        'partner_name' => [
            'label' => 'Nama Mitra',
            'type' => 'text',
            'group' => 'Identitas Footer',
        ],
        'partner_url' => [
            'label' => 'URL Mitra',
            'type' => 'url',
            'group' => 'Identitas Footer',
        ],
        'contact_address_short' => [
            'label' => 'Alamat Singkat',
            'type' => 'textarea',
            'group' => 'Kontak',
            'help' => 'Mendukung HTML sederhana seperti <code>&lt;br&gt;</code> untuk pindah baris.',
        ],
        'contact_maps_url' => [
            'label' => 'URL Google Maps',
            'type' => 'url',
            'group' => 'Kontak',
        ],
        'contact_maps_label' => [
            'label' => 'Label Tautan Google Maps',
            'type' => 'text',
            'group' => 'Kontak',
        ],
        'whatsapp_label' => [
            'label' => 'Nomor WhatsApp yang Ditampilkan',
            'type' => 'text',
            'group' => 'Kontak',
        ],
        'whatsapp_url' => [
            'label' => 'URL WhatsApp',
            'type' => 'url',
            'group' => 'Kontak',
            'help' => 'Contoh: <code>https://wa.me/628xxxxxxxxxx</code>.',
        ],
        'instagram_url' => [
            'label' => 'URL Instagram',
            'type' => 'url',
            'group' => 'Media Sosial',
        ],
        'tiktok_url' => [
            'label' => 'URL TikTok',
            'type' => 'url',
            'group' => 'Media Sosial',
        ],
        'youtube_url' => [
            'label' => 'URL YouTube',
            'type' => 'url',
            'group' => 'Media Sosial',
        ],
        'footer.copyright' => [
            'label' => 'Teks Copyright',
            'type' => 'text',
            'group' => 'Copyright',
        ],
    ];

    public function __construct()
    {
        $this->model = new SiteSettingModel();
    }

    public function index()
    {
        $this->ensureFields();
        $rows = $this->model->whereIn('setting_key', array_keys($this->fields))->findAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row;
        }

        return view('admin/footer/form', [
            'fields' => $this->fields,
            'settings' => $settings,
            'groups' => array_values(array_unique(array_column($this->fields, 'group'))),
        ]);
    }

    public function save()
    {
        $this->ensureFields();
        $posted = $this->request->getPost('footer');
        $posted = is_array($posted) ? $posted : [];

        foreach ($this->fields as $key => $definition) {
            $row = $this->model->where('setting_key', $key)->first();
            if (!$row) {
                continue;
            }

            $value = trim((string) ($posted[$key] ?? ''));
            $valueEn = $row['setting_value_en'] ?? null;
            if (in_array($definition['type'], ['text', 'textarea'], true)) {
                $valueEn = $this->translateText($value, $valueEn);
            }

            $this->model->update($row['id'], [
                'setting_value' => $value,
                'setting_value_en' => $valueEn,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/admin/footer')->with('success', 'Konten footer berhasil disimpan.');
    }

    private function ensureFields(): void
    {
        foreach ($this->fields as $key => $definition) {
            if ($this->model->where('setting_key', $key)->first()) {
                continue;
            }

            $this->model->insert([
                'setting_key' => $key,
                'setting_value' => '',
                'setting_value_en' => '',
                'label' => $definition['label'],
                'label_en' => $definition['label'],
                'location' => 'footer',
                'type' => $definition['type'],
                'sort_order' => 900,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}

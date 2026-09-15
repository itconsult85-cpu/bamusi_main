<?php

namespace App\Controllers;

use App\Models\PageModel;

class SitePage extends BaseController
{
    public function show($slug = null)
    {
        if (empty($slug)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $pageModel = new PageModel();

        // Ambil data halaman berdasarkan slug
        $page = $pageModel->where('slug', $slug)
            ->where('published', 1)
            ->first();

        if (!$page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();
        $locale = session()->get('lang') ?? 'id';

        // Pengaturan Global & Menu
        $settingsRaw = $db->table('site_settings')->get()->getResultArray();
        $settings = [];
        foreach ($settingsRaw as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
            if (!empty($row['setting_value_en'])) {
                $settings[$row['setting_key'] . '_en'] = $row['setting_value_en'];
            }
        }

        $navMenu = $pageModel->where('published', 1)
            ->where('show_in_menu', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('frontend/page/global', [
            'page'       => $page,
            'settings'   => $settings,
            'navMenu'    => $navMenu,
            'locale'     => $locale,
            'meta_title' => $page['meta_title'] ?: $page['title'],
            'meta_desc'  => $page['meta_description'] ?: $page['excerpt'],
        ]);
    }
}

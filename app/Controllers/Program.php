<?php

namespace App\Controllers;

use App\Models\ProgramModel;
use App\Models\PageModel;

class Program extends BaseController
{
    public function detail($slug)
    {
        $programModel = new ProgramModel();

        // Cari program yang aktif berdasarkan slug
        $program = $programModel->where('slug', $slug)
            ->where('published', 1)
            ->first();

        // Jika tidak ditemukan, kembalikan halaman 404
        if (!$program) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();
        $locale = session()->get('lang') ?? 'id';

        // Ambil pengaturan situs untuk layout frontend
        $settings = array_column(
            $db->table('site_settings')->get()->getResultArray(),
            'setting_value',
            'setting_key'
        );

        // Ambil menu navigasi untuk header
        $pageModel = new PageModel();
        $navMenu = $pageModel->where('published', 1)
            ->where('show_in_menu', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('frontend/program/detail', [
            'program'  => $program,
            'locale'   => $locale,
            'settings' => $settings,
            'navMenu'  => $navMenu
        ]);
    }
}

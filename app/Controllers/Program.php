<?php

namespace App\Controllers;

use App\Models\PageModel;
use App\Models\ProgramModel;

class Program extends BaseController
{
    protected $programModel;
    protected $pageModel;

    public function __construct()
    {
        $this->programModel = new ProgramModel();
        $this->pageModel = new PageModel();
    }

    public function index()
    {
        $locale = session()->get('lang') ?? 'id';
        $search = trim((string) $this->request->getGet('q'));
        $division = trim((string) $this->request->getGet('division'));

        $builder = $this->programModel
            ->where('published', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('name_en', $search)
                ->orLike('description', $search)
                ->orLike('division', $search)
                ->groupEnd();
        }
        if ($division !== '') $builder->where('division', $division);

        $programs = $builder->findAll();
        $divisions = $this->programModel
            ->select('division')
            ->where('published', 1)
            ->where('division !=', '')
            ->distinct()
            ->orderBy('division', 'ASC')
            ->findAll();

        $page = $this->pageModel->where('slug', 'program')->where('published', 1)->first() ?: [
            'title' => 'Program BAMUSI',
            'title_en' => 'BAMUSI Programs',
            'excerpt' => 'Ruang khidmah dan program BAMUSI untuk Indonesia.',
            'excerpt_en' => 'BAMUSI programs and service spaces for Indonesia.',
            'header_kicker' => 'BAMUSI / PROGRAM',
            'header_title' => 'Ruang khidmah yang nyata.',
            'header_intro' => 'Jelajahi seluruh program BAMUSI berdasarkan bidang dan kebutuhan.',
            'header_show_intro' => 1,
            'header_show_back' => 1,
        ];

        return view('frontend/program/index', [
            'page' => $page,
            'programs' => $programs,
            'divisions' => $divisions,
            'search' => $search,
            'division' => $division,
            'locale' => $locale,
            'settings' => $this->siteSettings(),
            'navMenu' => $this->navMenu(),
        ]);
    }

    public function detail($slug)
    {
        $program = $this->programModel->where('slug', $slug)->where('published', 1)->first();
        if (!$program) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('frontend/program/detail', [
            'program' => $program,
            'page' => $this->pageModel->where('slug', 'program')->first(),
            'locale' => session()->get('lang') ?? 'id',
            'settings' => $this->siteSettings(),
            'navMenu' => $this->navMenu(),
        ]);
    }

    private function siteSettings(): array
    {
        $rows = \Config\Database::connect()->table('site_settings')->get()->getResultArray();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
            if (!empty($row['setting_value_en'])) $settings[$row['setting_key'] . '_en'] = $row['setting_value_en'];
        }
        return $settings;
    }

    private function navMenu(): array
    {
        return $this->pageModel->where('published', 1)->where('show_in_menu', 1)->orderBy('sort_order', 'ASC')->findAll();
    }
}

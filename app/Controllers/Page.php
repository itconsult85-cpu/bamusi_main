<?php

namespace App\Controllers;

use App\Models\PageModel;
use App\Models\PageBlockModel;
use App\Models\BoardMemberModel;
use App\Models\ProgramModel;

class Page extends BaseController
{
    protected $pageModel;
    protected $boardModel;
    protected $blockModel;

    public function __construct()
    {
        $this->pageModel  = new PageModel();
        $this->boardModel = new BoardMemberModel();
        $this->blockModel = new PageBlockModel();
    }

    /**
     * Menampilkan halaman statis berdasarkan slug.
     * URL: /{slug}   (misal /sejarah, /struktur-pengurus)
     */
    public function show($slug = null)
    {
        if (empty($slug)) {
            return view('frontend/404');
        }

        $page = $this->pageModel
            ->where('slug', $slug)
            ->where('published', 1)
            ->first();

        if (!$page) {
            return view('frontend/404');
        }

        $db = \Config\Database::connect();

        // Data umum (settings + menu)
        $settingsRaw = $db->table('site_settings')->get()->getResultArray();
        $settings = [];
        foreach ($settingsRaw as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
            if (!empty($row['setting_value_en'])) {
                $settings[$row['setting_key'] . '_en'] = $row['setting_value_en'];
            }
        }

        // Menu navigasi (pages yang show_in_menu = 1)
        $navMenu = $this->pageModel
            ->where('published', 1)
            ->where('show_in_menu', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        if (session()->get('lang') === 'en') {
            $page = $this->ensureEnglishContent($page);
        }

        $data = [
            'page'     => $page,
            'blocks'   => $this->getBlocks($page['id']),
            'settings' => $settings,
            'navMenu'  => $navMenu,
            'locale'   => session()->get('lang') ?? 'id',
        ];

        // Khusus halaman struktur-pengurus → ambil board members
        if ($slug === 'struktur-pengurus') {
            $rows = $this->boardModel
                ->where('published', 1)
                ->orderBy('group_order', 'ASC')
                ->orderBy('member_order', 'ASC')
                ->orderBy('sort_order', 'ASC')
                ->findAll();

            // Kelompokkan
            $groups = [];
            foreach ($rows as $row) {
                $key = $row['group_name'] ?: 'Lainnya';
                if (!isset($groups[$key])) {
                    $groups[$key] = [
                        'name'    => $key,
                        'name_en' => $row['group_name_en'] ?: $key,
                        'order'   => (int)($row['group_order'] ?? 999),
                        'members' => [],
                    ];
                }
                $groups[$key]['members'][] = $row;
            }
            // Satu group CMS dapat berisi kepala dan wakil sekaligus.
            // Pecah menjadi dua section visual tanpa mengubah data database.
            $displayGroups = [];
            foreach ($groups as $group) {
                $mainMembers = [];
                $deputyMembers = [];
                foreach ($group['members'] as $member) {
                    $role = strtolower(trim((string)($member['role'] ?? '')));
                    if (str_starts_with($role, 'wakil')) $deputyMembers[] = $member;
                    else $mainMembers[] = $member;
                }

                if ($mainMembers && $deputyMembers) {
                    $group['members'] = $mainMembers;
                    $displayGroups[] = $group;

                    $deputyGroup = $group;
                    $deputyGroup['name'] = 'Wakil ' . $group['name'];
                    $deputyGroup['name_en'] = 'Deputy ' . $group['name_en'];
                    $deputyGroup['members'] = $deputyMembers;
                    $deputyGroup['order'] = (float)$group['order'] + 0.1;
                    $displayGroups[] = $deputyGroup;
                } else {
                    $displayGroups[] = $group;
                }
            }
            $groups = $displayGroups;
            usort($groups, fn($a, $b) => $a['order'] <=> $b['order']);
            foreach ($groups as &$group) {
                usort($group['members'], fn($a, $b) => ((int)($a['member_order'] ?? 999)) <=> ((int)($b['member_order'] ?? 999)));
            }
            unset($group);

            $data['groups'] = $groups;
            return view('frontend/page_struktur', $data);
        }

        // Halaman statis biasa
        return view('frontend/page', $data);
    }

    private function getBlocks(int $pageId): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('page_blocks')) return [];
        $blocks = $this->blockModel->where('page_id', $pageId)->where('published', 1)->orderBy('sort_order', 'ASC')->findAll();
        foreach ($blocks as &$block) {
            $block['data'] = json_decode($block['block_data'], true) ?: [];
            $block['data_en'] = !empty($block['block_data_en'])
                ? (json_decode($block['block_data_en'], true) ?: [])
                : [];
            if ($block['block_type'] === 'program_list') {
                $block['data']['programs'] = (new ProgramModel())
                    ->where('published', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
            }
        }
        unset($block);
        return $blocks;
    }

    private function ensureEnglishContent(array $page): array
    {
        $pageData = [];
        foreach ([
            'title' => 'title_en', 'excerpt' => 'excerpt_en', 'body' => 'body_en',
            'menu_label' => 'menu_label_en', 'menu_desc' => 'menu_desc_en',
            'meta_title' => 'meta_title_en', 'meta_description' => 'meta_description_en',
            'header_kicker' => 'header_kicker_en', 'header_title' => 'header_title_en', 'header_intro' => 'header_intro_en',
        ] as $source => $target) {
            if (!empty($page[$source]) && empty($page[$target])) {
                $translated = $this->translateText($page[$source], null);
                if ($translated !== null) {
                    $pageData[$target] = $translated;
                    $page[$target] = $translated;
                }
            }
        }
        if ($pageData) $this->pageModel->update($page['id'], $pageData);

        $db = \Config\Database::connect();
        if (!$db->tableExists('page_blocks')) return $page;
        $blocks = $this->blockModel->where('page_id', $page['id'])->findAll();
        foreach ($blocks as $block) {
            if (!empty($block['block_data_en'])) continue;
            $data = json_decode($block['block_data'], true) ?: [];
            $translatedData = [];
            $fields = match ($block['block_type']) {
                'rich_text' => ['html'], 'program_list' => ['title'], 'cards' => ['title', 'items'],
                'quote' => ['text', 'author'], 'cta' => ['text', 'label'], 'image' => ['alt', 'caption'], default => [],
            };
            foreach ($fields as $field) {
                if (array_key_exists($field, $data)) {
                    $translated = $this->translateText((string) $data[$field], null);
                    if ($translated !== null) $translatedData[$field] = $translated;
                }
            }
            if ($translatedData) {
                $this->blockModel->update($block['id'], ['block_data_en' => json_encode($translatedData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
        }
        return $page;
    }

    /**
     * Listing semua halaman statis (opsional).
     * URL: /halaman
     */
    public function index()
    {
        $pages = $this->pageModel
            ->where('published', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        $db = \Config\Database::connect();
        $settingsRaw = $db->table('site_settings')->get()->getResultArray();
        $settings = [];
        foreach ($settingsRaw as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
            if (!empty($row['setting_value_en'])) {
                $settings[$row['setting_key'] . '_en'] = $row['setting_value_en'];
            }
        }

        return view('frontend/page_list', [
            'pages'    => $pages,
            'settings' => $settings,
            'locale'   => session()->get('lang') ?? 'id',
        ]);
    }

}

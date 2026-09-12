<?php

namespace App\Controllers;

use App\Models\PageModel;
use App\Models\BoardMemberModel;

class Page extends BaseController
{
    protected $pageModel;
    protected $boardModel;

    public function __construct()
    {
        $this->pageModel  = new PageModel();
        $this->boardModel = new BoardMemberModel();
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

        $data = [
            'page'     => $page,
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

    protected function buildMenu()
    {
        $db = \Config\Database::connect();
        $all = $db->table('pages')
            ->where('published', 1)
            ->where('show_in_menu', 1)
            ->orderBy('sort_order', 'ASC')
            ->get()->getResultArray();

        $parents = [];
        $children = [];

        foreach ($all as $row) {
            if (empty($row['parent_id'])) {
                $parents[$row['id']] = $row;
                $parents[$row['id']]['children'] = [];
            } else {
                $children[$row['parent_id']][] = $row;
            }
        }
        foreach ($children as $parentId => $kids) {
            if (isset($parents[$parentId])) {
                $parents[$parentId]['children'] = $kids;
            }
        }
        return array_values($parents);
    }
}

<?php

namespace App\Controllers;

use App\Models\CmsItemModel;
use App\Models\AboutValueModel;
use App\Models\SiteSettingModel;
use App\Models\PageSectionModel;
use App\Models\HeroSlideModel;
use App\Models\SectionLinkModel;

class Home extends BaseController
{
    protected $cmsModel;
    protected $aboutModel;
    protected $settingModel;
    protected $sectionModel;

    public function __construct()
    {
        $this->cmsModel     = new CmsItemModel();
        $this->aboutModel   = new AboutValueModel();
        $this->settingModel = new SiteSettingModel();
        $this->sectionModel = new PageSectionModel();

        // Muat helper bahasa agar fungsi get_text() aktif
        helper('lang');
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $locale = session()->get('lang') ?? 'id';

        // 1. Ambil Site Settings
        $settingsData = $db->table('site_settings')->get()->getResultArray();
        $settings = array_column($settingsData, 'setting_value', 'setting_key');

        // 2. Ambil Sections
        $sectionsData = $this->sectionModel->where('published', 1)->orderBy('sort_order', 'ASC')->findAll();
        $sections = [];
        foreach ($sectionsData as $sec) {
            $sections[$sec['section_key']] = $sec;
        }

        // 3. RSS Google News (tidak berubah)
        $rssUrl = $settings['news_rss_url'] ?? 'https://news.google.com/rss/search?q=BAMUSI%20Baitul%20Muslimin%20Indonesia&hl=id&gl=ID&ceid=ID:id';
        $newsFeed = [];
        $rssContent = @file_get_contents($rssUrl);
        if ($rssContent !== false) {
            $xml = @simplexml_load_string($rssContent);
            if ($xml && isset($xml->channel->item)) {
                $count = 0;
                foreach ($xml->channel->item as $item) {
                    if ($count >= 4) break;
                    $fullTitle = (string) $item->title;
                    $titleParts = explode(' - ', $fullTitle);
                    $sourceFallback = count($titleParts) > 1 ? array_pop($titleParts) : 'Berita Nasional';
                    $cleanTitle = implode(' - ', $titleParts);
                    $source = (string) $item->source;
                    $newsFeed[] = [
                        'title'      => !empty($cleanTitle) ? $cleanTitle : $fullTitle,
                        'url'        => (string) $item->link,
                        'event_date' => date('d M Y', strtotime((string) $item->pubDate)),
                        'source'     => !empty($source) ? $source : $sourceFallback,
                        'category'   => 'Nasional',
                        'image_url'  => null
                    ];
                    $count++;
                }
            }
        }

        // 4. Susun $data — 'heroSlides' MASUK KE DALAM ARRAY
        $data = [
            'locale'      => $locale,
            'settings'    => $settings,
            'sections'    => $sections,
            'heroSlides'  => (new HeroSlideModel())          // ← ✅ DI SINI
                ->where('published', 1)
                ->orderBy('sort_order', 'ASC')
                ->findAll(),
            'aboutValues' => $db->table('about_values')->where('published', 1)->orderBy('sort_order', 'ASC')->get()->getResultArray(),
            'agenda'      => $db->table('cms_items')->where('kind', 'agenda')->where('published', 1)->orderBy('created_at', 'DESC')->limit(4)->get()->getResultArray(),
            'writingArticles' => $db->table('cms_items')->where('kind', 'article')->where('published', 1)->orderBy('created_at', 'DESC')->limit(4)->get()->getResultArray(),
            'programs'    => $db->table('programs')->where('published', 1)->get()->getResultArray(),
            'partners'    => $db->table('partners')->where('published', 1)->orderBy('sort_order', 'ASC')->get()->getResultArray(),
            'board'       => $db->table('board_members')->where('published', 1)->orderBy('sort_order', 'ASC')->limit(8)->get()->getResultArray(),
            'news'        => $newsFeed,
        ];

        $linkModel = new SectionLinkModel();
        $allLinks = $linkModel
            ->where('published', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        $sectionLinks = [];
        foreach ($allLinks as $row) {
            $sectionLinks[$row['section_key']][] = $row;
        }

        $data['sectionLinks'] = $sectionLinks;

        return view('frontend/home', $data);
    }

    public function struktur()
    {
        $db = \Config\Database::connect();

        $rows = $db->table('board_members')
            ->where('published', 1)
            ->orderBy('group_order', 'ASC')
            ->orderBy('member_order', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->get()->getResultArray();

        // Kelompokkan berdasarkan group_name
        $groups = [];
        foreach ($rows as $row) {
            $groupKey = $row['group_name'] ?: 'Lainnya';

            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [
                    'name'    => $groupKey,
                    'name_en' => $row['group_name_en'] ?: $groupKey,
                    'order'   => (int)($row['group_order'] ?? 999),
                    'members' => [],
                ];
            }
            $groups[$groupKey]['members'][] = $row;
        }

        // Urutkan grup
        usort($groups, fn($a, $b) => $a['order'] <=> $b['order']);

        $data = [
            'groups'   => $groups,
            'locale'   => session()->get('lang') ?? 'id',
            'settings' => array_column(
                $db->table('site_settings')->get()->getResultArray(),
                'setting_value',
                'setting_key'
            ),
        ];

        return view('frontend/pengurus', $data);
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

    private function getHeroSlides()
    {
        return (new HeroSlideModel())
            ->where('published', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}

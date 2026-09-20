<?php

namespace App\Controllers;

use App\Models\CmsItemModel;
use App\Models\AboutValueModel;
use App\Models\SiteSettingModel;
use App\Models\PageSectionModel;
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
        $settingsRaw = $db->table('site_settings')->get()->getResultArray();
        $settings = [];
        foreach ($settingsRaw as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
            if (!empty($row['setting_value_en'])) {
                $settings[$row['setting_key'] . '_en'] = $row['setting_value_en'];
            }
        }

        // 2. Ambil Sections
        // Hanya section yang dipublikasikan yang dikirim ke frontend. Section
        // draft tetap tersimpan dan dapat diedit dari CMS, tetapi tidak boleh
        // membuat markup homepage maupun alias render-nya muncul kembali.
        $sectionsData = $this->sectionModel->where('published', 1)->orderBy('sort_order', 'ASC')->findAll();
        $sections = $sectionsData;

        $homepageItems = [];
        if ($db->tableExists('homepage_section_items')) {
            foreach ($db->table('homepage_section_items')->where('published', 1)->orderBy('sort_order', 'ASC')->get()->getResultArray() as $item) {
                $homepageItems[$item['section_key']][] = $item;
            }
        }
        $itemOptions = static function (array $item): array {
            $options = json_decode((string) ($item['options_json'] ?? ''), true);
            return is_array($options) ? $options : [];
        };
        $agenda = array_map(static function (array $item) use ($itemOptions): array {
            $options = $itemOptions($item);
            return array_merge($item, ['title' => $item['title'] ?: $item['label'], 'title_en' => $item['title_en'] ?: $item['label_en'], 'event_date' => $options['event_date'] ?? '', 'slug' => $options['slug'] ?? $item['item_key']]);
        }, $homepageItems['agenda'] ?? []);
        $writingArticles = array_map(static function (array $item) use ($itemOptions): array {
            $options = $itemOptions($item);
            return array_merge($item, ['id' => $item['id'], 'title' => $item['title'] ?: $item['label'], 'title_en' => $item['title_en'] ?: $item['label_en'], 'image_url' => $item['media_url'], 'category' => $options['category'] ?? '']);
        }, $homepageItems['writing'] ?? []);
        $programItems = array_merge($homepageItems['program'] ?? [], $homepageItems['feature'] ?? []);
        usort($programItems, static fn (array $a, array $b): int => ((int) ($a['sort_order'] ?? 0)) <=> ((int) ($b['sort_order'] ?? 0)));
        $programs = array_map(static function (array $item) use ($itemOptions): array {
            $options = $itemOptions($item);
            return array_merge($item, ['name' => $item['title'] ?: $item['label'], 'name_en' => $item['title_en'] ?: $item['label_en'], 'description' => $item['body'], 'description_en' => $item['body_en'], 'slug' => $options['slug'] ?? $item['item_key']]);
        }, $programItems);
        $board = array_map(static function (array $item) use ($itemOptions): array {
            $options = $itemOptions($item);
            return array_merge($item, ['name' => $item['title'] ?: $item['label'], 'name_en' => $item['title_en'] ?: $item['label_en'], 'role' => $item['body'], 'role_en' => $item['body_en'], 'photo_url' => $item['media_url'], 'group_order' => (int) ($options['group_order'] ?? 0), 'member_order' => (int) ($options['member_order'] ?? $item['sort_order'])]);
        }, $homepageItems['board'] ?? []);
        $aboutValues = [];
        if ($db->tableExists('about_values')) {
            $aboutValues = $this->aboutModel->where('published', 1)->orderBy('sort_order', 'ASC')->findAll();
        }
        if (empty($aboutValues)) {
            $aboutValues = array_map(static function (array $item): array {
                return ['label' => $item['title'] ?: $item['label'], 'label_en' => $item['title_en'] ?: $item['label_en'], 'description' => $item['body'], 'description_en' => $item['body_en'], 'sort_order' => $item['sort_order'], 'published' => $item['published']];
            }, $homepageItems['nilai'] ?? []);
        }

        // Homepage menggunakan block sebagai satu-satunya jalur render.
        // Section baru tanpa block mendapat satu rich_text block virtual agar
        // tetap tampil berdasarkan data section terbaru dari database.
        $builderSections = [];
        if ($db->tableExists('homepage_section_blocks') && $db->fieldExists('layout_mode', 'page_sections')) {
            $blockRows = $db->table('homepage_section_blocks')
                ->where('published', 1)
                ->orderBy('section_id', 'ASC')
                ->orderBy('sort_order', 'ASC')
                ->get()->getResultArray();
            $blocksBySection = [];
            foreach ($blockRows as $block) {
                $block['data'] = json_decode($block['block_data'] ?? '', true) ?: [];
                $block['data_en'] = json_decode($block['block_data_en'] ?? '', true) ?: [];
                $blocksBySection[$block['section_id']][] = $block;
            }
            foreach ($sectionsData as $section) {
                $section['blocks'] = $blocksBySection[$section['id']] ?? [[
                    'id' => 'virtual-' . $section['id'],
                    'block_type' => 'rich_text',
                    'block_data' => json_encode(['source' => 'section']),
                    'block_data_en' => json_encode([]),
                    'data' => ['source' => 'section'],
                    'data_en' => [],
                    'published' => 1,
                    'sort_order' => 0,
                ]];
                $builderSections[] = $section;
            }
        }
        $builderMode = !empty($builderSections);
        // Mitra homepage sepenuhnya dikelola sebagai item pada Menu Section Mitra.
        // Data dari tabel partners tidak lagi menjadi fallback tersembunyi.
        $homepagePartners = array_map(static function (array $item): array {
            return [
                'name' => $item['title'] ?: $item['label'],
                'name_en' => $item['title_en'] ?: $item['label_en'],
                'website_url' => $item['url'],
                'logo_url' => $item['media_url'],
                'published' => $item['published'],
                'sort_order' => $item['sort_order'],
            ];
        }, $homepageItems['partners'] ?? []);

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

        // 4. Susun data untuk renderer block database.
        $data = [
            'locale'      => $locale,
            'settings'    => $settings,
            'sections'    => $sections,
            'sectionsData' => $sectionsData,
            'homepageItems' => $homepageItems,
            'builderSections' => $builderSections,
            'builderMode' => $builderMode,
            'aboutValues' => $aboutValues,
            'agenda'      => array_slice($agenda, 0, 4),
            'writingArticles' => array_slice($writingArticles, 0, 4),
            'programs'    => $programs,
            'partners'    => $homepagePartners,
            'board'       => $board,
            'news'        => $newsFeed,
        ];

        $sectionLinks = [];
        foreach ($homepageItems['about_links'] ?? [] as $item) {
            $options = $itemOptions($item);
            $sectionLinks['about'][] = [
                'label' => $item['title'] ?: $item['label'],
                'label_en' => $item['title_en'] ?: $item['label_en'],
                'sublabel' => $item['body'],
                'sublabel_en' => $item['body_en'],
                'url' => $item['url'],
                'sort_order' => $item['sort_order'],
                'published' => $item['published'],
                'options' => $options,
            ];
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

}

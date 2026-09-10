<?php

namespace App\Controllers;

use App\Models\CmsItemModel;
use App\Models\AboutValueModel;
use App\Models\SiteSettingModel;

class Home extends BaseController
{
    protected $cmsModel;
    protected $aboutModel;
    protected $settingModel;

    public function __construct()
    {
        $this->cmsModel = new CmsItemModel();
        $this->aboutModel = new AboutValueModel();
        $this->settingModel = new SiteSettingModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Ambil Site Settings
        $settingsData = $db->table('site_settings')->get()->getResultArray();
        $settings = array_column($settingsData, 'setting_value', 'setting_key');

        // 2. Ambil Website Texts
        $textsData = $db->table('website_texts')->where('published', 1)->get()->getResultArray();
        $texts = array_column($textsData, 'value', 'text_key');

        // 3. LOGIKA PARSER RSS GOOGLE NEWS
        $rssUrl = $settings['news_rss_url'] ?? 'https://news.google.com/rss/search?q=BAMUSI%20Baitul%20Muslimin%20Indonesia&hl=id&gl=ID&ceid=ID:id';
        $newsFeed = [];

        // Membaca konten RSS (Gunakan @ untuk menyembunyikan warning jika gagal memuat)
        $rssContent = @file_get_contents($rssUrl);

        if ($rssContent !== false) {
            $xml = @simplexml_load_string($rssContent);

            if ($xml && isset($xml->channel->item)) {
                $count = 0;
                foreach ($xml->channel->item as $item) {
                    if ($count >= 4) break; // Batasi 4 berita

                    // Ekstrak nama sumber media dari judul bawaan Google News
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
                        'image_url'  => null // Mengandalkan placeholder di frontend
                    ];
                    $count++;
                }
            }
        }

        // 4. Susun data untuk dikirim ke View (Nama disesuaikan persis dengan home.php)
        $data = [
            'settings'    => $settings,
            'texts'       => $texts,
            'slides'      => $db->table('hero_slides')->where('published', 1)->orderBy('sort_order', 'ASC')->get()->getResultArray(),
            'aboutValues' => $db->table('about_values')->where('published', 1)->orderBy('sort_order', 'ASC')->get()->getResultArray(),
            'agenda'      => $db->table('cms_items')->where('kind', 'agenda')->where('published', 1)->orderBy('created_at', 'DESC')->limit(4)->get()->getResultArray(),
            'programs'    => $db->table('programs')->where('published', 1)->get()->getResultArray(),
            'partners'    => $db->table('partners')->where('published', 1)->orderBy('sort_order', 'ASC')->get()->getResultArray(),
            'board'       => $db->table('board_members')->where('published', 1)->orderBy('sort_order', 'ASC')->limit(8)->get()->getResultArray(),
            'news'        => $newsFeed, // Menggunakan hasil parse RSS, bukan database
        ];

        return view('frontend/home', $data);
    }
}

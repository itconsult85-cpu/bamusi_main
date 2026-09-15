<?php

namespace App\Controllers;

use App\Models\CmsItemModel;
use App\Models\PageModel;

class News extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $locale = session()->get('lang') ?? 'id';
        $settings = array_column(
            $db->table('site_settings')->get()->getResultArray(),
            'setting_value',
            'setting_key'
        );

        $items = $this->getRssItems($settings['news_rss_url'] ?? null);
        $cmsItems = (new CmsItemModel())
            ->where('kind', 'news')
            ->where('published', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($cmsItems as $item) {
            $items[] = [
                'title'      => ($locale === 'en' && !empty($item['title_en'])) ? $item['title_en'] : $item['title'],
                'summary'    => ($locale === 'en' && !empty($item['summary_en'])) ? $item['summary_en'] : ($item['summary'] ?? ''),
                'url'        => $item['url'] ?: base_url('berita'),
                'event_date' => $item['created_at'] ? date('d M Y', strtotime($item['created_at'])) : '',
                'source'     => 'BAMUSI',
                'category'   => $item['category'] ?: 'BAMUSI',
                'image_url'  => $item['image_url'] ?? null,
            ];
        }

        $query = trim((string) $this->request->getGet('q'));
        $category = trim((string) $this->request->getGet('category'));
        $filtered = array_values(array_filter($items, static function (array $item) use ($query, $category): bool {
            $haystack = mb_strtolower(($item['title'] ?? '') . ' ' . ($item['summary'] ?? '') . ' ' . ($item['source'] ?? ''));
            $matchesQuery = $query === '' || mb_strpos($haystack, mb_strtolower($query)) !== false;
            $matchesCategory = $category === '' || strcasecmp((string) ($item['category'] ?? ''), $category) === 0;
            return $matchesQuery && $matchesCategory;
        }));

        $categories = [];
        foreach ($items as $item) {
            $value = trim((string) ($item['category'] ?? ''));
            if ($value !== '') $categories[$value] = $value;
        }
        natcasesort($categories);

        $perPage = 8;
        $total = count($filtered);
        $page = max(1, (int) $this->request->getGet('page'));
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $news = array_slice($filtered, ($page - 1) * $perPage, $perPage);

        return view('frontend/news/index', [
            'locale'     => $locale,
            'settings'   => $settings,
            'navMenu'    => (new PageModel())->where('published', 1)->where('show_in_menu', 1)->orderBy('sort_order', 'ASC')->findAll(),
            'news'       => $news,
            'categories' => array_values($categories),
            'query'      => $query,
            'category'   => $category,
            'page'       => $page,
            'totalPages' => $totalPages,
            'total'      => $total,
        ]);
    }

    private function getRssItems(?string $rssUrl): array
    {
        $rssUrl = $rssUrl ?: 'https://news.google.com/rss/search?q=BAMUSI%20Baitul%20Muslimin%20Indonesia&hl=id&gl=ID&ceid=ID:id';
        $context = stream_context_create(['http' => ['timeout' => 8, 'user_agent' => 'BAMUSI News/1.0']]);
        $content = @file_get_contents($rssUrl, false, $context);
        if ($content === false) return [];

        libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($content);
        if (!$xml || !isset($xml->channel->item)) return [];

        $items = [];
        foreach ($xml->channel->item as $item) {
            $fullTitle = trim((string) $item->title);
            $parts = explode(' - ', $fullTitle);
            $sourceFallback = count($parts) > 1 ? trim((string) array_pop($parts)) : 'Berita Nasional';
            $title = trim(implode(' - ', $parts));
            $source = trim((string) $item->source) ?: $sourceFallback;
            $items[] = [
                'title'      => $title ?: $fullTitle,
                'summary'    => trim(strip_tags((string) ($item->description ?? ''))),
                'url'        => trim((string) $item->link),
                'event_date' => ($date = strtotime((string) $item->pubDate)) ? date('d M Y', $date) : '',
                'source'     => $source,
                'category'   => 'Nasional',
                'image_url'  => null,
            ];
        }
        return $items;
    }
}


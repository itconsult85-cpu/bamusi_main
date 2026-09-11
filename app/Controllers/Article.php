<?php

namespace App\Controllers;

use App\Models\CmsItemModel;
use App\Models\PageModel;

class Article extends BaseController
{
    protected CmsItemModel $articleModel;
    protected PageModel $pageModel;

    public function __construct()
    {
        $this->articleModel = new CmsItemModel();
        $this->pageModel = new PageModel();
    }

    public function index()
    {
        $articles = $this->articleModel
            ->where('kind', 'article')
            ->where('published', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('frontend/articles/index', $this->sharedData(['articles' => $articles]));
    }

    public function show(int $id)
    {
        $article = $this->articleModel
            ->where('id', $id)
            ->where('kind', 'article')
            ->where('published', 1)
            ->first();

        if (!$article) {
            return view('frontend/404');
        }

        $relatedArticles = $this->articleModel
            ->where('kind', 'article')
            ->where('published', 1)
            ->where('id !=', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll(4);

        return view('frontend/articles/show', $this->sharedData([
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]));
    }

    protected function sharedData(array $data): array
    {
        $db = \Config\Database::connect();
        $data['settings'] = array_column($db->table('site_settings')->get()->getResultArray(), 'setting_value', 'setting_key');
        $data['navMenu'] = $this->pageModel->where('published', 1)->where('show_in_menu', 1)->orderBy('sort_order', 'ASC')->findAll();
        $data['locale'] = session()->get('lang') ?? 'id';
        return $data;
    }
}

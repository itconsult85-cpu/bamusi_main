<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageModel;

class MenuCMS extends BaseController
{
    protected PageModel $pageModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
    }

    public function index()
    {
        $pages = $this->pageModel
            ->orderBy('parent_id', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $parents = array_values(array_filter($pages, static fn (array $page): bool => empty($page['parent_id'])));

        return view('admin/menu/index', [
            'pages'   => $pages,
            'parents' => $parents,
        ]);
    }

    public function save()
    {
        $pageIds = $this->request->getPost('page_id') ?? [];
        $enabled = $this->request->getPost('show_in_menu') ?? [];
        $mega    = $this->request->getPost('is_mega') ?? [];
        $orders  = $this->request->getPost('sort_order') ?? [];
        $parents = $this->request->getPost('parent_id') ?? [];

        if (!is_array($pageIds)) {
            return redirect()->back()->with('error', 'Data menu tidak valid.');
        }

        $validIds = array_map('intval', $pageIds);
        $allPages = $this->pageModel->findAll();
        $pageMap  = [];
        foreach ($allPages as $page) {
            $pageMap[(int) $page['id']] = $page;
        }
        $rootIds = [];
        foreach ($allPages as $page) {
            if (empty($page['parent_id'])) {
                $rootIds[(int) $page['id']] = true;
            }
        }

        foreach ($validIds as $id) {
            if (!isset($pageMap[$id])) {
                continue;
            }

            $parentId = (int) ($parents[$id] ?? 0);
            if ($parentId === $id || ($parentId > 0 && !isset($rootIds[$parentId]))) {
                $parentId = 0;
            }

            $this->pageModel->update($id, [
                'show_in_menu' => isset($enabled[$id]) ? 1 : 0,
                'is_mega'      => isset($mega[$id]) ? 1 : 0,
                'sort_order'   => max(0, (int) ($orders[$id] ?? 0)),
                'parent_id'    => $parentId ?: null,
            ]);
        }

        return redirect()->to('/admin/menu')->with('success', 'Susunan menu berhasil disimpan.');
    }
}

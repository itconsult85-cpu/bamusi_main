<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuItemModel;
use App\Models\PageModel;

class MenuCMS extends BaseController
{
    protected PageModel $pageModel;
    protected MenuItemModel $menuItemModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
        $this->menuItemModel = new MenuItemModel();
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
            'menuItems' => $this->menuItemModel->orderBy('parent_id', 'ASC')->orderBy('sort_order', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/menu/form', [
            'item' => null,
            'parents' => $this->menuItemModel->where('parent_id', null)->orderBy('sort_order', 'ASC')->findAll(),
        ]);
    }

    public function edit($id)
    {
        $item = $this->menuItemModel->find($id);
        if (!$item) return redirect()->to('/admin/menu')->with('error', 'Menu tidak ditemukan.');
        return view('admin/menu/form', [
            'item' => $item,
            'parents' => $this->menuItemModel->where('parent_id', null)->where('id !=', $id)->orderBy('sort_order', 'ASC')->findAll(),
        ]);
    }

    public function saveItem()
    {
        $id = $this->request->getPost('id');
        $type = $this->request->getPost('target_type') ?: 'section';
        if (!in_array($type, ['section', 'url'], true)) $type = 'section';
        $target = trim((string) $this->request->getPost('target'));
        $parentId = (int) ($this->request->getPost('parent_id') ?? 0);
        if ($parentId === (int) $id) $parentId = 0;

        $this->menuItemModel->save([
            'id' => $id ?: null,
            'parent_id' => $parentId ?: null,
            'label' => trim((string) $this->request->getPost('label')),
            'target_type' => $type,
            'target' => $target !== '' ? $target : null,
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active') ?? 0,
            'is_mega' => $this->request->getPost('is_mega') ?? 0,
            'sort_order' => max(0, (int) $this->request->getPost('sort_order')),
        ]);
        return redirect()->to('/admin/menu')->with('success', 'Menu langsung berhasil disimpan.');
    }

    public function deleteItem($id)
    {
        $this->menuItemModel->delete($id);
        return redirect()->to('/admin/menu')->with('success', 'Menu langsung berhasil dihapus.');
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

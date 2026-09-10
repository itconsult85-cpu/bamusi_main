<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PageCMS extends BaseController
{
    protected $model;
    protected $uploadPath = 'uploads/pages/';

    public function __construct()
    {
        $this->model = new PageModel();
    }

    /* ============ 1. INDEX (DataTables) ============ */
    public function index()
    {
        return view('admin/pages/index');
    }

    /* ============ 2. AJAX DATATABLES ============ */
    public function ajaxData()
    {
        $request = \Config\Services::request();

        $start  = $request->getVar('start') ?? 0;
        $length = $request->getVar('length') ?? 10;
        $search = $request->getVar('search')['value'] ?? '';

        $builder = $this->model->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('slug', $search)
                ->orLike('excerpt', $search)
                ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal    = $this->model->countAllResults();

        $builder->orderBy('sort_order', 'ASC')->orderBy('id', 'DESC');
        $builder->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $formatted = [];
        foreach ($data as $row) {
            $status = $row['published']
                ? '<span class="badge text-bg-success">Publik</span>'
                : '<span class="badge text-bg-secondary">Draft</span>';

            $menuBadge = $row['show_in_menu']
                ? '<span class="badge text-bg-info">Menu</span>'
                : '<span class="badge text-bg-light text-muted">Hidden</span>';

            $trans = !empty($row['title_en'])
                ? '<span class="text-success"><i class="fas fa-check"></i></span>'
                : '<span class="text-danger"><i class="fas fa-times"></i></span>';

            $cover = !empty($row['image_url'])
                ? '<img src="' . base_url($row['image_url']) . '" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">'
                : '<span class="badge text-bg-secondary">-</span>';

            $action = '
                <a href="' . base_url('admin/pages/edit/' . $row['id']) . '" class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="' . base_url('admin/pages/delete/' . $row['id']) . '" class="btn btn-sm btn-danger"
                   onclick="return confirm(\'Hapus halaman ini?\')">
                    <i class="fas fa-trash"></i>
                </a>
            ';

            $formatted[] = [
                $cover,
                '<div class="fw-bold">' . esc($row['title']) . '</div>
                 <small class="text-muted">/' . esc($row['slug']) . '</small>',
                $menuBadge,
                '<span class="fw-bold">' . (int)$row['sort_order'] . '</span>',
                $trans,
                $status,
                $action,
            ];
        }

        return $this->response->setJSON([
            'draw'            => $request->getVar('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    /* ============ 3. FORM CREATE ============ */
    public function create()
    {
        return view('admin/pages/form', ['page' => null]);
    }

    /* ============ 4. FORM EDIT ============ */
    public function edit($id)
    {
        $page = $this->model->find($id);
        if (!$page) {
            return redirect()->to('/admin/pages')->with('error', 'Data tidak ditemukan.');
        }
        return view('admin/pages/form', ['page' => $page]);
    }

    /* ============ 5. SAVE ============ */
    public function save()
    {
        $id = $this->request->getPost('id');

        // Slug otomatis
        $slug = $this->request->getPost('slug');
        if (empty($slug)) {
            $slug = url_title($this->request->getPost('title'), '-', true);
        }
        $slug = url_title($slug, '-', true);

        // Auto translate
        $tr = new GoogleTranslate('en');
        $tr->setSource('id');

        $titleId   = $this->request->getPost('title');
        $excerptId = $this->request->getPost('excerpt');
        $bodyId    = $this->request->getPost('body');
        $menuId    = $this->request->getPost('menu_label');


        try {
            $titleEn   = !empty($titleId)   ? $tr->translate($titleId)   : null;
            $excerptEn = !empty($excerptId) ? $tr->translate($excerptId) : null;
            $bodyEn    = !empty($bodyId)    ? $tr->translate($bodyId)    : null;
            $menuEn    = !empty($menuId)    ? $tr->translate($menuId)    : null;
        } catch (\Exception $e) {
            $titleEn = $excerptEn = $bodyEn = $menuEn = null;
        }

        $oldData = !empty($id) ? $this->model->find($id) : null;

        $data = [
            'slug'              => $slug,
            'title'             => $titleId,
            'title_en'          => $titleEn,
            'excerpt'           => $excerptId,
            'excerpt_en'        => $excerptEn,
            'body'              => $bodyId,
            'body_en'           => $bodyEn,
            'published'         => $this->request->getPost('published') ?? 0,
            'show_in_menu'      => $this->request->getPost('show_in_menu') ?? 0,
            'menu_label'        => $menuId,
            'menu_label_en'     => $menuEn,
            'sort_order'        => $this->request->getPost('sort_order') ?? 0,
            'meta_title'        => $this->request->getPost('meta_title'),
            'meta_description'  => $this->request->getPost('meta_description'),
            'header_kicker'     => $this->request->getPost('header_kicker'),
            'header_title'      => $this->request->getPost('header_title'),
            'header_intro'      => $this->request->getPost('header_intro'),
            'header_show_logo'  => $this->request->getPost('header_show_logo') ?? 0,
            'header_show_intro' => $this->request->getPost('header_show_intro') ?? 0,
            'header_show_back'  => $this->request->getPost('header_show_back') ?? 0,
            'parent_id'  => $this->request->getPost('parent_id') ?: null,
            'is_mega'    => $this->request->getPost('is_mega') ?? 0,
            'menu_desc'  => $this->request->getPost('menu_desc'),
            'menu_desc_en' => $this->request->getPost('menu_desc'), // bisa ditranslate
        ];

        // Upload image utama (image_url)
        $file = $this->request->getFile('image_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $dir = FCPATH . $this->uploadPath;
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $file->getRandomName();
            $file->move($dir, $newName);

            // Unlink file lama
            if ($oldData && !empty($oldData['image_url'])) {
                $oldPath = FCPATH . ltrim($oldData['image_url'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) @unlink($oldPath);
            }

            $data['image_url'] = $this->uploadPath . $newName;
        }

        // Upload header logo
        $logo = $this->request->getFile('header_logo_url');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $dir = FCPATH . $this->uploadPath;
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $logo->getRandomName();
            $logo->move($dir, $newName);

            if ($oldData && !empty($oldData['header_logo_url'])) {
                $oldPath = FCPATH . ltrim($oldData['header_logo_url'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) @unlink($oldPath);
            }

            $data['header_logo_url'] = $this->uploadPath . $newName;
        }

        if (!empty($id)) $data['id'] = $id;

        $this->model->save($data);

        return redirect()->to('/admin/pages')->with('success', 'Halaman berhasil disimpan.');
    }

    /* ============ 6. DELETE ============ */
    public function delete($id)
    {
        $page = $this->model->find($id);
        if ($page) {
            foreach (['image_url', 'header_logo_url'] as $field) {
                if (!empty($page[$field])) {
                    $path = FCPATH . ltrim($page[$field], '/');
                    if (file_exists($path) && is_file($path)) @unlink($path);
                }
            }
            $this->model->delete($id);
        }
        return redirect()->to('/admin/pages')->with('success', 'Halaman dihapus.');
    }
}

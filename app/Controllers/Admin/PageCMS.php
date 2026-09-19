<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageModel;
use App\Models\PageBlockModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PageCMS extends BaseController
{
    protected $model;
    protected $blockModel;
    protected $uploadPath = 'uploads/pages/';

    public function __construct()
    {
        $this->model = new PageModel();
        $this->blockModel = new PageBlockModel();
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
                <a href="' . base_url($row['slug']) . '" target="_blank" class="btn btn-sm btn-info text-white" title="Lihat halaman">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                <form action="' . site_url('admin/pages/delete/' . $row['id']) . '" method="post" class="d-inline" onsubmit="return confirm(\'Hapus halaman ini?\')">
                    ' . csrf_field() . '
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            ';

            $formatted[] = [
                $cover,
                '<div class="fw-bold">' . esc($row['title']) . ($row['slug'] === 'berita' ? ' <span class="badge text-bg-primary">Halaman khusus</span>' : '') . '</div>
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
        return view('admin/pages/form', ['page' => null, 'blocks' => []]);
    }

    /* ============ 4. FORM EDIT ============ */
    public function edit($id)
    {
        $page = $this->model->find($id);
        if (!$page) {
            return redirect()->to('/admin/pages')->with('error', 'Data tidak ditemukan.');
        }
        $blocks = \Config\Database::connect()->tableExists('page_blocks')
            ? $this->blockModel->where('page_id', $id)->orderBy('sort_order', 'ASC')->findAll()
            : [];
        foreach ($blocks as &$block) {
            $block['data'] = json_decode($block['block_data'], true) ?: [];
        }
        unset($block);
        return view('admin/pages/form', ['page' => $page, 'blocks' => $blocks]);
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

        $titleId   = $this->request->getPost('title');
        $excerptId = $this->request->getPost('excerpt');
        $bodyId    = $this->request->getPost('body');
        $menuId    = $this->request->getPost('menu_label');
        $menuDescId = $this->request->getPost('menu_desc');
        $oldData   = !empty($id) ? $this->model->find($id) : null;
        $menuTargetType = $this->request->getPost('menu_target_type') ?: 'page';
        if (!in_array($menuTargetType, ['page', 'section', 'url'], true)) {
            $menuTargetType = 'page';
        }
        $menuTarget = trim((string) $this->request->getPost('menu_target'));
        if ($menuTargetType === 'url') {
            $menuTarget = trim((string) $this->request->getPost('menu_target_custom'));
        }


        $titleEn    = $this->translateText($titleId, $oldData['title_en'] ?? null);
        $excerptEn  = $this->translateText($excerptId, $oldData['excerpt_en'] ?? null);
        $bodyEn     = $this->translateText($bodyId, $oldData['body_en'] ?? null);
        $menuEn     = $this->translateText($menuId, $oldData['menu_label_en'] ?? null);
        $menuDescEn = $this->translateText($menuDescId, $oldData['menu_desc_en'] ?? null);

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
            'menu_target_type' => $menuTargetType,
            'menu_target' => $menuTarget !== '' ? $menuTarget : null,
            'menu_desc'  => $menuDescId,
            'menu_desc_en' => $menuDescEn,
        ];

        // Upload image utama (image_url)
        $file = $this->request->getFile('image_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (! $this->validate(['image_url' => 'is_image[image_url]|mime_in[image_url,image/jpg,image/jpeg,image/png,image/webp]|max_size[image_url,2048]'])) {
                return redirect()->back()->withInput()->with('error', 'Gambar halaman harus JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.');
            }
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
            if (! $this->validate(['header_logo_url' => 'is_image[header_logo_url]|mime_in[header_logo_url,image/jpg,image/jpeg,image/png,image/webp]|max_size[header_logo_url,2048]'])) {
                return redirect()->back()->withInput()->with('error', 'Logo halaman harus JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.');
            }
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

        $pageId = (int) ($id ?: $this->model->getInsertID());
        $this->saveBlocks($pageId, (string) $this->request->getPost('blocks_json'));

        return redirect()->to('/admin/pages')->with('success', 'Halaman berhasil disimpan.');
    }

    private function saveBlocks(int $pageId, string $rawBlocks): void
    {
        if ($pageId < 1) return;
        if (!\Config\Database::connect()->tableExists('page_blocks')) return;
        $blocks = json_decode($rawBlocks, true);
        if (!is_array($blocks)) $blocks = [];
        $allowed = ['rich_text', 'image', 'cards', 'program_list', 'quote', 'cta', 'spacer'];
        $this->blockModel->where('page_id', $pageId)->delete();
        foreach ($blocks as $order => $block) {
            $type = (string) ($block['type'] ?? '');
            if (!in_array($type, $allowed, true)) continue;
            $data = is_array($block['data'] ?? null) ? $block['data'] : [];
            $this->blockModel->insert([
                'page_id' => $pageId,
                'block_type' => $type,
                'block_data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'sort_order' => (int) $order,
                'published' => !empty($block['published']) ? 1 : 0,
            ]);
        }
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
            $this->blockModel->where('page_id', $id)->delete();
            $this->model->delete($id);
        }
        return redirect()->to('/admin/pages')->with('success', 'Halaman dihapus.');
    }
}

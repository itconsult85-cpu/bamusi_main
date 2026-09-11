<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CmsItemModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CmsItem extends BaseController
{
    protected $model;
    protected $uploadPath = 'uploads/cms/';

    public function __construct()
    {
        $this->model = new CmsItemModel();
    }

    /* ============ 1. INDEX ============ */
    public function index()
    {
        return view('admin/cms_items/index');
    }

    /* ============ 2. AJAX DATATABLES ============ */
    public function ajaxData()
    {
        $request = \Config\Services::request();

        $start  = $request->getVar('start') ?? 0;
        $length = $request->getVar('length') ?? 10;
        $search = $request->getVar('search')['value'] ?? '';
        $kind   = $request->getVar('kind') ?? '';

        $builder = $this->model->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('summary', $search)
                ->orLike('category', $search)
                ->groupEnd();
        }

        if (!empty($kind)) {
            $builder->where('kind', $kind);
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal    = $this->model->countAllResults();

        $builder->orderBy('created_at', 'DESC')->limit($length, $start);
        $data = $builder->get()->getResultArray();

        $formatted = [];
        foreach ($data as $row) {
            $status = $row['published']
                ? '<span class="badge text-bg-success">Publik</span>'
                : '<span class="badge text-bg-secondary">Draft</span>';

            $kindBadge = match ($row['kind']) {
                'news'    => '<span class="badge text-bg-info">Berita</span>',
                'agenda'  => '<span class="badge text-bg-warning">Agenda</span>',
                'article' => '<span class="badge text-bg-primary">Artikel</span>',
                'gallery' => '<span class="badge text-bg-success">Galeri</span>',
                'program' => '<span class="badge text-bg-dark">Program</span>',
                default   => '<span class="badge text-bg-secondary">' . esc($row['kind']) . '</span>',
            };

            $trans = !empty($row['title_en'])
                ? '<span class="text-success"><i class="fas fa-check"></i></span>'
                : '<span class="text-danger"><i class="fas fa-times"></i></span>';

            $image = !empty($row['image_url'])
                ? '<img src="' . base_url($row['image_url']) . '" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">'
                : '<span class="badge text-bg-secondary">-</span>';

            $action = '
                <a href="' . base_url('admin/cms-items/edit/' . $row['id']) . '" class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="' . base_url('admin/cms-items/delete/' . $row['id']) . '" class="btn btn-sm btn-danger"
                   onclick="return confirm(\'Hapus konten ini?\')">
                    <i class="fas fa-trash"></i>
                </a>
            ';

            $formatted[] = [
                $image,
                '<div class="fw-bold">' . esc(mb_strimwidth($row['title'], 0, 60, '...')) . '</div>
                 <small class="text-muted">' . esc($row['category'] ?? '-') . '</small>',
                $kindBadge,
                esc($row['event_date'] ?? '-'),
                $trans,
                $status,
                $action,
            ];
        }

        return $this->response->setJSON([
            'draw'            => (int) $request->getVar('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    /* ============ 3. FORM CREATE ============ */
    public function create()
    {
        return view('admin/cms_items/form', ['item' => null]);
    }

    /* ============ 4. FORM EDIT ============ */
    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return redirect()->to('/admin/cms-items')->with('error', 'Konten tidak ditemukan.');
        }
        return view('admin/cms_items/form', ['item' => $item]);
    }

    /* ============ 5. SAVE ============ */
    public function save()
    {
        $id = $this->request->getPost('id');

        $titleId   = $this->request->getPost('title');
        $summaryId = $this->request->getPost('summary');
        $bodyId    = $this->request->getPost('body');

        $tr = new GoogleTranslate('en');
        $tr->setSource('id');
        try {
            $titleEn   = !empty($titleId)   ? $tr->translate($titleId)   : null;
            $summaryEn = !empty($summaryId) ? $tr->translate($summaryId) : null;
            $bodyEn    = !empty($bodyId)    ? $tr->translate($bodyId)    : null;
        } catch (\Exception $e) {
            $titleEn = $summaryEn = $bodyEn = null;
        }

        $oldData = !empty($id) ? $this->model->find($id) : null;

        $data = [
            'kind'        => in_array($this->request->getPost('kind'), ['news', 'agenda', 'gallery', 'article', 'program'], true)
                ? $this->request->getPost('kind') : 'news',
            'title'       => $titleId,
            'title_en'    => $titleEn,
            'summary'     => $summaryId,
            'summary_en'  => $summaryEn,
            'body'        => $bodyId,
            'body_en'     => $bodyEn,
            'url'         => $this->request->getPost('url'),
            'category'    => $this->request->getPost('category'),
            'event_date'  => $this->request->getPost('event_date'),
            'location'    => $this->request->getPost('location'),
            'published'   => $this->request->getPost('published') ?? 1,
        ];

        // Upload gambar
        $file = $this->request->getFile('image_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $dir = FCPATH . $this->uploadPath;
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $file->getRandomName();
            $file->move($dir, $newName);

            // Unlink gambar lama
            if ($oldData && !empty($oldData['image_url'])) {
                $oldPath = FCPATH . ltrim($oldData['image_url'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) @unlink($oldPath);
            }

            $data['image_url'] = $this->uploadPath . $newName;
        }

        if (!empty($id)) $data['id'] = $id;
        $this->model->save($data);

        return redirect()->to('/admin/cms-items')->with('success', 'Konten berhasil disimpan.');
    }

    /* ============ 6. DELETE ============ */
    public function delete($id)
    {
        $item = $this->model->find($id);
        if ($item && !empty($item['image_url'])) {
            $path = FCPATH . ltrim($item['image_url'], '/');
            if (file_exists($path) && is_file($path)) @unlink($path);
        }
        $this->model->delete($id);
        return redirect()->to('/admin/cms-items')->with('success', 'Konten dihapus.');
    }
}

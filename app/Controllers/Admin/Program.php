<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProgramModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Program extends BaseController
{
    protected $programModel;
    protected $uploadPath = 'uploads/programs/';

    public function __construct()
    {
        $this->programModel = new ProgramModel();
    }

    /* ============ 1. INDEX ============ */
    public function index()
    {
        return view('admin/programs/index');
    }

    /* ============ 2. AJAX DATATABLES ============ */
    public function ajaxData()
    {
        $request = \Config\Services::request();

        $start  = $request->getVar('start') ?? 0;
        $length = $request->getVar('length') ?? 10;
        $search = $request->getVar('search')['value'] ?? '';

        $builder = $this->programModel->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->orLike('division', $search)
                ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal    = $this->programModel->countAllResults();

        $builder->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $formatted = [];
        foreach ($data as $row) {
            $status = $row['published']
                ? '<span class="badge text-bg-success">Publik</span>'
                : '<span class="badge text-bg-secondary">Draft</span>';

            $trans = !empty($row['name_en'])
                ? '<span class="badge text-bg-success">✓ Ada</span>'
                : '<span class="badge text-bg-danger">✗ Kosong</span>';

            $image = !empty($row['image_url'])
                ? '<img src="' . base_url($row['image_url']) . '" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">'
                : '<span class="badge text-bg-secondary">-</span>';

            $action = '
                <a href="' . base_url('admin/programs/edit/' . $row['id']) . '" class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="' . base_url('program/' . rawurlencode($row['slug'])) . '" target="_blank" class="btn btn-sm btn-info text-white" title="Lihat publik">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                <a href="' . base_url('admin/programs/delete/' . $row['id']) . '" class="btn btn-sm btn-danger"
                   onclick="return confirm(\'Hapus program ini?\')">
                    <i class="fas fa-trash"></i>
                </a>
            ';

            $formatted[] = [
                $image,
                '<div class="fw-bold">' . esc($row['name']) . '</div>
                 <small class="text-muted">/' . esc($row['slug'] ?? '-') . '</small>',
                '<span class="badge text-bg-light">' . esc($row['division'] ?? 'BAMUSI') . '</span>',
                $trans,
                $status,
                '<span class="fw-bold">' . (int)($row['sort_order'] ?? 0) . '</span>',
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
        return view('admin/programs/form', ['item' => null]);
    }

    /* ============ 4. FORM EDIT ============ */
    public function edit($id)
    {
        $item = $this->programModel->find($id);
        if (!$item) {
            return redirect()->to('/admin/programs')->with('error', 'Program tidak ditemukan.');
        }
        return view('admin/programs/form', ['item' => $item]);
    }

    /* ============ 5. SAVE ============ */
    public function save()
    {
        $id     = $this->request->getPost('id');
        $nameId = $this->request->getPost('name');
        $descId = $this->request->getPost('description');

        $oldData = !empty($id) ? $this->programModel->find($id) : null;
        $nameEn = $this->translateText($nameId, $oldData['name_en'] ?? null);
        $descEn = $this->translateText($descId, $oldData['description_en'] ?? null);

        $data = [
            'name'           => $nameId,
            'name_en'        => $nameEn,
            'slug'           => url_title($nameId, '-', true),
            'description'    => $descId,
            'description_en' => $descEn,
            'division'       => $this->request->getPost('division') ?: 'BAMUSI',
            'sort_order'     => $this->request->getPost('sort_order') ?? 0,
            'published'      => $this->request->getPost('published') ?? 1,
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
        $this->programModel->save($data);

        return redirect()->to('/admin/programs')->with('success', 'Program berhasil disimpan.');
    }

    /* ============ 6. DELETE ============ */
    public function delete($id)
    {
        $item = $this->programModel->find($id);
        if ($item && !empty($item['image_url'])) {
            $path = FCPATH . ltrim($item['image_url'], '/');
            if (file_exists($path) && is_file($path)) @unlink($path);
        }
        $this->programModel->delete($id);
        return redirect()->to('/admin/programs')->with('success', 'Program dihapus.');
    }
}

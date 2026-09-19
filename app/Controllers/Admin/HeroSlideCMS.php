<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HeroSlideModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HeroSlideCMS extends BaseController
{
    protected $model;
    protected $uploadPath = 'uploads/hero/';

    public function __construct()
    {
        $this->model = new HeroSlideModel();
    }

    /* ============ 1. INDEX ============ */
    public function index()
    {
        return view('admin/hero_slides/index');
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
                ->like('kicker', $search)
                ->orLike('title', $search)
                ->orLike('lead', $search)
                ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal    = $this->model->countAllResults();

        $builder->orderBy('sort_order', 'ASC')->limit($length, $start);
        $data = $builder->get()->getResultArray();

        $formatted = [];
        foreach ($data as $row) {
            $status = $row['published']
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Draft</span>';

            $trans = !empty($row['title_en'])
                ? '<span class="text-success"><i class="fas fa-check"></i></span>'
                : '<span class="text-danger"><i class="fas fa-times"></i></span>';

            $img = !empty($row['image_url']) && file_exists(FCPATH . $row['image_url'])
                ? '<img src="' . base_url($row['image_url']) . '" style="width:80px;height:50px;object-fit:cover;border-radius:6px;">'
                : '<span class="badge text-bg-secondary">-</span>';

            $action = '
                <a href="' . base_url('admin/hero-slides/edit/' . $row['id']) . '" class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="' . base_url('admin/hero-slides/delete/' . $row['id']) . '" class="btn btn-sm btn-danger"
                   onclick="return confirm(\'Hapus slide ini?\')">
                    <i class="fas fa-trash"></i>
                </a>
            ';

            $formatted[] = [
                $img,
                '<div class="fw-bold small" style="color:' . esc($row['kicker_color']) . '">' . esc($row['kicker']) . '</div>'
                    . '<div class="fw-bold">' . esc(mb_strimwidth($row['title'], 0, 60, '...')) . '</div>',
                '<span class="fw-bold">' . (int)$row['sort_order'] . '</span>',
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
        return view('admin/hero_slides/form', ['item' => null]);
    }

    /* ============ 4. FORM EDIT ============ */
    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return redirect()->to('/admin/hero-slides')->with('error', 'Slide tidak ditemukan.');
        }
        return view('admin/hero_slides/form', ['item' => $item]);
    }

    /* ============ 5. SAVE ============ */
    public function save()
    {
        $id = $this->request->getPost('id');

        $kickerId = $this->request->getPost('kicker');
        $titleId  = $this->request->getPost('title');
        $leadId   = $this->request->getPost('lead');
        $quoteId  = $this->request->getPost('quote');
        $btnId    = $this->request->getPost('button_label');

        $oldData = !empty($id) ? $this->model->find($id) : null;
        $kickerEn = $this->translateText($kickerId, $oldData['kicker_en'] ?? null);
        $titleEn  = $this->translateText($titleId, $oldData['title_en'] ?? null);
        $leadEn   = $this->translateText($leadId, $oldData['lead_en'] ?? null);
        $quoteEn  = $this->translateText($quoteId, $oldData['quote_en'] ?? null);
        $btnEn    = $this->translateText($btnId, $oldData['button_label_en'] ?? null);

        $data = [
            'kicker'          => $kickerId,
            'kicker_en'       => $kickerEn,
            'title'           => $titleId,
            'title_en'        => $titleEn,
            'lead'            => $leadId,
            'lead_en'         => $leadEn,
            'quote'           => $quoteId,
            'quote_en'        => $quoteEn,
            'kicker_color'    => $this->request->getPost('kicker_color') ?: '#e7aa6b',
            'title_color'     => $this->request->getPost('title_color')  ?: '#ffffff',
            'lead_color'      => $this->request->getPost('lead_color')   ?: '#d7e8dd',
            'quote_color'     => $this->request->getPost('quote_color')  ?: '#e7aa6b',
            'button_label'    => $btnId,
            'button_label_en' => $btnEn,
            'button_url'      => $this->request->getPost('button_url'),
            'sort_order'      => $this->request->getPost('sort_order') ?? 0,
            'published'       => $this->request->getPost('published') ?? 1,
        ];

        // Upload image
        $file = $this->request->getFile('image_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!$this->validate([
                'image_url' => 'is_image[image_url]|mime_in[image_url,image/jpg,image/jpeg,image/png,image/webp]|max_size[image_url,2048]'
            ])) {
                return redirect()->back()->withInput()
                    ->with('error', 'Gambar hero harus JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.');
            }

            $dir = FCPATH . $this->uploadPath;
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $file->getRandomName();
            $file->move($dir, $newName);

            // Unlink image lama
            if ($oldData && !empty($oldData['image_url'])) {
                $oldPath = FCPATH . ltrim($oldData['image_url'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) @unlink($oldPath);
            }

            $data['image_url'] = $this->uploadPath . $newName;
        }

        if (!empty($id)) $data['id'] = $id;
        $this->model->save($data);

        return redirect()->to('/admin/hero-slides')->with('success', 'Slide berhasil disimpan.');
    }

    /* ============ 6. DELETE ============ */
    public function delete($id)
    {
        $item = $this->model->find($id);
        if ($item) {
            if (!empty($item['image_url'])) {
                $path = FCPATH . ltrim($item['image_url'], '/');
                if (file_exists($path) && is_file($path)) @unlink($path);
            }
            $this->model->delete($id);
        }
        return redirect()->to('/admin/hero-slides')->with('success', 'Slide dihapus.');
    }
}

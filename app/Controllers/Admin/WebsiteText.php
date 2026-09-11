<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WebsiteTextModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class WebsiteText extends BaseController
{
    protected $textModel;

    public function __construct()
    {
        $this->textModel = new WebsiteTextModel();
    }

    /* ============================================
       1. INDEX — hanya tampil view (DataTables via AJAX)
       ============================================ */
    public function index()
    {
        return view('admin/website_texts/index');
    }

    /* ============================================
       2. AJAX DATATABLES — server-side
       ============================================ */
    public function ajaxData()
    {
        $request = \Config\Services::request();

        $start    = $request->getVar('start') ?? 0;
        $length   = $request->getVar('length') ?? 10;
        $search   = $request->getVar('search')['value'] ?? '';
        $location = $request->getVar('location') ?? '';

        $builder = $this->textModel->builder();

        // Filter pencarian
        if (!empty($search)) {
            $builder->groupStart()
                ->like('text_key', $search)
                ->orLike('label', $search)
                ->orLike('value', $search)
                ->groupEnd();
        }

        // Filter lokasi
        if (!empty($location)) {
            $builder->where('location', $location);
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal    = $this->textModel->countAllResults();

        $builder->orderBy('location', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $formatted = [];
        foreach ($data as $row) {
            $status = $row['published']
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Draft</span>';

            $transBadge = !empty($row['value_en'])
                ? '<span class="badge text-bg-success">✓ Ada</span>'
                : '<span class="badge text-bg-danger">✗ Kosong</span>';

            $preview = mb_strimwidth(strip_tags($row['value'] ?? ''), 0, 60, '...');

            $action = '
                <a href="' . base_url('admin/texts/edit/' . $row['id']) . '" 
                   class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
            ';

            $formatted[] = [
                '<span class="badge text-bg-dark">' . esc($row['text_key']) . '</span>
                 <br><small class="text-muted">' . esc($row['label']) . '</small>',
                '<span class="badge text-bg-light">' . esc($row['location']) . '</span>',
                '<div class="small">' . esc($preview) . '</div>',
                $transBadge,
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

    /* ============================================
       3. FORM TAMBAH
       ============================================ */
    public function create()
    {
        return view('admin/website_texts/form', ['item' => null]);
    }

    /* ============================================
       4. FORM EDIT
       ============================================ */
    public function edit($id)
    {
        $item = $this->textModel->find($id);
        if (!$item) {
            return redirect()->to('/admin/texts')->with('error', 'Data tidak ditemukan.');
        }
        return view('admin/website_texts/form', ['item' => $item]);
    }

    /* ============================================
       5. SAVE (create & update)
       ============================================ */
    public function save()
    {
        $id      = $this->request->getPost('id');
        $valueId = $this->request->getPost('value');

        // Auto translate
        $tr = new GoogleTranslate('en');
        $tr->setSource('id');
        try {
            $valueEn = !empty($valueId) ? $tr->translate($valueId) : null;
        } catch (\Exception $e) {
            $valueEn = null;
        }

        $data = [
            'text_key'   => $this->request->getPost('text_key'),
            'label'      => $this->request->getPost('label'),
            'location'   => $this->request->getPost('location'),
            'value'      => $valueId,
            'value_en'   => $valueEn,
            'sort_order' => $this->request->getPost('sort_order') ?? 0,
            'published'  => $this->request->getPost('published') ?? 1,
        ];

        if (!empty($id)) $data['id'] = $id;

        $this->textModel->save($data);

        return redirect()->to('/admin/texts')->with('success', 'Teks berhasil disimpan dan diterjemahkan.');
    }

    /* ============================================
       6. DELETE
       ============================================ */
    public function delete($id)
    {
        $this->textModel->delete($id);
        return redirect()->to('/admin/texts')->with('success', 'Teks dihapus.');
    }

    /* ============================================
       7. BULK TRANSLATE — untuk data yang value_en kosong
       ============================================ */
    public function bulkTranslate()
    {
        // Ambil semua yang value_en kosong atau NULL
        $items = $this->textModel
            ->groupStart()
            ->where('value_en', null)
            ->orWhere('value_en', '')
            ->groupEnd()
            ->findAll();

        if (empty($items)) {
            return redirect()->to('/admin/texts')
                ->with('success', 'Tidak ada teks yang perlu diterjemahkan.');
        }

        $tr = new GoogleTranslate('en');
        $tr->setSource('id');

        $successCount = 0;
        $failCount = 0;

        foreach ($items as $item) {
            if (empty($item['value'])) continue;

            try {
                $translated = $tr->translate($item['value']);
                if (!empty($translated)) {
                    $this->textModel->update($item['id'], ['value_en' => $translated]);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $failCount++;
                log_message('error', 'Translate failed for ID ' . $item['id'] . ': ' . $e->getMessage());
            }
        }

        return redirect()->to('/admin/texts')
            ->with('success', "Berhasil menerjemahkan {$successCount} teks. Gagal: {$failCount}.");
    }
}

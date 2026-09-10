<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageSectionModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SectionCMS extends BaseController
{
    protected $sectionModel;

    public function __construct()
    {
        $this->sectionModel = new PageSectionModel();
    }

    // 1. Menampilkan Halaman Tabel (DataTables)
    public function index()
    {
        return view('admin/sections/index');
    }

    // 2. Memproses DataTables Server-Side (AJAX)
    public function ajaxData()
    {
        $request = \Config\Services::request();

        // Parameter bawaan DataTables
        $start = $request->getVar('start') ?? 0;
        $length = $request->getVar('length') ?? 10;
        $search = $request->getVar('search')['value'] ?? '';

        $builder = $this->sectionModel->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('section_name', $search)
                ->orLike('section_key', $search)
                ->orLike('title', $search)
                ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal = $this->sectionModel->countAllResults();

        $builder->orderBy('sort_order', 'ASC');
        $builder->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $formattedData = [];
        foreach ($data as $row) {
            $status = $row['published'] ? '<span class="badge text-bg-success">Publik</span>' : '<span class="badge text-bg-secondary">Draft</span>';
            $trans = !empty($row['title_en']) ? '<span class="text-success"><i class="fas fa-check"></i> Ada</span>' : '<span class="text-danger"><i class="fas fa-times"></i> Kosong</span>';

            $action = '
                <a href="' . base_url('admin/sections/edit/' . $row['id']) . '" class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i> Edit
                </a>
            ';

            $formattedData[] = [
                '<span class="badge text-bg-dark">' . esc($row['section_key']) . '</span>',
                esc($row['section_name']),
                esc($row['title']),
                $trans,
                $status,
                $action
            ];
        }

        return $this->response->setJSON([
            'draw'            => $request->getVar('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formattedData
        ]);
    }

    // 3. Menampilkan Halaman Form Tambah
    public function create()
    {
        return view('admin/sections/form');
    }

    // 4. Menampilkan Halaman Form Edit
    public function edit($id)
    {
        $data['section'] = $this->sectionModel->find($id);
        if (!$data['section']) {
            return redirect()->to('/admin/sections')->with('error', 'Data tidak ditemukan.');
        }
        return view('admin/sections/form', $data);
    }

    // 5. Menyimpan Data (dari Create atau Edit)
    public function save()
    {
        $id = $this->request->getPost('id');

        // Tangkap semua input teks ID
        $kickerId   = $this->request->getPost('kicker');
        $titleId    = $this->request->getPost('title');
        $subId      = $this->request->getPost('subtitle');
        $quoteId    = $this->request->getPost('quote');
        $contentId  = $this->request->getPost('content');
        $btnLabelId = $this->request->getPost('button_label');

        // Auto Translate
        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
        $tr->setSource('id');
        try {
            $kickerEn   = !empty($kickerId) ? $tr->translate($kickerId) : null;
            $titleEn    = !empty($titleId) ? $tr->translate($titleId) : null;
            $subEn      = !empty($subId) ? $tr->translate($subId) : null;
            $quoteEn    = !empty($quoteId) ? $tr->translate($quoteId) : null;
            $contentEn  = !empty($contentId) ? $tr->translate($contentId) : null;
            $btnLabelEn = !empty($btnLabelId) ? $tr->translate($btnLabelId) : null;
        } catch (\Exception $e) {
            $kickerEn = $titleEn = $subEn = $quoteEn = $contentEn = $btnLabelEn = null;
        }

        // Siapkan Data Lengkap
        $data = [
            'section_name'    => $this->request->getPost('section_name'),
            'section_key'     => $this->request->getPost('section_key'),
            'kicker'          => $kickerId,
            'kicker_en'       => $kickerEn,
            'title'           => $titleId,
            'title_en'        => $titleEn,
            'subtitle'        => $subId,
            'subtitle_en'     => $subEn,
            'quote'           => $quoteId,
            'quote_en'        => $quoteEn,
            'content'         => $contentId,
            'content_en'      => $contentEn,
            'button_label'    => $btnLabelId,
            'button_label_en' => $btnLabelEn,
            'button_url'      => $this->request->getPost('button_url'),
            'published'       => $this->request->getPost('published') ?? 1
        ];

        // LOGIKA UPLOAD GAMBAR/VIDEO
        $mediaFile = $this->request->getFile('media_url');
        if ($mediaFile && $mediaFile->isValid() && !$mediaFile->hasMoved()) {
            // Data lama (kalau edit)
            $oldData = !empty($id) ? $this->sectionModel->find($id) : null;

            // Pastikan folder ada
            $dir = FCPATH . 'uploads/sections';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $mediaFile->getRandomName();
            $mediaFile->move($dir, $newName);

            // Hapus file lama
            if ($oldData && !empty($oldData['media_url'])) {
                $oldPath = FCPATH . ltrim($oldData['media_url'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['media_url'] = '/uploads/sections/' . $newName;
        }

        if (!empty($id)) {
            $data['id'] = $id;
        }

        $this->sectionModel->save($data);
        return redirect()->to('/admin/sections')->with('success', 'Section berhasil disimpan dan diterjemahkan penuh.');
    }
}

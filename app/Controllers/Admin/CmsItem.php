<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CmsItemModel;
use Stichoza\GoogleTranslate\GoogleTranslate; // 1. Panggil pustaka Stichoza

class CmsItem extends BaseController
{
    protected $cmsModel;

    public function __construct()
    {
        $this->cmsModel = new CmsItemModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Konten (Berita & Agenda)',
            'items' => $this->cmsModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('admin/cms_items/index', $data);
    }

    public function save()
    {
        $id        = $this->request->getPost('id');
        $titleId   = $this->request->getPost('title');
        $summaryId = $this->request->getPost('summary');
        $bodyId    = $this->request->getPost('body');

        // Inisialisasi Translator
        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
        $tr->setSource('id');

        try {
            $titleEn   = !empty($titleId) ? $tr->translate($titleId) : null;
            $summaryEn = !empty($summaryId) ? $tr->translate($summaryId) : null;
            $bodyEn    = !empty($bodyId) ? $tr->translate($bodyId) : null;
        } catch (\Exception $e) {
            // Jika localhost Anda gagal konek ke API Google, errornya akan muncul di sini
            return redirect()->back()->with('error', 'Gagal menerjemahkan: ' . $e->getMessage());
        }

        // Susun data (tanpa ID dulu)
        $data = [
            'kind'       => $this->request->getPost('kind'),
            'title'      => $titleId,
            'title_en'   => $titleEn,
            'summary'    => $summaryId,
            'summary_en' => $summaryEn,
            'body'       => $bodyId,
            'body_en'    => $bodyEn,
            'event_date' => $this->request->getPost('event_date'),
            'published'  => $this->request->getPost('published') ?? 1
        ];

        // Jika ID ada isinya (mode Edit), tambahkan ID ke array agar CI4 melakukan Update
        if (!empty($id)) {
            $data['id'] = $id;
        }

        $this->cmsModel->save($data);

        return redirect()->to('/admin/cms')->with('success', 'Konten berhasil disimpan dan diterjemahkan otomatis.');
    }
}

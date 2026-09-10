<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CmsItemModel;

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
        $this->cmsModel->save([
            'kind'       => $this->request->getPost('kind'),
            'title'      => $this->request->getPost('title'),
            'summary'    => $this->request->getPost('summary'),
            'event_date' => $this->request->getPost('event_date'),
            'published'  => $this->request->getPost('published') ?? 1
        ]);

        return redirect()->to('/admin/cms')->with('success', 'Konten berhasil disimpan');
    }
}

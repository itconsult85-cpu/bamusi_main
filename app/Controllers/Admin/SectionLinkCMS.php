<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SectionLinkModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SectionLinkCMS extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new SectionLinkModel();
    }

    // 1. Index
    public function index()
    {
        $data['items'] = $this->model
            ->orderBy('section_key', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->findAll();
        return view('admin/section_links/index', $data);
    }

    // 2. Form tambah
    public function create()
    {
        return view('admin/section_links/form', ['link' => null]);
    }

    // 3. Form edit
    public function edit($id)
    {
        $link = $this->model->find($id);
        if (!$link) {
            return redirect()->to('/admin/section-links')->with('error', 'Data tidak ditemukan.');
        }
        return view('admin/section_links/form', ['link' => $link]);
    }

    // 4. Save
    public function save()
    {
        $id = $this->request->getPost('id');

        $labelId    = $this->request->getPost('label');
        $sublabelId = $this->request->getPost('sublabel');

        $tr = new GoogleTranslate('en');
        $tr->setSource('id');
        try {
            $labelEn    = !empty($labelId)    ? $tr->translate($labelId)    : null;
            $sublabelEn = !empty($sublabelId) ? $tr->translate($sublabelId) : null;
        } catch (\Exception $e) {
            $labelEn = $sublabelEn = null;
        }

        $data = [
            'section_key'  => $this->request->getPost('section_key'),
            'label'        => $labelId,
            'label_en'     => $labelEn,
            'sublabel'     => $sublabelId,
            'sublabel_en'  => $sublabelEn,
            'url'          => $this->request->getPost('url'),
            'sort_order'   => $this->request->getPost('sort_order') ?? 0,
            'published'    => $this->request->getPost('published') ?? 1,
        ];

        if (!empty($id)) $data['id'] = $id;
        $this->model->save($data);

        return redirect()->to('/admin/section-links')->with('success', 'Link berhasil disimpan.');
    }

    // 5. Delete
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/section-links')->with('success', 'Link dihapus.');
    }
}

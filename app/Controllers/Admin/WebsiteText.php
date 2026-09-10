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

    public function index()
    {
        $data['items'] = $this->textModel->orderBy('location', 'ASC')->orderBy('sort_order', 'ASC')->findAll();
        return view('admin/website_texts/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $valueId = $this->request->getPost('value');

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
            'published'  => $this->request->getPost('published') ?? 1
        ];

        if (!empty($id)) $data['id'] = $id;

        $this->textModel->save($data);
        return redirect()->to('/admin/texts')->with('success', 'Teks website berhasil diperbarui dan diterjemahkan.');
    }
}

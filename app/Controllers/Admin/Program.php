<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProgramModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Program extends BaseController
{
    protected $programModel;

    public function __construct()
    {
        $this->programModel = new ProgramModel();
    }

    public function index()
    {
        $data = [
            'items' => $this->programModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('admin/programs/index', $data);
    }

    public function save()
    {
        $id      = $this->request->getPost('id');
        $nameId  = $this->request->getPost('name');
        $descId  = $this->request->getPost('description');

        // Inisialisasi Translator
        $tr = new GoogleTranslate('en');
        $tr->setSource('id');

        try {
            $nameEn = !empty($nameId) ? $tr->translate($nameId) : null;
            $descEn = !empty($descId) ? $tr->translate($descId) : null;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menerjemahkan: ' . $e->getMessage());
        }

        // Susun Data
        $data = [
            'name'           => $nameId,
            'name_en'        => $nameEn,
            'slug'           => url_title($nameId, '-', true), // Otomatis buat URL ramah SEO
            'description'    => $descId,
            'description_en' => $descEn,
            'division'       => $this->request->getPost('division') ?? 'BAMUSI',
            'published'      => $this->request->getPost('published') ?? 1
        ];

        // Jika Edit, masukkan ID
        if (!empty($id)) {
            $data['id'] = $id;
        }

        $this->programModel->save($data);

        return redirect()->to('/admin/programs')->with('success', 'Program berhasil disimpan dan diterjemahkan.');
    }
}

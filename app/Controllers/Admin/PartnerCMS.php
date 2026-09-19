<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PartnerModel;

class PartnerCMS extends BaseController
{
    protected PartnerModel $model;

    public function __construct()
    {
        $this->model = new PartnerModel();
    }

    public function index()
    {
        return view('admin/partners/index', ['items' => $this->model->orderBy('sort_order', 'ASC')->findAll()]);
    }

    public function create() { return view('admin/partners/form', ['item' => null]); }

    public function edit(int $id)
    {
        $item = $this->model->find($id);
        if (!$item) return redirect()->to('/admin/partners')->with('error', 'Mitra tidak ditemukan.');
        return view('admin/partners/form', ['item' => $item]);
    }

    public function save()
    {
        $id = (int) $this->request->getPost('id');
        $old = $id ? $this->model->find($id) : null;
        $name = trim((string) $this->request->getPost('name'));
        if ($name === '') return redirect()->back()->withInput()->with('error', 'Nama mitra wajib diisi.');
        $data = [
            'name' => $name,
            'name_en' => $this->translateText($name, $old['name_en'] ?? null),
            'website_url' => trim((string) $this->request->getPost('website_url')) ?: null,
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'published' => $this->request->getPost('published') ? 1 : 0,
        ];
        $file = $this->request->getFile('logo_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!$this->validate(['logo_url' => 'mime_in[logo_url,image/jpg,image/jpeg,image/png,image/webp]|max_size[logo_url,5120]'])) return redirect()->back()->withInput()->with('error', 'Logo harus JPG, PNG, atau WEBP maksimal 5 MB.');
            $dir = FCPATH . 'uploads/partners';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $nameFile = $file->getRandomName();
            $file->move($dir, $nameFile);
            $data['logo_url'] = '/uploads/partners/' . $nameFile;
            if ($old && !empty($old['logo_url'])) { $oldPath = FCPATH . ltrim($old['logo_url'], '/'); if (is_file($oldPath)) @unlink($oldPath); }
        } elseif ($old) $data['logo_url'] = $old['logo_url'] ?? null;
        if ($id) $data['id'] = $id;
        $this->model->save($data);
        return redirect()->to('/admin/partners')->with('success', 'Mitra berhasil disimpan.');
    }

    public function delete(int $id)
    {
        $item = $this->model->find($id);
        if ($item) {
            if (!empty($item['logo_url'])) { $path = FCPATH . ltrim($item['logo_url'], '/'); if (is_file($path)) @unlink($path); }
            $this->model->delete($id);
        }
        return redirect()->to('/admin/partners')->with('success', 'Mitra berhasil dihapus.');
    }
}

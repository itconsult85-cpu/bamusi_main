<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AboutValueModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AboutValueCMS extends BaseController
{
    protected AboutValueModel $model;

    public function __construct()
    {
        $this->model = new AboutValueModel();
    }

    public function index()
    {
        return view('admin/about_values/index', [
            'values' => $this->model->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/about_values/form', ['value' => null]);
    }

    public function edit($id)
    {
        $value = $this->model->find($id);
        if (!$value) return redirect()->to('/admin/about-values')->with('error', 'Kartu nilai tidak ditemukan.');
        return view('admin/about_values/form', ['value' => $value]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $old = $id ? $this->model->find($id) : null;
        $label = trim((string) $this->request->getPost('label'));
        $description = trim((string) $this->request->getPost('description'));
        $translator = new GoogleTranslate('en');
        $translator->setSource('id');

        $data = [
            'label' => $label,
            'label_en' => $this->safeTranslate($translator, $label, $old['label_en'] ?? null),
            'description' => $description,
            'description_en' => $this->safeTranslate($translator, $description, $old['description_en'] ?? null),
            'sort_order' => max(0, (int) $this->request->getPost('sort_order')),
            'published' => $this->request->getPost('published') ?? 0,
        ];
        if ($id) $data['id'] = $id;
        $this->model->save($data);
        return redirect()->to('/admin/about-values')->with('success', 'Kartu nilai berhasil disimpan dan diterjemahkan.');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/about-values')->with('success', 'Kartu nilai berhasil dihapus.');
    }

    private function safeTranslate(GoogleTranslate $translator, string $text, ?string $fallback): ?string
    {
        if ($text === '') return null;
        try {
            $translated = trim((string) $translator->translate($text));
            return $translated !== '' ? $translated : $fallback;
        } catch (\Throwable $e) {
            log_message('error', 'About value translation failed: ' . $e->getMessage());
            return $fallback;
        }
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HomepageSectionItemModel;

class HomepageContentCMS extends BaseController
{
    protected HomepageSectionItemModel $model;

    protected array $sections = [
        'feature' => 'Program Unggulan (item)',
        'social' => 'Media Sosial',
        'join_interest' => 'Pilihan Minat Bergabung',
    ];

    public function __construct()
    {
        $this->model = new HomepageSectionItemModel();
    }

    public function index(?string $sectionKey = null)
    {
        $sectionKey = $sectionKey ?: 'feature';
        if (!isset($this->sections[$sectionKey])) $sectionKey = 'feature';
        return view('admin/homepage_content/index', [
            'items' => $this->model->where('section_key', $sectionKey)->orderBy('sort_order', 'ASC')->findAll(),
            'sectionKey' => $sectionKey,
            'sectionLabel' => $this->sections[$sectionKey],
            'sections' => $this->sections,
        ]);
    }

    public function create(?string $sectionKey = null)
    {
        $sectionKey = $sectionKey ?: 'feature';
        if (!isset($this->sections[$sectionKey])) $sectionKey = 'feature';
        return view('admin/homepage_content/form', [
            'item' => null,
            'sectionKey' => $sectionKey,
            'sectionLabel' => $this->sections[$sectionKey],
        ]);
    }

    public function edit(int $id)
    {
        $item = $this->model->find($id);
        if (!$item || !isset($this->sections[$item['section_key']])) {
            return redirect()->to('/admin/homepage-content')->with('error', 'Item homepage tidak ditemukan.');
        }
        return view('admin/homepage_content/form', [
            'item' => $item,
            'sectionKey' => $item['section_key'],
            'sectionLabel' => $this->sections[$item['section_key']],
        ]);
    }

    public function save()
    {
        $id = (int) $this->request->getPost('id');
        $sectionKey = (string) $this->request->getPost('section_key');
        if (!isset($this->sections[$sectionKey])) return redirect()->back()->withInput()->with('error', 'Jenis section tidak valid.');

        $old = $id ? $this->model->find($id) : null;
        $label = trim((string) $this->request->getPost('label'));
        $title = trim((string) $this->request->getPost('title'));
        $body = (string) $this->request->getPost('body');
        $data = [
            'section_key' => $sectionKey,
            'item_key' => trim((string) $this->request->getPost('item_key')) ?: null,
            'label' => $label ?: null,
            'label_en' => $this->translateText($label, $old['label_en'] ?? null),
            'title' => $title ?: null,
            'title_en' => $this->translateText($title, $old['title_en'] ?? null),
            'body' => $body ?: null,
            'body_en' => $this->translateText($body, $old['body_en'] ?? null),
            'url' => trim((string) $this->request->getPost('url')) ?: null,
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'published' => $this->request->getPost('published') ? 1 : 0,
        ];

        $options = trim((string) $this->request->getPost('options_json'));
        if ($options !== '') {
            json_decode($options, true);
            if (json_last_error() !== JSON_ERROR_NONE) return redirect()->back()->withInput()->with('error', 'Options JSON tidak valid.');
            $data['options_json'] = $options;
        } else {
            $data['options_json'] = null;
        }

        $file = $this->request->getFile('media_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!$this->validate(['media_url' => 'mime_in[media_url,image/jpg,image/jpeg,image/png,image/webp,video/mp4]|max_size[media_url,10240]'])) {
                return redirect()->back()->withInput()->with('error', 'Media harus JPG, PNG, WEBP, atau MP4 maksimal 10 MB.');
            }
            $dir = FCPATH . 'uploads/homepage';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $name = $file->getRandomName();
            $file->move($dir, $name);
            $data['media_url'] = '/uploads/homepage/' . $name;
            if ($old && !empty($old['media_url'])) {
                $oldPath = FCPATH . ltrim($old['media_url'], '/');
                if (is_file($oldPath)) @unlink($oldPath);
            }
        } elseif ($old) {
            $data['media_url'] = $old['media_url'] ?? null;
        }

        if ($id) $data['id'] = $id;
        $this->model->save($data);
        $returnTo = (string) $this->request->getPost('return_to');
        if ($returnTo === '' || !str_starts_with($returnTo, base_url('admin/'))) {
            $returnTo = base_url('admin/homepage-content/' . $sectionKey);
        }
        return redirect()->to($returnTo)->with('success', 'Konten homepage berhasil disimpan.');
    }

    public function delete(int $id)
    {
        $item = $this->model->find($id);
        if ($item) {
            if (!empty($item['media_url'])) {
                $path = FCPATH . ltrim($item['media_url'], '/');
                if (is_file($path)) @unlink($path);
            }
            $this->model->delete($id);
        }
        return redirect()->back()->with('success', 'Konten homepage berhasil dihapus.');
    }
}

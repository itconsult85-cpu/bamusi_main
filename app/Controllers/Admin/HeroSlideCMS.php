<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HeroSlideModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HeroSlideCMS extends BaseController
{
    protected $model;
    protected $uploadPath = 'uploads/hero/';

    public function __construct()
    {
        $this->model = new HeroSlideModel();
    }

    public function index()
    {
        $data['items'] = $this->model->orderBy('sort_order', 'ASC')->findAll();
        return view('admin/hero_slides/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');

        $kickerId = $this->request->getPost('kicker');
        $titleId  = $this->request->getPost('title');
        $leadId   = $this->request->getPost('lead');
        $quoteId  = $this->request->getPost('quote');
        $btnId    = $this->request->getPost('button_label');

        // Auto translate ID → EN
        $tr = new GoogleTranslate('en');
        $tr->setSource('id');
        try {
            $kickerEn = !empty($kickerId) ? $tr->translate($kickerId) : null;
            $titleEn  = !empty($titleId)  ? $tr->translate($titleId)  : null;
            $leadEn   = !empty($leadId)   ? $tr->translate($leadId)   : null;
            $quoteEn  = !empty($quoteId)  ? $tr->translate($quoteId)  : null;
            $btnEn    = !empty($btnId)    ? $tr->translate($btnId)    : null;
        } catch (\Exception $e) {
            $kickerEn = $titleEn = $leadEn = $quoteEn = $btnEn = null;
        }

        $oldData = !empty($id) ? $this->model->find($id) : null;

        $data = [
            'kicker'          => $kickerId,
            'kicker_en'       => $kickerEn,
            'title'           => $titleId,
            'title_en'        => $titleEn,
            'lead'            => $leadId,
            'lead_en'         => $leadEn,
            'quote'           => $quoteId,
            'quote_en'        => $quoteEn,
            'kicker_color'    => $this->request->getPost('kicker_color') ?: '#e7aa6b',
            'title_color'     => $this->request->getPost('title_color')  ?: '#ffffff',
            'lead_color'      => $this->request->getPost('lead_color')   ?: '#d7e8dd',
            'quote_color'     => $this->request->getPost('quote_color')  ?: '#e7aa6b',
            'button_label'    => $btnId,
            'button_label_en' => $btnEn,
            'button_url'      => $this->request->getPost('button_url'),
            'sort_order'      => $this->request->getPost('sort_order') ?? 0,
            'published'       => $this->request->getPost('published') ?? 1,
        ];

        // Upload gambar + unlink gambar lama
        $file = $this->request->getFile('image_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Pastikan folder ada
            $dir = FCPATH . $this->uploadPath;
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $file->getRandomName();
            $file->move($dir, $newName);

            // Unlink file lama
            if ($oldData && !empty($oldData['image_url'])) {
                $oldPath = FCPATH . ltrim($oldData['image_url'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) @unlink($oldPath);
            }

            $data['image_url'] = $this->uploadPath . $newName;
        }

        if (!empty($id)) $data['id'] = $id;
        $this->model->save($data);

        return redirect()->to('/admin/hero-slides')->with('success', 'Slide berhasil disimpan.');
    }

    public function delete($id)
    {
        $item = $this->model->find($id);
        if ($item) {
            if (!empty($item['image_url'])) {
                $path = FCPATH . ltrim($item['image_url'], '/');
                if (file_exists($path) && is_file($path)) @unlink($path);
            }
            $this->model->delete($id);
        }
        return redirect()->to('/admin/hero-slides')->with('success', 'Slide dihapus.');
    }
}

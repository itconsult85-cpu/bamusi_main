<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class SettingCMS extends BaseController
{
    protected $model;
    protected $uploadPath = 'uploads/settings/';

    public function __construct()
    {
        $this->model = new SiteSettingModel();
    }

    // 1. Index
    public function index()
    {
        return view('admin/settings/index');
    }

    // 2. AJAX DataTables
    public function ajaxData()
    {
        $request = \Config\Services::request();

        $start    = $request->getVar('start') ?? 0;
        $length   = $request->getVar('length') ?? 10;
        $search   = $request->getVar('search')['value'] ?? '';
        $location = $request->getVar('location') ?? '';

        $builder = $this->model->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('setting_key', $search)
                ->orLike('label', $search)
                ->orLike('setting_value', $search)
                ->groupEnd();
        }

        if (!empty($location)) {
            $builder->where('location', $location);
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal    = $this->model->countAllResults();

        $builder->orderBy('location', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $formatted = [];
        foreach ($data as $row) {
            $preview = mb_strimwidth(strip_tags($row['setting_value'] ?? ''), 0, 60, '...');

            $action = '
                <a href="' . base_url('admin/settings/edit/' . $row['id']) . '"
                   class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
            ';

            $formatted[] = [
                '<span class="badge text-bg-dark">' . esc($row['setting_key']) . '</span>',
                '<div class="fw-bold small">' . esc($row['label'] ?? $row['setting_key']) . '</div>
                 <span class="badge text-bg-light">' . esc($row['location'] ?? 'global') . '</span>',
                '<div class="small text-muted">' . esc($preview) . '</div>',
                '<span class="badge text-bg-info">' . esc($row['type'] ?? 'text') . '</span>',
                $action,
            ];
        }

        return $this->response->setJSON([
            'draw'            => $request->getVar('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    // 3. Form Create
    public function create()
    {
        return view('admin/settings/form', ['item' => null]);
    }

    // 4. Form Edit
    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return redirect()->to('/admin/settings')->with('error', 'Setting tidak ditemukan.');
        }
        return view('admin/settings/form', ['item' => $item]);
    }

    // 5. Save
    public function save()
    {
        $id   = $this->request->getPost('id');
        $type = $this->request->getPost('type') ?? 'text';

        $oldData = !empty($id) ? $this->model->find($id) : null;

        $data = [
            'setting_key'   => $this->request->getPost('setting_key'),
            'label'         => $this->request->getPost('label'),
            'location'      => $this->request->getPost('location') ?? 'global',
            'type'          => $type,
            'sort_order'    => $this->request->getPost('sort_order') ?? 0,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        // Upload gambar jika type = image
        $file = $this->request->getFile('setting_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $dir = FCPATH . $this->uploadPath;
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $file->getRandomName();
            $file->move($dir, $newName);

            // Unlink file lama
            if ($oldData && !empty($oldData['setting_value'])) {
                $oldPath = FCPATH . ltrim($oldData['setting_value'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) @unlink($oldPath);
            }

            $data['setting_value'] = $this->uploadPath . $newName;
        } else {
            $data['setting_value'] = $this->request->getPost('setting_value');
        }

        if (!empty($id)) $data['id'] = $id;

        $this->model->save($data);

        return redirect()->to('/admin/settings')->with('success', 'Setting berhasil disimpan.');
    }

    // 6. Delete
    public function delete($id)
    {
        $item = $this->model->find($id);
        if ($item && $item['type'] === 'image' && !empty($item['setting_value'])) {
            $path = FCPATH . ltrim($item['setting_value'], '/');
            if (file_exists($path) && is_file($path)) @unlink($path);
        }
        $this->model->delete($id);
        return redirect()->to('/admin/settings')->with('success', 'Setting dihapus.');
    }
}

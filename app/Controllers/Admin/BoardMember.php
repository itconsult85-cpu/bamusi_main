<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BoardMemberModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BoardMember extends BaseController
{
    protected $boardModel;
    protected $uploadPath = 'uploads/board/';

    public function __construct()
    {
        $this->boardModel = new BoardMemberModel();
    }

    /* ============ 1. INDEX ============ */
    public function index()
    {
        return view('admin/board_members/index');
    }

    /* ============ 2. AJAX DATATABLES ============ */
    public function ajaxData()
    {
        $request = \Config\Services::request();

        $start  = $request->getVar('start') ?? 0;
        $length = $request->getVar('length') ?? 10;
        $search = $request->getVar('search')['value'] ?? '';
        $group  = $request->getVar('group') ?? '';

        $builder = $this->boardModel->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('role', $search)
                ->orLike('group_name', $search)
                ->groupEnd();
        }

        if (!empty($group)) {
            $builder->where('group_name', $group);
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal    = $this->boardModel->countAllResults();

        $builder->orderBy('group_order', 'ASC')
            ->orderBy('member_order', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $formatted = [];
        foreach ($data as $row) {
            $status = $row['published']
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Draft</span>';

            $trans = !empty($row['role_en'])
                ? '<span class="text-success"><i class="fas fa-check"></i></span>'
                : '<span class="text-danger"><i class="fas fa-times"></i></span>';

            $photo = !empty($row['photo_url']) && file_exists(FCPATH . $row['photo_url'])
                ? '<img src="' . base_url($row['photo_url']) . '" style="width:45px;height:45px;object-fit:cover;border-radius:50%;">'
                : '<span class="badge text-bg-secondary">-</span>';

            $action = '
                <a href="' . base_url('admin/board/edit/' . $row['id']) . '" class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="' . base_url('admin/board/delete/' . $row['id']) . '" class="btn btn-sm btn-danger"
                   onclick="return confirm(\'Hapus pengurus ini?\')">
                    <i class="fas fa-trash"></i>
                </a>
            ';

            $formatted[] = [
                $photo,
                '<div class="fw-bold">' . esc($row['name']) . '</div>',
                '<span class="badge text-bg-light">' . esc($row['group_name'] ?? 'Lainnya') . '</span>',
                esc($row['role']),
                '<span class="fw-bold">' . (int)$row['group_order'] . ' / ' . (int)$row['member_order'] . '</span>',
                $trans,
                $status,
                $action,
            ];
        }

        return $this->response->setJSON([
            'draw'            => (int) $request->getVar('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    /* ============ 3. FORM CREATE ============ */
    public function create()
    {
        return view('admin/board_members/form', ['item' => null]);
    }

    /* ============ 4. FORM EDIT ============ */
    public function edit($id)
    {
        $item = $this->boardModel->find($id);
        if (!$item) {
            return redirect()->to('/admin/board')->with('error', 'Pengurus tidak ditemukan.');
        }
        return view('admin/board_members/form', ['item' => $item]);
    }

    /* ============ 5. SAVE ============ */
    public function save()
    {
        $id = $this->request->getPost('id');

        $roleId      = $this->request->getPost('role');
        $noteId      = $this->request->getPost('note');
        $groupNameId = $this->request->getPost('group_name');

        $oldData = !empty($id) ? $this->boardModel->find($id) : null;
        $roleEn      = $this->translateText($roleId, $oldData['role_en'] ?? null);
        $noteEn      = $this->translateText($noteId, $oldData['note_en'] ?? null);
        $groupNameEn = $this->translateText($groupNameId, $oldData['group_name_en'] ?? null);

        $data = [
            'name'          => $this->request->getPost('name'),
            'group_name'    => $groupNameId,
            'group_name_en' => $groupNameEn,
            'group_order'   => $this->request->getPost('group_order') ?? 0,
            'member_order'  => $this->request->getPost('member_order') ?? 0,
            'role'          => $roleId,
            'role_en'       => $roleEn,
            'note'          => $noteId,
            'note_en'       => $noteEn,
            'sort_order'    => $this->request->getPost('sort_order') ?? 0,
            'published'     => $this->request->getPost('published') ?? 1,
        ];

        // Upload foto
        $file = $this->request->getFile('photo');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $validation = $this->validate([
                'photo' => [
                    'rules'  => 'is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]|max_size[photo,2048]',
                    'errors' => [
                        'is_image'  => 'File harus berupa gambar.',
                        'mime_in'   => 'Format harus JPG, PNG, atau WEBP.',
                        'max_size'  => 'Ukuran maksimal 2MB.',
                    ],
                ],
            ]);

            if (!$validation) {
                return redirect()->back()->withInput()
                    ->with('error', implode(' ', $this->validator->getErrors()));
            }

            $uploadDir = FCPATH . $this->uploadPath;
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);

            // Unlink foto lama
            if ($oldData && !empty($oldData['photo_url'])) {
                $oldFilePath = FCPATH . ltrim($oldData['photo_url'], '/');
                if (file_exists($oldFilePath) && is_file($oldFilePath)) @unlink($oldFilePath);
            }

            $data['photo_url'] = $this->uploadPath . $newName;
        }

        if (!empty($id)) $data['id'] = $id;
        $this->boardModel->save($data);

        return redirect()->to('/admin/board')->with('success', 'Data pengurus disimpan.');
    }

    /* ============ 6. DELETE ============ */
    public function delete($id)
    {
        $item = $this->boardModel->find($id);
        if ($item) {
            if (!empty($item['photo_url'])) {
                $filePath = FCPATH . ltrim($item['photo_url'], '/');
                if (file_exists($filePath) && is_file($filePath)) @unlink($filePath);
            }
            $this->boardModel->delete($id);
        }
        return redirect()->to('/admin/board')->with('success', 'Data pengurus dihapus.');
    }
}

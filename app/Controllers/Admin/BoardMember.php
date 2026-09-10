<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BoardMemberModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BoardMember extends BaseController
{
    protected $boardModel;
    protected $uploadPath = 'uploads/board/'; // relatif terhadap FCPATH

    public function __construct()
    {
        $this->boardModel = new BoardMemberModel();
    }

    public function index()
    {
        $data['items'] = $this->boardModel->orderBy('sort_order', 'ASC')->findAll();
        return view('admin/board_members/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');

        $roleId      = $this->request->getPost('role');
        $noteId      = $this->request->getPost('note');
        $groupNameId = $this->request->getPost('group_name');

        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
        $tr->setSource('id');

        try {
            $roleEn      = !empty($roleId)      ? $tr->translate($roleId)      : null;
            $noteEn      = !empty($noteId)      ? $tr->translate($noteId)      : null;
            $groupNameEn = !empty($groupNameId) ? $tr->translate($groupNameId) : null;
        } catch (\Exception $e) {
            $roleEn = $noteEn = $groupNameEn = null;
        }

        // Ambil data lama jika edit
        $oldData = !empty($id) ? $this->boardModel->find($id) : null;
        // Susun data
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

        // === HANDLE UPLOAD FOTO ===
        $file = $this->request->getFile('photo');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validasi
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

            // Pastikan folder ada
            $uploadDir = FCPATH . $this->uploadPath;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate nama file unik
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);

            // HAPUS FOTO LAMA (jika ada)
            if ($oldData && !empty($oldData['photo_url'])) {
                $oldFilePath = FCPATH . ltrim($oldData['photo_url'], '/');
                if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            $data['photo_url'] = $this->uploadPath . $newName;
        }
        // Jika tidak upload baru, photo_url lama tetap dipertahankan (tidak diubah)

        if (!empty($id)) $data['id'] = $id;

        $this->boardModel->save($data);
        return redirect()->to('/admin/board')->with('success', 'Data pengurus disimpan.');
    }

    /**
     * Opsional: Hapus foto saja tanpa hapus data
     */
    public function deletePhoto($id)
    {
        $item = $this->boardModel->find($id);
        if (!$item) {
            return redirect()->to('/admin/board')->with('error', 'Data tidak ditemukan.');
        }

        if (!empty($item['photo_url'])) {
            $filePath = FCPATH . ltrim($item['photo_url'], '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
            $this->boardModel->update($id, ['photo_url' => null]);
        }

        return redirect()->to('/admin/board')->with('success', 'Foto berhasil dihapus.');
    }
}

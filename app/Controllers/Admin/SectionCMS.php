<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageSectionModel;
use App\Models\HomepageSectionItemModel;
use App\Models\WebsiteTextModel;
use App\Models\HomepageSectionBlockModel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SectionCMS extends BaseController
{
    protected $sectionModel;
    protected $itemModel;
    protected $textModel;
    protected $blockModel;

    public function __construct()
    {
        $this->sectionModel = new PageSectionModel();
        $this->itemModel = new HomepageSectionItemModel();
        $this->textModel = new WebsiteTextModel();
        $this->blockModel = new HomepageSectionBlockModel();
    }

    // 1. Menampilkan Halaman Tabel (DataTables)
    public function index()
    {
        return view('admin/sections/index');
    }

    // 2. Memproses DataTables Server-Side (AJAX)
    public function ajaxData()
    {
        $request = \Config\Services::request();

        // Parameter bawaan DataTables
        $start = $request->getVar('start') ?? 0;
        $length = $request->getVar('length') ?? 10;
        $search = $request->getVar('search')['value'] ?? '';

        $builder = $this->sectionModel->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('section_name', $search)
                ->orLike('section_key', $search)
                ->orLike('title', $search)
                ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $recordsTotal = $this->sectionModel->countAllResults();

        $builder->orderBy('sort_order', 'ASC');
        $builder->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $formattedData = [];
        foreach ($data as $row) {
            $status = $row['published'] ? '<span class="badge text-bg-success">Publik</span>' : '<span class="badge text-bg-secondary">Draft</span>';
            $trans = !empty($row['title_en']) ? '<span class="text-success"><i class="fas fa-check"></i> Ada</span>' : '<span class="text-danger"><i class="fas fa-times"></i> Kosong</span>';

            $action = '
                <a href="' . base_url('admin/sections/edit/' . $row['id']) . '" class="btn btn-sm btn-warning text-white">
                    <i class="fas fa-edit"></i> Edit
                </a>
            ';

            $formattedData[] = [
                '<span class="badge text-bg-dark">' . esc($row['section_key']) . '</span>',
                esc($row['section_name']),
                esc($row['title']),
                $trans,
                $status,
                $action
            ];
        }

        return $this->response->setJSON([
            'draw'            => $request->getVar('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formattedData
        ]);
    }

    // 3. Menampilkan Halaman Form Tambah
    public function create()
    {
        return view('admin/sections/form');
    }

    // 4. Menampilkan Halaman Form Edit
    public function edit($id)
    {
        $data['section'] = $this->sectionModel->find($id);
        if (!$data['section']) {
            return redirect()->to('/admin/sections')->with('error', 'Data tidak ditemukan.');
        }
        $data['sectionItems'] = $this->itemModel->where('section_key', $data['section']['section_key'])->orderBy('sort_order', 'ASC')->findAll();
        $data['sectionTexts'] = $this->textModel->where('section_key', $data['section']['section_key'])->orderBy('sort_order', 'ASC')->findAll();
        $data['sectionBlocks'] = \Config\Database::connect()->tableExists('homepage_section_blocks')
            ? $this->blockModel->where('section_id', $id)->orderBy('sort_order', 'ASC')->findAll()
            : [];
        foreach ($data['sectionBlocks'] as &$block) {
            $block['data'] = json_decode($block['block_data'] ?? '', true) ?: [];
        }
        unset($block);
        return view('admin/sections/form', $data);
    }

    public function itemCreate($sectionKey)
    {
        $section = $this->sectionModel->where('section_key', $sectionKey)->first();
        if (!$section) return redirect()->to('/admin/sections')->with('error', 'Section tidak ditemukan.');
        return view('admin/homepage_content/form', ['item' => null, 'sectionKey' => $sectionKey, 'sectionLabel' => $section['section_name'], 'backUrl' => base_url('admin/sections/edit/' . $section['id'])]);
    }

    public function itemEdit($id)
    {
        $item = $this->itemModel->find($id);
        if (!$item) return redirect()->to('/admin/sections')->with('error', 'Item section tidak ditemukan.');
        $section = $this->sectionModel->where('section_key', $item['section_key'])->first();
        return view('admin/homepage_content/form', ['item' => $item, 'sectionKey' => $item['section_key'], 'sectionLabel' => $section['section_name'] ?? $item['section_key'], 'backUrl' => base_url('admin/sections/edit/' . ($section['id'] ?? ''))]);
    }

    public function itemDelete($id)
    {
        $item = $this->itemModel->find($id);
        $sectionKey = $item['section_key'] ?? 'feature';
        if ($item) {
            if (!empty($item['media_url'])) {
                $mediaPath = FCPATH . ltrim($item['media_url'], '/');
                if (is_file($mediaPath)) @unlink($mediaPath);
            }
            $this->itemModel->delete($id);
        }
        $section = $this->sectionModel->where('section_key', $sectionKey)->first();
        return redirect()->to('/admin/sections/edit/' . ($section['id'] ?? ''))->with('success', 'Item section berhasil dihapus.');
    }

    public function blockCreate($sectionKey)
    {
        $section = $this->sectionModel->where('section_key', $sectionKey)->first();
        if (!$section) return redirect()->to('/admin/sections')->with('error', 'Section tidak ditemukan.');
        return view('admin/sections/block_form', ['block' => null, 'section' => $section]);
    }

    public function blockEdit($id)
    {
        $block = $this->blockModel->find($id);
        if (!$block) return redirect()->to('/admin/sections')->with('error', 'Blok tidak ditemukan.');
        $section = $this->sectionModel->find($block['section_id']);
        if (!$section) return redirect()->to('/admin/sections')->with('error', 'Section tidak ditemukan.');
        $block['data'] = json_decode($block['block_data'] ?? '', true) ?: [];
        return view('admin/sections/block_form', ['block' => $block, 'section' => $section]);
    }

    public function blockSave()
    {
        if (!\Config\Database::connect()->tableExists('homepage_section_blocks')) {
            return redirect()->to('/admin/sections')->with('error', 'Migration builder belum dijalankan.');
        }
        $id = (int) $this->request->getPost('id');
        $sectionId = (int) $this->request->getPost('section_id');
        $section = $this->sectionModel->find($sectionId);
        if (!$section) return redirect()->to('/admin/sections')->with('error', 'Section tidak ditemukan.');

        $allowed = ['rich_text', 'image_text', 'hero_slider', 'cards', 'horizontal_slider', 'logo_grid', 'media_tabs', 'collection', 'quote', 'cta', 'spacer', 'join_form'];
        $type = trim((string) $this->request->getPost('block_type'));
        if (!in_array($type, $allowed, true)) return redirect()->back()->withInput()->with('error', 'Jenis blok tidak valid.');

        $old = $id ? $this->blockModel->find($id) : null;
        if ($old && (int) $old['section_id'] !== $sectionId) return redirect()->back()->with('error', 'Blok tidak cocok dengan section.');
        $rawItems = trim((string) $this->request->getPost('items_json'));
        $items = [];
        if ($rawItems !== '') {
            $items = json_decode($rawItems, true);
            if (!is_array($items)) return redirect()->back()->withInput()->with('error', 'Data item JSON tidak valid.');
        }
        $title = trim((string) $this->request->getPost('title'));
        $body = trim((string) $this->request->getPost('body'));
        $data = [
            'variant' => trim((string) $this->request->getPost('variant')),
            'source' => trim((string) $this->request->getPost('source')),
            'limit' => max(1, min(24, (int) ($this->request->getPost('limit') ?: 4))),
            'columns' => max(1, min(6, (int) ($this->request->getPost('columns') ?: 3))),
            'image_url' => trim((string) $this->request->getPost('image_url')),
            'image_position' => trim((string) $this->request->getPost('image_position')),
            'title' => $title,
            'title_en' => $this->translateText($title, null),
            'body' => $body,
            'body_en' => $this->translateText($body, null),
            'button_label' => trim((string) $this->request->getPost('button_label')),
            'button_url' => trim((string) $this->request->getPost('button_url')),
            'items' => $items,
        ];
        $record = [
            'section_id' => $sectionId,
            'section_key' => $section['section_key'],
            'block_type' => $type,
            'block_data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'block_data_en' => json_encode(['title' => $data['title_en'], 'body' => $data['body_en']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'published' => $this->request->getPost('published') ? 1 : 0,
        ];
        if ($id) $record['id'] = $id;
        $this->blockModel->save($record);
        return redirect()->to(base_url('admin/sections/edit/' . $sectionId))->with('success', 'Blok layout berhasil disimpan.');
    }

    public function blockDelete($id)
    {
        $block = $this->blockModel->find($id);
        if (!$block) return redirect()->to('/admin/sections')->with('error', 'Blok tidak ditemukan.');
        $this->blockModel->delete($id);
        return redirect()->to(base_url('admin/sections/edit/' . $block['section_id']))->with('success', 'Blok layout berhasil dihapus.');
    }

    public function textCreate($sectionKey)
    {
        $section = $this->sectionModel->where('section_key', $sectionKey)->first();
        if (!$section) return redirect()->to('/admin/sections')->with('error', 'Section tidak ditemukan.');

        return view('admin/sections/text_form', [
            'item' => null,
            'section' => $section,
            'backUrl' => base_url('admin/sections/edit/' . $section['id']),
        ]);
    }

    public function textEdit($id)
    {
        $item = $this->textModel->find($id);
        if (!$item || empty($item['section_key'])) return redirect()->to('/admin/sections')->with('error', 'Teks section tidak ditemukan.');
        $section = $this->sectionModel->where('section_key', $item['section_key'])->first();
        if (!$section) return redirect()->to('/admin/sections')->with('error', 'Section tidak ditemukan.');

        return view('admin/sections/text_form', [
            'item' => $item,
            'section' => $section,
            'backUrl' => base_url('admin/sections/edit/' . $section['id']),
        ]);
    }

    public function textSave()
    {
        $id = (int) $this->request->getPost('id');
        $sectionKey = trim((string) $this->request->getPost('section_key'));
        $section = $this->sectionModel->where('section_key', $sectionKey)->first();
        if (!$section) return redirect()->to('/admin/sections')->with('error', 'Section tidak ditemukan.');

        $old = $id ? $this->textModel->find($id) : null;
        if ($old) $sectionKey = (string) $old['section_key'];
        $textKey = trim((string) $this->request->getPost('text_key'));
        $value = trim((string) $this->request->getPost('value'));
        $label = trim((string) $this->request->getPost('label'));
        if ($textKey === '' || $label === '' || $value === '') {
            return redirect()->back()->withInput()->with('error', 'Key, label, dan nilai teks wajib diisi.');
        }

        $duplicate = $this->textModel->where('text_key', $textKey)->where('id !=', $id)->first();
        if ($duplicate) return redirect()->back()->withInput()->with('error', 'Text key sudah digunakan. Gunakan key yang unik.');

        $data = [
            'text_key' => $textKey,
            'label' => $label,
            'location' => 'homepage',
            'section_key' => $sectionKey,
            'value' => $value,
            'value_en' => $this->translateText($value, $old['value_en'] ?? null),
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'published' => $this->request->getPost('published') ? 1 : 0,
        ];
        if ($id) $data['id'] = $id;
        $this->textModel->save($data);

        return redirect()->to(base_url('admin/sections/edit/' . $section['id']))->with('success', 'Teks section berhasil disimpan.');
    }

    public function textDelete($id)
    {
        $item = $this->textModel->find($id);
        if (!$item || empty($item['section_key'])) return redirect()->to('/admin/sections')->with('error', 'Teks section tidak ditemukan.');
        $section = $this->sectionModel->where('section_key', $item['section_key'])->first();
        $this->textModel->delete($id);
        return redirect()->to(base_url('admin/sections/edit/' . ($section['id'] ?? '')))->with('success', 'Teks section berhasil dihapus.');
    }

    // 5. Menyimpan Data (dari Create atau Edit)
    public function save()
    {
        $id = $this->request->getPost('id');

        // Tangkap semua input teks ID
        $kickerId   = $this->request->getPost('kicker');
        $titleId    = $this->request->getPost('title');
        $subId      = $this->request->getPost('subtitle');
        $quoteId    = $this->request->getPost('quote');
        $contentId  = $this->request->getPost('content');
        $btnLabelId = $this->request->getPost('button_label');
        $sectionKey = (string) $this->request->getPost('section_key');
        $buttonPosition = $this->request->getPost('button_position') ?: 'center';
        $buttonLocation = $this->request->getPost('button_location') ?: 'bottom';
        if (!in_array($buttonPosition, ['left', 'center', 'right'], true)) $buttonPosition = 'center';
        if (!in_array($buttonLocation, ['top', 'bottom'], true)) $buttonLocation = 'bottom';

        $oldData = !empty($id) ? $this->sectionModel->find($id) : null;
        // Kunci section yang sudah dipakai template homepage tidak boleh berubah.
        if ($oldData) {
            $sectionKey = (string) $oldData['section_key'];
        }
        $kickerEn   = $this->translateText($kickerId, $oldData['kicker_en'] ?? null);
        $titleEn    = $this->translateText($titleId, $oldData['title_en'] ?? null);
        $subEn      = $this->translateText($subId, $oldData['subtitle_en'] ?? null);
        $quoteEn    = $this->translateText($quoteId, $oldData['quote_en'] ?? null);
        $contentEn  = $this->translateText($contentId, $oldData['content_en'] ?? null);
        $btnLabelEn = $this->translateText($btnLabelId, $oldData['button_label_en'] ?? null);

        // Siapkan Data Lengkap
        $data = [
            'section_name'    => $this->request->getPost('section_name'),
            'section_key'     => $sectionKey,
            'kicker'          => $kickerId,
            'kicker_en'       => $kickerEn,
            'title'           => $titleId,
            'title_en'        => $titleEn,
            'subtitle'        => $subId,
            'subtitle_en'     => $subEn,
            'quote'           => $quoteId,
            'quote_en'        => $quoteEn,
            'content'         => $contentId,
            'content_en'      => $contentEn,
            'button_label'    => $btnLabelId,
            'button_label_en' => $btnLabelEn,
            'button_url'      => $this->request->getPost('button_url'),
            'button_position' => $buttonPosition,
            'button_location' => $buttonLocation,
            'cards_visible'   => $this->request->getPost('cards_visible') ?? 1,
            'cards_limit'     => max(1, min(12, (int) ($this->request->getPost('cards_limit') ?: 5))),
            'cards_columns'   => max(2, min(6, (int) ($this->request->getPost('cards_columns') ?: 5))),
            'layout_mode'     => in_array($this->request->getPost('layout_mode'), ['legacy', 'builder'], true) ? $this->request->getPost('layout_mode') : 'legacy',
            'layout_options'  => trim((string) $this->request->getPost('layout_options')) ?: null,
            'published'       => $this->request->getPost('published') ?? 1
        ];

        if ($sectionKey === 'visi') {
            $visionId = $this->request->getPost('vision');
            $missionId = $this->request->getPost('mission');
            $data['vision'] = $visionId;
            $data['vision_en'] = $this->translateText($visionId, $oldData['vision_en'] ?? null);
            $data['mission'] = $missionId;
            $data['mission_en'] = $this->translateText($missionId, $oldData['mission_en'] ?? null);
        }

        $layoutOptions = trim((string) $this->request->getPost('layout_options'));
        if ($layoutOptions !== '') {
            json_decode($layoutOptions, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withInput()->with('error', 'Opsi Layout JSON tidak valid.');
            }
        }

        // LOGIKA UPLOAD GAMBAR/VIDEO
        $mediaFile = $this->request->getFile('media_url');
        if ($mediaFile && $mediaFile->isValid() && !$mediaFile->hasMoved()) {
            if (! $this->validate(['media_url' => 'mime_in[media_url,image/jpg,image/jpeg,image/png,image/webp,video/mp4]|max_size[media_url,10240]'])) {
                return redirect()->back()->withInput()->with('error', 'Media section harus JPG, PNG, WEBP, atau MP4 dengan ukuran maksimal 10 MB.');
            }
            // Data lama (kalau edit)
            $oldData = !empty($id) ? $this->sectionModel->find($id) : null;

            // Pastikan folder ada
            $dir = FCPATH . 'uploads/sections';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $newName = $mediaFile->getRandomName();
            $mediaFile->move($dir, $newName);

            // Hapus file lama
            if ($oldData && !empty($oldData['media_url'])) {
                $oldPath = FCPATH . ltrim($oldData['media_url'], '/');
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['media_url'] = '/uploads/sections/' . $newName;
        }

        if (!empty($id)) {
            $data['id'] = $id;
        }

        $this->sectionModel->save($data);
        return redirect()->to('/admin/sections')->with('success', 'Section berhasil disimpan dan diterjemahkan penuh.');
    }
}

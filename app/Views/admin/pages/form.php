<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<?php
$isEdit = isset($page) && !empty($page);

$val = function ($key, $default = '') use ($page) {
    if (empty($page) || !isset($page[$key])) return $default;
    return esc($page[$key]);
};

$chk = function ($key, $default = 1) use ($page) {
    if (empty($page)) return $default ? 'checked' : '';
    return (!empty($page[$key])) ? 'checked' : '';
};
?>

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0"><?= $isEdit ? 'Edit: ' . esc($page['title']) : 'Tambah Halaman Baru'; ?></h3>
        <a href="<?= base_url('admin/pages'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/pages/save'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $isEdit ? $page['id'] : ''; ?>">
                <?= csrf_field() ?>

            <div class="row">
                <!-- KOLOM KIRI -->
                <div class="col-lg-8">

                    <!-- Konten Utama -->
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Konten Utama</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg"
                                    value="<?= $val('title'); ?>" required
                                    placeholder="Contoh: Tentang Kami">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Slug URL</label>
                                    <input type="text" name="slug" class="form-control"
                                        value="<?= $val('slug'); ?>"
                                        placeholder="otomatis dari judul">
                                    <small class="text-muted">Kosongkan untuk generate otomatis.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="<?= $val('sort_order', 0); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Excerpt / Ringkasan</label>
                                <textarea name="excerpt" class="form-control" rows="2"
                                    placeholder="Ringkasan singkat halaman..."><?= $val('excerpt'); ?></textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Konten Lengkap</label>
                                <textarea name="body" class="form-control summernote"><?= $isEdit ? $page['body'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Header Section -->
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Header Halaman (Hero)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Kicker (Label Kecil)</label>
                                <input type="text" name="header_kicker" class="form-control"
                                    value="<?= $val('header_kicker'); ?>"
                                    placeholder="Contoh: TENTANG KAMI">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Header Title</label>
                                <input type="text" name="header_title" class="form-control"
                                    value="<?= $val('header_title'); ?>"
                                    placeholder="Judul besar di header halaman">
                                <small class="text-muted">Kalau kosong, akan pakai Judul halaman.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Header Intro</label>
                                <textarea name="header_intro" class="form-control" rows="2"
                                    placeholder="Paragraf pengantar di bawah judul..."><?= $val('header_intro'); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Header Logo</label>
                                <?php if ($isEdit && !empty($page['header_logo_url'])): ?>
                                    <div class="mb-2">
                                        <img src="<?= base_url($page['header_logo_url']); ?>"
                                            class="img-thumbnail" style="max-height:80px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="header_logo_url" class="form-control" accept="image/*">
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            name="header_show_logo" value="1"
                                            id="hsLogo" <?= $chk('header_show_logo', 1); ?>>
                                        <label class="form-check-label" for="hsLogo">Tampilkan Logo</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            name="header_show_intro" value="1"
                                            id="hsIntro" <?= $chk('header_show_intro', 1); ?>>
                                        <label class="form-check-label" for="hsIntro">Tampilkan Intro</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            name="header_show_back" value="1"
                                            id="hsBack" <?= $chk('header_show_back', 1); ?>>
                                        <label class="form-check-label" for="hsBack">Tombol Kembali</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="card card-dark card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">SEO Meta</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    value="<?= $val('meta_title'); ?>">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="2"><?= $val('meta_description'); ?></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN -->
                <div class="col-lg-4">

                    <!-- Publish -->
                    <div class="card card-secondary mb-4">
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="published" value="1"
                                    id="publishCheck" <?= $chk('published', 1); ?>>
                                <label class="form-check-label fw-bold" for="publishCheck">Tampilkan di Web</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-save me-1"></i> Simpan & Terjemahkan
                            </button>
                        </div>
                    </div>

                    <!-- Menu -->
                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Pengaturan Menu</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Parent Menu</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">— Menu Utama —</option>
                                    <?php
                                    $dbP = \Config\Database::connect();
                                    $parents = $dbP->table('pages')
                                        ->where('published', 1)->where('show_in_menu', 1)
                                        ->where('parent_id', null)
                                        ->orderBy('sort_order', 'ASC')->get()->getResultArray();
                                    $cur = $isEdit ? ($page['parent_id'] ?? '') : '';
                                    foreach ($parents as $p):
                                        if ($isEdit && $p['id'] == $page['id']) continue;
                                    ?>
                                        <option value="<?= $p['id']; ?>" <?= ($cur == $p['id']) ? 'selected' : ''; ?>>
                                            <?= esc($p['title']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_mega" value="1"
                                    id="isMega" <?= $chk('is_mega', 0); ?>>
                                <label class="form-check-label" for="isMega">Tampilkan sebagai Mega Menu</label>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Deskripsi Menu (Mega)</label>
                                <textarea name="menu_desc" class="form-control" rows="2"><?= $val('menu_desc'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Gambar Utama</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($isEdit && !empty($page['image_url'])): ?>
                                <img src="<?= base_url($page['image_url']); ?>"
                                    class="img-fluid rounded mb-3" style="max-height:150px;">
                            <?php endif; ?>
                            <input type="file" name="image_url" class="form-control" accept="image/*">
                            <small class="text-muted">Maks 2MB. Rasio 16:9 disarankan.</small>
                        </div>
                    </div>

                </div>
            </div>
            <!-- BLOK KONTEN DINAMIS -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div><h5 class="m-0">Blok Konten Dinamis</h5><small class="text-muted">Susun teks, gambar, kartu, kutipan, tombol, atau jarak tanpa mengubah kode.</small></div>
                    <div class="d-flex gap-2"><select id="blockType" class="form-select form-select-sm"><option value="rich_text">Teks</option><option value="image">Gambar</option><option value="cards">Kartu</option><option value="quote">Kutipan</option><option value="cta">Tombol CTA</option><option value="spacer">Jarak</option></select><button type="button" id="addBlock" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</button></div>
                </div>
                <div class="card-body"><div id="blockList"></div><div id="blockEmpty" class="text-center text-muted border rounded p-4">Belum ada blok. Tambahkan blok pertama untuk membangun layout halaman.</div></div>
            </div>
            <input type="hidden" name="blocks_json" id="blocksJson" value="<?= esc(json_encode($blocks ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?>">
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(function() {
        $('.summernote').summernote({
            height: 400,
            placeholder: 'Tulis konten lengkap di sini...',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
<script>
(() => {
    const list = document.querySelector('#blockList'), empty = document.querySelector('#blockEmpty'), output = document.querySelector('#blocksJson');
    let blocks = [];
    try { blocks = JSON.parse(output.value || '[]').map(b => ({type:b.block_type || b.type, data:b.data || {}, published:Number(b.published ?? 1)})); } catch (e) { blocks = []; }
    const esc = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
    const val = (data, key) => esc(data[key] || '');
    function fields(block, index) {
        const d = block.data || {};
        if (block.type === 'rich_text') return `<textarea class="form-control block-field" data-key="html" rows="5" placeholder="Tulis isi blok...">${val(d,'html')}</textarea>`;
        if (block.type === 'image') return `<div class="row g-2"><div class="col-md-8"><input class="form-control block-field" data-key="url" value="${val(d,'url')}" placeholder="URL gambar atau /uploads/..." /></div><div class="col-md-4"><input class="form-control block-field" data-key="alt" value="${val(d,'alt')}" placeholder="Alt text" /></div><div class="col-md-8"><input class="form-control block-field" data-key="caption" value="${val(d,'caption')}" placeholder="Caption (opsional)" /></div><div class="col-md-4"><select class="form-select block-field" data-key="width"><option value="container" ${d.width==='container'?'selected':''}>Lebar normal</option><option value="wide" ${d.width==='wide'?'selected':''}>Lebar penuh</option></select></div></div>`;
        if (block.type === 'cards') return `<div class="row g-2"><div class="col-md-6"><input class="form-control block-field" data-key="title" value="${val(d,'title')}" placeholder="Judul section kartu" /></div><div class="col-md-6"><input class="form-control block-field" data-key="columns" value="${val(d,'columns') || '3'}" type="number" min="1" max="4" placeholder="Kolom" /></div><div class="col-12"><textarea class="form-control block-field" data-key="items" rows="5" placeholder="Satu kartu per baris: Judul | Deskripsi | Link">${val(d,'items')}</textarea><small class="text-muted">Contoh: Program Kader | Deskripsi singkat | /program</small></div></div>`;
        if (block.type === 'quote') return `<div class="row g-2"><div class="col-md-8"><textarea class="form-control block-field" data-key="text" rows="3" placeholder="Isi kutipan">${val(d,'text')}</textarea></div><div class="col-md-4"><input class="form-control block-field" data-key="author" value="${val(d,'author')}" placeholder="Sumber/nama" /></div></div>`;
        if (block.type === 'cta') return `<div class="row g-2"><div class="col-md-6"><input class="form-control block-field" data-key="label" value="${val(d,'label')}" placeholder="Label tombol" /></div><div class="col-md-6"><input class="form-control block-field" data-key="url" value="${val(d,'url')}" placeholder="URL tombol" /></div><div class="col-12"><textarea class="form-control block-field" data-key="text" rows="2" placeholder="Pesan CTA (opsional)">${val(d,'text')}</textarea></div></div>`;
        return `<select class="form-select block-field" data-key="height"><option value="40" ${d.height==='40'?'selected':''}>Kecil</option><option value="80" ${d.height==='80'?'selected':''}>Sedang</option><option value="140" ${d.height==='140'?'selected':''}>Besar</option></select>`;
    }
    function render() { empty.classList.toggle('d-none', blocks.length > 0); list.innerHTML = blocks.map((b,i) => `<div class="border rounded-3 p-3 mb-3 bg-light block-item" data-index="${i}"><div class="d-flex justify-content-between align-items-center mb-3"><strong><i class="fas fa-grip-vertical me-2 text-muted"></i>${b.type.replace('_',' ')}</strong><div><button type="button" class="btn btn-sm btn-outline-secondary move-up">↑</button><button type="button" class="btn btn-sm btn-outline-secondary move-down">↓</button><button type="button" class="btn btn-sm btn-outline-danger remove-block">Hapus</button></div></div>${fields(b,i)}</div>`).join(''); output.value = JSON.stringify(blocks); }
    function sync() { document.querySelectorAll('.block-item').forEach((item,i) => item.querySelectorAll('.block-field').forEach(field => blocks[i].data[field.dataset.key] = field.value)); output.value = JSON.stringify(blocks); }
    document.querySelector('#addBlock').addEventListener('click', () => { sync(); blocks.push({type:document.querySelector('#blockType').value,data:{},published:1}); render(); });
    list.addEventListener('input', sync);
    list.addEventListener('click', e => { const item=e.target.closest('.block-item'); if(!item)return; const i=Number(item.dataset.index); if(e.target.closest('.remove-block')) blocks.splice(i,1); if(e.target.closest('.move-up')&&i>0) [blocks[i-1],blocks[i]]=[blocks[i],blocks[i-1]]; if(e.target.closest('.move-down')&&i<blocks.length-1) [blocks[i+1],blocks[i]]=[blocks[i],blocks[i+1]]; render(); });
    document.querySelector('form').addEventListener('submit', sync); render();
})();
</script>

<?= $this->endSection(); ?>

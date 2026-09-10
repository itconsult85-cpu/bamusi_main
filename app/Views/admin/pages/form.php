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

<?= $this->endSection(); ?>
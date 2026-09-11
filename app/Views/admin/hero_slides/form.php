<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<?php
$isEdit = !empty($item);
$val = function ($key, $default = '') use ($item) {
    if (empty($item) || !isset($item[$key])) return $default;
    return esc($item[$key]);
};
$chk = function ($key, $default = 1) use ($item) {
    if (empty($item)) return $default ? 'checked' : '';
    return (!empty($item[$key])) ? 'checked' : '';
};
?>

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0"><?= $isEdit ? 'Edit Slide' : 'Tambah Slide Baru'; ?></h3>
        <a href="<?= base_url('admin/hero-slides'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/hero-slides/save'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $isEdit ? $item['id'] : ''; ?>">

            <div class="row">
                <div class="col-lg-8">

                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Konten Slide</h5>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">Kicker (Label Kecil di Atas)</label>
                                    <input type="text" name="kicker" class="form-control"
                                        value="<?= $val('kicker'); ?>"
                                        placeholder="Contoh: PP BAMUSI / 2025—2030">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Warna Kicker</label>
                                    <input type="color" name="kicker_color"
                                        class="form-control form-control-color"
                                        value="<?= $val('kicker_color', '#e7aa6b'); ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">Title</label>
                                    <textarea name="title" class="form-control" rows="3"
                                        placeholder="Judul besar. Gunakan | untuk baris baru."><?= $val('title'); ?></textarea>
                                    <small class="text-muted">Gunakan <code>|</code> untuk baris baru.</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Warna Title</label>
                                    <input type="color" name="title_color"
                                        class="form-control form-control-color"
                                        value="<?= $val('title_color', '#ffffff'); ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">Lead / Subtitle</label>
                                    <textarea name="lead" class="form-control" rows="2"
                                        placeholder="Deskripsi singkat..."><?= $val('lead'); ?></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Warna Lead</label>
                                    <input type="color" name="lead_color"
                                        class="form-control form-control-color"
                                        value="<?= $val('lead_color', '#d7e8dd'); ?>">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8">
                                    <label class="form-label">Quote (Opsional)</label>
                                    <textarea name="quote" class="form-control" rows="2"
                                        placeholder="Kutipan pendek..."><?= $val('quote'); ?></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Warna Quote</label>
                                    <input type="color" name="quote_color"
                                        class="form-control form-control-color"
                                        value="<?= $val('quote_color', '#e7aa6b'); ?>">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Tombol Aksi (CTA)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teks Tombol</label>
                                    <input type="text" name="button_label" class="form-control"
                                        value="<?= $val('button_label'); ?>"
                                        placeholder="Contoh: Kenali BAMUSI">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL Tombol</label>
                                    <input type="text" name="button_url" class="form-control"
                                        value="<?= $val('button_url'); ?>"
                                        placeholder="#tentang atau https://...">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="card card-secondary mb-4">
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="published" value="1"
                                    id="publishCheck" <?= $chk('published', 1); ?>>
                                <label class="form-check-label fw-bold" for="publishCheck">Tampilkan</label>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="<?= $val('sort_order', 0); ?>">
                                <small class="text-muted">Kecil = tampil lebih dulu.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Gambar Hero</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($isEdit && !empty($item['image_url'])): ?>
                                <img src="<?= base_url($item['image_url']); ?>"
                                    class="img-fluid rounded mb-3" style="max-height:150px;">
                            <?php endif; ?>
                            <input type="file" name="image_url" class="form-control" accept="image/*">
                            <small class="text-muted">Format JPG/PNG/WEBP, maks 2MB.</small>
                        </div>
                    </div>

                    <div class="card card-primary mb-4">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> Simpan & Terjemahkan
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
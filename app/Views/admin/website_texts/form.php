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
        <h3 class="mb-0"><?= $isEdit ? 'Edit Teks: ' . esc($item['label']) : 'Tambah Teks Baru'; ?></h3>
        <a href="<?= base_url('admin/texts'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/texts/save'); ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $isEdit ? $item['id'] : ''; ?>">

            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Teks Utama (Indonesia)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nilai Teks <span class="text-danger">*</span></label>
                                <textarea name="value" class="form-control" rows="6" required
                                    placeholder="Teks yang akan tampil di website..."><?= $val('value'); ?></textarea>
                                <small class="text-muted">Teks ini akan otomatis diterjemahkan ke Bahasa Inggris saat disimpan.</small>
                            </div>

                            <?php if ($isEdit && !empty($item['value_en'])): ?>
                                <div class="mb-0">
                                    <label class="form-label text-muted">Versi Inggris (saat ini)</label>
                                    <div class="alert alert-light border small mb-0">
                                        <?= esc($item['value_en']); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Metadata -->
                    <div class="card card-secondary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Metadata</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Text Key <span class="text-danger">*</span></label>
                                <input type="text" name="text_key" class="form-control" required
                                    value="<?= $val('text_key'); ?>"
                                    placeholder="Contoh: global.brand_name"
                                    pattern="[a-zA-Z0-9._\-]+"
                                    title="Hanya huruf, angka, titik, underscore, dan dash">
                                <small class="text-muted">Identifier unik. Gunakan format <code>lokasi.nama</code>.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Label (Bantuan Admin) <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control" required
                                    value="<?= $val('label'); ?>"
                                    placeholder="Contoh: Nama Brand Global">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="location" class="form-control"
                                    value="<?= $val('location', 'global'); ?>"
                                    list="location-list"
                                    placeholder="global">
                                <datalist id="location-list">
                                    <option value="global">
                                    <option value="navbar">
                                    <option value="footer">
                                    <option value="hero">
                                    <option value="about">
                                    <option value="contact">
                                </datalist>
                                <small class="text-muted">Kategori lokasi untuk grouping.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="<?= $val('sort_order', 0); ?>">
                            </div>

                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="published" value="1"
                                    id="publishCheck" <?= $chk('published', 1); ?>>
                                <label class="form-check-label fw-bold" for="publishCheck">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <!-- Save -->
                    <div class="card card-success mb-4">
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
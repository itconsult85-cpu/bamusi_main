<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<?php
$isEdit = !empty($link);
$val = function ($key, $default = '') use ($link) {
    if (empty($link) || !isset($link[$key])) return $default;
    return esc($link[$key]);
};
$chk = function ($key, $default = 1) use ($link) {
    if (empty($link)) return $default ? 'checked' : '';
    return (!empty($link[$key])) ? 'checked' : '';
};
?>

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0"><?= $isEdit ? 'Edit Link' : 'Tambah Link Baru'; ?></h3>
        <a href="<?= base_url('admin/section-links'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/section-links/save'); ?>" method="post">
            <input type="hidden" name="id" value="<?= $isEdit ? $link['id'] : ''; ?>">
                <?= csrf_field() ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Detail Link</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Section Key <span class="text-danger">*</span></label>
                                <input type="text" name="section_key" class="form-control"
                                    value="<?= $val('section_key'); ?>" required
                                    list="section-key-list"
                                    placeholder="Contoh: about, board, hero">
                                <datalist id="section-key-list">
                                    <option value="about">
                                    <option value="board">
                                    <option value="hero">
                                    <option value="history">
                                    <option value="program">
                                    <option value="news">
                                </datalist>
                                <small class="text-muted">
                                    Harus sama dengan <code>section_key</code> di tabel <code>page_sections</code>.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Label <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control"
                                    value="<?= $val('label'); ?>" required
                                    placeholder="Contoh: 5 PILAR BAMUSI">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sublabel (opsional)</label>
                                <input type="text" name="sublabel" class="form-control"
                                    value="<?= $val('sublabel'); ?>"
                                    placeholder="Contoh: klik untuk info selengkapnya">
                            </div>

                            <div class="mb-0">
                                <label class="form-label">URL Tujuan</label>
                                <input type="text" name="url" class="form-control"
                                    value="<?= $val('url'); ?>"
                                    placeholder="Contoh: /lima-nilai-utama atau https://...">
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
                            <div class="mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="<?= $val('sort_order', 0); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
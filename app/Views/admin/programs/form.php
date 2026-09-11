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
        <h3 class="mb-0"><?= $isEdit ? 'Edit Program' : 'Tambah Program Baru'; ?></h3>
        <a href="<?= base_url('admin/programs'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/programs/save'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $isEdit ? $item['id'] : ''; ?>">

            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Detail Program</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Program <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" required
                                    value="<?= $val('name'); ?>"
                                    placeholder="Contoh: Pesantren Kebangsaan">
                                <small class="text-muted">Nama EN akan diterjemahkan otomatis.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="6" required
                                    placeholder="Jelaskan program ini..."><?= $val('description'); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Divisi / Bidang</label>
                                    <input type="text" name="division" class="form-control"
                                        value="<?= $val('division', 'BAMUSI'); ?>"
                                        list="division-list"
                                        placeholder="BAMUSI">
                                    <datalist id="division-list">
                                        <option value="BAMUSI">
                                        <option value="Pendidikan">
                                        <option value="Dakwah">
                                        <option value="Sosial">
                                        <option value="Ekonomi">
                                        <option value="Pemuda & Olahraga">
                                        <option value="Media">
                                    </datalist>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="<?= $val('sort_order', 0); ?>">
                                    <small class="text-muted">Kecil = tampil lebih dulu.</small>
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
                                <label class="form-check-label fw-bold" for="publishCheck">Terbitkan</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> Simpan & Terjemahkan
                            </button>
                        </div>
                    </div>

                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Gambar Program</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($isEdit && !empty($item['image_url'])): ?>
                                <img src="<?= base_url($item['image_url']); ?>"
                                    class="img-fluid rounded mb-3" style="max-height:150px;">
                            <?php endif; ?>
                            <input type="file" name="image_url" class="form-control" accept="image/*">
                            <small class="text-muted">Rasio 16:9 disarankan. Maks 2MB.</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
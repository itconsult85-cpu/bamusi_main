<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<?php
$isEdit = !empty($item);
$val = function ($key, $default = '') use ($item) {
    if (empty($item) || !isset($item[$key])) return $default;
    return esc($item[$key]);
};
$type = $val('type', 'text');
$currentValue = $isEdit ? ($item['setting_value'] ?? '') : '';
?>

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0"><?= $isEdit ? 'Edit: ' . esc($item['label'] ?? $item['setting_key']) : 'Tambah Setting Baru'; ?></h3>
        <a href="<?= base_url('admin/settings'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/settings/save'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $isEdit ? $item['id'] : ''; ?>">
            <input type="hidden" name="type" value="<?= esc($type); ?>">

            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Nilai Setting</h5>
                        </div>
                        <div class="card-body">

                            <?php if ($type === 'image'): ?>
                                <!-- Type: IMAGE -->
                                <?php if (!empty($currentValue) && file_exists(FCPATH . ltrim($currentValue, '/'))): ?>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Gambar Saat Ini</label>
                                        <div>
                                            <img src="<?= base_url($currentValue); ?>"
                                                class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label class="form-label">Upload Gambar Baru</label>
                                    <input type="file" name="setting_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB. Gambar lama akan otomatis terhapus.</small>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small text-muted">Atau masukkan URL Manual</label>
                                    <input type="text" name="setting_value" class="form-control"
                                        value="<?= esc($currentValue); ?>"
                                        placeholder="/uploads/settings/gambar.jpg">
                                </div>

                            <?php elseif ($type === 'textarea'): ?>
                                <div class="mb-0">
                                    <label class="form-label">Nilai <span class="text-danger">*</span></label>
                                    <textarea name="setting_value" class="form-control" rows="6" required><?= esc($currentValue); ?></textarea>
                                </div>

                            <?php elseif ($type === 'url'): ?>
                                <div class="mb-0">
                                    <label class="form-label">URL <span class="text-danger">*</span></label>
                                    <input type="url" name="setting_value" class="form-control" required
                                        value="<?= esc($currentValue); ?>"
                                        placeholder="https://...">
                                </div>

                            <?php elseif ($type === 'email'): ?>
                                <div class="mb-0">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="setting_value" class="form-control" required
                                        value="<?= esc($currentValue); ?>">
                                </div>

                            <?php else: ?>
                                <div class="mb-0">
                                    <label class="form-label">Nilai <span class="text-danger">*</span></label>
                                    <input type="text" name="setting_value" class="form-control" required
                                        value="<?= esc($currentValue); ?>">
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-secondary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Metadata</h5>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Setting Key <span class="text-danger">*</span></label>
                                <input type="text" name="setting_key" class="form-control" required
                                    value="<?= $val('setting_key'); ?>"
                                    placeholder="hero_kicker"
                                    pattern="[a-z0-9_.\-]+"
                                    title="Hanya huruf kecil, angka, titik, underscore, dan dash">
                                <small class="text-muted">Identifier unik. Format: <code>lokasi_nama</code>.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Label (Bantuan Admin) <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control" required
                                    value="<?= $val('label'); ?>"
                                    placeholder="Kicker Hero">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="location" class="form-control"
                                    value="<?= $val('location', 'global'); ?>"
                                    list="location-list">
                                <datalist id="location-list">
                                    <option value="global">
                                    <option value="hero">
                                    <option value="about">
                                    <option value="contact">
                                    <option value="social">
                                    <option value="branding">
                                    <option value="integration">
                                    <option value="seo">
                                </datalist>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipe Input</label>
                                <select class="form-select" disabled>
                                    <option value="text" <?= $type === 'text' ? 'selected' : '' ?>>Text</option>
                                    <option value="textarea" <?= $type === 'textarea' ? 'selected' : '' ?>>Textarea</option>
                                    <option value="url" <?= $type === 'url' ? 'selected' : '' ?>>URL</option>
                                    <option value="email" <?= $type === 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="image" <?= $type === 'image' ? 'selected' : '' ?>>Image Upload</option>
                                </select>
                                <small class="text-muted">Ubah tipe lewat database jika perlu.</small>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="<?= $val('sort_order', 0); ?>">
                            </div>

                        </div>
                    </div>

                    <div class="card card-success">
                        <div class="card-body">
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
<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<?php
$isEdit = !empty($item);
$val = static fn(string $key, string $default = '') => esc((string) ($item[$key] ?? $default));
$backUrl = $backUrl ?? base_url('admin/sections/edit/' . ($section['id'] ?? ''));
?>
<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1"><?= $isEdit ? 'Edit Teks' : 'Tambah Teks'; ?>: <?= esc($section['section_name']); ?></h3>
            <small class="text-muted">Teks ini tetap memakai data lama website, tetapi sekarang dikelola dari section yang sesuai.</small>
        </div>
        <a href="<?= esc($backUrl); ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali ke Section</a>
    </div>
</div>
<div class="app-content"><div class="container-fluid">
    <form action="<?= base_url('admin/sections/text/save'); ?>" method="post">
        <?= csrf_field(); ?>
        <input type="hidden" name="id" value="<?= $val('id'); ?>">
        <input type="hidden" name="section_key" value="<?= esc($section['section_key']); ?>">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header"><h5 class="m-0">Isi Teks</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Label bantuan admin <span class="text-danger">*</span></label>
                            <input name="label" class="form-control" required value="<?= $val('label'); ?>" placeholder="Contoh: Judul section Agenda">
                            <small class="text-muted">Gunakan nama yang mudah dipahami saat mencari konten.</small>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Nilai teks (Indonesia) <span class="text-danger">*</span></label>
                            <textarea name="value" class="form-control" rows="8" required placeholder="Teks yang tampil di website..."><?= $val('value'); ?></textarea>
                            <small class="text-muted">Versi Inggris akan diperbarui otomatis saat disimpan.</small>
                        </div>
                        <?php if ($isEdit && !empty($item['value_en'])): ?>
                            <div class="alert alert-light border mt-3 mb-0"><strong>Versi Inggris saat ini:</strong><br><?= esc($item['value_en']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-secondary card-outline mb-4">
                    <div class="card-header"><h5 class="m-0">Identitas &amp; Urutan</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Text key <span class="text-danger">*</span></label>
                            <input name="text_key" class="form-control" required pattern="[a-zA-Z0-9._\-]+" value="<?= $val('text_key'); ?>" placeholder="home.agenda_title">
                            <small class="text-muted">Key unik sistem. Jangan diubah saat edit jika tidak perlu.</small>
                        </div>
                        <div class="mb-3"><label class="form-label">Urutan</label><input type="number" name="sort_order" class="form-control" value="<?= $val('sort_order', '0'); ?>"></div>
                        <div class="form-check form-switch"><input type="checkbox" name="published" value="1" class="form-check-input" id="published" <?= !$isEdit || !empty($item['published']) ? 'checked' : ''; ?>><label for="published" class="form-check-label">Tampilkan di website</label></div>
                    </div>
                </div>
                <div class="card card-success"><div class="card-body"><button class="btn btn-primary w-100 mb-2"><i class="fas fa-save me-1"></i>Simpan &amp; Terjemahkan</button><a href="<?= esc($backUrl); ?>" class="btn btn-outline-secondary w-100">Batal</a></div></div>
            </div>
        </div>
    </form>
</div></div>
<?= $this->endSection(); ?>

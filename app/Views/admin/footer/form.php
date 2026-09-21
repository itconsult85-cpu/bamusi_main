<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<?php
$settingValue = static function (array $settings, string $key): string {
    return (string) ($settings[$key]['setting_value'] ?? '');
};
?>
<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1">Edit Footer Website</h3>
            <small class="text-muted">Ubah informasi kontak dan tampilan footer yang muncul di seluruh halaman website.</small>
        </div>
        <a href="<?= base_url('admin/settings'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-cog me-1"></i> Pengaturan Umum
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i><?= esc(session()->getFlashdata('success')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i>
            Perubahan di halaman ini langsung digunakan pada footer website. Versi Bahasa Inggris untuk field teks dibuat otomatis saat disimpan.
        </div>

        <form action="<?= site_url('admin/footer/save'); ?>" method="post">
            <?= csrf_field(); ?>
            <?php foreach ($groups as $group): ?>
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><?= esc($group); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($fields as $key => $field): ?>
                                <?php if ($field['group'] !== $group) continue; ?>
                                <?php $value = $settingValue($settings, $key); ?>
                                <div class="col-md-<?= in_array($field['type'], ['textarea'], true) ? '12' : '6'; ?>">
                                    <label class="form-label" for="footer-<?= esc(str_replace('.', '-', $key)); ?>">
                                        <?= esc($field['label']); ?>
                                    </label>
                                    <?php if ($field['type'] === 'textarea'): ?>
                                        <textarea class="form-control" rows="3" id="footer-<?= esc(str_replace('.', '-', $key)); ?>" name="footer[<?= esc($key); ?>]"><?= esc($value); ?></textarea>
                                    <?php else: ?>
                                        <input class="form-control" type="<?= esc($field['type']); ?>" id="footer-<?= esc(str_replace('.', '-', $key)); ?>" name="footer[<?= esc($key); ?>]" value="<?= esc($value); ?>">
                                    <?php endif; ?>
                                    <?php if (!empty($field['help'])): ?>
                                        <small class="form-text text-muted"><?= $field['help']; ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="<?= base_url('/'); ?>" target="_blank" rel="noopener" class="btn btn-outline-secondary">
                    <i class="fas fa-external-link-alt me-1"></i> Lihat Website
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Footer
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>

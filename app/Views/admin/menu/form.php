<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<?php $isEdit = !empty($item); $value = static fn(string $key, string $default = ''): string => esc((string) ($item[$key] ?? $default)); ?>
<div class="app-content-header"><div class="container-fluid d-flex justify-content-between align-items-center"><h3><?= $isEdit ? 'Edit Menu Langsung' : 'Tambah Menu Langsung'; ?></h3><a href="<?= base_url('admin/menu'); ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a></div></div>
<div class="app-content"><div class="container-fluid"><div class="card card-primary card-outline"><div class="card-body">
<form action="<?= site_url('admin/menu/item/save'); ?>" method="post">
<?= csrf_field(); ?><input type="hidden" name="id" value="<?= $isEdit ? (int) $item['id'] : ''; ?>">
<div class="row"><div class="col-lg-8">
<div class="mb-3"><label class="form-label">Label Menu <span class="text-danger">*</span></label><input name="label" class="form-control" value="<?= $value('label'); ?>" required></div>
<div class="mb-3"><label class="form-label">Arah Menu</label><select name="target_type" id="targetType" class="form-select"><option value="section" <?= ($item['target_type'] ?? 'section') === 'section' ? 'selected' : ''; ?>>Section Homepage</option><option value="url" <?= ($item['target_type'] ?? '') === 'url' ? 'selected' : ''; ?>>URL Custom</option></select></div>
<div class="mb-3"><label class="form-label">Target</label><input name="target" id="target" class="form-control" value="<?= $value('target'); ?>" placeholder="#nilai atau /berita"><small class="text-muted">Contoh section: <code>#nilai</code>, <code>#sejarah</code>, atau <code>#bergabung</code>.</small></div>
<div class="mb-3"><label class="form-label">Deskripsi Mega Menu</label><textarea name="description" class="form-control" rows="2"><?= $value('description'); ?></textarea></div>
</div><div class="col-lg-4">
<div class="card card-light mb-3"><div class="card-body"><div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" name="active" value="1" <?= !isset($item['active']) || $item['active'] ? 'checked' : ''; ?>><label class="form-check-label fw-bold">Aktif di Website</label></div><div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" name="is_mega" value="1" <?= !empty($item['is_mega']) ? 'checked' : ''; ?>><label class="form-check-label">Mega Menu</label></div><div class="mb-3"><label class="form-label">Parent Menu</label><select name="parent_id" class="form-select"><option value="">— Menu Utama —</option><?php foreach ($parents as $parent): ?><option value="<?= (int) $parent['id']; ?>" <?= ((int) ($item['parent_id'] ?? 0) === (int) $parent['id']) ? 'selected' : ''; ?>><?= esc($parent['label']); ?></option><?php endforeach; ?></select></div><div><label class="form-label">Urutan</label><input type="number" min="0" name="sort_order" class="form-control" value="<?= $value('sort_order', '0'); ?>"></div></div></div>
<button class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan Menu</button>
</div></div></form></div></div></div></div>
<?= $this->endSection(); ?>

<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<?php $edit = !empty($item); $v = static fn(string $key, string $default = '') => esc((string) ($item[$key] ?? $default)); ?>
<div class="app-content-header"><div class="container-fluid"><h3 class="mb-0"><?= $edit ? 'Edit' : 'Tambah'; ?> Konten: <?= esc($sectionLabel); ?></h3></div></div>
<div class="app-content"><div class="container-fluid"><form action="<?= base_url('admin/homepage-content/save'); ?>" method="post" enctype="multipart/form-data"><?= csrf_field(); ?><input type="hidden" name="id" value="<?= $v('id'); ?>"><input type="hidden" name="section_key" value="<?= esc($sectionKey); ?>">
<div class="row"><div class="col-lg-8">
<div class="card card-primary card-outline mb-4"><div class="card-header"><h5 class="m-0">Konten</h5></div><div class="card-body">
<div class="mb-3"><label>Key Item</label><input name="item_key" class="form-control" value="<?= $v('item_key'); ?>" placeholder="contoh: chairman-language"><small class="text-muted">Opsional, gunakan key unik agar konten mudah dikenali.</small></div>
<div class="mb-3"><label>Label</label><input name="label" class="form-control" value="<?= $v('label'); ?>" placeholder="Label pendek"></div>
<div class="mb-3"><label>Judul</label><input name="title" class="form-control" value="<?= $v('title'); ?>" placeholder="Judul item"></div>
<div class="mb-3"><label>Isi / Deskripsi</label><textarea name="body" class="form-control" rows="7" placeholder="Isi konten item..."><?= $v('body'); ?></textarea></div>
<div class="mb-3"><label>URL / Tautan</label><input name="url" class="form-control" value="<?= $v('url'); ?>" placeholder="https://... atau #section"></div>
<div class="mb-0"><label>Options JSON <span class="text-muted">(opsional)</span></label><textarea name="options_json" class="form-control font-monospace" rows="4" placeholder='{"handle":"@contoh","type":"instagram"}'><?= $v('options_json'); ?></textarea><small class="text-muted">Dipakai untuk metadata khusus item tanpa mengubah template.</small></div>
</div></div>
</div><div class="col-lg-4">
<div class="card card-success card-outline mb-4"><div class="card-header"><h5 class="m-0">Media</h5></div><div class="card-body"><label>Upload gambar / video</label><input type="file" name="media_url" class="form-control" accept="image/*,video/mp4"><?php if ($edit && !empty($item['media_url'])): ?><div class="alert alert-light border mt-3 mb-0"><a href="<?= base_url($item['media_url']); ?>" target="_blank">Pratinjau media saat ini</a></div><?php endif; ?></div></div>
<div class="card card-secondary mb-4"><div class="card-body"><div class="mb-3"><label>Urutan</label><input type="number" name="sort_order" class="form-control" value="<?= $v('sort_order', '0'); ?>"></div><div class="form-check form-switch mb-3"><input type="checkbox" name="published" value="1" class="form-check-input" id="published" <?= !$edit || !empty($item['published']) ? 'checked' : ''; ?>><label for="published" class="form-check-label">Tampilkan di homepage</label></div><button class="btn btn-primary w-100 mb-2"><i class="fas fa-save me-1"></i> Simpan</button><a href="<?= base_url('admin/homepage-content/' . $sectionKey); ?>" class="btn btn-outline-secondary w-100">Batal</a></div></div>
</div></div></form></div></div>
<?= $this->endSection(); ?>

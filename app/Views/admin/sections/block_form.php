<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<?php
$edit = !empty($block);
$data = $block['data'] ?? [];
$v = static fn(string $key, string $default = '') => esc((string) ($data[$key] ?? $block[$key] ?? $default));
$types = [
    'rich_text' => 'Rich Text / Artikel',
    'image_text' => 'Gambar + Teks',
    'hero_slider' => 'Slider / Carousel',
    'cards' => 'Grid Cards',
    'horizontal_slider' => 'Horizontal Slider',
    'logo_grid' => 'Logo / Partner Grid',
    'media_tabs' => 'Tab Media Sosial',
    'collection' => 'Data Collection (Agenda, Berita, Program, Pengurus)',
    'quote' => 'Quote / Testimoni',
    'cta' => 'Call to Action',
    'spacer' => 'Jarak / Spacer',
    'join_form' => 'Form Bergabung',
];
?>
<div class="app-content-header"><div class="container-fluid d-flex justify-content-between align-items-center"><div><h3 class="mb-1"><?= $edit ? 'Edit' : 'Tambah'; ?> Blok Layout</h3><small class="text-muted">Section: <?= esc($section['section_name']); ?> · Susun fitur homepage tanpa menulis kode.</small></div><a href="<?= base_url('admin/sections/edit/' . $section['id']); ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a></div></div>
<div class="app-content"><div class="container-fluid"><form action="<?= base_url('admin/sections/block/save'); ?>" method="post">
<?= csrf_field(); ?><input type="hidden" name="id" value="<?= esc($block['id'] ?? ''); ?>"><input type="hidden" name="section_id" value="<?= esc($section['id']); ?>">
<div class="row"><div class="col-lg-8">
<div class="card card-primary card-outline mb-4"><div class="card-header"><h5 class="m-0">Komponen</h5></div><div class="card-body">
<div class="mb-3"><label class="form-label">Jenis komponen <span class="text-danger">*</span></label><select name="block_type" class="form-select" required><?php foreach ($types as $key => $label): ?><option value="<?= $key; ?>" <?= ($block['block_type'] ?? '') === $key ? 'selected' : ''; ?>><?= esc($label); ?></option><?php endforeach; ?></select><small class="text-muted">Pilihan ini menentukan layout yang dirender di homepage.</small></div>
<div class="mb-3"><label class="form-label">Judul blok</label><input name="title" class="form-control" value="<?= $v('title'); ?>" placeholder="Contoh: Program Pilihan Kami"></div>
<div class="mb-3"><label class="form-label">Isi / pengantar</label><textarea name="body" class="form-control" rows="5" placeholder="Teks pengantar komponen..."><?= $v('body'); ?></textarea></div>
<div class="mb-3"><label class="form-label">Sumber data</label><select name="source" class="form-select"><option value="manual" <?= $v('source', 'manual') === 'manual' ? 'selected' : ''; ?>>Manual / item section</option><option value="agenda" <?= $v('source') === 'agenda' ? 'selected' : ''; ?>>Agenda</option><option value="article" <?= $v('source') === 'article' ? 'selected' : ''; ?>>Artikel / Berita</option><option value="program" <?= $v('source') === 'program' ? 'selected' : ''; ?>>Program</option><option value="board" <?= $v('source') === 'board' ? 'selected' : ''; ?>>Pengurus</option><option value="partners" <?= $v('source') === 'partners' ? 'selected' : ''; ?>>Mitra / Partner</option><option value="about_values" <?= $v('source') === 'about_values' ? 'selected' : ''; ?>>Nilai Utama</option></select><small class="text-muted">Dipakai oleh komponen Collection, Cards, Slider, dan Logo Grid.</small></div>
<div class="row"><div class="col-md-6 mb-3"><label class="form-label">Varian tampilan</label><input name="variant" class="form-control" value="<?= $v('variant'); ?>" placeholder="dark, light, red, compact"></div><div class="col-md-3 mb-3"><label class="form-label">Jumlah data</label><input type="number" name="limit" min="1" max="24" class="form-control" value="<?= $v('limit', '4'); ?>"></div><div class="col-md-3 mb-3"><label class="form-label">Kolom</label><input type="number" name="columns" min="1" max="6" class="form-control" value="<?= $v('columns', '3'); ?>"></div></div>
<div class="mb-3"><label class="form-label">URL gambar / media</label><input name="image_url" class="form-control" value="<?= $v('image_url'); ?>" placeholder="/uploads/... atau https://..."></div>
<div class="mb-3"><label class="form-label">Posisi gambar</label><select name="image_position" class="form-select"><option value="left" <?= $v('image_position', 'left') === 'left' ? 'selected' : ''; ?>>Kiri</option><option value="right" <?= $v('image_position') === 'right' ? 'selected' : ''; ?>>Kanan</option><option value="background" <?= $v('image_position') === 'background' ? 'selected' : ''; ?>>Background</option></select></div>
<div class="mb-3"><label class="form-label">Label tombol</label><input name="button_label" class="form-control" value="<?= $v('button_label'); ?>" placeholder="Selengkapnya"></div><div class="mb-3"><label class="form-label">URL tombol</label><input name="button_url" class="form-control" value="<?= $v('button_url'); ?>" placeholder="#agenda atau https://..."></div>
<div class="mb-0"><label class="form-label">Item manual (JSON opsional)</label><textarea name="items_json" class="form-control font-monospace" rows="8" placeholder='[{"title":"Judul","body":"Deskripsi","url":"#"}]'><?= esc(!empty($data['items']) ? json_encode($data['items'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : ''); ?></textarea><small class="text-muted">Untuk card/slider manual. Bisa dikosongkan jika memakai sumber data CMS.</small></div>
</div></div></div>
<div class="col-lg-4"><div class="card card-secondary mb-4"><div class="card-header"><h5 class="m-0">Publikasi</h5></div><div class="card-body"><div class="mb-3"><label>Urutan blok</label><input type="number" name="sort_order" class="form-control" value="<?= esc($block['sort_order'] ?? '0'); ?>"></div><div class="form-check form-switch mb-3"><input type="checkbox" name="published" value="1" class="form-check-input" id="published" <?= !$edit || !empty($block['published']) ? 'checked' : ''; ?>><label class="form-check-label" for="published">Tampilkan blok ini</label></div><button class="btn btn-primary w-100 mb-2"><i class="fas fa-save me-1"></i>Simpan Blok</button><a href="<?= base_url('admin/sections/edit/' . $section['id']); ?>" class="btn btn-outline-secondary w-100">Batal</a></div></div><div class="alert alert-info small"><strong>Tips:</strong> Buat beberapa blok dalam satu section untuk menghasilkan kombinasi layout seperti WordPress.</div></div></div>
</form></div></div>
<?= $this->endSection(); ?>

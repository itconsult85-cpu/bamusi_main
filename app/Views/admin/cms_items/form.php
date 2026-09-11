<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

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
        <h3 class="mb-0"><?= $isEdit ? 'Edit Konten' : 'Tambah Konten Baru'; ?></h3>
        <a href="<?= base_url('admin/cms-items'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/cms-items/save'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $isEdit ? $item['id'] : ''; ?>">

            <div class="row">
                <div class="col-lg-8">

                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Konten Utama</h5>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Konten <span class="text-danger">*</span></label>
                                    <select name="kind" class="form-select" required>
                                        <option value="news" <?= ($isEdit && $item['kind'] === 'news')   ? 'selected' : ''; ?>>Berita</option>
                                        <option value="agenda" <?= ($isEdit && $item['kind'] === 'agenda') ? 'selected' : ''; ?>>Agenda</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kategori</label>
                                    <input type="text" name="category" class="form-control"
                                        value="<?= $val('category'); ?>"
                                        placeholder="Contoh: Nasional, Program">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg" required
                                    value="<?= $val('title'); ?>"
                                    placeholder="Masukkan judul...">
                                <small class="text-muted">Judul EN akan diterjemahkan otomatis.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ringkasan / Summary</label>
                                <textarea name="summary" class="form-control" rows="3"
                                    placeholder="Ringkasan singkat..."><?= $val('summary'); ?></textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Isi Lengkap</label>
                                <textarea name="body" class="form-control summernote"><?= $isEdit ? $item['body'] : ''; ?></textarea>
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

                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Detail Agenda</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Acara</label>
                                <input type="text" name="event_date" class="form-control"
                                    value="<?= $val('event_date'); ?>"
                                    placeholder="Contoh: 15 Oktober 2026">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="location" class="form-control"
                                    value="<?= $val('location'); ?>"
                                    placeholder="Contoh: Jakarta">
                            </div>
                        </div>
                    </div>

                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Tautan Eksternal</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-0">
                                <label class="form-label">URL Sumber / Berita Asli</label>
                                <input type="url" name="url" class="form-control"
                                    value="<?= $val('url'); ?>"
                                    placeholder="https://...">
                                <small class="text-muted">Untuk berita dari media luar.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Gambar Utama</h5>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(function() {
        $('.summernote').summernote({
            height: 400,
            placeholder: 'Tulis isi lengkap...',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>

<?= $this->endSection(); ?>
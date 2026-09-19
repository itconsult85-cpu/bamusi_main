<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0"><?= isset($section) ? 'Edit Section: ' . esc($section['section_name']) : 'Tambah Section Baru' ?></h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/sections/save'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= isset($section) ? $section['id'] : '' ?>">
                <?= csrf_field() ?>

            <div class="row">
                <!-- KOLOM KIRI (Teks & Konten) -->
                <div class="col-lg-8">

                    <!-- Box: Identitas Sistem -->
                    <div class="card card-dark card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Identitas Section</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label>Nama Section (Bantuan Admin)</label>
                                <input type="text" name="section_name" class="form-control" required placeholder="Contoh: Hero Homepage" value="<?= isset($section) ? esc($section['section_name']) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Section Key (Kunci Sistem)</label>
                                <input type="text" name="section_key" class="form-control" required placeholder="Contoh: hero_main" value="<?= isset($section) ? esc($section['section_key']) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Box: Elemen Teks Pendek -->
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Elemen Teks Pendek</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Kicker / Tagline Atas</label>
                                <input type="text" name="kicker" class="form-control" placeholder="Contoh: MARI MERAWAT INDONESIA" value="<?= isset($section) ? esc($section['kicker']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label>Judul Utama (Title)</label>
                                <input type="text" name="title" class="form-control" placeholder="Judul besar section..." value="<?= isset($section) ? esc($section['title']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label>Sub-judul / Lead</label>
                                <textarea name="subtitle" class="form-control" rows="2" placeholder="Teks pengantar di bawah judul..."><?= isset($section) ? esc($section['subtitle']) : '' ?></textarea>
                            </div>
                            <div class="mb-0">
                                <label>Kutipan Tokoh (Quote)</label>
                                <textarea name="quote" class="form-control" rows="2" placeholder="Contoh: Kutipan dari Ketua Umum..."><?= isset($section) ? esc($section['quote']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Box: Konten & Artikel -->
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Konten Artikel (Opsional)</h5>
                        </div>
                        <div class="card-body p-0">
                            <textarea name="content" class="form-control summernote"><?= isset($section) ? $section['content'] : '' ?></textarea>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN (Aksi, Media, Publish) -->
                <div class="col-lg-4">

                    <!-- Box: Tombol Aksi (CTA) -->
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Tombol Aksi (CTA)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Teks Tombol</label>
                                <input type="text" name="button_label" class="form-control" placeholder="Contoh: Lihat Agenda" value="<?= isset($section) ? esc($section['button_label']) : '' ?>">
                            </div>
                            <div class="mb-0">
                                <label>URL / Tautan Tombol</label>
                                <input type="text" name="button_url" class="form-control" placeholder="Contoh: #agenda atau https://..." value="<?= isset($section) ? esc($section['button_url']) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <?php if (($section['section_key'] ?? '') === 'nilai'): ?>
                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header"><h5 class="card-title m-0">Tata Letak Section Nilai</h5></div>
                        <div class="card-body">
                            <div class="mb-3"><label>Posisi Tombol</label><select name="button_position" class="form-select"><option value="left" <?= ($section['button_position'] ?? 'center') === 'left' ? 'selected' : '' ?>>Kiri</option><option value="center" <?= ($section['button_position'] ?? 'center') === 'center' ? 'selected' : '' ?>>Tengah</option><option value="right" <?= ($section['button_position'] ?? 'center') === 'right' ? 'selected' : '' ?>>Kanan</option></select></div>
                            <div class="mb-3"><label>Letak Tombol</label><select name="button_location" class="form-select"><option value="top" <?= ($section['button_location'] ?? 'bottom') === 'top' ? 'selected' : '' ?>>Di atas kartu</option><option value="bottom" <?= ($section['button_location'] ?? 'bottom') === 'bottom' ? 'selected' : '' ?>>Di bawah kartu</option></select></div>
                            <div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" name="cards_visible" value="1" <?= !array_key_exists('cards_visible', $section) || !empty($section['cards_visible']) ? 'checked' : '' ?>><label class="form-check-label">Tampilkan kartu nilai</label></div>
                            <div class="row"><div class="col-6"><label>Jumlah kartu</label><input type="number" min="1" max="12" name="cards_limit" class="form-control" value="<?= (int) ($section['cards_limit'] ?? 5) ?>"></div><div class="col-6"><label>Kolom desktop</label><select name="cards_columns" class="form-select"><option value="2" <?= (int) ($section['cards_columns'] ?? 5) === 2 ? 'selected' : '' ?>>2</option><option value="3" <?= (int) ($section['cards_columns'] ?? 5) === 3 ? 'selected' : '' ?>>3</option><option value="4" <?= (int) ($section['cards_columns'] ?? 5) === 4 ? 'selected' : '' ?>>4</option><option value="5" <?= (int) ($section['cards_columns'] ?? 5) === 5 ? 'selected' : '' ?>>5</option><option value="6" <?= (int) ($section['cards_columns'] ?? 5) === 6 ? 'selected' : '' ?>>6</option></select></div></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- Box: Upload Media -->
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Media Utama</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Unggah Gambar / Video Latar</label>
                                <input type="file" name="media_url" class="form-control" accept="image/*,video/mp4">
                            </div>
                            <?php if (isset($section) && !empty($section['media_url'])): ?>
                                <div class="alert alert-light border">
                                    <strong>Media Saat Ini:</strong><br>
                                    <a href="<?= base_url($section['media_url']) ?>" target="_blank" class="text-decoration-none">Buka Pratinjau Tautan <i class="fas fa-external-link-alt ms-1"></i></a>
                                </div>
                            <?php else: ?>
                                <small class="text-muted">Isi jika section ini butuh visual.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Box: Publish & Simpan -->
                    <div class="card card-secondary mb-4">
                        <div class="card-body">
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" style="width: 2.5em; height: 1.25em;" type="checkbox" name="published" value="1" id="publishCheck" <?= (!isset($section) || $section['published'] == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label ms-2 pt-1 fw-bold" for="publishCheck">Tampilkan di Web</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-2"><i class="fas fa-save me-2"></i> Simpan & Terjemahkan</button>
                            <a href="<?= base_url('admin/sections') ?>" class="btn btn-outline-secondary w-100">Batal & Kembali</a>
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
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 250,
            placeholder: 'Tuliskan deskripsi panjang atau artikel di sini...',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'video']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
<?= $this->endSection(); ?>

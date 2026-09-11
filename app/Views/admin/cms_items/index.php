<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Kelola Konten (Berita & Agenda)</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Kolom Kiri: Form Input -->
            <div class="col-lg-4 mb-4">
                <div class="card card-primary card-outline" id="form-card">
                    <div class="card-header">
                        <h5 class="card-title m-0" id="form-title-text">Tambah Konten Baru</h5>
                    </div>
                    <!-- Form Utama -->
                    <form action="<?= base_url('admin/cms/save'); ?>" method="post" id="cms-form">
                        <!-- Input Hidden untuk menyimpan ID saat Edit -->
                            <?= csrf_field() ?>
                        <input type="hidden" name="id" id="input-id">

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Jenis Konten</label>
                                <select name="kind" id="input-kind" class="form-select" required>
                                    <option value="news">Berita</option>
                                    <option value="agenda">Agenda</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul (Bahasa Indonesia)</label>
                                <input type="text" name="title" id="input-title" class="form-control" placeholder="Masukkan judul..." required>
                                <small class="text-muted">Judul bahasa Inggris akan diterjemahkan otomatis.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ringkasan / Summary</label>
                                <textarea name="summary" id="input-summary" class="form-control" rows="3" placeholder="Tulis ringkasan singkat..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Isi Lengkap / Body</label>
                                <textarea name="body" id="input-body" class="form-control" rows="5" placeholder="Tulis isi berita/agenda..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Acara (Khusus Agenda)</label>
                                <input type="text" name="event_date" id="input-event_date" class="form-control" placeholder="Contoh: 15 Oktober 2026 atau Jadwal segera">
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="published" value="1" id="publishCheck" checked>
                                <label class="form-check-label" for="publishCheck">
                                    Langsung Terbitkan (Publish)
                                </label>
                            </div>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save me-2"></i> Simpan & Terjemahkan
                            </button>
                            <!-- Tombol Batal Edit (Tersembunyi secara default) -->
                            <button type="button" class="btn btn-secondary" id="btn-cancel" style="display: none;" onclick="cancelEdit()">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Tabel -->
            <div class="col-lg-8">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h5 class="card-title m-0">Daftar Konten</h5>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">ID</th>
                                    <th>Jenis</th>
                                    <th>Judul (ID)</th>
                                    <th>Terjemahan (EN)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($items)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada konten.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td class="text-center"><?= $item['id']; ?></td>
                                            <td>
                                                <span class="badge <?= $item['kind'] == 'news' ? 'text-bg-info' : 'text-bg-warning'; ?> text-uppercase">
                                                    <?= $item['kind']; ?>
                                                </span>
                                            </td>
                                            <td class="fw-bold"><?= esc($item['title']); ?></td>
                                            <td>
                                                <?php if (!empty($item['title_en'])): ?>
                                                    <span class="text-success small"><i class="fas fa-check-circle"></i> Ada</span>
                                                <?php else: ?>
                                                    <span class="text-danger small"><i class="fas fa-times-circle"></i> Kosong</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($item['published']): ?>
                                                    <span class="badge text-bg-success">Publik</span>
                                                <?php else: ?>
                                                    <span class="badge text-bg-secondary">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <!-- Tombol Edit (Kirim data baris ini ke JavaScript via JSON) -->
                                                <button class="btn btn-sm btn-warning text-white"
                                                    onclick='editItem(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Script Interaksi Form Edit -->
<script>
    function editItem(item) {
        // Gulir halaman ke atas (ke arah form)
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        // Ubah judul form
        document.getElementById('form-title-text').innerText = 'Edit Konten (ID: ' + item.id + ')';
        document.getElementById('form-card').classList.replace('card-primary', 'card-warning');

        // Isi data ke dalam form
        document.getElementById('input-id').value = item.id;
        document.getElementById('input-kind').value = item.kind;
        document.getElementById('input-title').value = item.title;
        document.getElementById('input-summary').value = item.summary || '';
        document.getElementById('input-body').value = item.body || '';
        document.getElementById('input-event_date').value = item.event_date || '';
        document.getElementById('publishCheck').checked = item.published == 1;

        // Munculkan tombol Batal
        document.getElementById('btn-cancel').style.display = 'inline-block';
    }

    function cancelEdit() {
        // Reset judul dan warna form
        document.getElementById('form-title-text').innerText = 'Tambah Konten Baru';
        document.getElementById('form-card').classList.replace('card-warning', 'card-primary');

        // Kosongkan form
        document.getElementById('cms-form').reset();
        document.getElementById('input-id').value = '';

        // Sembunyikan tombol Batal
        document.getElementById('btn-cancel').style.display = 'none';
    }
</script>
<?= $this->endSection(); ?>
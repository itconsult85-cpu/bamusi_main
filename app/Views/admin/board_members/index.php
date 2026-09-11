<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Kelola Susunan Pengurus</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
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
                        <h5 class="card-title m-0" id="form-title-text">Tambah Pengurus</h5>
                    </div>
                    <form action="<?= base_url('admin/board/save'); ?>" method="post"
                        id="board-form" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                        <input type="hidden" name="id" id="input-id">

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap & Gelar</label>
                                <input type="text" name="name" id="input-name" class="form-control" placeholder="Contoh: H. Fulan, S.E." required>
                                <small class="text-muted">Nama tidak akan diterjemahkan.</small>
                            </div>

                            <!-- Field Grup Baru -->
                            <div class="mb-3">
                                <label class="form-label">Grup / Kelompok</label>
                                <input type="text" name="group_name" id="input-group_name"
                                    class="form-control"
                                    placeholder="Contoh: Ketua Umum"
                                    list="group-list">
                                <datalist id="group-list">
                                    <option value="Ketua Umum">
                                    <option value="Sekretaris Jenderal">
                                    <option value="Bendahara Umum">
                                    <option value="Koordinator Bidang Internal">
                                    <option value="Koordinator Bidang Eksternal">
                                    <option value="Departemen">
                                </datalist>
                                <small class="text-muted">Anggota dengan grup sama akan dikelompokkan.</small>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Urutan Grup</label>
                                    <input type="number" name="group_order" id="input-group_order"
                                        class="form-control" value="0">
                                    <small class="text-muted">Kecil = atas</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Urutan Anggota</label>
                                    <input type="number" name="member_order" id="input-member_order"
                                        class="form-control" value="0">
                                    <small class="text-muted">Kecil = kiri</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Pengurus</label>
                                <?php if (!empty($items)): ?>
                                    <!-- Preview akan muncul saat edit -->
                                <?php endif; ?>
                                <div id="current-photo-wrapper" class="mb-2" style="display: none;">
                                    <img id="current-photo" src="" alt="Foto saat ini"
                                        class="img-thumbnail" style="max-height: 120px;">
                                    <small class="d-block text-muted mt-1">Foto saat ini</small>
                                </div>
                                <input type="file" name="photo" id="input-photo"
                                    class="form-control" accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB. Rasio disarankan 1:1.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jabatan (Bahasa Indonesia)</label>
                                <input type="text" name="role" id="input-role" class="form-control" placeholder="Contoh: Ketua Umum" required>
                                <small class="text-muted">Jabatan akan otomatis diterjemahkan ke Inggris.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan / Catatan Tambahan</label>
                                <input type="text" name="note" id="input-note" class="form-control" placeholder="Contoh: Pengurus BAMUSI">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Urutan (Sort Order)</label>
                                <input type="number" name="sort_order" id="input-sort_order" class="form-control" value="0">
                                <small class="text-muted">Angka lebih kecil akan tampil lebih dulu.</small>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="published" value="1" id="publishCheck" checked>
                                <label class="form-check-label" for="publishCheck">Tampilkan di Website</label>
                            </div>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save me-2"></i> Simpan & Terjemahkan
                            </button>
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
                        <h5 class="card-title m-0">Daftar Pengurus</h5>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0 text-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 60px;">Foto</th>
                                    <th class="text-center" style="width: 60px;">Urutan</th>
                                    <th>Nama Pengurus</th>
                                    <th>Jabatan (ID)</th>
                                    <th>Jabatan (EN)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($items)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data pengurus.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if (!empty($item['photo_url']) && file_exists(FCPATH . $item['photo_url'])): ?>
                                                    <img src="<?= base_url($item['photo_url']); ?>"
                                                        alt="<?= esc($item['name']); ?>"
                                                        class="rounded-circle"
                                                        style="width: 45px; height: 45px; object-fit: cover;">
                                                <?php else: ?>
                                                    <span class="badge text-bg-secondary">No Foto</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center fw-bold"><?= $item['sort_order']; ?></td>
                                            <td class="fw-bold"><?= esc($item['name']); ?></td>
                                            <td><?= esc($item['role']); ?></td>
                                            <td>
                                                <?php if (!empty($item['role_en'])): ?>
                                                    <span class="text-success"><i class="fas fa-check-circle"></i> Ada</span>
                                                <?php else: ?>
                                                    <span class="text-danger"><i class="fas fa-times-circle"></i> Kosong</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($item['published']): ?>
                                                    <span class="badge text-bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge text-bg-secondary">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
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

<script>
    function editItem(item) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        document.getElementById('form-title-text').innerText = 'Edit Pengurus';
        document.getElementById('form-card').classList.replace('card-primary', 'card-warning');

        document.getElementById('input-id').value = item.id;
        document.getElementById('input-name').value = item.name;
        document.getElementById('input-role').value = item.role;
        document.getElementById('input-note').value = item.note || '';
        document.getElementById('input-sort_order').value = item.sort_order;
        document.getElementById('publishCheck').checked = item.published == 1;
        document.getElementById('input-group_name').value = item.group_name || '';
        document.getElementById('input-group_order').value = item.group_order || 0;
        document.getElementById('input-member_order').value = item.member_order || 0;

        // Tampilkan foto saat ini
        const photoWrapper = document.getElementById('current-photo-wrapper');
        const photoImg = document.getElementById('current-photo');
        if (item.photo_url) {
            photoImg.src = '<?= base_url(); ?>/' + item.photo_url.replace(/^\//, '');
            photoWrapper.style.display = 'block';
        } else {
            photoWrapper.style.display = 'none';
        }

        // Reset file input
        document.getElementById('input-photo').value = '';

        document.getElementById('btn-cancel').style.display = 'inline-block';
    }

    function cancelEdit() {
        document.getElementById('form-title-text').innerText = 'Tambah Pengurus';
        document.getElementById('form-card').classList.replace('card-warning', 'card-primary');

        document.getElementById('board-form').reset();
        document.getElementById('input-id').value = '';
        document.getElementById('input-sort_order').value = '0';
        document.getElementById('current-photo-wrapper').style.display = 'none';

        document.getElementById('btn-cancel').style.display = 'none';
    }
</script>
<?= $this->endSection(); ?>
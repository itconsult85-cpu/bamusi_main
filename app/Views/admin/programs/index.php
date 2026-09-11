<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Kelola Program BAMUSI</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Form Input -->
            <div class="col-lg-4 mb-4">
                <div class="card card-primary card-outline" id="form-card">
                    <div class="card-header">
                        <h5 class="card-title m-0" id="form-title-text">Tambah Program</h5>
                    </div>
                    <form action="<?= base_url('admin/programs/save'); ?>" method="post" id="program-form">
                        <input type="hidden" name="id" id="input-id">
                            <?= csrf_field() ?>

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Program</label>
                                <input type="text" name="name" id="input-name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" id="input-description" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="published" value="1" id="publishCheck" checked>
                                <label class="form-check-label" for="publishCheck">Publikasikan</label>
                            </div>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Simpan & Terjemahkan</button>
                            <button type="button" class="btn btn-secondary" id="btn-cancel" style="display: none;" onclick="cancelEdit()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Daftar -->
            <div class="col-lg-8">
                <div class="card card-secondary card-outline">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Program</th>
                                    <th>Terjemahan (EN)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="fw-bold"><?= esc($item['name']); ?></td>
                                        <td>
                                            <?php if (!empty($item['name_en'])): ?>
                                                <span class="text-success small"><i class="fas fa-check-circle"></i> Ada</span>
                                            <?php else: ?>
                                                <span class="text-danger small"><i class="fas fa-times-circle"></i> Kosong</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item['published'] ? 'Publik' : 'Draft'; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning text-white" onclick='editItem(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
        document.getElementById('form-title-text').innerText = 'Edit Program';
        document.getElementById('form-card').classList.replace('card-primary', 'card-warning');
        document.getElementById('input-id').value = item.id;
        document.getElementById('input-name').value = item.name;
        document.getElementById('input-description').value = item.description || '';
        document.getElementById('publishCheck').checked = item.published == 1;
        document.getElementById('btn-cancel').style.display = 'inline-block';
    }

    function cancelEdit() {
        document.getElementById('form-title-text').innerText = 'Tambah Program';
        document.getElementById('form-card').classList.replace('card-warning', 'card-primary');
        document.getElementById('program-form').reset();
        document.getElementById('input-id').value = '';
        document.getElementById('btn-cancel').style.display = 'none';
    }
</script>
<?= $this->endSection(); ?>
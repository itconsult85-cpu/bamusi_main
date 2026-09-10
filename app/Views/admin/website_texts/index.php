<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Kelola Teks Website Global</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card card-primary card-outline" id="form-card">
                    <div class="card-header">
                        <h5 class="card-title m-0" id="form-title-text">Edit Teks</h5>
                    </div>
                    <form action="<?= base_url('admin/texts/save'); ?>" method="post" id="text-form">
                        <input type="hidden" name="id" id="input-id">
                        <input type="hidden" name="text_key" id="input-text_key">
                        <input type="hidden" name="location" id="input-location">
                        <input type="hidden" name="label" id="input-label">
                        <input type="hidden" name="sort_order" id="input-sort_order">

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted" id="display-label">Pilih teks di tabel kanan</label>
                                <textarea name="value" id="input-value" class="form-control" rows="5" required placeholder="Nilai teks (Bahasa Indonesia)"></textarea>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="published" value="1" id="publishCheck" checked>
                                <label class="form-check-label" for="publishCheck">Aktif</label>
                            </div>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Simpan & Terjemahkan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-secondary card-outline">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle mb-0 text-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Kunci (Key) / Label</th>
                                    <th>Teks Indonesia</th>
                                    <th>Teks Inggris</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <span class="badge text-bg-dark"><?= esc($item['text_key']); ?></span><br>
                                            <small class="text-muted"><?= esc($item['label']); ?></small>
                                        </td>
                                        <td><?= esc(substr($item['value'], 0, 50)) . '...'; ?></td>
                                        <td>
                                            <?php if (!empty($item['value_en'])): ?>
                                                <span class="text-success"><i class="fas fa-check"></i></span>
                                            <?php else: ?>
                                                <span class="text-danger"><i class="fas fa-times"></i></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning text-white" onclick='editItem(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)'>
                                                <i class="fas fa-edit"></i> Edit
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
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        document.getElementById('form-card').classList.replace('card-primary', 'card-warning');
        document.getElementById('display-label').innerText = item.label + ' (' + item.text_key + ')';

        document.getElementById('input-id').value = item.id;
        document.getElementById('input-text_key').value = item.text_key;
        document.getElementById('input-location').value = item.location;
        document.getElementById('input-label').value = item.label;
        document.getElementById('input-sort_order').value = item.sort_order;
        document.getElementById('input-value').value = item.value;
        document.getElementById('publishCheck').checked = item.published == 1;
    }
</script>
<?= $this->endSection(); ?>
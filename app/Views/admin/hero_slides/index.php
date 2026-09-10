<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Kelola Hero Slides</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Form -->
            <div class="col-lg-5 mb-4">
                <div class="card card-primary card-outline" id="form-card">
                    <div class="card-header">
                        <h5 class="m-0" id="form-title">Tambah Slide</h5>
                    </div>
                    <form action="<?= base_url('admin/hero-slides/save'); ?>" method="post"
                        enctype="multipart/form-data" id="slide-form">
                        <input type="hidden" name="id" id="input-id">

                        <div class="card-body" style="max-height: 75vh; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="form-label">Kicker</label>
                                <input type="text" name="kicker" id="input-kicker" class="form-control">
                                <div class="mt-1">
                                    <label class="small text-muted">Warna Kicker</label>
                                    <input type="color" name="kicker_color" id="input-kicker_color"
                                        class="form-control form-control-color" value="#e7aa6b">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <textarea name="title" id="input-title" class="form-control" rows="2"></textarea>
                                <small class="text-muted">Gunakan <code>|</code> untuk baris baru.</small>
                                <div class="mt-1">
                                    <label class="small text-muted">Warna Title</label>
                                    <input type="color" name="title_color" id="input-title_color"
                                        class="form-control form-control-color" value="#ffffff">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lead / Subtitle</label>
                                <textarea name="lead" id="input-lead" class="form-control" rows="2"></textarea>
                                <div class="mt-1">
                                    <label class="small text-muted">Warna Lead</label>
                                    <input type="color" name="lead_color" id="input-lead_color"
                                        class="form-control form-control-color" value="#d7e8dd">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quote</label>
                                <textarea name="quote" id="input-quote" class="form-control" rows="2"></textarea>
                                <div class="mt-1">
                                    <label class="small text-muted">Warna Quote</label>
                                    <input type="color" name="quote_color" id="input-quote_color"
                                        class="form-control form-control-color" value="#e7aa6b">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gambar Hero</label>
                                <div id="current-img-wrapper" class="mb-2" style="display:none;">
                                    <img id="current-img" src="" class="img-thumbnail" style="max-height:120px;">
                                </div>
                                <input type="file" name="image_url" class="form-control" accept="image/*">
                                <small class="text-muted">Format JPG/PNG/WEBP, maks 2MB.</small>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Button Label</label>
                                    <input type="text" name="button_label" id="input-button_label" class="form-control">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" name="sort_order" id="input-sort_order"
                                        class="form-control" value="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Button URL</label>
                                <input type="text" name="button_url" id="input-button_url" class="form-control"
                                    placeholder="#tentang atau https://...">
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="published" value="1"
                                    id="input-published" checked>
                                <label class="form-check-label" for="input-published">Tampilkan</label>
                            </div>
                        </div>

                        <div class="card-footer d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save me-1"></i> Simpan & Terjemahkan
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn-cancel"
                                style="display:none;" onclick="cancelEdit()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel -->
            <div class="col-lg-7">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h5 class="card-title m-0">Daftar Slide</h5>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:90px;">Gambar</th>
                                    <th>Kicker / Title</th>
                                    <th class="text-center">Urutan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width:110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($items)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada slide.</td>
                                    </tr>
                                    <?php else: foreach ($items as $item): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($item['image_url'])): ?>
                                                    <img src="<?= base_url($item['image_url']); ?>"
                                                        style="width:80px;height:50px;object-fit:cover;border-radius:6px;">
                                                <?php else: ?>
                                                    <span class="badge text-bg-secondary">No img</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="fw-bold small" style="color:<?= esc($item['kicker_color']); ?>">
                                                    <?= esc($item['kicker']); ?>
                                                </div>
                                                <div class="fw-bold"><?= esc(mb_strimwidth($item['title'], 0, 60, '...')); ?></div>
                                                <div class="small text-muted">
                                                    EN: <?= !empty($item['title_en']) ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>'; ?>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold"><?= $item['sort_order']; ?></td>
                                            <td class="text-center">
                                                <?= $item['published']
                                                    ? '<span class="badge text-bg-success">Aktif</span>'
                                                    : '<span class="badge text-bg-secondary">Draft</span>'; ?>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-warning text-white btn-edit"
                                                    data-item='<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="<?= base_url('admin/hero-slides/delete/' . $item['id']); ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Hapus slide ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-edit');
        if (!btn) return;

        let item;
        try {
            item = JSON.parse(btn.dataset.item);
        } catch (err) {
            console.error(err);
            alert('Data tidak valid');
            return;
        }

        document.getElementById('form-title').innerText = 'Edit Slide';
        document.getElementById('input-id').value = item.id || '';
        document.getElementById('input-kicker').value = item.kicker || '';
        document.getElementById('input-kicker_color').value = item.kicker_color || '#e7aa6b';
        document.getElementById('input-title').value = item.title || '';
        document.getElementById('input-title_color').value = item.title_color || '#ffffff';
        document.getElementById('input-lead').value = item.lead || '';
        document.getElementById('input-lead_color').value = item.lead_color || '#d7e8dd';
        document.getElementById('input-quote').value = item.quote || '';
        document.getElementById('input-quote_color').value = item.quote_color || '#e7aa6b';
        document.getElementById('input-button_label').value = item.button_label || '';
        document.getElementById('input-button_url').value = item.button_url || '';
        document.getElementById('input-sort_order').value = item.sort_order || 0;
        document.getElementById('input-published').checked = item.published == 1;

        const wrap = document.getElementById('current-img-wrapper');
        const img = document.getElementById('current-img');
        if (item.image_url) {
            img.src = '<?= base_url(); ?>/' + String(item.image_url).replace(/^\/+/, '');
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
        }

        document.getElementById('btn-cancel').style.display = 'inline-block';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    function cancelEdit() {
        document.getElementById('form-title').innerText = 'Tambah Slide';
        document.getElementById('slide-form').reset();
        document.getElementById('input-id').value = '';
        document.getElementById('current-img-wrapper').style.display = 'none';
        document.getElementById('btn-cancel').style.display = 'none';
    }
</script>

<?= $this->endSection(); ?>
<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Link Section</h3>
        <a href="<?= base_url('admin/section-links/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Link
        </a>
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

        <div class="card card-outline card-secondary">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:110px;">Section</th>
                            <th>Label</th>
                            <th>Sublabel</th>
                            <th>URL</th>
                            <th style="width:70px;" class="text-center">Urutan</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:130px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada link.</td>
                            </tr>
                            <?php else: foreach ($items as $item): ?>
                                <tr>
                                    <td><span class="badge text-bg-dark"><?= esc($item['section_key']); ?></span></td>
                                    <td class="fw-bold"><?= esc($item['label']); ?></td>
                                    <td class="text-muted small"><?= esc($item['sublabel'] ?? '-'); ?></td>
                                    <td class="small text-truncate" style="max-width:200px;">
                                        <a href="<?= esc($item['url']); ?>" target="_blank">
                                            <?= esc($item['url'] ?? '-'); ?>
                                        </a>
                                    </td>
                                    <td class="text-center fw-bold"><?= $item['sort_order']; ?></td>
                                    <td>
                                        <?= $item['published']
                                            ? '<span class="badge text-bg-success">Aktif</span>'
                                            : '<span class="badge text-bg-secondary">Draft</span>'; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/section-links/edit/' . $item['id']); ?>"
                                            class="btn btn-sm btn-warning text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('admin/section-links/delete/' . $item['id']); ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus link ini?')">
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

<?= $this->endSection(); ?>
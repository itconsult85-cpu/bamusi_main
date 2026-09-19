<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<div class="app-content-header"><div class="container-fluid"><div class="d-flex justify-content-between align-items-center"><h3 class="mb-0">Konten Homepage: <?= esc($sectionLabel); ?></h3><a href="<?= base_url('admin/homepage-content/create/' . $sectionKey); ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Konten</a></div></div></div>
<div class="app-content"><div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')); ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= esc(session()->getFlashdata('error')); ?></div><?php endif; ?>
    <div class="card card-secondary card-outline mb-3"><div class="card-body"><div class="btn-group flex-wrap">
        <?php foreach ($sections as $key => $label): ?><a href="<?= base_url('admin/homepage-content/' . $key); ?>" class="btn <?= $key === $sectionKey ? 'btn-primary' : 'btn-outline-primary'; ?>"><?= esc($label); ?></a><?php endforeach; ?>
    </div><p class="text-muted small mb-0 mt-3">Gunakan CRUD ini untuk item yang tampil berulang di homepage. Template visual homepage tidak berubah.</p></div></div>
    <div class="card"><div class="card-body table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Urutan</th><th>Key</th><th>Label / Judul</th><th>Isi</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($items as $item): ?><tr><td><?= (int) $item['sort_order']; ?></td><td><code><?= esc($item['item_key'] ?? ''); ?></code></td><td><?= esc($item['label'] ?: $item['title'] ?: '-'); ?></td><td><?= esc(mb_strimwidth((string) ($item['body'] ?: $item['title'] ?: ''), 0, 90, '…')); ?></td><td><?= !empty($item['published']) ? '<span class="badge text-bg-success">Aktif</span>' : '<span class="badge text-bg-secondary">Nonaktif</span>'; ?></td><td class="text-nowrap"><a href="<?= base_url('admin/homepage-content/edit/' . $item['id']); ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i> Edit</a><form method="post" action="<?= base_url('admin/homepage-content/delete/' . $item['id']); ?>" class="d-inline" onsubmit="return confirm('Hapus konten ini?');"><?= csrf_field(); ?><button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form></td></tr><?php endforeach; ?>
    <?php if (!$items): ?><tr><td colspan="6" class="text-center text-muted py-4">Belum ada konten pada section ini.</td></tr><?php endif; ?></tbody></table></div></div>
</div></div>
<?= $this->endSection(); ?>

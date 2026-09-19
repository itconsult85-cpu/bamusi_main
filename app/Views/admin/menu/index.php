<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<?php
$children = [];
foreach ($pages as $page) {
    if (!empty($page['parent_id'])) {
        $children[(int) $page['parent_id']][] = $page;
    }
}

$label = static function (array $page): string {
    return (string) ($page['menu_label'] ?: $page['title']);
};

$rows = [];
foreach ($pages as $page) {
    if (empty($page['parent_id'])) {
        $rows[] = ['page' => $page, 'level' => 0];
        foreach ($children[(int) $page['id']] ?? [] as $child) {
            $rows[] = ['page' => $child, 'level' => 1];
        }
    }
}
?>

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1">Susunan Menu Website</h3>
            <small class="text-muted">Atur item yang tampil di homepage, parent menu, mega menu, dan urutannya.</small>
        </div>
        <a href="<?= base_url('admin/pages'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-file-alt me-1"></i> Kelola Halaman
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= esc(session()->getFlashdata('success')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= esc(session()->getFlashdata('error')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-body">
                <div class="alert alert-info py-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Item yang tidak diaktifkan tetap tersimpan sebagai halaman, tetapi tidak ditampilkan pada navigasi website.
                    Mega menu akan aktif untuk item parent yang memiliki sub-menu aktif.
                </div>

                <form action="<?= site_url('admin/menu/save'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Halaman / Menu</th>
                                    <th style="width: 220px;">Parent Menu</th>
                                    <th style="width: 110px;" class="text-center">Aktif</th>
                                    <th style="width: 130px;" class="text-center">Mega Menu</th>
                                    <th style="width: 110px;">Urutan</th>
                                    <th style="width: 90px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): $page = $row['page']; $id = (int) $page['id']; ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="page_id[]" value="<?= $id; ?>">
                                            <div class="<?= $row['level'] ? 'ps-4' : ''; ?>">
                                                <?php if ($row['level']): ?><i class="fas fa-level-up-alt fa-rotate-90 text-muted me-2"></i><?php endif; ?>
                                                <strong><?= esc($label($page)); ?></strong>
                                                <div class="small text-muted">/<?= esc($page['slug']); ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="parent_id[<?= $id; ?>]" class="form-select form-select-sm">
                                                <option value="">— Menu Utama —</option>
                                                <?php foreach ($parents as $parent): if ((int) $parent['id'] === $id) continue; ?>
                                                    <option value="<?= (int) $parent['id']; ?>" <?= ((int) ($page['parent_id'] ?? 0) === (int) $parent['id']) ? 'selected' : ''; ?>>
                                                        <?= esc($label($parent)); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input" type="checkbox" name="show_in_menu[<?= $id; ?>]" value="1" <?= !empty($page['show_in_menu']) ? 'checked' : ''; ?> aria-label="Aktifkan <?= esc($label($page)); ?>">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input" type="checkbox" name="is_mega[<?= $id; ?>]" value="1" <?= !empty($page['is_mega']) ? 'checked' : ''; ?> aria-label="Mega menu <?= esc($label($page)); ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="sort_order[<?= $id; ?>]" class="form-control form-control-sm" value="<?= (int) $page['sort_order']; ?>">
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/pages/edit/' . $id); ?>" class="btn btn-sm btn-outline-warning" title="Edit halaman"><i class="fas fa-edit"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Susunan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

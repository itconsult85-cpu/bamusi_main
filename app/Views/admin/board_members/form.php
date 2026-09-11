<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

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
        <h3 class="mb-0"><?= $isEdit ? 'Edit Pengurus' : 'Tambah Pengurus Baru'; ?></h3>
        <a href="<?= base_url('admin/board'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/board/save'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $isEdit ? $item['id'] : ''; ?>">

            <div class="row">
                <div class="col-lg-8">

                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Data Pengurus</h5>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" required
                                    value="<?= $val('name'); ?>"
                                    placeholder="Contoh: H. Fulan, S.E.">
                                <small class="text-muted">Nama tidak akan diterjemahkan.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jabatan (Bahasa Indonesia) <span class="text-danger">*</span></label>
                                <input type="text" name="role" class="form-control" required
                                    value="<?= $val('role'); ?>"
                                    placeholder="Contoh: Ketua Umum">
                                <small class="text-muted">Akan diterjemahkan ke Inggris otomatis.</small>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Keterangan / Catatan</label>
                                <input type="text" name="note" class="form-control"
                                    value="<?= $val('note'); ?>"
                                    placeholder="Contoh: Pengurus BAMUSI">
                            </div>

                        </div>
                    </div>

                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Pengelompokan</h5>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Grup / Kelompok</label>
                                <input type="text" name="group_name" class="form-control"
                                    value="<?= $val('group_name'); ?>"
                                    list="group-list"
                                    placeholder="Contoh: Ketua Umum">
                                <datalist id="group-list">
                                    <option value="Ketua Umum">
                                    <option value="Wakil Ketua Umum">
                                    <option value="Sekretariat Jenderal">
                                    <option value="Bendahara Umum">
                                    <option value="Ketua Bidang">
                                    <option value="Departemen">
                                </datalist>
                                <small class="text-muted">Anggota dengan grup sama akan dikelompokkan di frontend.</small>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Urutan Grup</label>
                                    <input type="number" name="group_order" class="form-control"
                                        value="<?= $val('group_order', 0); ?>">
                                    <small class="text-muted">Kecil = atas</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Urutan Anggota</label>
                                    <input type="number" name="member_order" class="form-control"
                                        value="<?= $val('member_order', 0); ?>">
                                    <small class="text-muted">Kecil = kiri</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="<?= $val('sort_order', 0); ?>">
                                    <small class="text-muted">Fallback</small>
                                </div>
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
                                <label class="form-check-label fw-bold" for="publishCheck">Tampilkan di Website</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> Simpan & Terjemahkan
                            </button>
                        </div>
                    </div>

                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Foto Pengurus</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($isEdit && !empty($item['photo_url'])): ?>
                                <div class="mb-3 text-center">
                                    <img src="<?= base_url($item['photo_url']); ?>"
                                        class="rounded-circle"
                                        style="width:140px;height:140px;object-fit:cover;">
                                    <small class="d-block text-muted mt-2">Foto saat ini</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="photo" class="form-control"
                                accept="image/jpeg,image/png,image/webp">
                            <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB. Rasio 1:1 disarankan.</small>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
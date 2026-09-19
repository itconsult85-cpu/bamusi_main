<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Kelola Halaman</h3>
        <div class="d-flex gap-2">
            <form action="<?= site_url('admin/pages/translate-all'); ?>" method="post" onsubmit="return confirm('Lengkapi terjemahan halaman dan block yang masih kosong?')"><?= csrf_field(); ?><button class="btn btn-outline-success"><i class="fas fa-language me-1"></i> Lengkapi Terjemahan</button></form>
            <a href="<?= base_url('admin/pages/create'); ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Halaman</a>
        </div>
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
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card card-outline card-secondary">
            <div class="card-header bg-light border-0">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Semua halaman publik, termasuk halaman khusus seperti Berita, dikelola dari menu ini. Gunakan tombol tautan untuk melihat halaman.</small>
            </div>
            <div class="card-body table-responsive">
                <table id="pagesTable" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80px;">Image</th>
                            <th>Judul</th>
                            <th style="width:90px;">Menu</th>
                            <th style="width:70px;" class="text-center">Urutan</th>
                            <th style="width:60px;" class="text-center">EN</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:120px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function() {
        $('#pagesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '<?= base_url('admin/pages/ajaxData'); ?>',
            columns: [{
                    data: 0,
                    orderable: false,
                    searchable: false
                },
                {
                    data: 1
                },
                {
                    data: 2,
                    orderable: false,
                    searchable: false
                },
                {
                    data: 3,
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 4,
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 5,
                    orderable: false
                },
                {
                    data: 6,
                    orderable: false,
                    className: 'text-center'
                }
            ],
            order: []
        });
    });
</script>

<?= $this->endSection(); ?>

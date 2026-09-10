<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">Kelola Sections Halaman</h3>
            </div>
            <div class="col-sm-6 text-end">
                <a href="<?= base_url('admin/sections/create'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Section
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
        <?php endif; ?>

        <div class="card card-secondary card-outline">
            <div class="card-body">
                <table id="sectionsTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Kunci Sistem</th>
                            <th>Nama Section</th>
                            <th>Judul ID</th>
                            <th>Terjemahan (EN)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan dimuat otomatis oleh DataTables Server-Side -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#sectionsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/sections/ajax') ?>",
                "type": "GET"
            },
            "columns": [{
                    "orderable": false
                }, // Kunci
                {
                    "orderable": true
                }, // Nama
                {
                    "orderable": true
                }, // Judul
                {
                    "orderable": false
                }, // Terjemahan
                {
                    "orderable": false
                }, // Status
                {
                    "orderable": false,
                    "className": "text-center"
                } // Aksi
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
<?= $this->endSection(); ?>
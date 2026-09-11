<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Kelola Konten & Artikel</h3>
        <a href="<?= base_url('admin/cms-items/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Konten
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
            <div class="card-header">
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary filter-kind active" data-kind="">Semua</button>
                    <button class="btn btn-sm btn-outline-primary filter-kind" data-kind="news">Berita</button>
                    <button class="btn btn-sm btn-outline-primary filter-kind" data-kind="agenda">Agenda</button>
                    <button class="btn btn-sm btn-outline-primary filter-kind" data-kind="article">Artikel</button>
                </div>
            </div>
            <div class="card-body">
                <table id="cmsTable" class="table table-striped table-hover align-middle" style="width:100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80px;">Cover</th>
                            <th>Judul</th>
                            <th style="width:100px;">Jenis</th>
                            <th style="width:130px;">Tanggal</th>
                            <th style="width:60px;" class="text-center">EN</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:130px;" class="text-center">Aksi</th>
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
        let currentKind = '';

        const table = $('#cmsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('admin/cms-items/ajaxData'); ?>',
                data: function(d) {
                    d.kind = currentKind;
                }
            },
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
                    orderable: false
                },
                {
                    data: 3,
                    orderable: false
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
            order: [],
            pageLength: 25,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "→",
                    previous: "←"
                }
            }
        });

        $('.filter-kind').on('click', function() {
            $('.filter-kind').removeClass('active');
            $(this).addClass('active');
            currentKind = $(this).data('kind');
            table.ajax.reload();
        });
    });
</script>

<?= $this->endSection(); ?>
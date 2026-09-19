<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Teks Global (Navbar, Footer, Sistem)</h3>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger" id="btnBulkTranslate">
                <i class="fas fa-language me-1"></i> Bulk Translate (EN Kosong)
            </button>
            <a href="<?= base_url('admin/texts/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Teks
            </a>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="alert alert-info"><i class="fas fa-info-circle me-1"></i> Menu ini hanya untuk teks global seperti nama brand, navbar, footer, dan label sistem. Isi section homepage dikelola dari <a href="<?= base_url('admin/sections'); ?>">Section Homepage</a>, bukan dari menu ini.</div>

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
            <div class="card-header">
                <div class="d-flex gap-2 align-items-center">
                    <label class="mb-0 small fw-bold">Filter Lokasi:</label>
                    <select id="filterLocation" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">Semua Lokasi</option>
                        <?php
                        $db = \Config\Database::connect();
                        $locations = $db->table('website_texts')
                            ->select('location')
                            ->distinct()
                            ->orderBy('location', 'ASC')
                            ->get()->getResultArray();
                        foreach ($locations as $loc):
                            if (empty($loc['location'])) continue;
                        ?>
                            <option value="<?= esc($loc['location']); ?>"><?= esc($loc['location']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="textsTable" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Key / Label</th>
                            <th style="width:130px;">Lokasi</th>
                            <th>Teks (ID)</th>
                            <th style="width:90px;" class="text-center">EN</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:80px;" class="text-center">Aksi</th>
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
        let currentLocation = '';
        const table = $('#textsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('admin/texts/ajaxData'); ?>',
                data: function(d) {
                    d.location = currentLocation;
                }
            },
            columns: [{
                    data: 0
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
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 4,
                    orderable: false
                },
                {
                    data: 5,
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

        // Filter lokasi
        $('#filterLocation').on('change', function() {
            currentLocation = $(this).val();
            table.ajax.reload();
        });

        // Bulk translate
        $('#btnBulkTranslate').on('click', function() {
            const btn = $(this);
            const total = <?= (int) \Config\Database::connect()->table('website_texts')
                                ->groupStart()
                                ->where('value_en', null)
                                ->orWhere('value_en', '')
                                ->groupEnd()
                                ->countAllResults(); ?>;

            if (total === 0) {
                alert('Tidak ada teks yang perlu diterjemahkan. Semua sudah punya versi EN.');
                return;
            }

            if (!confirm('Akan menerjemahkan ' + total + ' teks yang belum punya versi EN. Proses ini membutuhkan waktu. Lanjutkan?')) {
                return;
            }

            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span> Menerjemahkan...'
            );

            window.location.href = '<?= base_url('admin/texts/bulkTranslate'); ?>';
        });
    });
</script>

<?= $this->endSection(); ?>

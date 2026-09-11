<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Pengaturan Website</h3>
        <button type="button" class="btn btn-outline-danger" id="btnBulkTranslate">
            <i class="fas fa-language me-1"></i> Bulk Translate
        </button>
        <a href="<?= base_url('admin/settings/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Setting
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
                        $locs = $db->table('site_settings')
                            ->select('location')->distinct()
                            ->where('location IS NOT NULL')
                            ->where('location !=', '')
                            ->orderBy('location', 'ASC')
                            ->get()->getResultArray();
                        foreach ($locs as $loc):
                        ?>
                            <option value="<?= esc($loc['location']); ?>"><?= esc($loc['location']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Info jumlah baris -->
                    <span class="text-muted small ms-2" id="rowInfo"></span>
                </div>
            </div>
            <div class="card-body">
                <table id="settingsTable" class="table table-striped table-hover align-middle" style="width:100%;">
                    <thead class="table-light">
                        <tr>
                            <th>Key</th>
                            <th>Label / Lokasi</th>
                            <th>Nilai</th>
                            <th>Tipe</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================
     SCRIPTS — urutan penting!
     ============================================ -->

<!-- 1. jQuery (pastikan tidak double-load di layout) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- 2. DataTables Core & Bootstrap -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- 3. Init DataTables -->
<script>
    $(document).ready(function() {
        // Cek apakah jQuery & DataTables sudah ter-load
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak ter-load!');
            return;
        }
        if (!$.fn.DataTable) {
            console.error('DataTables tidak ter-load!');
            return;
        }

        let currentLocation = '';

        const table = $('#settingsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            ajax: {
                url: '<?= base_url('admin/settings/ajaxData'); ?>',
                type: 'GET',
                data: function(d) {
                    d.location = currentLocation;
                },
                error: function(xhr, error, code) {
                    console.error('DataTables AJAX error:', error, xhr.responseText);
                    alert('Gagal memuat data. Cek console untuk detail.');
                }
            },
            columns: [{
                    data: 0,
                    name: 'key'
                },
                {
                    data: 1,
                    name: 'label',
                    orderable: false
                },
                {
                    data: 2,
                    name: 'value',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 3,
                    name: 'type',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 4,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [],
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            language: {
                processing: "Memuat...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Tidak ada data tersedia",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "→",
                    previous: "←"
                }
            },
            drawCallback: function(settings) {
                // Update info jumlah baris di header
                const info = settings.json;
                if (info) {
                    $('#rowInfo').text('(' + info.recordsFiltered + ' data)');
                }
            }
        });

        // Filter lokasi
        $('#filterLocation').on('change', function() {
            currentLocation = $(this).val();
            table.ajax.reload();
        });

        // Debug: tampilkan error jika search tidak bekerja
        table.on('xhr', function(e, settings, json) {
            console.log('DataTables response:', json);
        });
    });

    $('#btnBulkTranslate').on('click', function() {
        if (!confirm('Terjemahkan semua setting yang belum punya versi EN?')) return;
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Menerjemahkan...');
        window.location.href = '<?= base_url('admin/settings/bulkTranslate'); ?>';
    });
</script>

<?= $this->endSection(); ?>
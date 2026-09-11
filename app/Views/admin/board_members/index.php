<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Kelola Susunan Pengurus</h3>
        <a href="<?= base_url('admin/board/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Pengurus
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
                    <label class="mb-0 small fw-bold">Filter Grup:</label>
                    <select id="filterGroup" class="form-select form-select-sm" style="width: 250px;">
                        <option value="">Semua Grup</option>
                        <?php
                        $db = \Config\Database::connect();
                        $groups = $db->table('board_members')
                            ->select('group_name')
                            ->where('group_name IS NOT NULL')
                            ->where('group_name !=', '')
                            ->distinct()
                            ->orderBy('group_order', 'ASC')
                            ->get()->getResultArray();
                        foreach ($groups as $g):
                        ?>
                            <option value="<?= esc($g['group_name']); ?>"><?= esc($g['group_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <table id="boardTable" class="table table-striped table-hover align-middle" style="width:100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px;">Foto</th>
                            <th>Nama</th>
                            <th style="width:180px;">Grup</th>
                            <th>Jabatan</th>
                            <th style="width:110px;" class="text-center">Grup / Anggota</th>
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
        let currentGroup = '';

        const table = $('#boardTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('admin/board/ajaxData'); ?>',
                data: function(d) {
                    d.group = currentGroup;
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
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 6,
                    orderable: false
                },
                {
                    data: 7,
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

        $('#filterGroup').on('change', function() {
            currentGroup = $(this).val();
            table.ajax.reload();
        });
    });
</script>

<?= $this->endSection(); ?>
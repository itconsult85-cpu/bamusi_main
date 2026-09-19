<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | BAMUSI</title>

    <!-- Google Font: Source Sans Pro (Bawaan AdminLTE) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style (AdminLTE 4 Beta/RC) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        <!-- NAVBAR -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center px-2 text-secondary small">
                        <?= esc((string) session()->get('user_name')) ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= base_url('/'); ?>" target="_blank">
                            <i class="fas fa-globe"></i> Lihat Website
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="<?= site_url('logout'); ?>" method="post" class="m-0">
                            <?= csrf_field() ?>
                            <button class="nav-link btn btn-link text-danger" type="submit"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- SIDEBAR -->
        <aside class="app-sidebar bg-body-secondary shadow-sm" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="<?= base_url('admin'); ?>" class="brand-link">
                    <span class="brand-text fw-bold">Admin BAMUSI</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                        <li class="nav-item">
                            <a href="<?= base_url('admin'); ?>"
                                class="nav-link <?= (current_url() === base_url('admin')) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">KONTEN</li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/sections'); ?>"
                                class="nav-link <?= (strpos(current_url(), 'admin/sections') !== false) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-layer-group"></i>
                                <p>Section Homepage</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/pages'); ?>"
                                class="nav-link <?= (strpos(current_url(), 'admin/pages') !== false) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Halaman / Pages</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/menu'); ?>"
                                class="nav-link <?= (strpos(current_url(), 'admin/menu') !== false) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-bars"></i>
                                <p>Susunan Menu</p>
                            </a>
                        </li>

                        <li class="nav-header">DATA</li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/cms-items'); ?>"
                                class="nav-link <?= (strpos(current_url(), 'admin/cms-items') !== false) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Konten & Artikel</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/programs'); ?>"
                                class="nav-link <?= (strpos(current_url(), 'admin/programs') !== false) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-project-diagram"></i>
                                <p>Program</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/board'); ?>"
                                class="nav-link <?= (strpos(current_url(), 'admin/board') !== false) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Susunan Pengurus</p>
                            </a>
                        </li>

                        <li class="nav-header">PENGATURAN</li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/settings'); ?>"
                                class="nav-link <?= (strpos(current_url(), 'admin/settings') !== false) ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Pengaturan Website</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="app-main">
            <!-- Di sinilah halaman-halaman lain akan disuntikkan -->
            <?= $this->renderSection('content'); ?>
        </main>

        <!-- FOOTER -->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Versi 1.0</div>
            <strong>&copy; <?= date('Y'); ?> PP BAMUSI.</strong>
        </footer>

    </div>

    <!-- Script AdminLTE 4 & Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script>
</body>

</html>

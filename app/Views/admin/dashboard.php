<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Dashboard</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- Small Box (Widget khas AdminLTE) -->
                <div class="small-box text-bg-primary">
                    <div class="inner">
                        <h3>150</h3>
                        <p>Total Berita</p>
                    </div>
                    <i class="small-box-icon fas fa-newspaper"></i>
                    <a href="<?= base_url('admin/cms'); ?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        Kelola <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>53</h3>
                        <p>Pendaftar Magang</p>
                    </div>
                    <i class="small-box-icon fas fa-users"></i>
                    <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        Lihat Data <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
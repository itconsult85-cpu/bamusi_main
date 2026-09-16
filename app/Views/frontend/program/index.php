<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>
<?php
$locale = $locale ?? 'id';
$isEn = $locale === 'en';
$t = static function (array $row, string $field) use ($isEn) {
    return $isEn && !empty($row[$field . '_en']) ? $row[$field . '_en'] : ($row[$field] ?? '');
};
$title = $t($page, 'title') ?: ($isEn ? 'BAMUSI Programs' : 'Program BAMUSI');
$headTitle = $page['header_title'] ?: $title;
$headIntro = $page['header_intro'] ?: $t($page, 'excerpt');
?>
<section class="py-5 text-white" style="background:linear-gradient(135deg,#8a0000 0%,#420000 100%);padding-top:10rem!important;padding-bottom:6rem!important;">
    <div class="container">
        <?php if (!empty($page['header_show_back'])): ?><a href="<?= base_url('/'); ?>" class="text-white text-decoration-none opacity-75">← <?= $isEn ? 'Back to Home' : 'Kembali ke Beranda'; ?></a><?php endif; ?>
        <div class="mt-4 col-lg-9">
            <?php if (!empty($page['header_kicker'])): ?><div class="text-uppercase fw-bold text-warning small" style="letter-spacing:3px;"><?= esc($page['header_kicker']); ?></div><?php endif; ?>
            <h1 class="display-3 fw-bold mt-3 mb-3"><?= esc($headTitle); ?></h1>
            <?php if (!empty($page['header_show_intro'])): ?><p class="lead opacity-75 mb-0"><?= esc($headIntro); ?></p><?php endif; ?>
        </div>
    </div>
</section>
<section class="py-5 bg-light">
    <div class="container">
        <form method="get" action="<?= base_url('program'); ?>" class="card border-0 shadow-sm rounded-4 p-3 mb-5">
            <div class="row g-2 align-items-center">
                <div class="col-lg-6"><div class="input-group"><span class="input-group-text bg-white"><i class="fas fa-search"></i></span><input type="search" name="q" value="<?= esc($search); ?>" class="form-control" placeholder="<?= $isEn ? 'Search programs...' : 'Cari program...'; ?>"></div></div>
                <div class="col-lg-4"><select name="division" class="form-select"><option value=""><?= $isEn ? 'All fields' : 'Semua bidang'; ?></option><?php foreach ($divisions as $row): ?><option value="<?= esc($row['division']); ?>" <?= $division === $row['division'] ? 'selected' : ''; ?>><?= esc($row['division']); ?></option><?php endforeach; ?></select></div>
                <div class="col-lg-2 d-grid"><button class="btn btn-danger rounded-pill" type="submit"><?= $isEn ? 'Filter' : 'Tampilkan'; ?></button></div>
            </div>
        </form>
        <div class="d-flex justify-content-between align-items-center mb-4"><h2 class="h3 fw-bold mb-0"><?= $isEn ? 'All Programs' : 'Semua Program'; ?></h2><span class="text-muted small"><?= count($programs); ?> <?= $isEn ? 'programs' : 'program'; ?></span></div>
        <div class="row g-4">
            <?php foreach ($programs as $program): $programTitle = $t($program, 'name'); $description = $t($program, 'description'); $image = trim((string)($program['image_url'] ?? '')); if ($image && !preg_match('#^https?://#i', $image)) $image = base_url(ltrim($image, '/')); ?>
                <div class="col-md-6 col-xl-4"><a href="<?= base_url('program/' . rawurlencode($program['slug'])); ?>" class="text-decoration-none"><article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden program-card"><div class="ratio ratio-16x9 bg-danger-subtle"><?php if ($image): ?><img src="<?= esc($image); ?>" class="w-100 h-100 object-fit-cover" alt="<?= esc($programTitle); ?>"><?php else: ?><div class="d-flex align-items-center justify-content-center text-danger display-4 fw-bold">B</div><?php endif; ?></div><div class="card-body p-4"><span class="badge rounded-pill bg-danger-subtle text-danger"><?= esc($program['division'] ?: 'BAMUSI'); ?></span><h3 class="h4 text-dark fw-bold mt-3"><?= esc($programTitle); ?></h3><p class="text-secondary mb-0"><?= esc(mb_strimwidth(strip_tags($description), 0, 150, '...')); ?></p></div><div class="card-footer bg-white border-0 px-4 pb-4"><span class="text-danger fw-semibold"><?= $isEn ? 'View details' : 'Lihat rincian'; ?> <span aria-hidden="true">↗</span></span></div></article></a></div>
            <?php endforeach; ?>
        </div>
        <?php if (!$programs): ?><div class="alert alert-light border text-center py-5 mt-4"><?= $isEn ? 'No programs found.' : 'Program tidak ditemukan.'; ?></div><?php endif; ?>
    </div>
</section>
<style>.program-card{transition:transform .25s ease,box-shadow .25s ease}.program-card:hover{transform:translateY(-6px);box-shadow:0 1rem 2rem rgba(80,0,0,.12)!important}</style>
<?= $this->endSection(); ?>

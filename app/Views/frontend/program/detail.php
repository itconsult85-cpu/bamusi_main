<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>
<?php
$isEn = ($locale === 'en');
$title = ($isEn && !empty($program['name_en'])) ? $program['name_en'] : $program['name'];
$description = ($isEn && !empty($program['description_en'])) ? $program['description_en'] : $program['description'];
$division = $program['division'] ?? 'BAMUSI';

$image = trim((string)($program['image_url'] ?? ''));
if ($image && !preg_match('#^https?://#i', $image)) {
    $image = base_url(ltrim($image, '/'));
}
?>

<section class="py-5 text-white" style="background:linear-gradient(120deg,#640000 0%,#9f0000 100%);padding-top:9rem!important;">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-8">
                <span class="badge rounded-pill bg-white text-danger text-uppercase px-3 py-2 mb-3">
                    <?= $isEn ? 'Program Room' : 'Ruang Khidmah' ?>
                </span>
                <h1 class="display-4 fw-bold mb-3"><?= esc($title); ?></h1>
                <p class="lead mb-0 opacity-75">
                    <?= $isEn ? 'Division: ' : 'Bidang: ' ?> <?= esc($division); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-5">
    <div class="container">
        <div class="row justify-content-center py-4">
            <div class="col-lg-10">

                <?php if ($image): ?>
                    <img src="<?= esc($image); ?>" alt="<?= esc($title); ?>" class="img-fluid rounded-4 shadow-sm mb-5 w-100" style="max-height: 500px; object-fit: cover;">
                <?php endif; ?>

                <div class="fs-5 text-dark" style="line-height: 1.8;">
                    <?= $description; ?>
                </div>

                <div class="mt-5 border-top pt-4">
                    <a href="<?= base_url('program'); ?>" class="btn btn-outline-danger rounded-pill px-4">
                        &larr; <?= $isEn ? 'Back to Programs' : 'Kembali ke Program'; ?>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->extend('layout/frontend'); ?>
<?= $this->section('title'); ?><?= esc($meta_title); ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php
$isEn = ($locale === 'en');
$title = ($isEn && !empty($page['title_en'])) ? $page['title_en'] : $page['title'];
$body  = ($isEn && !empty($page['body_en'])) ? $page['body_en'] : $page['body'];

$kicker = $page['header_kicker'] ?: 'BAMUSI';
$hTitle = $page['header_title'] ?: $title;
$hIntro = $page['header_intro'] ?: '';
?>

<!-- Hero Section -->
<section class="py-5 text-white" style="background:linear-gradient(120deg,#640000 0%,#9f0000 100%);padding-top:9rem!important;">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge rounded-pill bg-white text-danger text-uppercase px-3 py-2 mb-3"><?= esc($kicker); ?></span>
                <h1 class="display-4 fw-bold mb-3"><?= esc($hTitle); ?></h1>
                <?php if ($hIntro): ?>
                    <p class="lead mb-0 opacity-75"><?= esc($hIntro); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Konten Utama Editor CMS -->
<section class="bg-white py-5">
    <div class="container">
        <div class="row justify-content-center py-4">
            <div class="col-lg-10">
                <div class="page-content text-dark" style="line-height: 1.8; font-size: 1.1rem;">
                    <?= $body; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* CSS ini memastikan gambar, quote, dan heading dari Summernote tampil rapi */
    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .page-content blockquote {
        border-left: 5px solid #8b0000;
        padding: 1.5rem;
        font-style: italic;
        background: #f8f9fa;
        margin: 2rem 0;
    }

    .page-content h2,
    .page-content h3 {
        color: #8b0000;
        margin-top: 2rem;
        font-weight: bold;
    }
</style>
<?= $this->endSection(); ?>
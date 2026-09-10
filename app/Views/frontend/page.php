<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php
$locale = $locale ?? 'id';
$t = function ($array, $field) use ($locale) {
    if ($locale === 'en' && !empty($array[$field . '_en'])) return $array[$field . '_en'];
    return $array[$field] ?? '';
};

$title    = $t($page, 'title');
$excerpt  = $t($page, 'excerpt');
$body     = $t($page, 'body');
$kicker   = $page['header_kicker'] ?? '';
$headTitle = $page['header_title'] ?: $title;
$headIntro = $page['header_intro'] ?: $excerpt;
$logo     = $page['header_logo_url'] ?? '';
$showLogo  = (int)($page['header_show_logo'] ?? 1);
$showIntro = (int)($page['header_show_intro'] ?? 1);
$showBack  = (int)($page['header_show_back'] ?? 1);
$image    = $page['image_url'] ?? '';
if ($image && !preg_match('#^https?://#i', $image)) $image = base_url(ltrim($image, '/'));
?>

<!-- ===== HEADER PAGE ===== -->
<section class="page-header position-relative overflow-hidden"
    style="background: linear-gradient(135deg, #8a0000 0%, #4a0000 50%, #200000 100%); padding: 140px 0 80px;">
    <div class="position-absolute" style="top: -20%; right: -10%; width: 40vw; height: 40vw;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 70%);
        border-radius: 50%; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1; min-height: 340px; display: flex; flex-direction: column; justify-content: center;">

        <?php if ($showBack): ?>
            <a href="<?= base_url('/'); ?>" class="text-white text-decoration-none mb-4 d-inline-flex align-items-center gap-2 opacity-75"
                style="font-size: 0.85rem; letter-spacing: 1px; transition: opacity 0.3s;"
                onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.75'">
                <span style="font-size: 1.1rem;">←</span> <?= $locale === 'en' ? 'Back to Home' : 'Kembali ke Beranda'; ?>
            </a>
        <?php endif; ?>

        <div class="row align-items-center">
            <div class="col-lg-9">
                <?php if ($kicker): ?>
                    <span class="text-uppercase fw-bold d-block mb-3"
                        style="color: #ff9999; letter-spacing: 3px; font-size: 0.8rem;">
                        <?= esc($kicker); ?>
                    </span>
                <?php endif; ?>

                <h1 class="fw-bolder text-white mb-0"
                    style="font-size: clamp(2.2rem, 5vw, 4rem); letter-spacing: -1.5px; line-height: 1.1;">
                    <?= esc($headTitle); ?>
                </h1>

                <?php if ($showIntro && $headIntro): ?>
                    <p class="text-white opacity-75 fs-5 mt-4 mb-0 pe-lg-5" style="line-height: 1.6;">
                        <?= esc($headIntro); ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if ($showLogo && $logo): ?>
                <div class="col-lg-3 text-lg-end mt-4 mt-lg-0">
                    <img src="<?= base_url(ltrim($logo, '/')); ?>" alt="Logo"
                        style="max-height: 120px; opacity: 0.9;">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ===== CONTENT ===== -->
<section class="py-5 bg-white" style="position: relative;">
    <div class="container-fluid px-4 px-lg-5 py-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">

                <?php if ($image): ?>
                    <figure class="mb-5">
                        <img src="<?= esc($image); ?>" alt="<?= esc($title); ?>"
                            class="img-fluid rounded-3 w-100"
                            style="max-height: 460px; object-fit: cover;">
                    </figure>
                <?php endif; ?>

                <?php if (!empty($body)): ?>
                    <article class="page-body fs-5 lh-lg text-dark" style="line-height: 1.9;">
                        <?= $body ?>
                    </article>
                <?php else: ?>
                    <div class="alert alert-light border text-center py-5">
                        <em class="text-muted">Konten halaman belum diisi.</em>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<style>
    /* Styling konten Summernote */
    .page-body h1,
    .page-body h2,
    .page-body h3 {
        color: #8b0000;
        font-weight: 800;
        margin-top: 2rem;
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
    }

    .page-body h2 {
        font-size: 1.75rem;
    }

    .page-body h3 {
        font-size: 1.35rem;
    }

    .page-body p {
        margin-bottom: 1.25rem;
    }

    .page-body img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .page-body blockquote {
        border-left: 4px solid #cc0000;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        background: #fdf5f5;
        font-style: italic;
        color: #555;
    }

    .page-body ul,
    .page-body ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }

    .page-body a {
        color: #cc0000;
        text-decoration: underline;
    }

    .page-body a:hover {
        color: #8b0000;
    }
</style>

<?= $this->endSection(); ?>
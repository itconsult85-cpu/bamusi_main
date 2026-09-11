<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php

$settings = $settings ?? [];
$sections = $sections ?? [];
$aboutValues = $aboutValues ?? [];
$programs = $programs ?? [];
$agenda = $agenda ?? [];
$news = $news ?? [];
$board = $board ?? [];
$partners = $partners ?? [];
$texts = $texts ?? [];

$locale = $locale ?? 'id';

$t = function ($array, $field) use ($locale) {
    if ($locale === 'en' && !empty($array[$field . '_en'])) {
        return $array[$field . '_en'];
    }
    return $array[$field] ?? '';
};

$t_br = function ($array, $field) use ($t) {
    $text = $t($array, $field);
    return str_replace('|', '<br>', esc($text));
};

// Helper: ambil URL gambar dari section (tanpa fallback hardcoded)
$sectionImg = function ($key) use ($sections) {
    $url = trim((string)($sections[$key]['media_url'] ?? ''));
    if ($url === '') return '';
    if (!preg_match('#^https?://#i', $url)) {
        $url = base_url(ltrim($url, '/'));
    }
    return $url;
};
?>

<?php
$heroSlides = $heroSlides ?? [];

// Helper terjemahan untuk slide
$ts = function ($row, $field) use ($locale) {
    if ($locale === 'en' && !empty($row[$field . '_en'])) return $row[$field . '_en'];
    return $row[$field] ?? '';
};
?>

<section class="hero-split-wrapper">
    <div class="hero-split-bg-right d-none d-lg-block"></div>
    <div id="mainHeroCarousel" class="carousel slide carousel-fade hero-carousel"
        data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner h-100">
            <?php foreach ($heroSlides as $i => $slide):
                $photo = trim((string)($slide['image_url'] ?? ''));
                if ($photo !== '' && !preg_match('#^https?://#i', $photo)) {
                    $photo = base_url(ltrim($photo, '/'));
                }
                if ($photo === '') continue;
            ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : ''; ?> h-100">
                    <div class="container-fluid px-4 px-lg-5 h-100">
                        <div class="row align-items-center h-100">
                            <div class="col-lg-7 py-5">
                                <?php if (!empty($slide['kicker'])): ?>
                                    <span class="eyebrow-text"
                                        style="color: <?= esc($slide['kicker_color'] ?: '#e7aa6b'); ?>;">
                                        <?= esc($ts($slide, 'kicker')); ?>
                                    </span>
                                <?php endif; ?>

                                <h1 class="fw-bolder mb-4"
                                    style="font-size: clamp(3rem, 5vw, 5.5rem);
                                           letter-spacing:-2px;
                                           text-shadow: 0 10px 30px rgba(0,0,0,0.3);
                                           line-height: 1.1;
                                           color: <?= esc($slide['title_color'] ?: '#ffffff'); ?>;">
                                    <?= str_replace('|', '<br>', esc($ts($slide, 'title'))); ?>
                                </h1>

                                <?php if (!empty($slide['quote'])): ?>
                                    <h2 class="fs-5 mb-2"
                                        style="color: <?= esc($slide['quote_color'] ?: '#e7aa6b'); ?>;">
                                        <?= esc($ts($slide, 'quote')); ?>
                                    </h2>
                                <?php endif; ?>
                                <br>

                                <?php if (!empty($slide['lead'])): ?>
                                    <p class="fs-5 mb-5 pe-lg-5"
                                        style="color: <?= esc($slide['lead_color'] ?: '#d7e8dd'); ?>; opacity: 0.85;">
                                        <?= esc($ts($slide, 'lead')); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($slide['button_label'])): ?>
                                    <a href="<?= esc($slide['button_url'] ?: '#'); ?>"
                                        class="btn rounded-0 text-dark bg-white fw-bold px-4 py-3 text-uppercase">
                                        <?= esc($ts($slide, 'button_label')); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-5 d-none d-lg-flex hero-image-col">
                                <div class="arched-frame">
                                    <div class="arched-frame-inner">
                                        <img src="<?= esc($photo); ?>" alt="Hero Slide <?= $i + 1; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($heroSlides) > 1): ?>
            <div class="carousel-indicators">
                <?php foreach ($heroSlides as $i => $s): ?>
                    <button type="button" data-bs-target="#mainHeroCarousel"
                        data-bs-slide-to="<?= $i; ?>"
                        class="<?= $i === 0 ? 'active' : ''; ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-5 position-relative" style="background-color: #f8f9fa;" id="tentang">
    <div class="position-absolute d-none d-lg-block" style="top: 2rem; right: 4rem;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="var(--bamusi-red, #a00000)" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
        </svg>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-5 mt-4">
        <div class="row align-items-center">
            <div class="col-lg-8 pe-lg-5 mb-5 mb-lg-0">
                <div class="d-flex align-items-start mb-5">
                    <div class="me-4">
                        <hr style="width: 35px; border-top: 3px solid var(--bamusi-red, #a00000); opacity: 1; margin: 0 0 10px 0;">
                        <span class="fw-bold" style="color: var(--bamusi-red, #a00000); font-size: 1.1rem;">02</span>
                    </div>
                    <div class="pt-1">
                        <span class="text-uppercase fw-bold" style="color: var(--bamusi-red, #a00000); letter-spacing: 2px; font-size: 0.85rem;">
                            <?= esc($t($sections['about'] ?? [], 'kicker')); ?>
                        </span>
                    </div>
                </div>

                <h2 class="fw-bolder mb-4" style="font-size: clamp(3rem, 5vw, 4.5rem); letter-spacing: -2px; line-height: 1.1; color: #8b0000;">
                    <?= $t_br($sections['about'] ?? [], 'title'); ?>
                </h2>

                <p class="fs-4 lh-base text-secondary mt-4 pe-lg-4">
                    <?= esc($t($sections['about'] ?? [], 'subtitle')); ?>
                </p>
            </div>

            <div class="col-lg-4 ps-lg-5">
                <!-- LINK LIST (dari section_links) -->
                <?php if (!empty($sectionLinks['about'])): ?>
                    <div class="about-sidebar">
                        <?php foreach ($sectionLinks['about'] as $link):
                            $label    = ($locale === 'en' && !empty($link['label_en']))    ? $link['label_en']    : $link['label'];
                            $sublabel = ($locale === 'en' && !empty($link['sublabel_en'])) ? $link['sublabel_en'] : $link['sublabel'];
                            $url      = $link['url'] ?? '#';
                            if (!preg_match('#^https?://#i', $url) && $url !== '#') {
                                $url = base_url(ltrim($url, '/'));
                            }
                        ?>
                            <a href="<?= esc($url); ?>" class="about-link">
                                <div class="about-link-head">
                                    <span class="about-link-label"><?= esc($label); ?></span>
                                    <span class="about-link-arrow">↗</span>
                                </div>
                                <?php if (!empty($sublabel)): ?>
                                    <span class="about-link-sub"><?= esc($sublabel); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- Fallback: kotak ornamen + tombol existing -->
                    <div class="border-start border-2 ps-4 ps-lg-5 py-3" style="border-color: #e0e0e0 !important;">
                        <div class="mb-4 position-relative d-inline-block">
                            <div style="width: 50px; height: 60px; border: 8px solid var(--bamusi-red, #a00000); border-bottom: 0; border-top-left-radius: 25px; border-top-right-radius: 25px;"></div>
                            <div style="width: 8px; height: 8px; background-color: var(--bamusi-red, #a00000); border-radius: 50%; position: absolute; top: 25px; left: 21px;"></div>
                        </div>
                        <p class="text-secondary mb-4" style="font-size: 0.95rem; line-height: 1.6;">
                            <?= esc($t($sections['about'] ?? [], 'content')); ?>
                        </p>
                        <a href="<?= esc($sections['about']['button_url'] ?? '#visi'); ?>" class="text-dark fw-bold text-decoration-none border-bottom border-dark pb-1 d-inline-block" style="font-size: 0.9rem;">
                            <?= esc($t($sections['about'] ?? [], 'button_label')); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
$nilai = $sections['nilai'] ?? [];
?>
<section class="py-5 text-white position-relative overflow-hidden" id="nilai"
    style="background: linear-gradient(135deg, #111111 0%, #2b0000 100%);">
    <div class="position-absolute" style="top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(204,0,0,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="position-absolute" style="bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">

        <!-- HEADER: Kicker + Judul + Deskripsi (dari CMS) -->
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div style="max-width: 720px;">
                <!-- Kicker (lebih besar) -->
                <span class="text-uppercase fw-bold d-block mb-4"
                    style="color: #ffffff; letter-spacing: 2.5px; font-size: 1.1rem;">
                    <?= esc($t($nilai, 'kicker')); ?>
                </span>

                <!-- Deskripsi langsung (judul dihilangkan) -->
                <?php if (!empty($t($nilai, 'subtitle'))): ?>
                    <p class="mb-0"
                        style="color: rgba(255,255,255,0.75); font-size: 0.9rem; line-height: 1.75; font-weight: 400; max-width: 620px;">
                        <?= esc($t($nilai, 'subtitle')); ?>
                    </p>
                <?php endif; ?>
            </div>

            <span class="fw-bolder fs-5 opacity-25 d-none d-md-block"
                style="letter-spacing: 2px; color: #ffffff;">05 / BAMUSI</span>
        </div>

        <!-- KARTU 5 NILAI (dari tabel about_values) -->
        <div class="row g-3 g-lg-4 pt-4 mt-2 border-top"
            style="border-color: rgba(255,255,255,0.1) !important;">
            <?php if (!empty($aboutValues)): ?>
                <?php foreach (array_slice($aboutValues, 0, 5) as $i => $value): ?>
                    <div class="col-6 col-md-4 col-lg flex-grow-1">
                        <a href="<?= esc($nilai['button_url'] ?? base_url('lima-nilai-utama')); ?>"
                            class="text-decoration-none d-block h-100 p-4 rounded-4"
                            style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px); transition: all 0.3s ease;"
                            onmouseover="this.style.background='rgba(204,0,0,0.9)'; this.style.transform='translateY(-8px)'; this.style.borderColor='#cc0000';"
                            onmouseout="this.style.background='rgba(255,255,255,0.03)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.05)';">
                            <div class="d-flex flex-column h-100 min-vh-25" style="min-height: 140px;">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <span class="fw-bolder fs-6 opacity-50 text-white" style="letter-spacing: 1px;">
                                        <?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT); ?>
                                    </span>
                                    <span class="text-white opacity-75 fs-5">↗</span>
                                </div>
                                <h3 class="fw-bold fs-5 mt-auto mb-0 text-white" style="line-height: 1.3;">
                                    <?= esc($t($value, 'label')); ?>
                                </h3>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<section class="pb-0 bg-white" id="visi">
    <div class="row g-0 align-items-stretch">
        <div class="col-lg-6 position-relative">
            <?php $imgVisi = $sectionImg('visi'); ?>
            <?php if ($imgVisi): ?>
                <img src="<?= esc($imgVisi); ?>" class="w-100 h-100"
                    style="object-fit: cover; border-top-right-radius: clamp(100px, 15vw, 150px); min-height: 400px;"
                    alt="Visi Misi">
            <?php else: ?>
                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center"
                    style="border-top-right-radius: clamp(100px, 15vw, 150px); min-height: 400px;">
                    <span class="text-muted small">Gambar belum diunggah</span>
                </div>
            <?php endif; ?>
            <div class="position-absolute" style="bottom: 40px; left: 40px; z-index: 2;">
                <div class="d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 55px; height: 55px; background-color: var(--bamusi-red, #c8102e);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="col-lg-6 ps-lg-5 pe-lg-5 px-4 d-flex flex-column justify-content-center py-5">
            <div class="pe-xl-5">
                <span class="eyebrow-text text-danger text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;">
                    <?= $locale === 'en' ? 'VISION AND MISSION' : 'VISI DAN MISI'; ?>
                </span>

                <h2 class="fw-bolder mb-4 mt-2" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing: -1.5px; line-height: 1.1; color: var(--bamusi-dark, #212529);">
                    <?= $locale === 'en' ? 'Becoming the national home of progressive Indonesian Muslims.' : 'Menjadi rumah kebangsaan Muslim Indonesia yang progresif.'; ?>
                </h2>

                <p class="fs-5 mb-5 text-secondary lh-base">
                    <?= esc($settings['about_vision'] ?? ''); ?>
                </p>

                <div class="mt-4">
                    <?php
                    $raw_missions = $settings['about_mission'] ?? '';
                    $missions = explode("\n", trim($raw_missions));
                    foreach ($missions as $index => $mission):
                        if (trim($mission) == '') continue;
                        $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    ?>
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
                            <span class="fw-bold me-4" style="color: var(--bamusi-red, #c8102e); font-size: 1rem;"><?= $num; ?></span>
                            <p class="mb-0 fs-6 fw-semibold text-dark">
                                <?= esc(trim($mission)); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-0 mt-0 text-white position-relative overflow-hidden" id="sejarah" style="background: linear-gradient(135deg, #111111 0%, #2b0000 100%);">
    <div class="position-absolute" style="top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(204,0,0,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="position-absolute" style="bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="row g-0 align-items-stretch position-relative" style="z-index: 1;">
        <div class="col-lg-5 position-relative d-flex align-items-center justify-content-center justify-content-lg-end px-4 pe-lg-4 py-5" style="min-height: 450px;">
            <?php $imgSejarah = $sectionImg('history'); ?>
            <?php if ($imgSejarah): ?>
                <img src="<?= esc($imgSejarah); ?>" class="position-absolute w-100 h-100"
                    style="top: 0; left: 0; object-fit: cover; object-position: center; z-index: -2;"
                    alt="Sejarah BAMUSI">
            <?php endif; ?>
            <div class="position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(to right, rgba(17,17,17,0.3) 0%, rgba(17,17,17,1) 98%); z-index: -1;"></div>
            <div class="position-absolute w-100 h-100 d-lg-none" style="bottom: 0; left: 0; background: linear-gradient(to bottom, rgba(17,17,17,0) 60%, rgba(17,17,17,1) 100%); z-index: -1;"></div>

            <h1 class="m-0 lh-1 position-relative" style="font-size: clamp(4rem, 8vw, 8rem); font-weight: 900; letter-spacing: -3px; color: #e60000; text-shadow: 3px 3px 0px #660000, 6px 6px 0px #330000, 12px 12px 25px rgba(0,0,0,0.9), -2px -2px 15px rgba(204,0,0,0.3); transform: translateY(-5px);">
                <?= $locale === 'en' ? 'HISTORY' : 'SEJARAH'; ?>
            </h1>
        </div>

        <div class="col-lg-7 px-4 ps-lg-5 pe-lg-5 py-5 d-flex flex-column justify-content-center">
            <div class="pe-xl-5 py-lg-4" style="max-width: 800px;">
                <h2 class="fw-bolder mb-4 text-uppercase text-white" style="font-size: clamp(2.2rem, 4.2vw, 4.2rem); letter-spacing: -1.5px; line-height: 1;">
                    <?= esc($t($sections['history'] ?? [], 'title')); ?>
                </h2>
                <p class="fs-5 mb-5 text-white opacity-75 lh-base">
                    <?= esc($t($sections['history'] ?? [], 'subtitle')); ?>
                </p>
                <div>
                    <a class="fw-bold text-white text-decoration-none border-bottom border-2 border-white pb-1 fs-6" href="<?= esc($sections['history']['button_url'] ?? base_url('sejarah')); ?>" style="transition: all 0.3s ease;" onmouseover="this.style.opacity='0.7'; this.style.borderColor='rgba(255,255,255,0.5)';" onmouseout="this.style.opacity='1'; this.style.borderColor='white';">
                        <?= esc($t($sections['history'] ?? [], 'button_label')); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white" id="pengurus">
    <style>
        .pengurus-scroll-wrapper::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div class="container-fluid px-4 px-lg-5 py-5">
        <div class="row align-items-center">
            <div class="col-lg-4 mb-5 mb-lg-0 pe-lg-5">
                <h2 class="fw-bold text-dark mb-5" style="font-size: clamp(2rem, 3vw, 2.5rem); line-height: 1.3;">
                    <?= $t_br($sections['board'] ?? [], 'title'); ?>
                </h2>
                <a href="<?= esc($sections['board']['button_url'] ?? base_url('struktur-pengurus')); ?>" class="btn rounded-0 text-white fw-bold px-4 py-3 text-uppercase" style="background-color: #cc0000; letter-spacing: 1px; font-size: 0.9rem;">
                    <?= $locale === 'en' ? 'View All' : 'Lihat Semua'; ?>
                </a>
            </div>

            <div class="col-lg-8">
                <div class="board-slider-container pengurus-scroll-wrapper d-flex flex-nowrap gap-4 pb-3" style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">
                    <?php if (!empty($board)): ?>
                        <?php foreach (array_slice($board, 0, 8) as $member):
                            $photo = trim((string)($member['photo_url'] ?? ''));
                            if ($photo !== '' && !preg_match('#^https?://#i', $photo)) {
                                $photo = base_url(ltrim($photo, '/'));
                            }
                        ?>
                            <div class="card border rounded-3 flex-shrink-0" style="width: 260px;">
                                <div class="card-body p-4 text-center d-flex flex-column align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden mb-4" style="width: 140px; height: 140px; background-color: #cc0000;">
                                        <?php if ($photo !== ''): ?>
                                            <img src="<?= esc($photo); ?>" alt="<?= esc($member['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="text-white fw-bolder" style="font-size: 3rem;"><?= esc(strtoupper(substr($member['name'], 0, 1))); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="fw-bold text-uppercase mb-2" style="color: #cc0000; font-size: 1rem; line-height: 1.4;">
                                        <?= esc($member['name']); ?>
                                    </h5>
                                    <small class="text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <?= esc($t($member, 'role')); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-0 position-relative" id="khidmah" style="background-color: #0a0a0a;">
    <?php $imgKhidmah = $sectionImg('program'); ?>
    <div class="position-relative text-white py-5 curved-banner overflow-hidden"
        style="<?= $imgKhidmah
                    ? "background: url('" . esc($imgKhidmah) . "') no-repeat right bottom / cover;"
                    : 'background-color: #3a0202;'; ?> min-height: 550px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, #3a0202 0%, #3a0202 40%, rgba(58,2,2,0.85) 60%, rgba(0,0,0,0.3) 100%);"></div>

        <div class="container-fluid px-4 px-lg-5 py-5 position-relative w-100" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-7 py-4">
                    <span class="eyebrow-text text-uppercase fw-bold" style="color: #ff9999; letter-spacing: 2px; font-size: 0.85rem;">
                        <?= esc($t($sections['program'] ?? [], 'kicker')); ?>
                    </span>
                    <h2 class="fw-bolder mt-3 mb-4 text-white" style="font-size: clamp(2.5rem, 4.5vw, 4.5rem); letter-spacing:-1.5px; line-height: 1.1;">
                        <?= $t_br($sections['program'] ?? [], 'title'); ?>
                    </h2>
                    <p class="fs-5 mb-4 text-white opacity-85 lh-base pe-lg-5">
                        <?= esc($t($sections['program'] ?? [], 'subtitle')); ?>
                    </p>

                    <a href="#daftar-bidang" class="btn bg-white text-dark rounded-pill fw-bold px-4 py-3 mt-2 text-uppercase shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;" onclick="document.getElementById('daftar-bidang').scrollIntoView({behavior: 'smooth'}); return false;">
                        <?= $locale === 'en' ? 'Explore Fields' : 'Jelajahi Bidang'; ?>
                        <span class="fs-6">↘</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-5" id="daftar-bidang" style="background: radial-gradient(ellipse at 50% 0%, #3a0000 0%, #0a0a0a 60%, #000000 100%);">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 g-4 py-4">
            <?php if (!empty($programs)): ?>
                <?php foreach (array_slice($programs, 0, 5) as $i => $program): ?>
                    <div class="col">
                        <a href="<?= base_url('program/' . esc($program['slug'] ?? '')); ?>" class="text-decoration-none d-block h-100">
                            <article class="p-4 rounded-4 position-relative h-100 d-flex flex-column overflow-hidden text-white"
                                style="background: rgba(20, 5, 5, 0.65); border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(12px); transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);"
                                onmouseover="this.style.background='rgba(150, 0, 0, 0.4)'; this.style.transform='translateY(-8px)'; this.style.borderColor='rgba(255,255,255,0.3)'; this.querySelector('.watermark-letter').style.opacity='0.12'; this.querySelector('.arrow-icon').style.transform='translate(3px, -3px)';"
                                onmouseout="this.style.background='rgba(20, 5, 5, 0.65)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.12)'; this.querySelector('.watermark-letter').style.opacity='0.03'; this.querySelector('.arrow-icon').style.transform='translate(0, 0)';">

                                <span class="watermark-letter position-absolute fw-black" style="font-size: 8rem; right: -10px; bottom: -20px; opacity: 0.03; color: #ffffff; line-height: 1; pointer-events: none; transition: opacity 0.4s ease;">
                                    <?= chr(65 + $i); ?>
                                </span>

                                <h3 class="fs-5 fw-bold mb-3 text-white position-relative" style="z-index: 1;">
                                    <?= esc($t($program, 'name')); ?>
                                </h3>
                                <p class="text-white opacity-75 mb-4 position-relative" style="font-size: 0.95rem; z-index: 1;">
                                    <?= esc($t($program, 'description')); ?>
                                </p>

                                <div class="mt-auto d-flex justify-content-between align-items-end position-relative" style="z-index: 1;">
                                    <span class="fw-bold text-white opacity-50" style="font-size: 0.85rem; letter-spacing: 1.5px;">
                                        0<?= $i + 1; ?>
                                    </span>
                                    <div class="arrow-icon text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background: rgba(255,255,255,0.15); transition: transform 0.3s ease;">
                                        <span class="fs-6">↗</span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="bg-light" id="program-unggulan">
    <div class="container-fluid p-0">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-6 position-relative pe-lg-4 pb-4 pb-lg-0 d-flex">
                <div class="position-relative overflow-hidden w-100" style="border-top-right-radius: 120px;">
                    <?php $imgFeature = $sectionImg('feature'); ?>
                    <?php if ($imgFeature): ?>
                        <img src="<?= esc($imgFeature); ?>" alt="Program Unggulan"
                            class="img-fluid w-100 h-100" style="object-fit: cover; min-height: 550px;">
                    <?php else: ?>
                        <div class="w-100 d-flex align-items-center justify-content-center bg-light"
                            style="min-height: 550px;">
                            <span class="text-muted small">Gambar belum diunggah</span>
                        </div>
                    <?php endif; ?>
                    <div class="position-absolute" style="top: 30px; right: 30px; bottom: -10px; left: -10px; border-top: 2px solid rgba(255,255,255,0.9); border-right: 2px solid rgba(255,255,255,0.9); border-top-right-radius: 90px; z-index: 2; pointer-events: none;"></div>
                </div>
            </div>

            <div class="col-lg-6 py-5 px-4 px-lg-5 d-flex align-items-center">
                <div class="ps-lg-5 w-100" style="max-width: 650px;">

                    <!-- Kicker (opsional) -->
                    <?php if (!empty($t($sections['feature'] ?? [], 'kicker'))): ?>
                        <span class="text-uppercase fw-bold d-block mb-3"
                            style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.8rem;">
                            <?= esc($t($sections['feature'] ?? [], 'kicker')); ?>
                        </span>
                    <?php endif; ?>

                    <!-- Judul -->
                    <?php if (!empty($t($sections['feature'] ?? [], 'title'))): ?>
                        <h2 class="fw-bolder mb-4"
                            style="font-size: clamp(2rem, 3.5vw, 3rem); letter-spacing: -1.5px; line-height: 1.15; color: #8b0000;">
                            <?= $t_br($sections['feature'] ?? [], 'title'); ?>
                        </h2>
                    <?php endif; ?>

                    <!-- Paragraf pengantar -->
                    <p class="text-dark mb-5 lh-base" style="font-size: clamp(1.1rem, 1.6vw, 1.4rem); font-weight: 400;">
                        <?= esc($t($sections['feature'] ?? [], 'subtitle')); ?>
                    </p>

                    <div class="d-flex flex-column">
                        <?php
                        $featureItems = [
                            [
                                'title' => ($locale === 'en' ? 'Chairman\'s Language' : 'Bahasa Ketum'),
                                'body' => ($locale === 'en' ? 'National ideas in speech that are close, reflective, and easy to understand.' : 'Gagasan kebangsaan dalam tutur yang dekat, reflektif, dan mudah dipahami.')
                            ],
                            [
                                'title' => ($locale === 'en' ? 'Mega Dhikr' : 'Mega Dzikir'),
                                'body' => ($locale === 'en' ? 'An assembly of prayer and togetherness that confirms spirituality and social care.' : 'Majelis doa dan kebersamaan yang meneguhkan spiritualitas, persatuan, serta kepedulian sosial.')
                            ],
                            [
                                'title' => ($locale === 'en' ? 'Open Communication' : 'Komunikasi Terbuka'),
                                'body' => ($locale === 'en' ? 'Delivery of public attitudes and agendas directly, responsibly, and based on facts.' : 'Penyampaian sikap dan agenda publik secara langsung, bertanggung jawab, dan berbasis fakta.')
                            ]
                        ];

                        foreach ($featureItems as $index => $item): ?>
                            <div class="d-flex align-items-start py-4 border-top" style="border-color: #dcdcdc !important;">
                                <div class="me-4" style="color: var(--bamusi-red, #cc0000); min-width: 40px; margin-top: 2px;">
                                    <span class="fs-5 fw-medium" style="letter-spacing: 1px;">
                                        <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="fw-bold fs-5 mb-2 text-dark">
                                        <?= esc($item['title']); ?>
                                    </h3>
                                    <p class="text-secondary mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                        <?= esc($item['body']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 text-white position-relative overflow-hidden" id="agenda" style="background: linear-gradient(135deg, #8a0000 0%, #4a0000 50%, #200000 100%);">
    <div class="position-absolute" style="top: -30%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
        <div class="row align-items-lg-center">
            <div class="col-lg-5 pe-lg-5 mb-5 mb-lg-0">
                <span class="text-uppercase fw-bold opacity-75" style="letter-spacing: 2px; font-size: 0.85rem;">
                    <?= esc($t($sections['agenda'] ?? [], 'kicker')); ?>
                </span>
                <h2 class="fw-bolder my-3" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1.5px; line-height: 1.1;">
                    <?= $t_br($sections['agenda'] ?? [], 'title'); ?>
                </h2>
                <p class="opacity-75 fs-5 mb-0">
                    <?= esc($t($sections['agenda'] ?? [], 'subtitle')); ?>
                </p>
            </div>

            <div class="col-lg-7">
                <div class="pt-2" style="border-top: 2px solid rgba(255,255,255,0.2);">
                    <?php if (!empty($agenda)): ?>
                        <?php foreach (array_slice($agenda, 0, 4) as $i => $item):
                            $agendaSlug = $item['slug'] ?? $item['id'] ?? '#';
                            $agendaUrl = base_url('agenda/' . $agendaSlug);
                        ?>
                            <a href="<?= $agendaUrl; ?>" class="text-decoration-none text-white d-block group-agenda">
                                <article class="d-flex align-items-center py-4 px-3 rounded-3 position-relative"
                                    style="border-bottom: 1px solid rgba(255,255,255,0.15); transition: all 0.3s ease;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.paddingLeft='20px';"
                                    onmouseout="this.style.background='transparent'; this.style.paddingLeft='1rem';">

                                    <div class="fw-bolder fs-3 me-4 opacity-50" style="min-width: 40px;">
                                        <?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT); ?>
                                    </div>
                                    <div class="flex-grow-1 pe-3">
                                        <p class="text-uppercase mb-1 opacity-75 fw-bold" style="font-size: 0.75rem; letter-spacing: 1.5px;">
                                            <?= esc($item['event_date'] ?? ($locale === 'en' ? 'Schedule coming soon' : 'Jadwal segera')); ?>
                                        </p>
                                        <h3 class="fw-bold mb-0 fs-4" style="letter-spacing: -0.5px;">
                                            <?= esc($t($item, 'title')); ?>
                                        </h3>
                                    </div>
                                    <div class="fs-4 fw-bold opacity-75 ps-2" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='translateX(5px)'">
                                        →
                                    </div>
                                </article>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white position-relative overflow-hidden" id="berita">
    <div class="position-absolute w-100 h-100 top-0 start-0" style="pointer-events: none; z-index: 0;">
        <div class="container-fluid px-4 px-lg-5 h-100 position-relative">
            <div class="row h-100 justify-content-between">
                <div class="col-3 h-100 border-end" style="border-color: rgba(0,0,0,0.06) !important;"></div>
                <div class="col-3 h-100 border-end d-none d-lg-block" style="border-color: rgba(0,0,0,0.06) !important;"></div>
                <div class="col-3 h-100 d-none d-lg-block" style="border-color: rgba(0,0,0,0.06) !important;"></div>
            </div>
        </div>
        <div class="position-absolute text-uppercase fw-black" style="font-size: 14vw; bottom: 8%; left: -1%; color: rgba(200,0,0,0.035); line-height: 1; user-select: none; letter-spacing: -5px;">
            <?= $locale === 'en' ? 'NEWS' : 'KABAR'; ?>
        </div>
        <div class="position-absolute" style="top: -10%; right: -5%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.04) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="eyebrow-text text-uppercase fw-bold" style="color: var(--bamusi-red, #c8102e); letter-spacing: 2px; font-size: 0.85rem;">
                    <?= esc($t($sections['news'] ?? [], 'kicker')); ?>
                </span>
                <h2 class="fw-bolder mt-3 mb-0" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1px; line-height: 1.1; color: var(--bamusi-dark, #212529);">
                    <?= $t_br($sections['news'] ?? [], 'title'); ?>
                </h2>
            </div>
            <a href="<?= esc($sections['news']['button_url'] ?? base_url('berita')); ?>" class="d-none d-md-block fw-bold text-dark text-decoration-none border-bottom border-2 border-dark pb-1 text-uppercase fs-6 transition-all" style="transition: opacity 0.3s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                <?= esc($t($sections['news'] ?? [], 'button_label')); ?> ↗
            </a>
        </div>

        <div class="row g-4 pt-4 mt-2" style="border-top: 2px solid var(--bamusi-dark, #212529);">
            <?php if (!empty($news)): ?>
                <?php foreach (array_slice($news, 0, 4) as $i => $item): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="card h-100 rounded-4 p-4 position-relative d-flex flex-column bg-white shadow-sm"
                            style="border: 1px solid rgba(0,0,0,0.08); transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);"
                            onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'; this.style.borderColor='var(--bamusi-red, #c8102e)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'; this.style.borderColor='rgba(0,0,0,0.08)';">

                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <span class="badge text-uppercase px-2.5 py-1.5" style="background-color: var(--bamusi-red, #c8102e); letter-spacing: 1px; font-size: 0.65rem;">
                                    <?= esc($item['category'] ?? 'Nasional'); ?>
                                </span>
                                <small class="text-secondary fw-bold" style="font-size: 0.75rem;">
                                    <?= esc($item['event_date'] ?? ''); ?>
                                </small>
                            </div>

                            <h3 class="fw-bold fs-5 mb-4 lh-base text-dark">
                                <a href="<?= esc($item['url'] ?? '#'); ?>" target="_blank" class="text-dark text-decoration-none stretched-link">
                                    <?= esc($t($item, 'title')); ?>
                                </a>
                            </h3>

                            <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.06) !important;">
                                <small class="text-uppercase fw-bold text-secondary" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <span style="color: var(--bamusi-red, #c8102e);"><?= $locale === 'en' ? 'Source:' : 'Sumber:'; ?></span> <?= esc($item['source'] ?? 'BAMUSI'); ?>
                                </small>
                                <span class="text-dark fw-bold fs-6">↗</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light position-relative" id="tulisan" style="overflow: hidden;">
    <div class="position-absolute" style="bottom: -20px; right: 8%; width: 180px; height: 220px; border: 30px solid rgba(204,0,0,0.06); border-bottom: 0; border-top-left-radius: 100px; border-top-right-radius: 100px; z-index: 0; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 pe-lg-5">
                <span class="text-uppercase fw-bold d-block mb-3" style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.8rem;">
                    <?= esc($t($sections['writing'] ?? [], 'kicker')); ?>
                </span>
                <h2 class="fw-bolder text-dark" style="font-size: clamp(2.5rem, 4vw, 3.5rem); letter-spacing: -1.5px; line-height: 1.1;">
                    <?= $t_br($sections['writing'] ?? [], 'title'); ?>
                </h2>
            </div>

            <div class="col-lg-5 offset-lg-1">
                <p class="fs-6 text-dark lh-base mb-4 pe-lg-4" style="font-weight: 400;">
                    <?= esc($t($sections['writing'] ?? [], 'subtitle')); ?>
                </p>
                <a href="<?= esc($sections['writing']['button_url'] ?? '#bergabung'); ?>" class="btn rounded-pill fw-bold text-uppercase d-inline-flex align-items-center gap-2"
                    style="border: 1px solid var(--bamusi-red, #cc0000); color: var(--bamusi-red, #cc0000); font-size: 0.85rem; padding: 0.6rem 1.5rem; transition: all 0.3s;"
                    onmouseover="this.style.backgroundColor='var(--bamusi-red, #cc0000)'; this.style.color='#ffffff';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--bamusi-red, #cc0000)';">
                    <?= esc($t($sections['writing'] ?? [], 'button_label')); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </a>
            </div>
        </div>

        <?php
        $topics = [
            ($locale === 'en' ? 'Archipelago Islam' : 'Islam Nusantara'),
            ($locale === 'en' ? 'Democracy & Nationality' : 'Demokrasi & kebangsaan'),
            ($locale === 'en' ? 'Education & Boarding' : 'Pesantren & pendidikan'),
            ($locale === 'en' ? 'Women, Family & Youth' : 'Perempuan, keluarga & generasi muda')
        ];
        ?>
        <div class="row g-0 pt-4" style="border-top: 1px solid #212529;">
            <?php foreach ($topics as $index => $topic): ?>
                <div class="col-lg-3 col-6 <?= $index < 3 ? 'border-end' : ''; ?>" style="border-color: #e0e0e0 !important;">
                    <a href="#" class="d-block h-100 p-4 text-decoration-none" style="transition: background-color 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#fdf0f0';"
                        onmouseout="this.style.backgroundColor='transparent';">
                        <span class="fw-bold d-block mb-4" style="color: var(--bamusi-red, #cc0000); font-size: 0.9rem;">
                            <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?>
                        </span>
                        <h3 class="fs-6 fw-bold text-dark lh-base pe-lg-3 mb-0">
                            <?= esc($topic); ?>
                        </h3>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5 position-relative overflow-hidden" id="sosial-media" style="background: linear-gradient(135deg, #111111 0%, #2b0000 100%);">
    <div class="position-absolute" style="top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(204,0,0,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="position-absolute" style="bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
        <div class="border-bottom pb-3 mb-5" style="border-color: rgba(255,255,255,0.2) !important;">
            <span class="text-uppercase fw-bold text-white opacity-75" style="letter-spacing: 2px; font-size: 0.75rem;">
                <?= esc($t($sections['social'] ?? [], 'kicker')); ?>
            </span>
        </div>

        <div class="row align-items-end mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bolder text-white mb-0" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing: -1.5px; line-height: 1.1;">
                    <?= $t_br($sections['social'] ?? [], 'title'); ?>
                </h2>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <p class="text-white opacity-75 mb-0 fs-6 lh-base pe-lg-5">
                    <?= esc($t($sections['social'] ?? [], 'subtitle')); ?>
                </p>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-lg-4 col-md-6">
                <a href="<?= esc($settings['instagram_url'] ?? '#'); ?>" target="_blank" class="text-decoration-none d-block h-100 position-relative overflow-hidden"
                    style="background-color: var(--bamusi-red, #cc0000); transition: transform 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);"
                    onmouseover="this.style.transform='translateY(-10px)';" onmouseout="this.style.transform='translateY(0)';">
                    <div class="position-absolute" style="bottom: -20px; right: -20px; width: 150px; height: 150px; border-radius: 50%; background: rgba(0,0,0,0.1);"></div>
                    <div class="p-4 p-lg-5 d-flex flex-column h-100 position-relative z-1">
                        <span class="text-white fw-bold text-uppercase mb-4" style="letter-spacing: 2px; font-size: 0.8rem;">Instagram</span>
                        <h3 class="text-white fw-bolder mb-5 pb-4" style="font-size: clamp(1.5rem, 2vw, 2.2rem); word-break: break-word; line-height: 1.1;">
                            @baitul.muslimin.indonesia
                        </h3>
                        <div class="mt-auto d-flex align-items-center gap-3">
                            <div class="rounded-circle border border-2 border-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </div>
                            <span class="text-white fw-bold fs-6"><?= $locale === 'en' ? 'Open profile ↘' : 'Buka profil ↘'; ?></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6">
                <a href="<?= esc($settings['tiktok_url'] ?? '#'); ?>" target="_blank" class="text-decoration-none d-block h-100 position-relative overflow-hidden"
                    style="background-color: #ffffff; transition: transform 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);"
                    onmouseover="this.style.transform='translateY(-10px)';" onmouseout="this.style.transform='translateY(0)';">
                    <div class="position-absolute" style="bottom: 0; right: 0; width: 200px; height: 200px; border-top-left-radius: 200px; background: rgba(0,0,0,0.03);"></div>
                    <div class="p-4 p-lg-5 d-flex flex-column h-100 position-relative z-1">
                        <span class="text-secondary fw-bold text-uppercase mb-4" style="letter-spacing: 2px; font-size: 0.8rem;">TikTok</span>
                        <h3 class="text-dark fw-bolder mb-5 pb-4" style="font-size: clamp(1.5rem, 2vw, 2.2rem); word-break: break-word; line-height: 1.1;">
                            @baitulmusliminindonesia
                        </h3>
                        <div class="mt-auto d-flex align-items-center gap-3">
                            <div class="rounded-circle border border-2 border-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#212529" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                                </svg>
                            </div>
                            <span class="text-dark fw-bold fs-6"><?= $locale === 'en' ? 'Open profile ↘' : 'Buka profil ↘'; ?></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-12">
                <a href="<?= esc($settings['youtube_url'] ?? '#'); ?>" target="_blank" class="text-decoration-none d-block h-100 position-relative overflow-hidden"
                    style="background-color: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);"
                    onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='var(--bamusi-red, #cc0000)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.1)';">
                    <div class="position-absolute" style="top: -20px; right: -20px; width: 120px; height: 120px; border: 20px solid rgba(255,255,255,0.02); border-radius: 20px; transform: rotate(15deg);"></div>
                    <div class="p-4 p-lg-5 d-flex flex-column h-100 position-relative z-1">
                        <span class="text-white opacity-75 fw-bold text-uppercase mb-4" style="letter-spacing: 2px; font-size: 0.8rem;">YouTube</span>
                        <h3 class="text-white fw-bolder mb-5 pb-4" style="font-size: clamp(1.5rem, 2vw, 2.2rem); word-break: break-word; line-height: 1.1;">
                            @bamusitv
                        </h3>
                        <div class="mt-auto d-flex align-items-center gap-3">
                            <div class="rounded-circle border border-2 border-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; opacity: 0.8;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path>
                                    <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                                </svg>
                            </div>
                            <span class="text-white opacity-75 fw-bold fs-6"><?= $locale === 'en' ? 'Open profile ↘' : 'Buka profil ↘'; ?></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-5 position-relative" style="background-color: #fdfdfd; overflow: hidden;" id="mitra">
    <div class="position-absolute d-none d-lg-block" style="top: 3rem; left: 2rem; opacity: 0.05; color: var(--bamusi-red, #cc0000);">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
        </svg>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
        <div class="row align-items-end mb-5 pb-3">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <span class="text-uppercase fw-bold d-block mb-3" style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.8rem;">
                    <?= $locale === 'en' ? 'Working Partners' : 'Mitra Kerja Sama'; ?>
                </span>
                <h2 class="fw-bolder m-0" style="color: #8b0000; font-size: clamp(2.5rem, 4vw, 3.5rem); letter-spacing: -1.5px; line-height: 1.1;">
                    <?= $locale === 'en' ? 'Growing through<br>networks and collaboration.' : 'Bertumbuh melalui<br>jejaring dan kolaborasi.'; ?>
                </h2>
            </div>
            <div class="col-lg-5 text-lg-end pb-lg-2">
                <p class="text-secondary mb-0 fs-6" style="font-weight: 400;">
                    <?= $locale === 'en' ? 'Click on the logo to visit the official website of each institution.' : 'Klik logo untuk mengunjungi situs resmi masing-masing lembaga.'; ?>
                </p>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-0 border-top border-start" style="border-color: #eaeaea !important;">
            <?php if (!empty($partners)): ?>
                <?php foreach ($partners as $partner):
                    if (!(int)($partner['published'] ?? 0)) continue;
                ?>
                    <div class="col border-end border-bottom position-relative overflow-hidden bg-white" style="border-color: #eaeaea !important;">
                        <div class="position-absolute" style="bottom: -40px; left: 50%; transform: translateX(-50%); width: 120px; height: 120px; border: 15px solid rgba(204,0,0,0.04); border-radius: 50%; pointer-events: none;"></div>
                        <a href="<?= esc($partner['website_url'] ?? '#'); ?>" target="_blank" class="d-flex flex-column align-items-center justify-content-between p-4 h-100 text-decoration-none text-dark position-relative z-1"
                            style="transition: background-color 0.3s ease, transform 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#fff5f5'; this.querySelector('.arrow-icon')?.style?.transform='translate(3px, -3px)';"
                            onmouseout="this.style.backgroundColor='transparent'; this.querySelector('.arrow-icon')?.style?.transform='translate(0, 0)';">
                            <div class="d-flex align-items-center justify-content-center mb-4" style="height: 120px;">
                                <img src="<?= esc($partner['logo_url'] ?? ''); ?>" alt="<?= esc($partner['name']); ?>" class="img-fluid" style="max-height: 90px; object-fit: contain;">
                            </div>
                            <span class="d-block fw-bold text-center mb-4 text-dark" style="font-size: 0.85rem; line-height: 1.4;">
                                <?= esc($partner['name']); ?>
                            </span>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="bergabung">
    <div class="container-fluid p-0">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-5 p-4 p-lg-5 d-flex flex-column justify-content-between" style="background-color: var(--bamusi-red, #cc0000);">
                <div class="mb-5 pb-4 text-white" style="margin-top: 2rem;">
                    <span class="text-uppercase fw-bold d-block mb-3 opacity-75" style="letter-spacing: 2px; font-size: 0.85rem;">
                        <?= esc($t($sections['join'] ?? [], 'kicker')); ?>
                    </span>
                    <h2 class="fw-bolder mb-4" style="font-size: clamp(3rem, 5vw, 4.5rem); letter-spacing: -1.5px; line-height: 1.05;">
                        <?= $t_br($sections['join'] ?? [], 'title'); ?>
                    </h2>
                    <p class="fs-6 opacity-85 lh-base pe-lg-4" style="font-weight: 300;">
                        <?= esc($t($sections['join'] ?? [], 'subtitle')); ?>
                    </p>
                </div>

                <div class="p-4 p-lg-5 mt-auto shadow-sm" style="background-color: #8b0000; border-left: 6px solid #ffffff;">
                    <div class="d-flex align-items-center gap-3 mb-4 text-white">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span class="text-uppercase fw-bold" style="letter-spacing: 2px; font-size: 0.85rem;">
                            <?= esc($t($sections['internship'] ?? [], 'kicker')); ?>
                        </span>
                    </div>
                    <h2 class="fw-bolder text-white mb-3" style="font-size: clamp(2rem, 3vw, 2.5rem); letter-spacing: -1px; line-height: 1.1;">
                        <?= $t_br($sections['internship'] ?? [], 'title'); ?>
                    </h2>
                    <p class="text-white mb-0 opacity-85" style="font-size: 0.95rem; line-height: 1.6; font-weight: 300;">
                        <?= esc($t($sections['internship'] ?? [], 'subtitle')); ?>
                    </p>
                </div>
            </div>

            <div class="col-lg-7 d-flex flex-column justify-content-center p-4 p-lg-5 bg-white">
                <div class="w-100 px-lg-4" style="max-width: 800px; margin: 0 auto;">
                    <form>
                        <div class="row py-3 mb-4" style="row-gap: 3rem; column-gap: 1rem;">
                            <div class="col-md-5">
                                <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Full Name' : 'Nama Lengkap'; ?></label>
                                <input type="text" name="name" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                            </div>
                            <div class="col-md-5 offset-md-1">
                                <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Domicile' : 'Domisili'; ?></label>
                                <input type="text" name="domicile" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                            </div>
                            <div class="col-md-5">
                                <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Email Address' : 'Alamat Email'; ?></label>
                                <input type="email" name="email" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                            </div>
                            <div class="col-md-5 offset-md-1">
                                <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'WhatsApp Number' : 'Nomor WhatsApp'; ?></label>
                                <input type="tel" name="phone" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                            </div>
                            <div class="col-12">
                                <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Interest' : 'Minat Kontribusi'; ?></label>
                                <select name="interest" class="form-select shadow-none px-0 rounded-0 fw-bold" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1rem; color: #111111; cursor: pointer;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'">
                                    <option value="" disabled selected><?= $locale === 'en' ? 'Choose interest' : 'Pilih minat'; ?></option>
                                    <option value="Keanggotaan Umum"><?= $locale === 'en' ? 'General Membership' : 'Keanggotaan Umum'; ?></option>
                                    <option value="Relawan Program"><?= $locale === 'en' ? 'Program Volunteer' : 'Relawan Program'; ?></option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Short Message' : 'Pesan Singkat'; ?></label>
                                <textarea name="message" rows="3" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; resize: none; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'"></textarea>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn rounded-pill d-inline-flex align-items-center gap-3 mb-3" style="background: transparent; border: 1px solid var(--bamusi-red, #cc0000); color: var(--bamusi-red, #cc0000); font-weight: 700; font-size: 0.85rem; padding: 0.75rem 2rem; letter-spacing: 0.5px; transition: 0.3s;" onmouseover="this.style.backgroundColor='var(--bamusi-red, #cc0000)'; this.style.color='#ffffff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--bamusi-red, #cc0000)';">
                                <?= $locale === 'en' ? 'SEND VIA WHATSAPP' : 'KIRIM MELALUI WHATSAPP'; ?>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                </svg>
                            </button>
                            <div class="d-flex align-items-center gap-2" style="color: #666666; font-size: 0.85rem;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?= $locale === 'en' ? 'Data is not stored on this site.' : 'Data tidak disimpan di situs ini.'; ?></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .reveal {
        opacity: 0;
        will-change: opacity, transform;
        transition: opacity 0.8s cubic-bezier(0.165, 0.84, 0.44, 1), transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .reveal.slide-from-left {
        transform: translateX(-40px);
    }

    .reveal.slide-from-right {
        transform: translateX(40px);
    }

    .reveal.fade-up-stagger {
        transform: translateY(40px);
    }

    .reveal.zoom-in {
        transform: scale(0.95);
    }

    .reveal.in-view {
        opacity: 1;
        transform: translate(0) scale(1);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("in-view");
                }
            });
        }, {
            rootMargin: '0px 0px -50px 0px',
            threshold: 0.05
        });

        document.querySelectorAll(".row > .col-lg-5:first-child, .row > .col-lg-6:first-child, .row > .col-lg-7:first-child").forEach(el => {
            el.classList.add("reveal", "slide-from-left");
            observer.observe(el);
        });

        document.querySelectorAll(".row > .col-lg-5:last-child, .row > .col-lg-6:last-child, .row > .col-lg-7:last-child").forEach(el => {
            if (!el.classList.contains("reveal")) {
                el.classList.add("reveal", "slide-from-right");
                observer.observe(el);
            }
        });

        document.querySelectorAll(".row > .col-lg-4, .row > .col-lg-3, .row > .col, .row > .col-6").forEach(el => {
            if (!el.classList.contains("reveal")) {
                el.classList.add("reveal", "fade-up-stagger");
                let siblingIndex = Array.from(el.parentNode.children).indexOf(el);
                el.style.transitionDelay = (siblingIndex * 0.1) + "s";
                observer.observe(el);
            }
        });

        document.querySelectorAll("section > .container-fluid > h2.text-center, .eyebrow-text").forEach(el => {
            if (!el.classList.contains("reveal")) {
                el.classList.add("reveal", "zoom-in");
                observer.observe(el);
            }
        });
    });
</script>

<?= $this->endSection(); ?>
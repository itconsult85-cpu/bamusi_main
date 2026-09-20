<?php
$section = $section ?? [];
$items = $items ?? [];
$sectionLinks = $items;
$locale = $locale ?? 'id';
$t = static function (array $row, string $field) use ($locale): string {
    return (string) (($locale === 'en' && !empty($row[$field . '_en'])) ? $row[$field . '_en'] : ($row[$field] ?? ''));
};
$ts = $t;
$visi = $section;
$visiKicker = $t($section, 'kicker');
$visiTitle = $t($section, 'title');
$t_br = static function (array $row, string $field) use ($t): string { return str_replace('|', '<br>', esc($t($row, $field))); };
$sectionImg = static function (string $key) use ($section): string {
    $url = trim((string) ($section['media_url'] ?? ''));
    return $url === '' ? '' : (preg_match('#^https?://#i', $url) ? $url : base_url(ltrim($url, '/')));
};
?>
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="hero-split-wrapper">
        <div class="hero-split-bg-right d-none d-lg-block"></div>
        <div id="hero-carousel-<?= esc($block['id'] ?? 'dynamic'); ?>" class="carousel slide carousel-fade hero-carousel"
            data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner h-100">
                <?php foreach ($items as $i => $slide):
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

            <?php if (count($items) > 1): ?>
                <div class="carousel-indicators">
                    <?php foreach ($items as $i => $s): ?>
                        <button type="button" data-bs-target="#hero-carousel-<?= esc($block['id'] ?? 'dynamic'); ?>"
                            data-bs-slide-to="<?= $i; ?>"
                            class="<?= $i === 0 ? 'active' : ''; ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

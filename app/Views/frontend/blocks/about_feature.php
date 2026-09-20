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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="homepage-feature-section bg-light" id="section-<?= esc($section['section_key'] ?? 'section'); ?>">
        <div class="container-fluid p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-6 position-relative pe-lg-4 pb-4 pb-lg-0 d-flex">
                    <div class="position-relative overflow-hidden w-100" style="border-top-right-radius: 120px;">
                        <?php $imgFeature = $sectionImg($section['section_key'] ?? ''); ?>
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
                        <?php if (!empty($t($section ?? [], 'kicker'))): ?>
                            <span class="text-uppercase fw-bold d-block mb-3"
                                style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.8rem;">
                                <?= esc($t($section ?? [], 'kicker')); ?>
                            </span>
                        <?php endif; ?>

                        <!-- Judul -->
                        <?php if (!empty($t($section ?? [], 'title'))): ?>
                            <h2 class="fw-bolder mb-4"
                                style="font-size: clamp(2rem, 3.5vw, 3rem); letter-spacing: -1.5px; line-height: 1.15; color: #8b0000;">
                                <?= $t_br($section ?? [], 'title'); ?>
                            </h2>
                        <?php endif; ?>

                        <!-- Paragraf pengantar -->
                            <p class="text-dark mb-5 lh-base" style="font-size: clamp(1.1rem, 1.6vw, 1.4rem); font-weight: 400;">
                            <?= esc($t($section ?? [], 'subtitle')); ?>
                        </p>

                        <div class="d-flex flex-column">
                            <?php
                            $featureItems = $items;

                            foreach ($featureItems as $index => $item): ?>
                                <div class="feature-list-item d-flex align-items-start py-4 border-top" style="border-color: #dedede !important;">
                                    <div class="me-4" style="color: var(--bamusi-red, #cc0000); min-width: 52px; margin-top: 2px;">
                                        <span class="fw-bold" style="font-size: 1rem; letter-spacing: 2px;">
                                            <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold fs-5 mb-2 text-dark" style="letter-spacing:-.3px;">
                                            <?= esc($t($item, 'title') ?: $t($item, 'label')); ?>
                                        </h3>
                                        <p class="text-secondary mb-0" style="font-size: 0.95rem; line-height: 1.7; max-width: 520px;">
                                            <?= esc($t($item, 'body')); ?>
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

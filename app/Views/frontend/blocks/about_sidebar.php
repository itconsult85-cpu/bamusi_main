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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 position-relative" style="background-color: #f8f9fa;" id="section-<?= esc($section['section_key'] ?? 'section'); ?>">
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
                                <?= esc($t($section ?? [], 'kicker')); ?>
                            </span>
                        </div>
                    </div>

                    <h2 class="fw-bolder mb-4" style="font-size: clamp(3rem, 5vw, 4.5rem); letter-spacing: -2px; line-height: 1.1; color: #8b0000;">
                        <?= $t_br($section ?? [], 'title'); ?>
                    </h2>

                    <p class="fs-4 lh-base text-secondary mt-4 pe-lg-4">
                        <?= esc($t($section ?? [], 'subtitle')); ?>
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
                                <?= esc($t($section ?? [], 'content')); ?>
                            </p>
                            <a href="<?= esc($section['button_url'] ?? '#visi'); ?>" class="text-dark fw-bold text-decoration-none border-bottom border-dark pb-1 d-inline-block" style="font-size: 0.9rem;">
                                <?= esc($t($section ?? [], 'button_label')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

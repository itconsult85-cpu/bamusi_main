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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 position-relative" style="background-color: #fdfdfd; overflow: hidden;" id="section-<?= esc($section['section_key'] ?? 'section'); ?>">
        <div class="position-absolute d-none d-lg-block" style="top: 3rem; left: 2rem; opacity: 0.05; color: var(--bamusi-red, #cc0000);">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
            </svg>
        </div>

        <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
            <div class="row align-items-end mb-5 pb-3">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <span class="text-uppercase fw-bold d-block mb-3" style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.8rem;">
                        <?= esc($t($section ?? [], 'kicker')); ?>
                    </span>
                    <h2 class="fw-bolder m-0" style="color: #8b0000; font-size: clamp(2.5rem, 4vw, 3.5rem); letter-spacing: -1.5px; line-height: 1.1;">
                        <?= $t_br($section ?? [], 'title'); ?>
                    </h2>
                </div>
                <div class="col-lg-5 text-lg-end pb-lg-2">
                    <p class="text-secondary mb-0 fs-6" style="font-weight: 400;">
                        <?= esc($t($section ?? [], 'subtitle')); ?>
                    </p>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-0 border-top border-start" style="border-color: #eaeaea !important;">
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $partner):
                        if (!(int) ($partner['published'] ?? 0)) continue;
                        $partnerName = $t($partner, 'name') ?: ($t($partner, 'title') ?: $t($partner, 'label'));
                        $partnerLogo = trim((string) ($partner['logo_url'] ?? $partner['image_url'] ?? $partner['media_url'] ?? ''));
                        if ($partnerLogo !== '' && !preg_match('#^https?://#i', $partnerLogo)) {
                            $partnerLogo = base_url(ltrim($partnerLogo, '/'));
                        }
                        $partnerWebsite = trim((string) ($partner['website_url'] ?? $partner['url'] ?? '#'));
                        if ($partnerWebsite !== '#' && !preg_match('#^https?://#i', $partnerWebsite) && $partnerWebsite[0] !== '#') {
                            $partnerWebsite = base_url(ltrim($partnerWebsite, '/'));
                        }
                    ?>
                        <div class="col border-end border-bottom position-relative overflow-hidden bg-white" style="border-color: #eaeaea !important;">
                            <div class="position-absolute" style="bottom: -40px; left: 50%; transform: translateX(-50%); width: 120px; height: 120px; border: 15px solid rgba(204,0,0,0.04); border-radius: 50%; pointer-events: none;"></div>
                            <a href="<?= esc($partnerWebsite); ?>" target="_blank" class="d-flex flex-column align-items-center justify-content-between p-4 h-100 text-decoration-none text-dark position-relative z-1"
                                style="transition: background-color 0.3s ease, transform 0.3s ease;"
                                onmouseover="this.style.backgroundColor='#fff5f5'; this.querySelector('.arrow-icon')?.style?.transform='translate(3px, -3px)';"
                                onmouseout="this.style.backgroundColor='transparent'; this.querySelector('.arrow-icon')?.style?.transform='translate(0, 0)';">
                                <div class="d-flex align-items-center justify-content-center mb-4" style="height: 120px;">
                                    <?php if ($partnerLogo !== ''): ?><img src="<?= esc($partnerLogo); ?>" alt="<?= esc($partnerName); ?>" class="img-fluid" style="max-height: 90px; object-fit: contain;"><?php endif; ?>
                                </div>
                                <span class="d-block fw-bold text-center mb-4 text-dark" style="font-size: 0.85rem; line-height: 1.4;">
                                    <?= esc($partnerName !== '' ? $partnerName : '—'); ?>
                                </span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

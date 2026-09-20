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
$history = $section;
$historyLabelSize = max(2, min(12, ((int) ($history['label_size'] ?? 96)) / 16));
$historyTitleSize = max(2, min(6, ((int) ($history['title_size'] ?? 56)) / 16));
?>
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="pt-0 mt-0 text-white position-relative overflow-hidden" id="section-<?= esc($section['section_key'] ?? 'section'); ?>" style="background: linear-gradient(135deg, #111111 0%, #2b0000 100%);">
        <div class="position-absolute" style="top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(204,0,0,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
        <div class="position-absolute" style="bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

        <div class="row g-0 align-items-stretch position-relative" style="z-index: 1;">
            <div class="col-lg-5 position-relative d-flex align-items-center justify-content-center justify-content-lg-end px-4 pe-lg-4 py-5" style="min-height: 450px;">
                <?php $imgSejarah = $sectionImg($section['section_key'] ?? ''); ?>
                <?php if ($imgSejarah): ?>
                    <img src="<?= esc($imgSejarah); ?>" class="position-absolute w-100 h-100"
                        style="top: 0; left: 0; object-fit: cover; object-position: center; z-index: -2;"
                        alt="Sejarah BAMUSI">
                <?php endif; ?>
                <div class="position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(to right, rgba(17,17,17,0.3) 0%, rgba(17,17,17,1) 98%); z-index: -1;"></div>
                <div class="position-absolute w-100 h-100 d-lg-none" style="bottom: 0; left: 0; background: linear-gradient(to bottom, rgba(17,17,17,0) 60%, rgba(17,17,17,1) 100%); z-index: -1;"></div>

                <h1 class="m-0 lh-1 position-relative" style="font-size: clamp(<?= $historyLabelSize; ?>rem, 8vw, 8rem); font-weight: 900; letter-spacing: -3px; color: #e60000; text-shadow: 3px 3px 0px #660000, 6px 6px 0px #330000, 12px 12px 25px rgba(0,0,0,0.9), -2px -2px 15px rgba(204,0,0,0.3); transform: translateY(-5px);">
                    <?= esc($t($section ?? [], 'label') ?: ($t($section ?? [], 'kicker') ?: ($locale === 'en' ? 'HISTORY' : 'SEJARAH'))); ?>
                </h1>
            </div>

            <div class="col-lg-7 px-4 ps-lg-5 pe-lg-5 py-5 d-flex flex-column justify-content-center">
                <div class="pe-xl-5 py-lg-4" style="max-width: 800px;">
                    <h2 class="fw-bolder mb-4 text-uppercase text-white" style="font-size: clamp(2.2rem, 4.2vw, <?= $historyTitleSize; ?>rem); letter-spacing: -1.5px; line-height: 1;">
                        <?= esc($t($section ?? [], 'title')); ?>
                    </h2>
                    <p class="fs-5 mb-5 text-white opacity-75 lh-base">
                        <?= esc($t($section ?? [], 'subtitle')); ?>
                    </p>
                    <div>
                        <a class="fw-bold text-white text-decoration-none border-bottom border-2 border-white pb-1 fs-6" href="<?= esc($section['button_url'] ?? '#'); ?>" style="transition: all 0.3s ease;" onmouseover="this.style.opacity='0.7'; this.style.borderColor='rgba(255,255,255,0.5)';" onmouseout="this.style.opacity='1'; this.style.borderColor='white';">
                            <?= esc($t($section ?? [], 'button_label')); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

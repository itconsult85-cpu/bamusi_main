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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="pb-0 bg-white" id="section-<?= esc($section['section_key'] ?? 'section'); ?>">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-6 position-relative">
                <?php $imgVisi = $sectionImg($section['section_key'] ?? ''); ?>
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
                    <div class="d-flex align-items-center justify-content-center text-white shadow-sm"
                        style="width: 55px; height: 55px; background-color: var(--bamusi-red, #c8102e);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 ps-lg-5 pe-lg-5 px-4 d-flex flex-column justify-content-center py-5">
                <div class="pe-xl-5">
                    <span class="eyebrow-text text-danger text-uppercase fw-bold"
                        style="letter-spacing: 1.8px; font-size: 1.35rem;">
                        <?= esc($visiKicker); ?>
                    </span>

                    <h2 class="fw-bolder mb-5 mt-2"
                        style="font-size: clamp(3rem, 5vw, 5rem); letter-spacing: -1.8px; line-height: 1.05; color: var(--bamusi-dark, #212529);">
                        <?= esc($visiTitle); ?>
                    </h2>

                    <?php
                    $visiText = $t($visi, 'vision');
                    $missionText = $t($visi, 'mission');
                    ?>
                    <div class="mt-4">
                        <p class="mb-4 text-dark" style="font-size: clamp(1.35rem, 2vw, 1.8rem); line-height: 1.6; font-weight: 600;">
                            <?= esc(trim($missionText)); ?>
                        </p>
                        <p class="mb-0 text-dark" style="font-size: clamp(1.35rem, 2vw, 1.8rem); line-height: 1.6; font-weight: 600;">
                            <?= esc(trim($visiText)); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

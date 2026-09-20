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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-0 position-relative" id="section-<?= esc($section['section_key'] ?? 'section'); ?>" style="background-color: #0a0a0a;">
        <?php $imgKhidmah = $sectionImg($section['section_key'] ?? ''); ?>
        <div class="position-relative text-white py-5 curved-banner overflow-hidden"
            style="<?= $imgKhidmah
                        ? "background: url('" . esc($imgKhidmah) . "') no-repeat right bottom / cover;"
                        : 'background-color: #3a0202;'; ?> min-height: 550px; display: flex; align-items: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, #3a0202 0%, #3a0202 40%, rgba(58,2,2,0.85) 60%, rgba(0,0,0,0.3) 100%);"></div>

            <div class="container-fluid px-4 px-lg-5 py-5 position-relative w-100" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-lg-7 py-4">
                        <span class="eyebrow-text text-uppercase fw-bold" style="color: #ff9999; letter-spacing: 2px; font-size: 0.85rem;">
                            <?= esc($t($section ?? [], 'kicker')); ?>
                        </span>
                        <h2 class="fw-bolder mt-3 mb-4 text-white" style="font-size: clamp(2.5rem, 4.5vw, 4.5rem); letter-spacing:-1.5px; line-height: 1.1;">
                            <?= $t_br($section ?? [], 'title'); ?>
                        </h2>
                        <p class="fs-5 mb-4 text-white opacity-85 lh-base pe-lg-5">
                            <?= esc($t($section ?? [], 'subtitle')); ?>
                        </p>

                        <a href="<?= esc($section['button_url'] ?? '#'); ?>" class="btn bg-white text-dark rounded-pill fw-bold px-4 py-3 mt-2 text-uppercase shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                            <?= esc($t($section ?? [], 'button_label')); ?>
                            <span class="fs-6">↘</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 px-lg-5 py-5" id="section-<?= esc($section['section_key'] ?? 'section'); ?>-items" style="background: radial-gradient(ellipse at 50% 0%, #3a0000 0%, #0a0a0a 60%, #000000 100%);">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 g-4 py-4">
                <?php if (!empty($items)): ?>
                    <?php foreach (array_slice($items, 0, 5) as $i => $program): ?>
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

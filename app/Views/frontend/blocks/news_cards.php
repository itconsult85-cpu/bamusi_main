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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 bg-white position-relative overflow-hidden" id="section-<?= esc($section['section_key'] ?? 'section'); ?>">
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
                        <?= esc($t($section ?? [], 'kicker')); ?>
                    </span>
                    <h2 class="fw-bolder mt-3 mb-0" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1px; line-height: 1.1; color: var(--bamusi-dark, #212529);">
                        <?= $t_br($section ?? [], 'title'); ?>
                    </h2>
                </div>
                <a href="<?= esc($section['button_url'] ?? base_url('berita')); ?>" class="d-none d-md-block fw-bold text-dark text-decoration-none border-bottom border-2 border-dark pb-1 text-uppercase fs-6 transition-all" style="transition: opacity 0.3s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                    <?= esc($t($section ?? [], 'button_label')); ?> ↗
                </a>
            </div>

            <div class="row g-4 pt-4 mt-2" style="border-top: 2px solid var(--bamusi-dark, #212529);">
                <?php if (!empty($items)): ?>
                    <?php foreach (array_slice($items, 0, 4) as $i => $item): ?>
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

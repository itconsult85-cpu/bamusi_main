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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 text-white position-relative overflow-hidden" id="section-<?= esc($section['section_key'] ?? 'section'); ?>" style="background: linear-gradient(135deg, #8a0000 0%, #4a0000 50%, #200000 100%);">
        <div class="position-absolute" style="top: -30%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

        <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
            <div class="row align-items-lg-center">
                <div class="col-lg-5 pe-lg-5 mb-5 mb-lg-0">
                    <span class="text-uppercase fw-bold opacity-75" style="letter-spacing: 2px; font-size: 0.85rem;">
                        <?= esc($t($section ?? [], 'kicker')); ?>
                    </span>
                    <h2 class="fw-bolder my-3" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1.5px; line-height: 1.1;">
                        <?= $t_br($section ?? [], 'title'); ?>
                    </h2>
                    <p class="opacity-75 fs-5 mb-0">
                        <?= esc($t($section ?? [], 'subtitle')); ?>
                    </p>
                </div>

                <div class="col-lg-7">
                    <div class="pt-2" style="border-top: 2px solid rgba(255,255,255,0.2);">
                        <?php if (!empty($items)): ?>
                            <?php foreach (array_slice($items, 0, 4) as $i => $item):
                                $itemsSlug = $item['slug'] ?? $item['id'] ?? '#';
                                $itemsUrl = !empty($item['url']) ? $item['url'] : base_url('agenda/' . $itemsSlug);
                                if (!preg_match('#^https?://#i', $itemsUrl) && $itemsUrl !== '#' && $itemsUrl[0] !== '#') $itemsUrl = base_url(ltrim($itemsUrl, '/'));
                            ?>
                                <a href="<?= $itemsUrl; ?>" class="text-decoration-none text-white d-block group-agenda">
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

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
$nilai = $section;
$nilaiButtonPositionValue = (string) ($nilai['button_position'] ?? 'center');
$nilaiButtonPosition = in_array($nilaiButtonPositionValue, ['left', 'center', 'right'], true) ? $nilaiButtonPositionValue : 'center';
$nilaiButtonLocation = ($nilai['button_location'] ?? 'bottom') === 'top' ? 'top' : 'bottom';
$nilaiCardsVisible = !array_key_exists('cards_visible', $nilai) || !empty($nilai['cards_visible']);
$nilaiCardsLimit = max(1, min(12, (int) ($nilai['cards_limit'] ?? 5)));
$nilaiCardsColumns = max(2, min(6, (int) ($nilai['cards_columns'] ?? 5)));
$nilaiColumnClass = [2 => 'col-lg-6', 3 => 'col-lg-4', 4 => 'col-lg-3', 5 => 'col-lg', 6 => 'col-lg-2'][$nilaiCardsColumns] ?? 'col-lg';
$nilaiButtonClass = ['left' => 'text-start', 'center' => 'text-center', 'right' => 'text-end'][$nilaiButtonPosition];
?>
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 text-white position-relative overflow-hidden" id="section-<?= esc($section['section_key'] ?? 'section'); ?>"
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

            <?php if ($nilaiButtonLocation === 'top'): ?>
                <div class="<?= $nilaiButtonClass; ?> mt-4 pt-2">
                    <a href="<?= esc($nilai['button_url'] ?? '#'); ?>" class="d-inline-flex align-items-center gap-2 fw-bold text-white text-decoration-none border-bottom border-2 border-white pb-2" style="letter-spacing: .3px;">
                        <?= esc($t($nilai, 'button_label')); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- KARTU 5 NILAI (dari tabel about_values) -->
            <?php if ($nilaiCardsVisible): ?>
                <div class="row g-3 g-lg-4 pt-4 mt-2 border-top"
                    style="border-color: rgba(255,255,255,0.1) !important;">
                    <?php if (!empty($items)): ?>
                        <?php foreach (array_slice($items, 0, $nilaiCardsLimit) as $i => $value): ?>
                            <div class="col-6 col-md-4 <?= $nilaiColumnClass; ?>">
                                <a href="<?= esc($nilai['button_url'] ?? '#'); ?>"
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
            <?php endif; ?>

            <?php if ($nilaiButtonLocation === 'bottom'): ?>
                <div class="<?= $nilaiButtonClass; ?> mt-5 pt-2">
                    <a href="<?= esc($nilai['button_url'] ?? '#'); ?>"
                        class="d-inline-flex align-items-center gap-2 fw-bold text-white text-decoration-none border-bottom border-2 border-white pb-2"
                        style="letter-spacing: .3px;">
                        <?= esc($t($nilai, 'button_label')); ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </section>

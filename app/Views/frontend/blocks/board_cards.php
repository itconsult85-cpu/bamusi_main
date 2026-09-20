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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 bg-white" id="section-<?= esc($section['section_key'] ?? 'section'); ?>">
        <style>
            .pengurus-scroll-wrapper::-webkit-scrollbar {
                display: none;
            }
        </style>
        <div class="container-fluid px-4 px-lg-5 py-5">
            <div class="row align-items-center">
                <div class="col-lg-4 mb-5 mb-lg-0 pe-lg-5">
                    <h2 class="fw-bold text-dark mb-5" style="font-size: clamp(2rem, 3vw, 2.5rem); line-height: 1.3;">
                        <?= $t_br($section ?? [], 'title'); ?>
                    </h2>
                    <?php
                    $itemsUrl = trim((string)($section['button_url'] ?? ''));
                    if ($itemsUrl !== '' && !preg_match('#^https?://#i', $itemsUrl) && $itemsUrl[0] !== '#') {
                        $itemsUrl = base_url(ltrim($itemsUrl, '/'));
                    }
                    $itemsButton = trim((string)($t($section ?? [], 'button_label')));
                    ?>
                    <a href="<?= esc($itemsUrl); ?>" class="btn rounded-0 text-white fw-bold px-4 py-3 text-uppercase" style="background-color: #cc0000; letter-spacing: 1px; font-size: 0.9rem;">
                        <?= esc($itemsButton); ?>
                    </a>
                </div>

                <div class="col-lg-8">
                    <div class="board-slider-container pengurus-scroll-wrapper d-flex flex-nowrap gap-4 pb-3" style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $member):
                                $photo = trim((string)($member['photo_url'] ?? ''));
                                if ($photo !== '' && !preg_match('#^https?://#i', $photo)) {
                                    $photo = base_url(ltrim($photo, '/'));
                                }
                            ?>
                                <div class="card border rounded-3 flex-shrink-0" style="width: 260px;">
                                    <div class="card-body p-4 text-center d-flex flex-column align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden mb-4" style="width: 140px; height: 140px; background-color: #cc0000;">
                                            <?php if ($photo !== ''): ?>
                                                <img src="<?= esc($photo); ?>" alt="<?= esc($member['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <span class="text-white fw-bolder" style="font-size: 3rem;"><?= esc(strtoupper(substr($member['name'], 0, 1))); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <h5 class="fw-bold text-uppercase mb-2" style="color: #cc0000; font-size: 1rem; line-height: 1.4;">
                                            <?= esc($member['name']); ?>
                                        </h5>
                                        <small class="text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <?= esc($t($member, 'role')); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

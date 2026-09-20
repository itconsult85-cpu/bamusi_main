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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 bg-light position-relative" id="section-<?= esc($section['section_key'] ?? 'section'); ?>" style="overflow: hidden;">
        <div class="position-absolute" style="bottom: -20px; right: 8%; width: 180px; height: 220px; border: 30px solid rgba(204,0,0,0.06); border-bottom: 0; border-top-left-radius: 100px; border-top-right-radius: 100px; z-index: 0; pointer-events: none;"></div>

        <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
            <div class="row mb-5 align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 pe-lg-5">
                    <span class="text-uppercase fw-bold d-block mb-3" style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.8rem;">
                        <?= esc($t($section ?? [], 'kicker')); ?>
                    </span>
                    <h2 class="fw-bolder text-dark" style="font-size: clamp(2.5rem, 4vw, 3.5rem); letter-spacing: -1.5px; line-height: 1.1;">
                        <?= $t_br($section ?? [], 'title'); ?>
                    </h2>
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <p class="fs-6 text-dark lh-base mb-4 pe-lg-4" style="font-weight: 400;">
                        <?= esc($t($section ?? [], 'subtitle')); ?>
                    </p>
                    <a href="<?= esc($section['button_url'] ?? '#bergabung'); ?>" class="btn rounded-pill fw-bold text-uppercase d-inline-flex align-items-center gap-2"
                        style="border: 1px solid var(--bamusi-red, #cc0000); color: var(--bamusi-red, #cc0000); font-size: 0.85rem; padding: 0.6rem 1.5rem; transition: all 0.3s;"
                        onmouseover="this.style.backgroundColor='var(--bamusi-red, #cc0000)'; this.style.color='#ffffff';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--bamusi-red, #cc0000)';">
                        <?= esc($t($section ?? [], 'button_label')); ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </a>
                </div>
            </div>

            <?php
            $topics = [
                ($locale === 'en' ? 'Archipelago Islam' : 'Islam Nusantara'),
                ($locale === 'en' ? 'Democracy & Nationality' : 'Demokrasi & kebangsaan'),
                ($locale === 'en' ? 'Education & Boarding' : 'Pesantren & pendidikan'),
                ($locale === 'en' ? 'Women, Family & Youth' : 'Perempuan, keluarga & generasi muda')
            ];
            $items = array_slice($items ?? [], 0, 4);
            ?>
            <div class="row g-0 pt-4" style="border-top: 1px solid #212529;">
                <?php foreach ($items as $index => $article): ?>
                    <?php
                    $articleImage = trim((string)($article['image_url'] ?? ''));
                    if ($articleImage && !preg_match('#^https?://#i', $articleImage)) {
                        $articleImage = base_url(ltrim($articleImage, '/'));
                    }
                    $articleCategory = trim((string)($article['category'] ?? '')) ?: ($topics[$index] ?? 'Kolom Tulisan');
                    $articleTitle = ($locale === 'en' && !empty($article['title_en'])) ? $article['title_en'] : $article['title'];
                    ?>
                    <div class="col-lg-3 col-6 <?= $index < 3 ? 'border-end' : ''; ?>" style="border-color: #e0e0e0 !important;">
                        <?php $articleUrl = $article['url'] ?? '';
                        if ($articleUrl !== '' && !preg_match('#^https?://#i', $articleUrl) && $articleUrl[0] !== '#') $articleUrl = base_url(ltrim($articleUrl, '/')); ?>
                        <a href="<?= esc($articleUrl ?: base_url('artikel/' . $article['id'])); ?>" class="d-block h-100 p-4 text-decoration-none" style="transition: background-color 0.2s ease;"
                            onmouseover="this.style.backgroundColor='#fdf0f0';"
                            onmouseout="this.style.backgroundColor='transparent';">
                            <span class="fw-bold d-block mb-4" style="color: var(--bamusi-red, #cc0000); font-size: 0.9rem;">
                                <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?>
                            </span>
                            <span class="text-uppercase d-block mb-2" style="color: #8b0000; letter-spacing: 1px; font-size: 0.68rem; font-weight: 800;">
                                <?= esc($articleCategory); ?>
                            </span>
                            <h3 class="fs-6 fw-bold text-dark lh-base pe-lg-3 mb-0">
                                <?= esc($articleTitle); ?>
                            </h3>
                        </a>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <div class="col-12 py-4 text-secondary">Belum ada artikel yang diterbitkan.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

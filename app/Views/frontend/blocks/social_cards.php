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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" class="py-5 position-relative overflow-hidden" id="section-<?= esc($section['section_key'] ?? 'section'); ?>" style="background: linear-gradient(135deg, #111111 0%, #2b0000 100%);">
        <div class="position-absolute" style="top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(204,0,0,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
        <div class="position-absolute" style="bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

        <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
            <div class="border-bottom pb-3 mb-5" style="border-color: rgba(255,255,255,0.2) !important;">
                <span class="text-uppercase fw-bold text-white opacity-75" style="letter-spacing: 2px; font-size: 0.75rem;">
                    <?= esc($t($section ?? [], 'kicker')); ?>
                </span>
            </div>

            <div class="row align-items-end mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bolder text-white mb-0" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing: -1.5px; line-height: 1.1;">
                        <?= $t_br($section ?? [], 'title'); ?>
                    </h2>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <p class="text-white opacity-75 mb-0 fs-6 lh-base pe-lg-5">
                        <?= esc($t($section ?? [], 'subtitle')); ?>
                    </p>
                </div>
            </div>

            <?php
            $socialItems = [];
            foreach (($items) as $socialItem) {
                $socialItems[$socialItem['item_key']] = $socialItem;
            }
            $instagramItem = $socialItems['instagram'] ?? [];
            $tiktokItem = $socialItems['tiktok'] ?? [];
            $youtubeItem = $socialItems['youtube'] ?? [];
            ?>
            <div class="row g-4 mt-2">
                <div class="col-lg-4 col-md-6">
                    <a href="<?= esc($instagramItem['url'] ?? '#'); ?>" target="_blank" class="text-decoration-none d-block h-100 position-relative overflow-hidden"
                        style="background-color: var(--bamusi-red, #cc0000); transition: transform 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);"
                        onmouseover="this.style.transform='translateY(-10px)';" onmouseout="this.style.transform='translateY(0)';">
                        <div class="position-absolute" style="bottom: -20px; right: -20px; width: 150px; height: 150px; border-radius: 50%; background: rgba(0,0,0,0.1);"></div>
                        <div class="homepage-social-card-body p-4 p-lg-5 d-flex flex-column h-100 position-relative z-1">
                            <span class="text-white fw-bold text-uppercase mb-4" style="letter-spacing: 2px; font-size: 0.8rem;"><?= esc($t($instagramItem, 'label')); ?></span>
                            <h3 class="homepage-social-handle text-white fw-bolder mb-4" style="font-size: clamp(1.35rem, 2vw, 2.2rem);">
                                <?= esc($t($instagramItem, 'title')); ?>
                            </h3>
                            <div class="mt-auto d-flex align-items-center gap-3">
                                <div class="rounded-circle border border-2 border-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                </div>
                                <span class="text-white fw-bold fs-6"><?= $locale === 'en' ? 'Open profile ↘' : 'Buka profil ↘'; ?></span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6">
                    <a href="<?= esc($tiktokItem['url'] ?? '#'); ?>" target="_blank" class="text-decoration-none d-block h-100 position-relative overflow-hidden"
                        style="background-color: #ffffff; transition: transform 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);"
                        onmouseover="this.style.transform='translateY(-10px)';" onmouseout="this.style.transform='translateY(0)';">
                        <div class="position-absolute" style="bottom: 0; right: 0; width: 200px; height: 200px; border-top-left-radius: 200px; background: rgba(0,0,0,0.03);"></div>
                        <div class="homepage-social-card-body p-4 p-lg-5 d-flex flex-column h-100 position-relative z-1">
                            <span class="text-secondary fw-bold text-uppercase mb-4" style="letter-spacing: 2px; font-size: 0.8rem;"><?= esc($t($tiktokItem, 'label')); ?></span>
                            <h3 class="homepage-social-handle text-dark fw-bolder mb-4" style="font-size: clamp(1.35rem, 2vw, 2.2rem);">
                                <?= esc($t($tiktokItem, 'title')); ?>
                            </h3>
                            <div class="mt-auto d-flex align-items-center gap-3">
                                <div class="rounded-circle border border-2 border-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#212529" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                                    </svg>
                                </div>
                                <span class="text-dark fw-bold fs-6"><?= $locale === 'en' ? 'Open profile ↘' : 'Buka profil ↘'; ?></span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-12">
                    <a href="<?= esc($youtubeItem['url'] ?? '#'); ?>" target="_blank" class="text-decoration-none d-block h-100 position-relative overflow-hidden"
                        style="background-color: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);"
                        onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='var(--bamusi-red, #cc0000)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.1)';">
                        <div class="position-absolute" style="top: -20px; right: -20px; width: 120px; height: 120px; border: 20px solid rgba(255,255,255,0.02); border-radius: 20px; transform: rotate(15deg);"></div>
                        <div class="homepage-social-card-body p-4 p-lg-5 d-flex flex-column h-100 position-relative z-1">
                            <span class="text-white opacity-75 fw-bold text-uppercase mb-4" style="letter-spacing: 2px; font-size: 0.8rem;"><?= esc($t($youtubeItem, 'label')); ?></span>
                            <h3 class="homepage-social-handle text-white fw-bolder mb-4" style="font-size: clamp(1.35rem, 2vw, 2.2rem);">
                                <?= esc($t($youtubeItem, 'title')); ?>
                            </h3>
                            <div class="mt-auto d-flex align-items-center gap-3">
                                <div class="rounded-circle border border-2 border-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; opacity: 0.8;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path>
                                        <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                                    </svg>
                                </div>
                                <span class="text-white opacity-75 fw-bold fs-6"><?= $locale === 'en' ? 'Open profile ↘' : 'Buka profil ↘'; ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

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
    <section data-section-key="<?= esc((string) ($section['section_key'] ?? '')); ?>" id="section-<?= esc($section['section_key'] ?? 'section'); ?>">
        <div class="container-fluid p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-5 p-4 p-lg-5 d-flex flex-column justify-content-between" style="background-color: var(--bamusi-red, #cc0000);">
                    <div class="mb-5 pb-4 text-white" style="margin-top: 2rem;">
                        <span class="text-uppercase fw-bold d-block mb-3 opacity-75" style="letter-spacing: 2px; font-size: 0.85rem;">
                            <?= esc($t($section ?? [], 'kicker')); ?>
                        </span>
                        <h2 class="fw-bolder mb-4" style="font-size: clamp(3rem, 5vw, 4.5rem); letter-spacing: -1.5px; line-height: 1.05;">
                            <?= $t_br($section ?? [], 'title'); ?>
                        </h2>
                        <p class="fs-6 opacity-85 lh-base pe-lg-4" style="font-weight: 300;">
                            <?= esc($t($section ?? [], 'subtitle')); ?>
                        </p>
                    </div>

                    <div class="p-4 p-lg-5 mt-auto shadow-sm" style="background-color: #8b0000; border-left: 6px solid #ffffff;">
                        <div class="d-flex align-items-center gap-3 mb-4 text-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span class="text-uppercase fw-bold" style="letter-spacing: 2px; font-size: 0.85rem;">
                                <?= esc($t($section, 'kicker')); ?>
                            </span>
                        </div>
                        <h2 class="fw-bolder text-white mb-3" style="font-size: clamp(2rem, 3vw, 2.5rem); letter-spacing: -1px; line-height: 1.1;">
                            <?= $t_br($section, 'title'); ?>
                        </h2>
                        <p class="text-white mb-0 opacity-85" style="font-size: 0.95rem; line-height: 1.6; font-weight: 300;">
                            <?= esc($t($section, 'subtitle')); ?>
                        </p>
                    </div>
                </div>

                <div class="col-lg-7 d-flex flex-column justify-content-center p-4 p-lg-5 bg-white">
                    <div class="w-100 px-lg-4" style="max-width: 800px; margin: 0 auto;">
                        <form>
                            <div class="row py-3 mb-4" style="row-gap: 3rem; column-gap: 1rem;">
                                <div class="col-md-5">
                                    <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Full Name' : 'Nama Lengkap'; ?></label>
                                    <input type="text" name="name" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                                </div>
                                <div class="col-md-5 offset-md-1">
                                    <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Domicile' : 'Domisili'; ?></label>
                                    <input type="text" name="domicile" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Email Address' : 'Alamat Email'; ?></label>
                                    <input type="email" name="email" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                                </div>
                                <div class="col-md-5 offset-md-1">
                                    <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'WhatsApp Number' : 'Nomor WhatsApp'; ?></label>
                                    <input type="tel" name="phone" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'" required>
                                </div>
                                <div class="col-12">
                                    <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Interest' : 'Minat Kontribusi'; ?></label>
                                    <select name="interest" class="form-select shadow-none px-0 rounded-0 fw-bold" style="border: none; border-bottom: 1px solid #111111; background: transparent; font-size: 1rem; color: #111111; cursor: pointer;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'">
                                        <option value="" disabled selected><?= $locale === 'en' ? 'Choose interest' : 'Pilih minat'; ?></option>
                                        <?php foreach (($items) as $interest): ?>
                                            <?php $interestText = $t($interest, 'label') ?: $t($interest, 'title'); ?>
                                            <option value="<?= esc($interestText); ?>"><?= esc($interestText); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="d-block text-uppercase fw-bold mb-2" style="color: var(--bamusi-red, #cc0000); letter-spacing: 1px; font-size: 0.8rem;"><?= $locale === 'en' ? 'Short Message' : 'Pesan Singkat'; ?></label>
                                    <textarea name="message" rows="3" class="form-control shadow-none px-0 rounded-0" style="border: none; border-bottom: 1px solid #111111; background: transparent; resize: none; font-size: 1.1rem; color: #111111;" onfocus="this.style.borderBottomColor='#cc0000'" onblur="this.style.borderBottomColor='#111111'"></textarea>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn rounded-pill d-inline-flex align-items-center gap-3 mb-3" style="background: transparent; border: 1px solid var(--bamusi-red, #cc0000); color: var(--bamusi-red, #cc0000); font-weight: 700; font-size: 0.85rem; padding: 0.75rem 2rem; letter-spacing: 0.5px; transition: 0.3s;" onmouseover="this.style.backgroundColor='var(--bamusi-red, #cc0000)'; this.style.color='#ffffff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--bamusi-red, #cc0000)';">
                                    <?= $locale === 'en' ? 'SEND VIA WHATSAPP' : 'KIRIM MELALUI WHATSAPP'; ?>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                    </svg>
                                </button>
                                <div class="d-flex align-items-center gap-2" style="color: #666666; font-size: 0.85rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?= $locale === 'en' ? 'Data is not stored on this site.' : 'Data tidak disimpan di situs ini.'; ?></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

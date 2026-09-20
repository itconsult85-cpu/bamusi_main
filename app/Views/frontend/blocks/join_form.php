<?php
$section = $section ?? [];
$internshipSection = $internshipSection ?? null;
$items = $items ?? [];
$locale = $locale ?? 'id';
$settings = $settings ?? [];
$t = static function (array $row, string $field) use ($locale): string {
    return (string) (($locale === 'en' && !empty($row[$field . '_en'])) ? $row[$field . '_en'] : ($row[$field] ?? ''));
};
$copy = static function (array $row, string $fallbackTitle, string $fallbackBody) use ($t): array {
    return ['title' => $t($row, 'title') ?: $fallbackTitle, 'body' => $t($row, 'subtitle') ?: $t($row, 'content') ?: $fallbackBody];
};
$joinCopy = $copy($section, $locale === 'en' ? 'Join BAMUSI' : 'Bergabung dengan BAMUSI', $locale === 'en' ? 'Take part in meaningful work for society.' : 'Ambil bagian dalam kerja khidmah untuk sesama.');
$internshipCopy = $internshipSection ? $copy($internshipSection, $locale === 'en' ? 'BAMUSI Internship' : 'Magang di BAMUSI', $locale === 'en' ? 'Learn, contribute, and grow with BAMUSI.' : 'Belajar, berkontribusi, dan berkembang bersama BAMUSI.') : $joinCopy;
$whatsappUrl = trim((string) ($settings['whatsapp_url'] ?? '')) ?: '#';
$interestItems = array_values(array_filter($items, static fn (array $item): bool => !array_key_exists('published', $item) || !empty($item['published'])));
?>
<section class="homepage-join-section" data-whatsapp-url="<?= esc($whatsappUrl, 'attr'); ?>" data-section-key="<?= esc((string) ($section['section_key'] ?? ''), 'attr'); ?>">
    <div class="container-fluid p-0">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-5 p-4 p-lg-5 d-flex flex-column" style="background-color:var(--bamusi-red,#cc0000);">
                <div class="mb-5 pb-4 text-white" style="margin-top:2rem;">
                    <span class="text-uppercase fw-bold d-block mb-3 opacity-75" style="letter-spacing:2px;font-size:.85rem;"><?= esc($t($section, 'kicker') ?: ($locale === 'en' ? 'CONTRIBUTE' : 'BERKONTRIBUSI')); ?></span>
                    <h2 class="fw-bolder mb-4" id="join-form-title" data-join-copy="<?= esc(json_encode($joinCopy, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'attr'); ?>" data-internship-copy="<?= esc(json_encode($internshipCopy, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'attr'); ?>" style="font-size:clamp(2.5rem,5vw,4.5rem);letter-spacing:-1.5px;line-height:1.05;"><?= esc($joinCopy['title']); ?></h2>
                    <p class="fs-6 opacity-85 lh-base pe-lg-4" id="join-form-body" style="font-weight:300;"><?= esc($joinCopy['body']); ?></p>
                </div>
                <div class="p-4 p-lg-5 mt-auto shadow-sm text-white" style="background-color:#8b0000;border-left:6px solid #fff;"><span class="text-uppercase fw-bold" style="letter-spacing:2px;font-size:.85rem;" id="join-form-context"><?= esc($locale === 'en' ? 'Your contribution matters' : 'Kontribusi Anda berarti'); ?></span><p class="mb-0 mt-3 opacity-85 small"><?= esc($locale === 'en' ? 'Choose an interest and the information on this side will adjust automatically.' : 'Pilih minat kontribusi dan informasi di sisi ini akan berubah otomatis.'); ?></p></div>
            </div>
            <div class="col-lg-7 d-flex flex-column justify-content-center p-4 p-lg-5 bg-white">
                <form class="join-interest-form w-100 px-lg-4" novalidate>
                    <div class="row py-3 mb-4" style="row-gap:2rem;column-gap:1rem;">
                        <div class="col-md-5"><label class="d-block text-uppercase fw-bold mb-2 text-danger small" for="join-name"><?= esc($locale === 'en' ? 'Full Name' : 'Nama Lengkap'); ?></label><input id="join-name" type="text" name="name" class="form-control shadow-none px-0 rounded-0" required></div>
                        <div class="col-md-5 offset-md-1"><label class="d-block text-uppercase fw-bold mb-2 text-danger small" for="join-domicile"><?= esc($locale === 'en' ? 'Domicile' : 'Domisili'); ?></label><input id="join-domicile" type="text" name="domicile" class="form-control shadow-none px-0 rounded-0" required></div>
                        <div class="col-md-5"><label class="d-block text-uppercase fw-bold mb-2 text-danger small" for="join-email">Email</label><input id="join-email" type="email" name="email" class="form-control shadow-none px-0 rounded-0" required></div>
                        <div class="col-md-5 offset-md-1"><label class="d-block text-uppercase fw-bold mb-2 text-danger small" for="join-phone"><?= esc($locale === 'en' ? 'WhatsApp Number' : 'Nomor WhatsApp'); ?></label><input id="join-phone" type="tel" name="phone" class="form-control shadow-none px-0 rounded-0" required></div>
                        <div class="col-12"><label class="d-block text-uppercase fw-bold mb-2 text-danger small" for="join-interest"><?= esc($locale === 'en' ? 'Contribution Interest' : 'Minat Kontribusi'); ?></label><div class="join-select-shell"><select id="join-interest" name="interest" class="join-interest-select" required><option value="" selected disabled><?= esc($locale === 'en' ? 'Choose interest' : 'Pilih minat'); ?></option><?php foreach ($interestItems as $interest): $value = (string) ($interest['item_key'] ?? $interest['title'] ?? $interest['label'] ?? ''); $label = $t($interest, 'label') ?: $t($interest, 'title'); ?><option value="<?= esc($value, 'attr'); ?>" data-label="<?= esc($label, 'attr'); ?>"><?= esc($label); ?></option><?php endforeach; ?><option value="magang" data-label="<?= esc($locale === 'en' ? 'Internship' : 'Magang', 'attr'); ?>"><?= esc($locale === 'en' ? 'Internship' : 'Magang'); ?></option></select></div></div>
                        <div class="col-12"><label class="d-block text-uppercase fw-bold mb-2 text-danger small" for="join-message"><?= esc($locale === 'en' ? 'Short Message' : 'Pesan Singkat'); ?></label><textarea id="join-message" name="message" rows="3" class="form-control shadow-none px-0 rounded-0" required></textarea></div>
                    </div>
                    <button type="submit" class="btn rounded-pill d-inline-flex align-items-center gap-3 mb-3 text-danger border-danger fw-bold px-4 py-3"><?= esc($locale === 'en' ? 'SEND VIA WHATSAPP' : 'KIRIM MELALUI WHATSAPP'); ?> ↗</button>
                    <small class="join-form-status d-block text-secondary" role="status"><?= esc($locale === 'en' ? 'Your data is not stored on this site.' : 'Data tidak disimpan di situs ini.'); ?></small>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.homepage-join-section').forEach(function(section){const form=section.querySelector('.join-interest-form'),interest=section.querySelector('#join-interest'),title=section.querySelector('#join-form-title'),body=section.querySelector('#join-form-body'),status=section.querySelector('.join-form-status'),norm=v=>String(v||'').toLowerCase().replace(/[^a-z0-9]+/g,'');function update(){const o=interest.options[interest.selectedIndex],isInternship=norm(o?.value).includes('magang')||norm(o?.value).includes('internship')||norm(o?.text).includes('magang')||norm(o?.text).includes('internship'),copy=JSON.parse(title.dataset[isInternship?'internshipCopy':'joinCopy']||'{}');if(copy.title)title.textContent=copy.title;if(copy.body)body.textContent=copy.body;}interest.addEventListener('change',update);form.addEventListener('submit',function(e){e.preventDefault();if(!form.checkValidity()){form.classList.add('was-validated');return;}const d=new FormData(form),o=interest.options[interest.selectedIndex],message=['Halo Admin BAMUSI,','','Saya ingin berkontribusi melalui website BAMUSI.','','Nama: '+d.get('name'),'Domisili: '+d.get('domicile'),'Email: '+d.get('email'),'WhatsApp: '+d.get('phone'),'Minat kontribusi: '+(o?.dataset.label||o?.text||d.get('interest')),'','Pesan:',d.get('message')].join('\n'),target=section.dataset.whatsappUrl;if(!target||target==='#'){status.textContent='Nomor WhatsApp belum dikonfigurasi oleh admin.';return;}window.open(target+(target.includes('?')?'&':'?')+'text='+encodeURIComponent(message),'_blank','noopener');});});});
</script>
<style>.homepage-join-section .form-control:focus{border-color:#cc0000;box-shadow:0 .25rem .25rem rgba(204,0,0,.12)}.homepage-join-section .form-control{border:none;border-bottom:1px solid #111;background:transparent}.homepage-join-section .form-control:focus{border-bottom-color:#cc0000}.join-select-shell{position:relative}.join-select-shell::after{content:'↓';position:absolute;right:1.2rem;top:50%;transform:translateY(-50%);color:#cc0000;font-size:1.1rem;font-weight:800;pointer-events:none;transition:transform .25s ease}.join-select-shell:focus-within::after{transform:translateY(-50%) rotate(180deg)}.join-interest-select{width:100%;appearance:none;-webkit-appearance:none;border:1px solid #e6dede;border-radius:1rem;padding:.95rem 3rem .95rem 1.1rem;background:linear-gradient(135deg,#fff,#fffafa);color:#3d1111;font-weight:700;letter-spacing:.1px;box-shadow:0 6px 18px rgba(91,0,0,.06);transition:border-color .25s ease,box-shadow .25s ease,transform .25s ease}.join-interest-select:hover{border-color:#cc0000;box-shadow:0 10px 24px rgba(91,0,0,.11);transform:translateY(-1px)}.join-interest-select:focus{outline:none;border-color:#cc0000;box-shadow:0 0 0 .25rem rgba(204,0,0,.13),0 10px 24px rgba(91,0,0,.1)}.join-interest-select option{font-weight:600;color:#3d1111;background:#fff}</style>

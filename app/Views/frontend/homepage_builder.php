<?php
$locale = $locale ?? 'id';
$sections = $sections ?? [];
$homepageItems = $homepageItems ?? [];
$heroSlides = $heroSlides ?? [];
$agenda = $agenda ?? [];
$news = $news ?? [];
$writingArticles = $writingArticles ?? [];
$programs = $programs ?? [];
$board = $board ?? [];
$partners = $partners ?? [];
$aboutValues = $aboutValues ?? [];
$t = static function (array $row, string $field) use ($locale): string {
    return (string) (($locale === 'en' && !empty($row[$field . '_en'])) ? $row[$field . '_en'] : ($row[$field] ?? ''));
};
$resolveUrl = static function (string $url): string {
    if ($url === '' || $url === '#') return $url ?: '#';
    return preg_match('#^https?://#i', $url) ? $url : base_url(ltrim($url, '/'));
};
$sourceRows = static function (string $source) use ($agenda, $news, $writingArticles, $programs, $board, $partners, $aboutValues, $homepageItems): array {
    return match ($source) {
        'agenda' => $agenda,
        'article', 'news' => $news ?: $writingArticles,
        'program' => $programs,
        'board' => $board,
        'partners' => $partners,
        'about_values' => $aboutValues,
        'about_links' => $homepageItems['about_links'] ?? [],
        'social' => $homepageItems['social'] ?? [],
        'join_interest' => $homepageItems['join_interest'] ?? [],
        default => [],
    };
};
$blockItems = static function (array $data, string $source, array $section) use ($sourceRows, $homepageItems): array {
    if (!empty($data['items']) && is_array($data['items'])) return $data['items'];
    if ($source === 'section') return [$section];
    if ($source !== 'manual') return $sourceRows($source);
    return $homepageItems[$data['section_key'] ?? ''] ?? [];
};
?>
<?php
$joinSection = null;
$internshipSection = null;
foreach ($builderSections as $candidate) {
    $candidateKey = strtolower((string) ($candidate['section_key'] ?? ''));
    $candidateRenderKey = strtolower((string) ($candidate['render_key'] ?? ''));
    if ($candidateKey === 'join' || $candidateRenderKey === 'join') $joinSection = $candidate;
    if ($candidateKey === 'internship' || $candidateRenderKey === 'internship') $internshipSection = $candidate;
}
?>
<main class="homepage-builder" data-homepage-renderer="blocks">
<?php foreach ($builderSections as $section): ?>
    <?php $sectionKeyLower = strtolower((string) ($section['section_key'] ?? '')); $sectionRenderKeyLower = strtolower((string) ($section['render_key'] ?? '')); if (($sectionKeyLower === 'internship' || $sectionRenderKeyLower === 'internship') && $joinSection !== null) continue; ?>
    <?php foreach (($section['blocks'] ?? []) as $block): $data = $block['data'] ?? []; $en = $block['data_en'] ?? []; if ($locale === 'en') $data = array_replace($data, $en); $type = $block['block_type']; $source = (string) ($data['source'] ?? 'manual'); $items = $blockItems(array_merge($data, ['section_key' => $section['section_key']]), $source, $section); $limit = array_key_exists('limit', $data) ? max(1, min(24, (int) $data['limit'])) : null; if ($limit !== null) $items = array_slice($items, 0, $limit); $columns = max(1, min(6, (int) ($data['columns'] ?? 3))); $col = max(1, (int) floor(12 / $columns)); $sectionDomKey = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $section['section_key']); ?>
    <div class="homepage-block-section homepage-transition-target" data-section-key="<?= esc($section['section_key']); ?>" data-render-key="<?= esc($section['render_key'] ?? ''); ?>" data-section-name="<?= esc($section['section_name'] ?? ''); ?>" id="<?= esc($sectionDomKey); ?>" style="scroll-margin-top: 88px;">
    <?php $variant = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($data['template_variant'] ?? '')); ?>
    <?php if ($variant !== ''): ?><?= view('frontend/blocks/' . $variant, ['section' => $section, 'items' => $items, 'locale' => $locale, 'settings' => $settings ?? [], 'internshipSection' => $internshipSection, 'block' => $block]); ?>
    <?php elseif ($type === 'spacer'): ?><div style="height:<?= max(20, min(240, (int) ($data['height'] ?? 80))); ?>px"></div>
    <?php elseif ($type === 'rich_text'): ?><section class="py-5 bg-white"><div class="container py-4"><h2 class="fw-bold text-danger mb-3"><?= esc($data['title'] ?? $t($section, 'title')); ?></h2><article class="page-body fs-5 lh-lg"><?= $data['body'] ?? ($t($section, 'content') ?: $t($section, 'subtitle')); ?></article></div></section>
    <?php elseif ($type === 'quote'): ?><section class="py-5 bg-light"><div class="container"><figure class="border-start border-4 border-danger p-4"><blockquote class="fs-3 fst-italic">“<?= esc($data['body'] ?? $section['quote'] ?? ''); ?>”</blockquote><figcaption><?= esc($data['title'] ?? ''); ?></figcaption></figure></div></section>
    <?php elseif ($type === 'cta'): ?><section class="py-5 bg-danger text-white"><div class="container d-flex flex-wrap justify-content-between align-items-center gap-3"><h2 class="h3 mb-0"><?= esc($data['body'] ?? $data['title'] ?? ''); ?></h2><?php if (!empty($data['button_url'])): ?><a class="btn btn-warning rounded-pill" href="<?= esc($resolveUrl($data['button_url'])); ?>"><?= esc($data['button_label'] ?? 'Selengkapnya'); ?> ↗</a><?php endif; ?></div></section>
    <?php elseif ($type === 'image_text'): ?><section class="py-5 bg-light"><div class="container"><div class="row align-items-center g-5"><div class="col-lg-6 <?= ($data['image_position'] ?? 'left') === 'right' ? 'order-lg-2' : ''; ?>"><?php if (!empty($data['image_url'])): ?><img src="<?= esc($resolveUrl($data['image_url'])); ?>" class="img-fluid rounded-4 w-100" alt="<?= esc($data['title'] ?? ''); ?>"><?php endif; ?></div><div class="col-lg-6"><small class="text-danger fw-bold text-uppercase"><?= esc($t($section, 'kicker')); ?></small><h2 class="fw-bold text-danger mt-2"><?= esc($data['title'] ?? $t($section, 'title')); ?></h2><p class="fs-5 text-secondary"><?= esc($data['body'] ?? $t($section, 'subtitle')); ?></p></div></div></div></section>
    <?php elseif (in_array($type, ['cards', 'collection'], true)): ?><section class="py-5 <?= ($data['variant'] ?? '') === 'dark' ? 'bg-dark text-white' : 'bg-light'; ?>"><div class="container py-4"><div class="mb-4"><small class="text-danger fw-bold text-uppercase"><?= esc($t($section, 'kicker')); ?></small><h2 class="fw-bold <?= ($data['variant'] ?? '') === 'dark' ? 'text-white' : 'text-danger'; ?>"><?= esc($data['title'] ?? $t($section, 'title')); ?></h2><p class="text-secondary"><?= esc($data['body'] ?? $t($section, 'subtitle')); ?></p></div><div class="row g-4"><?php foreach ($items as $item): $title = $t($item, 'title') ?: $t($item, 'name') ?: $t($item, 'label'); $body = $t($item, 'body') ?: $t($item, 'description') ?: $t($item, 'excerpt'); $url = $resolveUrl((string) ($item['url'] ?? (!empty($item['slug']) ? 'program/' . $item['slug'] : '#'))); ?><div class="col-md-<?= $col; ?>"><a href="<?= esc($url); ?>" class="text-decoration-none"><article class="card h-100 border-0 shadow-sm rounded-4 p-4 <?= ($data['variant'] ?? '') === 'dark' ? 'bg-black text-white' : ''; ?>"><h3 class="h5 fw-bold <?= ($data['variant'] ?? '') === 'dark' ? 'text-white' : 'text-dark'; ?>"><?= esc($title); ?></h3><p class="text-secondary mb-0"><?= esc($body); ?></p></article></a></div><?php endforeach; ?></div></div></section>
    <?php elseif (in_array($type, ['horizontal_slider', 'hero_slider'], true)): ?><section class="py-5 <?= $type === 'hero_slider' ? 'bg-dark text-white' : 'bg-white'; ?>"><div class="container py-4"><div class="d-flex justify-content-between align-items-end mb-4"><div><small class="text-danger fw-bold text-uppercase"><?= esc($t($section, 'kicker')); ?></small><h2 class="fw-bold"><?= esc($data['title'] ?? $t($section, 'title')); ?></h2></div></div><div class="d-flex gap-4 overflow-auto pb-3" style="scroll-snap-type:x mandatory;"><?php foreach (($type === 'hero_slider' && empty($data['items']) && $source !== 'section' ? $heroSlides : $items) as $item): $title = $t($item, 'title') ?: $t($item, 'name') ?: $t($item, 'label'); $image = $item['image_url'] ?? $item['media_url'] ?? ''; ?><article class="card flex-shrink-0 rounded-4 overflow-hidden" style="width:min(82vw,360px);scroll-snap-align:start;"><?php if ($image): ?><img src="<?= esc($resolveUrl($image)); ?>" class="w-100" style="height:190px;object-fit:cover" alt="<?= esc($title); ?>"><?php endif; ?><div class="card-body"><h3 class="h5 fw-bold"><?= esc($title); ?></h3><p class="text-secondary mb-0"><?= esc($t($item, 'body') ?: $t($item, 'description') ?: $t($item, 'lead')); ?></p></div></article><?php endforeach; ?></div></div></section>
    <?php elseif ($type === 'logo_grid'): ?><section class="py-5 bg-white"><div class="container py-4"><h2 class="fw-bold text-danger mb-4"><?= esc($data['title'] ?? $t($section, 'title')); ?></h2><div class="row row-cols-2 row-cols-md-<?= min(6, $columns); ?> g-0 border-top border-start"><?php foreach ($items as $item): $name = $t($item, 'name') ?: $t($item, 'title') ?: $t($item, 'label'); $logo = $item['logo_url'] ?? $item['media_url'] ?? ''; ?><div class="col border-end border-bottom p-4 d-flex flex-column align-items-center justify-content-center" style="min-height:150px;"><?php if ($logo): ?><img src="<?= esc($resolveUrl($logo)); ?>" class="img-fluid mb-3" style="max-height:80px;object-fit:contain" alt="<?= esc($name); ?>"><?php endif; ?><span class="small fw-bold text-center"><?= esc($name); ?></span></div><?php endforeach; ?></div></div></section>
    <?php elseif ($type === 'media_tabs'): ?><section class="py-5 bg-dark text-white"><div class="container py-4"><h2 class="fw-bold mb-4"><?= esc($data['title'] ?? $t($section, 'title')); ?></h2><ul class="nav nav-tabs mb-4" role="tablist"><?php foreach ($items as $i => $item): ?><li class="nav-item"><a class="nav-link <?= $i === 0 ? 'active' : ''; ?>" data-bs-toggle="tab" href="#builder-media-<?= $block['id']; ?>-<?= $i; ?>"><?= esc($t($item, 'label') ?: $t($item, 'title')); ?></a></li><?php endforeach; ?></ul><div class="tab-content"><?php foreach ($items as $i => $item): ?><div class="tab-pane fade <?= $i === 0 ? 'show active' : ''; ?>" id="builder-media-<?= $block['id']; ?>-<?= $i; ?>"><a class="text-white fs-3" href="<?= esc($resolveUrl((string) ($item['url'] ?? '#'))); ?>" target="_blank"><?= esc($t($item, 'title') ?: $t($item, 'body')); ?> ↗</a></div><?php endforeach; ?></div></div></section>
    <?php elseif ($type === 'join_form'): ?><section class="py-5 bg-danger text-white"><div class="container py-4"><div class="row g-5 align-items-center"><div class="col-lg-5"><h2 class="fw-bold"><?= esc($data['title'] ?? $t($section, 'title')); ?></h2><p><?= esc($data['body'] ?? $t($section, 'subtitle')); ?></p></div><div class="col-lg-7 bg-white text-dark rounded-4 p-4"><form><div class="row g-3"><div class="col-md-6"><label class="form-label">Nama Lengkap</label><input class="form-control" required></div><div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" required></div><div class="col-12"><label class="form-label">Minat Bergabung</label><select class="form-select"><option>Umum</option><option>Relawan</option><option>Penulis</option><option>Magang</option></select></div><div class="col-12"><button class="btn btn-danger">Kirim</button></div></div></form></div></div></div></section>
    <?php endif; ?></div>
    <?php endforeach; ?>
<?php endforeach; ?>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const normalize = value => String(value || '').toLowerCase().replace(/[^a-z0-9]+/g, '');
    const blocks = Array.from(document.querySelectorAll('.homepage-block-section[data-section-key]'));
    const transitionNames = ['fade-up', 'slide-left', 'slide-right', 'soft-zoom'];
    blocks.forEach((block, index) => block.classList.add('transition-' + transitionNames[(index * 7 + Math.floor(Math.random() * transitionNames.length)) % transitionNames.length]));
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
        }), { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        blocks.forEach(block => observer.observe(block));
    } else blocks.forEach(block => block.classList.add('is-visible'));
    const resolveTarget = hash => {
        const token = normalize(String(hash || '').replace(/^#/, ''));
        if (!token) return null;
        return blocks.find(block => [block.dataset.sectionKey, block.dataset.renderKey, block.dataset.sectionName, block.id]
            .some(value => normalize(value) === token || normalize(value).includes(token))) || null;
    };
    const go = hash => {
        const target = resolveTarget(hash);
        if (!target) return false;
        target.scrollIntoView({behavior: 'smooth', block: 'start'});
        return true;
    };
    document.querySelectorAll('a[href*="#"]').forEach(link => link.addEventListener('click', function (event) {
        const hash = this.hash;
        if (!hash || !resolveTarget(hash)) return;
        event.preventDefault();
        history.pushState(null, '', hash);
        go(hash);
    }));
    if (window.location.hash) window.setTimeout(() => go(window.location.hash), 0);
});
</script>

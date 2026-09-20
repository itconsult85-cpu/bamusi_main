<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php
$locale = $locale ?? 'id';
$sectionsData = $sectionsData ?? [];
$sections = $sections ?? [];
$homepageItems = $homepageItems ?? [];
$builderSections = $builderSections ?? [];
$settings = $settings ?? [];

$t = static function (array $row, string $field) use ($locale): string {
    $value = $locale === 'en' && !empty($row[$field . '_en']) ? $row[$field . '_en'] : ($row[$field] ?? '');
    return (string) $value;
};
$resolveUrl = static function (string $url): string {
    $url = trim($url);
    if ($url === '' || $url === '#') return $url ?: '#';
    return preg_match('#^https?://#i', $url) ? $url : base_url(ltrim($url, '/'));
};
$sectionItems = static function (array $section) use ($homepageItems): array {
    $key = (string) ($section['section_key'] ?? '');
    $renderKey = (string) ($section['render_key'] ?? $key);
    return $homepageItems[$key] ?? $homepageItems[$renderKey] ?? [];
};
$builderIds = [];
foreach ($builderSections as $builderSection) {
    $builderIds[(int) ($builderSection['id'] ?? 0)] = true;
}
?>

<?php if (!empty($builderSections)): ?>
    <?= view('frontend/homepage_builder', get_defined_vars()); ?>
<?php endif; ?>

<main class="homepage-dynamic">
    <?php foreach ($sectionsData as $section): ?>
        <?php
        // Section dengan blok builder sudah dirender oleh homepage_builder.
        if (isset($builderIds[(int) ($section['id'] ?? 0)])) continue;
        $key = trim((string) ($section['section_key'] ?? ''));
        if ($key === '' || (int) ($section['published'] ?? 0) !== 1) continue;
        $items = $sectionItems($section);
        $media = trim((string) ($section['media_url'] ?? ''));
        $mediaUrl = $resolveUrl($media);
        $title = $t($section, 'title');
        $body = $t($section, 'subtitle');
        if ($body === '') $body = strip_tags($t($section, 'content'));
        $buttonLabel = $t($section, 'button_label');
        $buttonUrl = $resolveUrl((string) ($section['button_url'] ?? '#'));
        ?>
        <section data-section-key="<?= esc($key); ?>" id="section-<?= esc($key); ?>" class="py-5 bg-white homepage-section">
            <?php if ($mediaUrl !== '#'): ?>
                <div class="container-fluid px-4 px-lg-5 mb-4">
                    <img src="<?= esc($mediaUrl); ?>" class="img-fluid w-100 rounded-4" style="max-height:460px;object-fit:cover" alt="<?= esc($title ?: $section['section_name']); ?>">
                </div>
            <?php endif; ?>
            <div class="container-fluid px-4 px-lg-5">
                <?php if ($t($section, 'kicker') !== ''): ?><div class="text-uppercase text-danger fw-bold mb-2"><?= esc($t($section, 'kicker')); ?></div><?php endif; ?>
                <?php if ($title !== ''): ?><h2 class="fw-bold mb-3"><?= str_replace('|', '<br>', esc($title)); ?></h2><?php endif; ?>
                <?php if ($body !== ''): ?><p class="text-secondary fs-5 mb-4"><?= esc($body); ?></p><?php endif; ?>
                <?php if ($t($section, 'content') !== ''): ?><div class="page-body mb-4"><?= $t($section, 'content'); ?></div><?php endif; ?>
                <?php if ($buttonLabel !== ''): ?><a class="btn btn-danger rounded-pill" href="<?= esc($buttonUrl); ?>"><?= esc($buttonLabel); ?></a><?php endif; ?>

                <?php if (!empty($items)): ?>
                    <div class="row g-4 mt-3">
                        <?php foreach ($items as $item): ?>
                            <?php
                            if ((int) ($item['published'] ?? 0) !== 1) continue;
                            $itemTitle = $t($item, 'title') ?: $t($item, 'label');
                            $itemBody = $t($item, 'body');
                            $itemUrl = $resolveUrl((string) ($item['url'] ?? '#'));
                            $itemMedia = trim((string) ($item['media_url'] ?? ''));
                            ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                    <?php if ($itemMedia !== ''): ?><img src="<?= esc($resolveUrl($itemMedia)); ?>" class="card-img-top" style="height:190px;object-fit:cover" alt="<?= esc($itemTitle); ?>"><?php endif; ?>
                                    <div class="card-body">
                                        <?php if ($itemTitle !== ''): ?><h3 class="h5 fw-bold"><?= esc($itemTitle); ?></h3><?php endif; ?>
                                        <?php if ($itemBody !== ''): ?><p class="text-secondary mb-3"><?= esc($itemBody); ?></p><?php endif; ?>
                                        <?php if ($itemUrl !== '#'): ?><a href="<?= esc($itemUrl); ?>" class="text-danger fw-bold text-decoration-none"><?= $locale === 'en' ? 'Read more' : 'Selengkapnya'; ?> ↗</a><?php endif; ?>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const main = document.querySelector('main.homepage-dynamic');
    if (!main) return;
    const sections = Array.from(main.querySelectorAll('section[data-section-key]'));
    sections.forEach((section) => {
        section.dataset.published = '1';
    });
});
</script>

<?= $this->endSection(); ?>

<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>
<?php
$baseQuery = [];
if ($query !== '') $baseQuery['q'] = $query;
if ($category !== '') $baseQuery['category'] = $category;
$paginationUrl = static function (int $number) use ($baseQuery): string {
    $params = $baseQuery;
    $params['page'] = $number;
    return base_url('berita') . '?' . http_build_query($params);
};
$heroKicker = $page['header_kicker'] ?? '05 / 05 · Ruang Berita';
$heroTitle = $page['header_title'] ?? 'Kabar BAMUSI untuk Indonesia.';
$heroIntro = $page['header_intro'] ?? 'Kurasi pemberitaan publik tentang Baitul Muslimin Indonesia dari sumber nasional.';
$heroTitleParts = preg_split('/\s+(?=untuk\s+Indonesia\.?$)/i', trim($heroTitle), 2);
?>
<section class="py-5 text-white" style="background:linear-gradient(120deg,#640000 0%,#9f0000 55%,#d9a441 150%);padding-top:9rem!important;">
    <div class="container py-4">
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <span class="badge rounded-pill text-bg-warning text-uppercase px-3 py-2 mb-3"><?= esc($heroKicker); ?></span>
                <h1 class="display-3 fw-bold mb-3"><?= esc($heroTitleParts[0]); ?><?php if (isset($heroTitleParts[1])): ?><br><span class="text-warning"><?= esc($heroTitleParts[1]); ?></span><?php endif; ?></h1>
                <p class="lead mb-0 opacity-75"><?= esc($heroIntro); ?></p>
            </div>
            <div class="col-lg-4 text-lg-end"><span class="fs-5 opacity-75"><?= esc($total); ?> berita ditemukan</span></div>
        </div>
    </div>
</section>
<section class="bg-light py-5">
    <div class="container">
        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body p-3 p-md-4">
                <form action="<?= base_url('berita'); ?>" method="get" class="row g-2 align-items-center">
                    <div class="col-lg-7"><label class="visually-hidden" for="news-search">Cari berita</label><div class="input-group input-group-lg"><span class="input-group-text bg-white border-end-0">⌕</span><input id="news-search" type="search" name="q" value="<?= esc($query); ?>" class="form-control border-start-0" placeholder="Cari judul, sumber, atau kata kunci..."></div></div>
                    <?php if ($category !== ''): ?><input type="hidden" name="category" value="<?= esc($category); ?>"><?php endif; ?>
                    <div class="col-lg-2 d-grid"><button class="btn btn-dark btn-lg rounded-3" type="submit">Cari berita</button></div>
                    <?php if ($query !== '' || $category !== ''): ?><div class="col-lg-2 d-grid"><a href="<?= base_url('berita'); ?>" class="btn btn-outline-secondary btn-lg rounded-3">Reset filter</a></div><?php endif; ?>
                </form>
                <div class="d-flex flex-wrap gap-2 align-items-center mt-4"><span class="small text-secondary fw-semibold me-1">Kategori:</span><a class="btn btn-sm rounded-pill <?= $category === '' ? 'btn-danger' : 'btn-outline-secondary'; ?>" href="<?= base_url('berita') . ($query !== '' ? '?q=' . urlencode($query) : ''); ?>">Semua</a><?php foreach ($categories as $itemCategory): ?><a class="btn btn-sm rounded-pill <?= strcasecmp($category, $itemCategory) === 0 ? 'btn-danger' : 'btn-outline-secondary'; ?>" href="<?= base_url('berita') . '?' . http_build_query(array_filter(['q' => $query, 'category' => $itemCategory])); ?>"><?= esc($itemCategory); ?></a><?php endforeach; ?></div>
            </div>
        </div>
        <?php if (empty($news)): ?>
            <div class="text-center bg-white rounded-4 shadow-sm p-5"><div class="display-4 mb-3">∅</div><h2 class="h4 fw-bold">Tidak ada berita yang cocok.</h2><p class="text-secondary mb-4">Coba gunakan kata kunci atau kategori yang berbeda.</p><a href="<?= base_url('berita'); ?>" class="btn btn-danger rounded-pill px-4">Reset filter</a></div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($news as $item): ?>
                    <?php $image = trim((string)($item['image_url'] ?? '')); if ($image && !preg_match('#^https?://#i', $image)) $image = base_url(ltrim($image, '/')); ?>
                    <div class="col-md-6 col-lg-3"><article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden news-card"><div class="ratio ratio-16x9 bg-danger-subtle"><?php if ($image): ?><img src="<?= esc($image); ?>" class="card-img-top object-fit-cover" alt="<?= esc($item['title']); ?>"><?php else: ?><div class="d-flex align-items-center justify-content-center bg-gradient text-danger-emphasis display-5 fw-bold">B</div><?php endif; ?></div><div class="card-body d-flex flex-column p-4"><div class="d-flex justify-content-between gap-2 mb-3"><span class="badge text-bg-danger rounded-pill"><?= esc($item['category']); ?></span><small class="text-secondary text-nowrap"><?= esc($item['event_date']); ?></small></div><h2 class="h5 fw-bold lh-sm"><a class="text-dark text-decoration-none" href="<?= esc($item['url']); ?>" target="_blank" rel="noopener noreferrer"><?= esc($item['title']); ?></a></h2><p class="small text-secondary mt-2 mb-4"><?= esc(mb_strimwidth($item['summary'] ?: 'Baca pemberitaan terbaru dari sumber terkait.', 0, 105, '...')); ?></p><div class="mt-auto d-flex justify-content-between align-items-center"><small class="fw-semibold text-danger"><?= esc($item['source']); ?></small><a href="<?= esc($item['url']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger rounded-pill">Baca ↗</a></div></div></article></div>
                <?php endforeach; ?>
            </div>
            <?php if ($totalPages > 1): ?><nav class="mt-5" aria-label="Navigasi halaman berita"><ul class="pagination justify-content-center gap-2"><?php for ($i = 1; $i <= $totalPages; $i++): ?><li class="page-item <?= $i === $page ? 'active' : ''; ?>"><a class="page-link rounded-circle d-flex align-items-center justify-content-center" style="width:42px;height:42px;" href="<?= esc($paginationUrl($i)); ?>"><?= $i; ?></a></li><?php endfor; ?></ul></nav><?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<style>.news-card{transition:transform .2s ease,box-shadow .2s ease}.news-card:hover{transform:translateY(-6px);box-shadow:0 1rem 3rem rgba(91,0,0,.14)!important}.object-fit-cover{object-fit:cover}.page-item.active .page-link{background-color:#8b0000;border-color:#8b0000}.page-link{color:#8b0000}</style>
<?= $this->endSection(); ?>

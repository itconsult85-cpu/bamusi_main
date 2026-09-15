<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>
<?php
$isEn = ($locale === 'en');

$baseQuery = [];
if ($query !== '') $baseQuery['q'] = $query;
if ($category !== '') $baseQuery['category'] = $category;
$paginationUrl = static function (int $number) use ($baseQuery): string {
    $params = $baseQuery;
    $params['page'] = $number;
    return base_url('berita') . '?' . http_build_query($params);
};

// Terjemahan Hero
$heroKicker = $page['header_kicker'] ?? ($isEn ? '05 / 05 · Newsroom' : '05 / 05 · Ruang Berita');
$heroTitle = $page['header_title'] ?? ($isEn ? 'BAMUSI news for Indonesia.' : 'Kabar BAMUSI untuk Indonesia.');
$heroIntro = $page['header_intro'] ?? ($isEn ? 'Curated public news about Baitul Muslimin Indonesia from national sources.' : 'Kurasi pemberitaan publik tentang Baitul Muslimin Indonesia dari sumber nasional.');
$regexSplit = $isEn ? '/\s+(?=for\s+Indonesia\.?$)/i' : '/\s+(?=untuk\s+Indonesia\.?$)/i';
$heroTitleParts = preg_split($regexSplit, trim($heroTitle), 2);

// Terjemahan UI Antarmuka
$txtFound = $isEn ? 'news found' : 'berita ditemukan';
$txtSearchLabel = $isEn ? 'SEARCH NEWS' : 'CARI BERITA';
$txtSearchPlaceholder = $isEn ? 'Title, source, or keyword...' : 'Judul, sumber, atau kata kunci...';
$txtAll = $isEn ? 'All' : 'Semua';
$txtEmptyTitle = $isEn ? 'No news found.' : 'Tidak ada berita yang cocok.';
$txtEmptyDesc = $isEn ? 'Try using different keywords or categories.' : 'Coba gunakan kata kunci atau kategori yang berbeda.';
$txtReset = $isEn ? 'Reset filters' : 'Reset filter';
$txtRead = $isEn ? 'Read more' : 'Baca selengkapnya';

// Penterjemah Kategori Dinamis (URL parameter tetap pakai bahasa Indonesia, tapi label yang tampil berubah)
$translateCat = function ($cat) use ($isEn) {
    if (!$isEn) return $cat;
    $map = [
        'Organisasi' => 'Organization',
        'Keislaman'  => 'Islamic',
        'Kebangsaan' => 'National',
        'Sosial'     => 'Social',
        'Nasional'   => 'National',
        'Umum'       => 'General'
    ];
    return $map[$cat] ?? $cat;
};
?>

<!-- 1. Menghilangkan elemen warna kuning (gradient, badge, dan teks) -->
<section class="py-5 text-white" style="background:linear-gradient(120deg,#640000 0%,#9f0000 100%);padding-top:9rem!important;">
    <div class="container py-4">
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <span class="badge rounded-pill bg-white text-danger text-uppercase px-3 py-2 mb-3"><?= esc($heroKicker); ?></span>
                <h1 class="display-3 fw-bold mb-3"><?= esc($heroTitleParts[0]); ?><?php if (isset($heroTitleParts[1])): ?><br><span><?= esc($heroTitleParts[1]); ?></span><?php endif; ?></h1>
                <p class="lead mb-0 opacity-75"><?= esc($heroIntro); ?></p>
            </div>
            <div class="col-lg-4 text-lg-end"><span class="fs-5 opacity-75"><?= esc($total); ?> <?= $txtFound ?></span></div>
        </div>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">

        <!-- 2. Tata letak pencarian dan kategori -->
        <div class="row align-items-end border-bottom pb-4 mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <label class="form-label fw-bold text-dark small text-uppercase mb-2"><?= $txtSearchLabel ?></label>
                <form action="<?= base_url('berita'); ?>" method="get">
                    <?php if ($category !== ''): ?><input type="hidden" name="category" value="<?= esc($category); ?>"><?php endif; ?>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">⌕</span>
                        <input id="news-search" type="search" name="q" value="<?= esc($query); ?>" class="form-control border-start-0" placeholder="<?= $txtSearchPlaceholder ?>">
                    </div>
                </form>
            </div>
            <div class="col-lg-7 d-flex justify-content-lg-end">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a class="btn btn-sm rounded-pill <?= $category === '' ? 'btn-danger' : 'btn-outline-secondary bg-white'; ?>" href="<?= base_url('berita') . ($query !== '' ? '?q=' . urlencode($query) : ''); ?>"><?= $txtAll ?></a>
                    <?php foreach ($categories as $itemCategory): ?>
                        <a class="btn btn-sm rounded-pill <?= strcasecmp($category, $itemCategory) === 0 ? 'btn-danger' : 'btn-outline-secondary bg-white'; ?>" href="<?= base_url('berita') . '?' . http_build_query(array_filter(['q' => $query, 'category' => $itemCategory])); ?>">
                            <?= esc($translateCat($itemCategory)); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if (empty($news)): ?>
            <div class="text-center bg-white rounded-4 shadow-sm p-5">
                <div class="display-4 mb-3 text-secondary">∅</div>
                <h2 class="h4 fw-bold"><?= $txtEmptyTitle ?></h2>
                <p class="text-secondary mb-4"><?= $txtEmptyDesc ?></p>
                <a href="<?= base_url('berita'); ?>" class="btn btn-danger rounded-pill px-4"><?= $txtReset ?></a>
            </div>
        <?php else: ?>
            <!-- 3. List Berita -->
            <div class="row g-4">
                <?php foreach ($news as $index => $item): ?>
                    <?php $num = str_pad($index + 1 + (($page - 1) * 9), 2, '0', STR_PAD_LEFT); ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden news-card">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="display-5 text-danger fw-bold mb-4 opacity-75"><?= $num ?></div>

                                <div class="d-flex justify-content-between gap-2 mb-3">
                                    <span class="badge text-bg-danger rounded-pill"><?= esc($translateCat($item['category'])); ?></span>
                                    <small class="text-secondary text-nowrap"><?= esc($item['event_date']); ?></small>
                                </div>

                                <h2 class="h5 fw-bold lh-sm">
                                    <a class="text-dark text-decoration-none" href="<?= esc($item['url']); ?>" target="_blank" rel="noopener noreferrer"><?= esc($item['title']); ?></a>
                                </h2>

                                <p class="small text-secondary mt-2 mb-4"><?= esc(mb_strimwidth($item['summary'] ?: '...', 0, 105, '...')); ?></p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <small class="fw-semibold text-danger"><?= esc($item['source']); ?></small>
                                    <a href="<?= esc($item['url']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger rounded-pill"><?= $txtRead ?> ↗</a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <nav class="mt-5" aria-label="Navigasi halaman berita">
                    <ul class="pagination justify-content-center gap-2">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : ''; ?>">
                                <a class="page-link rounded-circle d-flex align-items-center justify-content-center" style="width:42px;height:42px;" href="<?= esc($paginationUrl($i)); ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<style>
    .news-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .news-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 1rem 3rem rgba(91, 0, 0, .14) !important;
    }

    .page-item.active .page-link {
        background-color: #8b0000;
        border-color: #8b0000;
    }

    .page-link {
        color: #8b0000;
    }
</style>
<?= $this->endSection(); ?>
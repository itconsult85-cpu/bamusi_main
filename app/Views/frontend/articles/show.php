<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>
<?php
$locale = $locale ?? 'id';
$image = trim((string)($article['image_url'] ?? ''));
if ($image && !preg_match('#^https?://#i', $image)) $image = base_url(ltrim($image, '/'));
$category = trim((string)($article['category'] ?? '')) ?: ($locale === 'en' ? 'Articles' : 'Kolom Tulisan');
$title = ($locale === 'en' && !empty($article['title_en'])) ? $article['title_en'] : $article['title'];
$summary = ($locale === 'en' && !empty($article['summary_en'])) ? $article['summary_en'] : ($article['summary'] ?? '');
$body = ($locale === 'en' && !empty($article['body_en'])) ? $article['body_en'] : ($article['body'] ?? '');
$publishedAt = !empty($article['created_at']) ? strtotime($article['created_at']) : time();
$bodyText = trim(strip_tags((string)$body));
$readingMinutes = max(1, (int)ceil(str_word_count($bodyText) / 180));
$shareUrl = current_url();
?>
<main class="blog-details-page">
    <section class="article-page-title">
        <div class="container-fluid px-4 px-lg-5">
            <nav class="article-breadcrumb" aria-label="breadcrumb">
                <a href="<?= base_url('/'); ?>">⌂ <?= $locale === 'en' ? 'Home' : 'Beranda'; ?></a>
                <span>/</span>
                <a href="<?= base_url('artikel'); ?>"><?= $locale === 'en' ? 'Articles' : 'Kolom Tulisan'; ?></a>
                <span>/</span>
                <strong><?= esc($category); ?></strong>
            </nav>
            <div class="article-page-heading">
                <span><?= $locale === 'en' ? 'Article detail' : 'Detail artikel'; ?></span>
                <h1><?= esc($title); ?></h1>
                <?php if (!empty($summary)): ?><p><?= esc($summary); ?></p><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="article-layout-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <article class="article-card-detail">
                        <div class="article-hero-image">
                            <?php if ($image): ?>
                                <img src="<?= esc($image); ?>" alt="<?= esc($title); ?>" loading="lazy">
                            <?php else: ?>
                                <div class="article-image-placeholder">BAMUSI</div>
                            <?php endif; ?>
                            <div class="article-meta-overlay">
                                <span><?= esc($category); ?></span>
                                <b>•</b>
                                <span>◷ <?= $readingMinutes; ?> min read</span>
                            </div>
                        </div>

                        <div class="article-content-detail">
                            <header class="article-content-header">
                                <h2><?= esc($title); ?></h2>
                                <div class="article-author-row">
                                    <div class="author-avatar">B</div>
                                    <div>
                                        <strong>BAMUSI Editorial</strong>
                                        <span><?= $locale === 'en' ? 'Editorial team' : 'Tim redaksi'; ?></span>
                                    </div>
                                    <div class="article-post-meta">
                                        <span>◷ <?= date($locale === 'en' ? 'M d, Y' : 'd M Y', $publishedAt); ?></span>
                                    </div>
                                </div>
                            </header>

                            <div class="article-rich-text">
                                <?= $body ?: '<p>Konten artikel belum diisi.</p>'; ?>
                            </div>

                            <div class="article-meta-bottom">
                                <div>
                                    <h3><?= $locale === 'en' ? 'Related topics' : 'Topik terkait'; ?></h3>
                                    <div class="article-tags">
                                        <a href="<?= base_url('artikel'); ?>"><?= esc($category); ?></a>
                                        <a href="<?= base_url('artikel'); ?>"><?= $locale === 'en' ? 'Humanity' : 'Kemanusiaan'; ?></a>
                                        <a href="<?= base_url('artikel'); ?>"><?= $locale === 'en' ? 'Indonesia' : 'Indonesia'; ?></a>
                                    </div>
                                </div>
                                <div class="article-share" data-share-url="<?= esc($shareUrl); ?>">
                                    <h3><?= $locale === 'en' ? 'Share article' : 'Bagikan artikel'; ?></h3>
                                    <div>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode($shareUrl); ?>" target="_blank" rel="noopener" aria-label="Facebook">f</a>
                                        <a href="https://twitter.com/intent/tweet?url=<?= rawurlencode($shareUrl); ?>&text=<?= rawurlencode($title); ?>" target="_blank" rel="noopener" aria-label="X">𝕏</a>
                                        <button type="button" class="copy-article-link" aria-label="Copy link">↗</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <aside class="col-lg-4">
                    <div class="article-sidebar">
                        <div class="sidebar-accent"></div>
                        <h3><?= $locale === 'en' ? 'About this column' : 'Tentang kolom tulisan'; ?></h3>
                        <p><?= $locale === 'en' ? 'A space for clear, thoughtful, and humane writing about Islam, democracy, and Indonesia.' : 'Ruang bagi tulisan yang jernih, bernas, dan berpihak pada kemanusiaan tentang Islam, demokrasi, dan Indonesia.'; ?></p>
                    </div>
                    <div class="article-sidebar related-sidebar">
                        <h3><?= $locale === 'en' ? 'Latest articles' : 'Artikel terbaru'; ?></h3>
                        <?php if (!empty($relatedArticles)): ?>
                            <?php foreach ($relatedArticles as $related): ?>
                                <a class="related-article" href="<?= base_url('artikel/' . $related['id']); ?>">
                                    <span><?= esc($related['category'] ?: ($locale === 'en' ? 'Article' : 'Artikel')); ?></span>
                                    <strong><?= esc(($locale === 'en' && !empty($related['title_en'])) ? $related['title_en'] : $related['title']); ?></strong>
                                    <small><?= date($locale === 'en' ? 'M d, Y' : 'd M Y', strtotime($related['created_at'] ?? 'now')); ?> <b>↗</b></small>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0"><?= $locale === 'en' ? 'No other articles yet.' : 'Belum ada artikel lainnya.'; ?></p>
                        <?php endif; ?>
                    </div>
                    <a class="back-to-articles" href="<?= base_url('artikel'); ?>">← <?= $locale === 'en' ? 'View all articles' : 'Lihat semua artikel'; ?></a>
                </aside>
            </div>
        </div>
    </section>
</main>
<style>
.blog-details-page{background:#fff;color:#1c1c1c}.article-page-title{background:linear-gradient(135deg,#fff 0%,#fff8f8 62%,#f3d5d5 100%);border-bottom:1px solid #ead6d6;padding:128px 0 62px}.article-breadcrumb{display:flex;flex-wrap:wrap;gap:10px;align-items:center;color:#8b0000;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;margin-bottom:44px}.article-breadcrumb a{color:#8b0000;text-decoration:none}.article-breadcrumb span{color:#c79a9a}.article-page-heading{max-width:900px}.article-page-heading>span{display:block;color:#b00000;font-size:.78rem;letter-spacing:2.5px;text-transform:uppercase;font-weight:800;margin-bottom:14px}.article-page-heading h1{font-size:clamp(2.2rem,5vw,4.4rem);line-height:1.07;letter-spacing:-1.8px;font-weight:900;color:#7f0000;margin:0 0 18px}.article-page-heading p{max-width:820px;color:#6d5b5b;font-size:1.08rem;line-height:1.7;margin:0}.article-layout-section{padding:70px 0 110px;background:#fff}.article-card-detail{background:#fff;border:1px solid #eadada;box-shadow:0 18px 55px rgba(91,0,0,.09)}.article-hero-image{height:430px;position:relative;overflow:hidden;background:linear-gradient(135deg,#8b0000,#d79b9b)}.article-hero-image img{width:100%;height:100%;object-fit:cover}.article-image-placeholder{display:grid;place-items:center;height:100%;color:rgba(255,255,255,.45);font-size:4rem;font-weight:900;letter-spacing:4px}.article-meta-overlay{position:absolute;left:22px;bottom:20px;display:flex;gap:10px;align-items:center;background:#8b0000;color:#fff;padding:10px 16px;font-size:.75rem;letter-spacing:.7px;text-transform:uppercase;font-weight:800}.article-content-detail{padding:42px 48px 46px}.article-content-header h2{font-size:2rem;line-height:1.2;color:#8b0000;font-weight:900;margin:0 0 24px}.article-author-row{display:flex;gap:12px;align-items:center;padding:18px 0 26px;border-bottom:1px solid #ecdede}.author-avatar{width:42px;height:42px;border-radius:50%;background:#a90000;color:#fff;display:grid;place-items:center;font-weight:900}.article-author-row strong,.article-author-row span{display:block}.article-author-row strong{font-size:.88rem}.article-author-row span{font-size:.76rem;color:#8a6d6d;margin-top:3px}.article-post-meta{margin-left:auto;color:#765d5d;font-size:.8rem}.article-rich-text{font-size:1.06rem;line-height:1.9;color:#303030;padding-top:30px}.article-rich-text p{margin:0 0 1.3rem}.article-rich-text .lead{font-size:1.22rem;line-height:1.75;color:#6d3131;font-weight:500}.article-rich-text h2,.article-rich-text h3{color:#8b0000;line-height:1.2;font-weight:900;margin:2.3rem 0 1rem}.article-rich-text h2{font-size:1.7rem}.article-rich-text h3{font-size:1.3rem}.article-rich-text ul{padding-left:1.4rem;margin-bottom:1.5rem}.article-rich-text li{margin:.45rem 0}.article-rich-text blockquote{margin:2rem 0;padding:22px 26px;border-left:5px solid #c40000;background:#fff5f5;color:#6d2424;font-size:1.08rem}.article-rich-text .highlight-box{margin:2rem 0;padding:22px 26px;background:#fff3f3;border:1px solid #eccaca;border-top:4px solid #b00000}.article-rich-text img{max-width:100%;height:auto}.article-meta-bottom{display:flex;justify-content:space-between;gap:30px;border-top:1px solid #ecdede;margin-top:38px;padding-top:28px}.article-meta-bottom h3{font-size:.75rem;text-transform:uppercase;letter-spacing:1.5px;color:#8b0000;margin:0 0 12px}.article-tags{display:flex;flex-wrap:wrap;gap:7px}.article-tags a{color:#8b0000;border:1px solid #dfbcbc;text-decoration:none;padding:6px 10px;font-size:.75rem}.article-share{text-align:right}.article-share>div{display:flex;justify-content:flex-end;gap:7px}.article-share a,.copy-article-link{width:31px;height:31px;border:0;background:#8b0000;color:#fff;display:grid;place-items:center;text-decoration:none;font-weight:800;font-size:.8rem;cursor:pointer}.article-sidebar{border-top:4px solid #b00000;background:#fffafa;padding:26px 28px;margin-bottom:24px;box-shadow:0 9px 28px rgba(91,0,0,.06)}.article-sidebar h3{color:#8b0000;font-size:1.1rem;font-weight:900;margin:0 0 13px}.article-sidebar p{color:#6e5b5b;line-height:1.75;font-size:.92rem}.related-sidebar{background:#fff;border:1px solid #eadada;border-top:4px solid #b00000}.related-article{display:block;text-decoration:none;padding:16px 0;border-top:1px solid #efdfdf}.related-article:first-of-type{border-top:0}.related-article span,.related-article small{display:block;color:#a00000;font-size:.68rem;text-transform:uppercase;letter-spacing:1px;font-weight:800}.related-article strong{display:block;color:#292020;font-size:.95rem;line-height:1.35;margin:7px 0}.related-article small{color:#917070;text-transform:none;letter-spacing:0;font-weight:500}.related-article small b{float:right;color:#a00000;font-size:1rem}.back-to-articles{display:block;text-align:center;color:#8b0000;border:1px solid #c98f8f;padding:13px;text-decoration:none;font-size:.82rem;font-weight:800;text-transform:uppercase;letter-spacing:1px}.back-to-articles:hover{background:#8b0000;color:#fff}@media(max-width:991px){.article-page-title{padding-top:115px}.article-hero-image{height:360px}.article-content-detail{padding:32px 25px}.article-post-meta{margin-left:0}.article-author-row{flex-wrap:wrap}}@media(max-width:575px){.article-page-title{padding-bottom:42px}.article-breadcrumb{margin-bottom:30px}.article-hero-image{height:250px}.article-meta-overlay{left:12px;right:12px;bottom:12px;justify-content:center;font-size:.65rem}.article-content-header h2{font-size:1.5rem}.article-meta-bottom{display:block}.article-share{text-align:left;margin-top:25px}.article-share>div{justify-content:flex-start}}
</style>
<script>
document.querySelector('.copy-article-link')?.addEventListener('click', function () {
    const url = this.closest('.article-share').dataset.shareUrl;
    navigator.clipboard?.writeText(url).then(() => {
        this.textContent = '✓';
        setTimeout(() => this.textContent = '↗', 1400);
    });
});
</script>
<?= $this->endSection(); ?>

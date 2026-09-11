<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>
<?php $image = trim((string)($article['image_url'] ?? '')); if ($image && !preg_match('#^https?://#i', $image)) $image = base_url(ltrim($image, '/')); ?>
<article class="article-detail">
    <header class="article-detail-header">
        <div class="container article-detail-narrow">
            <a href="<?= base_url('artikel'); ?>" class="article-back">← Kembali ke Artikel</a>
            <div class="article-meta"><?= esc($article['category'] ?: 'Perspektif'); ?> · <?= esc(date('d M Y', strtotime($article['created_at'] ?? 'now'))); ?></div>
            <h1><?= esc($article['title']); ?></h1>
            <?php if (!empty($article['summary'])): ?><p class="article-lead"><?= esc($article['summary']); ?></p><?php endif; ?>
        </div>
    </header>
    <div class="container article-detail-narrow article-detail-content">
        <?php if ($image): ?><img class="article-cover" src="<?= esc($image); ?>" alt="<?= esc($article['title']); ?>"><?php endif; ?>
        <div class="article-rich-text"><?= $article['body'] ?: '<p>Konten artikel belum diisi.</p>'; ?></div>
    </div>
</article>
<style>
.article-detail-header{background:linear-gradient(135deg,#8b0000,#420000);color:#fff;padding:145px 0 78px}.article-detail-narrow{max-width:900px}.article-back{color:#ffd6d6;text-decoration:none;display:inline-block;margin-bottom:30px;font-weight:700}.article-detail-header .article-meta{color:#ffbcbc;text-transform:uppercase;letter-spacing:1.5px;font-size:.78rem;font-weight:800}.article-detail-header h1{font-size:clamp(2.4rem,5vw,4.7rem);line-height:1.08;font-weight:900;margin:16px 0}.article-lead{font-size:1.2rem;line-height:1.7;max-width:760px;color:#ffeaea}.article-detail-content{padding-top:64px;padding-bottom:100px}.article-cover{width:100%;max-height:500px;object-fit:cover;margin-bottom:48px;box-shadow:0 20px 55px rgba(80,0,0,.16)}.article-rich-text{font-size:1.12rem;line-height:1.9;color:#272727}.article-rich-text h2,.article-rich-text h3{color:#8b0000;font-weight:900;margin-top:2.5rem}.article-rich-text p{margin-bottom:1.35rem}.article-rich-text blockquote{border-left:5px solid #cc0000;background:#fff5f5;padding:20px 26px;margin:30px 0;color:#6d2020}.article-rich-text img{max-width:100%;height:auto}
</style>
<?= $this->endSection(); ?>

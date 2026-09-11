<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>
<section class="article-hero">
    <div class="container-fluid px-4 px-lg-5">
        <span class="article-eyebrow">Ruang Gagasan BAMUSI</span>
        <h1>Artikel</h1>
        <p>Catatan, gagasan, dan perspektif untuk Indonesia yang berkeadaban.</p>
    </div>
</section>
<section class="article-list-section">
    <div class="container-fluid px-4 px-lg-5">
        <?php if (empty($articles)): ?>
            <div class="article-empty">Belum ada artikel yang diterbitkan.</div>
        <?php else: ?>
            <div class="article-grid">
                <?php foreach ($articles as $article): ?>
                    <?php $image = trim((string)($article['image_url'] ?? '')); if ($image && !preg_match('#^https?://#i', $image)) $image = base_url(ltrim($image, '/')); ?>
                    <article class="article-card">
                        <a href="<?= base_url('artikel/' . $article['id']); ?>" class="article-card-image">
                            <?php if ($image): ?><img src="<?= esc($image); ?>" alt="<?= esc($article['title']); ?>"><?php else: ?><span><?= esc(strtoupper(mb_substr($article['title'], 0, 1))); ?></span><?php endif; ?>
                        </a>
                        <div class="article-card-body">
                            <div class="article-meta"><?= esc($article['category'] ?: 'Perspektif'); ?> · <?= esc(date('d M Y', strtotime($article['created_at'] ?? 'now'))); ?></div>
                            <h2><a href="<?= base_url('artikel/' . $article['id']); ?>"><?= esc($article['title']); ?></a></h2>
                            <p><?= esc($article['summary'] ?: mb_strimwidth(strip_tags($article['body'] ?? ''), 0, 160, '...')); ?></p>
                            <a class="article-read-more" href="<?= base_url('artikel/' . $article['id']); ?>">Baca selengkapnya <span>↗</span></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<style>
.article-hero{background:linear-gradient(135deg,#8b0000,#cc0000 55%,#f5d7d7);color:#fff;padding:145px 0 80px}.article-eyebrow{display:block;color:#ffd6d6;text-transform:uppercase;letter-spacing:3px;font-size:.78rem;font-weight:800;margin-bottom:18px}.article-hero h1{font-size:clamp(2.5rem,6vw,5rem);font-weight:900;margin:0 0 12px}.article-hero p{font-size:1.15rem;max-width:650px;opacity:.86}.article-list-section{background:#fff;padding:72px 0 100px}.article-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:28px;max-width:1280px;margin:auto}.article-card{border:1px solid #ead7d7;background:#fff;box-shadow:0 14px 40px rgba(91,0,0,.08);transition:transform .2s,box-shadow .2s}.article-card:hover{transform:translateY(-5px);box-shadow:0 22px 48px rgba(91,0,0,.15)}.article-card-image{display:block;height:220px;background:linear-gradient(135deg,#8b0000,#e4a0a0);overflow:hidden;color:#fff;text-decoration:none}.article-card-image img{width:100%;height:100%;object-fit:cover}.article-card-image span{display:grid;place-items:center;height:100%;font-size:5rem;font-weight:900}.article-card-body{padding:26px}.article-meta{color:#a60000;font-size:.75rem;letter-spacing:1.4px;text-transform:uppercase;font-weight:800}.article-card h2{font-size:1.45rem;line-height:1.2;margin:12px 0}.article-card h2 a{color:#171717;text-decoration:none}.article-card p{color:#666;line-height:1.7}.article-read-more{color:#a60000;text-decoration:none;font-weight:800}.article-read-more span{margin-left:8px}.article-empty{max-width:700px;margin:auto;text-align:center;border:1px dashed #d9a7a7;padding:60px;color:#7d3030}@media(max-width:900px){.article-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:600px){.article-grid{grid-template-columns:1fr}.article-hero{padding-top:125px}}
</style>
<?= $this->endSection(); ?>

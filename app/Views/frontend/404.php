<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php
$locale = session()->get('lang') ?? 'id';
$db = \Config\Database::connect();
$settings = array_column(
    $db->table('site_settings')->get()->getResultArray(),
    'setting_value',
    'setting_key'
);
?>

<style>
    .error-page {
        background: linear-gradient(135deg, #f8f8f8 0%, #ffffff 100%);
        min-height: 80vh;
        padding: 80px 0;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .error-page::before {
        content: "";
        position: absolute;
        top: -10%;
        right: -5%;
        width: 40vw;
        height: 40vw;
        background: radial-gradient(circle, rgba(204, 0, 0, 0.06) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .error-page::after {
        content: "404";
        position: absolute;
        bottom: -5%;
        left: -3%;
        font-size: 22vw;
        font-weight: 900;
        color: rgba(204, 0, 0, 0.035);
        line-height: 1;
        letter-spacing: -10px;
        pointer-events: none;
        user-select: none;
    }

    .error-content {
        position: relative;
        z-index: 1;
    }

    .error-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 32px;
    }

    .error-badge hr {
        width: 40px;
        border-top: 3px solid #cc0000;
        opacity: 1;
        margin: 0;
    }

    .error-badge-text {
        color: #cc0000;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 3px;
        font-size: 0.8rem;
    }

    .error-title {
        font-size: clamp(2.5rem, 6vw, 5rem);
        font-weight: 900;
        color: #1a1a1a;
        letter-spacing: -2px;
        line-height: 1;
        margin-bottom: 24px;
    }

    .error-title span {
        color: #cc0000;
    }

    .error-desc {
        font-size: 1.05rem;
        color: #666;
        line-height: 1.75;
        margin-bottom: 40px;
        max-width: 520px;
    }

    .error-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 40px;
    }

    .btn-error-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: #cc0000;
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 14px 26px;
        border-radius: 40px;
        border: 2px solid #cc0000;
        transition: all 0.25s;
    }

    .btn-error-primary:hover {
        background-color: #8b0000;
        border-color: #8b0000;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-error-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: transparent;
        color: #1a1a1a;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 14px 26px;
        border-radius: 40px;
        border: 2px solid #1a1a1a;
        transition: all 0.25s;
    }

    .btn-error-outline:hover {
        background-color: #1a1a1a;
        color: #fff;
        transform: translateY(-2px);
    }

    .error-suggest {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .error-suggest-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .error-suggest-list {
        display: flex;
        flex-direction: column;
    }

    .error-suggest-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        text-decoration: none;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.25s;
    }

    .error-suggest-link:last-child {
        border-bottom: none;
    }

    .error-suggest-link:hover {
        padding-left: 12px;
    }

    .error-suggest-link .label {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 0.9rem;
        transition: color 0.25s;
    }

    .error-suggest-link:hover .label {
        color: #cc0000;
    }

    .error-suggest-link .arrow {
        color: #999;
        transition: all 0.25s;
    }

    .error-suggest-link:hover .arrow {
        color: #cc0000;
        transform: translateX(3px);
    }
</style>

<div class="error-page">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row align-items-center g-5">

            <div class="col-lg-7 error-content">
                <div class="error-badge">
                    <hr>
                    <span class="error-badge-text">Error 404</span>
                </div>

                <h1 class="error-title">
                    <?= $locale === 'en'
                        ? 'This page is <span>not ready yet.</span>'
                        : 'Halaman ini <span>belum tersedia.</span>'; ?>
                </h1>

                <p class="error-desc">
                    <?= $locale === 'en'
                        ? 'The page you are looking for is still being prepared by our team. Please come back later or explore other sections of BAMUSI.'
                        : 'Halaman yang Anda cari sedang kami siapkan. Silakan kembali lagi nanti atau jelajahi bagian lain dari BAMUSI.'; ?>
                </p>

                <div class="error-actions">
                    <a href="<?= base_url('/'); ?>" class="btn-error-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <?= $locale === 'en' ? 'Back to Home' : 'Kembali ke Beranda'; ?>
                    </a>

                    <a href="javascript:history.back()" class="btn-error-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <?= $locale === 'en' ? 'Go Back' : 'Halaman Sebelumnya'; ?>
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="error-suggest">
                    <div class="error-suggest-title">
                        <?= $locale === 'en' ? 'You might be looking for' : 'Mungkin Anda mencari'; ?>
                    </div>

                    <?php
                    $suggestPages = $db->table('pages')
                        ->where('published', 1)
                        ->where('show_in_menu', 1)
                        ->orderBy('sort_order', 'ASC')
                        ->limit(5)
                        ->get()->getResultArray();
                    ?>

                    <div class="error-suggest-list">
                        <?php foreach ($suggestPages as $p):
                            $label = $locale === 'en' && !empty($p['menu_label_en'])
                                ? $p['menu_label_en']
                                : ($p['menu_label'] ?: ($locale === 'en' && !empty($p['title_en']) ? $p['title_en'] : $p['title']));
                        ?>
                            <a href="<?= base_url($p['slug']); ?>" class="error-suggest-link">
                                <span class="label"><?= esc($label); ?></span>
                                <span class="arrow">→</span>
                            </a>
                        <?php endforeach; ?>

                        <?php if (empty($suggestPages)): ?>
                            <p class="text-muted small mb-0">
                                <?= $locale === 'en' ? 'No pages available.' : 'Belum ada halaman lain.'; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>
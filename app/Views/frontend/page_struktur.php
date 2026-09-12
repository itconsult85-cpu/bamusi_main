<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php
$locale = $locale ?? 'id';
$t = function ($array, $field) use ($locale) {
    if ($locale === 'en' && !empty($array[$field . '_en'])) return $array[$field . '_en'];
    return $array[$field] ?? '';
};

$title    = $t($page, 'title');
$kicker   = $page['header_kicker'] ?? '';
$headTitle = $page['header_title'] ?: $title;
$headIntro = $page['header_intro'] ?: $t($page, 'excerpt');
$showIntro = (int)($page['header_show_intro'] ?? 1);
?>

<style>
    /* ===== FORCE NAVBAR SOLID ===== */
    nav.navbar,
    header.site-header,
    body>nav,
    body>header {
        background-color: #8b0000 !important;
    }

    nav.navbar .nav-link,
    header.site-header .nav-link,
    body>nav .nav-link,
    body>header .nav-link,
    nav.navbar .navbar-brand,
    body>nav .navbar-brand {
        color: #ffffff !important;
    }

    /* ===== PAGE ===== */
    .pengurus-page {
        background: #f0f0f0;
        padding: 50px 0;
    }

    /* ===== GROUP TITLE (seperti PDI) ===== */
    .group-title {
        font-size: 1.9rem;
        font-weight: 800;
        color: #1a1a1a;
        margin: 60px 0 24px;
        padding-bottom: 16px;
        border-bottom: 3px solid #cc0000;
        letter-spacing: -0.5px;
    }

    .group-title:first-of-type {
        margin-top: 0;
    }

    /* ===== GRID ===== */
    .member-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .member-row.single {
        grid-template-columns: repeat(3, 1fr);
        /* tetap 3 kolom */
    }

    .member-row.leader-row {
        grid-template-columns: minmax(280px, 620px);
        margin-bottom: 18px;
    }

    .deputies-wrap {
        margin: 0 0 12px 34px;
        padding: 20px 0 8px 24px;
        border-left: 3px solid #e2b2b2;
        position: relative;
    }

    .deputies-wrap::before {
        content: '';
        position: absolute;
        left: -3px;
        top: 0;
        width: 28px;
        border-top: 3px solid #e2b2b2;
    }

    .deputies-label {
        color: #8b0000;
        font-size: .75rem;
        font-weight: 800;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    /* ===== CARD HORIZONTAL (seperti PDI) ===== */
    .member-card {
        background: #ffffff;
        display: flex;
        align-items: stretch;
        min-height: 150px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        transition: all 0.25s ease;
        border-left: 4px solid #cc0000;
        /* Radius halus */
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
    }

    .member-card:hover {
        box-shadow: 0 6px 20px rgba(204, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    /* FOTO - porsi lebih besar, portrait */
    .member-photo {
        width: 130px;
        flex-shrink: 0;
        background: #cc0000;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .member-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
    }

    .member-photo .initial {
        color: #fff;
        font-size: 3rem;
        font-weight: 900;
        opacity: 0.9;
    }

    /* INFO - porsi teks */
    .member-info {
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
        min-width: 0;
    }

    .member-name {
        font-size: 0.95rem;
        font-weight: 800;
        color: #cc0000;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        line-height: 1.35;
        margin-bottom: 6px;
    }

    .member-role {
        font-size: 0.72rem;
        color: #555;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.8px;
        line-height: 1.4;
    }

    .member-note {
        font-size: 0.7rem;
        color: #999;
        margin-top: 4px;
        font-style: italic;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .member-photo {
            width: 110px;
        }

        .member-name {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 992px) {
        .member-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .member-row {
            grid-template-columns: 1fr;
        }

        .member-photo {
            width: 120px;
        }
    }
</style>

<!-- ===== HEADER HALAMAN (merah) ===== -->
<section class="page-header position-relative overflow-hidden"
    style="background: linear-gradient(135deg, #8a0000 0%, #4a0000 50%, #200000 100%); padding: 140px 0 80px;">
    <div class="position-absolute" style="top: -20%; right: -10%; width: 40vw; height: 40vw;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 70%);
        border-radius: 50%; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 position-relative" style="z-index: 1;">
        <?php if ($kicker): ?>
            <span class="text-uppercase fw-bold d-block mb-3"
                style="color: #ff9999; letter-spacing: 3px; font-size: 0.8rem;">
                <?= esc($kicker); ?>
            </span>
        <?php endif; ?>

        <h1 class="fw-bolder text-white mb-3"
            style="font-size: clamp(2rem, 4.5vw, 3.5rem); letter-spacing: -1.5px; line-height: 1.1;">
            <?= esc($headTitle); ?>
        </h1>

        <?php if ($showIntro && $headIntro): ?>
            <p class="text-white opacity-75 fs-6 mb-0" style="line-height: 1.6; max-width: 800px;">
                <?= esc($headIntro); ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<!-- ===== DAFTAR PENGURUS ===== -->
<div class="pengurus-page">
    <div class="container-fluid px-4 px-lg-5">

        <?php if (!empty($groups)): ?>
            <?php foreach ($groups as $group):
                $leader   = $group['leader'] ?? null;
                $members  = $group['deputies'] ?? ($group['members'] ?? []);
                $count    = count($members);
            ?>
                <h2 class="group-title">
                    <?= esc($locale === 'en' ? $group['name_en'] : $group['name']); ?>
                </h2>

                <?php if ($leader): ?>
                    <div class="member-row leader-row">
                        <?php $m = $leader;
                        $photo = trim((string)($m['photo_url'] ?? ''));
                        if ($photo !== '' && !preg_match('#^https?://#i', $photo)) {
                            $photo = base_url(ltrim($photo, '/'));
                        }
                        ?>
                        <div class="member-card">
                            <div class="member-photo">
                                <?php if ($photo !== ''): ?>
                                    <img src="<?= esc($photo); ?>" alt="<?= esc($m['name']); ?>">
                                <?php else: ?>
                                    <span class="initial"><?= esc(strtoupper(substr($m['name'], 0, 1))); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="member-info">
                                <div class="member-name"><?= esc($m['name']); ?></div>
                                <div class="member-role"><?= esc($t($m, 'role')); ?></div>
                                <?php if (!empty($m['note'])): ?>
                                    <div class="member-note"><?= esc($t($m, 'note')); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($count > 0): ?>
                    <div class="<?= $leader ? 'deputies-wrap' : ''; ?>">
                        <?php if ($leader): ?><div class="deputies-label"><?= $locale === 'en' ? 'Deputy positions' : 'Jabatan wakil'; ?></div><?php endif; ?>
                        <div class="member-row">
                            <?php foreach ($members as $m):
                                $photo = trim((string)($m['photo_url'] ?? ''));
                                if ($photo !== '' && !preg_match('#^https?://#i', $photo)) {
                                    $photo = base_url(ltrim($photo, '/'));
                                }
                            ?>
                                <div class="member-card">
                                    <div class="member-photo">
                                        <?php if ($photo !== ''): ?>
                                            <img src="<?= esc($photo); ?>" alt="<?= esc($m['name']); ?>">
                                        <?php else: ?>
                                            <span class="initial"><?= esc(strtoupper(substr($m['name'], 0, 1))); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="member-info">
                                        <div class="member-name"><?= esc($m['name']); ?></div>
                                        <div class="member-role"><?= esc($t($m, 'role')); ?></div>
                                        <?php if (!empty($m['note'])): ?><div class="member-note"><?= esc($t($m, 'note')); ?></div><?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">Belum ada data pengurus.</div>
        <?php endif; ?>

        <!-- Tombol kembali di bawah -->
        <div class="text-center mt-5 pt-4">
            <a href="<?= base_url('/'); ?>"
                class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold text-uppercase"
                style="letter-spacing: 1px; font-size: 0.85rem; border-width: 2px;">
                ← <?= $locale === 'en' ? 'Back to Home' : 'Kembali ke Beranda'; ?>
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>

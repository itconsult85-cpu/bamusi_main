<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php
$locale = $locale ?? 'id';
$t = function ($array, $field) use ($locale) {
    if ($locale === 'en' && !empty($array[$field . '_en'])) return $array[$field . '_en'];
    return $array[$field] ?? '';
};
?>

<style>
    .pengurus-page {
        background: #f5f5f5;
        padding: 60px 0;
    }

    .pengurus-page .page-title {
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        font-weight: 800;
        color: #1a1a1a;
        letter-spacing: -1.5px;
        margin-bottom: 12px;
    }

    .pengurus-page .page-title::after {
        content: "";
        display: block;
        width: 80px;
        height: 4px;
        background: #cc0000;
        margin-top: 16px;
    }

    .group-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a1a1a;
        margin: 60px 0 20px;
        padding-bottom: 14px;
        border-bottom: 3px solid #cc0000;
        letter-spacing: -0.5px;
    }

    .group-title:first-of-type {
        margin-top: 20px;
    }

    /* Card horizontal (single) */
    .member-card {
        background: #fff;
        border-left: 5px solid #cc0000;
        display: flex;
        align-items: stretch;
        min-height: 180px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .member-card:hover {
        box-shadow: 0 8px 24px rgba(204, 0, 0, 0.12);
        transform: translateY(-3px);
    }

    .member-photo {
        width: 180px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .member-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 20%;
    }

    .member-photo .initial {
        color: #fff;
        font-size: 4rem;
        font-weight: 900;
    }

    .member-info {
        padding: 24px 28px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
    }

    .member-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: #cc0000;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .member-role {
        font-size: 0.85rem;
        color: #555;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .member-note {
        font-size: 0.8rem;
        color: #888;
        margin-top: 8px;
        font-style: italic;
    }

    /* Grid untuk >1 anggota */
    .member-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .member-grid .member-card {
        flex-direction: column;
        min-height: auto;
    }

    .member-grid .member-photo {
        width: 100%;
        height: 220px;
    }

    .member-grid .member-info {
        text-align: center;
    }

    .member-single .member-card {
        max-width: 520px;
    }

    @media (max-width: 992px) {
        .member-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .member-photo {
            width: 140px;
        }
    }

    @media (max-width: 576px) {
        .member-grid {
            grid-template-columns: 1fr;
        }

        .member-card {
            flex-direction: column;
        }

        .member-photo {
            width: 100%;
            height: 220px;
        }

        .member-info {
            text-align: center;
        }
    }
</style>

<div class="pengurus-page">
    <div class="container-fluid px-4 px-lg-5">

        <h1 class="page-title">Susunan Pengurus</h1>
        <p class="text-muted mb-0" style="font-size: 1.05rem;">
            Struktur kepengurusan BAMUSI
        </p>

        <?php if (!empty($groups)): ?>
            <?php foreach ($groups as $group):
                $count = count($group['members']);
                $isSingle = ($count === 1);
            ?>
                <h2 class="group-title">
                    <?= esc($locale === 'en' ? $group['name_en'] : $group['name']); ?>
                </h2>

                <?php if ($isSingle): ?>
                    <div class="row member-single">
                        <div class="col-lg-6 col-md-8">
                            <?php
                            $m = $group['members'][0];
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
                    </div>
                <?php else: ?>
                    <div class="member-grid">
                        <?php foreach ($group['members'] as $m):
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
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info mt-5">Belum ada data pengurus.</div>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection(); ?>
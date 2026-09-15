<!DOCTYPE html>
<?php
// =========================================================
// AUTO-BUILD NAV MENU
// =========================================================
if (!isset($navMenu)) {
    $dbAuto = \Config\Database::connect();
    $all = $dbAuto->table('pages')
        ->where('published', 1)
        ->where('show_in_menu', 1)
        ->orderBy('sort_order', 'ASC')
        ->get()->getResultArray();

    $parents = [];
    $children = [];
    foreach ($all as $row) {
        if (empty($row['parent_id'])) {
            $parents[$row['id']] = $row;
            $parents[$row['id']]['children'] = [];
        } else {
            $children[$row['parent_id']][] = $row;
        }
    }
    foreach ($children as $parentId => $kids) {
        if (isset($parents[$parentId])) $parents[$parentId]['children'] = $kids;
    }
    $navMenu = array_values($parents);
}

// =========================================================
// AUTO-LOAD SETTINGS
// =========================================================
// Auto-load settings
if (!isset($settings)) {
    $db = \Config\Database::connect();
    $settingsRaw = $db->table('site_settings')->get()->getResultArray();
    $settings = [];
    foreach ($settingsRaw as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
        if (!empty($row['setting_value_en'])) {
            $settings[$row['setting_key'] . '_en'] = $row['setting_value_en'];
        }
    }
}

// Locale
$locale = session()->get('lang') ?? 'id';
$isHome = (current_url() === base_url('/') || current_url() === base_url());

// =========================================================
// HELPER: ambil setting dengan fallback EN/ID
// =========================================================
$s = function ($key, $default = '') use ($settings, $locale) {
    $keyEn = $key . '_en';
    if ($locale === 'en' && !empty($settings[$keyEn])) return $settings[$keyEn];
    return $settings[$key] ?? $default;
};

// =========================================================
// HELPER: bangun URL gambar dari setting
// =========================================================
$imgUrl = function ($key, $fallback = '') use ($settings) {
    $val = trim((string)($settings[$key] ?? ''));
    if ($val === '') $val = $fallback;
    if ($val === '') return '';
    if (preg_match('#^https?://#i', $val)) return $val;
    return base_url(ltrim($val, '/'));
};

// Logo brand & partner (dipakai di navbar + footer)
$brandLogoUrl   = $imgUrl('brand_logo_url', 'assets/images/bamusi-logo-transparent.png');
$partnerLogoUrl = $imgUrl('partner_logo_url', 'assets/images/pdi.png');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($texts['global.brand_name'] ?? $settings['global.brand_name'] ?? 'Baitul Muslimin Indonesia'); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bamusi.css'); ?>">

    <style>
        /* =========================================================
           NAVBAR — SELALU SOLID DI HALAMAN DALAM
           ========================================================= */
        body:not(.is-home) .navbar-custom,
        body.indeks-open .navbar-custom,
        body.mega-open .navbar-custom {
            background-color: var(--bamusi-dark, #8b0000) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding-top: 10px;
            padding-bottom: 10px;
        }

        #mainNavbar {
            z-index: 1050 !important;
        }

        /* =========================================================
           NAVBAR — DROPDOWN KECIL
           ========================================================= */
        .navbar-custom .nav-item {
            position: relative;
        }

        .navbar-custom .nav-link {
            position: relative;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .navbar-custom .caret {
            font-size: 0.7rem;
            transition: transform 0.25s;
        }

        .dropdown-small {
            position: relative;
        }

        .dropdown-small .dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 240px;
            background: #ffffff;
            list-style: none;
            padding: 8px 0;
            margin: 0;
            border-radius: 6px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
            z-index: 1100;
            pointer-events: none;
        }

        .dropdown-small:hover .dropdown-list {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .dropdown-small .dropdown-list li a {
            display: block;
            padding: 10px 20px;
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .dropdown-small .dropdown-list li a:hover {
            background: #fdf5f5;
            border-left-color: #cc0000;
            color: #cc0000;
            padding-left: 24px;
        }

        .dropdown-small:hover>.nav-link,
        .nav-has-mega:hover>.nav-link {
            color: #cc0000 !important;
        }

        .dropdown-small:hover .caret,
        .nav-has-mega:hover .caret {
            transform: rotate(180deg);
        }

        /* =========================================================
           MEGA MENU
           ========================================================= */
        .nav-has-mega {
            position: static;
        }

        .mega-panel {
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            background: #ffffff;
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-16px);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
            padding: 40px 0 36px;
            max-height: calc(100vh - 80px);
            overflow-y: auto;
            pointer-events: none;
        }

        .mega-panel.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* =========================================================
           INDEKS KANAL PANEL
           ========================================================= */
        .indeks-panel {
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            background: #ffffff;
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-16px);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
            max-height: calc(100vh - 80px);
            overflow-y: auto;
            padding: 40px 0 36px;
            pointer-events: none;
        }

        .indeks-panel.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* =========================================================
           SHARED — ISI PANEL (3 KOLOM)
           ========================================================= */
        .indeks-wrap {
            display: grid;
            grid-template-columns: 1.1fr 1fr 1.2fr;
            gap: 0;
            align-items: start;
        }

        .indeks-col {
            padding: 0 36px;
            min-height: 220px;
        }

        .indeks-col+.indeks-col {
            border-left: 1px solid #e5e5e5;
        }

        .indeks-panel-close {
            position: absolute;
            top: 20px;
            right: 24px;
            background: #f5f5f5;
            border: none;
            color: #333;
            cursor: pointer;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s;
            z-index: 10;
        }

        .indeks-panel-close:hover {
            background: #cc0000;
            color: #fff;
            transform: rotate(90deg);
        }

        .indeks-hero h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1a1a1a;
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .indeks-hero h2 span {
            color: #cc0000;
        }

        .indeks-hero p {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .indeks-hero .home-link {
            font-weight: 800;
            color: #1a1a1a;
            text-decoration: none;
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 3px;
            transition: all 0.25s;
        }

        .indeks-hero .home-link:hover {
            color: #cc0000;
            border-bottom-color: #cc0000;
        }

        .indeks-main-list {
            display: flex;
            flex-direction: column;
        }

        .indeks-main-list .indeks-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            text-decoration: none;
            border-bottom: 1px solid #ececec;
            transition: all 0.25s;
        }

        .indeks-main-list .indeks-item:last-child {
            border-bottom: none;
        }

        .indeks-main-list .indeks-item-title {
            font-weight: 700;
            color: #1a1a1a;
            font-size: 0.9rem;
            transition: color 0.25s;
        }

        .indeks-main-list .indeks-item:hover .indeks-item-title {
            color: #cc0000;
        }

        .indeks-main-list .indeks-item-arrow {
            color: #999;
            font-size: 0.85rem;
            transition: all 0.25s;
        }

        .indeks-main-list .indeks-item:hover .indeks-item-arrow {
            color: #cc0000;
            transform: translateX(3px);
        }

        .indeks-preview-title {
            font-size: 0.7rem;
            font-weight: 800;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 14px;
        }

        .indeks-preview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .indeks-preview-card {
            display: block;
            text-decoration: none;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.25s;
            background: #fff;
        }

        .indeks-preview-card:hover {
            border-color: #cc0000;
            box-shadow: 0 8px 20px rgba(204, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .indeks-preview-thumb {
            width: 100%;
            height: 110px;
            background: linear-gradient(135deg, #cc0000 0%, #8b0000 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.3);
            font-size: 2.5rem;
            font-weight: 900;
            overflow: hidden;
        }

        .indeks-preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .indeks-preview-body {
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .indeks-preview-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .indeks-preview-arrow {
            color: #cc0000;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .indeks-empty {
            padding: 40px;
            text-align: center;
            color: #999;
            font-style: italic;
        }

        @media (max-width: 992px) {
            .indeks-wrap {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .indeks-col {
                padding: 0 16px;
            }

            .indeks-col+.indeks-col {
                border-left: none;
                border-top: 1px solid #e5e5e5;
                padding-top: 24px;
            }

            .indeks-preview-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .indeks-preview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="<?= $isHome ? 'is-home' : 'is-inner' ?>">

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top" id="mainNavbar">
        <div class="container-fluid px-4 px-lg-5">

            <a class="navbar-brand" href="<?= base_url(); ?>">
                <img src="<?= esc($brandLogoUrl); ?>"
                    onerror="this.src='https://placehold.co/50x50/111111/ffffff?text=B'"
                    alt="Logo"
                    style="width: 50px; height: 50px; object-fit: contain;">
                <div class="brand-text-container">
                    <span class="brand-title">
                        <?= esc($texts['global.brand_short'] ?? $settings['global.brand_short'] ?? 'BAMUSI'); ?>
                    </span>
                    <div class="brand-line"></div>
                    <span class="brand-subtitle">
                        <?= esc($texts['global.brand_subtitle'] ?? $settings['global.brand_subtitle'] ?? 'Baitul Muslimin Indonesia'); ?>
                    </span>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="mainNav">
                <ul class="navbar-nav gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/'); ?>">
                            <?= esc($s('nav.home_label', 'Beranda')); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('artikel'); ?>">
                            <?= esc($locale === 'en' ? 'Articles' : 'Artikel'); ?>
                        </a>
                    </li>

                    <?php foreach ($navMenu ?? [] as $item):
                        $hasChildren = !empty($item['children']);
                        $isMega      = !empty($item['is_mega']) && $hasChildren;

                        $label = '';
                        if ($locale === 'en' && !empty($item['menu_label_en'])) $label = $item['menu_label_en'];
                        elseif (!empty($item['menu_label'])) $label = $item['menu_label'];
                        elseif ($locale === 'en' && !empty($item['title_en'])) $label = $item['title_en'];
                        else $label = $item['title'];
                    ?>
                        <?php if (!$hasChildren): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url($item['slug']); ?>">
                                    <?= esc($label); ?>
                                </a>
                            </li>
                        <?php elseif ($isMega): ?>
                            <li class="nav-item nav-has-mega">
                                <a class="nav-link" href="<?= base_url($item['slug']); ?>"
                                    data-mega-id="<?= $item['id']; ?>">
                                    <?= esc($label); ?> <span class="caret">▾</span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown-small">
                                <a class="nav-link" href="<?= base_url($item['slug']); ?>">
                                    <?= esc($label); ?> <span class="caret">▾</span>
                                </a>
                                <ul class="dropdown-list">
                                    <?php foreach ($item['children'] as $child):
                                        $childLabel = '';
                                        if ($locale === 'en' && !empty($child['menu_label_en'])) $childLabel = $child['menu_label_en'];
                                        elseif (!empty($child['menu_label'])) $childLabel = $child['menu_label'];
                                        elseif ($locale === 'en' && !empty($child['title_en'])) $childLabel = $child['title_en'];
                                        else $childLabel = $child['title'];
                                    ?>
                                        <li>
                                            <a href="<?= base_url($child['slug']); ?>">
                                                <?= esc($childLabel); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="nav-right-actions d-none d-lg-flex">
                <div class="lang-switch text-white">
                    <?php $currentLang = session()->get('lang') ?? 'id'; ?>
                    <a href="<?= base_url('lang/id'); ?>"
                        class="text-decoration-none <?= $currentLang === 'id' ? 'text-white fw-bold border-bottom border-2 border-white pb-1' : 'text-white opacity-50'; ?>">
                        IND
                    </a>
                    <span class="text-white opacity-50 mx-1">/</span>
                    <a href="<?= base_url('lang/en'); ?>"
                        class="text-decoration-none <?= $currentLang === 'en' ? 'text-white fw-bold border-bottom border-2 border-white pb-1' : 'text-white opacity-50'; ?>">
                        ENG
                    </a>
                </div>
                <button class="menu-trigger" id="indeksToggle" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                    </svg>
                    <?= esc($s('index_panel.kicker', 'Indeks Kanal')); ?>
                </button>
            </div>

        </div>
    </nav>

    <?php foreach ($navMenu ?? [] as $item):
        if (empty($item['is_mega']) || empty($item['children'])) continue;

        $parentLabel = '';
        if ($locale === 'en' && !empty($item['menu_label_en'])) $parentLabel = $item['menu_label_en'];
        elseif (!empty($item['menu_label'])) $parentLabel = $item['menu_label'];
        else $parentLabel = $item['title'];
    ?>
        <div class="mega-panel" data-mega-for="<?= $item['id']; ?>">
            <button class="indeks-panel-close" type="button" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <div class="container-fluid px-4 px-lg-5">
                <div class="indeks-wrap">

                    <div class="indeks-col indeks-hero">
                        <h2><?= esc($parentLabel); ?></h2>
                        <p>
                            <?= esc($locale === 'en'
                                ? ($item['menu_desc_en'] ?? 'Explore this section to learn more.')
                                : ($item['menu_desc'] ?? 'Jelajahi bagian ini untuk informasi lebih lanjut.')); ?>
                        </p>
                        <a href="<?= base_url($item['slug']); ?>" class="home-link">
                            <?= esc($s('mega.view_all', 'Lihat Semua')); ?>
                        </a>
                    </div>

                    <div class="indeks-col">
                        <div class="indeks-preview-title">
                            <?= esc($s('mega.sub_pages', 'Sub Halaman')); ?>
                        </div>
                        <div class="indeks-main-list">
                            <?php foreach ($item['children'] as $child):
                                $childLabel = '';
                                if ($locale === 'en' && !empty($child['menu_label_en'])) $childLabel = $child['menu_label_en'];
                                elseif (!empty($child['menu_label'])) $childLabel = $child['menu_label'];
                                else $childLabel = $child['title'];
                            ?>
                                <a href="<?= base_url($child['slug']); ?>" class="indeks-item">
                                    <span class="indeks-item-title"><?= esc($childLabel); ?></span>
                                    <span class="indeks-item-arrow">›</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="indeks-col">
                        <div class="indeks-preview-title">
                            <?= esc($s('mega.quick_access', 'Akses Cepat')); ?>
                        </div>
                        <div class="indeks-preview-grid">
                            <?php foreach (array_slice($item['children'], 0, 4) as $child):
                                $childLabel = '';
                                if ($locale === 'en' && !empty($child['menu_label_en'])) $childLabel = $child['menu_label_en'];
                                elseif (!empty($child['menu_label'])) $childLabel = $child['menu_label'];
                                else $childLabel = $child['title'];

                                $img = trim((string)($child['image_url'] ?? ''));
                                if ($img && !preg_match('#^https?://#i', $img)) $img = base_url(ltrim($img, '/'));
                            ?>
                                <a href="<?= base_url($child['slug']); ?>" class="indeks-preview-card">
                                    <div class="indeks-preview-thumb">
                                        <?php if ($img): ?>
                                            <img src="<?= esc($img); ?>" alt="<?= esc($childLabel); ?>">
                                        <?php else: ?>
                                            <?= esc(strtoupper(substr($childLabel, 0, 1))); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="indeks-preview-body">
                                        <span class="indeks-preview-label"><?= esc($childLabel); ?></span>
                                        <span class="indeks-preview-arrow">›</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="indeks-panel" id="indeksPanel">
        <button class="indeks-panel-close" id="indeksClose" type="button" aria-label="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="container-fluid px-4 px-lg-5">
            <?php
            $dbIdx = \Config\Database::connect();
            $navPages = $dbIdx->table('pages')
                ->where('published', 1)
                ->where('show_in_menu', 1)
                ->orderBy('sort_order', 'ASC')
                ->get()->getResultArray();

            $mainLinks    = array_slice($navPages, 0, 4);
            $previewLinks = array_slice($navPages, 0, 4);
            ?>

            <div class="indeks-wrap">

                <div class="indeks-col indeks-hero">
                    <h2><?= $s('index_panel.title', 'Jelajahi setiap <span>halaman.</span>'); ?></h2>
                    <p><?= esc($s('index_panel.desc', 'Temukan kisah lengkap BAMUSI — sejarah, para pengurus, dan komitmen kami untuk bangsa.')); ?></p>
                    <a href="<?= base_url('/'); ?>" class="home-link">
                        <?= esc($s('index_panel.button', 'Selengkapnya')); ?> ↗
                    </a>
                </div>

                <div class="indeks-col">
                    <div class="indeks-main-list">
                        <?php foreach ($mainLinks as $item):
                            $navLabel = '';
                            if ($locale === 'en' && !empty($item['menu_label_en'])) $navLabel = $item['menu_label_en'];
                            elseif (!empty($item['menu_label'])) $navLabel = $item['menu_label'];
                            elseif ($locale === 'en' && !empty($item['title_en'])) $navLabel = $item['title_en'];
                            else $navLabel = $item['title'];
                        ?>
                            <a href="<?= base_url($item['slug']); ?>" class="indeks-item">
                                <span class="indeks-item-title"><?= esc($navLabel); ?></span>
                                <span class="indeks-item-arrow">›</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="indeks-col">
                    <div class="indeks-preview-title">
                        <span><?= esc($s('mega.quick_access', 'Akses Cepat')); ?></span>
                    </div>

                    <?php if (!empty($previewLinks)): ?>
                        <div class="indeks-preview-grid">
                            <?php foreach ($previewLinks as $item):
                                $navLabel = '';
                                if ($locale === 'en' && !empty($item['menu_label_en'])) $navLabel = $item['menu_label_en'];
                                elseif (!empty($item['menu_label'])) $navLabel = $item['menu_label'];
                                elseif ($locale === 'en' && !empty($item['title_en'])) $navLabel = $item['title_en'];
                                else $navLabel = $item['title'];

                                $img = trim((string)($item['image_url'] ?? ''));
                                if ($img && !preg_match('#^https?://#i', $img)) $img = base_url(ltrim($img, '/'));
                            ?>
                                <a href="<?= base_url($item['slug']); ?>" class="indeks-preview-card">
                                    <div class="indeks-preview-thumb">
                                        <?php if ($img): ?>
                                            <img src="<?= esc($img); ?>" alt="<?= esc($navLabel); ?>">
                                        <?php else: ?>
                                            <?= esc(strtoupper(substr($navLabel, 0, 1))); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="indeks-preview-body">
                                        <span class="indeks-preview-label"><?= esc($navLabel); ?></span>
                                        <span class="indeks-preview-arrow">›</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="indeks-empty">
                            <?= $locale === 'en' ? 'No pages yet.' : 'Belum ada halaman.'; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <main>
        <?= $this->renderSection('content'); ?>
    </main>

    <footer class="pt-5 pb-4 position-relative text-white"
        style="background: radial-gradient(ellipse at 100% 0%, #2b0000 0%, #0a0a0a 50%, #000000 100%); overflow: hidden;">

        <div class="position-absolute top-0 start-0 w-100"
            style="height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);"></div>

        <div class="container-fluid px-4 px-lg-5 pt-5 position-relative" style="z-index: 1;">

            <div class="row g-5 mb-4">
                <div class="col-lg-5 pe-lg-5">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <?php
                        $partnerLink = trim((string)($settings['partner_url'] ?? '')) ?: 'https://pdiperjuangan.id/';
                        $partnerName = trim((string)($settings['partner_name'] ?? '')) ?: 'PDI Perjuangan';
                        ?>
                        <a href="<?= esc($partnerLink); ?>" target="_blank" rel="noopener noreferrer"
                            title="<?= esc($partnerName); ?>" style="display: inline-block; transition: opacity 0.25s;"
                            onmouseover="this.style.opacity='0.8';" onmouseout="this.style.opacity='1';">
                            <img src="<?= esc($partnerLogoUrl); ?>" alt="<?= esc($partnerName); ?>"
                                style="height: 65px; object-fit: contain;">
                        </a>
                        <div style="width: 1px; height: 50px; background-color: rgba(255,255,255,0.2);"></div>
                        <img src="<?= esc($brandLogoUrl); ?>" alt="Logo"
                            style="height: 65px; object-fit: contain;">
                    </div>

                    <h3 class="fw-bolder mb-3 text-white"
                        style="font-size: clamp(2rem, 3vw, 2.5rem); letter-spacing: -1px; line-height: 1.1;">
                        <?= ($locale === 'en' && !empty($settings['footer.heading_en']))
                            ? $settings['footer.heading_en']
                            : ($settings['footer.heading'] ?? 'Kantor Pengurus<br>Pusat BAMUSI'); ?>
                    </h3>
                    <p class="fs-6 opacity-75 mb-0" style="max-width: 400px; font-weight: 300; line-height: 1.6;">
                        <?= esc($s('footer.tagline', 'Islam Nusantara yang berkemajuan untuk Indonesia Raya.')); ?>
                    </p>
                </div>

                <div class="col-lg-7 d-flex flex-column">
                    <div class="row g-5 mb-auto">
                        <div class="col-md-6">
                            <strong class="d-block text-uppercase mb-3"
                                style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.85rem;">
                                <?= $locale === 'en' ? 'Address' : 'Alamat'; ?>
                            </strong>
                            <p class="opacity-75 lh-base mb-3" style="font-size: 0.95rem; font-weight: 300;">
                                <?= $settings['contact_address_short'] ?? 'Jl. Kalibata Tengah, Kalibata, Kec. Pancoran,<br>Kota Jakarta Selatan, DKI Jakarta 12740'; ?>
                            </p>

                            <?php if (!empty($settings['contact_maps_url'])): ?>
                                <a href="<?= esc($settings['contact_maps_url']); ?>"
                                    target="_blank" rel="noopener noreferrer"
                                    class="d-inline-flex align-items-center gap-2 text-decoration-none"
                                    style="color: #ffffff; font-weight: 700; font-size: 0.8rem;
                                           letter-spacing: 1px; text-transform: uppercase;
                                           border-bottom: 2px solid rgba(255,255,255,0.4);
                                           padding-bottom: 4px; transition: all 0.25s ease;"
                                    onmouseover="this.style.color='#cc0000'; this.style.borderBottomColor='#cc0000';"
                                    onmouseout="this.style.color='#ffffff'; this.style.borderBottomColor='rgba(255,255,255,0.4)';">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <?= esc($s('contact_maps_label', 'Buka di Google Maps')); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong class="d-block text-uppercase mb-3"
                                style="color: var(--bamusi-red, #cc0000); letter-spacing: 2px; font-size: 0.85rem;">
                                <?= $locale === 'en' ? 'Contact Us' : 'Kontak Kami'; ?>
                            </strong>
                            <p class="opacity-75 lh-base mb-0" style="font-size: 0.95rem; font-weight: 300;">
                                WhatsApp Admin<br>
                                <a href="<?= esc($settings['whatsapp_url'] ?? '#'); ?>"
                                    class="text-white text-decoration-none fw-bold"
                                    style="transition: color 0.3s;"
                                    onmouseover="this.style.color='var(--bamusi-red, #cc0000)';"
                                    onmouseout="this.style.color='white';">
                                    <?= esc($settings['whatsapp_label'] ?? '+62 878 9262 7144'); ?>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mt-5 pt-4 border-top"
                        style="border-color: rgba(255,255,255,0.1) !important;">
                        <div class="d-flex gap-4 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
                            <a href="<?= esc($settings['instagram_url'] ?? '#'); ?>" target="_blank"
                                class="text-white text-decoration-none opacity-75"
                                style="transition: opacity 0.3s;"
                                onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0.75';">Instagram</a>
                            <a href="<?= esc($settings['tiktok_url'] ?? '#'); ?>" target="_blank"
                                class="text-white text-decoration-none opacity-75"
                                style="transition: opacity 0.3s;"
                                onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0.75';">TikTok</a>
                            <a href="<?= esc($settings['youtube_url'] ?? '#'); ?>" target="_blank"
                                class="text-white text-decoration-none opacity-75"
                                style="transition: opacity 0.3s;"
                                onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0.75';">YouTube</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                <span class="opacity-50" style="font-size: 0.85rem; font-weight: 300;">
                    &copy; <?= date('Y'); ?> <?= esc(($locale === 'en' && !empty($settings['footer.copyright_en']))
                                                    ? $settings['footer.copyright_en']
                                                    : ($settings['footer.copyright'] ?? 'Baitul Muslimin Indonesia. Hak cipta dilindungi.')); ?>
                </span>
            </div>

        </div>
    </footer>

    <a href="#" id="backToTopBtn"
        class="rounded-circle d-flex align-items-center justify-content-center text-white text-decoration-none shadow"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;"
        style="position: fixed; bottom: 30px; right: 30px; width: 55px; height: 55px;
               background-color: var(--bamusi-red, #cc0000); z-index: 1050;
               opacity: 0; visibility: hidden; transform: translateY(20px);
               transition: all 0.3s ease;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="19" x2="12" y2="5"></line>
            <polyline points="5 12 12 5 19 12"></polyline>
        </svg>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.nav-has-mega').forEach(function(li) {
                const link = li.querySelector('.nav-link');
                const panelId = link?.getAttribute('data-mega-id');
                const panel = document.querySelector('.mega-panel[data-mega-for="' + panelId + '"]');
                if (!panel) return;

                let timer = null;

                function open() {
                    clearTimeout(timer);
                    document.querySelectorAll('.mega-panel.open').forEach(p => p.classList.remove('open'));
                    document.querySelectorAll('.indeks-panel.open').forEach(p => p.classList.remove('open'));
                    panel.classList.add('open');
                    document.body.classList.add('mega-open');
                }

                function close() {
                    timer = setTimeout(function() {
                        panel.classList.remove('open');
                        if (!document.querySelector('.mega-panel.open')) {
                            document.body.classList.remove('mega-open');
                        }
                    }, 250);
                }

                li.addEventListener('mouseenter', open);
                li.addEventListener('mouseleave', close);
                panel.addEventListener('mouseenter', () => clearTimeout(timer));
                panel.addEventListener('mouseleave', close);

                panel.querySelector('.indeks-panel-close')?.addEventListener('click', function() {
                    panel.classList.remove('open');
                    document.body.classList.remove('mega-open');
                });
            });

            const toggle = document.getElementById('indeksToggle');
            const panel = document.getElementById('indeksPanel');
            const closeBtn = document.getElementById('indeksClose');

            if (toggle && panel) {
                let hoverTimer = null;

                function openPanel() {
                    document.querySelectorAll('.mega-panel.open').forEach(p => p.classList.remove('open'));
                    panel.classList.add('open');
                    document.body.classList.add('indeks-open');
                }

                function closePanel() {
                    panel.classList.remove('open');
                    document.body.classList.remove('indeks-open');
                }

                function togglePanel(e) {
                    if (e) e.preventDefault();
                    if (panel.classList.contains('open')) closePanel();
                    else openPanel();
                }

                toggle.addEventListener('click', togglePanel);
                if (closeBtn) closeBtn.addEventListener('click', closePanel);

                toggle.addEventListener('mouseenter', function() {
                    if (window.innerWidth >= 992) {
                        clearTimeout(hoverTimer);
                        openPanel();
                    }
                });

                function scheduleClose() {
                    clearTimeout(hoverTimer);
                    hoverTimer = setTimeout(closePanel, 300);
                }
                toggle.addEventListener('mouseleave', scheduleClose);
                panel.addEventListener('mouseleave', scheduleClose);
                panel.addEventListener('mouseenter', () => clearTimeout(hoverTimer));

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closePanel();
                });
            }

            const backToTopBtn = document.getElementById('backToTopBtn');
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 200) {
                        backToTopBtn.style.opacity = '1';
                        backToTopBtn.style.visibility = 'visible';
                        backToTopBtn.style.transform = 'translateY(0)';
                    } else {
                        backToTopBtn.style.opacity = '0';
                        backToTopBtn.style.visibility = 'hidden';
                        backToTopBtn.style.transform = 'translateY(20px)';
                    }
                });

                backToTopBtn.addEventListener('mouseover', function() {
                    this.style.backgroundColor = '#990000';
                    this.style.transform = 'translateY(-5px)';
                });
                backToTopBtn.addEventListener('mouseout', function() {
                    this.style.backgroundColor = 'var(--bamusi-red, #cc0000)';
                    this.style.transform = 'translateY(0)';
                });
            }

            const navbar = document.getElementById('mainNavbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) navbar.classList.add('scrolled');
                    else navbar.classList.remove('scrolled');
                });
            }

            const slider = document.querySelector('.board-slider-container');
            if (slider) {
                const cardWidth = 260 + 24;

                function autoScroll() {
                    const maxScroll = slider.scrollWidth - slider.clientWidth;
                    if (slider.scrollLeft >= maxScroll - 10) {
                        slider.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        slider.scrollBy({
                            left: cardWidth,
                            behavior: 'smooth'
                        });
                    }
                }
                let slideInterval = setInterval(autoScroll, 3000);
                slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
                slider.addEventListener('mouseleave', () => slideInterval = setInterval(autoScroll, 3000));
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

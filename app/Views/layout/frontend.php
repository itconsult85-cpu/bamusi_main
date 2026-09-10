<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($texts['global.brand_name'] ?? $settings['global.brand_name'] ?? 'Baitul Muslimin Indonesia'); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bamusi.css'); ?>">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top" id="mainNavbar">
        <div class="container-fluid px-4 px-lg-5">

            <a class="navbar-brand" href="<?= base_url(); ?>">
                <img src="<?= base_url('assets/images/bamusi-logo-transparent.png'); ?>" onerror="this.src='https://placehold.co/50x50/111111/ffffff?text=B'" alt="Logo BAMUSI" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                <div class="brand-text-container">
                    <span class="brand-title"><?= esc($texts['global.brand_short'] ?? 'BAMUSI'); ?></span>
                    <div class="brand-line"></div>
                    <span class="brand-subtitle"><?= esc($texts['global.brand_name'] ?? 'Baitul Muslimin Indonesia'); ?></span>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="mainNav">
                <ul class="navbar-nav gap-2">
                    <li class="nav-item"><a class="nav-link" href="#khidmah">Ruang Khidmah</a></li>
                    <li class="nav-item"><a class="nav-link" href="#berita">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#agenda">Agenda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#bergabung">Bergabung</a></li>
                </ul>
            </div>

            <div class="nav-right-actions d-none d-lg-flex">
                <div class="lang-switch">
                    <span class="active">IND</span> / <span>ENG</span>
                </div>
                <button class="menu-trigger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                    </svg>
                    INDEKS KANAL
                </button>
            </div>

        </div>
    </nav>

    <main>
        <?= $this->renderSection('content'); ?>
    </main>

    <footer class="py-5 text-white" style="background-color: var(--bamusi-black);">
        <div class="container-fluid px-4 px-lg-5 py-4">
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-3 mt-4">
                        <img src="<?= base_url('assets/images/pdi.png'); ?>" alt="Logo PDI Perjuangan" style="height: 80px;">
                        <img src="<?= base_url('assets/images/bamusi-logo-transparent.png'); ?>" alt="Logo BAMUSI" style="height: 80px;">
                    </div>
                    <h3 class="fw-bolder mb-4" style="font-size: 2.2rem; letter-spacing: -1px;">Kantor Pengurus<br>Pusat BAMUSI</h3>
                    <p class="fs-5 opacity-75 mb-4">Islam Nusantara yang berkemajuan untuk Indonesia Raya.</p>
                </div>
                <div class="col-lg-7 d-flex flex-column justify-content-between">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <strong class="d-block text-uppercase mb-2 text-danger" style="letter-spacing:1px;">Alamat</strong>
                            <p class="opacity-75">Jl. Kalibata Tengah, Kalibata, Kec. Pancoran,<br>Kota Jakarta Selatan, DKI Jakarta 12740</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <strong class="d-block text-uppercase mb-2 text-danger" style="letter-spacing:1px;">Kontak Kami</strong>
                            <p class="opacity-75">WhatsApp Admin<br><a href="https://wa.me/6287892627144" class="text-white text-decoration-none">+62 878 9262 7144</a></p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 border-top border-secondary pt-4 mt-auto fw-bold text-uppercase" style="font-size: 0.85rem;">
                        <a href="<?= esc($settings['instagram_url'] ?? '#'); ?>" class="text-white text-decoration-none">Instagram</a>
                        <a href="<?= esc($settings['tiktok_url'] ?? '#'); ?>" class="text-white text-decoration-none">TikTok</a>
                        <a href="#" class="text-white text-decoration-none ms-auto" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">Kembali ke atas ↑</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Utama untuk Navbar dan Slider -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* 1. SCRIPT EFEK NAVBAR SCROLL */
            const navbar = document.getElementById('mainNavbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled'); // Menambahkan background merah
                    } else {
                        navbar.classList.remove('scrolled'); // Kembali transparan di atas
                    }
                });
            }

            /* 2. SCRIPT AUTO-SLIDER PENGURUS (Otomatis geser) */
            const slider = document.querySelector('.board-slider-container');
            if (slider) {
                const cardWidth = 260 + 24; // Lebar kartu (260px) + gap (1.5rem = ~24px)

                function autoScroll() {
                    const maxScroll = slider.scrollWidth - slider.clientWidth;

                    if (slider.scrollLeft >= maxScroll - 10) {
                        // Jika sudah di ujung kanan, kembali ke awal
                        slider.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        // Geser satu kartu ke kanan
                        slider.scrollBy({
                            left: cardWidth,
                            behavior: 'smooth'
                        });
                    }
                }

                // Geser setiap 3 detik
                let slideInterval = setInterval(autoScroll, 3000);

                // Jeda saat mouse diarahkan ke slider
                slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
                slider.addEventListener('mouseleave', () => slideInterval = setInterval(autoScroll, 3000));
            }
        });
    </script>
</body>

</html>
<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php
$settings = $settings ?? [];
$texts = $texts ?? [];
$aboutValues = $aboutValues ?? [];
$programs = $programs ?? [];
$agenda = $agenda ?? [];
$news = $news ?? [];
// dd($news);
$board = $board ?? [];
$partners = $partners ?? [];
?>

<!-- HERO SECTION -->
<section class="hero-split-wrapper">
    <div class="hero-split-bg-right d-none d-lg-block"></div>
    <div id="mainHeroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <?php if (!empty($slides)): ?>
                <?php foreach ($slides as $index => $slide):
                    $heroImg = !empty($slide['image_url']) ? base_url(ltrim($slide['image_url'], '/')) : base_url('uploads/reference/exact/bamusi-hero-community.jpg');
                    $title = esc($slide['title'] ?? 'Islam Nusantara yang Berkemajuan');
                    $title = !str_contains($title, '|') ? str_replace([' yang ', ' Untuk '], ['<br>yang ', '<br>Untuk '], $title) : implode('<br>', array_map('esc', explode('|', $title)));
                ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?> h-100">
                        <div class="container-fluid px-4 px-lg-5 h-100">
                            <div class="row align-items-center h-100">
                                <div class="col-lg-7 py-5">
                                    <span class="eyebrow-text text-white-50"><?= esc($texts['home.rail_label'] ?? 'PP BAMUSI / 2025—2030'); ?></span>
                                    <h2 class="fs-5 text-white mb-2"><?= esc($slide['kicker'] ?? $texts['hero_kicker'] ?? 'Mari Merawat Indonesia'); ?></h2>
                                    <h1 class="fw-bolder text-white mb-4" style="font-size: clamp(3rem, 5vw, 5.5rem); letter-spacing:-2px; text-shadow: 0 10px 30px rgba(0,0,0,0.3); line-height: 1.1;"><?= $title; ?></h1>
                                </div>
                                <div class="col-lg-5 d-none d-lg-flex hero-image-col">
                                    <div class="arched-frame">
                                        <div class="arched-frame-inner">
                                            <img src="<?= esc($heroImg); ?>" alt="BAMUSI Hero">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 1. PERKENALAN ORGANISASI (REDESAIN GAYA REFERENSI) -->
<section class="py-5 position-relative" style="background-color: #f8f9fa;" id="tentang">
    <!-- Ikon Bintang di Kanan Atas -->
    <div class="position-absolute d-none d-lg-block" style="top: 2rem; right: 4rem;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="var(--bamusi-red, #a00000)" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
        </svg>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-5 mt-4">
        <div class="row">

            <!-- Kolom Kiri: Judul & Deskripsi Utama -->
            <div class="col-lg-8 pe-lg-5 mb-5 mb-lg-0">
                <!-- Eyebrow "02" & Label -->
                <div class="d-flex align-items-start mb-5">
                    <div class="me-4">
                        <hr style="width: 35px; border-top: 3px solid var(--bamusi-red, #a00000); opacity: 1; margin: 0 0 10px 0;">
                        <span class="fw-bold" style="color: var(--bamusi-red, #a00000); font-size: 1.1rem;">02</span>
                    </div>
                    <div class="pt-1">
                        <span class="text-uppercase fw-bold" style="color: var(--bamusi-red, #a00000); letter-spacing: 2px; font-size: 0.85rem;">
                            Perkenalan Organisasi
                        </span>
                    </div>
                </div>

                <!-- Judul Utama (Merah Gelap) -->
                <h2 class="fw-bolder mb-4" style="font-size: clamp(3rem, 5vw, 4.5rem); letter-spacing: -2px; line-height: 1.1; color: #8b0000;">
                    <?= nl2br(esc($texts['reference.about_title'] ?? $settings['about_heading'] ?? 'Islam yang hadir<br>sebagai sahabat<br>kemajuan.')); ?>
                </h2>

                <!-- Teks Pengantar -->
                <p class="fs-4 lh-base text-secondary mt-4 pe-lg-4">
                    <?= esc($settings['about_intro'] ?? 'Baitul Muslimin Indonesia menghadirkan ruang temu bagi umat, warga, dan generasi baru untuk memperkuat keislaman yang ramah, kebangsaan yang kokoh, serta demokrasi yang berkeadaban.'); ?>
                </p>
            </div>

            <!-- Kolom Kanan: Ikon Arch, Teks Tambahan, & Link -->
            <div class="col-lg-4 ps-lg-5 d-flex flex-column justify-content-end">
                <div class="h-100 border-start border-2 ps-4 ps-lg-5 py-3" style="border-color: #e0e0e0 !important;">

                    <!-- Ikon Lengkungan & Titik (CSS Murni) -->
                    <div class="mb-4 position-relative d-inline-block">
                        <div style="width: 50px; height: 60px; border: 8px solid var(--bamusi-red, #a00000); border-bottom: 0; border-top-left-radius: 25px; border-top-right-radius: 25px;"></div>
                        <div style="width: 8px; height: 8px; background-color: var(--bamusi-red, #a00000); border-radius: 50%; position: absolute; top: 25px; left: 21px;"></div>
                    </div>

                    <!-- Teks Deskripsi Sekunder -->
                    <p class="text-secondary mb-4" style="font-size: 0.95rem; line-height: 1.6;">
                        BAMUSI menempatkan dakwah bukan sekadar sebagai wacana, melainkan sebagai kerja sosial, pendidikan, kebudayaan, dan kemanusiaan yang dapat dirasakan masyarakat.
                    </p>

                    <!-- Link Visi Misi -->
                    <a href="#visi" class="text-dark fw-bold text-decoration-none border-bottom border-dark pb-1 d-inline-block" style="font-size: 0.9rem;">
                        Visi dan misi &gt;
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 2. LIMA NILAI UTAMA (ELEGAN, RINGKAS, & LINKED) -->
<section class="py-5 text-white position-relative overflow-hidden" id="nilai" style="background: linear-gradient(135deg, #111111 0%, #2b0000 100%);">

    <!-- Dekorasi Latar Belakang Abstrak (Efek Cahaya/Glow) -->
    <div class="position-absolute" style="top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(204,0,0,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="position-absolute" style="bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="text-uppercase fw-bold" style="color: #cc0000; letter-spacing: 2px; font-size: 0.85rem;">Lima Nilai Utama</span>
                <h2 class="fw-bolder mt-3 mb-0" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1px; line-height: 1.1;">Prinsip yang menuntun<br>setiap langkah.</h2>
            </div>
            <span class="fw-bolder fs-5 opacity-25 d-none d-md-block" style="letter-spacing: 2px;">05 / BAMUSI</span>
        </div>

        <!-- Menggunakan Row Bootstrap flex untuk membagi 5 kolom merata -->
        <div class="row g-3 g-lg-4 pt-4 mt-2 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
            <?php if (!empty($aboutValues)): ?>
                <?php foreach (array_slice($aboutValues, 0, 5) as $i => $value): ?>
                    <div class="col-6 col-md-4 col-lg flex-grow-1">
                        <!-- Kartu Link Interaktif -->
                        <a href="<?= base_url('lima-nilai-utama'); ?>" class="text-decoration-none d-block h-100 p-4 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.background='rgba(204,0,0,0.9)'; this.style.transform='translateY(-8px)'; this.style.borderColor='#cc0000';" onmouseout="this.style.background='rgba(255,255,255,0.03)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.05)';">
                            <div class="d-flex flex-column h-100 min-vh-25" style="min-height: 140px;">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <span class="fw-bolder fs-6 opacity-50 text-white" style="letter-spacing: 1px;">
                                        <?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT); ?>
                                    </span>
                                    <span class="text-white opacity-75 fs-5">↗</span>
                                </div>
                                <!-- Hanya Menampilkan Label -->
                                <h3 class="fw-bold fs-5 mt-auto mb-0 text-white" style="line-height: 1.3;">
                                    <?= esc($value['label']); ?>
                                </h3>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 3. VISI & MISI -->
<section class="pb-0 bg-white" id="visi">
    <div class="row g-0 align-items-stretch">

        <!-- Kolom Kiri: Gambar Rapat Ke Kiri & Atas -->
        <div class="col-lg-6 position-relative">
            <img src="<?= base_url('uploads/reference/exact/bamusi-islam-nusantara.jpg'); ?>"
                class="w-100 h-100"
                style="object-fit: cover; border-top-right-radius: clamp(100px, 15vw, 150px); min-height: 400px;"
                alt="Visi Misi">

            <!-- Kotak Merah dengan Ikon Sparkle (Posisi menggantung sesuai gambar) -->
            <div class="position-absolute" style="bottom: 40px; left: 40px; z-index: 2;">
                <div class="d-flex align-items-center justify-content-center text-white shadow-sm"
                    style="width: 55px; height: 55px; background-color: var(--bamusi-red, #c8102e);">
                    <!-- Ikon Sparkle / Bintang 4 Sisi (SVG Native tanpa dependency font icon) -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Teks Visi & Misi -->
        <div class="col-lg-6 ps-lg-5 pe-lg-5 px-4 d-flex flex-column justify-content-center py-5">
            <div class="pe-xl-5">
                <span class="eyebrow-text text-danger text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;">VISI DAN MISI</span>

                <h2 class="fw-bolder mb-4 mt-2" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing: -1.5px; line-height: 1.1; color: var(--bamusi-dark, #212529);">
                    Menjadi rumah kebangsaan Muslim Indonesia yang progresif.
                </h2>

                <p class="fs-5 mb-5 text-secondary lh-base">
                    <?= esc($settings['about_vision'] ?? 'Membangun masyarakat religius, demokratis, berkeadilan, dan berkemajuan melalui dakwah kebangsaan yang inklusif dan kerja-kerja khidmah yang nyata.'); ?>
                </p>

                <!-- Daftar Misi -->
                <div class="mt-4">
                    <?php
                    $raw_missions = $settings['about_mission'] ?? "Memperkuat Islam Nusantara yang moderat dan toleran.\nMeningkatkan literasi, kaderisasi, dan kepemimpinan umat.\nMenggerakkan solidaritas sosial dan pemberdayaan masyarakat.\nMerawat demokrasi, Pancasila, dan kebhinekaan Indonesia.";

                    $missions = is_array($raw_missions) ? $raw_missions : explode("\n", trim($raw_missions));

                    foreach ($missions as $index => $mission):
                        if (trim($mission) == '') continue;
                        $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    ?>
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
                            <span class="fw-bold me-4" style="color: var(--bamusi-red, #c8102e); font-size: 1rem;"><?= $num; ?></span>
                            <p class="mb-0 fs-6 fw-semibold text-dark">
                                <?= esc(trim($mission)); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- 4. SEJARAH -->
<!-- Menghilangkan padding atas-bawah (py-5) di section agar gambar bisa penuh menyentuh batas atas/bawah -->
<section class="text-white position-relative overflow-hidden" id="sejarah" style="background: linear-gradient(135deg, #111111 0%, #2b0000 100%);">

    <!-- Dekorasi Latar Belakang Abstrak (Efek Cahaya/Glow) -->
    <div class="position-absolute" style="top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(204,0,0,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div class="position-absolute" style="bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="row g-0 align-items-stretch position-relative" style="z-index: 1;">

        <!-- Kolom Kiri: Gambar Sejarah dengan Overlay Dramatis & Teks Raksasa -->
        <!-- min-height ditambahkan agar gambar tetap punya ruang tampil di layar HP -->
        <div class="col-lg-5 position-relative d-flex align-items-center justify-content-center justify-content-lg-end px-4 pe-lg-4 py-5" style="min-height: 450px;">

            <!-- Gambar Latar Dramatis (Bisa diganti dengan foto sejarah yang sesuai) -->
            <img src="<?= base_url('uploads/sejarah.jpg'); ?>"
                class="position-absolute w-100 h-100"
                style="top: 0; left: 0; object-fit: cover; object-position: center; z-index: -2; filter: grayscale(100%);"
                alt="Sejarah BAMUSI">

            <!-- Overlay Gradasi Hitam (Memudar ke arah Kanan untuk layar besar) -->
            <div class="position-absolute w-100 h-100"
                style="top: 0; left: 0; background: linear-gradient(to right, rgba(17,17,17,0.3) 0%, rgba(17,17,17,1) 98%); z-index: -1;"></div>

            <!-- Overlay Gradasi Hitam (Memudar ke arah Bawah, khusus layar HP agar tidak ada batas kaku) -->
            <div class="position-absolute w-100 h-100 d-lg-none"
                style="bottom: 0; left: 0; background: linear-gradient(to bottom, rgba(17,17,17,0) 60%, rgba(17,17,17,1) 100%); z-index: -1;"></div>

            <!-- Teks Raksasa (Diberi efek bayangan/glow merah agar muncul dari gambar) -->
            <!-- Ganti tag <h1> SEJARAH sebelumnya dengan kode ini -->
            <h1 class="m-0 lh-1 position-relative"
                style="
        font-size: clamp(4rem, 8vw, 8rem); 
        font-weight: 900; 
        letter-spacing: -3px; 
        color: #e60000; 
        text-shadow: 
            3px 3px 0px #660000, 
            6px 6px 0px #330000, 
            12px 12px 25px rgba(0,0,0,0.9),
            -2px -2px 15px rgba(204,0,0,0.3);
        transform: translateY(-5px);
    ">
                SEJARAH
            </h1>
        </div>

        <!-- Kolom Kanan: Judul Utama, Teks & Link -->
        <!-- Menambahkan py-5 dan py-lg-5 agar teks punya jarak napas yang pas -->
        <div class="col-lg-7 px-4 ps-lg-5 pe-lg-5 py-5 d-flex flex-column justify-content-center">
            <div class="pe-xl-5 py-lg-4" style="max-width: 800px;">
                <!-- Judul Utama -->
                <h2 class="fw-bolder mb-4 text-uppercase text-white"
                    style="font-size: clamp(2.2rem, 4.2vw, 4.2rem); letter-spacing: -1.5px; line-height: 1;">
                    <?= esc($texts['home.history_label'] ?? 'SEJARAH BAITUL MUSLIMIN INDONESIA'); ?>
                </h2>

                <!-- Deskripsi Teks -->
                <p class="fs-5 mb-5 text-white opacity-75 lh-base">
                    <?= esc($texts['home.history_body'] ?? 'Baitul Muslimin Indonesia (BAMUSI) merupakan organisasi sayap keagamaan Islam Partai Demokrasi Indonesia Perjuangan (PDI Perjuangan) dan Ibu Hj. Megawati Soekarnoputri sebagai Ketua Umum. Dan dorongan para tokoh bangsa dan tokoh PDI Perjuangan yang memiliki komitmen kuat untuk memperkuat harmoni antara nilai-nilai Islam dan kebangsaan.'); ?>
                </p>

                <!-- Link Selengkapnya -->
                <div>
                    <a class="fw-bold text-white text-decoration-none border-bottom border-2 border-white pb-1 fs-6"
                        href="<?= base_url('sejarah'); ?>" style="transition: all 0.3s ease;" onmouseover="this.style.opacity='0.7'; this.style.borderColor='rgba(255,255,255,0.5)';" onmouseout="this.style.opacity='1'; this.style.borderColor='white';">
                        <?= esc($texts['home.history_link'] ?? 'Selengkapnya tentang sejarah BAMUSI ↗'); ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- 5. STRUKTUR PENGURUS (REDESAIN GAYA REFERENSI) -->
<section class="py-5 bg-white" id="pengurus">
    <style>
        /* CSS singkat untuk menyembunyikan scrollbar bawaan browser tapi tetap bisa digeser */
        .pengurus-scroll-wrapper::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div class="container-fluid px-4 px-lg-5 py-5">
        <div class="row align-items-center">

            <!-- Kolom Kiri: Teks dan Tombol -->
            <div class="col-lg-4 mb-5 mb-lg-0 pe-lg-5">
                <h2 class="fw-bold text-dark mb-5" style="font-size: clamp(2rem, 3vw, 2.5rem); line-height: 1.3;">
                    Struktur Pengurus<br>Dewan Pimpinan<br>Pusat BAMUSI
                </h2>
                <!-- Tombol solid merah khas referensi -->
                <a href="<?= base_url('struktur-pengurus'); ?>" class="btn rounded-0 text-white fw-bold px-4 py-3 text-uppercase" style="background-color: #cc0000; letter-spacing: 1px; font-size: 0.9rem;">
                    Lihat Semua
                </a>
            </div>

            <!-- Kolom Kanan: Slider Kartu Pengurus -->
            <div class="col-lg-8">
                <!-- Wrapper horizontal dengan class board-slider-container agar script geser otomatis Anda tetap jalan -->
                <div class="board-slider-container pengurus-scroll-wrapper d-flex flex-nowrap gap-4 pb-3" style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">

                    <?php if (!empty($board)): ?>
                        <?php foreach (array_slice($board, 0, 8) as $member):
                            $photo = trim((string)($member['photo_url'] ?? ''));
                            if ($photo !== '' && !preg_match('#^https?://#i', $photo)) {
                                $photo = base_url(ltrim($photo, '/'));
                            }
                        ?>
                            <!-- Kartu Pengurus -->
                            <div class="card border rounded-3 flex-shrink-0" style="width: 260px;">
                                <div class="card-body p-4 text-center d-flex flex-column align-items-center">

                                    <!-- Wrapper Foto Lingkaran (Background merah) -->
                                    <div class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden mb-4" style="width: 140px; height: 140px; background-color: #cc0000;">
                                        <?php if ($photo !== ''): ?>
                                            <!-- Gunakan gambar transparan berformat PNG agar menyatu dengan background merah -->
                                            <img src="<?= esc($photo); ?>" alt="<?= esc($member['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="text-white fw-bolder" style="font-size: 3rem;"><?= esc(strtoupper(substr($member['name'], 0, 1))); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Nama (Merah, Bold, Uppercase) -->
                                    <h5 class="fw-bold text-uppercase mb-2" style="color: #cc0000; font-size: 1rem; line-height: 1.4;">
                                        <?= esc($member['name']); ?>
                                    </h5>

                                    <!-- Jabatan (Abu-abu, Uppercase) -->
                                    <small class="text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <?= esc($member['role']); ?>
                                    </small>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- 6. RUANG KHIDMAH -->
<!-- Background Elegan: Radial gradient memusat di atas, memudar ke hitam pekat di bawah -->
<!-- 6. RUANG KHIDMAH (DENGAN BACKGROUND IMAGE & OVERLAY) -->
<section class="py-5 position-relative text-white" id="khidmah" style="background: url('<?= base_url('uploads/reference/exact/bamusi-hero-community.jpg'); ?>') no-repeat center center / cover; overflow: hidden;">

    <!-- Overlay Gradasi Gelap & Marun (Kiri pekat agar teks terbaca, kanan tembus pandang melihat foto) -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, #1f0202 0%, rgba(31,2,2,0.95) 35%, rgba(10,10,10,0.75) 70%, rgba(10,10,10,0.5) 100%); z-index: 1;"></div>

    <!-- Dekorasi aksen garis tipis menyala di batas atas section -->
    <div class="position-absolute top-0 start-0 w-100" style="height: 1px; background: linear-gradient(90deg, transparent, rgba(204,0,0,0.8), transparent); z-index: 2;"></div>

    <!-- Konten Utama -->
    <div class="container-fluid px-4 px-lg-5 py-lg-5 py-4 position-relative" style="z-index: 2;">

        <div class="row mb-5">
            <div class="col-lg-7">
                <span class="eyebrow-text text-uppercase fw-bold" style="color: #ff4d4d; letter-spacing: 2px; font-size: 0.85rem;">
                    Ruang Khidmah
                </span>
                <h2 class="fw-bolder mt-3 mb-4 text-white" style="font-size: clamp(2.5rem, 4.5vw, 4.5rem); letter-spacing:-1.5px; line-height: 1.1;">
                    Kerja yang membumi,<br>gagasan yang meluas.
                </h2>
                <!-- Garis pemisah tipis dan deskripsi -->
                <div class="pt-4 mt-2" style="border-top: 1px solid rgba(255,255,255,0.2); max-width: 700px;">
                    <p class="fs-5 mb-0 text-white opacity-85 lh-base">
                        Ruang Khidmah adalah etalase kegiatan bidang-bidang BAMUSI — dari dakwah kebangsaan, pesantren, kemanusiaan, keluarga, hingga literasi digital.
                    </p>
                </div>
            </div>
        </div>

        <!-- Grid 5 Kolom (Responsif: 1 di HP, 2 di Tablet, 3 di Laptop, 5 di Layar Lebar) -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 g-4 mt-4">
            <?php if (!empty($programs)): ?>
                <?php foreach (array_slice($programs, 0, 5) as $i => $program): ?>
                    <div class="col">
                        <!-- Kartu Interaktif dengan efek Glassmorphism di atas background foto -->
                        <a href="#" class="text-decoration-none d-block h-100">
                            <article class="p-4 rounded-4 position-relative h-100 d-flex flex-column overflow-hidden"
                                style="background: rgba(20, 5, 5, 0.65); border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(12px); transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);"
                                onmouseover="this.style.background='rgba(150, 0, 0, 0.4)'; this.style.transform='translateY(-8px)'; this.style.borderColor='rgba(255,255,255,0.3)'; this.querySelector('.watermark-letter').style.opacity='0.12'; this.querySelector('.arrow-icon').style.transform='translate(3px, -3px)';"
                                onmouseout="this.style.background='rgba(20, 5, 5, 0.65)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.12)'; this.querySelector('.watermark-letter').style.opacity='0.03'; this.querySelector('.arrow-icon').style.transform='translate(0, 0)';">

                                <!-- Watermark Huruf A, B, C (Besar dan samar di belakang teks) -->
                                <span class="watermark-letter position-absolute fw-black"
                                    style="font-size: 8rem; right: -10px; bottom: -20px; opacity: 0.03; color: #ffffff; line-height: 1; pointer-events: none; transition: opacity 0.4s ease;">
                                    <?= chr(65 + $i); ?>
                                </span>

                                <!-- Konten Teks (z-index 1 agar berada di atas watermark) -->
                                <h3 class="fs-5 fw-bold mb-3 text-white position-relative" style="z-index: 1;">
                                    <?= esc($program['name']); ?>
                                </h3>

                                <p class="text-white opacity-75 mb-4 position-relative" style="font-size: 0.95rem; z-index: 1;">
                                    <?= esc($program['description']); ?>
                                </p>

                                <!-- Navigasi Bawah Kartu -->
                                <div class="mt-auto d-flex justify-content-between align-items-end position-relative" style="z-index: 1;">
                                    <span class="fw-bold text-white opacity-50" style="font-size: 0.85rem; letter-spacing: 1.5px;">
                                        0<?= $i + 1; ?>
                                    </span>
                                    <div class="arrow-icon text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 35px; height: 35px; background: rgba(255,255,255,0.15); transition: transform 0.3s ease;">
                                        <span class="fs-6">↗</span>
                                    </div>
                                </div>

                            </article>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- 7. PROGRAM UNGGULAN -->
<!-- Tambahkan position-relative dan overflow-hidden agar ornamen SVG tidak keluar jalur -->
<section class="py-5 bg-white position-relative overflow-hidden" id="program-unggulan">

    <!-- Ornamen Latar Belakang (Pola Geometris Islam / Bintang 8) -->
    <!-- Opacity 0.04 (sangat samar) dan warna stroke merah -->
    <div class="position-absolute w-100 h-100 top-0 start-0" style="opacity: 0.04; pointer-events: none; z-index: 0;">
        <svg width="100%" height="100%">
            <defs>
                <pattern id="islamic-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                    <!-- Pola belah ketupat -->
                    <path d="M40 0 L80 40 L40 80 L0 40 Z" fill="none" stroke="var(--bamusi-red, #c8102e)" stroke-width="1.5" />
                    <!-- Pola bujur sangkar -->
                    <path d="M20 20 L60 20 L60 60 L20 60 Z" fill="none" stroke="var(--bamusi-red, #c8102e)" stroke-width="1.5" />
                    <!-- Bintang 8 kecil di tengah -->
                    <path d="M40 28 L43 37 L52 40 L43 43 L40 52 L37 43 L28 40 L37 37 Z" fill="var(--bamusi-red, #c8102e)" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#islamic-pattern)" />
        </svg>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">

        <!-- Header Section -->
        <div class="row align-items-end pb-4 mb-5" style="border-bottom: 2px solid var(--bamusi-dark, #212529);">
            <div class="col-lg-4 mb-3 mb-lg-0">
                <span class="eyebrow-text text-uppercase fw-bold" style="color: var(--bamusi-red, #c8102e); letter-spacing: 2px; font-size: 0.85rem;">
                    Program Unggulan
                </span>
            </div>
            <div class="col-lg-8">
                <h2 class="fw-bolder mb-0 text-dark" style="font-size: clamp(2.5rem, 4vw, 3.5rem); letter-spacing: -1.5px; line-height: 1;">
                    Pesantren Kebangsaan
                </h2>
            </div>
        </div>

        <!-- Grid Program (3 Kolom) -->
        <div class="row g-4 g-lg-5 mt-2">
            <?php for ($i = 1; $i <= 3; $i++):
                $title = $texts['home.feature_item_' . $i . '_title'] ?? '';
                $body = $texts['home.feature_item_' . $i . '_body'] ?? '';
                if ($title !== ''): ?>

                    <div class="col-lg-4">
                        <!-- Kartu Editorial (Dilengkapi efek hover transisi ringan) -->
                        <article class="d-flex flex-column h-100 p-4 rounded-4 bg-white"
                            style="border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: transform 0.3s ease, box-shadow 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(200,0,0,0.08)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">

                            <div class="d-flex gap-4 align-items-start">
                                <!-- Penomoran (Merah) -->
                                <span class="fw-bolder" style="font-size: 3rem; color: var(--bamusi-red, #c8102e); line-height: 0.8;">
                                    <?= str_pad((string)$i, 2, '0', STR_PAD_LEFT); ?>
                                </span>

                                <!-- Konten Teks -->
                                <div>
                                    <h3 class="fw-bold fs-4 mb-3 text-dark" style="letter-spacing: -0.5px;">
                                        <?= esc($title); ?>
                                    </h3>
                                    <p class="text-secondary mb-0 lh-base" style="font-size: 0.95rem;">
                                        <?= esc($body); ?>
                                    </p>
                                </div>
                            </div>

                        </article>
                    </div>

            <?php endif;
            endfor; ?>
        </div>

    </div>
</section>

<!-- 8. AGENDA KEGIATAN -->
<!-- Background diganti dengan gradasi merah gelap yang elegan & tidak monoton -->
<section class="py-5 text-white position-relative overflow-hidden" id="agenda" style="background: linear-gradient(135deg, #8a0000 0%, #4a0000 50%, #200000 100%);">

    <!-- Dekorasi aksen cahaya halus (glow) di latar belakang -->
    <div class="position-absolute" style="top: -30%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">
        <div class="row align-items-lg-center">

            <!-- Kolom Kiri: Judul dan Keterangan -->
            <div class="col-lg-5 pe-lg-5 mb-5 mb-lg-0">
                <span class="text-uppercase fw-bold opacity-75" style="letter-spacing: 2px; font-size: 0.85rem;">
                    Agenda Kegiatan
                </span>
                <h2 class="fw-bolder my-3" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1.5px; line-height: 1.1;">
                    Temukan ruang perjumpaan berikutnya.
                </h2>
                <p class="opacity-75 fs-5 mb-0">
                    Tanggal dan lokasi resmi akan diumumkan melalui kanal BAMUSI.
                </p>
            </div>

            <!-- Kolom Kanan: Daftar List Agenda (Maksimal 4 item) -->
            <div class="col-lg-7">
                <div class="pt-2" style="border-top: 2px solid rgba(255,255,255,0.2);">
                    <?php if (!empty($agenda)): ?>
                        <?php foreach (array_slice($agenda, 0, 4) as $i => $item): ?>

                            <!-- Mengubah setiap item menjadi tautan dinamis yang mengarah ke detail agenda -->
                            <!-- Asumsi database menggunakan field 'slug' atau 'id' untuk URL detail -->
                            <?php
                            $agendaSlug = $item['slug'] ?? $item['id'] ?? '#';
                            $agendaUrl = base_url('agenda/' . $agendaSlug);
                            ?>
                            <a href="<?= $agendaUrl; ?>" class="text-decoration-none text-white d-block group-agenda">
                                <article class="d-flex align-items-center py-4 px-3 rounded-3 position-relative"
                                    style="border-bottom: 1px solid rgba(255,255,255,0.15); transition: all 0.3s ease;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.paddingLeft='20px';"
                                    onmouseout="this.style.background='transparent'; this.style.paddingLeft='1rem';">

                                    <!-- Nomor Urut -->
                                    <div class="fw-bolder fs-3 me-4 opacity-50" style="min-width: 40px;">
                                        <?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT); ?>
                                    </div>

                                    <!-- Detail Tanggal & Judul Agenda -->
                                    <div class="flex-grow-1 pe-3">
                                        <p class="text-uppercase mb-1 opacity-75 fw-bold" style="font-size: 0.75rem; letter-spacing: 1.5px;">
                                            <?= esc($item['event_date'] ?? 'Jadwal segera'); ?>
                                        </p>
                                        <h3 class="fw-bold mb-0 fs-4" style="letter-spacing: -0.5px;">
                                            <?= esc($item['title']); ?>
                                        </h3>
                                    </div>

                                    <!-- Ikon Panah Interaktif -->
                                    <div class="fs-4 fw-bold opacity-75 ps-2" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='translateX(5px)'">
                                        →
                                    </div>

                                </article>
                            </a>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 9. BERITA BAMUSI (GAYA EDITORIAL MODERN DENGAN BACKGROUND ELEGAN) -->
<section class="py-5 bg-white position-relative overflow-hidden" id="berita">

    <!-- Latar Belakang Jurnalisme yang Lebih Jelas & Elegan -->
    <div class="position-absolute w-100 h-100 top-0 start-0" style="pointer-events: none; z-index: 0;">

        <!-- Garis Grid Vertikal (Diperjelas sedikit kontrasnya agar terlihat rapi dan elegan) -->
        <div class="container-fluid px-4 px-lg-5 h-100 position-relative">
            <div class="row h-100 justify-content-between">
                <div class="col-3 h-100 border-end" style="border-color: rgba(0,0,0,0.06) !important;"></div>
                <div class="col-3 h-100 border-end d-none d-lg-block" style="border-color: rgba(0,0,0,0.06) !important;"></div>
                <div class="col-3 h-100 d-none d-lg-block" style="border-color: rgba(0,0,0,0.06) !important;"></div>
            </div>
        </div>

        <!-- Watermark Teks Besar 'KABAR' (Dinaikkan opasitasnya agar terlihat jelas sebagai ornamen artistik) -->
        <div class="position-absolute text-uppercase fw-black" style="font-size: 14vw; bottom: 8%; left: -1%; color: rgba(200,0,0,0.035); line-height: 1; user-select: none; letter-spacing: -5px;">
            KABAR
        </div>

        <!-- Aksen Gradasi Cahaya Tipis di Pojok Kanan Atas -->
        <div class="position-absolute" style="top: -10%; right: -5%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(204,0,0,0.04) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
    </div>

    <!-- Konten Utama -->
    <div class="container-fluid px-4 px-lg-5 py-5 position-relative" style="z-index: 1;">

        <!-- Header Berita -->
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="eyebrow-text text-uppercase fw-bold" style="color: var(--bamusi-red, #c8102e); letter-spacing: 2px; font-size: 0.85rem;">
                    Berita BAMUSI
                </span>
                <h2 class="fw-bolder mt-3 mb-0" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1px; line-height: 1.1; color: var(--bamusi-dark, #212529);">
                    Indeks kabar nasional<br>dan informasi.
                </h2>
            </div>
            <!-- Link Lihat Semua untuk Desktop -->
            <a href="#" class="d-none d-md-block fw-bold text-dark text-decoration-none border-bottom border-2 border-dark pb-1 text-uppercase fs-6 transition-all" style="transition: opacity 0.3s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                Lihat Semua Berita ↗
            </a>
        </div>

        <!-- Grid 4 Kolom (Editorial Card) -->
        <div class="row g-4 pt-4 mt-2" style="border-top: 2px solid var(--bamusi-dark, #212529);">
            <?php if (!empty($news)): ?>
                <?php foreach (array_slice($news, 0, 4) as $i => $item): ?>
                    <div class="col-lg-3 col-md-6">
                        <!-- Kartu Berita -->
                        <div class="card h-100 rounded-4 p-4 position-relative d-flex flex-column bg-white shadow-sm"
                            style="border: 1px solid rgba(0,0,0,0.08); transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);"
                            onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'; this.style.borderColor='var(--bamusi-red, #c8102e)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'; this.style.borderColor='rgba(0,0,0,0.08)';">

                            <!-- Kategori & Tanggal -->
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <span class="badge text-uppercase px-2.5 py-1.5" style="background-color: var(--bamusi-red, #c8102e); letter-spacing: 1px; font-size: 0.65rem;">
                                    <?= esc($item['category'] ?? 'Nasional'); ?>
                                </span>
                                <small class="text-secondary fw-bold" style="font-size: 0.75rem;">
                                    <?= esc($item['event_date'] ?? ''); ?>
                                </small>
                            </div>

                            <!-- Judul Berita -->
                            <h3 class="fw-bold fs-5 mb-4 lh-base text-dark">
                                <a href="<?= esc($item['url'] ?? '#'); ?>" target="_blank" class="text-dark text-decoration-none stretched-link">
                                    <?= esc($item['title']); ?>
                                </a>
                            </h3>

                            <!-- Footer Kartu -->
                            <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.06) !important;">
                                <small class="text-uppercase fw-bold text-secondary" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <span style="color: var(--bamusi-red, #c8102e);">Sumber:</span> <?= esc($item['source'] ?? 'BAMUSI'); ?>
                                </small>
                                <span class="text-dark fw-bold fs-6">↗</span>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Link Lihat Semua untuk Mobile -->
        <div class="mt-5 text-center d-md-none">
            <a href="#" class="btn rounded-0 fw-bold text-uppercase px-4 py-3 w-100 shadow-sm" style="background: transparent; color: var(--bamusi-dark, #212529); border: 2px solid var(--bamusi-dark, #212529);">
                Lihat Semua Berita ↗
            </a>
        </div>

    </div>
</section>

<!-- 10. KOLOM TULISAN -->
<section class="py-5 bg-light" id="tulisan">
    <div class="container-fluid px-4 px-lg-5 py-5 border-thick-top border-thick-bottom">
        <h2 class="fw-bolder text-center mb-5" style="font-size: clamp(2rem, 3vw, 3.5rem); letter-spacing:-1px; color: var(--bamusi-dark);">Gagasan untuk Islam,<br>demokrasi, dan Indonesia.</h2>
        <div class="row g-0 border-thick-top">
            <?php for ($i = 1; $i <= 4; $i++): $topic = $texts['home.writing_topic_' . $i] ?? '';
                if ($topic !== ''): ?>
                    <div class="col-lg-3 col-6 p-4 text-center" style="border-right: 2px solid var(--bamusi-black);">
                        <span class="fw-bolder fs-3 d-block mb-2" style="color: var(--bamusi-red);"><?= str_pad((string)$i, 2, '0', STR_PAD_LEFT); ?></span>
                        <strong class="fs-6 text-dark text-uppercase"><?= esc($topic); ?></strong>
                    </div>
            <?php endif;
            endfor; ?>
        </div>
    </div>
</section>

<!-- 11. MEDIA SOSIAL -->
<section class="py-5 text-center text-white" style="background-color: var(--bamusi-black);">
    <div class="container-fluid px-4 px-lg-5 py-5">
        <h2 class="fw-bolder mb-5" style="font-size: clamp(2.5rem, 4vw, 4rem); letter-spacing:-1px; line-height: 1.1;">Ikuti gerak BAMUSI<br>dari kanal resminya.</h2>
        <div class="d-flex justify-content-center gap-4 flex-wrap">
            <a href="<?= esc($settings['instagram_url'] ?? '#'); ?>" target="_blank" class="p-4 text-decoration-none text-white flex-grow-1" style="max-width:400px; border: 2px solid white;">
                <small class="d-block text-uppercase fw-bold mb-2 opacity-75" style="letter-spacing: 2px;">Instagram</small>
                <h3 class="fw-bold m-0 text-break" style="color: var(--bamusi-red);">@baitul.muslimin.id</h3>
            </a>
            <a href="<?= esc($settings['tiktok_url'] ?? '#'); ?>" target="_blank" class="p-4 text-decoration-none text-white flex-grow-1" style="max-width:400px; border: 2px solid white;">
                <small class="d-block text-uppercase fw-bold mb-2 opacity-75" style="letter-spacing: 2px;">TikTok</small>
                <h3 class="fw-bold m-0 text-break" style="color: var(--bamusi-red);">@bamusi_indonesia</h3>
            </a>
        </div>
    </div>
</section>

<!-- 12. MITRA KERJA SAMA -->
<section class="py-5 bg-white">
    <div class="container-fluid px-4 px-lg-5 py-5">
        <div class="row align-items-center mb-5">
            <div class="col-lg-4">
                <span class="eyebrow-text mb-0" style="color: var(--bamusi-red);">Mitra Kerja Sama</span>
            </div>
            <div class="col-lg-8">
                <h2 class="fw-bolder m-0" style="font-size: clamp(2rem, 3vw, 2.5rem); letter-spacing:-1px; color: var(--bamusi-dark);">Bertumbuh melalui jejaring<br>dan kolaborasi.</h2>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-5 justify-content-center justify-content-lg-between border-thick-top pt-5">
            <?php foreach ($partners as $partner): if (!(int)($partner['published'] ?? 0)) continue; ?>
                <a href="<?= esc($partner['website_url'] ?? '#'); ?>" target="_blank" class="text-center text-dark text-decoration-none" style="width: 140px;">
                    <img src="<?= esc($partner['logo_url'] ?? ''); ?>" alt="<?= esc($partner['name']); ?>" class="img-fluid mb-3" style="max-height: 70px; object-fit: contain; filter: grayscale(100%); transition: 0.3s;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                    <span class="d-block fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing:1px;"><?= esc($partner['name']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 13. CARA BERGABUNG -->
<section class="py-5" style="background-color: var(--bamusi-red);" id="bergabung">
    <div class="container-fluid px-4 px-lg-5 py-5">
        <div class="row">
            <div class="col-lg-5 pe-lg-5 mb-5 mb-lg-0 text-white">
                <span class="eyebrow-text" style="color: var(--bamusi-dark);">Cara Bergabung</span>
                <h2 class="fw-bolder mb-4" style="font-size: clamp(3rem, 5vw, 4.5rem); letter-spacing:-1.5px; line-height: 1.1;">Ambil bagian<br>dalam kerja<br>khidmah.</h2>
                <p class="fs-5 opacity-75 pe-lg-4">Isi data singkat berikut. Informasi akan dirangkai menjadi pesan WhatsApp kepada admin.</p>
            </div>
            <div class="col-lg-7">
                <form class="bg-white text-dark p-4 p-lg-5 h-100 border-thick-dark">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="eyebrow-text mb-1" style="color: var(--bamusi-red);">Nama Lengkap</label>
                            <input type="text" name="name" class="join-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="eyebrow-text mb-1" style="color: var(--bamusi-red);">Domisili</label>
                            <input type="text" name="domicile" class="join-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="eyebrow-text mb-1" style="color: var(--bamusi-red);">Alamat Email</label>
                            <input type="email" name="email" class="join-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="eyebrow-text mb-1" style="color: var(--bamusi-red);">Nomor WhatsApp</label>
                            <input type="tel" name="phone" class="join-input" required>
                        </div>
                        <div class="col-12 mt-4">
                            <label class="eyebrow-text mb-1" style="color: var(--bamusi-red);">Minat Kontribusi</label>
                            <select name="interest" class="join-input text-uppercase fw-bold" style="cursor: pointer;">
                                <option>Pilih Minat</option>
                                <option>Keanggotaan Umum</option>
                                <option>Relawan Program</option>
                            </select>
                        </div>
                        <div class="col-12 mt-5">
                            <button type="submit" class="btn w-100 fs-5 fw-bold text-uppercase py-3 rounded-0 border-thick-dark" style="background: transparent; color: var(--bamusi-black);" onmouseover="this.style.backgroundColor='var(--bamusi-black)'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--bamusi-black)';">
                                KIRIM MELALUI WHATSAPP ↗
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>
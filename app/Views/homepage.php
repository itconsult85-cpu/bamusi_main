<!DOCTYPE html>
<html lang="<?= $locale ?>">

<head>
    <meta charset="UTF-8">
    <title>BAMUSI</title>
</head>

<body>

    <!-- Tombol Ganti Bahasa -->
    <div style="text-align: right; padding: 20px;">
        <a href="<?= base_url('id') ?>" style="<?= $locale == 'id' ? 'font-weight:bold;' : '' ?>">🇮🇩 Indonesia</a> |
        <a href="<?= base_url('en') ?>" style="<?= $locale == 'en' ? 'font-weight:bold;' : '' ?>">🇬🇧 English</a>
    </div>

    <h1>Berita Terbaru BAMUSI</h1>

    <!-- Looping Berita -->
    <?php foreach ($berita as $row): ?>
        <?php
        // Cek apakah bahasa Inggris aktif DAN hasil translate-nya ada
        if ($locale == 'en' && !empty($row['title_en'])) {
            $judul = $row['title_en'];
            $isi   = $row['body_en'];
        } else {
            $judul = $row['title'];
            $isi   = $row['body'];
        }
        ?>

        <div class="card" style="border:1px solid #ccc; padding:15px; margin-bottom:10px;">
            <h3><?= esc($judul) ?></h3>
            <p><?= esc($isi) ?></p>
        </div>
    <?php endforeach; ?>

</body>

</html>
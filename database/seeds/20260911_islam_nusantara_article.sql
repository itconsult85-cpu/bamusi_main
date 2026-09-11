-- Seed contoh artikel CMS BAMUSI
-- Aman dijalankan ulang: hanya mengganti artikel contoh dengan judul yang sama.
-- Prasyarat: tabel cms_items sudah tersedia dari bamusi.sql.

START TRANSACTION;

DELETE FROM `cms_items`
WHERE `kind` = 'article'
  AND `title` = 'Islam Nusantara: Merawat Tradisi, Meneguhkan Kemanusiaan, dan Menjaga Indonesia';

INSERT INTO `cms_items` (
    `kind`, `title`, `title_en`, `summary`, `summary_en`, `body`, `body_en`,
    `url`, `image_url`, `category`, `event_date`, `location`, `published`,
    `created_at`, `updated_at`
) VALUES (
    'article',
    'Islam Nusantara: Merawat Tradisi, Meneguhkan Kemanusiaan, dan Menjaga Indonesia',
    'Islam Nusantara: Preserving Tradition, Upholding Humanity, and Caring for Indonesia',
    'Islam Nusantara bukan sekadar istilah, melainkan cara menghidupkan ajaran Islam melalui tradisi, ilmu, dan kerja kebangsaan yang menghormati martabat setiap manusia.',
    'Islam Nusantara is not merely a term, but a way of living Islamic teachings through tradition, knowledge, and national service that respects the dignity of every person.',
    '<p class="lead">Indonesia tidak dibangun oleh satu warna. Ia tumbuh dari perjumpaan antara keyakinan, kebudayaan, bahasa, sejarah, dan pengalaman hidup yang beragam. Dalam ruang kebangsaan seperti ini, Islam Nusantara hadir sebagai cara beragama yang berakar pada ajaran Islam sekaligus peka terhadap kenyataan sosial tempat umat hidup.</p>

<h2>Islam yang berakar dan terbuka</h2>
<p>Islam Nusantara dapat dipahami sebagai ikhtiar untuk menghadirkan nilai-nilai Islam—keadilan, kemaslahatan, kasih sayang, amanah, dan penghormatan terhadap ilmu—dalam konteks masyarakat Nusantara. Ia tidak mengubah prinsip dasar agama. Sebaliknya, ia mengajak kita membaca prinsip tersebut secara jernih agar mampu menjawab persoalan nyata: kemiskinan, ketimpangan, kekerasan, krisis lingkungan, dan melemahnya kepercayaan publik.</p>
<p>Karena itu, tradisi tidak perlu dipertentangkan dengan kemajuan. Tradisi yang baik adalah sumber kebijaksanaan, sementara ilmu pengetahuan membantu kita menguji, mengembangkan, dan menerapkan kebijaksanaan itu secara bertanggung jawab. Sikap ini membuat keberagamaan tidak berhenti pada simbol, tetapi bergerak menjadi tindakan yang memberi manfaat.</p>

<blockquote><p>Beragama dengan akar yang kuat tidak berarti menutup diri dari dunia. Justru dengan akar itulah kita dapat berdiri tegak, berdialog, dan memberi arah bagi masa depan.</p></blockquote>

<h2>Pesantren sebagai ruang pembentukan karakter</h2>
<p>Salah satu kekuatan penting Islam Nusantara adalah tradisi pesantren. Pesantren tidak hanya mengajarkan pengetahuan keislaman, tetapi juga membentuk kebiasaan hidup: kesederhanaan, disiplin, penghormatan kepada guru, kemandirian, dan kepedulian kepada masyarakat. Nilai-nilai tersebut menjadi modal sosial yang penting ketika generasi muda menghadapi perubahan teknologi dan arus informasi yang sangat cepat.</p>
<p>Pesantren masa kini perlu terus terbuka terhadap literasi digital, sains, kewirausahaan, dan percakapan lintas iman. Keterbukaan itu bukan tanda kehilangan identitas. Ia adalah bentuk tanggung jawab agar pendidikan Islam mampu menyiapkan generasi yang teguh dalam nilai, terampil dalam pengetahuan, dan matang dalam mengambil keputusan.</p>

<h2>Demokrasi sebagai ikhtiar menjaga kemaslahatan</h2>
<p>Demokrasi memberi ruang bagi perbedaan untuk dikelola melalui dialog, hukum, dan partisipasi. Dalam perspektif Islam Nusantara, demokrasi dapat menjadi jalan untuk memastikan bahwa kekuasaan tidak berjalan tanpa batas dan bahwa suara kelompok yang rentan tetap didengar. Musyawarah, amanah, keadilan, serta penghormatan terhadap martabat manusia merupakan nilai yang dapat memperkuat praktik demokrasi.</p>
<p>Namun, demokrasi tidak cukup hanya dengan pemilihan umum. Ia juga membutuhkan kebiasaan mendengar, kesediaan memeriksa informasi, keberanian mengoreksi kebijakan, dan komitmen untuk tidak menjadikan agama sebagai alat merendahkan sesama. Kebangsaan yang sehat dibangun oleh warga yang mampu berbeda pendapat tanpa kehilangan persaudaraan.</p>

<div class="highlight-box"><h3>Empat sikap yang dapat dirawat</h3><ul><li>Menjadikan ilmu dan tabayun sebagai dasar sebelum menyebarkan informasi.</li><li>Menghormati tradisi lokal yang tidak bertentangan dengan nilai kemanusiaan dan prinsip agama.</li><li>Membangun dialog lintas kelompok dengan bahasa yang santun dan setara.</li><li>Mengubah kepedulian sosial menjadi kerja nyata bagi lingkungan sekitar.</li></ul></div>

<h2>Perempuan dan generasi muda sebagai penggerak perubahan</h2>
<p>Islam Nusantara juga harus memberi ruang yang adil bagi perempuan dan generasi muda. Keduanya bukan sekadar penerima manfaat pembangunan, melainkan pengambil keputusan, pendidik, pemimpin komunitas, peneliti, pekerja kreatif, dan penggerak perubahan. Ruang keagamaan dan kebangsaan akan menjadi lebih kuat ketika pengalaman mereka didengar dan kontribusi mereka dihargai.</p>
<p>Di tengah perubahan zaman, generasi muda membutuhkan teladan yang mampu menggabungkan kesalehan personal dengan tanggung jawab sosial. Mereka perlu melihat bahwa mencintai Indonesia bukan slogan kosong, melainkan kesediaan merawat ruang publik, melawan diskriminasi, menjaga lingkungan, dan membantu mereka yang tertinggal.</p>

<h2>Menjaga Indonesia melalui kerja kemanusiaan</h2>
<p>Pada akhirnya, Islam Nusantara diukur dari manfaatnya. Ia tampak ketika rumah ibadah menjadi ruang yang menenteramkan, ketika lembaga pendidikan melahirkan manusia yang berintegritas, ketika organisasi mampu melayani tanpa membeda-bedakan, dan ketika perbedaan dipandang sebagai kenyataan yang harus dirawat dengan kebijaksanaan.</p>
<p>Indonesia membutuhkan keberagamaan yang menghadirkan harapan, bukan ketakutan; yang memperluas persaudaraan, bukan mempersempitnya; dan yang mendorong umat untuk bekerja bersama menyelesaikan persoalan. Merawat Islam Nusantara berarti merawat tradisi ilmu, demokrasi, kemanusiaan, serta masa depan Indonesia yang damai dan berkeadaban.</p>

<p><strong>Catatan redaksi:</strong> Artikel ini merupakan contoh konten untuk memperlihatkan format pengisian artikel pada CMS BAMUSI. Editor dapat menyesuaikan penulis, gambar utama, kutipan, dan referensi sebelum diterbitkan sebagai tulisan resmi.</p>',
    NULL,
    NULL,
    'Islam Nusantara',
    NULL,
    NULL,
    1,
    NOW(),
    NOW()
);

COMMIT;

-- Verifikasi hasil:
-- SELECT id, kind, title, category, published, created_at
-- FROM cms_items
-- WHERE kind = 'article'
-- ORDER BY created_at DESC;

-- Untuk menghapus contoh ini tanpa menyentuh artikel lain:
-- DELETE FROM cms_items
-- WHERE kind = 'article'
--   AND title = 'Islam Nusantara: Merawat Tradisi, Meneguhkan Kemanusiaan, dan Menjaga Indonesia';

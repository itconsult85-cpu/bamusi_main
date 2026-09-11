-- Seed contoh artikel CMS BAMUSI.
-- Prasyarat: tabel cms_items sudah tersedia dari bamusi.sql.
-- Format INSERT ... SET dipakai agar tidak terkena error #1136 pada schema lokal.
-- Aman dijalankan ulang: hanya mengganti artikel contoh dengan judul yang sama.

START TRANSACTION;

DELETE FROM `cms_items`
WHERE `kind` = 'article'
  AND `title` = 'Islam Nusantara: Merawat Tradisi, Meneguhkan Kemanusiaan, dan Menjaga Indonesia';

INSERT INTO `cms_items` SET
    `kind` = 'article',
    `title` = 'Islam Nusantara: Merawat Tradisi, Meneguhkan Kemanusiaan, dan Menjaga Indonesia',
    `title_en` = 'Islam Nusantara: Preserving Tradition, Upholding Humanity, and Caring for Indonesia',
    `summary` = 'Islam Nusantara bukan sekadar istilah, melainkan cara menghidupkan ajaran Islam melalui tradisi, ilmu, dan kerja kebangsaan yang menghormati martabat setiap manusia.',
    `summary_en` = 'Islam Nusantara is not merely a term, but a way of living Islamic teachings through tradition, knowledge, and national service that respects the dignity of every person.',
    `body` = '<p class="lead">Indonesia tidak dibangun oleh satu warna. Ia tumbuh dari perjumpaan antara keyakinan, kebudayaan, bahasa, sejarah, dan pengalaman hidup yang beragam. Dalam ruang kebangsaan seperti ini, Islam Nusantara hadir sebagai cara beragama yang berakar pada ajaran Islam sekaligus peka terhadap kenyataan sosial tempat umat hidup.</p>

<h2>Islam yang berakar dan terbuka</h2>
<p>Islam Nusantara dapat dipahami sebagai ikhtiar untuk menghadirkan nilai-nilai Islam—keadilan, kemaslahatan, kasih sayang, amanah, dan penghormatan terhadap ilmu—dalam konteks masyarakat Nusantara. Ia tidak mengubah prinsip dasar agama. Sebaliknya, ia mengajak kita membaca prinsip tersebut secara jernih agar mampu menjawab persoalan nyata: kemiskinan, ketimpangan, kekerasan, krisis lingkungan, dan melemahnya kepercayaan publik.</p>
<p>Karena itu, tradisi tidak perlu dipertentangkan dengan kemajuan. Tradisi yang baik adalah sumber kebijaksanaan, sementara ilmu pengetahuan membantu kita menguji, mengembangkan, dan menerapkan kebijaksanaan itu secara bertanggung jawab.</p>

<blockquote><p>Beragama dengan akar yang kuat tidak berarti menutup diri dari dunia. Justru dengan akar itulah kita dapat berdiri tegak, berdialog, dan memberi arah bagi masa depan.</p></blockquote>

<h2>Pesantren sebagai ruang pembentukan karakter</h2>
<p>Salah satu kekuatan penting Islam Nusantara adalah tradisi pesantren. Pesantren tidak hanya mengajarkan pengetahuan keislaman, tetapi juga membentuk kebiasaan hidup: kesederhanaan, disiplin, penghormatan kepada guru, kemandirian, dan kepedulian kepada masyarakat.</p>
<p>Pesantren masa kini perlu terus terbuka terhadap literasi digital, sains, kewirausahaan, dan percakapan lintas iman. Keterbukaan itu bukan tanda kehilangan identitas, melainkan tanggung jawab agar pendidikan Islam mampu menyiapkan generasi yang teguh dalam nilai dan terampil dalam pengetahuan.</p>

<h2>Demokrasi sebagai ikhtiar menjaga kemaslahatan</h2>
<p>Demokrasi memberi ruang bagi perbedaan untuk dikelola melalui dialog, hukum, dan partisipasi. Dalam perspektif Islam Nusantara, demokrasi dapat menjadi jalan untuk memastikan bahwa kekuasaan tidak berjalan tanpa batas dan bahwa suara kelompok yang rentan tetap didengar.</p>
<p>Demokrasi juga membutuhkan kebiasaan mendengar, kesediaan memeriksa informasi, keberanian mengoreksi kebijakan, dan komitmen untuk tidak menjadikan agama sebagai alat merendahkan sesama. Kebangsaan yang sehat dibangun oleh warga yang mampu berbeda pendapat tanpa kehilangan persaudaraan.</p>

<div class="highlight-box"><h3>Empat sikap yang dapat dirawat</h3><ul><li>Menjadikan ilmu dan tabayun sebagai dasar sebelum menyebarkan informasi.</li><li>Menghormati tradisi lokal yang tidak bertentangan dengan nilai kemanusiaan dan prinsip agama.</li><li>Membangun dialog lintas kelompok dengan bahasa yang santun dan setara.</li><li>Mengubah kepedulian sosial menjadi kerja nyata bagi lingkungan sekitar.</li></ul></div>

<h2>Perempuan dan generasi muda sebagai penggerak perubahan</h2>
<p>Islam Nusantara juga harus memberi ruang yang adil bagi perempuan dan generasi muda. Keduanya bukan sekadar penerima manfaat pembangunan, melainkan pengambil keputusan, pendidik, pemimpin komunitas, peneliti, pekerja kreatif, dan penggerak perubahan.</p>
<p>Generasi muda membutuhkan teladan yang mampu menggabungkan kesalehan personal dengan tanggung jawab sosial. Mencintai Indonesia bukan slogan kosong, melainkan kesediaan merawat ruang publik, melawan diskriminasi, menjaga lingkungan, dan membantu mereka yang tertinggal.</p>

<h2>Menjaga Indonesia melalui kerja kemanusiaan</h2>
<p>Pada akhirnya, Islam Nusantara diukur dari manfaatnya. Ia tampak ketika rumah ibadah menjadi ruang yang menenteramkan, lembaga pendidikan melahirkan manusia yang berintegritas, dan organisasi mampu melayani tanpa membeda-bedakan.</p>
<p>Indonesia membutuhkan keberagamaan yang menghadirkan harapan, bukan ketakutan; memperluas persaudaraan, bukan mempersempitnya; dan mendorong umat bekerja bersama menyelesaikan persoalan. Merawat Islam Nusantara berarti merawat tradisi ilmu, demokrasi, kemanusiaan, serta masa depan Indonesia yang damai dan berkeadaban.</p>

<p><strong>Catatan redaksi:</strong> Artikel ini adalah contoh konten untuk memperlihatkan format pengisian artikel pada CMS BAMUSI. Editor dapat menyesuaikan penulis, gambar utama, kutipan, dan referensi sebelum diterbitkan sebagai tulisan resmi.</p>',
    `body_en` = '<p class="lead">Indonesia was not built from a single color. It grew through encounters among faith, culture, language, history, and diverse life experiences. In this national setting, Islam Nusantara represents a way of living Islam that is rooted in Islamic teachings and attentive to social reality.</p><h2>Rooted and open Islam</h2><p>Islam Nusantara brings values such as justice, public benefit, compassion, trust, and respect for knowledge into the Nusantara context. It keeps faith connected to real challenges and meaningful service.</p><h2>Pesantren and character</h2><p>Pesantren develops knowledge as well as simplicity, discipline, respect, independence, and concern for society. Its openness to science and digital literacy can prepare a generation that is principled and capable.</p><h2>Democracy and public benefit</h2><p>Democracy provides a space for differences to be managed through dialogue, law, and participation. A healthy national life requires listening, checking information, correcting policy, and preserving fellowship despite disagreement.</p><h2>Humanity and Indonesia</h2><p>Islam Nusantara is ultimately measured by its benefit: peaceful houses of worship, ethical education, inclusive service, and cooperation for a peaceful and dignified Indonesia.</p>',
    `url` = NULL,
    `image_url` = NULL,
    `category` = 'Islam Nusantara',
    `event_date` = NULL,
    `location` = NULL,
    `published` = 1,
    `created_at` = NOW(),
    `updated_at` = NOW();

COMMIT;

-- Verifikasi:
-- SELECT id, kind, title, category, published, created_at
-- FROM cms_items WHERE kind = 'article' ORDER BY created_at DESC;

-- Menghapus contoh ini:
-- DELETE FROM cms_items WHERE kind = 'article'
-- AND title = 'Islam Nusantara: Merawat Tradisi, Meneguhkan Kemanusiaan, dan Menjaga Indonesia';

# Audit Keamanan dan CMS BAMUSI

## Kesimpulan

Repository `itconsult85-cpu/bamusi_main` adalah aplikasi CodeIgniter 4 dengan database MySQL/MariaDB. Dump SQL menunjukkan CMS telah memiliki tabel konten yang cukup luas, tetapi implementasi awal belum menghubungkan tabel `users` dengan mekanisme login. Sebelum perubahan ini, seluruh route `/admin/*` dapat dipanggil tanpa autentikasi.

Perbaikan baseline telah diterapkan pada checkout lokal. Perubahan tersebut menambahkan login administrator berbasis session, membatasi seluruh route admin dengan filter role `admin`, mengaktifkan CSRF dan security headers, memperketat cookie, mengubah operasi hapus menjadi POST, serta memvalidasi upload media.

## Temuan Awal

| Area | Kondisi awal | Risiko | Status baseline |
|---|---|---|---|
| Login | Tidak ditemukan controller login atau `password_verify` | Siapa pun yang mengetahui URL admin dapat mengubah konten | Diperbaiki dengan `Auth`, `UserModel`, dan session |
| Otorisasi | Group admin tidak memiliki filter | Akses penuh tanpa role check | Diperbaiki dengan `AdminAuth` dan role `admin` |
| CSRF | Filter global dikomentari dan form admin tidak konsisten | Request perubahan dapat dipalsukan dari situs lain | Diaktifkan global dan token ditambahkan ke form POST |
| Security headers | Filter `secureheaders` dikomentari | Perlindungan browser berkurang | Diaktifkan |
| Cookie | `secure=false`, `SameSite=Lax` | Cookie dapat lebih mudah disalahgunakan pada deployment HTTP atau alur lintas situs | `Secure` aktif di production dan `SameSite=Strict` |
| Session fixation | `regenerateDestroy=false` | ID session lama masih dapat dipakai sampai garbage collection | Diaktifkan penghancuran ID lama |
| Delete action | Beberapa operasi hapus menggunakan GET | Crawler atau tautan pihak ketiga dapat memicu penghapusan | Route delete diubah menjadi POST ber-CSRF |
| Upload | Beberapa controller memindahkan file tanpa validasi MIME dan ukuran | Upload file berbahaya atau file terlalu besar | Validasi image/MP4 dan batas ukuran ditambahkan |
| Hardcode UI | Banyak label, footer, dan navigasi masih ditulis langsung di view | Perubahan konten membutuhkan edit source code | Sebagian sudah tersedia di `website_texts`; migrasi penuh masih diperlukan |
| Login abuse | Belum ada pembatasan percobaan login atau event log | Brute force sulit dideteksi dan dikendalikan | Rate limit email+IP dan audit log ditambahkan |

## Implementasi yang Telah Dibuat

File utama yang ditambahkan atau diubah adalah sebagai berikut:

- `app/Controllers/Auth.php` menyediakan halaman login, verifikasi `password_verify`, rehash otomatis, session regeneration, dan logout.
- `app/Models/UserModel.php` membatasi pencarian login pada user dengan role `admin` dan tidak pernah mengirim hash password ke view.
- `app/Filters/AdminAuth.php` menolak request ke CMS apabila session tidak valid atau bukan role `admin`.
- `app/Config/Routes.php` menambahkan `/login`, `/logout`, dan filter admin; route penghapusan menggunakan POST.
- `app/Config/Filters.php` mengaktifkan CSRF, invalid-character filtering, security headers, dan alias auth.
- `app/Config/Cookie.php`, `app/Config/Session.php`, dan `app/Config/Security.php` memperketat atribut cookie, rotasi session, dan randomisasi token CSRF.
- Form CMS mendapatkan token CSRF. Layout CMS menampilkan administrator aktif dan menyediakan logout POST.
- Upload pada hero, page, header logo, dan section divalidasi berdasarkan tipe MIME dan ukuran.
- `2026-09-11-071500_CreateAuthAuditLogs.php` menambahkan tabel audit login/logout tanpa menyimpan password atau alamat IP mentah.
- `Auth.php` membatasi percobaan login menjadi lima kali per 15 menit berdasarkan email dan IP, serta mencatat `login_failed`, `rate_limited`, `login_success`, dan `logout`.

## Status CMS dan Hardcode

Struktur dump SQL sudah menyediakan komponen berikut:

| Komponen | Tabel | Kegunaan |
|---|---|---|
| Pengguna | `users` | Identitas administrator dan role |
| Halaman | `pages` | Slug, judul, isi, menu, SEO, header |
| Bagian halaman | `page_sections` | Hero atau section dengan teks, media, dan tombol |
| Teks global | `website_texts` | Label dan copy yang dapat diterjemahkan |
| Pengaturan situs | `site_settings` | Nilai konfigurasi situs |
| Berita/agenda | `cms_items` | Konten berdasarkan `kind` |
| Hero | `hero_slides` | Carousel berurutan dan publish state |
| Link section | `section_links` | Link dan urutan navigasi section |
| Program dan pengurus | `programs`, `board_members` | Konten operasional website |

Namun, menu sidebar admin dan sebagian teks status UI masih hardcode. Langkah CMS berikutnya yang disarankan adalah membuat modul **Site Configuration** untuk menu global, identitas situs, kontak, footer, SEO default, media library, dan audit log. Nilai tersebut sebaiknya dibaca melalui service/repository tunggal, bukan query langsung berulang di view.

## Rekomendasi Fase Berikutnya

### Fase 1: Stabilisasi produksi

Password admin dari dump harus segera diganti dengan password unik minimal 12 karakter setelah login pertama. Kredensial database dan `.env` tidak boleh dimasukkan ke Git. Deployment harus menggunakan HTTPS penuh, `CI_ENVIRONMENT=production`, error detail nonaktif, dan direktori `writable` tidak boleh dapat mengeksekusi PHP.

### Fase 2: Penguatan autentikasi

Rate limiting login dan pencatatan audit dasar sudah ditambahkan. Tahap berikutnya adalah notifikasi login, reset password berbasis token sekali pakai, serta opsi 2FA untuk administrator. Sistem juga perlu memisahkan permission granular dari role tunggal apabila akan ada editor, translator, reviewer, dan super administrator.

### Fase 3: CMS tanpa hardcode

Buat modul menu dinamis, site settings, footer, SEO, media library, draft-preview-publish workflow, revision history, dan audit log. Konten HTML yang diizinkan harus disanitasi berdasarkan allow-list tag dan atribut; jangan merender input HTML mentah tanpa kebijakan sanitasi.

### Fase 4: Quality gate

Tambahkan test untuk login valid, password salah, role user, session fixation, CSRF, upload MIME palsu, akses admin tanpa session, delete tanpa POST, dan publish state. Jalankan lint, unit test, dependency audit, dan pemeriksaan konfigurasi pada setiap deployment.

## Catatan Verifikasi

Pemeriksaan otomatis token CSRF pada form POST admin berhasil. Lint `php -l` terhadap seluruh file PHP yang berubah berhasil tanpa error. Migration audit log belum dijalankan terhadap database production; jalankan migration melalui proses deployment resmi setelah backup database.

## Referensi

[1]: https://owasp.org/www-project-authentication-cheat-sheet/ "OWASP Authentication Cheat Sheet"
[2]: https://owasp.org/www-project-session-management-cheat-sheet/ "OWASP Session Management Cheat Sheet"
[3]: https://owasp.org/www-community/attacks/csrf "OWASP Cross-Site Request Forgery Prevention"
[4]: https://codeigniter.com/user_guide/libraries/security.html "CodeIgniter 4 Security"
[5]: https://codeigniter.com/user_guide/incoming/filters.html "CodeIgniter 4 Filters"

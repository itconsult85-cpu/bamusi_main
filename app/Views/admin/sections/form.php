<?= $this->extend('admin/layout/template'); ?>

<?= $this->section('content'); ?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0"><?= isset($section) ? 'Edit Section: ' . esc($section['section_name']) : 'Tambah Section Baru' ?></h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="<?= base_url('admin/sections/save'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= isset($section) ? $section['id'] : '' ?>">
                <?= csrf_field() ?>

            <div class="row">
                <!-- KOLOM KIRI (Teks & Konten) -->
                <div class="col-lg-8">

                    <!-- Box: Identitas Sistem -->
                    <div class="card card-dark card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Identitas Section</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label>Nama Section (Bisa Diubah)</label>
                                <input type="text" name="section_name" class="form-control" required placeholder="Contoh: Hero Homepage" value="<?= isset($section) ? esc($section['section_name']) : '' ?>">
                                <small class="text-muted">Nama ini hanya untuk memudahkan pengelolaan di CMS dan tidak mengubah tampilan website.</small>
                            </div>
                            <div class="col-md-6">
                                <label>Section Key (Kunci Sistem)</label>
                                <input type="text" name="section_key" class="form-control" required placeholder="Contoh: hero_main" value="<?= isset($section) ? esc($section['section_key']) : '' ?>" <?= isset($section) ? 'readonly' : '' ?>>
                                <small class="text-warning">Kunci ini dipakai template homepage. Jangan diubah saat edit agar section tetap muncul di lokasi yang benar.</small>
                            </div>
                            <?php if (isset($section)): ?>
                            <div class="col-md-6 mt-3">
                                <label>Mode Layout Homepage</label>
                                <select name="layout_mode" class="form-select">
                                    <option value="legacy" <?= ($section['layout_mode'] ?? 'legacy') === 'legacy' ? 'selected' : ''; ?>>Legacy (layout existing)</option>
                                    <option value="builder" <?= ($section['layout_mode'] ?? '') === 'builder' ? 'selected' : ''; ?>>Builder (blok modular)</option>
                                </select>
                                <small class="text-muted">Builder aktif setelah blok ditambahkan. Jika belum ada blok, layout lama tetap dipakai.</small>
                            </div>
                            <div class="col-md-6 mt-3"><label>Opsi Layout JSON <span class="text-muted">(opsional)</span></label><input name="layout_options" class="form-control font-monospace" value="<?= esc($section['layout_options'] ?? ''); ?>" placeholder='{"background":"light"}'><small class="text-muted">Pengaturan tambahan untuk tema section.</small></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Box: Elemen Teks Pendek -->
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Elemen Teks Pendek</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Label Section</label>
                                <input type="text" name="label" class="form-control" placeholder="Contoh: SEJARAH atau 01 / 04" value="<?= isset($section) ? esc($section['label'] ?? '') : '' ?>">
                                <small class="text-muted">Label visual section, berbeda dari kicker. Opsional dan berlaku untuk semua section.</small>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6"><label>Ukuran Label</label><input type="number" name="label_size" min="1" max="200" class="form-control" value="<?= (int) ($section['label_size'] ?? 96) ?>"></div>
                                <div class="col-md-6"><label>Ukuran Judul</label><input type="number" name="title_size" min="1" max="200" class="form-control" value="<?= (int) ($section['title_size'] ?? 56) ?>"></div>
                            </div>
                            <div class="mb-3">
                                <label>Kicker / Tagline Atas</label>
                                <input type="text" name="kicker" class="form-control" placeholder="Contoh: MARI MERAWAT INDONESIA" value="<?= isset($section) ? esc($section['kicker']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label>Judul Utama (Title)</label>
                                <input type="text" name="title" class="form-control" placeholder="Judul besar section..." value="<?= isset($section) ? esc($section['title']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label>Sub-judul / Lead</label>
                                <textarea name="subtitle" class="form-control" rows="2" placeholder="Teks pengantar di bawah judul..."><?= isset($section) ? esc($section['subtitle']) : '' ?></textarea>
                            </div>
                            <div class="mb-0">
                                <label>Kutipan Tokoh (Quote)</label>
                                <textarea name="quote" class="form-control" rows="2" placeholder="Contoh: Kutipan dari Ketua Umum..."><?= isset($section) ? esc($section['quote']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Box: Visual Section Builder -->
                    <?php if (isset($section)): ?>
                    <div class="card card-dark card-outline mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div><h5 class="card-title m-0"><i class="fas fa-layer-group me-2"></i>Visual Section Builder</h5><small class="text-muted">Susun beberapa layout dalam satu section seperti WordPress.</small></div>
                            <a href="<?= base_url('admin/sections/block/create/' . $section['section_key']); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Tambah Blok</a>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info small"><strong>Workflow:</strong> pilih mode <strong>Builder</strong> di atas, tambahkan blok, lalu atur urutan. Data lama tidak dihapus dan masih dapat dipulihkan dengan mode Legacy.</div>
                            <?php if (!empty($sectionBlocks)): ?>
                            <div class="table-responsive"><table class="table table-sm table-striped align-middle mb-0"><thead><tr><th>Urutan</th><th>Komponen</th><th>Judul</th><th>Sumber</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                                <?php foreach ($sectionBlocks as $sectionBlock): ?><tr><td><?= (int) $sectionBlock['sort_order']; ?></td><td><span class="badge text-bg-dark"><?= esc($sectionBlock['block_type']); ?></span></td><td><?= esc($sectionBlock['data']['title'] ?? '-'); ?></td><td><?= esc($sectionBlock['data']['source'] ?? 'manual'); ?></td><td><?= !empty($sectionBlock['published']) ? '<span class="badge text-bg-success">Aktif</span>' : '<span class="badge text-bg-secondary">Draft</span>'; ?></td><td class="text-nowrap"><a href="<?= base_url('admin/sections/block/edit/' . $sectionBlock['id']); ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a><form method="post" action="<?= base_url('admin/sections/block/delete/' . $sectionBlock['id']); ?>" class="d-inline" onsubmit="return confirm('Hapus blok layout ini?');"><?= csrf_field(); ?><button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form></td></tr><?php endforeach; ?>
                            </tbody></table></div>
                            <?php else: ?><div class="text-muted small">Belum ada blok builder. Homepage masih menggunakan layout Legacy.</div><?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (($section['section_key'] ?? '') === 'nilai'): ?>
                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header"><h5 class="card-title m-0"><i class="fas fa-star me-2"></i>Data Kartu Nilai Utama</h5><small class="text-muted">Data ini adalah bagian dari section Nilai dan disimpan bersama form ini.</small></div>
                        <div class="card-body" id="aboutValuesRows">
                            <?php foreach (($aboutValues ?? []) as $i => $value): ?>
                            <div class="border rounded p-3 mb-3 about-value-row"><input type="hidden" name="about_values[<?= $i ?>][id]" value="<?= (int) $value['id'] ?>"><div class="row g-2"><div class="col-md-4"><label>Label</label><input name="about_values[<?= $i ?>][label]" class="form-control" value="<?= esc($value['label']) ?>"></div><div class="col-md-5"><label>Deskripsi</label><textarea name="about_values[<?= $i ?>][description]" class="form-control" rows="2"><?= esc($value['description'] ?? '') ?></textarea></div><div class="col-md-2"><label>Urutan</label><input type="number" name="about_values[<?= $i ?>][sort_order]" class="form-control" value="<?= (int) $value['sort_order'] ?>"></div><div class="col-md-1 d-flex align-items-end"><label class="small"><input type="checkbox" name="about_values[<?= $i ?>][published]" value="1" <?= !empty($value['published']) ? 'checked' : '' ?>> Aktif</label></div></div></div>
                            <?php endforeach; ?>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addValueRow"><i class="fas fa-plus me-1"></i>Tambah Kartu</button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (($section['section_key'] ?? '') === 'about'): ?>
                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header"><h5 class="card-title m-0"><i class="fas fa-link me-2"></i>Link Section</h5><small class="text-muted">Link navigasi ini adalah bagian dari section Tentang dan dikelola di sini.</small></div>
                        <div class="card-body" id="sectionLinksRows">
                            <?php foreach (($sectionLinks ?? []) as $i => $link): ?>
                            <div class="border rounded p-3 mb-3 section-link-row"><input type="hidden" name="section_links[<?= $i ?>][id]" value="<?= (int) $link['id'] ?>"><div class="row g-2"><div class="col-md-3"><label>Label</label><input name="section_links[<?= $i ?>][label]" class="form-control" value="<?= esc($link['label']) ?>"></div><div class="col-md-3"><label>Sub-label</label><input name="section_links[<?= $i ?>][sublabel]" class="form-control" value="<?= esc($link['sublabel'] ?? '') ?>"></div><div class="col-md-3"><label>URL</label><input name="section_links[<?= $i ?>][url]" class="form-control" value="<?= esc($link['url'] ?? '') ?>"></div><div class="col-md-2"><label>Urutan</label><input type="number" name="section_links[<?= $i ?>][sort_order]" class="form-control" value="<?= (int) $link['sort_order'] ?>"></div><div class="col-md-1 d-flex align-items-end"><label class="small"><input type="checkbox" name="section_links[<?= $i ?>][published]" value="1" <?= !empty($link['published']) ? 'checked' : '' ?>> Aktif</label></div></div></div>
                            <?php endforeach; ?>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addLinkRow"><i class="fas fa-plus me-1"></i>Tambah Link</button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Box: Konten & Artikel -->
                    <?php if (isset($section)): ?>
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0"><i class="fas fa-list me-2"></i>Item Konten Section</h5>
                            <a href="<?= base_url('admin/sections/item/create/' . $section['section_key']); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Tambah Item</a>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Gunakan item ini untuk konten berulang seperti kartu, pilihan, sosial media, mitra, atau daftar lain. Form inputnya seragam: teks, URL, media, urutan, dan status aktif.</p>
                            <?php if (!empty($sectionItems)): ?>
                                <div class="table-responsive"><table class="table table-sm table-striped align-middle mb-0"><thead><tr><th>Urutan</th><th>Judul / Label</th><th>Isi</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                                <?php foreach ($sectionItems as $sectionItem): ?><tr><td><?= (int) $sectionItem['sort_order']; ?></td><td><?= esc($sectionItem['title'] ?: $sectionItem['label'] ?: '-'); ?></td><td><?= esc(mb_strimwidth((string) ($sectionItem['body'] ?: $sectionItem['url'] ?: ''), 0, 70, '…')); ?></td><td><?= !empty($sectionItem['published']) ? '<span class="badge text-bg-success">Aktif</span>' : '<span class="badge text-bg-secondary">Nonaktif</span>'; ?></td><td class="text-nowrap"><a href="<?= base_url('admin/sections/item/edit/' . $sectionItem['id']); ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a><form method="post" action="<?= base_url('admin/sections/item/delete/' . $sectionItem['id']); ?>" class="d-inline" onsubmit="return confirm('Hapus item section ini?');"><?= csrf_field(); ?><button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form></td></tr><?php endforeach; ?></tbody></table></div>
                            <?php else: ?><div class="text-muted small">Belum ada item tambahan pada section ini.</div><?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Box: Konten Tambahan Seragam -->
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Konten Tambahan Section</h5>
                            <small class="text-muted">Bidang opsional yang tersedia secara seragam untuk semua section.</small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3"><label>Konten Tambahan 1</label><textarea name="vision" class="form-control" rows="4" placeholder="Paragraf tambahan pertama (opsional)."><?= esc($section['vision'] ?? '') ?></textarea></div>
                            <div class="mb-3"><label>Konten Tambahan 2</label><textarea name="mission" class="form-control" rows="5" placeholder="Paragraf tambahan kedua (opsional)."><?= esc($section['mission'] ?? '') ?></textarea></div>
                            <label>Konten Artikel / Deskripsi Panjang</label>
                            <textarea name="content" class="form-control summernote"><?= isset($section) ? $section['content'] : '' ?></textarea>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN (Aksi, Media, Publish) -->
                <div class="col-lg-4">

                    <!-- Box: Tombol Aksi (CTA) -->
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Tombol Aksi (CTA)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Teks Tombol</label>
                                <input type="text" name="button_label" class="form-control" placeholder="Contoh: Lihat Agenda" value="<?= isset($section) ? esc($section['button_label']) : '' ?>">
                            </div>
                            <div class="mb-0">
                                <label>URL / Tautan Tombol</label>
                                <input type="text" name="button_url" class="form-control" placeholder="Contoh: #agenda atau https://..." value="<?= isset($section) ? esc($section['button_url']) : '' ?>">
                            </div>
                            <hr>
<div class="mb-3"><label>Posisi Tombol</label><select name="button_position" class="form-select"><option value="left" <?= ($section['button_position'] ?? 'center') === 'left' ? 'selected' : '' ?>>Kiri</option><option value="center" <?= ($section['button_position'] ?? 'center') === 'center' ? 'selected' : '' ?>>Tengah</option><option value="right" <?= ($section['button_position'] ?? 'center') === 'right' ? 'selected' : '' ?>>Kanan</option></select></div>
                            <div class="mb-0"><label>Letak Tombol</label><select name="button_location" class="form-select"><option value="top" <?= ($section['button_location'] ?? 'bottom') === 'top' ? 'selected' : '' ?>>Di atas konten</option><option value="bottom" <?= ($section['button_location'] ?? 'bottom') === 'bottom' ? 'selected' : '' ?>>Di bawah konten</option></select></div>
                        </div>
                    </div>

                    <!-- Box: Upload Media -->
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Media Utama</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Unggah Gambar / Video Latar</label>
                                <input type="file" name="media_url" class="form-control" accept="image/*,video/mp4">
                            </div>
                            <?php if (isset($section) && !empty($section['media_url'])): ?>
                                <div class="alert alert-light border">
                                    <strong>Media Saat Ini:</strong><br>
                                    <a href="<?= base_url($section['media_url']) ?>" target="_blank" class="text-decoration-none">Buka Pratinjau Tautan <i class="fas fa-external-link-alt ms-1"></i></a>
                                </div>
                            <?php else: ?>
                                <small class="text-muted">Isi jika section ini butuh visual.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Box: Publish & Simpan -->
                    <div class="card card-secondary mb-4">
                        <div class="card-body">
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" style="width: 2.5em; height: 1.25em;" type="checkbox" name="published" value="1" id="publishCheck" <?= (!isset($section) || $section['published'] == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label ms-2 pt-1 fw-bold" for="publishCheck">Tampilkan di Web</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-2"><i class="fas fa-save me-2"></i> Simpan & Terjemahkan</button>
                            <a href="<?= base_url('admin/sections') ?>" class="btn btn-outline-secondary w-100">Batal & Kembali</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>

    function addRepeaterRow(containerId, className, html) {
        const container = document.getElementById(containerId);
        const index = container.querySelectorAll('.' + className).length;
        container.insertAdjacentHTML('beforeend', html.replaceAll('__INDEX__', index));
    }
    document.getElementById('addValueRow')?.addEventListener('click', () => addRepeaterRow('aboutValuesRows', 'about-value-row', `<div class="border rounded p-3 mb-3 about-value-row"><input type="hidden" name="about_values[__INDEX__][id]" value=""><div class="row g-2"><div class="col-md-4"><label>Label</label><input name="about_values[__INDEX__][label]" class="form-control"></div><div class="col-md-5"><label>Deskripsi</label><textarea name="about_values[__INDEX__][description]" class="form-control" rows="2"></textarea></div><div class="col-md-2"><label>Urutan</label><input type="number" name="about_values[__INDEX__][sort_order]" class="form-control" value="0"></div><div class="col-md-1 d-flex align-items-end"><label class="small"><input type="checkbox" name="about_values[__INDEX__][published]" value="1" checked> Aktif</label></div></div></div>`));
    document.getElementById('addLinkRow')?.addEventListener('click', () => addRepeaterRow('sectionLinksRows', 'section-link-row', `<div class="border rounded p-3 mb-3 section-link-row"><input type="hidden" name="section_links[__INDEX__][id]" value=""><div class="row g-2"><div class="col-md-3"><label>Label</label><input name="section_links[__INDEX__][label]" class="form-control"></div><div class="col-md-3"><label>Sub-label</label><input name="section_links[__INDEX__][sublabel]" class="form-control"></div><div class="col-md-3"><label>URL</label><input name="section_links[__INDEX__][url]" class="form-control"></div><div class="col-md-2"><label>Urutan</label><input type="number" name="section_links[__INDEX__][sort_order]" class="form-control" value="0"></div><div class="col-md-1 d-flex align-items-end"><label class="small"><input type="checkbox" name="section_links[__INDEX__][published]" value="1" checked> Aktif</label></div></div></div>`));

    $(document).ready(function() {
        $('.summernote').summernote({
            height: 250,
            placeholder: 'Tuliskan deskripsi panjang atau artikel di sini...',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'video']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
<?= $this->endSection(); ?>

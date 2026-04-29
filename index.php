<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Manajemen Blog (CMS)</title>
<style>
  /* ===== RESET & BASE ===== */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; color: #333; }

  /* ===== HEADER ===== */
  header {
    background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
    color: #fff;
    padding: 18px 30px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.3);
    position: sticky; top: 0; z-index: 100;
  }
  header .logo { font-size: 26px; }
  header h1 { font-size: 1.4rem; font-weight: 700; letter-spacing: .5px; }
  header p  { font-size: .8rem; opacity: .8; margin-top: 2px; }

  /* ===== LAYOUT ===== */
  .wrapper { display: flex; min-height: calc(100vh - 70px); }

  /* ===== SIDEBAR ===== */
  nav {
    width: 230px; min-width: 230px;
    background: #1e2a3a;
    padding: 24px 0;
  }
  nav .menu-label {
    color: #90a4ae;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0 20px 10px;
  }
  nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #cfd8dc;
    text-decoration: none;
    padding: 13px 20px;
    font-size: .95rem;
    transition: background .2s, color .2s;
    cursor: pointer;
    border-left: 3px solid transparent;
  }
  nav a:hover, nav a.aktif {
    background: #283593;
    color: #fff;
    border-left-color: #42a5f5;
  }
  nav a .icon { font-size: 1.1rem; width: 22px; text-align: center; }

  /* ===== KONTEN ===== */
  main {
    flex: 1;
    padding: 28px 30px;
    overflow-y: auto;
  }

  /* ===== PANEL ===== */
  .panel { display: none; }
  .panel.aktif { display: block; }

  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }
  .panel-header h2 { font-size: 1.3rem; color: #1a237e; }
  .panel-header p  { font-size: .85rem; color: #666; margin-top: 3px; }

  /* ===== TOMBOL ===== */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    border: none;
    border-radius: 6px;
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .2s, transform .1s;
  }
  .btn:hover  { opacity: .88; }
  .btn:active { transform: scale(.97); }
  .btn-primary  { background: #1976d2; color: #fff; }
  .btn-success  { background: #388e3c; color: #fff; }
  .btn-warning  { background: #f57c00; color: #fff; }
  .btn-danger   { background: #c62828; color: #fff; }
  .btn-secondary{ background: #546e7a; color: #fff; }
  .btn-sm { padding: 5px 11px; font-size: .8rem; }

  /* ===== CARD ===== */
  .card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,.08);
    overflow: hidden;
  }

  /* ===== TABEL ===== */
  table { width: 100%; border-collapse: collapse; }
  thead th {
    background: #1a237e;
    color: #fff;
    padding: 13px 14px;
    text-align: left;
    font-size: .85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
  }
  tbody tr { border-bottom: 1px solid #eee; }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #f5f7ff; }
  tbody td { padding: 11px 14px; font-size: .9rem; vertical-align: middle; }

  /* foto dalam tabel */
  .foto-tabel {
    width: 52px; height: 52px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
  }
  .gambar-tabel {
    width: 80px; height: 55px;
    border-radius: 6px;
    object-fit: cover;
    border: 1px solid #e0e0e0;
  }

  /* badge */
  .badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
  }
  .badge-blue  { background: #e3f2fd; color: #1565c0; }
  .badge-green { background: #e8f5e9; color: #2e7d32; }

  /* aksi */
  .aksi-group { display: flex; gap: 6px; }

  /* loading */
  .loading-row td { text-align: center; padding: 40px; color: #888; font-size: .95rem; }

  /* ===== MODAL ===== */
  .modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 200;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-backdrop.buka { display: flex; }

  .modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,.3);
    animation: slideDown .25s ease;
  }
  @keyframes slideDown {
    from { transform: translateY(-30px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  .modal-header {
    padding: 18px 22px;
    background: linear-gradient(135deg, #1a237e, #283593);
    color: #fff;
    border-radius: 12px 12px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .modal-header h3 { font-size: 1.05rem; }
  .modal-close {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.4rem;
    cursor: pointer;
    line-height: 1;
    opacity: .8;
  }
  .modal-close:hover { opacity: 1; }

  .modal-body { padding: 22px; }
  .modal-footer {
    padding: 14px 22px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }

  /* ===== FORM ===== */
  .form-group { margin-bottom: 16px; }
  label {
    display: block;
    font-size: .85rem;
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
  }
  label .required { color: #c62828; }
  input[type="text"],
  input[type="password"],
  input[type="file"],
  select,
  textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #ccc;
    border-radius: 7px;
    font-size: .9rem;
    font-family: inherit;
    transition: border-color .2s;
    background: #fafafa;
  }
  input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #1976d2;
    background: #fff;
  }
  textarea { resize: vertical; min-height: 100px; }
  .help-text { font-size: .78rem; color: #888; margin-top: 4px; }

  /* Preview foto */
  .foto-preview {
    width: 80px; height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
    margin-top: 8px;
    display: block;
  }
  .gambar-preview {
    width: 100%; max-height: 160px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    margin-top: 8px;
    display: none;
  }

  /* ===== KONFIRMASI HAPUS ===== */
  .konfirmasi-box { text-align: center; padding: 10px 0; }
  .konfirmasi-box .konfirmasi-icon { font-size: 3rem; margin-bottom: 12px; }
  .konfirmasi-box h4 { font-size: 1.1rem; color: #333; margin-bottom: 8px; }
  .konfirmasi-box p  { font-size: .9rem;  color: #666; }

  /* ===== ALERT TOAST ===== */
  #toast {
    position: fixed;
    bottom: 28px; right: 28px;
    padding: 14px 22px;
    border-radius: 8px;
    color: #fff;
    font-size: .92rem;
    font-weight: 600;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: transform .3s, opacity .3s;
    max-width: 340px;
  }
  #toast.tampil  { transform: translateY(0); opacity: 1; }
  #toast.sukses  { background: #2e7d32; }
  #toast.error   { background: #c62828; }

  /* ===== KOSONG ===== */
  .kosong { text-align: center; padding: 60px 20px; color: #aaa; }
  .kosong .kosong-icon { font-size: 3rem; margin-bottom: 12px; }
</style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo">📝</div>
  <div>
    <h1>Sistem Manajemen Blog (CMS)</h1>
    <p>Kelola penulis, artikel, dan kategori secara mudah</p>
  </div>
</header>

<div class="wrapper">

  <!-- SIDEBAR -->
  <nav>
    <div class="menu-label">Menu Utama</div>
    <a class="menu-link aktif" data-panel="panel-penulis">
      <span class="icon">👤</span> Kelola Penulis
    </a>
    <a class="menu-link" data-panel="panel-artikel">
      <span class="icon">📄</span> Kelola Artikel
    </a>
    <a class="menu-link" data-panel="panel-kategori">
      <span class="icon">🗂️</span> Kelola Kategori Artikel
    </a>
  </nav>

  <!-- KONTEN UTAMA -->
  <main>

    <!-- ===== PANEL PENULIS ===== -->
    <div id="panel-penulis" class="panel aktif">
      <div class="panel-header">
        <div>
          <h2>👤 Kelola Penulis</h2>
          <p>Daftar seluruh penulis yang terdaftar</p>
        </div>
        <button class="btn btn-primary" onclick="bukaTambahPenulis()">➕ Tambah Penulis</button>
      </div>
      <div class="card">
        <table id="tabel-penulis">
          <thead>
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama Lengkap</th>
              <th>Username</th>
              <th>Password</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="body-penulis">
            <tr class="loading-row"><td colspan="6">⏳ Memuat data...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ===== PANEL ARTIKEL ===== -->
    <div id="panel-artikel" class="panel">
      <div class="panel-header">
        <div>
          <h2>📄 Kelola Artikel</h2>
          <p>Daftar seluruh artikel yang telah diterbitkan</p>
        </div>
        <button class="btn btn-primary" onclick="bukaTambahArtikel()">➕ Tambah Artikel</button>
      </div>
      <div class="card">
        <table id="tabel-artikel">
          <thead>
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Judul</th>
              <th>Kategori</th>
              <th>Penulis</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="body-artikel">
            <tr class="loading-row"><td colspan="7">⏳ Memuat data...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ===== PANEL KATEGORI ===== -->
    <div id="panel-kategori" class="panel">
      <div class="panel-header">
        <div>
          <h2>🗂️ Kelola Kategori Artikel</h2>
          <p>Manajemen kategori untuk pengelompokan artikel</p>
        </div>
        <button class="btn btn-primary" onclick="bukaTambahKategori()">➕ Tambah Kategori</button>
      </div>
      <div class="card">
        <table id="tabel-kategori">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Kategori</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="body-kategori">
            <tr class="loading-row"><td colspan="4">⏳ Memuat data...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div><!-- /wrapper -->


<!-- ============================================================ -->
<!--  MODAL PENULIS                                                -->
<!-- ============================================================ -->

<!-- Tambah Penulis -->
<div id="modal-tambah-penulis" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h3>➕ Tambah Penulis Baru</h3>
      <button class="modal-close" onclick="tutupModal('modal-tambah-penulis')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Nama Depan <span class="required">*</span></label>
        <input type="text" id="tp-nama-depan" placeholder="Masukkan nama depan">
      </div>
      <div class="form-group">
        <label>Nama Belakang <span class="required">*</span></label>
        <input type="text" id="tp-nama-belakang" placeholder="Masukkan nama belakang">
      </div>
      <div class="form-group">
        <label>Username <span class="required">*</span></label>
        <input type="text" id="tp-username" placeholder="Masukkan username (unik)">
      </div>
      <div class="form-group">
        <label>Password <span class="required">*</span></label>
        <input type="password" id="tp-password" placeholder="Masukkan password">
      </div>
      <div class="form-group">
        <label>Foto Profil</label>
        <input type="file" id="tp-foto" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewFoto(this,'tp-preview')">
        <p class="help-text">Format: JPG, PNG, GIF, WEBP. Maks 2 MB. Kosongkan untuk gunakan foto default.</p>
        <img id="tp-preview" class="foto-preview" src="uploads_penulis/default.png" alt="Preview">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-penulis')">Batal</button>
      <button class="btn btn-success" onclick="simpanPenulis()">💾 Simpan</button>
    </div>
  </div>
</div>

<!-- Edit Penulis -->
<div id="modal-edit-penulis" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h3>✏️ Edit Data Penulis</h3>
      <button class="modal-close" onclick="tutupModal('modal-edit-penulis')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="ep-id">
      <div class="form-group">
        <label>Nama Depan <span class="required">*</span></label>
        <input type="text" id="ep-nama-depan">
      </div>
      <div class="form-group">
        <label>Nama Belakang <span class="required">*</span></label>
        <input type="text" id="ep-nama-belakang">
      </div>
      <div class="form-group">
        <label>Username <span class="required">*</span></label>
        <input type="text" id="ep-username">
      </div>
      <div class="form-group">
        <label>Password Baru</label>
        <input type="password" id="ep-password" placeholder="Kosongkan jika tidak ingin mengubah password">
      </div>
      <div class="form-group">
        <label>Foto Profil</label>
        <input type="file" id="ep-foto" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewFoto(this,'ep-preview')">
        <p class="help-text">Biarkan kosong untuk mempertahankan foto saat ini.</p>
        <img id="ep-preview" class="foto-preview" src="" alt="Preview">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-penulis')">Batal</button>
      <button class="btn btn-warning" onclick="updatePenulis()">💾 Perbarui</button>
    </div>
  </div>
</div>

<!-- Hapus Penulis -->
<div id="modal-hapus-penulis" class="modal-backdrop">
  <div class="modal" style="max-width:420px">
    <div class="modal-header" style="background:linear-gradient(135deg,#b71c1c,#c62828)">
      <h3>🗑️ Konfirmasi Hapus</h3>
      <button class="modal-close" onclick="tutupModal('modal-hapus-penulis')">✕</button>
    </div>
    <div class="modal-body">
      <div class="konfirmasi-box">
        <div class="konfirmasi-icon">⚠️</div>
        <h4>Hapus Penulis?</h4>
        <p>Data penulis <strong id="hp-nama"></strong> akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <input type="hidden" id="hp-id">
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-penulis')">Batal</button>
      <button class="btn btn-danger" onclick="hapusPenulis()">🗑️ Ya, Hapus</button>
    </div>
  </div>
</div>


<!-- ============================================================ -->
<!--  MODAL ARTIKEL                                                -->
<!-- ============================================================ -->

<!-- Tambah Artikel -->
<div id="modal-tambah-artikel" class="modal-backdrop">
  <div class="modal" style="max-width:580px">
    <div class="modal-header">
      <h3>➕ Tambah Artikel Baru</h3>
      <button class="modal-close" onclick="tutupModal('modal-tambah-artikel')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Judul Artikel <span class="required">*</span></label>
        <input type="text" id="ta-judul" placeholder="Masukkan judul artikel">
      </div>
      <div class="form-group">
        <label>Penulis <span class="required">*</span></label>
        <select id="ta-penulis"></select>
      </div>
      <div class="form-group">
        <label>Kategori <span class="required">*</span></label>
        <select id="ta-kategori"></select>
      </div>
      <div class="form-group">
        <label>Isi Artikel <span class="required">*</span></label>
        <textarea id="ta-isi" rows="5" placeholder="Tulis isi artikel di sini..."></textarea>
      </div>
      <div class="form-group">
        <label>Gambar Artikel <span class="required">*</span></label>
        <input type="file" id="ta-gambar" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewGambar(this,'ta-gambar-preview')">
        <p class="help-text">Format: JPG, PNG, GIF, WEBP. Maks 2 MB. Wajib diunggah.</p>
        <img id="ta-gambar-preview" class="gambar-preview" alt="Preview Gambar">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-artikel')">Batal</button>
      <button class="btn btn-success" onclick="simpanArtikel()">💾 Simpan</button>
    </div>
  </div>
</div>

<!-- Edit Artikel -->
<div id="modal-edit-artikel" class="modal-backdrop">
  <div class="modal" style="max-width:580px">
    <div class="modal-header">
      <h3>✏️ Edit Artikel</h3>
      <button class="modal-close" onclick="tutupModal('modal-edit-artikel')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="ea-id">
      <div class="form-group">
        <label>Judul Artikel <span class="required">*</span></label>
        <input type="text" id="ea-judul">
      </div>
      <div class="form-group">
        <label>Penulis <span class="required">*</span></label>
        <select id="ea-penulis"></select>
      </div>
      <div class="form-group">
        <label>Kategori <span class="required">*</span></label>
        <select id="ea-kategori"></select>
      </div>
      <div class="form-group">
        <label>Isi Artikel <span class="required">*</span></label>
        <textarea id="ea-isi" rows="5"></textarea>
      </div>
      <div class="form-group">
        <label>Ganti Gambar</label>
        <input type="file" id="ea-gambar" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewGambar(this,'ea-gambar-preview')">
        <p class="help-text">Biarkan kosong untuk mempertahankan gambar saat ini.</p>
        <img id="ea-gambar-preview" class="gambar-preview" alt="Preview Gambar">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-artikel')">Batal</button>
      <button class="btn btn-warning" onclick="updateArtikel()">💾 Perbarui</button>
    </div>
  </div>
</div>

<!-- Hapus Artikel -->
<div id="modal-hapus-artikel" class="modal-backdrop">
  <div class="modal" style="max-width:420px">
    <div class="modal-header" style="background:linear-gradient(135deg,#b71c1c,#c62828)">
      <h3>🗑️ Konfirmasi Hapus</h3>
      <button class="modal-close" onclick="tutupModal('modal-hapus-artikel')">✕</button>
    </div>
    <div class="modal-body">
      <div class="konfirmasi-box">
        <div class="konfirmasi-icon">⚠️</div>
        <h4>Hapus Artikel?</h4>
        <p>Artikel <strong id="ha-judul"></strong> akan dihapus beserta gambarnya secara permanen.</p>
      </div>
      <input type="hidden" id="ha-id">
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-artikel')">Batal</button>
      <button class="btn btn-danger" onclick="hapusArtikel()">🗑️ Ya, Hapus</button>
    </div>
  </div>
</div>


<!-- ============================================================ -->
<!--  MODAL KATEGORI                                               -->
<!-- ============================================================ -->

<!-- Tambah Kategori -->
<div id="modal-tambah-kategori" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h3>➕ Tambah Kategori Baru</h3>
      <button class="modal-close" onclick="tutupModal('modal-tambah-kategori')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Nama Kategori <span class="required">*</span></label>
        <input type="text" id="tk-nama" placeholder="Masukkan nama kategori">
      </div>
      <div class="form-group">
        <label>Keterangan</label>
        <textarea id="tk-keterangan" rows="3" placeholder="Deskripsi singkat kategori (opsional)"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-kategori')">Batal</button>
      <button class="btn btn-success" onclick="simpanKategori()">💾 Simpan</button>
    </div>
  </div>
</div>

<!-- Edit Kategori -->
<div id="modal-edit-kategori" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h3>✏️ Edit Kategori</h3>
      <button class="modal-close" onclick="tutupModal('modal-edit-kategori')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="ek-id">
      <div class="form-group">
        <label>Nama Kategori <span class="required">*</span></label>
        <input type="text" id="ek-nama">
      </div>
      <div class="form-group">
        <label>Keterangan</label>
        <textarea id="ek-keterangan" rows="3"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-kategori')">Batal</button>
      <button class="btn btn-warning" onclick="updateKategori()">💾 Perbarui</button>
    </div>
  </div>
</div>

<!-- Hapus Kategori -->
<div id="modal-hapus-kategori" class="modal-backdrop">
  <div class="modal" style="max-width:420px">
    <div class="modal-header" style="background:linear-gradient(135deg,#b71c1c,#c62828)">
      <h3>🗑️ Konfirmasi Hapus</h3>
      <button class="modal-close" onclick="tutupModal('modal-hapus-kategori')">✕</button>
    </div>
    <div class="modal-body">
      <div class="konfirmasi-box">
        <div class="konfirmasi-icon">⚠️</div>
        <h4>Hapus Kategori?</h4>
        <p>Kategori <strong id="hk-nama"></strong> akan dihapus secara permanen.</p>
      </div>
      <input type="hidden" id="hk-id">
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-kategori')">Batal</button>
      <button class="btn btn-danger" onclick="hapusKategori()">🗑️ Ya, Hapus</button>
    </div>
  </div>
</div>


<!-- TOAST -->
<div id="toast"></div>


<!-- ============================================================ -->
<!--  JAVASCRIPT                                                   -->
<!-- ============================================================ -->
<script>
/* ===== NAVIGASI PANEL ===== */
document.querySelectorAll('.menu-link').forEach(link => {
  link.addEventListener('click', () => {
    document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('aktif'));
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('aktif'));

    link.classList.add('aktif');
    const target = link.dataset.panel;
    document.getElementById(target).classList.add('aktif');

    if (target === 'panel-penulis')  muatPenulis();
    if (target === 'panel-artikel')  muatArtikel();
    if (target === 'panel-kategori') muatKategori();
  });
});

/* ===== MODAL HELPERS ===== */
function bukaModal(id)  { document.getElementById(id).classList.add('buka'); }
function tutupModal(id) {
  document.getElementById(id).classList.remove('buka');
  /* Reset file input */
  document.querySelectorAll(`#${id} input[type="file"]`).forEach(f => f.value = '');
}

/* Klik backdrop tutup modal */
document.querySelectorAll('.modal-backdrop').forEach(b => {
  b.addEventListener('click', e => { if (e.target === b) tutupModal(b.id); });
});

/* ===== TOAST ===== */
function tampilToast(pesan, tipe = 'sukses') {
  const t = document.getElementById('toast');
  t.textContent = pesan;
  t.className   = `tampil ${tipe}`;
  clearTimeout(t._timer);
  t._timer = setTimeout(() => { t.classList.remove('tampil'); }, 3500);
}

/* ===== PREVIEW GAMBAR/FOTO ===== */
function previewFoto(input, targetId) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => { document.getElementById(targetId).src = e.target.result; };
  reader.readAsDataURL(file);
}

function previewGambar(input, targetId) {
  const file = input.files[0];
  const img  = document.getElementById(targetId);
  if (!file) { img.style.display = 'none'; return; }
  const reader = new FileReader();
  reader.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
  reader.readAsDataURL(file);
}

/* ================================================================ */
/*  PENULIS                                                          */
/* ================================================================ */

async function muatPenulis() {
  document.getElementById('body-penulis').innerHTML =
    '<tr class="loading-row"><td colspan="6">⏳ Memuat data...</td></tr>';
  try {
    const res  = await fetch('ambil_penulis.php');
    const json = await res.json();
    if (!json.sukses) throw new Error(json.pesan);

    const tbody = document.getElementById('body-penulis');
    if (!json.data.length) {
      tbody.innerHTML = '<tr class="loading-row"><td colspan="6">📭 Belum ada data penulis.</td></tr>';
      return;
    }
    tbody.innerHTML = json.data.map((p, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><img src="uploads_penulis/${p.foto}" class="foto-tabel" onerror="this.src='uploads_penulis/default.png'" alt="foto"></td>
        <td><strong>${p.nama_depan} ${p.nama_belakang}</strong></td>
        <td><span class="badge badge-blue">${p.user_name}</span></td>
        <td><em style="color:#999">Terenkripsi</em></td>
        <td>
          <div class="aksi-group">
            <button class="btn btn-warning btn-sm" onclick="bukaEditPenulis(${p.id})">✏️ Edit</button>
            <button class="btn btn-danger btn-sm"  onclick="bukaHapusPenulis(${p.id},'${p.nama_depan} ${p.nama_belakang}')">🗑️ Hapus</button>
          </div>
        </td>
      </tr>`).join('');
  } catch (err) {
    tampilToast('Gagal memuat penulis: ' + err.message, 'error');
  }
}

function bukaTambahPenulis() {
  ['tp-nama-depan','tp-nama-belakang','tp-username','tp-password'].forEach(id => {
    document.getElementById(id).value = '';
  });
  document.getElementById('tp-preview').src = 'uploads_penulis/default.png';
  bukaModal('modal-tambah-penulis');
}

async function simpanPenulis() {
  const nd = document.getElementById('tp-nama-depan').value.trim();
  const nb = document.getElementById('tp-nama-belakang').value.trim();
  const un = document.getElementById('tp-username').value.trim();
  const pw = document.getElementById('tp-password').value.trim();
  if (!nd || !nb || !un || !pw) { tampilToast('Semua field wajib diisi!', 'error'); return; }

  const fd = new FormData();
  fd.append('nama_depan',    nd);
  fd.append('nama_belakang', nb);
  fd.append('user_name',     un);
  fd.append('password',      pw);
  const fotoFile = document.getElementById('tp-foto').files[0];
  if (fotoFile) fd.append('foto', fotoFile);

  try {
    const res  = await fetch('simpan_penulis.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-tambah-penulis');
      tampilToast(json.pesan, 'sukses');
      muatPenulis();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

async function bukaEditPenulis(id) {
  try {
    const res  = await fetch(`ambil_satu_penulis.php?id=${id}`);
    const json = await res.json();
    if (!json.sukses) { tampilToast(json.pesan, 'error'); return; }
    const d = json.data;
    document.getElementById('ep-id').value          = d.id;
    document.getElementById('ep-nama-depan').value  = d.nama_depan;
    document.getElementById('ep-nama-belakang').value = d.nama_belakang;
    document.getElementById('ep-username').value    = d.user_name;
    document.getElementById('ep-password').value    = '';
    document.getElementById('ep-preview').src       = 'uploads_penulis/' + d.foto;
    bukaModal('modal-edit-penulis');
  } catch { tampilToast('Gagal mengambil data.', 'error'); }
}

async function updatePenulis() {
  const id = document.getElementById('ep-id').value;
  const nd = document.getElementById('ep-nama-depan').value.trim();
  const nb = document.getElementById('ep-nama-belakang').value.trim();
  const un = document.getElementById('ep-username').value.trim();
  if (!nd || !nb || !un) { tampilToast('Nama dan username wajib diisi!', 'error'); return; }

  const fd = new FormData();
  fd.append('id',            id);
  fd.append('nama_depan',    nd);
  fd.append('nama_belakang', nb);
  fd.append('user_name',     un);
  fd.append('password',      document.getElementById('ep-password').value.trim());
  const fotoFile = document.getElementById('ep-foto').files[0];
  if (fotoFile) fd.append('foto', fotoFile);

  try {
    const res  = await fetch('update_penulis.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-edit-penulis');
      tampilToast(json.pesan, 'sukses');
      muatPenulis();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

function bukaHapusPenulis(id, nama) {
  document.getElementById('hp-id').value    = id;
  document.getElementById('hp-nama').textContent = nama;
  bukaModal('modal-hapus-penulis');
}

async function hapusPenulis() {
  const id = document.getElementById('hp-id').value;
  const fd = new FormData(); fd.append('id', id);
  try {
    const res  = await fetch('hapus_penulis.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-hapus-penulis');
      tampilToast(json.pesan, 'sukses');
      muatPenulis();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

/* ================================================================ */
/*  ARTIKEL                                                          */
/* ================================================================ */

async function muatArtikel() {
  document.getElementById('body-artikel').innerHTML =
    '<tr class="loading-row"><td colspan="7">⏳ Memuat data...</td></tr>';
  try {
    const res  = await fetch('ambil_artikel.php');
    const json = await res.json();
    if (!json.sukses) throw new Error(json.pesan);

    const tbody = document.getElementById('body-artikel');
    if (!json.data.length) {
      tbody.innerHTML = '<tr class="loading-row"><td colspan="7">📭 Belum ada artikel.</td></tr>';
      return;
    }
    tbody.innerHTML = json.data.map((a, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><img src="uploads_artikel/${a.gambar}" class="gambar-tabel" onerror="this.src=''" alt="gambar"></td>
        <td><strong>${a.judul}</strong></td>
        <td><span class="badge badge-blue">${a.nama_kategori}</span></td>
        <td><span class="badge badge-green">${a.nama_penulis}</span></td>
        <td style="font-size:.8rem;color:#555">${a.hari_tanggal}</td>
        <td>
          <div class="aksi-group">
            <button class="btn btn-warning btn-sm" onclick="bukaEditArtikel(${a.id})">✏️ Edit</button>
            <button class="btn btn-danger btn-sm"  onclick="bukaHapusArtikel(${a.id},'${a.judul.replace(/'/g,"\\'")}')">🗑️ Hapus</button>
          </div>
        </td>
      </tr>`).join('');
  } catch (err) {
    tampilToast('Gagal memuat artikel: ' + err.message, 'error');
  }
}

/* Isi dropdown penulis & kategori */
async function isiDropdownArtikel(penulisSel = 0, kategoriSel = 0, prefixId = 'ta') {
  const [resPenulis, resKategori] = await Promise.all([
    fetch('ambil_penulis.php'),
    fetch('ambil_kategori.php')
  ]);
  const jp = await resPenulis.json();
  const jk = await resKategori.json();

  const selPenulis  = document.getElementById(`${prefixId}-penulis`);
  const selKategori = document.getElementById(`${prefixId}-kategori`);

  selPenulis.innerHTML = '<option value="">-- Pilih Penulis --</option>' +
    (jp.data || []).map(p => `<option value="${p.id}" ${p.id == penulisSel ? 'selected' : ''}>${p.nama_depan} ${p.nama_belakang}</option>`).join('');

  selKategori.innerHTML = '<option value="">-- Pilih Kategori --</option>' +
    (jk.data || []).map(k => `<option value="${k.id}" ${k.id == kategoriSel ? 'selected' : ''}>${k.nama_kategori}</option>`).join('');
}

async function bukaTambahArtikel() {
  document.getElementById('ta-judul').value = '';
  document.getElementById('ta-isi').value   = '';
  document.getElementById('ta-gambar').value = '';
  document.getElementById('ta-gambar-preview').style.display = 'none';
  await isiDropdownArtikel(0, 0, 'ta');
  bukaModal('modal-tambah-artikel');
}

async function simpanArtikel() {
  const judul      = document.getElementById('ta-judul').value.trim();
  const penulis    = document.getElementById('ta-penulis').value;
  const kategori   = document.getElementById('ta-kategori').value;
  const isi        = document.getElementById('ta-isi').value.trim();
  const gambarFile = document.getElementById('ta-gambar').files[0];

  if (!judul || !penulis || !kategori || !isi) { tampilToast('Semua field wajib diisi!', 'error'); return; }
  if (!gambarFile) { tampilToast('Gambar artikel wajib diunggah!', 'error'); return; }

  const fd = new FormData();
  fd.append('id_penulis',  penulis);
  fd.append('id_kategori', kategori);
  fd.append('judul',       judul);
  fd.append('isi',         isi);
  fd.append('gambar',      gambarFile);

  try {
    const res  = await fetch('simpan_artikel.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-tambah-artikel');
      tampilToast(json.pesan, 'sukses');
      muatArtikel();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

async function bukaEditArtikel(id) {
  try {
    const res  = await fetch(`ambil_satu_artikel.php?id=${id}`);
    const json = await res.json();
    if (!json.sukses) { tampilToast(json.pesan, 'error'); return; }
    const d = json.data;

    await isiDropdownArtikel(d.id_penulis, d.id_kategori, 'ea');

    document.getElementById('ea-id').value    = d.id;
    document.getElementById('ea-judul').value = d.judul;
    document.getElementById('ea-isi').value   = d.isi;
    document.getElementById('ea-gambar').value = '';

    const prev = document.getElementById('ea-gambar-preview');
    prev.src = 'uploads_artikel/' + d.gambar;
    prev.style.display = 'block';

    bukaModal('modal-edit-artikel');
  } catch { tampilToast('Gagal mengambil data artikel.', 'error'); }
}

async function updateArtikel() {
  const id       = document.getElementById('ea-id').value;
  const judul    = document.getElementById('ea-judul').value.trim();
  const penulis  = document.getElementById('ea-penulis').value;
  const kategori = document.getElementById('ea-kategori').value;
  const isi      = document.getElementById('ea-isi').value.trim();

  if (!judul || !penulis || !kategori || !isi) { tampilToast('Semua field wajib diisi!', 'error'); return; }

  const fd = new FormData();
  fd.append('id',          id);
  fd.append('id_penulis',  penulis);
  fd.append('id_kategori', kategori);
  fd.append('judul',       judul);
  fd.append('isi',         isi);
  const gambarFile = document.getElementById('ea-gambar').files[0];
  if (gambarFile) fd.append('gambar', gambarFile);

  try {
    const res  = await fetch('update_artikel.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-edit-artikel');
      tampilToast(json.pesan, 'sukses');
      muatArtikel();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

function bukaHapusArtikel(id, judul) {
  document.getElementById('ha-id').value              = id;
  document.getElementById('ha-judul').textContent     = judul;
  bukaModal('modal-hapus-artikel');
}

async function hapusArtikel() {
  const id = document.getElementById('ha-id').value;
  const fd = new FormData(); fd.append('id', id);
  try {
    const res  = await fetch('hapus_artikel.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-hapus-artikel');
      tampilToast(json.pesan, 'sukses');
      muatArtikel();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

/* ================================================================ */
/*  KATEGORI                                                         */
/* ================================================================ */

async function muatKategori() {
  document.getElementById('body-kategori').innerHTML =
    '<tr class="loading-row"><td colspan="4">⏳ Memuat data...</td></tr>';
  try {
    const res  = await fetch('ambil_kategori.php');
    const json = await res.json();
    if (!json.sukses) throw new Error(json.pesan);

    const tbody = document.getElementById('body-kategori');
    if (!json.data.length) {
      tbody.innerHTML = '<tr class="loading-row"><td colspan="4">📭 Belum ada kategori.</td></tr>';
      return;
    }
    tbody.innerHTML = json.data.map((k, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><strong>${k.nama_kategori}</strong></td>
        <td>${k.keterangan || '<em style="color:#aaa">-</em>'}</td>
        <td>
          <div class="aksi-group">
            <button class="btn btn-warning btn-sm" onclick="bukaEditKategori(${k.id})">✏️ Edit</button>
            <button class="btn btn-danger btn-sm"  onclick="bukaHapusKategori(${k.id},'${k.nama_kategori.replace(/'/g,"\\'")}')">🗑️ Hapus</button>
          </div>
        </td>
      </tr>`).join('');
  } catch (err) {
    tampilToast('Gagal memuat kategori: ' + err.message, 'error');
  }
}

function bukaTambahKategori() {
  document.getElementById('tk-nama').value       = '';
  document.getElementById('tk-keterangan').value = '';
  bukaModal('modal-tambah-kategori');
}

async function simpanKategori() {
  const nama = document.getElementById('tk-nama').value.trim();
  const ket  = document.getElementById('tk-keterangan').value.trim();
  if (!nama) { tampilToast('Nama kategori wajib diisi!', 'error'); return; }

  const fd = new FormData();
  fd.append('nama_kategori', nama);
  fd.append('keterangan',    ket);

  try {
    const res  = await fetch('simpan_kategori.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-tambah-kategori');
      tampilToast(json.pesan, 'sukses');
      muatKategori();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

async function bukaEditKategori(id) {
  try {
    const res  = await fetch(`ambil_satu_kategori.php?id=${id}`);
    const json = await res.json();
    if (!json.sukses) { tampilToast(json.pesan, 'error'); return; }
    const d = json.data;
    document.getElementById('ek-id').value          = d.id;
    document.getElementById('ek-nama').value         = d.nama_kategori;
    document.getElementById('ek-keterangan').value   = d.keterangan || '';
    bukaModal('modal-edit-kategori');
  } catch { tampilToast('Gagal mengambil data kategori.', 'error'); }
}

async function updateKategori() {
  const id   = document.getElementById('ek-id').value;
  const nama = document.getElementById('ek-nama').value.trim();
  const ket  = document.getElementById('ek-keterangan').value.trim();
  if (!nama) { tampilToast('Nama kategori wajib diisi!', 'error'); return; }

  const fd = new FormData();
  fd.append('id',            id);
  fd.append('nama_kategori', nama);
  fd.append('keterangan',    ket);

  try {
    const res  = await fetch('update_kategori.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-edit-kategori');
      tampilToast(json.pesan, 'sukses');
      muatKategori();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

function bukaHapusKategori(id, nama) {
  document.getElementById('hk-id').value           = id;
  document.getElementById('hk-nama').textContent   = nama;
  bukaModal('modal-hapus-kategori');
}

async function hapusKategori() {
  const id = document.getElementById('hk-id').value;
  const fd = new FormData(); fd.append('id', id);
  try {
    const res  = await fetch('hapus_kategori.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.sukses) {
      tutupModal('modal-hapus-kategori');
      tampilToast(json.pesan, 'sukses');
      muatKategori();
    } else {
      tampilToast(json.pesan, 'error');
    }
  } catch { tampilToast('Terjadi kesalahan jaringan.', 'error'); }
}

/* ===== LOAD AWAL ===== */
muatPenulis();
</script>
</body>
</html>

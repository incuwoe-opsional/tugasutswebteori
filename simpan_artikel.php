<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id_penulis  = (int)($_POST['id_penulis']  ?? 0);
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$judul       = trim($_POST['judul']        ?? '');
$isi         = trim($_POST['isi']          ?? '');

if (!$id_penulis || !$id_kategori || !$judul || !$isi) {
    echo json_encode(['sukses' => false, 'pesan' => 'Semua field wajib diisi.']);
    exit;
}

/* Wajib ada gambar */
if (empty($_FILES['gambar']['name'])) {
    echo json_encode(['sukses' => false, 'pesan' => 'Gambar artikel wajib diunggah.']);
    exit;
}

$file_tmp  = $_FILES['gambar']['tmp_name'];
$file_size = $_FILES['gambar']['size'];

$finfo   = new finfo(FILEINFO_MIME_TYPE);
$mime    = $finfo->file($file_tmp);
$tipe_ok = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($mime, $tipe_ok)) {
    echo json_encode(['sukses' => false, 'pesan' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP.']);
    exit;
}

if ($file_size > 2 * 1024 * 1024) {
    echo json_encode(['sukses' => false, 'pesan' => 'Ukuran file maksimal 2 MB.']);
    exit;
}

$ekstensi  = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
$nama_file = uniqid('artikel_', true) . '.' . strtolower($ekstensi);
$tujuan    = __DIR__ . '/uploads_artikel/' . $nama_file;

if (!move_uploaded_file($file_tmp, $tujuan)) {
    echo json_encode(['sukses' => false, 'pesan' => 'Gagal mengunggah gambar.']);
    exit;
}

/* Buat hari_tanggal otomatis dari server */
date_default_timezone_set('Asia/Jakarta');
$hari   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$bulan  = [
    1=>'Januari', 2=>'Februari', 3=>'Maret',
    4=>'April',   5=>'Mei',      6=>'Juni',
    7=>'Juli',    8=>'Agustus',  9=>'September',
    10=>'Oktober',11=>'November',12=>'Desember'
];
$sekarang    = new DateTime();
$nama_hari   = $hari[$sekarang->format('w')];
$tanggal     = $sekarang->format('j');
$nama_bulan  = $bulan[(int)$sekarang->format('n')];
$tahun       = $sekarang->format('Y');
$jam         = $sekarang->format('H:i');
$hari_tanggal = "$nama_hari, $tanggal $nama_bulan $tahun | $jam";

$stmt = $koneksi->prepare(
    "INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param('iissss', $id_penulis, $id_kategori, $judul, $isi, $nama_file, $hari_tanggal);

if ($stmt->execute()) {
    echo json_encode(['sukses' => true, 'pesan' => 'Artikel berhasil ditambahkan.']);
} else {
    @unlink($tujuan);
    echo json_encode(['sukses' => false, 'pesan' => 'Gagal menyimpan artikel: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();

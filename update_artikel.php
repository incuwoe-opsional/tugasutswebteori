<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id          = (int)($_POST['id']          ?? 0);
$id_penulis  = (int)($_POST['id_penulis']  ?? 0);
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$judul       = trim($_POST['judul']        ?? '');
$isi         = trim($_POST['isi']          ?? '');

if ($id <= 0 || !$id_penulis || !$id_kategori || !$judul || !$isi) {
    echo json_encode(['sukses' => false, 'pesan' => 'Data tidak lengkap.']);
    exit;
}

/* Ambil gambar lama */
$cek = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$cek->bind_param('i', $id);
$cek->execute();
$lama = $cek->get_result()->fetch_assoc();
$cek->close();

if (!$lama) {
    echo json_encode(['sukses' => false, 'pesan' => 'Artikel tidak ditemukan.']);
    exit;
}

$nama_file = $lama['gambar'];

/* Upload gambar baru jika ada */
if (!empty($_FILES['gambar']['name'])) {
    $file_tmp  = $_FILES['gambar']['tmp_name'];
    $file_size = $_FILES['gambar']['size'];

    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($file_tmp);
    $tipe_ok = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $tipe_ok)) {
        echo json_encode(['sukses' => false, 'pesan' => 'Tipe file tidak diizinkan.']);
        exit;
    }

    if ($file_size > 2 * 1024 * 1024) {
        echo json_encode(['sukses' => false, 'pesan' => 'Ukuran file maksimal 2 MB.']);
        exit;
    }

    $ekstensi  = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_baru = uniqid('artikel_', true) . '.' . strtolower($ekstensi);
    $tujuan    = __DIR__ . '/uploads_artikel/' . $nama_baru;

    if (!move_uploaded_file($file_tmp, $tujuan)) {
        echo json_encode(['sukses' => false, 'pesan' => 'Gagal mengunggah gambar.']);
        exit;
    }

    @unlink(__DIR__ . '/uploads_artikel/' . $nama_file);
    $nama_file = $nama_baru;
}

$stmt = $koneksi->prepare(
    "UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=?, gambar=? WHERE id=?"
);
$stmt->bind_param('iisssi', $id_penulis, $id_kategori, $judul, $isi, $nama_file, $id);

if ($stmt->execute()) {
    echo json_encode(['sukses' => true, 'pesan' => 'Artikel berhasil diperbarui.']);
} else {
    echo json_encode(['sukses' => false, 'pesan' => 'Gagal memperbarui artikel: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();

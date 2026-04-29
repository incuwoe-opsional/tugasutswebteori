<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['sukses' => false, 'pesan' => 'ID tidak valid.']);
    exit;
}

/* Cek apakah penulis masih memiliki artikel */
$cek = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM artikel WHERE id_penulis = ?");
$cek->bind_param('i', $id);
$cek->execute();
$jumlah = $cek->get_result()->fetch_assoc()['jumlah'];
$cek->close();

if ($jumlah > 0) {
    echo json_encode(['sukses' => false, 'pesan' => 'Penulis tidak dapat dihapus karena masih memiliki artikel.']);
    exit;
}

/* Ambil nama foto sebelum dihapus */
$ambil = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$ambil->bind_param('i', $id);
$ambil->execute();
$baris = $ambil->get_result()->fetch_assoc();
$ambil->close();

if (!$baris) {
    echo json_encode(['sukses' => false, 'pesan' => 'Data tidak ditemukan.']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    /* Hapus foto dari server jika bukan default */
    if ($baris['foto'] !== 'default.png') {
        @unlink(__DIR__ . '/uploads_penulis/' . $baris['foto']);
    }
    echo json_encode(['sukses' => true, 'pesan' => 'Penulis berhasil dihapus.']);
} else {
    echo json_encode(['sukses' => false, 'pesan' => 'Gagal menghapus data: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();

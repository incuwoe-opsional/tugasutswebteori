<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['sukses' => false, 'pesan' => 'ID tidak valid.']);
    exit;
}

/* Cek apakah kategori masih dipakai */
$cek = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM artikel WHERE id_kategori = ?");
$cek->bind_param('i', $id);
$cek->execute();
$jumlah = $cek->get_result()->fetch_assoc()['jumlah'];
$cek->close();

if ($jumlah > 0) {
    echo json_encode(['sukses' => false, 'pesan' => 'Kategori tidak dapat dihapus karena masih memiliki artikel.']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM kategori_artikel WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['sukses' => true, 'pesan' => 'Kategori berhasil dihapus.']);
} else {
    echo json_encode(['sukses' => false, 'pesan' => 'Gagal menghapus: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();

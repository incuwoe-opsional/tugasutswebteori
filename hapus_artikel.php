<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['sukses' => false, 'pesan' => 'ID tidak valid.']);
    exit;
}

/* Ambil gambar sebelum dihapus */
$cek = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$cek->bind_param('i', $id);
$cek->execute();
$baris = $cek->get_result()->fetch_assoc();
$cek->close();

if (!$baris) {
    echo json_encode(['sukses' => false, 'pesan' => 'Artikel tidak ditemukan.']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM artikel WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    @unlink(__DIR__ . '/uploads_artikel/' . $baris['gambar']);
    echo json_encode(['sukses' => true, 'pesan' => 'Artikel berhasil dihapus.']);
} else {
    echo json_encode(['sukses' => false, 'pesan' => 'Gagal menghapus artikel: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();

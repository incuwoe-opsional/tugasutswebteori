<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['sukses' => false, 'pesan' => 'ID tidak valid.']);
    exit;
}

$stmt = $koneksi->prepare("SELECT id, nama_kategori, keterangan FROM kategori_artikel WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$baris = $stmt->get_result()->fetch_assoc();

if ($baris) {
    echo json_encode(['sukses' => true, 'data' => $baris]);
} else {
    echo json_encode(['sukses' => false, 'pesan' => 'Data tidak ditemukan.']);
}

$stmt->close();
$koneksi->close();

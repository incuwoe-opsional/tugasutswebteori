<?php
require 'koneksi.php';
header('Content-Type: application/json');

$hasil = $koneksi->query("SELECT id, nama_kategori, keterangan FROM kategori_artikel ORDER BY id ASC");
$data  = [];
while ($baris = $hasil->fetch_assoc()) {
    $baris['nama_kategori'] = htmlspecialchars($baris['nama_kategori']);
    $baris['keterangan']    = htmlspecialchars($baris['keterangan'] ?? '');
    $data[] = $baris;
}

echo json_encode(['sukses' => true, 'data' => $data]);
$koneksi->close();

<?php
require 'koneksi.php';
header('Content-Type: application/json');

$sql  = "SELECT id, nama_depan, nama_belakang, user_name, password, foto FROM penulis ORDER BY id ASC";
$hasil = $koneksi->query($sql);

$data = [];
while ($baris = $hasil->fetch_assoc()) {
    $baris['nama_depan']    = htmlspecialchars($baris['nama_depan']);
    $baris['nama_belakang'] = htmlspecialchars($baris['nama_belakang']);
    $baris['user_name']     = htmlspecialchars($baris['user_name']);
    $baris['foto']          = htmlspecialchars($baris['foto']);
    $data[] = $baris;
}

echo json_encode(['sukses' => true, 'data' => $data]);
$koneksi->close();

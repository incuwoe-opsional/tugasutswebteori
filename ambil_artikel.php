<?php
require 'koneksi.php';
header('Content-Type: application/json');

$sql = "SELECT a.id, a.judul, a.gambar, a.hari_tanggal,
               CONCAT(p.nama_depan, ' ', p.nama_belakang) AS nama_penulis,
               k.nama_kategori
        FROM artikel a
        JOIN penulis p          ON a.id_penulis  = p.id
        JOIN kategori_artikel k ON a.id_kategori = k.id
        ORDER BY a.id DESC";

$hasil = $koneksi->query($sql);
$data  = [];
while ($baris = $hasil->fetch_assoc()) {
    $baris['judul']         = htmlspecialchars($baris['judul']);
    $baris['gambar']        = htmlspecialchars($baris['gambar']);
    $baris['hari_tanggal']  = htmlspecialchars($baris['hari_tanggal']);
    $baris['nama_penulis']  = htmlspecialchars($baris['nama_penulis']);
    $baris['nama_kategori'] = htmlspecialchars($baris['nama_kategori']);
    $data[] = $baris;
}

echo json_encode(['sukses' => true, 'data' => $data]);
$koneksi->close();

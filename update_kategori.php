<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id            = (int)($_POST['id']            ?? 0);
$nama_kategori = trim($_POST['nama_kategori'] ?? '');
$keterangan    = trim($_POST['keterangan']    ?? '');

if ($id <= 0 || !$nama_kategori) {
    echo json_encode(['sukses' => false, 'pesan' => 'Data tidak lengkap.']);
    exit;
}

$stmt = $koneksi->prepare("UPDATE kategori_artikel SET nama_kategori=?, keterangan=? WHERE id=?");
$stmt->bind_param('ssi', $nama_kategori, $keterangan, $id);

if ($stmt->execute()) {
    echo json_encode(['sukses' => true, 'pesan' => 'Kategori berhasil diperbarui.']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['sukses' => false, 'pesan' => 'Nama kategori sudah ada.']);
    } else {
        echo json_encode(['sukses' => false, 'pesan' => 'Gagal memperbarui: ' . $stmt->error]);
    }
}

$stmt->close();
$koneksi->close();

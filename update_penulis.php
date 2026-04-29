<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id            = (int)($_POST['id']            ?? 0);
$nama_depan    = trim($_POST['nama_depan']    ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name     = trim($_POST['user_name']     ?? '');
$password      = trim($_POST['password']      ?? '');

if ($id <= 0 || !$nama_depan || !$nama_belakang || !$user_name) {
    echo json_encode(['sukses' => false, 'pesan' => 'Data tidak lengkap.']);
    exit;
}

/* Ambil data lama */
$cek  = $koneksi->prepare("SELECT foto, password FROM penulis WHERE id = ?");
$cek->bind_param('i', $id);
$cek->execute();
$lama = $cek->get_result()->fetch_assoc();
$cek->close();

if (!$lama) {
    echo json_encode(['sukses' => false, 'pesan' => 'Data tidak ditemukan.']);
    exit;
}

$nama_file = $lama['foto'];

/* ---- Upload foto baru jika ada ---- */
if (!empty($_FILES['foto']['name'])) {
    $file_tmp  = $_FILES['foto']['tmp_name'];
    $file_size = $_FILES['foto']['size'];

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

    $ekstensi  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_baru = uniqid('foto_', true) . '.' . strtolower($ekstensi);
    $tujuan    = __DIR__ . '/uploads_penulis/' . $nama_baru;

    if (!move_uploaded_file($file_tmp, $tujuan)) {
        echo json_encode(['sukses' => false, 'pesan' => 'Gagal mengunggah foto.']);
        exit;
    }

    /* Hapus foto lama jika bukan default */
    if ($nama_file !== 'default.png') {
        @unlink(__DIR__ . '/uploads_penulis/' . $nama_file);
    }

    $nama_file = $nama_baru;
}

/* Tentukan password: jika diisi, hash ulang; jika kosong, pakai yang lama */
$hash = $password ? password_hash($password, PASSWORD_BCRYPT) : $lama['password'];

$stmt = $koneksi->prepare(
    "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?"
);
$stmt->bind_param('sssssi', $nama_depan, $nama_belakang, $user_name, $hash, $nama_file, $id);

if ($stmt->execute()) {
    echo json_encode(['sukses' => true, 'pesan' => 'Data penulis berhasil diperbarui.']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['sukses' => false, 'pesan' => 'Username sudah digunakan.']);
    } else {
        echo json_encode(['sukses' => false, 'pesan' => 'Gagal memperbarui data: ' . $stmt->error]);
    }
}

$stmt->close();
$koneksi->close();

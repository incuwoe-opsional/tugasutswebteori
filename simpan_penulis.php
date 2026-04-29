<?php
require 'koneksi.php';
header('Content-Type: application/json');

$nama_depan    = trim($_POST['nama_depan']    ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name     = trim($_POST['user_name']     ?? '');
$password      = trim($_POST['password']      ?? '');

if (!$nama_depan || !$nama_belakang || !$user_name || !$password) {
    echo json_encode(['sukses' => false, 'pesan' => 'Semua field wajib diisi.']);
    exit;
}

/* ---- Upload foto ---- */
$nama_file = 'default.png';

if (!empty($_FILES['foto']['name'])) {
    $file_tmp  = $_FILES['foto']['tmp_name'];
    $file_size = $_FILES['foto']['size'];

    /* Validasi tipe file dengan finfo */
    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime      = $finfo->file($file_tmp);
    $tipe_ok   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $tipe_ok)) {
        echo json_encode(['sukses' => false, 'pesan' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP.']);
        exit;
    }

    if ($file_size > 2 * 1024 * 1024) {
        echo json_encode(['sukses' => false, 'pesan' => 'Ukuran file maksimal 2 MB.']);
        exit;
    }

    $ekstensi  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_file = uniqid('foto_', true) . '.' . strtolower($ekstensi);
    $tujuan    = __DIR__ . '/uploads_penulis/' . $nama_file;

    if (!move_uploaded_file($file_tmp, $tujuan)) {
        echo json_encode(['sukses' => false, 'pesan' => 'Gagal mengunggah foto.']);
        exit;
    }
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $koneksi->prepare(
    "INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param('sssss', $nama_depan, $nama_belakang, $user_name, $hash, $nama_file);

if ($stmt->execute()) {
    echo json_encode(['sukses' => true, 'pesan' => 'Penulis berhasil ditambahkan.']);
} else {
    /* Hapus foto yang sudah terupload jika insert gagal */
    if ($nama_file !== 'default.png') {
        @unlink(__DIR__ . '/uploads_penulis/' . $nama_file);
    }
    if ($koneksi->errno === 1062) {
        echo json_encode(['sukses' => false, 'pesan' => 'Username sudah digunakan.']);
    } else {
        echo json_encode(['sukses' => false, 'pesan' => 'Gagal menyimpan data: ' . $stmt->error]);
    }
}

$stmt->close();
$koneksi->close();

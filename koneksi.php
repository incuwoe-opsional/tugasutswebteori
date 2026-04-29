<?php
$host     = '127.0.0.1';
$user     = 'root';
$password = '';
$database = 'db_blog';
$port     = 3307;

$koneksi = new mysqli($host, $user, $password, $database, $port);
$koneksi->set_charset('utf8mb4');

if ($koneksi->connect_error) {
    http_response_code(500);
    die(json_encode(['sukses' => false, 'pesan' => 'Koneksi database gagal: ' . $koneksi->connect_error]));
}

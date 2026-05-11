<?php
// Konfigurasi database
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','db_absensi');

// Membuat koneksi
$conn=new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
die("Koneksi gagal: ". $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");
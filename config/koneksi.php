<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "inventaris_db";

$koneksi = mysqli_connect($host, $user, $password, $database);

/* cek koneksi */
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

/* set charset (PENTING biar aman karakter) */
mysqli_set_charset($koneksi, "utf8mb4");
?>
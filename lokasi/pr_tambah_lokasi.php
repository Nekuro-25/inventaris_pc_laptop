<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* Validasi method */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: lokasi.php");
    exit;
}

/* ✅ FIX #12 CSRF: Validasi token */
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: lokasi.php?error=invalid_request");
    exit;
}

/* ✅ FIX #15: Validasi input */
$nama_lokasi = trim($_POST['nama_lokasi'] ?? '');
if (empty($nama_lokasi)) {
    header("Location: tambah_lokasi.php?error=nama_kosong");
    exit;
}

/* ✅ FIX SQL Injection: Prepared Statement */
$stmt = mysqli_prepare($koneksi, "INSERT INTO lokasi (nama_lokasi) VALUES (?)");
mysqli_stmt_bind_param($stmt, "s", $nama_lokasi);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: lokasi.php?pesan=berhasil");
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: tambah_lokasi.php?error=gagal_simpan");
    exit;
}
?>

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

/* ✅ FIX: Bersihkan & validasi input */
$id          = (int) ($_POST['id_lokasi'] ?? 0);
$nama_lokasi = trim($_POST['nama_lokasi'] ?? '');

if ($id === 0 || empty($nama_lokasi)) {
    header("Location: lokasi.php?error=data_tidak_valid");
    exit;
}

/* ✅ FIX SQL Injection: Prepared Statement */
$stmt = mysqli_prepare($koneksi, "
    UPDATE lokasi SET nama_lokasi = ?, updated_at = NOW()
    WHERE id_lokasi = ? AND deleted_at IS NULL
");
mysqli_stmt_bind_param($stmt, "si", $nama_lokasi, $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: lokasi.php?pesan=update_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: lokasi.php?error=gagal_update");
    exit;
}
?>

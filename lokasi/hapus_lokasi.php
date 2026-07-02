<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: lokasi.php");
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: lokasi.php?error=invalid_request");
    exit;
}

/* Validasi parameter */
if (!isset($_POST['id_lokasi'])) {
    header("Location: lokasi.php");
    exit;
}

/* ✅ FIX: Cast ke integer */
$id = (int) $_POST['id_lokasi'];

/* ✅ FIX: Soft delete pakai Prepared Statement */
$stmt = mysqli_prepare($koneksi, "
    UPDATE lokasi 
    SET deleted_at = NOW() 
    WHERE id_lokasi = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: lokasi.php?pesan=hapus_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: lokasi.php?error=gagal_hapus");
    exit;
}
?>

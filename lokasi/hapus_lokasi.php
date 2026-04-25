<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* Validasi parameter */
if (!isset($_GET['id_lokasi'])) {
    header("Location: lokasi.php");
    exit;
}

/* ✅ FIX: Cast ke integer */
$id = (int) $_GET['id_lokasi'];

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

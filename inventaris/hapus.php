<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

/* Validasi parameter */
if (!isset($_GET['id'])) {
    header("Location: data.php");
    exit;
}

/* ✅ FIX: Cast ke integer — mencegah SQL Injection pada parameter GET */
$id = (int) $_GET['id'];

/* ✅ FIX: Prepared Statement */
$stmt = mysqli_prepare($koneksi, "
    UPDATE inventaris 
    SET deleted_at = NOW() 
    WHERE id_barang = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: data.php?pesan=hapus_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: data.php?error=gagal_hapus");
    exit;
}
?>

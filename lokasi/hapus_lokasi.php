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

/* 
 * ✅ FIX LOGICAL BUG: Cek relasi ke tabel inventaris 
 * Jangan biarkan lokasi dihapus jika masih ada barang di dalamnya
 */
$stmtCek = mysqli_prepare($koneksi, "
    SELECT id_barang FROM inventaris
    WHERe id_lokasi = ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtCek, "i", $id);
mysqli_stmt_execute($stmtCek);
$resultCek = mysqli_stmt_get_result($stmtCek);
mysqli_stmt_close($stmtCek);

if (mysqli_num_rows($resultCek) > 0) {
    header ("Location: lokasi.php?error=lokasi_terpakai");
    exit;
}

/* ✅ FIX: Soft delete pakai Prepared Statement */
$stmtHapus = mysqli_prepare($koneksi, "
    UPDATE lokasi
    SET deleted_at = NOW()
    WHERE id_lokasi = ?
");
mysqli_stmt_bind_param($stmtHapus, "i", $id);

if (mysqli_stmt_execute($stmtHapus)) {
    mysqli_stmt_close($stmtHapus);
    header("Location: lokasi.php?pesan=hapus_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmtHapus);
    header("Location: lokasi.php?error=gagal_hapus");
    exit;
}
?>

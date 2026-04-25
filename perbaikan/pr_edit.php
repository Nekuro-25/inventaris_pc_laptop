<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

/* Validasi method */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data_perbaikan.php");
    exit;
}

/* Validasi input wajib */
if (empty($_POST['id_perbaikan']) || empty($_POST['id_barang']) || empty($_POST['tanggal']) || empty($_POST['kerusakan'])) {
    header("Location: data_perbaikan.php?error=data_tidak_lengkap");
    exit;
}

/* ✅ FIX: Bersihkan input */
$id        = (int) $_POST['id_perbaikan'];
$id_barang = (int) $_POST['id_barang'];
$tanggal   = trim($_POST['tanggal']);
$kerusakan = trim($_POST['kerusakan']);
$tindakan  = isset($_POST['tindakan']) ? trim($_POST['tindakan']) : '';

/* Validasi data perbaikan ada & belum dihapus */
$stmtCek = mysqli_prepare($koneksi, "
    SELECT id_perbaikan FROM perbaikan 
    WHERE id_perbaikan = ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtCek, "i", $id);
mysqli_stmt_execute($stmtCek);
$resultCek = mysqli_stmt_get_result($stmtCek);
mysqli_stmt_close($stmtCek);

if (mysqli_num_rows($resultCek) === 0) {
    header("Location: data_perbaikan.php?error=data_tidak_ditemukan");
    exit;
}

/* Validasi barang ada & belum dihapus */
$stmtBarang = mysqli_prepare($koneksi, "
    SELECT id_barang FROM inventaris 
    WHERE id_barang = ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtBarang, "i", $id_barang);
mysqli_stmt_execute($stmtBarang);
$resultBarang = mysqli_stmt_get_result($stmtBarang);
mysqli_stmt_close($stmtBarang);

if (mysqli_num_rows($resultBarang) === 0) {
    header("Location: data_perbaikan.php?error=barang_tidak_valid");
    exit;
}

/* ✅ FIX: Update pakai Prepared Statement */
$stmtUpdate = mysqli_prepare($koneksi, "
    UPDATE perbaikan SET
        id_barang  = ?,
        tanggal    = ?,
        kerusakan  = ?,
        tindakan   = ?,
        updated_at = NOW()
    WHERE id_perbaikan = ?
");
mysqli_stmt_bind_param($stmtUpdate, "isssi", $id_barang, $tanggal, $kerusakan, $tindakan, $id);

if (mysqli_stmt_execute($stmtUpdate)) {
    mysqli_stmt_close($stmtUpdate);
    header("Location: data_perbaikan.php?pesan=update_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmtUpdate);
    header("Location: data_perbaikan.php?error=gagal_update");
    exit;
}
?>

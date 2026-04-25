<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data_perbaikan.php");
    exit;
}

/* ✅ FIX #12 CSRF: Validasi token */
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: data_perbaikan.php?error=invalid_request");
    exit;
}

if (empty($_POST['id_barang']) || empty($_POST['tanggal']) || empty($_POST['kerusakan'])) {
    header("Location: tambah_perbaikan.php?error=data_tidak_lengkap");
    exit;
}

$id_barang = (int) $_POST['id_barang'];
$tanggal   = trim($_POST['tanggal']);
$kerusakan = trim($_POST['kerusakan']);
$tindakan  = isset($_POST['tindakan']) ? trim($_POST['tindakan']) : null;

/* Validasi barang ada */
$stmtCek = mysqli_prepare($koneksi, "SELECT id_barang FROM inventaris WHERE id_barang = ? AND deleted_at IS NULL");
mysqli_stmt_bind_param($stmtCek, "i", $id_barang);
mysqli_stmt_execute($stmtCek);
$resultCek = mysqli_stmt_get_result($stmtCek);
mysqli_stmt_close($stmtCek);

if (mysqli_num_rows($resultCek) == 0) {
    header("Location: tambah_perbaikan.php?error=barang_tidak_ditemukan");
    exit;
}

$stmtInsert = mysqli_prepare($koneksi, "INSERT INTO perbaikan (id_barang, tanggal, kerusakan, tindakan) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmtInsert, "isss", $id_barang, $tanggal, $kerusakan, $tindakan);

if (mysqli_stmt_execute($stmtInsert)) {
    mysqli_stmt_close($stmtInsert);
    $stmtUpdate = mysqli_prepare($koneksi, "UPDATE inventaris SET status = 'rusak' WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmtUpdate, "i", $id_barang);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);
    header("Location: data_perbaikan.php?pesan=berhasil");
    exit;
} else {
    mysqli_stmt_close($stmtInsert);
    header("Location: tambah_perbaikan.php?error=gagal_simpan");
    exit;
}
?>

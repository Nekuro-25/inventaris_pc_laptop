<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: index.php?error=invalid_request");
    exit;
}

if (empty($_POST['id']) || empty($_POST['id_barang']) || empty($_POST['tanggal_kembali'])) {
    header("Location: index.php?error=data_tidak_lengkap");
    exit;
}

$id              = (int) $_POST['id'];
$id_barang       = (int) $_POST['id_barang'];
$tanggal_kembali = trim($_POST['tanggal_kembali']);

// ✅ FIX BUG #2: Validasi format tanggal
$tgl = DateTime::createFromFormat('Y-m-d', $tanggal_kembali);
if (!$tgl || $tgl->format('Y-m-d') !== $tanggal_kembali) {
    header("Location: index.php?error=format_tanggal_salah");
    exit;
}

// ✅ FIX BUG #1: Gunakan transaksi database
mysqli_begin_transaction($koneksi);

try {
    $stmtUpdate = mysqli_prepare($koneksi, "
        UPDATE peminjaman SET status = 'kembali', tanggal_kembali = ? WHERE id = ?
    ");
    mysqli_stmt_bind_param($stmtUpdate, "si", $tanggal_kembali, $id);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);

    $stmtInventaris = mysqli_prepare($koneksi, "UPDATE inventaris SET status = 'tersedia' WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmtInventaris, "i", $id_barang);
    mysqli_stmt_execute($stmtInventaris);
    mysqli_stmt_close($stmtInventaris);

    mysqli_commit($koneksi);
    header("Location: index.php?pesan=kembali_berhasil");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    header("Location: index.php?error=gagal_kembali");
    exit;
}
?>
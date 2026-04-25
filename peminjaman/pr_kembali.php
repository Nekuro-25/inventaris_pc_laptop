<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

/* Validasi method & input */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (empty($_POST['id']) || empty($_POST['id_barang']) || empty($_POST['tanggal_kembali'])) {
    header("Location: index.php?error=data_tidak_lengkap");
    exit;
}

/* ✅ FIX: Bersihkan input */
$id              = (int) $_POST['id'];
$id_barang       = (int) $_POST['id_barang'];
$tanggal_kembali = trim($_POST['tanggal_kembali']);

/* ✅ FIX: Update peminjaman pakai Prepared Statement */
$stmtUpdate = mysqli_prepare($koneksi, "
    UPDATE peminjaman 
    SET status = 'kembali', tanggal_kembali = ?
    WHERE id = ?
");
mysqli_stmt_bind_param($stmtUpdate, "si", $tanggal_kembali, $id);

if (mysqli_stmt_execute($stmtUpdate)) {
    mysqli_stmt_close($stmtUpdate);

    /* Update status inventaris kembali tersedia */
    $stmtInventaris = mysqli_prepare($koneksi, "
        UPDATE inventaris SET status = 'tersedia' WHERE id_barang = ?
    ");
    mysqli_stmt_bind_param($stmtInventaris, "i", $id_barang);
    mysqli_stmt_execute($stmtInventaris);
    mysqli_stmt_close($stmtInventaris);

    header("Location: index.php?pesan=kembali_berhasil");
    exit;

} else {
    mysqli_stmt_close($stmtUpdate);
    header("Location: index.php?error=gagal_kembali");
    exit;
}
?>

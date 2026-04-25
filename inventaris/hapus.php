<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

if (!isset($_GET['id'])) {
    header("Location: data.php");
    exit;
}

$id = (int) $_GET['id'];

// ✅ FIX BUG #3: Cek apakah barang sedang dipinjam sebelum dihapus
$stmtCekPinjam = mysqli_prepare($koneksi, "
    SELECT id FROM peminjaman
    WHERE id_barang = ?
    AND status = 'dipinjam'
    AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtCekPinjam, "i", $id);
mysqli_stmt_execute($stmtCekPinjam);
$resultPinjam = mysqli_stmt_get_result($stmtCekPinjam);
mysqli_stmt_close($stmtCekPinjam);

if (mysqli_num_rows($resultPinjam) > 0) {
    header("Location: data.php?error=barang_sedang_dipinjam");
    exit;
}

// Aman untuk dihapus
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
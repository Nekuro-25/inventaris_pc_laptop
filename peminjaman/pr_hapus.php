<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: index.php?error=invalid_request");
    exit;
}

/* Validasi parameter */
if (!isset($_POST['id'])) {
    header("Location: index.php");
    exit;
}

/* ✅ FIX: Cast ke integer */
$id = (int) $_POST['id'];

/* ✅ FIX: Ambil data peminjaman pakai Prepared Statement */
$stmtCek = mysqli_prepare($koneksi, "
    SELECT * FROM peminjaman 
    WHERE id = ? AND deleted_at IS NULL
    LIMIT 1
");
if (!$stmtCek) {
    header("Location: index.php?error=gagal_hapus");
    exit;
}
mysqli_stmt_bind_param($stmtCek, "i", $id);
if (!mysqli_stmt_execute($stmtCek)) {
    mysqli_stmt_close($stmtCek);
    header("Location: index.php?error=gagal_hapus");
    exit;
}
$result = mysqli_stmt_get_result($stmtCek);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmtCek);

if (!$row) {
    header("Location: index.php?error=data_tidak_ditemukan");
    exit;
}

/* Kalau masih dipinjam, kembalikan status barang */
if ($row['status'] === 'dipinjam') {
    $stmtKembali = mysqli_prepare($koneksi, "
        UPDATE inventaris SET status = 'tersedia' WHERE id_barang = ?
    ");
    if ($stmtKembali) {
        mysqli_stmt_bind_param($stmtKembali, "i", $row['id_barang']);
        mysqli_stmt_execute($stmtKembali);
        mysqli_stmt_close($stmtKembali);
    }
}

/* ✅ FIX: Soft delete pakai Prepared Statement */
$stmtHapus = mysqli_prepare($koneksi, "
    UPDATE peminjaman SET deleted_at = NOW() WHERE id = ?
");
if (!$stmtHapus) {
    header("Location: index.php?error=gagal_hapus");
    exit;
}
mysqli_stmt_bind_param($stmtHapus, "i", $id);

if (mysqli_stmt_execute($stmtHapus)) {
    mysqli_stmt_close($stmtHapus);
    header("Location: index.php?pesan=hapus_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmtHapus);
    header("Location: index.php?error=gagal_hapus");
    exit;
}
?>

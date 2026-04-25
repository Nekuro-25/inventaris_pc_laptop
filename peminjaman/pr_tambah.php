<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

/* Validasi method & input */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (empty($_POST['id_barang']) || empty($_POST['nama_peminjam']) || empty($_POST['tanggal_pinjam'])) {
    header("Location: index.php?error=data_tidak_lengkap");
    exit;
}

/* ✅ FIX: Bersihkan input */
$id_barang      = (int) $_POST['id_barang'];
$nama_peminjam  = trim($_POST['nama_peminjam']);
$tanggal_pinjam = trim($_POST['tanggal_pinjam']);

/* ✅ FIX: Cek status barang pakai Prepared Statement */
$stmtCek = mysqli_prepare($koneksi, "
    SELECT status FROM inventaris 
    WHERE id_barang = ? 
    AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtCek, "i", $id_barang);
mysqli_stmt_execute($stmtCek);
$resultCek = mysqli_stmt_get_result($stmtCek);
$barang = mysqli_fetch_assoc($resultCek);
mysqli_stmt_close($stmtCek);

if (!$barang) {
    header("Location: index.php?error=barang_tidak_ditemukan");
    exit;
}

if ($barang['status'] === 'dipinjam') {
    header("Location: index.php?error=barang_sedang_dipinjam");
    exit;
}

/* ✅ FIX: Insert peminjaman pakai Prepared Statement */
$stmtInsert = mysqli_prepare($koneksi, "
    INSERT INTO peminjaman (id_barang, nama_peminjam, tanggal_pinjam, status)
    VALUES (?, ?, ?, 'dipinjam')
");
mysqli_stmt_bind_param($stmtInsert, "iss", $id_barang, $nama_peminjam, $tanggal_pinjam);

if (mysqli_stmt_execute($stmtInsert)) {
    mysqli_stmt_close($stmtInsert);

    /* Update status inventaris */
    $stmtUpdate = mysqli_prepare($koneksi, "
        UPDATE inventaris SET status = 'dipinjam' WHERE id_barang = ?
    ");
    mysqli_stmt_bind_param($stmtUpdate, "i", $id_barang);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);

    header("Location: index.php?pesan=berhasil");
    exit;

} else {
    mysqli_stmt_close($stmtInsert);
    header("Location: index.php?error=gagal_simpan");
    exit;
}
?>

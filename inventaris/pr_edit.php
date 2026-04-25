<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

/* Validasi method */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data.php");
    exit;
}

/* ✅ FIX #12 CSRF: Validasi token */
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: data.php?error=invalid_request");
    exit;
}

/* Validasi input wajib */
if (empty($_POST['id_barang']) || empty($_POST['kode_barang']) || empty($_POST['nama_barang'])) {
    header("Location: data.php?error=data_tidak_lengkap");
    exit;
}

/* ✅ FIX: Bersihkan input */
$id          = (int) $_POST['id_barang'];
$kode_barang = trim($_POST['kode_barang']);
$nama_barang = trim($_POST['nama_barang']);
$jenis       = $_POST['jenis'];
$merk        = trim($_POST['merk']);
$processor   = trim($_POST['processor']);
$ram         = trim($_POST['ram']);
$storage     = trim($_POST['storage']);
$id_lokasi   = (int) $_POST['id_lokasi'];
$status      = $_POST['status'];

/* Whitelist nilai enum */
$jenis_allowed  = ['PC', 'Laptop'];
$status_allowed = ['tersedia', 'dipinjam', 'rusak'];

if (!in_array($jenis, $jenis_allowed) || !in_array($status, $status_allowed)) {
    header("Location: data.php?error=data_tidak_valid");
    exit;
}

/* ✅ FIX SQL Injection: Prepared Statement */
$stmt = mysqli_prepare($koneksi, "
    UPDATE inventaris SET
        kode_barang = ?,
        nama_barang = ?,
        jenis       = ?,
        merk        = ?,
        processor   = ?,
        ram         = ?,
        storage     = ?,
        id_lokasi   = ?,
        status      = ?,
        updated_at  = NOW()
    WHERE id_barang = ?
");
mysqli_stmt_bind_param($stmt, "sssssssssi",
    $kode_barang, $nama_barang, $jenis, $merk,
    $processor, $ram, $storage, $id_lokasi, $status, $id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: data.php?pesan=update_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: data.php?error=gagal_update");
    exit;
}
?>

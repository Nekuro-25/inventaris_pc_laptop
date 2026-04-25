<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

/* Validasi method */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data.php");
    exit;
}

/* Validasi input wajib */
if (empty($_POST['kode_barang']) || empty($_POST['nama_barang']) || empty($_POST['id_lokasi'])) {
    header("Location: tambah.php?error=data_tidak_lengkap");
    exit;
}

/* ✅ FIX: Ambil & bersihkan input */
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
    header("Location: tambah.php?error=data_tidak_valid");
    exit;
}

/* ✅ FIX: Prepared Statement — mencegah SQL Injection */
$stmt = mysqli_prepare($koneksi, "
    INSERT INTO inventaris 
    (kode_barang, nama_barang, jenis, merk, processor, ram, storage, id_lokasi, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
mysqli_stmt_bind_param($stmt, "sssssssss",
    $kode_barang,
    $nama_barang,
    $jenis,
    $merk,
    $processor,
    $ram,
    $storage,
    $id_lokasi,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: data.php?pesan=berhasil");
    exit;
} else {
    /* ✅ FIX: Jangan tampilkan error MySQL ke user */
    mysqli_stmt_close($stmt);
    header("Location: tambah.php?error=gagal_simpan");
    exit;
}
?>

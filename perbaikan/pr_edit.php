<?php

include("../config/auth.php");
include("../config/koneksi.php");

adminOrTeknisi();
blockUser();

/* VALIDASI METHOD */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: data_perbaikan.php");
    exit;
}

/* VALIDASI INPUT WAJIB */
if(
    empty($_POST['id_perbaikan']) ||
    empty($_POST['id_barang']) ||
    empty($_POST['tanggal']) ||
    empty($_POST['kerusakan'])
){
    echo "Data tidak lengkap!";
    exit;
}

/* AMANKAN DATA */
$id = (int) $_POST['id_perbaikan'];
$id_barang = (int) $_POST['id_barang'];
$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
$kerusakan = mysqli_real_escape_string($koneksi, $_POST['kerusakan']);
$tindakan = isset($_POST['tindakan']) 
    ? mysqli_real_escape_string($koneksi, $_POST['tindakan']) 
    : '';

/* VALIDASI DATA PERBAIKAN ADA & BELUM DIHAPUS */
$cek = mysqli_query($koneksi,"
SELECT id_perbaikan FROM perbaikan 
WHERE id_perbaikan = '$id' 
AND deleted_at IS NULL
");

if(!$cek){
    die("Query error: " . mysqli_error($koneksi));
}

if(mysqli_num_rows($cek) === 0){
    echo "Data tidak ditemukan atau sudah dihapus!";
    exit;
}

/* VALIDASI BARANG ADA & BELUM DIHAPUS */
$cekBarang = mysqli_query($koneksi,"
SELECT id_barang FROM inventaris
WHERE id_barang = '$id_barang'
AND deleted_at IS NULL
");

if(mysqli_num_rows($cekBarang) === 0){
    echo "Barang tidak valid!";
    exit;
}

/* UPDATE DATA */
$query = mysqli_query($koneksi,"
UPDATE perbaikan SET
    id_barang = '$id_barang',
    tanggal = '$tanggal',
    kerusakan = '$kerusakan',
    tindakan = '$tindakan',
    updated_at = NOW()
WHERE id_perbaikan = '$id'
");

if($query){
    header("Location: data_perbaikan.php?pesan=update_berhasil");
    exit;
}else{
    echo "Update gagal : " . mysqli_error($koneksi);
}

?>
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

/* VALIDASI INPUT */
if(
    !isset($_POST['id_perbaikan']) ||
    !isset($_POST['id_barang']) ||
    !isset($_POST['tanggal']) ||
    !isset($_POST['kerusakan'])
){
    echo "Data tidak lengkap!";
    exit;
}

/* AMANKAN DATA */
$id = (int) $_POST['id_perbaikan'];
$id_barang = (int) $_POST['id_barang'];
$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
$kerusakan = mysqli_real_escape_string($koneksi, $_POST['kerusakan']);
$tindakan = mysqli_real_escape_string($koneksi, $_POST['tindakan']);

/* VALIDASI DATA ADA */
$cek = mysqli_query($koneksi,"
SELECT * FROM perbaikan 
WHERE id_perbaikan='$id' 
AND deleted_at IS NULL
");

if(!$cek){
    die("Query error: " . mysqli_error($koneksi));
}

if(mysqli_num_rows($cek) == 0){
    echo "Data tidak ditemukan!";
    exit;
}

/* UPDATE DATA */
$query = mysqli_query($koneksi,"
UPDATE perbaikan SET
id_barang='$id_barang',
tanggal='$tanggal',
kerusakan='$kerusakan',
tindakan='$tindakan'
WHERE id_perbaikan='$id'
");

if($query){

    header("Location: data_perbaikan.php");
    exit;

}else{

    echo "Update gagal : " . mysqli_error($koneksi);

}

?>
<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

/* VALIDASI POST */
if(!isset($_POST['id']) || !isset($_POST['id_barang'])){
    header("Location: index.php");
    exit;
}

$id = $_POST['id'];
$id_barang = $_POST['id_barang'];
$tanggal_kembali = $_POST['tanggal_kembali'];

/* VALIDASI TANGGAL */
if(empty($tanggal_kembali)){
    echo "Tanggal kembali wajib diisi!";
    exit;
}

/* update peminjaman */
$query = mysqli_query($koneksi,"
UPDATE peminjaman 
SET status='kembali', tanggal_kembali='$tanggal_kembali'
WHERE id='$id'
");

if($query){

    mysqli_query($koneksi,"
    UPDATE inventaris 
    SET status='aktif' 
    WHERE id_barang='$id_barang'
    ");

    header("Location: index.php");
    exit;

}else{
    echo "Gagal mengembalikan: " . mysqli_error($koneksi);
}
?>
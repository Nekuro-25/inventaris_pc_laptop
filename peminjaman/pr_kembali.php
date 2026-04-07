<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$id = $_POST['id'];
$id_barang = $_POST['id_barang'];
$tanggal_kembali = $_POST['tanggal_kembali'];

/* update peminjaman */
$query = mysqli_query($koneksi,"
UPDATE peminjaman 
SET status='kembali', tanggal_kembali='$tanggal_kembali'
WHERE id='$id'
");

if($query){

    /* update status inventaris */
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
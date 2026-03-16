<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

/* ambil id dari url */
$id = $_GET['id'];

/* hapus data */
$query = mysqli_query($koneksi,"DELETE FROM inventaris WHERE id_barang='$id'");

if($query){

    header("Location: data.php");

}else{

    echo "Data gagal dihapus : " . mysqli_error($koneksi);

}

?>
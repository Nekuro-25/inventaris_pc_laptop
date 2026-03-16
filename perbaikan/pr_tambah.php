<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$id_barang = $_POST['id_barang'];
$tanggal = $_POST['tanggal'];
$kerusakan = $_POST['kerusakan'];
$tindakan = $_POST['tindakan'];

$query = mysqli_query($koneksi,"
INSERT INTO perbaikan
(id_barang, tanggal, kerusakan, tindakan)
VALUES
('$id_barang','$tanggal','$kerusakan','$tindakan')
");

if($query){

header("Location: data_perbaikan.php");

}else{

echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>
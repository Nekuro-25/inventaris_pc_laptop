<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$id = $_POST['id_perbaikan'];
$id_barang = $_POST['id_barang'];
$tanggal = $_POST['tanggal'];
$kerusakan = $_POST['kerusakan'];
$tindakan = $_POST['tindakan'];

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

}else{

echo "Update gagal : ".mysqli_error($koneksi);

}

?>
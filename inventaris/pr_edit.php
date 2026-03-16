<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$id = $_POST['id_barang'];

$kode_barang = $_POST['kode_barang'];
$nama_barang = $_POST['nama_barang'];
$jenis = $_POST['jenis'];
$merk = $_POST['merk'];
$processor = $_POST['processor'];
$ram = $_POST['ram'];
$storage = $_POST['storage'];
$id_lokasi = $_POST['id_lokasi'];
$status = $_POST['status'];

$query = mysqli_query($koneksi,"
UPDATE inventaris SET
kode_barang='$kode_barang',
nama_barang='$nama_barang',
jenis='$jenis',
merk='$merk',
processor='$processor',
ram='$ram',
storage='$storage',
id_lokasi='$id_lokasi',
status='$status'
WHERE id_barang='$id'
");

if($query){

header("Location: data.php");

}else{

echo "Update gagal : ".mysqli_error($koneksi);

}

?>
<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

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
INSERT INTO inventaris
(kode_barang,nama_barang,jenis,merk,processor,ram,storage,id_lokasi,status)
VALUES
('$kode_barang','$nama_barang','$jenis','$merk','$processor','$ram','$storage','$id_lokasi','$status')
");

if($query){

header("Location: data.php");
exit;

}else{

echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>
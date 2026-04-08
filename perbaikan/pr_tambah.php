<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

$id_barang = $_POST['id_barang'];
$tanggal = $_POST['tanggal'];
$kerusakan = $_POST['kerusakan'];
$tindakan = $_POST['tindakan'];

/* simpan data perbaikan */
$query = mysqli_query($koneksi,"
INSERT INTO perbaikan
(id_barang, tanggal, kerusakan, tindakan)
VALUES
('$id_barang','$tanggal','$kerusakan','$tindakan')
");

if($query){

    /* update status inventaris jadi maintenance */
    $update = mysqli_query($koneksi,"
    UPDATE inventaris 
    SET status='maintenance' 
    WHERE id_barang='$id_barang'
    ");

    header("Location: data_perbaikan.php");
    exit;

}else{

    echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>
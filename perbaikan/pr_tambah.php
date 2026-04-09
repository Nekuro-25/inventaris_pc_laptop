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
    !isset($_POST['id_barang']) ||
    !isset($_POST['tanggal']) ||
    !isset($_POST['kerusakan'])
){
    echo "Data tidak lengkap!";
    exit;
}

/* AMANKAN INPUT */
$id_barang = (int) $_POST['id_barang'];
$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
$kerusakan = mysqli_real_escape_string($koneksi, $_POST['kerusakan']);
$tindakan = mysqli_real_escape_string($koneksi, $_POST['tindakan']);

/* VALIDASI BARANG ADA */
$cekBarang = mysqli_query($koneksi,"
SELECT * FROM inventaris 
WHERE id_barang='$id_barang' 
AND deleted_at IS NULL
");

if(!$cekBarang){
    die("Query error: " . mysqli_error($koneksi));
}

if(mysqli_num_rows($cekBarang) == 0){
    echo "Barang tidak ditemukan!";
    exit;
}

/* SIMPAN DATA PERBAIKAN */
$query = mysqli_query($koneksi,"
INSERT INTO perbaikan
(id_barang, tanggal, kerusakan, tindakan)
VALUES
('$id_barang','$tanggal','$kerusakan','$tindakan')
");

if($query){

    /* UPDATE STATUS INVENTARIS */
    $update = mysqli_query($koneksi,"
    UPDATE inventaris 
    SET status='maintenance' 
    WHERE id_barang='$id_barang'
    ");

    if(!$update){
        die("Gagal update status inventaris: " . mysqli_error($koneksi));
    }

    header("Location: data_perbaikan.php");
    exit;

}else{

    echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>
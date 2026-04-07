<?php

include("../config/auth.php");
include("../config/koneksi.php");

$id_barang       = $_POST['id_barang'];
$nama_peminjam   = $_POST['nama_peminjam'];
$tanggal_pinjam  = $_POST['tanggal_pinjam'];

$cek = mysqli_query($koneksi,"
SELECT * FROM inventaris 
WHERE id_barang='$id_barang' AND status='dipinjam'
");

if(mysqli_num_rows($cek) > 0){
    echo "Barang sedang dipinjam!";
    exit;
}

/* simpan ke database */
$query = mysqli_query($koneksi,"
INSERT INTO peminjaman
(id_barang, nama_peminjam, tanggal_pinjam, status)
VALUES
('$id_barang','$nama_peminjam','$tanggal_pinjam','dipinjam')
");

if($query){

    /* update status barang jadi dipinjam */
    mysqli_query($koneksi,"
    UPDATE inventaris 
    SET status='dipinjam' 
    WHERE id_barang='$id_barang'
    ");

    header("Location: index.php");
    exit;

}else{

    echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>
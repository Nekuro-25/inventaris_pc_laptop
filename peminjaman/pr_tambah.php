<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

/* VALIDASI */
if(
    !isset($_POST['id_barang']) ||
    !isset($_POST['nama_peminjam']) ||
    !isset($_POST['tanggal_pinjam'])
){
    header("Location: index.php");
    exit;
}

$id_barang       = $_POST['id_barang'];
$nama_peminjam   = $_POST['nama_peminjam'];
$tanggal_pinjam  = $_POST['tanggal_pinjam'];

/* cek barang */
$cek = mysqli_query($koneksi,"
SELECT * FROM inventaris 
WHERE id_barang='$id_barang' 
AND status='dipinjam'
AND deleted_at IS NULL
");

if(mysqli_num_rows($cek) > 0){
    echo "Barang sedang dipinjam!";
    exit;
}

/* simpan */
$query = mysqli_query($koneksi,"
INSERT INTO peminjaman
(id_barang, nama_peminjam, tanggal_pinjam, status)
VALUES
('$id_barang','$nama_peminjam','$tanggal_pinjam','dipinjam')
");

if($query){

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
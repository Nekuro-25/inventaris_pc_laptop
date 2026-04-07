<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$id = $_GET['id'];

/* ambil data dulu */
$data = mysqli_query($koneksi,"
SELECT * FROM peminjaman WHERE id='$id'
");

$row = mysqli_fetch_assoc($data);

/* kalau masih dipinjam, kembalikan status barang */
if($row['status'] == 'dipinjam'){
    mysqli_query($koneksi,"
    UPDATE inventaris 
    SET status='aktif' 
    WHERE id_barang='".$row['id_barang']."'
    ");
}

/* hapus data */
$query = mysqli_query($koneksi,"
DELETE FROM peminjaman WHERE id='$id'
");

if($query){

    header("Location: index.php");
    exit;

}else{

    echo "Data gagal dihapus : " . mysqli_error($koneksi);

}

?>
<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

/* VALIDASI ID */
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

/* ambil data */
$data = mysqli_query($koneksi,"
SELECT * FROM peminjaman 
WHERE id='$id' AND deleted_at IS NULL
");

$row = mysqli_fetch_assoc($data);

/* VALIDASI DATA */
if(!$row){
    echo "Data tidak ditemukan!";
    exit;
}

/* kalau masih dipinjam */
if($row['status'] == 'dipinjam'){
    mysqli_query($koneksi,"
    UPDATE inventaris 
    SET status='aktif' 
    WHERE id_barang='".$row['id_barang']."'
    ");
}

/* soft delete */
$query = mysqli_query($koneksi,"
UPDATE peminjaman 
SET deleted_at = NOW() 
WHERE id='$id'
");

if($query){
    header("Location: index.php");
    exit;
}else{
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>
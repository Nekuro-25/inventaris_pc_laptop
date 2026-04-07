<?php

include("../config/auth.php");
include("../config/koneksi.php");

/* hanya admin & teknisi */
if(!$isAdmin && !$isTeknisi){
    header("Location: data_perbaikan.php");
    exit;
}

$id = $_GET['id'];

/* ambil data perbaikan */
$data = mysqli_query($koneksi,"
SELECT * FROM perbaikan WHERE id_perbaikan='$id'
");

$row = mysqli_fetch_assoc($data);

/* jika ada relasi ke barang, bisa update status (opsional) */
/* contoh: jika setelah perbaikan selesai ingin aktifkan kembali */
mysqli_query($koneksi,"
UPDATE inventaris 
SET status='aktif' 
WHERE id_barang='".$row['id_barang']."'
");

/* soft delete */
$query = mysqli_query($koneksi,"
UPDATE perbaikan 
SET deleted_at = NOW() 
WHERE id_perbaikan='$id'
");

if($query){

    header("Location: data_perbaikan.php");
    exit;

}else{

    echo "Gagal menghapus data: " . mysqli_error($koneksi);

}

?>
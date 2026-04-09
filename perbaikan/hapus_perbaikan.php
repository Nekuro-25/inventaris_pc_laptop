<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

/* VALIDASI ID */
if(!isset($_GET['id'])){
    header("Location: data_perbaikan.php");
    exit;
}

$id = (int) $_GET['id']; // FIX: amankan ID

/* ambil data perbaikan */
$data = mysqli_query($koneksi,"
SELECT * FROM perbaikan 
WHERE id_perbaikan='$id' 
AND deleted_at IS NULL
");

/* VALIDASI QUERY */
if(!$data){
    die("Query error: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($data);

/* VALIDASI DATA */
if(!$row){
    echo "Data tidak ditemukan!";
    exit;
}

/* update status inventaris (opsional) */
$updateInventaris = mysqli_query($koneksi,"
UPDATE inventaris 
SET status='aktif' 
WHERE id_barang='".$row['id_barang']."'
");

/* OPTIONAL CHECK */
if(!$updateInventaris){
    die("Gagal update inventaris: " . mysqli_error($koneksi));
}

/* soft delete */
$query = mysqli_query($koneksi,"
UPDATE perbaikan 
SET deleted_at = NOW() 
WHERE id_perbaikan='$id'
");

/* VALIDASI DELETE */
if($query){

    header("Location: data_perbaikan.php");
    exit;

}else{

    echo "Gagal menghapus data: " . mysqli_error($koneksi);

}

?>
<?php

include("../config/auth.php");
include("../config/koneksi.php");

adminOrTeknisi();
blockUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data_perbaikan.php");
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: data_perbaikan.php?error=invalid_request");
    exit;
}

/* VALIDASI ID */
if(!isset($_POST['id'])){
    header("Location: data_perbaikan.php");
    exit;
}

$id = (int) $_POST['id'];

/* ambil data perbaikan */
$data = mysqli_query($koneksi,"
SELECT * FROM perbaikan 
WHERE id_perbaikan = $id
AND deleted_at IS NULL
LIMIT 1
");

/* VALIDASI QUERY */
if(!$data){
    die("Query error: " . mysqli_error($koneksi));
}

/* VALIDASI DATA */
if(mysqli_num_rows($data) === 0){
    echo "Data tidak ditemukan!";
    exit;
}

$row = mysqli_fetch_assoc($data);

/* update status inventaris (disesuaikan ENUM terbaru) */
$updateInventaris = mysqli_query($koneksi,"
UPDATE inventaris 
SET status='tersedia' 
WHERE id_barang = ".$row['id_barang']."
");

/* OPTIONAL CHECK */
if(!$updateInventaris){
    die("Gagal update inventaris: " . mysqli_error($koneksi));
}

/* soft delete */
$query = mysqli_query($koneksi,"
UPDATE perbaikan 
SET deleted_at = NOW() 
WHERE id_perbaikan = $id
");

/* VALIDASI DELETE */
if($query){

    header("Location: data_perbaikan.php");
    exit;

}else{

    echo "Gagal menghapus data: " . mysqli_error($koneksi);

}
?>

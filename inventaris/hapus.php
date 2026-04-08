<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

$id = $_GET['id'];

$query = mysqli_query($koneksi,"
UPDATE inventaris 
SET deleted_at = NOW() 
WHERE id_barang='$id'
");

if($query){
    header("Location: data.php");
}else{
    echo "Data gagal dihapus : " . mysqli_error($koneksi);
}
?>
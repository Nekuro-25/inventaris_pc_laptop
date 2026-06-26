<?php

include("../config/auth.php");
include("../config/koneksi.php");

/* validasi parameter */
if(!isset($_GET['id'])){
    header("Location: data_user.php?error=invalid");
    exit;
}

$id = intval($_GET['id']);

/* cek apakah user masih aktif */
$cek = mysqli_query($koneksi, "
    SELECT id_pengguna 
    FROM pengguna 
    WHERE id_pengguna = $id 
    AND deleted_at IS NULL
");

if(mysqli_num_rows($cek) == 0){
    header("Location: data_user.php?error=data_tidak_ditemukan");
    exit;
}

/* soft delete */
$query = mysqli_query($koneksi, "
    UPDATE pengguna 
    SET deleted_at = NOW() 
    WHERE id_pengguna = $id
");

/* cek hasil */
if($query){
    header("Location: data_user.php?pesan=hapus_berhasil");
} else {
    header("Location: data_user.php?error=gagal_hapus");
}

?>
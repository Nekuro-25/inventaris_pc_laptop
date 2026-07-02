<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* validasi parameter */
if(!isset($_GET['id'])){
    header("Location: data_user.php?error=invalid");
    exit;
}

$id = intval($_GET['id']); // amankan input

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

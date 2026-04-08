<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

if(isset($_GET['id_lokasi'])){

    $id = $_GET['id_lokasi'];

    $query = mysqli_query($koneksi,"DELETE FROM lokasi WHERE id_lokasi='$id'");

    if($query){
        header("Location: lokasi.php");
        exit;
    }else{
        echo "Data gagal dihapus";
    }

}else{

    header("Location: lokasi.php");
    exit;

}

?>
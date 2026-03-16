<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

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
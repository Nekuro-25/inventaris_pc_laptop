<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$id = $_GET['id'];

mysqli_query($koneksi,"DELETE FROM user WHERE id_user='$id'");

header("Location: data_user.php");

?>
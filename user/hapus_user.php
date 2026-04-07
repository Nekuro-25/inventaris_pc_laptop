<?php

include("../config/auth.php");
include("../config/koneksi.php");

if(!$isAdmin){
    header("Location: data.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($koneksi,"DELETE FROM user WHERE id_user='$id'");

header("Location: data_user.php");

?>
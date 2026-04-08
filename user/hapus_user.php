<?php

include("../config/auth.php");
include("../config/koneksi.php");

$id = $_GET['id'];

mysqli_query($koneksi,"DELETE FROM user WHERE id_user='$id'");

header("Location: data_user.php");

?>
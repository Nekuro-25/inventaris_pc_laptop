<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

/* simpan ke database */
$query = mysqli_query($koneksi,"
INSERT INTO user (nama, username, password, role)
VALUES ('$nama','$username','$password','$role')
");

if($query){

header("Location: data_user.php");

}else{

echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>
<?php

include("../config/auth.php");
include("../config/koneksi.php");

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// enkripsi password
$hash = password_hash($password, PASSWORD_DEFAULT);

/* simpan ke database */
$query = mysqli_query($koneksi,"
INSERT INTO user (nama, username, password, role)
VALUES ('$nama','$username','$hash','$role')
");

if($query){

header("Location: data_user.php");

}else{

echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>
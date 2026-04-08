<?php

include("../config/auth.php");
include("../config/koneksi.php");

$id = $_POST['id'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

/* cek password diisi atau tidak */
if(!empty($password)){

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $query = mysqli_query($koneksi,"
    UPDATE user SET
    nama='$nama',
    username='$username',
    password='$hash',
    role='$role'
    WHERE id_user='$id'
    ");

}else{

    $query = mysqli_query($koneksi,"
    UPDATE user SET
    nama='$nama',
    username='$username',
    role='$role'
    WHERE id_user='$id'
    ");

}

if($query){
    header("Location: data_user.php");
    exit;
}else{
    echo "Gagal update: " . mysqli_error($koneksi);
}

?>
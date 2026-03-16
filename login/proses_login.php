<?php

session_start();

include("../config/koneksi.php");

$username = $_POST["username"];
$password = $_POST["password"];

$query = mysqli_query($koneksi,"SELECT * FROM user WHERE username='$username' AND password='$password'");

$data = mysqli_fetch_assoc($query);

if ($data){

    $_SESSION['username'] = $data['username'];

    header("Location: ../dashboard/index.php");
    exit;

}else{

    echo "Username atau Password salah";

}

?>
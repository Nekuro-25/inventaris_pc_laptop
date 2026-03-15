<?php

include("../config/koneksi.php");

$username = $_POST["username"];
$password = $_POST["password"];

$query = mysqli_query($koneksi,"SELECT * FROM users WHERE username='$username' AND password='$password'");

$data = mysqli_fetch_assoc($query);

if ($data){
    
    header("Location: ../dashboard/index.php");

}else{

    echo "Username atau Password salah";

}

?>
<?php

session_start();
include("../config/koneksi.php");

$username = $_POST["username"];
$password = $_POST["password"];

$query = mysqli_query($koneksi,"SELECT * FROM user WHERE username='$username'");
$data = mysqli_fetch_assoc($query);

if ($data){

    // cek password hash
    if (password_verify($password, $data['password'])) {

        session_regenerate_id(true);
        
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];
        
        header("Location: ../dashboard/index.php");
        exit;

    } else {
        echo "Password salah!";
    }

} else {
    echo "Username tidak ditemukan!";
}

?>
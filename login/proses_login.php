<?php
session_start();

include("../config/koneksi.php");

$username = $_POST["username"];
$password = $_POST["password"];

// FIX: pakai tabel pengguna + soft delete
$query = mysqli_query($koneksi,"
SELECT * FROM pengguna 
WHERE username='$username' 
AND deleted_at IS NULL
");

$data = mysqli_fetch_assoc($query);

if ($data){
    
    if (password_verify($password, $data['password'])) {

        session_regenerate_id(true);
        
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['id_pengguna'] = $data['id_pengguna']; // ✅ penting
        
        header("Location: ../dashboard/index.php");
        exit;

    } else {
        echo "Password salah!";
    }

} else {
    echo "Username tidak ditemukan!";
}
?>
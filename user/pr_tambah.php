<?php

include("../config/auth.php");
include("../config/koneksi.php");

/* validasi request */
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    /* validasi sederhana */
    if(empty($nama) || empty($username) || empty($password) || empty($role)){
        echo "Semua field wajib diisi!";
        exit;
    }

    /* enkripsi password */
    $hash = password_hash($password, PASSWORD_DEFAULT);

    /* cek username sudah ada atau belum */
    $cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username'");
    if(mysqli_num_rows($cek) > 0){
        echo "Username sudah digunakan!";
        exit;
    }

    /* simpan ke database (pakai tabel yang benar: pengguna) */
    $query = mysqli_query($koneksi,"
        INSERT INTO pengguna (nama, username, password, role)
        VALUES ('$nama','$username','$hash','$role')
    ");

    if($query){
        header("Location: data_user.php?pesan=berhasil");
        exit;
    } else {
        echo "Data gagal disimpan : " . mysqli_error($koneksi);
    }

} else {
    echo "Akses tidak valid!";
}
?>
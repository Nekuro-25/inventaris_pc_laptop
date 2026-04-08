<?php
include("../config/koneksi.php");

/* pastikan session aktif */
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

/* cek login dasar */
if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

/* ambil data user terbaru dari database */
$username = $_SESSION['username'];

$query = mysqli_query($koneksi, "
    SELECT * FROM pengguna 
    WHERE username='$username' 
    AND deleted_at IS NULL
");

$user = mysqli_fetch_assoc($query);

/* jika user tidak ditemukan */
if(!$user){
    session_destroy();
    header("Location: ../index.php");
    exit;
}

/* sinkronisasi session */
$_SESSION['role'] = $user['role'];
$_SESSION['id_pengguna'] = $user['id_pengguna']; 

/* role */
$isAdmin   = ($user['role'] == 'admin');
$isTeknisi = ($user['role'] == 'teknisi');
$isUser    = ($user['role'] == 'user');

/* =========================
   HELPER AKSES
   ========================= */

function onlyAdmin(){
    global $isAdmin;
    if(!$isAdmin){
        header("Location: ../dashboard/index.php");
        exit;
    }
}

function adminOrTeknisi(){
    global $isAdmin, $isTeknisi;
    if(!$isAdmin && !$isTeknisi){
        header("Location: ../dashboard/index.php");
        exit;
    }
}

function blockUser(){
    global $isUser;
    if($isUser){
        header("Location: ../dashboard/index.php");
        exit;
    }
}
?>
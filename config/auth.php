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

/* ambil data user terbaru dari database (PENTING) */
$username = $_SESSION['username'];

$query = mysqli_query($koneksi, "
    SELECT * FROM pengguna 
    WHERE username='$username' 
    AND deleted_at IS NULL
");

$user = mysqli_fetch_assoc($query);

/* jika user tidak ditemukan / sudah dihapus */
if(!$user){
    session_destroy();
    header("Location: ../index.php");
    exit;
}

/* set ulang session biar sinkron */
$_SESSION['role'] = $user['role'];

/* role */
$isAdmin   = ($user['role'] == 'admin');
$isTeknisi = ($user['role'] == 'teknisi');
$isUser    = ($user['role'] == 'user');

/* =========================
   HELPER AKSES
   ========================= */

/* hanya admin */
function onlyAdmin(){
    global $isAdmin;

    if(!$isAdmin){
        header("Location: ../dashboard/index.php");
        exit;
    }
}

/* admin + teknisi */
function adminOrTeknisi(){
    global $isAdmin, $isTeknisi;

    if(!$isAdmin && !$isTeknisi){
        header("Location: ../dashboard/index.php");
        exit;
    }
}

/* blok user biasa */
function blockUser(){
    global $isUser;

    if($isUser){
        header("Location: ../dashboard/index.php");
        exit;
    }
}
?>
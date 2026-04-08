<?php
/* pastikan session aktif */
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

/* cek login */
if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

/* role */
$isAdmin   = ($_SESSION['role'] == 'admin');
$isTeknisi = ($_SESSION['role'] == 'teknisi');
$isUser    = ($_SESSION['role'] == 'user');

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
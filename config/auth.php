<?php
/* pastikan session aktif */
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

/* helper login */
if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

/* helper role */
$isAdmin = ($_SESSION['role'] == 'admin');
$isTeknisi = ($_SESSION['role'] == 'teknisi');
$isUser = ($_SESSION['role'] == 'user');
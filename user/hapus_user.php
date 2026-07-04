<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data_user.php");
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: data_user.php?error=invalid_request");
    exit;
}

/* validasi parameter */
if(!isset($_POST['id'])){
    header("Location: data_user.php?error=invalid");
    exit;
}

$id = intval($_POST['id']); // amankan input

/* soft delete */
$stmt = mysqli_prepare($koneksi, "
    UPDATE pengguna 
    SET deleted_at = NOW() 
    WHERE id_pengguna = ?
");

if (!$stmt) {
    header("Location: data_user.php?error=gagal_hapus");
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);

/* cek hasil */
if(mysqli_stmt_execute($stmt)){
    mysqli_stmt_close($stmt);
    header("Location: data_user.php?pesan=hapus_berhasil");
} else {
    mysqli_stmt_close($stmt);
    header("Location: data_user.php?error=gagal_hapus");
}

?>

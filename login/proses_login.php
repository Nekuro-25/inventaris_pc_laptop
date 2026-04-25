<?php
session_start();

include("../config/koneksi.php");

/* Pastikan request dari POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$username = trim($_POST["username"]);
$password = $_POST["password"];

/* Validasi input tidak kosong */
if (empty($username) || empty($password)) {
    header("Location: ../index.php?error=1");
    exit;
}

/* ✅ FIX: Prepared Statement — mencegah SQL Injection */
$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM pengguna 
    WHERE username = ? 
    AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* ✅ FIX: Pesan error digabung jadi satu — mencegah user enumeration */
if ($data && password_verify($password, $data['password'])) {

    session_regenerate_id(true);

    $_SESSION['username']    = $data['username'];
    $_SESSION['role']        = $data['role'];
    $_SESSION['id_pengguna'] = $data['id_pengguna'];

    header("Location: ../dashboard/index.php");
    exit;

} else {
    /* Pesan sama untuk username salah maupun password salah */
    header("Location: ../index.php?error=1");
    exit;
}
?>

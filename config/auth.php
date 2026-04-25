<?php
include("../config/koneksi.php");

/* Pastikan session aktif */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Cek login dasar */
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

/* ✅ FIX: Prepared Statement — username dari session tetap harus aman */
$username = $_SESSION['username'];

$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM pengguna 
    WHERE username = ? 
    AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* Jika user tidak ditemukan (misal sudah dihapus) */
if (!$user) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

/* Sinkronisasi session */
$_SESSION['role']        = $user['role'];
$_SESSION['id_pengguna'] = $user['id_pengguna'];

/* Role flags */
$isAdmin   = ($user['role'] == 'admin');
$isTeknisi = ($user['role'] == 'teknisi');
$isUser    = ($user['role'] == 'user');

/* =========================
   HELPER AKSES
   ========================= */

function onlyAdmin() {
    global $isAdmin;
    if (!$isAdmin) {
        header("Location: ../dashboard/index.php");
        exit;
    }
}

function adminOrTeknisi() {
    global $isAdmin, $isTeknisi;
    if (!$isAdmin && !$isTeknisi) {
        header("Location: ../dashboard/index.php");
        exit;
    }
}

function blockUser() {
    global $isUser;
    if ($isUser) {
        header("Location: ../dashboard/index.php");
        exit;
    }
}
?>

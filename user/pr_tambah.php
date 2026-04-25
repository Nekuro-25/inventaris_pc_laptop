<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* Validasi method */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data_user.php");
    exit;
}

/* ✅ FIX: Bersihkan input */
$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

/* Validasi tidak kosong */
if (empty($nama) || empty($username) || empty($password) || empty($role)) {
    header("Location: tambah_user.php?error=field_kosong");
    exit;
}

/* Whitelist role */
$role_allowed = ['admin', 'teknisi', 'user'];
if (!in_array($role, $role_allowed)) {
    header("Location: tambah_user.php?error=role_tidak_valid");
    exit;
}

/* Enkripsi password */
$hash = password_hash($password, PASSWORD_DEFAULT);

/* ✅ FIX: Cek username unik pakai Prepared Statement */
$stmtCek = mysqli_prepare($koneksi, "
    SELECT id_pengguna FROM pengguna 
    WHERE username = ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtCek, "s", $username);
mysqli_stmt_execute($stmtCek);
$resultCek = mysqli_stmt_get_result($stmtCek);
mysqli_stmt_close($stmtCek);

if (mysqli_num_rows($resultCek) > 0) {
    header("Location: tambah_user.php?error=username_digunakan");
    exit;
}

/* ✅ FIX: Insert pakai Prepared Statement */
$stmtInsert = mysqli_prepare($koneksi, "
    INSERT INTO pengguna (nama, username, password, role)
    VALUES (?, ?, ?, ?)
");
mysqli_stmt_bind_param($stmtInsert, "ssss", $nama, $username, $hash, $role);

if (mysqli_stmt_execute($stmtInsert)) {
    mysqli_stmt_close($stmtInsert);
    header("Location: data_user.php?pesan=berhasil");
    exit;
} else {
    mysqli_stmt_close($stmtInsert);
    header("Location: tambah_user.php?error=gagal_simpan");
    exit;
}
?>

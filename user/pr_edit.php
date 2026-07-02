<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* Validasi method */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data_user.php");
    exit;
}

/* Validasi token CSRF */
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: data_user.php?error=invalid_request");
    exit;
}

/* Validasi input wajib (password tidak wajib -- opsional) */
if (empty($_POST['id_pengguna']) || empty($_POST['nama']) || empty($_POST['username']) || empty($_POST['role'])) {
    header("Location: data_user.php?error=data_tidak_lengkap");
    exit;
}

$id       = (int) $_POST['id_pengguna'];
$nama     = trim($_POST['nama']);
$username = trim($_POST['username']);
$password = $_POST['password'] ?? '';
$role     = $_POST['role'];

/* Whitelist nilai role */
$role_allowed = ['admin', 'teknisi', 'user'];
if (!in_array($role, $role_allowed)) {
    header("Location: edit.php?id=$id&error=role_tidak_valid");
    exit;
}

/* Pastikan user yang diedit masih ada dan belum dihapus */
$stmtCekUser = mysqli_prepare($koneksi, "
    SELECT id_pengguna FROM pengguna
    WHERE id_pengguna = ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtCekUser, "i", $id);
mysqli_stmt_execute($stmtCekUser);
$resultCekUser = mysqli_stmt_get_result($stmtCekUser);
mysqli_stmt_close($stmtCekUser);

if (mysqli_num_rows($resultCekUser) === 0) {
    header("Location: data_user.php?error=data_tidak_ditemukan");
    exit;
}

/* Pastikan username tidak dipakai user lain (kecualikan diri sendiri) */
$stmtCekUsername = mysqli_prepare($koneksi, "
    SELECT id_pengguna FROM pengguna
    WHERE username = ? AND id_pengguna != ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtCekUsername, "si", $username, $id);
mysqli_stmt_execute($stmtCekUsername);
$resultCekUsername = mysqli_stmt_get_result($stmtCekUsername);
mysqli_stmt_close($stmtCekUsername);

if (mysqli_num_rows($resultCekUsername) > 0) {
    header("Location: edit.php?id=$id&error=username_digunakan");
    exit;
}

/* Update dengan atau tanpa perubahan password */
if (!empty($password)) {

    if (strlen($password) < 6) {
        header("Location: edit.php?id=$id&error=password_terlalu_pendek");
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($koneksi, "
        UPDATE pengguna SET
            nama     = ?,
            username = ?,
            password = ?,
            role     = ?
        WHERE id_pengguna = ?
    ");
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $username, $hash, $role, $id);

} else {

    $stmt = mysqli_prepare($koneksi, "
        UPDATE pengguna SET
            nama     = ?,
            username = ?,
            role     = ?
        WHERE id_pengguna = ?
    ");
    mysqli_stmt_bind_param($stmt, "sssi", $nama, $username, $role, $id);
}

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: data_user.php?pesan=update_berhasil");
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: edit.php?id=$id&error=gagal_update");
    exit;
}
?>

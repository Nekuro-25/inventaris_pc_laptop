<?php

/* Sistem login terdiri dari 4 lapisan: Guard (Request & CSRF), Validasi Input, Autentikasi Database, dan Session */

/* Mengaktifkan session PHP dan memuat file koneksi database */

session_start();
require_once __DIR__ . "/../config/koneksi.php";

/* Memastikan halaman hanya menerima request POST dan tidak bisa diakses langsung melalui URL */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?error=invalid");
    exit;
}

/* Mengambil data dari form login */

/* trim digunakan untuk menghapus spasi di awal dan akhir username agar input lebih konsisten */

$username = trim((string) filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW));
$password = (string) filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
$csrf     = (string) filter_input(INPUT_POST, 'csrf', FILTER_UNSAFE_RAW);

/* Memastikan CSRF token yang dikirim form sesuai dengan token yang tersimpan di session */

if (empty($_SESSION['csrf']) || empty($csrf) || !hash_equals($_SESSION['csrf'], $csrf)) {
    header("Location: ../index.php?error=invalid");
    exit;
}

/* Memastikan username dan password tidak kosong */

if (empty($username) || empty($password)) {
    header("Location: ../index.php?error=empty");
    exit;
}

/* Memvalidasi format username agar hanya berisi huruf, angka, dan underscore */

if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    header("Location: ../index.php?error=format");
    exit;
}

/* Memastikan password minimal terdiri dari 6 karakter */

if (strlen($password) < 6) {
    header("Location: ../index.php?error=format");
    exit;
}

/* Menyiapkan prepared statement untuk mencegah SQL Injection */

$stmt = mysqli_prepare($koneksi, "
    SELECT id_pengguna, username, password, role
    FROM pengguna
    WHERE username = ?
    AND deleted_at IS NULL
    LIMIT 1
");

/* Menghentikan proses jika query gagal dipersiapkan */

if (!$stmt) {
    header("Location: ../index.php?error=invalid");
    exit;
}

/* Mengikat username ke query dan menjalankannya */

mysqli_stmt_bind_param($stmt, "s", $username);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: ../index.php?error=invalid");
    exit;
}

/* Mengambil hasil query tanpa mysqli_stmt_get_result agar kompatibel dengan lebih banyak environment PHP */

mysqli_stmt_bind_result(
    $stmt,
    $id_pengguna,
    $db_username,
    $db_password,
    $role
);

$data = null;

if (mysqli_stmt_fetch($stmt)) {
    $data = [
        'id_pengguna' => $id_pengguna,
        'username'    => $db_username,
        'password'    => $db_password,
        'role'        => $role
    ];
}

mysqli_stmt_close($stmt);

/* Memverifikasi password hash, membuat session login, lalu mengarahkan user ke dashboard */

if ($data && password_verify($password, $data['password'])) {

    session_regenerate_id(true);

    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['id_pengguna'] = $data['id_pengguna'];

    unset($_SESSION['csrf']);

    header("Location: ../dashboard/index.php");
    exit;
}

/* Username atau password tidak sesuai */

header("Location: ../index.php?error=invalid");
exit;
?>
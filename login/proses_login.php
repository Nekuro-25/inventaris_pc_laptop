/*Untuk login ke dashboard, ada 4 lapis sistem. Guard (Validasi dan CSRF), Input validasi, Autentikasi database, Sesi */

/* Mengaktifkan sistem session PHP dan mengambil sistem koneksi untuk dipakai sebagai penghubung ke database*/

<?php
session_start();
require_once __DIR__ . "/../config/koneksi.php"; 

/* Memastikan endpoint hanya bisa diakses via POST karena GET bisa dibuka via URL browser dan untuk mencegah abuse / direct access */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?error=invalid");
    exit;
}

/* Mengambil input dari request dengan filtering dan lebih aman daripada $_POST langsung serta strukturnya lebih rapi*/

/* trim untuk menghilangkan spasi di awal dan akhir agar tidak terjadi kesalahan */

$username = trim(filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW));
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
$csrf     = filter_input(INPUT_POST, 'csrf', FILTER_UNSAFE_RAW);

/* Membandingkan token dengan aman. Untuk mencegah mencegah timing attack (side-channel attack) */

if (empty($_SESSION['csrf']) || empty($csrf) || !hash_equals($_SESSION['csrf'], $csrf)) {
    header("Location: ../index.php?error=invalid");
    exit;
}

/* 2. Cek apakah variabelnya kosong */

if (empty($username) || empty($password)) {
    header("Location: ../index.php?error=empty");
    exit;
}

/* 3. Validasi format username untuk mencegah karakter aneh (SQL/XSS injection) */
if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    header("Location: ../index.php?error=format");
    exit;
}

/* Minimal password 6 digit */

if (strlen($password) < 6) {
    header("Location: ../index.php?error=format");
    exit;
}

/* Prepared statement untuk mencegah SQL Injection */

$stmt = mysqli_prepare($koneksi, "
    SELECT id_pengguna, username, password, role
    FROM pengguna
    WHERE username = ?
    AND deleted_at IS NULL
    LIMIT 1
");

/* Mengikat parameter ke query */

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

/* Validasi Password, password di DB pakai hash dan mengganti session id setelah login, menyimpan state login user, menghapus CSRF setelah login, redirect user ke dashboard */

if ($data && password_verify($password, $data['password'])) {

    session_regenerate_id(true);

    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['id_pengguna'] = $data['id_pengguna'];

    unset($_SESSION['csrf']);

    header("Location: ../dashboard/index.php");
    exit;
}

/* INVALID LOGIN */
header("Location: ../index.php?error=invalid");
exit;
?>
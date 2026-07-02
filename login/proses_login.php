<?php
// proses_login.php

require_once __DIR__ . "/../config/session.php";
require_once __DIR__ . "/../config/koneksi.php";
require_once __DIR__ . "/../config/constants.php";

// --- Fungsi Helper (cek keberadaan agar tidak duplikasi) ---
if (!function_exists('flash')) {
    function flash($key, $message = null) {
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
        } elseif (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}

if (!function_exists('redirect')) {
    function redirect($path, $error = null) {
        if ($error !== null) {
            flash('login_error', $error);
        }
        header("Location: " . BASE_PATH . $path);
        exit;
    }
}

if (!function_exists('log_attempt')) {
    function log_attempt($username, $status, $ip) {
        $log = date('Y-m-d H:i:s') . " | IP: $ip | User: $username | Status: $status" . PHP_EOL;
        file_put_contents(__DIR__ . '/../logs/login.log', $log, FILE_APPEND);
    }
}

// --- Cek Koneksi Database (dipindah ke awal karena rate limiting sekarang butuh $koneksi) ---
if (!$koneksi) {
    log_attempt('', 'db_connection_error', $_SERVER['REMOTE_ADDR']);
    redirect('index.php', 'Gagal terhubung ke database. Silakan coba lagi.');
    exit;
}

// --- Rate Limiting (per IP, disimpan persisten di database) ---
$ip = $_SERVER['REMOTE_ADDR'];

$stmtRate = mysqli_prepare($koneksi, "
    SELECT attempt_count, first_attempt_at
    FROM login_attempts
    WHERE ip = ?
    LIMIT 1
");
mysqli_stmt_bind_param($stmtRate, "s", $ip);
mysqli_stmt_execute($stmtRate);
mysqli_stmt_bind_result($stmtRate, $rate_count, $rate_first_attempt);
$rate_found = mysqli_stmt_fetch($stmtRate);
mysqli_stmt_close($stmtRate);

if (!$rate_found) {
    $rate_count = 0;
    $rate_first_attempt = date('Y-m-d H:i:s');
}

// Reset jika lebih dari 15 menit sejak percobaan pertama
if (strtotime($rate_first_attempt) !== false && (time() - strtotime($rate_first_attempt) > 900)) {
    $rate_count = 0;
    $rate_first_attempt = date('Y-m-d H:i:s');

    $stmtReset = mysqli_prepare($koneksi, "DELETE FROM login_attempts WHERE ip = ?");
    mysqli_stmt_bind_param($stmtReset, "s", $ip);
    mysqli_stmt_execute($stmtReset);
    mysqli_stmt_close($stmtReset);

    $rate_found = false;
}

// Cek batas percobaan
if ($rate_count >= 5) {
    log_attempt('', 'rate_limit_exceeded', $ip);
    redirect('index.php', 'Terlalu banyak percobaan login. Silakan coba lagi setelah 15 menit.');
    exit;
}

/* Helper: tambah 1 percobaan gagal untuk IP ini (dipanggil di setiap jalur gagal) */
if (!function_exists('rate_limit_increment')) {
    function rate_limit_increment($koneksi, $ip, $rate_found, $first_attempt) {
        if ($rate_found) {
            $stmt = mysqli_prepare($koneksi, "
                UPDATE login_attempts SET attempt_count = attempt_count + 1 WHERE ip = ?
            ");
            mysqli_stmt_bind_param($stmt, "s", $ip);
        } else {
            $stmt = mysqli_prepare($koneksi, "
                INSERT INTO login_attempts (ip, attempt_count, first_attempt_at)
                VALUES (?, 1, ?)
            ");
            mysqli_stmt_bind_param($stmt, "ss", $ip, $first_attempt);
        }
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// --- Validasi Request Method ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php', 'Metode request tidak valid.');
    exit;
}

// --- Ambil Input ---
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$csrf     = (string) ($_POST['csrf'] ?? '');

// --- Validasi CSRF ---
if (empty($_SESSION['csrf']) || empty($csrf) || !hash_equals($_SESSION['csrf'], $csrf)) {
    log_attempt($username, 'csrf_invalid', $ip);
    redirect('index.php', 'Token keamanan tidak valid. Silakan refresh halaman.');
    exit;
}

// --- Validasi Input ---
if (empty($username) || empty($password)) {
    rate_limit_increment($koneksi, $ip, $rate_found, $rate_first_attempt);
    redirect('index.php', 'Username dan password tidak boleh kosong.');
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    rate_limit_increment($koneksi, $ip, $rate_found, $rate_first_attempt);
    redirect('index.php', 'Format username tidak valid (hanya huruf, angka, underscore).');
    exit;
}

if (strlen($password) < 6) {
    rate_limit_increment($koneksi, $ip, $rate_found, $rate_first_attempt);
    redirect('index.php', 'Password minimal 6 karakter.');
    exit;
}

// --- Query ke Database (prepared statement) ---
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id_pengguna, username, password, role
     FROM pengguna
     WHERE username = ? AND deleted_at IS NULL
     LIMIT 1"
);

if (!$stmt) {
    log_attempt($username, 'db_prepare_error', $ip);
    redirect('index.php', 'Terjadi kesalahan sistem. Silakan coba lagi.');
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $username);
if (!mysqli_stmt_execute($stmt)) {
    log_attempt($username, 'db_execute_error', $ip);
    mysqli_stmt_close($stmt);
    redirect('index.php', 'Terjadi kesalahan sistem. Silakan coba lagi.');
    exit;
}

mysqli_stmt_bind_result($stmt, $id_pengguna, $db_username, $db_password, $role);
$user = null;
if (mysqli_stmt_fetch($stmt)) {
    $user = [
        'id'        => $id_pengguna,
        'username'  => $db_username,
        'password'  => $db_password,
        'role'      => $role
    ];
}
mysqli_stmt_close($stmt);

// --- Verifikasi User ---
if (!$user) {
    rate_limit_increment($koneksi, $ip, $rate_found, $rate_first_attempt);
    log_attempt($username, 'user_not_found', $ip);
    redirect('index.php', 'Username atau password salah.');
    exit;
}

// Cek password
if (!password_verify($password, $user['password'])) {
    rate_limit_increment($koneksi, $ip, $rate_found, $rate_first_attempt);
    log_attempt($username, 'password_wrong', $ip);
    redirect('index.php', 'Username atau password salah.');
    exit;
}

// --- Login Berhasil ---
session_regenerate_id(true);

$_SESSION['username']    = $user['username'];
$_SESSION['role']        = $user['role'];
$_SESSION['id_pengguna'] = $user['id'];

// Hapus CSRF lama (akan dibuat ulang di halaman login)
unset($_SESSION['csrf']);
// Hapus counter rate limiting (persisten di database)
$stmtClearRate = mysqli_prepare($koneksi, "DELETE FROM login_attempts WHERE ip = ?");
mysqli_stmt_bind_param($stmtClearRate, "s", $ip);
mysqli_stmt_execute($stmtClearRate);
mysqli_stmt_close($stmtClearRate);

log_attempt($username, 'success', $ip);

redirect('dashboard/index.php');
exit;

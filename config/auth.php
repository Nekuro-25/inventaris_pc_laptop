<?php

/* Memuat koneksi database */

require_once __DIR__ . "/koneksi.php";

/* Memastikan session sudah aktif */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Memastikan user sudah login */

if (
    !isset($_SESSION['username']) ||
    !is_string($_SESSION['username']) ||
    trim($_SESSION['username']) === ''
) {
    session_destroy();

    header("Location: ../index.php");
    exit;
}

/* Mengambil username dari session */

$username = trim($_SESSION['username']);

/* Menyiapkan prepared statement untuk mengambil data user */

$stmt = mysqli_prepare($koneksi, "
    SELECT
        id_pengguna,
        username,
        role
    FROM pengguna
    WHERE username = ?
    AND deleted_at IS NULL
    LIMIT 1
");

/* Menghentikan proses jika query gagal dipersiapkan */

if (!$stmt) {

    session_destroy();

    header("Location: ../index.php");
    exit;
}

/* Mengikat username ke query */

mysqli_stmt_bind_param($stmt, "s", $username);

/* Menjalankan query */

if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    session_destroy();

    header("Location: ../index.php");
    exit;
}

/* Mengambil data user tanpa mysqli_stmt_get_result agar kompatibel dengan lebih banyak environment PHP */

mysqli_stmt_bind_result(
    $stmt,
    $id_pengguna,
    $db_username,
    $db_role
);

$user = null;

if (mysqli_stmt_fetch($stmt)) {
    $user = [
        'id_pengguna' => $id_pengguna,
        'username'    => $db_username,
        'role'        => $db_role
    ];
}

mysqli_stmt_close($stmt);/* Memastikan user masih tersedia di database */

if (!$user) {

    session_destroy();

    header("Location: ../index.php");
    exit;
}

/* Normalisasi role */

$user['role'] = strtolower(trim($user['role']));

/* Memastikan role valid */

$allowed_roles = [
    'admin',
    'teknisi',
    'user'
];

if (!in_array($user['role'], $allowed_roles, true)) {

    session_destroy();

    header("Location: ../index.php");
    exit;
}

/* Menyinkronkan data session dengan database */

$_SESSION['id_pengguna'] = (int) $user['id_pengguna'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

/* Menentukan role user yang sedang login */

$isAdmin = ($user['role'] === 'admin');
$isTeknisi = ($user['role'] === 'teknisi');
$isUser = ($user['role'] === 'user');

/* Membatasi akses hanya untuk admin */

function onlyAdmin()
{
    global $isAdmin;

    if (!$isAdmin) {
        header("Location: ../dashboard/index.php");
        exit;
    }
}

/* Mengizinkan akses untuk admin dan teknisi */

function adminOrTeknisi()
{
    global $isAdmin, $isTeknisi;

    if (!$isAdmin && !$isTeknisi) {
        header("Location: ../dashboard/index.php");
        exit;
    }
}

/* Memblokir akses untuk user biasa */

function blockUser()
{
    global $isUser;

    if ($isUser) {
        header("Location: ../dashboard/index.php");
        exit;
    }
}

?>

<?php

// ============================================================
// CARA PAKAI:
// Buat file ".env" di ROOT project (sejajar folder config/)
// Isi file .env:
//   DB_HOST=localhost
//   DB_USER=nama_user_db_anda
//   DB_PASS=password_db_anda
//   DB_NAME=inventaris_db
//
// Pastikan file .env masuk ke .gitignore agar tidak ikut di-commit!
// Tambahkan baris ini di .gitignore:  .env
// ============================================================

// ✅ FIX #13: Baca kredensial dari file .env, bukan hardcode
$env_path = __DIR__ . '/../../.env'; // path ke root project

if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue; // skip komentar
        if (strpos($line, '=') !== false) {
            [$key, $val] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val);
        }
    }
}

$host     = $_ENV['DB_HOST'] ?? 'localhost';
$user     = $_ENV['DB_USER'] ?? 'root';       // fallback jika .env tidak ada
$password = $_ENV['DB_PASS'] ?? '';
$database = $_ENV['DB_NAME'] ?? 'inventaris_db';

$koneksi = mysqli_connect($host, $user, $password, $database);

// ✅ FIX #14: Error koneksi tidak menampilkan detail teknis ke browser
if (!$koneksi) {
    // Log error ke file (tidak tampil ke user)
    error_log("DB connection failed: " . mysqli_connect_error());
    die("Sistem tidak dapat terhubung ke database. Hubungi administrator.");
}

mysqli_set_charset($koneksi, "utf8mb4");
?>

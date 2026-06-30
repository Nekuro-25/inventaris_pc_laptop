<?php

// ============================================================
// Baca kredensial dari file .env di root project
// ============================================================

$env_path = __DIR__ . "/../.env";

if (file_exists($env_path)) {

    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (str_starts_with(trim($line), '#')) continue;

        if (strpos($line, '=') !== false) {

            [$key, $val] = explode('=', $line, 2);

            $_ENV[trim($key)] = trim($val);
        }
    }
}

$host     = $_ENV['DB_HOST'] ?? '127.0.0.1';
$user     = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
$database = $_ENV['DB_NAME'] ?? 'inventaris_db';

$koneksi = mysqli_connect($host, $user, $password, $database);

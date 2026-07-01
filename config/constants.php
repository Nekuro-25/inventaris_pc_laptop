<?php
// config/constants.php

// Deteksi base path berdasarkan lokasi file ini
// Asumsi: constants.php berada di folder config/, satu level di bawah root proyek
$rootDir = dirname(__DIR__); // absolute path ke folder inventaris
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$relativePath = str_replace($docRoot, '', $rootDir);
$relativePath = str_replace('\\', '/', $relativePath); // untuk Windows

// Jika root proyek sama dengan document root, basePath = '/'
if ($relativePath === '' || $relativePath === '/') {
    $basePath = '/';
} else {
    $basePath = rtrim($relativePath, '/') . '/';
}

define('BASE_PATH', $basePath);

// BASE_URL untuk keperluan absolute (opsional)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
define('BASE_URL', $protocol . '://' . $host . rtrim(BASE_PATH, '/') . '/');

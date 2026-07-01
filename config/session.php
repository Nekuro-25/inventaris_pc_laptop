<?php
// config/session.php

// Atur cookie session sebelum session_start()
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => $_SERVER['HTTP_HOST'],
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => isset($_SERVER['HTTPS'])
]);

// Mulai session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

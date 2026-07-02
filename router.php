<?php

/**
 * router.php
 *
 * Router untuk PHP built-in server (php -S).
 * Berfungsi sebagai pengganti .htaccess, karena PHP built-in
 * server (dipakai di Termux) tidak membaca .htaccess sama sekali.
 *
 * Cara pakai:
 *   php -S 127.0.0.1:8000 router.php
 *
 * File/folder sensitif tetap diblokir walau tanpa Apache.
 */

$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

/* Daftar pola path yang harus diblokir -- selaras dengan aturan di .htaccess */
$blocked_patterns = [
    '#^/\.env$#',          // kredensial database
    '#^/\.git(/|$)#',      // source code + history git
    '#^/\.htaccess$#',     // file konfigurasi apache itu sendiri
    '#^/\.gitignore$#',
    '#^/\.gitattributes$#',
    '#^/config(/|$)#',     // auth.php, koneksi.php, session.php, constants.php
    '#^/database(/|$)#',   // inventaris_db.sql
    '#^/logs(/|$)#',       // login.log
];

foreach ($blocked_patterns as $pattern) {
    if (preg_match($pattern, $path)) {
        http_response_code(403);
        header('Content-Type: text/plain');
        echo "403 Forbidden";
        return true;
    }
}

/* Selain itu, layani seperti biasa (static file atau .php dieksekusi normal) */
return false;

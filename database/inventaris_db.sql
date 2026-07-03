SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS inventaris_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE inventaris_db;



/* Membuat tabel lokasi */

CREATE TABLE lokasi (

    id_lokasi INT AUTO_INCREMENT PRIMARY KEY,

    nama_lokasi VARCHAR(100) NOT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL

);



/* Membuat tabel pengguna */

CREATE TABLE pengguna (

    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,

    nama VARCHAR(100) NOT NULL,

    username VARCHAR(50) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM(
        'admin',
        'teknisi',
        'user'
    ) NOT NULL DEFAULT 'user',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL

);



/* Menambahkan akun admin bawaan */

INSERT INTO pengguna (

    nama,
    username,
    password,
    role

) VALUES (

    'admin',
    'admin01',
    '$2y$10$IhRaokOlLAfCDkKEokR5NetH0oxdWI6nn2FOfYV1/SqevjP8ymhNe',
    'admin'

);



/* Membuat tabel inventaris */

CREATE TABLE inventaris (

    id_barang INT AUTO_INCREMENT PRIMARY KEY,

    kode_barang VARCHAR(50) NOT NULL,

    nama_barang VARCHAR(100) NOT NULL,

    jenis ENUM(
        'PC',
        'Laptop'
    ) NOT NULL,

    merk VARCHAR(100) DEFAULT NULL,

    processor VARCHAR(100) DEFAULT NULL,

    ram VARCHAR(50) DEFAULT NULL,

    storage VARCHAR(50) DEFAULT NULL,

    id_lokasi INT DEFAULT NULL,

    status ENUM(
        'tersedia',
        'dipinjam',
        'rusak'
    ) NOT NULL DEFAULT 'tersedia',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL,
    deleted_at DATETIME DEFAULT NULL,

    CONSTRAINT inventaris_ibfk_1
        FOREIGN KEY (id_lokasi)
        REFERENCES lokasi(id_lokasi)

);



/* Membuat tabel peminjaman */

CREATE TABLE peminjaman (

    id INT AUTO_INCREMENT PRIMARY KEY,

    id_barang INT NOT NULL,

    nama_peminjam VARCHAR(100) NOT NULL,

    tanggal_pinjam DATE DEFAULT NULL,

    tanggal_kembali DATE DEFAULT NULL,

    status ENUM(
        'dipinjam',
        'kembali'
    ) NOT NULL DEFAULT 'dipinjam',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,

    CONSTRAINT peminjaman_ibfk_1
        FOREIGN KEY (id_barang)
        REFERENCES inventaris(id_barang)

);

/* Membuat tabel perbaikan */

CREATE TABLE perbaikan (

    id_perbaikan INT AUTO_INCREMENT PRIMARY KEY,

    id_barang INT NOT NULL,

    tanggal DATE DEFAULT NULL,

    kerusakan TEXT DEFAULT NULL,

    tindakan TEXT DEFAULT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL,
    deleted_at DATETIME DEFAULT NULL,

    CONSTRAINT perbaikan_ibfk_1
        FOREIGN KEY (id_barang)
        REFERENCES inventaris(id_barang)

);



/* Membuat tabel login_attempts (rate limiting login, persisten per IP) */

CREATE TABLE login_attempts (

    ip VARCHAR(45) NOT NULL PRIMARY KEY,

    attempt_count INT NOT NULL DEFAULT 0,

    first_attempt_at DATETIME NOT NULL

);

COMMIT;

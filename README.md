Sistem Inventaris PC & Laptop

Aplikasi inventaris berbasis PHP Native dan MariaDB untuk mengelola data PC, laptop, peminjaman, perbaikan, serta manajemen pengguna.

---

Persyaratan

Pastikan perangkat telah terpasang:

- PHP 8.2 atau lebih baru
- MariaDB 11+ / MySQL 8+
- Git
- Termux (Android) atau Linux

---

Clone Repository

git clone git@github.com:Nekuro-25/inventaris_pc_laptop.git
cd (masuk ke folder projek)

---

Menjalankan MariaDB

Jalankan service MariaDB:

mariadbd-safe --datadir=$PREFIX/var/lib/mysql

Masuk ke MariaDB:

mariadb -u (username, tidak perlu tanda kurung) -p

---

Membuat Database

Di MariaDB jalankan:

CREATE DATABASE inventaris_db;

Import database:

mariadb -u <username> -p inventaris_db < database/inventaris_db.sql

atau melalui prompt MariaDB sesuai kebutuhan.

---

Konfigurasi Database

Project menggunakan file ".env".

Contoh:

DB_HOST=127.0.0.1
DB_USER=NamaUserMariaDB
DB_PASS=PasswordMariaDB
DB_NAME=inventaris_db

Penting

Nilai berikut harus sama dengan akun MariaDB yang digunakan.

Misalnya jika login MariaDB menggunakan:

mariadb -u Nekuro -pNekuro25

maka ".env" harus berisi:

DB_USER=Nekuro
DB_PASS=Nekuro25

Jangan menggunakan "root" apabila MariaDB tidak dikonfigurasi menggunakan akun tersebut.

---

Menjalankan Web Server

Masuk ke folder project lalu jalankan:

php -S 127.0.0.1:8000

Server akan berjalan di:

http://127.0.0.1:8000

Buka browser kemudian akses alamat tersebut.

---

Login Default

Role| Username| Password
Admin| admin01| admin01

«Password pada database disimpan menggunakan "password_hash()".»

---

Struktur Folder

config/
database/
dashboard/
inventaris/
js/
css/
laporan/
lokasi/
login/
peminjaman/
perbaikan/
user/

index.php
logout.php
.env
README.md

---

Troubleshooting

HTTP 500 saat Login

Penyebab yang paling sering adalah konfigurasi database pada ".env" tidak sesuai.

Periksa:

DB_HOST
DB_USER
DB_PASS
DB_NAME

Pastikan user dan password sama dengan akun MariaDB yang digunakan.

---

Access denied for user

Contoh:

Access denied for user 'root'@'localhost'

Artinya PHP mencoba login menggunakan akun yang salah.

Perbaiki isi ".env".

---

Session atau Header Error

Contoh:

Headers already sent

Biasanya terjadi karena masih ada:

echo
var_dump
print_r

yang digunakan saat debugging.

Hapus seluruh output tersebut sebelum menjalankan aplikasi.

---

Tidak Bisa Masuk Dashboard

Periksa:

- Session aktif.
- Konfigurasi ".env".
- Database berhasil diimport.
- User admin tersedia pada tabel "pengguna".

---

Catatan Pengembangan

Project ini dikembangkan menggunakan:

- PHP Native
- MariaDB
- Termux
- Neovim

Branch "main" digunakan sebagai branch stabil.

Branch pengembangan digunakan untuk eksperimen sebelum perubahan digabungkan ke "main".

Selalu lakukan pengujian sebelum melakukan merge ke branch utama.

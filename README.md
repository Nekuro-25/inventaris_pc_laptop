🖥️ Yang Dibutuhkan Sebelum Mulai
Project ini adalah aplikasi PHP + MySQL, jadi butuh:

XAMPP (sudah include Apache + MySQL + PHP, gratis)
Browser (Chrome, Firefox, dll)
File project Inventaris yang sudah ada


📥 LANGKAH 1 — Install XAMPP

Buka browser, pergi ke https://www.apachefriends.org
Klik Download XAMPP for Windows
Jalankan installer yang didownload → klik Next terus sampai selesai
Setelah selesai, buka XAMPP Control Panel (muncul otomatis atau cari di Start Menu)


▶️ LANGKAH 2 — Jalankan XAMPP
Di XAMPP Control Panel:

Klik Start di baris Apache
Klik Start di baris MySQL
Keduanya harus berwarna hijau → artinya server sudah berjalan


📁 LANGKAH 3 — Taruh File Project

Buka File Explorer
Pergi ke folder: C:\xampp\htdocs\
Copy folder Inventaris (hasil extract zip tadi) ke dalam folder htdocs tersebut
Hasilnya: C:\xampp\htdocs\Inventaris\


🗄️ LANGKAH 4 — Buat Database

Buka browser → ketik http://localhost/phpmyadmin
Klik New di panel kiri → beri nama database: inventaris_db → klik Create
Setelah database terbuat, klik tab Import (di bagian atas)
Klik Choose File → cari dan pilih file: C:\xampp\htdocs\Inventaris\database\inventaris_db.sql
Klik Import (tombol di bawah) → tunggu sampai muncul pesan sukses hijau


⚙️ LANGKAH 5 — Setting Koneksi Database

Buka file: C:\xampp\htdocs\Inventaris\config\koneksi.php (pakai Notepad atau VS Code)
Pastikan isinya seperti ini:

php$host = "localhost";
$user = "root";
$pass = "";           // kosong, default XAMPP
$db   = "inventaris_db";

Simpan file jika ada perubahan


🌐 LANGKAH 6 — Buka Aplikasi

Buka browser
Ketik: http://localhost/Inventaris
Akan muncul halaman Login


🔑 LANGKAH 7 — Login & Testing
Cek file SQL untuk username/password default. Biasanya:
Role Username Password Admin admin01 admin01

Proyek ini sekarang dikembangkan menggunakan Termux di Android 14.

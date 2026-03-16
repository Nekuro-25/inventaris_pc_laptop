<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah User</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<script>

function togglePassword() {

var password = document.getElementById("password");

if (password.type === "password") {

password.type = "text";

} else {

password.type = "password";

}

}

</script>

<body>

<div class="container">

<!-- Sidebar -->
<div class="sidebar">

<h2>Inventaris</h2>

<ul>
<li><a href="../dashboard/index.php">Dashboard</a></li>
<li><a href="../inventaris/data.php">Data Inventaris</a></li>
<li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
<li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
<li><a href="../laporan/laporan.php">Laporan</a></li>
<li><a href="data_user.php">Manajemen User</a></li>
<li><a href="../logout.php">Logout</a></li>
</ul>

</div>

<!-- Main Content -->
<div class="main">

<div class="topbar">
<h1>Tambah User</h1>
</div>

<div class="form-container">

<form method="POST" action="pr_tambah.php">

<div class="form-group">
<label>Nama</label>
<input type="text" name="nama" placeholder="Masukkan nama" required>
</div>

<div class="form-group">
<label>Username</label>
<input type="text" name="username" placeholder="Masukkan username" required>
</div>

<div class="form-group">
<label>Password</label>

<div style="display:flex; gap:10px;">

<input type="password" id="password" name="password" placeholder="Masukkan password" required>

<button type="button" onclick="togglePassword()">Lihat</button>

</div>

</div>

<div class="form-group">
<label>Role</label>

<select name="role" required>

<option value="">-- Pilih Role --</option>
<option value="Admin">Admin</option>
<option value="Teknisi">Teknisi</option>
<option value="User">User</option>

</select>

</div>

<div class="form-buttons">

<button class="btn-simpan" type="submit" name="simpan">
Simpan
</button>

<a href="data_user.php" class="btn-batal">
Batal
</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>
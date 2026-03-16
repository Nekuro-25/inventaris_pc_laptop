<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

/* ambil data lokasi */
$data_lokasi = mysqli_query($koneksi,"SELECT * FROM lokasi");

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Inventaris</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="container">

<!-- Sidebar -->
<div class="sidebar">

<h2>Inventaris</h2>

<ul>
<li><a href="../dashboard/index.php">Dashboard</a></li>
<li><a href="data.php">Data Inventaris</a></li>
<li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
<li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
<li><a href="../laporan/laporan.php">Laporan</a></li>
<li><a href="../user/data_user.php">Manajemen User</a></li>
<li><a href="../logout.php">Logout</a></li>
</ul>

</div>

<!-- Main Content -->
<div class="main">

<div class="topbar">
<h1>Tambah Inventaris</h1>
</div>

<div class="form-container">

<form method="POST" action="pr_tambah.php">

<div class="form-group">
<label>Kode Barang</label>
<input type="text" name="kode_barang" required>
</div>

<div class="form-group">
<label>Nama Barang</label>
<input type="text" name="nama_barang" required>
</div>

<div class="form-group">
<label>Jenis</label>
<select name="jenis">
<option value="PC">PC</option>
<option value="Laptop">Laptop</option>
</select>
</div>

<div class="form-group">
<label>Merk</label>
<input type="text" name="merk">
</div>

<div class="form-group">
<label>Processor</label>
<input type="text" name="processor">
</div>

<div class="form-group">
<label>RAM</label>
<input type="text" name="ram">
</div>

<div class="form-group">
<label>Storage</label>
<input type="text" name="storage">
</div>

<div class="form-group">
<label>Lokasi</label>

<select name="id_lokasi" required>

<option value="">-- Pilih Lokasi --</option>

<?php
while($lokasi = mysqli_fetch_assoc($data_lokasi)){
?>

<option value="<?php echo $lokasi['id_lokasi']; ?>">
<?php echo $lokasi['nama_lokasi']; ?>
</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Status</label>

<select name="status">
<option value="aktif">Aktif</option>
<option value="rusak">Rusak</option>
<option value="maintenance">Maintenance</option>
</select>

</div>

<div class="form-buttons">

<button class="btn-simpan" type="submit">Simpan</button>

<a href="data.php" class="btn-batal">Batal</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>
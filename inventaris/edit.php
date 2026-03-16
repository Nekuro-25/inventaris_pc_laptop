<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

/* ambil id dari URL */
$id = $_GET['id'];

/* ambil data inventaris */
$query = mysqli_query($koneksi,"
SELECT * FROM inventaris WHERE id_barang='$id'
");

$data = mysqli_fetch_assoc($query);

/* ambil data lokasi untuk dropdown */
$data_lokasi = mysqli_query($koneksi,"SELECT * FROM lokasi");

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Inventaris</title>

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
<h1>Edit Inventaris</h1>
</div>

<div class="form-container">

<form method="POST" action="pr_edit.php">

<input type="hidden" name="id_barang" value="<?php echo $data['id_barang']; ?>">

<div class="form-group">
<label>Kode Barang</label>
<input type="text" name="kode_barang" value="<?php echo $data['kode_barang']; ?>">
</div>

<div class="form-group">
<label>Nama Barang</label>
<input type="text" name="nama_barang" value="<?php echo $data['nama_barang']; ?>">
</div>

<div class="form-group">
<label>Jenis Barang</label>

<select name="jenis">

<option value="PC" <?php if($data['jenis']=="PC") echo "selected"; ?>>PC</option>

<option value="Laptop" <?php if($data['jenis']=="Laptop") echo "selected"; ?>>Laptop</option>

</select>

</div>

<div class="form-group">
<label>Merk</label>
<input type="text" name="merk" value="<?php echo $data['merk']; ?>">
</div>

<div class="form-group">
<label>Processor</label>
<input type="text" name="processor" value="<?php echo $data['processor']; ?>">
</div>

<div class="form-group">
<label>RAM</label>
<input type="text" name="ram" value="<?php echo $data['ram']; ?>">
</div>

<div class="form-group">
<label>Storage</label>
<input type="text" name="storage" value="<?php echo $data['storage']; ?>">
</div>

<div class="form-group">
<label>Lokasi</label>

<select name="id_lokasi">

<?php while($lokasi = mysqli_fetch_assoc($data_lokasi)){ ?>

<option value="<?php echo $lokasi['id_lokasi']; ?>"

<?php if($lokasi['id_lokasi']==$data['id_lokasi']) echo "selected"; ?>

>

<?php echo $lokasi['nama_lokasi']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Status</label>

<select name="status">

<option value="aktif" <?php if($data['status']=="aktif") echo "selected"; ?>>Aktif</option>

<option value="rusak" <?php if($data['status']=="rusak") echo "selected"; ?>>Rusak</option>

<option value="maintenance" <?php if($data['status']=="maintenance") echo "selected"; ?>>Maintenance</option>

</select>

</div>

<div class="form-buttons">

<button class="btn-simpan" type="submit">Update</button>

<a href="data.php" class="btn-batal">Batal</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>
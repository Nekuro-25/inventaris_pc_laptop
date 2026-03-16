<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

/* ambil id dari URL */
$id = $_GET['id'];

/* ambil data perbaikan */
$query = mysqli_query($koneksi,"
SELECT * FROM perbaikan WHERE id_perbaikan='$id'
");

$data = mysqli_fetch_assoc($query);

/* ambil data inventaris untuk dropdown */
$data_barang = mysqli_query($koneksi,"SELECT * FROM inventaris");

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Perbaikan</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="container">

<!-- Sidebar -->
<div class="sidebar">

<h2>Inventaris</h2>

<ul>
<li><a href="../dashboard/index.php">Dashboard</a></li>
<li><a href="../inventaris/data.php">Data Inventaris</a></li>
<li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
<li><a href="data_perbaikan.php">Perbaikan</a></li>
<li><a href="../laporan/laporan.php">Laporan</a></li>
<li><a href="../user/data_user.php">Manajemen User</a></li>
<li><a href="../logout.php">Logout</a></li>
</ul>

</div>

<!-- Main Content -->
<div class="main">

<div class="topbar">
<h1>Edit Data Perbaikan</h1>
</div>

<div class="form-container">

<form method="POST" action="pr_edit.php">

<input type="hidden" name="id_perbaikan" value="<?php echo $data['id_perbaikan']; ?>">

<div class="form-group">

<label>Pilih Barang</label>

<select name="id_barang">

<?php
while($barang = mysqli_fetch_assoc($data_barang)){
?>

<option value="<?php echo $barang['id_barang']; ?>"

<?php
if($barang['id_barang'] == $data['id_barang']){
echo "selected";
}
?>

>

<?php echo $barang['kode_barang']; ?> - <?php echo $barang['nama_barang']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Tanggal Perbaikan</label>
<input type="date" name="tanggal" value="<?php echo $data['tanggal']; ?>">
</div>

<div class="form-group">
<label>Kerusakan</label>
<input type="text" name="kerusakan" value="<?php echo $data['kerusakan']; ?>">
</div>

<div class="form-group">
<label>Tindakan</label>
<input type="text" name="tindakan" value="<?php echo $data['tindakan']; ?>">
</div>

<div class="form-buttons">

<button class="btn-simpan" type="submit">Update</button>

<a href="data_perbaikan.php" class="btn-batal">Batal</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>
<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$query = mysqli_query($koneksi,"
SELECT inventaris.*, lokasi.nama_lokasi
FROM inventaris
JOIN lokasi ON inventaris.id_lokasi = lokasi.id_lokasi
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Inventaris</title>

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
<h1>Data Inventaris</h1>
</div>

<div class="table-container">

<a href="tambah.php" class="btn-tambah">+ Tambah Inventaris</a>

<table>

<thead>
<tr>
<th>No</th>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>Jenis</th>
<th>Merk</th>
<th>Lokasi</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php
$no = 1;

while($row = mysqli_fetch_assoc($query)){
?>

<tr>

<td><?php echo $no++; ?></td>

<td><?php echo $row['kode_barang']; ?></td>

<td><?php echo $row['nama_barang']; ?></td>

<td><?php echo $row['jenis']; ?></td>

<td><?php echo $row['merk']; ?></td>

<td><?php echo $row['nama_lokasi']; ?></td>

<td><?php echo $row['status']; ?></td>

<td>

<a href="edit.php?id=<?php echo $row['id_barang']; ?>" class="btn-edit">Edit</a>

<a href="hapus.php?id=<?php echo $row['id_barang']; ?>" 
class="btn-hapus"
onclick="return konfirmasiHapus()">
Hapus
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script src="../js/konfirmasi.js"></script>

</body>
</html>
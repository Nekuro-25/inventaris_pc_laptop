<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

/* ambil data user */
$query = mysqli_query($koneksi,"SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen User</title>

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
<li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
<li><a href="../laporan/laporan.php">Laporan</a></li>
<li><a href="data_user.php">Manajemen User</a></li>
<li><a href="../logout.php">Logout</a></li>
</ul>

</div>

<!-- Main Content -->
<div class="main">

<div class="topbar">
<h1>Manajemen User</h1>
</div>

<div class="table-container">

<a href="tambah_user.php" class="btn-tambah">+ Tambah User</a>

<table>

<thead>

<tr>
<th>No</th>
<th>Nama</th>
<th>Username</th>
<th>Role</th>
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

<td><?php echo $row['nama']; ?></td>

<td><?php echo $row['username']; ?></td>

<td><?php echo $row['role']; ?></td>

<td>

<a href="edit.php?id=<?php echo $row['id_user']; ?>" class="btn-edit">
Edit
</a>

<a href="hapus_user.php?id=<?php echo $row['id_user']; ?>"
class="btn-hapus"
onclick="return konfirmasiHapus('Yakin ingin menghapus data ini?')">
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
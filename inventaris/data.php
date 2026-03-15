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
            <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="#">Logout</a></li>
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

<tr>
<td>1</td>
<td>PC01</td>
<td>PC Lab 1</td>
<td>PC</td>
<td>Dell</td>
<td>Lab Komputer</td>
<td>Aktif</td>
<td>
<a href="edit.php" class="btn-edit">Edit</a>
<button class="btn-hapus">Hapus</button>
</td>
</tr>

<tr>
<td>2</td>
<td>LP02</td>
<td>Laptop Asus</td>
<td>Laptop</td>
<td>Asus</td>
<td>Ruang TU</td>
<td>Rusak</td>
<td>
<a href="edit.php" class="btn-edit">Edit</a>
<button class="btn-hapus">Hapus</button>
</td>
</tr>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>
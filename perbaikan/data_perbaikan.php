<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");

include("../config/koneksi.php");

$query = mysqli_query($koneksi,"SELECT * FROM inventaris");

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Perbaikan</title>

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
            <h1>Data Perbaikan</h1>
        </div>

        <div class="table-container">

            <a href="tambah_perbaikan.php" class="btn-tambah">+ Tambah Perbaikan</a>

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Tanggal</th>
                        <th>Kerusakan</th>
                        <th>Tindakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>


                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
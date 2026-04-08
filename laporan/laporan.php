<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

$query = mysqli_query($koneksi,"SELECT * FROM inventaris");


?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Inventaris</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Laporan</h2>

        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>

            <!-- ADMIN & TEKNISI -->
            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <?php } ?>

            <!-- KHUSUS ADMIN -->
            <?php if($isAdmin){ ?>
                <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
                <li><a href="laporan.php">Laporan</a></li>
                <li><a href="../user/data_user.php">Manajemen User</a></li>
            <?php } ?>

            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Laporan Inventaris</h1>
        </div>

        <div class="table-container">

            <h3>Filter Laporan</h3>

            <div class="filter-form">

                <div class="filter-group">
                    <label>Tanggal Awal</label>
                    <input type="date">
                </div>

                <div class="filter-group">
                    <label>Tanggal Akhir</label>
                    <input type="date">
                </div>

                <div class="filter-group">
                    <label>Jenis Barang</label>
                    <select>
                        <option>Semua</option>
                        <option>PC</option>
                        <option>Laptop</option>
                    </select>
                </div>

                <button class="btn-filter">Tampilkan</button>
                <a href="cetak_pdf.php" target="_blank" class="btn-cetak">Cetak PDF</a>

            </div>

            <hr style="margin:20px 0">

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
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;
                while($row = mysqli_fetch_array($query)){
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['kode_barang'] ?></td>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['merk'] ?></td>
                    <td><?= $row['lokasi'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
                <?php } ?>
                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
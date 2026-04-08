<?php

include("../config/auth.php");
include("../config/koneksi.php");

/* hitung data */
$total_pc = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM inventaris WHERE jenis='PC'
"))['total'];

$total_laptop = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM inventaris WHERE jenis='Laptop'
"))['total'];

$total_rusak = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM inventaris WHERE status='rusak'
"))['total'];

$total_inventaris = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) as total FROM inventaris
"))['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Inventaris</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Dashboard</h2>

        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>

            <!-- ADMIN & TEKNISI -->
            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <?php } ?>

            <!-- KHUSUS ADMIN -->
            <?php if($isAdmin){ ?>
                <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
                <li><a href="../laporan/laporan.php">Laporan</a></li>
                <li><a href="../user/data_user.php">Manajemen User</a></li>
            <?php } ?>

            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Dashboard</h1>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total PC</h3>
                <p><?php echo $total_pc; ?></p>
            </div>

            <div class="card">
                <h3>Total Laptop</h3>
                <p><?php echo $total_laptop; ?></p>
            </div>

            <div class="card">
                <h3>Perangkat Rusak</h3>
                <p><?php echo $total_rusak; ?></p>
            </div>

            <div class="card">
                <h3>Total Inventaris</h3>
                <p><?php echo $total_inventaris; ?></p>
            </div>

        </div>

    </div>

</div>

</body>
</html>
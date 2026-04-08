<?php

include("../config/auth.php");
include("../config/koneksi.php");

/* helper function */
function getTotal($koneksi, $query){
    $result = mysqli_query($koneksi, $query);
    if(!$result){
        return 0;
    }
    $data = mysqli_fetch_assoc($result);
    return $data['total'] ?? 0;
}

/* ================= ADMIN ================= */
if($isAdmin){

    $total_pc = getTotal($koneksi, "SELECT COUNT(*) as total FROM inventaris WHERE jenis='PC'");
    $total_laptop = getTotal($koneksi, "SELECT COUNT(*) as total FROM inventaris WHERE jenis='Laptop'");
    $total_rusak = getTotal($koneksi, "SELECT COUNT(*) as total FROM inventaris WHERE kondisi='rusak'");
    $total_inventaris = getTotal($koneksi, "SELECT COUNT(*) as total FROM inventaris");

    $total_peminjaman = getTotal($koneksi, "SELECT COUNT(*) as total FROM peminjaman");
    $sedang_dipinjam = getTotal($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'");

    $total_user = getTotal($koneksi, "SELECT COUNT(*) as total FROM user");
}

/* ================= TEKNISI ================= */
if($isTeknisi){

    $total_rusak = getTotal($koneksi, "SELECT COUNT(*) as total FROM inventaris WHERE kondisi='rusak'");
    $total_perbaikan = getTotal($koneksi, "SELECT COUNT(*) as total FROM perbaikan");
}

/* ================= USER ================= */
if($isUser){

    $user_id = $_SESSION['id_user'];

    $peminjaman_saya = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM peminjaman WHERE user_id='$user_id'
    ");

    $dipinjam_saya = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM peminjaman 
        WHERE user_id='$user_id' AND status='dipinjam'
    ");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Inventaris</title>

<!-- FORCE RELOAD CSS -->
<link rel="stylesheet" href="../css/dashboard.css?v=2">

</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Dashboard</h2>

        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>

            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <?php } ?>

            <?php if($isAdmin){ ?>
                <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
                <li><a href="../laporan/laporan.php">Laporan</a></li>
                <li><a href="../user/data_user.php">Manajemen User</a></li>
            <?php } ?>

            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main -->
    <div class="main">

        <div class="topbar">
            <h1>Dashboard</h1>
            <p>Halo, <?= $_SESSION['username']; ?> (<?= $_SESSION['role']; ?>)</p>
        </div>

        <div class="cards">

        <!-- ADMIN -->
        <?php if($isAdmin){ ?>

            <div class="card card-primary">
                <h3>Total Inventaris</h3>
                <p><?= $total_inventaris; ?></p>
            </div>

            <div class="card card-success">
                <h3>Total PC</h3>
                <p><?= $total_pc; ?></p>
            </div>

            <div class="card card-success">
                <h3>Total Laptop</h3>
                <p><?= $total_laptop; ?></p>
            </div>

            <div class="card card-danger">
                <h3>Perangkat Rusak</h3>
                <p><?= $total_rusak; ?></p>
            </div>

            <div class="card card-primary">
                <h3>Total Peminjaman</h3>
                <p><?= $total_peminjaman; ?></p>
            </div>

            <div class="card card-success">
                <h3>Sedang Dipinjam</h3>
                <p><?= $sedang_dipinjam; ?></p>
            </div>

            <div class="card card-primary">
                <h3>Total User</h3>
                <p><?= $total_user; ?></p>
            </div>

        <?php } ?>

        <!-- TEKNISI -->
        <?php if($isTeknisi){ ?>

            <div class="card card-danger">
                <h3>Perangkat Rusak</h3>
                <p><?= $total_rusak; ?></p>
            </div>

            <div class="card card-primary">
                <h3>Total Perbaikan</h3>
                <p><?= $total_perbaikan; ?></p>
            </div>

        <?php } ?>

        <!-- USER -->
        <?php if($isUser){ ?>

            <div class="card card-primary">
                <h3>Peminjaman Saya</h3>
                <p><?= $peminjaman_saya; ?></p>
            </div>

            <div class="card card-success">
                <h3>Sedang Dipinjam</h3>
                <p><?= $dipinjam_saya; ?></p>
            </div>

        <?php } ?>

        </div>

    </div>

</div>

</body>
</html>
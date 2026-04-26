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

    $total_pc = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM inventaris 
        WHERE jenis='PC' AND deleted_at IS NULL
    ");

    $total_laptop = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM inventaris 
        WHERE jenis='Laptop' AND deleted_at IS NULL
    ");

    $total_rusak = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM inventaris 
        WHERE status='rusak' AND deleted_at IS NULL
    ");

    $total_inventaris = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM inventaris 
        WHERE deleted_at IS NULL
    ");

    $total_peminjaman = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM peminjaman 
        WHERE deleted_at IS NULL
    ");

    $sedang_dipinjam = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM peminjaman 
        WHERE status='dipinjam' AND deleted_at IS NULL
    ");

    $total_user = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM pengguna 
        WHERE deleted_at IS NULL
    ");
}

/* ================= TEKNISI ================= */
if($isTeknisi){

    $total_rusak = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM inventaris 
        WHERE status='rusak' AND deleted_at IS NULL
    ");

    $total_perbaikan = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM perbaikan 
        WHERE deleted_at IS NULL
    ");
}

/* ================= USER ================= */
if($isUser){

    $user_id = $_SESSION['id_pengguna'];

    $peminjaman_saya = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM peminjaman 
        WHERE id_pengguna='$user_id' 
        AND deleted_at IS NULL
    ");

    $dipinjam_saya = getTotal($koneksi, "
        SELECT COUNT(*) as total FROM peminjaman 
        WHERE id_pengguna='$user_id' 
        AND status='dipinjam' 
        AND deleted_at IS NULL
    ");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Inventaris</title>

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
            <p>
                Halo, <?= htmlspecialchars($_SESSION['username']); ?> 
                (<?= htmlspecialchars($_SESSION['role']); ?>)
            </p>
        </div>

        <div class="cards">

            <?php if($isAdmin){ ?>

    <div class="card card-primary">
        <div class="card-icon">📦</div>
        <div class="card-info">
            <h3>Total Inventaris</h3>
            <p><?= $total_inventaris; ?></p>
        </div>
    </div>

    <div class="card card-success">
        <div class="card-icon">🖥️</div>
        <div class="card-info">
            <h3>Total PC</h3>
            <p><?= $total_pc; ?></p>
        </div>
    </div>

    <div class="card card-success">
        <div class="card-icon">💻</div>
        <div class="card-info">
            <h3>Total Laptop</h3>
            <p><?= $total_laptop; ?></p>
        </div>
    </div>

    <div class="card card-danger">
        <div class="card-icon">🔧</div>
        <div class="card-info">
            <h3>Perangkat Rusak</h3>
            <p><?= $total_rusak; ?></p>
        </div>
    </div>

    <div class="card card-warning">
        <div class="card-icon">📋</div>
        <div class="card-info">
            <h3>Total Peminjaman</h3>
            <p><?= $total_peminjaman; ?></p>
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-icon">🔄</div>
        <div class="card-info">
            <h3>Sedang Dipinjam</h3>
            <p><?= $sedang_dipinjam; ?></p>
        </div>
    </div>

    <div class="card card-purple">
        <div class="card-icon">👥</div>
        <div class="card-info">
            <h3>Total User</h3>
            <p><?= $total_user; ?></p>
        </div>
    </div>

            <?php } ?>

            <?php if($isTeknisi){ ?>

    <div class="card card-danger">
        <div class="card-icon">🔧</div>
        <div class="card-info">
            <h3>Perangkat Rusak</h3>
            <p><?= $total_rusak; ?></p>
        </div>
    </div>

    <div class="card card-warning">
        <div class="card-icon">🛠️</div>
        <div class="card-info">
            <h3>Total Perbaikan</h3>
            <p><?= $total_perbaikan; ?></p>
        </div>
    </div>

            <?php } ?>

            <?php if($isUser){ ?>

    <div class="card card-primary">
        <div class="card-icon">📋</div>
        <div class="card-info">
            <h3>Peminjaman Saya</h3>
            <p><?= $peminjaman_saya; ?></p>
        </div>
    </div>

    <div class="card card-success">
        <div class="card-icon">🔄</div>
        <div class="card-info">
            <h3>Sedang Dipinjam</h3>
            <p><?= $dipinjam_saya; ?></p>
        </div>
    </div>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>
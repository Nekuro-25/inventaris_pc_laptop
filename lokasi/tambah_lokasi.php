<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

if(isset($_POST['simpan'])){

    $nama_lokasi = mysqli_real_escape_string($koneksi, $_POST['nama_lokasi']);

    $query = mysqli_query($koneksi,"
    INSERT INTO lokasi (nama_lokasi) 
    VALUES ('$nama_lokasi')
    ");

    if($query){
        header("Location: lokasi.php");
        exit;
    }else{
        echo "Data gagal disimpan";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Lokasi</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Tambah Lokasi</h2>

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
                <li><a href="lokasi.php">Data Lokasi</a></li>
                <li><a href="../laporan/laporan.php">Laporan</a></li>
                <li><a href="../user/data_user.php">Manajemen User</a></li>
            <?php } ?>

            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>


    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Tambah Lokasi</h1>
        </div>

        <div class="form-container">

            <form method="POST">

                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" placeholder="Masukkan nama lokasi" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
                    <a href="lokasi.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
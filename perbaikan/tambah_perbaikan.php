<?php

include("../config/auth.php");
include("../config/koneksi.php");

if(!$isAdmin || $isTeknisi){
    header("Location: data_perbaikan.php");
    exit;
}

/* ambil data inventaris untuk dropdown */
$data_barang = mysqli_query($koneksi,"SELECT id_barang, kode_barang, nama_barang FROM inventaris");

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Perbaikan</title>

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
            <li><a href="../peminjaman/index.php">Peminjaman</a></li>
            <li><a href="data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Tambah Data Perbaikan</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_tambah.php">

                <div class="form-group">
                    <label>Pilih Barang</label>

                    <select name="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php
                            while($barang = mysqli_fetch_assoc($data_barang)){
                        ?>
                        <option value="<?php echo $barang['id_barang']; ?>">
                            <?php echo $barang['kode_barang']; ?> - <?php echo $barang['nama_barang']; ?>
                        </option>
                        <?php } ?>

                    </select>

                </div>

                <div class="form-group">
                    <label>Tanggal Perbaikan</label>
                    <input type="date" name="tanggal" required>
                </div>

                <div class="form-group">
                    <label>Kerusakan</label>
                    <input type="text" name="kerusakan" placeholder="Masukkan kerusakan" required>
                </div>

                <div class="form-group">
                    <label>Tindakan</label>
                    <input type="text" name="tindakan" placeholder="Masukkan tindakan perbaikan">
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit" name="simpan">Simpan</button>
                    <a href="data_perbaikan.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
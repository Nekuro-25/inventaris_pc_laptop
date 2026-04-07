<?php

include("../config/auth.php");
include("../config/koneksi.php");

/* ambil data peminjaman berdasarkan id */
$id = $_GET['id'];

$data = mysqli_query($koneksi,"
SELECT peminjaman.*, inventaris.kode_barang, inventaris.nama_barang 
FROM peminjaman
JOIN inventaris ON peminjaman.id_barang = inventaris.id_barang
WHERE peminjaman.id='$id'
");

$row = mysqli_fetch_assoc($data);

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengembalian Barang</title>

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
            <li><a href="index.php">Peminjaman</a></li>
            <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Pengembalian Barang</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_kembali.php">

                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">

                <div class="form-group">
                    <label>Barang</label>
                    <input type="text" 
                        value="<?php echo $row['kode_barang'].' - '.$row['nama_barang']; ?>" 
                        readonly>
                </div>

                <div class="form-group">
                    <label>Nama Peminjam</label>
                    <input type="text" value="<?php echo $row['nama_peminjam']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="text" value="<?php echo $row['tanggal_pinjam']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" required>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Kembalikan</button>
                    <a href="index.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
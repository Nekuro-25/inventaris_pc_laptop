<?php

include("../config/auth.php");
include("../config/koneksi.php");

if(!$isAdmin && !$isTeknisi){
    header("Location: data_perbaikan.php");
    exit;
}

/* ambil id dari URL */
$id = $_GET['id'];

/* ambil data perbaikan + inventaris */
$query = mysqli_query($koneksi,"
SELECT perbaikan.*, inventaris.kode_barang, inventaris.nama_barang
FROM perbaikan
JOIN inventaris ON perbaikan.id_barang = inventaris.id_barang
WHERE id_perbaikan='$id'
");

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Perbaikan</title>

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
            <h1>Edit Data Perbaikan</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_edit.php">

                <input type="hidden" name="id_perbaikan" value="<?php echo $data['id_perbaikan']; ?>">

                <!-- BARANG (READONLY) -->
                <div class="form-group">
                    <label>Barang</label>

                    <input type="text" 
                           value="<?php echo $data['kode_barang'].' - '.$data['nama_barang']; ?>" 
                           readonly>

                    <!-- tetap dikirim ke backend -->
                    <input type="hidden" name="id_barang" value="<?php echo $data['id_barang']; ?>">
                </div>

                <div class="form-group">
                    <label>Tanggal Perbaikan</label>
                    <input type="date" name="tanggal" value="<?php echo $data['tanggal']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kerusakan</label>
                    <input type="text" name="kerusakan" value="<?php echo $data['kerusakan']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Tindakan</label>
                    <input type="text" name="tindakan" value="<?php echo $data['tindakan']; ?>">
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Update</button>                            
                    <a href="data_perbaikan.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
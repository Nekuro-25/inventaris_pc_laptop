<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$query = mysqli_query($koneksi,"SELECT * FROM lokasi");

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Lokasi</title>

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
            <li><a href="lokasi.php">Data Lokasi</a></li>
            <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>


    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Data Lokasi</h1>
        </div>

        <div class="table-container">

            <a href="tambah_lokasi.php" class="btn-tambah">+ Tambah Lokasi</a>

            <table>

                <tr>
                    <th>No</th>
                    <th>Nama Lokasi</th>
                    <th>Aksi</th>
                </tr>

                <?php
                $no = 1;
                while($row = mysqli_fetch_assoc($query)){
                ?>

                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_lokasi']; ?></td>
                    <td>

                        <a href="edit_lokasi.php?id_lokasi=<?php echo $row['id_lokasi']; ?>" class="btn-edit">Edit</a>

                        <a href="hapus_lokasi.php?id_lokasi=<?php echo $row['id_lokasi']; ?>" class="btn-hapus">Hapus</a>

                    </td>
                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>

</body>
</html>
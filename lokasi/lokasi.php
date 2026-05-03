<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

$query = mysqli_query($koneksi,"
SELECT * FROM lokasi 
WHERE deleted_at IS NULL
ORDER BY id_lokasi DESC
");

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
        <h2>Lokasi</h2>

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
            <h1>Data Lokasi</h1>
        </div>

        <!-- ✅ FIX: Blok Notifikasi Error yang Diperbarui -->
        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert alert-success">
                ✅ <?= $_GET['pesan']==='berhasil' ? 'Lokasi berhasil disimpan.' : ($_GET['pesan']==='update_berhasil' ? 'Lokasi berhasil diupdate.' : ($_GET['pesan']==='hapus_berhasil' ? 'Lokasi berhasil dihapus.' : htmlspecialchars($_GET['pesan']))) ?>
            </div>
        <?php elseif(isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                ⚠️ 
                <?php 
                if ($_GET['error'] === 'lokasi_sedang_terpakai') {
                    echo 'Lokasi gagal dihapus karena masih ada barang yang menggunakan lokasi ini. Pindahkan barang terlebih dahulu.';
                } else {
                    echo 'Terjadi kesalahan: ' . htmlspecialchars($_GET['error']);
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="table-container">

            <div class="table-header">
                <span class="table-header-title">Daftar Lokasi</span>
                <a href="tambah_lokasi.php" class="btn-tambah">+ Tambah Lokasi</a>
            </div>

            <div class="table-wrapper">
            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    
                    <?php
                    $no = 1;
                    // FIX: cek apakah ada data lokasi 
                    if(mysqli_num_rows($query) > 0) {
                        while($row = mysqli_fetch_assoc($query)){
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>        
                        <td><?php echo htmlspecialchars($row['nama_lokasi']); ?></td>
                        <td>
                            <a href="edit_lokasi.php?id_lokasi=<?php echo $row['id_lokasi']; ?>" class="btn-edit">Edit</a>
                            <a href="hapus_lokasi.php?id_lokasi=<?php echo $row['id_lokasi']; ?>"class="btn-hapus"onclick="return konfirmasiHapus()">Hapus</a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                    ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">Belum ada data lokasi.</td>
                    </tr>
                    <?php } ?>

                </tbody>

            </table>
            </div><!-- end table-wrapper -->
        </div><!-- end table-container -->

    </div>

</div>


<script src="../js/konfirmasi.js"></script>

</body>
</html>
<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

$query = mysqli_query($koneksi,"
SELECT peminjaman.*, inventaris.kode_barang, inventaris.nama_barang 
FROM peminjaman
JOIN inventaris ON peminjaman.id_barang = inventaris.id_barang
WHERE peminjaman.deleted_at IS NULL
AND inventaris.deleted_at IS NULL
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Peminjaman</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Peminjaman</h2>

        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>

            <!-- ADMIN & TEKNISI -->
            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="index.php">Peminjaman</a></li>
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
            <h1>Data Peminjaman</h1>
        </div>
    
        <?php if(isset($_GET['pesan'])): ?>
        <div class="alert alert-success">✅ <?= $_GET['pesan']==='berhasil' ? 'Peminjaman berhasil dicatat.' : ($_GET['pesan']==='kembali_berhasil' ? 'Barang berhasil dikembalikan.' : ($_GET['pesan']==='hapus_berhasil' ? 'Data berhasil dihapus.' : htmlspecialchars($_GET['pesan']))) ?></div>
        <?php elseif(isset($_GET['error'])): ?>
        <div class="alert alert-danger">⚠️ Terjadi kesalahan. Silakan coba lagi.</div>
        <?php endif; ?>
    
        <div class="table-container">

            <div class="table-header">
                <span class="table-header-title">Daftar Peminjaman</span>
                <a href="tambah.php" class="btn-tambah">+ Tambah Peminjaman</a>
            </div>

            <div class="table-wrapper">
            <table>
    
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
    
                <tbody>

                    <?php
                    $no = 1;

                    while($row = mysqli_fetch_assoc($query)){
                    ?>

                    <tr>

                        <td><?php echo $no++; ?></td>
                        <td><?= htmlspecialchars($row['kode_barang']); ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?= htmlspecialchars($row['nama_peminjam']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_kembali']); ?></td>
                        <td>
                            <?php
                            $st = $row['status'];
                            $label = ['dipinjam'=>'Dipinjam','kembali'=>'Dikembalikan'];
                            echo '<span class="badge badge-'.$st.'">'.htmlspecialchars($label[$st] ?? $st).'</span>';
                            ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 'dipinjam'){ ?>
                                <a href="kembali.php?id=<?php echo $row['id']; ?>" class="btn-edit">Kembalikan</a>
                            <?php } ?>
                            <?php if($row['status'] == 'kembali'){ ?>
                                <a href="pr_hapus.php?id=<?php echo $row['id']; ?>" class="btn-hapus"onclick="return konfirmasiHapus()">Hapus</a>
                            <?php } ?>
                        </td>

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
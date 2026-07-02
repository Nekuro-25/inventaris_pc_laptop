<?php

include("../config/auth.php");
include("../config/koneksi.php");

/* Generate token CSRF untuk form hapus di halaman ini */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$query = mysqli_query($koneksi, "
    SELECT inventaris.*, lokasi.nama_lokasi
    FROM inventaris
    JOIN lokasi ON inventaris.id_lokasi = lokasi.id_lokasi
    WHERE inventaris.deleted_at IS NULL
    AND lokasi.deleted_at IS NULL
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Inventaris</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Inventaris</h2>

        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="data.php">Data Inventaris</a></li>

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

    <!-- Main Content -->
    <div class="main">
    
        <div class="topbar">
            <h1>Data Inventaris</h1>
        </div>
    
        <?php if(isset($_GET['pesan'])): ?>
        <div class="alert alert-success">✅ <?= $_GET['pesan']==='berhasil' ? 'Data berhasil disimpan.' : ($_GET['pesan']==='update_berhasil' ? 'Data berhasil diupdate.' : ($_GET['pesan']==='hapus_berhasil' ? 'Data berhasil dihapus.' : htmlspecialchars($_GET['pesan']))) ?></div>
        <?php elseif(isset($_GET['error'])): ?>
        <div class="alert alert-danger">⚠️ Terjadi kesalahan. Silakan coba lagi.</div>
        <?php endif; ?>

        <div class="table-container">

            <div class="table-header">
                <span class="table-header-title">Daftar Inventaris</span>
                <?php if($isAdmin || $isTeknisi){ ?>
                    <a href="tambah.php" class="btn-tambah">+ Tambah Inventaris</a>
                <?php } ?>
            </div>

            <div class="table-wrapper">
            <table>
    
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Merk</th>
                        <th>Lokasi</th>
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
                        <!-- ✅ FIX XSS: semua output data dari DB dibungkus htmlspecialchars() -->
                        <td><?php echo htmlspecialchars($row['kode_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis']); ?></td>
                        <td><?php echo htmlspecialchars($row['merk']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_lokasi']); ?></td>
                        <td>
                            <?php
                            $st = $row['status'];
                            $label = ['tersedia'=>'Tersedia','dipinjam'=>'Dipinjam','rusak'=>'Rusak'];
                            echo '<span class="badge badge-'.$st.'">'.htmlspecialchars($label[$st] ?? $st).'</span>';
                            ?>
                        </td>
                        <td>

                            <?php if($isAdmin || $isTeknisi){ ?>
                                <!-- ✅ FIX XSS: id di URL juga diproteksi -->
                                <a href="edit.php?id=<?php echo (int)$row['id_barang']; ?>" class="btn-edit">Edit</a>
                                <form method="POST" action="hapus.php" style="display:inline">
                                    <input type="hidden" name="id" value="<?php echo (int)$row['id_barang']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                    <button type="submit" class="btn-hapus" onclick="return konfirmasiHapus()">Hapus</button>
                                </form>
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

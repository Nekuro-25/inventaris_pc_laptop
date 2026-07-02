<?php

include("../config/auth.php");
include("../config/koneksi.php");

adminOrTeknisi();
blockUser();

/* Generate token CSRF untuk form hapus di halaman ini */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

/* ✅ Query aman — kolom deleted_at sekarang sudah ada di tabel perbaikan */
$query = mysqli_query($koneksi, "
    SELECT perbaikan.*, inventaris.kode_barang, inventaris.nama_barang
    FROM perbaikan
    JOIN inventaris ON perbaikan.id_barang = inventaris.id_barang
    WHERE perbaikan.deleted_at IS NULL
    AND inventaris.deleted_at IS NULL
");

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Perbaikan</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Perbaikan</h2>

        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>

            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="data_perbaikan.php">Perbaikan</a></li>
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
            <h1>Data Perbaikan</h1>
        </div>

        <?php if(isset($_GET['pesan'])): ?>
        <div class="alert alert-success">✅ <?= $_GET['pesan']==='berhasil' ? 'Data perbaikan berhasil disimpan.' : ($_GET['pesan']==='update_berhasil' ? 'Data berhasil diupdate.' : ($_GET['pesan']==='hapus_berhasil' ? 'Data berhasil dihapus.' : htmlspecialchars($_GET['pesan']))) ?></div>
        <?php elseif(isset($_GET['error'])): ?>
        <div class="alert alert-danger">⚠️ Terjadi kesalahan. Silakan coba lagi.</div>
        <?php endif; ?>

        <div class="table-container">

            <div class="table-header">
                <span class="table-header-title">Daftar Perbaikan</span>
                <?php if($isAdmin || $isTeknisi){ ?>
                    <a href="tambah_perbaikan.php" class="btn-tambah">+ Tambah Perbaikan</a>
                <?php } ?>
            </div>

            <div class="table-wrapper">
            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Tanggal</th>
                        <th>Kerusakan</th>
                        <th>Tindakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $no = 1;
                    if (mysqli_num_rows($query) > 0) {
                        while($row = mysqli_fetch_assoc($query)){
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <!-- ✅ XSS: semua output pakai htmlspecialchars -->
                        <td><?= htmlspecialchars($row['kode_barang']); ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal']); ?></td>
                        <td><?= htmlspecialchars($row['kerusakan']); ?></td>
                        <td><?= htmlspecialchars($row['tindakan'] ?? '-'); ?></td>
                        <td>
                            <?php if($isAdmin || $isTeknisi){ ?>
                                <a href="edit_perbaikan.php?id=<?= (int)$row['id_perbaikan']; ?>" class="btn-edit">Edit</a>
                                <form method="POST" action="hapus_perbaikan.php" style="display:inline">
                                    <input type="hidden" name="id" value="<?= (int)$row['id_perbaikan']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token); ?>">
                                    <button type="submit" class="btn-hapus" onclick="return konfirmasiHapus('Yakin ingin menghapus data ini?')">
                                       Hapus
                                    </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>

                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>Data tidak tersedia</td></tr>";
                    }
                    ?>

                </tbody>

            </table>
            </div><!-- end table-wrapper -->
        </div><!-- end table-container -->

    </div>

</div>

<script src="../js/konfirmasi.js"></script>

</body>
</html>

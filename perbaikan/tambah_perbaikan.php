<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

/* Ambil barang dengan status rusak untuk dropdown */
$data_barang = mysqli_query($koneksi, "
    SELECT id_barang, kode_barang, nama_barang 
    FROM inventaris 
    WHERE status = 'rusak' AND deleted_at IS NULL
    ORDER BY nama_barang ASC
");

if (!$data_barang) {
    die("Query error.");
}

/* ✅ FIX #12 CSRF: Generate token */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
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

    <div class="sidebar">
        <h2>Tambah Perbaikan</h2>
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

    <div class="main">
        <div class="topbar">
            <h1>Tambah Data Perbaikan</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_tambah.php">

                <!-- ✅ FIX #12 CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label>Pilih Barang</label>
                    <select name="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php if (mysqli_num_rows($data_barang) > 0){ ?>
                            <?php while($barang = mysqli_fetch_assoc($data_barang)){ ?>
                                <option value="<?= (int)$barang['id_barang']; ?>">
                                    <?= htmlspecialchars($barang['kode_barang']); ?> -
                                    <?= htmlspecialchars($barang['nama_barang']); ?>
                                </option>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="">Tidak ada barang rusak</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Perbaikan</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group">
                    <label>Kerusakan</label>
                    <!-- ✅ FIX #15: Validasi required di sisi client -->
                    <input type="text" name="kerusakan" placeholder="Masukkan kerusakan" required maxlength="255">
                </div>

                <div class="form-group">
                    <label>Tindakan</label>
                    <input type="text" name="tindakan" placeholder="Masukkan tindakan perbaikan" maxlength="255">
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Simpan</button>
                    <a href="data_perbaikan.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

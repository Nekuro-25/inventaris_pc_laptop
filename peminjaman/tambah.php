<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

/* ✅ FIX SQL Injection: query pakai enum yang benar ('tersedia') */
$data_barang = mysqli_query($koneksi, "
    SELECT id_barang, kode_barang, nama_barang FROM inventaris 
    WHERE status = 'tersedia' AND deleted_at IS NULL
    ORDER BY nama_barang ASC
");

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
<title>Tambah Peminjaman</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Tambah Peminjaman</h2>
        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>
            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="index.php">Peminjaman</a></li>
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

    <div class="main">
        <div class="topbar">
            <h1>Tambah Peminjaman</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_tambah.php">

                <!-- ✅ FIX #12 CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label>Barang</label>
                    <select name="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php while($barang = mysqli_fetch_assoc($data_barang)){ ?>
                        <option value="<?php echo (int)$barang['id_barang']; ?>">
                            <?php echo htmlspecialchars($barang['kode_barang'] . " - " . $barang['nama_barang']); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Peminjam</label>
                    <!-- ✅ FIX #15: Validasi maxlength -->
                    <input type="text" name="nama_peminjam" required maxlength="100">
                </div>

                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" required>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Simpan</button>
                    <a href="index.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

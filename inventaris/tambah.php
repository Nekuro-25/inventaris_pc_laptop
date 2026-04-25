<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

/* Ambil data lokasi */
$data_lokasi = mysqli_query($koneksi, "SELECT * FROM lokasi WHERE deleted_at IS NULL");

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
<title>Tambah Inventaris</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Tambah Inventaris</h2>
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

    <div class="main">
        <div class="topbar">
            <h1>Tambah Inventaris</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_tambah.php">

                <!-- ✅ FIX #12 CSRF: Token tersembunyi di form -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="kode_barang" required maxlength="50">
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <!-- ✅ FIX #15: Validasi maxlength sesuai kolom DB (100 char) -->
                    <input type="text" name="nama_barang" required maxlength="100">
                </div>

                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis">
                        <option value="PC">PC</option>
                        <option value="Laptop">Laptop</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" maxlength="100">
                </div>

                <div class="form-group">
                    <label>Processor</label>
                    <input type="text" name="processor" maxlength="100">
                </div>

                <div class="form-group">
                    <label>RAM</label>
                    <!-- ✅ FIX #15: Format diperjelas -->
                    <input type="text" name="ram" maxlength="50" placeholder="contoh: 8 GB">
                </div>

                <div class="form-group">
                    <label>Storage</label>
                    <input type="text" name="storage" maxlength="50" placeholder="contoh: 256 GB SSD">
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="id_lokasi" required>
                        <option value="">-- Pilih Lokasi --</option>
                        <?php while($lokasi = mysqli_fetch_assoc($data_lokasi)){ ?>
                        <option value="<?php echo (int)$lokasi['id_lokasi']; ?>">
                            <?php echo htmlspecialchars($lokasi['nama_lokasi']); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Simpan</button>
                    <a href="data.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

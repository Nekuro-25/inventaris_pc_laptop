<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

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
<title>Tambah Lokasi</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Tambah Lokasi</h2>
        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>
            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <?php } ?>
            <?php if($isAdmin){ ?>
                <li><a href="lokasi.php">Data Lokasi</a></li>
                <li><a href="../laporan/laporan.php">Laporan</a></li>
                <li><a href="../user/data_user.php">Manajemen User</a></li>
            <?php } ?>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Tambah Lokasi</h1>
        </div>

        <div class="form-container">

            <!-- ✅ FIX: Form POST ke file terpisah pr_tambah_lokasi.php -->
            <form method="POST" action="pr_tambah_lokasi.php">

                <!-- ✅ FIX #12 CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" placeholder="Masukkan nama lokasi" required maxlength="100">
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-simpan">Simpan</button>
                    <a href="lokasi.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

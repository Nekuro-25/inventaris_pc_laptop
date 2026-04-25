<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

/* Validasi ID */
if (!isset($_GET['id'])) {
    header("Location: data_perbaikan.php");
    exit;
}

/* ✅ FIX SQL Injection: sudah ada dari sesi sebelumnya */
$id = (int) $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT perbaikan.*, inventaris.kode_barang, inventaris.nama_barang
    FROM perbaikan
    JOIN inventaris ON perbaikan.id_barang = inventaris.id_barang
    WHERE perbaikan.id_perbaikan = $id
    AND perbaikan.deleted_at IS NULL
    AND inventaris.deleted_at IS NULL
");

if (!$query) {
    die("Query error.");
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: data_perbaikan.php?error=tidak_ditemukan");
    exit;
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
<title>Edit Perbaikan</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Edit Perbaikan</h2>
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
            <h1>Edit Data Perbaikan</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_edit.php">

                <!-- ✅ FIX #12 CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="id_perbaikan" value="<?= (int)$data['id_perbaikan']; ?>">

                <div class="form-group">
                    <label>Barang</label>
                    <input type="text" value="<?= htmlspecialchars($data['kode_barang'] . ' - ' . $data['nama_barang']); ?>" readonly>
                    <input type="hidden" name="id_barang" value="<?= (int)$data['id_barang']; ?>">
                </div>

                <div class="form-group">
                    <label>Tanggal Perbaikan</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Kerusakan</label>
                    <input type="text" name="kerusakan" value="<?= htmlspecialchars($data['kerusakan']); ?>" required maxlength="255">
                </div>

                <div class="form-group">
                    <label>Tindakan</label>
                    <input type="text" name="tindakan" value="<?= htmlspecialchars($data['tindakan'] ?? ''); ?>" maxlength="255">
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

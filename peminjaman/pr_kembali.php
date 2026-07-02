<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

/* Validasi ID */
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

/* ✅ FIX SQL Injection: cast ke integer */
$id = (int) $_GET['id'];

/* ✅ FIX SQL Injection: Prepared Statement */
$stmt = mysqli_prepare($koneksi, "
    SELECT peminjaman.*, inventaris.kode_barang, inventaris.nama_barang
    FROM peminjaman
    JOIN inventaris ON peminjaman.id_barang = inventaris.id_barang
    WHERE peminjaman.id = ? AND peminjaman.deleted_at IS NULL
    LIMIT 1
");
if (!$stmt) {
    header("Location: index.php?error=gagal_hapus");
    exit;
}
mysqli_stmt_bind_param($stmt, "i", $id);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: index.php?error=gagal_hapus");
    exit;
}
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    header("Location: index.php?error=data_tidak_ditemukan");
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
<title>Pengembalian Barang</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Pengembalian</h2>
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
            <h1>Pengembalian Barang</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_kembali.php">

                <!-- ✅ FIX #12 CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                <input type="hidden" name="id_barang" value="<?php echo (int)$row['id_barang']; ?>">

                <div class="form-group">
                    <label>Barang</label>
                    <!-- ✅ FIX XSS: value di-escape -->
                    <input type="text" value="<?php echo htmlspecialchars($row['kode_barang'] . ' - ' . $row['nama_barang']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Nama Peminjam</label>
                    <input type="text" value="<?php echo htmlspecialchars($row['nama_peminjam']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="text" value="<?php echo htmlspecialchars($row['tanggal_pinjam']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" required>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Kembalikan</button>
                    <a href="index.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* Validasi ID dari URL */
if (!isset($_GET['id_lokasi'])) {
    header("Location: lokasi.php");
    exit;
}

/* ✅ FIX SQL Injection: cast ke integer */
$id = (int) $_GET['id_lokasi'];

/* ✅ FIX SQL Injection: Prepared Statement untuk ambil data */
$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM lokasi WHERE id_lokasi = ? AND deleted_at IS NULL LIMIT 1
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: lokasi.php?error=data_tidak_ditemukan");
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
<title>Edit Lokasi</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Edit Lokasi</h2>
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
            <h1>Edit Lokasi</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_edit_lokasi.php">

                <!-- ✅ FIX #12 CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="id_lokasi" value="<?php echo (int)$data['id_lokasi']; ?>">

                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <!-- ✅ FIX XSS: value di-escape -->
                    <input type="text" name="nama_lokasi"
                        value="<?php echo htmlspecialchars($data['nama_lokasi']); ?>"
                        required maxlength="100">
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-simpan">Update</button>
                    <a href="lokasi.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

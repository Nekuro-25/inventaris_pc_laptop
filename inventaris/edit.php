<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

/* Validasi ID dari URL */
if (!isset($_GET['id'])) {
    header("Location: data.php");
    exit;
}

/* ✅ FIX SQL Injection: cast ke integer */
$id = (int) $_GET['id'];

/* ✅ FIX SQL Injection: Prepared Statement */
$stmtBarang = mysqli_prepare($koneksi, "
    SELECT * FROM inventaris 
    WHERE id_barang = ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtBarang, "i", $id);
mysqli_stmt_execute($stmtBarang);
$result = mysqli_stmt_get_result($stmtBarang);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmtBarang);

if (!$data) {
    header("Location: data.php?error=data_tidak_ditemukan");
    exit;
}

/* Ambil data lokasi */
$data_lokasi = mysqli_query($koneksi, "SELECT * FROM lokasi WHERE deleted_at IS NULL");

/* ✅ FIX #12 CSRF: Generate token dan simpan di session */
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
<title>Edit Inventaris</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

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

    <div class="main">
        <div class="topbar">
            <h1>Edit Inventaris</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_edit.php">

                <!-- ✅ FIX #12 CSRF: Token tersembunyi di form -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="id_barang" value="<?php echo (int)$data['id_barang']; ?>">

                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="kode_barang" value="<?php echo htmlspecialchars($data['kode_barang']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Jenis Barang</label>
                    <select name="jenis">
                        <option value="PC" <?php if($data['jenis'] == "PC") echo "selected"; ?>>PC</option>
                        <option value="Laptop" <?php if($data['jenis'] == "Laptop") echo "selected"; ?>>Laptop</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" value="<?php echo htmlspecialchars($data['merk']); ?>">
                </div>

                <div class="form-group">
                    <label>Processor</label>
                    <input type="text" name="processor" value="<?php echo htmlspecialchars($data['processor']); ?>">
                </div>

                <div class="form-group">
                    <label>RAM</label>
                    <input type="text" name="ram" value="<?php echo htmlspecialchars($data['ram']); ?>">
                </div>

                <div class="form-group">
                    <label>Storage</label>
                    <input type="text" name="storage" value="<?php echo htmlspecialchars($data['storage']); ?>">
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="id_lokasi">
                        <?php while($lokasi = mysqli_fetch_assoc($data_lokasi)){ ?>
                        <option value="<?php echo (int)$lokasi['id_lokasi']; ?>"
                            <?php if($lokasi['id_lokasi'] == $data['id_lokasi']) echo "selected"; ?>>
                            <?php echo htmlspecialchars($lokasi['nama_lokasi']); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="tersedia" <?php if($data['status'] == "tersedia") echo "selected"; ?>>Tersedia</option>
                        <option value="dipinjam" <?php if($data['status'] == "dipinjam") echo "selected"; ?>>Dipinjam</option>
                        <option value="rusak" <?php if($data['status'] == "rusak") echo "selected"; ?>>Rusak</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Update</button>
                    <a href="data.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

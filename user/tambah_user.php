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
<title>Tambah Pengguna</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Tambah Pengguna</h2>
        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>
            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <?php } ?>
            <?php if($isAdmin){ ?>
                <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
                <li><a href="../laporan/laporan.php">Laporan</a></li>
                <li><a href="data_user.php">Manajemen User</a></li>
            <?php } ?>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Tambah Pengguna</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_tambah.php" autocomplete="off">

                <!-- ✅ FIX #12 CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label>Nama</label>
                    <!-- ✅ FIX #15: Validasi maxlength -->
                    <input type="text" name="nama" placeholder="Masukkan nama" required maxlength="100">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required maxlength="50">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password"
                            placeholder="Minimal 6 karakter" required minlength="6">
                        <button type="button" onclick="togglePassword()">Lihat</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="teknisi">Teknisi</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Simpan</button>
                    <a href="data_user.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
function togglePassword() {
    var password = document.getElementById("password");
    password.type = (password.type === "password") ? "text" : "password";
}
</script>

</body>
</html>

<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* Validasi ID dari URL */
if (!isset($_GET['id'])) {
    header("Location: data_user.php");
    exit;
}

/* Cast ke integer untuk mencegah SQL Injection */
$id = (int) $_GET['id'];

/* Ambil data user berdasarkan ID (Prepared Statement) */
$stmtUser = mysqli_prepare($koneksi, "
    SELECT id_pengguna, nama, username, role
    FROM pengguna
    WHERE id_pengguna = ? AND deleted_at IS NULL
    LIMIT 1
");
mysqli_stmt_bind_param($stmtUser, "i", $id);
mysqli_stmt_execute($stmtUser);
$result = mysqli_stmt_get_result($stmtUser);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmtUser);

if (!$data) {
    header("Location: data_user.php?error=data_tidak_ditemukan");
    exit;
}

/* Generate token CSRF dan simpan di session */
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
<title>Edit Pengguna</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Pengguna</h2>
        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>
            <?php if ($isAdmin || $isTeknisi) { ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <?php } ?>
            <?php if ($isAdmin) { ?>
                <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
                <li><a href="../laporan/laporan.php">Laporan</a></li>
                <li><a href="data_user.php">Manajemen User</a></li>
            <?php } ?>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Edit Pengguna</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_edit.php" autocomplete="off">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="id_pengguna" value="<?php echo (int) $data['id_pengguna']; ?>">

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required maxlength="100">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required minlength="3" maxlength="50">
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password"
                            placeholder="Kosongkan jika tidak ingin mengubah password" minlength="6">
                        <button type="button" onclick="togglePassword()">Lihat</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="admin" <?php if ($data['role'] === 'admin') echo "selected"; ?>>Admin</option>
                        <option value="teknisi" <?php if ($data['role'] === 'teknisi') echo "selected"; ?>>Teknisi</option>
                        <option value="user" <?php if ($data['role'] === 'user') echo "selected"; ?>>User</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan" type="submit">Update</button>
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

<?php

include("../config/auth.php");
include("../config/koneksi.php");

if(!$isAdmin){
    header("Location: data_user.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($koneksi,"SELECT * FROM user WHERE id_user='$id'");
$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Pengguna</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Edit User</h2>

        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>

            <!-- ADMIN & TEKNISI -->
            <?php if($isAdmin || $isTeknisi){ ?>
                <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <?php } ?>

            <!-- KHUSUS ADMIN -->
            <?php if($isAdmin){ ?>
                <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
                <li><a href="../laporan/laporan.php">Laporan</a></li>
                <li><a href="data_user.php">Manajemen User</a></li>
            <?php } ?>

            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Edit User</h1>
        </div>

        <div class="form-container">

            <form method="POST" action="pr_edit.php">

                <input type="hidden" name="id" value="<?php echo $data['id_user']; ?>">

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $data['username']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>

                    <div style="display:flex; gap:10px;">
                        <input type="password" id="password" name="password">
                        <button type="button" onclick="togglePassword()">Lihat</button>
                    </div>

                    <small>Kosongkan jika tidak ingin mengubah password</small>
                </div>

                <div class="form-group">
                    <label>Role</label>

                    <select name="role">
                        <option value="admin" <?php if($data['role']=="admin") echo "selected"; ?>>Admin</option>
                        <option value="teknisi" <?php if($data['role']=="teknisi") echo "selected"; ?>>Teknisi</option>
                        <option value="user" <?php if($data['role']=="user") echo "selected"; ?>>User</option>
                    </select>

                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-simpan">Update</button>
                    <a href="data_user.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
function togglePassword() {
    var input = document.getElementById("password");
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>

</body>
</html>
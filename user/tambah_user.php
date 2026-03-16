<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah User</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">

        <h2>Inventaris</h2>

        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../inventaris/data.php">Data Inventaris</a></li>
            <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
            <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="data_user.php">Manajemen User</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Tambah User</h1>
        </div>

        <div class="form-container">

            <form>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" placeholder="Masukkan nama">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" placeholder="Masukkan username">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" placeholder="Masukkan password">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select>
                    <option>Admin</option>
                    <option>Teknisi</option>
                    <option>User</option>
                    </select>
                </div>

                <div class="form-buttons">

                    <button class="btn-simpan">Simpan</button>
                    <a href="data_user.php" class="btn-batal">Batal</a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
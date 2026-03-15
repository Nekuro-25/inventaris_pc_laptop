<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Perbaikan</title>

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
            <li><a href="data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="#">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Tambah Data Perbaikan</h1>
        </div>

        <div class="form-container">

            <form>

                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" placeholder="Masukkan kode barang">
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" placeholder="Masukkan nama barang">
                </div>

                <div class="form-group">
                    <label>Tanggal Perbaikan</label>
                    <input type="date">
                </div>

                <div class="form-group">
                    <label>Kerusakan</label>
                    <input type="text" placeholder="Masukkan kerusakan">
                </div>

                <div class="form-group">
                    <label>Tindakan</label>
                    <input type="text" placeholder="Masukkan tindakan perbaikan">
                </div>

                <div class="form-buttons">

                    <button class="btn-simpan">Simpan</button>
                    <a href="data_perbaikan.php" class="btn-batal">Batal</a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
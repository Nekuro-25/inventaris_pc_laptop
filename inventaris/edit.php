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

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Inventaris</h2>
        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="data.php">Data Inventaris</a></li>
            <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
            <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Edit Inventaris</h1>
        </div>

        <div class="form-container">

            <form>

                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" value="PC01">
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" value="PC Lab 1">
                </div>

                <div class="form-group">
                    <label>Jenis Barang</label>
                    <select>
                    <option>PC</option>
                    <option>Laptop</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" value="Dell">
                </div>

                <div class="form-group">
                    <label>Processor</label>
                    <input type="text" value="Intel i5">
                </div>

                <div class="form-group">
                    <label>RAM</label>
                    <input type="text" value="8GB">
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <select>
                    <option>Lab Komputer</option>
                    <option>Ruang TU</option>
                    <option>Ruang Dosen</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select>
                    <option>Aktif</option>
                    <option>Rusak</option>
                    <option>Maintenance</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button class="btn-simpan">Update</button>
                    <a href="data.php" class="btn-batal">Batal</a>
                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
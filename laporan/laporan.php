<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Inventaris</title>

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
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Laporan Inventaris</h1>
        </div>

        <div class="table-container">

            <h3>Filter Laporan</h3>

            <div class="filter-form">

                <div class="filter-group">
                    <label>Tanggal Awal</label>
                    <input type="date">
                </div>

                <div class="filter-group">
                    <label>Tanggal Akhir</label>
                    <input type="date">
                </div>

                <div class="filter-group">
                    <label>Jenis Barang</label>
                    <select>
                    <option>Semua</option>
                    <option>PC</option>
                    <option>Laptop</option>
                    </select>
                </div>

                <button class="btn-filter">Tampilkan</button>
                <button class="btn-cetak">Cetak</button>

            </div>

            <hr style="margin:20px 0">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Merk</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>


                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
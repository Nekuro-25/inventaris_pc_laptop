<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen User</title>

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
            <li><a href="../laporan/laporan,php">Laporan</a></li>
            <li><a href="data_user.php">Manajemen User</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Manajemen User</h1>
        </div>

        <div class="table-container">

            <a href="tambah_user.php" class="btn-tambah">+ Tambah User</a>

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>1</td>
                        <td>Administrator</td>
                        <td>admin</td>
                        <td>Admin</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-hapus">Hapus</button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Budi</td>
                        <td>budi01</td>
                        <td>User</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-hapus">Hapus</button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Andi</td>
                        <td>teknisi01</td>
                        <td>Teknisi</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-hapus">Hapus</button>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
<?php

include("../config/auth.php");
include("../config/koneksi.php");
onlyAdmin();

/* pastikan variabel role aman */
$isAdmin = isset($isAdmin) ? $isAdmin : false;
$isTeknisi = isset($isTeknisi) ? $isTeknisi : false;

/* ambil data user (soft delete aktif) */
$query = mysqli_query($koneksi,"
    SELECT * FROM pengguna 
    WHERE deleted_at IS NULL
    ORDER BY id_pengguna DESC
");

/* cek error query */
if(!$query){
    die("Query error: " . mysqli_error($koneksi));
}
?>

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
        <h2>Pengguna</h2>

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

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Manajemen User</h1>
        </div>

        <div class="table-container">

        <?php if($isAdmin){ ?>
            <a href="tambah_user.php" class="btn-tambah">+ Tambah User</a>
        <?php } ?>
        
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

                    <?php
                    $no = 1;

                    if(mysqli_num_rows($query) > 0){
                        while($row = mysqli_fetch_assoc($query)){
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>    
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td>
                            <?php if($isAdmin){ ?>
                                <a href="edit.php?id=<?php echo urlencode($row['id_pengguna']); ?>" class="btn-edit">Edit</a>
                                <a href="hapus_user.php?id=<?php echo urlencode($row['id_pengguna']); ?>" 
                                    class="btn-hapus"
                                    onclick="return konfirmasiHapus('Yakin ingin menghapus data ini?')">
                                    Hapus
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                    
                    <?php 
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">Data tidak ditemukan</td>
                        </tr>
                    <?php } ?>
            
                </tbody>

            </table>

        </div>

    </div>

</div>

<script src="../js/konfirmasi.js"></script>

</body>
</html>
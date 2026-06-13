<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

// FIX: tambah soft delete
$query = mysqli_query($koneksi,"
SELECT inventaris.*, lokasi.nama_lokasi
FROM inventaris
JOIN lokasi ON inventaris.id_lokasi = lokasi.id_lokasi
WHERE inventaris.deleted_at IS NULL
AND lokasi.deleted_at IS NULL
");

?>

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

            <li><a href="../inventaris/data.php">
                Data Inventaris
            </a></li>

            <?php if($isAdmin || $isTeknisi){ ?>

                <li><a href="../peminjaman/index.php">
                    Peminjaman
                </a></li>

                <li><a href="../perbaikan/data_perbaikan.php">
                    Perbaikan
                </a></li>

            <?php } ?>

            <?php if($isAdmin){ ?>

                <li><a href="../lokasi/lokasi.php">
                    Data Lokasi
                </a></li>

                <li><a href="laporan.php">
                    Laporan
                </a></li>

                <li><a href="../user/data_user.php">
                    Manajemen User
                </a></li>

            <?php } ?>

            <li><a href="../logout.php">Logout</a></li>

        </ul>

    </div>

    <!-- Main -->
    <div class="main">

        <div class="topbar">
            <h1>Laporan Inventaris</h1>
        </div>

        <div class="table-container">

            <div class="table-header">
                <span class="table-header-title">
                    Filter Laporan
                </span>
            </div>

            <div style="padding:20px 24px;">

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

                    <button
                        type="button"
                        class="btn-filter">
                        Tampilkan
                    </button>

                    <a
                        href="cetak_pdf.php"
                        target="_blank"
                        class="btn-cetak">
                        Cetak PDF
                    </a>

                </div>

            </div>

            <div class="table-wrapper">

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

                    <?php
                    $no = 1;

                    while($row = mysqli_fetch_assoc($query)){
                    ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                <?= htmlspecialchars($row['kode_barang']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_barang']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['jenis']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['merk']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_lokasi']) ?>
                            </td>

                            <td>

                                <?php

                                $st = $row['status'];

                                $label = [
                                    'tersedia' => 'Tersedia',
                                    'dipinjam' => 'Dipinjam',
                                    'rusak' => 'Rusak'
                                ];

                                echo '<span class="badge badge-'.$st.'">' .
                                     htmlspecialchars($label[$st] ?? $st) .
                                     '</span>';

                                ?>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
<?php

include("../config/auth.php");
include("../config/koneksi.php");
blockUser();

/* Generate token CSRF untuk form hapus di halaman ini */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

/* Ambil data lokasi aktif */
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM lokasi
     WHERE deleted_at IS NULL
     ORDER BY nama_lokasi ASC"
);

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Lokasi</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h2>Data Lokasi</h2>

        <ul>

            <li>
                <a href="../dashboard/index.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="../inventaris/data.php">
                    Data Inventaris
                </a>
            </li>

            <?php if($isAdmin || $isTeknisi){ ?>

                <li>
                    <a href="../peminjaman/index.php">
                        Peminjaman
                    </a>
                </li>

                <li>
                    <a href="../perbaikan/data_perbaikan.php">
                        Perbaikan
                    </a>
                </li>

            <?php } ?>

            <?php if($isAdmin){ ?>

                <li>
                    <a href="lokasi.php">
                        Data Lokasi
                    </a>
                </li>

                <li>
                    <a href="../laporan/laporan.php">
                        Laporan
                    </a>
                </li>

                <li>
                    <a href="../user/data_user.php">
                        Manajemen User
                    </a>
                </li>

            <?php } ?>

            <li>
                <a href="../logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <h1>Data Lokasi</h1>
            <p>Kelola seluruh lokasi inventaris sekolah.</p>
        </div>

        <?php if(isset($_GET['pesan'])): ?>

            <div class="alert alert-success">

                <?php

                if($_GET['pesan'] === 'berhasil'){
                    echo "Lokasi berhasil disimpan.";
                }
                elseif($_GET['pesan'] === 'update_berhasil'){
                    echo "Lokasi berhasil diperbarui.";
                }
                elseif($_GET['pesan'] === 'hapus_berhasil'){
                    echo "Lokasi berhasil dihapus.";
                }
                else{
                    echo htmlspecialchars($_GET['pesan']);
                }

                ?>

            </div>

        <?php endif; ?>


        <?php if(isset($_GET['error'])): ?>

            <div class="alert alert-danger">
                Terjadi kesalahan. Silakan coba lagi.
            </div>

        <?php endif; ?>


        <div class="table-container">

            <div class="table-header">

                <span class="table-header-title">
                    Daftar Lokasi
                </span>

                <?php if($isAdmin){ ?>
                    <a href="tambah_lokasi.php" class="btn-tambah">
                        + Tambah Lokasi
                    </a>
                <?php } ?>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $no = 1;

                    while($row = mysqli_fetch_assoc($query)){

                    ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_lokasi']); ?>
                            </td>

                            <td>

                                <?php if($isAdmin){ ?>

                                    <a
                                        href="edit_lokasi.php?id_lokasi=<?= (int)$row['id_lokasi']; ?>"
                                        class="btn-edit">
                                        Edit
                                    </a>

                                    <form method="POST" action="hapus_lokasi.php" style="display:inline">
                                        <input type="hidden" name="id_lokasi" value="<?= (int)$row['id_lokasi']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token); ?>">
                                        <button type="submit" class="btn-hapus" onclick="return konfirmasiHapus()">
                                            Hapus
                                        </button>
                                    </form>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                    <?php if(mysqli_num_rows($query) === 0){ ?>

                        <tr>

                            <td colspan="3">
                                Belum ada data lokasi.
                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script src="../js/konfirmasi.js"></script>

</body>
</html>

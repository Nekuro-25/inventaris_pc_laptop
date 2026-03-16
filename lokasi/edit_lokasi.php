<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id_lokasi'];

$query = mysqli_query($koneksi,"SELECT * FROM lokasi WHERE id_lokasi='$id'");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $nama_lokasi = $_POST['nama_lokasi'];

    $update = mysqli_query($koneksi,"UPDATE lokasi SET 
        nama_lokasi='$nama_lokasi'
        WHERE id_lokasi='$id'
    ");

    if($update){
        header("Location: lokasi.php");
        exit;
    }else{
        echo "Data gagal diupdate";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Lokasi</title>

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
            <li><a href="lokasi.php">Data Lokasi</a></li>
            <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Edit Lokasi</h1>
        </div>

        <div class="form-container">

            <form method="POST">

                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" 
                    value="<?php echo $data['nama_lokasi']; ?>" required>
                </div>

                <div class="form-buttons">

                    <button type="submit" name="update" class="btn-simpan">Update</button>

                    <a href="lokasi.php" class="btn-batal">Batal</a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>